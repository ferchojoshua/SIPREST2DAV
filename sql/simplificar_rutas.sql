-- =====================================================
-- SCRIPT PARA SIMPLIFICAR MÓDULO DE RUTAS
-- =====================================================

-- Eliminar tablas innecesarias
DROP TABLE IF EXISTS visitas_ruta;
DROP TABLE IF EXISTS alertas_ruta;
DROP VIEW IF EXISTS v_metricas_rutas;

-- Eliminar campos innecesarios de clientes_rutas
ALTER TABLE clientes_rutas
DROP COLUMN IF EXISTS coordenadas,
DROP COLUMN IF EXISTS tiempo_estimado,
DROP COLUMN IF EXISTS dia_visita,
DROP COLUMN IF EXISTS franja_horaria,
DROP COLUMN IF EXISTS hora_preferida;

-- Actualizar la vista de cobros por ruta
CREATE OR REPLACE VIEW v_cobros_ruta AS 
SELECT 
    r.ruta_id,
    r.ruta_nombre,
    r.ruta_codigo,
    COUNT(DISTINCT cr.cliente_id) as total_clientes,
    COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
    SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END) as total_por_cobrar,
    COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
    u.usuario as cobrador_asignado
FROM rutas r
LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
LEFT JOIN prestamo_cabecera pc ON cr.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_codigo, u.usuario; 