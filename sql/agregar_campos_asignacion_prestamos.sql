-- =====================================================
-- AGREGAR CAMPOS DE ASIGNACIÓN A PRESTAMO_CABECERA
-- =====================================================
-- Script para agregar campos de sucursal, ruta y cobrador al momento de aprobar préstamos
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Agregar columnas de asignación a prestamo_cabecera
ALTER TABLE `prestamo_cabecera` 
ADD COLUMN `sucursal_asignada_id` INT(11) NULL COMMENT 'Sucursal asignada para cobranza' AFTER `caja_id`,
ADD COLUMN `ruta_asignada_id` INT(11) NULL COMMENT 'Ruta asignada para cobranza' AFTER `sucursal_asignada_id`,
ADD COLUMN `cobrador_asignado_id` INT(11) NULL COMMENT 'Usuario cobrador asignado' AFTER `ruta_asignada_id`,
ADD COLUMN `fecha_asignacion` DATETIME NULL COMMENT 'Fecha de asignación de ruta y cobrador' AFTER `cobrador_asignado_id`,
ADD COLUMN `observaciones_asignacion` TEXT NULL COMMENT 'Observaciones de la asignación' AFTER `fecha_asignacion`;

-- Agregar índices para mejorar el rendimiento
ALTER TABLE `prestamo_cabecera`
ADD INDEX `idx_sucursal_asignada` (`sucursal_asignada_id`),
ADD INDEX `idx_ruta_asignada` (`ruta_asignada_id`),
ADD INDEX `idx_cobrador_asignado` (`cobrador_asignado_id`),
ADD INDEX `idx_fecha_asignacion` (`fecha_asignacion`);

-- Agregar restricciones de clave foránea (opcional - comentado por compatibilidad)
-- ALTER TABLE `prestamo_cabecera`
-- ADD CONSTRAINT `fk_prestamo_sucursal_asignada` FOREIGN KEY (`sucursal_asignada_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
-- ADD CONSTRAINT `fk_prestamo_ruta_asignada` FOREIGN KEY (`ruta_asignada_id`) REFERENCES `rutas` (`ruta_id`) ON DELETE SET NULL ON UPDATE CASCADE,
-- ADD CONSTRAINT `fk_prestamo_cobrador_asignado` FOREIGN KEY (`cobrador_asignado_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Actualizar préstamos existentes con la sucursal del cliente (opcional)
-- UPDATE prestamo_cabecera pc 
-- INNER JOIN clientes c ON pc.cliente_id = c.cliente_id 
-- SET pc.sucursal_asignada_id = c.sucursal_id 
-- WHERE pc.sucursal_asignada_id IS NULL AND c.sucursal_id IS NOT NULL;

-- Verificar que las columnas se agregaron correctamente
DESCRIBE prestamo_cabecera;

-- Mostrar mensaje de éxito
SELECT 'Campos de asignación agregados exitosamente a prestamo_cabecera' as mensaje; 