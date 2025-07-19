-- =====================================================
-- CORRECCIÓN DE ESTRUCTURA DE TABLAS EXISTENTES
-- Ejecuta este script para corregir la estructura y activar los botones
-- =====================================================

-- 1. Verificar estructura actual de cajas_sucursales
DESCRIBE cajas_sucursales;

-- 2. Agregar columnas faltantes a cajas_sucursales si no existen
-- (Esto no dará error si las columnas ya existen)

-- Verificar si necesitamos agregar nombre_sucursal o si tiene otro nombre
SET @query = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE cajas_sucursales ADD COLUMN nombre_sucursal VARCHAR(100) NOT NULL DEFAULT "Sucursal" AFTER sucursal_id',
        'SELECT "La columna nombre_sucursal ya existe" as mensaje'
    )
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cajas_sucursales' 
    AND COLUMN_NAME = 'nombre_sucursal'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3. Verificar si necesitamos agregar otras columnas
SET @query = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE cajas_sucursales ADD COLUMN direccion TEXT DEFAULT NULL',
        'SELECT "La columna direccion ya existe" as mensaje'
    )
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cajas_sucursales' 
    AND COLUMN_NAME = 'direccion'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4. Verificar tipo_caja
SET @query = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE cajas_sucursales ADD COLUMN tipo_caja ENUM("principal","secundaria","temporal") DEFAULT "principal"',
        'SELECT "La columna tipo_caja ya existe" as mensaje'
    )
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cajas_sucursales' 
    AND COLUMN_NAME = 'tipo_caja'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 5. Verificar monto_inicial_sugerido
SET @query = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE cajas_sucursales ADD COLUMN monto_inicial_sugerido DECIMAL(15,2) DEFAULT 0.00',
        'SELECT "La columna monto_inicial_sugerido ya existe" as mensaje'
    )
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cajas_sucursales' 
    AND COLUMN_NAME = 'monto_inicial_sugerido'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 6. Verificar estado
SET @query = (
    SELECT IF(
        COUNT(*) = 0,
        'ALTER TABLE cajas_sucursales ADD COLUMN estado ENUM("activa","inactiva") DEFAULT "activa"',
        'SELECT "La columna estado ya existe" as mensaje'
    )
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'cajas_sucursales' 
    AND COLUMN_NAME = 'estado'
);

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 7. Ahora insertamos los datos usando la estructura correcta
-- Pero primero vamos a ver qué columnas tiene realmente la tabla
SELECT 'ESTRUCTURA ACTUAL DE cajas_sucursales:' as INFO;
DESCRIBE cajas_sucursales;

-- 8. Insertar datos básicos (ajustado según las columnas que existan)
-- Verificamos si ya existe un registro con ID 1
SET @exists = (SELECT COUNT(*) FROM cajas_sucursales WHERE sucursal_id = 1);

-- Si no existe, lo insertamos con las columnas que sabemos que existen
INSERT IGNORE INTO cajas_sucursales (sucursal_id) VALUES (1);

-- Actualizamos con los valores correctos si las columnas existen
UPDATE cajas_sucursales SET
    nombre_sucursal = CASE 
        WHEN EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'cajas_sucursales' 
            AND COLUMN_NAME = 'nombre_sucursal'
        ) THEN 'Sucursal Principal'
        ELSE nombre_sucursal
    END,
    direccion = CASE 
        WHEN EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'cajas_sucursales' 
            AND COLUMN_NAME = 'direccion'
        ) THEN 'Oficina Central'
        ELSE direccion
    END,
    tipo_caja = CASE 
        WHEN EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'cajas_sucursales' 
            AND COLUMN_NAME = 'tipo_caja'
        ) THEN 'principal'
        ELSE tipo_caja
    END,
    monto_inicial_sugerido = CASE 
        WHEN EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'cajas_sucursales' 
            AND COLUMN_NAME = 'monto_inicial_sugerido'
        ) THEN 50000.00
        ELSE monto_inicial_sugerido
    END,
    estado = CASE 
        WHEN EXISTS (
            SELECT 1 FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
            AND TABLE_NAME = 'cajas_sucursales' 
            AND COLUMN_NAME = 'estado'
        ) THEN 'activa'
        ELSE estado
    END
WHERE sucursal_id = 1;

-- 9. Asegurar que caja_permisos tenga la estructura correcta
-- Verificar si la tabla existe, si no crearla
CREATE TABLE IF NOT EXISTS `caja_permisos` (
  `permiso_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `puede_abrir_caja` tinyint(1) DEFAULT 1,
  `puede_cerrar_caja` tinyint(1) DEFAULT 1,
  `puede_ver_reportes` tinyint(1) DEFAULT 1,
  `puede_gestionar_movimientos` tinyint(1) DEFAULT 1,
  `puede_supervisar` tinyint(1) DEFAULT 0,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`permiso_id`),
  UNIQUE KEY `uk_usuario_permisos` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 10. Dar permisos al usuario administrador
INSERT IGNORE INTO `caja_permisos` 
(`id_usuario`, `puede_abrir_caja`, `puede_cerrar_caja`, `puede_ver_reportes`, `puede_gestionar_movimientos`, `puede_supervisar`) 
VALUES 
(1, 1, 1, 1, 1, 1);

-- 11. Asegurar que el módulo esté en el menú
INSERT IGNORE INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES (65, 'Dashboard de Caja', 39, 'dashboard_caja.php', 'fas fa-tachometer-alt', 2);

-- 12. Dar permisos del módulo al perfil administrador
INSERT IGNORE INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`) 
VALUES (1, 65, 0);

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

SELECT 'ESTRUCTURA FINAL DE cajas_sucursales:' as RESULTADO;
DESCRIBE cajas_sucursales;

SELECT 'DATOS EN cajas_sucursales:' as RESULTADO;
SELECT * FROM cajas_sucursales;

SELECT 'PERMISOS CONFIGURADOS:' as RESULTADO;
SELECT cp.*, u.nombre_usuario 
FROM caja_permisos cp 
LEFT JOIN usuarios u ON cp.id_usuario = u.id_usuario;

SELECT 'MÓDULO DASHBOARD AGREGADO:' as RESULTADO;
SELECT * FROM modulos WHERE vista = 'dashboard_caja.php';

SELECT '✅ CORRECCIÓN COMPLETADA - BOTONES LISTOS PARA USAR' as ESTADO; 