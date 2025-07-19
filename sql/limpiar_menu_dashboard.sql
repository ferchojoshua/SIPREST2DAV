-- =====================================================
-- LIMPIAR MENÚ - ELIMINAR ENTRADAS NO NECESARIAS
-- =====================================================

-- Desactivar modo seguro temporalmente
SET SQL_SAFE_UPDATES = 0;

-- Primero obtener los IDs de los módulos a eliminar
SET @dashboard_id = (SELECT id FROM modulos WHERE modulo = 'Dashboard Mejorado' OR vista = 'dashboard_mejorado.php' LIMIT 1);
SET @sucursales_id = (SELECT id FROM modulos WHERE modulo = 'Configurar Sucursales' AND padre_id = 39 LIMIT 1);

-- Eliminar registros dependientes en perfil_modulo para Dashboard Mejorado
DELETE FROM perfil_modulo WHERE id_modulo = @dashboard_id;

-- Eliminar registros dependientes en perfil_modulo para Configurar Sucursales
DELETE FROM perfil_modulo WHERE id_modulo = @sucursales_id;

-- Ahora eliminar los módulos principales
DELETE FROM modulos WHERE id = @dashboard_id;
DELETE FROM modulos WHERE id = @sucursales_id;

-- Reactivar modo seguro
SET SQL_SAFE_UPDATES = 1;

-- Verificar que se eliminaron correctamente
SELECT 'Entradas eliminadas del menú' as resultado;
SELECT id, modulo, vista, padre_id FROM modulos WHERE padre_id = 39 ORDER BY orden; 