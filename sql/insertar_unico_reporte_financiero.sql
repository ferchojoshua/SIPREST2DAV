-- =====================================================================
-- SCRIPT PARA INSERTAR UNA SOLA OPCIÓN DE REPORTES FINANCIEROS
-- Elimina todos los existentes e inserta uno nuevo y limpio
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;

-- =====================================================================
-- PASO 1: ELIMINAR TODOS LOS REPORTES FINANCIEROS EXISTENTES
-- =====================================================================

-- Ver qué se va a eliminar
SELECT 'MÓDULOS A ELIMINAR:' as titulo;
SELECT id, modulo, vista, padre_id 
FROM modulos 
WHERE modulo LIKE '%Reportes Financieros%' 
   OR modulo LIKE '%reportes financieros%';

-- Eliminar permisos de todos los reportes financieros existentes
DELETE FROM perfil_modulo 
WHERE id_modulo IN (
    SELECT id FROM modulos 
    WHERE modulo LIKE '%Reportes Financieros%' 
       OR modulo LIKE '%reportes financieros%'
);

-- Eliminar todos los módulos de reportes financieros existentes
DELETE FROM modulos 
WHERE modulo LIKE '%Reportes Financieros%' 
   OR modulo LIKE '%reportes financieros%';

-- =====================================================================
-- PASO 2: INSERTAR UNA SOLA OPCIÓN NUEVA Y LIMPIA
-- =====================================================================

-- Obtener el ID del módulo padre "Reportes"
SET @reportes_padre_id = (SELECT id FROM modulos WHERE modulo = 'Reportes' AND padre_id = 0);

-- Insertar el nuevo módulo único de Reportes Financieros
INSERT INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reportes Financieros', @reportes_padre_id, 'reportes_financieros.php', 'far fa-circle', 61);

-- Obtener el ID del módulo recién insertado
SET @nuevo_modulo_id = LAST_INSERT_ID();

-- =====================================================================
-- PASO 3: ASIGNAR PERMISOS AL MÓDULO ÚNICO
-- =====================================================================

-- Asignar permiso al perfil Administrador (id_perfil = 1)
INSERT INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
VALUES (1, @nuevo_modulo_id, 0, 1);

-- Asignar permiso al perfil Prestamista/Supervisor (id_perfil = 2) si existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) 
VALUES (2, @nuevo_modulo_id, 0, 1);

-- =====================================================================
-- PASO 4: VERIFICACIÓN FINAL
-- =====================================================================

-- Verificar que solo existe uno
SELECT 'MÓDULO ÚNICO CREADO:' as titulo;
SELECT 
    id,
    modulo,
    padre_id,
    vista,
    icon_menu,
    orden,
    '✅ ÚNICO Y LIMPIO' as estado
FROM modulos 
WHERE modulo = 'Reportes Financieros';

-- Verificar permisos asignados
SELECT 'PERMISOS ASIGNADOS:' as titulo;
SELECT 
    pm.id_perfil,
    p.descripcion as perfil_nombre,
    m.modulo,
    pm.vista_inicio,
    pm.estado,
    '✅ PERMISO ACTIVO' as estado_permiso
FROM perfil_modulo pm
JOIN perfiles p ON pm.id_perfil = p.id_perfil
JOIN modulos m ON pm.id_modulo = m.id
WHERE m.modulo = 'Reportes Financieros';

-- Verificar menú completo de reportes
SELECT 'MENÚ DE REPORTES COMPLETO:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    orden,
    CASE 
        WHEN modulo = 'Reportes Financieros' THEN '🆕 NUEVO ÚNICO'
        ELSE '📊 EXISTENTE'
    END as estado
FROM modulos 
WHERE padre_id = @reportes_padre_id
ORDER BY orden;

-- Contar total de reportes financieros (debe ser 1)
SELECT 
    'VERIFICACIÓN FINAL:' as titulo,
    COUNT(*) as total_reportes_financieros,
    CASE 
        WHEN COUNT(*) = 1 THEN '✅ PERFECTO - SOLO UNO'
        WHEN COUNT(*) = 0 THEN '❌ ERROR - NINGUNO'
        ELSE '⚠️ ERROR - TODAVÍA HAY DUPLICADOS'
    END as resultado
FROM modulos 
WHERE modulo = 'Reportes Financieros';

SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 
    '✅ REPORTES FINANCIEROS ÚNICO CREADO' as resultado,
    'Eliminados todos los duplicados' as accion_1,
    'Insertado uno nuevo y limpio' as accion_2,
    'Con permisos correctos asignados' as accion_3,
    'Reiniciar sesión para ver cambios' as instruccion,
    NOW() as fecha_ejecucion; 