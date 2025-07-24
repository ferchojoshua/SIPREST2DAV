-- ============================================================
-- SCRIPT PARA REINICIAR CONSECUTIVOS DE TODAS LAS SUCURSALES
-- ============================================================
-- 
-- Este script reinicia todos los consecutivos de préstamos, 
-- recibos y vouchers de todas las sucursales a 1.
-- 
-- ⚠️  IMPORTANTE: Ejecutar solo cuando sea necesario reiniciar
-- 
-- CrediCrece - Sistema de Préstamos
-- ============================================================

-- 1. VERIFICAR ESTADO ACTUAL
SELECT 
    '=== ESTADO ACTUAL DE CONSECUTIVOS ===' as info;

SELECT 
    id,
    codigo,
    nombre,
    consecutivo_prestamos,
    consecutivo_recibos, 
    consecutivo_vouchers,
    estado
FROM sucursales 
ORDER BY id;

-- 2. REINICIAR TODOS LOS CONSECUTIVOS A 1
SELECT 
    '=== REINICIANDO CONSECUTIVOS ===' as info;

UPDATE sucursales 
SET consecutivo_prestamos = 1,
    consecutivo_recibos = 1,
    consecutivo_vouchers = 1;

-- 3. VERIFICAR RESULTADO
SELECT 
    '=== ESTADO DESPUÉS DEL REINICIO ===' as info;

SELECT 
    id,
    codigo,
    nombre,
    consecutivo_prestamos,
    consecutivo_recibos, 
    consecutivo_vouchers,
    estado
FROM sucursales 
ORDER BY id;

-- 4. PROBAR GENERACIÓN DE CONSECUTIVOS
SELECT 
    '=== PROBANDO GENERACIÓN DE CONSECUTIVOS ===' as info;

-- Probar para sucursal 1
CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(1);

-- Probar para sucursal 2 (si existe)
CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(2);

-- 5. VERIFICAR VISTA DE CONSECUTIVOS
SELECT 
    '=== VISTA DE CONSECUTIVOS ACTUALIZADA ===' as info;

SELECT * FROM v_consecutivos_sucursales;

SELECT 
    '=== PROCESO COMPLETADO ===' as info,
    NOW() as fecha_reinicio; 