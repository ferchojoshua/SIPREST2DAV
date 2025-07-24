<?php
/**
 * Script para corregir manualmente el error "Unknown column 'c.cliente_nombres' in 'field list'"
 * Version sin DELIMITER para compatibilidad con PDO
 */

require_once "modelos/conexion.php";

echo "<h2>🔧 CORRIGIENDO ERROR EN DASHBOARD (Manual)</h2>";
echo "<p><strong>Error detectado:</strong> Unknown column 'c.cliente_nombres' in 'field list'</p>";
echo "<hr>";

try {
    $pdo = Conexion::conectar();
    
    $errores = 0;
    $exitos = 0;
    
    // 1. Eliminar procedimientos existentes
    echo "<h3>🗑️ ELIMINANDO PROCEDIMIENTOS CORRUPTOS</h3>";
    
    $procedimientos_eliminar = [
        'SP_CLIENTES_CON_PRESTAMOS',
        'SP_CUOTAS_VENCIDAS', 
        'SP_PRESTAMOS_MES_ACTUAL'
    ];
    
    foreach ($procedimientos_eliminar as $proc) {
        try {
            $pdo->exec("DROP PROCEDURE IF EXISTS $proc");
            echo "<p>✅ Eliminado: <code>$proc</code></p>";
            $exitos++;
        } catch (PDOException $e) {
            echo "<p>❌ Error eliminando $proc: " . htmlspecialchars($e->getMessage()) . "</p>";
            $errores++;
        }
    }
    
    // 2. Recrear SP_CLIENTES_CON_PRESTAMOS
    echo "<h3>🔨 RECREANDO PROCEDIMIENTOS</h3>";
    
    $sql_clientes = "
    CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CLIENTES_CON_PRESTAMOS` ()   
    BEGIN
        SELECT
            c.cliente_dni,
            c.cliente_nombres,
            COUNT(pc.nro_prestamo) AS cant,
            SUM(pc.pres_monto_total) AS total 
        FROM prestamo_cabecera pc
        INNER JOIN clientes c ON pc.cliente_id = c.cliente_id 
        WHERE pc.pres_aprobacion IN ('aprobado', 'finalizado') 
        GROUP BY pc.cliente_id 
        ORDER BY SUM(ROUND(pc.pres_monto_total, 2)) DESC 
        LIMIT 10;
    END";
    
    try {
        $pdo->exec($sql_clientes);
        echo "<p>✅ Recreado: <code>SP_CLIENTES_CON_PRESTAMOS</code></p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error recreando SP_CLIENTES_CON_PRESTAMOS: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }
    
    // 3. Recrear SP_CUOTAS_VENCIDAS
    $sql_cuotas = "
    CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CUOTAS_VENCIDAS` ()   
    BEGIN
        SELECT 
            c.cliente_nombres,
            COUNT(pd.pdetalle_id) AS cantidad_cuotas,
            SUM(pd.pdetalle_monto_cuota) AS monto_total
        FROM prestamo_detalle pd
        INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
        INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
        WHERE pd.pdetalle_estado_cuota = 'pendiente' 
        AND DATE(pd.pdetalle_fecha) < CURDATE()
        GROUP BY c.cliente_id, c.cliente_nombres
        ORDER BY monto_total DESC;
    END";
    
    try {
        $pdo->exec($sql_cuotas);
        echo "<p>✅ Recreado: <code>SP_CUOTAS_VENCIDAS</code></p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error recreando SP_CUOTAS_VENCIDAS: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }
    
    // 4. Recrear SP_PRESTAMOS_MES_ACTUAL
    $sql_prestamos = "
    CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_PRESTAMOS_MES_ACTUAL` ()   
    BEGIN
        SELECT 
            DATE_FORMAT(pc.pres_fecha_registro, '%d') as dia,
            COUNT(pc.pres_id) as cantidad,
            ROUND(SUM(pc.pres_monto), 2) as monto
        FROM prestamo_cabecera pc
        WHERE MONTH(pc.pres_fecha_registro) = MONTH(CURDATE())
        AND YEAR(pc.pres_fecha_registro) = YEAR(CURDATE())
        AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
        GROUP BY DATE(pc.pres_fecha_registro)
        ORDER BY pc.pres_fecha_registro ASC;
    END";
    
    try {
        $pdo->exec($sql_prestamos);
        echo "<p>✅ Recreado: <code>SP_PRESTAMOS_MES_ACTUAL</code></p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error recreando SP_PRESTAMOS_MES_ACTUAL: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }
    
    echo "<hr>";
    echo "<h3>📊 RESUMEN DE CORRECCIÓN</h3>";
    echo "<p>✅ <strong>Operaciones exitosas:</strong> $exitos</p>";
    echo "<p>" . ($errores > 0 ? "❌" : "✅") . " <strong>Errores:</strong> $errores</p>";
    
    if ($errores === 0) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE!</h4>";
        echo "<p>Los procedimientos del dashboard han sido corregidos completamente.</p>";
        echo "<p><strong>Acciones completadas:</strong></p>";
        echo "<ul>";
        echo "<li>✅ Eliminados procedimientos corruptos</li>";
        echo "<li>✅ Recreados procedimientos con estructura correcta</li>";
        echo "<li>✅ Error 'c.cliente_nombres' solucionado</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>⚠️ CORRECCIÓN CON ADVERTENCIAS</h4>";
        echo "<p>Se completó la corrección pero algunos procedimientos pueden requerir atención adicional.</p>";
        echo "</div>";
    }
    
    // Verificación final
    echo "<hr>";
    echo "<h3>🔍 VERIFICACIÓN FINAL</h3>";
    
    $procedimientos_verificar = [
        'SP_DATOS_DASHBOARD' => 'Dashboard principal',
        'SP_CLIENTES_CON_PRESTAMOS' => 'Clientes con préstamos',
        'SP_CUOTAS_VENCIDAS' => 'Cuotas vencidas',
        'SP_PRESTAMOS_MES_ACTUAL' => 'Préstamos del mes'
    ];
    
    $funcionando = 0;
    foreach ($procedimientos_verificar as $proc => $desc) {
        try {
            $stmt = $pdo->prepare("CALL $proc()");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>✅ <strong>$proc:</strong> $desc - Funciona correctamente (" . count($resultado) . " filas)</p>";
            $funcionando++;
        } catch (PDOException $e) {
            echo "<p>❌ <strong>$proc:</strong> $desc - " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
    echo "<hr>";
    
    if ($funcionando === count($procedimientos_verificar)) {
        echo "<div style='background: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>🚀 ¡SISTEMA COMPLETAMENTE FUNCIONAL!</h4>";
        echo "<p>Todos los procedimientos están funcionando correctamente.</p>";
        echo "<p><strong>El error 'c.cliente_nombres' ha sido resuelto completamente.</strong></p>";
        echo "</div>";
        
        echo "<h4>✨ PRÓXIMOS PASOS</h4>";
        echo "<ol>";
        echo "<li><strong>Probar el dashboard:</strong> <a href='index.php' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>🚀 IR AL DASHBOARD</a></li>";
        echo "<li><strong>Verificar logs:</strong> Los errores en Apache deberían haber cesado</li>";
        echo "<li><strong>Validar funcionalidad:</strong> Las tablas de clientes y cuotas vencidas deberían cargar</li>";
        echo "</ol>";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>⚠️ ATENCIÓN REQUERIDA</h4>";
        echo "<p>Algunos procedimientos requieren revisión manual adicional.</p>";
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ ERROR CRÍTICO</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php' style='background: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;'>← Volver al Sistema</a></p>";
?> 