<?php

class MovimientosControlador
{

    /*===================================================================*/
    //LISTAR MOVIMIENTOS CON PROCEDURE  EN DATATABLE
    /*===================================================================*/
    static public function ctrListarMovimientos()
    {
        $Movimientos = MovimientosModelo::mdlListarMovimientos();
        return $Movimientos;
    }


    
    /*===================================================================*/
    //REGISTRAR MOVIMIENTOS
    /*===================================================================*/
    static public function ctrRegistrarMovi($movi_tipo, $movi_descripcion, $movi_monto, $caja_id)
    {
        $registroMovi = MovimientosModelo::mdlRegistrarMovi($movi_tipo, $movi_descripcion, $movi_monto, $caja_id);
        return $registroMovi;
    }



    /*===================================================================*/
    //ACTUALIZAR MOVIMIENTOS
    /*===================================================================*/
    static public function ctrActualizarMovi($table, $data, $id, $nameId)
    {
        $respuesta = MovimientosModelo::mdlActualizarMovi($table, $data, $id, $nameId);
        return $respuesta;
    }



    /*===================================================================*/
    //ELIMINAR MOVIMIENTOS
    /*===================================================================*/
    static public function ctrEliminarMovi($movimientos_id)
    {
        $respuesta = MovimientosModelo::mdlEliminarMovi($movimientos_id);
        return $respuesta;
    }


}