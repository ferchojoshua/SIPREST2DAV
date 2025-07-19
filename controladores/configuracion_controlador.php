<?php
require_once '../modelos/configuracion_modelo.php';


class ConfiguracionControlador {

    /*===================================================================*/
    //OBTENER TODOS LOS DATOS DE LA EMPRESA 
    /*===================================================================*/
    static public function ctrObtenerDataEmpresa(){
        $respuesta = ConfiguracionModelo::mdlObtenerDataEmpresa();
        return $respuesta;
    }


    /*===================================================================*/
    //ACTUALIZAR DATOS DE LA EMPRESA
    /*===================================================================*/
     static public function ctrActualizarConfiguracion($table, $data, $id, $nameId)
     {
         $respuesta = ConfiguracionModelo::mdlActualizarConfiguracion($table, $data, $id, $nameId);
         return $respuesta;
     }




    /*===================================================================*/
    //OBTENER CORRELATIVO
    /*===================================================================*/
     static public function ctrObtenerCorrelativo(){
        $correlativo = ConfiguracionModelo::mdlObtenerCorrelativo();
        return $correlativo;
    }

}