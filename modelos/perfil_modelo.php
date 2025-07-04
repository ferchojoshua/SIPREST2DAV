<?php

require_once "conexion.php";

class PerfilModelo{

    /*=============================================
    Peticion LIST PARA DATATBLE
    =============================================*/
    static public function mdlObtenerPerfiles(){

        $stmt = Conexion::conectar()->prepare("select p.id_perfil,
                                                        p.descripcion,
                                                        p.estado,
                                                        ' ' as opciones
                                                from perfiles p
                                                order by p.id_perfil");

        $stmt -> execute();

        return $stmt->fetchAll();

    }



    /*=============================================
    Peticion INSERT para REGISTRAR DATOS A LA BASE
    =============================================*/
    static public function mdlRegistrarPerfil($descripcion) {
        try {
            //$fecha = date('Y-m-d');
            $stmt = Conexion::conectar()->prepare("INSERT INTO perfiles(descripcion , estado
                                                                           ) 
                                                                VALUES (:descripcion,'1')");

            $stmt->bindParam(":descripcion", $descripcion, PDO::PARAM_STR);
        
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
    Peticion UPDATE: para ACTUALIZAR DATOS
    =============================================*/
    static public function mdlActualizarPerfil($table, $data, $id, $nameId)
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
    Peticion DELETE: PARA ELIMINAR DATOS DE LA TABLA POR ID
    =============================================*/

    static public function mdlEliminarPerfil($table, $id, $nameId)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";;
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }







}