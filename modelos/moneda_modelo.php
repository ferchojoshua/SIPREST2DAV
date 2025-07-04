<?php

require_once "conexion.php";

class MonedaModelo
{


    /*===================================================================*/
    // LISTAR MONEDAS EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    static public function mdlListarMoneda()
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_MONEDAS_TABLE()');
        $smt->execute();
        return $smt->fetchAll();
    }



    /*=============================================
    REGISTRAR MONEDA
    =============================================*/
    static public function mdlRegistrarMoneda($moneda_nombre, $moneda_abrevia, $moneda_simbolo, $moneda_Descripcion)
    {
        try {
            //$fecha = date('Y-m-d');
            $stmt = Conexion::conectar()->prepare("INSERT INTO moneda  (moneda_nombre, 
                                                                          moneda_abrevia,
                                                                          moneda_simbolo,
                                                                          moneda_Descripcion ) 
                                                                VALUES (:moneda_nombre, 
                                                                        :moneda_abrevia,
                                                                        :moneda_simbolo,
                                                                        :moneda_Descripcion)");

            $stmt->bindParam(":moneda_nombre", $moneda_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":moneda_abrevia", $moneda_abrevia, PDO::PARAM_STR);
            $stmt->bindParam(":moneda_simbolo", $moneda_simbolo, PDO::PARAM_STR);
            $stmt->bindParam(":moneda_Descripcion", $moneda_Descripcion, PDO::PARAM_STR);

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
    ACTUALIZAR DATOS DEL MONEDA
    =============================================*/
    static public function mdlActualizarMoneda($table, $data, $id, $nameId)
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
    ELIMINAR DATOS DE LA  MONEDA
    =============================================*/
    static public function mdlEliminarMoneda($table, $id, $nameId)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";;
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }


    /*===================================================================*/
    //SELECT MONEDAS EN COMBO
    /*===================================================================*/
    static public function mdlListarSelectMoneda()
    {

        $stmt = Conexion::conectar()->prepare("SELECT moneda_id, 
                                                    CONCAT_WS(' | ',moneda_nombre,moneda_abrevia,moneda_simbolo) as moneda
                                                FROM
                                                    moneda			
                                                ORDER BY moneda_id asc");

        $stmt->execute();

        return $stmt->fetchAll();
    }
}
