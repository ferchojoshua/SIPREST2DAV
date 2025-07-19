-- =====================================================
-- SCRIPT DE INTEGRACIÓN DE DASHBOARDS - VERSIÓN FINAL CORREGIDA
-- Compatible con estructura real de perfil_modulo
-- =====================================================

-- Deshabilitar safe mode temporalmente para este script
SET SQL_SAFE_UPDATES = 0;

-- 1. Actualizar el módulo principal usando ID específico
UPDATE modulos 
SET modulo = 'Dashboard Ejecutivo', 
    icon_menu = 'fas fa-tachometer-alt'
WHERE id = (SELECT * FROM (SELECT id FROM modulos WHERE modulo = 'Tablero pincipal' LIMIT 1) AS temp);

-- También actualizar por vista si existe
UPDATE modulos 
SET modulo = 'Dashboard Ejecutivo', 
    icon_menu = 'fas fa-tachometer-alt'
WHERE id = (SELECT * FROM (SELECT id FROM modulos WHERE vista = 'dashboard.php' LIMIT 1) AS temp2);

-- 2. Verificar que el Dashboard de Cobradores esté en el menú
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboard Cobradores', 0, 'dashboard_cobradores.php', 'fas fa-chart-pie', 1);

-- 3. Crear submódulo de dashboards si no existe
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboards', 0, NULL, 'fas fa-chart-pie', 0);

-- Obtener el ID del módulo padre "Dashboards"
SET @dashboard_padre_id = (SELECT id FROM modulos WHERE modulo = 'Dashboards' AND padre_id = 0 LIMIT 1);

-- 4. Mover dashboards como submódulos usando IDs específicos
UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 1
WHERE id = (SELECT * FROM (SELECT id FROM modulos WHERE modulo = 'Dashboard Ejecutivo' LIMIT 1) AS temp3);

UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 2  
WHERE id = (SELECT * FROM (SELECT id FROM modulos WHERE modulo = 'Dashboard Cobradores' LIMIT 1) AS temp4);

-- 5. Verificar permisos para dashboards usando estructura real de tabla
-- Estructura real: (idperfil_modulo, id_perfil, id_modulo, vista_inicio, estado)

-- Obtener IDs de los módulos de dashboards
SET @dashboard_ejecutivo_id = (SELECT id FROM modulos WHERE modulo = 'Dashboard Ejecutivo' LIMIT 1);
SET @dashboard_cobradores_id = (SELECT id FROM modulos WHERE modulo = 'Dashboard Cobradores' LIMIT 1);
SET @dashboards_padre_id = (SELECT id FROM modulos WHERE modulo = 'Dashboards' LIMIT 1);

-- Asignar permisos al perfil Administrador (id_perfil = 1)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES 
(1, @dashboards_padre_id, 0, 1),
(1, @dashboard_ejecutivo_id, 0, 1),
(1, @dashboard_cobradores_id, 0, 1);

-- Asignar permisos al perfil Prestamista (id_perfil = 2) si existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, @dashboards_padre_id, 0, 1 
WHERE EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, @dashboard_ejecutivo_id, 0, 1 
WHERE EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, @dashboard_cobradores_id, 0, 1 
WHERE EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- Reactivar safe mode
SET SQL_SAFE_UPDATES = 1;

-- 6. Verificación final
SELECT 
    m.id,
    m.modulo,
    m.padre_id,
    m.vista,
    m.icon_menu,
    m.orden,
    CASE 
        WHEN m.padre_id = 0 THEN 'Módulo Principal'
        ELSE CONCAT('Submódulo de: ', mp.modulo)
    END as tipo
FROM modulos m
LEFT JOIN modulos mp ON m.padre_id = mp.id
WHERE m.modulo LIKE '%Dashboard%' OR m.modulo = 'Dashboards'
ORDER BY m.padre_id, m.orden;

-- Verificar permisos asignados
SELECT 
    pm.id_perfil,
    p.descripcion as perfil,
    m.modulo,
    pm.vista_inicio,
    pm.estado
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.modulo LIKE '%Dashboard%' OR m.modulo = 'Dashboards'
ORDER BY pm.id_perfil, m.modulo;

-- Mensaje de confirmación
SELECT 
    'Integración de dashboards completada exitosamente' as mensaje,
    COUNT(*) as total_dashboards_configurados
FROM modulos 
WHERE modulo LIKE '%Dashboard%' OR vista LIKE '%dashboard%'; 