-- Script para agregar el módulo de Reporte Diario
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Insertar el nuevo módulo de Reporte Diario
INSERT INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES (48, 'Reporte Diario', 10, 'reporte_diario.php', 'far fa-circle', 15);

-- Insertar el módulo en el perfil de Administrador (id_perfil = 1)
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, 48, 0, 1);

-- Opcional: También agregar para perfil Colector (id_perfil = 2) si necesita acceso
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (2, 48, 0, 1);

-- Verificar que el módulo se agregó correctamente
SELECT * FROM modulos WHERE id = 48;
SELECT * FROM perfil_modulo WHERE id_modulo = 48; 