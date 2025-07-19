-- =====================================================
-- ACTIVACIÓN INMEDIATA DE BOTONES DE CAJA
-- Ejecuta este script para que funcionen los botones ahora mismo
-- =====================================================

-- 1. Crear tabla básica de sucursales si no existe
CREATE TABLE IF NOT EXISTS `cajas_sucursales` (
  `sucursal_id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_sucursal` varchar(100) NOT NULL,
  `direccion` text DEFAULT NULL,
  `tipo_caja` enum('principal','secundaria','temporal') DEFAULT 'principal',
  `monto_inicial_sugerido` decimal(15,2) DEFAULT 0.00,
  `estado` enum('activa','inactiva') DEFAULT 'activa',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sucursal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 2. Crear tabla básica de permisos si no existe
CREATE TABLE IF NOT EXISTS `caja_permisos` (
  `permiso_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `puede_abrir_caja` tinyint(1) DEFAULT 1,
  `puede_cerrar_caja` tinyint(1) DEFAULT 1,
  `puede_ver_reportes` tinyint(1) DEFAULT 1,
  `puede_gestionar_movimientos` tinyint(1) DEFAULT 1,
  `puede_supervisar` tinyint(1) DEFAULT 0,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`permiso_id`),
  UNIQUE KEY `uk_usuario_permisos` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- 3. Insertar sucursal principal por defecto
INSERT IGNORE INTO `cajas_sucursales` 
(`sucursal_id`, `nombre_sucursal`, `direccion`, `tipo_caja`, `monto_inicial_sugerido`, `estado`) 
VALUES 
(1, 'Sucursal Principal', 'Oficina Central', 'principal', 50000.00, 'activa');

-- 4. Dar permisos completos al usuario actual (asumiendo que es ID 1)
-- Puedes cambiar el ID según tu usuario
INSERT IGNORE INTO `caja_permisos` 
(`id_usuario`, `puede_abrir_caja`, `puede_cerrar_caja`, `puede_ver_reportes`, `puede_gestionar_movimientos`, `puede_supervisar`) 
VALUES 
(1, 1, 1, 1, 1, 1);

-- 5. Verificar que el módulo Dashboard de Caja esté en el menú
INSERT IGNORE INTO `modulos` (`id`, `modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES (65, 'Dashboard de Caja', 39, 'dashboard_caja.php', 'fas fa-tachometer-alt', 2);

-- 6. Dar permisos del nuevo módulo al perfil administrador (ID 1)
INSERT IGNORE INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`) 
VALUES (1, 65, 0);

-- =====================================================
-- VERIFICAR INSTALACIÓN
-- =====================================================

-- Mostrar sucursales creadas
SELECT 'SUCURSALES CONFIGURADAS:' as RESULTADO;
SELECT * FROM cajas_sucursales;

-- Mostrar permisos asignados
SELECT 'PERMISOS ASIGNADOS:' as RESULTADO;
SELECT cp.*, u.nombre_usuario 
FROM caja_permisos cp 
LEFT JOIN usuarios u ON cp.id_usuario = u.id_usuario;

-- Mostrar módulo en menú
SELECT 'MÓDULO EN MENÚ:' as RESULTADO;
SELECT * FROM modulos WHERE id = 65;

SELECT '✅ BOTONES ACTIVADOS CORRECTAMENTE' as RESULTADO; 