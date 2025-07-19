-- Script para crear el procedimiento almacenado para el reporte de saldos arrastrados.
-- Ejecutar este script en la base de datos `dbprestamo`.

DELIMITER $$

CREATE PROCEDURE `SP_REPORTE_SALDOS_ARRASTRADOS`(IN `fecha_inicio` DATE, IN `fecha_fin` DATE)
BEGIN
    SELECT 
        `log_id`,
        `nro_prestamo`,
        `cuota_origen`,
        `cuota_destino`,
        `monto_arrastrado`,
        DATE_FORMAT(`fecha_movimiento`, '%d/%m/%Y %H:%i:%s') as `fecha_movimiento`
    FROM 
        `log_saldos_arrastrados`
    WHERE 
        DATE(`fecha_movimiento`) BETWEEN `fecha_inicio` AND `fecha_fin`
    ORDER BY 
        `fecha_movimiento` DESC;
END$$

DELIMITER ; 