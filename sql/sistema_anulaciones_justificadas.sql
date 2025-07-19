-- =========================================================
-- SISTEMA DE ANULACIONES JUSTIFICADAS Y CONTROL DE ACCESO
-- =========================================================
-- Este script implementa:
-- 1. Tabla de auditoría para anulaciones
-- 2. Control de acceso por perfil de usuario
-- 3. Justificaciones obligatorias
-- =========================================================

-- 1. CREAR TABLA DE AUDITORÍA DE ANULACIONES
CREATE TABLE IF NOT EXISTS `anulaciones_auditoria` (
    `anulacion_id` INT(11) NOT NULL AUTO_INCREMENT,
    `tipo_documento` ENUM('pago', 'cuota', 'prestamo', 'contrato', 'nota_debito') NOT NULL,
    `documento_id` VARCHAR(50) NOT NULL COMMENT 'ID del documento anulado',
    `nro_prestamo` VARCHAR(8) NULL COMMENT 'Número de préstamo relacionado',
    `usuario_id` INT(11) NOT NULL COMMENT 'Usuario que realizó la anulación',
    `usuario_nombre` VARCHAR(255) NOT NULL COMMENT 'Nombre del usuario para auditoría',
    `motivo_anulacion` TEXT NOT NULL COMMENT 'Justificación obligatoria',
    `datos_originales` JSON NULL COMMENT 'Datos del documento antes de anular',
    `fecha_anulacion` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `sucursal_id` INT(11) NULL COMMENT 'Sucursal donde se realizó la anulación',
    `ip_origen` VARCHAR(45) NULL COMMENT 'IP desde donde se realizó la anulación',
    `estado` ENUM('activa', 'revertida') DEFAULT 'activa' COMMENT 'Estado de la anulación',
    PRIMARY KEY (`anulacion_id`),
    INDEX `idx_tipo_documento` (`tipo_documento`),
    INDEX `idx_documento_id` (`documento_id`),
    INDEX `idx_prestamo` (`nro_prestamo`),
    INDEX `idx_usuario` (`usuario_id`),
    INDEX `idx_fecha` (`fecha_anulacion`),
    INDEX `idx_sucursal` (`sucursal_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Auditoría de todas las anulaciones realizadas en el sistema';

-- 2. CREAR TABLA DE PERMISOS DE ANULACIÓN POR PERFIL
CREATE TABLE IF NOT EXISTS `permisos_anulacion` (
    `permiso_id` INT(11) NOT NULL AUTO_INCREMENT,
    `id_perfil` INT(11) NOT NULL COMMENT 'ID del perfil de usuario',
    `tipo_documento` ENUM('pago', 'cuota', 'prestamo', 'contrato', 'nota_debito') NOT NULL,
    `puede_anular` BOOLEAN DEFAULT FALSE COMMENT 'Si puede anular este tipo de documento',
    `requiere_justificacion` BOOLEAN DEFAULT TRUE COMMENT 'Si requiere justificación',
    `limite_tiempo_horas` INT(11) NULL COMMENT 'Límite de tiempo en horas para anular (NULL = sin límite)',
    `nivel_aprobacion` ENUM('propio', 'supervisor', 'administrador') DEFAULT 'propio' COMMENT 'Nivel de aprobación requerido',
    `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `activo` BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (`permiso_id`),
    UNIQUE KEY `uk_perfil_tipo` (`id_perfil`, `tipo_documento`),
    INDEX `idx_perfil` (`id_perfil`),
    INDEX `idx_tipo` (`tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci 
COMMENT='Permisos de anulación por perfil de usuario';

-- 3. INSERTAR PERMISOS PREDETERMINADOS
-- Permisos para Administrador (perfil_id = 1)
INSERT INTO `permisos_anulacion` (`id_perfil`, `tipo_documento`, `puede_anular`, `requiere_justificacion`, `limite_tiempo_horas`, `nivel_aprobacion`) VALUES
(1, 'pago', TRUE, TRUE, NULL, 'propio'),
(1, 'cuota', TRUE, TRUE, NULL, 'propio'),
(1, 'prestamo', TRUE, TRUE, NULL, 'propio'),
(1, 'contrato', TRUE, TRUE, NULL, 'propio'),
(1, 'nota_debito', TRUE, TRUE, NULL, 'propio');

-- Permisos para otros perfiles (solo préstamos con restricciones)
-- Nota: Ajustar estos valores según los perfiles existentes en tu sistema
INSERT INTO `permisos_anulacion` (`id_perfil`, `tipo_documento`, `puede_anular`, `requiere_justificacion`, `limite_tiempo_horas`, `nivel_aprobacion`) VALUES
(2, 'pago', FALSE, TRUE, 24, 'administrador'),
(2, 'cuota', FALSE, TRUE, 24, 'administrador'),
(2, 'prestamo', FALSE, TRUE, 48, 'administrador'),
(2, 'contrato', FALSE, TRUE, 48, 'administrador'),
(2, 'nota_debito', FALSE, TRUE, NULL, 'administrador');

-- 4. CREAR VISTA PARA CONSULTA FÁCIL DE PERMISOS
CREATE OR REPLACE VIEW `v_permisos_anulacion_usuarios` AS
SELECT 
    u.id_usuario,
    u.nombre_usuario,
    u.apellido_usuario,
    p.descripcion as perfil_nombre,
    pa.tipo_documento,
    pa.puede_anular,
    pa.requiere_justificacion,
    pa.limite_tiempo_horas,
    pa.nivel_aprobacion,
    s.nombre as sucursal_nombre,
    s.codigo as sucursal_codigo
FROM usuarios u
INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
LEFT JOIN permisos_anulacion pa ON p.id_perfil = pa.id_perfil AND pa.activo = TRUE
LEFT JOIN sucursales s ON u.sucursal_id = s.id
WHERE u.estado = 1
ORDER BY u.nombre_usuario, pa.tipo_documento;

-- 5. CREAR VISTA PARA AUDITORÍA DE ANULACIONES
CREATE OR REPLACE VIEW `v_anulaciones_auditoria_completa` AS
SELECT 
    aa.anulacion_id,
    aa.tipo_documento,
    aa.documento_id,
    aa.nro_prestamo,
    aa.usuario_nombre,
    aa.motivo_anulacion,
    aa.fecha_anulacion,
    aa.estado,
    s.nombre as sucursal_nombre,
    s.codigo as sucursal_codigo,
    p.descripcion as perfil_usuario,
    CASE 
        WHEN aa.fecha_anulacion >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 'Reciente'
        WHEN aa.fecha_anulacion >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 'Esta semana'
        WHEN aa.fecha_anulacion >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 'Este mes'
        ELSE 'Anterior'
    END as periodo_anulacion
FROM anulaciones_auditoria aa
LEFT JOIN sucursales s ON aa.sucursal_id = s.id
LEFT JOIN usuarios u ON aa.usuario_id = u.id_usuario
LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
ORDER BY aa.fecha_anulacion DESC;

-- 6. PROCEDIMIENTOS ALMACENADOS PARA ANULACIONES

-- Procedimiento para verificar permisos de anulación
DELIMITER $$
CREATE PROCEDURE `SP_VERIFICAR_PERMISOS_ANULACION`(
    IN p_usuario_id INT,
    IN p_tipo_documento VARCHAR(20),
    IN p_fecha_documento DATETIME
)
BEGIN
    DECLARE v_puede_anular BOOLEAN DEFAULT FALSE;
    DECLARE v_requiere_justificacion BOOLEAN DEFAULT TRUE;
    DECLARE v_limite_horas INT DEFAULT NULL;
    DECLARE v_perfil_id INT;
    DECLARE v_horas_transcurridas INT;
    
    -- Obtener perfil del usuario
    SELECT id_perfil_usuario INTO v_perfil_id 
    FROM usuarios 
    WHERE id_usuario = p_usuario_id AND estado = 1;
    
    -- Obtener permisos
    SELECT puede_anular, requiere_justificacion, limite_tiempo_horas
    INTO v_puede_anular, v_requiere_justificacion, v_limite_horas
    FROM permisos_anulacion 
    WHERE id_perfil = v_perfil_id 
    AND tipo_documento = p_tipo_documento 
    AND activo = TRUE;
    
    -- Calcular horas transcurridas
    IF p_fecha_documento IS NOT NULL THEN
        SET v_horas_transcurridas = TIMESTAMPDIFF(HOUR, p_fecha_documento, NOW());
    ELSE
        SET v_horas_transcurridas = 0;
    END IF;
    
    -- Verificar límite de tiempo
    IF v_limite_horas IS NOT NULL AND v_horas_transcurridas > v_limite_horas THEN
        SET v_puede_anular = FALSE;
    END IF;
    
    -- Retornar resultados
    SELECT 
        v_puede_anular as puede_anular,
        v_requiere_justificacion as requiere_justificacion,
        v_limite_horas as limite_horas,
        v_horas_transcurridas as horas_transcurridas,
        CASE 
            WHEN NOT v_puede_anular AND v_limite_horas IS NOT NULL AND v_horas_transcurridas > v_limite_horas THEN 'Tiempo límite excedido'
            WHEN NOT v_puede_anular THEN 'Sin permisos para anular este tipo de documento'
            ELSE 'Permitido'
        END as mensaje;
END$$

-- Procedimiento para registrar anulación
CREATE PROCEDURE `SP_REGISTRAR_ANULACION`(
    IN p_tipo_documento VARCHAR(20),
    IN p_documento_id VARCHAR(50),
    IN p_nro_prestamo VARCHAR(8),
    IN p_usuario_id INT,
    IN p_motivo TEXT,
    IN p_datos_originales JSON,
    IN p_sucursal_id INT,
    IN p_ip_origen VARCHAR(45)
)
BEGIN
    DECLARE v_usuario_nombre VARCHAR(255);
    
    -- Obtener nombre del usuario
    SELECT CONCAT(nombre_usuario, ' ', apellido_usuario) INTO v_usuario_nombre
    FROM usuarios WHERE id_usuario = p_usuario_id;
    
    -- Insertar registro de anulación
    INSERT INTO anulaciones_auditoria (
        tipo_documento, documento_id, nro_prestamo, usuario_id, usuario_nombre,
        motivo_anulacion, datos_originales, sucursal_id, ip_origen
    ) VALUES (
        p_tipo_documento, p_documento_id, p_nro_prestamo, p_usuario_id, v_usuario_nombre,
        p_motivo, p_datos_originales, p_sucursal_id, p_ip_origen
    );
    
    SELECT LAST_INSERT_ID() as anulacion_id, 'ok' as resultado;
END$$

DELIMITER ;

-- 7. TRIGGERS PARA AUDITORÍA AUTOMÁTICA

-- Trigger para cuando se actualiza estado de préstamo a anulado
DELIMITER $$
CREATE TRIGGER `tr_prestamo_anulado_audit` 
AFTER UPDATE ON `prestamo_cabecera`
FOR EACH ROW
BEGIN
    IF OLD.pres_aprobacion != 'anulado' AND NEW.pres_aprobacion = 'anulado' THEN
        -- Solo insertar si no existe ya un registro reciente (para evitar duplicados)
        IF NOT EXISTS (
            SELECT 1 FROM anulaciones_auditoria 
            WHERE tipo_documento = 'prestamo' 
            AND documento_id = NEW.nro_prestamo 
            AND fecha_anulacion >= DATE_SUB(NOW(), INTERVAL 1 MINUTE)
        ) THEN
            INSERT INTO anulaciones_auditoria (
                tipo_documento, documento_id, nro_prestamo, usuario_id, usuario_nombre,
                motivo_anulacion, datos_originales
            ) VALUES (
                'prestamo', NEW.nro_prestamo, NEW.nro_prestamo, 
                COALESCE(@USUARIO_ANULACION, 1), 
                COALESCE(@USUARIO_NOMBRE_ANULACION, 'Sistema'),
                COALESCE(@MOTIVO_ANULACION, 'Anulación desde sistema legacy'),
                JSON_OBJECT('monto_original', OLD.pres_monto, 'estado_anterior', OLD.pres_aprobacion)
            );
        END IF;
    END IF;
END$$

DELIMITER ;

-- 8. COMENTARIOS Y DOCUMENTACIÓN
/*
INSTRUCCIONES DE USO:
1. Ejecutar este script en la base de datos
2. Configurar permisos por perfil según necesidades del negocio
3. Modificar los modelos PHP para usar SP_VERIFICAR_PERMISOS_ANULACION antes de anular
4. Usar SP_REGISTRAR_ANULACION para registrar todas las anulaciones
5. Las vistas facilitan la consulta de permisos y auditoría

EJEMPLOS DE USO:
- Verificar permisos: CALL SP_VERIFICAR_PERMISOS_ANULACION(1, 'pago', '2025-01-13 10:00:00');
- Ver permisos de usuario: SELECT * FROM v_permisos_anulacion_usuarios WHERE id_usuario = 1;
- Ver auditoría: SELECT * FROM v_anulaciones_auditoria_completa WHERE fecha_anulacion >= CURDATE();
*/ 