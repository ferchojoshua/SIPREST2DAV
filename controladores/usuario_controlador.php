<?php

class UsuarioControlador{


    /*===================================================================*/
    //INICIO DE SESSION
    /*===================================================================*/
    static public function login($usuario, $password){
          
            // Intentar primero el método complejo original
            $respuesta = UsuarioModelo::mdlIniciarSesion($usuario, $password);     

            // Si falla (respuesta vacía o null), intentar con el método simplificado
            if(empty($respuesta) || $respuesta === null){
                $respuesta = UsuarioModelo::mdlIniciarSesionSimple($usuario, $password);
            }

            return $respuesta;

    }



    /*===================================================================*/
    //OBTENEMOS LOS MENUS - PADRES
    /*===================================================================*/
    static public function ctrObtenerMenuUsuario($id_usuario){

        $menuUsuario = UsuarioModelo::mdlObtenerMenuUsuario($id_usuario);

        return $menuUsuario;
    }


    /*===================================================================*/
    //OBTENEMOS LOS SUBMENUS - HIJOS
    /*===================================================================*/
    static public function ctrObtenerSubMenuUsuario($idMenu,$id_perfil_usuario){

        $subMenuUsuario = UsuarioModelo::mdlObtenerSubMenuUsuario($idMenu,$id_perfil_usuario);
        
        return $subMenuUsuario ;
    
    }


    /*===================================================================*/
    //LISTAR USUARIOS CON PROCEDURE
    /*===================================================================*/
    static public function ctrListarUsuarios()
    {
        $usuario = UsuarioModelo::mdlListarUsuarios();
        return $usuario;
    }


    /*===================================================================*/
    //LISTAR PERFILES EN COMBOBOX
    /*===================================================================*/
    static public function ctrListarSelectPerfiles()
    {
        try {
            $perfiles = UsuarioModelo::mdlListarSelectPerfiles();
            return $perfiles;
        } catch (Exception $e) {
            error_log("Error en ctrListarSelectPerfiles: " . $e->getMessage());
            return false;
        }
    }



    /*===================================================================*/
     //REGISTRAR USUARIOS
     /*===================================================================*/
     static public function ctrRegistrarUsuario($nombre_usuario, $apellido_usuario, $usuario, $clave, $id_perfil_usuario, $sucursal_id)
     {
         $registroUsuario = UsuarioModelo::mdlRegistrarUsuario($nombre_usuario, $apellido_usuario, $usuario, $clave, $id_perfil_usuario, $sucursal_id);
         return $registroUsuario;
     }


     /*===================================================================*/
     //ACTUALIZAR USUARIOS
     /*===================================================================*/
    static public function ctrActualizarUsuario($table, $data, $id, $nameId)
    {
        $respuesta = UsuarioModelo::mdlActualizarUsuario($table, $data, $id, $nameId);
        return $respuesta;
    }


    static public function ctrObtenerColectores()
    {
        $respuesta = UsuarioModelo::mdlObtenerColectores();
        return $respuesta;
    }


    /*===================================================================*/
     //ELIMINAR USUARIOS
     /*===================================================================*/
     static public function ctrEliminarUsuario($table, $id, $nameId)
     {
 
         $respuesta = UsuarioModelo::mdlEliminarUsuario($table, $id, $nameId);
         return $respuesta;
     }


     /*===================================================================*/
     //CAMBIAR CLAVE
     /*===================================================================*/
    static public function ctrActualizarClaveUsuario($table, $data, $id, $nameId)
    {
        $respuesta = UsuarioModelo::mdlActualizarClaveUsuario($table, $data, $id, $nameId);
        return $respuesta;
    }







}