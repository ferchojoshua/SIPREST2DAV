-- =====================================================
-- INTEGRACIÓN TABLA BARRIOS CON SISTEMA SIPREST
-- =====================================================
-- Script para integrar la tabla de barrios con las tablas existentes

-- 1. Agregar campo de barrio a la tabla clientes (OPCIONAL)
-- Esto permitirá referenciar directamente el barrio del cliente
ALTER TABLE `clientes` 
ADD COLUMN `barrio_id` int(11) DEFAULT NULL COMMENT 'Referencia al barrio del cliente' AFTER `cliente_direccion`,
ADD INDEX `idx_barrio_id` (`barrio_id`),
ADD CONSTRAINT `fk_clientes_barrio` FOREIGN KEY (`barrio_id`) REFERENCES `barrios_leon` (`barrio_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- 2. Crear vista que combine clientes con información de barrios
CREATE OR REPLACE VIEW `v_clientes_barrios` AS
SELECT 
    c.*,
    b.codigo_barrio,
    b.nombre_barrio,
    b.zona as barrio_zona,
    b.es_historico as barrio_historico,
    b.coordenadas_lat as barrio_lat,
    b.coordenadas_lng as barrio_lng,
    CONCAT(c.cliente_direccion, 
           CASE WHEN b.nombre_barrio IS NOT NULL 
                THEN CONCAT(', Barrio ', b.nombre_barrio) 
                ELSE '' 
           END) as direccion_completa
FROM clientes c
LEFT JOIN barrios_leon b ON c.barrio_id = b.barrio_id;

-- 3. Crear función para buscar barrio por nombre
DELIMITER $$
CREATE FUNCTION fn_buscar_barrio_id(p_nombre_barrio VARCHAR(100))
RETURNS INT
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_barrio_id INT DEFAULT NULL;
    
    SELECT barrio_id INTO v_barrio_id
    FROM barrios_leon 
    WHERE nombre_barrio LIKE CONCAT('%', p_nombre_barrio, '%')
       OR codigo_barrio = p_nombre_barrio
    LIMIT 1;
    
    RETURN v_barrio_id;
END$$
DELIMITER ;

-- 4. Crear procedimiento para actualizar barrios de clientes existentes
DELIMITER $$
CREATE PROCEDURE sp_actualizar_barrios_clientes()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_cliente_id INT;
    DECLARE v_direccion VARCHAR(255);
    DECLARE v_barrio_id INT;
    
    DECLARE cur CURSOR FOR 
        SELECT cliente_id, cliente_direccion 
        FROM clientes 
        WHERE barrio_id IS NULL AND cliente_direccion IS NOT NULL;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO v_cliente_id, v_direccion;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Buscar barrio basado en palabras clave en la dirección
        SET v_barrio_id = NULL;
        
        -- Centro Histórico
        IF v_direccion LIKE '%CATEDRAL%' OR v_direccion LIKE '%CENTRO%' OR v_direccion LIKE '%CENTRAL%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'CENTRO-01');
        
        -- Sutiava
        ELSEIF v_direccion LIKE '%SUTIAVA%' OR v_direccion LIKE '%SUBTIAVA%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'CENTRO-03');
        
        -- San Sebastián
        ELSEIF v_direccion LIKE '%SAN SEBASTIAN%' OR v_direccion LIKE '%SEBASTIAN%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'CENTRO-02');
        
        -- El Calvario
        ELSEIF v_direccion LIKE '%CALVARIO%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'CENTRO-04');
        
        -- Villa 23 de Julio
        ELSEIF v_direccion LIKE '%23 DE JULIO%' OR v_direccion LIKE '%VILLA 23%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'NORTE-01');
        
        -- Praderas Nueva León
        ELSEIF v_direccion LIKE '%PRADERAS%' OR v_direccion LIKE '%NUEVA LEON%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'NORTE-02');
        
        -- Oscar Pérez
        ELSEIF v_direccion LIKE '%OSCAR PEREZ%' OR v_direccion LIKE '%OSCAR PÉREZ%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'NORTE-03');
        
        -- Guadalupe
        ELSEIF v_direccion LIKE '%GUADALUPE%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'CENTRO-09');
        
        -- La Providencia
        ELSEIF v_direccion LIKE '%PROVIDENCIA%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'SUR-03');
        
        -- Todo Será Mejor
        ELSEIF v_direccion LIKE '%TODO SERA MEJOR%' OR v_direccion LIKE '%TODO SERÁ MEJOR%' THEN
            SET v_barrio_id = (SELECT barrio_id FROM barrios_leon WHERE codigo_barrio = 'SUR-05');
        
        END IF;
        
        -- Actualizar cliente si se encontró barrio
        IF v_barrio_id IS NOT NULL THEN
            UPDATE clientes 
            SET barrio_id = v_barrio_id 
            WHERE cliente_id = v_cliente_id;
        END IF;
        
    END LOOP;
    
    CLOSE cur;
    
    -- Mostrar resultados
    SELECT 
        'Clientes actualizados con barrio' as descripcion,
        COUNT(*) as cantidad
    FROM clientes 
    WHERE barrio_id IS NOT NULL;
    
END$$
DELIMITER ;

-- 5. Crear tabla de relación rutas-barrios (opcional para optimización de rutas)
CREATE TABLE IF NOT EXISTS `rutas_barrios` (
  `ruta_barrio_id` int(11) NOT NULL AUTO_INCREMENT,
  `ruta_id` int(11) NOT NULL,
  `barrio_id` int(11) NOT NULL,
  `orden_visita` int(11) DEFAULT 0,
  `tiempo_estimado_minutos` int(11) DEFAULT 30,
  `observaciones` text DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_asignacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ruta_barrio_id`),
  UNIQUE KEY `uk_ruta_barrio` (`ruta_id`, `barrio_id`),
  KEY `idx_ruta` (`ruta_id`),
  KEY `idx_barrio` (`barrio_id`),
  CONSTRAINT `fk_rutas_barrios_ruta` FOREIGN KEY (`ruta_id`) REFERENCES `rutas` (`ruta_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_rutas_barrios_barrio` FOREIGN KEY (`barrio_id`) REFERENCES `barrios_leon` (`barrio_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Relación entre rutas de cobranza y barrios';

-- 6. Crear vista de estadísticas por barrio
CREATE OR REPLACE VIEW `v_estadisticas_barrios` AS
SELECT 
    b.barrio_id,
    b.codigo_barrio,
    b.nombre_barrio,
    b.zona,
    b.es_historico,
    COUNT(c.cliente_id) as total_clientes,
    COUNT(CASE WHEN c.cliente_estado_prestamo = 'con prestamo' THEN 1 END) as clientes_con_prestamo,
    COUNT(CASE WHEN c.cliente_estado_prestamo = 'sin prestamo' THEN 1 END) as clientes_sin_prestamo,
    ROUND(COUNT(CASE WHEN c.cliente_estado_prestamo = 'con prestamo' THEN 1 END) * 100.0 / 
          NULLIF(COUNT(c.cliente_id), 0), 2) as porcentaje_con_prestamo
FROM barrios_leon b
LEFT JOIN clientes c ON b.barrio_id = c.barrio_id
WHERE b.estado = 'ACTIVO'
GROUP BY b.barrio_id, b.codigo_barrio, b.nombre_barrio, b.zona, b.es_historico
ORDER BY total_clientes DESC;

-- 7. Queries de ejemplo y verificación
-- Listar todos los barrios con su información
SELECT 
    codigo_barrio,
    nombre_barrio,
    zona,
    CASE WHEN es_historico = 1 THEN 'Histórico' ELSE 'Moderno' END as tipo,
    descripcion
FROM barrios_leon 
WHERE estado = 'ACTIVO'
ORDER BY zona, nombre_barrio;

-- Contar barrios por zona
SELECT 
    zona,
    COUNT(*) as total_barrios,
    SUM(es_historico) as historicos,
    COUNT(*) - SUM(es_historico) as modernos
FROM barrios_leon 
WHERE estado = 'ACTIVO'
GROUP BY zona
ORDER BY zona;

-- Comentarios de uso
/*
INSTRUCCIONES DE USO:

1. EJECUTAR PRIMERO: catalogo_barrios_leon_nicaragua.sql
2. EJECUTAR DESPUÉS: este archivo (integracion_barrios_sistema.sql)

3. PARA ACTUALIZAR BARRIOS DE CLIENTES EXISTENTES:
   CALL sp_actualizar_barrios_clientes();

4. PARA BUSCAR BARRIO POR NOMBRE:
   SELECT fn_buscar_barrio_id('Sutiava');

5. PARA VER CLIENTES CON INFORMACIÓN DE BARRIOS:
   SELECT * FROM v_clientes_barrios;

6. PARA VER ESTADÍSTICAS POR BARRIO:
   SELECT * FROM v_estadisticas_barrios;

BENEFICIOS:
- Mejor organización de direcciones
- Optimización de rutas de cobranza
- Estadísticas geográficas de clientes
- Facilita búsquedas por zona
- Coordenadas GPS para futuras integraciones

NOTAS:
- El campo barrio_id en clientes es OPCIONAL
- Se puede seguir usando el campo cliente_direccion como texto libre
- La vista v_clientes_barrios combina ambos enfoques
*/ 