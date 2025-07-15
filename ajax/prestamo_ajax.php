<?php

// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 0); // Desactivar display_errors en producción
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/prestamo_ajax_fatal_error.log');

// Manejador de excepciones global
set_exception_handler(function ($exception) {
    error_log("UNCAUGHT EXCEPTION in ajax/prestamo_ajax.php: " . $exception->getMessage() . " on line " . $exception->getLine() . " in " . $exception->getFile());
    // Limpiar cualquier salida previa y enviar un JSON de error
    ob_clean();
    echo json_encode(['error' => 'Error interno del servidor. Por favor, revise el log para más detalles.']);
    exit();
});

// Función de shutdown para capturar errores fatales
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_CORE_ERROR || $error['type'] === E_COMPILE_ERROR)) {
        ob_clean(); // Limpiar cualquier buffer de salida
        error_log("FATAL ERROR in ajax/prestamo_ajax.php: " . $error['message'] . " on line " . $error['line'] . " in " . $error['file']);
        echo json_encode(['error' => 'Error fatal del servidor. Por favor, revise el log para más detalles.']);
    }
});

ob_start(); // Iniciar buffer de salida

// Determinar la ruta base correcta
$basePath = dirname(__DIR__);
require_once $basePath . "/controladores/prestamo_controlador.php";
require_once $basePath . "/modelos/prestamo_modelo.php";

class AjaxPrestamo
{

    /*===================================================================
    // CARGAR SELECT FORMA DE PAGO
    /*===================================================================*/
    public function ajaxCargarFormaPago()
    {
        error_log("DEBUG: Inicia ajaxCargarFormaPago");
        $respuesta = PrestamoControlador::ctrCargarSelect("forma_pago");
        echo json_encode($respuesta);
        error_log("DEBUG: Finaliza ajaxCargarFormaPago");
    }

    /*===================================================================
    // CARGAR SELECT MONEDA
    /*===================================================================*/
    public function ajaxCargarMoneda()
    {
        error_log("DEBUG: Inicia ajaxCargarMoneda");
        $respuesta = PrestamoControlador::ctrCargarSelect("moneda");
        echo json_encode($respuesta);
        error_log("DEBUG: Finaliza ajaxCargarMoneda");
    }

    /*===================================================================
    // OBTENER TIPOS DE CALCULO (Amortización)
    /*===================================================================*/
    public function ajaxObtenerTiposCalculo()
    {
        error_log("DEBUG: Inicia ajaxObtenerTiposCalculo");
        $tiposCalculo = PrestamoControlador::ctrObtenerTiposCalculo();
        echo json_encode($tiposCalculo, JSON_UNESCAPED_UNICODE);
        error_log("DEBUG: Finaliza ajaxObtenerTiposCalculo");
    }

    /*===================================================================
    // REGISTRAR CABECERA PRESTAMO
    /*===================================================================*/
    public function ajaxRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo)
    {
        error_log("DEBUG: Inicia ajaxRegistrarPrestamo");
        $registroPrestamo = PrestamoControlador::ctrRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo);
        echo json_encode($registroPrestamo, JSON_UNESCAPED_UNICODE);
        error_log("DEBUG: Finaliza ajaxRegistrarPrestamo");
    }


    /*===================================================================
    // REGISTRAR DETALLE PRESTAMO
    /*===================================================================*/
    public function ajaxRegistrarPrestamoDetalle($nro_prestamo, $pdetalle_nro_cuota, $pdetalle_monto_cuota, $pdetalle_fecha)
    {
        error_log("DEBUG: Inicia ajaxRegistrarPrestamoDetalle");
        $registroPrestamoDetalle = PrestamoControlador::ctrRegistrarPrestamoDetalle($nro_prestamo, $pdetalle_nro_cuota, $pdetalle_monto_cuota, $pdetalle_fecha);
        echo json_encode($registroPrestamoDetalle);
        error_log("DEBUG: Finaliza ajaxRegistrarPrestamoDetalle");
    }


    /*===================================================================
    //VALIDAD SI HAY MONTO DISPONIBLE EN CAJA
    /*===================================================================*/
    public function ajaxValidarMontoPrestamo()
    {
        error_log("DEBUG: Inicia ajaxValidarMontoPrestamo");
        $ValidarPres = PrestamoControlador::ctrValidarMontoPrestamo();
        echo json_encode($ValidarPres, JSON_UNESCAPED_UNICODE);
        error_log("DEBUG: Finaliza ajaxValidarMontoPrestamo");
    }

    /*===================================================================
    //CALCULAR AMORTIZACION
    /*===================================================================*/
    public function ajaxCalcularAmortizacion($monto, $interes, $cuotas, $tipo_calculo, $fecha_inicio, $fpago)
    {
        error_log("DEBUG: Inicia ajaxCalcularAmortizacion con monto: $monto, interes: $interes, cuotas: $cuotas, tipo_calculo: $tipo_calculo, fecha_inicio: $fecha_inicio, fpago: $fpago");
        require_once "../utilitarios/calculadora_prestamos.php";
        
        try {
            $calculadora = new CalculadoraPrestamos();
            $resultado = $calculadora->calcularAmortizacion(
                floatval($monto),
                floatval($interes),
                intval($cuotas),
                $tipo_calculo,
                $fecha_inicio,
                $fpago
            );
            
            // Si se solicita una página específica, paginar la tabla de amortización
            if (isset($_POST['pagina'])) {
                $pagina = intval($_POST['pagina']);
                $porPagina = isset($_POST['por_pagina']) ? intval($_POST['por_pagina']) : 12;
                
                // Guardar la tabla completa para usar en paginación
                $tablaCompleta = $resultado['tabla_amortizacion'];
                
                // Obtener solo la página solicitada
                $paginacion = CalculadoraPrestamos::paginarTablaAmortizacion(
                    $tablaCompleta,
                    $pagina,
                    $porPagina
                );
                
                // Reemplazar la tabla completa por la página solicitada y añadir info de paginación
                $resultado['tabla_amortizacion'] = $paginacion['registros'];
                $resultado['paginacion'] = $paginacion['paginacion'];
            }
            
            echo json_encode($resultado);
            error_log("DEBUG: ajaxCalcularAmortizacion finalizada exitosamente. Resultado: " . json_encode($resultado));
        } catch (Exception $e) {
            error_log("ERROR en ajaxCalcularAmortizacion: " . $e->getMessage() . " on line " . $e->getLine() . " in " . $e->getFile());
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// ===================================================================
// GESTOR DE ACCIONES AJAX
// ===================================================================

$ajax = new AjaxPrestamo();

// Debugging: Log the received action
$received_action = $_POST['accion'] ?? 'Ninguna';
error_log("DEBUG: Acción recibida en prestamo_ajax.php: " . $received_action);

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case 'cargar_forma_pago':
            $ajax->ajaxCargarFormaPago();
            break;

        case 'cargar_moneda':
            $ajax->ajaxCargarMoneda();
            break;

        case 'obtener_tipos_calculo':
        case '4': // Legacy support
            $ajax->ajaxObtenerTiposCalculo();
            break;

        case 'guardar_prestamo':
            $ajax->ajaxRegistrarPrestamo(
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
            break;

        case 'guardar_detalle':
            $ajax->ajaxRegistrarPrestamoDetalle(
                $_POST['nro_prestamo'],
                $_POST['pdetalle_nro_cuota'],
                $_POST['pdetalle_monto_cuota'],
                $_POST['pdetalle_fecha']
            );
            break;
            
        case 'validar_monto':
            $ajax->ajaxValidarMontoPrestamo();
            break;

        case 'calcular_amortizacion':
            $ajax->ajaxCalcularAmortizacion(
                $_POST['monto'],
                $_POST['interes'],
                $_POST['cuotas'],
                $_POST['tipo_calculo'],
                $_POST['fecha_inicio'],
                $_POST['fpago']
            );
            break;

        default:
            error_log("Acción no reconocida en prestamo_ajax.php: " . ($_POST['accion'] ?? 'N/A') . ". POST data: " . print_r($_POST, true));
            echo json_encode(['error' => 'Acción no reconocida']);
            break;
    }

} else {
    error_log("Petición sin acción especificada en prestamo_ajax.php. Request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(['error' => 'Petición sin acción especificada']);
}

// Asegurarse de que el buffer de salida se limpie al final.
// Esto es redundante con register_shutdown_function si hay un error fatal,
// pero es buena práctica para la salida normal.
ob_end_clean();
?>
