-- =====================================================
-- CONSULTA SIMPLE: CLIENTES EN MORA PARA USO DIARIO
-- =====================================================
-- Consulta rápida y simple para ver quién debe pagar

SELECT 
    CONCAT(c.cliente_nombres, ' ', c.cliente_apellidos) as 'Cliente',
    c.cliente_celular as 'Teléfono',
    pc.nro_prestamo as 'Préstamo',
    pd.pdetalle_nro_cuota as 'Cuota',
    DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as 'Fecha Vencimiento',
    pd.pdetalle_monto_cuota as 'Monto',
    CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END as 'Días Mora',
    CASE 
        WHEN pd.pdetalle_estado_cuota = 'pagado' THEN '🟢 PAGADO'
        WHEN pd.pdetalle_fecha IS NULL OR pd.pdetalle_fecha = '0000-00-00' THEN '⚫ SIN FECHA'
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 0 THEN '🟢 AL DÍA'
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 30 THEN '🟡 MORA LEVE'
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 60 THEN '🟠 MORA MODERADA'
        ELSE '🔴 MORA CRÍTICA'
    END as 'Estado'
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pc.pres_aprobacion = 'aprobado'
  AND c.cliente_estatus = 1
  AND (
    -- Mostrar cuotas no pagadas con fechas válidas
    (pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00')
  )
ORDER BY 
    CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END DESC; 

-- =====================================================
-- RESUMEN POR CLIENTE
-- =====================================================

SELECT 
    '=== RESUMEN POR CLIENTE ===' as separador;

SELECT 
    CONCAT(c.cliente_nombres, ' ', c.cliente_apellidos) as 'Cliente',
    c.cliente_celular as 'Teléfono',
    COUNT(pd.pdetalle_nro_cuota) as 'Cuotas Pendientes',
    SUM(pd.pdetalle_monto_cuota) as 'Monto Total Pendiente',
    MAX(CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END) as 'Mayor Días Mora',
    CASE 
        WHEN MAX(CASE 
            WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
            THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
            ELSE 0
        END) <= 0 THEN '🟢 AL DÍA'
        WHEN MAX(CASE 
            WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
            THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
            ELSE 0
        END) <= 30 THEN '🟡 MORA LEVE'
        WHEN MAX(CASE 
            WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
            THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
            ELSE 0
        END) <= 60 THEN '🟠 MORA MODERADA'
        ELSE '🔴 MORA CRÍTICA'
    END as 'Estado Cliente'
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pc.pres_aprobacion = 'aprobado'
  AND c.cliente_estatus = 1
  AND pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha IS NOT NULL 
  AND pd.pdetalle_fecha != '0000-00-00'
GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_apellidos, c.cliente_celular
ORDER BY 
    MAX(CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END) DESC;

-- =====================================================
-- ESTADÍSTICAS GENERALES
-- =====================================================

SELECT 
    '=== ESTADÍSTICAS GENERALES ===' as separador;

SELECT 
    COUNT(DISTINCT c.cliente_id) as 'Total Clientes con Mora',
    COUNT(pd.pdetalle_nro_cuota) as 'Total Cuotas Pendientes',
    SUM(pd.pdetalle_monto_cuota) as 'Monto Total Pendiente',
    AVG(CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END) as 'Promedio Días Mora'
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pc.pres_aprobacion = 'aprobado'
  AND c.cliente_estatus = 1
  AND pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha IS NOT NULL 
  AND pd.pdetalle_fecha != '0000-00-00'
  AND pd.pdetalle_fecha < CURDATE();

-- =====================================================
-- VERIFICACIÓN DE DATOS INSERTADOS (PRUEBA)
-- =====================================================

SELECT 
    '=== VERIFICACIÓN DE DATOS DE PRUEBA ===' as separador;

-- Verificar los datos de prueba insertados
SELECT 
    CONCAT(c.cliente_nombres, ' ', c.cliente_apellidos) as 'Cliente',
    pc.nro_prestamo as 'Préstamo',
    pd.pdetalle_nro_cuota as 'Cuota',
    DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as 'Fecha Vencimiento',
    pd.pdetalle_monto_cuota as 'Monto',
    pd.pdetalle_estado_cuota as 'Estado',
    CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END as 'Días Mora'
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pc.nro_prestamo LIKE '0000000%'
ORDER BY pc.nro_prestamo, pd.pdetalle_nro_cuota; 