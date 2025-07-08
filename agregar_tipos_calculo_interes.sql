-- Crear tabla de tipos de cálculo de interés
CREATE TABLE IF NOT EXISTS tipos_calculo_interes (
    tipo_calculo_id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_calculo_nombre VARCHAR(50) NOT NULL,
    tipo_calculo_descripcion VARCHAR(255) NOT NULL,
    tipo_calculo_estado CHAR(1) DEFAULT '1'
);

-- Insertar los tipos de cálculo disponibles
INSERT INTO tipos_calculo_interes (tipo_calculo_nombre, tipo_calculo_descripcion) VALUES 
('FRANCES', 'Sistema Francés - Cuota fija con amortización creciente e interés decreciente'),
('ALEMAN', 'Sistema Alemán - Amortización fija con cuota e interés decreciente'),
('AMERICANO', 'Sistema Americano - Pago de intereses y capital al final del plazo'),
('SIMPLE', 'Sistema Simple - Cuota, amortización e interés fijo'),
('COMPUESTO', 'Sistema Compuesto - Interés sobre interés con cuotas crecientes');

-- Agregar campo tipo_calculo a la tabla prestamo_cabecera si no existe
ALTER TABLE prestamo_cabecera 
ADD COLUMN IF NOT EXISTS tipo_calculo VARCHAR(50) DEFAULT 'FRANCES' 
COMMENT 'Tipo de cálculo de interés: FRANCES, ALEMAN, AMERICANO, SIMPLE, COMPUESTO'; 