<?php
/**
 * AJAX para manejo de consecutivos por sucursal
 * =============================================
 * 
 * Endpoint para obtener números consecutivos automáticamente
 * basados en la sucursal del usuario logueado.
 */

require_once "../modelos/consecutivos_modelo.php";

session_start();

class AjaxConsecutivos
{
    /**
     * Obtener próximo número de préstamo para la sucursal especificada
     */
    public function ajaxObtenerNumeroPrestamo()
    {
        try {
            // DEBUG: Log de datos recibidos
            error_log("=== DEBUG CONSECUTIVOS ===");
            error_log("POST data: " . print_r($_POST, true));
            error_log("SESSION usuario: " . print_r($_SESSION["usuario"] ?? 'NO_SESSION', true));
            
            // Determinar qué sucursal usar
            $sucursal_id = null;
            
            // Si se envía sucursal_id específica (para administradores)
            if (isset($_POST['sucursal_id']) && !empty($_POST['sucursal_id'])) {
                $sucursal_id = (int)$_POST['sucursal_id'];
                error_log("Usando sucursal de POST: " . $sucursal_id);
            }
            // Si no, usar la sucursal del usuario logueado
            elseif (isset($_SESSION["usuario"]->sucursal_id)) {
                $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                error_log("Usando sucursal de sesión: " . $sucursal_id);
            }
            
            if (!$sucursal_id) {
                $mensaje_error = 'No se pudo determinar la sucursal para generar el consecutivo';
                error_log("ERROR: " . $mensaje_error);
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $mensaje_error,
                    'debug' => [
                        'post_sucursal_id' => $_POST['sucursal_id'] ?? 'NO_ENVIADO',
                        'session_usuario' => isset($_SESSION["usuario"]) ? 'EXISTE' : 'NO_EXISTE',
                        'session_sucursal' => $_SESSION["usuario"]->sucursal_id ?? 'NO_DEFINIDO'
                    ]
                ], JSON_UNESCAPED_UNICODE);
                return;
            }
            
            $numero_prestamo = ConsecutivosModelo::mdlGenerarNumeroPrestamo($sucursal_id);
            
            if ($numero_prestamo) {
                echo json_encode([
                    'estado' => 'exito',
                    'numero_prestamo' => $numero_prestamo,
                    'sucursal_id' => $sucursal_id,
                    'mensaje' => 'Número generado exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se pudo generar el número de préstamo'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Obtener próximo número de recibo para la sucursal del usuario
     */
    public function ajaxObtenerNumeroRecibo()
    {
        try {
            $numero_recibo = ConsecutivosModelo::mdlGenerarNumeroRecibo();
            
            if ($numero_recibo) {
                echo json_encode([
                    'estado' => 'exito',
                    'numero_recibo' => $numero_recibo,
                    'sucursal_id' => $_SESSION["usuario"]->sucursal_id ?? null,
                    'mensaje' => 'Número generado exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se pudo generar el número de recibo'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Obtener próximo número de voucher para la sucursal del usuario
     */
    public function ajaxObtenerNumeroVoucher()
    {
        try {
            $numero_voucher = ConsecutivosModelo::mdlGenerarNumeroVoucher();
            
            if ($numero_voucher) {
                echo json_encode([
                    'estado' => 'exito',
                    'numero_voucher' => $numero_voucher,
                    'sucursal_id' => $_SESSION["usuario"]->sucursal_id ?? null,
                    'mensaje' => 'Número generado exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se pudo generar el número de voucher'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Confirmar uso de consecutivo (incrementar contador)
     */
    public function ajaxConfirmarUsoConsecutivo()
    {
        try {
            $tipo = $_POST['tipo'] ?? 'prestamo';
            $resultado = false;

            switch ($tipo) {
                case 'prestamo':
                    $resultado = ConsecutivosModelo::mdlConfirmarUsoPrestamo();
                    break;
                case 'recibo':
                    $resultado = ConsecutivosModelo::mdlConfirmarUsoRecibo();
                    break;
                case 'voucher':
                    $resultado = ConsecutivosModelo::mdlConfirmarUsoVoucher();
                    break;
            }

            if ($resultado) {
                echo json_encode([
                    'estado' => 'exito',
                    'mensaje' => 'Consecutivo confirmado e incrementado'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se pudo confirmar el consecutivo'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /**
     * Obtener estado de consecutivos de la sucursal
     */
    public function ajaxObtenerEstadoConsecutivos()
    {
        try {
            $consecutivos = ConsecutivosModelo::mdlObtenerConsecutivosSucursal();
            
            if ($consecutivos) {
                echo json_encode([
                    'estado' => 'exito',
                    'consecutivos' => $consecutivos,
                    'mensaje' => 'Estado obtenido exitosamente'
                ], JSON_UNESCAPED_UNICODE);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se pudo obtener el estado de consecutivos'
                ], JSON_UNESCAPED_UNICODE);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}

// Manejar las peticiones AJAX
$ajax = new AjaxConsecutivos();

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        case 'obtener_numero_prestamo':
            $ajax->ajaxObtenerNumeroPrestamo();
            break;
            
        case 'obtener_numero_recibo':
            $ajax->ajaxObtenerNumeroRecibo();
            break;
            
        case 'obtener_numero_voucher':
            $ajax->ajaxObtenerNumeroVoucher();
            break;
            
        case 'confirmar_uso':
            $ajax->ajaxConfirmarUsoConsecutivo();
            break;
            
        case 'obtener_estado':
            $ajax->ajaxObtenerEstadoConsecutivos();
            break;
            
        default:
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Acción no reconocida'
            ], JSON_UNESCAPED_UNICODE);
            break;
    }
} else {
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'No se especificó una acción'
    ], JSON_UNESCAPED_UNICODE);
}
?> 