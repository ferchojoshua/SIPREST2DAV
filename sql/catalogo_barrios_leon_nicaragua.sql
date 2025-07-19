-- =====================================================
-- CATÁLOGO DE BARRIOS DE LEÓN, NICARAGUA
-- =====================================================
-- Script para crear tabla de barrios de León, Nicaragua
-- Basado en información histórica y geográfica de la ciudad

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

-- Insertar barrios de León, Nicaragua
INSERT INTO `barrios_leon` 
(`codigo_barrio`, `nombre_barrio`, `descripcion`, `zona`, `es_historico`, `coordenadas_lat`, `coordenadas_lng`) VALUES

-- BARRIOS HISTÓRICOS DEL CENTRO
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

-- BARRIOS MODERNOS Y RESIDENCIALES
('NORTE-01', 'Villa 23 de Julio', 'Barrio residencial zona norte', 'NORTE', 0, 12.445000, -86.875000),
('NORTE-02', 'Praderas Nueva León', 'Conjunto residencial moderno', 'NORTE', 0, 12.447222, -86.876111),
('NORTE-03', 'Oscar Pérez Cassar', 'Barrio residencial', 'NORTE', 0, 12.442778, -86.873889),
('NORTE-04', 'Los Ángeles', 'Barrio zona norte de León', 'NORTE', 0, 12.446667, -86.878333),
('NORTE-05', 'San José', 'Barrio San José', 'NORTE', 0, 12.444444, -86.880000),

('SUR-01', 'Pueblo Nuevo', 'Barrio sur de León', 'SUR', 0, 12.425000, -86.878000),
('SUR-02', 'Santa Ana', 'Barrio Santa Ana zona sur', 'SUR', 0, 12.427778, -86.876667),
('SUR-03', 'La Providencia', 'Barrio La Providencia', 'SUR', 0, 12.428333, -86.879444),
('SUR-04', 'Los Pescaditos', 'Barrio Los Pescaditos', 'SUR', 0, 12.426111, -86.875556),
('SUR-05', 'Todo Será Mejor', 'Barrio Todo Será Mejor', 'SUR', 0, 12.429167, -86.877500),

('ESTE-01', 'Las Brisas', 'Barrio Las Brisas zona este', 'ESTE', 0, 12.435000, -86.870000),
('ESTE-02', 'El Recreo', 'Barrio El Recreo', 'ESTE', 0, 12.437222, -86.872222),
('ESTE-03', 'Las Flores', 'Barrio Las Flores', 'ESTE', 0, 12.438889, -86.871111),
('ESTE-04', 'El Progreso', 'Barrio El Progreso', 'ESTE', 0, 12.436667, -86.873333),

('OESTE-01', 'Subtiava', 'Zona occidental histórica extendida', 'OESTE', 0, 12.430000, -86.885000),
('OESTE-02', 'Las Palmeras', 'Barrio Las Palmeras', 'OESTE', 0, 12.432222, -86.886667),
('OESTE-03', 'Los Laureles', 'Barrio Los Laureles', 'OESTE', 0, 12.431111, -86.888333),

-- BARRIOS COMERCIALES Y ESPECIALES
('COME-01', 'Mercado Central', 'Zona del mercado central', 'CENTRO', 0, 12.433333, -86.877500),
('COME-02', 'Terminal de Buses', 'Zona de terminal de buses', 'SUR', 0, 12.425556, -86.875000),
('COME-03', 'Universidad', 'Zona universitaria UNAN-León', 'CENTRO', 0, 12.434444, -86.876944),

-- BARRIOS PERIFÉRICOS
('PERI-01', 'Salinas Grandes', 'Barrio periférico', 'NORTE', 0, 12.450000, -86.880000),
('PERI-02', 'El Laborío', 'Zona de la Iglesia San Nicolás del Laborío', 'SUR', 1, 12.420000, -86.876000),
('PERI-03', 'Los Rieles', 'Zona de la antigua estación del ferrocarril', 'ESTE', 0, 12.435000, -86.872000),
('PERI-04', 'Las Cañadas', 'Barrio Las Cañadas', 'OESTE', 0, 12.428000, -86.890000);

-- Verificar inserción
SELECT 
    COUNT(*) as total_barrios,
    SUM(es_historico) as barrios_historicos,
    COUNT(*) - SUM(es_historico) as barrios_modernos
FROM barrios_leon;

-- Mostrar barrios por zona
SELECT 
    zona,
    COUNT(*) as cantidad,
    GROUP_CONCAT(nombre_barrio ORDER BY nombre_barrio SEPARATOR ', ') as barrios
FROM barrios_leon 
WHERE estado = 'ACTIVO'
GROUP BY zona
ORDER BY zona;

-- Comentarios de información adicional
/*
INFORMACIÓN ADICIONAL SOBRE LOS BARRIOS DE LEÓN:

BARRIOS HISTÓRICOS PRINCIPALES:
- Centro Histórico: Corazón colonial de León con la Catedral Basílica
- San Sebastián: Barrio donde estaba la histórica Cárcel la 21 (1910-2000)
- Sutiava: Antiguo pueblo indígena, ahora barrio histórico con iglesia de 1698
- El Calvario: Barrio barroco leonés del siglo XVIII
- La Recolección: Zona de la iglesia barroca de 1786

REFERENCIAS GEOGRÁFICAS:
- León está ubicado en 12°26'N, 86°53'W
- Ciudad colonial fundada en 1610
- Segunda ciudad más grande de Nicaragua
- Capital histórica del departamento de León

FUENTES:
- UNESCO World Heritage Sites
- Wikipedia: León, Nicaragua
- Información histórica de iglesias coloniales
- Referencias turísticas oficiales de Nicaragua

NOTA: Las coordenadas son aproximadas basadas en la ubicación general
de León, Nicaragua. Para uso en sistema de préstamos/cobranza urbana.
*/ 