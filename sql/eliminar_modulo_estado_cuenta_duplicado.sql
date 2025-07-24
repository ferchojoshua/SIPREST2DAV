-- Script para eliminar el módulo duplicado "Estado de Cuenta Cliente" del menú
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Obtener el ID del módulo estado_cuenta_cliente.php
SET @estado_cuenta_id = (SELECT id FROM modulos WHERE vista = 'estado_cuenta_cliente.php' LIMIT 1);

-- Eliminar el módulo de los perfiles de usuarios
DELETE FROM perfil_modulo WHERE id_modulo = @estado_cuenta_id;

-- Eliminar el módulo principal
DELETE FROM modulos WHERE vista = 'estado_cuenta_cliente.php';

-- Confirmar eliminación
SELECT 'Módulo "Estado de Cuenta Cliente" eliminado correctamente' AS mensaje;

-- Verificar que no quedan referencias
SELECT COUNT(*) as modulos_restantes FROM modulos WHERE vista = 'estado_cuenta_cliente.php'; 