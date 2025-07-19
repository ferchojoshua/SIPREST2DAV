-- =====================================================
-- SCRIPT PARA AGREGAR MÓDULO DE REPORTES FINANCIEROS
-- =====================================================
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- 1. Insertar el nuevo módulo de Reportes Financieros
INSERT INTO `modulos` (`modulo`, `padre_id`, `vista`, `icon_menu`, `orden`) 
VALUES ('Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 19)
ON DUPLICATE KEY UPDATE 
`modulo` = VALUES(`modulo`),
`padre_id` = VALUES(`padre_id`),
`vista` = VALUES(`vista`),
`icon_menu` = VALUES(`icon_menu`),
`orden` = VALUES(`orden`);

-- 2. Obtener el ID del módulo recién insertado
SET @modulo_id = (SELECT id FROM modulos WHERE vista = 'reportes_financieros.php' LIMIT 1);

-- 3. Insertar el módulo en el perfil de Administrador (id_perfil = 1)
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (1, @modulo_id, 0, 1)
ON DUPLICATE KEY UPDATE 
`vista_inicio` = VALUES(`vista_inicio`), 
`estado` = VALUES(`estado`);

-- 4. Insertar el módulo en el perfil de Supervisor/Gerente (id_perfil = 2) si existe
INSERT INTO `perfil_modulo` (`id_perfil`, `id_modulo`, `vista_inicio`, `estado`) 
VALUES (2, @modulo_id, 0, 1)
ON DUPLICATE KEY UPDATE 
`vista_inicio` = VALUES(`vista_inicio`), 
`estado` = VALUES(`estado`);

-- 5. Verificar que el módulo se agregó correctamente
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.icon_menu,
    m.orden,
    (SELECT modulo FROM modulos WHERE id = m.padre_id) as modulo_padre
FROM modulos m 
WHERE m.vista = 'reportes_financieros.php';

-- 6. Verificar permisos asignados
SELECT 
    pm.id_perfil,
    p.descripcion as perfil_descripcion,
    m.modulo,
    pm.estado
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
INNER JOIN modulos m ON pm.id_modulo = m.id
WHERE m.vista = 'reportes_financieros.php';

-- Mensaje de confirmación
SELECT 'Módulo de Reportes Financieros agregado exitosamente' as mensaje; 