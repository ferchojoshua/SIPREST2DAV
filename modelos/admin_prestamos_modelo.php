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
}
