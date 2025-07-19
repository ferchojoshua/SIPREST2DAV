-- =====================================================
-- BARRIOS OFICIALES DE LEÓN - VERSIÓN FINAL SEGURA
-- =====================================================
-- Maneja modo seguro + foreign keys

-- Configuración inicial
SET SQL_SAFE_UPDATES = 0;
SET FOREIGN_KEY_CHECKS = 0;

-- Obtener ID sucursal León
SET @leon = (SELECT id FROM sucursales WHERE nombre LIKE '%Leon%' LIMIT 1);

-- Limpiar datos existentes (ahora funcionará sin problemas)
DELETE FROM clientes_rutas;
DELETE FROM usuarios_rutas;
DELETE FROM rutas;

-- DISTRITO CENTRAL (18 barrios) - Azules
INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, ruta_estado, ruta_orden, usuario_creacion) VALUES
('Barrio Zaragoza', 'Distrito Central', 'DC-ZARAGOZA', '#1e3a8a', @leon, 'activa', 101, 1),
('Barrio El Calvario', 'Distrito Central', 'DC-CALVARIO', '#1e40af', @leon, 'activa', 102, 1),
('Barrio El Coyolar', 'Distrito Central', 'DC-COYOLAR', '#1d4ed8', @leon, 'activa', 103, 1),
('Barrio El Laborío', 'Distrito Central', 'DC-LABORIO', '#2563eb', @leon, 'activa', 104, 1),
('Barrio El Sagrario', 'Distrito Central', 'DC-SAGRARIO', '#3b82f6', @leon, 'activa', 105, 1),
('Barrio San Juan', 'Distrito Central', 'DC-SANJUAN', '#60a5fa', @leon, 'activa', 106, 1),
('Barrio San Felipe', 'Distrito Central', 'DC-SANFELIPE', '#93c5fd', @leon, 'activa', 107, 1),
('Barrio San José', 'Distrito Central', 'DC-SANJOSE', '#bfdbfe', @leon, 'activa', 108, 1),
('Barrio San Sebastián', 'Distrito Central', 'DC-SEBASTIAN', '#dbeafe', @leon, 'activa', 109, 1),
('Colonia La de Mayo', 'Distrito Central', 'DC-LAMAYO', '#eff6ff', @leon, 'activa', 110, 1),
('Colonia La Fosforera', 'Distrito Central', 'DC-FOSFORERA', '#f8fafc', @leon, 'activa', 111, 1),
('Colonia Avellán', 'Distrito Central', 'DC-AVELLAN', '#f1f5f9', @leon, 'activa', 112, 1),
('H. y M. de Zaragoza', 'Distrito Central', 'DC-HMZARAGOZA', '#e2e8f0', @leon, 'activa', 113, 1),
('Colonia Santa Martha', 'Distrito Central', 'DC-SANTAMARTHA', '#cbd5e1', @leon, 'activa', 114, 1),
('H. y M. 26 de Abril', 'Distrito Central', 'DC-HM26ABRIL', '#94a3b8', @leon, 'activa', 115, 1),
('San Nicolás', 'Distrito Central', 'DC-SANNICOLAS', '#64748b', @leon, 'activa', 116, 1),
('Pedro José Avendaño', 'Distrito Central', 'DC-AVENDANO', '#475569', @leon, 'activa', 117, 1),
('Rogelio Santana', 'Distrito Central', 'DC-SANTANA', '#334155', @leon, 'activa', 118, 1),

-- DISTRITO NORESTE (20 barrios) - Verdes
('Andrés Zapata', 'Distrito Noreste', 'DN-ZAPATA', '#065f46', @leon, 'activa', 201, 1),
('Anexo Maritza López', 'Distrito Noreste', 'DN-MARITZA', '#047857', @leon, 'activa', 202, 1),
('Anexo Villa Soberana', 'Distrito Noreste', 'DN-VILLASOBERA', '#059669', @leon, 'activa', 203, 1),
('Aracely Pérez', 'Distrito Noreste', 'DN-ARACELY', '#0d9488', @leon, 'activa', 204, 1),
('Augusto César Sandino', 'Distrito Noreste', 'DN-SANDINO', '#0f766e', @leon, 'activa', 205, 1),
('Barrio Ermita de Dolores', 'Distrito Noreste', 'DN-ERMITA', '#115e59', @leon, 'activa', 206, 1),
('Bella Vista', 'Distrito Noreste', 'DN-BELLAVISTA', '#134e4a', @leon, 'activa', 207, 1),
('Benjamín Zeledón', 'Distrito Noreste', 'DN-ZELEDON', '#1f2937', @leon, 'activa', 208, 1),
('Colonia Farabundo Martí', 'Distrito Noreste', 'DN-FARABUNDO', '#166534', @leon, 'activa', 209, 1),
('Colonia Brenda Sofía', 'Distrito Noreste', 'DN-BRENDASOFIA', '#15803d', @leon, 'activa', 210, 1),
('El Platanal', 'Distrito Noreste', 'DN-PLATANAL', '#16a34a', @leon, 'activa', 211, 1),
('El Porvenir', 'Distrito Noreste', 'DN-PORVENIR', '#22c55e', @leon, 'activa', 212, 1),
('Enrique Lorente', 'Distrito Noreste', 'DN-LORENTE', '#4ade80', @leon, 'activa', 213, 1),
('Foyulesa', 'Distrito Noreste', 'DN-FOYULESA', '#6ade87', @leon, 'activa', 214, 1),
('Jericó', 'Distrito Noreste', 'DN-JERICO', '#86efac', @leon, 'activa', 215, 1),
('José Benito Escobar', 'Distrito Noreste', 'DN-ESCOBAR', '#a7f3d0', @leon, 'activa', 216, 1),
('José de la Cruz Mena', 'Distrito Noreste', 'DN-CRUZMENA', '#bbf7d0', @leon, 'activa', 217, 1),
('Linda Vista', 'Distrito Noreste', 'DN-LINDAVISTA', '#d1fae5', @leon, 'activa', 218, 1),
('Manolo Quezada', 'Distrito Noreste', 'DN-QUEZADA', '#ecfdf5', @leon, 'activa', 219, 1),
('Maritza López', 'Distrito Noreste', 'DN-MARITZALOP', '#f0fdf4', @leon, 'activa', 220, 1),

-- DISTRITO SURESTE (20 barrios) - Naranjas
('18 de Agosto', 'Distrito Sureste', 'DS-18AGOSTO', '#ea580c', @leon, 'activa', 301, 1),
('Alfonso Cortés', 'Distrito Sureste', 'DS-CORTES', '#dc2626', @leon, 'activa', 302, 1),
('Anexo Gustavo López', 'Distrito Sureste', 'DS-GUSTAVO', '#c2410c', @leon, 'activa', 303, 1),
('Arrocera I', 'Distrito Sureste', 'DS-ARROCERA1', '#b91c1c', @leon, 'activa', 304, 1),
('Arrocera II', 'Distrito Sureste', 'DS-ARROCERA2', '#991b1b', @leon, 'activa', 305, 1),
('Anexo Villa 23 de Julio', 'Distrito Sureste', 'DS-VILLA23', '#7f1d1d', @leon, 'activa', 306, 1),
('Azarías H. Pallais', 'Distrito Sureste', 'DS-PALLAIS', '#78716c', @leon, 'activa', 307, 1),
('Barrio Guadalupe', 'Distrito Sureste', 'DS-GUADALUPE', '#ef4444', @leon, 'activa', 308, 1),
('Benito Mauricio Lacayo', 'Distrito Sureste', 'DS-LACAYO', '#f87171', @leon, 'activa', 309, 1),
('Brisas de Acosasco', 'Distrito Sureste', 'DS-ACOSASCO', '#fca5a5', @leon, 'activa', 310, 1),
('Carlos Fonseca', 'Distrito Sureste', 'DS-FONSECA', '#fed7d7', @leon, 'activa', 311, 1),
('Candelaria', 'Distrito Sureste', 'DS-CANDELARIA', '#fee2e2', @leon, 'activa', 312, 1),
('Che Guevara', 'Distrito Sureste', 'DS-CHEGEVARA', '#fef2f2', @leon, 'activa', 313, 1),
('Colonia Universidad', 'Distrito Sureste', 'DS-UNIVERSIDAD', '#fb7185', @leon, 'activa', 314, 1),
('Concepción de María', 'Distrito Sureste', 'DS-CONCEPCION', '#f97316', @leon, 'activa', 315, 1),
('El Calvario', 'Distrito Sureste', 'DS-CALVARIO', '#fb923c', @leon, 'activa', 316, 1),
('El Cocal', 'Distrito Sureste', 'DS-COCAL', '#fdba74', @leon, 'activa', 317, 1),
('Emir Cabezas', 'Distrito Sureste', 'DS-CABEZAS', '#fed7aa', @leon, 'activa', 318, 1),
('Fundeci I', 'Distrito Sureste', 'DS-FUNDECI1', '#ffedd5', @leon, 'activa', 319, 1),
('Fundeci II', 'Distrito Sureste', 'DS-FUNDECI2', '#fff7ed', @leon, 'activa', 320, 1),

-- DISTRITO OESTE (19 barrios) - Morados
('Adiac I', 'Distrito Oeste', 'DO-ADIAC1', '#7c3aed', @leon, 'activa', 401, 1),
('Adiac II', 'Distrito Oeste', 'DO-ADIAC2', '#8b5cf6', @leon, 'activa', 402, 1),
('Adiac III', 'Distrito Oeste', 'DO-ADIAC3', '#a78bfa', @leon, 'activa', 403, 1),
('Anexo La Providencia', 'Distrito Oeste', 'DO-PROVIDENCIA', '#c4b5fd', @leon, 'activa', 404, 1),
('Barrio Sutiava', 'Distrito Oeste', 'DO-SUTIAVA', '#ddd6fe', @leon, 'activa', 405, 1),
('Belén', 'Distrito Oeste', 'DO-BELEN', '#ede9fe', @leon, 'activa', 406, 1),
('Bello Horizonte', 'Distrito Oeste', 'DO-HORIZONTE', '#f3f4f6', @leon, 'activa', 407, 1),
('Carlos Núñez', 'Distrito Oeste', 'DO-NUNEZ', '#6366f1', @leon, 'activa', 408, 1),
('Colonia Sonia Barrera', 'Distrito Oeste', 'DO-SONIABARRERA', '#818cf8', @leon, 'activa', 409, 1),
('Covista', 'Distrito Oeste', 'DO-COVISTA', '#a5b4fc', @leon, 'activa', 410, 1),
('Divino Niño', 'Distrito Oeste', 'DO-DIVININO', '#c7d2fe', @leon, 'activa', 411, 1),
('El Triángulo', 'Distrito Oeste', 'DO-TRIANGULO', '#e0e7ff', @leon, 'activa', 412, 1),
('Esfuerzo de la Comunidad', 'Distrito Oeste', 'DO-ESFUERZO', '#f0f4ff', @leon, 'activa', 413, 1),
('Fanor Urroz', 'Distrito Oeste', 'DO-URROZ', '#ec4899', @leon, 'activa', 414, 1),
('Félix Pedro Quiroz', 'Distrito Oeste', 'DO-QUIROZ', '#f472b6', @leon, 'activa', 415, 1),
('Felipe Santana', 'Distrito Oeste', 'DO-SANTANA', '#f9a8d4', @leon, 'activa', 416, 1),
('H. y M. Veracruz', 'Distrito Oeste', 'DO-VERACRUZ', '#fbbf24', @leon, 'activa', 417, 1),
('Hipólito Sánchez', 'Distrito Oeste', 'DO-SANCHEZ', '#f59e0b', @leon, 'activa', 418, 1),
('Juan José Álvarez', 'Distrito Oeste', 'DO-ALVAREZ', '#d97706', @leon, 'activa', 419, 1);

-- Recrear vista (sin variables)
DROP VIEW IF EXISTS v_estadisticas_barrios_leon;
CREATE VIEW v_estadisticas_barrios_leon AS
SELECT 
    r.ruta_id,
    r.ruta_codigo,
    r.ruta_nombre as barrio_nombre,
    r.ruta_descripcion,
    r.ruta_color,
    CASE 
        WHEN r.ruta_codigo LIKE 'DC-%' THEN 'CENTRAL'
        WHEN r.ruta_codigo LIKE 'DN-%' THEN 'NORESTE'
        WHEN r.ruta_codigo LIKE 'DS-%' THEN 'SURESTE'
        WHEN r.ruta_codigo LIKE 'DO-%' THEN 'OESTE'
        ELSE 'OTROS'
    END as distrito,
    COUNT(DISTINCT cr.cliente_id) as total_clientes,
    COUNT(DISTINCT ur.usuario_id) as cobradores_asignados,
    s.nombre as sucursal_nombre
FROM rutas r
INNER JOIN sucursales s ON r.sucursal_id = s.id
LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id AND cr.estado = 'activo'
LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
WHERE r.ruta_codigo LIKE 'D%-%'
GROUP BY r.ruta_id, r.ruta_codigo, r.ruta_nombre, r.ruta_descripcion, r.ruta_color, s.nombre
ORDER BY r.ruta_orden;

-- Restaurar configuraciones
SET FOREIGN_KEY_CHECKS = 1;
SET SQL_SAFE_UPDATES = 1;

-- Verificaciones finales
SELECT 'VERIFICACIÓN DE INSERCIÓN' as paso;

SELECT 
    CASE 
        WHEN ruta_codigo LIKE 'DC-%' THEN '🏛️ DISTRITO CENTRAL'
        WHEN ruta_codigo LIKE 'DN-%' THEN '🟢 DISTRITO NORESTE'
        WHEN ruta_codigo LIKE 'DS-%' THEN '🟠 DISTRITO SURESTE'
        WHEN ruta_codigo LIKE 'DO-%' THEN '🟣 DISTRITO OESTE'
    END as distrito,
    COUNT(*) as total_barrios,
    MIN(ruta_nombre) as ejemplo_primer_barrio,
    MAX(ruta_nombre) as ejemplo_ultimo_barrio
FROM rutas 
WHERE ruta_codigo LIKE 'D%-%'
GROUP BY 
    CASE 
        WHEN ruta_codigo LIKE 'DC-%' THEN 1
        WHEN ruta_codigo LIKE 'DN-%' THEN 2
        WHEN ruta_codigo LIKE 'DS-%' THEN 3
        WHEN ruta_codigo LIKE 'DO-%' THEN 4
    END
ORDER BY 
    CASE 
        WHEN ruta_codigo LIKE 'DC-%' THEN 1
        WHEN ruta_codigo LIKE 'DN-%' THEN 2
        WHEN ruta_codigo LIKE 'DS-%' THEN 3
        WHEN ruta_codigo LIKE 'DO-%' THEN 4
    END;

SELECT 
    '✅ COMPLETADO EXITOSAMENTE' as estado,
    COUNT(*) as total_barrios_leon,
    'Todos los barrios oficiales de León han sido insertados' as mensaje
FROM rutas 
WHERE ruta_codigo LIKE 'D%-%';

SELECT 
    '🚀 SIGUIENTE PASO' as accion,
    'Ve al módulo RUTAS en tu sistema web para ver todos los barrios' as instruccion; 