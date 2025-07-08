-- Script para crear tabla de notas de débito
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Crear tabla para notas de débito
CREATE TABLE IF NOT EXISTS `notas_debito` (
  `id_nota_debito` int(11) NOT NULL AUTO_INCREMENT,
  `nro_nota_debito` varchar(20) NOT NULL,
  `nro_prestamo` varchar(20) NOT NULL,
  `motivo` text NOT NULL,
  `interes_anterior` decimal(5,2) NOT NULL,
  `interes_nuevo` decimal(5,2) NOT NULL,
  `cuotas_anterior` int(11) NOT NULL,
  `cuotas_nuevas` int(11) NOT NULL,
  `cuota_anterior` decimal(10,2) NOT NULL,
  `cuota_nueva` decimal(10,2) NOT NULL,
  `saldo_capital` decimal(10,2) NOT NULL,
  `monto_interes_nuevo` decimal(10,2) NOT NULL,
  `monto_total_nuevo` decimal(10,2) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `estado` varchar(20) DEFAULT 'ACTIVO',
  PRIMARY KEY (`id_nota_debito`),
  UNIQUE KEY `nro_nota_debito` (`nro_nota_debito`),
  KEY `nro_prestamo` (`nro_prestamo`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `fk_notas_debito_prestamo` FOREIGN KEY (`nro_prestamo`) REFERENCES `prestamo_cabecera` (`nro_prestamo`) ON DELETE CASCADE,
  CONSTRAINT `fk_notas_debito_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- Agregar módulo para gestión de notas de débito
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Notas de Débito', 0, 'notas_debito.php', 'fas fa-file-invoice', 8)
ON DUPLICATE KEY UPDATE 
`modulo` = VALUES(`modulo`),
`vista` = VALUES(`vista`),
`icon_menu` = VALUES(`icon_menu`),
`orden` = VALUES(`orden`);

-- Obtener el ID del módulo recién insertado o existente
SET @modulo_id = (SELECT id FROM modulos WHERE vista = 'notas_debito.php' LIMIT 1);

-- Asignar el módulo al perfil Administrador (id_perfil = 1)
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @modulo_id, 'Y', 1)
ON DUPLICATE KEY UPDATE 
`vista_inicio` = VALUES(`vista_inicio`),
`estado` = VALUES(`estado`);

-- Verificar la creación
SELECT 'Tabla notas_debito creada exitosamente' as resultado;
DESCRIBE `notas_debito`;

-- Mostrar el módulo agregado
SELECT * FROM `modulos` WHERE `vista` = 'notas_debito.php'; 