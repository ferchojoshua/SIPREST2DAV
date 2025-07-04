<?php


class ConfiguracionControlador {

    /*===================================================================*/
    //OBTENER TODOS LOS DATOS DE LA EMPRESA 
    /*===================================================================*/
    static public function ctrObtenerDataEmpresa(){
        $DataEmpresa = ConfiguracionModelo::mdlObtenerDataEmpresa();
        return $DataEmpresa;
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