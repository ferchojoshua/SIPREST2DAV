<?php

class SucursalControlador
{
    /**
     * Listar todas las sucursales
     */
    static public function ctrListarSucursales()
    {
        return SucursalModelo::mdlListarSucursales();
    }

    /**
     * Listar sucursales activas para combos
     */
    static public function ctrListarSucursalesActivas()
    {
        return SucursalModelo::mdlListarSucursalesActivas();
    }

    /**
     * Listar sucursales activas con información completa para combos mejorados
     */
    static public function ctrListarSucursalesActivasCompletas()
    {
        return SucursalModelo::mdlListarSucursalesActivasCompletas();
    }

    /**
     * Guardar o actualizar sucursal con validación completa
     */
    static public function ctrGuardarSucursal($datos)
    {
        // Validar y sanitizar datos de entrada
        $validation = self::validateSucursalData($datos);
        if (!$validation['valid']) {
            return $validation;
        }

        // Datos sanitizados
        $datosSanitizados = $validation['data'];

        // Verificar duplicados antes de guardar
        $duplicateCheck = self::ctrVerificarDuplicados(
            $datosSanitizados['codigo'], 
            $datosSanitizados['nombre'], 
            $datosSanitizados['id']
        );
        
        if (!$duplicateCheck['valid']) {
            return $duplicateCheck;
        }

        // Intentar guardar
        $resultado = SucursalModelo::mdlGuardarSucursal($datosSanitizados);
        
        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoria($datosSanitizados, empty($datosSanitizados['id']) ? 'CREATE' : 'UPDATE');
            return "ok";
        } else {
            return [
                'valid' => false,
                'estado' => 'error',
                'mensaje' => 'Error en la base de datos: ' . (is_array($resultado) ? $resultado[2] : $resultado)
            ];
        }
    }

    /**
     * Eliminar sucursal
     */
    static public function ctrEliminarSucursal($id)
    {
        // Validar ID
        if (!is_numeric($id) || $id <= 0) {
            return [
                'estado' => 'error',
                'mensaje' => 'ID de sucursal inválido'
            ];
        }

        $resultado = SucursalModelo::mdlEliminarSucursal($id);
        
        if ($resultado === "ok") {
            // Log de auditoría
            self::logAuditoria(['id' => $id], 'DELETE');
        }
        
        return $resultado;
    }

    /**
     * Verificar duplicados de código y nombre
     */
    static public function ctrVerificarDuplicados($codigo, $nombre, $id = null)
    {
        // Sanitizar datos
        $codigo = trim(strtoupper($codigo));
        $nombre = trim($nombre);
        
        if (empty($codigo) || empty($nombre)) {
            return [
                'valid' => false,
                'message' => 'Código y nombre son requeridos para verificar duplicados'
            ];
        }

        $duplicados = SucursalModelo::mdlVerificarDuplicados($codigo, $nombre, $id);
        
        if ($duplicados['codigo_duplicado']) {
            return [
                'valid' => false,
                'message' => "El código '{$codigo}' ya existe en otra sucursal"
            ];
        }
        
        if ($duplicados['nombre_duplicado']) {
            return [
                'valid' => false,
                'message' => "El nombre '{$nombre}' ya existe en otra sucursal"
            ];
        }
        
        return ['valid' => true];
    }

    /**
     * Validar y sanitizar datos de sucursal
     */
    private static function validateSucursalData($datos)
    {
        $errors = [];
        $sanitizedData = [];

        // Validar y sanitizar ID
        $sanitizedData['id'] = !empty($datos['id']) ? intval($datos['id']) : null;
        
        // Validar y sanitizar empresa_id
        $sanitizedData['empresa_id'] = !empty($datos['empresa_id']) ? intval($datos['empresa_id']) : 1;
        
        // Validar y sanitizar nombre
        if (empty($datos['nombre'])) {
            $errors[] = 'El nombre es requerido';
        } else {
            $nombre = trim($datos['nombre']);
            if (strlen($nombre) < 2) {
                $errors[] = 'El nombre debe tener al menos 2 caracteres';
            } elseif (strlen($nombre) > 100) {
                $errors[] = 'El nombre no puede exceder 100 caracteres';
            } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
                $errors[] = 'El nombre solo puede contener letras y espacios';
            } else {
                $sanitizedData['nombre'] = $nombre;
            }
        }

        // Validar y sanitizar código
        if (empty($datos['codigo'])) {
            $errors[] = 'El código es requerido';
        } else {
            $codigo = trim(strtoupper($datos['codigo']));
            if (strlen($codigo) < 2) {
                $errors[] = 'El código debe tener al menos 2 caracteres';
            } elseif (strlen($codigo) > 10) {
                $errors[] = 'El código no puede exceder 10 caracteres';
            } elseif (!preg_match('/^[A-Z0-9]+$/', $codigo)) {
                $errors[] = 'El código solo puede contener letras mayúsculas y números';
            } else {
                $sanitizedData['codigo'] = $codigo;
            }
        }

        // Validar y sanitizar dirección (opcional)
        if (!empty($datos['direccion'])) {
            $direccion = trim($datos['direccion']);
            if (strlen($direccion) > 255) {
                $errors[] = 'La dirección no puede exceder 255 caracteres';
            } else {
                $sanitizedData['direccion'] = $direccion;
            }
        } else {
            $sanitizedData['direccion'] = '';
        }

        // Validar y sanitizar teléfono (opcional)
        if (!empty($datos['telefono'])) {
            $telefono = trim($datos['telefono']);
            if (strlen($telefono) < 7) {
                $errors[] = 'El teléfono debe tener al menos 7 caracteres';
            } elseif (strlen($telefono) > 15) {
                $errors[] = 'El teléfono no puede exceder 15 caracteres';
            } elseif (!preg_match('/^[\d\s\-\+\(\)]+$/', $telefono)) {
                $errors[] = 'El teléfono contiene caracteres inválidos';
            } else {
                $sanitizedData['telefono'] = $telefono;
            }
        } else {
            $sanitizedData['telefono'] = '';
        }

        // Validar estado
        if (empty($datos['estado']) || !in_array($datos['estado'], ['activa', 'inactiva'])) {
            $errors[] = 'El estado debe ser "activa" o "inactiva"';
        } else {
            $sanitizedData['estado'] = $datos['estado'];
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'data' => $sanitizedData,
            'estado' => empty($errors) ? 'ok' : 'error',
            'mensaje' => empty($errors) ? '' : implode(', ', $errors)
        ];
    }

    /**
     * Registrar auditoría de operaciones
     */
    private static function logAuditoria($datos, $operacion)
    {
        try {
            // Obtener usuario de sesión (implementar según tu sistema de autenticación)
            $usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1;
            
            $log = [
                'tabla' => 'sucursales',
                'operacion' => $operacion,
                'registro_id' => $datos['id'] ?? null,
                'datos' => json_encode($datos),
                'usuario_id' => $usuario_id,
                'fecha' => date('Y-m-d H:i:s'),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
            ];
            
            // Guardar en log de auditoría (implementar según tu sistema)
            // AuditoriaModelo::mdlGuardarLog($log);
            
            error_log("SUCURSAL_AUDIT: " . json_encode($log));
            
        } catch (Exception $e) {
            error_log("Error en log de auditoría: " . $e->getMessage());
        }
    }
} 