-- =====================================================================
-- SCRIPT COMPLETO PARA RESTAURAR PRÉSTAMOS Y REPORTES
-- Asegura que TODAS las opciones estén disponibles y funcionando
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;

-- =====================================================================
-- PASO 1: VERIFICAR Y RESTAURAR MÓDULOS DE PRÉSTAMOS
-- =====================================================================

-- Asegurar que el módulo padre "Prestamos" existe
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (29, 'Prestamos', 0, '', 'fas fa-landmark', 5);

-- Asegurar submódulos de préstamos
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (34, 'Solicitud/Prestamo', 29, 'prestamo.php', 'far fa-circle', 6);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (35, 'Listado Prestamos', 29, 'administrar_prestamos.php', 'far fa-circle', 7);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (36, 'Aprobar S/P', 29, 'aprobacion.php', 'far fa-circle', 8);

-- =====================================================================
-- PASO 2: VERIFICAR Y RESTAURAR MÓDULOS DE REPORTES COMPLETOS
-- =====================================================================

-- Asegurar que el módulo padre "Reportes" existe
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (10, 'Reportes', 0, '', 'fas fa-chart-line', 15);

-- REPORTES BÁSICOS (ya existían)
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (37, 'Por Cliente', 10, 'reporte_cliente.php', 'far fa-circle', 16);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (38, 'Cuotas Pagadas', 10, 'reporte_cuotas_pagadas.php', 'far fa-circle', 17);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (43, 'Pivot', 10, 'reportes.php', 'far fa-circle', 18);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (49, 'Reporte Diario', 10, 'reporte_diario.php', 'far fa-circle', 19);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (50, 'Estado de C. Cliente', 10, 'estado_cuenta_cliente.php', 'far fa-circle', 20);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (51, 'Reporte Mora', 10, 'reporte_mora.php', 'far fa-circle', 21);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (52, 'Reporte Cobro Diaria', 10, 'reporte_cobranza.php', 'far fa-circle', 22);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (53, 'Reporte C.Mora', 10, 'reporte_cuotas_atrasadas.php', 'far fa-circle', 23);

-- REPORTES ADICIONALES (pueden haberse perdido)
INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Saldos Arrastrados', 10, 'reporte_saldos_arrastrados.php', 'far fa-circle', 24);

INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reporte Recuperación', 10, 'reporte_recuperacion.php', 'far fa-circle', 25);

INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Reportes Financieros', 10, 'reportes_financieros.php', 'far fa-circle', 26);

INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
VALUES ('Grupos Reportes', 10, 'grupos_reportes.php', 'far fa-circle', 27);

-- =====================================================================
-- PASO 3: ASIGNAR PERMISOS PARA ADMINISTRADOR (TODOS LOS MÓDULOS)
-- =====================================================================

-- Préstamos - Permisos para administrador
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, id, 0, 1 
FROM modulos 
WHERE padre_id = 29 OR id = 29;

-- Reportes - Permisos para administrador
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, id, 0, 1 
FROM modulos 
WHERE padre_id = 10 OR id = 10;

-- =====================================================================
-- PASO 4: ASIGNAR PERMISOS PARA PERFIL 2 (OPERATIVO)
-- =====================================================================

-- Préstamos básicos para perfil operativo
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, id, 0, 1 
FROM modulos 
WHERE vista IN (
    'prestamo.php',
    'administrar_prestamos.php',
    'aprobacion.php'
) AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- Reportes básicos para perfil operativo
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 2, id, 0, 1 
FROM modulos 
WHERE vista IN (
    'reporte_cliente.php',
    'reporte_cuotas_pagadas.php',
    'reporte_diario.php',
    'estado_cuenta_cliente.php',
    'reporte_cobranza.php',
    'reportes_financieros.php'
) AND padre_id = 10 
AND EXISTS (SELECT 1 FROM perfiles WHERE id_perfil = 2);

-- =====================================================================
-- PASO 5: VERIFICAR OTROS MÓDULOS IMPORTANTES
-- =====================================================================

-- Asegurar otros módulos del sistema
INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (24, 'Clientes', 0, 'cliente.php', 'fas fa-id-card', 4);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (39, 'Caja', 0, '', 'fas fa-cash-register', 1);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (40, 'Aperturar Caja', 39, 'caja.php', 'far fa-circle', 2);

INSERT IGNORE INTO modulos (id, modulo, padre_id, vista, icon_menu, orden) 
VALUES (41, 'Ingresos / Egre', 39, 'ingresos.php', 'far fa-circle', 3);

-- Permisos para módulos básicos
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, id, 0, 1 
FROM modulos 
WHERE id IN (24, 39, 40, 41);

-- =====================================================================
-- PASO 6: LIMPIAR DUPLICADOS Y REORGANIZAR
-- =====================================================================

-- Eliminar duplicados manteniendo el de menor ID
DELETE m1 FROM modulos m1
INNER JOIN modulos m2 
WHERE m1.id > m2.id 
  AND m1.vista = m2.vista 
  AND m1.vista IS NOT NULL 
  AND m1.vista != '';

-- Reorganizar orden de préstamos
UPDATE modulos SET orden = 5 WHERE id = 29; -- Prestamos padre
UPDATE modulos SET orden = 6 WHERE id = 34; -- Solicitud/Prestamo
UPDATE modulos SET orden = 7 WHERE id = 35; -- Listado Prestamos
UPDATE modulos SET orden = 8 WHERE id = 36; -- Aprobar S/P

-- Reorganizar orden de reportes
UPDATE modulos SET orden = 15 WHERE id = 10; -- Reportes padre
UPDATE modulos SET orden = 16 WHERE id = 37; -- Por Cliente
UPDATE modulos SET orden = 17 WHERE id = 38; -- Cuotas Pagadas
UPDATE modulos SET orden = 18 WHERE id = 43; -- Pivot
UPDATE modulos SET orden = 19 WHERE id = 49; -- Reporte Diario
UPDATE modulos SET orden = 20 WHERE id = 50; -- Estado de C. Cliente
UPDATE modulos SET orden = 21 WHERE id = 51; -- Reporte Mora
UPDATE modulos SET orden = 22 WHERE id = 52; -- Reporte Cobro Diaria
UPDATE modulos SET orden = 23 WHERE id = 53; -- Reporte C.Mora

-- =====================================================================
-- PASO 7: VERIFICACIÓN FINAL
-- =====================================================================

-- Mostrar módulos de préstamos
SELECT 'MÓDULOS DE PRÉSTAMOS:' as titulo;
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.orden,
    CASE 
        WHEN pm.estado = 1 THEN '✅ ADMIN'
        ELSE '❌ SIN PERMISOS'
    END as permisos_admin
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = 1
WHERE m.padre_id = 29 OR m.id = 29
ORDER BY m.orden;

-- Mostrar módulos de reportes
SELECT 'MÓDULOS DE REPORTES:' as titulo;
SELECT 
    m.id,
    m.modulo,
    m.vista,
    m.orden,
    CASE 
        WHEN pm.estado = 1 THEN '✅ ADMIN'
        ELSE '❌ SIN PERMISOS'
    END as permisos_admin
FROM modulos m
LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = 1
WHERE m.padre_id = 10 OR m.id = 10
ORDER BY m.orden;

-- Contar totales
SELECT 
    'RESUMEN FINAL:' as titulo,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = 29 OR id = 29) as total_prestamos,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = 10 OR id = 10) as total_reportes,
    (SELECT COUNT(*) FROM perfil_modulo WHERE id_perfil = 1) as permisos_admin
FROM dual;

SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 'RESTAURACIÓN COMPLETA FINALIZADA ✅' as resultado,
       'Reiniciar sesión para ver todos los cambios' as instruccion,
       NOW() as fecha_ejecucion;

-- =====================================================================
-- ESTRUCTURA FINAL ESPERADA:
-- =====================================================================
/*
📋 PRÉSTAMOS:
├── Solicitud/Prestamo (prestamo.php)
├── Listado Prestamos (administrar_prestamos.php)
└── Aprobar S/P (aprobacion.php)

📊 REPORTES:
├── Por Cliente (reporte_cliente.php)
├── Cuotas Pagadas (reporte_cuotas_pagadas.php)
├── Pivot (reportes.php)
├── Reporte Diario (reporte_diario.php)
├── Estado de C. Cliente (estado_cuenta_cliente.php)
├── Reporte Mora (reporte_mora.php)
├── Reporte Cobro Diaria (reporte_cobranza.php)
├── Reporte C.Mora (reporte_cuotas_atrasadas.php)
├── Saldos Arrastrados (reporte_saldos_arrastrados.php)
├── Reporte Recuperación (reporte_recuperacion.php)
├── Reportes Financieros (reportes_financieros.php)
└── Grupos Reportes (grupos_reportes.php)

TODOS LOS ARCHIVOS DE VISTAS ESTÁN CREADOS Y DISPONIBLES.
*/ 