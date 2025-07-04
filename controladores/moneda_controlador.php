<?php

class MonedaControlador
{

    /*===================================================================*/
    //LISTAR MONEDA CON PROCEDURE  EN DATATABLE
    /*===================================================================*/
    static public function ctrListarMoneda()
    {
        $moneda = MonedaModelo::mdlListarMoneda();
        return $moneda;
    }



    /*===================================================================*/
    //REGISTRAR MONEDA
    /*===================================================================*/
    static public function ctrRegistrarMoneda($moneda_nombre, $moneda_abrevia, $moneda_simbolo, $moneda_Descripcion)
    {
        $registromoneda = MonedaModelo::mdlRegistrarMoneda($moneda_nombre, $moneda_abrevia, $moneda_simbolo, $moneda_Descripcion);
        return $registromoneda;
    }



    /*===================================================================*/
    //ACTUALIZAR MONEDA
    /*===================================================================*/
    static public function ctrActualizarMoneda($table, $data, $id, $nameId)
    {
        $respuesta = MonedaModelo::mdlActualizarMoneda($table, $data, $id, $nameId);
        return $respuesta;
    }



    /*===================================================================*/
    //ELIMINAR MONEDA
    /*===================================================================*/
    static public function ctrEliminarMoneda($table, $id, $nameId)
    {
        $respuesta = MonedaModelo::mdlEliminarMoneda($table, $id, $nameId);
        return $respuesta;
    }


    /*===================================================================*/
    //SELECT     MONEDA 
    /*===================================================================*/
    static public function ctrListarSelectMoneda()
    {
        $moneda = MonedaModelo::mdlListarSelectMoneda();
        return $moneda;
    }
}
