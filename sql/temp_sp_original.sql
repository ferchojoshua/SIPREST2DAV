DELIMITER $$

DROP PROCEDURE IF EXISTS SP_REGISTRAR_APERTURA_CAJA$$

CREATE PROCEDURE SP_REGISTRAR_APERTURA_CAJA(
    IN DESCRIPCION VARCHAR(100), 
    IN MONTO_INI FLOAT
)
BEGIN
    DECLARE CANTIDAD INT;
    DECLARE v_usuario_id INT DEFAULT 1; -- Usuario por defecto si no se especifica
    
    -- Obtener el usuario de la sesión actual (esto debería venir del controlador)
    SET v_usuario_id = 1; -- Por ahora usamos 1 para compatibilidad
    
    -- Verificar si el usuario ya tiene una caja abierta
    SET @CANTIDAD := (SELECT COUNT(*) FROM caja WHERE caja_estado='VIGENTE' AND id_usuario = v_usuario_id);
    
    IF @CANTIDAD = 0 THEN
        INSERT INTO caja (
            caja_descripcion, 
            caja_monto_inicial, 
            caja_f_apertura, 
            caja_estado, 
            caja_hora_apertura,
            id_usuario
        ) VALUES(
            DESCRIPCION, 
            MONTO_INI, 
            CURDATE(), 
            'VIGENTE', 
            CURRENT_TIME(),
            v_usuario_id
        );
        SELECT 1;
    ELSE
        SELECT 2;
    END IF;
END$$

DELIMITER ; 