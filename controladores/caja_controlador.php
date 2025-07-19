<?php
 //require_once("../modelos/conexion.php");
 require_once("../modelos/email_caja.php");
 $CierreCorreo2 = new EmailCaja();


class CajaControlador
 {

    /*===================================================================*/
     //LISTAR CAJA EN DATATABLE CON PROCEDURE
    /*===================================================================*/
     static public function ctrListarAperturaCaja()
     {
         $ListarCaja = CajaModelo::mdlListarAperturaCaja();
         return $ListarCaja;
         var_dump('controlador',$ListarCaja);
     }

    /*===================================================================*/
      //REGISTRAR CAJA
    /*===================================================================*/
    static public function ctrRegistrarCaja($caja_descripcion, $caja_monto_inicial)
    {
        $RegCaja = CajaModelo::mdlRegistrarCaja($caja_descripcion, $caja_monto_inicial);
        return $RegCaja;
        //var_dump($RegCaja);
    }


    /*===================================================================*/
    //OBTENER DATOS PARA EL CIERRE DE  CAJA
    /*===================================================================*/
    static public function ctrObtenerDataCierreCaja(){
        $DataCierreCaja = CajaModelo::mdlObtenerDataCierreCaja();
        return $DataCierreCaja;
    }


    /*===================================================================*/
    //CERRAR LA CAJA
    /*===================================================================*/
    static public function ctrCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total,$caja_count_prestamo,$caja_count_ingreso,$caja_count_egreso,$caja_interes)
    {
        $CerrarCaja = CajaModelo::mdlCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso,$caja_interes);
        return $CerrarCaja;
        var_dump($CerrarCaja);
    }


    /*===================================================================*/
    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    /*===================================================================*/
    static public function ctrObtenerDataEstadoCaja(){
        $DataEstadoCaja = CajaModelo::mdlObtenerDataEstadoCaja();
        return $DataEstadoCaja;
    }

  
    /*===================================================================*/
    //OBTENER   ID DE LA CAJA
    /*===================================================================*/
    static public function ctrObtenerIDCaja(){
        $traerIdCaja = CajaModelo::mdlObtenerIDCaja();
        return $traerIdCaja;
    } 


    /*===================================================================*/
    //VER  PRESTAMO POR CAJA ID
    /*===================================================================*/
    static public function ctrPrestamoPorCajaID($caja_id)
    {
        $PrestamosporCajaID =  CajaModelo::mdlPrestamoPorCajaID($caja_id);
        return $PrestamosporCajaID;
    }

    /*===================================================================*/
    // NUEVOS MÉTODOS PARA SISTEMA MEJORADO DE CAJA
    /*===================================================================*/

    /*===================================================================*/
    // VERIFICAR PERMISOS DE USUARIO
    /*===================================================================*/
    static public function ctrVerificarPermisosCaja($id_usuario, $accion, $monto = 0)
    {
        $permisos = CajaModelo::mdlVerificarPermisosCaja($id_usuario, $accion, $monto);
        return $permisos;
    }

    /*===================================================================*/
    // REGISTRAR CAJA CON VALIDACIONES COMPLETAS
    /*===================================================================*/
    static public function ctrRegistrarCajaConValidaciones($caja_descripcion, $caja_monto_inicial, $id_usuario, $validacion_fisica = false, $observaciones = null)
    {
        // Obtener IP del cliente
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
        
        // Registrar con validaciones
        $resultado = CajaModelo::mdlRegistrarCajaConValidaciones(
            $caja_descripcion, 
            $caja_monto_inicial, 
            $id_usuario, 
            $ip_address, 
            $validacion_fisica, 
            $observaciones
        );
        
        return $resultado;
    }

    /*===================================================================*/
    // CERRAR CAJA CON VALIDACIONES Y AUDITORÍA
    /*===================================================================*/
    static public function ctrCerrarCajaConValidaciones($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso, $caja_interes, $id_usuario, $validacion_fisica = false, $observaciones = null)
    {
        try {
            // Verificar permisos
            $permisos = CajaModelo::mdlVerificarPermisosCaja($id_usuario, 'CERRAR_CAJA');
            
            if (!$permisos->puede_ejecutar) {
                return (object)[
                    'success' => false,
                    'message' => 'No tiene permisos para cerrar caja',
                    'codigo' => 'PERMISSION_DENIED'
                ];
            }
            
            // Obtener datos actuales de la caja para auditoría
            $cajaActual = CajaModelo::mdlObtenerDataEstadoCaja();
            
            // Proceder con cierre original
            $resultado = CajaModelo::mdlCerrarCaja(
                $caja_monto_ingreso, 
                $caja_prestamo, 
                $caja__monto_egreso, 
                $caja_monto_total,
                $caja_count_prestamo, 
                $caja_count_ingreso, 
                $caja_count_egreso, 
                $caja_interes
            );
            
            if ($resultado == 1) {
                $ip_address = $_SERVER['REMOTE_ADDR'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? null;
                
                // Actualizar campos adicionales de cierre
                if ($cajaActual && isset($cajaActual->caja_id)) {
                    $pdo = Conexion::conectar();
                    $stmt = $pdo->prepare("
                        UPDATE caja 
                        SET usuario_cierre = :id_usuario,
                            ip_cierre = :ip_address,
                            validacion_fisica_cierre = :validacion_fisica,
                            observaciones_cierre = :observaciones
                        WHERE caja_id = :caja_id
                    ");
                    
                    $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
                    $stmt->bindParam(":ip_address", $ip_address, PDO::PARAM_STR);
                    $stmt->bindParam(":validacion_fisica", $validacion_fisica, PDO::PARAM_INT);
                    $stmt->bindParam(":observaciones", $observaciones, PDO::PARAM_STR);
                    $stmt->bindParam(":caja_id", $cajaActual->caja_id, PDO::PARAM_INT);
                    $stmt->execute();
                    
                    // Registrar auditoría del cierre
                    CajaModelo::mdlRegistrarAuditoriaCaja(
                        $cajaActual->caja_id,
                        $id_usuario,
                        'CIERRE',
                        "Cierre de caja - Total final: " . number_format($caja_monto_total, 2),
                        json_encode([
                            'estado_anterior' => 'VIGENTE',
                            'monto_inicial' => $cajaActual->caja_monto_inicial ?? 0
                        ]),
                        json_encode([
                            'monto_total' => $caja_monto_total,
                            'prestamos' => $caja_prestamo,
                            'ingresos' => $caja_monto_ingreso,
                            'egresos' => $caja__monto_egreso,
                            'validacion_fisica' => $validacion_fisica,
                            'observaciones' => $observaciones
                        ]),
                        $ip_address,
                        $caja_monto_total
                    );
                }
                
                return (object)[
                    'success' => true,
                    'message' => 'Caja cerrada correctamente',
                    'codigo' => 'SUCCESS'
                ];
            } else {
                return (object)[
                    'success' => false,
                    'message' => 'Hay préstamos pendientes. No se puede cerrar la caja.',
                    'codigo' => 'PRESTAMOS_PENDIENTES'
                ];
            }
            
        } catch (Exception $e) {
            error_log("Error en cierre de caja: " . $e->getMessage());
            return (object)[
                'success' => false,
                'message' => 'Error interno del sistema',
                'codigo' => 'SYSTEM_ERROR'
            ];
        }
    }

    /*===================================================================*/
    // OBTENER DASHBOARD EN TIEMPO REAL
    /*===================================================================*/
    static public function ctrObtenerDashboardCaja($id_usuario = null)
    {
        $dashboard = CajaModelo::mdlObtenerDashboardCaja($id_usuario);
        return $dashboard;
    }

    /*===================================================================*/
    // LISTAR ALERTAS PENDIENTES
    /*===================================================================*/
    static public function ctrListarAlertasPendientes($id_usuario = null)
    {
        $alertas = CajaModelo::mdlListarAlertasPendientes($id_usuario);
        return $alertas;
    }

    /*===================================================================*/
    // MARCAR ALERTA COMO LEÍDA
    /*===================================================================*/
    static public function ctrMarcarAlertaLeida($alerta_id, $id_usuario)
    {
        $resultado = CajaModelo::mdlMarcarAlertaLeida($alerta_id, $id_usuario);
        return $resultado;
    }

    /*===================================================================*/
    // REGISTRAR CONTEO FÍSICO
    /*===================================================================*/
    static public function ctrRegistrarConteoFisico($caja_id, $usuario_conteo, $tipo_conteo, $saldo_sistema, $saldo_fisico, $denominaciones = null, $observaciones = null)
    {
        // Convertir denominaciones a JSON si es un array
        if (is_array($denominaciones)) {
            $denominaciones = json_encode($denominaciones);
        }
        
        $conteo_id = CajaModelo::mdlRegistrarConteoFisico(
            $caja_id, 
            $usuario_conteo, 
            $tipo_conteo, 
            $saldo_sistema, 
            $saldo_fisico, 
            $denominaciones, 
            $observaciones
        );
        
        if ($conteo_id) {
            // Registrar auditoría del conteo
            $diferencia = $saldo_fisico - $saldo_sistema;
            CajaModelo::mdlRegistrarAuditoriaCaja(
                $caja_id,
                $usuario_conteo,
                'CONTEO_FISICO',
                "Conteo físico realizado - Diferencia: " . number_format($diferencia, 2),
                null,
                json_encode([
                    'tipo_conteo' => $tipo_conteo,
                    'saldo_sistema' => $saldo_sistema,
                    'saldo_fisico' => $saldo_fisico,
                    'diferencia' => $diferencia,
                    'conteo_id' => $conteo_id
                ]),
                $_SERVER['REMOTE_ADDR'] ?? null
            );
        }
        
        return $conteo_id;
    }

    /*===================================================================*/
    // GENERAR ALERTA MANUAL
    /*===================================================================*/
    static public function ctrGenerarAlertaCaja($caja_id, $tipo_alerta, $nivel_criticidad, $titulo, $mensaje, $datos_adicionales = null, $usuario_notificado = null)
    {
        $resultado = CajaModelo::mdlGenerarAlertaCaja(
            $caja_id, 
            $tipo_alerta, 
            $nivel_criticidad, 
            $titulo, 
            $mensaje, 
            $datos_adicionales, 
            $usuario_notificado
        );
        
        return $resultado;
    }

    /*===================================================================*/
    // VALIDAR SESIÓN Y OBTENER DATOS CONTEXTUALES
    /*===================================================================*/
    static public function ctrObtenerContextoUsuario()
    {
        // Verificar si hay sesión activa
        if (!isset($_SESSION['id_usuario'])) {
            return (object)[
                'success' => false,
                'message' => 'Sesión no válida'
            ];
        }
        
        $id_usuario = $_SESSION['id_usuario'];
        
        // Obtener permisos de caja del usuario
        $permisos_abrir = CajaModelo::mdlVerificarPermisosCaja($id_usuario, 'ABRIR_CAJA');
        $permisos_cerrar = CajaModelo::mdlVerificarPermisosCaja($id_usuario, 'CERRAR_CAJA');
        $permisos_supervisar = CajaModelo::mdlVerificarPermisosCaja($id_usuario, 'SUPERVISAR');
        
        // Obtener alertas pendientes
        $alertas = CajaModelo::mdlListarAlertasPendientes($id_usuario);
        
        return (object)[
            'success' => true,
            'id_usuario' => $id_usuario,
            'nombre_usuario' => $_SESSION['nombre_usuario'] ?? 'Usuario',
            'perfil' => $_SESSION['perfil'] ?? 'Usuario',
            'permisos' => (object)[
                'puede_abrir' => $permisos_abrir->puede_ejecutar ?? false,
                'puede_cerrar' => $permisos_cerrar->puede_ejecutar ?? false,
                'puede_supervisar' => $permisos_supervisar->puede_ejecutar ?? false,
                'es_administrador' => $permisos_abrir->es_administrador ?? false,
                'limite_apertura' => $permisos_abrir->limite_monto ?? 0,
                'limite_cerrar' => $permisos_cerrar->limite_monto ?? 0
            ],
            'alertas_pendientes' => count($alertas),
            'alertas' => $alertas
        ];
    }

    /*===================================================================*/
    // VERIFICAR ESTADO ACTUAL DEL SISTEMA DE CAJA
    /*===================================================================*/
    static public function ctrVerificarEstadoSistemaCaja()
    {
        try {
            // Verificar si hay cajas abiertas hace más de 12 horas
            $pdo = Conexion::conectar();
            $stmt = $pdo->prepare("
                SELECT 
                    caja_id,
                    caja_descripcion,
                    TIMESTAMPDIFF(HOUR, TIMESTAMP(caja_f_apertura, caja_hora_apertura), NOW()) as horas_abierta
                FROM caja 
                WHERE caja_estado = 'VIGENTE' 
                AND TIMESTAMPDIFF(HOUR, TIMESTAMP(caja_f_apertura, caja_hora_apertura), NOW()) > 12
            ");
            $stmt->execute();
            $cajas_prolongadas = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Generar alertas para cajas con tiempo prolongado
            foreach ($cajas_prolongadas as $caja) {
                CajaModelo::mdlGenerarAlertaCaja(
                    $caja->caja_id,
                    'TIEMPO_PROLONGADO',
                    $caja->horas_abierta > 24 ? 'CRITICAL' : 'WARNING',
                    'Caja abierta por tiempo prolongado',
                    "La caja '{$caja->caja_descripcion}' lleva {$caja->horas_abierta} horas abierta.",
                    json_encode(['horas_abierta' => $caja->horas_abierta])
                );
            }
            
            return (object)[
                'success' => true,
                'cajas_prolongadas' => count($cajas_prolongadas),
                'verificacion_timestamp' => date('Y-m-d H:i:s')
            ];
            
        } catch (Exception $e) {
            error_log("Error verificando estado del sistema de caja: " . $e->getMessage());
            return (object)[
                'success' => false,
                'message' => 'Error en verificación del sistema'
            ];
        }
    }


 }