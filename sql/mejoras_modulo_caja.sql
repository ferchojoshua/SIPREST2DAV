-- =====================================================
-- MEJORAS MODULO DE CAJA - SIPREST
-- Developer: Senior Level
-- Fecha: $(date)
-- =====================================================

-- =====================================================
-- 1. TABLA DE PERMISOS ESPECÍFICOS DE CAJA
-- =====================================================
CREATE TABLE IF NOT EXISTS `caja_permisos` (
  `permiso_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `puede_abrir_caja` tinyint(1) DEFAULT 0 COMMENT 'Puede abrir cajas',
  `puede_cerrar_caja` tinyint(1) DEFAULT 0 COMMENT 'Puede cerrar cajas',
  `puede_ver_reportes` tinyint(1) DEFAULT 1 COMMENT 'Puede ver reportes de caja',
  `puede_gestionar_movimientos` tinyint(1) DEFAULT 0 COMMENT 'Puede crear ingresos/egresos',
  `puede_supervisar` tinyint(1) DEFAULT 0 COMMENT 'Puede supervisar todas las cajas',
  `limite_monto_apertura` decimal(15,2) DEFAULT NULL COMMENT 'Monto máximo para apertura',
  `limite_monto_movimiento` decimal(15,2) DEFAULT NULL COMMENT 'Monto máximo por movimiento',
  `requiere_autorizacion` tinyint(1) DEFAULT 0 COMMENT 'Requiere autorización para acciones',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `usuario_creacion` int(11) NOT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  PRIMARY KEY (`permiso_id`),
  UNIQUE KEY `uk_usuario_permisos` (`id_usuario`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `fk_caja_permisos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Permisos específicos para operaciones de caja por usuario';

-- =====================================================
-- 2. TABLA DE AUDITORÍA DE CAJA
-- =====================================================
CREATE TABLE IF NOT EXISTS `caja_auditoria` (
  `auditoria_id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `accion` enum('APERTURA','CIERRE','MOVIMIENTO','CONSULTA','MODIFICACION','AUTORIZACION') NOT NULL,
  `descripcion` text NOT NULL COMMENT 'Descripción detallada de la acción',
  `datos_anteriores` text DEFAULT NULL COMMENT 'JSON con datos antes del cambio',
  `datos_nuevos` text DEFAULT NULL COMMENT 'JSON con datos después del cambio',
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `monto_involucrado` decimal(15,2) DEFAULT NULL,
  `resultado` enum('EXITOSO','FALLIDO','PENDIENTE') DEFAULT 'EXITOSO',
  `observaciones` text DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`auditoria_id`),
  KEY `idx_caja` (`caja_id`),
  KEY `idx_usuario` (`id_usuario`),
  KEY `idx_accion` (`accion`),
  KEY `idx_fecha` (`fecha_registro`),
  KEY `idx_resultado` (`resultado`),
  CONSTRAINT `fk_auditoria_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  CONSTRAINT `fk_auditoria_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Registro completo de auditoría para todas las operaciones de caja';

-- =====================================================
-- 3. TABLA DE CAJAS MÚLTIPLES (MULTI-PUNTO)
-- =====================================================
CREATE TABLE IF NOT EXISTS `cajas_sucursales` (
  `caja_sucursal_id` int(11) NOT NULL AUTO_INCREMENT,
  `sucursal_id` int(11) NOT NULL,
  `nombre_caja` varchar(100) NOT NULL COMMENT 'Ej: Caja Principal, Caja 2, etc.',
  `codigo_caja` varchar(20) NOT NULL COMMENT 'Código único de la caja',
  `descripcion` text DEFAULT NULL,
  `monto_limite` decimal(15,2) DEFAULT NULL COMMENT 'Monto límite para alertas',
  `usuario_responsable` int(11) DEFAULT NULL COMMENT 'Usuario responsable principal',
  `estado` enum('activa','inactiva','mantenimiento') DEFAULT 'activa',
  `tipo_caja` enum('principal','secundaria','temporal') DEFAULT 'principal',
  `ubicacion_fisica` varchar(200) DEFAULT NULL,
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `usuario_creacion` int(11) NOT NULL,
  PRIMARY KEY (`caja_sucursal_id`),
  UNIQUE KEY `uk_codigo_caja` (`codigo_caja`),
  UNIQUE KEY `uk_sucursal_nombre` (`sucursal_id`, `nombre_caja`),
  KEY `idx_estado` (`estado`),
  KEY `idx_tipo` (`tipo_caja`),
  CONSTRAINT `fk_caja_sucursal` FOREIGN KEY (`sucursal_id`) REFERENCES `sucursales` (`id`),
  CONSTRAINT `fk_caja_responsable` FOREIGN KEY (`usuario_responsable`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Configuración de múltiples cajas por sucursal';

-- =====================================================
-- 4. TABLA DE ALERTAS DEL SISTEMA DE CAJA
-- =====================================================
CREATE TABLE IF NOT EXISTS `caja_alertas` (
  `alerta_id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `tipo_alerta` enum('SALDO_BAJO','TIEMPO_PROLONGADO','ALTA_ACTIVIDAD','DISCREPANCIA','LIMITE_EXCEDIDO','SISTEMA') NOT NULL,
  `nivel_criticidad` enum('INFO','WARNING','CRITICAL','URGENT') DEFAULT 'INFO',
  `titulo` varchar(200) NOT NULL,
  `mensaje` text NOT NULL,
  `datos_adicionales` text DEFAULT NULL COMMENT 'JSON con datos contextuales',
  `usuario_notificado` int(11) DEFAULT NULL,
  `fecha_generacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_lectura` datetime DEFAULT NULL,
  `fecha_resolucion` datetime DEFAULT NULL,
  `estado` enum('pendiente','leida','resuelta','ignorada') DEFAULT 'pendiente',
  `acciones_tomadas` text DEFAULT NULL,
  `usuario_resolucion` int(11) DEFAULT NULL,
  PRIMARY KEY (`alerta_id`),
  KEY `idx_caja` (`caja_id`),
  KEY `idx_tipo` (`tipo_alerta`),
  KEY `idx_nivel` (`nivel_criticidad`),
  KEY `idx_estado` (`estado`),
  KEY `idx_fecha` (`fecha_generacion`),
  CONSTRAINT `fk_alerta_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  CONSTRAINT `fk_alerta_usuario` FOREIGN KEY (`usuario_notificado`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Sistema de alertas y notificaciones para el módulo de caja';

-- =====================================================
-- 5. TABLA DE CONTEOS FÍSICOS Y CONCILIACIÓN
-- =====================================================
CREATE TABLE IF NOT EXISTS `caja_conteos_fisicos` (
  `conteo_id` int(11) NOT NULL AUTO_INCREMENT,
  `caja_id` int(11) NOT NULL,
  `usuario_conteo` int(11) NOT NULL,
  `tipo_conteo` enum('APERTURA','CIERRE','INTERMEDIO','SUPERVISION') NOT NULL,
  `saldo_sistema` decimal(15,2) NOT NULL COMMENT 'Saldo según el sistema',
  `saldo_fisico` decimal(15,2) NOT NULL COMMENT 'Saldo contado físicamente',
  `diferencia` decimal(15,2) GENERATED ALWAYS AS (saldo_fisico - saldo_sistema) STORED,
  `denominaciones` text DEFAULT NULL COMMENT 'JSON con detalle de billetes y monedas',
  `observaciones` text DEFAULT NULL,
  `foto_evidencia` varchar(500) DEFAULT NULL COMMENT 'Ruta de foto del conteo',
  `requiere_justificacion` tinyint(1) GENERATED ALWAYS AS (ABS(diferencia) > 0) STORED,
  `justificacion` text DEFAULT NULL,
  `supervisor_validacion` int(11) DEFAULT NULL,
  `fecha_conteo` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_validacion` datetime DEFAULT NULL,
  `estado` enum('pendiente','validado','rechazado') DEFAULT 'pendiente',
  PRIMARY KEY (`conteo_id`),
  KEY `idx_caja` (`caja_id`),
  KEY `idx_usuario` (`usuario_conteo`),
  KEY `idx_tipo` (`tipo_conteo`),
  KEY `idx_fecha` (`fecha_conteo`),
  KEY `idx_estado` (`estado`),
  CONSTRAINT `fk_conteo_caja` FOREIGN KEY (`caja_id`) REFERENCES `caja` (`caja_id`),
  CONSTRAINT `fk_conteo_usuario` FOREIGN KEY (`usuario_conteo`) REFERENCES `usuarios` (`id_usuario`),
  CONSTRAINT `fk_conteo_supervisor` FOREIGN KEY (`supervisor_validacion`) REFERENCES `usuarios` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Registro de conteos físicos y conciliación de cajas';

-- =====================================================
-- 6. MODIFICACIONES A LA TABLA CAJA EXISTENTE
-- =====================================================
ALTER TABLE `caja` 
ADD COLUMN IF NOT EXISTS `caja_sucursal_id` int(11) DEFAULT NULL COMMENT 'Referencia a caja específica de sucursal',
ADD COLUMN IF NOT EXISTS `usuario_apertura` int(11) DEFAULT NULL COMMENT 'Usuario que abrió la caja',
ADD COLUMN IF NOT EXISTS `usuario_cierre` int(11) DEFAULT NULL COMMENT 'Usuario que cerró la caja',
ADD COLUMN IF NOT EXISTS `ip_apertura` varchar(45) DEFAULT NULL COMMENT 'IP de apertura',
ADD COLUMN IF NOT EXISTS `ip_cierre` varchar(45) DEFAULT NULL COMMENT 'IP de cierre',
ADD COLUMN IF NOT EXISTS `validacion_fisica_apertura` tinyint(1) DEFAULT 0 COMMENT 'Si se realizó conteo físico en apertura',
ADD COLUMN IF NOT EXISTS `validacion_fisica_cierre` tinyint(1) DEFAULT 0 COMMENT 'Si se realizó conteo físico en cierre',
ADD COLUMN IF NOT EXISTS `observaciones_apertura` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `observaciones_cierre` text DEFAULT NULL,
ADD COLUMN IF NOT EXISTS `nivel_criticidad` enum('NORMAL','ALTO','CRITICO') DEFAULT 'NORMAL' COMMENT 'Nivel de criticidad de la sesión',
ADD INDEX IF NOT EXISTS `idx_sucursal_caja` (`caja_sucursal_id`),
ADD INDEX IF NOT EXISTS `idx_usuario_apertura` (`usuario_apertura`),
ADD INDEX IF NOT EXISTS `idx_usuario_cierre` (`usuario_cierre`),
ADD INDEX IF NOT EXISTS `idx_estado_fecha` (`caja_estado`, `caja_f_apertura`);

-- Agregar constraints si no existen
SET @exists = (SELECT COUNT(*) FROM information_schema.table_constraints 
               WHERE constraint_name = 'fk_caja_sucursal_config' 
               AND table_name = 'caja' 
               AND table_schema = DATABASE());

SET @sql = IF(@exists = 0, 
    'ALTER TABLE caja ADD CONSTRAINT fk_caja_sucursal_config FOREIGN KEY (caja_sucursal_id) REFERENCES cajas_sucursales(caja_sucursal_id)',
    'SELECT "Constraint fk_caja_sucursal_config already exists" as message');

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- =====================================================
-- 7. PROCEDIMIENTOS ALMACENADOS PARA PERMISOS
-- =====================================================

-- Verificar permisos de usuario para operaciones de caja
DELIMITER $$
DROP PROCEDURE IF EXISTS SP_VERIFICAR_PERMISOS_CAJA$$
CREATE PROCEDURE SP_VERIFICAR_PERMISOS_CAJA(
    IN p_id_usuario INT,
    IN p_accion VARCHAR(50),
    IN p_monto DECIMAL(15,2)
)
BEGIN
    DECLARE v_puede_ejecutar TINYINT DEFAULT 0;
    DECLARE v_es_admin TINYINT DEFAULT 0;
    DECLARE v_limite_monto DECIMAL(15,2) DEFAULT 0;
    
    -- Verificar si es administrador
    SELECT COUNT(*) INTO v_es_admin
    FROM usuarios u 
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    WHERE u.id_usuario = p_id_usuario 
    AND p.descripcion = 'Administrador' 
    AND u.estado = 1;
    
    -- Si es admin, puede todo
    IF v_es_admin > 0 THEN
        SET v_puede_ejecutar = 1;
    ELSE
        -- Verificar permisos específicos
        CASE p_accion
            WHEN 'ABRIR_CAJA' THEN
                SELECT puede_abrir_caja, COALESCE(limite_monto_apertura, 999999999) 
                INTO v_puede_ejecutar, v_limite_monto
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
                IF p_monto > v_limite_monto THEN
                    SET v_puede_ejecutar = 0;
                END IF;
                
            WHEN 'CERRAR_CAJA' THEN
                SELECT puede_cerrar_caja INTO v_puede_ejecutar
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
            WHEN 'GESTIONAR_MOVIMIENTOS' THEN
                SELECT puede_gestionar_movimientos, COALESCE(limite_monto_movimiento, 999999999)
                INTO v_puede_ejecutar, v_limite_monto
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
                IF p_monto > v_limite_monto THEN
                    SET v_puede_ejecutar = 0;
                END IF;
                
            WHEN 'SUPERVISAR' THEN
                SELECT puede_supervisar INTO v_puede_ejecutar
                FROM caja_permisos 
                WHERE id_usuario = p_id_usuario AND estado = 'activo';
                
            ELSE
                SET v_puede_ejecutar = 0;
        END CASE;
    END IF;
    
    -- Devolver resultado
    SELECT 
        COALESCE(v_puede_ejecutar, 0) as puede_ejecutar,
        v_es_admin as es_administrador,
        COALESCE(v_limite_monto, 0) as limite_monto;
        
END$$

-- Registrar evento de auditoría
DROP PROCEDURE IF EXISTS SP_REGISTRAR_AUDITORIA_CAJA$$
CREATE PROCEDURE SP_REGISTRAR_AUDITORIA_CAJA(
    IN p_caja_id INT,
    IN p_id_usuario INT,
    IN p_accion VARCHAR(50),
    IN p_descripcion TEXT,
    IN p_datos_anteriores TEXT,
    IN p_datos_nuevos TEXT,
    IN p_ip_address VARCHAR(45),
    IN p_monto_involucrado DECIMAL(15,2),
    IN p_resultado VARCHAR(20),
    IN p_observaciones TEXT
)
BEGIN
    INSERT INTO caja_auditoria (
        caja_id, id_usuario, accion, descripcion, 
        datos_anteriores, datos_nuevos, ip_address,
        monto_involucrado, resultado, observaciones
    ) VALUES (
        p_caja_id, p_id_usuario, p_accion, p_descripcion,
        p_datos_anteriores, p_datos_nuevos, p_ip_address,
        p_monto_involucrado, p_resultado, p_observaciones
    );
END$$

-- Generar alerta del sistema
DROP PROCEDURE IF EXISTS SP_GENERAR_ALERTA_CAJA$$
CREATE PROCEDURE SP_GENERAR_ALERTA_CAJA(
    IN p_caja_id INT,
    IN p_tipo_alerta VARCHAR(50),
    IN p_nivel_criticidad VARCHAR(20),
    IN p_titulo VARCHAR(200),
    IN p_mensaje TEXT,
    IN p_datos_adicionales TEXT,
    IN p_usuario_notificado INT
)
BEGIN
    INSERT INTO caja_alertas (
        caja_id, tipo_alerta, nivel_criticidad, titulo,
        mensaje, datos_adicionales, usuario_notificado
    ) VALUES (
        p_caja_id, p_tipo_alerta, p_nivel_criticidad, p_titulo,
        p_mensaje, p_datos_adicionales, p_usuario_notificado
    );
END$$

DELIMITER ;

-- =====================================================
-- 8. DATOS INICIALES Y CONFIGURACIÓN
-- =====================================================

-- Insertar permisos por defecto para administradores existentes
INSERT IGNORE INTO caja_permisos (
    id_usuario, puede_abrir_caja, puede_cerrar_caja, puede_ver_reportes,
    puede_gestionar_movimientos, puede_supervisar, usuario_creacion
)
SELECT 
    u.id_usuario, 1, 1, 1, 1, 1, 1
FROM usuarios u 
INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
WHERE p.descripcion = 'Administrador' 
AND u.estado = 1;

-- Insertar caja principal para sucursales existentes
INSERT IGNORE INTO cajas_sucursales (
    sucursal_id, nombre_caja, codigo_caja, descripcion, 
    tipo_caja, usuario_creacion
)
SELECT 
    s.id, 
    CONCAT('Caja Principal - ', s.nombre),
    CONCAT('CP-', s.codigo),
    CONCAT('Caja principal de la sucursal ', s.nombre),
    'principal',
    1
FROM sucursales s 
WHERE s.estado = 'activa';

-- =====================================================
-- 9. TRIGGERS PARA AUDITORÍA AUTOMÁTICA
-- =====================================================

-- Trigger para auditar apertura de caja
DELIMITER $$
DROP TRIGGER IF EXISTS TG_AUDITORIA_CAJA_APERTURA$$
CREATE TRIGGER TG_AUDITORIA_CAJA_APERTURA
    AFTER INSERT ON caja
    FOR EACH ROW
BEGIN
    DECLARE v_usuario_actual INT DEFAULT 1;
    
    -- Obtener usuario de sesión si está disponible
    SET v_usuario_actual = COALESCE(NEW.usuario_apertura, 1);
    
    INSERT INTO caja_auditoria (
        caja_id, id_usuario, accion, descripcion,
        datos_nuevos, monto_involucrado, resultado
    ) VALUES (
        NEW.caja_id, 
        v_usuario_actual,
        'APERTURA',
        CONCAT('Apertura de caja: ', NEW.caja_descripcion),
        JSON_OBJECT(
            'monto_inicial', NEW.caja_monto_inicial,
            'fecha_apertura', NEW.caja_f_apertura,
            'hora_apertura', NEW.caja_hora_apertura,
            'estado', NEW.caja_estado
        ),
        NEW.caja_monto_inicial,
        'EXITOSO'
    );
END$$

-- Trigger para auditar cierre de caja
DROP TRIGGER IF EXISTS TG_AUDITORIA_CAJA_CIERRE$$
CREATE TRIGGER TG_AUDITORIA_CAJA_CIERRE
    AFTER UPDATE ON caja
    FOR EACH ROW
BEGIN
    DECLARE v_usuario_actual INT DEFAULT 1;
    
    -- Solo si se está cerrando la caja
    IF OLD.caja_estado = 'VIGENTE' AND NEW.caja_estado = 'CERRADO' THEN
        
        SET v_usuario_actual = COALESCE(NEW.usuario_cierre, 1);
        
        INSERT INTO caja_auditoria (
            caja_id, id_usuario, accion, descripcion,
            datos_anteriores, datos_nuevos, monto_involucrado, resultado
        ) VALUES (
            NEW.caja_id,
            v_usuario_actual,
            'CIERRE',
            CONCAT('Cierre de caja: ', NEW.caja_descripcion),
            JSON_OBJECT(
                'estado_anterior', OLD.caja_estado,
                'monto_inicial', OLD.caja_monto_inicial
            ),
            JSON_OBJECT(
                'monto_total', NEW.caja_monto_total,
                'fecha_cierre', NEW.caja_f_cierre,
                'hora_cierre', NEW.caja_hora_cierre,
                'estado', NEW.caja_estado,
                'prestamos', NEW.caja_prestamo,
                'ingresos', NEW.caja_monto_ingreso,
                'egresos', NEW.caja__monto_egreso
            ),
            NEW.caja_monto_total,
            'EXITOSO'
        );
        
        -- Generar alerta si hay discrepancias significativas
        IF ABS(NEW.caja_monto_total - NEW.caja_monto_inicial) > 10000 THEN
            INSERT INTO caja_alertas (
                caja_id, tipo_alerta, nivel_criticidad, titulo, mensaje
            ) VALUES (
                NEW.caja_id,
                'ALTA_ACTIVIDAD',
                'WARNING',
                'Actividad alta detectada en caja',
                CONCAT('La caja ', NEW.caja_descripcion, ' registró movimientos por un total significativo: ', 
                       FORMAT(NEW.caja_monto_total, 2))
            );
        END IF;
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- 10. VISTAS ÚTILES PARA REPORTES
-- =====================================================

-- Vista consolidada de estado de cajas
CREATE OR REPLACE VIEW v_estado_cajas AS
SELECT 
    c.caja_id,
    c.caja_descripcion,
    c.caja_estado,
    c.caja_monto_inicial,
    c.caja_monto_total,
    c.caja_f_apertura,
    c.caja_f_cierre,
    c.caja_hora_apertura,
    c.caja_hora_cierre,
    ua.nombre_usuario as usuario_apertura_nombre,
    uc.nombre_usuario as usuario_cierre_nombre,
    cs.nombre_caja as nombre_caja_sucursal,
    s.nombre as sucursal_nombre,
    CASE 
        WHEN c.caja_estado = 'VIGENTE' THEN 
            TIMESTAMPDIFF(HOUR, TIMESTAMP(c.caja_f_apertura, c.caja_hora_apertura), NOW())
        ELSE NULL
    END as horas_abierta,
    (SELECT COUNT(*) FROM caja_alertas ca WHERE ca.caja_id = c.caja_id AND ca.estado = 'pendiente') as alertas_pendientes
FROM caja c
LEFT JOIN usuarios ua ON c.usuario_apertura = ua.id_usuario
LEFT JOIN usuarios uc ON c.usuario_cierre = uc.id_usuario
LEFT JOIN cajas_sucursales cs ON c.caja_sucursal_id = cs.caja_sucursal_id
LEFT JOIN sucursales s ON cs.sucursal_id = s.id
ORDER BY c.caja_f_apertura DESC;

-- Vista de auditoría consolidada
CREATE OR REPLACE VIEW v_auditoria_caja_detallada AS
SELECT 
    ca.auditoria_id,
    ca.caja_id,
    c.caja_descripcion,
    ca.accion,
    ca.descripcion,
    u.nombre_usuario,
    u.apellido_usuario,
    ca.monto_involucrado,
    ca.resultado,
    ca.fecha_registro,
    ca.ip_address,
    s.nombre as sucursal_nombre
FROM caja_auditoria ca
INNER JOIN usuarios u ON ca.id_usuario = u.id_usuario
INNER JOIN caja c ON ca.caja_id = c.caja_id
LEFT JOIN cajas_sucursales cs ON c.caja_sucursal_id = cs.caja_sucursal_id
LEFT JOIN sucursales s ON cs.sucursal_id = s.id
ORDER BY ca.fecha_registro DESC;

-- =====================================================
-- FIN DEL SCRIPT DE MEJORAS
-- =====================================================

-- Mensaje de confirmación
SELECT 'MEJORAS DEL MÓDULO DE CAJA IMPLEMENTADAS CORRECTAMENTE' as mensaje,
       'Permisos, Auditoría, Multi-Caja y Alertas configurados' as detalle,
       NOW() as fecha_implementacion; 