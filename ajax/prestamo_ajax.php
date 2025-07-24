<?php

// 1. MANEJO DE ERRORES Y CONFIGURACIÓN
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/prestamo_ajax_error.log');

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

ob_start();

// 2. INCLUSIÓN DE DEPENDENCIAS
require_once __DIR__ . '/../controladores/prestamo_controlador.php';
require_once __DIR__ . '/../modelos/prestamo_modelo.php';

// 3. DEFINICIÓN DE LA CLASE AJAX
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
        try {
            $tiposCalculo = PrestamoControlador::ctrObtenerTiposCalculo();
            echo json_encode($tiposCalculo, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
        }
    }

    /*===================================================================
    // REGISTRAR CABECERA PRESTAMO
    /*===================================================================*/
    public function ajaxRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo, $sucursal_id = null)
    {
        error_log("DEBUG: Inicia ajaxRegistrarPrestamo con sucursal_id: " . $sucursal_id);
        $registroPrestamo = PrestamoControlador::ctrRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo, $sucursal_id);
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
        header('Content-Type: application/json');
        try {
            // Validar que la calculadora exista antes de incluirla
            if (!file_exists(__DIR__ . "/../utilitarios/calculadora_prestamos.php")) {
                throw new Exception("El archivo de la calculadora de préstamos no se encuentra.");
            }
            require_once __DIR__ . "/../utilitarios/calculadora_prestamos.php";

            if (!class_exists('CalculadoraPrestamos')) {
                throw new Exception("La clase CalculadoraPrestamos no está definida.");
            }
            
            $calculadora = new CalculadoraPrestamos();
            $resultado = $calculadora->calcularAmortizacion(
                floatval($monto),
                floatval($interes),
                intval($cuotas),
                $tipo_calculo,
                $fecha_inicio,
                $fpago
            );
            
            // Asegurar que la salida sea JSON
            echo json_encode($resultado, JSON_UNESCAPED_UNICODE);

        } catch (Exception $e) {
            // Capturar cualquier excepción y devolver un JSON de error
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// 4. GESTOR DE ACCIONES (EL ROUTER)
if (isset($_POST['accion'])) {
    $ajax = new AjaxPrestamo();
    switch ($_POST['accion']) {
        case 'cargar_forma_pago':
            $ajax->ajaxCargarFormaPago();
            break;
        case 'cargar_moneda':
            $ajax->ajaxCargarMoneda();
            break;
        case 'obtener_tipos_calculo':
            $ajax->ajaxObtenerTiposCalculo();
            break;
        case '1': // Guardar cabecera del préstamo
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
                $_POST['tipo_calculo'],
                $_POST['sucursal_id'] ?? null // Pass sucursal_id
            );
            break;
        case '2': // Guardar detalle del préstamo
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
    echo json_encode(['error' => 'No se especificó acción']);
}

ob_end_flush();
?>
