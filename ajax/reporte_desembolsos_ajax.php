<?php

require_once "../controladores/reporte_desembolsos_controlador.php";
require_once "../modelos/reporte_desembolsos_modelo.php";

if (isset($_POST["accion"])) {
    switch ($_POST["accion"]) {
        case 'obtener_desembolsos':
                $response = ReporteDesembolsosControlador::ctrObtenerDesembolsos();
                header('Content-Type: application/json');
                die(json_encode($response)); // solo si estás haciendo pruebas


            break;
        default:
            echo json_encode(['error' => true, 'message' => 'Acción no definida.']);
            break;
    }
} else {
    echo json_encode(['error' => true, 'message' => 'No se recibió ninguna acción.']);
}

?>