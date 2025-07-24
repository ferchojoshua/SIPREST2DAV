DELIMITER $$

DROP PROCEDURE IF EXISTS SP_REGISTRAR_APERTURA_CAJA_SUCURSAL$$

CREATE PROCEDURE SP_REGISTRAR_APERTURA_CAJA_SUCURSAL(
    IN p_descripcion VARCHAR(100),
    IN p_monto_inicial DECIMAL(15,2),
    IN p_usuario_id INT,
    IN p_sucursal_id INT
)
BEGIN
    DECLARE v_resultado INT DEFAULT 0;
    DECLARE v_cajas_abiertas INT DEFAULT 0;
    DECLARE v_caja_id INT;
    DECLARE v_es_admin BOOLEAN DEFAULT FALSE;
    
    -- Verificar si el usuario es administrador
    SELECT COUNT(*) INTO v_es_admin
    FROM usuarios u
    INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
    WHERE u.id_usuario = p_usuario_id AND p.id_perfil = 1;
    
    -- Si es admin, no verificar cajas abiertas
    IF v_es_admin THEN
        SET v_cajas_abiertas = 0;
    ELSE
        -- Verificar si ya hay una caja abierta para este usuario
        SELECT COUNT(*) INTO v_cajas_abiertas
        FROM caja 
        WHERE caja_estado = 'VIGENTE' 
        AND usuario_apertura = p_usuario_id;
    END IF;
    
    IF v_cajas_abiertas > 0 THEN
        -- Ya existe una caja abierta para este usuario
        SELECT 2 as resultado, 'Ya tienes una caja abierta' as mensaje;
    ELSE
        -- Insertar nueva caja
        INSERT INTO caja (
            caja_descripcion, 
            caja_monto_inicial, 
            caja_f_apertura, 
            caja_hora_apertura, 
            caja_estado,
            sucursal_id,
            usuario_apertura
        ) VALUES (
            p_descripcion, 
            p_monto_inicial, 
            CURDATE(), 
            CURTIME(), 
            'VIGENTE',
            p_sucursal_id,
            p_usuario_id
        );
        
        SET v_caja_id = LAST_INSERT_ID();
        SET v_resultado = 1;
        
        -- Registrar en auditoría
        INSERT INTO caja_auditoria (
            caja_id, 
            usuario_id, 
            accion, 
            descripcion, 
            datos_nuevos,
            monto_involucrado
        ) VALUES (
            v_caja_id,
            p_usuario_id,
            'APERTURA',
            CONCAT('Apertura de caja: ', p_descripcion),
            JSON_OBJECT(
                'monto_inicial', p_monto_inicial,
                'descripcion', p_descripcion,
                'sucursal_id', p_sucursal_id,
                'es_admin', v_es_admin
            ),
            p_monto_inicial
        );
        
        SELECT v_resultado as resultado, 'Caja abierta correctamente' as mensaje, v_caja_id as caja_id;
    END IF;
END$$

DELIMITER ; 