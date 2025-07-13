-- Script para agregar los nuevos sistemas de amortización FLAT y SOBRE SALDO
-- Ejecutar este script en la base de datos para agregar las nuevas opciones

-- Verificar si ya existen los registros para evitar duplicados
INSERT INTO tipos_calculo_interes (tipo_calculo_nombre, tipo_calculo_descripcion, tipo_calculo_estado) 
SELECT 'FLAT', 'Sistema de amortización flat - Interés siempre sobre el capital original', '1'
WHERE NOT EXISTS (
    SELECT 1 FROM tipos_calculo_interes WHERE tipo_calculo_nombre = 'FLAT'
);

INSERT INTO tipos_calculo_interes (tipo_calculo_nombre, tipo_calculo_descripcion, tipo_calculo_estado) 
SELECT 'SOBRE SALDO', 'Sistema de amortización sobre saldo - Interés sobre el saldo restante', '1'
WHERE NOT EXISTS (
    SELECT 1 FROM tipos_calculo_interes WHERE tipo_calculo_nombre = 'SOBRE SALDO'
);

-- Verificar que se insertaron correctamente
SELECT * FROM tipos_calculo_interes WHERE tipo_calculo_nombre IN ('FLAT', 'SOBRE SALDO'); 