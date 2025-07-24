-- Script para eliminar el módulo duplicado "Reporte Mora" del menú
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Obtener el ID del módulo reporte_mora.php
SET @reporte_mora_id = (SELECT id FROM modulos WHERE vista = 'reporte_mora.php' LIMIT 1);

-- Eliminar el módulo de los perfiles de usuarios
DELETE FROM perfil_modulo WHERE id_modulo = @reporte_mora_id;

-- Eliminar el módulo principal
DELETE FROM modulos WHERE vista = 'reporte_mora.php';

-- Confirmar eliminación
SELECT 'Módulo "Reporte Mora" eliminado correctamente' AS mensaje;

-- Verificar que no quedan referencias
SELECT COUNT(*) as modulos_restantes FROM modulos WHERE vista = 'reporte_mora.php'; 