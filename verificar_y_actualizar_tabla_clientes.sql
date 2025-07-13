-- Script seguro para agregar los campos faltantes a la tabla de clientes
-- Este script verifica si los campos ya existen antes de agregarlos

-- Verificar estructura actual de la tabla clientes
SELECT 'Estructura actual de la tabla clientes:' as info;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'clientes' 
AND TABLE_SCHEMA = DATABASE()
ORDER BY ORDINAL_POSITION;

-- Procedimiento para agregar campos solo si no existen
DELIMITER $$

CREATE PROCEDURE AgregarCamposSiNoExisten()
BEGIN
    -- Verificar y agregar cliente_empresa_laboral
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_empresa_laboral' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_empresa_laboral` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_cargo_laboral
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_cargo_laboral' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_cargo_laboral` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_tel_laboral
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_tel_laboral' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_tel_laboral` VARCHAR(20) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_dir_laboral
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_dir_laboral' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_dir_laboral` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_refe_per_nombre
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_refe_per_nombre' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_refe_per_nombre` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_refe_per_cel
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_refe_per_cel' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_refe_per_cel` VARCHAR(20) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_refe_per_dir
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_refe_per_dir' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_refe_per_dir` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_refe_fami_nombre
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_refe_fami_nombre' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_refe_fami_nombre` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_refe_fami_cel
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_refe_fami_cel' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_refe_fami_cel` VARCHAR(20) DEFAULT NULL;
    END IF;
    
    -- Verificar y agregar cliente_refe_fami_dir
    IF NOT EXISTS (SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
                   WHERE TABLE_NAME = 'clientes' 
                   AND COLUMN_NAME = 'cliente_refe_fami_dir' 
                   AND TABLE_SCHEMA = DATABASE()) THEN
        ALTER TABLE `clientes` ADD COLUMN `cliente_refe_fami_dir` VARCHAR(255) DEFAULT NULL;
    END IF;
    
    SELECT 'Campos agregados exitosamente' as resultado;
END$$

DELIMITER ;

-- Ejecutar el procedimiento
CALL AgregarCamposSiNoExisten();

-- Eliminar el procedimiento después de usarlo
DROP PROCEDURE AgregarCamposSiNoExisten;

-- Verificar estructura final de la tabla clientes
SELECT 'Estructura final de la tabla clientes:' as info;
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'clientes' 
AND TABLE_SCHEMA = DATABASE()
ORDER BY ORDINAL_POSITION; 