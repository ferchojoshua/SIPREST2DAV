DELIMITER $$

DROP PROCEDURE IF EXISTS SP_LISTAR_USUARIOS$$

CREATE PROCEDURE SP_LISTAR_USUARIOS()
BEGIN
    SELECT
        u.id_usuario,
        u.nombre_usuario,
        u.apellido_usuario,
        u.usuario, 
        u.clave,
        u.id_perfil_usuario, 
        p.descripcion,
        u.estado,
        u.sucursal_id,
        COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
        CASE 
            WHEN u.estado = 1 THEN 'Activo'
            ELSE 'Inactivo'
        END as estado_texto,
        '' as opciones
    FROM usuarios u
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    LEFT JOIN sucursales s ON u.sucursal_id = s.id
    ORDER BY u.id_usuario ASC;
END$$

DELIMITER ; 