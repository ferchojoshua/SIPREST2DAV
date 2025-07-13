-- ====================================================
-- INSERTAR DATOS DE PRUEBA PARA SISTEMA DE MORA
-- ====================================================

-- Insertar clientes de prueba
INSERT INTO clientes (cliente_nombres, cliente_apellidos, cliente_nro_documento, cliente_celular, cliente_email, cliente_direccion, cliente_estatus, cliente_fecha_registro) VALUES
('Maria Elena', 'Gonzalez Perez', '12345678', '70123456', 'maria.gonzalez@email.com', 'Av. 6 de Marzo #123', 1, '2023-01-15'),
('Carlos Alberto', 'Rodriguez Silva', '87654321', '71234567', 'carlos.rodriguez@email.com', 'Calle Murillo #456', 1, '2023-02-10'),
('Ana Patricia', 'Mamani Quispe', '11223344', '72345678', 'ana.mamani@email.com', 'Av. América #789', 1, '2023-03-05'),
('Luis Fernando', 'Vargas Morales', '44332211', '73456789', 'luis.vargas@email.com', 'Calle Comercio #321', 1, '2023-04-20'),
('Carmen Rosa', 'Lopez Gutierrez', '55667788', '74567890', 'carmen.lopez@email.com', 'Zona Sur #654', 1, '2023-05-12'),
('Roberto Carlos', 'Mendoza Flores', '88776655', '75678901', 'roberto.mendoza@email.com', 'Av. Ballivián #987', 1, '2023-06-08'),
('Silvia Beatriz', 'Condori Mamani', '99887766', '76789012', 'silvia.condori@email.com', 'Calle Sagárnaga #147', 1, '2023-07-15'),
('Daniel Alejandro', 'Torrez Quispe', '22334455', '77890123', 'daniel.torrez@email.com', 'Av. Camacho #258', 1, '2023-08-22');

-- Insertar prestamos con diferentes estados de mora
-- PRESTAMO 1: Muy atrasado (90+ días)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000001', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '12345678'), '2023-06-01', '2024-06-01', 5000.00, 15.00, 12, 'aprobado', 'Préstamo con mora crítica');

-- PRESTAMO 2: Atrasado (30-60 días)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000002', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '87654321'), '2023-09-01', '2024-09-01', 3000.00, 12.00, 12, 'aprobado', 'Préstamo con mora moderada');

-- PRESTAMO 3: Levemente atrasado (7-30 días)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000003', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '11223344'), '2023-11-01', '2024-11-01', 2500.00, 10.00, 12, 'aprobado', 'Préstamo con mora leve');

-- PRESTAMO 4: Al día (sin mora)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000004', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '44332211'), '2023-12-01', '2024-12-01', 4000.00, 14.00, 12, 'aprobado', 'Préstamo al día');

-- PRESTAMO 5: Atrasado crítico (120+ días)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000005', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '55667788'), '2023-05-01', '2024-05-01', 6000.00, 18.00, 12, 'aprobado', 'Préstamo con mora crítica');

-- PRESTAMO 6: Atrasado moderado (45 días)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000006', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '88776655'), '2023-08-15', '2024-08-15', 3500.00, 13.00, 12, 'aprobado', 'Préstamo con mora moderada');

-- PRESTAMO 7: Levemente atrasado (15 días)
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000007', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '99887766'), '2023-10-15', '2024-10-15', 2800.00, 11.00, 12, 'aprobado', 'Préstamo con mora leve');

-- PRESTAMO 8: Al día
INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, fecha_registro, fecha_vencimiento, prestamo_monto, prestamo_interes, prestamo_plazo, pres_aprobacion, prestamo_obs) VALUES
('00000008', (SELECT cliente_id FROM clientes WHERE cliente_nro_documento = '22334455'), '2023-12-15', '2024-12-15', 4500.00, 16.00, 12, 'aprobado', 'Préstamo al día');

-- Insertar detalle de prestamos con fechas vencidas
-- DETALLE PRESTAMO 1: Cuotas muy atrasadas
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000001', 1, '2023-07-01', 480.00, 'pendiente', NULL, 0.00),
('00000001', 2, '2023-08-01', 480.00, 'pendiente', NULL, 0.00),
('00000001', 3, '2023-09-01', 480.00, 'pendiente', NULL, 0.00),
('00000001', 4, '2023-10-01', 480.00, 'pendiente', NULL, 0.00),
('00000001', 5, '2023-11-01', 480.00, 'pendiente', NULL, 0.00),
('00000001', 6, '2023-12-01', 480.00, 'pendiente', NULL, 0.00),
('00000001', 7, '2024-01-01', 480.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 2: Cuotas atrasadas moderadas
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000002', 1, '2023-10-01', 280.00, 'pagado', '2023-09-28', 280.00),
('00000002', 2, '2023-11-01', 280.00, 'pagado', '2023-10-30', 280.00),
('00000002', 3, '2023-12-01', 280.00, 'pendiente', NULL, 0.00),
('00000002', 4, '2024-01-01', 280.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 3: Cuotas levemente atrasadas
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000003', 1, '2023-12-01', 235.00, 'pagado', '2023-11-30', 235.00),
('00000003', 2, '2024-01-01', 235.00, 'pendiente', NULL, 0.00),
('00000003', 3, '2024-02-01', 235.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 4: Al día
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000004', 1, '2024-01-01', 380.00, 'pagado', '2023-12-28', 380.00),
('00000004', 2, '2024-02-01', 380.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 5: Cuotas críticas
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000005', 1, '2023-06-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 2, '2023-07-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 3, '2023-08-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 4, '2023-09-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 5, '2023-10-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 6, '2023-11-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 7, '2023-12-01', 580.00, 'pendiente', NULL, 0.00),
('00000005', 8, '2024-01-01', 580.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 6: Atrasado moderado
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000006', 1, '2023-09-15', 330.00, 'pagado', '2023-09-10', 330.00),
('00000006', 2, '2023-10-15', 330.00, 'pagado', '2023-10-12', 330.00),
('00000006', 3, '2023-11-15', 330.00, 'pagado', '2023-11-20', 330.00),
('00000006', 4, '2023-12-15', 330.00, 'pendiente', NULL, 0.00),
('00000006', 5, '2024-01-15', 330.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 7: Levemente atrasado
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000007', 1, '2023-11-15', 265.00, 'pagado', '2023-11-10', 265.00),
('00000007', 2, '2023-12-15', 265.00, 'pagado', '2023-12-12', 265.00),
('00000007', 3, '2024-01-15', 265.00, 'pendiente', NULL, 0.00);

-- DETALLE PRESTAMO 8: Al día
INSERT INTO prestamo_detalle (nro_prestamo, pdetalle_nro_cuota, pdetalle_fecha, pdetalle_monto_cuota, pdetalle_estado_cuota, pdetalle_fecha_pago, pdetalle_monto_pagado) VALUES
('00000008', 1, '2024-01-15', 425.00, 'pagado', '2024-01-10', 425.00),
('00000008', 2, '2024-02-15', 425.00, 'pendiente', NULL, 0.00);

-- Mensaje de confirmación
SELECT 'DATOS DE PRUEBA INSERTADOS CORRECTAMENTE' as resultado;
SELECT 'Total clientes insertados:', COUNT(*) FROM clientes WHERE cliente_nro_documento IN ('12345678', '87654321', '11223344', '44332211', '55667788', '88776655', '99887766', '22334455');
SELECT 'Total prestamos insertados:', COUNT(*) FROM prestamo_cabecera WHERE nro_prestamo LIKE '0000000%';
SELECT 'Total detalles insertados:', COUNT(*) FROM prestamo_detalle WHERE nro_prestamo LIKE '0000000%'; 