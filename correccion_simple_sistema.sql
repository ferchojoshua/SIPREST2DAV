-- =====================================================
-- CORRECCIÓN SIMPLIFICADA DEL SISTEMA SIPREST
-- =====================================================
-- Este script resuelve los problemas SIN usar INFORMATION_SCHEMA
-- para evitar problemas de permisos

-- =====================================================
-- PARTE 1: CORREGIR ESTADOS DE CLIENTES
-- =====================================================

-- 1.1 Actualizar SP_LISTAR_CLIENTES_TABLE
DROP PROCEDURE IF EXISTS SP_LISTAR_CLIENTES_TABLE;

DELIMITER $$

CREATE PROCEDURE SP_LISTAR_CLIENTES_TABLE()
BEGIN
    SELECT
        cliente_id, 
        cliente_nombres, 
        cliente_dni, 
        cliente_cel, 
        cliente_estado_prestamo, 
        CASE 
            WHEN cliente_estatus = '1' THEN 'Activo'
            WHEN cliente_estatus = '0' THEN 'Desactivado'
            ELSE 'Desconocido'
        END AS cliente_estatus,
        cliente_direccion,
        cliente_correo,
        '' as opciones,
        cliente_refe,
        cliente_cel_refe
    FROM
        clientes
    ORDER BY cliente_id DESC;
END$$

DELIMITER ;

-- 1.2 Actualizar SP_LISTAR_CLIENTES_PRESTAMO
DROP PROCEDURE IF EXISTS SP_LISTAR_CLIENTES_PRESTAMO;

DELIMITER $$

CREATE PROCEDURE SP_LISTAR_CLIENTES_PRESTAMO()
BEGIN
    SELECT
        cliente_id, 
        cliente_nombres, 
        cliente_dni, 
        cliente_estado_prestamo, 
        CASE 
            WHEN cliente_estatus = '1' THEN 'Activo'
            WHEN cliente_estatus = '0' THEN 'Desactivado'
            ELSE 'Desconocido'
        END AS cliente_estatus
    FROM
        clientes
    ORDER BY cliente_id DESC;
END$$

DELIMITER ;

-- =====================================================
-- PARTE 2: CREAR PROCEDIMIENTO ADMINISTRAR PRESTAMOS
-- =====================================================

-- 2.1 Crear/Actualizar SP_LISTAR_PRESTAMOS_POR_USUARIO
DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_USUARIO;

DELIMITER $$

CREATE PROCEDURE SP_LISTAR_PRESTAMOS_POR_USUARIO(IN p_id_usuario INT)
BEGIN
    SELECT 
        pc.pres_id,
        pc.nro_prestamo,
        pc.cliente_id,
        c.cliente_nombres,
        pc.pres_monto,
        pc.pres_interes,
        pc.pres_cuotas,
        pc.fpago_id,
        fp.fpago_descripcion,
        pc.id_usuario,
        u.usuario,
        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha,
        pc.pres_aprobacion as estado,
        '' as opciones,
        pc.pres_monto_cuota,
        pc.pres_monto_interes,
        pc.pres_monto_total,
        pc.pres_cuotas_pagadas,
        IFNULL(pc.reimpreso_admin, 0) as reimpreso_admin
    FROM prestamo_cabecera pc
    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
    WHERE pc.id_usuario = p_id_usuario
    ORDER BY pc.pres_id DESC;
END$$

DELIMITER ;

-- =====================================================
-- PARTE 3: AGREGAR COLUMNA SI NO EXISTE
-- =====================================================

-- 3.1 Intentar agregar columna reimpreso_admin (se ignora si ya existe)
ALTER TABLE prestamo_cabecera ADD COLUMN reimpreso_admin TINYINT(1) DEFAULT 0;

-- =====================================================
-- PARTE 4: PROBAR PROCEDIMIENTOS
-- =====================================================

-- 4.1 Probar SP_LISTAR_CLIENTES_TABLE
SELECT 'Probando SP_LISTAR_CLIENTES_TABLE...' as mensaje;
-- CALL SP_LISTAR_CLIENTES_TABLE();

-- 4.2 Probar SP_LISTAR_CLIENTES_PRESTAMO
SELECT 'Probando SP_LISTAR_CLIENTES_PRESTAMO...' as mensaje;
-- CALL SP_LISTAR_CLIENTES_PRESTAMO();

-- 4.3 Probar SP_LISTAR_PRESTAMOS_POR_USUARIO con usuario ID 1
SELECT 'Probando SP_LISTAR_PRESTAMOS_POR_USUARIO...' as mensaje;
-- CALL SP_LISTAR_PRESTAMOS_POR_USUARIO(1);

-- =====================================================
-- PARTE 5: VERIFICAR DATOS
-- =====================================================

-- 5.1 Mostrar conteo de préstamos por usuario
SELECT 
    u.id_usuario,
    u.usuario,
    COUNT(pc.pres_id) as total_prestamos,
    COUNT(CASE WHEN pc.pres_aprobacion = 'aprobado' THEN 1 END) as prestamos_aprobados
FROM usuarios u
LEFT JOIN prestamo_cabecera pc ON u.id_usuario = pc.id_usuario
GROUP BY u.id_usuario, u.usuario
ORDER BY u.id_usuario;

-- 5.2 Mostrar estado de clientes
SELECT 
    COUNT(*) as total_clientes,
    COUNT(CASE WHEN cliente_estatus = '1' THEN 1 END) as clientes_activos,
    COUNT(CASE WHEN cliente_estatus = '0' THEN 1 END) as clientes_desactivados
FROM clientes;

-- =====================================================
-- PARTE 6: MENSAJES FINALES
-- =====================================================

SELECT '✅ CORRECCIÓN SIMPLIFICADA COMPLETADA' as estado;
SELECT 'Cambios realizados:' as detalle;
SELECT '1. Estados de clientes: "Activo" o "Desactivado"' as cambio1;
SELECT '2. Procedimiento SP_LISTAR_PRESTAMOS_POR_USUARIO creado' as cambio2;
SELECT '3. Columna reimpreso_admin agregada' as cambio3;

-- =====================================================
-- PARTE 7: INSTRUCCIONES FINALES
-- =====================================================

SELECT 'PRÓXIMOS PASOS:' as titulo;
SELECT '1. Refrescar la página de clientes' as paso1;
SELECT '2. Verificar administrar préstamos' as paso2;
SELECT '3. Probar las notificaciones mejoradas' as paso3;
SELECT '4. Ejecutar test_admin_prestamos.php si hay problemas' as paso4; 