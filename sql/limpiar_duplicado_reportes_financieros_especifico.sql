-- =====================================================================
-- SCRIPT ESPECÍFICO PARA ELIMINAR DUPLICACIÓN DE REPORTES FINANCIEROS
-- Deja solo la opción que funciona correctamente
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
-- PASO 1: IDENTIFICAR MÓDULOS DUPLICADOS DE REPORTES FINANCIEROS
-- =====================================================================

SELECT 'ANTES - MÓDULOS DE REPORTES FINANCIEROS DUPLICADOS:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    padre_id,
    orden,
    CASE 
        WHEN vista = 'reportes_financieros.php' THEN '📊 CON ARCHIVO'
        WHEN vista IS NULL OR vista = '' THEN '❌ SIN ARCHIVO'
        ELSE '⚠️ OTRA VISTA'
    END as estado_archivo
FROM modulos 
WHERE vista = 'reportes_financieros.php' 
   OR modulo LIKE '%Reportes Financieros%'
   OR modulo LIKE '%reportes financieros%'
ORDER BY id;

-- =====================================================================
-- PASO 2: VERIFICAR CUAL TIENE ARCHIVO FUNCIONAL
-- =====================================================================

-- El módulo correcto debe tener vista = 'reportes_financieros.php'
SET @modulo_correcto_id = (
    SELECT MIN(id) 
    FROM modulos 
    WHERE vista = 'reportes_financieros.php' 
    AND padre_id = 10
);

-- Identificar módulos duplicados para eliminar
SET @modulos_duplicados = (
    SELECT GROUP_CONCAT(id) 
    FROM modulos 
    WHERE (vista = 'reportes_financieros.php' OR modulo LIKE '%Reportes Financieros%')
    AND id != @modulo_correcto_id
);

-- Mostrar qué se va a mantener y qué se va a eliminar
SELECT 
    CONCAT('✅ Se mantendrá el módulo ID: ', @modulo_correcto_id) as decision_1,
    CASE 
        WHEN @modulos_duplicados IS NOT NULL 
        THEN CONCAT('🗑️ Se eliminarán los módulos IDs: ', @modulos_duplicados)
        ELSE '✅ No hay duplicados para eliminar'
    END as decision_2;

-- =====================================================================
-- PASO 3: MIGRAR PERMISOS AL MÓDULO CORRECTO
-- =====================================================================

-- Asegurar que el módulo correcto tenga permisos para Administrador
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (1, @modulo_correcto_id, 0, 1);

-- Asegurar que el módulo correcto tenga permisos para Prestamista (si existe)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (2, @modulo_correcto_id, 0, 1);

-- =====================================================================
-- PASO 4: ELIMINAR PERMISOS DE MÓDULOS DUPLICADOS
-- =====================================================================

-- Eliminar permisos de todos los módulos duplicados
DELETE FROM perfil_modulo 
WHERE id_modulo IN (
    SELECT id FROM modulos 
    WHERE (vista = 'reportes_financieros.php' OR modulo LIKE '%Reportes Financieros%')
    AND id != @modulo_correcto_id
);

-- =====================================================================
-- PASO 5: ELIMINAR MÓDULOS DUPLICADOS
-- =====================================================================

-- Eliminar módulos duplicados (ahora sin permisos)
DELETE FROM modulos 
WHERE (vista = 'reportes_financieros.php' OR modulo LIKE '%Reportes Financieros%')
AND id != @modulo_correcto_id;

-- =====================================================================
-- PASO 6: ASEGURAR CONFIGURACIÓN CORRECTA DEL MÓDULO ÚNICO
-- =====================================================================

-- Actualizar el módulo que quedó para asegurar configuración correcta
UPDATE modulos 
SET 
    modulo = 'Reportes Financieros',
    padre_id = 10,
    vista = 'reportes_financieros.php',
    icon_menu = 'far fa-circle',
    orden = 61
WHERE id = @modulo_correcto_id;

-- =====================================================================
-- PASO 7: VERIFICACIÓN FINAL
-- =====================================================================

SELECT 'DESPUÉS - MÓDULO ÚNICO DE REPORTES FINANCIEROS:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    padre_id,
    orden,
    '✅ ÚNICO Y FUNCIONAL' as estado
FROM modulos 
WHERE vista = 'reportes_financieros.php';

-- Verificar permisos del módulo único
SELECT 'PERMISOS DEL MÓDULO ÚNICO:' as titulo;
SELECT 
    pm.id_perfil,
    p.descripcion as perfil_nombre,
    m.modulo,
    pm.estado,
    '✅ PERMISO ACTIVO' as estado_permiso
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.vista = 'reportes_financieros.php';

-- Verificar que no hay duplicados restantes
SELECT 'VERIFICACIÓN - DUPLICADOS RESTANTES:' as titulo;
SELECT 
    modulo,
    COUNT(*) as cantidad
FROM modulos 
WHERE modulo LIKE '%Reportes Financieros%'
GROUP BY modulo 
HAVING COUNT(*) > 1;

-- Mostrar todos los reportes únicos
SELECT 'MENÚ DE REPORTES FINAL:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    orden,
    CASE 
        WHEN vista IS NOT NULL AND vista != '' THEN '✅ CON ARCHIVO'
        ELSE '⚠️ SIN ARCHIVO'
    END as estado_archivo
FROM modulos 
WHERE padre_id = 10
ORDER BY orden;

-- Resumen final
SELECT 
    'LIMPIEZA ESPECÍFICA COMPLETADA ✅' as resultado,
    (SELECT COUNT(*) FROM modulos WHERE vista = 'reportes_financieros.php') as reportes_financieros_unicos,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = 10) as total_reportes,
    (SELECT COUNT(*) FROM perfil_modulo pm JOIN modulos m ON pm.id_modulo = m.id WHERE m.vista = 'reportes_financieros.php') as total_permisos
FROM dual;

SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 
    '🧹 DUPLICACIÓN DE REPORTES FINANCIEROS ELIMINADA' as resultado,
    'Solo queda la opción funcional con archivo PHP' as descripcion,
    'Reiniciar sesión para ver el cambio' as instruccion,
    NOW() as fecha_ejecucion; 