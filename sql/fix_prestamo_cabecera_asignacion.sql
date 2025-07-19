-- =====================================================
-- FIX PRESTAMO_CABECERA - AGREGAR CAMPOS DE ASIGNACIÓN
-- =====================================================
-- Script para corregir la tabla prestamo_cabecera agregando campos faltantes
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Verificar estructura actual
SELECT 'Verificando estructura actual de prestamo_cabecera...' as estado;
DESCRIBE prestamo_cabecera;

-- Agregar columnas de asignación si no existen
SET @sql = 'ALTER TABLE prestamo_cabecera 
ADD COLUMN IF NOT EXISTS sucursal_asignada_id INT(11) NULL COMMENT "Sucursal asignada para cobranza",
ADD COLUMN IF NOT EXISTS ruta_asignada_id INT(11) NULL COMMENT "Ruta asignada para cobranza", 
ADD COLUMN IF NOT EXISTS cobrador_asignado_id INT(11) NULL COMMENT "Usuario cobrador asignado",
ADD COLUMN IF NOT EXISTS fecha_asignacion DATETIME NULL COMMENT "Fecha de asignación de ruta y cobrador",
ADD COLUMN IF NOT EXISTS observaciones_asignacion TEXT NULL COMMENT "Observaciones de la asignación"';

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Agregar índices si no existen
CREATE INDEX IF NOT EXISTS idx_sucursal_asignada ON prestamo_cabecera (sucursal_asignada_id);
CREATE INDEX IF NOT EXISTS idx_ruta_asignada ON prestamo_cabecera (ruta_asignada_id);
CREATE INDEX IF NOT EXISTS idx_cobrador_asignado ON prestamo_cabecera (cobrador_asignado_id);
CREATE INDEX IF NOT EXISTS idx_fecha_asignacion ON prestamo_cabecera (fecha_asignacion);

-- Verificar que las columnas se agregaron correctamente
SELECT 'Verificando estructura después de los cambios...' as estado;
DESCRIBE prestamo_cabecera;

-- Mostrar mensaje de éxito
SELECT 'CAMPOS DE ASIGNACIÓN AGREGADOS EXITOSAMENTE' as resultado;
SELECT 'Ya puedes aprobar préstamos con asignación de rutas y cobradores' as mensaje; 