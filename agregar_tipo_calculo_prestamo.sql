-- Agregar campo tipo_calculo a la tabla prestamo_cabecera
ALTER TABLE prestamo_cabecera ADD COLUMN tipo_calculo VARCHAR(10) DEFAULT 'frances' COMMENT 'Tipo de cálculo de interés: frances o aleman'; 