INSERT INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) VALUES
(84, 'Reporte de Desembolsos', 10, 'reporte_desembolsos.php', 'far fa-circle', 28);

INSERT INTO `perfil_modulo` (`idperfil_modulo`, `id_perfil`, `id_modulo`, `vista_inicio`, `estado`) VALUES
(NULL, 1, 84, 0, 1); 