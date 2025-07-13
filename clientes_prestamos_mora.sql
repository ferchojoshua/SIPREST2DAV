-- =====================================================
-- SCRIPT: CLIENTES CON PRÉSTAMOS EN MORA
-- =====================================================
-- Este script obtiene información detallada de clientes
-- que tienen cuotas vencidas sin pagar

-- =====================================================
-- CONSULTA 1: CLIENTES CON CUOTAS EN MORA
-- =====================================================

SELECT 
    c.cliente_id,
    c.cliente_nombres,
    c.cliente_dni,
    c.cliente_cel,
    c.cliente_direccion,
    pc.nro_prestamo,
    pd.pdetalle_nro_cuota,
    DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as fecha_vencimiento,
    pd.pdetalle_monto_cuota,
    pd.pdetalle_saldo_cuota,
    DATEDIFF(CURDATE(), pd.pdetalle_fecha) as dias_mora,
    CASE 
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 30 THEN 'MORA LEVE (1-30 días)'
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 60 THEN 'MORA MODERADA (31-60 días)'
        WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 90 THEN 'MORA ALTA (61-90 días)'
        ELSE 'MORA CRÍTICA (+90 días)'
    END as nivel_mora,
    pc.pres_monto as monto_prestamo,
    pc.pres_monto_total as total_prestamo,
    fp.fpago_descripcion as forma_pago,
    u.usuario as gestor
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.pres_aprobacion = 'aprobado'
ORDER BY dias_mora DESC, c.cliente_nombres;

-- =====================================================
-- CONSULTA 2: RESUMEN POR CLIENTE EN MORA
-- =====================================================

SELECT 
    c.cliente_id,
    c.cliente_nombres,
    c.cliente_dni,
    c.cliente_cel,
    COUNT(pd.pdetalle_id) as total_cuotas_vencidas,
    SUM(pd.pdetalle_monto_cuota) as monto_total_vencido,
    MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_maxima,
    MIN(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_minima,
    AVG(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_promedio,
    COUNT(DISTINCT pc.nro_prestamo) as prestamos_en_mora,
    GROUP_CONCAT(DISTINCT pc.nro_prestamo ORDER BY pc.nro_prestamo) as numeros_prestamos
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.pres_aprobacion = 'aprobado'
GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_dni, c.cliente_cel
ORDER BY dias_mora_maxima DESC, monto_total_vencido DESC;

-- =====================================================
-- CONSULTA 3: ESTADÍSTICAS GENERALES DE MORA
-- =====================================================

SELECT 
    'ESTADÍSTICAS GENERALES DE MORA' as titulo,
    COUNT(DISTINCT c.cliente_id) as clientes_con_mora,
    COUNT(DISTINCT pc.nro_prestamo) as prestamos_con_mora,
    COUNT(pd.pdetalle_id) as total_cuotas_vencidas,
    ROUND(SUM(pd.pdetalle_monto_cuota), 2) as monto_total_en_mora,
    ROUND(AVG(DATEDIFF(CURDATE(), pd.pdetalle_fecha)), 0) as dias_mora_promedio,
    COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) <= 30 THEN 1 END) as mora_leve,
    COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) BETWEEN 31 AND 60 THEN 1 END) as mora_moderada,
    COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) BETWEEN 61 AND 90 THEN 1 END) as mora_alta,
    COUNT(CASE WHEN DATEDIFF(CURDATE(), pd.pdetalle_fecha) > 90 THEN 1 END) as mora_critica
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.pres_aprobacion = 'aprobado';

-- =====================================================
-- CONSULTA 4: TOP 10 CLIENTES CON MAYOR MORA
-- =====================================================

SELECT 
    'TOP 10 CLIENTES CON MAYOR MORA' as titulo,
    c.cliente_nombres,
    c.cliente_dni,
    c.cliente_cel,
    COUNT(pd.pdetalle_id) as cuotas_vencidas,
    ROUND(SUM(pd.pdetalle_monto_cuota), 2) as monto_vencido,
    MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_max,
    GROUP_CONCAT(DISTINCT pc.nro_prestamo) as prestamos
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.pres_aprobacion = 'aprobado'
GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_dni, c.cliente_cel
ORDER BY monto_vencido DESC
LIMIT 10;

-- =====================================================
-- CONSULTA 5: CLIENTES PARA CONTACTAR HOY (PRIORIDAD)
-- =====================================================

SELECT 
    'CLIENTES PRIORITARIOS PARA CONTACTAR HOY' as titulo,
    c.cliente_nombres,
    c.cliente_dni,
    c.cliente_cel,
    pc.nro_prestamo,
    COUNT(pd.pdetalle_id) as cuotas_vencidas,
    ROUND(SUM(pd.pdetalle_monto_cuota), 2) as monto_total_vencido,
    MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as dias_mora_max,
    CASE 
        WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 90 THEN '🔴 URGENTE - LLAMAR YA'
        WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 60 THEN '🟡 PRIORITARIO - LLAMAR HOY'
        WHEN MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) > 30 THEN '🟢 IMPORTANTE - LLAMAR PRONTO'
        ELSE '🔵 RECORDATORIO - LLAMAR ESTA SEMANA'
    END as accion_requerida
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.pres_aprobacion = 'aprobado'
GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_dni, c.cliente_cel, pc.nro_prestamo
HAVING MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) >= 15  -- Solo mostrar con más de 15 días de mora
ORDER BY dias_mora_max DESC, monto_total_vencido DESC;

-- =====================================================
-- CONSULTA 6: EXPORTAR PARA EXCEL (FORMATO SIMPLE)
-- =====================================================

SELECT 
    'EXPORTACIÓN PARA EXCEL' as titulo,
    c.cliente_nombres as 'Nombre Cliente',
    c.cliente_dni as 'Cédula',
    c.cliente_cel as 'Teléfono',
    pc.nro_prestamo as 'Nro Préstamo',
    COUNT(pd.pdetalle_id) as 'Cuotas Vencidas',
    ROUND(SUM(pd.pdetalle_monto_cuota), 2) as 'Monto Vencido',
    MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) as 'Días Mora',
    DATE_FORMAT(MIN(pd.pdetalle_fecha), '%d/%m/%Y') as 'Primera Cuota Vencida',
    DATE_FORMAT(MAX(pd.pdetalle_fecha), '%d/%m/%Y') as 'Última Cuota Vencida'
FROM prestamo_detalle pd
INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
WHERE pd.pdetalle_estado_cuota = 'pendiente'
  AND pd.pdetalle_fecha < CURDATE()
  AND pc.pres_aprobacion = 'aprobado'
GROUP BY c.cliente_id, c.cliente_nombres, c.cliente_dni, c.cliente_cel, pc.nro_prestamo
ORDER BY MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) DESC; 