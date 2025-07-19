-- Script para actualizar el módulo de Rutas
-- Mover el módulo de Rutas después de Sucursales en el menú de Mantenimiento
-- Ejecutar en phpMyAdmin o línea de comandos MySQL

-- Actualizar el módulo de Rutas para que esté bajo Mantenimiento después de Sucursales
UPDATE `modulos` 
SET `padre_id` = 14, 
    `orden` = 16 
WHERE `id` = 55 AND `modulo` = 'Rutas';

-- Verificar que el módulo se actualizó correctamente
SELECT 'Módulo de Rutas actualizado:' as mensaje;
SELECT m.id, m.modulo, m.padre_id, 
       (SELECT mp.modulo FROM modulos mp WHERE mp.id = m.padre_id) as modulo_padre,
       m.vista, m.icon_menu, m.orden 
FROM modulos m 
WHERE m.id = 55;

-- Mostrar el orden actual del menú de Mantenimiento
SELECT 'Módulos en Mantenimiento:' as mensaje;
SELECT m.id, m.modulo, m.vista, m.icon_menu, m.orden 
FROM modulos m 
WHERE m.padre_id = 14 
ORDER BY m.orden; 