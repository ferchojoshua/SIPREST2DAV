<?php
// Script para limpiar menú duplicado
require_once "../conexion_reportes/r_conexion.php";

try {
    $pdo = Conexion::conectar();
    
    echo "<h2>🧹 LIMPIANDO MENÚ DUPLICADO</h2>";
    
    // Deshabilitar safe mode
    $pdo->exec("SET SQL_SAFE_UPDATES = 0");
    
    // 1. Eliminar duplicados de Caja
    $stmt = $pdo->exec("DELETE FROM modulos WHERE modulo = 'Caja' AND id NOT IN (SELECT * FROM (SELECT MIN(id) FROM modulos WHERE modulo = 'Caja') as temp)");
    echo "<p>✅ Eliminados " . $stmt . " módulos duplicados de Caja</p>";
    
    // 2. Eliminar duplicados de Clientes
    $stmt = $pdo->exec("DELETE FROM modulos WHERE modulo = 'Clientes' AND id NOT IN (SELECT * FROM (SELECT MIN(id) FROM modulos WHERE modulo = 'Clientes') as temp)");
    echo "<p>✅ Eliminados " . $stmt . " módulos duplicados de Clientes</p>";
    
    // 3. Eliminar duplicados de Prestamos
    $stmt = $pdo->exec("DELETE FROM modulos WHERE modulo = 'Prestamos' AND id NOT IN (SELECT * FROM (SELECT MIN(id) FROM modulos WHERE modulo = 'Prestamos') as temp)");
    echo "<p>✅ Eliminados " . $stmt . " módulos duplicados de Prestamos</p>";
    
    // 4. Eliminar módulo Dashboards padre
    $stmt = $pdo->exec("DELETE FROM modulos WHERE modulo = 'Dashboards'");
    echo "<p>✅ Eliminado módulo padre Dashboards</p>";
    
    // 5. Hacer Dashboard principal
    $pdo->exec("UPDATE modulos SET padre_id = 0, orden = 1 WHERE modulo = 'Dashboard' OR vista = 'dashboard.php'");
    echo "<p>✅ Dashboard configurado como principal</p>";
    
    // 6. Actualizar orden de módulos
    $ordenes = [
        "Dashboard" => 1,
        "Caja" => 2,
        "Clientes" => 3,
        "Prestamos" => 4,
        "Reportes" => 5,
        "Mantenimiento" => 6,
        "Notas de Débito" => 7,
        "Empresa" => 8,
        "Moneda" => 9,
        "Backup" => 10
    ];
    
    foreach ($ordenes as $modulo => $orden) {
        $pdo->exec("UPDATE modulos SET orden = $orden WHERE modulo = '$modulo'");
    }
    echo "<p>✅ Orden de módulos actualizado</p>";
    
    // 7. Limpiar permisos huérfanos
    $stmt = $pdo->exec("DELETE FROM perfil_modulo WHERE id_modulo NOT IN (SELECT id FROM modulos)");
    echo "<p>✅ Eliminados " . $stmt . " permisos huérfanos</p>";
    
    // 8. Asegurar permisos para administrador
    $pdo->exec("INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado) SELECT 1, m.id, CASE WHEN m.vista = 'dashboard.php' THEN 1 ELSE 0 END, 1 FROM modulos m WHERE m.padre_id = 0 OR m.padre_id IS NULL");
    echo "<p>✅ Permisos asegurados para administrador</p>";
    
    // Reactivar safe mode
    $pdo->exec("SET SQL_SAFE_UPDATES = 1");
    
    // Verificación final
    echo "<h3>📋 Módulos principales después de limpieza:</h3>";
    $stmt = $pdo->query("SELECT m.id, m.modulo, m.vista, m.orden FROM modulos m WHERE m.padre_id = 0 OR m.padre_id IS NULL ORDER BY m.orden");
    $modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Módulo</th><th>Vista</th><th>Orden</th></tr>";
    foreach ($modulos as $modulo) {
        echo "<tr>";
        echo "<td>{$modulo['id']}</td>";
        echo "<td>{$modulo['modulo']}</td>";
        echo "<td>{$modulo['vista']}</td>";
        echo "<td>{$modulo['orden']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>🎉 Limpieza completada exitosamente</h3>";
    echo "<p><strong>Ahora recarga el sistema:</strong></p>";
    echo "<p><a href='../index.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔄 Recargar Sistema</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 