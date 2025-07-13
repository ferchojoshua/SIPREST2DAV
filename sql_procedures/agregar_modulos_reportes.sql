-- Script para agregar los módulos de reportes al menú
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Reporte Diario
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Reporte Diario', 10, 'reporte_diario.php', 'far fa-circle', 16)
ON DUPLICATE KEY UPDATE 
`modulo` = VALUES(`modulo`),
`padre_id` = VALUES(`padre_id`),
`vista` = VALUES(`vista`),
`icon_menu` = VALUES(`icon_menu`),
`orden` = VALUES(`orden`);

SET @reporte_diario_id = (SELECT id FROM modulos WHERE vista = 'reporte_diario.php' LIMIT 1);
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @reporte_diario_id, 0, 1)
ON DUPLICATE KEY UPDATE `vista_inicio` = VALUES(`vista_inicio`), `estado` = VALUES(`estado`);

-- Estado de Cuenta en reporte por cliente
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Estado de Cuenta Cliente', 10, 'estado_cuenta_cliente.php', 'far fa-circle', 17)
ON DUPLICATE KEY UPDATE 
`modulo` = VALUES(`modulo`),
`padre_id` = VALUES(`padre_id`),
`vista` = VALUES(`vista`),
`icon_menu` = VALUES(`icon_menu`),
`orden` = VALUES(`orden`);

SET @estado_cuenta_cliente_id = (SELECT id FROM modulos WHERE vista = 'estado_cuenta_cliente.php' LIMIT 1);
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @estado_cuenta_cliente_id, 0, 1)
ON DUPLICATE KEY UPDATE `vista_inicio` = VALUES(`vista_inicio`), `estado` = VALUES(`estado`);

-- Reporte por Mora
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Reporte Mora', 10, 'reporte_mora.php', 'far fa-circle', 18)
ON DUPLICATE KEY UPDATE 
`modulo` = VALUES(`modulo`),
`padre_id` = VALUES(`padre_id`),
`vista` = VALUES(`vista`),
`icon_menu` = VALUES(`icon_menu`),
`orden` = VALUES(`orden`);

SET @reporte_mora_id = (SELECT id FROM modulos WHERE vista = 'reporte_mora.php' LIMIT 1);
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @reporte_mora_id, 0, 1)
ON DUPLICATE KEY UPDATE `vista_inicio` = VALUES(`vista_inicio`), `estado` = VALUES(`estado`); 