-- =====================================================================
-- SCRIPT DIRECTO - ELIMINAR DUPLICADO DE REPORTES FINANCIEROS
-- Solución rápida y simple
-- =====================================================================

-- PASO 1: Ver duplicados actuales
SELECT 'DUPLICADOS ACTUALES:' as titulo;
SELECT id, modulo, vista, padre_id, orden 
FROM modulos 
WHERE modulo = 'Reportes Financieros';

-- PASO 2: Eliminar duplicado manteniendo el de menor ID
-- Obtener el ID más pequeño (el que se mantiene)
SET @id_mantener = (SELECT MIN(id) FROM modulos WHERE modulo = 'Reportes Financieros');

-- Mostrar cuál se mantiene y cuál se elimina  
SELECT 
    CONCAT('✅ Se mantiene ID: ', @id_mantener) as accion_1,
    CONCAT('🗑️ Se eliminan IDs: ', GROUP_CONCAT(id)) as accion_2
FROM modulos 
WHERE modulo = 'Reportes Financieros' 
  AND id > @id_mantener;

-- PASO 3: Eliminar permisos del duplicado
DELETE FROM perfil_modulo 
WHERE id_modulo IN (
    SELECT id FROM modulos 
    WHERE modulo = 'Reportes Financieros' 
      AND id > @id_mantener
);

-- PASO 4: Eliminar módulo duplicado
DELETE FROM modulos 
WHERE modulo = 'Reportes Financieros' 
  AND id > @id_mantener;

-- PASO 5: Verificar que solo quede uno
SELECT 'RESULTADO FINAL:' as titulo;
SELECT id, modulo, vista, padre_id, orden 
FROM modulos 
WHERE modulo = 'Reportes Financieros';

-- PASO 6: Asegurar permisos del que quedó
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (1, @id_mantener, 0, 1);

-- Mensaje final
SELECT '✅ DUPLICADO ELIMINADO - Solo queda un Reportes Financieros' as resultado; 