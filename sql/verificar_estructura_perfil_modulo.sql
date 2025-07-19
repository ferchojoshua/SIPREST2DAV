-- =====================================================
-- VERIFICAR ESTRUCTURA DE TABLA perfil_modulo
-- =====================================================

-- Mostrar estructura de la tabla
DESCRIBE perfil_modulo;

-- Mostrar ejemplo de datos existentes
SELECT * FROM perfil_modulo LIMIT 5;

-- Verificar qué módulos de caja existen actualmente
SELECT 'Módulos de caja actuales:' as info;
SELECT id, modulo, vista, padre_id, orden FROM modulos WHERE padre_id = 39 ORDER BY orden; 