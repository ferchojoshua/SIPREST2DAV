-- Agregar módulo de Dashboard de Cobradores al menú del sistema
-- Ejecutar este script para agregar la nueva funcionalidad

-- Verificar si el módulo ya existe
SELECT id_modulo, modulo_nombre 
FROM modulos 
WHERE modulo_nombre = 'Dashboard Cobradores' 
   OR vista_nombre = 'dashboard_cobradores';

-- Si no existe, insertarlo
INSERT IGNORE INTO modulos (
    modulo_nombre, 
    vista_nombre, 
    icon_menu, 
    orden,
    fecha_creacion,
    fecha_actualizacion
) VALUES (
    'Dashboard Cobradores',
    'dashboard_cobradores', 
    'fas fa-chart-pie',
    15,
    NOW(),
    NOW()
);

-- Obtener el ID del módulo recién creado
SET @dashboard_modulo_id = LAST_INSERT_ID();

-- Si LAST_INSERT_ID() es 0, significa que ya existe, obtener el ID existente
IF @dashboard_modulo_id = 0 THEN
    SELECT @dashboard_modulo_id := id_modulo 
    FROM modulos 
    WHERE modulo_nombre = 'Dashboard Cobradores' 
    LIMIT 1;
END IF;

-- Asignar permisos al perfil Administrador (id_perfil = 1)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, fecha_asignacion)
VALUES (1, @dashboard_modulo_id, NOW());

-- Asignar permisos al perfil Prestamista/Operador (id_perfil = 2) si existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, fecha_asignacion)
SELECT 2, @dashboard_modulo_id, NOW()
WHERE EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- Verificar la inserción
SELECT 
    m.id_modulo,
    m.modulo_nombre,
    m.vista_nombre,
    m.icon_menu,
    m.orden,
    GROUP_CONCAT(p.descripcion SEPARATOR ', ') as perfiles_asignados
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id_modulo = pm.id_modulo
LEFT JOIN perfiles p ON pm.id_perfil = p.id_perfil
WHERE m.modulo_nombre = 'Dashboard Cobradores'
GROUP BY m.id_modulo, m.modulo_nombre, m.vista_nombre, m.icon_menu, m.orden;

-- Mensaje de confirmación
SELECT 'Dashboard de Cobradores agregado exitosamente al menú del sistema' as mensaje;

-- Opcional: Actualizar orden de otros módulos si es necesario
-- UPDATE modulos SET orden = orden + 1 WHERE orden >= 15 AND modulo_nombre != 'Dashboard Cobradores'; 