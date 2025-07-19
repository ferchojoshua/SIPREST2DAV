-- =====================================================
-- INSERTAR BARRIOS DE LEÓN COMO RUTAS - SCRIPT DIRECTO
-- =====================================================
-- Ejecutar este archivo en phpMyAdmin o línea de comandos
-- Aprovecha sistema de rutas existente de SIPREST

-- Obtener ID de sucursal León
SET @sucursal_leon = (SELECT id FROM sucursales WHERE nombre LIKE '%Leon%' OR codigo LIKE '%LE%' LIMIT 1);

-- Insertar los 32 barrios de León como rutas
INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, ruta_estado, ruta_orden, usuario_creacion) VALUES

-- BARRIOS HISTÓRICOS (Azules) - 10 barrios
('Centro Histórico', 'Barrio central con Catedral y edificios coloniales', 'BH-CENTRO', '#1e3a8a', @sucursal_leon, 'activa', 101, 1),
('San Sebastián', 'Barrio histórico de la antigua Cárcel la 21', 'BH-SEBASTIAN', '#1e40af', @sucursal_leon, 'activa', 102, 1),
('Sutiava', 'Barrio indígena histórico con iglesia de 1698', 'BH-SUTIAVA', '#1d4ed8', @sucursal_leon, 'activa', 103, 1),
('El Calvario', 'Barrio barroco histórico del siglo XVIII', 'BH-CALVARIO', '#2563eb', @sucursal_leon, 'activa', 104, 1),
('La Recolección', 'Zona de iglesia barroca de 1786', 'BH-RECOLECC', '#3b82f6', @sucursal_leon, 'activa', 105, 1),
('San Francisco', 'Barrio del Convento histórico de 1639', 'BH-SANFRAN', '#60a5fa', @sucursal_leon, 'activa', 106, 1),
('La Merced', 'Zona histórica de UNAN y convento colonial', 'BH-MERCED', '#93c5fd', @sucursal_leon, 'activa', 107, 1),
('San Felipe', 'Barrio histórico de iglesia de 1685', 'BH-SANFELIPE', '#bfdbfe', @sucursal_leon, 'activa', 108, 1),
('Guadalupe', 'Barrio de cementerio histórico y puente colonial', 'BH-GUADALUPE', '#dbeafe', @sucursal_leon, 'activa', 109, 1),
('El Laborío', 'Zona histórica de San Nicolás del Laborío (1618)', 'BH-LABORIO', '#eff6ff', @sucursal_leon, 'activa', 110, 1),

-- ZONA NORTE (Verdes) - 6 barrios
('Villa 23 de Julio', 'Barrio residencial zona norte', 'BN-VILLA23', '#059669', @sucursal_leon, 'activa', 201, 1),
('Praderas Nueva León', 'Conjunto residencial moderno', 'BN-PRADERAS', '#0d9488', @sucursal_leon, 'activa', 202, 1),
('Oscar Pérez Cassar', 'Barrio residencial consolidado', 'BN-OSCARPER', '#0f766e', @sucursal_leon, 'activa', 203, 1),
('Los Ángeles', 'Barrio popular zona norte', 'BN-ANGELES', '#115e59', @sucursal_leon, 'activa', 204, 1),
('San José', 'Barrio consolidado zona norte', 'BN-SANJOSE', '#134e4a', @sucursal_leon, 'activa', 205, 1),
('Salinas Grandes', 'Barrio periférico norte', 'BN-SALINAS', '#1f2937', @sucursal_leon, 'activa', 206, 1),

-- ZONA SUR (Naranjas) - 6 barrios
('Pueblo Nuevo', 'Barrio tradicional zona sur', 'BS-PUEBNUEVO', '#ea580c', @sucursal_leon, 'activa', 301, 1),
('Santa Ana', 'Barrio residencial zona sur', 'BS-SANTAANA', '#dc2626', @sucursal_leon, 'activa', 302, 1),
('La Providencia', 'Barrio popular zona sur', 'BS-PROVIDEN', '#b91c1c', @sucursal_leon, 'activa', 303, 1),
('Los Pescaditos', 'Barrio tradicional de pescadores', 'BS-PESCADIT', '#991b1b', @sucursal_leon, 'activa', 304, 1),
('Todo Será Mejor', 'Barrio de esperanza zona sur', 'BS-TODOSERA', '#7f1d1d', @sucursal_leon, 'activa', 305, 1),
('Terminal de Buses', 'Zona de terminal de transporte', 'BS-TERMINAL', '#78716c', @sucursal_leon, 'activa', 306, 1),

-- ZONA ESTE (Morados) - 5 barrios
('Las Brisas', 'Barrio residencial zona este', 'BE-BRISAS', '#7c3aed', @sucursal_leon, 'activa', 401, 1),
('El Recreo', 'Barrio familiar zona este', 'BE-RECREO', '#8b5cf6', @sucursal_leon, 'activa', 402, 1),
('Las Flores', 'Barrio residencial zona este', 'BE-FLORES', '#a78bfa', @sucursal_leon, 'activa', 403, 1),
('El Progreso', 'Barrio en desarrollo zona este', 'BE-PROGRESO', '#c4b5fd', @sucursal_leon, 'activa', 404, 1),
('Los Rieles', 'Zona de antigua estación ferrocarril', 'BE-RIELES', '#ddd6fe', @sucursal_leon, 'activa', 405, 1),

-- ZONA OESTE (Rosas) - 4 barrios
('Subtiava Extensión', 'Zona occidental extendida', 'BO-SUBTIAVA', '#ec4899', @sucursal_leon, 'activa', 501, 1),
('Las Palmeras', 'Barrio residencial zona oeste', 'BO-PALMERAS', '#f472b6', @sucursal_leon, 'activa', 502, 1),
('Los Laureles', 'Barrio zona oeste', 'BO-LAURELES', '#f9a8d4', @sucursal_leon, 'activa', 503, 1),
('Las Cañadas', 'Barrio periférico oeste', 'BO-CANADAS', '#fbbf24', @sucursal_leon, 'activa', 504, 1),

-- ZONAS ESPECIALES (Grises) - 2 zonas
('Mercado Central', 'Zona comercial central', 'ZE-MERCADO', '#6b7280', @sucursal_leon, 'activa', 601, 1),
('Universidad UNAN', 'Campus universitario', 'ZE-UNIVERSI', '#9ca3af', @sucursal_leon, 'activa', 602, 1);

-- Verificar inserción
SELECT 
    'BARRIOS INSERTADOS' as resultado,
    COUNT(*) as total_barrios
FROM rutas 
WHERE sucursal_id = @sucursal_leon 
AND ruta_codigo LIKE 'B%-%' OR ruta_codigo LIKE 'ZE-%';

-- Mostrar resumen por zonas
SELECT 
    CASE 
        WHEN ruta_codigo LIKE 'BH-%' THEN '🏛️ HISTÓRICOS'
        WHEN ruta_codigo LIKE 'BN-%' THEN '🟢 NORTE'
        WHEN ruta_codigo LIKE 'BS-%' THEN '🟠 SUR'
        WHEN ruta_codigo LIKE 'BE-%' THEN '🟣 ESTE'
        WHEN ruta_codigo LIKE 'BO-%' THEN '🩷 OESTE'
        WHEN ruta_codigo LIKE 'ZE-%' THEN '⚪ ESPECIALES'
    END as zona,
    COUNT(*) as cantidad
FROM rutas 
WHERE sucursal_id = @sucursal_leon 
AND (ruta_codigo LIKE 'B%-%' OR ruta_codigo LIKE 'ZE-%')
GROUP BY 
    CASE 
        WHEN ruta_codigo LIKE 'BH-%' THEN 1
        WHEN ruta_codigo LIKE 'BN-%' THEN 2
        WHEN ruta_codigo LIKE 'BS-%' THEN 3
        WHEN ruta_codigo LIKE 'BE-%' THEN 4
        WHEN ruta_codigo LIKE 'BO-%' THEN 5
        WHEN ruta_codigo LIKE 'ZE-%' THEN 6
    END;

-- Listo! Ve al módulo Rutas en tu sistema web para ver los 32 barrios 