<?php
// No hay require en este archivo, solo aseguramos que si se agrega alguno, se use __DIR__

class Conexion {

    static public function conectar(){

        try {
           $conn = new PDO("mysql:host=localhost;dbname=credicrece;charset=utf8",
                          "root",
                          "",
                          array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
           $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
           return $conn;
        } catch (PDOException $e) {
            // En lugar de hacer echo, lanzamos la excepción para que pueda ser manejada más arriba.
            error_log("Error de conexión a BD: " . $e->getMessage());
            throw new PDOException('Fallo la conexion: '.$e->getMessage());
        }
    }

}