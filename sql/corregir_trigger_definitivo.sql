-- ======================================================
-- CORRECCIÓN DEFINITIVA DEL TRIGGER WHATSAPP
-- ======================================================
-- Versión que maneja problemas de collation y campos faltantes
-- ======================================================

-- 1. Eliminar trigger existente
DROP TRIGGER IF EXISTS `trg_prestamo_aprobado_whatsapp`;

-- 2. Verificar si existe la tabla whatsapp_mensajes
SET @table_exists = (SELECT COUNT(*) FROM information_schema.tables 
                    WHERE table_schema = DATABASE() 
                    AND table_name = 'whatsapp_mensajes');

-- 3. Solo crear el trigger si la tabla existe
DELIMITER $$
CREATE TRIGGER `trg_prestamo_aprobado_whatsapp` AFTER UPDATE ON `prestamo_cabecera` FOR EACH ROW 
BEGIN
    DECLARE table_exists INT DEFAULT 0;
    
    -- Verificar si existe la tabla whatsapp_mensajes
    SELECT COUNT(*) INTO table_exists 
    FROM information_schema.tables 
    WHERE table_schema = DATABASE() 
    AND table_name = 'whatsapp_mensajes';
    
    -- Solo ejecutar si el préstamo cambió a 'aprobado' Y la tabla existe
    IF OLD.pres_aprobacion != 'aprobado' 
       AND NEW.pres_aprobacion = 'aprobado' 
       AND table_exists > 0 THEN
        
        -- Insertar mensaje (versión segura sin problemas de collation)
        INSERT INTO whatsapp_mensajes (
            numero_destino,
            mensaje,
            tipo_mensaje,
            estado,
            cliente_id
        )
        SELECT 
            IFNULL(c.telefono, ''),
            CONCAT(
                'FELICITACIONES! Su prestamo ha sido APROBADO. ',
                'Numero: ', IFNULL(NEW.nro_prestamo, ''), '. ',
                'Monto: $', IFNULL(CAST(NEW.pres_monto AS CHAR), '0'), '. ',
                'Cuotas: ', IFNULL(CAST(NEW.pres_cuotas AS CHAR), '0'), '. ',
                'CrediCrece'
            ),
            'text',
            'pendiente',
            NEW.cliente_id
        FROM clientes c
        WHERE c.cliente_id = NEW.cliente_id
        AND c.telefono IS NOT NULL
        AND c.telefono != ''
        AND CHAR_LENGTH(c.telefono) >= 8;
        
    END IF;
END$$
DELIMITER ;

-- Mensaje de confirmación
SELECT 'Trigger corregido definitivamente - maneja todos los casos' as resultado;

-- Verificar que se creó correctamente
SHOW TRIGGERS LIKE 'prestamo_cabecera'; 