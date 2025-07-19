<?php

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Headers de seguridad
header('Content-Type: application/json; charset=utf-8');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['estado' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

// Debug: Mostrar información de la sesión
error_log("DEBUG SIMPLE: session_status = " . session_status());
error_log("DEBUG SIMPLE: session_id = " . session_id());
error_log("DEBUG SIMPLE: tiene usuario = " . (isset($_SESSION["usuario"]) ? "SÍ" : "NO"));

if (isset($_SESSION["usuario"])) {
    error_log("DEBUG SIMPLE: usuario = " . $_SESSION["usuario"]->nombre_usuario);
    error_log("DEBUG SIMPLE: perfil_id = " . $_SESSION["usuario"]->id_perfil_usuario);
}

try {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'verificar_permisos':
            // Verificación simplificada
            if (!isset($_SESSION["usuario"])) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No hay sesión activa'
                ]);
                exit;
            }
            
            $es_admin = $_SESSION["usuario"]->id_perfil_usuario == 1;
            
            echo json_encode([
                'estado' => 'ok',
                'permisos' => [
                    'puede_anular' => $es_admin,
                    'requiere_justificacion' => true,
                    'es_administrador' => $es_admin,
                    'usuario' => $_SESSION["usuario"]->nombre_usuario,
                    'perfil_id' => $_SESSION["usuario"]->id_perfil_usuario
                ]
            ]);
            break;
            
        case 'anular_pago':
            if (!isset($_SESSION["usuario"])) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No hay sesión activa'
                ]);
                exit;
            }
            
            if ($_SESSION["usuario"]->id_perfil_usuario != 1) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No tiene permisos para anular pagos'
                ]);
                exit;
            }
            
            // Aquí iría la lógica real de anulación
            echo json_encode([
                'estado' => 'ok',
                'mensaje' => 'Funcionalidad de anulación pendiente de implementar completamente'
            ]);
            break;
            
        default:
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Acción no válida'
            ]);
    }
    
} catch (Exception $e) {
    error_log("Error en anulaciones_simple_ajax.php: " . $e->getMessage());
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'Error interno: ' . $e->getMessage()
    ]);
}
?> 