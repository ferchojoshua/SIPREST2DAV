INSERT INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) VALUES
(85, 'Reporte Proyección', 10, 'reporte_proyeccion.php', 'far fa-circle', 29),
(86, 'Reporte Cierre', 10, 'reporte_cierre.php', 'far fa-circle', 30);

INSERT INTO `perfil_modulo` (`idperfil_modulo`, `id_perfil`, `id_modulo`, `vista_inicio`, `estado`) VALUES
(NULL, 1, 85, 0, 1),
(NULL, 1, 86, 0, 1); 