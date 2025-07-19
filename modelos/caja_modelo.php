<?php
require_once "conexion.php";

class CajaModelo 
{

    /*===================================================================*/
    //Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    static public function mdlListarAperturaCaja()
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_CAJA()');
        // $stmt -> bindParam(":fecha_ini",$fecha_ini,PDO::PARAM_STR);
        //$stmt -> bindParam(":fecha_fin",$fecha_fin,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }

    /*===================================================================*/
    //Peticion INSERT para REGISTRAR DATOS A LA BASE
    /*===================================================================*/
    static public function mdlRegistrarCaja($caja_descripcion, $caja_monto_inicial)
    {
        $stmt = Conexion::conectar()->prepare('call SP_REGISTRAR_APERTURA_CAJA(:caja_descripcion, :caja_monto_inicial)');
        $stmt->bindParam(":caja_descripcion", $caja_descripcion, PDO::PARAM_STR);
        $stmt->bindParam(":caja_monto_inicial", $caja_monto_inicial, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetchColumn()) {
            return $row;
        }
    }


    /*===================================================================*/
    //OBTENER TODOS LOS DATOS DE INGRE, EGRE, PRES PARA EL CIERRE
    /*===================================================================*/
    static public function mdlObtenerDataCierreCaja()
    {
        $stmt = Conexion::conectar()->prepare('call SP_REPORTE_LISTAR_TOTAL_CIERRE_CAJA()');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    //CERRAR LA CAJA
    /*===================================================================*/
    static public function mdlCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso,$caja_interes)
    {
        $stmt = Conexion::conectar()->prepare('call SP_REGISTRAR_CAJA_CIERRE(:caja_monto_ingreso, :caja_prestamo, :caja__monto_egreso, :caja_monto_total ,:caja_count_prestamo, :caja_count_ingreso, :caja_count_egreso, :caja_interes)');

        $stmt->bindParam(":caja_monto_ingreso", $caja_monto_ingreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_prestamo", $caja_prestamo, PDO::PARAM_STR);
        $stmt->bindParam(":caja__monto_egreso", $caja__monto_egreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_monto_total", $caja_monto_total, PDO::PARAM_STR);
        $stmt->bindParam(":caja_count_prestamo", $caja_count_prestamo, PDO::PARAM_STR);
        $stmt->bindParam(":caja_count_ingreso", $caja_count_ingreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_count_egreso", $caja_count_egreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_interes", $caja_interes, PDO::PARAM_STR);

        $stmt->execute();
        //  if($resultado){
        // 	return 'ok';
        // }else{
        // 	return 'error';
        // }
        if ($row = $stmt->fetchColumn()) {
            return $row;
        }

        //var_dump($stmt);
    }


    /*===================================================================*/
    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    /*===================================================================*/
    static public function mdlObtenerDataEstadoCaja()
    {
        $smt = Conexion::conectar()->prepare('call SP_OBTENER_ESTADO_CAJA()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }



    /*===================================================================*/
    //OBTENER   ID DE LA CAJA
    /*===================================================================*/
    static public function mdlObtenerIDCaja()
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_ID_CAJA_PARA_PRESTAMOS()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    //VER DETALLE DL PRESTAMO EN MODAL
    /*===================================================================*/
    static public function mdlPrestamoPorCajaID($caja_id)
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_PRESTAMOS_POR_CAJA(:caja_id)');
        $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /*===================================================================*/
    //VER DETALLE DL PRESTAMO EN MODAL
    /*===================================================================*/
    static public function mdlMovimientosPorCajaID($caja_id)
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_MOVIMIENTOS_POR_CAJA(:caja_id)');
        $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*===================================================================*/
    // NUEVOS MÉTODOS PARA SISTEMA DE PERMISOS Y AUDITORÍA
    /*===================================================================*/

    /*===================================================================*/
    // VERIFICAR PERMISOS DE USUARIO PARA OPERACIONES DE CAJA
    /*===================================================================*/
    static public function mdlVerificarPermisosCaja($id_usuario, $accion, $monto = 0)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_VERIFICAR_PERMISOS_CAJA(:id_usuario, :accion, :monto)');
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":monto", $monto, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error verificando permisos de caja: " . $e->getMessage());
            return (object)['puede_ejecutar' => 0, 'es_administrador' => 0, 'limite_monto' => 0];
        }
    }

    /*===================================================================*/
    // REGISTRAR EVENTO DE AUDITORÍA
    /*===================================================================*/
    static public function mdlRegistrarAuditoriaCaja($caja_id, $id_usuario, $accion, $descripcion, $datos_anteriores = null, $datos_nuevos = null, $ip_address = null, $monto_involucrado = null, $resultado = 'EXITOSO', $observaciones = null)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_REGISTRAR_AUDITORIA_CAJA(:caja_id, :id_usuario, :accion, :descripcion, :datos_anteriores, :datos_nuevos, :ip_address, :monto_involucrado, :resultado, :observaciones)');
            
            $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":datos_anteriores", $datos_anteriores, PDO::PARAM_STR);
            $stmt->bindParam(":datos_nuevos", $datos_nuevos, PDO::PARAM_STR);
            $stmt->bindParam(":ip_address", $ip_address, PDO::PARAM_STR);
            $stmt->bindParam(":monto_involucrado", $monto_involucrado, PDO::PARAM_STR);
            $stmt->bindParam(":resultado", $resultado, PDO::PARAM_STR);
            $stmt->bindParam(":observaciones", $observaciones, PDO::PARAM_STR);
            
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Error registrando auditoría de caja: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    // GENERAR ALERTA DEL SISTEMA
    /*===================================================================*/
    static public function mdlGenerarAlertaCaja($caja_id, $tipo_alerta, $nivel_criticidad, $titulo, $mensaje, $datos_adicionales = null, $usuario_notificado = null)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_GENERAR_ALERTA_CAJA(:caja_id, :tipo_alerta, :nivel_criticidad, :titulo, :mensaje, :datos_adicionales, :usuario_notificado)');
            
            $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_INT);
            $stmt->bindParam(":tipo_alerta", $tipo_alerta, PDO::PARAM_STR);
            $stmt->bindParam(":nivel_criticidad", $nivel_criticidad, PDO::PARAM_STR);
            $stmt->bindParam(":titulo", $titulo, PDO::PARAM_STR);
            $stmt->bindParam(":mensaje", $mensaje, PDO::PARAM_STR);
            $stmt->bindParam(":datos_adicionales", $datos_adicionales, PDO::PARAM_STR);
            $stmt->bindParam(":usuario_notificado", $usuario_notificado, PDO::PARAM_INT);
            
            $stmt->execute();
            return true;
        } catch (Exception $e) {
            error_log("Error generando alerta de caja: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    // LISTAR ALERTAS PENDIENTES PARA UN USUARIO
    /*===================================================================*/
    static public function mdlListarAlertasPendientes($id_usuario = null)
    {
        try {
            $sql = "SELECT 
                        ca.alerta_id,
                        ca.caja_id,
                        c.caja_descripcion,
                        ca.tipo_alerta,
                        ca.nivel_criticidad,
                        ca.titulo,
                        ca.mensaje,
                        ca.fecha_generacion,
                        CASE 
                            WHEN ca.nivel_criticidad = 'URGENT' THEN 'danger'
                            WHEN ca.nivel_criticidad = 'CRITICAL' THEN 'warning'
                            WHEN ca.nivel_criticidad = 'WARNING' THEN 'info'
                            ELSE 'secondary'
                        END as bootstrap_class
                    FROM caja_alertas ca
                    INNER JOIN caja c ON ca.caja_id = c.caja_id
                    WHERE ca.estado = 'pendiente'";
            
            if ($id_usuario !== null) {
                $sql .= " AND (ca.usuario_notificado = :id_usuario OR ca.usuario_notificado IS NULL)";
            }
            
            $sql .= " ORDER BY 
                        FIELD(ca.nivel_criticidad, 'URGENT', 'CRITICAL', 'WARNING', 'INFO'),
                        ca.fecha_generacion DESC
                    LIMIT 10";
            
            $stmt = Conexion::conectar()->prepare($sql);
            
            if ($id_usuario !== null) {
                $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error listando alertas pendientes: " . $e->getMessage());
            return [];
        }
    }

    /*===================================================================*/
    // MARCAR ALERTA COMO LEÍDA
    /*===================================================================*/
    static public function mdlMarcarAlertaLeida($alerta_id, $id_usuario)
    {
        try {
            $stmt = Conexion::conectar()->prepare("UPDATE caja_alertas 
                                                 SET estado = 'leida', 
                                                     fecha_lectura = NOW(),
                                                     usuario_resolucion = :id_usuario
                                                 WHERE alerta_id = :alerta_id");
            
            $stmt->bindParam(":alerta_id", $alerta_id, PDO::PARAM_INT);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Error marcando alerta como leída: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    // OBTENER DASHBOARD DE CAJA EN TIEMPO REAL
    /*===================================================================*/
    static public function mdlObtenerDashboardCaja($id_usuario = null)
    {
        try {
            // Obtener estadísticas generales
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    (SELECT COUNT(*) FROM caja WHERE caja_estado = 'VIGENTE') as cajas_abiertas,
                    (SELECT COALESCE(SUM(caja_monto_inicial + COALESCE(caja_monto_ingreso, 0) - COALESCE(caja__monto_egreso, 0)), 0) 
                     FROM caja WHERE caja_estado = 'VIGENTE') as saldo_total_activo,
                    (SELECT COUNT(*) FROM caja WHERE DATE(caja_f_apertura) = CURDATE()) as aperturas_hoy,
                    (SELECT COUNT(*) FROM caja WHERE DATE(caja_f_cierre) = CURDATE()) as cierres_hoy,
                    (SELECT COUNT(*) FROM caja_alertas WHERE estado = 'pendiente' AND nivel_criticidad IN ('URGENT', 'CRITICAL')) as alertas_criticas,
                    (SELECT COUNT(*) FROM caja_auditoria WHERE DATE(fecha_registro) = CURDATE()) as operaciones_hoy
            ");
            
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_OBJ);
            
            // Obtener cajas activas con detalles
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    c.*,
                    ua.nombre_usuario as usuario_apertura_nombre,
                    TIMESTAMPDIFF(HOUR, TIMESTAMP(c.caja_f_apertura, c.caja_hora_apertura), NOW()) as horas_abierta,
                    (SELECT COUNT(*) FROM caja_alertas ca WHERE ca.caja_id = c.caja_id AND ca.estado = 'pendiente') as alertas_pendientes
                FROM caja c
                LEFT JOIN usuarios ua ON c.usuario_apertura = ua.id_usuario
                WHERE c.caja_estado = 'VIGENTE'
                ORDER BY c.caja_f_apertura DESC
            ");
            
            $stmt->execute();
            $cajas_activas = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            return (object)[
                'estadisticas' => $estadisticas,
                'cajas_activas' => $cajas_activas,
                'alertas' => self::mdlListarAlertasPendientes($id_usuario)
            ];
            
        } catch (Exception $e) {
            error_log("Error obteniendo dashboard de caja: " . $e->getMessage());
            return (object)[
                'estadisticas' => (object)['cajas_abiertas' => 0, 'saldo_total_activo' => 0],
                'cajas_activas' => [],
                'alertas' => []
            ];
        }
    }

    /*===================================================================*/
    // REGISTRAR CONTEO FÍSICO
    /*===================================================================*/
    static public function mdlRegistrarConteoFisico($caja_id, $usuario_conteo, $tipo_conteo, $saldo_sistema, $saldo_fisico, $denominaciones = null, $observaciones = null)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                INSERT INTO caja_conteos_fisicos (
                    caja_id, usuario_conteo, tipo_conteo, saldo_sistema, 
                    saldo_fisico, denominaciones, observaciones
                ) VALUES (
                    :caja_id, :usuario_conteo, :tipo_conteo, :saldo_sistema,
                    :saldo_fisico, :denominaciones, :observaciones
                )
            ");
            
            $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_INT);
            $stmt->bindParam(":usuario_conteo", $usuario_conteo, PDO::PARAM_INT);
            $stmt->bindParam(":tipo_conteo", $tipo_conteo, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_sistema", $saldo_sistema, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_fisico", $saldo_fisico, PDO::PARAM_STR);
            $stmt->bindParam(":denominaciones", $denominaciones, PDO::PARAM_STR);
            $stmt->bindParam(":observaciones", $observaciones, PDO::PARAM_STR);
            
            $stmt->execute();
            
            $conteo_id = Conexion::conectar()->lastInsertId();
            
            // Generar alerta si hay diferencia significativa (más de 100)
            $diferencia = abs($saldo_fisico - $saldo_sistema);
            if ($diferencia > 100) {
                self::mdlGenerarAlertaCaja(
                    $caja_id,
                    'DISCREPANCIA',
                    $diferencia > 1000 ? 'CRITICAL' : 'WARNING',
                    'Discrepancia en conteo físico',
                    "Se detectó una diferencia de " . number_format($diferencia, 2) . " entre el saldo del sistema y el conteo físico.",
                    json_encode([
                        'saldo_sistema' => $saldo_sistema,
                        'saldo_fisico' => $saldo_fisico,
                        'diferencia' => $diferencia,
                        'conteo_id' => $conteo_id
                    ]),
                    $usuario_conteo
                );
            }
            
            return $conteo_id;
            
        } catch (Exception $e) {
            error_log("Error registrando conteo físico: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    // MÉTODO MEJORADO PARA APERTURA CON VALIDACIONES
    /*===================================================================*/
    static public function mdlRegistrarCajaConValidaciones($caja_descripcion, $caja_monto_inicial, $id_usuario, $ip_address = null, $validacion_fisica = false, $observaciones = null)
    {
        try {
            // Verificar permisos primero
            $permisos = self::mdlVerificarPermisosCaja($id_usuario, 'ABRIR_CAJA', $caja_monto_inicial);
            
            if (!$permisos->puede_ejecutar) {
                return (object)[
                    'success' => false,
                    'message' => 'No tiene permisos para abrir caja con este monto',
                    'codigo' => 'PERMISSION_DENIED'
                ];
            }
            
            // Continuar con apertura normal
            $stmt = Conexion::conectar()->prepare('CALL SP_REGISTRAR_APERTURA_CAJA(:caja_descripcion, :caja_monto_inicial)');
            $stmt->bindParam(":caja_descripcion", $caja_descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":caja_monto_inicial", $caja_monto_inicial, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetchColumn();
            
            if ($resultado == 1) {
                // Obtener ID de la caja recién creada
                $caja_id = Conexion::conectar()->lastInsertId();
                
                // Actualizar con datos adicionales
                $stmt2 = Conexion::conectar()->prepare("
                    UPDATE caja 
                    SET usuario_apertura = :id_usuario,
                        ip_apertura = :ip_address,
                        validacion_fisica_apertura = :validacion_fisica,
                        observaciones_apertura = :observaciones
                    WHERE caja_id = :caja_id
                ");
                
                $stmt2->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                $stmt2->bindParam(":ip_address", $ip_address, PDO::PARAM_STR);
                $stmt2->bindParam(":validacion_fisica", $validacion_fisica, PDO::PARAM_INT);
                $stmt2->bindParam(":observaciones", $observaciones, PDO::PARAM_STR);
                $stmt2->bindParam(":caja_id", $caja_id, PDO::PARAM_INT);
                $stmt2->execute();
                
                // Registrar auditoría
                self::mdlRegistrarAuditoriaCaja(
                    $caja_id,
                    $id_usuario,
                    'APERTURA',
                    "Apertura de caja: $caja_descripcion con monto inicial: " . number_format($caja_monto_inicial, 2),
                    null,
                    json_encode([
                        'monto_inicial' => $caja_monto_inicial,
                        'descripcion' => $caja_descripcion,
                        'validacion_fisica' => $validacion_fisica,
                        'observaciones' => $observaciones
                    ]),
                    $ip_address,
                    $caja_monto_inicial
                );
                
                return (object)[
                    'success' => true,
                    'message' => 'Caja abierta correctamente',
                    'caja_id' => $caja_id,
                    'codigo' => 'SUCCESS'
                ];
            } else {
                return (object)[
                    'success' => false,
                    'message' => 'Ya existe una caja abierta',
                    'codigo' => 'CAJA_EXISTENTE'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Error en apertura de caja con validaciones: " . $e->getMessage());
            return (object)[
                'success' => false,
                'message' => 'Error interno del sistema',
                'codigo' => 'SYSTEM_ERROR'
            ];
        }
    }

    /*===================================================================*/
    // OBTENER ESTADÍSTICAS RÁPIDAS (APERTURAS/CIERRES HOY)
    /*===================================================================*/
    static public function mdlObtenerEstadisticasRapidas()
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_OBTENER_ESTADISTICAS_RAPIDAS()');
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error obteniendo estadísticas rápidas: " . $e->getMessage());
            return (object)['aperturas_hoy' => 0, 'cierres_hoy' => 0, 'success' => false];
        }
    }
}
