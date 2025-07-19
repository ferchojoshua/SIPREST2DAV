-- Script para crear la tabla de logs de saldos arrastrados.
-- Ejecutar este script en la base de datos `dbprestamo`.

CREATE TABLE `log_saldos_arrastrados` (
  `log_id` INT NOT NULL AUTO_INCREMENT,
  `nro_prestamo` VARCHAR(8) NOT NULL,
  `cuota_origen` INT NOT NULL,
  `cuota_destino` INT NOT NULL,
  `monto_arrastrado` DECIMAL(10, 2) NOT NULL,
  `fecha_movimiento` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`log_id`),
  INDEX `idx_nro_prestamo` (`nro_prestamo`)
);

-- También, vamos a añadir una columna para el monto original de la cuota en la tabla de detalle.
-- Esto hará los cálculos más limpios y evitará confusiones.
ALTER TABLE `prestamo_detalle` ADD COLUMN `pdetalle_monto_original_cuota` DECIMAL(10, 2) NULL AFTER `pdetalle_monto_cuota`;

-- Ahora, poblamos esa nueva columna con los valores existentes para que no quede en null.
UPDATE `prestamo_detalle` SET `pdetalle_monto_original_cuota` = `pdetalle_monto_cuota`; 