<?php
require_once __DIR__ . "/conexion.php";

class ModeloGruposReportes {

    /*=============================================
    CREAR GRUPO
    =============================================*/
    static public function mdlCrearGrupo($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(grupo_nombre, grupo_descripcion) VALUES (:nombre, :descripcion)");
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    MOSTRAR GRUPOS
    =============================================*/
    static public function mdlMostrarGrupos($tabla, $item, $valor) {
        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY grupo_nombre ASC");
            $stmt->execute();
            return $stmt->fetchAll();
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    EDITAR GRUPO
    =============================================*/
    static public function mdlEditarGrupo($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET grupo_nombre = :nombre, grupo_descripcion = :descripcion WHERE grupo_id = :id");
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datos["descripcion"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    BORRAR GRUPO
    =============================================*/
    static public function mdlBorrarGrupo($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE grupo_id = :id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    MOSTRAR MIEMBROS DE UN GRUPO
    =============================================*/
    static public function mdlMostrarMiembros($tabla, $item, $valor) {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item ORDER BY miembro_email ASC");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    /*=============================================
    AGREGAR MIEMBRO
    =============================================*/
    static public function mdlAgregarMiembro($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla (grupo_id, miembro_email, miembro_nombre) VALUES (:grupo_id, :email, :nombre)");
        $stmt->bindParam(":grupo_id", $datos["grupo_id"], PDO::PARAM_INT);
        $stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
        $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }

    /*=============================================
    BORRAR MIEMBRO
    =============================================*/
    static public function mdlBorrarMiembro($tabla, $datos) {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE miembro_id = :id");
        $stmt->bindParam(":id", $datos, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt->closeCursor();
        $stmt = null;
    }
} 