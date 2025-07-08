<?php

require_once "conexion.php";



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
                                                            -- DATE(pc.pres_fecha_registro) as fecha,
                                                            DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha,
                                                            pc.pres_aprobacion as estado,
                                                            '' as opciones,
                                                            pc.pres_monto_cuota,
															pc.pres_monto_interes,
															pc.pres_monto_total,
															pc.pres_cuotas_pagadas          
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

            return $stmt->fetchAll();
        } catch (Exception $e) {
            return 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }


        $stmt = null;
    }


    /*===================================================================*/
    //VER DETALLE DL PRESTAMO EN MODAL
    /*===================================================================*/
    static public function mdlDetallePrestamo($nro_prestamo)
    {
        // $stmt = Conexion::conectar()->prepare('call SP_VER_DETALLE_PRESTAMO(:nro_prestamo)');
        $stmt = Conexion::conectar()->prepare("SELECT
                                                    pd.pdetalle_id,
                                                    pd.nro_prestamo,
                                                    pd.pdetalle_nro_cuota,
                                                    DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') AS pdetalle_fecha,
                                                    pd.pdetalle_monto_cuota,
                                                    pd.pdetalle_saldo_cuota,
                                                    pd.pdetalle_estado_cuota,
                                                    mo.moneda_simbolo,
                                                    mo.moneda_nombre
                                                FROM
                                                    prestamo_detalle pd
                                                INNER JOIN
                                                    prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                                                INNER JOIN
                                                    moneda mo ON pc.moneda_id = mo.moneda_id
                                                WHERE
                                                    pd.nro_prestamo = :nro_prestamo");
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
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
    // REGISTRAR ABONO DE CUOTA
    /*===================================================================*/
    static public function mdlRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT pdetalle_monto_cuota, pdetalle_saldo_cuota FROM prestamo_detalle WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :pdetalle_nro_cuota");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);
            $stmt->execute();
            $cuota = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cuota) {
                return "error_cuota_no_encontrada";
            }

            $saldo_actual = (float) $cuota["pdetalle_saldo_cuota"];
            $nuevo_saldo = $saldo_actual - (float) $monto_a_abonar;

            $estado_cuota = 'parcialmente_pagada';
            if ($nuevo_saldo <= 0.001) { // Usar un pequeño margen para comparar flotantes/decimales
                $estado_cuota = 'pagada';
                $nuevo_saldo = 0; // Asegurar que el saldo no sea negativo si se paga completo o un poco más
            }

            // Actualizar el saldo y el estado de la cuota
            $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_saldo_cuota = :pdetalle_saldo_cuota, pdetalle_estado_cuota = :pdetalle_estado_cuota, pdetalle_fecha_registro = CURRENT_TIME() WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :pdetalle_nro_cuota");
            $stmt->bindParam(":pdetalle_saldo_cuota", $nuevo_saldo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_estado_cuota", $estado_cuota, PDO::PARAM_STR);
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Si la cuota se pagó completamente, verificar si el préstamo está finalizado
                if ($estado_cuota == 'pagada') {
                    $stmt = null;
                    $stmt = Conexion::conectar()->prepare('call SP_CAMBIAR_ESTADO_CABECERA(:nro_prestamo)');
                    $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                    $stmt->execute();
                }
                return "ok";
            } else {
                return "error_actualizacion_cuota";
            }
        } catch (Exception $e) {
            return 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        $stmt = null;
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
}
