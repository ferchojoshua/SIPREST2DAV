-- =====================================================================
-- SCRIPT PARA LIMPIAR DUPLICACIONES COMPLETAS DEL MENÚ
-- Elimina todas las duplicaciones y reorganiza el menú correctamente
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;

-- =====================================================================
-- PASO 1: HACER BACKUP DE LAS TABLAS
-- =====================================================================

-- Crear tabla de respaldo de módulos
DROP TABLE IF EXISTS modulos_backup_duplicados;
CREATE TABLE modulos_backup_duplicados AS SELECT * FROM modulos;

-- Crear tabla de respaldo de permisos
DROP TABLE IF EXISTS perfil_modulo_backup_duplicados;
CREATE TABLE perfil_modulo_backup_duplicados AS SELECT * FROM perfil_modulo;

-- =====================================================================
-- PASO 2: IDENTIFICAR Y MOSTRAR DUPLICADOS
-- =====================================================================

SELECT 'DUPLICADOS ENCONTRADOS:' as titulo;

-- Mostrar duplicados por vista
SELECT 
    vista,
    modulo,
    COUNT(*) as cantidad,
    GROUP_CONCAT(id ORDER BY id) as ids_duplicados
FROM modulos 
WHERE vista IS NOT NULL AND vista != ''
GROUP BY vista 
HAVING COUNT(*) > 1
ORDER BY cantidad DESC;

-- Mostrar duplicados por módulo padre
SELECT 
    modulo,
    padre_id,
    COUNT(*) as cantidad,
    GROUP_CONCAT(id ORDER BY id) as ids_duplicados
FROM modulos 
WHERE padre_id > 0
GROUP BY modulo, padre_id 
HAVING COUNT(*) > 1
ORDER BY cantidad DESC;

-- =====================================================================
-- PASO 3: ELIMINAR DUPLICADOS - MANTENER EL DE MENOR ID
-- =====================================================================

-- Eliminar duplicados de módulos por vista (mantener el de menor ID)
DELETE m1 FROM modulos m1
INNER JOIN modulos m2 
WHERE m1.id > m2.id 
  AND m1.vista = m2.vista 
  AND m1.vista IS NOT NULL 
  AND m1.vista != '';

-- Eliminar duplicados de módulos principales (mantener el de menor ID)
DELETE m1 FROM modulos m1
INNER JOIN modulos m2 
WHERE m1.id > m2.id 
  AND m1.modulo = m2.modulo 
  AND m1.padre_id = m2.padre_id;

-- =====================================================================
-- PASO 4: LIMPIAR PERMISOS DUPLICADOS
-- =====================================================================

-- Eliminar permisos duplicados (mantener el de menor ID)
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo 
  AND pm1.id > pm2.id;

-- Eliminar permisos huérfanos (módulos que ya no existen)
DELETE FROM perfil_modulo 
WHERE id_modulo NOT IN (SELECT id FROM modulos);

-- =====================================================================
-- PASO 5: REORGANIZAR ESTRUCTURA COMPLETA DEL MENÚ
-- =====================================================================

-- MENÚ PRINCIPAL (orden base)
UPDATE modulos SET orden = 1 WHERE modulo = 'Tablero pincipal' AND padre_id = 0;
UPDATE modulos SET orden = 2 WHERE modulo = 'Caja' AND padre_id = 0;
UPDATE modulos SET orden = 3 WHERE modulo = 'Clientes' AND padre_id = 0;
UPDATE modulos SET orden = 4 WHERE modulo = 'Prestamos' AND padre_id = 0;
UPDATE modulos SET orden = 5 WHERE modulo = 'Rutas' AND padre_id = 0;
UPDATE modulos SET orden = 6 WHERE modulo = 'Notas de Débito' AND padre_id = 0;
UPDATE modulos SET orden = 7 WHERE modulo = 'Empresa' AND padre_id = 0;
UPDATE modulos SET orden = 8 WHERE modulo = 'Moneda' AND padre_id = 0;
UPDATE modulos SET orden = 9 WHERE modulo = 'Backup' AND padre_id = 0;
UPDATE modulos SET orden = 10 WHERE modulo = 'Mantenimiento' AND padre_id = 0;
UPDATE modulos SET orden = 11 WHERE modulo = 'Reportes' AND padre_id = 0;

-- SUBMÓDULOS DE CAJA
UPDATE modulos SET orden = 21 WHERE vista = 'caja.php' AND padre_id = (SELECT id FROM (SELECT id FROM modulos WHERE modulo = 'Caja' AND padre_id = 0) AS t);
UPDATE modulos SET orden = 22 WHERE vista = 'ingresos.php' AND padre_id = (SELECT id FROM (SELECT id FROM modulos WHERE modulo = 'Caja' AND padre_id = 0) AS t);

-- SUBMÓDULOS DE PRÉSTAMOS
UPDATE modulos SET orden = 31 WHERE vista = 'prestamo.php';
UPDATE modulos SET orden = 32 WHERE vista = 'administrar_prestamos.php';
UPDATE modulos SET orden = 33 WHERE vista = 'aprobacion.php';

-- SUBMÓDULOS DE MANTENIMIENTO
UPDATE modulos SET orden = 41 WHERE vista = 'usuario.php';
UPDATE modulos SET orden = 42 WHERE vista = 'modulos_perfiles.php';
UPDATE modulos SET orden = 43 WHERE vista = 'sucursales.php';

-- =====================================================================
-- PASO 6: REORGANIZAR REPORTES (ELIMINAR TODOS LOS DUPLICADOS)
-- =====================================================================

-- Obtener ID del módulo padre Reportes
SET @reportes_padre_id = (SELECT id FROM modulos WHERE modulo = 'Reportes' AND padre_id = 0 LIMIT 1);

-- REPORTES ÚNICOS - Orden definitivo
UPDATE modulos SET orden = 51 WHERE vista = 'reporte_cliente.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 52 WHERE vista = 'reporte_cuotas_pagadas.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 53 WHERE vista = 'reportes.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 54 WHERE vista = 'reporte_diario.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 55 WHERE vista = 'estado_cuenta_cliente.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 56 WHERE vista = 'reporte_mora.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 57 WHERE vista = 'reporte_cobranza.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 58 WHERE vista = 'reporte_cuotas_atrasadas.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 59 WHERE vista = 'reporte_saldos_arrastrados.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 60 WHERE vista = 'reporte_recuperacion.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 61 WHERE vista = 'reportes_financieros.php' AND padre_id = @reportes_padre_id;
UPDATE modulos SET orden = 62 WHERE vista = 'grupos_reportes.php' AND padre_id = @reportes_padre_id;

-- =====================================================================
-- PASO 7: ASEGURAR NOMBRES ÚNICOS PARA REPORTES
-- =====================================================================

UPDATE modulos SET modulo = 'Por Cliente' WHERE vista = 'reporte_cliente.php';
UPDATE modulos SET modulo = 'Cuotas Pagadas' WHERE vista = 'reporte_cuotas_pagadas.php';
UPDATE modulos SET modulo = 'Pivot' WHERE vista = 'reportes.php';
UPDATE modulos SET modulo = 'Reporte Diario' WHERE vista = 'reporte_diario.php';
UPDATE modulos SET modulo = 'Estado de C. Cliente' WHERE vista = 'estado_cuenta_cliente.php';
UPDATE modulos SET modulo = 'Reporte Mora' WHERE vista = 'reporte_mora.php';
UPDATE modulos SET modulo = 'Reporte Cobro Diaria' WHERE vista = 'reporte_cobranza.php';
UPDATE modulos SET modulo = 'Reporte C.Mora' WHERE vista = 'reporte_cuotas_atrasadas.php';
UPDATE modulos SET modulo = 'Saldos Arrastrados' WHERE vista = 'reporte_saldos_arrastrados.php';
UPDATE modulos SET modulo = 'Reporte Recuperación' WHERE vista = 'reporte_recuperacion.php';
UPDATE modulos SET modulo = 'Reportes Financieros' WHERE vista = 'reportes_financieros.php';
UPDATE modulos SET modulo = 'Grupos Reportes' WHERE vista = 'grupos_reportes.php';

-- =====================================================================
-- PASO 8: ASEGURAR PERMISOS ÚNICOS PARA ADMINISTRADOR
-- =====================================================================

-- Eliminar todos los permisos existentes para evitar duplicados
DELETE FROM perfil_modulo WHERE id_perfil = 1;

-- Insertar permisos únicos para administrador
INSERT INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT DISTINCT 1, id, 0, 1 
FROM modulos 
WHERE id IS NOT NULL;

-- =====================================================================
-- PASO 9: VERIFICACIÓN FINAL
-- =====================================================================

-- Verificar que no hay duplicados
SELECT 'VERIFICACIÓN - MÓDULOS PRINCIPALES:' as titulo;
SELECT 
    id,
    modulo,
    padre_id,
    vista,
    orden
FROM modulos 
WHERE padre_id = 0 
ORDER BY orden;

SELECT 'VERIFICACIÓN - SUBMÓDULOS DE REPORTES:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    orden
FROM modulos 
WHERE padre_id = @reportes_padre_id
ORDER BY orden;

-- Contar duplicados restantes
SELECT 'DUPLICADOS RESTANTES:' as titulo;
SELECT 
    vista,
    COUNT(*) as cantidad
FROM modulos 
WHERE vista IS NOT NULL AND vista != ''
GROUP BY vista 
HAVING COUNT(*) > 1;

-- Contar total de módulos
SELECT 
    'RESUMEN FINAL:' as titulo,
    COUNT(*) as total_modulos,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = 0) as menu_principal,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = @reportes_padre_id) as reportes,
    (SELECT COUNT(*) FROM perfil_modulo WHERE id_perfil = 1) as permisos_admin
FROM modulos;

-- =====================================================================
-- PASO 10: LIMPIEZA FINAL
-- =====================================================================

-- Eliminar cualquier módulo sin nombre o vista
DELETE FROM modulos 
WHERE (modulo IS NULL OR modulo = '') 
  AND (vista IS NULL OR vista = '');

-- Reorganizar IDs secuenciales (opcional)
-- SET @count = 0;
-- UPDATE modulos SET id = @count:= @count + 1 ORDER BY orden, id;

SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 
    'LIMPIEZA COMPLETA FINALIZADA ✅' as resultado,
    'Menú reorganizado sin duplicaciones' as descripcion,
    'Reiniciar sesión para ver cambios' as instruccion,
    NOW() as fecha_ejecucion;

-- =====================================================================
-- NOTA: En caso de problemas, restaurar desde backup:
-- =====================================================================
/*
-- Para restaurar en caso de problemas:
DROP TABLE modulos;
CREATE TABLE modulos AS SELECT * FROM modulos_backup_duplicados;

DROP TABLE perfil_modulo;
CREATE TABLE perfil_modulo AS SELECT * FROM perfil_modulo_backup_duplicados;
*/

-- =====================================================================
-- ESTRUCTURA FINAL ESPERADA:
-- =====================================================================
/*
🏠 MENÚ PRINCIPAL:
├── Tablero principal
├── Caja
│   ├── Aperturar Caja
│   └── Ingresos / Egre
├── Clientes
├── Prestamos
│   ├── Solicitud/Prestamo
│   ├── Listado Prestamos
│   └── Aprobar S/P
├── Rutas
├── Notas de Débito
├── Empresa
├── Moneda
├── Backup
├── Mantenimiento
│   ├── Usuarios
│   ├── Modulos y Perfiles
│   └── Sucursales
└── Reportes
    ├── Por Cliente
    ├── Cuotas Pagadas
    ├── Pivot
    ├── Reporte Diario
    ├── Estado de C. Cliente
    ├── Reporte Mora
    ├── Reporte Cobro Diaria
    ├── Reporte C.Mora
    ├── Saldos Arrastrados
    ├── Reporte Recuperación
    ├── Reportes Financieros
    └── Grupos Reportes

✅ TODAS LAS DUPLICACIONES ELIMINADAS
✅ MENÚ REORGANIZADO CORRECTAMENTE
✅ PERMISOS ÚNICOS ASIGNADOS
*/ 