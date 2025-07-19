-- =====================================================
-- MEJORAS SISTEMA DE CAJA - SUCURSALES Y CIERRE DE DÍA
-- =====================================================
-- Script para implementar cajas independientes por sucursal
-- y sistema de cierre de día independiente del cierre de caja

-- =====================================================
-- 1. AGREGAR SUCURSAL A TABLA CAJA
-- =====================================================

-- Verificar estructura actual
SELECT 'Verificando estructura actual de tabla caja...' as estado;
DESCRIBE caja;

-- Agregar campo sucursal_id a tabla caja
ALTER TABLE `caja` 
ADD COLUMN `sucursal_id` INT(11) NULL COMMENT 'Sucursal a la que pertenece la caja' AFTER `caja_id`;

-- Agregar índice para mejorar rendimiento
ALTER TABLE `caja`
ADD INDEX `idx_caja_sucursal` (`sucursal_id`);

-- Agregar restricción de clave foránea (opcional)
-- ALTER TABLE `caja`
-- ADD CONSTRAINT `fk_caja_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Actualizar cajas existentes asignándolas a sucursal principal (ID 1)
-- Solo si existe la sucursal principal
UPDATE caja 
SET sucursal_id = (SELECT MIN(id) FROM sucursales WHERE estado = 'activa' LIMIT 1)
WHERE sucursal_id IS NULL;

-- =====================================================
-- 2. AGREGAR SUCURSAL A TABLA MOVIMIENTOS
-- =====================================================

-- Agregar campo sucursal_id a tabla movimientos para consistencia
ALTER TABLE `movimientos` 
ADD COLUMN `sucursal_id` INT(11) NULL COMMENT 'Sucursal donde se realizó el movimiento' AFTER `caja_id`;

-- Agregar índice
ALTER TABLE `movimientos`
ADD INDEX `idx_movimientos_sucursal` (`sucursal_id`);

-- Actualizar movimientos existentes basándose en la caja
UPDATE movimientos m
INNER JOIN caja c ON m.caja_id = c.caja_id
SET m.sucursal_id = c.sucursal_id
WHERE m.sucursal_id IS NULL;

-- =====================================================
-- 3. CREAR TABLA CIERRE_DIA
-- =====================================================

CREATE TABLE IF NOT EXISTS `cierre_dia` (
  `cierre_dia_id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) NOT NULL COMMENT 'Sucursal del cierre',
  `fecha_cierre` date NOT NULL COMMENT 'Fecha del cierre de día',
  `usuario_cierre` int(11) NOT NULL COMMENT 'Usuario que realizó el cierre',
  
  -- Resumen de caja
  `caja_id_activa` int(11) NULL COMMENT 'ID de la caja activa al momento del cierre',
  `monto_inicial_caja` decimal(15,2) DEFAULT 0 COMMENT 'Monto inicial de caja',
  `monto_final_calculado` decimal(15,2) DEFAULT 0 COMMENT 'Monto final calculado',
  
  -- Resumen de operaciones del día
  `total_prestamos_otorgados` int(11) DEFAULT 0 COMMENT 'Cantidad de préstamos otorgados',
  `monto_prestamos_otorgados` decimal(15,2) DEFAULT 0 COMMENT 'Monto total prestamos otorgados',
  `monto_intereses_prestamos` decimal(15,2) DEFAULT 0 COMMENT 'Total intereses de préstamos otorgados',
  
  `total_pagos_recibidos` int(11) DEFAULT 0 COMMENT 'Cantidad de pagos recibidos',
  `monto_pagos_recibidos` decimal(15,2) DEFAULT 0 COMMENT 'Monto total de pagos recibidos',
  `monto_capital_cobrado` decimal(15,2) DEFAULT 0 COMMENT 'Capital cobrado en cuotas',
  `monto_intereses_cobrados` decimal(15,2) DEFAULT 0 COMMENT 'Intereses cobrados en cuotas',
  
  `total_ingresos_extras` int(11) DEFAULT 0 COMMENT 'Cantidad de ingresos extra',
  `monto_ingresos_extras` decimal(15,2) DEFAULT 0 COMMENT 'Monto de ingresos extra',
  
  `total_egresos` int(11) DEFAULT 0 COMMENT 'Cantidad de egresos',
  `monto_egresos` decimal(15,2) DEFAULT 0 COMMENT 'Monto total de egresos',
  
  -- Campos adicionales
  `observaciones` text NULL COMMENT 'Observaciones del cierre de día',
  `estado_cierre` enum('abierto','cerrado','revisado') DEFAULT 'cerrado' COMMENT 'Estado del cierre',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_revision` datetime NULL COMMENT 'Fecha de revisión por supervisor',
  `usuario_revision` int(11) NULL COMMENT 'Usuario que revisó el cierre',
  
  PRIMARY KEY (`cierre_dia_id`),
  UNIQUE KEY `uk_cierre_dia_sucursal_fecha` (`sucursal_id`, `fecha_cierre`),
  KEY `idx_fecha_cierre` (`fecha_cierre`),
  KEY `idx_sucursal_fecha` (`sucursal_id`, `fecha_cierre`),
  KEY `idx_usuario_cierre` (`usuario_cierre`),
  KEY `idx_estado_cierre` (`estado_cierre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Registro de cierres de día por sucursal';

-- =====================================================
-- 4. STORED PROCEDURES PARA CAJAS POR SUCURSAL
-- =====================================================

-- Procedimiento para listar cajas por sucursal
DELIMITER $$
DROP PROCEDURE IF EXISTS SP_LISTAR_CAJAS_POR_SUCURSAL$$
CREATE PROCEDURE SP_LISTAR_CAJAS_POR_SUCURSAL(IN p_sucursal_id INT, IN p_es_admin BOOLEAN)
BEGIN
    IF p_es_admin = TRUE THEN
        -- Admin puede ver todas las cajas
        SELECT 
            c.caja_id,
            c.caja_descripcion,
            c.caja_monto_inicial,
            c.caja_fecha_apertura,
            c.caja_fecha_cierre,
            c.caja_estado,
            c.sucursal_id,
            COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
            COALESCE(s.codigo, 'N/A') as sucursal_codigo,
            u.nombre_usuario as usuario_apertura,
            -- Resumen de operaciones de la caja
            (SELECT COUNT(*) FROM prestamo_cabecera pc WHERE pc.caja_id = c.caja_id) as total_prestamos,
            (SELECT COUNT(*) FROM movimientos m WHERE m.caja_id = c.caja_id AND m.movi_tipo = 'INGRESO') as total_ingresos,
            (SELECT COUNT(*) FROM movimientos m WHERE m.caja_id = c.caja_id AND m.movi_tipo = 'EGRESO') as total_egresos,
            '' as opciones
        FROM caja c
        LEFT JOIN sucursales s ON c.sucursal_id = s.id
        LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
        ORDER BY c.caja_fecha_apertura DESC;
    ELSE
        -- Usuario normal solo ve cajas de su sucursal
        SELECT 
            c.caja_id,
            c.caja_descripcion,
            c.caja_monto_inicial,
            c.caja_fecha_apertura,
            c.caja_fecha_cierre,
            c.caja_estado,
            c.sucursal_id,
            COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
            COALESCE(s.codigo, 'N/A') as sucursal_codigo,
            u.nombre_usuario as usuario_apertura,
            (SELECT COUNT(*) FROM prestamo_cabecera pc WHERE pc.caja_id = c.caja_id) as total_prestamos,
            (SELECT COUNT(*) FROM movimientos m WHERE m.caja_id = c.caja_id AND m.movi_tipo = 'INGRESO') as total_ingresos,
            (SELECT COUNT(*) FROM movimientos m WHERE m.caja_id = c.caja_id AND m.movi_tipo = 'EGRESO') as total_egresos,
            '' as opciones
        FROM caja c
        LEFT JOIN sucursales s ON c.sucursal_id = s.id
        LEFT JOIN usuarios u ON c.id_usuario = u.id_usuario
        WHERE c.sucursal_id = p_sucursal_id OR c.sucursal_id IS NULL
        ORDER BY c.caja_fecha_apertura DESC;
    END IF;
END$$

-- Procedimiento para abrir caja con sucursal
DROP PROCEDURE IF EXISTS SP_REGISTRAR_APERTURA_CAJA_SUCURSAL$$
CREATE PROCEDURE SP_REGISTRAR_APERTURA_CAJA_SUCURSAL(
    IN p_caja_descripcion VARCHAR(255),
    IN p_caja_monto_inicial DECIMAL(15,2),
    IN p_usuario_id INT,
    IN p_sucursal_id INT
)
BEGIN
    DECLARE v_existe_caja_abierta INT DEFAULT 0;
    DECLARE v_caja_id INT;
    
    -- Verificar si ya existe una caja abierta en la sucursal
    SELECT COUNT(*) INTO v_existe_caja_abierta
    FROM caja 
    WHERE caja_estado = 'VIGENTE' 
    AND (sucursal_id = p_sucursal_id OR (sucursal_id IS NULL AND p_sucursal_id IS NULL));
    
    IF v_existe_caja_abierta > 0 THEN
        -- Ya existe una caja abierta en esta sucursal
        SELECT 2 as resultado, 'Ya existe una caja abierta en esta sucursal' as mensaje;
    ELSE
        -- Abrir nueva caja
        INSERT INTO caja (
            caja_descripcion, 
            caja_monto_inicial, 
            caja_fecha_apertura, 
            caja_estado, 
            id_usuario,
            sucursal_id
        ) VALUES (
            p_caja_descripcion, 
            p_caja_monto_inicial, 
            NOW(), 
            'VIGENTE', 
            p_usuario_id,
            p_sucursal_id
        );
        
        SET v_caja_id = LAST_INSERT_ID();
        SELECT 1 as resultado, v_caja_id as caja_id, 'Caja abierta exitosamente' as mensaje;
    END IF;
END$$

-- =====================================================
-- 5. STORED PROCEDURES PARA CIERRE DE DÍA
-- =====================================================

-- Procedimiento para generar cierre de día
DROP PROCEDURE IF EXISTS SP_GENERAR_CIERRE_DIA$$
CREATE PROCEDURE SP_GENERAR_CIERRE_DIA(
    IN p_sucursal_id INT,
    IN p_fecha_cierre DATE,
    IN p_usuario_cierre INT,
    IN p_observaciones TEXT
)
BEGIN
    DECLARE v_existe_cierre INT DEFAULT 0;
    DECLARE v_caja_id_activa INT DEFAULT NULL;
    DECLARE v_monto_inicial_caja DECIMAL(15,2) DEFAULT 0;
    
    -- Verificar si ya existe un cierre para esta fecha y sucursal
    SELECT COUNT(*) INTO v_existe_cierre
    FROM cierre_dia 
    WHERE sucursal_id = p_sucursal_id AND fecha_cierre = p_fecha_cierre;
    
    IF v_existe_cierre > 0 THEN
        SELECT 2 as resultado, 'Ya existe un cierre de día para esta fecha' as mensaje;
    ELSE
        -- Obtener caja activa de la sucursal
        SELECT caja_id, caja_monto_inicial INTO v_caja_id_activa, v_monto_inicial_caja
        FROM caja 
        WHERE caja_estado = 'VIGENTE' 
        AND (sucursal_id = p_sucursal_id OR (sucursal_id IS NULL AND p_sucursal_id IS NULL))
        LIMIT 1;
        
        -- Insertar cierre de día con cálculos automáticos
        INSERT INTO cierre_dia (
            sucursal_id,
            fecha_cierre,
            usuario_cierre,
            caja_id_activa,
            monto_inicial_caja,
            total_prestamos_otorgados,
            monto_prestamos_otorgados,
            monto_intereses_prestamos,
            total_pagos_recibidos,
            monto_pagos_recibidos,
            total_ingresos_extras,
            monto_ingresos_extras,
            total_egresos,
            monto_egresos,
            monto_final_calculado,
            observaciones
        )
        SELECT 
            p_sucursal_id,
            p_fecha_cierre,
            p_usuario_cierre,
            v_caja_id_activa,
            v_monto_inicial_caja,
            
            -- Préstamos otorgados
            COALESCE((SELECT COUNT(*) FROM prestamo_cabecera pc 
                     WHERE DATE(pc.pres_fecha_registro) = p_fecha_cierre 
                     AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0),
                     
            COALESCE((SELECT SUM(pc.pres_monto) FROM prestamo_cabecera pc 
                     WHERE DATE(pc.pres_fecha_registro) = p_fecha_cierre 
                     AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0),
                     
            COALESCE((SELECT SUM(pc.pres_monto_interes) FROM prestamo_cabecera pc 
                     WHERE DATE(pc.pres_fecha_registro) = p_fecha_cierre 
                     AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0),
            
            -- Pagos recibidos (cuotas pagadas del día)
            COALESCE((SELECT COUNT(*) FROM prestamo_detalle pd
                     INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                     WHERE DATE(pd.pdetalle_fecha_registro) = p_fecha_cierre
                     AND pd.pdetalle_estado_cuota = 'pagada'
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0),
                     
            COALESCE((SELECT SUM(pd.pdetalle_monto_cuota) FROM prestamo_detalle pd
                     INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                     WHERE DATE(pd.pdetalle_fecha_registro) = p_fecha_cierre
                     AND pd.pdetalle_estado_cuota = 'pagada'
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0),
            
            -- Ingresos extras
            COALESCE((SELECT COUNT(*) FROM movimientos m 
                     WHERE DATE(m.movi_fecha) = p_fecha_cierre 
                     AND m.movi_tipo = 'INGRESO'
                     AND (m.sucursal_id = p_sucursal_id OR (m.sucursal_id IS NULL AND p_sucursal_id IS NULL))), 0),
                     
            COALESCE((SELECT SUM(m.movi_monto) FROM movimientos m 
                     WHERE DATE(m.movi_fecha) = p_fecha_cierre 
                     AND m.movi_tipo = 'INGRESO'
                     AND (m.sucursal_id = p_sucursal_id OR (m.sucursal_id IS NULL AND p_sucursal_id IS NULL))), 0),
            
            -- Egresos
            COALESCE((SELECT COUNT(*) FROM movimientos m 
                     WHERE DATE(m.movi_fecha) = p_fecha_cierre 
                     AND m.movi_tipo = 'EGRESO'
                     AND (m.sucursal_id = p_sucursal_id OR (m.sucursal_id IS NULL AND p_sucursal_id IS NULL))), 0),
                     
            COALESCE((SELECT SUM(m.movi_monto) FROM movimientos m 
                     WHERE DATE(m.movi_fecha) = p_fecha_cierre 
                     AND m.movi_tipo = 'EGRESO'
                     AND (m.sucursal_id = p_sucursal_id OR (m.sucursal_id IS NULL AND p_sucursal_id IS NULL))), 0),
            
            -- Monto final calculado
            v_monto_inicial_caja + 
            COALESCE((SELECT SUM(pd.pdetalle_monto_cuota) FROM prestamo_detalle pd
                     INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                     WHERE DATE(pd.pdetalle_fecha_registro) = p_fecha_cierre
                     AND pd.pdetalle_estado_cuota = 'pagada'
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0) +
            COALESCE((SELECT SUM(m.movi_monto) FROM movimientos m 
                     WHERE DATE(m.movi_fecha) = p_fecha_cierre 
                     AND m.movi_tipo = 'INGRESO'
                     AND (m.sucursal_id = p_sucursal_id OR (m.sucursal_id IS NULL AND p_sucursal_id IS NULL))), 0) -
            COALESCE((SELECT SUM(m.movi_monto) FROM movimientos m 
                     WHERE DATE(m.movi_fecha) = p_fecha_cierre 
                     AND m.movi_tipo = 'EGRESO'
                     AND (m.sucursal_id = p_sucursal_id OR (m.sucursal_id IS NULL AND p_sucursal_id IS NULL))), 0) -
            COALESCE((SELECT SUM(pc.pres_monto) FROM prestamo_cabecera pc 
                     WHERE DATE(pc.pres_fecha_registro) = p_fecha_cierre 
                     AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
                     AND (pc.sucursal_asignada_id = p_sucursal_id OR (pc.sucursal_asignada_id IS NULL AND p_sucursal_id IS NULL))), 0),
            
            p_observaciones;
        
        SELECT 1 as resultado, 'Cierre de día generado exitosamente' as mensaje, LAST_INSERT_ID() as cierre_id;
    END IF;
END$$

-- Procedimiento para listar cierres de día
DROP PROCEDURE IF EXISTS SP_LISTAR_CIERRES_DIA$$
CREATE PROCEDURE SP_LISTAR_CIERRES_DIA(IN p_sucursal_id INT, IN p_es_admin BOOLEAN)
BEGIN
    IF p_es_admin = TRUE THEN
        SELECT 
            cd.*,
            s.nombre as sucursal_nombre,
            s.codigo as sucursal_codigo,
            u1.nombre_usuario as usuario_cierre_nombre,
            u2.nombre_usuario as usuario_revision_nombre,
            '' as opciones
        FROM cierre_dia cd
        LEFT JOIN sucursales s ON cd.sucursal_id = s.id
        LEFT JOIN usuarios u1 ON cd.usuario_cierre = u1.id_usuario
        LEFT JOIN usuarios u2 ON cd.usuario_revision = u2.id_usuario
        ORDER BY cd.fecha_cierre DESC, cd.sucursal_id;
    ELSE
        SELECT 
            cd.*,
            s.nombre as sucursal_nombre,
            s.codigo as sucursal_codigo,
            u1.nombre_usuario as usuario_cierre_nombre,
            u2.nombre_usuario as usuario_revision_nombre,
            '' as opciones
        FROM cierre_dia cd
        LEFT JOIN sucursales s ON cd.sucursal_id = s.id
        LEFT JOIN usuarios u1 ON cd.usuario_cierre = u1.id_usuario
        LEFT JOIN usuarios u2 ON cd.usuario_revision = u2.id_usuario
        WHERE cd.sucursal_id = p_sucursal_id
        ORDER BY cd.fecha_cierre DESC;
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- 6. VERIFICACIÓN FINAL
-- =====================================================

-- Verificar estructura actualizada
SELECT 'Estructura de caja actualizada:' as resultado;
DESCRIBE caja;

SELECT 'Estructura de movimientos actualizada:' as resultado;
DESCRIBE movimientos;

SELECT 'Estructura de cierre_dia creada:' as resultado;
DESCRIBE cierre_dia;

-- Verificar procedimientos creados
SELECT 'Procedimientos almacenados creados:' as resultado;
SHOW PROCEDURE STATUS WHERE Name LIKE 'SP_%SUCURSAL%' OR Name LIKE 'SP_%CIERRE_DIA%';

SELECT 'MEJORAS DEL SISTEMA DE CAJA IMPLEMENTADAS EXITOSAMENTE' as resultado;
SELECT 'Las cajas ahora están asociadas a sucursales y se puede hacer cierre de día independiente' as mensaje; 