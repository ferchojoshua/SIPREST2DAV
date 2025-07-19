-- =====================================================================
-- SCRIPT PARA LIMPIAR DUPLICACIONES EN EL MENÚ DE SIPREST
-- Elimina entradas duplicadas y reorganiza la estructura correctamente
-- =====================================================================

-- Deshabilitar safe mode temporalmente
SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
-- PASO 1: IDENTIFICAR Y ELIMINAR DUPLICACIONES
-- =====================================================================

-- Mostrar estado actual antes de limpiar
SELECT 'ESTADO ACTUAL DEL MENÚ - ANTES DE LIMPIAR:' as titulo;
SELECT id, modulo, padre_id, vista, icon_menu, orden 
FROM modulos 
WHERE modulo LIKE '%Dashboard%' OR modulo LIKE '%Caja%' 
ORDER BY orden, id;

-- =====================================================================
-- PASO 2: ELIMINAR DUPLICACIONES ESPECÍFICAS
-- =====================================================================

-- Eliminar posibles duplicados de "Dashboard de Caja" 
DELETE FROM modulos 
WHERE modulo LIKE '%Dashboard de Caja%' 
  AND id NOT IN (
    SELECT min_id FROM (
      SELECT MIN(id) as min_id 
      FROM modulos 
      WHERE modulo LIKE '%Dashboard de Caja%'
    ) as temp
  );

-- Eliminar referencias a "Sistema de Caja Mejorado" si existen en BD
DELETE FROM modulos 
WHERE modulo LIKE '%Sistema de Caja Mejorado%';

-- Eliminar duplicados de módulo Dashboard si hay más de uno
DELETE FROM modulos 
WHERE modulo = 'Dashboards' 
  AND padre_id = 0 
  AND id NOT IN (
    SELECT min_id FROM (
      SELECT MIN(id) as min_id 
      FROM modulos 
      WHERE modulo = 'Dashboards' AND padre_id = 0
    ) as temp
  );

-- =====================================================================
-- PASO 3: CORREGIR NOMBRES Y ESTRUCTURA
-- =====================================================================

-- Asegurar que existe el módulo padre "Dashboards"
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboards', 0, NULL, 'fas fa-chart-pie', 0);

-- Obtener ID del módulo padre
SET @dashboard_padre_id = (SELECT id FROM modulos WHERE modulo = 'Dashboards' AND padre_id = 0 LIMIT 1);

-- Corregir el nombre del dashboard principal
UPDATE modulos 
SET modulo = 'Dashboard Ejecutivo' 
WHERE (modulo = 'Tablero pincipal' OR modulo = 'Dashboard Principal') 
  AND vista = 'dashboard.php';

-- Asegurar que existe el Dashboard de Caja con nombre correcto
INSERT INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboard de Caja', @dashboard_padre_id, 'dashboard_caja.php', 'far fa-circle', 3)
ON DUPLICATE KEY UPDATE 
  modulo = 'Dashboard de Caja',
  padre_id = @dashboard_padre_id,
  vista = 'dashboard_caja.php',
  icon_menu = 'far fa-circle',
  orden = 3;

-- Asegurar que existe el Dashboard de Cobradores
INSERT INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Dashboard Cobradores', @dashboard_padre_id, 'dashboard_cobradores.php', 'far fa-circle', 4)
ON DUPLICATE KEY UPDATE 
  modulo = 'Dashboard Cobradores',
  padre_id = @dashboard_padre_id,
  vista = 'dashboard_cobradores.php',
  icon_menu = 'far fa-circle',
  orden = 4;

-- =====================================================================
-- PASO 4: ORGANIZAR JERARQUÍA CORRECTA
-- =====================================================================

-- Mover Dashboard Ejecutivo bajo Dashboards
UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 1
WHERE modulo = 'Dashboard Ejecutivo' 
  AND vista = 'dashboard.php';

-- Mover Dashboard de Caja bajo Dashboards
UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 2
WHERE modulo = 'Dashboard de Caja' 
  AND vista = 'dashboard_caja.php';

-- Mover Dashboard Cobradores bajo Dashboards
UPDATE modulos 
SET padre_id = @dashboard_padre_id, 
    orden = 3
WHERE modulo = 'Dashboard Cobradores' 
  AND vista = 'dashboard_cobradores.php';

-- =====================================================================
-- PASO 5: LIMPIAR MÓDULO DE CAJA - ELIMINAR DUPLICADOS
-- =====================================================================

-- Asegurar que solo existe un módulo "Caja" padre
UPDATE modulos 
SET padre_id = 0,
    orden = 1
WHERE modulo = 'Caja' 
  AND id = (SELECT * FROM (SELECT MIN(id) FROM modulos WHERE modulo = 'Caja') as temp);

-- Eliminar duplicados del módulo Caja
DELETE FROM modulos 
WHERE modulo = 'Caja' 
  AND id NOT IN (
    SELECT min_id FROM (
      SELECT MIN(id) as min_id 
      FROM modulos 
      WHERE modulo = 'Caja'
    ) as temp
  );

-- Obtener ID del módulo Caja
SET @caja_padre_id = (SELECT id FROM modulos WHERE modulo = 'Caja' AND padre_id = 0 LIMIT 1);

-- Asegurar submódulos de Caja correctos
UPDATE modulos 
SET padre_id = @caja_padre_id 
WHERE modulo IN ('Aperturar Caja', 'Ingresos / Egre') 
  AND padre_id != @caja_padre_id;

-- =====================================================================
-- PASO 6: VERIFICAR Y ASIGNAR PERMISOS
-- =====================================================================

-- Asignar permisos al perfil Administrador para todos los dashboards
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, id, 0, 1 
FROM modulos 
WHERE modulo IN ('Dashboards', 'Dashboard Ejecutivo', 'Dashboard de Caja', 'Dashboard Cobradores');

-- Asignar permisos al perfil 2 si existe
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, id, 0, 1 
FROM modulos 
WHERE modulo IN ('Dashboards', 'Dashboard Ejecutivo', 'Dashboard de Caja', 'Dashboard Cobradores')
  AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- =====================================================================
-- PASO 7: VERIFICACIÓN FINAL
-- =====================================================================

-- Mostrar estructura final limpia
SELECT 'ESTRUCTURA FINAL - DESPUÉS DE LIMPIAR:' as titulo;
SELECT 
    m.id,
    m.modulo,
    m.padre_id,
    CASE 
        WHEN m.padre_id = 0 THEN '(Raíz)'
        ELSE (SELECT mp.modulo FROM modulos mp WHERE mp.id = m.padre_id)
    END as padre_nombre,
    m.vista,
    m.icon_menu,
    m.orden
FROM modulos m 
WHERE m.modulo LIKE '%Dashboard%' OR m.modulo LIKE '%Caja%'
ORDER BY 
    CASE WHEN m.padre_id = 0 THEN 0 ELSE 1 END,
    m.padre_id,
    m.orden,
    m.id;

-- Verificar permisos asignados
SELECT 'PERMISOS ASIGNADOS:' as titulo;
SELECT 
    m.id,
    m.modulo,
    GROUP_CONCAT(p.descripcion SEPARATOR ', ') as perfiles_con_acceso
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo
LEFT JOIN perfiles p ON pm.id_perfil = p.id_perfil
WHERE m.modulo LIKE '%Dashboard%' OR m.modulo = 'Caja'
GROUP BY m.id, m.modulo
ORDER BY m.modulo;

-- Habilitar safe mode nuevamente
SET SQL_SAFE_UPDATES = 1;
SET FOREIGN_KEY_CHECKS = 1;

-- Mensaje de finalización
SELECT 'LIMPIEZA DE MENÚ COMPLETADA EXITOSAMENTE' as resultado,
       NOW() as fecha_ejecucion;

-- =====================================================================
-- INSTRUCCIONES POST-EJECUCIÓN:
-- =====================================================================
/*
PASOS DESPUÉS DE EJECUTAR ESTE SCRIPT:

1. Cerrar y abrir sesión en el sistema
2. Verificar que el menú se muestre correctamente:
   - Dashboards (padre)
     ├── Dashboard Ejecutivo
     ├── Dashboard de Caja  
     └── Dashboard Cobradores
   - Caja (padre)
     ├── Aperturar Caja
     └── Ingresos / Egre

3. Probar navegación entre dashboards
4. Verificar que no hay duplicaciones visibles
5. Reportar cualquier problema al administrador del sistema

Si hay problemas, ejecutar:
SELECT * FROM modulos WHERE modulo LIKE '%Dashboard%' ORDER BY id;
*/ 