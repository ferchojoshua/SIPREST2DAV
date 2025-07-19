-- =====================================================
-- AGREGAR DASHBOARD DE CAJA AL MENÚ - SIPREST
-- =====================================================

-- Verificar si ya existe el módulo
SELECT 'Verificando módulo Dashboard de Caja...' as mensaje;

-- Eliminar si existe (para re-ejecutar el script)
DELETE FROM perfil_modulo WHERE id_modulo IN (
    SELECT id FROM modulos WHERE vista = 'dashboard_caja.php'
);

DELETE FROM modulos WHERE vista = 'dashboard_caja.php';

-- Insertar el nuevo módulo de Dashboard de Caja
INSERT INTO modulos (
    id, 
    modulo, 
    padre_id, 
    vista, 
    icon_menu, 
    orden
) VALUES (
    NULL,
    'Dashboard de Caja',
    39,  -- Padre: Caja (ID 39)
    'dashboard_caja.php',
    'fas fa-tachometer-alt',
    1.5  -- Antes de "Aperturar Caja" 
);

-- Obtener el ID del módulo recién insertado
SET @dashboard_caja_id = LAST_INSERT_ID();

-- Dar permisos al perfil Administrador (id_perfil = 1)
INSERT INTO perfil_modulo (
    idperfil_modulo,
    id_perfil,
    id_modulo,
    vista_inicio,
    estado
) VALUES (
    NULL,
    1,
    @dashboard_caja_id,
    0,
    1
);

-- Dar permisos al perfil Prestamista (id_perfil = 2) si existe
INSERT IGNORE INTO perfil_modulo (
    idperfil_modulo,
    id_perfil,
    id_modulo,
    vista_inicio,
    estado
) VALUES (
    NULL,
    2,
    @dashboard_caja_id,
    0,
    1
);

-- Mostrar resultado
SELECT 
    'Dashboard de Caja agregado correctamente al menú' as mensaje,
    @dashboard_caja_id as modulo_id,
    NOW() as fecha_instalacion;

-- Verificar la estructura del menú de Caja
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.orden,
    m.icon_menu
FROM modulos m 
WHERE m.padre_id = 39 OR m.id = 39
ORDER BY m.orden ASC; 