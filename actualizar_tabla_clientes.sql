-- Script para agregar los campos faltantes a la tabla de clientes
-- Ejecutar este script en la base de datos

-- Agregar campos de información laboral
ALTER TABLE `clientes` ADD COLUMN `cliente_empresa_laboral` VARCHAR(255) DEFAULT NULL AFTER `cliente_correo`;
ALTER TABLE `clientes` ADD COLUMN `cliente_cargo_laboral` VARCHAR(255) DEFAULT NULL AFTER `cliente_empresa_laboral`;
ALTER TABLE `clientes` ADD COLUMN `cliente_tel_laboral` VARCHAR(20) DEFAULT NULL AFTER `cliente_cargo_laboral`;
ALTER TABLE `clientes` ADD COLUMN `cliente_dir_laboral` VARCHAR(255) DEFAULT NULL AFTER `cliente_tel_laboral`;

-- Agregar campos de referencia personal
ALTER TABLE `clientes` ADD COLUMN `cliente_refe_per_nombre` VARCHAR(255) DEFAULT NULL AFTER `cliente_dir_laboral`;
ALTER TABLE `clientes` ADD COLUMN `cliente_refe_per_cel` VARCHAR(20) DEFAULT NULL AFTER `cliente_refe_per_nombre`;
ALTER TABLE `clientes` ADD COLUMN `cliente_refe_per_dir` VARCHAR(255) DEFAULT NULL AFTER `cliente_refe_per_cel`;

-- Agregar campos de referencia familiar
ALTER TABLE `clientes` ADD COLUMN `cliente_refe_fami_nombre` VARCHAR(255) DEFAULT NULL AFTER `cliente_refe_per_dir`;
ALTER TABLE `clientes` ADD COLUMN `cliente_refe_fami_cel` VARCHAR(20) DEFAULT NULL AFTER `cliente_refe_fami_nombre`;
ALTER TABLE `clientes` ADD COLUMN `cliente_refe_fami_dir` VARCHAR(255) DEFAULT NULL AFTER `cliente_refe_fami_cel`;

-- Verificar que los campos se agregaron correctamente
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'clientes' 
AND TABLE_SCHEMA = DATABASE()
ORDER BY ORDINAL_POSITION; 