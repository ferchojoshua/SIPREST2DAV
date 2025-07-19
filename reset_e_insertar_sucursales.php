<?php
/**
 * SCRIPT PARA REINICIAR CONTADOR DE SUCURSALES E INSERTAR DATOS DE EJEMPLO
 * Para ejecutar desde terminal: php reset_e_insertar_sucursales.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'modelos/conexion.php';

echo "=== REINICIO E INSERCIÓN DE SUCURSALES DE EJEMPLO ===\n";
echo "Este script hará lo siguiente:\n";
echo "1. Eliminar TODOS los registros de sucursales\n";
echo "2. Reiniciar el contador AUTO_INCREMENT\n";
echo "3. Insertar 5 sucursales de ejemplo\n";
echo "\n¿Estás seguro de que quieres continuar? (s/n): ";

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
    
    echo "\n=== FASE 1: REINICIO ===\n";
    
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
    
    echo "\n=== FASE 2: INSERCIÓN DE DATOS DE EJEMPLO ===\n";
    
    // Datos de ejemplo para sucursales
    $sucursales_ejemplo = [
        ['Sucursal Central', 'Av. Principal 123, Ciudad Centro', '+1 234 567 8900', 'SUC001', 'activa'],
        ['Sucursal Norte', 'Calle Norte 456, Zona Norte', '+1 234 567 8901', 'SUC002', 'activa'],
        ['Sucursal Sur', 'Av. Sur 789, Zona Sur', '+1 234 567 8902', 'SUC003', 'activa'],
        ['Sucursal Este', 'Calle Este 321, Zona Este', '+1 234 567 8903', 'SUC004', 'activa'],
        ['Sucursal Oeste', 'Av. Oeste 654, Zona Oeste', '+1 234 567 8904', 'SUC005', 'inactiva']
    ];
    
    echo "Insertando sucursales de ejemplo...\n";
    $stmt = $pdo->prepare("
        INSERT INTO sucursales (nombre, direccion, telefono, codigo, estado, empresa_id, fecha_registro) 
        VALUES (?, ?, ?, ?, ?, 1, NOW())
    ");
    
    $insertados = 0;
    foreach ($sucursales_ejemplo as $sucursal) {
        $stmt->execute($sucursal);
        $insertados++;
        echo "- Insertada: {$sucursal[0]} ({$sucursal[3]})\n";
    }
    
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
    
    $stmt = $pdo->query("
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN estado = 'activa' THEN 1 ELSE 0 END) as activas,
            SUM(CASE WHEN estado = 'inactiva' THEN 1 ELSE 0 END) as inactivas
        FROM sucursales
    ");
    $resumen = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Confirmar transacción
    $pdo->commit();
    
    echo "\n=== RESULTADO FINAL ===\n";
    echo "✓ Registros eliminados: $deleted\n";
    echo "✓ Clientes actualizados: $affected\n";
    echo "✓ Sucursales insertadas: $insertados\n";
    echo "✓ Total de sucursales: {$resumen['total']}\n";
    echo "✓ Sucursales activas: {$resumen['activas']}\n";
    echo "✓ Sucursales inactivas: {$resumen['inactivas']}\n";
    echo "✓ Próximo ID: $next_id\n";
    echo "✓ Proceso completado exitosamente\n";
    
    echo "\n=== LISTADO DE SUCURSALES CREADAS ===\n";
    $stmt = $pdo->query("SELECT id, nombre, codigo, estado FROM sucursales ORDER BY id");
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($sucursales as $sucursal) {
        echo "ID: {$sucursal['id']} | {$sucursal['nombre']} | {$sucursal['codigo']} | {$sucursal['estado']}\n";
    }
    
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