-- ======================================================
-- CORRECCIÓN SIMPLE DEL TRIGGER (SIN PROBLEMAS DE COLLATION)
-- ======================================================
-- Esta es una versión simplificada que evita problemas de codificación
-- ======================================================

-- 1. Eliminar el trigger existente
DROP TRIGGER IF EXISTS `trg_prestamo_aprobado_whatsapp`;

-- 2. Recrear con versión simplificada
DELIMITER $$
CREATE TRIGGER `trg_prestamo_aprobado_whatsapp` AFTER UPDATE ON `prestamo_cabecera` FOR EACH ROW 
BEGIN
    -- Si el préstamo cambió de estado a 'aprobado'
    IF OLD.pres_aprobacion != 'aprobado' AND NEW.pres_aprobacion = 'aprobado' THEN
        
        -- Insertar mensaje simplificado (sin emojis para evitar problemas de collation)
        INSERT INTO whatsapp_mensajes (
            numero_destino,
            mensaje,
            tipo_mensaje,
            estado,
            cliente_id
        )
        SELECT 
            c.telefono,
            CONCAT(
                'FELICITACIONES!\n\n',
                'Estimado/a ', IFNULL(c.cliente_nombres, 'Cliente'), '\n\n',
                'Su prestamo ha sido APROBADO\n\n',
                'Detalles:\n',
                'Numero: ', IFNULL(NEW.nro_prestamo, ''), '\n',
                'Monto: $', IFNULL(FORMAT(NEW.pres_monto, 2), '0.00'), '\n',
                'Cuotas: ', IFNULL(NEW.pres_cuotas, '0'), '\n\n',
                'Pronto nos comunicaremos para coordinar la entrega.\n\n',
                'CrediCrece - Su socio financiero'
            ),
            'text',
            'pendiente',
            NEW.cliente_id
        FROM clientes c
        WHERE c.cliente_id = NEW.cliente_id
        AND c.telefono IS NOT NULL
        AND c.telefono != '';
        
    END IF;
END$$
DELIMITER ;

-- Verificación
SELECT 'Trigger simple creado exitosamente' as resultado; 