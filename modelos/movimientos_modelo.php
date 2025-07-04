<?php

require_once "conexion.php";

class MovimientosModelo
{

    /*===================================================================*/
     // LISTAR MOVIMIENTOS EN DATATABLE CON PROCEDURE
     /*===================================================================*/
     static public function mdlListarMovimientos()
     {
         $smt = Conexion::conectar()->prepare('call SP_LISTAR_MOVIMIENTOS()');
         $smt->execute();
         return $smt->fetchAll();
     }


     

    /*=============================================
    REGISTRAR MOVIMIENTOS
    =============================================*/
    static public function mdlRegistrarMovi($movi_tipo, $movi_descripcion, $movi_monto, $caja_id)
    {
        try {
            //$fecha = date('Y-m-d');
            $stmt = Conexion::conectar()->prepare("INSERT INTO movimientos ( movi_tipo, 
                                                                                movi_descripcion, 
                                                                                movi_monto, 
                                                                                movi_fecha,movi_caja, caja_id )
                                                                    VALUES(:movi_tipo,
                                                                            :movi_descripcion,
                                                                            :movi_monto, 
                                                                            CURRENT_TIMESTAMP(), 'VIGENTE', :caja_id)");

            $stmt->bindParam(":movi_tipo", $movi_tipo, PDO::PARAM_STR);
            $stmt->bindParam(":movi_descripcion", $movi_descripcion, PDO::PARAM_STR);
            $stmt->bindParam(":movi_monto", $movi_monto, PDO::PARAM_STR);
            $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
           
            if ($stmt->execute()) {
                $resultado = "ok";
            } else {
                $resultado = "error";
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        return $resultado;

        $stmt = null;
    }


    

    /*=============================================
    ACTUALIZAR DATOS DEL MOVIMIENTOS
    =============================================*/
    static public function mdlActualizarMovi($table, $data, $id, $nameId)
    {

        $set = "";

        foreach ($data as $key => $value) {
            $set .= $key . " = :" . $key . ","; //DEPENDE DEL ARRAY QUE VIENE DEL AJAX
        }

        $set = substr($set, 0, -1); //QUITA LA COMA

        $stmt = Conexion::conectar()->prepare("UPDATE $table SET $set WHERE $nameId = :$nameId");

        foreach ($data as $key => $value) {
            $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
        }

        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {

            return Conexion::conectar()->errorInfo();
        }
    }



    /*=============================================
       ELIMINAR DATOS DE MOVIMIENTOS
    =============================================*/

    static public function mdlEliminarMovi($movimientos_id)
    {

        $stmt = Conexion::conectar()->prepare('call SP_ELIMINAR_MOVIMIENTO(:movimientos_id)');
        $stmt->bindParam(":movimientos_id", $movimientos_id, PDO::PARAM_INT);

        $stmt->execute();

       if ($row = $stmt->fetchColumn()) {
           return $row;
       }
    }


}