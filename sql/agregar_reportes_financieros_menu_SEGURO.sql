-- =====================================================
-- SCRIPT SEGURO PARA AGREGAR REPORTES FINANCIEROS AL MENÚ
-- =====================================================
-- Este script agrega el módulo sin afectar la funcionalidad existente
-- Ejecutar paso a paso en phpMyAdmin

-- PASO 1: Verificar el último ID de orden en reportes
SELECT id, modulo, vista, orden 
FROM modulos 
WHERE padre_id = 10 
ORDER BY orden DESC;

-- PASO 2: Insertar el nuevo módulo de Reportes Financieros
-- Usamos orden 25 para que aparezca al final sin conflictos
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Reportes Financieros Integrados', 10, 'reportes_financieros.php', 'fas fa-chart-bar', 25);

-- PASO 3: Verificar que se insertó correctamente
SELECT id, modulo, vista, orden 
FROM modulos 
WHERE vista = 'reportes_financieros.php';

-- PASO 4: Obtener el ID del módulo recién insertado para los permisos
-- (Tomar nota del ID que aparece en el resultado anterior)

-- PASO 5: Agregar permisos para Administrador (perfil_id = 1)
-- Reemplazar XXX con el ID real del módulo obtenido en el paso anterior
-- INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
-- VALUES (1, XXX, 0, 1);

-- PASO 6: Agregar permisos para otros perfiles si es necesario
-- INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
-- VALUES (2, XXX, 0, 1);

-- PASO 7: Verificar la estructura completa del menú reportes
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.orden,
    CASE 
        WHEN pm.id_perfil IS NOT NULL THEN 'Con Permisos'
        ELSE 'Sin Permisos'
    END as estado_permisos
FROM modulos m 
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = 1
WHERE m.padre_id = 10 
ORDER BY m.orden;

-- =====================================================
-- SCRIPT ALTERNATIVO AUTOMÁTICO (Usar si quieres todo automático)
-- =====================================================

/*
-- Este script hace todo automáticamente
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Reportes Financieros Integrados', 10, 'reportes_financieros.php', 'fas fa-chart-bar', 25);

-- Obtener el ID automáticamente
SET @modulo_id = LAST_INSERT_ID();

-- Agregar permisos para administrador
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @modulo_id, 0, 1);

-- Agregar permisos para supervisor/gerente si existe
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (2, @modulo_id, 0, 1);

-- Verificar resultado
SELECT 
    m.id,
    m.modulo,
    m.vista,
    pm.id_perfil,
    p.descripcion as perfil_nombre
FROM modulos m 
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo
LEFT JOIN perfiles p ON pm.id_perfil = p.id_perfil
WHERE m.vista = 'reportes_financieros.php';
*/

-- =====================================================
-- NOTAS IMPORTANTES
-- =====================================================
/*
1. Este script NO modifica ningún reporte existente
2. Solo AGREGA el módulo de reportes financieros al menú
3. Los reportes actuales siguen funcionando igual
4. Los usuarios pueden usar ambos sistemas gradualmente
5. Orden 25 evita conflictos con órdenes existentes

BENEFICIOS:
- Acceso completo a 16 reportes integrados
- Filtros avanzados y exportaciones
- Diseño moderno y responsive
- NO afecta reportes actuales
*/ 