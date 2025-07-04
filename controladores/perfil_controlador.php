<?php

class PerfilControlador{

    /*===================================================================*/
    //OBTENER PERFILES
    /*===================================================================*/
    static public function ctrObtenerPerfiles(){

        $modulos = PerfilModelo::mdlObtenerPerfiles();
        return $modulos;
    }


    /*===================================================================*/
    //REGISTRAR PERFIL
    /*===================================================================*/
    static public function ctrRegistrarPerfil($descripcion)
    {
        $registroPerfil = PerfilModelo::mdlRegistrarPerfil($descripcion);
        return $registroPerfil;
    }


    /*===================================================================*/
    //ACTUALIZAR PERFIL
    /*===================================================================*/
    static public function ctrActualizarPerfil($table, $data, $id, $nameId)
    {
        $respuesta = PerfilModelo::mdlActualizarPerfil($table, $data, $id, $nameId);
        return $respuesta;
    }


    /*===================================================================*/
    //ELIMINAR PERFIL
    /*===================================================================*/
    static public function ctrEliminarPerfil($table, $id, $nameId)
    {

        $respuesta = PerfilModelo::mdlEliminarPerfil($table, $id, $nameId);

        return $respuesta;
    }



    

}