<?php

// Configurar cookies de sesión para AJAX
ini_set('session.cookie_httponly', 0);
ini_set('session.cookie_samesite', 'Lax');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Debug: Forzar información de sesión
error_log("ANULACIONES AJAX DEBUG:");
error_log("Session Status: " . session_status());
error_log("Session ID: " . session_id());
error_log("Session Name: " . session_name());
error_log("Has Session Usuario: " . (isset($_SESSION["usuario"]) ? "YES" : "NO"));
error_log("Cookies: " . json_encode($_COOKIE));
error_log("Session Content: " . json_encode($_SESSION ?? []));

// Headers de seguridad
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['estado' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

require_once "../controladores/anulaciones_controlador.php";
require_once "../modelos/anulaciones_modelo.php";

class AnulacionesAjax
{
    /**
     * Anular pago de cuota
     */
    public function ajaxAnularPago()
    {
        try {
            // Validar datos requeridos
            if (empty($_POST['nro_prestamo']) || empty($_POST['nro_cuota']) || empty($_POST['motivo'])) {
                throw new Exception('Datos incompletos para anular pago');
            }

            $nro_prestamo = trim($_POST['nro_prestamo']);
            $nro_cuota = intval($_POST['nro_cuota']);
            $motivo = trim($_POST['motivo']);

            $resultado = AnulacionesControlador::ctrAnularPago($nro_prestamo, $nro_cuota, $motivo);
            echo json_encode($resultado);

        } catch (Exception $e) {
            error_log("Error en ajaxAnularPago: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Anular préstamo/contrato
     */
    public function ajaxAnularPrestamo()
    {
        try {
            // Validar datos requeridos
            if (empty($_POST['nro_prestamo']) || empty($_POST['motivo'])) {
                throw new Exception('Datos incompletos para anular préstamo');
            }

            $nro_prestamo = trim($_POST['nro_prestamo']);
            $motivo = trim($_POST['motivo']);

            $resultado = AnulacionesControlador::ctrAnularPrestamo($nro_prestamo, $motivo);
            echo json_encode($resultado);

        } catch (Exception $e) {
            error_log("Error en ajaxAnularPrestamo: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verificar permisos de anulación
     */
    public function ajaxVerificarPermisos()
    {
        try {
            if (empty($_POST['tipo_documento'])) {
                throw new Exception('Tipo de documento requerido');
            }

            $tipo_documento = trim($_POST['tipo_documento']);
            $fecha_documento = !empty($_POST['fecha_documento']) ? $_POST['fecha_documento'] : null;

            // Verificar sesión directamente (como otros archivos AJAX del sistema)
            if (!isset($_SESSION["usuario"])) {
                error_log("AJAX anulaciones: No hay sesión de usuario");
                throw new Exception('Sesión no válida o expirada');
            }
            
            // Crear datos del usuario directamente de la sesión
            $usuario_actual = [
                'id_usuario' => $_SESSION["usuario"]->id_usuario,
                'nombre_completo' => $_SESSION["usuario"]->nombre_usuario . ' ' . $_SESSION["usuario"]->apellido_usuario,
                'perfil_id' => $_SESSION["usuario"]->id_perfil_usuario,
                'sucursal_id' => $_SESSION["usuario"]->sucursal_id ?? null,
                'es_administrador' => $_SESSION["usuario"]->id_perfil_usuario == 1
            ];

            $permisos = AnulacionesControlador::ctrVerificarPermisosAnulacion(
                $usuario_actual['id_usuario'], 
                $tipo_documento, 
                $fecha_documento
            );

            echo json_encode([
                'estado' => 'ok',
                'permisos' => $permisos
            ]);

        } catch (Exception $e) {
            error_log("Error en ajaxVerificarPermisos: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener historial de anulaciones
     */
    public function ajaxObtenerHistorial()
    {
        try {
            $filtros = [];
            
            // Aplicar filtros si vienen en la petición
            if (!empty($_POST['usuario_id'])) {
                $filtros['usuario_id'] = intval($_POST['usuario_id']);
            }
            
            if (!empty($_POST['tipo_documento'])) {
                $filtros['tipo_documento'] = trim($_POST['tipo_documento']);
            }
            
            if (!empty($_POST['fecha_desde'])) {
                $filtros['fecha_desde'] = $_POST['fecha_desde'];
            }
            
            if (!empty($_POST['fecha_hasta'])) {
                $filtros['fecha_hasta'] = $_POST['fecha_hasta'];
            }
            
            if (!empty($_POST['sucursal_id'])) {
                $filtros['sucursal_id'] = intval($_POST['sucursal_id']);
            }

            $historial = AnulacionesControlador::ctrObtenerHistorialAnulaciones($filtros);

            echo json_encode([
                'estado' => 'ok',
                'data' => $historial
            ]);

        } catch (Exception $e) {
            error_log("Error en ajaxObtenerHistorial: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
    }
}

// Procesar la acción
try {
    if (!isset($_POST['accion'])) {
        throw new Exception('Acción no especificada');
    }

    $anulaciones = new AnulacionesAjax();

    switch ($_POST['accion']) {
        case 'anular_pago':
            $anulaciones->ajaxAnularPago();
            break;

        case 'anular_prestamo':
            $anulaciones->ajaxAnularPrestamo();
            break;

        case 'verificar_permisos':
            $anulaciones->ajaxVerificarPermisos();
            break;

        case 'obtener_historial':
            $anulaciones->ajaxObtenerHistorial();
            break;

        default:
            throw new Exception('Acción no válida');
    }

} catch (Exception $e) {
    error_log("Error en anulaciones_ajax.php: " . $e->getMessage());
    echo json_encode([
        'estado' => 'error',
        'mensaje' => $e->getMessage()
    ]);
}
?> 