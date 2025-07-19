-- =====================================================
-- BARRIOS DE LEÓN COMO RUTAS - INTEGRACIÓN COMPLETA
-- =====================================================
-- Script para insertar los 32 barrios de León como rutas 
-- en el sistema existente de SIPREST
-- Aprovecha toda la funcionalidad de rutas ya implementada

-- =====================================================
-- PASO 1: VERIFICAR ESTRUCTURA EXISTENTE
-- =====================================================

-- Verificar que existe sucursal León
SELECT 
    '🔍 VERIFICANDO SUCURSAL LEÓN...' as info,
    id as sucursal_id,
    nombre,
    codigo,
    estado
FROM sucursales 
WHERE nombre LIKE '%Leon%' OR codigo LIKE '%LE%'
LIMIT 1;

-- Obtener ID de sucursal León para usar en las inserciones
SET @sucursal_leon_id = (
    SELECT id FROM sucursales 
    WHERE nombre LIKE '%Leon%' OR codigo LIKE '%LE%'
    LIMIT 1
);

-- =====================================================
-- PASO 2: LIMPIAR RUTAS GENÉRICAS EXISTENTES (OPCIONAL)
-- =====================================================
-- Solo si quieres reemplazar las rutas genéricas con barrios específicos
-- DESCOMENTA las siguientes líneas si deseas limpiar:

/*
DELETE FROM clientes_rutas WHERE ruta_id IN (
    SELECT ruta_id FROM rutas 
    WHERE ruta_codigo IN ('RT-CENTRO', 'RT-NORTE', 'RT-SUR', 'LE-SURESTE')
    AND sucursal_id = @sucursal_leon_id
);

DELETE FROM usuarios_rutas WHERE ruta_id IN (
    SELECT ruta_id FROM rutas 
    WHERE ruta_codigo IN ('RT-CENTRO', 'RT-NORTE', 'RT-SUR', 'LE-SURESTE')
    AND sucursal_id = @sucursal_leon_id
);

DELETE FROM rutas 
WHERE ruta_codigo IN ('RT-CENTRO', 'RT-NORTE', 'RT-SUR', 'LE-SURESTE')
AND sucursal_id = @sucursal_leon_id;
*/

-- =====================================================
-- PASO 3: INSERTAR BARRIOS HISTÓRICOS COMO RUTAS
-- =====================================================

INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, ruta_estado, ruta_orden, ruta_observaciones, usuario_creacion) VALUES

-- BARRIOS HISTÓRICOS DEL CENTRO (Colores azules - históricos)
('Centro Histórico', 'Barrio central con Catedral y edificios coloniales - Zona de alta densidad turística', 'BH-CENTRO', '#1e3a8a', @sucursal_leon_id, 'activa', 1, 'Barrio histórico colonial. Incluye: Catedral, Palacio Episcopal, museos', 1),
('San Sebastián', 'Barrio histórico donde estaba la Cárcel la 21 - Zona de memoria histórica', 'BH-SEBASTIAN', '#1e40af', @sucursal_leon_id, 'activa', 2, 'Barrio histórico. Incluye: antigua Cárcel la 21 (ahora museo)', 1),
('Sutiava', 'Barrio indígena histórico con iglesia de 1698 - Patrimonio cultural', 'BH-SUTIAVA', '#1d4ed8', @sucursal_leon_id, 'activa', 3, 'Barrio indígena histórico. Iglesia más antigua de León (1698)', 1),
('El Calvario', 'Barrio barroco histórico del siglo XVIII - Arquitectura colonial', 'BH-CALVARIO', '#2563eb', @sucursal_leon_id, 'activa', 4, 'Barrio histórico barroco. Iglesia del siglo XVIII', 1),
('La Recolección', 'Zona de iglesia barroca de 1786 - Centro religioso histórico', 'BH-RECOLECC', '#3b82f6', @sucursal_leon_id, 'activa', 5, 'Barrio histórico. Iglesia barroca de 1786', 1),
('San Francisco', 'Barrio del Convento histórico de 1639 - Zona monástica colonial', 'BH-SANFRAN', '#60a5fa', @sucursal_leon_id, 'activa', 6, 'Barrio histórico. Convento de San Francisco (1639)', 1),
('La Merced', 'Zona histórica de UNAN y convento colonial - Centro educativo', 'BH-MERCED', '#93c5fd', @sucursal_leon_id, 'activa', 7, 'Barrio histórico. Universidad UNAN y convento colonial', 1),
('San Felipe', 'Barrio histórico de iglesia de 1685 - Zona de comunidad afrodescendiente', 'BH-SANFELIPE', '#bfdbfe', @sucursal_leon_id, 'activa', 8, 'Barrio histórico. Iglesia de 1685 para comunidad afrodescendiente', 1),
('Guadalupe', 'Barrio de cementerio histórico y puente colonial - Zona patrimonial', 'BH-GUADALUPE', '#dbeafe', @sucursal_leon_id, 'activa', 9, 'Barrio histórico. Cementerio y puente de Guadalupe', 1),
('El Laborío', 'Zona histórica de San Nicolás del Laborío (1618) - Periferia colonial', 'BH-LABORIO', '#eff6ff', @sucursal_leon_id, 'activa', 10, 'Barrio histórico periférico. Iglesia San Nicolás (1618)', 1),

-- BARRIOS ZONA NORTE (Colores verdes)
('Villa 23 de Julio', 'Barrio residencial zona norte - Desarrollo urbano moderno', 'BN-VILLA23', '#059669', @sucursal_leon_id, 'activa', 11, 'Barrio residencial moderno zona norte', 1),
('Praderas Nueva León', 'Conjunto residencial moderno - Desarrollo habitacional planificado', 'BN-PRADERAS', '#0d9488', @sucursal_leon_id, 'activa', 12, 'Conjunto residencial planificado zona norte', 1),
('Oscar Pérez Cassar', 'Barrio residencial consolidado - Zona de clase media', 'BN-OSCARPER', '#0f766e', @sucursal_leon_id, 'activa', 13, 'Barrio residencial establecido zona norte', 1),
('Los Ángeles', 'Barrio popular zona norte - Comunidad establecida', 'BN-ANGELES', '#115e59', @sucursal_leon_id, 'activa', 14, 'Barrio popular zona norte de León', 1),
('San José', 'Barrio consolidado zona norte - Área residencial mixta', 'BN-SANJOSE', '#134e4a', @sucursal_leon_id, 'activa', 15, 'Barrio San José zona norte', 1),
('Salinas Grandes', 'Barrio periférico norte - Zona de expansión urbana', 'BN-SALINAS', '#1f2937', @sucursal_leon_id, 'activa', 16, 'Barrio periférico zona norte', 1),

-- BARRIOS ZONA SUR (Colores naranjas)
('Pueblo Nuevo', 'Barrio tradicional zona sur - Comunidad establecida', 'BS-PUEBNUEVO', '#ea580c', @sucursal_leon_id, 'activa', 17, 'Barrio tradicional zona sur de León', 1),
('Santa Ana', 'Barrio residencial zona sur - Área de desarrollo medio', 'BS-SANTAANA', '#dc2626', @sucursal_leon_id, 'activa', 18, 'Barrio Santa Ana zona sur', 1),
('La Providencia', 'Barrio popular zona sur - Comunidad trabajadora', 'BS-PROVIDEN', '#b91c1c', @sucursal_leon_id, 'activa', 19, 'Barrio La Providencia zona sur', 1),
('Los Pescaditos', 'Barrio tradicional zona sur - Área de pescadores tradicionales', 'BS-PESCADIT', '#991b1b', @sucursal_leon_id, 'activa', 20, 'Barrio Los Pescaditos zona sur', 1),
('Todo Será Mejor', 'Barrio de esperanza zona sur - Comunidad en desarrollo', 'BS-TODOSERA', '#7f1d1d', @sucursal_leon_id, 'activa', 21, 'Barrio Todo Será Mejor zona sur', 1),
('Terminal de Buses', 'Zona de terminal de transporte - Área comercial de servicios', 'BS-TERMINAL', '#78716c', @sucursal_leon_id, 'activa', 22, 'Zona de terminal de buses y servicios', 1),

-- BARRIOS ZONA ESTE (Colores morados)
('Las Brisas', 'Barrio residencial zona este - Área de clase media', 'BE-BRISAS', '#7c3aed', @sucursal_leon_id, 'activa', 23, 'Barrio Las Brisas zona este', 1),
('El Recreo', 'Barrio familiar zona este - Área residencial tranquila', 'BE-RECREO', '#8b5cf6', @sucursal_leon_id, 'activa', 24, 'Barrio El Recreo zona este', 1),
('Las Flores', 'Barrio residencial zona este - Desarrollo habitacional', 'BE-FLORES', '#a78bfa', @sucursal_leon_id, 'activa', 25, 'Barrio Las Flores zona este', 1),
('El Progreso', 'Barrio en desarrollo zona este - Área de crecimiento urbano', 'BE-PROGRESO', '#c4b5fd', @sucursal_leon_id, 'activa', 26, 'Barrio El Progreso zona este', 1),
('Los Rieles', 'Zona de antigua estación ferrocarril - Área histórica de transporte', 'BE-RIELES', '#ddd6fe', @sucursal_leon_id, 'activa', 27, 'Zona histórica de ferrocarril', 1),

-- BARRIOS ZONA OESTE (Colores rosas)
('Subtiava Extensión', 'Zona occidental extendida - Expansión del barrio histórico', 'BO-SUBTIAVA', '#ec4899', @sucursal_leon_id, 'activa', 28, 'Extensión occidental del histórico Sutiava', 1),
('Las Palmeras', 'Barrio residencial zona oeste - Área de palmeras características', 'BO-PALMERAS', '#f472b6', @sucursal_leon_id, 'activa', 29, 'Barrio Las Palmeras zona oeste', 1),
('Los Laureles', 'Barrio zona oeste - Área residencial con vegetación', 'BO-LAURELES', '#f9a8d4', @sucursal_leon_id, 'activa', 30, 'Barrio Los Laureles zona oeste', 1),
('Las Cañadas', 'Barrio periférico oeste - Zona de cañadas naturales', 'BO-CANADAS', '#fbbf24', @sucursal_leon_id, 'activa', 31, 'Barrio Las Cañadas zona oeste', 1),

-- ZONAS ESPECIALES (Colores grises)
('Mercado Central', 'Zona comercial central - Área de mercado y comercio', 'ZE-MERCADO', '#6b7280', @sucursal_leon_id, 'activa', 32, 'Zona comercial del mercado central', 1),
('Universidad UNAN', 'Campus universitario - Zona educativa y estudiantil', 'ZE-UNIVERSI', '#9ca3af', @sucursal_leon_id, 'activa', 33, 'Campus universitario UNAN-León', 1);

-- =====================================================
-- PASO 4: VERIFICAR INSERCIÓN Y MOSTRAR RESULTADOS
-- =====================================================

-- Contar rutas insertadas
SELECT 
    '✅ BARRIOS INSERTADOS COMO RUTAS' as info,
    COUNT(*) as total_rutas_barrios,
    COUNT(CASE WHEN ruta_codigo LIKE 'BH-%' THEN 1 END) as historicos,
    COUNT(CASE WHEN ruta_codigo LIKE 'BN-%' THEN 1 END) as zona_norte,
    COUNT(CASE WHEN ruta_codigo LIKE 'BS-%' THEN 1 END) as zona_sur,
    COUNT(CASE WHEN ruta_codigo LIKE 'BE-%' THEN 1 END) as zona_este,
    COUNT(CASE WHEN ruta_codigo LIKE 'BO-%' THEN 1 END) as zona_oeste,
    COUNT(CASE WHEN ruta_codigo LIKE 'ZE-%' THEN 1 END) as zonas_especiales
FROM rutas 
WHERE sucursal_id = @sucursal_leon_id
AND (ruta_codigo LIKE 'BH-%' OR ruta_codigo LIKE 'BN-%' OR ruta_codigo LIKE 'BS-%' OR 
     ruta_codigo LIKE 'BE-%' OR ruta_codigo LIKE 'BO-%' OR ruta_codigo LIKE 'ZE-%');

-- Mostrar rutas por zona con colores
SELECT 
    '📍 RUTAS POR ZONA' as info,
    CASE 
        WHEN ruta_codigo LIKE 'BH-%' THEN '🏛️ HISTÓRICOS'
        WHEN ruta_codigo LIKE 'BN-%' THEN '🟢 ZONA NORTE'
        WHEN ruta_codigo LIKE 'BS-%' THEN '🟠 ZONA SUR'
        WHEN ruta_codigo LIKE 'BE-%' THEN '🟣 ZONA ESTE'
        WHEN ruta_codigo LIKE 'BO-%' THEN '🩷 ZONA OESTE'
        WHEN ruta_codigo LIKE 'ZE-%' THEN '⚪ ESPECIALES'
        ELSE '❓ OTROS'
    END as zona,
    COUNT(*) as cantidad,
    GROUP_CONCAT(ruta_nombre ORDER BY ruta_orden SEPARATOR ' | ') as barrios
FROM rutas 
WHERE sucursal_id = @sucursal_leon_id
AND (ruta_codigo LIKE 'BH-%' OR ruta_codigo LIKE 'BN-%' OR ruta_codigo LIKE 'BS-%' OR 
     ruta_codigo LIKE 'BE-%' OR ruta_codigo LIKE 'BO-%' OR ruta_codigo LIKE 'ZE-%')
GROUP BY 
    CASE 
        WHEN ruta_codigo LIKE 'BH-%' THEN 1
        WHEN ruta_codigo LIKE 'BN-%' THEN 2
        WHEN ruta_codigo LIKE 'BS-%' THEN 3
        WHEN ruta_codigo LIKE 'BE-%' THEN 4
        WHEN ruta_codigo LIKE 'BO-%' THEN 5
        WHEN ruta_codigo LIKE 'ZE-%' THEN 6
    END
ORDER BY 
    CASE 
        WHEN ruta_codigo LIKE 'BH-%' THEN 1
        WHEN ruta_codigo LIKE 'BN-%' THEN 2
        WHEN ruta_codigo LIKE 'BS-%' THEN 3
        WHEN ruta_codigo LIKE 'BE-%' THEN 4
        WHEN ruta_codigo LIKE 'BO-%' THEN 5
        WHEN ruta_codigo LIKE 'ZE-%' THEN 6
    END;

-- =====================================================
-- PASO 5: CREAR VISTA PARA ESTADÍSTICAS DE BARRIOS
-- =====================================================

CREATE OR REPLACE VIEW v_estadisticas_barrios_leon AS
SELECT 
    r.ruta_id,
    r.ruta_codigo,
    r.ruta_nombre as barrio_nombre,
    r.ruta_descripcion as barrio_descripcion,
    r.ruta_color,
    CASE 
        WHEN r.ruta_codigo LIKE 'BH-%' THEN 'HISTÓRICO'
        WHEN r.ruta_codigo LIKE 'BN-%' THEN 'NORTE'
        WHEN r.ruta_codigo LIKE 'BS-%' THEN 'SUR'
        WHEN r.ruta_codigo LIKE 'BE-%' THEN 'ESTE'
        WHEN r.ruta_codigo LIKE 'BO-%' THEN 'OESTE'
        WHEN r.ruta_codigo LIKE 'ZE-%' THEN 'ESPECIAL'
        ELSE 'OTROS'
    END as zona,
    CASE 
        WHEN r.ruta_codigo LIKE 'BH-%' THEN 1
        ELSE 0
    END as es_historico,
    COUNT(DISTINCT cr.cliente_id) as total_clientes,
    COUNT(DISTINCT CASE WHEN c.cliente_estado_prestamo = 'con prestamo' THEN cr.cliente_id END) as clientes_con_prestamo,
    COUNT(DISTINCT ur.usuario_id) as cobradores_asignados,
    GROUP_CONCAT(DISTINCT u.usuario ORDER BY u.usuario SEPARATOR ', ') as lista_cobradores
FROM rutas r
LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id AND cr.estado = 'activo'
LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
WHERE r.sucursal_id = @sucursal_leon_id
AND (r.ruta_codigo LIKE 'BH-%' OR r.ruta_codigo LIKE 'BN-%' OR r.ruta_codigo LIKE 'BS-%' OR 
     r.ruta_codigo LIKE 'BE-%' OR r.ruta_codigo LIKE 'BO-%' OR r.ruta_codigo LIKE 'ZE-%')
GROUP BY r.ruta_id, r.ruta_codigo, r.ruta_nombre, r.ruta_descripcion, r.ruta_color
ORDER BY 
    CASE 
        WHEN r.ruta_codigo LIKE 'BH-%' THEN 1
        WHEN r.ruta_codigo LIKE 'BN-%' THEN 2
        WHEN r.ruta_codigo LIKE 'BS-%' THEN 3
        WHEN r.ruta_codigo LIKE 'BE-%' THEN 4
        WHEN r.ruta_codigo LIKE 'BO-%' THEN 5
        WHEN r.ruta_codigo LIKE 'ZE-%' THEN 6
    END, r.ruta_orden;

-- =====================================================
-- PASO 6: INSTRUCCIONES FINALES
-- =====================================================

SELECT '🎉 ¡BARRIOS DE LEÓN INTEGRADOS COMO RUTAS!

✅ COMPLETADO:
- 32 barrios de León insertados como rutas
- Organizados por zonas con colores distintivos  
- Integrados con sistema existente de rutas
- Vista de estadísticas creada

🔗 FUNCIONALIDADES DISPONIBLES:
- Asignación de clientes a barrios (via clientes_rutas)
- Asignación de cobradores por barrio (via usuarios_rutas)
- Reportes y estadísticas por barrio
- Optimización de rutas de cobranza
- Interface web existente funciona

🚀 PRÓXIMOS PASOS:
1. Ve al módulo Rutas en tu sistema web
2. Verás los 32 barrios como rutas organizadas
3. Asigna clientes a barrios específicos
4. Asigna cobradores por zona/barrio
5. Usa reportes existentes por "ruta" (ahora barrio)

📊 PARA VER ESTADÍSTICAS:
SELECT * FROM v_estadisticas_barrios_leon;

🎯 ¡APROVECHA TODA LA FUNCIONALIDAD EXISTENTE!' as instrucciones; 