-- Script para actualizar el procedimiento almacenado SP_LISTAR_USUARIOS
-- Ejecutar este script en la base de datos para incluir los nuevos campos

DROP PROCEDURE IF EXISTS `SP_LISTAR_USUARIOS`;

DELIMITER $$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIOS` ()   
BEGIN
    SELECT
        u.id_usuario,
        u.nombre_usuario,
        u.apellido_usuario,
        u.usuario, 
        u.clave,
        u.id_perfil_usuario, 
        p.descripcion,
        u.sucursal_id,
        COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
        u.estado,
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
        CONCAT(
            '<div class="btn-group" role="group">',
                '<button type="button" class="btn btn-warning btn-sm btn_editar_usuario" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Usuario">',
                    '<i class="fas fa-edit"></i>',
                '</button>',
                '<button type="button" class="btn btn-info btn-sm btn_cambiar_clave" data-bs-toggle="tooltip" data-bs-placement="top" title="Cambiar Clave">',
                    '<i class="fas fa-key"></i>',
                '</button>',
                CASE 
                    WHEN u.estado = 1 THEN 
                        '<button type="button" class="btn btn-danger btn-sm btn_desactivar_usuario" data-bs-toggle="tooltip" data-bs-placement="top" title="Desactivar Usuario"><i class="fas fa-user-times"></i></button>'
                    ELSE 
                        '<button type="button" class="btn btn-success btn-sm btn_activar_usuario" data-bs-toggle="tooltip" data-bs-placement="top" title="Activar Usuario"><i class="fas fa-user-check"></i></button>'
                END,
            '</div>'
        ) as opciones
    FROM usuarios u
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    LEFT JOIN sucursales s ON u.sucursal_id = s.id
    ORDER BY u.id_usuario ASC;
END$$

DELIMITER ;

-- Verificar que el procedimiento se actualizó correctamente
-- CALL SP_LISTAR_USUARIOS(); 