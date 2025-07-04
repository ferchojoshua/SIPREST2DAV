<?php

class ModuloControlador{

    /*===================================================================*/
    //OBTENER MODULOS
    /*===================================================================*/
    static public function ctrObtenerModulos(){
        $modulos = ModuloModelo::mdlObtenerModulos();
        return $modulos;
    }


    /*===================================================================*/
     //MODULOS SEGUN EL PERFIL - ADMIN O VENDE
    /*===================================================================*/
    static public function ctrObtenerModulosPorPerfil($id_perfil){
        $modulosPerfil = ModuloModelo::mdlObtenerModulosPorPerfil($id_perfil);
        return $modulosPerfil;
    }


    /*===================================================================*/
   //LISTA DE MODULOS PARA EL DATATABLE
   /*===================================================================*/
    static public function ctrObtenerListaModulos(){
        $modulos = ModuloModelo::mdlObtenerListaModulos();
        return $modulos;
    }


    /*==============================================================
    FNC PARA REORGANIZAR LOS MODULOS DEL SISTEMA
    ==============================================================*/
    static public function ctrReorganizarModulos($modulos_ordenados){
        $modulosOrdenados = ModuloModelo::mdlReorganizarModulos($modulos_ordenados);
        return $modulosOrdenados;
    }


    /*===================================================================*/
    //REGISTRAR MODULOS
    /*===================================================================*/
    static public function ctrRegistrarModulos($modulo, $vista, $icon_menu)
    {
        $registroModulos = ModuloModelo::mdlRegistrarModulos($modulo, $vista, $icon_menu);
        return $registroModulos;
    }



    /*===================================================================*/
    //ACTUALIZAR MODULOS
    /*===================================================================*/
    static public function ctrActualizarModulos($table, $data, $id, $nameId)
    {
        $respuesta = ModuloModelo::mdlActualizarModulos($table, $data, $id, $nameId);
        return $respuesta;
    }


    /*===================================================================*/
    //ELIMINAR MODULOS
    /*===================================================================*/
    static public function ctrEliminarModulo($table, $id, $nameId)
    {
        $respuesta = ModuloModelo::mdlEliminarModulo($table, $id, $nameId);
        return $respuesta;
    }

}