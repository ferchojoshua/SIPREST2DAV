-- 1. Insertar el nuevo módulo en la tabla de módulos
-- El padre_id = 14 corresponde al módulo "Mantenimiento".
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`)
VALUES ('Grupos de Reportes', 14, 'grupos_reportes.php', 'fa fa-envelope', 15);

-- 2. Asignar el permiso del nuevo módulo al Perfil de Administrador
-- Primero, obtenemos el ID del módulo que acabamos de crear.
SET @id_modulo_nuevo = LAST_INSERT_ID();

-- Luego, asignamos el permiso al perfil de administrador (asumiendo que el perfil de administrador tiene el ID 1).
-- La tabla de permisos correcta es 'perfil_modulo'.
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`)
VALUES (1, @id_modulo_nuevo);

-- Opcional: Si quieres darle el permiso directamente a un usuario específico (ej: tu usuario con id 1)
-- INSERT INTO `vs_usuario_modulo` (`id_usuario`, `id_modulo`) VALUES (1, @id_modulo_nuevo); 