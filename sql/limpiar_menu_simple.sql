-- =====================================================
-- LIMPIAR MENÚ - VERSIÓN SIMPLE Y SEGURA
-- =====================================================

-- Primero, consultamos qué IDs hay que eliminar
SELECT 'IDs a eliminar:' as accion;
SELECT id, modulo, vista, padre_id 
FROM modulos 
WHERE modulo = 'Dashboard Mejorado' 
   OR vista = 'dashboard_mejorado.php'
   OR (modulo = 'Configurar Sucursales' AND padre_id = 39);

-- Eliminar por ID específico (reemplazar X por el ID real que aparezca en la consulta anterior)
-- DELETE FROM modulos WHERE id = X;

-- Consulta final para verificar módulos de caja
SELECT 'Módulos de caja restantes:' as resultado;
SELECT id, modulo, vista, padre_id, orden 
FROM modulos 
WHERE padre_id = 39 
ORDER BY orden; 