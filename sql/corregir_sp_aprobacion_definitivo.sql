-- ================================================================
-- CORRECCIÓN DEFINITIVA DE SP_LISTAR_PRESTAMOS_POR_APROBACION
-- ================================================================
-- 
-- Este script corrige el stored procedure que tiene el error 
-- "Unknown column 'c.nombres'" reemplazándolo por la versión correcta.
-- 
-- Uso: Ejecutar en phpMyAdmin -> SQL
-- ================================================================

-- 1. ELIMINAR PROCEDIMIENTO CORRUPTO
DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION;

-- 2. CREAR PROCEDIMIENTO CORREGIDO
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_APROBACION` (IN `fecha_ini` DATE, IN `fecha_fin` DATE)
BEGIN
    SELECT 
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,  -- ✅ CORRECTO: usar cliente_nombres NO c.nombres
        pc.pres_monto,
        pc.pres_interes,
        pc.pres_cuotas,
        pc.fpago_id,
        fp.fpago_descripcion,
        pc.id_usuario,
        u.usuario,
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha,
        pc.pres_aprobacion AS estado,
        '' AS opciones,
        pc.pres_monto_cuota,
        pc.pres_monto_interes,
        pc.pres_monto_total,
        pc.pres_cuotas_pagadas
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pc.pres_fecha_registro BETWEEN fecha_ini AND fecha_fin
    ORDER BY pc.pres_fecha_registro DESC;
END$$

DELIMITER ;

-- 3. PROBAR EL PROCEDIMIENTO CORREGIDO
SELECT '=== PROBANDO PROCEDIMIENTO CORREGIDO ===' as info;

-- Probar con fechas del mes actual
CALL SP_LISTAR_PRESTAMOS_POR_APROBACION(CONCAT(YEAR(CURDATE()), '-', LPAD(MONTH(CURDATE()), 2, '0'), '-01'), CURDATE());

SELECT '=== PROCEDIMIENTO CORREGIDO EXITOSAMENTE ===' as resultado; 