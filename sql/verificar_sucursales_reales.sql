-- =====================================================
-- VERIFICAR TABLA REAL DE SUCURSALES
-- =====================================================

-- 1. Verificar estructura de la tabla real
SELECT 'ESTRUCTURA DE LA TABLA sucursales:' as INFO;
DESCRIBE sucursales;

-- 2. Verificar datos existentes
SELECT 'DATOS ACTUALES EN TABLA sucursales:' as INFO;
SELECT id, nombre, codigo, direccion, telefono, estado FROM sucursales;

-- 3. Verificar que hay al menos una sucursal activa
SELECT 'VERIFICACIÓN DE SUCURSALES ACTIVAS:' as INFO;
SELECT COUNT(*) as total_activas FROM sucursales WHERE estado = 'activa';

-- 4. Si no hay sucursales activas, insertar una por defecto usando empresa existente
INSERT IGNORE INTO sucursales (empresa_id, nombre, direccion, telefono, codigo, estado, creado_por)
SELECT 
    e.confi_id,
    CONCAT('Sucursal ', e.confi_razon),
    e.confi_direccion,
    e.config_celular,
    'SUC-001',
    'activa',
    1
FROM empresa e 
WHERE e.confi_id = 1
AND NOT EXISTS (SELECT 1 FROM sucursales WHERE estado = 'activa');

-- 5. Mostrar datos finales que usará el modal
SELECT 'DATOS PARA EL MODAL (tal como los ve el AJAX):' as RESULTADO;
SELECT 
    s.id as sucursal_id,
    s.nombre as sucursal_nombre,
    s.codigo as sucursal_codigo,
    s.direccion as sucursal_direccion,
    s.telefono as sucursal_telefono,
    CONCAT(s.codigo, ' - ', s.nombre) as texto_completo,
    CASE 
        WHEN s.direccion IS NOT NULL AND s.direccion != '' 
        THEN CONCAT(s.codigo, ' - ', s.nombre, ' (', s.direccion, ')') 
        ELSE CONCAT(s.codigo, ' - ', s.nombre)
    END as texto_descriptivo,
    (SELECT COUNT(*) FROM rutas WHERE sucursal_id = s.id AND ruta_estado = 'activa') as total_rutas,
    (SELECT COUNT(DISTINCT u.id_usuario) FROM usuarios u WHERE u.sucursal_id = s.id AND u.estado = 1) as total_usuarios
FROM sucursales s 
WHERE s.estado = 'activa' 
ORDER BY s.nombre;

SELECT '✅ TABLA REAL DE SUCURSALES VERIFICADA' as ESTADO; 