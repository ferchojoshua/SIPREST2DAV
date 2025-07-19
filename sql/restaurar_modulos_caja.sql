-- =====================================================
-- RESTAURAR MÓDULOS DE CAJA ELIMINADOS POR ERROR
-- =====================================================

-- Verificar qué módulos de caja existen actualmente
SELECT 'Módulos de caja actuales:' as info;
SELECT id, modulo, vista, padre_id, orden FROM modulos WHERE padre_id = 39 ORDER BY orden;

-- Insertar módulos de caja que deberían existir (basado en la estructura del sistema)
INSERT IGNORE INTO modulos (modulo, vista, icon_menu, padre_id, orden) VALUES
('Aperturar Caja', 'caja.php', 'far fa-circle', 39, 1),
('Dashboard de Caja', 'dashboard_caja.php', 'fas fa-tachometer-alt', 39, 2),
('Ingresos / Egre', 'ingresos.php', 'far fa-circle', 39, 3);

-- Verificar módulos después de la inserción
SELECT 'Módulos de caja restaurados:' as resultado;
SELECT id, modulo, vista, padre_id, orden FROM modulos WHERE padre_id = 39 ORDER BY orden;

-- Asignar permisos básicos al perfil administrador (perfil_id = 1)
-- Usando la estructura correcta de perfil_modulo
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
SELECT 1, id, 1, 1 FROM modulos WHERE padre_id = 39;

SELECT 'Módulos de caja restaurados exitosamente' as mensaje; 