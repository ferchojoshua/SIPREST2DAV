<?php
/**
 * SCRIPT SIMPLE PARA REINICIAR CONTADOR DE SUCURSALES
 * Para ejecutar desde terminal: php reset_sucursales_simple.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'modelos/conexion.php';

echo "=== REINICIO DE CONTADOR DE SUCURSALES ===\n";
echo "ADVERTENCIA: Este script eliminará TODOS los registros de sucursales.\n";
echo "¿Estás seguro de que quieres continuar? (s/n): ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$respuesta = trim($line);

if (strtolower($respuesta) !== 's' && strtolower($respuesta) !== 'si') {
    echo "Operación cancelada.\n";
    exit;
}

try {
    $pdo = Conexion::conectar();
    
    // Verificar registros actuales
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sucursales");
    $count = $stmt->fetchColumn();
    echo "Registros encontrados: $count\n";
    
    // Verificar clientes con sucursal
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes WHERE sucursal_id IS NOT NULL");
    $clientes_con_sucursal = $stmt->fetchColumn();
    echo "Clientes con sucursal asignada: $clientes_con_sucursal\n";
    
    if ($clientes_con_sucursal > 0) {
        echo "ATENCIÓN: $clientes_con_sucursal clientes quedarán sin sucursal asignada.\n";
        echo "¿Continuar? (s/n): ";
        $line = fgets($handle);
        $respuesta = trim($line);
        
        if (strtolower($respuesta) !== 's' && strtolower($respuesta) !== 'si') {
            echo "Operación cancelada.\n";
            exit;
        }
    }
    
    // Iniciar transacción
    $pdo->beginTransaction();
    
    // Ejecutar script de reinicio
    echo "Deshabilitando verificación de claves foráneas...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    
    echo "Limpiando referencias en clientes...\n";
    $stmt = $pdo->prepare("UPDATE clientes SET sucursal_id = NULL WHERE sucursal_id IS NOT NULL");
    $stmt->execute();
    $affected = $stmt->rowCount();
    echo "- $affected clientes actualizados\n";
    
    echo "Eliminando registros de sucursales...\n";
    $stmt = $pdo->prepare("DELETE FROM sucursales");
    $stmt->execute();
    $deleted = $stmt->rowCount();
    echo "- $deleted registros eliminados\n";
    
    echo "Reiniciando contador AUTO_INCREMENT...\n";
    $pdo->exec("ALTER TABLE sucursales AUTO_INCREMENT = 1");
    
    echo "Habilitando verificación de claves foráneas...\n";
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Verificar resultado
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sucursales");
    $final_count = $stmt->fetchColumn();
    
    $stmt = $pdo->query("
        SELECT AUTO_INCREMENT 
        FROM information_schema.TABLES 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'sucursales'
    ");
    $next_id = $stmt->fetchColumn();
    
    // Confirmar transacción
    $pdo->commit();
    
    echo "\n=== RESULTADO ===\n";
    echo "✓ Registros restantes: $final_count\n";
    echo "✓ Próximo ID: $next_id\n";
    echo "✓ Clientes actualizados: $affected\n";
    echo "✓ Contador reiniciado correctamente\n";
    
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "La transacción ha sido revertida.\n";
}

fclose($handle);
echo "\nScript completado.\n";
?> 