<?php
/**
 * FORZAR CORRECCIÓN DEL SP_LISTAR_PRESTAMOS_POR_APROBACION
 * =========================================================
 */

require_once "../modelos/conexion.php";

echo "🔧 FORZANDO CORRECCIÓN DEL SP_LISTAR_PRESTAMOS_POR_APROBACION\n";
echo str_repeat("=", 60) . "\n\n";

try {
    $pdo = Conexion::conectar();
    
    echo "📋 1. ELIMINANDO PROCEDIMIENTO ACTUAL...\n";
    $stmt = $pdo->prepare("DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION");
    $stmt->execute();
    echo "   ✅ Procedimiento eliminado\n\n";
    
    echo "📋 2. CREANDO PROCEDIMIENTO CORREGIDO...\n";
    
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
    
    $stmt = $pdo->prepare($sql_procedure);
    $stmt->execute();
    echo "   ✅ Procedimiento creado exitosamente\n\n";
    
    echo "📋 3. VERIFICANDO NUEVA DEFINICIÓN...\n";
    $stmt = $pdo->prepare("SHOW CREATE PROCEDURE SP_LISTAR_PRESTAMOS_POR_APROBACION");
    $stmt->execute();
    $definicion = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "📄 NUEVA DEFINICIÓN:\n";
    echo str_repeat("-", 40) . "\n";
    echo $definicion['Create Procedure'] . "\n";
    echo str_repeat("-", 40) . "\n\n";
    
    echo "📋 4. PROBANDO PROCEDIMIENTO...\n";
    
    $fecha_ini = date('Y-m-01');
    $fecha_fin = date('Y-m-d');
    
    $stmt = $pdo->prepare("CALL SP_LISTAR_PRESTAMOS_POR_APROBACION(?, ?)");
    $stmt->execute([$fecha_ini, $fecha_fin]);
    
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "   ✅ Procedimiento ejecutado exitosamente!\n";
    echo "   📊 Encontrados: " . count($resultados) . " registros\n";
    
    if (!empty($resultados)) {
        echo "   📋 Campos: " . implode(', ', array_keys($resultados[0])) . "\n";
        $primer_resultado = $resultados[0];
        echo "   📋 Ejemplo: Préstamo {$primer_resultado['nro_prestamo']} - Cliente: {$primer_resultado['cliente_nombres']}\n";
    }
    
    echo "\n🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE!\n";
    echo str_repeat("=", 60) . "\n";
    echo "✅ El procedimiento ha sido recreado correctamente\n";
    echo "✅ El error 'Unknown column c.nombres' debería estar resuelto\n";
    echo str_repeat("=", 60) . "\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\n🔧 SOLUCIÓN MANUAL:\n";
    echo "Ejecuta en phpMyAdmin -> SQL:\n\n";
    echo "DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION;\n\n";
    echo "DELIMITER \$\$\n";
    echo "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PRESTAMOS_POR_APROBACION` (IN `fecha_ini` DATE, IN `fecha_fin` DATE)\n";
    echo "BEGIN\n";
    echo "    SELECT pc.pres_id, pc.nro_prestamo, pc.cliente_id, c.cliente_nombres,\n";
    echo "           pc.pres_monto, pc.pres_interes, pc.pres_cuotas, pc.fpago_id,\n";
    echo "           fp.fpago_descripcion, pc.id_usuario, u.usuario,\n";
    echo "           DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS fecha,\n";
    echo "           pc.pres_aprobacion AS estado, '' AS opciones,\n";
    echo "           pc.pres_monto_cuota, pc.pres_monto_interes, pc.pres_monto_total, pc.pres_cuotas_pagadas\n";
    echo "    FROM prestamo_cabecera pc\n";
    echo "    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id\n";
    echo "    INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id\n";
    echo "    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario\n";
    echo "    WHERE pc.pres_fecha_registro BETWEEN fecha_ini AND fecha_fin\n";
    echo "    ORDER BY pc.pres_fecha_registro DESC;\n";
    echo "END\$\$\n";
    echo "DELIMITER ;\n";
}

echo "\n📝 Corrección completada el " . date('Y-m-d H:i:s') . "\n";
?> 