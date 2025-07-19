-- =====================================================================
-- SCRIPT DE EMERGENCIA - RESTAURAR FUNCIONALIDAD BÁSICA
-- Para que el usuario pueda cerrar sesión y continuar trabajando
-- =====================================================================

SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
-- PASO 1: RESTAURAR PERMISOS BÁSICOS PARA ADMINISTRADOR
-- =====================================================================

-- Asegurar que administrador tenga acceso a módulos esenciales
INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
VALUES 
(1, 1, 1, 1),   -- Dashboard principal
(1, 39, 0, 1),  -- Caja
(1, 24, 0, 1),  -- Clientes
(1, 29, 0, 1),  -- Prestamos
(1, 10, 0, 1),  -- Reportes
(1, 14, 0, 1);  -- Mantenimiento

-- =====================================================================
-- PASO 2: LIMPIAR DUPLICADOS EN PERFIL_MODULO (CAMPO CORRECTO)
-- =====================================================================

-- Eliminar duplicados usando el campo correcto idperfil_modulo
DELETE pm1 FROM perfil_modulo pm1
INNER JOIN perfil_modulo pm2 
WHERE pm1.idperfil_modulo > pm2.idperfil_modulo 
  AND pm1.id_perfil = pm2.id_perfil 
  AND pm1.id_modulo = pm2.id_modulo;

-- =====================================================================
-- PASO 3: LIMPIAR DUPLICADOS BÁSICOS EN MÓDULOS
-- =====================================================================

-- Eliminar módulos duplicados por vista (conservar el de menor ID)
DELETE m1 FROM modulos m1
INNER JOIN modulos m2 
WHERE m1.id > m2.id 
  AND m1.vista = m2.vista 
  AND m1.vista IS NOT NULL 
  AND m1.vista != ''
  AND NOT EXISTS (
    SELECT 1 FROM perfil_modulo pm WHERE pm.id_modulo = m1.id
  );

-- =====================================================================
-- PASO 4: REORGANIZACIÓN RÁPIDA DEL MENÚ PRINCIPAL
-- =====================================================================

-- Asegurar solo un módulo de cada tipo principal
UPDATE modulos SET 
  modulo = 'Tablero pincipal', 
  orden = 1 
WHERE id = 1;

UPDATE modulos SET 
  modulo = 'Caja', 
  padre_id = 0, 
  orden = 2 
WHERE id = 39 AND padre_id = 0;

UPDATE modulos SET 
  modulo = 'Clientes', 
  padre_id = 0, 
  orden = 3 
WHERE id = 24 AND padre_id = 0;

UPDATE modulos SET 
  modulo = 'Prestamos', 
  padre_id = 0, 
  orden = 4 
WHERE id = 29 AND padre_id = 0;

UPDATE modulos SET 
  modulo = 'Reportes', 
  padre_id = 0, 
  orden = 5 
WHERE id = 10 AND padre_id = 0;

UPDATE modulos SET 
  modulo = 'Mantenimiento', 
  padre_id = 0, 
  orden = 6 
WHERE id = 14 AND padre_id = 0;

-- =====================================================================
-- PASO 5: ELIMINAR DUPLICADOS SIMPLES DE REPORTES
-- =====================================================================

-- Identificar y mantener solo un reporte de cada vista
DELETE FROM modulos 
WHERE padre_id = 10 
  AND id NOT IN (
    SELECT * FROM (
      SELECT MIN(id) 
      FROM modulos 
      WHERE padre_id = 10 
        AND vista IS NOT NULL 
        AND vista != ''
      GROUP BY vista
    ) AS temp
  );

-- =====================================================================
-- PASO 6: VERIFICAR FUNCIONALIDAD MÍNIMA
-- =====================================================================

-- Verificar menú principal
SELECT 'MENÚ PRINCIPAL RESTAURADO:' as titulo;
SELECT id, modulo, padre_id, vista, orden 
FROM modulos 
WHERE padre_id = 0 
ORDER BY orden;

-- Verificar permisos de administrador
SELECT 'PERMISOS ADMINISTRADOR:' as titulo;
SELECT COUNT(*) as total_permisos 
FROM perfil_modulo 
WHERE id_perfil = 1;

-- Verificar reportes únicos
SELECT 'REPORTES ÚNICOS:' as titulo;
SELECT id, modulo, vista 
FROM modulos 
WHERE padre_id = 10 
ORDER BY id;

SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;

-- =====================================================================
-- MENSAJE FINAL
-- =====================================================================
SELECT 
    'FUNCIONALIDAD BÁSICA RESTAURADA ✅' as resultado,
    'Ya puedes cerrar sesión y volver a entrar' as instruccion,
    'Ejecutar script completo después' as siguiente_paso,
    NOW() as fecha_ejecucion; 