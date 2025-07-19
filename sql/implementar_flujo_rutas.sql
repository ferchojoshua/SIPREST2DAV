-- =====================================================
-- IMPLEMENTACIÓN DEL FLUJO DE RUTAS
-- =====================================================

-- Verificar y actualizar tabla clientes_rutas
ALTER TABLE clientes_rutas
ADD COLUMN IF NOT EXISTS coordenadas POINT COMMENT 'Coordenadas GPS del cliente en esta ruta',
ADD COLUMN IF NOT EXISTS tiempo_estimado INT COMMENT 'Tiempo estimado de visita en minutos',
ADD COLUMN IF NOT EXISTS dia_visita ENUM('lunes','martes','miercoles','jueves','viernes','sabado','domingo') COMMENT 'Día preferido de visita',
ADD COLUMN IF NOT EXISTS franja_horaria VARCHAR(20) COMMENT 'Ej: mañana, tarde, específico',
ADD COLUMN IF NOT EXISTS hora_preferida TIME COMMENT 'Hora específica preferida de visita';

-- Crear tabla de visitas si no existe
CREATE TABLE IF NOT EXISTS visitas_ruta (
    visita_id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_ruta_id INT,
    fecha_visita DATETIME,
    estado_visita ENUM('pendiente','realizada','no_encontrado','reprogramada'),
    resultado VARCHAR(100),
    monto_cobrado DECIMAL(10,2),
    observaciones TEXT,
    coordenadas_visita POINT,
    foto_visita VARCHAR(255),
    usuario_id INT,
    FOREIGN KEY (cliente_ruta_id) REFERENCES clientes_rutas(cliente_ruta_id),
    INDEX idx_fecha (fecha_visita),
    INDEX idx_estado (estado_visita)
);

-- Crear tabla de alertas si no existe
CREATE TABLE IF NOT EXISTS alertas_ruta (
    alerta_id INT PRIMARY KEY AUTO_INCREMENT,
    ruta_id INT,
    tipo_alerta ENUM('retraso','meta_no_cumplida','cliente_no_encontrado','zona_riesgo'),
    mensaje TEXT,
    fecha_alerta DATETIME,
    estado ENUM('pendiente','atendida','ignorada') DEFAULT 'pendiente',
    FOREIGN KEY (ruta_id) REFERENCES rutas(ruta_id),
    INDEX idx_fecha (fecha_alerta),
    INDEX idx_estado (estado)
);

-- Vista para métricas de rutas
CREATE OR REPLACE VIEW v_metricas_rutas AS
SELECT 
    r.ruta_id,
    r.ruta_nombre,
    COUNT(DISTINCT cr.cliente_id) as total_clientes,
    COUNT(DISTINCT v.visita_id) as total_visitas,
    SUM(v.monto_cobrado) as total_cobrado,
    AVG(CASE WHEN v.estado_visita = 'realizada' THEN 1 ELSE 0 END) * 100 as efectividad_visitas,
    COUNT(DISTINCT CASE WHEN v.estado_visita = 'no_encontrado' THEN v.visita_id END) as clientes_no_encontrados
FROM rutas r
LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
LEFT JOIN visitas_ruta v ON cr.cliente_ruta_id = v.cliente_ruta_id
GROUP BY r.ruta_id, r.ruta_nombre;

-- Procedimiento para obtener estadísticas de ruta
DELIMITER $$
CREATE OR REPLACE PROCEDURE SP_ESTADISTICAS_RUTA(IN p_ruta_id INT)
BEGIN
    SELECT 
        r.ruta_nombre,
        r.ruta_codigo,
        COUNT(DISTINCT cr.cliente_id) as total_clientes,
        COUNT(DISTINCT CASE WHEN cr.estado = 'activo' THEN cr.cliente_id END) as clientes_activos,
        COUNT(DISTINCT CASE WHEN cr.estado = 'inactivo' THEN cr.cliente_id END) as clientes_inactivos,
        COUNT(DISTINCT pc.nro_prestamo) as prestamos_activos,
        COALESCE(SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN pd.pdetalle_saldo_cuota ELSE 0 END), 0) as saldo_total_pendiente,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 1 END) as cuotas_vencidas,
        COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY) THEN 1 END) as cuotas_proximas_7_dias,
        COUNT(DISTINCT ur.usuario_id) as usuarios_asignados
    FROM rutas r
    LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id
    LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
    LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_estado = 'VIGENTE'
    LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
    LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
    WHERE r.ruta_id = p_ruta_id
    GROUP BY r.ruta_id, r.ruta_nombre, r.ruta_codigo;
END$$
DELIMITER ;

-- Procedimiento para optimizar ruta
DELIMITER $$
CREATE OR REPLACE PROCEDURE SP_OPTIMIZAR_RUTA(IN p_ruta_id INT)
BEGIN
    -- Aquí implementaremos el algoritmo TSP más adelante
    -- Por ahora solo ordenamos por proximidad a la sucursal
    UPDATE clientes_rutas cr1
    JOIN (
        SELECT 
            cr.cliente_ruta_id,
            @rownum := @rownum + 1 as nuevo_orden
        FROM clientes_rutas cr
        CROSS JOIN (SELECT @rownum := 0) r
        WHERE cr.ruta_id = p_ruta_id
        ORDER BY cr.orden_visita
    ) cr2 ON cr1.cliente_ruta_id = cr2.cliente_ruta_id
    SET cr1.orden_visita = cr2.nuevo_orden
    WHERE cr1.ruta_id = p_ruta_id;
END$$
DELIMITER ;

-- Procedimiento para registrar visita
DELIMITER $$
CREATE OR REPLACE PROCEDURE SP_REGISTRAR_VISITA(
    IN p_cliente_ruta_id INT,
    IN p_estado_visita ENUM('realizada','no_encontrado','reprogramada'),
    IN p_resultado VARCHAR(100),
    IN p_monto_cobrado DECIMAL(10,2),
    IN p_observaciones TEXT,
    IN p_lat DECIMAL(10,8),
    IN p_lng DECIMAL(10,8),
    IN p_foto_visita VARCHAR(255),
    IN p_usuario_id INT
)
BEGIN
    INSERT INTO visitas_ruta (
        cliente_ruta_id,
        fecha_visita,
        estado_visita,
        resultado,
        monto_cobrado,
        observaciones,
        coordenadas_visita,
        foto_visita,
        usuario_id
    ) VALUES (
        p_cliente_ruta_id,
        NOW(),
        p_estado_visita,
        p_resultado,
        p_monto_cobrado,
        p_observaciones,
        POINT(p_lat, p_lng),
        p_foto_visita,
        p_usuario_id
    );

    -- Generar alerta si es necesario
    IF p_estado_visita = 'no_encontrado' THEN
        INSERT INTO alertas_ruta (
            ruta_id,
            tipo_alerta,
            mensaje,
            fecha_alerta
        ) SELECT 
            cr.ruta_id,
            'cliente_no_encontrado',
            CONCAT('Cliente no encontrado en visita: ', c.cliente_nombres),
            NOW()
        FROM clientes_rutas cr
        JOIN clientes c ON cr.cliente_id = c.cliente_id
        WHERE cr.cliente_ruta_id = p_cliente_ruta_id;
    END IF;
END$$
DELIMITER ;

-- Índices adicionales para optimización
ALTER TABLE prestamo_cabecera ADD INDEX idx_estado_cliente (cliente_id, pres_estado);
ALTER TABLE prestamo_detalle ADD INDEX idx_estado_fecha (pdetalle_estado_cuota, pdetalle_fecha);
ALTER TABLE usuarios_rutas ADD INDEX idx_usuario_estado (usuario_id, estado);

-- Verificación final
SELECT 'Verificación de tablas creadas:' as mensaje;
SHOW TABLES LIKE '%ruta%';

SELECT 'Verificación de procedimientos creados:' as mensaje;
SHOW PROCEDURE STATUS WHERE Db = DATABASE() AND Name LIKE '%RUTA%';

SELECT 'Verificación de vistas creadas:' as mensaje;
SHOW FULL TABLES WHERE Table_type = 'VIEW'; 