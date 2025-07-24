<?php

header('Content-Type: application/json; charset=utf-8');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once "../controladores/whatsapp_controlador.php";

try {
    if (!isset($_POST['accion'])) {
        echo json_encode(['success' => false, 'message' => 'No se especificó la acción']);
        exit;
    }

    switch ($_POST['accion']) {
        
        case 'enviar_mensaje':
            $telefono = $_POST['telefono'] ?? '';
            $mensaje = $_POST['mensaje'] ?? '';
            
            if (empty($telefono) || empty($mensaje)) {
                echo json_encode(['success' => false, 'message' => 'Teléfono y mensaje son requeridos']);
                break;
            }
            
            $resultado = WhatsAppControlador::ctrEnviarMensaje($telefono, $mensaje);
            echo json_encode($resultado);
            break;
            
        case 'notificar_prestamo':
            $cliente_id = $_POST['cliente_id'] ?? 0;
            $monto = $_POST['monto'] ?? 0;
            $cuotas = $_POST['cuotas'] ?? 0;
            
            if (empty($cliente_id) || empty($monto)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                break;
            }
            
            $resultado = WhatsAppControlador::ctrNotificarPrestamoAprobado($cliente_id, $monto, $cuotas);
            echo json_encode($resultado);
            break;
            
        case 'recordatorio_pago':
            $cliente_id = $_POST['cliente_id'] ?? 0;
            $monto = $_POST['monto'] ?? 0;
            $fecha = $_POST['fecha'] ?? '';
            
            if (empty($cliente_id) || empty($monto)) {
                echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
                break;
            }
            
            $resultado = WhatsAppControlador::ctrEnviarRecordatorioPago($cliente_id, $monto, $fecha);
            echo json_encode($resultado);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Acción no válida']);
            break;
    }

} catch (Exception $e) {
    error_log("Error en whatsapp_ajax: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error del servidor: ' . $e->getMessage()]);
}
?> 