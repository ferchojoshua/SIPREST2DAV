-- =====================================================
-- SCRIPT PARA AGREGAR DASHBOARD MEJORADO AL MENÚ
-- =====================================================
-- Permite tener ambos dashboards: original y mejorado

-- Agregar el dashboard mejorado al menú
INSERT INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboard Mejorado', 0, 'dashboard_mejorado.php', 'fas fa-chart-line', 0.5)
ON DUPLICATE KEY UPDATE 
modulo = VALUES(modulo),
vista = VALUES(vista),
icon_menu = VALUES(icon_menu),
orden = VALUES(orden);

-- Obtener el ID del módulo recién insertado
SET @modulo_dashboard_mejorado_id = (SELECT id FROM modulos WHERE vista = 'dashboard_mejorado.php' LIMIT 1);

-- Asignar permisos al perfil de Administrador (ID = 1)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES (1, @modulo_dashboard_mejorado_id, '', '1');

-- Asignar permisos al perfil de Prestamista (ID = 2) si existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, @modulo_dashboard_mejorado_id, '', '1'
WHERE EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- Verificar la inserción
SELECT 
    '✅ DASHBOARD MEJORADO AGREGADO AL MENÚ' as resultado,
    m.id as modulo_id,
    m.modulo as nombre_modulo,
    m.vista as archivo_vista,
    m.icon_menu as icono,
    m.orden as orden_menu
FROM modulos m 
WHERE m.vista = 'dashboard_mejorado.php';

-- Verificar permisos asignados
SELECT 
    '📋 PERMISOS ASIGNADOS' as info,
    p.descripcion as perfil,
    pm.id_perfil as perfil_id,
    pm.id_modulo as modulo_id
FROM perfil_modulo pm
INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
WHERE pm.id_modulo = @modulo_dashboard_mejorado_id;

-- Mensaje de instrucciones
SELECT '
🎉 ¡DASHBOARD MEJORADO AGREGADO EXITOSAMENTE!

📋 OPCIONES DISPONIBLES:
1️⃣ Dashboard Original → vistas/dashboard.php (sin cambios)
2️⃣ Dashboard Mejorado → vistas/dashboard_mejorado.php (con filtros y botones funcionales)

🚀 PRÓXIMOS PASOS:
1. Accede al sistema como Administrador
2. Verás "Dashboard Mejorado" en el menú principal
3. Compara ambas versiones
4. Decide cuál usar como predeterminado

🔄 PARA REEMPLAZAR EL DASHBOARD ORIGINAL:
- Opcional: Hacer backup de vistas/dashboard.php
- Copiar vistas/dashboard_mejorado.php → vistas/dashboard.php
- Eliminar entrada "Dashboard Mejorado" del menú (opcional)

📊 BENEFICIOS DE LA VERSIÓN MEJORADA:
✅ Filtros por sucursal y período
✅ Botones "Más info" funcionales  
✅ Explicación de métricas
✅ Mejor experiencia de usuario
✅ 100% compatible con sistema existente
' as instrucciones; 