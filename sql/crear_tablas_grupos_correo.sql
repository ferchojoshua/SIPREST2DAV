-- Creación de la tabla para los grupos de correo
CREATE TABLE `reporte_grupos` (
  `grupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_nombre` varchar(100) NOT NULL,
  `grupo_descripcion` varchar(255) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`grupo_id`),
  UNIQUE KEY `grupo_nombre` (`grupo_nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Creación de la tabla para los miembros de cada grupo
CREATE TABLE `reporte_grupo_miembros` (
  `miembro_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_id` int(11) NOT NULL,
  `miembro_email` varchar(150) NOT NULL,
  `miembro_nombre` varchar(150) DEFAULT NULL,
  `fecha_agregado` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`miembro_id`),
  KEY `grupo_id` (`grupo_id`),
  CONSTRAINT `reporte_grupo_miembros_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `reporte_grupos` (`grupo_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar un grupo de ejemplo para empezar
INSERT INTO `reporte_grupos` (`grupo_nombre`, `grupo_descripcion`) VALUES
('Administración', 'Personal administrativo y gerencial'); 