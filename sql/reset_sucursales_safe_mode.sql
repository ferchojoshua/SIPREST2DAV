-- ================================================
-- SCRIPT PARA REINICIAR CONTADOR DE SUCURSALES
-- COMPATIBLE CON SAFE UPDATE MODE
-- ================================================

-- Desactivar temporalmente el modo seguro
SET SQL_SAFE_UPDATES = 0;

-- Deshabilitar verificación de claves foráneas temporalmente
SET FOREIGN_KEY_CHECKS = 0;

-- Limpiar referencias en clientes (con WHERE para safe mode)
UPDATE clientes 
SET sucursal_id = NULL 
WHERE sucursal_id IS NOT NULL;

-- Eliminar todos los registros de sucursales (con WHERE para safe mode)
DELETE FROM sucursales 
WHERE id > 0 OR id = 0;

-- Reiniciar el contador AUTO_INCREMENT
ALTER TABLE sucursales AUTO_INCREMENT = 1;

-- Habilitar verificación de claves foráneas nuevamente
SET FOREIGN_KEY_CHECKS = 1;

-- Reactivar el modo seguro
SET SQL_SAFE_UPDATES = 1;

-- Verificar que la tabla esté vacía
SELECT COUNT(*) as total_sucursales FROM sucursales;

-- Verificar el próximo ID que se asignará
SELECT AUTO_INCREMENT 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'sucursales';

-- Mensaje de confirmación
SELECT 'Contador de sucursales reiniciado correctamente (Safe Mode Compatible)' as mensaje; 