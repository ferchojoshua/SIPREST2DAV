<?php
 //require_once("../modelos/conexion.php");
 //require_once("../modelos/email_caja.php");
 // $CierreCorreo2 = new EmailCaja();


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
    static public function ctrRegistrarCaja($caja_descripcion, $caja_monto_inicial, $sucursal_id = null)
    {
        require_once "modelos/caja_modelo.php";
        session_start();
        $usuario_id = $_SESSION['id_usuario'] ?? null;
        $perfil = $_SESSION['perfil'] ?? '';
        // Si el usuario es admin y selecciona sucursal, usar ese valor
        if (strtolower($perfil) === 'administrador' && $sucursal_id) {
            $sucursal = $sucursal_id;
        } else {
            // Si no, usar la sucursal asignada al usuario
            $stmt = Conexion::conectar()->prepare('SELECT sucursal_id FROM usuarios WHERE id_usuario = :usuario_id');
            $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $sucursal = $row ? $row['sucursal_id'] : null;
        }
        $RegCaja = CajaModelo::mdlRegistrarCajaSucursal($caja_descripcion, $caja_monto_inicial, $usuario_id, $sucursal);
        return $RegCaja;
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
    static public function ctrCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso, $caja_interes)
    {
        require_once "modelos/caja_modelo.php";
        session_start();
        $usuario_id = $_SESSION['id_usuario'] ?? null;
        $perfil = $_SESSION['perfil'] ?? '';
        $cajaAbierta = CajaModelo::mdlObtenerDataEstadoCaja();
        if (!$cajaAbierta || $cajaAbierta->caja_estado !== 'VIGENTE') {
            return [
                'success' => false,
                'message' => 'No hay caja abierta para cerrar.'
            ];
        }
        // Validar tipo de caja
        if (isset($cajaAbierta->tipo_caja) && strtolower($cajaAbierta->tipo_caja) === 'principal') {
            if (strtolower($perfil) !== 'administrador') {
                return [
                    'success' => false,
                    'message' => 'Solo un administrador puede cerrar la caja principal.'
                ];
            }
        }
        // Si es secundaria/temporal, permitir al responsable
        // (Aquí podrías agregar lógica extra si quieres validar el responsable)
        $CerrarCaja = CajaModelo::mdlCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso, $caja_interes);
        return $CerrarCaja;
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
    //TRAER MOVIMIENTOS POR ID DE CAJA
    /*===================================================================*/
    static public function ctrMovimientosPorCajaID($caja_id)
    {
        $MovimientosporCajaID = CajaModelo::mdlMovimientosPorCajaID($caja_id);
        return $MovimientosporCajaID;
    }

    /*===================================================================*/
    // VERIFICAR PERMISOS DE USUARIO (Restaurado)
    /*===================================================================*/
    static public function ctrVerificarPermisosCaja($id_usuario, $accion, $monto = 0)
    {
        $permisos = CajaModelo::mdlVerificarPermisosCaja($id_usuario, $accion, $monto);
        return $permisos;
    }

    /*===================================================================*/
    // OBTENER CONTEXTO DE USUARIO PARA EL DASHBOARD
    /*===================================================================*/
    static public function ctrObtenerContextoUsuario($id_usuario)
    {
        $usuario = CajaModelo::mdlObtenerDatosUsuario($id_usuario);
        
        if (!$usuario) {
            return null; // O manejar el error como se prefiera
        }

        $perfiles_admin = ['administrador', 'developer senior', 'super administrador'];
        $es_admin = in_array(strtolower($usuario['perfil']), $perfiles_admin);

        $permisos_caja = $es_admin 
            ? [
                'puede_ejecutar' => true,
                'es_administrador' => true,
                'limite_monto' => 999999999.99,
                'mensaje' => 'Acceso total al sistema'
              ]
            : CajaModelo::mdlVerificarPermisosCaja($id_usuario, 'ABRIR_CAJA');

        return [
            'usuario' => [
                'id' => $usuario['id_usuario'],
                'nombre' => $usuario['nombre_usuario'],
                'perfil' => $usuario['perfil'],
                'sucursal' => $usuario['sucursal_nombre'],
                'es_admin' => $es_admin
            ],
            'permisos_caja' => $permisos_caja
        ];
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

    /*===================================================================*/
    // NUEVOS CONTROLADORES PARA SISTEMA DE SUCURSALES
    /*===================================================================*/

    /*===================================================================*/
    // LISTAR CAJAS POR SUCURSAL
    /*===================================================================*/
    static public function ctrListarCajasPorSucursal($usuario_id)
    {
        // Obtener información del usuario
        $stmt = Conexion::conectar()->prepare('SELECT u.sucursal_id, p.descripcion as perfil 
                                             FROM usuarios u 
                                             INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
                                             WHERE u.id_usuario = :usuario_id');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            return ['error' => true, 'mensaje' => 'Usuario no encontrado'];
        }
        
        $es_admin = ($usuario['perfil'] === 'Administrador');
        $sucursal_id = $usuario['sucursal_id'];
        
        $ListarCajas = CajaModelo::mdlListarCajasPorSucursal($sucursal_id, $es_admin);
        return $ListarCajas;
    }

    /*===================================================================*/
    // REGISTRAR CAJA CON SUCURSAL (ahora permite que el admin seleccione sucursal)
    /*===================================================================*/
    static public function ctrRegistrarCajaSucursal($caja_descripcion, $caja_monto_inicial, $usuario_id, $sucursal_id_param = null)
    {
        try {
            // Obtener información del usuario
            $stmt = Conexion::conectar()->prepare('
                SELECT 
                    u.sucursal_id, 
                    p.descripcion as perfil,
                    p.id_perfil
                FROM usuarios u 
                INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
                WHERE u.id_usuario = :usuario_id
            ');
            $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$usuario) {
                return ['resultado' => 3, 'mensaje' => 'Usuario no encontrado'];
            }
            
            // Si es administrador (id_perfil = 1), puede usar cualquier sucursal
            $es_admin = ($usuario['id_perfil'] == 1);
            
            // Determinar la sucursal a usar
            if ($es_admin) {
                // Administrador puede usar la sucursal especificada o su sucursal por defecto
                $sucursal_id = $sucursal_id_param ?: $usuario['sucursal_id'];
            } else {
                // Otros usuarios solo pueden usar su sucursal asignada
                $sucursal_id = $usuario['sucursal_id'];
                
                // Verificar que no intenten usar otra sucursal
                if ($sucursal_id_param && $sucursal_id_param != $sucursal_id) {
                    return [
                        'resultado' => 4, 
                        'mensaje' => 'No tiene permisos para abrir caja en otra sucursal'
                    ];
                }
            }
            
            // Verificar que la sucursal exista
            if ($sucursal_id) {
                $stmt = Conexion::conectar()->prepare('
                    SELECT id FROM sucursales WHERE id = :sucursal_id AND estado = 1
                ');
                $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
                $stmt->execute();
                if (!$stmt->fetch()) {
                    return [
                        'resultado' => 5, 
                        'mensaje' => 'La sucursal seleccionada no existe o está inactiva'
                    ];
                }
            }
            
            // Registrar la caja
            $RegCaja = CajaModelo::mdlRegistrarCajaSucursal(
                $caja_descripcion, 
                $caja_monto_inicial, 
                $usuario_id, 
                $sucursal_id
            );
            
            return $RegCaja;
            
        } catch (Exception $e) {
            error_log("Error en ctrRegistrarCajaSucursal: " . $e->getMessage());
            return [
                'resultado' => 0,
                'mensaje' => 'Error interno del sistema'
            ];
        }
    }

    /*===================================================================*/
    // VERIFICAR ACCESO A CAJA
    /*===================================================================*/
    static public function ctrVerificarAccesoCaja($usuario_id, $sucursal_id = null)
    {
        $acceso = CajaModelo::mdlVerificarAccesoCajaSucursal($usuario_id, $sucursal_id);
        return $acceso;
    }

    /*===================================================================*/
    // CONTROLADORES PARA CIERRE DE DÍA
    /*===================================================================*/

    /*===================================================================*/
    // GENERAR CIERRE DE DÍA
    /*===================================================================*/
    static public function ctrGenerarCierreDia($usuario_id, $fecha_cierre = null, $observaciones = '')
    {
        if ($fecha_cierre === null) {
            $fecha_cierre = date('Y-m-d');
        }
        
        // Obtener sucursal del usuario
        $stmt = Conexion::conectar()->prepare('SELECT sucursal_id FROM usuarios WHERE id_usuario = :usuario_id');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            return ['resultado' => 3, 'mensaje' => 'Usuario no encontrado'];
        }
        
        $sucursal_id = $usuario['sucursal_id'];
        
        // Verificar si ya existe cierre para la fecha
        if (CajaModelo::mdlVerificarCierreDiaExiste($sucursal_id, $fecha_cierre)) {
            return ['resultado' => 2, 'mensaje' => 'Ya existe un cierre de día para esta fecha'];
        }
        
        $resultado = CajaModelo::mdlGenerarCierreDia($sucursal_id, $fecha_cierre, $usuario_id, $observaciones);
        return $resultado;
    }

    /*===================================================================*/
    // LISTAR CIERRES DE DÍA
    /*===================================================================*/
    static public function ctrListarCierresDia($usuario_id)
    {
        // Obtener información del usuario
        $stmt = Conexion::conectar()->prepare('SELECT u.sucursal_id, p.descripcion as perfil 
                                             FROM usuarios u 
                                             INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
                                             WHERE u.id_usuario = :usuario_id');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            return ['error' => true, 'mensaje' => 'Usuario no encontrado'];
        }
        
        $es_admin = ($usuario['perfil'] === 'Administrador');
        $sucursal_id = $usuario['sucursal_id'];
        
        $cierres = CajaModelo::mdlListarCierresDia($sucursal_id, $es_admin);
        return $cierres;
    }

    /*===================================================================*/
    // OBTENER RESUMEN DEL DÍA ACTUAL
    /*===================================================================*/
    static public function ctrObtenerResumenDiaActual($usuario_id)
    {
        // Obtener sucursal del usuario
        $stmt = Conexion::conectar()->prepare('SELECT sucursal_id FROM usuarios WHERE id_usuario = :usuario_id');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$usuario) {
            return null;
        }
        
        $sucursal_id = $usuario['sucursal_id'];
        $fecha_actual = date('Y-m-d');
        
        $resumen = CajaModelo::mdlObtenerResumenDia($sucursal_id, $fecha_actual);
        
        // Agregar información adicional
        $resumen->puede_cerrar_dia = !CajaModelo::mdlVerificarCierreDiaExiste($sucursal_id, $fecha_actual);
        $resumen->fecha_cierre = $fecha_actual;
        
        return $resumen;
    }

    /*===================================================================*/
    // OBTENER SUCURSAL DEL USUARIO
    /*===================================================================*/
    static public function ctrObtenerSucursalUsuario($usuario_id)
    {
        $stmt = Conexion::conectar()->prepare('
            SELECT 
                u.sucursal_id,
                s.nombre as sucursal_nombre,
                s.codigo as sucursal_codigo,
                p.descripcion as perfil_nombre,
                CASE WHEN p.descripcion = "Administrador" THEN TRUE ELSE FALSE END as es_admin
            FROM usuarios u 
            LEFT JOIN sucursales s ON u.sucursal_id = s.id
            INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil 
            WHERE u.id_usuario = :usuario_id
        ');
        $stmt->bindParam(":usuario_id", $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


 }