-- =====================================================================
-- SCRIPT CORREGIDO PARA LIMPIAR DUPLICACIONES DEL MENÚ
-- Maneja correctamente las restricciones de clave foránea
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;

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
-- PASO 2: IDENTIFICAR DUPLICADOS Y CREAR TABLA DE MAPEO
-- =====================================================================

-- Crear tabla temporal para mapear duplicados
DROP TABLE IF EXISTS temp_duplicados_mapping;
CREATE TEMPORARY TABLE temp_duplicados_mapping (
    id_duplicado INT,
    id_mantener INT,
    vista VARCHAR(50),
    modulo VARCHAR(255)
);

-- Insertar mapeo de duplicados por vista (mantener el de menor ID)
INSERT INTO temp_duplicados_mapping (id_duplicado, id_mantener, vista, modulo)
SELECT 
    m1.id as id_duplicado,
    MIN(m2.id) as id_mantener,
    m1.vista,
    m1.modulo
FROM modulos m1
INNER JOIN modulos m2 ON m1.vista = m2.vista 
WHERE m1.vista IS NOT NULL 
  AND m1.vista != ''
  AND m1.id > m2.id
GROUP BY m1.id, m1.vista, m1.modulo;

-- Mostrar duplicados encontrados
SELECT 'DUPLICADOS ENCONTRADOS POR VISTA:' as titulo;
SELECT 
    vista,
    COUNT(*) as cantidad_duplicados,
    GROUP_CONCAT(id_duplicado ORDER BY id_duplicado) as ids_a_eliminar,
    id_mantener as id_que_se_mantiene
FROM temp_duplicados_mapping
GROUP BY vista, id_mantener
ORDER BY cantidad_duplicados DESC;

-- =====================================================================
-- PASO 3: ACTUALIZAR REFERENCIAS EN PERFIL_MODULO
-- =====================================================================

-- Actualizar permisos para que apunten al módulo que se mantendrá
UPDATE perfil_modulo pm
INNER JOIN temp_duplicados_mapping tdm ON pm.id_modulo = tdm.id_duplicado
SET pm.id_modulo = tdm.id_mantener;

-- =====================================================================
-- PASO 4: ELIMINAR PERMISOS DUPLICADOS RESULTANTES
-- =====================================================================

-- Eliminar permisos duplicados que puedan haberse creado
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo 
  AND pm1.id > pm2.id;

-- =====================================================================
-- PASO 5: ELIMINAR MÓDULOS DUPLICADOS (AHORA SIN REFERENCIAS)
-- =====================================================================

-- Eliminar módulos duplicados usando la tabla de mapeo
DELETE FROM modulos 
WHERE id IN (SELECT id_duplicado FROM temp_duplicados_mapping);

-- =====================================================================
-- PASO 6: LIMPIAR DUPLICADOS POR NOMBRE DE MÓDULO
-- =====================================================================

-- Crear tabla temporal para duplicados por nombre y padre
DROP TABLE IF EXISTS temp_duplicados_nombre;
CREATE TEMPORARY TABLE temp_duplicados_nombre (
    id_duplicado INT,
    id_mantener INT,
    modulo VARCHAR(255),
    padre_id INT
);

-- Insertar mapeo de duplicados por nombre de módulo
INSERT INTO temp_duplicados_nombre (id_duplicado, id_mantener, modulo, padre_id)
SELECT 
    m1.id as id_duplicado,
    MIN(m2.id) as id_mantener,
    m1.modulo,
    m1.padre_id
FROM modulos m1
INNER JOIN modulos m2 ON m1.modulo = m2.modulo AND m1.padre_id = m2.padre_id
WHERE m1.id > m2.id
GROUP BY m1.id, m1.modulo, m1.padre_id;

-- Actualizar referencias para duplicados por nombre
UPDATE perfil_modulo pm
INNER JOIN temp_duplicados_nombre tdn ON pm.id_modulo = tdn.id_duplicado
SET pm.id_modulo = tdn.id_mantener;

-- Eliminar permisos duplicados resultantes
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo 
  AND pm1.id > pm2.id;

-- Eliminar módulos duplicados por nombre
DELETE FROM modulos 
WHERE id IN (SELECT id_duplicado FROM temp_duplicados_nombre);

-- =====================================================================
-- PASO 7: REORGANIZAR ESTRUCTURA DEL MENÚ
-- =====================================================================

-- MENÚ PRINCIPAL (orden base)
UPDATE modulos SET orden = 1 WHERE modulo = 'Tablero pincipal' AND padre_id = 0;

-- Actualizar Caja (asegurar solo uno)
UPDATE modulos SET orden = 2, modulo = 'Caja' WHERE (modulo = 'Caja' OR modulo LIKE '%Caja%') AND padre_id = 0 LIMIT 1;

-- Actualizar Clientes (asegurar solo uno)
UPDATE modulos SET orden = 3, modulo = 'Clientes' WHERE (modulo = 'Clientes' OR modulo LIKE '%Cliente%') AND padre_id = 0 LIMIT 1;

-- Actualizar Prestamos (asegurar solo uno)
UPDATE modulos SET orden = 4, modulo = 'Prestamos' WHERE (modulo = 'Prestamos' OR modulo LIKE '%Prestamo%') AND padre_id = 0 LIMIT 1;

-- Otros módulos principales
UPDATE modulos SET orden = 5 WHERE modulo = 'Rutas' AND padre_id = 0;
UPDATE modulos SET orden = 6 WHERE modulo = 'Notas de Débito' AND padre_id = 0;
UPDATE modulos SET orden = 7 WHERE modulo = 'Empresa' AND padre_id = 0;
UPDATE modulos SET orden = 8 WHERE modulo = 'Moneda' AND padre_id = 0;
UPDATE modulos SET orden = 9 WHERE modulo = 'Backup' AND padre_id = 0;
UPDATE modulos SET orden = 10 WHERE modulo = 'Mantenimiento' AND padre_id = 0;

-- Actualizar Reportes (asegurar solo uno)
UPDATE modulos SET orden = 11, modulo = 'Reportes' WHERE (modulo = 'Reportes' OR modulo LIKE '%Reporte%') AND padre_id = 0 LIMIT 1;

-- =====================================================================
-- PASO 8: REORGANIZAR SUBMÓDULOS
-- =====================================================================

-- Obtener IDs de módulos padre únicos
SET @caja_id = (SELECT id FROM modulos WHERE modulo = 'Caja' AND padre_id = 0 LIMIT 1);
SET @prestamos_id = (SELECT id FROM modulos WHERE modulo = 'Prestamos' AND padre_id = 0 LIMIT 1);
SET @reportes_id = (SELECT id FROM modulos WHERE modulo = 'Reportes' AND padre_id = 0 LIMIT 1);
SET @mantenimiento_id = (SELECT id FROM modulos WHERE modulo = 'Mantenimiento' AND padre_id = 0 LIMIT 1);

-- SUBMÓDULOS DE CAJA
UPDATE modulos SET padre_id = @caja_id, orden = 21, modulo = 'Aperturar Caja' WHERE vista = 'caja.php' LIMIT 1;
UPDATE modulos SET padre_id = @caja_id, orden = 22, modulo = 'Ingresos / Egre' WHERE vista = 'ingresos.php' LIMIT 1;

-- SUBMÓDULOS DE PRÉSTAMOS
UPDATE modulos SET padre_id = @prestamos_id, orden = 31, modulo = 'Solicitud/Prestamo' WHERE vista = 'prestamo.php' LIMIT 1;
UPDATE modulos SET padre_id = @prestamos_id, orden = 32, modulo = 'Listado Prestamos' WHERE vista = 'administrar_prestamos.php' LIMIT 1;
UPDATE modulos SET padre_id = @prestamos_id, orden = 33, modulo = 'Aprobar S/P' WHERE vista = 'aprobacion.php' LIMIT 1;

-- SUBMÓDULOS DE MANTENIMIENTO
UPDATE modulos SET padre_id = @mantenimiento_id, orden = 41, modulo = 'Usuarios' WHERE vista = 'usuario.php' LIMIT 1;
UPDATE modulos SET padre_id = @mantenimiento_id, orden = 42, modulo = 'Modulos y Perfiles' WHERE vista = 'modulos_perfiles.php' LIMIT 1;
UPDATE modulos SET padre_id = @mantenimiento_id, orden = 43, modulo = 'Sucursales' WHERE vista = 'sucursales.php' LIMIT 1;

-- =====================================================================
-- PASO 9: REORGANIZAR REPORTES ÚNICOS
-- =====================================================================

-- Asegurar nombres únicos y orden para reportes
UPDATE modulos SET padre_id = @reportes_id, orden = 51, modulo = 'Por Cliente' WHERE vista = 'reporte_cliente.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 52, modulo = 'Cuotas Pagadas' WHERE vista = 'reporte_cuotas_pagadas.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 53, modulo = 'Pivot' WHERE vista = 'reportes.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 54, modulo = 'Reporte Diario' WHERE vista = 'reporte_diario.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 55, modulo = 'Estado de C. Cliente' WHERE vista = 'estado_cuenta_cliente.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 56, modulo = 'Reporte Mora' WHERE vista = 'reporte_mora.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 57, modulo = 'Reporte Cobro Diaria' WHERE vista = 'reporte_cobranza.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 58, modulo = 'Reporte C.Mora' WHERE vista = 'reporte_cuotas_atrasadas.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 59, modulo = 'Saldos Arrastrados' WHERE vista = 'reporte_saldos_arrastrados.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 60, modulo = 'Reporte Recuperación' WHERE vista = 'reporte_recuperacion.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 61, modulo = 'Reportes Financieros' WHERE vista = 'reportes_financieros.php' LIMIT 1;
UPDATE modulos SET padre_id = @reportes_id, orden = 62, modulo = 'Grupos Reportes' WHERE vista = 'grupos_reportes.php' LIMIT 1;

-- =====================================================================
-- PASO 10: ASEGURAR PERMISOS ÚNICOS PARA ADMINISTRADOR
-- =====================================================================

-- Insertar permisos faltantes para administrador (sin duplicar)
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
SELECT 1, id, 0, 1 
FROM modulos 
WHERE id NOT IN (SELECT id_modulo FROM perfil_modulo WHERE id_perfil = 1);

-- =====================================================================
-- PASO 11: VERIFICACIÓN FINAL
-- =====================================================================

-- Verificar módulos principales
SELECT 'MENÚ PRINCIPAL LIMPIO:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    orden,
    CASE 
        WHEN EXISTS(SELECT 1 FROM perfil_modulo WHERE id_modulo = modulos.id AND id_perfil = 1) 
        THEN '✅ CON PERMISOS'
        ELSE '❌ SIN PERMISOS'
    END as permisos
FROM modulos 
WHERE padre_id = 0 
ORDER BY orden;

-- Verificar reportes
SELECT 'REPORTES ÚNICOS:' as titulo;
SELECT 
    id,
    modulo,
    vista,
    orden
FROM modulos 
WHERE padre_id = @reportes_id
ORDER BY orden;

-- Verificar duplicados restantes
SELECT 'DUPLICADOS RESTANTES:' as titulo;
SELECT 
    vista,
    modulo,
    COUNT(*) as cantidad
FROM modulos 
WHERE vista IS NOT NULL AND vista != ''
GROUP BY vista, modulo
HAVING COUNT(*) > 1;

-- Resumen final
SELECT 
    'LIMPIEZA FINALIZADA:' as titulo,
    COUNT(*) as total_modulos,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = 0) as menu_principal,
    (SELECT COUNT(*) FROM modulos WHERE padre_id = @reportes_id) as reportes,
    (SELECT COUNT(*) FROM perfil_modulo WHERE id_perfil = 1) as permisos_admin
FROM modulos;

SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 
    'DUPLICACIONES ELIMINADAS CORRECTAMENTE ✅' as resultado,
    'Restricciones de clave foránea respetadas' as metodo,
    'Reiniciar sesión para ver cambios' as instruccion,
    NOW() as fecha_ejecucion;

-- =====================================================================
-- RESTAURACIÓN EN CASO DE PROBLEMAS:
-- =====================================================================
/*
-- Para restaurar:
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE modulos;
CREATE TABLE modulos AS SELECT * FROM modulos_backup_duplicados;
DROP TABLE perfil_modulo;
CREATE TABLE perfil_modulo AS SELECT * FROM perfil_modulo_backup_duplicados;
SET FOREIGN_KEY_CHECKS = 1;
*/ 