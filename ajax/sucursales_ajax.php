<?php

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Headers de seguridad
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Verificar que sea una petición POST o GET válida
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    http_response_code(405);
    echo json_encode(['estado' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

require_once "../controladores/sucursales_controlador.php";
require_once "../modelos/sucursales_modelo.php";

class SucursalAjax
{
    public function ajaxListarSucursales()
    {
        try {
            $sucursales = SucursalControlador::ctrListarSucursales();
            
            // Validar que se obtuvieron datos
            if ($sucursales === false) {
                throw new Exception("Error al obtener datos de sucursales");
            }
            
            echo json_encode($sucursales);
        } catch (Exception $e) {
            error_log("Error en ajaxListarSucursales: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar las sucursales'
            ]);
        }
    }

    public function ajaxGuardarSucursal()
    {
        try {
            // Validar que se recibieron los datos necesarios
            $camposRequeridos = ['nombre', 'codigo', 'estado'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                    throw new Exception("El campo {$campo} es requerido");
                }
            }

            // Preparar datos
            $datos = [
                'id' => $_POST['id'] ?? '',
                'empresa_id' => $_POST['empresa_id'] ?? 1,
                'nombre' => $_POST['nombre'] ?? '',
                'direccion' => $_POST['direccion'] ?? '',
                'telefono' => $_POST['telefono'] ?? '',
                'codigo' => $_POST['codigo'] ?? '',
                'estado' => $_POST['estado'] ?? 'activa'
            ];

            // Llamar al controlador
            $respuesta = SucursalControlador::ctrGuardarSucursal($datos);
            
            // Manejar diferentes tipos de respuesta
            if ($respuesta === "ok") {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Sucursal guardada correctamente'
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['valid']) && !$respuesta['valid']) {
                // Error de validación
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['estado']) && $respuesta['estado'] === 'error') {
                // Error del controlador
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } else {
                // Error de base de datos
                $mensaje = is_array($respuesta) && isset($respuesta[2]) ? $respuesta[2] : 'Error desconocido';
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error en la base de datos: ' . $mensaje
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxGuardarSucursal: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function ajaxEliminarSucursal()
    {
        try {
            // Validar que se recibió el ID
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                throw new Exception("ID de sucursal requerido");
            }

            $id = $_POST['id'];
            
            // Validar que el ID sea numérico
            if (!is_numeric($id)) {
                throw new Exception("ID de sucursal inválido");
            }

            $respuesta = SucursalControlador::ctrEliminarSucursal($id);
            
            if ($respuesta === 'ok') {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Sucursal eliminada correctamente'
                ]);
            } elseif ($respuesta === 'en_uso') {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'La sucursal no se puede eliminar porque está asignada a uno o más registros'
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['estado']) && $respuesta['estado'] === 'error') {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } else {
                $mensaje = is_array($respuesta) && isset($respuesta[2]) ? $respuesta[2] : 'Error desconocido';
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error en la base de datos: ' . $mensaje
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxEliminarSucursal: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function ajaxVerificarDuplicados()
    {
        try {
            // Validar que se recibieron los datos necesarios
            if (!isset($_POST['codigo']) || !isset($_POST['nombre'])) {
                throw new Exception("Código y nombre son requeridos");
            }

            $codigo = trim($_POST['codigo']);
            $nombre = trim($_POST['nombre']);
            $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : null;

            // Validar que no estén vacíos
            if (empty($codigo) || empty($nombre)) {
                throw new Exception("Código y nombre no pueden estar vacíos");
            }

            $respuesta = SucursalControlador::ctrVerificarDuplicados($codigo, $nombre, $id);
            
            if ($respuesta['valid']) {
                echo json_encode([
                    'valid' => true,
                    'message' => 'Datos válidos'
                ]);
            } else {
                echo json_encode([
                    'valid' => false,
                    'message' => $respuesta['message']
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxVerificarDuplicados: " . $e->getMessage());
            echo json_encode([
                'valid' => false,
                'message' => 'Error al verificar duplicados: ' . $e->getMessage()
            ]);
        }
    }

    public function ajaxObtenerSucursal()
    {
        try {
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                throw new Exception("ID de sucursal requerido");
            }

            $id = $_POST['id'];
            
            if (!is_numeric($id)) {
                throw new Exception("ID de sucursal inválido");
            }

            $sucursal = SucursalModelo::mdlObtenerSucursal($id);
            
            if ($sucursal) {
                echo json_encode([
                    'estado' => 'ok',
                    'data' => $sucursal
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Sucursal no encontrada'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxObtenerSucursal: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    public function ajaxListarSucursalesActivas()
    {
        try {
            $sucursales = SucursalModelo::mdlListarSucursalesActivas();
            echo json_encode($sucursales);
        } catch (Exception $e) {
            error_log("Error en ajaxListarSucursalesActivas: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar sucursales activas'
            ]);
        }
    }
}

// Función para validar y sanitizar la acción
function validarAccion($accion) {
    // Añadimos la nueva acción 'listar_sucursales' a las permitidas
    $accionesPermitidas = ['listar', 'guardar', 'eliminar', 'verificar_duplicados', 'obtener', 'listar_activas', 'listar_sucursales'];
    return in_array($accion, $accionesPermitidas) ? $accion : false;
}

// Manejo de las peticiones
try {
    // Primero intentar obtener la acción de GET, luego de POST
    $accion_recibida = null;
    
    // Si viene por GET (como ?accion=listar)
    if (isset($_GET['accion'])) {
        $accion_recibida = $_GET['accion'];
    }
    // Si viene por POST
    elseif (isset($_POST['accion'])) {
        $accion_recibida = $_POST['accion'];
    }
    
    // Si no se envió ninguna acción, usar 'listar' por defecto
    if (!$accion_recibida) {
        $accion_recibida = 'listar';
    }

    $accion = validarAccion($accion_recibida);

    if (!$accion) {
        throw new Exception("Acción no válida: " . $accion_recibida);
    }

    $ajax = new SucursalAjax();

    switch ($accion) {
        // Unificamos 'listar_sucursales' y 'listar' para que hagan lo mismo
        case 'listar_sucursales':
        case 'listar':
            $ajax->ajaxListarSucursales();
            break;
        case 'guardar':
            $ajax->ajaxGuardarSucursal();
            break;
        case 'eliminar':
            $ajax->ajaxEliminarSucursal();
            break;
        case 'verificar_duplicados':
            $ajax->ajaxVerificarDuplicados();
            break;
        case 'obtener':
            $ajax->ajaxObtenerSucursal();
            break;
        case 'listar_activas':
            $ajax->ajaxListarSucursalesActivas();
            break;
    }
} catch (Exception $e) {
    error_log("Error en el manejador de peticiones de SucursalAjax: " . $e->getMessage());
    echo json_encode(['estado' => 'error', 'mensaje' => 'Error fatal en la petición: ' . $e->getMessage()]);
} 