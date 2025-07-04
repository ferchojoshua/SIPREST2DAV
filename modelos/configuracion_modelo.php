<?php

require_once "conexion.php";



class ConfiguracionModelo
{
   

    /*===================================================================*/
    //OBTENER TODOS LOS DATOS DE LA EMPRESA 
    /*===================================================================*/
    static public function mdlObtenerDataEmpresa()
    {
        $smt = Conexion::conectar()->prepare('call SP_OBTENER_DATA_EMPRESA()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }


    
     /*=============================================
    Peticion UPDATE: para ACTUALIZAR DATOS
    =============================================*/
    static public function mdlActualizarConfiguracion($table, $data, $id, $nameId)
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


    /*===================================================================*/
    //OBTENER CORRELATIVO
    /*===================================================================*/
    static public function mdlObtenerCorrelativo()
    {
        $smt = Conexion::conectar()->prepare('call SP_OBTENER_NRO_CORRELATIVO()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }

}