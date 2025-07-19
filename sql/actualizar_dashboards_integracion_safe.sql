-- =====================================================
-- SCRIPT DE INTEGRACIÓN DE DASHBOARDS - SAFE MODE
-- Compatible con MySQL Safe Update Mode
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

-- 5. Verificar permisos para ambos dashboards
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, fecha_asignacion)
SELECT 1, id, NOW() 
FROM modulos 
WHERE modulo IN ('Dashboards', 'Dashboard Ejecutivo', 'Dashboard Cobradores');

-- Para perfil 2 (si existe)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, fecha_asignacion)
SELECT 2, id, NOW() 
FROM modulos 
WHERE modulo IN ('Dashboards', 'Dashboard Ejecutivo', 'Dashboard Cobradores')
AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

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

-- Mensaje de confirmación
SELECT 
    'Integración de dashboards completada exitosamente (Safe Mode)' as mensaje,
    COUNT(*) as total_dashboards
FROM modulos 
WHERE modulo LIKE '%Dashboard%' OR vista LIKE '%dashboard%'; 