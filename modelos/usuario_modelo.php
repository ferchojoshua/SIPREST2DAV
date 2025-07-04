<?php

require_once "conexion.php";


class UsuarioModelo
{

    /*===================================================================*/
    //PARA EL INICIO DE SESION
    /*===================================================================*/
    static public function mdlIniciarSesion($usuario, $password)
    {
        $logFile = fopen("log.txt", 'a') or die("Error creando archivo");
        fwrite($logFile, date("d/m/Y H:i:s"). '  ' . $usuario.'-'.$password."\n") or die("Error escribiendo en el archivo");
        fclose($logFile);

        $stmt = Conexion::conectar()->prepare("select *
                                                from usuarios u 
                                                inner join perfiles p
                                                on u.id_perfil_usuario = p.id_perfil
                                                inner join perfil_modulo pm
                                                on pm.id_perfil = u.id_perfil_usuario
                                                inner join modulos m
                                                on m.id = pm.id_modulo
                                                where u.usuario = :usuario
                                                and u.clave = :password
                                                and u.estado = 1
                                                and vista_inicio = 1");


        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);


        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }


    /*===================================================================*/
    //PARA EL INICIO DE SESION - VERSION SIMPLIFICADA PARA RESET
    /*===================================================================*/
    static public function mdlIniciarSesionSimple($usuario, $password)
    {
        $logFile = fopen("log.txt", 'a') or die("Error creando archivo");
        fwrite($logFile, date("d/m/Y H:i:s"). '  ' . $usuario.'-'.$password."\n") or die("Error escribiendo en el archivo");
        fclose($logFile);

        // Primero verificar usuario y contraseña
        $stmt = Conexion::conectar()->prepare("SELECT u.*, p.descripcion as perfil_descripcion
                                                FROM usuarios u 
                                                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                                                WHERE u.usuario = :usuario
                                                AND u.clave = :password
                                                AND u.estado = 1");

        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR);
        $stmt->execute();
        
        $user_data = $stmt->fetchAll(PDO::FETCH_CLASS);
        
        if (count($user_data) > 0) {
            // Si el usuario existe, buscar su vista de inicio
            $stmt2 = Conexion::conectar()->prepare("SELECT m.vista
                                                    FROM usuarios u 
                                                    INNER JOIN perfil_modulo pm ON pm.id_perfil = u.id_perfil_usuario
                                                    INNER JOIN modulos m ON m.id = pm.id_modulo
                                                    WHERE u.id_usuario = :id_usuario
                                                    AND vista_inicio = 1
                                                    LIMIT 1");
            
            $stmt2->bindParam(":id_usuario", $user_data[0]->id_usuario, PDO::PARAM_STR);
            $stmt2->execute();
            
            $vista_data = $stmt2->fetch(PDO::FETCH_ASSOC);
            
            if ($vista_data) {
                // Agregar la vista al objeto usuario
                $user_data[0]->vista = $vista_data['vista'];
            } else {
                // Vista por defecto si no tiene configurada
                $user_data[0]->vista = 'dashboard.php';
            }
        }

        return $user_data;
    }


    /*===================================================================*/
    //OBTENEMOS LOS MENUS -  PADRES
    /*===================================================================*/
    static public function mdlObtenerMenuUsuario($id_usuario)
    {

        $stmt = Conexion::conectar()->prepare("SELECT m.id,
                                                    m.modulo,
                                                    m.icon_menu,
                                                    m.vista,
                                                    pm.vista_inicio
                                                    from usuarios u inner join perfiles p on u.id_perfil_usuario = p.id_perfil
                                                    inner join perfil_modulo pm on pm.id_perfil = p.id_perfil
                                                    inner join modulos m on m.id = pm.id_modulo
                                                    where u.id_usuario = :id_usuario
                                                    and (m.padre_id is null or m.padre_id = 0)
                                                    order by m.orden");

        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }



    /*===================================================================*/
    //OBTENEMOS LOS SUBMENUS -  HIJOS
    /*===================================================================*/
    static public function mdlObtenerSubMenuUsuario($idMenu,$id_usuario)
    {

        $stmt = Conexion::conectar()->prepare("SELECT m.id,m.modulo,m.icon_menu,m.vista,pm.vista_inicio
                                                from usuarios u inner join perfiles p on u.id_perfil_usuario = p.id_perfil
                                                inner join perfil_modulo pm on pm.id_perfil = p.id_perfil
                                                inner join modulos m on m.id = pm.id_modulo
                                                where u.id_usuario = :id_usuario
                                                and m.padre_id = :idMenu
                                                order by m.orden");

        $stmt->bindParam(":idMenu", $idMenu, PDO::PARAM_STR);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }



    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlListarUsuarios()
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_USUARIOS()');
        $smt->execute();
        return $smt->fetchAll();

        // $stmt = Conexion::conectar()->prepare("SELECT
        //                                                 id_categoria, 
        //                                                 nombre_categoria, 
        //                                                 aplica_peso as medida, 
        //                                                 DATE(fecha_creacion_categoria) as fecha_creacion_categoria, 
        //                                                 fecha_actualizacion_categoria,
        //                                                 '' as opciones
        //                                             FROM
        //                                                 categorias 
        //                                                 ORDER BY id_categoria DESC");
        // $stmt->execute();
        // return $stmt ->fetchAll();
    }

    /*=============================================
    Peticion SELECT: PARA MOSTRAR EN COMBO DE USUARIO
    =============================================*/
    static public function mdlListarSelectPerfiles()
    {

        $stmt = Conexion::conectar()->prepare("SELECT  id_perfil, descripcion
                                                FROM perfiles 
                                                where estado = 1
                                                order BY id_perfil asc");
        $stmt->execute();
        return $stmt->fetchAll();
    }



    /*=============================================
    REGISTRAR USUARIO
    =============================================*/
    static public function mdlRegistrarUsuario($nombre_usuario, $apellido_usuario, $usuario, $clave, $id_perfil_usuario)
    {
        try {
            //$fecha = date('Y-m-d');
            $stmt = Conexion::conectar()->prepare("INSERT INTO usuarios(nombre_usuario, 
                                                                          apellido_usuario,
                                                                          usuario,
                                                                          clave ,
                                                                          id_perfil_usuario, estado) 
                                                                VALUES (:nombre_usuario, 
                                                                        :apellido_usuario,
                                                                        :usuario,
                                                                        :clave,
                                                                        :id_perfil_usuario, '1')");

            $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(":apellido_usuario", $apellido_usuario, PDO::PARAM_STR);
            $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->bindParam(":clave", $clave, PDO::PARAM_STR);
            $stmt->bindParam(":id_perfil_usuario", $id_perfil_usuario, PDO::PARAM_STR);

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
    ACTUALIZAR DATOS DEL USUARIO
    =============================================*/
    static public function mdlActualizarUsuario($table, $data, $id, $nameId)
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
    ELIMINAR DATOS DEL USUARIO
    =============================================*/

    static public function mdlEliminarUsuario($table, $id, $nameId)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";;
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }


     /*=============================================
    ACTUALIZAR DATOS DEL USUARIO
    =============================================*/
    static public function mdlActualizarClaveUsuario($table, $data, $id, $nameId)
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
}
