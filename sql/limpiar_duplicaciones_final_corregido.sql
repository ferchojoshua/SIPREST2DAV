-- =====================================================================
-- SCRIPT FINAL CORREGIDO PARA LIMPIAR DUPLICACIONES
-- Usa el campo correcto 'idperfil_modulo' de la tabla perfil_modulo
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
-- PASO 1: BACKUP DE SEGURIDAD
-- =====================================================================

-- Crear backup de módulos
DROP TABLE IF EXISTS modulos_backup_final;
CREATE TABLE modulos_backup_final AS SELECT * FROM modulos;

-- Crear backup de permisos
DROP TABLE IF EXISTS perfil_modulo_backup_final;
CREATE TABLE perfil_modulo_backup_final AS SELECT * FROM perfil_modulo;

-- =====================================================================
-- PASO 2: LIMPIAR DUPLICADOS EN PERFIL_MODULO (CAMPO CORRECTO)
-- =====================================================================

-- Eliminar duplicados usando idperfil_modulo (campo correcto)
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.idperfil_modulo > pm2.idperfil_modulo 
  AND pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo;

-- =====================================================================
-- PASO 3: CREAR TABLA TEMPORAL PARA MAPEAR DUPLICADOS
-- =====================================================================

-- Crear tabla temporal para duplicados de módulos
DROP TABLE IF EXISTS temp_modulos_duplicados;
CREATE TEMPORARY TABLE temp_modulos_duplicados (
    id_mantener INT,
    ids_eliminar TEXT,
    vista VARCHAR(50),
    modulo VARCHAR(255),
    total_duplicados INT
);

-- Insertar duplicados por vista
INSERT INTO temp_modulos_duplicados (id_mantener, ids_eliminar, vista, modulo, total_duplicados)
SELECT 
    MIN(id) as id_mantener,
    GROUP_CONCAT(id ORDER BY id) as ids_eliminar,
    vista,
    MIN(modulo) as modulo,
    COUNT(*) as total_duplicados
FROM modulos 
WHERE vista IS NOT NULL 
  AND vista != ''
GROUP BY vista 
HAVING COUNT(*) > 1;

-- Mostrar duplicados encontrados
SELECT 'DUPLICADOS ENCONTRADOS:' as titulo;
SELECT * FROM temp_modulos_duplicados;

-- =====================================================================
-- PASO 4: ACTUALIZAR REFERENCIAS EN PERFIL_MODULO
-- =====================================================================

-- Actualizar referencias de módulos duplicados
UPDATE perfil_modulo pm
INNER JOIN modulos m ON pm.id_modulo = m.id
INNER JOIN temp_modulos_duplicados tmd ON m.vista = tmd.vista
SET pm.id_modulo = tmd.id_mantener
WHERE pm.id_modulo != tmd.id_mantener;

-- Eliminar permisos duplicados resultantes
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.idperfil_modulo > pm2.idperfil_modulo 
  AND pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo;

-- =====================================================================
-- PASO 5: ELIMINAR MÓDULOS DUPLICADOS
-- =====================================================================

-- Eliminar módulos duplicados (ahora sin referencias)
DELETE FROM modulos 
WHERE id IN (
    SELECT id FROM (
        SELECT m.id
        FROM modulos m
        INNER JOIN temp_modulos_duplicados tmd ON m.vista = tmd.vista
        WHERE m.id != tmd.id_mantener
    ) AS subquery
);

-- =====================================================================
-- PASO 6: LIMPIAR DUPLICADOS POR NOMBRE DE MÓDULO
-- =====================================================================

-- Crear tabla temporal para duplicados por nombre
DROP TABLE IF EXISTS temp_nombres_duplicados;
CREATE TEMPORARY TABLE temp_nombres_duplicados (
    id_mantener INT,
    ids_eliminar TEXT,
    modulo VARCHAR(255),
    padre_id INT,
    total_duplicados INT
);

-- Insertar duplicados por nombre y padre
INSERT INTO temp_nombres_duplicados (id_mantener, ids_eliminar, modulo, padre_id, total_duplicados)
SELECT 
    MIN(id) as id_mantener,
    GROUP_CONCAT(id ORDER BY id) as ids_eliminar,
    modulo,
    padre_id,
    COUNT(*) as total_duplicados
FROM modulos 
GROUP BY modulo, padre_id 
HAVING COUNT(*) > 1;

-- Actualizar referencias por nombre
UPDATE perfil_modulo pm
INNER JOIN modulos m ON pm.id_modulo = m.id
INNER JOIN temp_nombres_duplicados tnd ON m.modulo = tnd.modulo AND m.padre_id = tnd.padre_id
SET pm.id_modulo = tnd.id_mantener
WHERE pm.id_modulo != tnd.id_mantener;

-- Eliminar permisos duplicados resultantes
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.idperfil_modulo > pm2.idperfil_modulo 
  AND pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo;

-- Eliminar módulos duplicados por nombre
DELETE FROM modulos 
WHERE id IN (
    SELECT id FROM (
        SELECT m.id
        FROM modulos m
        INNER JOIN temp_nombres_duplicados tnd ON m.modulo = tnd.modulo AND m.padre_id = tnd.padre_id
        WHERE m.id != tnd.id_mantener
    ) AS subquery
);

-- =====================================================================
-- PASO 7: REORGANIZAR ESTRUCTURA FINAL
-- =====================================================================

-- Reorganizar menú principal
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

-- Obtener IDs únicos para reorganizar submódulos
SET @caja_id = (SELECT id FROM modulos WHERE modulo = 'Caja' AND padre_id = 0 LIMIT 1);
SET @prestamos_id = (SELECT id FROM modulos WHERE modulo = 'Prestamos' AND padre_id = 0 LIMIT 1);
SET @reportes_id = (SELECT id FROM modulos WHERE modulo = 'Reportes' AND padre_id = 0 LIMIT 1);
SET @mantenimiento_id = (SELECT id FROM modulos WHERE modulo = 'Mantenimiento' AND padre_id = 0 LIMIT 1);

-- Reorganizar submódulos de Caja
UPDATE modulos SET padre_id = @caja_id, orden = 21, modulo = 'Aperturar Caja' 
WHERE vista = 'caja.php' LIMIT 1;
UPDATE modulos SET padre_id = @caja_id, orden = 22, modulo = 'Ingresos / Egre' 
WHERE vista = 'ingresos.php' LIMIT 1;

-- Reorganizar submódulos de Préstamos
UPDATE modulos SET padre_id = @prestamos_id, orden = 31, modulo = 'Solicitud/Prestamo' 
WHERE vista = 'prestamo.php' LIMIT 1;
UPDATE modulos SET padre_id = @prestamos_id, orden = 32, modulo = 'Listado Prestamos' 
WHERE vista = 'administrar_prestamos.php' LIMIT 1;
UPDATE modulos SET padre_id = @prestamos_id, orden = 33, modulo = 'Aprobar S/P' 
WHERE vista = 'aprobacion.php' LIMIT 1;

-- Reorganizar submódulos de Mantenimiento
UPDATE modulos SET padre_id = @mantenimiento_id, orden = 41, modulo = 'Usuarios' 
WHERE vista = 'usuario.php' LIMIT 1;
UPDATE modulos SET padre_id = @mantenimiento_id, orden = 42, modulo = 'Modulos y Perfiles' 
WHERE vista = 'modulos_perfiles.php' LIMIT 1;
UPDATE modulos SET padre_id = @mantenimiento_id, orden = 43, modulo = 'Sucursales' 
WHERE vista = 'sucursales.php' LIMIT 1;

-- Reorganizar submódulos de Reportes (orden único)
UPDATE modulos SET padre_id = @reportes_id, orden = 51, modulo = 'Por Cliente' 
WHERE vista = 'reporte_cliente.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 52, modulo = 'Cuotas Pagadas' 
WHERE vista = 'reporte_cuotas_pagadas.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 53, modulo = 'Pivot' 
WHERE vista = 'reportes.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 54, modulo = 'Reporte Diario' 
WHERE vista = 'reporte_diario.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 55, modulo = 'Estado de C. Cliente' 
WHERE vista = 'estado_cuenta_cliente.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 56, modulo = 'Reporte Mora' 
WHERE vista = 'reporte_mora.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 57, modulo = 'Reporte Cobro Diaria' 
WHERE vista = 'reporte_cobranza.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 58, modulo = 'Reporte C.Mora' 
WHERE vista = 'reporte_cuotas_atrasadas.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 59, modulo = 'Saldos Arrastrados' 
WHERE vista = 'reporte_saldos_arrastrados.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 60, modulo = 'Reporte Recuperación' 
WHERE vista = 'reporte_recuperacion.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 61, modulo = 'Reportes Financieros' 
WHERE vista = 'reportes_financieros.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 62, modulo = 'Grupos Reportes' 
WHERE vista = 'grupos_reportes.php' LIMIT 1;

-- =====================================================================
-- PASO 8: ASEGURAR PERMISOS ÚNICOS PARA ADMINISTRADOR
-- =====================================================================

-- Insertar permisos faltantes para administrador
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, id, 0, 1 
FROM modulos 
WHERE id NOT IN (
    SELECT id_modulo FROM perfil_modulo WHERE id_perfil = 1
);

-- =====================================================================
-- PASO 9: VERIFICACIÓN FINAL
-- =====================================================================

-- Verificar menú principal limpio
SELECT 'MENÚ PRINCIPAL FINAL:' as titulo;
SELECT id, modulo, vista, orden 
FROM modulos 
WHERE padre_id = 0 
ORDER BY orden;

-- Verificar reportes únicos
SELECT 'REPORTES ÚNICOS:' as titulo;
SELECT id, modulo, vista, orden 
FROM modulos 
WHERE padre_id = @reportes_id
ORDER BY orden;

-- Verificar duplicados restantes
SELECT 'VERIFICACIÓN - DUPLICADOS RESTANTES:' as titulo;
SELECT vista, COUNT(*) as cantidad
FROM modulos 
WHERE vista IS NOT NULL AND vista != ''
GROUP BY vista 
HAVING COUNT(*) > 1;

-- Resumen final
SELECT 
    'LIMPIEZA COMPLETADA ✅' as resultado,
    COUNT(*) as total_modulos,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = 0) as menu_principal,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = @reportes_id) as total_reportes,
    (SELECT COUNT(*) FROM perfil_modulo WHERE id_perfil = 1) as permisos_admin
FROM modulos;

SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 
    'DUPLICACIONES ELIMINADAS CORRECTAMENTE ✅' as resultado,
    'Campo idperfil_modulo usado correctamente' as detalle,
    'Menú completamente limpio y reorganizado' as descripcion,
    'Reiniciar sesión para ver cambios' as instruccion,
    NOW() as fecha_ejecucion;

-- =====================================================================
-- SCRIPT DE RESTAURACIÓN EN CASO DE PROBLEMAS:
-- =====================================================================
/*
-- Para restaurar en caso de problemas:
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE modulos;
CREATE TABLE modulos AS SELECT * FROM modulos_backup_final;
DROP TABLE perfil_modulo;
CREATE TABLE perfil_modulo AS SELECT * FROM perfil_modulo_backup_final;
SET FOREIGN_KEY_CHECKS = 1;
*/ 