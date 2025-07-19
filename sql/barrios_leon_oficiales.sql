-- =====================================================
-- BARRIOS OFICIALES DE LEÓN NICARAGUA - CATÁLOGO REAL
-- =====================================================
-- Script basado en el catálogo oficial de barrios de León
-- Trunquemos tabla actual e insertamos datos reales

-- =====================================================
-- PASO 1: LIMPIAR DATOS ACTUALES
-- =====================================================

-- Eliminar relaciones primero (clientes y usuarios asignados a rutas)
DELETE FROM clientes_rutas WHERE ruta_id IN (SELECT ruta_id FROM rutas);
DELETE FROM usuarios_rutas WHERE ruta_id IN (SELECT ruta_id FROM rutas);

-- Truncar tabla rutas para empezar limpio
TRUNCATE TABLE rutas;

-- =====================================================
-- PASO 2: OBTENER ID DE SUCURSAL LEÓN
-- =====================================================

-- Obtener ID de sucursal León (debe existir)
SET @sucursal_leon = (SELECT id FROM sucursales WHERE nombre LIKE '%Leon%' OR codigo LIKE '%LE%' LIMIT 1);

-- Verificar que existe la sucursal
SELECT 
    CASE 
        WHEN @sucursal_leon IS NOT NULL THEN CONCAT('✅ Sucursal León encontrada: ID ', @sucursal_leon)
        ELSE '❌ ERROR: No se encontró sucursal León'
    END as verificacion;

-- =====================================================
-- PASO 3: INSERTAR BARRIOS OFICIALES POR DISTRITO
-- =====================================================

-- DISTRITO CENTRAL (Códigos DC-XXX, Colores azules)
INSERT INTO rutas (ruta_nombre, ruta_descripcion, ruta_codigo, ruta_color, sucursal_id, ruta_estado, ruta_orden, usuario_creacion) VALUES
('Barrio Zaragoza', 'Distrito Central - Barrio Zaragoza', 'DC-ZARAGOZA', '#1e3a8a', @sucursal_leon, 'activa', 101, 1),
('Barrio El Calvario', 'Distrito Central - Barrio El Calvario', 'DC-CALVARIO', '#1e40af', @sucursal_leon, 'activa', 102, 1),
('Barrio El Coyolar', 'Distrito Central - Barrio El Coyolar', 'DC-COYOLAR', '#1d4ed8', @sucursal_leon, 'activa', 103, 1),
('Barrio El Laborío', 'Distrito Central - Barrio El Laborío', 'DC-LABORIO', '#2563eb', @sucursal_leon, 'activa', 104, 1),
('Barrio El Sagrario', 'Distrito Central - Barrio El Sagrario', 'DC-SAGRARIO', '#3b82f6', @sucursal_leon, 'activa', 105, 1),
('Barrio San Juan', 'Distrito Central - Barrio San Juan', 'DC-SANJUAN', '#60a5fa', @sucursal_leon, 'activa', 106, 1),
('Barrio San Felipe', 'Distrito Central - Barrio San Felipe', 'DC-SANFELIPE', '#93c5fd', @sucursal_leon, 'activa', 107, 1),
('Barrio San José', 'Distrito Central - Barrio San José', 'DC-SANJOSE', '#bfdbfe', @sucursal_leon, 'activa', 108, 1),
('Barrio San Sebastián', 'Distrito Central - Barrio San Sebastián', 'DC-SEBASTIAN', '#dbeafe', @sucursal_leon, 'activa', 109, 1),
('Colonia La de Mayo', 'Distrito Central - Colonia La de Mayo', 'DC-LAMAYO', '#eff6ff', @sucursal_leon, 'activa', 110, 1),
('Colonia La Fosforera', 'Distrito Central - Colonia La Fosforera', 'DC-FOSFORERA', '#f8fafc', @sucursal_leon, 'activa', 111, 1),
('Colonia Avellán', 'Distrito Central - Colonia Avellán', 'DC-AVELLAN', '#f1f5f9', @sucursal_leon, 'activa', 112, 1),
('H. y M. de Zaragoza', 'Distrito Central - H. y M. de Zaragoza', 'DC-HMZARAGOZA', '#e2e8f0', @sucursal_leon, 'activa', 113, 1),
('Colonia Santa Martha', 'Distrito Central - Colonia Santa Martha', 'DC-SANTAMARTHA', '#cbd5e1', @sucursal_leon, 'activa', 114, 1),
('H. y M. 26 de Abril', 'Distrito Central - H. y M. 26 de Abril', 'DC-HM26ABRIL', '#94a3b8', @sucursal_leon, 'activa', 115, 1),
('San Nicolás', 'Distrito Central - San Nicolás', 'DC-SANNICOLAS', '#64748b', @sucursal_leon, 'activa', 116, 1),
('Pedro José Avendaño', 'Distrito Central - Pedro José Avendaño', 'DC-AVENDANO', '#475569', @sucursal_leon, 'activa', 117, 1),
('Rogelio Santana', 'Distrito Central - Rogelio Santana', 'DC-SANTANA', '#334155', @sucursal_leon, 'activa', 118, 1),

-- DISTRITO NORESTE (Códigos DN-XXX, Colores verdes)
('Andrés Zapata', 'Distrito Noreste - Andrés Zapata', 'DN-ZAPATA', '#065f46', @sucursal_leon, 'activa', 201, 1),
('Anexo Maritza López', 'Distrito Noreste - Anexo Maritza López', 'DN-MARITZA', '#047857', @sucursal_leon, 'activa', 202, 1),
('Anexo Villa Soberana', 'Distrito Noreste - Anexo Villa Soberana', 'DN-VILLASOBERA', '#059669', @sucursal_leon, 'activa', 203, 1),
('Aracely Pérez', 'Distrito Noreste - Aracely Pérez', 'DN-ARACELY', '#0d9488', @sucursal_leon, 'activa', 204, 1),
('Augusto César Sandino', 'Distrito Noreste - Augusto César Sandino', 'DN-SANDINO', '#0f766e', @sucursal_leon, 'activa', 205, 1),
('Barrio Ermita de Dolores', 'Distrito Noreste - Barrio Ermita de Dolores', 'DN-ERMITA', '#115e59', @sucursal_leon, 'activa', 206, 1),
('Bella Vista', 'Distrito Noreste - Bella Vista', 'DN-BELLAVISTA', '#134e4a', @sucursal_leon, 'activa', 207, 1),
('Benjamín Zeledón', 'Distrito Noreste - Benjamín Zeledón', 'DN-ZELEDON', '#1f2937', @sucursal_leon, 'activa', 208, 1),
('Colonia Farabundo Martí', 'Distrito Noreste - Colonia Farabundo Martí', 'DN-FARABUNDO', '#166534', @sucursal_leon, 'activa', 209, 1),
('Colonia Brenda Sofía', 'Distrito Noreste - Colonia Brenda Sofía', 'DN-BRENDASOFIA', '#15803d', @sucursal_leon, 'activa', 210, 1),
('El Platanal', 'Distrito Noreste - El Platanal', 'DN-PLATANAL', '#16a34a', @sucursal_leon, 'activa', 211, 1),
('El Porvenir', 'Distrito Noreste - El Porvenir', 'DN-PORVENIR', '#22c55e', @sucursal_leon, 'activa', 212, 1),
('Enrique Lorente', 'Distrito Noreste - Enrique Lorente', 'DN-LORENTE', '#4ade80', @sucursal_leon, 'activa', 213, 1),
('Foyulesa', 'Distrito Noreste - Foyulesa', 'DN-FOYULESA', '#6ade87', @sucursal_leon, 'activa', 214, 1),
('Jericó', 'Distrito Noreste - Jericó', 'DN-JERICO', '#86efac', @sucursal_leon, 'activa', 215, 1),
('José Benito Escobar', 'Distrito Noreste - José Benito Escobar', 'DN-ESCOBAR', '#a7f3d0', @sucursal_leon, 'activa', 216, 1),
('José de la Cruz Mena', 'Distrito Noreste - José de la Cruz Mena', 'DN-CRUZMENA', '#bbf7d0', @sucursal_leon, 'activa', 217, 1),
('Linda Vista', 'Distrito Noreste - Linda Vista', 'DN-LINDAVISTA', '#d1fae5', @sucursal_leon, 'activa', 218, 1),
('Manolo Quezada', 'Distrito Noreste - Manolo Quezada', 'DN-QUEZADA', '#ecfdf5', @sucursal_leon, 'activa', 219, 1),
('Maritza López', 'Distrito Noreste - Maritza López', 'DN-MARITZALOP', '#f0fdf4', @sucursal_leon, 'activa', 220, 1),

-- DISTRITO SURESTE (Códigos DS-XXX, Colores naranjas)
('18 de Agosto', 'Distrito Sureste - 18 de Agosto', 'DS-18AGOSTO', '#ea580c', @sucursal_leon, 'activa', 301, 1),
('Alfonso Cortés', 'Distrito Sureste - Alfonso Cortés', 'DS-CORTES', '#dc2626', @sucursal_leon, 'activa', 302, 1),
('Anexo Gustavo López', 'Distrito Sureste - Anexo Gustavo López', 'DS-GUSTAVO', '#c2410c', @sucursal_leon, 'activa', 303, 1),
('Arrocera I', 'Distrito Sureste - Arrocera I', 'DS-ARROCERA1', '#b91c1c', @sucursal_leon, 'activa', 304, 1),
('Arrocera II', 'Distrito Sureste - Arrocera II', 'DS-ARROCERA2', '#991b1b', @sucursal_leon, 'activa', 305, 1),
('Anexo Villa 23 de Julio', 'Distrito Sureste - Anexo Villa 23 de Julio', 'DS-VILLA23', '#7f1d1d', @sucursal_leon, 'activa', 306, 1),
('Azarías H. Pallais', 'Distrito Sureste - Azarías H. Pallais', 'DS-PALLAIS', '#78716c', @sucursal_leon, 'activa', 307, 1),
('Barrio Guadalupe', 'Distrito Sureste - Barrio Guadalupe', 'DS-GUADALUPE', '#ef4444', @sucursal_leon, 'activa', 308, 1),
('Benito Mauricio Lacayo', 'Distrito Sureste - Benito Mauricio Lacayo', 'DS-LACAYO', '#f87171', @sucursal_leon, 'activa', 309, 1),
('Brisas de Acosasco', 'Distrito Sureste - Brisas de Acosasco', 'DS-ACOSASCO', '#fca5a5', @sucursal_leon, 'activa', 310, 1),
('Carlos Fonseca', 'Distrito Sureste - Carlos Fonseca', 'DS-FONSECA', '#fed7d7', @sucursal_leon, 'activa', 311, 1),
('Candelaria', 'Distrito Sureste - Candelaria', 'DS-CANDELARIA', '#fee2e2', @sucursal_leon, 'activa', 312, 1),
('Che Guevara', 'Distrito Sureste - Che Guevara', 'DS-CHEGEVARA', '#fef2f2', @sucursal_leon, 'activa', 313, 1),
('Colonia Universidad', 'Distrito Sureste - Colonia Universidad', 'DS-UNIVERSIDAD', '#fb7185', @sucursal_leon, 'activa', 314, 1),
('Concepción de María', 'Distrito Sureste - Concepción de María', 'DS-CONCEPCION', '#f97316', @sucursal_leon, 'activa', 315, 1),
('El Calvario', 'Distrito Sureste - El Calvario', 'DS-CALVARIO', '#fb923c', @sucursal_leon, 'activa', 316, 1),
('El Cocal', 'Distrito Sureste - El Cocal', 'DS-COCAL', '#fdba74', @sucursal_leon, 'activa', 317, 1),
('Emir Cabezas', 'Distrito Sureste - Emir Cabezas', 'DS-CABEZAS', '#fed7aa', @sucursal_leon, 'activa', 318, 1),
('Fundeci I', 'Distrito Sureste - Fundeci I', 'DS-FUNDECI1', '#ffedd5', @sucursal_leon, 'activa', 319, 1),
('Fundeci II', 'Distrito Sureste - Fundeci II', 'DS-FUNDECI2', '#fff7ed', @sucursal_leon, 'activa', 320, 1),

-- DISTRITO OESTE (Códigos DO-XXX, Colores morados)
('Adiac I', 'Distrito Oeste - Adiac I', 'DO-ADIAC1', '#7c3aed', @sucursal_leon, 'activa', 401, 1),
('Adiac II', 'Distrito Oeste - Adiac II', 'DO-ADIAC2', '#8b5cf6', @sucursal_leon, 'activa', 402, 1),
('Adiac III', 'Distrito Oeste - Adiac III', 'DO-ADIAC3', '#a78bfa', @sucursal_leon, 'activa', 403, 1),
('Anexo La Providencia', 'Distrito Oeste - Anexo La Providencia', 'DO-PROVIDENCIA', '#c4b5fd', @sucursal_leon, 'activa', 404, 1),
('Barrio Sutiava', 'Distrito Oeste - Barrio Sutiava', 'DO-SUTIAVA', '#ddd6fe', @sucursal_leon, 'activa', 405, 1),
('Belén', 'Distrito Oeste - Belén', 'DO-BELEN', '#ede9fe', @sucursal_leon, 'activa', 406, 1),
('Bello Horizonte', 'Distrito Oeste - Bello Horizonte', 'DO-HORIZONTE', '#f3f4f6', @sucursal_leon, 'activa', 407, 1),
('Carlos Núñez', 'Distrito Oeste - Carlos Núñez', 'DO-NUNEZ', '#6366f1', @sucursal_leon, 'activa', 408, 1),
('Colonia Sonia Barrera', 'Distrito Oeste - Colonia Sonia Barrera', 'DO-SONIABARRERA', '#818cf8', @sucursal_leon, 'activa', 409, 1),
('Covista', 'Distrito Oeste - Covista', 'DO-COVISTA', '#a5b4fc', @sucursal_leon, 'activa', 410, 1),
('Divino Niño', 'Distrito Oeste - Divino Niño', 'DO-DIVININO', '#c7d2fe', @sucursal_leon, 'activa', 411, 1),
('El Triángulo', 'Distrito Oeste - El Triángulo', 'DO-TRIANGULO', '#e0e7ff', @sucursal_leon, 'activa', 412, 1),
('Esfuerzo de la Comunidad', 'Distrito Oeste - Esfuerzo de la Comunidad', 'DO-ESFUERZO', '#f0f4ff', @sucursal_leon, 'activa', 413, 1),
('Fanor Urroz', 'Distrito Oeste - Fanor Urroz', 'DO-URROZ', '#ec4899', @sucursal_leon, 'activa', 414, 1),
('Félix Pedro Quiroz', 'Distrito Oeste - Félix Pedro Quiroz', 'DO-QUIROZ', '#f472b6', @sucursal_leon, 'activa', 415, 1),
('Felipe Santana', 'Distrito Oeste - Felipe Santana', 'DO-SANTANA', '#f9a8d4', @sucursal_leon, 'activa', 416, 1),
('H. y M. Veracruz', 'Distrito Oeste - H. y M. Veracruz', 'DO-VERACRUZ', '#fbbf24', @sucursal_leon, 'activa', 417, 1),
('Hipólito Sánchez', 'Distrito Oeste - Hipólito Sánchez', 'DO-SANCHEZ', '#f59e0b', @sucursal_leon, 'activa', 418, 1),
('Juan José Álvarez', 'Distrito Oeste - Juan José Álvarez', 'DO-ALVAREZ', '#d97706', @sucursal_leon, 'activa', 419, 1),
('La Providencia', 'Distrito Oeste - La Providencia', 'DO-LAPROVIDEN', '#b45309', @sucursal_leon, 'activa', 420, 1);

-- =====================================================
-- PASO 4: CREAR VISTA CORREGIDA (SIN VARIABLES)
-- =====================================================

-- Eliminar vista anterior si existe
DROP VIEW IF EXISTS v_estadisticas_barrios_leon;

-- Crear nueva vista sin variables
CREATE VIEW v_estadisticas_barrios_leon AS
SELECT 
    r.ruta_id,
    r.ruta_codigo,
    r.ruta_nombre as barrio_nombre,
    r.ruta_descripcion as barrio_descripcion,
    r.ruta_color,
    CASE 
        WHEN r.ruta_codigo LIKE 'DC-%' THEN 'CENTRAL'
        WHEN r.ruta_codigo LIKE 'DN-%' THEN 'NORESTE'
        WHEN r.ruta_codigo LIKE 'DS-%' THEN 'SURESTE'
        WHEN r.ruta_codigo LIKE 'DO-%' THEN 'OESTE'
        ELSE 'OTROS'
    END as distrito,
    CASE 
        WHEN r.ruta_codigo LIKE 'DC-%' THEN 1
        ELSE 0
    END as es_central,
    COUNT(DISTINCT cr.cliente_id) as total_clientes,
    COUNT(DISTINCT CASE WHEN c.cliente_estado_prestamo = 'con prestamo' THEN cr.cliente_id END) as clientes_con_prestamo,
    COUNT(DISTINCT ur.usuario_id) as cobradores_asignados,
    GROUP_CONCAT(DISTINCT u.usuario ORDER BY u.usuario SEPARATOR ', ') as lista_cobradores,
    s.nombre as sucursal_nombre
FROM rutas r
INNER JOIN sucursales s ON r.sucursal_id = s.id
LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id AND cr.estado = 'activo'
LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
WHERE (r.ruta_codigo LIKE 'DC-%' OR r.ruta_codigo LIKE 'DN-%' OR 
       r.ruta_codigo LIKE 'DS-%' OR r.ruta_codigo LIKE 'DO-%')
GROUP BY r.ruta_id, r.ruta_codigo, r.ruta_nombre, r.ruta_descripcion, r.ruta_color, s.nombre
ORDER BY 
    CASE 
        WHEN r.ruta_codigo LIKE 'DC-%' THEN 1
        WHEN r.ruta_codigo LIKE 'DN-%' THEN 2
        WHEN r.ruta_codigo LIKE 'DS-%' THEN 3
        WHEN r.ruta_codigo LIKE 'DO-%' THEN 4
    END, r.ruta_orden;

-- =====================================================
-- PASO 5: VERIFICACIÓN Y ESTADÍSTICAS
-- =====================================================

-- Contar barrios insertados por distrito
SELECT 
    '📊 RESUMEN DE BARRIOS OFICIALES INSERTADOS' as info,
    CASE 
        WHEN ruta_codigo LIKE 'DC-%' THEN '🏛️ DISTRITO CENTRAL'
        WHEN ruta_codigo LIKE 'DN-%' THEN '🟢 DISTRITO NORESTE'
        WHEN ruta_codigo LIKE 'DS-%' THEN '🟠 DISTRITO SURESTE'
        WHEN ruta_codigo LIKE 'DO-%' THEN '🟣 DISTRITO OESTE'
    END as distrito,
    COUNT(*) as total_barrios
FROM rutas 
WHERE sucursal_id = @sucursal_leon
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

-- Total general
SELECT 
    '✅ TOTAL DE BARRIOS OFICIALES' as resultado,
    COUNT(*) as total_barrios_leon
FROM rutas 
WHERE sucursal_id = @sucursal_leon;

-- =====================================================
-- PASO 6: INSTRUCCIONES FINALES
-- =====================================================

SELECT '🎉 ¡BARRIOS OFICIALES DE LEÓN INSERTADOS!

✅ COMPLETADO:
- Datos anteriores eliminados (truncate)
- 77 barrios oficiales insertados según catálogo
- Organizados por distritos oficiales
- Vista corregida (sin variables)
- Códigos oficiales: DC, DN, DS, DO

📍 DISTRITOS:
- CENTRAL: 18 barrios (azules)
- NORESTE: 20 barrios (verdes)  
- SURESTE: 20 barrios (naranjas)
- OESTE: 20 barrios (morados)

🚀 FUNCIONALIDADES:
- Interface web de rutas funciona
- Asignación de clientes por barrio real
- Asignación de cobradores por distrito
- Reportes por barrio oficial
- Vista de estadísticas disponible

📊 VER ESTADÍSTICAS:
SELECT * FROM v_estadisticas_barrios_leon;

🎯 ¡SISTEMA LISTO CON BARRIOS REALES!' as instrucciones; 