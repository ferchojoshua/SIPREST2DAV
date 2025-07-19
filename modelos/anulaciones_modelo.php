<?php

require_once "conexion.php";

class AnulacionesModelo
{
    /**
     * Verificar permisos de anulación para un usuario
     */
    static public function mdlVerificarPermisosAnulacion($usuario_id, $tipo_documento, $fecha_documento = null)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_VERIFICAR_PERMISOS_ANULACION(:usuario_id, :tipo_documento, :fecha_documento)');
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->bindParam(":tipo_documento", $tipo_documento, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_documento", $fecha_documento, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al verificar permisos de anulación: " . $e->getMessage());
            return [
                'puede_anular' => false,
                'requiere_justificacion' => true,
                'mensaje' => 'Error interno del sistema'
            ];
        }
    }

    /**
     * Registrar anulación en auditoría
     */
    static public function mdlRegistrarAnulacion($datos)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_REGISTRAR_ANULACION(:tipo_documento, :documento_id, :nro_prestamo, :usuario_id, :motivo, :datos_originales, :sucursal_id, :ip_origen)');
            
            $stmt->bindParam(":tipo_documento", $datos['tipo_documento'], PDO::PARAM_STR);
            $stmt->bindParam(":documento_id", $datos['documento_id'], PDO::PARAM_STR);
            $stmt->bindParam(":nro_prestamo", $datos['nro_prestamo'], PDO::PARAM_STR);
            $stmt->bindParam(":usuario_id", $datos['usuario_id'], PDO::PARAM_INT);
            $stmt->bindParam(":motivo", $datos['motivo'], PDO::PARAM_STR);
            $stmt->bindParam(":datos_originales", $datos['datos_originales'], PDO::PARAM_STR);
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
            $stmt->bindParam(":ip_origen", $datos['ip_origen'], PDO::PARAM_STR);
            
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al registrar anulación: " . $e->getMessage());
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }
    }

    /**
     * Anular pago con validaciones y auditoría
     */
    static public function mdlAnularPago($nro_prestamo, $nro_cuota, $usuario_id, $motivo, $sucursal_id = null)
    {
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            // 1. Obtener datos del pago antes de anular
            $stmt = $conexion->prepare("
                SELECT pdetalle_estado_cuota, pdetalle_fecha_registro, pdetalle_monto_cuota 
                FROM prestamo_detalle 
                WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :nro_cuota
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":nro_cuota", $nro_cuota, PDO::PARAM_INT);
            $stmt->execute();
            $datos_pago = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$datos_pago) {
                $conexion->rollback();
                return "error_pago_no_encontrado";
            }

            if ($datos_pago['pdetalle_estado_cuota'] !== 'pagada') {
                $conexion->rollback();
                return "error_pago_no_pagado";
            }

            // 2. Verificar permisos
            $fecha_pago = $datos_pago['pdetalle_fecha_registro'];
            $permisos = self::mdlVerificarPermisosAnulacion($usuario_id, 'pago', $fecha_pago);
            
            if (!$permisos['puede_anular']) {
                $conexion->rollback();
                return "error_sin_permisos: " . $permisos['mensaje'];
            }

            // 3. Registrar en auditoría ANTES de anular
            $datos_auditoria = [
                'tipo_documento' => 'pago',
                'documento_id' => $nro_prestamo . '-' . $nro_cuota,
                'nro_prestamo' => $nro_prestamo,
                'usuario_id' => $usuario_id,
                'motivo' => $motivo,
                'datos_originales' => json_encode($datos_pago),
                'sucursal_id' => $sucursal_id ?: ($_SESSION["usuario"]->sucursal_id ?? null),
                'ip_origen' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];

            $resultado_auditoria = self::mdlRegistrarAnulacion($datos_auditoria);
            if ($resultado_auditoria['resultado'] !== 'ok') {
                $conexion->rollback();
                return "error_auditoria";
            }

            // 4. Anular el pago
            $stmt = $conexion->prepare("
                UPDATE prestamo_detalle 
                SET pdetalle_estado_cuota = 'pendiente', 
                    pdetalle_fecha_registro = NULL,
                    pdetalle_saldo_cuota = pdetalle_monto_cuota
                WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :nro_cuota
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":nro_cuota", $nro_cuota, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                $conexion->rollback();
                return "error_base_datos";
            }

            // 5. Actualizar estado del préstamo
            $stmt = $conexion->prepare('CALL SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)');
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();

            $conexion->commit();
            return "ok";

        } catch (Exception $e) {
            if (isset($conexion) && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
            error_log("Error en mdlAnularPago: " . $e->getMessage());
            return 'Error al anular pago: ' . $e->getMessage();
        }
    }

    /**
     * Anular préstamo/contrato con validaciones y auditoría
     */
    static public function mdlAnularPrestamoConJustificacion($nro_prestamo, $usuario_id, $motivo, $sucursal_id = null)
    {
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            // 1. Obtener datos del préstamo antes de anular
            $stmt = $conexion->prepare("
                SELECT pc.*, c.cliente_nombres, c.cliente_dni
                FROM prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                WHERE pc.nro_prestamo = :nro_prestamo
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $datos_prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$datos_prestamo) {
                $conexion->rollback();
                return "error_prestamo_no_encontrado";
            }

            if ($datos_prestamo['pres_aprobacion'] === 'anulado') {
                $conexion->rollback();
                return "error_prestamo_ya_anulado";
            }

            // 2. Verificar permisos
            $fecha_prestamo = $datos_prestamo['pres_fecha_registro'];
            $permisos = self::mdlVerificarPermisosAnulacion($usuario_id, 'prestamo', $fecha_prestamo);
            
            if (!$permisos['puede_anular']) {
                $conexion->rollback();
                return "error_sin_permisos: " . $permisos['mensaje'];
            }

            // 3. Verificar si tiene pagos realizados
            $stmt = $conexion->prepare("
                SELECT COUNT(*) as pagos_realizados 
                FROM prestamo_detalle 
                WHERE nro_prestamo = :nro_prestamo AND pdetalle_estado_cuota = 'pagada'
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $pagos = $stmt->fetchColumn();

            if ($pagos > 0) {
                // Si tiene pagos, requiere justificación especial y verificación adicional
                if (strlen($motivo) < 50) {
                    $conexion->rollback();
                    return "error_justificacion_insuficiente";
                }
            }

            // 4. Registrar en auditoría ANTES de anular
            $datos_auditoria = [
                'tipo_documento' => 'prestamo',
                'documento_id' => $nro_prestamo,
                'nro_prestamo' => $nro_prestamo,
                'usuario_id' => $usuario_id,
                'motivo' => $motivo,
                'datos_originales' => json_encode($datos_prestamo),
                'sucursal_id' => $sucursal_id ?: ($_SESSION["usuario"]->sucursal_id ?? null),
                'ip_origen' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];

            $resultado_auditoria = self::mdlRegistrarAnulacion($datos_auditoria);
            if ($resultado_auditoria['resultado'] !== 'ok') {
                $conexion->rollback();
                return "error_auditoria";
            }

            // 5. Establecer variables de sesión para el trigger
            $stmt = $conexion->prepare("SET @USUARIO_ANULACION = :usuario_id");
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt = $conexion->prepare("SET @MOTIVO_ANULACION = :motivo");
            $stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);
            $stmt->execute();

            // 6. Anular el préstamo usando el procedimiento existente
            $stmt = $conexion->prepare('CALL SP_ANULAR_PRESTAMO(:nro_prestamo)');
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $resultado = $stmt->fetchColumn();

            if ($resultado !== 'ok') {
                $conexion->rollback();
                return "error_procedimiento_anulacion";
            }

            $conexion->commit();
            return "ok";

        } catch (Exception $e) {
            if (isset($conexion) && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
            error_log("Error en mdlAnularPrestamoConJustificacion: " . $e->getMessage());
            return 'Error al anular préstamo: ' . $e->getMessage();
        }
    }

    /**
     * Obtener historial de anulaciones
     */
    static public function mdlObtenerHistorialAnulaciones($filtros = [])
    {
        try {
            $sql = "SELECT * FROM v_anulaciones_auditoria_completa WHERE 1=1";
            $params = [];

            // Aplicar filtros
            if (!empty($filtros['usuario_id'])) {
                $sql .= " AND usuario_id = :usuario_id";
                $params['usuario_id'] = $filtros['usuario_id'];
            }

            if (!empty($filtros['tipo_documento'])) {
                $sql .= " AND tipo_documento = :tipo_documento";
                $params['tipo_documento'] = $filtros['tipo_documento'];
            }

            if (!empty($filtros['fecha_desde'])) {
                $sql .= " AND fecha_anulacion >= :fecha_desde";
                $params['fecha_desde'] = $filtros['fecha_desde'];
            }

            if (!empty($filtros['fecha_hasta'])) {
                $sql .= " AND fecha_anulacion <= :fecha_hasta";
                $params['fecha_hasta'] = $filtros['fecha_hasta'];
            }

            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND sucursal_id = :sucursal_id";
                $params['sucursal_id'] = $filtros['sucursal_id'];
            }

            $sql .= " ORDER BY fecha_anulacion DESC LIMIT 100";

            $stmt = Conexion::conectar()->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(":" . $key, $value);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener historial de anulaciones: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Verificar si el usuario actual es administrador
     */
    static public function mdlEsAdministrador($usuario_id = null)
    {
        try {
            if ($usuario_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->id_perfil_usuario)) {
                    return $_SESSION["usuario"]->id_perfil_usuario == 1;
                }
                return false;
            }

            $stmt = Conexion::conectar()->prepare("SELECT id_perfil_usuario FROM usuarios WHERE id_usuario = :usuario_id");
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $perfil = $stmt->fetchColumn();
            
            return $perfil == 1;
        } catch (Exception $e) {
            error_log("Error al verificar si es administrador: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener información del usuario actual
     */
    static public function mdlObtenerUsuarioActual()
    {
        try {
            // Iniciar sesión si no está iniciada
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            
            // Debug: Log información de sesión
            error_log("DEBUG: Estado de sesión: " . session_status());
            error_log("DEBUG: ID de sesión: " . session_id());
            error_log("DEBUG: Usuario en sesión: " . (isset($_SESSION["usuario"]) ? "SÍ" : "NO"));
            
            if (!isset($_SESSION["usuario"])) {
                error_log("DEBUG: No hay usuario en sesión");
                return null;
            }

            // Verificar que el objeto usuario tenga las propiedades necesarias
            if (!isset($_SESSION["usuario"]->id_usuario)) {
                error_log("DEBUG: Usuario no tiene id_usuario");
                return null;
            }

            $usuario_data = [
                'id_usuario' => $_SESSION["usuario"]->id_usuario,
                'nombre_completo' => ($_SESSION["usuario"]->nombre_usuario ?? '') . ' ' . ($_SESSION["usuario"]->apellido_usuario ?? ''),
                'perfil_id' => $_SESSION["usuario"]->id_perfil_usuario ?? 0,
                'sucursal_id' => $_SESSION["usuario"]->sucursal_id ?? null,
                'es_administrador' => ($_SESSION["usuario"]->id_perfil_usuario ?? 0) == 1
            ];
            
            error_log("DEBUG: Datos del usuario: " . json_encode($usuario_data));
            return $usuario_data;
            
        } catch (Exception $e) {
            error_log("Error al obtener usuario actual: " . $e->getMessage());
            error_log("DEBUG: Trace: " . $e->getTraceAsString());
            return null;
        }
    }
}
?> 