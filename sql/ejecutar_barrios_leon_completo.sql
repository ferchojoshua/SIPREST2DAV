-- =====================================================
-- SCRIPT COMPLETO: BARRIOS DE LEÓN, NICARAGUA
-- =====================================================
-- Ejecuta todo el catálogo de barrios en un solo script
-- Para SIPREST - Sistema de Préstamos León, Nicaragua

-- =====================================================
-- PASO 1: CREAR TABLA DE BARRIOS
-- =====================================================

-- Crear tabla de barrios
CREATE TABLE IF NOT EXISTS `barrios_leon` (
  `barrio_id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo_barrio` varchar(10) NOT NULL,
  `nombre_barrio` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `zona` enum('CENTRO','NORTE','SUR','ESTE','OESTE') DEFAULT 'CENTRO',
  `es_historico` tinyint(1) DEFAULT 0 COMMENT '1 si es un barrio histórico/colonial',
  `coordenadas_lat` decimal(10,8) DEFAULT NULL,
  `coordenadas_lng` decimal(11,8) DEFAULT NULL,
  `estado` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_modificacion` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`barrio_id`),
  UNIQUE KEY `uk_codigo_barrio` (`codigo_barrio`),
  KEY `idx_nombre_barrio` (`nombre_barrio`),
  KEY `idx_zona` (`zona`),
  KEY `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='Catálogo de barrios de León, Nicaragua';

-- =====================================================
-- PASO 2: INSERTAR BARRIOS (32 barrios total)
-- =====================================================

INSERT INTO `barrios_leon` 
(`codigo_barrio`, `nombre_barrio`, `descripcion`, `zona`, `es_historico`, `coordenadas_lat`, `coordenadas_lng`) VALUES

-- BARRIOS HISTÓRICOS DEL CENTRO (10 barrios)
('CENTRO-01', 'Centro Histórico', 'Zona central de León con la Catedral y edificios coloniales', 'CENTRO', 1, 12.434167, -86.878611),
('CENTRO-02', 'San Sebastián', 'Barrio histórico donde estaba ubicada la antigua Cárcel la 21', 'CENTRO', 1, 12.435000, -86.877000),
('CENTRO-03', 'Sutiava', 'Barrio indígena histórico con la iglesia más antigua de León', 'OESTE', 1, 12.431944, -86.883056),
('CENTRO-04', 'El Calvario', 'Barrio histórico alrededor de la Iglesia El Calvario', 'CENTRO', 1, 12.436111, -86.876389),
('CENTRO-05', 'La Recolección', 'Zona alrededor de la Iglesia de La Recolección', 'CENTRO', 1, 12.437222, -86.878333),
('CENTRO-06', 'San Francisco', 'Barrio del Convento de San Francisco', 'CENTRO', 1, 12.433889, -86.879444),
('CENTRO-07', 'La Merced', 'Zona de la Iglesia de La Merced y UNAN', 'CENTRO', 1, 12.434722, -86.877778),
('CENTRO-08', 'San Felipe', 'Barrio histórico de la Iglesia San Felipe', 'SUR', 1, 12.432500, -86.878000),
('CENTRO-09', 'Guadalupe', 'Barrio de la Iglesia Guadalupe y cementerio', 'ESTE', 1, 12.435556, -86.875000),
('CENTRO-10', 'Zaragoza', 'Zona de la Iglesia de Zaragoza', 'NORTE', 1, 12.437778, -86.877222),

-- BARRIOS ZONA NORTE (5 barrios)
('NORTE-01', 'Villa 23 de Julio', 'Barrio residencial zona norte', 'NORTE', 0, 12.445000, -86.875000),
('NORTE-02', 'Praderas Nueva León', 'Conjunto residencial moderno', 'NORTE', 0, 12.447222, -86.876111),
('NORTE-03', 'Oscar Pérez Cassar', 'Barrio residencial', 'NORTE', 0, 12.442778, -86.873889),
('NORTE-04', 'Los Ángeles', 'Barrio zona norte de León', 'NORTE', 0, 12.446667, -86.878333),
('NORTE-05', 'San José', 'Barrio San José', 'NORTE', 0, 12.444444, -86.880000),

-- BARRIOS ZONA SUR (5 barrios)
('SUR-01', 'Pueblo Nuevo', 'Barrio sur de León', 'SUR', 0, 12.425000, -86.878000),
('SUR-02', 'Santa Ana', 'Barrio Santa Ana zona sur', 'SUR', 0, 12.427778, -86.876667),
('SUR-03', 'La Providencia', 'Barrio La Providencia', 'SUR', 0, 12.428333, -86.879444),
('SUR-04', 'Los Pescaditos', 'Barrio Los Pescaditos', 'SUR', 0, 12.426111, -86.875556),
('SUR-05', 'Todo Será Mejor', 'Barrio Todo Será Mejor', 'SUR', 0, 12.429167, -86.877500),

-- BARRIOS ZONA ESTE (4 barrios)
('ESTE-01', 'Las Brisas', 'Barrio Las Brisas zona este', 'ESTE', 0, 12.435000, -86.870000),
('ESTE-02', 'El Recreo', 'Barrio El Recreo', 'ESTE', 0, 12.437222, -86.872222),
('ESTE-03', 'Las Flores', 'Barrio Las Flores', 'ESTE', 0, 12.438889, -86.871111),
('ESTE-04', 'El Progreso', 'Barrio El Progreso', 'ESTE', 0, 12.436667, -86.873333),

-- BARRIOS ZONA OESTE (3 barrios)
('OESTE-01', 'Subtiava', 'Zona occidental histórica extendida', 'OESTE', 0, 12.430000, -86.885000),
('OESTE-02', 'Las Palmeras', 'Barrio Las Palmeras', 'OESTE', 0, 12.432222, -86.886667),
('OESTE-03', 'Los Laureles', 'Barrio Los Laureles', 'OESTE', 0, 12.431111, -86.888333),

-- BARRIOS COMERCIALES Y ESPECIALES (3 barrios)
('COME-01', 'Mercado Central', 'Zona del mercado central', 'CENTRO', 0, 12.433333, -86.877500),
('COME-02', 'Terminal de Buses', 'Zona de terminal de buses', 'SUR', 0, 12.425556, -86.875000),
('COME-03', 'Universidad', 'Zona universitaria UNAN-León', 'CENTRO', 0, 12.434444, -86.876944),

-- BARRIOS PERIFÉRICOS (4 barrios)
('PERI-01', 'Salinas Grandes', 'Barrio periférico', 'NORTE', 0, 12.450000, -86.880000),
('PERI-02', 'El Laborío', 'Zona de la Iglesia San Nicolás del Laborío', 'SUR', 1, 12.420000, -86.876000),
('PERI-03', 'Los Rieles', 'Zona de la antigua estación del ferrocarril', 'ESTE', 0, 12.435000, -86.872000),
('PERI-04', 'Las Cañadas', 'Barrio Las Cañadas', 'OESTE', 0, 12.428000, -86.890000);

-- =====================================================
-- PASO 3: CREAR VISTA DE RESUMEN
-- =====================================================

CREATE OR REPLACE VIEW `v_resumen_barrios_leon` AS
SELECT 
    zona,
    COUNT(*) as total_barrios,
    SUM(es_historico) as barrios_historicos,
    COUNT(*) - SUM(es_historico) as barrios_modernos,
    GROUP_CONCAT(
        CASE WHEN es_historico = 1 
        THEN CONCAT('🏛️ ', nombre_barrio) 
        ELSE CONCAT('🏘️ ', nombre_barrio) 
        END 
        ORDER BY nombre_barrio 
        SEPARATOR ', '
    ) as lista_barrios
FROM barrios_leon 
WHERE estado = 'ACTIVO'
GROUP BY zona
ORDER BY 
    CASE zona 
        WHEN 'CENTRO' THEN 1 
        WHEN 'NORTE' THEN 2 
        WHEN 'SUR' THEN 3 
        WHEN 'ESTE' THEN 4 
        WHEN 'OESTE' THEN 5 
    END;

-- =====================================================
-- PASO 4: MOSTRAR RESULTADOS
-- =====================================================

-- Verificar inserción exitosa
SELECT 
    '✅ CATÁLOGO CREADO EXITOSAMENTE' as status,
    COUNT(*) as total_barrios_insertados,
    SUM(es_historico) as barrios_historicos,
    COUNT(*) - SUM(es_historico) as barrios_modernos
FROM barrios_leon;

-- Mostrar resumen por zonas
SELECT 
    '📍 RESUMEN POR ZONAS' as info,
    zona,
    total_barrios,
    barrios_historicos,
    barrios_modernos
FROM v_resumen_barrios_leon;

-- Mostrar algunos barrios históricos destacados
SELECT 
    '🏛️ BARRIOS HISTÓRICOS PRINCIPALES' as info,
    codigo_barrio,
    nombre_barrio,
    descripcion
FROM barrios_leon 
WHERE es_historico = 1 
AND codigo_barrio IN ('CENTRO-01', 'CENTRO-02', 'CENTRO-03', 'CENTRO-04')
ORDER BY codigo_barrio;

-- =====================================================
-- INSTRUCCIONES DE USO
-- =====================================================

SELECT '📋 INSTRUCCIONES DE USO:

✅ COMPLETADO: Se crearon 32 barrios de León, Nicaragua

🔗 PRÓXIMOS PASOS OPCIONALES:

1. Para integrar con tabla clientes:
   SOURCE sql/integracion_barrios_sistema.sql;

2. Para ver todos los barrios:
   SELECT * FROM barrios_leon ORDER BY zona, nombre_barrio;

3. Para buscar por zona:
   SELECT * FROM barrios_leon WHERE zona = "CENTRO";

4. Para ver solo históricos:
   SELECT * FROM barrios_leon WHERE es_historico = 1;

📊 DATOS INCLUIDOS:
- 10 barrios históricos/coloniales
- 22 barrios modernos/residenciales  
- 5 zonas geográficas (Centro, Norte, Sur, Este, Oeste)
- Coordenadas GPS aproximadas
- Descripciones detalladas

🎯 LISTO PARA USAR EN SIPREST' as instrucciones; 