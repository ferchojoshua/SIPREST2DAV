-- =====================================================
-- SCRIPT SIMPLE PARA ACTUALIZAR ESTADO DE CLIENTES
-- =====================================================
-- Este script actualiza los procedimientos para mostrar "Activo" o "Desactivado"
-- en lugar de números (1 o 0) en el campo cliente_estatus
-- Versión simplificada sin INFORMATION_SCHEMA

-- =====================================================
-- 1. ACTUALIZAR SP_LISTAR_CLIENTES_TABLE
-- =====================================================

-- Eliminar el procedimiento existente
DROP PROCEDURE IF EXISTS SP_LISTAR_CLIENTES_TABLE;

-- Crear el procedimiento actualizado
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

-- =====================================================
-- 2. ACTUALIZAR SP_LISTAR_CLIENTES_PRESTAMO
-- =====================================================

-- Eliminar el procedimiento existente
DROP PROCEDURE IF EXISTS SP_LISTAR_CLIENTES_PRESTAMO;

-- Crear el procedimiento actualizado
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
-- 3. PROBAR LOS PROCEDIMIENTOS
-- =====================================================

-- Probar el primer procedimiento
SELECT 'Probando SP_LISTAR_CLIENTES_TABLE...' as mensaje;
CALL SP_LISTAR_CLIENTES_TABLE();

-- Probar el segundo procedimiento
SELECT 'Probando SP_LISTAR_CLIENTES_PRESTAMO...' as mensaje;
CALL SP_LISTAR_CLIENTES_PRESTAMO();

-- =====================================================
-- 4. MENSAJE FINAL
-- =====================================================

SELECT 'Procedimientos actualizados exitosamente' as resultado;
SELECT 'Refresca la página de clientes para ver los cambios' as instruccion; 