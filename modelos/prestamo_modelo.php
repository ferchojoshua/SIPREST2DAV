<?php

require_once "conexion.php";


class PrestamoModelo
{

    /*===================================================================*/
    //REGISTRAR PRESTAMO
    /*===================================================================*/
    static public function mdlRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo)
    {
        //print_r($nro_boleta);
        $fecha = date('Y-m-d H:i:s');
        // date("d/m/Y H:i:s"). 
        try {
            //INSERTAMOS LA CABECERA
            $stmt = Conexion::conectar()->prepare("INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, pres_monto, pres_cuotas, pres_interes, fpago_id, moneda_id, pres_f_emision, pres_monto_cuota, pres_monto_interes, pres_monto_total, pres_estado, pres_estatus, id_usuario, pres_aprobacion, pres_fecha_registro, pres_estado_caja, caja_id, tipo_calculo) 
                                                VALUES (:nro_prestamo, :cliente_id, :pres_monto, :pres_cuotas, :pres_interes, :fpago_id, :moneda_id, :pres_f_emision, :pres_monto_cuota, :pres_monto_interes, :pres_monto_total, 'Pendiente', '1', :id_usuario, 'pendiente', CURRENT_TIME(), 'VIGENTE', :caja_id, :tipo_calculo)");

            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto", $pres_monto, PDO::PARAM_STR);
            $stmt->bindParam(":pres_cuotas", $pres_cuotas, PDO::PARAM_STR);
            $stmt->bindParam(":pres_interes", $pres_interes, PDO::PARAM_STR);
            $stmt->bindParam(":fpago_id", $fpago_id, PDO::PARAM_STR);
            $stmt->bindParam(":moneda_id", $moneda_id, PDO::PARAM_STR);
            $stmt->bindParam(":pres_f_emision", $pres_f_emision, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto_cuota", $pres_monto_cuota, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto_interes", $pres_monto_interes, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto_total", $pres_monto_total, PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);
            $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
            $stmt->bindParam(":tipo_calculo", $tipo_calculo, PDO::PARAM_STR);

            if ($stmt->execute()) {

                $stmt = null; //colocamos en null porque se va a usar de nuevo para actualizar correlativo

                //ACTUALIZAMOS EL CORRELATIVO
                $stmt = Conexion::conectar()->prepare("UPDATE empresa SET confi_correlativo = LPAD(confi_correlativo + 1,8,'0')");

                if ($stmt->execute()) {
                    //$stmt = Conexion::conectar()->prepare("UPDATE clientes SET cliente_estado_prestamo =  'con prestamo' where cliente_id = :cliente_id");
                    $stmt = Conexion::conectar()->prepare('call SP_ACTUALIZAR_ESTADO_CLIENTE_PRESTAMO(:cliente_id)');
                    $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        $resultado = "Se registro el prestamo correctamente.";
                    } else {
                        $resultado = "Error rrrr";
                    }
                }
            } else {
                $resultado = "Error al registrar el prestamo";
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }
        return $resultado;

        $stmt = null;
    }




    /*===================================================================*/
    //REGISTRAR DETALLE DEL PRESTAMO
    /*===================================================================*/
    static public function mdlRegistrarPrestamoDetalle($nro_prestamo, $array_cuota, $array_monto, $array_fecha)
    {
        try {
            // Convertir las cadenas de arrays a arrays PHP si no lo están ya
            $cuotas = explode(",", $array_cuota);
            $montos = explode(",", $array_monto);
            $fechas = explode(",", $array_fecha);

            $conn = Conexion::conectar();
            $conn->beginTransaction();

            for ($i = 0; $i < count($cuotas); $i++) {
                $pdetalle_nro_cuota = trim($cuotas[$i]);
                $pdetalle_monto_cuota = trim($montos[$i]);
                $pdetalle_fecha_cuota = trim($fechas[$i]);

                $stmt = $conn->prepare("INSERT INTO prestamo_detalle(nro_prestamo, pdetalle_nro_cuota, pdetalle_monto_cuota, pdetalle_fecha, pdetalle_estado_cuota, pdetalle_liquidar, pdetalle_caja, pdetalle_aprobacion, pdetalle_saldo_cuota) 
                                                    VALUES(:nro_prestamo,:pdetalle_nro_cuota,:pdetalle_monto_cuota,:pdetalle_fecha, 'pendiente', '0', 'VIGENTE', 'pendiente', :pdetalle_saldo_cuota)");

                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_monto_cuota", $pdetalle_monto_cuota, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_fecha", $pdetalle_fecha_cuota, PDO::PARAM_STR);
                $stmt->bindParam(":pdetalle_saldo_cuota", $pdetalle_monto_cuota, PDO::PARAM_STR);

                if (!$stmt->execute()) {
                    $conn->rollBack();
                    return "error al guardar detalle";
                }
            }

            $conn->commit();
            $resultado = "ok";

        } catch (Exception $e) {
            if (isset($conn) && $conn->inTransaction()) {
                $conn->rollBack();
            }
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        return $resultado;
    }




    /*===================================================================*/
    //VALIDAD SI HAY MONTO DISPONIBLE EN CAJA
    /*===================================================================*/
    static public function mdlValidarMontoPrestamo()
    {
        $smt = Conexion::conectar()->prepare('call SP_ALERTA_PRESTAMO_CAJA()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }

    /*===================================================================*/
    //OBTENER TIPOS DE CALCULO
    /*===================================================================*/
    static public function mdlObtenerTiposCalculo()
    {
        $stmt = Conexion::conectar()->prepare("SELECT tipo_calculo_id as id, 
                                                    tipo_calculo_nombre as nombre, 
                                                    tipo_calculo_descripcion as descripcion 
                                             FROM tipos_calculo_interes 
                                             WHERE tipo_calculo_estado = '1' 
                                             ORDER BY tipo_calculo_id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
