<?php
/**
 * SCRIPT PARA REINICIAR CONTADOR DE SUCURSALES
 * 
 * ADVERTENCIA: Este script eliminará TODOS los registros de sucursales
 * Asegúrate de hacer un backup antes de ejecutar este script
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivo de conexión
require_once 'modelos/conexion.php';

echo "<h2>🔄 REINICIO DE CONTADOR DE SUCURSALES</h2>";
echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
echo "<strong>⚠️ ADVERTENCIA:</strong> Este script eliminará TODOS los registros de sucursales.";
echo "</div>";

try {
    $pdo = Conexion::conectar();
    
    // Iniciar transacción
    $pdo->beginTransaction();
    
    echo "<h3>📋 Proceso de reinicio:</h3>";
    echo "<ol>";
    
    // 1. Verificar registros actuales
    echo "<li>Verificando registros actuales...</li>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sucursales");
    $count = $stmt->fetchColumn();
    echo "<div style='margin-left: 20px; color: #666;'>Registros encontrados: <strong>$count</strong></div>";
    
    // 2. Verificar relaciones con otras tablas
    echo "<li>Verificando relaciones con tabla clientes...</li>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes WHERE sucursal_id IS NOT NULL");
    $clientes_con_sucursal = $stmt->fetchColumn();
    echo "<div style='margin-left: 20px; color: #666;'>Clientes con sucursal asignada: <strong>$clientes_con_sucursal</strong></div>";
    
    if ($clientes_con_sucursal > 0) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
        echo "<strong>⚠️ ATENCIÓN:</strong> Hay $clientes_con_sucursal clientes con sucursal asignada. ";
        echo "Estos clientes quedarán sin sucursal asignada (sucursal_id = NULL).";
        echo "</div>";
    }
    
    // 3. Deshabilitar verificación de claves foráneas
    echo "<li>Deshabilitando verificación de claves foráneas...</li>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    echo "<div style='margin-left: 20px; color: #28a745;'>✓ Completado</div>";
    
    // 4. Limpiar referencia de sucursal en clientes
    echo "<li>Limpiando referencias de sucursal en clientes...</li>";
    $stmt = $pdo->prepare("UPDATE clientes SET sucursal_id = NULL WHERE sucursal_id IS NOT NULL");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "<div style='margin-left: 20px; color: #28a745;'>✓ $affected clientes actualizados</div>";
    
    // 5. Eliminar registros de sucursales
    echo "<li>Eliminando registros de sucursales...</li>";
    $stmt = $pdo->prepare("DELETE FROM sucursales");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "<div style='margin-left: 20px; color: #28a745;'>✓ $deleted registros eliminados</div>";
    
    // 6. Reiniciar contador AUTO_INCREMENT
    echo "<li>Reiniciando contador AUTO_INCREMENT...</li>";
    $pdo->exec("ALTER TABLE sucursales AUTO_INCREMENT = 1");
    echo "<div style='margin-left: 20px; color: #28a745;'>✓ Contador reiniciado a 1</div>";
    
    // 7. Habilitar verificación de claves foráneas
    echo "<li>Habilitando verificación de claves foráneas...</li>";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "<div style='margin-left: 20px; color: #28a745;'>✓ Completado</div>";
    
    // 8. Verificar resultado
    echo "<li>Verificando resultado...</li>";
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sucursales");
    $final_count = $stmt->fetchColumn();
    
    $stmt = $pdo->query("
        SELECT AUTO_INCREMENT 
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'sucursales'
    ");
    $next_id = $stmt->fetchColumn();
    
    echo "<div style='margin-left: 20px; color: #28a745;'>";
    echo "✓ Registros restantes: <strong>$final_count</strong><br>";
    echo "✓ Próximo ID: <strong>$next_id</strong>";
    echo "</div>";
    
    echo "</ol>";
    
    // Confirmar transacción
    $pdo->commit();
    
    echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<strong>✅ ÉXITO:</strong> El contador de sucursales ha sido reiniciado correctamente.";
    echo "<br><strong>Estado final:</strong>";
    echo "<br>• Registros de sucursales: $final_count";
    echo "<br>• Próximo ID: $next_id";
    echo "<br>• Clientes actualizados: $affected";
    echo "</div>";
    
} catch (Exception $e) {
    // Revertir transacción en caso de error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<strong>❌ ERROR:</strong> No se pudo reiniciar el contador de sucursales.";
    echo "<br><strong>Detalle del error:</strong> " . $e->getMessage();
    echo "<br><strong>Línea:</strong> " . $e->getLine();
    echo "<br><strong>Archivo:</strong> " . $e->getFile();
    echo "</div>";
    
    echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    echo "<strong>ℹ️ INFORMACIÓN:</strong> La transacción ha sido revertida. No se realizaron cambios en la base de datos.";
    echo "</div>";
}

echo "<br><hr>";
echo "<p><strong>📝 Notas importantes:</strong></p>";
echo "<ul>";
echo "<li>Este script debe ejecutarse solo cuando sea necesario reiniciar completamente el sistema de sucursales.</li>";
echo "<li>Se recomienda hacer un backup de la base de datos antes de ejecutar este script.</li>";
echo "<li>Después de ejecutar este script, necesitarás crear nuevamente las sucursales.</li>";
echo "<li>Los clientes que tenían sucursal asignada quedarán sin sucursal (sucursal_id = NULL).</li>";
echo "</ul>";

echo "<p><a href='vistas/sucursales.php' style='display: inline-block; background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px;'>← Volver al módulo de Sucursales</a></p>";
?> 