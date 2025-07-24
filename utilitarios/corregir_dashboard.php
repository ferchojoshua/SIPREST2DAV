<?php
/**
 * Script para corregir el error "Unknown column 'c.cliente_nombres' in 'field list'"
 * que está afectando el dashboard del sistema CrediCrece
 */

require_once "modelos/conexion.php";

echo "<h2>🔧 CORRIGIENDO ERROR EN DASHBOARD</h2>";
echo "<p><strong>Error detectado:</strong> Unknown column 'c.cliente_nombres' in 'field list'</p>";
echo "<hr>";

try {
    $pdo = Conexion::conectar();
    
    // Leer el archivo SQL de corrección
    $sqlFile = 'fix_dashboard_procedures.sql';
    if (!file_exists($sqlFile)) {
        throw new Exception("No se encuentra el archivo fix_dashboard_procedures.sql");
    }
    
    $sql = file_get_contents($sqlFile);
    if ($sql === false) {
        throw new Exception("Error al leer el archivo SQL de corrección");
    }
    
    // Dividir las consultas SQL (separadas por punto y coma)
    $queries = explode(';', $sql);
    
    $ejecutadas = 0;
    $errores = 0;
    
    foreach ($queries as $query) {
        $query = trim($query);
        
        // Saltar consultas vacías y comentarios
        if (empty($query) || strpos($query, '--') === 0 || strpos($query, 'DELIMITER') === 0) {
            continue;
        }
        
        try {
            $pdo->exec($query);
            $ejecutadas++;
            
            // Mostrar progreso para queries importantes
            if (strpos($query, 'DROP PROCEDURE') !== false) {
                $procName = preg_match('/DROP PROCEDURE IF EXISTS\s+(\w+)/i', $query, $matches);
                if ($procName) {
                    echo "<p>✅ Eliminado procedimiento: <code>{$matches[1]}</code></p>";
                }
            } elseif (strpos($query, 'CREATE') !== false && strpos($query, 'PROCEDURE') !== false) {
                $procName = preg_match('/CREATE.*PROCEDURE\s+`?(\w+)`?\s*\(/i', $query, $matches);
                if ($procName) {
                    echo "<p>✅ Recreado procedimiento: <code>{$matches[1]}</code></p>";
                }
            }
            
        } catch (PDOException $e) {
            $errores++;
            echo "<p>❌ Error ejecutando query: " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p>Query: <code>" . htmlspecialchars(substr($query, 0, 100)) . "...</code></p>";
        }
    }
    
    echo "<hr>";
    echo "<h3>📊 RESUMEN DE CORRECCIÓN</h3>";
    echo "<p>✅ <strong>Consultas ejecutadas:</strong> $ejecutadas</p>";
    echo "<p>" . ($errores > 0 ? "❌" : "✅") . " <strong>Errores:</strong> $errores</p>";
    
    if ($errores === 0) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>🎉 ¡CORRECCIÓN COMPLETADA EXITOSAMENTE!</h4>";
        echo "<p>Los procedimientos del dashboard han sido corregidos.</p>";
        echo "<p><strong>Acciones completadas:</strong></p>";
        echo "<ul>";
        echo "<li>✅ Eliminados procedimientos corruptos</li>";
        echo "<li>✅ Recreados procedimientos con estructura correcta</li>";
        echo "<li>✅ Error 'c.cliente_nombres' solucionado</li>";
        echo "</ul>";
        echo "</div>";
        
        echo "<h4>🚀 PRÓXIMOS PASOS</h4>";
        echo "<ol>";
        echo "<li><strong>Prueba el dashboard:</strong> <a href='index.php' target='_blank' style='background: #007bff; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;'>Ir al Dashboard</a></li>";
        echo "<li><strong>Verifica logs:</strong> Los errores en <code>/xampp/apache/logs/error.log</code> deberían haber cesado</li>";
        echo "<li><strong>Confirma funcionalidad:</strong> Las tablas de clientes y cuotas vencidas deberían cargar correctamente</li>";
        echo "</ol>";
        
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>⚠️ CORRECCIÓN PARCIAL</h4>";
        echo "<p>Se completó la corrección pero con algunos errores. Revisa los detalles arriba.</p>";
        echo "</div>";
    }
    
    // Verificación adicional: Probar los procedimientos
    echo "<hr>";
    echo "<h3>🔍 VERIFICACIÓN DE PROCEDIMIENTOS</h3>";
    
    $procedimientos = ['SP_DATOS_DASHBOARD', 'SP_CLIENTES_CON_PRESTAMOS', 'SP_CUOTAS_VENCIDAS', 'SP_PRESTAMOS_MES_ACTUAL'];
    
    foreach ($procedimientos as $proc) {
        try {
            $stmt = $pdo->prepare("CALL $proc()");
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo "<p>✅ <strong>$proc:</strong> Funciona correctamente (" . count($resultado) . " filas)</p>";
        } catch (PDOException $e) {
            echo "<p>❌ <strong>$proc:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ ERROR CRÍTICO</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Por favor, ejecuta manualmente el archivo <code>fix_dashboard_procedures.sql</code> en phpMyAdmin.</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Volver al Sistema</a></p>";
?> 