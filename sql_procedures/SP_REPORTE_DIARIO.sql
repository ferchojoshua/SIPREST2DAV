DELIMITER $$

DROP PROCEDURE IF EXISTS SP_REPORTE_DIARIO $$
CREATE PROCEDURE SP_REPORTE_DIARIO(IN p_fecha DATE)
BEGIN
    SELECT
        'PRÉSTAMOS' as tipo_operacion,
        COUNT(pc.pres_id) as cantidad,
        ROUND(IFNULL(SUM(pc.pres_monto),0),2) as monto_capital,
        ROUND(IFNULL(SUM(pc.pres_monto_interes),0),2) as monto_interes,
        ROUND(IFNULL(SUM(pc.pres_monto_total),0),2) as monto_total,
        m.moneda_simbolo,
        m.moneda_nombre
    FROM prestamo_cabecera pc
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE DATE(pc.pres_fecha_registro) = p_fecha
    AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
    GROUP BY m.moneda_id, m.moneda_simbolo, m.moneda_nombre

    UNION ALL

    SELECT
        'PAGOS DE CUOTAS' as tipo_operacion,
        COUNT(pd.pdetalle_id) as cantidad,
        ROUND(IFNULL(SUM(pd.pdetalle_monto_cuota),0),2) as monto_capital,
        0 as monto_interes,
        ROUND(IFNULL(SUM(pd.pdetalle_monto_cuota),0),2) as monto_total,
        m.moneda_simbolo,
        m.moneda_nombre
    FROM prestamo_detalle pd
    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE DATE(pd.pdetalle_fecha_registro) = p_fecha
    AND pd.pdetalle_estado_cuota = 'pagada'
    GROUP BY m.moneda_id, m.moneda_simbolo, m.moneda_nombre

    UNION ALL

    SELECT
        'INGRESOS' as tipo_operacion,
        COUNT(mv.movimientos_id) as cantidad,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_capital,
        0 as monto_interes,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_total,
        '$' as moneda_simbolo,
        'Mixta' as moneda_nombre
    FROM movimientos mv
    WHERE DATE(mv.movi_fecha) = p_fecha
    AND mv.movi_tipo = 'INGRESO'

    UNION ALL

    SELECT
        'EGRESOS' as tipo_operacion,
        COUNT(mv.movimientos_id) as cantidad,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_capital,
        0 as monto_interes,
        ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_total,
        '$' as moneda_simbolo,
        'Mixta' as moneda_nombre
    FROM movimientos mv
    WHERE DATE(mv.movi_fecha) = p_fecha
    AND mv.movi_tipo = 'EGRESO'

    ORDER BY tipo_operacion;
END $$

DELIMITER ; 