CREATE VIEW v_desembolsos_reporte AS
SELECT
    pc.nro_prestamo,
    c.cliente_nombres,
    pc.pres_monto AS monto_desembolsado,
    pc.pres_f_emision AS fecha_desembolso,
    pc.pres_fecha_registro AS fecha_registro_prestamo,
    pc.pres_aprobacion
FROM
    prestamo_cabecera pc
JOIN
    clientes c ON pc.cliente_id = c.cliente_id
WHERE
    pc.pres_aprobacion = 'aprobado'
    AND pc.pres_estado != 'Anulado'; 