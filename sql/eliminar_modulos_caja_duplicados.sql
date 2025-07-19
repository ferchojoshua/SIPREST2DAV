-- =====================================================
-- ELIMINAR MÓDULOS DUPLICADOS EN LA SECCIÓN DE CAJA
-- =====================================================

-- Desactivar modo seguro para permitir DELETE sin WHERE en KEY
SET SQL_SAFE_UPDATES = 0;

-- Paso 1: Identificar los IDs de los módulos duplicados a eliminar
-- (Mantener el ID más bajo para cada combinación de modulo, vista y padre_id)
DROP TEMPORARY TABLE IF EXISTS temp_modulos_a_eliminar;
CREATE TEMPORARY TABLE temp_modulos_a_eliminar AS
SELECT m.id
FROM modulos m
WHERE m.padre_id = 39 -- Módulos de Caja
  AND EXISTS (
    SELECT 1
    FROM modulos m2
    WHERE m2.modulo = m.modulo
      AND m2.vista = m.vista
      AND m2.padre_id = m.padre_id
      AND m2.id < m.id -- Seleccionar los IDs mayores (los duplicados)
  );

SELECT 'IDs de módulos duplicados a eliminar:' AS mensaje;
SELECT id FROM temp_modulos_a_eliminar;

-- Paso 2: Eliminar las referencias de los módulos duplicados en perfil_modulo
DELETE pm FROM perfil_modulo pm
INNER JOIN temp_modulos_a_eliminar t ON pm.id_modulo = t.id;

-- Paso 3: Eliminar los módulos duplicados de la tabla modulos
DELETE m FROM modulos m
INNER JOIN temp_modulos_a_eliminar t ON m.id = t.id;

-- Paso 4: Reorganizar el orden de los módulos de caja (opcional, pero buena práctica)
SET @orden_caja = 0;
UPDATE modulos
SET orden = (@orden_caja := @orden_caja + 1)
WHERE padre_id = 39
ORDER BY orden;

-- Reactivar modo seguro
SET SQL_SAFE_UPDATES = 1;

-- Verificar el resultado final
SELECT 'Módulos de caja después de la limpieza:' AS mensaje;
SELECT id, modulo, vista, padre_id, orden FROM modulos WHERE padre_id = 39 ORDER BY orden;

SELECT 'Proceso de limpieza de duplicados completado exitosamente' AS mensaje; 