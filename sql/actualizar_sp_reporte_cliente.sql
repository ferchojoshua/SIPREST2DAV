-- Script para actualizar el Stored Procedure SP_REPORTE_POR_CLIENTE
-- Agrega: nombre_cliente, fecha_apertura, fecha_vencimiento, moneda_simbolo

USE credicrece;

-- Eliminar el procedimiento existente
DROP PROCEDURE IF EXISTS SP_REPORTE_POR_CLIENTE;

-- Crear el procedimiento actualizado
DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REPORTE_POR_CLIENTE` (IN `id` INT)   
BEGIN
    SELECT 
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        pc.pres_monto AS monto_prestamo,
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha_prestamo,
        DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') AS fecha_apertura,
        pc.pres_monto_total,
        pc.pres_monto_cuota,
        pc.pres_cuotas,
        pc.fpago_id,
        fp.fpago_descripcion,
        pc.pres_aprobacion AS estado,
        '' AS opciones,
        pc.pres_interes,
        pc.pres_monto_interes,
        pc.pres_cuotas_pagadas,
        (pc.pres_monto_total - (pc.pres_cuotas_pagadas * pc.pres_monto_cuota)) AS saldo_pendiente,
        DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') AS femision,
        -- Calcular fecha de vencimiento basada en la frecuencia de pago
        CASE 
            WHEN fp.fpago_descripcion LIKE '%Diario%' OR fp.fpago_descripcion LIKE '%DIARIO%' THEN 
                DATE_FORMAT(DATE_ADD(pc.pres_f_emision, INTERVAL pc.pres_cuotas DAY), '%d/%m/%Y')
            WHEN fp.fpago_descripcion LIKE '%Semanal%' OR fp.fpago_descripcion LIKE '%SEMANAL%' THEN 
                DATE_FORMAT(DATE_ADD(pc.pres_f_emision, INTERVAL pc.pres_cuotas WEEK), '%d/%m/%Y')
            WHEN fp.fpago_descripcion LIKE '%Quincenal%' OR fp.fpago_descripcion LIKE '%QUINCENAL%' THEN 
                DATE_FORMAT(DATE_ADD(pc.pres_f_emision, INTERVAL (pc.pres_cuotas * 15) DAY), '%d/%m/%Y')
            WHEN fp.fpago_descripcion LIKE '%Mensual%' OR fp.fpago_descripcion LIKE '%MENSUAL%' THEN 
                DATE_FORMAT(DATE_ADD(pc.pres_f_emision, INTERVAL pc.pres_cuotas MONTH), '%d/%m/%Y')
            ELSE 
                DATE_FORMAT(DATE_ADD(pc.pres_f_emision, INTERVAL pc.pres_cuotas MONTH), '%d/%m/%Y')
        END AS fecha_vencimiento,
        m.moneda_simbolo
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
    WHERE pc.cliente_id = id
    ORDER BY pc.pres_fecha_registro DESC;
END$$

DELIMITER ;

-- Mensaje de confirmación
SELECT 'Stored Procedure SP_REPORTE_POR_CLIENTE actualizado exitosamente' AS resultado; 