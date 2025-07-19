-- =====================================================
-- SCRIPT CORREGIDO PARA AGREGAR MÓDULO DE REPORTES FINANCIEROS
-- =====================================================
-- Ejecutar en phpMyAdmin paso a paso

-- PASO 1: Insertar el nuevo módulo de Reportes Financieros
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 19);

-- PASO 2: Verificar que el módulo se insertó correctamente
SELECT id, modulo, vista, icon_menu, orden 
FROM modulos 
WHERE vista = 'reportes_financieros.php';

-- PASO 3: Obtener el ID del módulo recién insertado
-- (Copiar el ID que aparece en el resultado anterior)
-- Por ejemplo, si el ID es 52, usar ese número en los siguientes comandos

-- PASO 4: Insertar permisos para el perfil Administrador (id_perfil = 1)
-- REEMPLAZA 52 con el ID real del módulo
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, 52, 0, 1);

-- PASO 5: Insertar permisos para el perfil Prestamista (id_perfil = 2)
-- REEMPLAZA 52 con el ID real del módulo
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (2, 52, 0, 1);

-- PASO 6: Verificar que los permisos se asignaron correctamente
SELECT 
    pm.id_perfil,
    p.descripcion as perfil_descripcion,
    m.modulo,
    pm.estado
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.vista = 'reportes_financieros.php';

-- PASO 7: Verificar la estructura completa del menú de reportes
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.icon_menu,
    m.orden,
    CASE 
        WHEN m.padre_id = 0 THEN 'MENÚ PRINCIPAL'
        ELSE (SELECT modulo FROM modulos WHERE id = m.padre_id)
    END as modulo_padre
FROM modulos m 
WHERE m.padre_id = 10 OR m.id = 10
ORDER BY m.orden;

-- Mensaje de éxito
SELECT '✅ Módulo de Reportes Financieros agregado exitosamente al sistema' as mensaje; 