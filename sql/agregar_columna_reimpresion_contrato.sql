ALTER TABLE prestamo_cabecera
ADD COLUMN reimpreso_admin TINYINT(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el contrato ha sido reimpreso por un administrador (1=si, 0=no)'; 