-- ====================================================
-- LIMPIAR DATOS DE PRUEBA ANTERIORES
-- ====================================================

-- Eliminar detalles de préstamos de prueba
DELETE FROM prestamo_detalle WHERE nro_prestamo LIKE '0000000%';

-- Eliminar préstamos de prueba
DELETE FROM prestamo_cabecera WHERE nro_prestamo LIKE '0000000%';

-- Eliminar clientes de prueba
DELETE FROM clientes WHERE cliente_nro_documento IN ('12345678', '87654321', '11223344', '44332211', '55667788', '88776655', '99887766', '22334455');

-- Verificar limpieza
SELECT 'DATOS DE PRUEBA ELIMINADOS' as resultado;
SELECT 'Clientes eliminados:', ROW_COUNT() as cantidad;

-- Mostrar estado actual
SELECT 'ESTADO ACTUAL DE LA BASE DE DATOS' as info;
SELECT COUNT(*) as total_clientes FROM clientes;
SELECT COUNT(*) as total_prestamos FROM prestamo_cabecera;
SELECT COUNT(*) as total_detalle_prestamos FROM prestamo_detalle; 