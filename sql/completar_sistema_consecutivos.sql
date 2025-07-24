-- SCRIPT PARA COMPLETAR EL SISTEMA DE CONSECUTIVOS POR SUCURSAL
-- CrediCrece - Sistema de Préstamos
-- ===========================================================

-- 1. AGREGAR CAMPOS DE CONSECUTIVOS A LA TABLA SUCURSALES
-- =========================================================
ALTER TABLE `sucursales` 
ADD COLUMN `consecutivo_prestamos` INT(11) DEFAULT 1 COMMENT 'Consecutivo de préstamos por sucursal',
ADD COLUMN `consecutivo_recibos` INT(11) DEFAULT 1 COMMENT 'Consecutivo de recibos por sucursal', 
ADD COLUMN `consecutivo_vouchers` INT(11) DEFAULT 1 COMMENT 'Consecutivo de vouchers por sucursal';

-- 2. CREAR VISTA PARA CONSULTAR CONSECUTIVOS DE SUCURSALES
-- =========================================================
CREATE OR REPLACE VIEW `v_consecutivos_sucursales` AS
SELECT 
    s.id as sucursal_id,
    s.nombre as sucursal_nombre,
    s.codigo as sucursal_codigo,
    s.consecutivo_prestamos,
    s.consecutivo_recibos,
    s.consecutivo_vouchers,
    CONCAT(s.codigo, '-', LPAD(s.consecutivo_prestamos, 8, '0')) as proximo_nro_prestamo,
    CONCAT(s.codigo, '-', LPAD(s.consecutivo_recibos, 8, '0')) as proximo_nro_recibo,
    CONCAT(s.codigo, '-', LPAD(s.consecutivo_vouchers, 8, '0')) as proximo_nro_voucher,
    s.estado as sucursal_estado
FROM sucursales s 
WHERE s.estado = 'activa';

-- 3. CREAR STORED PROCEDURES PARA CONSECUTIVOS DE PRÉSTAMOS
-- =========================================================

DELIMITER $$

-- SP para obtener próximo consecutivo de préstamo por sucursal
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL`(IN `p_sucursal_id` INT)
BEGIN
    DECLARE v_consecutivo INT DEFAULT 1;
    DECLARE v_codigo_sucursal VARCHAR(20) DEFAULT 'SUC';
    
    -- Obtener consecutivo y código de sucursal
    SELECT consecutivo_prestamos, codigo 
    INTO v_consecutivo, v_codigo_sucursal
    FROM sucursales 
    WHERE id = p_sucursal_id AND estado = 'activa';
    
    -- Si no se encuentra la sucursal, usar valores por defecto
    IF v_consecutivo IS NULL THEN
        SET v_consecutivo = 1;
        SET v_codigo_sucursal = 'DEF';
    END IF;
    
    -- Retornar el número formateado
    SELECT CONCAT(v_codigo_sucursal, '-', LPAD(v_consecutivo, 8, '0')) as nro_prestamo,
           v_consecutivo as consecutivo_actual,
           v_codigo_sucursal as codigo_sucursal,
           p_sucursal_id as sucursal_id;
END$$

-- SP para incrementar consecutivo de préstamo por sucursal
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL`(IN `p_sucursal_id` INT)
BEGIN
    DECLARE v_existe INT DEFAULT 0;
    
    -- Verificar que la sucursal existe y está activa
    SELECT COUNT(*) INTO v_existe 
    FROM sucursales 
    WHERE id = p_sucursal_id AND estado = 'activa';
    
    IF v_existe > 0 THEN
        -- Incrementar consecutivo
        UPDATE sucursales 
        SET consecutivo_prestamos = consecutivo_prestamos + 1 
        WHERE id = p_sucursal_id;
        
        SELECT 'ok' as resultado, consecutivo_prestamos as nuevo_consecutivo
        FROM sucursales 
        WHERE id = p_sucursal_id;
    ELSE
        SELECT 'error' as resultado, 'Sucursal no encontrada o inactiva' as mensaje;
    END IF;
END$$

-- 4. CREAR STORED PROCEDURES PARA CONSECUTIVOS DE RECIBOS
-- ========================================================

-- SP para obtener próximo consecutivo de recibo por sucursal
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_CONSECUTIVO_RECIBO_SUCURSAL`(IN `p_sucursal_id` INT)
BEGIN
    DECLARE v_consecutivo INT DEFAULT 1;
    DECLARE v_codigo_sucursal VARCHAR(20) DEFAULT 'SUC';
    
    -- Obtener consecutivo y código de sucursal
    SELECT consecutivo_recibos, codigo 
    INTO v_consecutivo, v_codigo_sucursal
    FROM sucursales 
    WHERE id = p_sucursal_id AND estado = 'activa';
    
    -- Si no se encuentra la sucursal, usar valores por defecto
    IF v_consecutivo IS NULL THEN
        SET v_consecutivo = 1;
        SET v_codigo_sucursal = 'DEF';
    END IF;
    
    -- Retornar el número formateado
    SELECT CONCAT('R-', v_codigo_sucursal, '-', LPAD(v_consecutivo, 8, '0')) as nro_recibo,
           v_consecutivo as consecutivo_actual,
           v_codigo_sucursal as codigo_sucursal,
           p_sucursal_id as sucursal_id;
END$$

-- SP para incrementar consecutivo de recibo por sucursal
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INCREMENTAR_CONSECUTIVO_RECIBO_SUCURSAL`(IN `p_sucursal_id` INT)
BEGIN
    DECLARE v_existe INT DEFAULT 0;
    
    -- Verificar que la sucursal existe y está activa
    SELECT COUNT(*) INTO v_existe 
    FROM sucursales 
    WHERE id = p_sucursal_id AND estado = 'activa';
    
    IF v_existe > 0 THEN
        -- Incrementar consecutivo
        UPDATE sucursales 
        SET consecutivo_recibos = consecutivo_recibos + 1 
        WHERE id = p_sucursal_id;
        
        SELECT 'ok' as resultado, consecutivo_recibos as nuevo_consecutivo
        FROM sucursales 
        WHERE id = p_sucursal_id;
    ELSE
        SELECT 'error' as resultado, 'Sucursal no encontrada o inactiva' as mensaje;
    END IF;
END$$

-- 5. CREAR STORED PROCEDURES PARA CONSECUTIVOS DE VOUCHERS
-- =========================================================

-- SP para obtener próximo consecutivo de voucher por sucursal
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_CONSECUTIVO_VOUCHER_SUCURSAL`(IN `p_sucursal_id` INT)
BEGIN
    DECLARE v_consecutivo INT DEFAULT 1;
    DECLARE v_codigo_sucursal VARCHAR(20) DEFAULT 'SUC';
    
    -- Obtener consecutivo y código de sucursal
    SELECT consecutivo_vouchers, codigo 
    INTO v_consecutivo, v_codigo_sucursal
    FROM sucursales 
    WHERE id = p_sucursal_id AND estado = 'activa';
    
    -- Si no se encuentra la sucursal, usar valores por defecto
    IF v_consecutivo IS NULL THEN
        SET v_consecutivo = 1;
        SET v_codigo_sucursal = 'DEF';
    END IF;
    
    -- Retornar el número formateado
    SELECT CONCAT('V-', v_codigo_sucursal, '-', LPAD(v_consecutivo, 8, '0')) as nro_voucher,
           v_consecutivo as consecutivo_actual,
           v_codigo_sucursal as codigo_sucursal,
           p_sucursal_id as sucursal_id;
END$$

-- SP para incrementar consecutivo de voucher por sucursal
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INCREMENTAR_CONSECUTIVO_VOUCHER_SUCURSAL`(IN `p_sucursal_id` INT)
BEGIN
    DECLARE v_existe INT DEFAULT 0;
    
    -- Verificar que la sucursal existe y está activa
    SELECT COUNT(*) INTO v_existe 
    FROM sucursales 
    WHERE id = p_sucursal_id AND estado = 'activa';
    
    IF v_existe > 0 THEN
        -- Incrementar consecutivo
        UPDATE sucursales 
        SET consecutivo_vouchers = consecutivo_vouchers + 1 
        WHERE id = p_sucursal_id;
        
        SELECT 'ok' as resultado, consecutivo_vouchers as nuevo_consecutivo
        FROM sucursales 
        WHERE id = p_sucursal_id;
    ELSE
        SELECT 'error' as resultado, 'Sucursal no encontrada o inactiva' as mensaje;
    END IF;
END$$

DELIMITER ;

-- 6. INICIALIZAR CONSECUTIVOS EXISTENTES (OPCIONAL)
-- =================================================
-- Si ya tienes préstamos registrados, puedes inicializar los consecutivos
-- basándote en el último número usado por sucursal

-- Ejemplo para sucursal Leon (ID: 1)
-- UPDATE sucursales SET consecutivo_prestamos = 1 WHERE id = 1;

-- Ejemplo para sucursal Chinandega (ID: 2)  
-- UPDATE sucursales SET consecutivo_prestamos = 1 WHERE id = 2;

-- 7. VERIFICACIÓN FINAL
-- =====================
-- Probar que todo funciona correctamente

-- Consultar vista de consecutivos
-- SELECT * FROM v_consecutivos_sucursales;

-- Probar obtener consecutivo de préstamo para sucursal 1
-- CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(1);

-- Probar incrementar consecutivo
-- CALL SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL(1);

-- Verificar que se incrementó
-- CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(1);

-- ===========================================================
-- RESULTADO ESPERADO:
-- ===========================================================
-- ✅ Tabla sucursales con campos de consecutivos
-- ✅ Vista v_consecutivos_sucursales funcional
-- ✅ Stored procedures para obtener/incrementar consecutivos
-- ✅ Formato de números: LE001-00000001, CH001-00000001
-- ✅ Sistema compatible con consecutivos_modelo.php
-- ===========================================================

SELECT 'Sistema de consecutivos por sucursal implementado exitosamente' as resultado; 