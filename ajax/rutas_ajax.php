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

require_once "../controladores/rutas_controlador.php";
require_once "../modelos/rutas_modelo.php";

class RutasAjax
{
    /**
     * Listar rutas por sucursal (o todas si es administrador)
     */
    public function ajaxListarRutas()
    {
        try {
            // Log para debugging
            error_log("ajaxListarRutas: Iniciando función");
            error_log("ajaxListarRutas: Sesión usuario existe: " . (isset($_SESSION["usuario"]) ? "SI" : "NO"));
            
            // Verificar si es administrador
            $es_administrador = false;
            if (isset($_SESSION["usuario"])) {
                $es_administrador = (
                    isset($_SESSION["usuario"]->id_perfil_usuario) && $_SESSION["usuario"]->id_perfil_usuario == 1
                );
            }
            
            // Para administradores, usar null; para otros, usar su sucursal
            if ($es_administrador) {
                $sucursal_id = null; // Ver todas las rutas
                error_log("ajaxListarRutas: Usuario ADMINISTRADOR - Viendo todas las rutas");
            } else {
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;
                error_log("ajaxListarRutas: Usuario regular - Sucursal ID: " . $sucursal_id);
            }
            
            $respuesta = RutasControlador::ctrListarRutas($sucursal_id);
            error_log("ajaxListarRutas: Respuesta del controlador - Estado: " . ($respuesta['estado'] ?? 'NULL'));
            error_log("ajaxListarRutas: Respuesta del controlador - Mensaje: " . ($respuesta['mensaje'] ?? 'N/A'));
            
            if ($respuesta['estado'] === 'ok') {
                // Formato correcto para DataTables
                $resultado = [
                    'data' => $respuesta['data']
                ];
                error_log("ajaxListarRutas: Enviando " . count($respuesta['data']) . " rutas al cliente.");
                echo json_encode($resultado);
            } else {
                error_log("ajaxListarRutas: Error en respuesta del controlador: " . ($respuesta['mensaje'] ?? 'Sin mensaje'));
                http_response_code(500);
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje'] ?? 'Error al listar rutas desde el controlador'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxListarRutas: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener ruta por ID
     */
    public function ajaxObtenerRuta()
    {
        try {
            if (!isset($_POST['ruta_id']) || empty($_POST['ruta_id'])) {
                throw new Exception("ID de ruta requerido");
            }

            $ruta_id = $_POST['ruta_id'];
            
            if (!is_numeric($ruta_id)) {
                throw new Exception("ID de ruta inválido");
            }

            $ruta = RutasControlador::ctrObtenerRuta($ruta_id);
            
            if ($ruta) {
                echo json_encode([
                    'estado' => 'ok',
                    'data' => $ruta
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Ruta no encontrada'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxObtenerRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Guardar ruta (crear o actualizar)
     */
    public function ajaxGuardarRuta()
    {
        try {
            // Validar que se recibieron los datos necesarios
            $camposRequeridos = ['ruta_nombre', 'ruta_codigo', 'ruta_estado'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
                    throw new Exception("El campo {$campo} es requerido");
                }
            }

            // Obtener usuario de sesión
            $usuario_id = $_SESSION["usuario"]->id_usuario ?? 1;
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;

            // Preparar datos
            $datos = [
                'ruta_id' => $_POST['ruta_id'] ?? '',
                'ruta_nombre' => $_POST['ruta_nombre'] ?? '',
                'ruta_descripcion' => $_POST['ruta_descripcion'] ?? '',
                'ruta_codigo' => $_POST['ruta_codigo'] ?? '',
                'ruta_color' => $_POST['ruta_color'] ?? '#3498db',
                'sucursal_id' => $sucursal_id,
                'ruta_estado' => $_POST['ruta_estado'] ?? 'activa',
                'ruta_orden' => $_POST['ruta_orden'] ?? 0,
                'ruta_observaciones' => $_POST['ruta_observaciones'] ?? ''
            ];

            // Agregar usuario según operación
            if (empty($datos['ruta_id'])) {
                $datos['usuario_creacion'] = $usuario_id;
            } else {
                $datos['usuario_modificacion'] = $usuario_id;
            }

            // Llamar al controlador
            $respuesta = RutasControlador::ctrGuardarRuta($datos);
            
            // Manejar diferentes tipos de respuesta
            if ($respuesta === "ok") {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Ruta guardada correctamente'
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
            error_log("Error en ajaxGuardarRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar ruta
     */
    public function ajaxEliminarRuta()
    {
        try {
            if (!isset($_POST['ruta_id']) || empty($_POST['ruta_id'])) {
                throw new Exception("ID de ruta requerido");
            }

            $ruta_id = $_POST['ruta_id'];
            
            if (!is_numeric($ruta_id)) {
                throw new Exception("ID de ruta inválido");
            }

            $respuesta = RutasControlador::ctrEliminarRuta($ruta_id);
            
            if ($respuesta === 'ok') {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Ruta eliminada correctamente'
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
            error_log("Error en ajaxEliminarRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Listar rutas activas para select
     */
    public function ajaxListarRutasActivas()
    {
        try {
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;
            $rutas = RutasControlador::ctrListarRutasActivas($sucursal_id);
            echo json_encode($rutas);
        } catch (Exception $e) {
            error_log("Error en ajaxListarRutasActivas: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar rutas activas'
            ]);
        }
    }

    /**
     * Listar clientes de una ruta específica
     */
    public function ajaxListarClientesRuta()
    {
        try {
            if (!isset($_POST['ruta_id']) || empty($_POST['ruta_id'])) {
                throw new Exception("ID de ruta requerido");
            }

            $ruta_id = $_POST['ruta_id'];
            
            if (!is_numeric($ruta_id)) {
                throw new Exception("ID de ruta inválido");
            }

            $clientes = RutasControlador::ctrListarClientesRuta($ruta_id);
            echo json_encode($clientes);
        } catch (Exception $e) {
            error_log("Error en ajaxListarClientesRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar clientes de la ruta'
            ]);
        }
    }

    /**
     * Asignar cliente a ruta
     */
    public function ajaxAsignarClienteRuta()
    {
        try {
            // Validar datos requeridos
            if (!isset($_POST['cliente_id']) || !isset($_POST['ruta_id'])) {
                throw new Exception("Cliente y ruta son requeridos");
            }

            $usuario_id = $_SESSION["usuario"]->id_usuario ?? 1;

            $datos = [
                'cliente_id' => $_POST['cliente_id'],
                'ruta_id' => $_POST['ruta_id'],
                'orden_visita' => $_POST['orden_visita'] ?? 0,
                'direccion_especifica' => $_POST['direccion_especifica'] ?? '',
                'observaciones' => $_POST['observaciones'] ?? '',
                'usuario_asignacion' => $usuario_id
            ];

            $respuesta = RutasControlador::ctrAsignarClienteRuta($datos);
            
            if ($respuesta === 'ok') {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Cliente asignado correctamente'
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['estado']) && $respuesta['estado'] === 'error') {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error al asignar cliente'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxAsignarClienteRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remover cliente de ruta
     */
    public function ajaxRemoverClienteRuta()
    {
        try {
            if (!isset($_POST['cliente_ruta_id']) || empty($_POST['cliente_ruta_id'])) {
                throw new Exception("ID de asignación requerido");
            }

            $cliente_ruta_id = $_POST['cliente_ruta_id'];
            
            if (!is_numeric($cliente_ruta_id)) {
                throw new Exception("ID de asignación inválido");
            }

            $respuesta = RutasControlador::ctrRemoverClienteRuta($cliente_ruta_id);
            
            if ($respuesta === 'ok') {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Cliente removido correctamente'
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['estado']) && $respuesta['estado'] === 'error') {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error al remover cliente'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxRemoverClienteRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Actualizar orden de visita
     */
    public function ajaxActualizarOrdenVisita()
    {
        try {
            if (!isset($_POST['cliente_ruta_id']) || !isset($_POST['nuevo_orden'])) {
                throw new Exception("ID de asignación y nuevo orden son requeridos");
            }

            $cliente_ruta_id = $_POST['cliente_ruta_id'];
            $nuevo_orden = $_POST['nuevo_orden'];
            
            $respuesta = RutasControlador::ctrActualizarOrdenVisita($cliente_ruta_id, $nuevo_orden);
            
            if ($respuesta === 'ok') {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Orden actualizado correctamente'
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['estado']) && $respuesta['estado'] === 'error') {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error al actualizar orden'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxActualizarOrdenVisita: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Listar usuarios disponibles para asignar a rutas
     */
    public function ajaxListarUsuariosDisponibles()
    {
        try {
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;
            $usuarios = RutasControlador::ctrListarUsuariosDisponibles($sucursal_id);
            echo json_encode($usuarios);
        } catch (Exception $e) {
            error_log("Error en ajaxListarUsuariosDisponibles: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar usuarios disponibles'
            ]);
        }
    }

    /**
     * Listar todos los usuarios con acceso (para administradores)
     */
    public function ajaxListarTodosUsuariosConAcceso()
    {
        try {
            // Verificar que el usuario sea administrador
            $perfil_usuario = $_SESSION["usuario"]->id_perfil_usuario ?? 2;
            if ($perfil_usuario != 1) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No tienes permisos para realizar esta acción'
                ]);
                return;
            }

            $usuarios = RutasControlador::ctrListarTodosUsuariosConAcceso();
            
            echo json_encode([
                'estado' => 'ok',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al obtener usuarios: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Asignar usuario a ruta
     */
    public function ajaxAsignarUsuarioRuta()
    {
        try {
            // Validar datos requeridos
            if (!isset($_POST['usuario_id']) || !isset($_POST['ruta_id'])) {
                throw new Exception("Usuario y ruta son requeridos");
            }

            $usuario_asignacion = $_SESSION["usuario"]->id_usuario ?? 1;

            $datos = [
                'usuario_id' => $_POST['usuario_id'],
                'ruta_id' => $_POST['ruta_id'],
                'tipo_asignacion' => $_POST['tipo_asignacion'] ?? 'responsable',
                'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-d'),
                'fecha_fin' => $_POST['fecha_fin'] ?? null,
                'observaciones' => $_POST['observaciones'] ?? '',
                'usuario_asignacion' => $usuario_asignacion
            ];

            $respuesta = RutasControlador::ctrAsignarUsuarioRuta($datos);
            
            if ($respuesta === 'ok') {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Usuario asignado correctamente'
                ]);
            } elseif (is_array($respuesta) && isset($respuesta['estado']) && $respuesta['estado'] === 'error') {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => $respuesta['mensaje']
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error al asignar usuario'
                ]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxAsignarUsuarioRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Listar usuarios asignados a una ruta
     */
    public function ajaxListarUsuariosAsignados()
    {
        try {
            if (!isset($_POST['ruta_id'])) {
                throw new Exception("ID de ruta es requerido");
            }

            $ruta_id = $_POST['ruta_id'];
            $usuarios = RutasControlador::ctrListarUsuariosAsignados($ruta_id);
            
            echo json_encode([
                'estado' => 'ok',
                'data' => $usuarios
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al obtener usuarios asignados: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remover usuario de ruta
     */
    public function ajaxRemoverUsuarioRuta()
    {
        try {
            if (!isset($_POST['usuario_ruta_id'])) {
                throw new Exception("ID de asignación es requerido");
            }

            $usuario_ruta_id = $_POST['usuario_ruta_id'];
            $resultado = RutasControlador::ctrRemoverUsuarioRuta($usuario_ruta_id);

            if ($resultado === "ok") {
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Usuario removido correctamente'
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => is_array($resultado) ? $resultado[2] : $resultado
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al remover usuario: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Obtener estadísticas de una ruta
     */
    public function ajaxObtenerEstadisticasRuta()
    {
        try {
            if (!isset($_POST['ruta_id']) || empty($_POST['ruta_id'])) {
                throw new Exception("ID de ruta requerido");
            }

            $ruta_id = $_POST['ruta_id'];
            
            if (!is_numeric($ruta_id)) {
                throw new Exception("ID de ruta inválido");
            }

            $respuesta = RutasControlador::ctrObtenerEstadisticasRuta($ruta_id);
            
            // El controlador ahora devuelve un array con estado
            if (is_array($respuesta) && isset($respuesta['estado'])) {
                echo json_encode($respuesta);
            } else {
                // Compatibilidad con respuesta antigua
                if ($respuesta) {
                echo json_encode([
                    'estado' => 'ok',
                        'data' => $respuesta
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se pudieron obtener las estadísticas'
                ]);
                }
            }
        } catch (Exception $e) {
            error_log("Error en ajaxObtenerEstadisticasRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error interno del servidor: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Listar clientes sin asignar a rutas
     */
    public function ajaxListarClientesSinRuta()
    {
        try {
            $sucursal_id = $_SESSION["usuario"]->sucursal_id ?? 1;
            $clientes = RutasControlador::ctrListarClientesSinRuta($sucursal_id);
            echo json_encode($clientes);
        } catch (Exception $e) {
            error_log("Error en ajaxListarClientesSinRuta: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar clientes sin ruta'
            ]);
        }
    }

    /**
     * Listar rutas por sucursal para combos
     */
    public function ajaxListarRutasPorSucursal()
    {
        try {
            if (!isset($_POST['id_sucursal']) || !is_numeric($_POST['id_sucursal'])) {
                throw new Exception("ID de sucursal inválido o no proporcionado.");
            }
            
            $id_sucursal = $_POST['id_sucursal'];
            $rutas = RutasControlador::ctrListarRutasPorSucursal($id_sucursal);
            
            echo json_encode($rutas);

        } catch (Exception $e) {
            error_log("Error en ajaxListarRutasPorSucursal: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar las rutas por sucursal: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Listar sucursales para combos simples
     */
    public function ajaxListarSucursales()
    {
        try {
            $controlador = new RutasControlador();
            $sucursales = $controlador->ctrListarSucursales();
            
            if ($sucursales) {
                echo json_encode($sucursales);
            } else {
                echo json_encode([]);
            }
        } catch (Exception $e) {
            error_log("Error en ajaxListarSucursales: " . $e->getMessage());
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al listar sucursales: ' . $e->getMessage()
            ]);
        }
    }
}

// Función para validar y sanitizar la acción
function validarAccionRuta($accion) {
    $accionesPermitidas = [
        'listar', 'obtener', 'guardar', 'eliminar', 
        'listar_activas', 'listar_clientes', 'asignar_cliente', 
        'remover_cliente', 'actualizar_orden', 'listar_usuarios_disponibles',
        'listar_usuarios_asignados', 'asignar_usuario', 'remover_usuario',
        'obtener_estadisticas', 'listar_clientes_sin_ruta',
        'listar_rutas_por_sucursal', 'listar_sucursales' // <-- Nuevas acciones
    ];
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

    $accion = validarAccionRuta($accion_recibida);

    if (!$accion) {
        throw new Exception("Acción no válida: " . $accion_recibida);
    }

    $ajax = new RutasAjax();

    switch ($accion) {
        case 'listar':
            $ajax->ajaxListarRutas();
            break;
        case 'listar_rutas_por_sucursal': // <-- Nuevo caso
            $ajax->ajaxListarRutasPorSucursal();
            break;
        case 'obtener':
            $ajax->ajaxObtenerRuta();
            break;
        case 'guardar':
            $ajax->ajaxGuardarRuta();
            break;
        case 'eliminar':
            $ajax->ajaxEliminarRuta();
            break;
        case 'listar_activas':
            $ajax->ajaxListarRutasActivas();
            break;
        case 'listar_clientes':
            $ajax->ajaxListarClientesRuta();
            break;
        case 'asignar_cliente':
            $ajax->ajaxAsignarClienteRuta();
            break;
        case 'remover_cliente':
            $ajax->ajaxRemoverClienteRuta();
            break;
        case 'actualizar_orden':
            $ajax->ajaxActualizarOrdenVisita();
            break;
        case 'listar_usuarios_disponibles':
            $ajax->ajaxListarUsuariosDisponibles();
            break;
        case 'listar_usuarios_asignados':
            $ajax->ajaxListarUsuariosAsignados();
            break;
        case 'asignar_usuario':
            $ajax->ajaxAsignarUsuarioRuta();
            break;
        case 'remover_usuario':
            $ajax->ajaxRemoverUsuarioRuta();
            break;
        case 'obtener_estadisticas':
            $ajax->ajaxObtenerEstadisticasRuta();
            break;
        case 'listar_clientes_sin_ruta':
            $ajax->ajaxListarClientesSinRuta();
            break;
        case 'listar_sucursales':
            $ajax->ajaxListarSucursales();
            break;
    }
} catch (Exception $e) {
    error_log("Error en el manejador de peticiones de RutasAjax: " . $e->getMessage());
    echo json_encode(['estado' => 'error', 'mensaje' => 'Error fatal en la petición: ' . $e->getMessage()]);
} 