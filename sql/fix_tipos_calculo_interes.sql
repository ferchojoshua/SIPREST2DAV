-- Script para corregir y asegurar los datos de tipos_calculo_interes

-- Crear tabla si no existe
CREATE TABLE IF NOT EXISTS tipos_calculo_interes (
    tipo_calculo_id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_calculo_nombre VARCHAR(100) NOT NULL,
    tipo_calculo_descripcion TEXT DEFAULT NULL,
    tipo_calculo_estado TINYINT(1) DEFAULT 1
);

-- Limpiar datos existentes
DELETE FROM tipos_calculo_interes;

-- Insertar tipos de cálculo correctos
INSERT INTO tipos_calculo_interes (tipo_calculo_id, tipo_calculo_nombre, tipo_calculo_descripcion, tipo_calculo_estado) VALUES 
(1, 'FRANCES', 'Sistema Francés - Cuotas fijas', 1),
(2, 'ALEMAN', 'Sistema Alemán - Capital fijo', 1),
(3, 'AMERICANO', 'Sistema Americano - Solo intereses', 1),
(4, 'SIMPLE', 'Sistema Simple - Interés fijo', 1),
(5, 'COMPUESTO', 'Sistema Compuesto - Interés sobre interés', 1),
(6, 'FLAT', 'Sistema Flat - Interés siempre sobre capital original', 1);

-- Verificar que se insertaron correctamente
SELECT * FROM tipos_calculo_interes WHERE tipo_calculo_estado = 1 ORDER BY tipo_calculo_id; 