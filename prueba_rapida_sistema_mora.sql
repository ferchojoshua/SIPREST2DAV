-- ====================================================
-- PRUEBA RÁPIDA DEL SISTEMA DE MORA - CORREGIDO
-- ====================================================

-- PASO 1: Verificar estructura de la tabla clientes
SELECT 'PASO 1: VERIFICANDO ESTRUCTURA DE CLIENTES' as paso;
DESCRIBE clientes;

-- PASO 2: Verificar si existen datos de prueba anteriores
SELECT 'PASO 2: VERIFICANDO DATOS ANTERIORES' as paso;
SELECT COUNT(*) as clientes_prueba FROM clientes WHERE cliente_nro_documento IN ('12345678', '87654321', '11223344');
SELECT COUNT(*) as prestamos_prueba FROM prestamo_cabecera WHERE nro_prestamo LIKE '0000000%';

-- PASO 3: Insertar un cliente de prueba simple
SELECT 'PASO 3: INSERTANDO CLIENTE DE PRUEBA' as paso;
INSERT INTO clientes (cliente_nombres, cliente_nro_documento, cliente_celular, cliente_email, cliente_direccion, cliente_estatus, cliente_fecha_registro) 
VALUES ('Juan Perez Lopez', '99999999', '70000000', 'juan.perez@test.com', 'Calle Test #123', 1, '2024-01-01');

-- PASO 4: Insertar prestamo de prueba
SELECT 'PASO 4: INSERTANDO PRESTAMO DE PRUEBA' as paso;
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) 
VALUES ('TEST001', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '99999999'), '2024-01-01', '2024-12-31', 1000.00, 12.00, 12, 'aprobado', 'Préstamo de prueba');

-- PASO 5: Insertar cuota vencida
SELECT 'PASO 5: INSERTANDO CUOTA VENCIDA' as paso;
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) 
VALUES ('TEST001', 1, '2024-01-15', 100.00, 'pendiente', NULL, 0.00);

-- PASO 6: Probar consulta de mora
SELECT 'PASO 6: PROBANDO CONSULTA DE MORA' as paso;
SELECT 
    c.cliente_nombres as 'Cliente',
    pc.nro_prestamo as 'Préstamo',
    pd.pdetalle_nro_cuota as 'Cuota',
    DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as 'Fecha Vencimiento',
    pd.pdetalle_monto_cuota as 'Monto',
    DATEDIFF(CURDATE(), pd.pdetalle_fecha) as 'Días Mora',
    CASE 
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 30 THEN '🟡 MORA LEVE'
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 60 THEN '🟠 MORA MODERADA'
        ELSE '🔴 MORA CRÍTICA'
    END as 'Estado'
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.nro_prestamo = 'TEST001';

-- PASO 7: Limpiar datos de prueba
SELECT 'PASO 7: LIMPIANDO DATOS DE PRUEBA' as paso;
DELETE FROM prestamo_detalle WHERE nro_prestamo = 'TEST001';
DELETE FROM prestamo_cabecera WHERE nro_prestamo = 'TEST001';
DELETE FROM clientes WHERE cliente_nro_documento = '99999999';

-- PASO 8: Verificar limpieza
SELECT 'PASO 8: VERIFICANDO LIMPIEZA' as paso;
SELECT COUNT(*) as clientes_test FROM clientes WHERE cliente_nro_documento = '99999999';

-- MENSAJE FINAL
SELECT 'SISTEMA DE MORA FUNCIONANDO CORRECTAMENTE' as resultado;
SELECT 'Puedes proceder a ejecutar los archivos corregidos:' as instruccion;
SELECT '1. insertar_datos_prueba_mora_corregido.sql' as archivo_1;
SELECT '2. consulta_mora_simple_corregida.sql' as archivo_2;
SELECT '3. procedimiento_mora_corregido.sql' as archivo_3; 