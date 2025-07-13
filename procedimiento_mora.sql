-- =====================================================
-- PROCEDIMIENTOS ALMACENADOS PARA MORA
-- =====================================================
-- Estos procedimientos se pueden usar desde PHP en tu sistema

-- =====================================================
-- PROCEDIMIENTO 1: LISTAR CLIENTES EN MORA
-- =====================================================

DROP PROCEDURE IF EXISTS SP_REPORTE_CLIENTES_MORA;

DELIMITER $$

CREATE PROCEDURE SP_REPORTE_CLIENTES_MORA()
BEGIN
    SELECT 
        c.cliente_id,
        c.cliente_nombres,
        c.cliente_nro_documento,
        c.cliente_celular,
        c.cliente_direccion,
        pc.nro_prestamo,
        pd.pdetalle_nro_cuota,
        DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as fecha_vencimiento,
        pd.pdetalle_monto_cuota,
        pd.pdetalle_monto_pagado,
        DATEDIFF(CURDATE(), pd.pdetalle_fecha) as dias_mora,
        CASE 
            WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 30 THEN 'LEVE'
            WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 60 THEN 'MODERADA'
            WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 90 THEN 'ALTA'
            ELSE 'CRÍTICA'
        END as nivel_mora,
        pc.prestamo_monto,
        '' as opciones
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    WHERE pd.pdetalle_estado_cuota = 'pendiente'
      AND pd.pdetalle_fecha < CURDATE()
      AND pc.pres_aprobacion = 'aprobado'
    ORDER BY dias_mora DESC, c.cliente_nombres;
END$$

DELIMITER ;

-- =====================================================
-- PROCEDIMIENTO 2: RESUMEN DE MORA POR CLIENTE
-- =====================================================

DROP PROCEDURE IF EXISTS SP_RESUMEN_MORA_CLIENTE;

DELIMITER $$

CREATE PROCEDURE SP_RESUMEN_MORA_CLIENTE()
BEGIN
    SELECT 
        c.cliente_id,
        c.cliente_nombres,
        c.cliente_nro_documento,
        c.cliente_celular,
        COUNT(pd.pdetalle_id) as total_cuotas_vencidas,
        ROUND(SUM(pd.pdetalle_monto_cuota), 2) as monto_total_vencido,
        MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_maxima,
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_en_mora,
        CASE 
            WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 90 THEN 'URGENTE'
            WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 60 THEN 'PRIORITARIO'
            WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 30 THEN 'IMPORTANTE'
            ELSE 'RECORDATORIO'
        END as prioridad,
        '' as opciones
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    WHERE pd.pdetalle_estado_cuota = 'pendiente'
      AND pd.pdetalle_fecha < CURDATE()
      AND pc.pres_aprobacion = 'aprobado'
    GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_nro_documento, c.cliente_celular
    ORDER BY dias_mora_maxima DESC, monto_total_vencido DESC;
END$$

DELIMITER ;

-- =====================================================
-- PROCEDIMIENTO 3: ESTADÍSTICAS DE MORA
-- =====================================================

DROP PROCEDURE IF EXISTS SP_ESTADISTICAS_MORA;

DELIMITER $$

CREATE PROCEDURE SP_ESTADISTICAS_MORA()
BEGIN
    SELECT 
        COUNT(DISTINCT c.cliente_id) as clientes_con_mora,
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_con_mora,
        COUNT(pd.pdetalle_id) as total_cuotas_vencidas,
        ROUND(SUM(pd.pdetalle_monto_cuota), 2) as monto_total_en_mora,
        ROUND(AVG(DATEDIFF(CURDATE(), pd.pdetalle_fecha)), 0) as dias_mora_promedio,
        COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 30 THEN 1 END) as mora_leve,
        COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) BETWEEN 31 AND 60 THEN 1 END) as mora_moderada,
        COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) BETWEEN 61 AND 90 THEN 1 END) as mora_alta,
        COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) > 90 THEN 1 END) as mora_critica
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    WHERE pd.pdetalle_estado_cuota = 'pendiente'
      AND pd.pdetalle_fecha < CURDATE()
      AND pc.pres_aprobacion = 'aprobado';
END$$

DELIMITER ;

-- =====================================================
-- PROCEDIMIENTO 4: MORA POR USUARIO/GESTOR
-- =====================================================

DROP PROCEDURE IF EXISTS SP_MORA_POR_USUARIO;

DELIMITER $$

CREATE PROCEDURE SP_MORA_POR_USUARIO(IN p_id_usuario INT)
BEGIN
    SELECT 
        c.cliente_id,
        c.cliente_nombres,
        c.cliente_nro_documento,
        c.cliente_celular,
        pc.nro_prestamo,
        COUNT(pd.pdetalle_id) as cuotas_vencidas,
        ROUND(SUM(pd.pdetalle_monto_cuota), 2) as monto_vencido,
        MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_max,
        CASE 
            WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 90 THEN 'CRÍTICA'
            WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 60 THEN 'ALTA'
            WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 30 THEN 'MODERADA'
            ELSE 'LEVE'
        END as nivel_mora,
        u.usuario as gestor,
        '' as opciones
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pd.pdetalle_estado_cuota = 'pendiente'
      AND pd.pdetalle_fecha < CURDATE()
      AND pc.pres_aprobacion = 'aprobado'
      AND pc.id_usuario = p_id_usuario
    GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_nro_documento, c.cliente_celular, 
             pc.nro_prestamo, u.usuario
    ORDER BY dias_mora_max DESC, monto_vencido DESC;
END$$

DELIMITER ;

-- =====================================================
-- CREAR LOS PROCEDIMIENTOS
-- =====================================================

SELECT 'Procedimientos de mora creados exitosamente:' as mensaje;
SELECT 'SP_REPORTE_CLIENTES_MORA() - Lista completa de clientes en mora' as proc1;
SELECT 'SP_RESUMEN_MORA_CLIENTE() - Resumen agrupado por cliente' as proc2;
SELECT 'SP_ESTADISTICAS_MORA() - Estadísticas generales' as proc3;
SELECT 'SP_MORA_POR_USUARIO(id_usuario) - Mora filtrada por gestor' as proc4;

-- =====================================================
-- PROBAR LOS PROCEDIMIENTOS
-- =====================================================

SELECT 'Probando procedimientos...' as test;

-- Probar estadísticas
CALL SP_ESTADISTICAS_MORA();

-- Probar resumen por cliente (primeros 5)
SELECT 'Primeros 5 clientes en mora:' as titulo;
CALL SP_RESUMEN_MORA_CLIENTE();

-- Probar mora por usuario 1
SELECT 'Mora del usuario ID 1:' as titulo_usuario;
CALL SP_MORA_POR_USUARIO(1); 