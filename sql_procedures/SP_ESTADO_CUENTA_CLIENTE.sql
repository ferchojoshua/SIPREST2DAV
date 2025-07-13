DELIMITER $$

DROP PROCEDURE IF EXISTS SP_ESTADO_CUENTA_CLIENTE $$
CREATE PROCEDURE SP_ESTADO_CUENTA_CLIENTE(IN p_cliente_id INT)
BEGIN
    SELECT
        -- Información del préstamo
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        c.cliente_dni,
        c.cliente_celular,
        c.cliente_direccion,
        
        -- Datos financieros del préstamo
        pc.pres_monto,
        pc.pres_interes,
        pc.pres_monto_interes,
        pc.pres_monto_total,
        pc.pres_monto_cuota,
        pc.pres_cuotas,
        pc.pres_cuotas_pagadas,
        
        -- Fechas importantes
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha_registro,
        DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') as fecha_emision,
        
        -- Estado y forma de pago
        pc.pres_aprobacion as estado,
        fp.fpago_descripcion,
        m.moneda_simbolo,
        m.moneda_nombre,
        u.usuario,
        
        -- Cálculos de saldo
        ROUND((pc.pres_monto_total - (pc.pres_cuotas_pagadas * pc.pres_monto_cuota)), 2) as saldo_pendiente,
        ROUND((pc.pres_cuotas_pagadas * pc.pres_monto_cuota), 2) as monto_pagado,
        (pc.pres_cuotas - pc.pres_cuotas_pagadas) as cuotas_pendientes,
        
        -- Porcentaje de avance
        ROUND((pc.pres_cuotas_pagadas / pc.pres_cuotas * 100), 2) as porcentaje_avance
        
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pc.cliente_id = p_cliente_id
    ORDER BY pc.pres_fecha_registro DESC;
END $$

DELIMITER ; 