DELIMITER $$

-- SP_DATOS_DASHBOARD_FILTRADO
-- Para obtener métricas principales del dashboard con filtros de sucursal y período
DROP PROCEDURE IF EXISTS SP_DATOS_DASHBOARD_FILTRADO$$
CREATE PROCEDURE SP_DATOS_DASHBOARD_FILTRADO(
    IN p_sucursal_id INT,
    IN p_periodo VARCHAR(50)
)
BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcular fechas según el período
    IF p_periodo = 'hoy' THEN
        SET fecha_inicio = CURDATE();
        SET fecha_fin = CURDATE();
    ELSEIF p_periodo = 'semana' THEN
        SET fecha_inicio = SUBDATE(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY); -- Lunes de esta semana
        SET fecha_fin = ADDDATE(fecha_inicio, INTERVAL 6 DAY); -- Domingo de esta semana
    ELSEIF p_periodo = 'mes' THEN
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY); -- Primer día del mes actual
        SET fecha_fin = LAST_DAY(CURDATE()); -- Último día del mes actual
    ELSEIF p_periodo = 'trimestre' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL (QUARTER(CURDATE()) - 1) QUARTER;
        SET fecha_fin = LAST_DAY(MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) * 3 - 1 MONTH);
    ELSEIF p_periodo = 'año' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1);
        SET fecha_fin = MAKEDATE(YEAR(CURDATE()), 366); -- Fin de año (maneja bisiestos)
    ELSE
        -- Default a 'mes' si el período no es reconocido
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    END IF;

    SELECT
        ROUND(IFNULL((SELECT SUM(mc.movi_monto) FROM movimientos_caja mc
                      WHERE mc.movi_tipo = 'INGRESO'
                      AND (p_sucursal_id IS NULL OR mc.id_sucursal = p_sucursal_id)
                      AND DATE(mc.movi_fecha) BETWEEN fecha_inicio AND fecha_fin), 0) -
              IFNULL((SELECT SUM(mc.movi_monto) FROM movimientos_caja mc
                      WHERE mc.movi_tipo = 'EGRESO'
                      AND (p_sucursal_id IS NULL OR mc.id_sucursal = p_sucursal_id)
                      AND DATE(mc.movi_fecha) BETWEEN fecha_inicio AND fecha_fin), 0), 2) AS caja,
        IFNULL((SELECT COUNT(c.id_cliente) FROM clientes c
                WHERE (p_sucursal_id IS NULL OR c.id_sucursal = p_sucursal_id)
                AND DATE(c.cliente_fecha_registro) BETWEEN fecha_inicio AND fecha_fin), 0) AS clientes,
        IFNULL((SELECT COUNT(pc.pres_id) FROM prestamo_cabecera pc
                WHERE pc.pres_aprobacion = 'aprobado'
                AND (p_sucursal_id IS NULL OR pc.id_sucursal = p_sucursal_id)
                AND DATE(pc.pres_fecha_registro) BETWEEN fecha_inicio AND fecha_fin), 0) AS prestamos,
        ROUND(IFNULL((SELECT SUM(pd.pdetalle_saldo) FROM prestamo_detalle pd
                      INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                      WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
                      AND (p_sucursal_id IS NULL OR pc.id_sucursal = p_sucursal_id)
                      AND DATE(pd.pdetalle_fecha) BETWEEN fecha_inicio AND fecha_fin), 0), 2) AS total_cobrar;
END $$

-- SP_PRESTAMOS_MES_ACTUAL_FILTRADO
-- Para obtener préstamos del mes para el gráfico de barras con filtros
DROP PROCEDURE IF EXISTS SP_PRESTAMOS_MES_ACTUAL_FILTRADO$$
CREATE PROCEDURE SP_PRESTAMOS_MES_ACTUAL_FILTRADO(
    IN p_sucursal_id INT,
    IN p_periodo VARCHAR(50)
)
BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcular fechas según el período
    IF p_periodo = 'hoy' THEN
        SET fecha_inicio = CURDATE();
        SET fecha_fin = CURDATE();
    ELSEIF p_periodo = 'semana' THEN
        SET fecha_inicio = SUBDATE(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY);
        SET fecha_fin = ADDDATE(fecha_inicio, INTERVAL 6 DAY);
    ELSEIF p_periodo = 'mes' THEN
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    ELSEIF p_periodo = 'trimestre' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL (QUARTER(CURDATE()) - 1) QUARTER;
        SET fecha_fin = LAST_DAY(MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) * 3 - 1 MONTH);
    ELSEIF p_periodo = 'año' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1);
        SET fecha_fin = MAKEDATE(YEAR(CURDATE()), 366);
    ELSE
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    END IF;

    SELECT
        DATE_FORMAT(pres_fecha_registro, '%Y-%m-%d') AS fecha,
        COUNT(pres_id) AS total_prestamos,
        SUM(pres_monto_total) AS monto_total
    FROM prestamo_cabecera
    WHERE pres_aprobacion = 'aprobado'
    AND (p_sucursal_id IS NULL OR id_sucursal = p_sucursal_id)
    AND DATE(pres_fecha_registro) BETWEEN fecha_inicio AND fecha_fin
    GROUP BY DATE_FORMAT(pres_fecha_registro, '%Y-%m-%d')
    ORDER BY fecha;
END $$

-- SP_KPIs_FILTRADOS
-- Para obtener los KPIs gerenciales con filtros
DROP PROCEDURE IF EXISTS SP_KPIs_FILTRADOS$$
CREATE PROCEDURE SP_KPIs_FILTRADOS(
    IN p_sucursal_id INT,
    IN p_periodo VARCHAR(50)
)
BEGIN
    DECLARE fecha_inicio DATE;
    DECLARE fecha_fin DATE;

    -- Calcular fechas según el período
    IF p_periodo = 'hoy' THEN
        SET fecha_inicio = CURDATE();
        SET fecha_fin = CURDATE();
    ELSEIF p_periodo = 'semana' THEN
        SET fecha_inicio = SUBDATE(CURDATE(), INTERVAL (WEEKDAY(CURDATE()) + 1) DAY);
        SET fecha_fin = ADDDATE(fecha_inicio, INTERVAL 6 DAY);
    ELSEIF p_periodo = 'mes' THEN
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    ELSEIF p_periodo = 'trimestre' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL (QUARTER(CURDATE()) - 1) QUARTER;
        SET fecha_fin = LAST_DAY(MAKEDATE(YEAR(CURDATE()), 1) + INTERVAL QUARTER(CURDATE()) * 3 - 1 MONTH);
    ELSEIF p_periodo = 'año' THEN
        SET fecha_inicio = MAKEDATE(YEAR(CURDATE()), 1);
        SET fecha_fin = MAKEDATE(YEAR(CURDATE()), 366);
    ELSE
        SET fecha_inicio = DATE_SUB(CURDATE(), INTERVAL DAYOFMONTH(CURDATE()) - 1 DAY);
        SET fecha_fin = LAST_DAY(CURDATE());
    END IF;

    SELECT
        ROUND(IFNULL(SUM(pc.pres_saldo_actual), 0), 2) AS saldo_cartera,
        IFNULL(COUNT(DISTINCT c.id_cliente), 0) AS clientes_activos,
        ROUND(IFNULL(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' AND pd.pdetalle_fecha < CURDATE() THEN pd.pdetalle_saldo ELSE 0 END), 0), 2) AS monto_en_mora,
        ROUND(IFNULL((SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' AND pd.pdetalle_fecha < CURDATE() THEN pd.pdetalle_saldo ELSE 0 END) / NULLIF(SUM(pc.pres_saldo_actual), 0)) * 100, 0), 2) AS porcentaje_mora
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.id_cliente = c.id_cliente
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    WHERE pc.pres_aprobacion = 'aprobado'
    AND (p_sucursal_id IS NULL OR pc.id_sucursal = p_sucursal_id)
    AND DATE(pc.pres_fecha_registro) BETWEEN fecha_inicio AND fecha_fin;
END $$

-- SP_OBTENER_ESTADISTICAS_RAPIDAS
-- Para obtener el número de aperturas y cierres de caja del día actual
DROP PROCEDURE IF EXISTS SP_OBTENER_ESTADISTICAS_RAPIDAS$$
CREATE PROCEDURE SP_OBTENER_ESTADISTICAS_RAPIDAS()
BEGIN
    SELECT
        (SELECT COUNT(caja_id) FROM caja WHERE DATE(caja_f_apertura) = CURDATE()) AS aperturas_hoy,
        (SELECT COUNT(caja_id) FROM caja WHERE DATE(caja_f_cierre) = CURDATE()) AS cierres_hoy;
END $$

DELIMITER ; 