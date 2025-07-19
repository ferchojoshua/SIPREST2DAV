-- =====================================================
-- CORRECCIÓN DE MENÚ DUPLICADO - DEVELOPER SENIOR
-- =====================================================

-- 1. Mostrar módulos actuales relacionados con caja
SELECT 'MÓDULOS ACTUALES DE CAJA:' as INFO;
SELECT id, modulo, padre_id, vista, orden 
FROM modulos 
WHERE padre_id = 39 OR modulo LIKE '%Dashboard%' OR modulo LIKE '%Caja%'
ORDER BY orden;

-- 2. Eliminar duplicados de Dashboard de Caja
-- Mantener solo el primero y eliminar duplicados
DELETE m1 FROM modulos m1
INNER JOIN modulos m2 
WHERE m1.id > m2.id 
AND m1.modulo = m2.modulo 
AND m1.vista = m2.vista
AND m1.modulo = 'Dashboard de Caja';

-- 3. Limpiar permisos huérfanos de módulos eliminados
DELETE pm FROM perfil_modulo pm
LEFT JOIN modulos m ON pm.id_modulo = m.id
WHERE m.id IS NULL;

-- 4. Verificar estructura final del menú CAJA
SELECT 'ESTRUCTURA FINAL DEL MENÚ CAJA:' as RESULTADO;
SELECT id, modulo, padre_id, vista, icon_menu, orden 
FROM modulos 
WHERE padre_id = 39 OR id = 39
ORDER BY orden;

-- 5. Asegurar que los módulos estén correctamente ordenados
UPDATE modulos SET orden = 1 WHERE id = 40 AND modulo = 'Aperturar Caja';
UPDATE modulos SET orden = 2 WHERE modulo = 'Dashboard de Caja' AND vista = 'dashboard_caja.php';
UPDATE modulos SET orden = 3 WHERE id = 41 AND modulo = 'Ingresos / Egre';
UPDATE modulos SET orden = 4 WHERE modulo = 'Configurar Sucursales' AND vista = 'configuracion_sucursales.php';

-- 6. Verificar permisos finales
SELECT 'PERMISOS DE MÓDULOS DE CAJA:' as INFO;
SELECT pm.id_perfil, pm.id_modulo, m.modulo, m.vista
FROM perfil_modulo pm
JOIN modulos m ON pm.id_modulo = m.id
WHERE m.padre_id = 39
ORDER BY m.orden;

SELECT '✅ MENÚ CORREGIDO - SIN DUPLICADOS' as ESTADO; 