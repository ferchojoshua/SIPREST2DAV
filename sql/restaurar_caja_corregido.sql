-- =====================================================
-- RESTAURAR MÓDULOS DE CAJA - VERSIÓN CORREGIDA
-- =====================================================

-- Insertar módulos de caja esenciales
INSERT IGNORE INTO modulos (modulo, vista, icon_menu, padre_id, orden) VALUES
('Aperturar Caja', 'caja.php', 'far fa-circle', 39, 1),
('Dashboard de Caja', 'dashboard_caja.php', 'fas fa-tachometer-alt', 39, 2),
('Ingresos / Egre', 'ingresos.php', 'far fa-circle', 39, 3);

-- Asignar permisos al perfil administrador usando estructura correcta
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
SELECT 1, id, 1, 1 FROM modulos WHERE padre_id = 39;

-- También asignar al perfil prestamista si existe (id_perfil = 2)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
SELECT 2, id, 0, 1 FROM modulos WHERE padre_id = 39 AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- Verificar resultado
SELECT 'Módulos de caja restaurados:' as resultado;
SELECT id, modulo, vista, padre_id, orden FROM modulos WHERE padre_id = 39 ORDER BY orden;

-- Verificar permisos asignados
SELECT 'Permisos asignados:' as permisos;
SELECT 
    pm.id_perfil,
    p.descripcion as perfil,
    m.modulo,
    pm.vista_inicio,
    pm.estado
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.padre_id = 39
ORDER BY pm.id_perfil, m.orden; 