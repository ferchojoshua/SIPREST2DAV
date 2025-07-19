-- =====================================================
-- LIMPIEZA Y RE-INSERCIÓN COMPLETA DE MÓDULOS DE CAJA
-- =====================================================

-- Desactivar modo seguro para permitir eliminaciones y actualizaciones
SET SQL_SAFE_UPDATES = 0;

-- 1. Eliminar entradas de `perfil_modulo` que apuntan a módulos de caja
--    (para evitar problemas de clave foránea al eliminar de `modulos`)
DELETE pm FROM perfil_modulo pm
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.padre_id = 39 -- Módulos de Caja
   OR m.modulo IN ('Aperturar Caja', 'Dashboard de Caja', 'Ingresos / Egre');

-- 2. Eliminar todos los módulos de caja duplicados o incorrectos
DELETE FROM modulos
WHERE padre_id = 39 -- Módulos de Caja
   OR modulo IN ('Aperturar Caja', 'Dashboard de Caja', 'Ingresos / Egre');

-- 3. Re-insertar los módulos de caja esenciales una sola vez
INSERT INTO modulos (modulo, vista, icon_menu, padre_id, orden) VALUES
('Aperturar Caja', 'caja.php', 'far fa-circle', 39, 1),
('Dashboard de Caja', 'dashboard_caja.php', 'fas fa-tachometer-alt', 39, 2),
('Ingresos / Egre', 'ingresos.php', 'far fa-circle', 39, 3);

-- 4. Asignar permisos básicos al perfil administrador (id_perfil = 1)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
SELECT 1, id, 1, 1 FROM modulos WHERE padre_id = 39;

-- 5. Asignar permisos al perfil Prestamista (id_perfil = 2) si existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
SELECT 2, id, 0, 1 FROM modulos WHERE padre_id = 39 AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- 6. Reactivar modo seguro
SET SQL_SAFE_UPDATES = 1;

-- 7. Verificar el resultado final
SELECT 'Módulos de caja después de la limpieza y re-inserción:' AS mensaje;
SELECT id, modulo, vista, padre_id, orden FROM modulos WHERE padre_id = 39 ORDER BY orden;

SELECT 'Proceso de limpieza y re-inserción de módulos de caja completado exitosamente' AS mensaje; 