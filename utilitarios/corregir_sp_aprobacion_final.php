<?php
/**
 * CORRECCIÓN DEFINITIVA DE SP_LISTAR_PRESTAMOS_POR_APROBACION
 * ===========================================================
 * 
 * Este script corrige el stored procedure que tiene el error 
 * "Unknown column 'c.nombres'" reemplazándolo por la versión correcta.
 * 
 * Uso: php corregir_sp_aprobacion_final.php
 */

require_once "../conexion.php";

echo "================================================================\n";
echo "🔧 CORRECCIÓN DEFINITIVA DE SP_LISTAR_PRESTAMOS_POR_APROBACION\n";
echo "================================================================\n\n";

try {
    $pdo = Conexion::conectar();
    
    echo "📋 1. VERIFICANDO STORED PROCEDURE ACTUAL...\n";
    
    // Verificar si existe el procedimiento
    $stmt = $pdo->prepare("SHOW PROCEDURE STATUS WHERE Name = 'SP_LISTAR_PRESTAMOS_POR_APROBACION'");
    $stmt->execute();
    $procedure_exists = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($procedure_exists) {
        echo "   ✅ Procedimiento existe. Creado: {$procedure_exists['Created']}\n";
        echo "   🔍 Verificando definición actual...\n";
        
        try {
            // Probar el procedimiento con datos de prueba
            $fecha_ini = date('Y-m-01'); // Primer día del mes actual
            $fecha_fin = date('Y-m-d');  // Hoy
            
            $stmt = $pdo->prepare("CALL SP_LISTAR_PRESTAMOS_POR_APROBACION(:fecha_ini, :fecha_fin)");
            $stmt->bindParam(':fecha_ini', $fecha_ini);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
            
            echo "   ✅ Procedimiento funciona correctamente.\n";
            echo "   ℹ️  No se requiere corrección.\n\n";
            
            // Mostrar algunos resultados si hay
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($resultados)) {
                echo "   📊 Encontrados " . count($resultados) . " préstamos para aprobación.\n";
                if (count($resultados) > 0) {
                    $primer_resultado = $resultados[0];
                    echo "   📋 Ejemplo: Préstamo {$primer_resultado['nro_prestamo']} - Cliente: {$primer_resultado['cliente_nombres']}\n";
                }
            } else {
                echo "   📊 No hay préstamos en el rango de fechas especificado.\n";
            }
            
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'c.nombres') !== false) {
                echo "   ❌ ERROR CONFIRMADO: " . $e->getMessage() . "\n";
                echo "   🔧 Procediendo a corregir...\n\n";
                
                // Corregir el procedimiento
                corregirProcedimiento($pdo);
            } else {
                echo "   ❌ Error diferente: " . $e->getMessage() . "\n";
                throw $e;
            }
        }
    } else {
        echo "   ❌ Procedimiento no existe. Creándolo...\n\n";
        crearProcedimiento($pdo);
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR CRÍTICO: " . $e->getMessage() . "\n";
    exit(1);
}

function corregirProcedimiento($pdo) {
    echo "📋 2. ELIMINANDO PROCEDIMIENTO CORRUPTO...\n";
    
    try {
        $stmt = $pdo->prepare("DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION");
        $stmt->execute();
        echo "   ✅ Procedimiento eliminado.\n\n";
        
        crearProcedimiento($pdo);
        
    } catch (Exception $e) {
        echo "   ❌ Error al eliminar: " . $e->getMessage() . "\n";
        throw $e;
    }
}

function crearProcedimiento($pdo) {
    echo "📋 3. CREANDO PROCEDIMIENTO CORREGIDO...\n";
    
    $sql_procedure = "
    CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_APROBACION` (IN `fecha_ini` DATE, IN `fecha_fin` DATE)
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
            DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha,
            pc.pres_aprobacion AS estado,
            '' AS opciones,
            pc.pres_monto_cuota,
            pc.pres_monto_interes,
            pc.pres_monto_total,
            pc.pres_cuotas_pagadas
        FROM prestamo_cabecera pc
        INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
        INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
        INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
        WHERE pc.pres_fecha_registro BETWEEN fecha_ini AND fecha_fin
        ORDER BY pc.pres_fecha_registro DESC;
    END";
    
    try {
        $stmt = $pdo->prepare($sql_procedure);
        $stmt->execute();
        echo "   ✅ Procedimiento creado exitosamente.\n\n";
        
        // Probar el procedimiento nuevo
        echo "📋 4. PROBANDO PROCEDIMIENTO CORREGIDO...\n";
        
        $fecha_ini = date('Y-m-01');
        $fecha_fin = date('Y-m-d');
        
        $stmt = $pdo->prepare("CALL SP_LISTAR_PRESTAMOS_POR_APROBACION(:fecha_ini, :fecha_fin)");
        $stmt->bindParam(':fecha_ini', $fecha_ini);
        $stmt->bindParam(':fecha_fin', $fecha_fin);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "   ✅ Procedimiento funciona correctamente!\n";
        echo "   📊 Encontrados " . count($resultados) . " registros de prueba.\n";
        
        if (!empty($resultados)) {
            echo "   📋 Campos retornados: " . implode(', ', array_keys($resultados[0])) . "\n";
            $primer_resultado = $resultados[0];
            echo "   📋 Ejemplo: Préstamo {$primer_resultado['nro_prestamo']} - Cliente: {$primer_resultado['cliente_nombres']}\n";
        }
        
        echo "\n🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE!\n";
        echo "================================================================\n";
        echo "✅ El error 'Unknown column c.nombres' ha sido resuelto.\n";
        echo "✅ El flujo de aprobación de préstamos ahora debería funcionar.\n";
        echo "================================================================\n";
        
    } catch (Exception $e) {
        echo "   ❌ Error al crear procedimiento: " . $e->getMessage() . "\n";
        throw $e;
    }
}

echo "\n📝 Corrección completada el " . date('Y-m-d H:i:s') . "\n";
?> 