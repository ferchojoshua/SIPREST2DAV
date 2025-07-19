-- =======================================================================
-- SCRIPT PARA CORREGIR PERMISOS DE REPORTES FALTANTES
-- =======================================================================
-- Este script asegura que todos los reportes tengan permisos correctos

SET SQL_SAFE_UPDATES = 0;

-- =======================================================================
-- PASO 1: MOSTRAR ESTADO ACTUAL
-- =======================================================================
SELECT 'ESTADO ACTUAL DE REPORTES:' as titulo;
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.orden,
    CASE 
        WHEN pm.estado = 1 THEN '✅ TIENE PERMISO'
        WHEN pm.estado IS NULL THEN '❌ SIN PERMISO'
        ELSE '🔒 BLOQUEADO'
    END as estado_permiso
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = 1
WHERE m.padre_id = 10 OR m.id = 10
ORDER BY m.orden;

-- =======================================================================
-- PASO 2: ASIGNAR PERMISOS FALTANTES PARA ADMINISTRADOR
-- =======================================================================

-- Insertar permisos para módulos existentes que no los tengan
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, m.id, 0, 1
FROM modulos m
WHERE (m.padre_id = 10 OR m.id = 10)
  AND NOT EXISTS (
    SELECT 1 FROM perfil_modulo pm 
    WHERE pm.id_modulo = m.id AND pm.id_perfil = 1
  );

-- =======================================================================
-- PASO 3: AGREGAR REPORTES FALTANTES SI NO EXISTEN
-- =======================================================================

-- Reporte Cliente (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Por Cliente', 10, 'reporte_cliente.php', 'far fa-circle', 16);

-- Cuotas Pagadas (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Cuotas Pagadas', 10, 'reporte_cuotas_pagadas.php', 'far fa-circle', 17);

-- Pivot (si no existe)  
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Pivot', 10, 'reportes.php', 'far fa-circle', 18);

-- Reporte Diario (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reporte Diario', 10, 'reporte_diario.php', 'far fa-circle', 19);

-- Estado de Cuenta Cliente (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Estado de C. Cliente', 10, 'estado_cuenta_cliente.php', 'far fa-circle', 20);

-- Reporte Mora (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reporte Mora', 10, 'reporte_mora.php', 'far fa-circle', 21);

-- Reporte Cobranza (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reporte Cobro Diaria', 10, 'reporte_cobranza.php', 'far fa-circle', 22);

-- Reporte Cuotas Atrasadas (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reporte C.Mora', 10, 'reporte_cuotas_atrasadas.php', 'far fa-circle', 23);

-- Reportes Financieros (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 24);

-- Reporte Saldos Arrastrados (si no existe)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Saldos Arrastrados', 10, 'reporte_saldos_arrastrados.php', 'far fa-circle', 25);

-- Reporte Recuperación (si no existe) 
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reporte Recuperación', 10, 'reporte_recuperacion.php', 'far fa-circle', 26);

-- =======================================================================
-- PASO 4: ASIGNAR PERMISOS A TODOS LOS REPORTES NUEVOS
-- =======================================================================

-- Asignar permisos de administrador a todos los reportes
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, m.id, 0, 1
FROM modulos m
WHERE (m.padre_id = 10 OR m.id = 10)
  AND NOT EXISTS (
    SELECT 1 FROM perfil_modulo pm 
    WHERE pm.id_modulo = m.id AND pm.id_perfil = 1
  );

-- Asignar permisos de perfil 2 (si existe) a reportes básicos
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, m.id, 0, 1
FROM modulos m
WHERE m.padre_id = 10 
  AND m.vista IN (
    'reporte_cliente.php',
    'reporte_cuotas_pagadas.php', 
    'reporte_diario.php',
    'estado_cuenta_cliente.php',
    'reportes_financieros.php'
  )
  AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2)
  AND NOT EXISTS (
    SELECT 1 FROM perfil_modulo pm 
    WHERE pm.id_modulo = m.id AND pm.id_perfil = 2
  );

-- =======================================================================
-- PASO 5: CORREGIR DUPLICADOS Y ORDENAMIENTO
-- =======================================================================

-- Eliminar duplicados si existen (mantener el de menor ID)
DELETE m1 FROM modulos m1
INNER JOIN modulos m2 
WHERE m1.id > m2.id 
  AND m1.vista = m2.vista 
  AND m1.padre_id = 10 
  AND m2.padre_id = 10;

-- Reorganizar orden para evitar conflictos
UPDATE modulos SET orden = 16 WHERE vista = 'reporte_cliente.php' AND padre_id = 10;
UPDATE modulos SET orden = 17 WHERE vista = 'reporte_cuotas_pagadas.php' AND padre_id = 10;
UPDATE modulos SET orden = 18 WHERE vista = 'reportes.php' AND padre_id = 10;
UPDATE modulos SET orden = 19 WHERE vista = 'reporte_diario.php' AND padre_id = 10;
UPDATE modulos SET orden = 20 WHERE vista = 'estado_cuenta_cliente.php' AND padre_id = 10;
UPDATE modulos SET orden = 21 WHERE vista = 'reporte_mora.php' AND padre_id = 10;
UPDATE modulos SET orden = 22 WHERE vista = 'reporte_cobranza.php' AND padre_id = 10;
UPDATE modulos SET orden = 23 WHERE vista = 'reporte_cuotas_atrasadas.php' AND padre_id = 10;
UPDATE modulos SET orden = 24 WHERE vista = 'reportes_financieros.php' AND padre_id = 10;
UPDATE modulos SET orden = 25 WHERE vista = 'reporte_saldos_arrastrados.php' AND padre_id = 10;
UPDATE modulos SET orden = 26 WHERE vista = 'reporte_recuperacion.php' AND padre_id = 10;

-- =======================================================================
-- PASO 6: VERIFICACIÓN FINAL
-- =======================================================================

SELECT 'REPORTES DESPUÉS DE LA CORRECCIÓN:' as titulo;
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.orden,
    CASE 
        WHEN pm.estado = 1 THEN '✅ TIENE PERMISO'
        WHEN pm.estado IS NULL THEN '❌ SIN PERMISO'
        ELSE '🔒 BLOQUEADO'
    END as estado_permiso_admin,
    CASE 
        WHEN pm2.estado = 1 THEN '✅ TIENE PERMISO'
        WHEN pm2.estado IS NULL THEN '❌ SIN PERMISO'
        ELSE '🔒 BLOQUEADO'
    END as estado_permiso_perfil2
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = 1
LEFT JOIN perfil_modulo pm2 ON m.id = pm2.id_modulo AND pm2.id_perfil = 2
WHERE m.padre_id = 10 OR m.id = 10
ORDER BY m.orden;

-- Contar reportes sin permisos
SELECT 
    'RESUMEN FINAL:' as titulo,
    COUNT(*) as total_reportes,
    SUM(CASE WHEN pm.estado = 1 THEN 1 ELSE 0 END) as con_permisos_admin,
    SUM(CASE WHEN pm.estado IS NULL THEN 1 ELSE 0 END) as sin_permisos_admin
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = 1
WHERE m.padre_id = 10;

SET SQL_SAFE_UPDATES = 1;

-- =======================================================================
-- MENSAJE FINAL
-- =======================================================================
SELECT 'CORRECCIÓN DE PERMISOS COMPLETADA ✅' as resultado,
       'Reinicie sesión para ver los cambios' as nota_importante,
       NOW() as fecha_ejecucion;

-- =======================================================================
-- INSTRUCCIONES POST-EJECUCIÓN:
-- =======================================================================
/*
DESPUÉS DE EJECUTAR ESTE SCRIPT:

1. Cerrar sesión en SIPREST
2. Volver a iniciar sesión
3. Ir al menú Reportes y verificar que aparezcan todos
4. Si algún reporte da error 404, verificar que el archivo existe en vistas/
5. Para crear archivos faltantes, contactar al desarrollador

REPORTES QUE DEBERÍAN APARECER:
✅ Por Cliente
✅ Cuotas Pagadas  
✅ Pivot
✅ Reporte Diario
✅ Estado de C. Cliente
✅ Reporte Mora
✅ Reporte Cobro Diaria
✅ Reporte C.Mora
✅ Reportes Financieros
✅ Saldos Arrastrados
✅ Reporte Recuperación

Si alguno no aparece después del script, puede ser:
- Archivo no existe físicamente
- Cache del navegador (Ctrl+F5)
- Problema de permisos de PHP
*/ 