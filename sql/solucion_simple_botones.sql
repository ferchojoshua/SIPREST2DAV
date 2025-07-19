-- =====================================================
-- SOLUCIÓN SIMPLE PARA ACTIVAR BOTONES INMEDIATAMENTE
-- =====================================================

-- 1. Primero vemos qué estructura tiene la tabla existente
SELECT 'VERIFICANDO ESTRUCTURA ACTUAL:' as INFO;
DESCRIBE cajas_sucursales;

-- 2. Insertamos datos usando solo las columnas que sabemos que existen
-- Verificamos si ya hay un registro básico
SELECT 'DATOS ACTUALES EN cajas_sucursales:' as INFO;
SELECT * FROM cajas_sucursales LIMIT 5;

-- 3. Asegurar que tenemos al menos un registro en cajas_sucursales
-- (usando solo la columna sucursal_id que seguramente existe)
INSERT IGNORE INTO cajas_sucursales (sucursal_id) VALUES (1);

-- 4. Crear tabla de permisos básica (más simple)
CREATE TABLE IF NOT EXISTS `caja_permisos_simple` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `permisos` text DEFAULT 'todos',
  `activo` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 5. Insertar permiso básico para usuario administrador
INSERT IGNORE INTO caja_permisos_simple (usuario_id, permisos, activo) 
VALUES (1, 'abrir,cerrar,reportes,movimientos,supervisar', 1);

-- 6. Asegurar módulo Dashboard en menú (si no existe)
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (65, 'Dashboard de Caja', 39, 'dashboard_caja.php', 'fas fa-tachometer-alt', 2);

-- 7. Dar permisos del módulo al perfil administrador
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio) 
VALUES (1, 65, 0);

-- 8. Verificar instalación
SELECT '=== VERIFICACIÓN FINAL ===' as RESULTADO;

SELECT 'cajas_sucursales tiene registros:' as CHECK1;
SELECT COUNT(*) as total_sucursales FROM cajas_sucursales;

SELECT 'caja_permisos_simple configurado:' as CHECK2;
SELECT * FROM caja_permisos_simple;

SELECT 'Módulo Dashboard en menú:' as CHECK3;
SELECT * FROM modulos WHERE vista = 'dashboard_caja.php';

SELECT 'Permisos del módulo asignados:' as CHECK4;
SELECT pm.*, m.modulo 
FROM perfil_modulo pm 
JOIN modulos m ON pm.id_modulo = m.id 
WHERE m.vista = 'dashboard_caja.php';

SELECT '✅ INSTALACIÓN BÁSICA COMPLETADA' as ESTADO;
SELECT 'Los botones ya deberían funcionar ahora' as INSTRUCCION; 