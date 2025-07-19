-- =====================================================
-- LIMPIAR MENÚ - VERSIÓN SUPER SEGURA
-- =====================================================

-- Desactivar modo seguro
SET SQL_SAFE_UPDATES = 0;

-- Verificar qué módulos existen antes de eliminar
SELECT 'Módulos encontrados para eliminar:' as info;
SELECT id, modulo, vista, padre_id 
FROM modulos 
WHERE modulo = 'Dashboard Mejorado' 
   OR vista = 'dashboard_mejorado.php'
   OR (modulo = 'Configurar Sucursales' AND padre_id = 39);

-- Eliminar permisos asociados para Dashboard Mejorado (si existe)
DELETE pm FROM perfil_modulo pm 
INNER JOIN modulos m ON pm.id_modulo = m.id 
WHERE m.modulo = 'Dashboard Mejorado' OR m.vista = 'dashboard_mejorado.php';

-- Eliminar permisos asociados para Configurar Sucursales (si existe)
DELETE pm FROM perfil_modulo pm 
INNER JOIN modulos m ON pm.id_modulo = m.id 
WHERE m.modulo = 'Configurar Sucursales' AND m.padre_id = 39;

-- Eliminar módulo Dashboard Mejorado (si existe)
DELETE FROM modulos 
WHERE modulo = 'Dashboard Mejorado' OR vista = 'dashboard_mejorado.php';

-- Eliminar módulo Configurar Sucursales (si existe)
DELETE FROM modulos 
WHERE modulo = 'Configurar Sucursales' AND padre_id = 39;

-- Reactivar modo seguro
SET SQL_SAFE_UPDATES = 1;

-- Verificar resultado final
SELECT 'Módulos de caja restantes:' as resultado;
SELECT id, modulo, vista, padre_id, orden 
FROM modulos 
WHERE padre_id = 39 
ORDER BY orden;

SELECT 'Proceso completado exitosamente' as mensaje; 