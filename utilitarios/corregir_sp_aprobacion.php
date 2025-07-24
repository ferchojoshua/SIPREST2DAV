<?php
/**
 * Script para corregir el procedimiento SP_LISTAR_PRESTAMOS_POR_APROBACION
 * que contiene el error c.cliente_nombres
 */

require_once "modelos/conexion.php";

echo "<h2>🔧 CORRIGIENDO SP_LISTAR_PRESTAMOS_POR_APROBACION</h2>";
echo "<p>Reparando el procedimiento que contiene el error 'c.cliente_nombres'...</p>";
echo "<hr>";

try {
    $pdo = Conexion::conectar();
    
    echo "<h3>🗑️ ELIMINANDO PROCEDIMIENTO CORRUPTO</h3>";
    
    // 1. Eliminar el procedimiento corrupto
    try {
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION");
        echo "<p>✅ Procedimiento eliminado: <code>SP_LISTAR_PRESTAMOS_POR_APROBACION</code></p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error eliminando procedimiento: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "<h3>🔨 RECREANDO PROCEDIMIENTO CORRECTO</h3>";
    
    // 2. Recrear el procedimiento con la estructura correcta
    $sql_procedimiento = "
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
        $pdo->exec($sql_procedimiento);
        echo "<p>✅ Procedimiento recreado: <code>SP_LISTAR_PRESTAMOS_POR_APROBACION</code></p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error recreando procedimiento: " . htmlspecialchars($e->getMessage()) . "</p>";
        throw $e;
    }
    
    echo "<h3>🔍 VERIFICACIÓN</h3>";
    
    // 3. Probar el procedimiento con fechas de prueba
    try {
        $stmt = $pdo->prepare("CALL SP_LISTAR_PRESTAMOS_POR_APROBACION('2025-01-01', '2025-12-31')");
        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>✅ Procedimiento probado correctamente - " . count($resultado) . " registros encontrados</p>";
        
        // Mostrar una muestra si hay datos
        if (!empty($resultado)) {
            echo "<h4>📋 Muestra de datos:</h4>";
            echo "<pre>" . htmlspecialchars(json_encode($resultado[0], JSON_PRETTY_PRINT)) . "</pre>";
        }
        
    } catch (PDOException $e) {
        echo "<p>❌ Error probando procedimiento: " . htmlspecialchars($e->getMessage()) . "</p>";
        throw $e;
    }
    
    echo "<hr>";
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h4>🎉 ¡CORRECCIÓN COMPLETADA!</h4>";
    echo "<p>El procedimiento <code>SP_LISTAR_PRESTAMOS_POR_APROBACION</code> ha sido corregido exitosamente.</p>";
    echo "<p><strong>Cambios realizados:</strong></p>";
    echo "<ul>";
    echo "<li>✅ Eliminado procedimiento corrupto</li>";
    echo "<li>✅ Recreado con estructura correcta</li>";
    echo "<li>✅ Verificado funcionamiento</li>";
    echo "<li>✅ Error 'c.cliente_nombres' resuelto</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<h4>🚀 PRÓXIMOS PASOS</h4>";
    echo "<ol>";
    echo "<li><strong>Probar módulo de aprobaciones:</strong> <a href='vistas/aprobacion.php' target='_blank' style='background: #28a745; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;'>🔗 Ir a Aprobaciones</a></li>";
    echo "<li><strong>Verificar que no hay más errores</strong> en los logs de Apache</li>";
    echo "<li><strong>Confirmar</strong> que las listas de préstamos cargan correctamente</li>";
    echo "</ol>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ ERROR EN LA CORRECCIÓN</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Por favor, ejecuta manualmente la corrección en phpMyAdmin:</p>";
    echo "<ol>";
    echo "<li>Eliminar procedimiento: <code>DROP PROCEDURE IF EXISTS SP_LISTAR_PRESTAMOS_POR_APROBACION;</code></li>";
    echo "<li>Recrear procedimiento con la estructura correcta del archivo CrediCrece.sql</li>";
    echo "</ol>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php' style='background: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;'>← Volver al Sistema</a></p>";
?> 