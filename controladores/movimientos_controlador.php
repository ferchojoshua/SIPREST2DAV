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

    static public function ctrRegistrarIngreso($datos) {
        require_once "modelos/caja_modelo.php";
        $cajaAbierta = CajaModelo::mdlObtenerDataEstadoCaja();
        if (!$cajaAbierta || $cajaAbierta->caja_estado !== 'VIGENTE') {
            return [
                'success' => false,
                'message' => 'Debe tener una caja abierta para registrar ingresos.'
            ];
        }
        // ... lógica original para registrar ingreso ...
    }

    static public function ctrRegistrarEgreso($datos) {
        require_once "modelos/caja_modelo.php";
        $cajaAbierta = CajaModelo::mdlObtenerDataEstadoCaja();
        if (!$cajaAbierta || $cajaAbierta->caja_estado !== 'VIGENTE') {
            return [
                'success' => false,
                'message' => 'Debe tener una caja abierta para registrar egresos.'
            ];
        }
        // ... lógica original para registrar egreso ...
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