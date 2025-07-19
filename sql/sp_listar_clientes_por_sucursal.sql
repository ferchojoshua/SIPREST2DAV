DELIMITER $$
DROP PROCEDURE IF EXISTS `SP_LISTAR_CLIENTES`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CLIENTES`(IN `_sucursal_id` INT)
BEGIN
    SELECT
        c.cliente_id,
        c.cliente_nombres,
        c.cliente_dni,
        c.cliente_cel,
        c.cliente_estado_prestamo,
        c.cliente_direccion,
        c.cliente_obs,
        c.cliente_correo,
        c.cliente_estatus,
        c.cliente_cant_prestamo,
        c.cliente_refe,
        c.cliente_cel_refe,
        s.nombre as sucursal_nombre
    FROM
        clientes c
    INNER JOIN
        sucursales s ON c.sucursal_id = s.id
    WHERE
        c.sucursal_id = _sucursal_id
    ORDER BY
        c.cliente_id
    DESC;
END$$
DELIMITER ; 