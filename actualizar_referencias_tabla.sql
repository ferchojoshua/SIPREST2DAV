-- Script para agregar campos de dirección a la tabla referencias
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Agregar campos de dirección para las referencias
ALTER TABLE `referencias` 
ADD COLUMN `refe_per_dir` VARCHAR(255) DEFAULT NULL COMMENT 'Dirección de referencia personal' AFTER `refe_cel_per`,
ADD COLUMN `refe_fami_dir` VARCHAR(255) DEFAULT NULL COMMENT 'Dirección de referencia familiar' AFTER `refe_cel_fami`;

-- Verificar que los campos se agregaron correctamente
DESCRIBE `referencias`;

-- Mostrar algunos registros para verificar
SELECT * FROM `referencias` LIMIT 5; 