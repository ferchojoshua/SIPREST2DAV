-- Script para corregir stored procedures con errores de columnas
-- Ejecutar este script en tu base de datos MySQL

-- Corregir SP_LISTAR_RUTAS
DROP PROCEDURE IF EXISTS SP_LISTAR_RUTAS;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_RUTAS` (IN `p_sucursal_id` INT) 
BEGIN
    SELECT 
        r.ruta_id,
        r.ruta_nombre,
        r.ruta_descripcion,
        r.ruta_codigo,
        r.ruta_color,
        r.ruta_estado,
        r.ruta_orden,
        r.ruta_observaciones,
        s.nombre as sucursal_nombre,
        COUNT(DISTINCT cr.cliente_id) as total_clientes,
        COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END) as clientes_activos,
        GROUP_CONCAT(DISTINCT CONCAT(u.nombre_usuario, ' ', COALESCE(u.apellido_usuario, '')) SEPARATOR ', ') as responsables,
        r.fecha_creacion,
        CONCAT(uc.nombre_usuario, ' ', COALESCE(uc.apellido_usuario, '')) as usuario_creacion_nombre,
        '' as opciones
    FROM rutas r
    LEFT JOIN sucursales s ON r.sucursal_id = s.id
    LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
    LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id
    LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
    LEFT JOIN usuarios uc ON r.usuario_creacion = uc.id_usuario
    WHERE (p_sucursal_id IS NULL OR r.sucursal_id = p_sucursal_id)
    GROUP BY r.ruta_id
    ORDER BY r.ruta_orden, r.ruta_nombre;
END$$

DELIMITER ;

-- Corregir SP_LISTAR_CAJA
DROP PROCEDURE IF EXISTS SP_LISTAR_CAJA;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_CAJA` (IN `p_sucursal_id` INT) 
BEGIN
    SELECT 
        c.caja_id,
        c.caja_fecha_apertura,
        c.caja_hora_apertura,
        c.caja_fecha_cierre,
        c.caja_hora_cierre,
        s.nombre as sucursal_nombre,
        CONCAT(COALESCE(u.nombre_usuario, ''), ' ', COALESCE(u.apellido_usuario, '')) as usuario_apertura_nombre
    FROM caja c
    LEFT JOIN sucursales s ON c.sucursal_id = s.id
    LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario
    WHERE (p_sucursal_id IS NULL OR c.sucursal_id = p_sucursal_id)
    ORDER BY c.caja_fecha_apertura DESC;
END$$

DELIMITER ;

-- Corregir SP_LISTAR_USUARIOS_POR_SUCURSAL
DROP PROCEDURE IF EXISTS SP_LISTAR_USUARIOS_POR_SUCURSAL;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIOS_POR_SUCURSAL` (IN `p_sucursal_id` INT) 
BEGIN
    SELECT 
        u.id_usuario,
        CONCAT(u.nombre_usuario, ' ', COALESCE(u.apellido_usuario, '')) as nombre_completo,
        u.usuario,
        p.descripcion as perfil,
        CASE 
            WHEN u.estado = 1 THEN 'Activo'
            ELSE 'Inactivo'
        END as estado_texto,
        u.cedula,
        u.celular,
        u.cargo,
        u.telefono_whatsapp,
        u.whatsapp_activo,
        u.whatsapp_admin,
        u.ciudad,
        u.direccion,
        u.profesion,
        u.fecha_ingreso,
        u.numero_seguro,
        u.forma_pago,
        '' as opciones
    FROM usuarios u
    LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    WHERE u.sucursal_id = p_sucursal_id 
    AND u.estado = 1
    GROUP BY u.id_usuario, u.nombre_usuario, u.apellido_usuario, u.usuario, p.descripcion, u.estado
    ORDER BY u.nombre_usuario ASC, u.apellido_usuario ASC;
END$$

DELIMITER ;

-- Mensaje de confirmación
SELECT 'Stored procedures corregidos exitosamente' as mensaje; 