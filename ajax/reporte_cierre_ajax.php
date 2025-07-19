<?php

require_once "../controladores/reporte_cierre_controlador.php";
require_once "../modelos/reporte_cierre_modelo.php";

if (isset($_POST["accion"])) {
    switch ($_POST["accion"]) {
        case 'obtener_cierre':
            $mes = isset($_POST["mes"]) ? (int)$_POST["mes"] : date('m');
            $anio = isset($_POST["anio"]) ? (int)$_POST["anio"] : date('Y');
            $response = ReporteCierreControlador::ctrObtenerCierreMensual($mes, $anio);
            echo json_encode($response);
            break;
        default:
            echo json_encode(['error' => true, 'message' => 'Acción no definida.']);
            break;
    }
} else {
    echo json_encode(['error' => true, 'message' => 'No se recibió ninguna acción.']);
}

?> 