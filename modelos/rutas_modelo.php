<?php

require_once "conexion.php";

class RutasModelo
{
    /**
     * Listar todas las rutas por sucursal (o todas si es administrador)
     */
    static public function mdlListarRutas($sucursal_id)
    {
        try {
            $pdo = Conexion::conectar();
            
            // Verificar si la tabla rutas existe
            $stmt = $pdo->prepare("SHOW TABLES LIKE 'rutas'");
            $stmt->execute();
            
            if (!$stmt->fetch()) {
                error_log("La tabla 'rutas' no existe");
                return [];
            }
            
            // Verificar si el usuario es administrador (perfil_id = 1 o sucursal_id es NULL)
            $es_administrador = false;
            if (isset($_SESSION['usuario'])) {
                $es_administrador = (
                    isset($_SESSION['usuario']->id_perfil_usuario) && $_SESSION['usuario']->id_perfil_usuario == 1
                ) || (
                    $sucursal_id === null || $sucursal_id === 0
                );
            }
            
            // Construir consulta base
            $sql = "SELECT 
                r.ruta_id,
                r.ruta_nombre,
                r.ruta_descripcion,
                r.ruta_codigo,
                r.ruta_color,
                r.ruta_estado,
                r.ruta_orden,
                r.ruta_observaciones,
                r.sucursal_id,
                COALESCE(s.nombre, 'Sucursal Principal') as sucursal_nombre,
                COALESCE(COUNT(DISTINCT cr.cliente_id), 0) as total_clientes,
                COALESCE(COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END), 0) as clientes_activos,
                COALESCE(GROUP_CONCAT(DISTINCT u.usuario SEPARATOR ', '), '') as responsables,
                r.fecha_creacion,
                COALESCE(uc.usuario, 'Sistema') as usuario_creacion_nombre
            FROM rutas r
            LEFT JOIN sucursales s ON r.sucursal_id = s.id
            LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
            LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo' AND ur.tipo_asignacion = 'responsable'
            LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
            LEFT JOIN usuarios uc ON r.usuario_creacion = uc.id_usuario";
            
            // Agregar filtro de sucursal solo si NO es administrador
            if (!$es_administrador && $sucursal_id) {
                $sql .= " WHERE r.sucursal_id = :sucursal_id";
            }
            
            $sql .= " GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_descripcion, r.ruta_codigo, r.ruta_color, 
                     r.ruta_estado, r.ruta_orden, r.ruta_observaciones, r.sucursal_id, s.nombre,
                     r.fecha_creacion, uc.usuario
            ORDER BY s.nombre ASC, r.ruta_orden ASC, r.ruta_nombre ASC";
            
            $stmt = $pdo->prepare($sql);
            
            // Bindear parámetro solo si NO es administrador
            if (!$es_administrador && $sucursal_id) {
                $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($resultado === false) {
                error_log("Error al obtener resultados de rutas");
                return [];
            }
            
            return $resultado;
        } catch (Exception $e) {
            error_log("Error en mdlListarRutas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener ruta por ID
     */
    static public function mdlObtenerRuta($ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT r.*, s.nombre as sucursal_nombre 
                                                   FROM rutas r 
                                                   INNER JOIN sucursales s ON r.sucursal_id = s.id 
                                                   WHERE r.ruta_id = :ruta_id");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener ruta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Guardar o actualizar ruta
     */
    static public function mdlGuardarRuta($datos)
    {
        try {
            $pdo = Conexion::conectar();
            
            if (empty($datos['ruta_id'])) {
                // Crear nueva ruta
                $stmt = $pdo->prepare("INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, 
                                                          sucursal_id, ruta_estado, ruta_orden, ruta_observaciones, 
                                                          usuario_creacion) 
                                       VALUES (:ruta_nombre, :ruta_descripcion, :ruta_codigo, :ruta_color, 
                                               :sucursal_id, :ruta_estado, :ruta_orden, :ruta_observaciones, 
                                               :usuario_creacion)");
                $stmt->bindParam(":usuario_creacion", $datos['usuario_creacion'], PDO::PARAM_INT);
            } else {
                // Actualizar ruta existente
                $stmt = $pdo->prepare("UPDATE rutas SET 
                                       ruta_nombre = :ruta_nombre, 
                                       ruta_descripcion = :ruta_descripcion, 
                                       ruta_codigo = :ruta_codigo, 
                                       ruta_color = :ruta_color, 
                                       ruta_estado = :ruta_estado, 
                                       ruta_orden = :ruta_orden, 
                                       ruta_observaciones = :ruta_observaciones,
                                       usuario_modificacion = :usuario_modificacion
                                       WHERE ruta_id = :ruta_id");
                $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
                $stmt->bindParam(":usuario_modificacion", $datos['usuario_modificacion'], PDO::PARAM_INT);
            }

            $stmt->bindParam(":ruta_nombre", $datos['ruta_nombre'], PDO::PARAM_STR);
            $stmt->bindParam(":ruta_descripcion", $datos['ruta_descripcion'], PDO::PARAM_STR);
            $stmt->bindParam(":ruta_codigo", $datos['ruta_codigo'], PDO::PARAM_STR);
            $stmt->bindParam(":ruta_color", $datos['ruta_color'], PDO::PARAM_STR);
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
            $stmt->bindParam(":ruta_estado", $datos['ruta_estado'], PDO::PARAM_STR);
            $stmt->bindParam(":ruta_orden", $datos['ruta_orden'], PDO::PARAM_INT);
            $stmt->bindParam(":ruta_observaciones", $datos['ruta_observaciones'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al guardar ruta: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Eliminar ruta
     */
    static public function mdlEliminarRuta($ruta_id)
    {
        try {
            $pdo = Conexion::conectar();
            
            // Verificar si la ruta tiene clientes asignados
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM clientes_rutas WHERE ruta_id = :ruta_id AND estado = 'activo'");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['total'] > 0) {
                return "en_uso";
            }

            // Verificar si la ruta tiene usuarios asignados
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM usuarios_rutas WHERE ruta_id = :ruta_id AND estado = 'activo'");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['total'] > 0) {
                return "usuarios_asignados";
            }

            // Eliminar la ruta
            $stmt = $pdo->prepare("DELETE FROM rutas WHERE ruta_id = :ruta_id");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al eliminar ruta: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Verificar duplicados de código de ruta
     */
    static public function mdlVerificarDuplicadoRuta($ruta_codigo, $sucursal_id, $ruta_id = null)
    {
        try {
            $pdo = Conexion::conectar();
            
            if ($ruta_id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM rutas WHERE ruta_codigo = :ruta_codigo AND sucursal_id = :sucursal_id AND ruta_id != :ruta_id");
                $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM rutas WHERE ruta_codigo = :ruta_codigo AND sucursal_id = :sucursal_id");
            }
            
            $stmt->bindParam(":ruta_codigo", $ruta_codigo, PDO::PARAM_STR);
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['total'] > 0;
        } catch (Exception $e) {
            error_log("Error al verificar duplicado de ruta: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Listar rutas activas para select
     */
    static public function mdlListarRutasActivas($sucursal_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT ruta_id, ruta_nombre, ruta_codigo, ruta_color 
                                                   FROM rutas 
                                                   WHERE sucursal_id = :sucursal_id AND ruta_estado = 'activa' 
                                                   ORDER BY ruta_orden ASC, ruta_nombre ASC");
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar rutas activas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar rutas activas con información completa para combos mejorados
     */
    static public function mdlListarRutasActivasCompletas($sucursal_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    r.ruta_id,
                    r.ruta_nombre,
                    r.ruta_codigo,
                    r.ruta_color,
                    r.ruta_descripcion,
                    r.ruta_orden,
                    CONCAT(r.ruta_codigo, ' - ', r.ruta_nombre) as texto_completo,
                    CASE 
                        WHEN r.ruta_descripcion IS NOT NULL AND r.ruta_descripcion != '' 
                        THEN CONCAT(r.ruta_codigo, ' - ', r.ruta_nombre, ' (', r.ruta_descripcion, ')') 
                        ELSE CONCAT(r.ruta_codigo, ' - ', r.ruta_nombre)
                    END as texto_descriptivo,
                    (SELECT COUNT(*) FROM clientes_rutas WHERE ruta_id = r.ruta_id AND estado = 'activo') as total_clientes,
                    (SELECT COUNT(*) FROM usuarios_rutas WHERE ruta_id = r.ruta_id AND estado = 'activo') as total_usuarios,
                    COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre
                FROM rutas r
                LEFT JOIN sucursales s ON r.sucursal_id = s.id
                WHERE r.sucursal_id = :sucursal_id AND r.ruta_estado = 'activa' 
                ORDER BY r.ruta_orden ASC, r.ruta_nombre ASC
            ");
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar rutas activas completas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar clientes de una ruta específica
     */
    static public function mdlListarClientesRuta($ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_LISTAR_CLIENTES_RUTA(:ruta_id)');
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar clientes de ruta: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Asignar cliente a ruta
     */
    static public function mdlAsignarClienteRuta($datos)
    {
        try {
            $pdo = Conexion::conectar();
            
            // Verificar si el cliente ya está asignado a esta ruta
            $stmt = $pdo->prepare("SELECT cliente_ruta_id FROM clientes_rutas WHERE cliente_id = :cliente_id AND ruta_id = :ruta_id");
            $stmt->bindParam(":cliente_id", $datos['cliente_id'], PDO::PARAM_INT);
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                return "ya_asignado";
            }

            // Asignar cliente a la ruta
            $stmt = $pdo->prepare("INSERT INTO clientes_rutas (cliente_id, ruta_id, orden_visita, 
                                                               direccion_especifica, observaciones, 
                                                               usuario_asignacion) 
                                   VALUES (:cliente_id, :ruta_id, :orden_visita, 
                                           :direccion_especifica, :observaciones, 
                                           :usuario_asignacion)");
            
            $stmt->bindParam(":cliente_id", $datos['cliente_id'], PDO::PARAM_INT);
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
            $stmt->bindParam(":orden_visita", $datos['orden_visita'], PDO::PARAM_INT);
            $stmt->bindParam(":direccion_especifica", $datos['direccion_especifica'], PDO::PARAM_STR);
            $stmt->bindParam(":observaciones", $datos['observaciones'], PDO::PARAM_STR);
            $stmt->bindParam(":usuario_asignacion", $datos['usuario_asignacion'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al asignar cliente a ruta: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Remover cliente de ruta
     */
    static public function mdlRemoverClienteRuta($cliente_ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM clientes_rutas WHERE cliente_ruta_id = :cliente_ruta_id");
            $stmt->bindParam(":cliente_ruta_id", $cliente_ruta_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al remover cliente de ruta: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Actualizar orden de visita de cliente en ruta
     */
    static public function mdlActualizarOrdenVisita($cliente_ruta_id, $nuevo_orden)
    {
        try {
            $stmt = Conexion::conectar()->prepare("UPDATE clientes_rutas SET orden_visita = :nuevo_orden WHERE cliente_ruta_id = :cliente_ruta_id");
            $stmt->bindParam(":nuevo_orden", $nuevo_orden, PDO::PARAM_INT);
            $stmt->bindParam(":cliente_ruta_id", $cliente_ruta_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al actualizar orden de visita: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Listar usuarios disponibles para asignar a rutas
     */
    static public function mdlListarUsuariosDisponibles($sucursal_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_LISTAR_USUARIOS_DISPONIBLES(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar usuarios disponibles: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar todos los usuarios con acceso (para administradores) - CATÁLOGO UNIFICADO
     */
    static public function mdlListarTodosUsuariosConAcceso()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT 
                                                    u.id_usuario,
                                                    u.nombre_usuario,
                                                    u.apellido_usuario,
                                                    u.usuario,
                                                    u.sucursal_id,
                                                    s.nombre as sucursal_nombre,
                                                    s.codigo as sucursal_codigo,
                                                    p.descripcion as perfil_nombre,
                                                    u.estado,
                                                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as nombre_completo,
                                                    
                                                    -- Información adicional para el catálogo
                                                    (SELECT COUNT(*) FROM usuarios_rutas ur 
                                                     WHERE ur.usuario_id = u.id_usuario 
                                                     AND ur.estado = 'activo') as rutas_asignadas,
                                                     
                                                    (SELECT GROUP_CONCAT(DISTINCT r.ruta_nombre SEPARATOR ', ') 
                                                     FROM usuarios_rutas ur 
                                                     INNER JOIN rutas r ON ur.ruta_id = r.ruta_id 
                                                     WHERE ur.usuario_id = u.id_usuario 
                                                     AND ur.estado = 'activo') as rutas_nombres,
                                                     
                                                    -- Última actividad (última asignación)
                                                    (SELECT MAX(ur.fecha_asignacion) 
                                                     FROM usuarios_rutas ur 
                                                     WHERE ur.usuario_id = u.id_usuario) as ultima_asignacion
                                                     
                                                FROM usuarios u
                                                LEFT JOIN sucursales s ON u.sucursal_id = s.id
                                                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                                                WHERE u.estado = 1
                                                ORDER BY 
                                                    s.nombre ASC,
                                                    p.descripcion DESC, 
                                                    u.nombre_usuario ASC, 
                                                    u.apellido_usuario ASC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar todos los usuarios con acceso: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar usuarios asignados a una ruta
     */
    static public function mdlListarUsuariosAsignados($ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT 
                                                    ur.usuario_ruta_id,
                                                    ur.usuario_id,
                                                    ur.ruta_id,
                                                    ur.tipo_asignacion,
                                                    ur.fecha_asignacion,
                                                    ur.fecha_inicio,
                                                    ur.fecha_fin,
                                                    ur.estado,
                                                    ur.observaciones,
                                                    u.nombre_usuario,
                                                    u.apellido_usuario,
                                                    u.usuario,
                                                    u.sucursal_id,
                                                    s.nombre as sucursal_nombre,
                                                    p.descripcion as perfil_nombre,
                                                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as nombre_completo
                                                FROM usuarios_rutas ur
                                                INNER JOIN usuarios u ON ur.usuario_id = u.id_usuario
                                                LEFT JOIN sucursales s ON u.sucursal_id = s.id
                                                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                                                WHERE ur.ruta_id = :ruta_id
                                                ORDER BY ur.fecha_asignacion DESC");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar usuarios asignados: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar usuarios asignados a una ruta con información completa para combos mejorados
     */
    static public function mdlListarUsuariosAsignadosCompletos($ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    ur.usuario_ruta_id,
                    ur.usuario_id,
                    ur.ruta_id,
                    ur.tipo_asignacion,
                    ur.fecha_asignacion,
                    ur.estado as estado_asignacion,
                    u.nombre_usuario,
                    u.apellido_usuario,
                    u.usuario,
                    u.sucursal_id,
                    u.estado as estado_usuario,
                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as nombre_completo,
                    CONCAT(u.usuario, ' - ', u.nombre_usuario, ' ', u.apellido_usuario) as texto_completo,
                    CASE 
                        WHEN s.nombre IS NOT NULL 
                        THEN CONCAT(u.usuario, ' - ', u.nombre_usuario, ' ', u.apellido_usuario, ' (', s.nombre, ')') 
                        ELSE CONCAT(u.usuario, ' - ', u.nombre_usuario, ' ', u.apellido_usuario)
                    END as texto_descriptivo,
                    COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
                    COALESCE(p.descripcion, 'Sin perfil') as perfil_nombre,
                    ur.tipo_asignacion as tipo_usuario,
                    DATE_FORMAT(ur.fecha_asignacion, '%d/%m/%Y') as fecha_asignacion_formato,
                    CASE ur.estado 
                        WHEN 'activo' THEN '✅ Activo'
                        WHEN 'inactivo' THEN '❌ Inactivo'
                        ELSE '❓ Sin estado'
                    END as estado_texto
                FROM usuarios_rutas ur
                INNER JOIN usuarios u ON ur.usuario_id = u.id_usuario
                LEFT JOIN sucursales s ON u.sucursal_id = s.id
                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                WHERE ur.ruta_id = :ruta_id AND ur.estado = 'activo' AND p.descripcion = 'Cobrador'
                ORDER BY ur.tipo_asignacion DESC, u.nombre_usuario ASC
            ");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar usuarios asignados completos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Remover usuario de ruta
     */
    static public function mdlRemoverUsuarioRuta($usuario_ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("DELETE FROM usuarios_rutas WHERE usuario_ruta_id = :usuario_ruta_id");
            $stmt->bindParam(":usuario_ruta_id", $usuario_ruta_id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al remover usuario de ruta: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Asignar usuario a ruta
     */
    static public function mdlAsignarUsuarioRuta($datos)
    {
        try {
            $pdo = Conexion::conectar();
            
            // Verificar si el usuario ya está asignado a esta ruta
            $stmt = $pdo->prepare("SELECT usuario_ruta_id FROM usuarios_rutas WHERE usuario_id = :usuario_id AND ruta_id = :ruta_id AND estado = 'activo'");
            $stmt->bindParam(":usuario_id", $datos['usuario_id'], PDO::PARAM_INT);
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->fetch()) {
                return "ya_asignado";
            }

            // Asignar usuario a la ruta
            $stmt = $pdo->prepare("INSERT INTO usuarios_rutas (usuario_id, ruta_id, tipo_asignacion, 
                                                               fecha_inicio, fecha_fin, observaciones, 
                                                               usuario_asignacion) 
                                   VALUES (:usuario_id, :ruta_id, :tipo_asignacion, 
                                           :fecha_inicio, :fecha_fin, :observaciones, 
                                           :usuario_asignacion)");
            
            $stmt->bindParam(":usuario_id", $datos['usuario_id'], PDO::PARAM_INT);
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
            $stmt->bindParam(":tipo_asignacion", $datos['tipo_asignacion'], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);
            $stmt->bindParam(":observaciones", $datos['observaciones'], PDO::PARAM_STR);
            $stmt->bindParam(":usuario_asignacion", $datos['usuario_asignacion'], PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al asignar usuario a ruta: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Obtener estadísticas de una ruta
     */
    static public function mdlObtenerEstadisticasRuta($ruta_id)
    {
        try {
            $pdo = Conexion::conectar();
            
            // Obtener información básica de la ruta
            $stmt = $pdo->prepare("SELECT ruta_nombre, ruta_codigo FROM rutas WHERE ruta_id = :ruta_id");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $ruta = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$ruta) {
                return null;
            }
            
            // Obtener estadísticas de clientes
            $stmt = $pdo->prepare("SELECT 
                COUNT(DISTINCT cr.cliente_id) as total_clientes,
                COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END) as clientes_activos,
                COUNT(DISTINCT CASE WHEN cr.estado = 'inactivo' THEN cr.cliente_id END) as clientes_inactivos
            FROM clientes_rutas cr
            WHERE cr.ruta_id = :ruta_id");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $clientes = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Obtener estadísticas de préstamos (si existen las tablas)
            $prestamos = [
                'prestamos_activos' => 0,
                'saldo_total_pendiente' => 0,
                'cuotas_vencidas' => 0,
                'cuotas_proximas_7_dias' => 0
            ];
            
            try {
                $stmt = $pdo->prepare("SELECT 
                    COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
                    COALESCE(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END), 0) as saldo_total_pendiente,
                    COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
                    COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as cuotas_proximas_7_dias
                FROM clientes_rutas cr
                INNER JOIN clientes c ON cr.cliente_id = c.cliente_id
                LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
                LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
                WHERE cr.ruta_id = :ruta_id AND cr.estado = 'activo'");
                $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
                $stmt->execute();
                $prestamos = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                // Si no existen las tablas de préstamos, usar valores por defecto
                error_log("Las tablas de préstamos no existen o hay error: " . $e->getMessage());
            }
            
            // Obtener usuarios asignados
            $stmt = $pdo->prepare("SELECT 
                COUNT(DISTINCT ur.usuario_id) as usuarios_asignados
            FROM usuarios_rutas ur
            WHERE ur.ruta_id = :ruta_id AND ur.estado = 'activo'");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $usuarios = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Combinar todos los resultados
            $estadisticas = array_merge($ruta, $clientes, $prestamos, $usuarios);
            
            // Asegurar que todos los valores numéricos sean números
            $estadisticas['total_clientes'] = (int)($estadisticas['total_clientes'] ?? 0);
            $estadisticas['clientes_activos'] = (int)($estadisticas['clientes_activos'] ?? 0);
            $estadisticas['clientes_inactivos'] = (int)($estadisticas['clientes_inactivos'] ?? 0);
            $estadisticas['prestamos_activos'] = (int)($estadisticas['prestamos_activos'] ?? 0);
            $estadisticas['saldo_total_pendiente'] = (float)($estadisticas['saldo_total_pendiente'] ?? 0);
            $estadisticas['cuotas_vencidas'] = (int)($estadisticas['cuotas_vencidas'] ?? 0);
            $estadisticas['cuotas_proximas_7_dias'] = (int)($estadisticas['cuotas_proximas_7_dias'] ?? 0);
            $estadisticas['usuarios_asignados'] = (int)($estadisticas['usuarios_asignados'] ?? 0);
            
            return $estadisticas;
        } catch (Exception $e) {
            error_log("Error al obtener estadísticas de ruta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar clientes sin asignar a rutas
     */
    static public function mdlListarClientesSinRuta($sucursal_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT c.cliente_id, c.cliente_nombres, c.cliente_dni, 
                                                          c.cliente_cel, c.cliente_direccion, c.cliente_estado_prestamo
                                                   FROM clientes c
                                                   LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id AND cr.estado = 'activo'
                                                   WHERE c.sucursal_id = :sucursal_id 
                                                   AND c.cliente_estatus = '1'
                                                   AND cr.cliente_id IS NULL
                                                   ORDER BY c.cliente_nombres ASC");
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar clientes sin ruta: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtener próximo número de orden para una ruta
     */
    static public function mdlObtenerProximoOrden($ruta_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT COALESCE(MAX(orden_visita), 0) + 1 as proximo_orden FROM clientes_rutas WHERE ruta_id = :ruta_id");
            $stmt->bindParam(":ruta_id", $ruta_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            return $resultado['proximo_orden'];
        } catch (Exception $e) {
            error_log("Error al obtener próximo orden: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Listar rutas por sucursal para reportes financieros
     */
    static public function mdlListarRutasPorSucursal($id_sucursal)
    {
        try {
            $stmt = Conexion::conectar()->prepare(
                "SELECT 
                    ruta_id as id, 
                    ruta_nombre as nombre_ruta,
                    ruta_codigo as codigo,
                    ruta_descripcion as descripcion,
                    ruta_color as color
                 FROM rutas 
                 WHERE sucursal_id = :id_sucursal AND ruta_estado = 'activa'
                 ORDER BY ruta_orden ASC, ruta_nombre ASC"
            );

            $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en mdlListarRutasPorSucursal: " . $e->getMessage());
            return []; // Retorna un array vacío en caso de error
        }
    }

    /**
     * Listar todas las sucursales para combos simples
     */
    static public function mdlListarSucursales()
    {
        try {
            $pdo = Conexion::conectar();
            
            // Verificar si la tabla sucursales existe
            $stmt = $pdo->prepare("SHOW TABLES LIKE 'sucursales'");
            $stmt->execute();
            
            if (!$stmt->fetch()) {
                error_log("La tabla 'sucursales' no existe");
                return [];
            }
            
            $stmt = $pdo->prepare(
                "SELECT 
                    id as sucursal_id,
                    nombre as sucursal_nombre,
                    codigo as sucursal_codigo
                 FROM sucursales 
                 WHERE estado = 1 
                 ORDER BY nombre ASC"
            );

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log("Error en mdlListarSucursales: " . $e->getMessage());
            return [];
        }
    }
} 