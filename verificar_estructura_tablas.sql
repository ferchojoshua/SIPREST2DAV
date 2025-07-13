-- ====================================================
-- VERIFICAR ESTRUCTURA DE TABLAS PARA SISTEMA DE MORA
-- ====================================================

-- Verificar estructura de tabla clientes
SELECT 'ESTRUCTURA TABLA CLIENTES' as tabla;
DESCRIBE clientes;

-- Verificar estructura de tabla prestamo_cabecera
SELECT 'ESTRUCTURA TABLA PRESTAMO_CABECERA' as tabla;
DESCRIBE prestamo_cabecera;

-- Verificar estructura de tabla prestamo_detalle
SELECT 'ESTRUCTURA TABLA PRESTAMO_DETALLE' as tabla;
DESCRIBE prestamo_detalle;

-- Verificar algunos datos existentes
SELECT 'DATOS EXISTENTES EN CLIENTES' as info;
SELECT cliente_id, cliente_nombres, cliente_apellidos, cliente_estatus, cliente_fecha_registro 
FROM clientes 
LIMIT 5;

SELECT 'DATOS EXISTENTES EN PRESTAMO_CABECERA' as info;
SELECT prestamo_id, nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, pres_aprobacion 
FROM prestamo_cabecera 
LIMIT 5;

SELECT 'DATOS EXISTENTES EN PRESTAMO_DETALLE' as info;
SELECT pdetalle_id, nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_fecha_pago, pdetalle_estado_cuota 
FROM prestamo_detalle 
LIMIT 5;

-- Verificar fechas nulas o problemáticas
SELECT 'PROBLEMAS CON FECHAS' as problema;
SELECT COUNT(*) as total_prestamos FROM prestamo_cabecera;
SELECT COUNT(*) as prestamos_sin_fecha_registro FROM prestamo_cabecera WHERE fecha_registro IS NULL OR fecha_registro = '0000-00-00';
SELECT COUNT(*) as prestamos_sin_fecha_vencimiento FROM prestamo_cabecera WHERE fecha_vencimiento IS NULL OR fecha_vencimiento = '0000-00-00';

SELECT COUNT(*) as total_detalle_prestamos FROM prestamo_detalle;
SELECT COUNT(*) as cuotas_sin_fecha_vencimiento FROM prestamo_detalle WHERE pdetalle_fecha IS NULL OR pdetalle_fecha = '0000-00-00'; 