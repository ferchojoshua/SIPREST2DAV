-- =====================================================
-- SCRIPT PARA LIMPIAR DUPLICADOS DE REPORTES FINANCIEROS
-- =====================================================
-- Ejecutar en phpMyAdmin para eliminar módulos duplicados

-- PASO 1: Verificar cuántos módulos duplicados existen
SELECT 
    id,
    modulo,
    vista,
    padre_id,
    orden
FROM modulos 
WHERE vista = 'reportes_financieros.php' 
   OR modulo LIKE '%Reportes Financieros%'
ORDER BY id;

-- PASO 2: Eliminar permisos de módulos duplicados (mantener solo el primer ID)
-- Primero identificamos los IDs duplicados
SET @primer_id = (SELECT MIN(id) FROM modulos WHERE vista = 'reportes_financieros.php');
SET @ids_duplicados = (SELECT GROUP_CONCAT(id) FROM modulos WHERE vista = 'reportes_financieros.php' AND id > @primer_id);

-- Mostrar qué se va a eliminar
SELECT 
    CONCAT('Se mantendrá el módulo con ID: ', @primer_id) as accion_1,
    CASE 
        WHEN @ids_duplicados IS NOT NULL 
        THEN CONCAT('Se eliminarán los módulos con IDs: ', @ids_duplicados)
        ELSE 'No hay duplicados para eliminar'
    END as accion_2;

-- PASO 3: Eliminar permisos de los módulos duplicados
DELETE FROM perfil_modulo 
WHERE id_modulo IN (
    SELECT id FROM modulos 
    WHERE vista = 'reportes_financieros.php' 
    AND id > (SELECT MIN(id) FROM modulos WHERE vista = 'reportes_financieros.php')
);

-- PASO 4: Eliminar los módulos duplicados (mantener solo el primero)
DELETE FROM modulos 
WHERE vista = 'reportes_financieros.php' 
AND id > (SELECT * FROM (
    SELECT MIN(id) FROM modulos WHERE vista = 'reportes_financieros.php'
) as temp);

-- PASO 5: Verificar que solo quede un módulo
SELECT 
    id,
    modulo,
    vista,
    padre_id,
    orden,
    'MÓDULO ÚNICO MANTENIDO' as estado
FROM modulos 
WHERE vista = 'reportes_financieros.php';

-- PASO 6: Verificar los permisos del módulo que quedó
SELECT 
    pm.id_perfil,
    p.descripcion as perfil_descripcion,
    m.modulo,
    pm.estado,
    'PERMISO ACTIVO' as estado_permiso
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.vista = 'reportes_financieros.php';

-- PASO 7: Si no hay permisos, agregarlos al módulo que quedó
-- Obtener el ID del módulo único
SET @modulo_unico_id = (SELECT id FROM modulos WHERE vista = 'reportes_financieros.php' LIMIT 1);

-- Agregar permiso para Administrador si no existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (1, @modulo_unico_id, 0, 1);

-- Agregar permiso para Prestamista si no existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (2, @modulo_unico_id, 0, 1);

-- PASO 8: Verificación final del menú de reportes
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.icon_menu,
    m.orden,
    CASE 
        WHEN m.padre_id = 0 THEN 'MENÚ PRINCIPAL'
        ELSE (SELECT modulo FROM modulos WHERE id = m.padre_id)
    END as modulo_padre,
    CASE 
        WHEN EXISTS (SELECT 1 FROM perfil_modulo WHERE id_modulo = m.id) 
        THEN '✅ CON PERMISOS' 
        ELSE '❌ SIN PERMISOS' 
    END as estado_permisos
FROM modulos m 
WHERE m.padre_id = 10 OR m.id = 10
ORDER BY m.orden;

-- Mensaje de confirmación
SELECT '🧹 Limpieza de duplicados completada. Solo queda un módulo de Reportes Financieros.' as mensaje; 