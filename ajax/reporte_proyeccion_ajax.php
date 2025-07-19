<?php

require_once "../controladores/reporte_proyeccion_controlador.php";
require_once "../modelos/reporte_proyeccion_modelo.php";

if (isset($_POST["accion"])) {
    switch ($_POST["accion"]) {
        case 'obtener_proyeccion':
            $mes = isset($_POST["mes"]) ? (int)$_POST["mes"] : date('m');
            $anio = isset($_POST["anio"]) ? (int)$_POST["anio"] : date('Y');
            $response = ReporteProyeccionControlador::ctrObtenerProyeccionMensual($mes, $anio);
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