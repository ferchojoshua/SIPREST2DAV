-- ================================================
-- SCRIPT PARA INSERTAR SUCURSALES DE EJEMPLO
-- ================================================
-- Este script inserta algunas sucursales de ejemplo para pruebas

-- Insertar sucursales de ejemplo
INSERT INTO sucursales (nombre, direccion, telefono, codigo, estado, empresa_id, fecha_registro) VALUES
('Sucursal Central', 'Av. Principal 123, Ciudad Centro', '+1 234 567 8900', 'SUC001', 'activa', 1, NOW()),
('Sucursal Norte', 'Calle Norte 456, Zona Norte', '+1 234 567 8901', 'SUC002', 'activa', 1, NOW()),
('Sucursal Sur', 'Av. Sur 789, Zona Sur', '+1 234 567 8902', 'SUC003', 'activa', 1, NOW()),
('Sucursal Este', 'Calle Este 321, Zona Este', '+1 234 567 8903', 'SUC004', 'activa', 1, NOW()),
('Sucursal Oeste', 'Av. Oeste 654, Zona Oeste', '+1 234 567 8904', 'SUC005', 'inactiva', 1, NOW());

-- Verificar datos insertados
SELECT * FROM sucursales ORDER BY id;

-- Mostrar resumen
SELECT 
    COUNT(*) as total_sucursales,
    SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END) as activas,
    SUM(CASE WHEN estado = 'inactiva' THEN 1 ELSE 0 END) as inactivas
FROM sucursales; 