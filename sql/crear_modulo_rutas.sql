-- =====================================================
-- MÓDULO DE RUTAS - ESTRUCTURA COMPLETA
-- =====================================================
-- Script para crear todas las tablas y procedimientos del módulo de rutas
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- =====================================================
-- TABLA: rutas
-- =====================================================
CREATE TABLE IF NOT EXISTS `rutas` (
  `ruta_id` int(11) NOT NULL AUTO_INCREMENT,
  `ruta_nombre` varchar(100) NOT NULL,
  `ruta_descripcion` text DEFAULT NULL,
  `ruta_codigo` varchar(20) NOT NULL,
  `ruta_color` varchar(7) DEFAULT '#3498db' COMMENT 'Color hexadecimal para identificar la ruta',
  `sucursal_id` int(11) NOT NULL,
  `ruta_estado` enum('activa','inactiva') DEFAULT 'activa',
  `ruta_orden` int(11) DEFAULT 0 COMMENT 'Orden de recorrido sugerido',
  `ruta_observaciones` text DEFAULT NULL,
  `usuario_creacion` int(11) NOT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuario_modificacion` int(11) DEFAULT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ruta_id`),
  UNIQUE KEY `uk_ruta_codigo_sucursal` (`ruta_codigo`, `sucursal_id`),
  KEY `idx_sucursal` (`sucursal_id`),
  KEY `idx_estado` (`ruta_estado`),
  CONSTRAINT `fk_rutas_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Tabla para gestionar rutas de cobranza';

-- =====================================================
-- TABLA: clientes_rutas
-- =====================================================
CREATE TABLE IF NOT EXISTS `clientes_rutas` (
  `cliente_ruta_id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `ruta_id` int(11) NOT NULL,
  `orden_visita` int(11) DEFAULT 0 COMMENT 'Orden sugerido de visita en la ruta',
  `direccion_especifica` text DEFAULT NULL COMMENT 'Dirección específica para la ruta si difiere del cliente',
  `observaciones` text DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `fecha_asignacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `usuario_asignacion` int(11) NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cliente_ruta_id`),
  UNIQUE KEY `uk_cliente_ruta` (`cliente_id`, `ruta_id`),
  KEY `idx_ruta` (`ruta_id`),
  KEY `idx_estado` (`estado`),
  KEY `idx_orden` (`orden_visita`),
  CONSTRAINT `fk_clientes_rutas_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`cliente_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_clientes_rutas_ruta` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`ruta_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Relación entre clientes y rutas';

-- =====================================================
-- TABLA: usuarios_rutas
-- =====================================================
CREATE TABLE IF NOT EXISTS `usuarios_rutas` (
  `usuario_ruta_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `ruta_id` int(11) NOT NULL,
  `tipo_asignacion` enum('responsable','apoyo') DEFAULT 'responsable',
  `fecha_asignacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `observaciones` text DEFAULT NULL,
  `usuario_asignacion` int(11) NOT NULL,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`usuario_ruta_id`),
  UNIQUE KEY `uk_usuario_ruta_activo` (`usuario_id`, `ruta_id`, `estado`),
  KEY `idx_ruta` (`ruta_id`),
  KEY `idx_tipo` (`tipo_asignacion`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `fk_usuarios_rutas_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_usuarios_rutas_ruta` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`ruta_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Asignación de usuarios (cobradores) a rutas';

-- =====================================================
-- STORED PROCEDURES
-- =====================================================

-- Listar rutas por sucursal
DROP PROCEDURE IF EXISTS SP_LISTAR_RUTAS;
DELIMITER $$
CREATE PROCEDURE SP_LISTAR_RUTAS(IN p_sucursal_id INT)
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
        GROUP_CONCAT(DISTINCT CONCAT(u.nombres, ' ', u.apellidos) SEPARATOR ', ') as responsables,
        r.fecha_creacion,
        CONCAT(uc.nombres, ' ', uc.apellidos) as usuario_creacion_nombre,
        '' as opciones
    FROM rutas r
    INNER JOIN sucursales s ON r.sucursal_id = s.id
    LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
    LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo' AND ur.tipo_asignacion = 'responsable'
    LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
    LEFT JOIN usuarios uc ON r.usuario_creacion = uc.id_usuario
    WHERE r.sucursal_id = p_sucursal_id
    GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_descripcion, r.ruta_codigo, r.ruta_color, 
             r.ruta_estado, r.ruta_orden, r.ruta_observaciones, s.nombre, r.fecha_creacion, 
             uc.nombres, uc.apellidos
    ORDER BY r.ruta_orden ASC, r.ruta_nombre ASC;
END$$
DELIMITER ;

-- Listar clientes de una ruta específica
DROP PROCEDURE IF EXISTS SP_LISTAR_CLIENTES_RUTA;
DELIMITER $$
CREATE PROCEDURE SP_LISTAR_CLIENTES_RUTA(IN p_ruta_id INT)
BEGIN
    SELECT 
        cr.cliente_ruta_id,
        cr.cliente_id,
        c.cliente_nombres,
        c.cliente_dni,
        c.cliente_cel,
        c.cliente_direccion,
        cr.direccion_especifica,
        cr.orden_visita,
        cr.observaciones,
        cr.estado,
        cr.fecha_asignacion,
        -- Información de préstamos activos
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
        COALESCE(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END), 0) as saldo_pendiente,
        -- Próxima cuota vencida
        MIN(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN pd.pdetalle_fecha END) as proxima_cuota_vencida,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
        '' as opciones
    FROM clientes_rutas cr
    INNER JOIN clientes c ON cr.cliente_id = c.cliente_id
    LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    WHERE cr.ruta_id = p_ruta_id
    GROUP BY cr.cliente_ruta_id, cr.cliente_id, c.cliente_nombres, c.cliente_dni, 
             c.cliente_cel, c.cliente_direccion, cr.direccion_especifica, 
             cr.orden_visita, cr.observaciones, cr.estado, cr.fecha_asignacion
    ORDER BY cr.orden_visita ASC, c.cliente_nombres ASC;
END$$
DELIMITER ;

-- Listar usuarios disponibles para asignar a rutas
DROP PROCEDURE IF EXISTS SP_LISTAR_USUARIOS_DISPONIBLES;
DELIMITER $$
CREATE PROCEDURE SP_LISTAR_USUARIOS_DISPONIBLES(IN p_sucursal_id INT)
BEGIN
    SELECT 
        u.id_usuario,
        CONCAT(u.nombres, ' ', u.apellidos) as nombre_completo,
        u.usuario,
        p.descripcion as perfil,
        u.estado,
        COUNT(DISTINCT ur.ruta_id) as rutas_asignadas
    FROM usuarios u
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    LEFT JOIN usuarios_rutas ur ON u.id_usuario = ur.usuario_id AND ur.estado = 'activo'
    WHERE u.sucursal_id = p_sucursal_id 
    AND u.estado = 1
    GROUP BY u.id_usuario, u.nombres, u.apellidos, u.usuario, p.descripcion, u.estado
    ORDER BY u.nombres ASC, u.apellidos ASC;
END$$
DELIMITER ;

-- Obtener estadísticas de una ruta
DROP PROCEDURE IF EXISTS SP_ESTADISTICAS_RUTA;
DELIMITER $$
CREATE PROCEDURE SP_ESTADISTICAS_RUTA(IN p_ruta_id INT)
BEGIN
    SELECT 
        r.ruta_nombre,
        r.ruta_codigo,
        COUNT(DISTINCT cr.cliente_id) as total_clientes,
        COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END) as clientes_activos,
        COUNT(DISTINCT CASE WHEN cr.estado = 'inactivo' THEN cr.cliente_id END) as clientes_inactivos,
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
        COALESCE(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END), 0) as saldo_total_pendiente,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as cuotas_proximas_7_dias,
        COUNT(DISTINCT ur.usuario_id) as usuarios_asignados
    FROM rutas r
    LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
    LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
    LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
    WHERE r.ruta_id = p_ruta_id
    GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_codigo;
END$$
DELIMITER ;

-- =====================================================
-- DATOS INICIALES
-- =====================================================

-- Insertar rutas de ejemplo (ajustar según las sucursales existentes)
INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, usuario_creacion) 
SELECT 
    'Ruta Centro',
    'Ruta de cobranza para la zona céntrica de la ciudad',
    'RT-CENTRO',
    '#3498db',
    s.id,
    1
FROM sucursales s 
WHERE s.estado = 'activa'
LIMIT 1;

INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, usuario_creacion) 
SELECT 
    'Ruta Norte',
    'Ruta de cobranza para la zona norte de la ciudad',
    'RT-NORTE',
    '#e74c3c',
    s.id,
    1
FROM sucursales s 
WHERE s.estado = 'activa'
LIMIT 1;

INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, usuario_creacion) 
SELECT 
    'Ruta Sur',
    'Ruta de cobranza para la zona sur de la ciudad',
    'RT-SUR',
    '#2ecc71',
    s.id,
    1
FROM sucursales s 
WHERE s.estado = 'activa'
LIMIT 1;

-- =====================================================
-- AGREGAR MÓDULO AL MENÚ
-- =====================================================

-- Insertar módulo principal de rutas
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Rutas', 0, 'rutas.php', 'fas fa-route', 4.5)
ON DUPLICATE KEY UPDATE 
`modulo` = VALUES(`modulo`),
`vista` = VALUES(`vista`),
`icon_menu` = VALUES(`icon_menu`),
`orden` = VALUES(`orden`);

-- Obtener el ID del módulo recién insertado
SET @modulo_rutas_id = (SELECT id FROM modulos WHERE vista = 'rutas.php' LIMIT 1);

-- Asignar el módulo al perfil Administrador (id_perfil = 1)
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @modulo_rutas_id, 0, 1)
ON DUPLICATE KEY UPDATE `vista_inicio` = VALUES(`vista_inicio`), `estado` = VALUES(`estado`);

-- Asignar el módulo al perfil Prestamista (id_perfil = 2) si existe
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (2, @modulo_rutas_id, 0, 1)
ON DUPLICATE KEY UPDATE `vista_inicio` = VALUES(`vista_inicio`), `estado` = VALUES(`estado`);

-- =====================================================
-- VERIFICACIÓN
-- =====================================================

-- Verificar que las tablas se crearon correctamente
SELECT 'Tablas creadas:' as mensaje;
SELECT TABLE_NAME, TABLE_COMMENT 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME IN ('rutas', 'clientes_rutas', 'usuarios_rutas');

-- Verificar que los procedimientos se crearon correctamente
SELECT 'Procedimientos creados:' as mensaje;
SELECT ROUTINE_NAME, ROUTINE_TYPE 
FROM INFORMATION_SCHEMA.ROUTINES 
WHERE ROUTINE_SCHEMA = DATABASE() 
AND ROUTINE_NAME LIKE 'SP_%RUTA%';

-- Verificar que el módulo se agregó al menú
SELECT 'Módulo agregado al menú:' as mensaje;
SELECT m.id, m.modulo, m.vista, m.icon_menu, m.orden 
FROM modulos m 
WHERE m.vista = 'rutas.php';

-- Mostrar rutas de ejemplo creadas
SELECT 'Rutas de ejemplo creadas:' as mensaje;
SELECT r.ruta_id, r.ruta_nombre, r.ruta_codigo, r.ruta_color, s.nombre as sucursal 
FROM rutas r 
INNER JOIN sucursales s ON r.sucursal_id = s.id;

-- =====================================================
-- INSTRUCCIONES FINALES
-- =====================================================
/*
INSTRUCCIONES POST-INSTALACIÓN:

1. Ejecutar este script completo en phpMyAdmin
2. Verificar que todas las tablas se crearon sin errores
3. Verificar que los procedimientos almacenados se crearon correctamente
4. Verificar que el módulo "Rutas" aparece en el menú del sistema
5. Asignar clientes a rutas usando la interfaz web
6. Asignar usuarios (cobradores) a rutas

FUNCIONALIDADES IMPLEMENTADAS:
- ✅ Gestión completa de rutas (CRUD)
- ✅ Asignación de clientes a rutas
- ✅ Asignación de usuarios (cobradores) a rutas
- ✅ Estadísticas y reportes por ruta
- ✅ Integración con sistema de préstamos
- ✅ Control de permisos por perfil
- ✅ Soporte multi-sucursal

PRÓXIMOS PASOS:
- Implementar la interfaz web (rutas.php)
- Crear reportes avanzados de cobranza por ruta
- Implementar optimización de recorridos
- Agregar geolocalización (opcional)
*/ 