-- =====================================================================
-- SCRIPT COMPATIBLE CON MYSQL SAFE MODE
-- Elimina duplicado de Reportes Financieros
-- =====================================================================

-- PASO 1: Deshabilitar temporalmente safe mode
SET SQL_SAFE_UPDATES = 0;

-- PASO 2: Ver duplicados actuales
SELECT 'DUPLICADOS ACTUALES:' as titulo;
SELECT id, modulo, vista, padre_id, orden 
FROM modulos 
WHERE modulo = 'Reportes Financieros';

-- PASO 3: Obtener IDs específicos
SET @id_mantener = (SELECT MIN(id) FROM modulos WHERE modulo = 'Reportes Financieros');
SET @id_eliminar = (SELECT MAX(id) FROM modulos WHERE modulo = 'Reportes Financieros');

-- Mostrar qué se va a hacer
SELECT 
    CONCAT('✅ Se mantiene ID: ', @id_mantener) as accion_1,
    CONCAT('🗑️ Se elimina ID: ', @id_eliminar) as accion_2;

-- PASO 4: Eliminar permisos del módulo duplicado (usando ID específico)
DELETE FROM perfil_modulo 
WHERE id_modulo = @id_eliminar;

-- PASO 5: Eliminar módulo duplicado (usando ID específico)
DELETE FROM modulos 
WHERE id = @id_eliminar;

-- PASO 6: Verificar resultado
SELECT 'RESULTADO FINAL:' as titulo;
SELECT id, modulo, vista, padre_id, orden 
FROM modulos 
WHERE modulo = 'Reportes Financieros';

-- PASO 7: Asegurar permisos del módulo que quedó
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (1, @id_mantener, 0, 1);

-- PASO 8: Verificar permisos finales
SELECT 'PERMISOS FINALES:' as titulo;
SELECT 
    pm.id_perfil,
    p.descripcion as perfil,
    m.modulo,
    pm.estado
FROM perfil_modulo pm
JOIN perfiles p ON pm.id_perfil = p.id_perfil
JOIN modulos m ON pm.id_modulo = m.id
WHERE m.modulo = 'Reportes Financieros';

-- PASO 9: Reactivar safe mode
SET SQL_SAFE_UPDATES = 1;

-- Mensaje final
SELECT '✅ DUPLICADO ELIMINADO CON SAFE MODE' as resultado; 