<?php
/**
 * DIAGNÓSTICO RÁPIDO DEL SP_LISTAR_PRESTAMOS_POR_APROBACION
 * ==========================================================
 */

// Buscar conexion.php en diferentes rutas posibles
$rutas_posibles = [
    __DIR__ . "/../conexion.php",
    __DIR__ . "/../../conexion.php", 
    __DIR__ . "/../modelos/conexion.php",
    "C:/xampp/htdocs/CrediCrece/conexion.php"
];

$conexion_encontrada = false;
foreach ($rutas_posibles as $ruta) {
    if (file_exists($ruta)) {
        require_once $ruta;
        $conexion_encontrada = true;
        echo "✅ Conexión encontrada en: $ruta\n";
        break;
    }
}

if (!$conexion_encontrada) {
    echo "❌ No se encontró conexion.php en ninguna ruta.\n";
    echo "📁 Rutas probadas:\n";
    foreach ($rutas_posibles as $ruta) {
        echo "   - $ruta\n";
    }
    exit(1);
}

echo "\n🔍 DIAGNOSTICANDO SP_LISTAR_PRESTAMOS_POR_APROBACION...\n";

try {
    $pdo = Conexion::conectar();
    
    // Ver la definición actual del procedimiento
    echo "📋 1. VIENDO DEFINICIÓN ACTUAL DEL SP...\n";
    $stmt = $pdo->prepare("SHOW CREATE PROCEDURE SP_LISTAR_PRESTAMOS_POR_APROBACION");
    $stmt->execute();
    $definicion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($definicion) {
        echo "✅ Procedimiento existe\n";
        echo "📄 DEFINICIÓN ACTUAL:\n";
        echo str_repeat("=", 60) . "\n";
        echo $definicion['Create Procedure'] . "\n";
        echo str_repeat("=", 60) . "\n\n";
        
        // Buscar 'c.nombres' en la definición
        if (strpos($definicion['Create Procedure'], 'c.nombres') !== false) {
            echo "❌ CONFIRMADO: El SP tiene 'c.nombres' (INCORRECTO)\n";
            echo "🔧 NECESITA CORRECCIÓN\n\n";
        } else {
            echo "✅ El SP parece correcto (usa 'cliente_nombres')\n\n";
        }
    } else {
        echo "❌ Procedimiento NO existe\n\n";
    }
    
    // Probar ejecutar el SP
    echo "📋 2. PROBANDO EJECUTAR EL SP...\n";
    try {
        $fecha_ini = date('Y-m-01');
        $fecha_fin = date('Y-m-d');
        
        $stmt = $pdo->prepare("CALL SP_LISTAR_PRESTAMOS_POR_APROBACION(?, ?)");
        $stmt->execute([$fecha_ini, $fecha_fin]);
        
        echo "✅ SP ejecutado exitosamente\n";
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "📊 Encontrados: " . count($resultados) . " registros\n";
        
    } catch (Exception $e) {
        echo "❌ ERROR AL EJECUTAR SP: " . $e->getMessage() . "\n";
        
        if (strpos($e->getMessage(), 'c.nombres') !== false) {
            echo "🔧 CONFIRMADO: Error de columna 'c.nombres'\n";
            echo "\n📝 SOLUCIÓN SQL DIRECTA:\n";
            echo str_repeat("=", 60) . "\n";
            mostrarSolucionSQL();
            echo str_repeat("=", 60) . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERROR DE CONEXIÓN: " . $e->getMessage() . "\n";
}

function mostrarSolucionSQL() {
    echo "-- Ejecutar en phpMyAdmin -> SQL:\n\n";
    echo "DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION;\n\n";
    echo "DELIMITER \$\$\n\n";
    echo "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_APROBACION` (IN `fecha_ini` DATE, IN `fecha_fin` DATE)\n";
    echo "BEGIN\n";
    echo "    SELECT \n";
    echo "        pc.pres_id,\n";
    echo "        pc.nro_prestamo,\n";
    echo "        pc.cliente_id,\n";
    echo "        c.cliente_nombres,  -- ✅ CORRECTO\n";
    echo "        pc.pres_monto,\n";
    echo "        pc.pres_interes,\n";
    echo "        pc.pres_cuotas,\n";
    echo "        pc.fpago_id,\n";
    echo "        fp.fpago_descripcion,\n";
    echo "        pc.id_usuario,\n";
    echo "        u.usuario,\n";
    echo "        DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha,\n";
    echo "        pc.pres_aprobacion AS estado,\n";
    echo "        '' AS opciones,\n";
    echo "        pc.pres_monto_cuota,\n";
    echo "        pc.pres_monto_interes,\n";
    echo "        pc.pres_monto_total,\n";
    echo "        pc.pres_cuotas_pagadas\n";
    echo "    FROM prestamo_cabecera pc\n";
    echo "    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id\n";
    echo "    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id\n";
    echo "    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario\n";
    echo "    WHERE pc.pres_fecha_registro BETWEEN fecha_ini AND fecha_fin\n";
    echo "    ORDER BY pc.pres_fecha_registro DESC;\n";
    echo "END\$\$\n\n";
    echo "DELIMITER ;\n\n";
    echo "-- Probar:\n";
    echo "CALL SP_LISTAR_PRESTAMOS_POR_APROBACION('2024-01-01', CURDATE());\n";
}

echo "\n📝 Diagnóstico completado el " . date('Y-m-d H:i:s') . "\n";
?> 