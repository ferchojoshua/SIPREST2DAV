<?php

class RutasControlador
{
    /**
     * Listar todas las rutas por sucursal (o todas si es administrador)
     */
    static public function ctrListarRutas($sucursal_id)
    {
        try {
            // Para administradores, permitir sucursal_id null o 0
            $es_administrador = false;
            if (isset($_SESSION['usuario'])) {
                $es_administrador = (
                    isset($_SESSION['usuario']->id_perfil_usuario) && $_SESSION['usuario']->id_perfil_usuario == 1
                );
            }
            
            // Validar sucursal_id solo si NO es administrador
            if (!$es_administrador && (!is_numeric($sucursal_id) || $sucursal_id <= 0)) {
                error_log("ID de sucursal inválido para usuario no administrador: " . $sucursal_id);
                return [
                    'estado' => 'error',
                    'mensaje' => 'ID de sucursal inválido'
                ];
        }

            $rutas = RutasModelo::mdlListarRutas($sucursal_id);
            
            // El modelo ahora devuelve un array vacío en caso de error
            if (!is_array($rutas)) {
                error_log("Error al obtener rutas de la sucursal: " . $sucursal_id);
                return [
                    'estado' => 'error',
                    'mensaje' => 'Error al obtener las rutas'
                ];
            }

            return [
                'estado' => 'ok',
                'data' => $rutas
            ];
        } catch (Exception $e) {
            error_log("Error en ctrListarRutas: " . $e->getMessage());
            return [
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor'
            ];
        }
    }

    /**
     * Obtener ruta por ID
     */
    static public function ctrObtenerRuta($ruta_id)
    {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
            return null;
        }

        return RutasModelo::mdlObtenerRuta($ruta_id);
    }

    /**
     * Guardar o actualizar ruta con validación completa
     */
    static public function ctrGuardarRuta($datos)
    {
        // Validar y sanitizar datos de entrada
        $validation = self::validarDatosRuta($datos);
        if (!$validation['valid']) {
            return $validation;
        }

        // Datos sanitizados
        $datosSanitizados = $validation['data'];

        // Verificar duplicados antes de guardar
        $duplicado = RutasModelo::mdlVerificarDuplicadoRuta(
            $datosSanitizados['ruta_codigo'],
            $datosSanitizados['sucursal_id'],
            $datosSanitizados['ruta_id']
        );

        if ($duplicado) {
            return [
                'valid' => false,
                'estado' => 'error',
                'mensaje' => 'El código de ruta ya existe en esta sucursal'
            ];
        }

        // Intentar guardar
        $resultado = RutasModelo::mdlGuardarRuta($datosSanitizados);

        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoriaRuta($datosSanitizados, empty($datosSanitizados['ruta_id']) ? 'CREATE' : 'UPDATE');
            return "ok";
        } else {
            return [
                'valid' => false,
                'estado' => 'error',
                'mensaje' => 'Error en la base de datos: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Eliminar ruta
     */
    static public function ctrEliminarRuta($ruta_id)
    {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
            return [
                'estado' => 'error',
                'mensaje' => 'ID de ruta inválido'
            ];
        }

        $resultado = RutasModelo::mdlEliminarRuta($ruta_id);

        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoriaRuta(['ruta_id' => $ruta_id], 'DELETE');
            return "ok";
        } elseif ($resultado === "en_uso") {
            return [
                'estado' => 'error',
                'mensaje' => 'La ruta no se puede eliminar porque tiene clientes asignados'
            ];
        } elseif ($resultado === "usuarios_asignados") {
            return [
                'estado' => 'error',
                'mensaje' => 'La ruta no se puede eliminar porque tiene usuarios asignados'
            ];
        } else {
            return [
                'estado' => 'error',
                'mensaje' => 'Error al eliminar la ruta: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Listar rutas activas para select
     */
    static public function ctrListarRutasActivas($sucursal_id)
    {
        // Validar sucursal_id
        if (!is_numeric($sucursal_id) || $sucursal_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarRutasActivas($sucursal_id);
    }

    /**
     * Listar rutas activas con información completa para combos mejorados
     */
    static public function ctrListarRutasActivasCompletas($sucursal_id)
    {
        // Validar sucursal_id
        if (!is_numeric($sucursal_id) || $sucursal_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarRutasActivasCompletas($sucursal_id);
    }

    /**
     * Listar clientes de una ruta específica
     */
    static public function ctrListarClientesRuta($ruta_id)
    {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarClientesRuta($ruta_id);
    }

    /**
     * Asignar cliente a ruta
     */
    static public function ctrAsignarClienteRuta($datos)
    {
        // Validar datos de entrada
        $validation = self::validarAsignacionCliente($datos);
        if (!$validation['valid']) {
            return $validation;
        }

        // Datos sanitizados
        $datosSanitizados = $validation['data'];

        // Obtener próximo orden si no se especifica
        if (empty($datosSanitizados['orden_visita'])) {
            $datosSanitizados['orden_visita'] = RutasModelo::mdlObtenerProximoOrden($datosSanitizados['ruta_id']);
        }

        $resultado = RutasModelo::mdlAsignarClienteRuta($datosSanitizados);

        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoriaRuta($datosSanitizados, 'ASSIGN_CLIENT');
            return "ok";
        } elseif ($resultado === "ya_asignado") {
            return [
                'estado' => 'error',
                'mensaje' => 'El cliente ya está asignado a esta ruta'
            ];
        } else {
            return [
                'estado' => 'error',
                'mensaje' => 'Error al asignar cliente: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Remover cliente de ruta
     */
    static public function ctrRemoverClienteRuta($cliente_ruta_id)
    {
        // Validar cliente_ruta_id
        if (!is_numeric($cliente_ruta_id) || $cliente_ruta_id <= 0) {
            return [
                'estado' => 'error',
                'mensaje' => 'ID de asignación inválido'
            ];
        }

        $resultado = RutasModelo::mdlRemoverClienteRuta($cliente_ruta_id);

        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoriaRuta(['cliente_ruta_id' => $cliente_ruta_id], 'REMOVE_CLIENT');
            return "ok";
        } else {
            return [
                'estado' => 'error',
                'mensaje' => 'Error al remover cliente: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Actualizar orden de visita
     */
    static public function ctrActualizarOrdenVisita($cliente_ruta_id, $nuevo_orden)
    {
        // Validar parámetros
        if (!is_numeric($cliente_ruta_id) || $cliente_ruta_id <= 0) {
            return [
                'estado' => 'error',
                'mensaje' => 'ID de asignación inválido'
            ];
        }

        if (!is_numeric($nuevo_orden) || $nuevo_orden < 0) {
            return [
                'estado' => 'error',
                'mensaje' => 'Orden de visita inválido'
            ];
        }

        $resultado = RutasModelo::mdlActualizarOrdenVisita($cliente_ruta_id, $nuevo_orden);

        if ($resultado === "ok") {
            return "ok";
        } else {
            return [
                'estado' => 'error',
                'mensaje' => 'Error al actualizar orden: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Listar usuarios disponibles para asignar a rutas
     */
    static public function ctrListarUsuariosDisponibles($sucursal_id)
    {
        // Validar sucursal_id
        if (!is_numeric($sucursal_id) || $sucursal_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarUsuariosDisponibles($sucursal_id);
    }

    /**
     * Listar todos los usuarios con acceso (para administradores)
     */
    static public function ctrListarTodosUsuariosConAcceso()
    {
        return RutasModelo::mdlListarTodosUsuariosConAcceso();
    }

    /**
     * Listar usuarios asignados a una ruta específica
     */
    static public function ctrListarUsuariosRuta($ruta_id)
    {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarUsuariosRuta($ruta_id);
    }

    /**
     * Listar usuarios asignados a una ruta
     */
    static public function ctrListarUsuariosAsignados($ruta_id)
    {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarUsuariosRuta($ruta_id);
    }

    /**
     * Listar usuarios asignados a una ruta con información completa para combos mejorados
     */
    static public function ctrListarUsuariosRutaCompletos($ruta_id)
    {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarUsuariosAsignadosCompletos($ruta_id);
    }

    /**
     * Remover usuario de ruta
     */
    static public function ctrRemoverUsuarioRuta($usuario_ruta_id)
    {
        // Validar usuario_ruta_id
        if (!is_numeric($usuario_ruta_id) || $usuario_ruta_id <= 0) {
            return [
                'estado' => 'error',
                'mensaje' => 'ID de asignación inválido'
            ];
        }

        $resultado = RutasModelo::mdlRemoverUsuarioRuta($usuario_ruta_id);

        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoriaRuta(['usuario_ruta_id' => $usuario_ruta_id], 'REMOVE_USER');
            return "ok";
        } else {
            return [
                'estado' => 'error',
                'mensaje' => 'Error al remover usuario: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Asignar usuario a ruta
     */
    static public function ctrAsignarUsuarioRuta($datos)
    {
        // Validar datos de entrada
        $validation = self::validarAsignacionUsuario($datos);
        if (!$validation['valid']) {
            return $validation;
        }

        // Datos sanitizados
        $datosSanitizados = $validation['data'];

        $resultado = RutasModelo::mdlAsignarUsuarioRuta($datosSanitizados);

        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoriaRuta($datosSanitizados, 'ASSIGN_USER');
            return "ok";
        } elseif ($resultado === "ya_asignado") {
            return [
                'estado' => 'error',
                'mensaje' => 'El usuario ya está asignado a esta ruta'
            ];
        } else {
            return [
                'estado' => 'error',
                'mensaje' => 'Error al asignar usuario: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Obtener estadísticas de una ruta
     */
    static public function ctrObtenerEstadisticasRuta($ruta_id)
    {
        try {
        // Validar ruta_id
        if (!is_numeric($ruta_id) || $ruta_id <= 0) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'ID de ruta inválido'
                ];
        }

            $estadisticas = RutasModelo::mdlObtenerEstadisticasRuta($ruta_id);
            
            if ($estadisticas === null) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'Ruta no encontrada'
                ];
            }
            
            return [
                'estado' => 'ok',
                'data' => $estadisticas
            ];
        } catch (Exception $e) {
            error_log("Error en ctrObtenerEstadisticasRuta: " . $e->getMessage());
            return [
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor'
            ];
        }
    }

    /**
     * Listar clientes sin asignar a rutas
     */
    static public function ctrListarClientesSinRuta($sucursal_id)
    {
        // Validar sucursal_id
        if (!is_numeric($sucursal_id) || $sucursal_id <= 0) {
            return [];
        }

        return RutasModelo::mdlListarClientesSinRuta($sucursal_id);
    }

    /**
     * Validar y sanitizar datos de ruta
     */
    private static function validarDatosRuta($datos)
    {
        $errors = [];
        $sanitizedData = [];

        // Validar y sanitizar ruta_id
        $sanitizedData['ruta_id'] = !empty($datos['ruta_id']) ? intval($datos['ruta_id']) : null;

        // Validar y sanitizar sucursal_id
        if (empty($datos['sucursal_id'])) {
            $errors[] = 'La sucursal es requerida';
        } else {
            $sanitizedData['sucursal_id'] = intval($datos['sucursal_id']);
        }

        // Validar y sanitizar nombre
        if (empty($datos['ruta_nombre'])) {
            $errors[] = 'El nombre de la ruta es requerido';
        } else {
            $nombre = trim($datos['ruta_nombre']);
            if (strlen($nombre) < 3) {
                $errors[] = 'El nombre debe tener al menos 3 caracteres';
            } elseif (strlen($nombre) > 100) {
                $errors[] = 'El nombre no puede exceder 100 caracteres';
            } else {
                $sanitizedData['ruta_nombre'] = $nombre;
            }
        }

        // Validar y sanitizar código
        if (empty($datos['ruta_codigo'])) {
            $errors[] = 'El código de la ruta es requerido';
        } else {
            $codigo = trim(strtoupper($datos['ruta_codigo']));
            if (strlen($codigo) < 2) {
                $errors[] = 'El código debe tener al menos 2 caracteres';
            } elseif (strlen($codigo) > 20) {
                $errors[] = 'El código no puede exceder 20 caracteres';
            } elseif (!preg_match('/^[A-Z0-9\-_]+$/', $codigo)) {
                $errors[] = 'El código solo puede contener letras, números, guiones y guiones bajos';
            } else {
                $sanitizedData['ruta_codigo'] = $codigo;
            }
        }

        // Validar y sanitizar descripción
        $sanitizedData['ruta_descripcion'] = !empty($datos['ruta_descripcion']) ? trim($datos['ruta_descripcion']) : '';

        // Validar y sanitizar color
        if (!empty($datos['ruta_color'])) {
            $color = trim($datos['ruta_color']);
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
                $errors[] = 'El color debe ser un valor hexadecimal válido';
            } else {
                $sanitizedData['ruta_color'] = $color;
            }
        } else {
            $sanitizedData['ruta_color'] = '#3498db';
        }

        // Validar estado
        if (empty($datos['ruta_estado']) || !in_array($datos['ruta_estado'], ['activa', 'inactiva'])) {
            $errors[] = 'El estado debe ser "activa" o "inactiva"';
        } else {
            $sanitizedData['ruta_estado'] = $datos['ruta_estado'];
        }

        // Validar y sanitizar orden
        $sanitizedData['ruta_orden'] = !empty($datos['ruta_orden']) ? intval($datos['ruta_orden']) : 0;

        // Validar y sanitizar observaciones
        $sanitizedData['ruta_observaciones'] = !empty($datos['ruta_observaciones']) ? trim($datos['ruta_observaciones']) : '';

        // Validar usuario
        if (empty($datos['usuario_creacion']) && empty($datos['usuario_modificacion'])) {
            $errors[] = 'Usuario requerido';
        } else {
            if (!empty($datos['usuario_creacion'])) {
                $sanitizedData['usuario_creacion'] = intval($datos['usuario_creacion']);
            }
            if (!empty($datos['usuario_modificacion'])) {
                $sanitizedData['usuario_modificacion'] = intval($datos['usuario_modificacion']);
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $sanitizedData,
            'estado' => empty($errors) ? 'ok' : 'error',
            'mensaje' => empty($errors) ? '' : implode(', ', $errors)
        ];
    }

    /**
     * Validar datos de asignación de cliente
     */
    private static function validarAsignacionCliente($datos)
    {
        $errors = [];
        $sanitizedData = [];

        // Validar cliente_id
        if (empty($datos['cliente_id']) || !is_numeric($datos['cliente_id'])) {
            $errors[] = 'Cliente requerido';
        } else {
            $sanitizedData['cliente_id'] = intval($datos['cliente_id']);
        }

        // Validar ruta_id
        if (empty($datos['ruta_id']) || !is_numeric($datos['ruta_id'])) {
            $errors[] = 'Ruta requerida';
        } else {
            $sanitizedData['ruta_id'] = intval($datos['ruta_id']);
        }

        // Validar orden de visita
        $sanitizedData['orden_visita'] = !empty($datos['orden_visita']) ? intval($datos['orden_visita']) : 0;

        // Sanitizar dirección específica
        $sanitizedData['direccion_especifica'] = !empty($datos['direccion_especifica']) ? trim($datos['direccion_especifica']) : '';

        // Sanitizar observaciones
        $sanitizedData['observaciones'] = !empty($datos['observaciones']) ? trim($datos['observaciones']) : '';

        // Validar usuario de asignación
        if (empty($datos['usuario_asignacion']) || !is_numeric($datos['usuario_asignacion'])) {
            $errors[] = 'Usuario de asignación requerido';
        } else {
            $sanitizedData['usuario_asignacion'] = intval($datos['usuario_asignacion']);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $sanitizedData,
            'estado' => empty($errors) ? 'ok' : 'error',
            'mensaje' => empty($errors) ? '' : implode(', ', $errors)
        ];
    }

    /**
     * Validar datos de asignación de usuario
     */
    private static function validarAsignacionUsuario($datos)
    {
        $errors = [];
        $sanitizedData = [];

        // Validar usuario_id
        if (empty($datos['usuario_id']) || !is_numeric($datos['usuario_id'])) {
            $errors[] = 'Usuario requerido';
        } else {
            $sanitizedData['usuario_id'] = intval($datos['usuario_id']);
        }

        // Validar ruta_id
        if (empty($datos['ruta_id']) || !is_numeric($datos['ruta_id'])) {
            $errors[] = 'Ruta requerida';
        } else {
            $sanitizedData['ruta_id'] = intval($datos['ruta_id']);
        }

        // Validar tipo de asignación
        if (empty($datos['tipo_asignacion']) || !in_array($datos['tipo_asignacion'], ['responsable', 'apoyo'])) {
            $errors[] = 'Tipo de asignación debe ser "responsable" o "apoyo"';
        } else {
            $sanitizedData['tipo_asignacion'] = $datos['tipo_asignacion'];
        }

        // Validar fechas
        $sanitizedData['fecha_inicio'] = !empty($datos['fecha_inicio']) ? $datos['fecha_inicio'] : date('Y-m-d');
        $sanitizedData['fecha_fin'] = !empty($datos['fecha_fin']) ? $datos['fecha_fin'] : null;

        // Sanitizar observaciones
        $sanitizedData['observaciones'] = !empty($datos['observaciones']) ? trim($datos['observaciones']) : '';

        // Validar usuario de asignación
        if (empty($datos['usuario_asignacion']) || !is_numeric($datos['usuario_asignacion'])) {
            $errors[] = 'Usuario de asignación requerido';
        } else {
            $sanitizedData['usuario_asignacion'] = intval($datos['usuario_asignacion']);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $sanitizedData,
            'estado' => empty($errors) ? 'ok' : 'error',
            'mensaje' => empty($errors) ? '' : implode(', ', $errors)
        ];
    }

    /**
     * Registrar auditoría de operaciones
     */
    private static function logAuditoriaRuta($datos, $operacion)
    {
        try {
            // Obtener usuario de sesión
            $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1;

            $log = [
                'tabla' => 'rutas',
                'operacion' => $operacion,
                'registro_id' => $datos['ruta_id'] ?? null,
                'datos' => json_encode($datos),
                'usuario_id' => $usuario_id,
                'fecha' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];

            error_log("RUTA_AUDIT: " . json_encode($log));
        } catch (Exception $e) {
            error_log("Error en log de auditoría de rutas: " . $e->getMessage());
        }
    }

    /**
     * Listar rutas activas por sucursal para combos
     */
    static public function ctrListarRutasPorSucursal($id_sucursal)
    {
        $rutas = RutasModelo::mdlListarRutasPorSucursal($id_sucursal);
        return $rutas;
    }

    /**
     * Listar todas las sucursales para combos simples
     */
    static public function ctrListarSucursales()
    {
        try {
            $sucursales = RutasModelo::mdlListarSucursales();
            return $sucursales;
        } catch (Exception $e) {
            error_log("Error en ctrListarSucursales: " . $e->getMessage());
            return [];
        }
    }
} 