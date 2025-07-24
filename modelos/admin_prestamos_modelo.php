<?php

require_once __DIR__ . '/conexion.php';



class AdminPrestamosModelo
{

    /*===================================================================*/
    //LISTAR PRESTAMOS POR ID DEL USUARIO
    /*===================================================================*/
    static public function mdlListarPrestamoPorUsuario($id_usuario)
    {

        try {

            $stmt = Conexion::conectar()->prepare("SELECT pc.pres_id ,
                                                            pc.nro_prestamo,
                                                            pc.cliente_id,
                                                            c.cliente_nombres,
                                                            pc.pres_monto,
                                                            pc.pres_interes,
                                                            pc.pres_cuotas,
                                                            pc.fpago_id,
                                                            fp.fpago_descripcion,
                                                            pc.id_usuario,
                                                            u.usuario,		
                                                            DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha,
                                                            pc.pres_aprobacion as estado,
                                                            '' as opciones,
                                                            pc.pres_monto_cuota,
															pc.pres_monto_interes,
															pc.pres_monto_total,
															pc.pres_cuotas_pagadas,
                                                            pc.reimpreso_admin          
                                                            from prestamo_cabecera pc
                                                            INNER JOIN clientes c on
                                                            pc.cliente_id = c.cliente_id
                                                            INNER JOIN forma_pago fp on 
                                                            pc.fpago_id = fp.fpago_id
                                                            INNER JOIN usuarios u on
                                                            pc.id_usuario = u.id_usuario
                                                            where pc.id_usuario =:id_usuario");

            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);


            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }


        $stmt = null;
    }


    /*===================================================================*/
    //VER DETALLE DL PRESTAMO EN MODAL
    /*===================================================================*/
    static public function mdlDetallePrestamo($nro_prestamo){
        
        // $stmt = Conexion::conectar()->prepare('call SP_VER_DETALLE_PRESTAMO(:nro_prestamo)');
        $stmt = Conexion::conectar()->prepare("SELECT
                                                pd.pdetalle_id,
                                                pd.nro_prestamo,
                                                pd.pdetalle_nro_cuota,
                                                CASE 
                                                    WHEN pd.pdetalle_fecha = '0000-00-00' OR pd.pdetalle_fecha IS NULL 
                                                    THEN DATE_FORMAT(NOW(), '%d/%m/%Y')
                                                    ELSE DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y')
                                                END as pdetalle_fecha,
                                                pd.pdetalle_monto_cuota,
                                                pd.pdetalle_saldo_cuota,
                                                pd.pdetalle_estado_cuota,
                                                CASE 
                                                    WHEN pd.pdetalle_fecha_registro = '0000-00-00 00:00:00' OR pd.pdetalle_fecha_registro IS NULL 
                                                    THEN ''
                                                    ELSE DATE_FORMAT(pd.pdetalle_fecha_registro, '%d/%m/%Y %H:%i')
                                                END as pdetalle_fecha_registro,
                                                m.moneda_simbolo,
                                                -- Datos de cabecera (solo una vez, no repetidos por cuota)
                                                pc.pres_monto,
                                                pc.pres_monto_total,
                                                pc.pres_interes,
                                                pc.pres_cuotas,
                                                DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') as pres_f_emision,
                                                c.cliente_nombres,
                                                c.cliente_dni,
                                                fp.fpago_descripcion
                                            FROM prestamo_detalle pd
                                            INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                                            INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                                            INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                                            INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                                            WHERE pd.nro_prestamo = :nro_prestamo
                                            ORDER BY pd.pdetalle_nro_cuota ASC");
        
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = null;
    }



    /*===================================================================*/
    //PAGAR CUOTA DEL PRESTAMO
    /*===================================================================*/
    static public function mdlPagarCuota($nro_prestamo, $pdetalle_nro_cuota)
    {
        $date = date("Y-m-d H:i:s");
        //print_r($nro_boleta);
        try {
            //ACTUALIZAMOS  EL DETALLE DEL PRESTAMO
            $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'pagada', pdetalle_fecha_registro = CURRENT_TIME() where nro_prestamo = :nro_prestamo   and pdetalle_nro_cuota = :pdetalle_nro_cuota  ");

            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);
            //$stmt->bindParam(":pdetalle_fecha_registro", $date, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $stmt = null;

                $stmt = Conexion::conectar()->prepare('call SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)'); //AL PAGAR TODAS LAS CUOTAS CAMBIA DE ESTADO A FINALIZADO
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

                if ($stmt->execute()) {

                    $stmt = null;

                    $stmt = Conexion::conectar()->prepare('call SP_MONTO_POR_CUOTA_PAGADA_D(:nro_prestamo, :pdetalle_nro_cuota)'); //CALCULAR MONTO RESTANTE 
                    $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                    $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        $resultado = "ok";
                    } else {
                        $resultado = "error";
                    }
                } else {
                    $resultado = "Error al registrar ";
                }
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        return $resultado;
        $stmt = null;
    }


    /*===================================================================*/
    //OBTENER CUOTAS PAGADAS
    /*===================================================================*/
    static public function mdlObtenerCuotasPagadas($nro_prestamo)
    {
        $stmt = Conexion::conectar()->prepare('call SP_CUOTAS_PAGADAS(:nro_prestamo)');
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    // OBTENER INFORMACION PARA NOTIFICACION WHATSAPP
    /*===================================================================*/
    static public function mdlObtenerInfoParaWhatsApp($nro_prestamo, $pdetalle_nro_cuota)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    c.cliente_nombres,
                    c.cliente_celular,
                    pc.pres_monto_restante,
                    pd.pdetalle_monto_cuota,
                    m.moneda_simbolo
                FROM prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pc.nro_prestamo = :nro_prestamo AND pd.pdetalle_nro_cuota = :pdetalle_nro_cuota
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$resultado) {
                error_log("No se encontraron datos para WhatsApp - Préstamo: $nro_prestamo, Cuota: $pdetalle_nro_cuota");
                return null;
            }
            
            return $resultado;
        } catch (Exception $e) {
            error_log("Error al obtener info para WhatsApp: " . $e->getMessage());
            return null;
        }
    }


    /*===================================================================*/
    //LIQUIDAR PRESTAMO
    /*===================================================================*/
    static public function mdlLiquidarPrestamo($nro_prestamo, $array_cuota)
    {

        try {

            $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'pagada', pdetalle_fecha_registro = CURRENT_TIME() where nro_prestamo = :nro_prestamo   and pdetalle_nro_cuota = :pdetalle_nro_cuota  ");

            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $array_cuota, PDO::PARAM_INT);


            if ($stmt->execute()) {
                $stmt = null;
                $stmt = Conexion::conectar()->prepare('call SP_LIQUIDAR_PRESTAMO (:nro_prestamo, :pdetalle_nro_cuota)'); //CALCULAR MONTO RESTANTE 
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_nro_cuota", $array_cuota, PDO::PARAM_INT);




                if ($stmt->execute()) {

                    $stmt = null;
                    $stmt = Conexion::conectar()->prepare('call SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)'); //AL PAGAR TODAS LAS CUOTAS CAMBIA DE ESTADO A FINALIZADO
                    $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);



                    if ($stmt->execute()) {

                        $resultado = "ok";
                    } else {
                        $resultado = "error";
                    }
                } else {
                    $resultado = "Error al registrar ";
                }
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }


        return $resultado;
        //var_dump($resultado);
        $stmt = null;


        // try {
        //     // ACTUALIZAMOS  EL DETALLE DEL PRESTAMO
        //     $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'pagada', pdetalle_fecha_registro = CURRENT_TIME() where nro_prestamo = :nro_prestamo   and pdetalle_nro_cuota = :pdetalle_nro_cuota  " );

        //     $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
        //     $stmt->bindParam(":pdetalle_nro_cuota", $array_cuota, PDO::PARAM_INT);

        //     //if($stmt -> execute()){
        //       //  $stmt = null;

        //       //  $stmt = Conexion::conectar()->prepare('call SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)'); //AL PAGAR TODAS LAS CUOTAS CAMBIA DE ESTADO A FINALIZADO
        //       //  $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

        //         if ($stmt->execute()) {

        //             $stmt = null;

        //             $stmt = Conexion::conectar()->prepare('call SP_MONTO_POR_CUOTA_PAGADA_D(:nro_prestamo, :pdetalle_nro_cuota)'); //CALCULAR MONTO RESTANTE 
        //             $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
        //             $stmt->bindParam(":pdetalle_nro_cuota", $array_cuota, PDO::PARAM_INT);

        //             if ($stmt->execute()) {

        //                 $resultado = "ok";
        //             } else {
        //                 $resultado = "error";
        //             }
        //         } else {
        //             $resultado = "Error al registrar ";
        //         }
        //   //  }


        // } catch (Exception $e) {
        //    $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        // }


        // return $resultado;
        // var_dump($resultado);
        // $stmt = null;


    }

    /*===================================================================*/
    // OBTENER INFORMACIÓN COMPLETA PARA WHATSAPP
    /*===================================================================*/
    static public function mdlObtenerInfoWhatsApp($nro_prestamo, $pdetalle_nro_cuota)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    c.cliente_nombres,
                    c.cliente_celular,
                    pc.nro_prestamo,
                    pd.pdetalle_nro_cuota,
                    pd.pdetalle_monto_cuota,
                    pd.pdetalle_saldo_cuota,
                    m.moneda_simbolo,
                    m.moneda_nombre,
                    pc.pres_monto_total,
                    (SELECT SUM(pd2.pdetalle_saldo_cuota) 
                     FROM prestamo_detalle pd2 
                     WHERE pd2.nro_prestamo = pc.nro_prestamo 
                     AND pd2.pdetalle_estado_cuota != 'pagada') as saldo_total_prestamo
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pd.nro_prestamo = :nro_prestamo 
                AND pd.pdetalle_nro_cuota = :pdetalle_nro_cuota
            ");
            
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener info WhatsApp: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    // REGISTRAR ABONO DE CUOTA CON ARRASTRE Y LOG DE SALDO
    /*===================================================================*/
    static public function mdlRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar)
    {
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            // 1. Obtener la cuota actual
            error_log("Buscando cuota - Préstamo: $nro_prestamo, Cuota: $pdetalle_nro_cuota");
            
            $stmt = $conexion->prepare("SELECT pdetalle_monto_cuota, pdetalle_saldo_cuota, pdetalle_estado_cuota FROM prestamo_detalle WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :pdetalle_nro_cuota FOR UPDATE");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_INT);
            $stmt->execute();
            $cuota_actual = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cuota_actual) {
                error_log("No se encontró cuota - Préstamo: $nro_prestamo, Cuota: $pdetalle_nro_cuota");
                
                // Verificar si existe el préstamo
                $stmt_check = $conexion->prepare("SELECT COUNT(*) as total FROM prestamo_detalle WHERE nro_prestamo = :nro_prestamo");
                $stmt_check->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt_check->execute();
                $count = $stmt_check->fetch(PDO::FETCH_ASSOC);
                
                if ($count['total'] == 0) {
                    error_log("El préstamo $nro_prestamo no existe en prestamo_detalle");
                    $conexion->rollBack();
                    return "error_prestamo_no_existe";
                } else {
                    error_log("El préstamo $nro_prestamo existe pero no tiene la cuota $pdetalle_nro_cuota");
                    $conexion->rollBack();
                    return "error_cuota_no_encontrada";
                }
            }
            
            error_log("Cuota encontrada - Estado: " . $cuota_actual['pdetalle_estado_cuota'] . ", Saldo: " . $cuota_actual['pdetalle_saldo_cuota']);

            $saldo_a_pagar_hoy = (float) $cuota_actual["pdetalle_saldo_cuota"];
            $saldo_post_abono = $saldo_a_pagar_hoy - (float) $monto_a_abonar;
            
            // 2. Determinar estado de la cuota y si hay saldo para arrastrar
            if ($saldo_post_abono <= 0.01) { // El pago cubre la totalidad o más de la cuota
                // La cuota queda totalmente pagada
                $stmt = $conexion->prepare("UPDATE prestamo_detalle SET pdetalle_saldo_cuota = 0, pdetalle_estado_cuota = 'pagada', pdetalle_fecha_registro = CURRENT_TIME() WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :pdetalle_nro_cuota");
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_INT);
                $stmt->execute();

            } else { // El pago es parcial, hay que arrastrar el saldo
                $monto_arrastrado = $saldo_post_abono;
                $siguiente_cuota_nro = (int)$pdetalle_nro_cuota + 1;

                // Marcar la cuota actual como pagada (su deuda se mueve) y su saldo en 0
                $stmt = $conexion->prepare("UPDATE prestamo_detalle SET pdetalle_saldo_cuota = 0, pdetalle_estado_cuota = 'pagada', pdetalle_fecha_registro = CURRENT_TIME() WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :pdetalle_nro_cuota");
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_INT);
                $stmt->execute();

                // Sumar el saldo a la siguiente cuota
                $stmt = $conexion->prepare("UPDATE prestamo_detalle SET pdetalle_saldo_cuota = pdetalle_saldo_cuota + :monto_arrastrado WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :siguiente_cuota_nro");
                $stmt->bindParam(":monto_arrastrado", $monto_arrastrado, PDO::PARAM_STR);
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":siguiente_cuota_nro", $siguiente_cuota_nro, PDO::PARAM_INT);
                $stmt->execute();

                // Registrar en el log
                $stmt = $conexion->prepare("INSERT INTO log_saldos_arrastrados (nro_prestamo, cuota_origen, cuota_destino, monto_arrastrado) VALUES (:nro_prestamo, :cuota_origen, :cuota_destino, :monto_arrastrado)");
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":cuota_origen", $pdetalle_nro_cuota, PDO::PARAM_INT);
                $stmt->bindParam(":cuota_destino", $siguiente_cuota_nro, PDO::PARAM_INT);
                $stmt->bindParam(":monto_arrastrado", $monto_arrastrado, PDO::PARAM_STR);
                $stmt->execute();
            }

            // 3. Verificar si el préstamo está finalizado
            $stmt_finalizado = $conexion->prepare('call SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)');
            $stmt_finalizado->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt_finalizado->execute();

            $conexion->commit();
            return "ok";

        } catch (Exception $e) {
            if (isset($conexion) && $conexion->inTransaction()) {
                $conexion->rollBack();
            }
            error_log("Error en mdlRegistrarAbono: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return 'Error al registrar el abono: ' .  $e->getMessage();
        }
    }

    /*===================================================================*/
    // REGISTRAR ABONO EXTRAORDINARIO
    /*===================================================================*/
    static public function mdlRegistrarAbonoExtraordinario($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar)
    {
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            $monto_restante = (float) $monto_a_abonar;
            $cuota_actual = (int) $pdetalle_nro_cuota;
            
            // Obtener todas las cuotas pendientes desde la cuota actual en adelante
            $stmt = $conexion->prepare("
                SELECT pdetalle_nro_cuota, pdetalle_saldo_cuota 
                FROM prestamo_detalle 
                WHERE nro_prestamo = :nro_prestamo 
                  AND pdetalle_nro_cuota >= :pdetalle_nro_cuota 
                  AND pdetalle_estado_cuota IN ('pendiente', 'parcialmente_pagada')
                ORDER BY pdetalle_nro_cuota ASC
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $cuota_actual, PDO::PARAM_INT);
            $stmt->execute();
            $cuotas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($cuotas)) {
                $conexion->rollback();
                return "error_no_cuotas_pendientes";
            }

            $cuotas_afectadas = [];
            
            // Aplicar el abono a las cuotas
            foreach ($cuotas as $cuota) {
                if ($monto_restante <= 0) {
                    break;
                }

                $saldo_cuota = (float) $cuota['pdetalle_saldo_cuota'];
                $nro_cuota = (int) $cuota['pdetalle_nro_cuota'];
                
                if ($monto_restante >= $saldo_cuota) {
                    // El abono cubre completamente esta cuota
                    $monto_aplicado = $saldo_cuota;
                    $nuevo_saldo = 0;
                    $estado_cuota = 'pagada';
                    $monto_restante -= $saldo_cuota;
                } else {
                    // El abono cubre parcialmente esta cuota
                    $monto_aplicado = $monto_restante;
                    $nuevo_saldo = $saldo_cuota - $monto_restante;
                    $estado_cuota = 'parcialmente_pagada';
                    $monto_restante = 0;
                }

                // Actualizar la cuota
                $stmt = $conexion->prepare("
                    UPDATE prestamo_detalle 
                    SET pdetalle_saldo_cuota = :nuevo_saldo, 
                        pdetalle_estado_cuota = :estado_cuota, 
                        pdetalle_fecha_registro = CURRENT_TIME() 
                    WHERE nro_prestamo = :nro_prestamo 
                      AND pdetalle_nro_cuota = :nro_cuota
                ");
                $stmt->bindParam(":nuevo_saldo", $nuevo_saldo, PDO::PARAM_STR);
                $stmt->bindParam(":estado_cuota", $estado_cuota, PDO::PARAM_STR);
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":nro_cuota", $nro_cuota, PDO::PARAM_INT);
                $stmt->execute();

                $cuotas_afectadas[] = [
                    'cuota' => $nro_cuota,
                    'monto_aplicado' => $monto_aplicado,
                    'estado' => $estado_cuota
                ];
            }

            // Verificar si el préstamo está completamente pagado
            $stmt = $conexion->prepare('CALL SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)');
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();

            $conexion->commit();
            
            return [
                'status' => 'ok',
                'cuotas_afectadas' => $cuotas_afectadas,
                'monto_sobrante' => $monto_restante
            ];

        } catch (Exception $e) {
            $conexion->rollback();
            return 'Excepción capturada: ' . $e->getMessage();
        }
    }

    /*===================================================================*/
    // CREAR NOTA DE DÉBITO PARA RECÁLCULO DE PRÉSTAMO
    /*===================================================================*/
    static public function mdlCrearNotaDebito($nro_prestamo, $nuevo_interes, $nuevas_cuotas, $motivo, $id_usuario)
    {
        try {
            $conexion = Conexion::conectar();
            $conexion->beginTransaction();

            // 1. Obtener datos actuales del préstamo
            $stmt = $conexion->prepare("
                SELECT pc.*, c.cliente_nombres, c.cliente_dni, fp.fpago_descripcion, m.moneda_simbolo
                FROM prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pc.nro_prestamo = :nro_prestamo
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $prestamo_actual = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$prestamo_actual) {
                $conexion->rollback();
                return "error_prestamo_no_encontrado";
            }

            // 2. Calcular el saldo actual del préstamo
            $stmt = $conexion->prepare("
                SELECT 
                    COUNT(*) as cuotas_pagadas,
                    COALESCE(SUM(pdetalle_monto_cuota), 0) as monto_pagado
                FROM prestamo_detalle 
                WHERE nro_prestamo = :nro_prestamo 
                  AND pdetalle_estado_cuota = 'pagada'
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $estado_pagos = $stmt->fetch(PDO::FETCH_ASSOC);

            $saldo_capital = floatval($prestamo_actual['pres_monto']) - floatval($estado_pagos['monto_pagado']);
            
            if ($saldo_capital <= 0) {
                $conexion->rollback();
                return "error_prestamo_ya_pagado";
            }

            // 3. Recalcular el préstamo con nuevas condiciones
            $nuevo_interes_decimal = floatval($nuevo_interes) / 100;
            $fpago_id = $prestamo_actual['fpago_id'];
            
            // Determinar tasa por período según forma de pago
            $i_per_period = 0;
            switch ($fpago_id) {
                case "1": $i_per_period = $nuevo_interes_decimal / 365; break; // DIARIO
                case "2": $i_per_period = $nuevo_interes_decimal / 52; break;  // SEMANAL
                case "3": $i_per_period = $nuevo_interes_decimal / 24; break;  // QUINCENAL
                case "4": $i_per_period = $nuevo_interes_decimal / 12; break;  // MENSUAL
                case "5": $i_per_period = $nuevo_interes_decimal / 6; break;   // BIMESTRAL
                case "6": $i_per_period = $nuevo_interes_decimal / 2; break;   // SEMESTRAL
                case "7": $i_per_period = $nuevo_interes_decimal / 1; break;   // ANUAL
                default: $i_per_period = $nuevo_interes_decimal / 12; break;
            }

            // Calcular nueva cuota fija
            $nueva_cuota_fija = 0;
            if ($i_per_period == 0) {
                $nueva_cuota_fija = $saldo_capital / intval($nuevas_cuotas);
            } else {
                $nueva_cuota_fija = $saldo_capital * ($i_per_period * pow((1 + $i_per_period), intval($nuevas_cuotas))) / (pow((1 + $i_per_period), intval($nuevas_cuotas)) - 1);
            }

            $nuevo_monto_total = $nueva_cuota_fija * intval($nuevas_cuotas);
            $nuevo_monto_interes = $nuevo_monto_total - $saldo_capital;

            // 4. Generar número de nota de débito
            $stmt = $conexion->prepare("SELECT confi_correlativo FROM empresa LIMIT 1");
            $stmt->execute();
            $correlativo = $stmt->fetch(PDO::FETCH_ASSOC);
            $nro_nota_debito = "ND-" . str_pad($correlativo['confi_correlativo'], 8, '0', STR_PAD_LEFT);

            // 5. Registrar la nota de débito
            $stmt = $conexion->prepare("
                INSERT INTO notas_debito (
                    nro_nota_debito, nro_prestamo, motivo, 
                    interes_anterior, interes_nuevo,
                    cuotas_anterior, cuotas_nuevas,
                    cuota_anterior, cuota_nueva,
                    saldo_capital, monto_interes_nuevo, monto_total_nuevo,
                    id_usuario, fecha_registro
                ) VALUES (
                    :nro_nota_debito, :nro_prestamo, :motivo,
                    :interes_anterior, :interes_nuevo,
                    :cuotas_anterior, :cuotas_nuevas,
                    :cuota_anterior, :cuota_nueva,
                    :saldo_capital, :monto_interes_nuevo, :monto_total_nuevo,
                    :id_usuario, NOW()
                )
            ");
            $stmt->bindParam(":nro_nota_debito", $nro_nota_debito, PDO::PARAM_STR);
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);
            $stmt->bindParam(":interes_anterior", $prestamo_actual['pres_interes'], PDO::PARAM_STR);
            $stmt->bindParam(":interes_nuevo", $nuevo_interes, PDO::PARAM_STR);
            $stmt->bindParam(":cuotas_anterior", $prestamo_actual['pres_cuotas'], PDO::PARAM_INT);
            $stmt->bindParam(":cuotas_nuevas", $nuevas_cuotas, PDO::PARAM_INT);
            $stmt->bindParam(":cuota_anterior", $prestamo_actual['pres_monto_cuota'], PDO::PARAM_STR);
            $stmt->bindParam(":cuota_nueva", $nueva_cuota_fija, PDO::PARAM_STR);
            $stmt->bindParam(":saldo_capital", $saldo_capital, PDO::PARAM_STR);
            $stmt->bindParam(":monto_interes_nuevo", $nuevo_monto_interes, PDO::PARAM_STR);
            $stmt->bindParam(":monto_total_nuevo", $nuevo_monto_total, PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $stmt->execute();

            // 6. Actualizar el préstamo con las nuevas condiciones
            $stmt = $conexion->prepare("
                UPDATE prestamo_cabecera SET
                    pres_interes = :nuevo_interes,
                    pres_cuotas = :nuevas_cuotas,
                    pres_monto_cuota = :nueva_cuota,
                    pres_monto_interes = :nuevo_monto_interes,
                    pres_monto_total = :nuevo_monto_total,
                    pres_cuotas_restante = :nuevas_cuotas,
                    pres_monto_restante = :nuevo_monto_total
                WHERE nro_prestamo = :nro_prestamo
            ");
            $stmt->bindParam(":nuevo_interes", $nuevo_interes, PDO::PARAM_STR);
            $stmt->bindParam(":nuevas_cuotas", $nuevas_cuotas, PDO::PARAM_INT);
            $stmt->bindParam(":nueva_cuota", $nueva_cuota_fija, PDO::PARAM_STR);
            $stmt->bindParam(":nuevo_monto_interes", $nuevo_monto_interes, PDO::PARAM_STR);
            $stmt->bindParam(":nuevo_monto_total", $nuevo_monto_total, PDO::PARAM_STR);
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();

            // 7. Eliminar cuotas pendientes del cronograma anterior
            $stmt = $conexion->prepare("
                DELETE FROM prestamo_detalle 
                WHERE nro_prestamo = :nro_prestamo 
                  AND pdetalle_estado_cuota = 'pendiente'
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();

            // 8. Generar nuevo cronograma de cuotas
            $cuota_inicial = intval($estado_pagos['cuotas_pagadas']) + 1;
            
            // Obtener fecha de la última cuota pagada para continuar desde ahí
            $stmt = $conexion->prepare("
                SELECT MAX(pdetalle_fecha) as ultima_fecha 
                FROM prestamo_detalle 
                WHERE nro_prestamo = :nro_prestamo 
                  AND pdetalle_estado_cuota = 'pagada'
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            $ultima_fecha_result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $fecha_base = $ultima_fecha_result['ultima_fecha'] ? $ultima_fecha_result['ultima_fecha'] : $prestamo_actual['pres_f_emision'];
            $fecha_actual = new DateTime($fecha_base);
            
            // Avanzar una unidad de tiempo según la forma de pago
            switch ($fpago_id) {
                case "1": $fecha_actual->add(new DateInterval('P1D')); break; // DIARIO
                case "2": $fecha_actual->add(new DateInterval('P1W')); break; // SEMANAL
                case "3": $fecha_actual->add(new DateInterval('P14D')); break; // QUINCENAL
                case "4": $fecha_actual->add(new DateInterval('P1M')); break; // MENSUAL
                case "5": $fecha_actual->add(new DateInterval('P2M')); break; // BIMESTRAL
                case "6": $fecha_actual->add(new DateInterval('P6M')); break; // SEMESTRAL
                case "7": $fecha_actual->add(new DateInterval('P1Y')); break; // ANUAL
            }

            // Crear las nuevas cuotas
            for ($i = 0; $i < intval($nuevas_cuotas); $i++) {
                $nro_cuota = $cuota_inicial + $i;
                $fecha_cuota = $fecha_actual->format('Y-m-d');
                
                $stmt = $conexion->prepare("
                    INSERT INTO prestamo_detalle (
                        nro_prestamo, pdetalle_nro_cuota, pdetalle_monto_cuota, 
                        pdetalle_fecha, pdetalle_estado_cuota, pdetalle_liquidar, 
                        pdetalle_caja, pdetalle_aprobacion, pdetalle_saldo_cuota
                    ) VALUES (
                        :nro_prestamo, :nro_cuota, :monto_cuota,
                        :fecha_cuota, 'pendiente', '0',
                        'VIGENTE', 'pendiente', :saldo_cuota
                    )
                ");
                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":nro_cuota", $nro_cuota, PDO::PARAM_INT);
                $stmt->bindParam(":monto_cuota", $nueva_cuota_fija, PDO::PARAM_STR);
                $stmt->bindParam(":fecha_cuota", $fecha_cuota, PDO::PARAM_STR);
                $stmt->bindParam(":saldo_cuota", $nueva_cuota_fija, PDO::PARAM_STR);
                $stmt->execute();

                // Avanzar a la siguiente fecha
                switch ($fpago_id) {
                    case "1": $fecha_actual->add(new DateInterval('P1D')); break;
                    case "2": $fecha_actual->add(new DateInterval('P1W')); break;
                    case "3": $fecha_actual->add(new DateInterval('P14D')); break;
                    case "4": $fecha_actual->add(new DateInterval('P1M')); break;
                    case "5": $fecha_actual->add(new DateInterval('P2M')); break;
                    case "6": $fecha_actual->add(new DateInterval('P6M')); break;
                    case "7": $fecha_actual->add(new DateInterval('P1Y')); break;
                }
            }

            // 9. Actualizar correlativo
            $stmt = $conexion->prepare("UPDATE empresa SET confi_correlativo = LPAD(confi_correlativo + 1, 8, '0')");
            $stmt->execute();

            $conexion->commit();
            
            return [
                'status' => 'ok',
                'nro_nota_debito' => $nro_nota_debito,
                'saldo_capital' => $saldo_capital,
                'nueva_cuota' => $nueva_cuota_fija,
                'nuevo_monto_total' => $nuevo_monto_total,
                'nuevo_monto_interes' => $nuevo_monto_interes,
                'cuotas_restantes' => intval($nuevas_cuotas)
            ];

        } catch (Exception $e) {
            $conexion->rollback();
            return 'Excepción capturada: ' . $e->getMessage();
        }
    }

    /*===================================================================*/
    // OBTENER DATOS PARA NOTA DE DÉBITO
    /*===================================================================*/
    static public function mdlObtenerDatosNotaDebito($nro_nota_debito)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    nd.*,
                    pc.cliente_id,
                    c.cliente_nombres,
                    c.cliente_dni,
                    c.cliente_direccion,
                    fp.fpago_descripcion,
                    m.moneda_simbolo,
                    m.moneda_nombre,
                    e.confi_razon,
                    e.confi_ruc,
                    e.confi_direccion as empresa_direccion,
                    e.config_correo,
                    e.config_celular,
                    e.config_logo,
                    u.usuario
                FROM notas_debito nd
                INNER JOIN prestamo_cabecera pc ON nd.nro_prestamo = pc.nro_prestamo
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                INNER JOIN usuarios u ON nd.id_usuario = u.id_usuario,
                empresa e
                WHERE nd.nro_nota_debito = :nro_nota_debito
            ");
            $stmt->bindParam(":nro_nota_debito", $nro_nota_debito, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    /*===================================================================*/
    // LISTAR NOTAS DE DÉBITO
    /*===================================================================*/
    static public function mdlListarNotasDebito()
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    nd.nro_nota_debito,
                    nd.nro_prestamo,
                    nd.motivo,
                    nd.saldo_capital,
                    nd.cuota_nueva,
                    nd.fecha_registro,
                    c.cliente_nombres,
                    u.usuario,
                    m.moneda_simbolo
                FROM notas_debito nd
                INNER JOIN prestamo_cabecera pc ON nd.nro_prestamo = pc.nro_prestamo
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                INNER JOIN usuarios u ON nd.id_usuario = u.id_usuario
                ORDER BY nd.fecha_registro DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /*===================================================================*/
    // OBTENER ESTADO DE REIMPRESIÓN DEL CONTRATO
    /*===================================================================*/
    static public function mdlObtenerEstadoReimpresion($id_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT reimpreso_admin 
                FROM prestamo_cabecera 
                WHERE pres_id = :id_prestamo
            ");
            $stmt->bindParam(":id_prestamo", $id_prestamo, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    /*===================================================================*/
    // ACTUALIZAR REIMPRESIÓN ADMIN
    /*===================================================================*/
    static public function mdlActualizarReimpresionAdmin($id_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                UPDATE prestamo_cabecera 
                SET reimpreso_admin = 1 
                WHERE pres_id = :id_prestamo
            ");
            $stmt->bindParam(":id_prestamo", $id_prestamo, PDO::PARAM_INT);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (Exception $e) {
            return "error";
        }
    }

    /*===================================================================*/
    // OBTENER INFORMACION COMPLETA DEL PRESTAMO
    /*===================================================================*/
    static public function mdlObtenerInfoPrestamo($nro_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    pc.pres_id,
                    pc.nro_prestamo,
                    pc.cliente_id,
                    c.cliente_nombres,
                    c.cliente_cel as cliente_celular,
                    c.cliente_correo as cliente_email,
                    c.cliente_dni,
                    pc.pres_monto,
                    pc.pres_interes,
                    pc.pres_cuotas,
                    pc.pres_monto_cuota,
                    pc.pres_monto_interes,
                    pc.pres_monto_total,
                    pc.pres_monto_restante,
                    pc.pres_cuotas_pagadas,
                    DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha_registro,
                    pc.pres_aprobacion as estado,
                    fp.fpago_descripcion,
                    m.moneda_simbolo,
                    m.moneda_nombre
                FROM prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pc.nro_prestamo = :nro_prestamo
            ");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en mdlObtenerInfoPrestamo: " . $e->getMessage());
            return false;
        }
    }

    /*===================================================================*/
    // ENVIAR TABLA DE PAGOS POR CORREO
    /*===================================================================*/
    static public function mdlEnviarTablaCorreo($infoPrestamo, $historialPagos, $cliente_nombres)
    {
        try {
            // Verificar si el cliente tiene email
            if (empty($infoPrestamo['cliente_email'])) {
                return "El cliente no tiene email registrado.";
            }

            // Requerir PHPMailer
            require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
            require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
            require_once __DIR__ . '/../PHPMailer/src/Exception.php';

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);

            // Configuración del servidor de correo
            $mail->isSMTP();
            
            // Usar la configuración centralizada de email_config.php
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            
            // Configuración adicional para Gmail
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            
            // Configuración adicional para Gmail SMTP
            $mail->SMTPDebug = 0; // Cambiar a 2 para debug detallado
            $mail->Debugoutput = 'error_log';
            $mail->SMTPKeepAlive = true;

            // Remitente y destinatario
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($infoPrestamo['cliente_email'], $cliente_nombres);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Tabla de Pagos - Préstamo ' . $infoPrestamo['nro_prestamo'];
            
            // Generar HTML de la tabla
            $html = self::generarHTMLTablaPagos($infoPrestamo, $historialPagos);
            $mail->Body = $html;

            $mail->send();
            return "ok";
        } catch (Exception $e) {
            // Log detallado del error para debugging
            error_log("Error SMTP detallado: " . $e->getMessage());
            error_log("Configuración SMTP usada: " . json_encode($config_correo));
            return "Error al enviar correo: " . $e->getMessage();
        }
    }

    /*===================================================================*/
    // GENERAR HTML DE LA TABLA DE PAGOS
    /*===================================================================*/
    private static function generarHTMLTablaPagos($infoPrestamo, $historialPagos)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Tabla de Pagos</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .info-prestamo { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
                .tabla-pagos { width: 100%; border-collapse: collapse; }
                .tabla-pagos th, .tabla-pagos td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .tabla-pagos th { background-color: #007bff; color: white; }
                .estado-pagada { color: green; font-weight: bold; }
                .estado-pendiente { color: red; font-weight: bold; }
                .estado-parcial { color: orange; font-weight: bold; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class="header">
                <h2>TABLA DE PAGOS</h2>
                <h3>Préstamo N° ' . $infoPrestamo['nro_prestamo'] . '</h3>
            </div>
            
            <div class="info-prestamo">
                <h4>Información del Préstamo</h4>
                <p><strong>Cliente:</strong> ' . $infoPrestamo['cliente_nombres'] . '</p>
                <p><strong>DNI:</strong> ' . $infoPrestamo['cliente_dni'] . '</p>
                <p><strong>Monto del Préstamo:</strong> ' . $infoPrestamo['moneda_simbolo'] . ' ' . number_format($infoPrestamo['pres_monto'], 2) . '</p>
                <p><strong>Interés:</strong> ' . $infoPrestamo['pres_interes'] . '%</p>
                <p><strong>Cuotas:</strong> ' . $infoPrestamo['pres_cuotas'] . '</p>
                <p><strong>Monto por Cuota:</strong> ' . $infoPrestamo['moneda_simbolo'] . ' ' . number_format($infoPrestamo['pres_monto_cuota'], 2) . '</p>
                <p><strong>Monto Total:</strong> ' . $infoPrestamo['moneda_simbolo'] . ' ' . number_format($infoPrestamo['pres_monto_total'], 2) . '</p>
                <p><strong>Forma de Pago:</strong> ' . $infoPrestamo['fpago_descripcion'] . '</p>
                <p><strong>Fecha de Registro:</strong> ' . $infoPrestamo['fecha_registro'] . '</p>
            </div>
            
            <table class="tabla-pagos">
                <thead>
                    <tr>
                        <th>Cuota N°</th>
                        <th>Fecha</th>
                        <th>Monto</th>
                        <th>Saldo Pendiente</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>';
                
        foreach ($historialPagos as $cuota) {
            $estadoClase = '';
            if ($cuota['pdetalle_estado_cuota'] == 'pagada') {
                $estadoClase = 'estado-pagada';
            } elseif ($cuota['pdetalle_estado_cuota'] == 'parcialmente_pagada') {
                $estadoClase = 'estado-parcial';
            } else {
                $estadoClase = 'estado-pendiente';
            }
            
            $html .= '
                    <tr>
                        <td>' . $cuota['pdetalle_nro_cuota'] . '</td>
                        <td>' . $cuota['pdetalle_fecha'] . '</td>
                        <td>' . $cuota['moneda_simbolo'] . ' ' . number_format($cuota['pdetalle_monto_cuota'], 2) . '</td>
                        <td>' . $cuota['moneda_simbolo'] . ' ' . number_format($cuota['pdetalle_saldo_cuota'], 2) . '</td>
                        <td class="' . $estadoClase . '">' . strtoupper($cuota['pdetalle_estado_cuota']) . '</td>
                    </tr>';
        }
        
        $html .= '
                </tbody>
            </table>
            
            <div class="footer">
                <p>Este documento fue generado automáticamente por el Sistema de Préstamos.</p>
                <p>Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /*===================================================================*/
    // OBTENER CONFIGURACIÓN DE CORREO DESDE BD O FALLBACK
    /*===================================================================*/
    private static function obtenerConfiguracionCorreo()
    {
        try {
            // Intentar obtener configuración desde la base de datos
            $stmt = Conexion::conectar()->prepare("SELECT config_correo, confi_razon FROM empresa LIMIT 1");
            $stmt->execute();
            $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $correo_empresa = $empresa['config_correo'] ?? 'siprestaitsolutions@gmail.com';
            $nombre_empresa = $empresa['confi_razon'] ?? 'Sistema de Préstamos';
            
            // Verificar si hay configuración SMTP personalizada
            $stmt = Conexion::conectar()->prepare("SHOW TABLES LIKE 'configuracion_smtp'");
            $stmt->execute();
            $tabla_smtp_existe = $stmt->fetch();
            
            if ($tabla_smtp_existe) {
                $stmt = Conexion::conectar()->prepare("SELECT * FROM configuracion_smtp WHERE activo = 1 LIMIT 1");
                $stmt->execute();
                $config_smtp = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($config_smtp) {
                    return [
                        'host' => $config_smtp['smtp_host'],
                        'smtp_auth' => (bool)$config_smtp['smtp_auth'],
                        'username' => $config_smtp['smtp_username'],
                        'password' => $config_smtp['smtp_password'],
                        'encryption' => $config_smtp['smtp_encryption'],
                        'port' => (int)$config_smtp['smtp_port'],
                        'nombre_empresa' => $nombre_empresa
                    ];
                }
            }
            
        } catch (Exception $e) {
            error_log("Error al obtener configuración de correo: " . $e->getMessage());
        }
        
        // Configuración por defecto (Gmail SMTP)
        return [
            'host' => 'smtp.gmail.com',
            'smtp_auth' => true, // Habilitar autenticación para Gmail
            'username' => 'siprestaitsolutions@gmail.com', // Email de autenticación
            'password' => 'vnuk vlrs tiog srsc', // App Password de Gmail
            'encryption' => 'tls', // Usar TLS para Gmail
            'port' => 587,
            'nombre_empresa' => $nombre_empresa ?? 'Sistema de Préstamos'
        ];
    }
}
