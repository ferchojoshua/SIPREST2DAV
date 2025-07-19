-- ================================================
-- SCRIPT PARA REINICIAR CONTADOR DE SUCURSALES
-- USANDO TRUNCATE (MÁS EFICIENTE)
-- ================================================

-- TRUNCATE es más eficiente que DELETE y no se ve afectado por safe mode
-- Pero requiere manejar las claves foráneas correctamente

-- Desactivar verificación de claves foráneas
SET FOREIGN_KEY_CHECKS = 0;

-- Limpiar referencias en clientes primero
UPDATE clientes 
SET sucursal_id = NULL 
WHERE sucursal_id IS NOT NULL;

-- Usar TRUNCATE para vaciar la tabla y reiniciar el contador automáticamente
TRUNCATE TABLE sucursales;

-- Habilitar verificación de claves foráneas nuevamente
SET FOREIGN_KEY_CHECKS = 1;

-- Verificar que la tabla esté vacía
SELECT COUNT(*) as total_sucursales FROM sucursales;

-- Verificar el próximo ID que se asignará (debería ser 1)
SELECT AUTO_INCREMENT 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'sucursales';

-- Mensaje de confirmación
SELECT 'Contador de sucursales reiniciado correctamente (TRUNCATE)' as mensaje; 