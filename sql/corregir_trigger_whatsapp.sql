-- ======================================================
-- CORRECCIÓN DEL TRIGGER QUE CAUSA EL ERROR 'c.nombres'
-- ======================================================
-- Este script corrige el trigger 'trg_prestamo_aprobado_whatsapp'
-- que está causando el error "Unknown column 'c.nombres'"
-- ======================================================

-- 1. Eliminar el trigger existente
DROP TRIGGER IF EXISTS `trg_prestamo_aprobado_whatsapp`;

-- 2. Recrear el trigger con la corrección
DELIMITER $$
CREATE TRIGGER `trg_prestamo_aprobado_whatsapp` AFTER UPDATE ON `prestamo_cabecera` FOR EACH ROW 
BEGIN
    -- Si el préstamo cambió de estado a 'aprobado'
    IF OLD.pres_aprobacion != 'aprobado' AND NEW.pres_aprobacion = 'aprobado' THEN
        
        -- Insertar mensaje de notificación en cola
        INSERT INTO whatsapp_mensajes (
            numero_destino,
            mensaje,
            tipo_mensaje,
            estado,
            cliente_id
        )
        SELECT 
            c.telefono,
            CONCAT('¡FELICITACIONES!\n\n',
                   'Estimado/a ', CONVERT(c.cliente_nombres USING utf8mb4), ',\n\n',  -- ✅ CORREGIDO con CONVERT
                   'Su prestamo ha sido APROBADO\n\n',
                   'Detalles:\n',
                   '• Numero: ', CONVERT(NEW.nro_prestamo USING utf8mb4), '\n',
                   '• Monto: $', CONVERT(FORMAT(NEW.pres_monto, 2) USING utf8mb4), '\n',
                   '• Cuotas: ', CONVERT(NEW.pres_cuotas USING utf8mb4), '\n\n',
                   'Pronto nos comunicaremos para coordinar la entrega.\n\n',
                   'CrediCrece - Su socio financiero'),
            'text',
            'pendiente',
            NEW.cliente_id
        FROM clientes c
        WHERE c.cliente_id = NEW.cliente_id  -- ✅ CORREGIDO: c.cliente_id
        AND c.telefono IS NOT NULL
        AND c.telefono != ''
        AND NOT EXISTS (
            SELECT 1 FROM whatsapp_opt_out wo 
            WHERE wo.numero_cliente = c.telefono
        );
        
    END IF;
END$$
DELIMITER ;

-- ======================================================
-- VERIFICACIÓN
-- ======================================================
SELECT 'Trigger corregido exitosamente' as resultado;

-- Para verificar que el trigger fue creado correctamente:
-- SHOW TRIGGERS LIKE 'prestamo_cabecera'; 