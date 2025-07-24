DELIMITER $$

DROP PROCEDURE IF EXISTS SP_LISTAR_CAJA$$

CREATE PROCEDURE SP_LISTAR_CAJA()
BEGIN
    SELECT 
        c.caja_id,
        c.caja_descripcion,
        c.caja_monto_inicial,
        COALESCE(c.caja_monto_ingreso, 0) as caja_monto_ingreso,
        COALESCE(c.caja__monto_egreso, 0) as caja__monto_egreso,
        COALESCE(c.caja_prestamo, 0) as caja_prestamo,
        c.caja_f_apertura,
        c.caja_f_cierre,
        COALESCE(c.caja_count_prestamo, 0) as caja_count_prestamo,
        COALESCE(c.caja_monto_total, c.caja_monto_inicial) as caja_monto_total,
        c.caja_estado,
        c.caja_hora_apertura,
        c.caja_hora_cierre,
        s.nombre as sucursal_nombre,
        CONCAT(COALESCE(u.nombre_usuario, ''), ' ', COALESCE(u.apellido_usuario, '')) as usuario_apertura_nombre
    FROM caja c
    LEFT JOIN sucursales s ON c.sucursal_id = s.id
    LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario
    ORDER BY c.caja_f_apertura DESC, c.caja_hora_apertura DESC;
END$$

DELIMITER ; 