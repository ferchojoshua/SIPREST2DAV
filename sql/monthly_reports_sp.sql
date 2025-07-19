DELIMITER $$
CREATE PROCEDURE SP_REPORTE_PROYECCION_MENSUAL(IN p_mes INT, IN p_anio INT)
BEGIN
    -- Clientes a cobrar y monto a cobrar
    SELECT
        COUNT(DISTINCT pc.cliente_id) AS clientes_a_cobrar,
        SUM(pd.pdetalle_monto_cuota) AS monto_a_cobrar
    FROM
        prestamo_detalle pd
    JOIN
        prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    WHERE
        MONTH(pd.pdetalle_fecha) = p_mes AND YEAR(pd.pdetalle_fecha) = p_anio
        AND pd.pdetalle_estado_cuota = 'pendiente'; -- Only pending payments

    -- Prestamos a colocar (approved loans in the month)
    SELECT
        COUNT(nro_prestamo) AS cantidad_prestamos_colocados,
        SUM(pres_monto) AS monto_prestamos_colocados
    FROM
        prestamo_cabecera
    WHERE
        MONTH(pres_f_emision) = p_mes AND YEAR(pres_f_emision) = p_anio
        AND pres_aprobacion = 'aprobado'; -- Only approved loans
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE SP_REPORTE_CIERRE_MENSUAL(IN p_mes INT, IN p_anio INT)
BEGIN
    -- Monto cobrado en el mes
    SELECT
        SUM(movi_monto) AS monto_cobrado_mes
    FROM
        movimientos
    WHERE
        MONTH(movi_fecha) = p_mes AND YEAR(movi_fecha) = p_anio
        AND movi_tipo = 'INGRESO'; -- Assuming 'INGRESO' means collection/payment

    -- Mora al cierre del mes (cuotas pendientes con fecha anterior o igual al final del mes)
    SELECT
        COUNT(DISTINCT pc.cliente_id) AS clientes_en_mora,
        SUM(pd.pdetalle_monto_cuota) AS monto_mora_fin_mes
    FROM
        prestamo_detalle pd
    JOIN
        prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    WHERE
        pd.pdetalle_estado_cuota = 'pendiente'
        AND pd.pdetalle_fecha <= LAST_DAY(STR_TO_DATE(CONCAT(p_anio, '-', p_mes, '-01'), '%Y-%m-%d'));
END$$
DELIMITER ; 