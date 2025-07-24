<?php
ob_start(); // Iniciar buffer de salida

// Desactivar la visualización de errores directos en la salida para evitar romper el JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Asegurar que la respuesta sea JSON
header('Content-Type: application/json; charset=utf-8');

// Manejador de excepciones para capturar cualquier error no detectado
set_exception_handler(function($exception) {
    ob_end_clean(); // Limpiar cualquier salida antes del error
    echo json_encode([
        'error' => true,
        'message' => 'Error de servidor: ' . $exception->getMessage(),
        'code' => $exception->getCode(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString()
    ]);
    exit();
});

// Manejador de errores fatales de PHP
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_end_clean(); // Limpiar cualquier salida antes del error
        echo json_encode([
            'error' => true,
            'message' => 'Error fatal del servidor: ' . $error['message'],
            'file' => $error['file'],
            'line' => $error['line']
        ]);
        exit();
    }
});

// Iniciar sesión si no está iniciada (necesario para $_SESSION)
if (session_status() == PHP_SESSION_NONE) {
session_start();
}

// Logging para depuración (se verá en logs de servidor, no en la salida JSON)
error_log("clientes_ajax.php - Acción recibida: " . ($_POST['accion'] ?? 'NO_DEFINIDA'));

require_once "../controladores/cliente_controlador.php";
require_once "../modelos/cliente_modelo.php";

if (!isset($_POST['accion'])) {
    // Si no hay acción, devolver un error JSON
    echo json_encode(['error' => true, 'message' => 'No se especificó la acción']);
    exit;
}

try {
    switch ($_POST['accion']) {
        case 'ListarSelectClientes':
            $clientes = ClienteControlador::ctrListarSelectClientes();
            if ($clientes === false) {
                echo json_encode(['error' => true, 'message' => 'Error al obtener los clientes']);
                exit;
            }
            error_log("clientes_ajax.php - Clientes encontrados para select: " . count($clientes));
            echo json_encode($clientes);
            break;
            
        case '1': // LISTAR CLIENTE EN DATATABLE DE CLIENTE (usa tbl_clientes en vistas/cliente.php)
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;
            $cliente = ClienteControlador::ctrListarClientesForDataTableAssoc($sucursal_id);
            
            if ($cliente === false || $cliente === null) {
                echo json_encode([]);
                exit;
            }
            error_log("clientes_ajax.php - Clientes encontrados para DataTables (asoc): " . count($cliente));
            echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
            break;
            
        case '7': // LISTAR CLIENTE EN DATATABLE (usa tbl_lista_cliente en vistas/prestamo.php)
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;
        $cliente = ClienteControlador::ctrListarClientes($sucursal_id);
            
            if ($cliente === false || $cliente === null) {
                echo json_encode([]);
                exit;
            }
            error_log("clientes_ajax.php - Clientes encontrados para DataTables (num): " . count($cliente));
            echo json_encode($cliente, JSON_UNESCAPED_UNICODE);
            break;
            
        case '4': // ELIMINAR UN CLIENTE (manteniendo compatibilidad)
            $table = "clientes";
            $id = $_POST["cliente_id"];
            $nameId = "cliente_id";
            $respuesta = ClienteControlador::ctrEliminarCliente($table, $id, $nameId);
            error_log("clientes_ajax.php - Cliente eliminado: " . ($respuesta === 'ok' ? 'OK' : 'ERROR'));
            echo json_encode($respuesta);
            break;
            
        case 'buscar_clientes': // BUSCAR CLIENTES PARA SELECT2
            if (!isset($_POST['busqueda']) || strlen($_POST['busqueda']) < 2) {
                echo json_encode([]);
                break;
            }
            
            $busqueda = $_POST['busqueda'];
            $clientes = ClienteControlador::ctrBuscarClientesParaSelect($busqueda);
            
            if ($clientes === false || $clientes === null) {
                echo json_encode([]);
                break;
            }
            
            // Formatear para Select2 (id, text)
            $clientesFormateados = [];
            foreach ($clientes as $cliente) {
                $clientesFormateados[] = [
                    'id' => $cliente['cliente_id'],
                    'text' => $cliente['cliente_nombres'] . ' - ' . ($cliente['cliente_dni'] ?? 'Sin DNI')
                ];
            }
            
            error_log("clientes_ajax.php - Clientes encontrados para búsqueda: " . count($clientesFormateados));
            echo json_encode($clientesFormateados);
            break;
            
        case 'buscar_clientes_disponibles': // BUSCAR SOLO CLIENTES DISPONIBLES PARA PRÉSTAMOS
            if (!isset($_POST['busqueda']) || strlen($_POST['busqueda']) < 2) {
                echo json_encode([]);
                break;
            }
            
            $busqueda = $_POST['busqueda'];
            $clientes = ClienteControlador::ctrBuscarClientesDisponibles($busqueda);
            
            if ($clientes === false || $clientes === null) {
                echo json_encode([]);
                break;
            }
            
            // Formatear para Select2 (id, text) solo clientes sin préstamos
            $clientesFormateados = [];
            foreach ($clientes as $cliente) {
                $clientesFormateados[] = [
                    'id' => $cliente['cliente_id'],
                    'text' => $cliente['cliente_nombres'] . ' - ' . ($cliente['cliente_dni'] ?? 'Sin DNI'),
                    'telefono' => $cliente['cliente_cel'] ?? ''
                ];
            }
            
            error_log("clientes_ajax.php - Clientes disponibles encontrados: " . count($clientesFormateados));
            echo json_encode($clientesFormateados);
            break;
            
        default:
            // Si la acción no es reconocida, devolver un error JSON
            echo json_encode(['error' => true, 'message' => 'Acción no válida: ' . $_POST['accion']]);
            break;
    }
    } catch (Exception $e) {
    // Capturar cualquier excepción general del bloque try-catch
    error_log("Error inesperado en clientes_ajax.php: " . $e->getMessage());
    echo json_encode([
        'error' => true,
        'message' => 'Error interno del servidor (excepción): ' . $e->getMessage()
    ]);
} finally {
    ob_end_flush(); // Vaciar el buffer de salida al final
}
?>
