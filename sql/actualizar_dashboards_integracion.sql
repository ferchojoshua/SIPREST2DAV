-- =====================================================
-- SCRIPT DE INTEGRACIÓN DE DASHBOARDS
-- Actualiza la estructura para navegación inteligente
-- =====================================================

-- 1. Actualizar el módulo principal para reflejar el nuevo nombre
UPDATE modulos 
SET modulo = 'Dashboard Ejecutivo', 
    icon_menu = 'fas fa-tachometer-alt'
WHERE modulo = 'Tablero pincipal' OR vista = 'dashboard.php';

-- 2. Verificar que el Dashboard de Cobradores esté en el menú
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboard Cobradores', 0, 'dashboard_cobradores.php', 'fas fa-chart-pie', 1);

-- 3. Crear submódulo de dashboards si no existe
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboards', 0, NULL, 'fas fa-chart-pie', 0);

-- Obtener el ID del módulo padre "Dashboards"
SET @dashboard_padre_id = (SELECT id FROM modulos WHERE modulo = 'Dashboards' AND padre_id = 0 LIMIT 1);

-- 4. Mover dashboards como submódulos
UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 1
WHERE modulo = 'Dashboard Ejecutivo';

UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 2  
WHERE modulo = 'Dashboard Cobradores';

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
    'Integración de dashboards completada exitosamente' as mensaje,
    COUNT(*) as total_dashboards
FROM modulos 
WHERE modulo LIKE '%Dashboard%' OR vista LIKE '%dashboard%'; 