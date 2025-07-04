<?php

require_once "../controladores/prestamo_controlador.php";
require_once "../modelos/prestamo_modelo.php";

class AjaxPrestamo
{

    /*===================================================================*/
    // REGISTRAR CABECERA PRESTAMO
    /*===================================================================*/
    public function ajaxRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id)
    {
        $registroPrestamo = PrestamoControlador::ctrRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id);
        echo json_encode($registroPrestamo, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    // REGISTRAR DETALLE PRESTAMO
    /*===================================================================*/
    public function ajaxRegistrarPrestamoDetalle($nro_prestamo, $pdetalle_nro_cuota, $pdetalle_monto_cuota, $pdetalle_fecha)
    {
        $registroPrestamoDetalle = PrestamoControlador::ctrRegistrarPrestamoDetalle($nro_prestamo, $pdetalle_nro_cuota, $pdetalle_monto_cuota, $pdetalle_fecha);
        echo json_encode($registroPrestamoDetalle);
        var_dump($registroPrestamoDetalle);
    }


    /*===================================================================*/
    //VALIDAD SI HAY MONTO DISPONIBLE EN CAJA
    /*===================================================================*/
    public function ajaxValidarMontoPrestamo()
    {
        $ValidarPres = PrestamoControlador::ctrValidarMontoPrestamo();
        echo json_encode($ValidarPres, JSON_UNESCAPED_UNICODE);
    }
}



if (isset($_POST["accion"]) && $_POST["accion"] == 1) { // GUARDAR PRESTAMO

    $registrar = new AjaxPrestamo();
    $registrar->ajaxRegistrarPrestamo(
        $_POST['nro_prestamo'],
        $_POST['cliente_id'],
        $_POST['pres_monto'],
        $_POST['pres_cuotas'],
        $_POST['pres_interes'],
        $_POST['fpago_id'],
        $_POST['moneda_id'],
        $_POST['pres_f_emision'],
        $_POST['pres_monto_cuota'],
        $_POST['pres_monto_interes'],
        $_POST['pres_monto_total'],
        $_POST['id_usuario'],
        $_POST['caja_id']
    );


} else if (isset($_POST["accion"]) && $_POST["accion"] == 2) { // REGISTRAR DETALLE PRESTAMO

    $registrarDetalle = new AjaxPrestamo();
    $registrarDetalle->ajaxRegistrarPrestamoDetalle(
        $_POST['nro_prestamo'],
        $_POST['pdetalle_nro_cuota'],
        $_POST['pdetalle_monto_cuota'],
        $_POST['pdetalle_fecha']

    );
} else if (isset($_POST['accion']) && $_POST['accion'] == 3) {
    $ValidarPres = new AjaxPrestamo();
    $ValidarPres->ajaxValidarMontoPrestamo(); //VALIDAD SI HAY MONTO DISPONIBLE EN CAJA

}
