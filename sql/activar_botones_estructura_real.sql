-- =====================================================
-- ACTIVAR BOTONES - USANDO ESTRUCTURA REAL DE TU TABLA
-- =====================================================

-- 1. Verificar datos actuales
SELECT 'DATOS ACTUALES EN cajas_sucursales:' as INFO;
SELECT * FROM cajas_sucursales;

-- 2. Insertar sucursal usando las COLUMNAS REALES que tienes
INSERT IGNORE INTO cajas_sucursales 
(caja_sucursal_id, nombre_caja, descripcion, monto_limite, usuario_responsable, estado, tipo_caja, ubicacion_fisica, usuario_creacion) 
VALUES 
(1, 'Caja Principal - Sistema', 'Caja principal del sistema mejorado', 100000.00, 1, 'activa', 'principal', 'Oficina Central', 1);

-- 3. Crear tabla de permisos simple que SÍ funcione
CREATE TABLE IF NOT EXISTS `caja_permisos_basico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `puede_todo` tinyint(1) DEFAULT 1,
  `activo` tinyint(1) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_usuario` (`usuario_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 4. Dar permisos al usuario administrador
INSERT IGNORE INTO caja_permisos_basico (usuario_id, puede_todo, activo) 
VALUES (1, 1, 1);

-- 5. Asegurar que el módulo Dashboard esté en el menú
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (65, 'Dashboard de Caja', 39, 'dashboard_caja.php', 'fas fa-tachometer-alt', 2);

-- 6. Dar permisos del módulo al perfil administrador (ID 1)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio) 
VALUES (1, 65, 0);

-- 7. Verificar que los usuarios puedan acceder al módulo configuración
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (66, 'Configurar Sucursales', 39, 'configuracion_sucursales.php', 'fas fa-cogs', 3);

INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio) 
VALUES (1, 66, 0);

-- =====================================================
-- VERIFICACIÓN FINAL
-- =====================================================

SELECT '=== VERIFICACIÓN FINAL ===' as RESULTADO;

SELECT 'Sucursales configuradas:' as CHECK1;
SELECT * FROM cajas_sucursales;

SELECT 'Permisos básicos:' as CHECK2;
SELECT * FROM caja_permisos_basico;

SELECT 'Módulos agregados al menú:' as CHECK3;
SELECT * FROM modulos WHERE vista IN ('dashboard_caja.php', 'configuracion_sucursales.php');

SELECT 'Permisos de módulos asignados:' as CHECK4;
SELECT pm.*, m.modulo 
FROM perfil_modulo pm 
JOIN modulos m ON pm.id_modulo = m.id 
WHERE m.vista IN ('dashboard_caja.php', 'configuracion_sucursales.php');

SELECT '✅ BOTONES ACTIVADOS CON ESTRUCTURA REAL' as ESTADO;
SELECT '🔄 Refresca SIPREST (F5) y prueba los botones' as INSTRUCCION; 