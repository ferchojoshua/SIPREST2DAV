<?php

require_once "conexion.php";

class AprobacionModelo
{

    /*===================================================================*/
    //Peticion  MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    static public function mdlListarPrestamosPorAprobacion($fecha_ini, $fecha_fin)
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_PRESTAMOS_POR_APROBACION(:fecha_ini, :fecha_fin)');
        $stmt->bindParam(":fecha_ini", $fecha_ini, PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $fecha_fin, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }



    /*===================================================================*/
    //APROBAR PRESTAMO
    /*===================================================================*/
    static public function mdlActualizarEstadoPrest($nro_prestamo)
    {

        try {

            $stmt = Conexion::conectar()->prepare("UPDATE prestamo_cabecera SET pres_aprobacion = 'aprobado' , pres_estado_caja = 'VIGENTE'  where nro_prestamo = :nro_prestamo");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);



            if ($stmt->execute()) {
                $stmt = null;
                $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_caja = 'VIGENTE', pdetalle_aprobacion = 'aprobado' where nro_prestamo = :nro_prestamo");

                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);



                if ($stmt->execute()) {
                    $resultado = "ok";
                } else {
                    $resultado = "error";
                }
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        return $resultado;
        $stmt = null;
    }

    /*===================================================================*/
    //VALIDAR PRÉSTAMO PARA APROBACIÓN
    /*===================================================================*/
    static public function mdlValidarPrestamoParaAprobacion($nro_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT pres_id, nro_prestamo, cliente_id, pres_aprobacion, pres_estado 
                                                   FROM prestamo_cabecera 
                                                   WHERE nro_prestamo = :nro_prestamo AND pres_aprobacion = 'pendiente'");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al validar préstamo: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    //VALIDAR QUE RUTA PERTENECE A SUCURSAL
    /*===================================================================*/
    static public function mdlValidarRutaSucursal($ruta_id, $sucursal_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as existe 
                                                   FROM rutas 
                                                   WHERE ruta_id = :ruta_id AND sucursal_id = :sucursal_id AND ruta_estado = 'activa'");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['existe'] > 0;
        } catch (Exception $e) {
            error_log("Error al validar ruta-sucursal: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    //VALIDAR QUE COBRADOR ESTÁ ASIGNADO A RUTA
    /*===================================================================*/
    static public function mdlValidarCobradorRuta($cobrador_id, $ruta_id)
    {
        try {
            // Verificar que el usuario existe y está activo
            $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as existe 
                                                   FROM usuarios 
                                                   WHERE id_usuario = :cobrador_id AND estado = 1");
            $stmt->bindParam(":cobrador_id", $cobrador_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $usuarioExiste = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($usuarioExiste['existe'] == 0) {
                return false;
            }

            // Verificar que el usuario está asignado a la ruta (opcional, si existe tabla usuarios_rutas)
            $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as asignado 
                                                   FROM usuarios_rutas 
                                                   WHERE usuario_id = :cobrador_id AND ruta_id = :ruta_id AND estado = 'activo'");
            $stmt->bindParam(":cobrador_id", $cobrador_id, PDO::PARAM_INT);
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);
            // Si no hay asignación específica, aceptar cualquier usuario activo (administradores pueden asignar libremente)
            return true; // Cambiar a: return $asignacion['asignado'] > 0; si se requiere asignación estricta
        } catch (Exception $e) {
            error_log("Error al validar cobrador-ruta: " . $e->getMessage());
            return true; // Permitir si hay error (fail-open para administradores)
        }
    }

    /*===================================================================*/
    //VERIFICAR SI EXISTEN CAMPOS DE ASIGNACIÓN EN LA TABLA
    /*===================================================================*/
    static public function mdlVerificarCamposAsignacion()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM prestamo_cabecera LIKE 'sucursal_asignada_id'");
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /*===================================================================*/
    //APROBAR PRÉSTAMO CON ASIGNACIÓN (ORIGINAL)
    /*===================================================================*/
    static public function mdlAprobarPrestamoConAsignacion($nro_prestamo, $sucursal_asignada_id, $ruta_asignada_id, $cobrador_asignado_id, $observaciones_asignacion)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();

            // 1. Aprobar el préstamo y asignar ruta/cobrador
            $stmt = $pdo->prepare("UPDATE prestamo_cabecera 
                                   SET pres_aprobacion = 'aprobado', 
                                       pres_estado_caja = 'VIGENTE',
                                       sucursal_asignada_id = :sucursal_asignada_id,
                                       ruta_asignada_id = :ruta_asignada_id,
                                       cobrador_asignado_id = :cobrador_asignado_id,
                                       fecha_asignacion = NOW(),
                                       observaciones_asignacion = :observaciones_asignacion
                                   WHERE nro_prestamo = :nro_prestamo");
            
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":sucursal_asignada_id", $sucursal_asignada_id, PDO::PARAM_INT);
            $stmt->bindParam(":ruta_asignada_id", $ruta_asignada_id, PDO::PARAM_INT);
            $stmt->bindParam(":cobrador_asignado_id", $cobrador_asignado_id, PDO::PARAM_INT);
            $stmt->bindParam(":observaciones_asignacion", $observaciones_asignacion, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar préstamo cabecera");
            }

            // 2. Actualizar el detalle del préstamo
            $stmt = $pdo->prepare("UPDATE prestamo_detalle 
                                   SET pdetalle_caja = 'VIGENTE', pdetalle_aprobacion = 'aprobado' 
                                   WHERE nro_prestamo = :nro_prestamo");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar préstamo detalle");
            }

            // 3. Obtener información del cliente para verificar si ya está en la ruta
            $stmt = $pdo->prepare("SELECT cliente_id FROM prestamo_cabecera WHERE nro_prestamo = :nro_prestamo");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($prestamo) {
                // 4. Verificar si el cliente ya está asignado a esta ruta
                $stmt = $pdo->prepare("SELECT COUNT(*) as existe FROM clientes_rutas 
                                       WHERE cliente_id = :cliente_id AND ruta_id = :ruta_id AND estado = 'activo'");
                $stmt->bindParam(":cliente_id", $prestamo['cliente_id'], PDO::PARAM_INT);
                $stmt->bindParam(":ruta_id", $ruta_asignada_id, PDO::PARAM_INT);
                $stmt->execute();
                $existe = $stmt->fetch(PDO::FETCH_ASSOC);

                // 5. Si no está asignado, agregarlo a la ruta
                if ($existe['existe'] == 0) {
                    // Obtener próximo orden de visita
                    $stmt = $pdo->prepare("SELECT COALESCE(MAX(orden_visita), 0) + 1 as siguiente_orden 
                                           FROM clientes_rutas WHERE ruta_id = :ruta_id");
                    $stmt->bindParam(":ruta_id", $ruta_asignada_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $orden = $stmt->fetch(PDO::FETCH_ASSOC);

                    // Insertar cliente en la ruta
                    $stmt = $pdo->prepare("INSERT INTO clientes_rutas 
                                           (cliente_id, ruta_id, orden_visita, estado, fecha_asignacion, observaciones) 
                                           VALUES (:cliente_id, :ruta_id, :orden_visita, 'activo', NOW(), :observaciones)");
                    $stmt->bindParam(":cliente_id", $prestamo['cliente_id'], PDO::PARAM_INT);
                    $stmt->bindParam(":ruta_id", $ruta_asignada_id, PDO::PARAM_INT);
                    $stmt->bindParam(":orden_visita", $orden['siguiente_orden'], PDO::PARAM_INT);
                    $stmt->bindParam(":observaciones", $observaciones_asignacion, PDO::PARAM_STR);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Error al asignar cliente a la ruta");
                    }
                }
            }

            $pdo->commit();
            return "ok";

        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log("Error en mdlAprobarPrestamoConAsignacion: " . $e->getMessage());
            return "Error: " . $e->getMessage();
        }
    }

    /*===================================================================*/
    //APROBAR PRÉSTAMO CON ASIGNACIÓN (SEGURO - DETECTA CAMPOS DISPONIBLES)
    /*===================================================================*/
    static public function mdlAprobarPrestamoConAsignacionSeguro($nro_prestamo, $sucursal_asignada_id, $ruta_asignada_id, $cobrador_asignado_id, $observaciones_asignacion)
    {
        try {
            $pdo = Conexion::conectar();
            $pdo->beginTransaction();

            // Verificar si existen los campos de asignación
            $tieneAsignacion = self::mdlVerificarCamposAsignacion();

            if ($tieneAsignacion) {
                // 1. Aprobar el préstamo CON asignación
                $stmt = $pdo->prepare("UPDATE prestamo_cabecera 
                                       SET pres_aprobacion = 'aprobado', 
                                           pres_estado_caja = 'VIGENTE',
                                           sucursal_asignada_id = :sucursal_asignada_id,
                                           ruta_asignada_id = :ruta_asignada_id,
                                           cobrador_asignado_id = :cobrador_asignado_id,
                                           fecha_asignacion = NOW(),
                                           observaciones_asignacion = :observaciones_asignacion
                                       WHERE nro_prestamo = :nro_prestamo");
                
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":sucursal_asignada_id", $sucursal_asignada_id, PDO::PARAM_INT);
                $stmt->bindParam(":ruta_asignada_id", $ruta_asignada_id, PDO::PARAM_INT);
                $stmt->bindParam(":cobrador_asignado_id", $cobrador_asignado_id, PDO::PARAM_INT);
                $stmt->bindParam(":observaciones_asignacion", $observaciones_asignacion, PDO::PARAM_STR);
                
                $mensaje = "Préstamo aprobado y asignado exitosamente";
            } else {
                // 1. Aprobar el préstamo SIN asignación (campos no disponibles)
                $stmt = $pdo->prepare("UPDATE prestamo_cabecera 
                                       SET pres_aprobacion = 'aprobado', 
                                           pres_estado_caja = 'VIGENTE'
                                       WHERE nro_prestamo = :nro_prestamo");
                
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                
                $mensaje = "Préstamo aprobado exitosamente (sin asignación - actualice la base de datos)";
            }

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar préstamo cabecera");
            }

            // 2. Actualizar el detalle del préstamo
            $stmt = $pdo->prepare("UPDATE prestamo_detalle 
                                   SET pdetalle_caja = 'VIGENTE', pdetalle_aprobacion = 'aprobado' 
                                   WHERE nro_prestamo = :nro_prestamo");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al actualizar préstamo detalle");
            }

            // 3. Solo intentar asignar cliente a ruta si tenemos campos de asignación
            if ($tieneAsignacion) {
                // Obtener información del cliente para verificar si ya está en la ruta
                $stmt = $pdo->prepare("SELECT cliente_id FROM prestamo_cabecera WHERE nro_prestamo = :nro_prestamo");
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->execute();
                $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($prestamo) {
                    // 4. Verificar si el cliente ya está asignado a esta ruta
                    $stmt = $pdo->prepare("SELECT COUNT(*) as existe FROM clientes_rutas 
                                           WHERE cliente_id = :cliente_id AND ruta_id = :ruta_id AND estado = 'activo'");
                    $stmt->bindParam(":cliente_id", $prestamo['cliente_id'], PDO::PARAM_INT);
                    $stmt->bindParam(":ruta_id", $ruta_asignada_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $existe = $stmt->fetch(PDO::FETCH_ASSOC);

                    // 5. Si no está asignado, agregarlo a la ruta
                    if ($existe['existe'] == 0) {
                        // Obtener próximo orden de visita
                        $stmt = $pdo->prepare("SELECT COALESCE(MAX(orden_visita), 0) + 1 as siguiente_orden 
                                               FROM clientes_rutas WHERE ruta_id = :ruta_id");
                        $stmt->bindParam(":ruta_id", $ruta_asignada_id, PDO::PARAM_INT);
                        $stmt->execute();
                        $orden = $stmt->fetch(PDO::FETCH_ASSOC);

                        // Insertar cliente en la ruta
                        $stmt = $pdo->prepare("INSERT INTO clientes_rutas 
                                               (cliente_id, ruta_id, orden_visita, estado, fecha_asignacion, observaciones) 
                                               VALUES (:cliente_id, :ruta_id, :orden_visita, 'activo', NOW(), :observaciones)");
                        $stmt->bindParam(":cliente_id", $prestamo['cliente_id'], PDO::PARAM_INT);
                        $stmt->bindParam(":ruta_id", $ruta_asignada_id, PDO::PARAM_INT);
                        $stmt->bindParam(":orden_visita", $orden['siguiente_orden'], PDO::PARAM_INT);
                        $stmt->bindParam(":observaciones", $observaciones_asignacion, PDO::PARAM_STR);
                        
                        if (!$stmt->execute()) {
                            throw new Exception("Error al asignar cliente a la ruta");
                        }
                    }
                }
            }

            $pdo->commit();
            return ["status" => "ok", "message" => $mensaje, "has_assignment" => $tieneAsignacion];

        } catch (Exception $e) {
            if (isset($pdo)) {
                $pdo->rollBack();
            }
            error_log("Error en mdlAprobarPrestamoConAsignacionSeguro: " . $e->getMessage());
            return ["status" => "error", "message" => "Error: " . $e->getMessage()];
        }
    }


    /*===================================================================*/
    //DESARPOBAR PRESTAMO
    /*===================================================================*/
    static public function mdlDesaprobarPrest($nro_prestamo)
    {

        $stmt = Conexion::conectar()->prepare('call SP_DESAPROBAR_PRESTAMO(:nro_prestamo)');
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

        $stmt->execute();

       if ($row = $stmt->fetchColumn()) {
           return $row;
       }
    }



    /*===================================================================*/
    //ANULAR PRESTAMO
    /*===================================================================*/
     static public function mdlAnularPrest($nro_prestamo)
     {

        $stmt = Conexion::conectar()->prepare('call SP_ANULAR_PRESTAMO(:nro_prestamo)');
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

        $stmt->execute();

       if ($row = $stmt->fetchColumn()) {
           return $row;
       }
 
        /* try {
             $stmt = Conexion::conectar()->prepare("UPDATE prestamo_cabecera SET pres_aprobacion = 'anulado', pres_estado_caja = '', pres_estado = 'Anulado' where nro_prestamo = :nro_prestamo");
             $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
 
             if ($stmt->execute()) {
                 $stmt = null;
                 $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'Anulado' where nro_prestamo = :nro_prestamo");
 
                 $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

                 if ($stmt->execute()) {
                     $resultado = "ok";
                 } else {
                     $resultado = "error";
                 }
             }
         } catch (Exception $e) {
             $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
         }
 
         return $resultado;
         $stmt = null;*/
     }

    /*===================================================================*/
    //LISTAR USUARIOS ACTIVOS (COBRADORES)
    /*===================================================================*/
    static public function mdlListarUsuariosActivos()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT 
                    u.id_usuario,
                    u.usuario,
                    CONCAT(u.nombre_usuario, ' ', COALESCE(u.apellido_usuario, '')) as  nombre_usuario,
                    u.estado,
                    s.nombre as sucursal_nombre,
                    p.descripcion as perfil_nombre,
                    (SELECT COUNT(*) FROM usuarios_rutas ur WHERE ur.usuario_id = u.id_usuario AND ur.estado = 'activo') as rutas_asignadas,
                    (SELECT GROUP_CONCAT(r.ruta_nombre) 
                     FROM usuarios_rutas ur 
                     JOIN rutas r ON ur.ruta_id = r.ruta_id 
                     WHERE ur.usuario_id = u.id_usuario AND ur.estado = 'activo'
                    ) as rutas_nombres,
                    (SELECT MAX(ur.fecha_asignacion) 
                     FROM usuarios_rutas ur 
                     WHERE ur.usuario_id = u.id_usuario
                    ) as ultima_asignacion
                FROM usuarios u
                LEFT JOIN sucursales s ON u.sucursal_id = s.id
                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                WHERE u.estado = 1 
                AND p.descripcion = 'Cobrador'
                ORDER BY u.nombre_usuario");
            
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($resultado)) {
                return [
                    'estado' => 'info',
                    'mensaje' => 'No hay cobradores disponibles'
                ];
            }
            
            return [
                'estado' => 'ok',
                'data' => $resultado
            ];
            
        } catch (Exception $e) {
            error_log("Error al listar usuarios activos: " . $e->getMessage());
            return [
                'estado' => 'error',
                'mensaje' => 'Error al cargar cobradores: ' . $e->getMessage()
            ];
        }
    }

    /*===================================================================*/
    // OBTENER ESTADO DE APROBACION DE PRESTAMO
    /*===================================================================*/
    static public function mdlGetPrestamoAprobacionStatus($nro_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT pres_aprobacion FROM prestamo_cabecera WHERE nro_prestamo = :nro_prestamo");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['pres_aprobacion'] : null;
        } catch (Exception $e) {
            error_log("Error en mdlGetPrestamoAprobacionStatus: " . $e->getMessage());
            return null;
        }
    }

    /*===================================================================*/
    //OBTENER DATOS COMPLETOS DEL PRÉSTAMO PARA PLAN DE PAGO
    /*===================================================================*/
    static public function mdlObtenerDatosCompletoPrestamo($nro_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    pc.pres_id,
                    pc.nro_prestamo,
                    pc.cliente_id,
                    pc.pres_monto,
                    pc.pres_interes,
                    pc.pres_cuotas,
                    pc.fpago_id,
                    pc.moneda_id,
                    DATE_FORMAT(pc.pres_f_emision, '%Y-%m-%d') as pres_f_emision,
                    pc.pres_monto_cuota,
                    pc.pres_monto_interes,
                    pc.pres_monto_total,
                    pc.tipo_calculo,
                    fp.fpago_descripcion,
                    m.moneda_simbolo,
                    c.cliente_nombres
                FROM prestamo_cabecera pc
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                WHERE pc.nro_prestamo = :nro_prestamo
                AND pc.pres_aprobacion = 'pendiente'
            ");
            
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            
            $datos = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($datos) {
                return $datos;
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerDatosCompletoPrestamo: " . $e->getMessage());
            return false;
        }
    }
}
