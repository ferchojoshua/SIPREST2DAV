DELIMITER $$

DROP PROCEDURE IF EXISTS SP_REPORTE_MOROSOS $$
CREATE PROCEDURE SP_REPORTE_MOROSOS()
BEGIN
    SELECT
        c.cliente_id,
        c.cliente_nombres AS cliente_nombres,
        pc.nro_prestamo,
        pd.pdetalle_nro_cuota,
        IF(pd.pdetalle_fecha = '0000-00-00 00:00:00', '', DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y')) AS fecha_vencimiento, -- Formato de fecha con manejo de fechas inválidas
        pd.pdetalle_monto_cuota,
        pd.pdetalle_saldo_cuota,
        IFNULL(DATEDIFF(CURDATE(), pd.pdetalle_fecha), 0) AS dias_mora, -- Manejo de NULL para días de mora
        m.moneda_simbolo,
        (SELECT COUNT(*) FROM prestamo_detalle pd2 WHERE pd2.nro_prestamo = pc.nro_prestamo AND pd2.pdetalle_estado_cuota = 'pendiente') AS cuotas_pendientes_prestamo
    FROM prestamo_detalle pd
    INNER JOIN {51B8018B-CBC3-457B-B3C1-16A0D3BC4C36}.pngprestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE pd.pdetalle_estado_cuota = 'pendiente'
      AND pd.pdetalle_fecha < CURDATE()
    ORDER BY dias_mora DESC;
END $$

DELIMITER ; 