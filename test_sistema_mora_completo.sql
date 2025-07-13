-- ====================================================
-- PRUEBA COMPLETA DEL SISTEMA DE MORA
-- ====================================================

-- PASO 1: Verificar estructura de tablas
SELECT 'PASO 1: VERIFICANDO ESTRUCTURA DE TABLAS' as paso;
-- Ejecutar: verificar_estructura_tablas.sql

-- PASO 2: Limpiar datos anteriores
SELECT 'PASO 2: LIMPIANDO DATOS ANTERIORES' as paso;
-- Ejecutar: limpiar_datos_prueba.sql

-- PASO 3: Insertar datos de prueba
SELECT 'PASO 3: INSERTANDO DATOS DE PRUEBA' as paso;
-- Ejecutar: insertar_datos_prueba_mora.sql

-- PASO 4: Probar consultas de mora
SELECT 'PASO 4: PROBANDO CONSULTAS DE MORA' as paso;
-- Ejecutar: consulta_mora_simple.sql

-- PASO 5: Crear procedimientos almacenados
SELECT 'PASO 5: CREANDO PROCEDIMIENTOS ALMACENADOS' as paso;
-- Ejecutar: procedimiento_mora.sql

-- ====================================================
-- INSTRUCCIONES DE USO
-- ====================================================

SELECT 'INSTRUCCIONES DE USO:' as instrucciones;

SELECT '1. Ejecutar verificar_estructura_tablas.sql para revisar la estructura' as paso_1;
SELECT '2. Ejecutar limpiar_datos_prueba.sql para limpiar datos anteriores' as paso_2;
SELECT '3. Ejecutar insertar_datos_prueba_mora.sql para insertar datos de prueba' as paso_3;
SELECT '4. Ejecutar consulta_mora_simple.sql para ver los resultados' as paso_4;
SELECT '5. Ejecutar procedimiento_mora.sql para crear los procedimientos' as paso_5;

-- ====================================================
-- CONSULTA RÁPIDA DE VERIFICACIÓN
-- ====================================================

SELECT 'CONSULTA RÁPIDA DE VERIFICACIÓN:' as verificacion;

-- Verificar si hay datos
SELECT 
    (SELECT COUNT(*) FROM clientes WHERE cliente_nro_documento IN ('12345678', '87654321', '11223344', '44332211', '55667788', '88776655', '99887766', '22334455')) as 'Clientes Prueba',
    (SELECT COUNT(*) FROM prestamo_cabecera WHERE nro_prestamo LIKE '0000000%') as 'Préstamos Prueba',
    (SELECT COUNT(*) FROM prestamo_detalle WHERE nro_prestamo LIKE '0000000%') as 'Detalles Prueba';

-- Consulta simple de mora
SELECT 
    'Si hay datos de prueba, esta consulta mostrará clientes en mora:' as info;

SELECT 
    CONCAT(c.cliente_nombres, ' ', c.cliente_apellidos) as 'Cliente',
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
  AND pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha IS NOT NULL 
  AND pd.pdetalle_fecha != '0000-00-00'
  AND pc.nro_prestamo LIKE '0000000%'
ORDER BY 
    CASE 
        WHEN pd.pdetalle_fecha IS NOT NULL AND pd.pdetalle_fecha != '0000-00-00' 
        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
        ELSE 0
    END DESC
LIMIT 10; 