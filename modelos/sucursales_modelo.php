<?php

require_once "conexion.php";

class SucursalModelo
{
    static public function mdlListarSucursales()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id, nombre, direccion, telefono, codigo, estado FROM sucursales ORDER BY nombre");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar sucursales: " . $e->getMessage());
            return [];
        }
    }

    static public function mdlGuardarSucursal($datos)
    {
        try {
            $pdo = Conexion::conectar();
            
            // El campo creado_por se podría obtener de la sesión del usuario
            $creado_por = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1;

            if (empty($datos['id'])) {
                // Nuevo registro
                $stmt = $pdo->prepare("INSERT INTO sucursales (empresa_id, nombre, direccion, telefono, codigo, estado, creado_por, fecha_creacion) VALUES (:empresa_id, :nombre, :direccion, :telefono, :codigo, :estado, :creado_por, NOW())");
                $stmt->bindParam(":creado_por", $creado_por, PDO::PARAM_INT);
            } else {
                // Actualizar registro
                $stmt = $pdo->prepare("UPDATE sucursales SET empresa_id = :empresa_id, nombre = :nombre, direccion = :direccion, telefono = :telefono, codigo = :codigo, estado = :estado, fecha_modificacion = NOW() WHERE id = :id");
                $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
            }

            $stmt->bindParam(":empresa_id", $datos['empresa_id'], PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $datos['nombre'], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $datos['direccion'], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $datos['telefono'], PDO::PARAM_STR);
            $stmt->bindParam(":codigo", $datos['codigo'], PDO::PARAM_STR);
            $stmt->bindParam(":estado", $datos['estado'], PDO::PARAM_STR);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al guardar sucursal: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    static public function mdlEliminarSucursal($id)
    {
        try {
            $pdo = Conexion::conectar();
            
            // Verificar si la sucursal está en uso
            $tablas = ['usuarios', 'clientes', 'prestamo_cabecera', 'caja', 'movimientos'];
            foreach ($tablas as $tabla) {
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM $tabla WHERE sucursal_id = :id");
                    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                    $stmt->execute();
                    if ($stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0) {
                        return "en_uso";
                    }
                } catch (Exception $e) {
                    // Si la tabla o la columna no existe, simplemente continuamos
                    continue;
                }
            }

            $stmt = $pdo->prepare("DELETE FROM sucursales WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                return $stmt->errorInfo();
            }
        } catch (Exception $e) {
            error_log("Error al eliminar sucursal: " . $e->getMessage());
            return ["Error", "23000", "Error interno del servidor"];
        }
    }

    /**
     * Verificar si existe duplicado de código o nombre
     */
    static public function mdlVerificarDuplicados($codigo, $nombre, $id = null)
    {
        try {
            $pdo = Conexion::conectar();
            
            $resultado = [
                'codigo_duplicado' => false,
                'nombre_duplicado' => false
            ];

            // Verificar código duplicado
            if ($id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sucursales WHERE codigo = :codigo AND id != :id");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sucursales WHERE codigo = :codigo");
            }
            $stmt->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $stmt->execute();
            $resultado['codigo_duplicado'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;

            // Verificar nombre duplicado
            if ($id) {
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sucursales WHERE nombre = :nombre AND id != :id");
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sucursales WHERE nombre = :nombre");
            }
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->execute();
            $resultado['nombre_duplicado'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] > 0;

            return $resultado;
        } catch (Exception $e) {
            error_log("Error al verificar duplicados: " . $e->getMessage());
            return [
                'codigo_duplicado' => false,
                'nombre_duplicado' => false
            ];
        }
    }

    /**
     * Obtener sucursal por ID
     */
    static public function mdlObtenerSucursal($id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM sucursales WHERE id = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al obtener sucursal: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtener sucursales activas para selects
     */
    static public function mdlListarSucursalesActivas()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id, nombre, codigo FROM sucursales WHERE estado = 'activa' ORDER BY nombre");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar sucursales activas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Listar sucursales activas con información completa para combos mejorados
     */
    static public function mdlListarSucursalesActivasCompletas()
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    s.id as sucursal_id,
                    s.nombre as sucursal_nombre,
                    s.codigo as sucursal_codigo,
                    s.direccion as sucursal_direccion,
                    s.telefono as sucursal_telefono,
                    CONCAT(s.codigo, ' - ', s.nombre) as texto_completo,
                    CASE 
                        WHEN s.direccion IS NOT NULL AND s.direccion != '' 
                        THEN CONCAT(s.codigo, ' - ', s.nombre, ' (', s.direccion, ')') 
                        ELSE CONCAT(s.codigo, ' - ', s.nombre)
                    END as texto_descriptivo,
                    (SELECT COUNT(*) FROM rutas WHERE sucursal_id = s.id AND ruta_estado = 'activa') as total_rutas,
                    (SELECT COUNT(DISTINCT u.id_usuario) FROM usuarios u WHERE u.sucursal_id = s.id AND u.estado = 1) as total_usuarios
                FROM sucursales s 
                WHERE s.estado = 'activa' 
                ORDER BY s.nombre
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar sucursales activas completas: " . $e->getMessage());
            return [];
        }
    }
} 