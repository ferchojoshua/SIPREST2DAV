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
        session_start();
        if (!isset($_SESSION['id_usuario'])) {
            return (object)[
                'caja_estado' => 'CERRADA',
                'caja_monto_inicial' => 0
            ];
        }
        $usuario_id = $_SESSION['id_usuario'];

        $stmt = Conexion::conectar()->prepare("
            SELECT c.* 
            FROM caja c
            INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
            WHERE c.caja_estado = 'VIGENTE' AND u.id_usuario = :usuario_id
            ORDER BY c.caja_fecha_apertura DESC 
            LIMIT 1
        ");
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_OBJ);

        if ($resultado) {
            return $resultado;
        } else {
            return (object)[
                'caja_estado' => 'CERRADA',
                'caja_monto_inicial' => 0.00,
                'caja_descripcion' => 'No hay caja abierta'
            ];
        }
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
            // Primero verificar si el usuario existe y obtener su perfil
            $stmt = Conexion::conectar()->prepare('
                SELECT 
                    u.id_usuario,
                    p.descripcion as perfil,
                    p.id_perfil,
                    u.sucursal_id
                FROM usuarios u 
                INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
                WHERE u.id_usuario = :id_usuario
                AND u.estado = 1
            ');
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$usuario) {
                error_log("Usuario no encontrado o inactivo: " . $id_usuario);
                return (object)[
                    'puede_ejecutar' => false,
                    'es_administrador' => false,
                    'limite_monto' => 0,
                    'mensaje' => 'Usuario no encontrado o inactivo'
                ];
            }
            
            // Si es administrador (id_perfil = 1), tiene acceso total
            if ($usuario->id_perfil == 1) {
                return (object)[
                    'puede_ejecutar' => true,
                    'es_administrador' => true,
                    'limite_monto' => 999999999.99,
                    'mensaje' => 'Acceso total al sistema - Administrador',
                    'perfil' => $usuario->perfil,
                    'sucursal_id' => $usuario->sucursal_id
                ];
            }
            
            // Para otros perfiles, verificar permisos específicos
            $stmt = Conexion::conectar()->prepare('
                SELECT 
                    CASE 
                        WHEN :accion = "ABRIR_CAJA" AND p.puede_abrir_caja = 1 THEN 1
                        WHEN :accion = "CERRAR_CAJA" AND p.puede_cerrar_caja = 1 THEN 1
                        WHEN :accion = "SUPERVISAR" AND p.puede_supervisar = 1 THEN 1
                        ELSE 0
                    END as puede_ejecutar,
                    p.limite_monto_caja
                FROM perfiles p
                WHERE p.id_perfil = :id_perfil
            ');
            
            $stmt->bindParam(":id_perfil", $usuario->id_perfil, PDO::PARAM_INT);
            $stmt->bindParam(":accion", $accion, PDO::PARAM_STR);
            $stmt->execute();
            $permisos = $stmt->fetch(PDO::FETCH_OBJ);
            
            if (!$permisos) {
                return (object)[
                    'puede_ejecutar' => false,
                    'es_administrador' => false,
                    'limite_monto' => 0,
                    'mensaje' => 'No tiene permisos asignados',
                    'perfil' => $usuario->perfil,
                    'sucursal_id' => $usuario->sucursal_id
                ];
            }
            
            $puede_ejecutar = $permisos->puede_ejecutar == 1;
            $dentro_limite = $monto <= $permisos->limite_monto_caja;
            
            return (object)[
                'puede_ejecutar' => $puede_ejecutar && $dentro_limite,
                'es_administrador' => false,
                'limite_monto' => $permisos->limite_monto_caja,
                'mensaje' => $puede_ejecutar ? 
                    ($dentro_limite ? 'Acceso permitido' : 'Monto excede límite') : 
                    'No tiene permiso para esta acción',
                'perfil' => $usuario->perfil,
                'sucursal_id' => $usuario->sucursal_id
            ];
            
        } catch (Exception $e) {
            error_log("Error verificando permisos de caja: " . $e->getMessage());
            return (object)[
                'puede_ejecutar' => false,
                'es_administrador' => false,
                'limite_monto' => 0,
                'mensaje' => 'Error interno del sistema: ' . $e->getMessage()
            ];
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

    /*===================================================================*/
    // NUEVOS MÉTODOS PARA SISTEMA DE SUCURSALES
    /*===================================================================*/
    
    /*===================================================================*/
    // LISTAR CAJAS POR SUCURSAL (ADMIN PUEDE VER TODAS)
    /*===================================================================*/
    static public function mdlListarCajasPorSucursal($sucursal_id, $es_admin = false)
    {
        if ($es_admin) {
            $stmt = Conexion::conectar()->prepare("
                SELECT c.*, u.nombre_usuario as usuario_apertura_nombre
                FROM caja c
                LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario
                ORDER BY c.caja_f_apertura DESC, c.caja_hora_apertura DESC
            ");
        } else {
            $stmt = Conexion::conectar()->prepare("
                SELECT c.*, u.nombre_usuario as usuario_apertura_nombre
                FROM caja c
                LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario
                WHERE c.sucursal_id = :sucursal_id
                ORDER BY c.caja_f_apertura DESC, c.caja_hora_apertura DESC
            ");
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /*===================================================================*/
    // REGISTRAR CAJA CON SUCURSAL
    /*===================================================================*/
    static public function mdlRegistrarCajaSucursal($caja_descripcion, $caja_monto_inicial, $usuario_id, $sucursal_id)
    {
        $stmt = Conexion::conectar()->prepare('CALL SP_REGISTRAR_APERTURA_CAJA_SUCURSAL(:caja_descripcion, :caja_monto_inicial, :usuario_id, :sucursal_id)');
        $stmt->bindParam(":caja_descripcion", $caja_descripcion, PDO::PARAM_STR);
        $stmt->bindParam(":caja_monto_inicial", $caja_monto_inicial, PDO::PARAM_STR);
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /*===================================================================*/
    // VERIFICAR SI USUARIO PUEDE ACCEDER A CAJA DE SUCURSAL
    /*===================================================================*/
    static public function mdlVerificarAccesoCajaSucursal($usuario_id, $sucursal_id)
    {
        // Verificar si tiene permisos administrativos
        $stmt = Conexion::conectar()->prepare('SELECT p.descripcion 
                                             FROM usuarios u 
                                             INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
                                             WHERE u.id_usuario = :usuario_id');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $perfil = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Lista de perfiles con acceso administrativo
        $perfiles_admin = ['administrador', 'developer senior', 'supervisor'];
        
        if ($perfil && in_array(strtolower($perfil['descripcion']), $perfiles_admin)) {
            return ['puede_acceder' => true, 'es_admin' => true, 'razon' => 'Acceso administrativo'];
        }
        
        // Verificar si pertenece a la sucursal
        $stmt = Conexion::conectar()->prepare('SELECT sucursal_id FROM usuarios WHERE id_usuario = :usuario_id');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($usuario && ($usuario['sucursal_id'] == $sucursal_id || $usuario['sucursal_id'] === null)) {
            return ['puede_acceder' => true, 'es_admin' => false, 'razon' => 'Usuario de la sucursal'];
        }
        
        return ['puede_acceder' => false, 'es_admin' => false, 'razon' => 'Sin permisos para esta sucursal'];
    }

    /*===================================================================*/
    // MÉTODOS PARA CIERRE DE DÍA
    /*===================================================================*/
    
    /*===================================================================*/
    // GENERAR CIERRE DE DÍA
    /*===================================================================*/
    static public function mdlGenerarCierreDia($sucursal_id, $fecha_cierre, $usuario_cierre, $observaciones = '')
    {
        // Se vuelve a utilizar el SP ahora que está corregido.
        $stmt = Conexion::conectar()->prepare('CALL SP_GENERAR_CIERRE_DIA(:sucursal_id, :fecha_cierre, :usuario_cierre, :observaciones)');
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        $stmt->bindParam(":fecha_cierre", $fecha_cierre, PDO::PARAM_STR);
        $stmt->bindParam(":usuario_cierre", $usuario_cierre, PDO::PARAM_INT);
        $stmt->bindParam(":observaciones", $observaciones, PDO::PARAM_STR);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /*===================================================================*/
    // LISTAR CIERRES DE DÍA POR SUCURSAL
    /*===================================================================*/
    static public function mdlListarCierresDia($sucursal_id, $es_admin = false)
    {
        $stmt = Conexion::conectar()->prepare('CALL SP_LISTAR_CIERRES_DIA(:sucursal_id, :es_admin)');
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        $stmt->bindParam(":es_admin", $es_admin, PDO::PARAM_BOOL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*===================================================================*/
    // OBTENER RESUMEN DE OPERACIONES DEL DÍA (PARA CIERRE)
    /*===================================================================*/
    static public function mdlObtenerResumenDia($sucursal_id, $fecha)
    {
        $stmt = Conexion::conectar()->prepare('
            SELECT 
                -- Préstamos del día
                COALESCE((SELECT COUNT(*) FROM prestamo_cabecera pc 
                         WHERE DATE(pc.pres_fecha_registro) = :fecha 
                         AND pc.pres_aprobacion IN ("aprobado", "finalizado")
                         AND (pc.sucursal_asignada_id = :sucursal_id OR (pc.sucursal_asignada_id IS NULL AND :sucursal_id IS NULL))), 0) as prestamos_otorgados,
                         
                COALESCE((SELECT SUM(pc.pres_monto) FROM prestamo_cabecera pc 
                         WHERE DATE(pc.pres_fecha_registro) = :fecha 
                         AND pc.pres_aprobacion IN ("aprobado", "finalizado")
                         AND (pc.sucursal_asignada_id = :sucursal_id OR (pc.sucursal_asignada_id IS NULL AND :sucursal_id IS NULL))), 0) as monto_prestamos,
                
                -- Pagos del día
                COALESCE((SELECT COUNT(*) FROM prestamo_detalle pd
                         INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                         WHERE DATE(pd.pdetalle_fecha_registro) = :fecha
                         AND pd.pdetalle_estado_cuota = "pagada"
                         AND (pc.sucursal_asignada_id = :sucursal_id OR (pc.sucursal_asignada_id IS NULL AND :sucursal_id IS NULL))), 0) as pagos_recibidos,
                         
                COALESCE((SELECT SUM(pd.pdetalle_monto_cuota) FROM prestamo_detalle pd
                         INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                         WHERE DATE(pd.pdetalle_fecha_registro) = :fecha
                         AND pd.pdetalle_estado_cuota = "pagada"
                         AND (pc.sucursal_asignada_id = :sucursal_id OR (pc.sucursal_asignada_id IS NULL AND :sucursal_id IS NULL))), 0) as monto_pagos,
                
                -- Movimientos del día (corregido con JOIN a caja)
                COALESCE((SELECT COUNT(*) FROM movimientos m 
                         INNER JOIN caja c ON m.movi_caja = c.caja_id
                         WHERE DATE(m.movi_fecha) = :fecha 
                         AND m.movi_tipo = "INGRESO"
                         AND (c.sucursal_id = :sucursal_id OR (c.sucursal_id IS NULL AND :sucursal_id IS NULL))), 0) as ingresos_count,
                         
                COALESCE((SELECT SUM(m.movi_monto) FROM movimientos m 
                         INNER JOIN caja c ON m.movi_caja = c.caja_id
                         WHERE DATE(m.movi_fecha) = :fecha 
                         AND m.movi_tipo = "INGRESO"
                         AND (c.sucursal_id = :sucursal_id OR (c.sucursal_id IS NULL AND :sucursal_id IS NULL))), 0) as ingresos_monto,
                         
                COALESCE((SELECT COUNT(*) FROM movimientos m 
                         INNER JOIN caja c ON m.movi_caja = c.caja_id
                         WHERE DATE(m.movi_fecha) = :fecha 
                         AND m.movi_tipo = "EGRESO"
                         AND (c.sucursal_id = :sucursal_id OR (c.sucursal_id IS NULL AND :sucursal_id IS NULL))), 0) as egresos_count,
                         
                COALESCE((SELECT SUM(m.movi_monto) FROM movimientos m 
                         INNER JOIN caja c ON m.movi_caja = c.caja_id
                         WHERE DATE(m.movi_fecha) = :fecha 
                         AND m.movi_tipo = "EGRESO"
                         AND (c.sucursal_id = :sucursal_id OR (c.sucursal_id IS NULL AND :sucursal_id IS NULL))), 0) as egresos_monto,
                
                -- Caja activa
                (SELECT caja_monto_inicial FROM caja 
                 WHERE caja_estado = "VIGENTE" 
                 AND (sucursal_id = :sucursal_id OR (sucursal_id IS NULL AND :sucursal_id IS NULL))
                 LIMIT 1) as monto_inicial_caja
        ');
        
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /*===================================================================*/
    // VERIFICAR SI YA EXISTE CIERRE DE DÍA
    /*===================================================================*/
    static public function mdlVerificarCierreDiaExiste($sucursal_id, $fecha)
    {
        $stmt = Conexion::conectar()->prepare('SELECT COUNT(*) as existe FROM cierre_dia WHERE sucursal_id = :sucursal_id AND fecha_cierre = :fecha');
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['existe'] > 0;
    }

    /*===================================================================*/
    // OBTENER DATOS DE USUARIO PARA CONTEXTO
    /*===================================================================*/
    static public function mdlObtenerDatosUsuario($id_usuario)
    {
        $stmt = Conexion::conectar()->prepare("
            SELECT 
                u.id_usuario,
                u.nombre_usuario,
                p.descripcion as perfil,
                s.nombre as sucursal_nombre
            FROM usuarios u 
            INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
            LEFT JOIN sucursales s ON u.sucursal_id = s.id
            WHERE u.id_usuario = :id_usuario
        ");
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    static public function mdlObtenerCajaPrincipalAbiertaPorSucursal($sucursal_id) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM caja WHERE caja_estado = 'VIGENTE' AND tipo_caja = 'principal' AND sucursal_id = :sucursal_id LIMIT 1");
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}
