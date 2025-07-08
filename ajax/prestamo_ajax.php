<?php

require_once "../controladores/prestamo_controlador.php";
require_once "../modelos/prestamo_modelo.php";

class AjaxPrestamo
{

    /*===================================================================*/
    // REGISTRAR CABECERA PRESTAMO
    /*===================================================================*/
    public function ajaxRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo)
    {
        $registroPrestamo = PrestamoControlador::ctrRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo);
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

    /*===================================================================*/
    //OBTENER TIPOS DE CALCULO
    /*===================================================================*/
    public function ajaxObtenerTiposCalculo()
    {
        $tiposCalculo = PrestamoControlador::ctrObtenerTiposCalculo();
        echo json_encode($tiposCalculo, JSON_UNESCAPED_UNICODE);
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
        $_POST['caja_id'],
        $_POST['tipo_calculo']
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
} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { // OBTENER TIPOS DE CALCULO
    $tiposCalculo = new AjaxPrestamo();
    $tiposCalculo->ajaxObtenerTiposCalculo();
} else if (isset($_POST['accion']) && $_POST['accion'] == 5) { // CALCULAR AMORTIZACION
    require_once "../utilitarios/calculadora_prestamos.php";
    
    try {
        $calculadora = new CalculadoraPrestamos();
        $resultado = $calculadora->calcularAmortizacion(
            floatval($_POST['monto']),
            floatval($_POST['interes']),
            intval($_POST['cuotas']),
            $_POST['tipo_calculo'],
            $_POST['fecha_inicio'],
            $_POST['fpago']
        );
        
        echo json_encode($resultado);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else if ($_POST["funcion"] === "calcular_amortizacion") {
    $monto = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
    $tasa = filter_input(INPUT_POST, 'tasa', FILTER_VALIDATE_FLOAT);
    $plazo = filter_input(INPUT_POST, 'plazo', FILTER_VALIDATE_INT);
    $sistema = filter_input(INPUT_POST, 'sistema', FILTER_SANITIZE_STRING);
    $fecha = filter_input(INPUT_POST, 'fecha', FILTER_SANITIZE_STRING);
    
    if (!$monto || !$tasa || !$plazo || !$sistema || !$fecha) {
        echo json_encode(['error' => 'Todos los campos son requeridos']);
        exit;
    }
    
    try {
        require_once "../utilitarios/calculadora_prestamos.php";
        
        $resultado = CalculadoraPrestamos::calcularAmortizacion(
            $monto,
            $tasa,
            $plazo,
            $sistema,
            $fecha
        );
        
        echo json_encode($resultado);
        
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
