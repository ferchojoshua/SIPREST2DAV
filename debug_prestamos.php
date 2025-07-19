<?php
// Debug para verificar datos de préstamos y cuotas
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🔍 Debug de Préstamos y Cuotas</h2>";

try {
    require_once 'modelos/conexion.php';
    $conexion = Conexion::conectar();
    
    // 1. Verificar préstamos existentes
    echo "<h3>📋 Préstamos en prestamo_cabecera:</h3>";
    $stmt = $conexion->prepare("SELECT nro_prestamo, cliente_id, pres_aprobacion, pres_cuotas FROM prestamo_cabecera LIMIT 10");
    $stmt->execute();
    $prestamos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($prestamos)) {
        echo "❌ No hay préstamos en prestamo_cabecera<br>";
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Nro Préstamo</th><th>Cliente ID</th><th>Aprobación</th><th>Cuotas</th></tr>";
        foreach ($prestamos as $prestamo) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($prestamo['nro_prestamo']) . "</td>";
            echo "<td>" . htmlspecialchars($prestamo['cliente_id']) . "</td>";
            echo "<td>" . htmlspecialchars($prestamo['pres_aprobacion']) . "</td>";
            echo "<td>" . htmlspecialchars($prestamo['pres_cuotas']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 2. Verificar detalles de préstamos
    echo "<h3>📊 Detalles en prestamo_detalle:</h3>";
    $stmt = $conexion->prepare("SELECT nro_prestamo, pdetalle_nro_cuota, pdetalle_estado_cuota, pdetalle_saldo_cuota FROM prestamo_detalle ORDER BY nro_prestamo, pdetalle_nro_cuota LIMIT 20");
    $stmt->execute();
    $detalles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($detalles)) {
        echo "❌ No hay detalles en prestamo_detalle<br>";
    } else {
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Nro Préstamo</th><th>Nro Cuota</th><th>Estado</th><th>Saldo</th></tr>";
        foreach ($detalles as $detalle) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($detalle['nro_prestamo']) . "</td>";
            echo "<td>" . htmlspecialchars($detalle['pdetalle_nro_cuota']) . "</td>";
            echo "<td>" . htmlspecialchars($detalle['pdetalle_estado_cuota']) . "</td>";
            echo "<td>" . htmlspecialchars($detalle['pdetalle_saldo_cuota']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // 3. Test específico con un préstamo
    if (!empty($prestamos)) {
        $primer_prestamo = $prestamos[0]['nro_prestamo'];
        echo "<h3>🧪 Test con préstamo: $primer_prestamo</h3>";
        
        // Buscar cuotas de este préstamo
        $stmt = $conexion->prepare("SELECT pdetalle_nro_cuota, pdetalle_estado_cuota, pdetalle_saldo_cuota FROM prestamo_detalle WHERE nro_prestamo = :nro_prestamo ORDER BY pdetalle_nro_cuota");
        $stmt->bindParam(":nro_prestamo", $primer_prestamo, PDO::PARAM_STR);
        $stmt->execute();
        $cuotas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($cuotas)) {
            echo "❌ No hay cuotas para el préstamo $primer_prestamo<br>";
        } else {
            echo "✅ Cuotas encontradas para $primer_prestamo:<br>";
            foreach ($cuotas as $cuota) {
                echo "- Cuota " . $cuota['pdetalle_nro_cuota'] . " - Estado: " . $cuota['pdetalle_estado_cuota'] . " - Saldo: " . $cuota['pdetalle_saldo_cuota'] . "<br>";
            }
            
            // Test de búsqueda específica
            $primera_cuota = $cuotas[0]['pdetalle_nro_cuota'];
            echo "<h4>🔍 Test de búsqueda específica:</h4>";
            echo "Buscando préstamo: '$primer_prestamo', cuota: '$primera_cuota'<br>";
            
            $stmt_test = $conexion->prepare("SELECT pdetalle_monto_cuota, pdetalle_saldo_cuota, pdetalle_estado_cuota FROM prestamo_detalle WHERE nro_prestamo = :nro_prestamo AND pdetalle_nro_cuota = :pdetalle_nro_cuota");
            $stmt_test->bindParam(":nro_prestamo", $primer_prestamo, PDO::PARAM_STR);
            $stmt_test->bindParam(":pdetalle_nro_cuota", $primera_cuota, PDO::PARAM_INT);
            $stmt_test->execute();
            $resultado_test = $stmt_test->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado_test) {
                echo "✅ Cuota encontrada exitosamente:<br>";
                echo "- Monto: " . $resultado_test['pdetalle_monto_cuota'] . "<br>";
                echo "- Saldo: " . $resultado_test['pdetalle_saldo_cuota'] . "<br>";
                echo "- Estado: " . $resultado_test['pdetalle_estado_cuota'] . "<br>";
            } else {
                echo "❌ No se pudo encontrar la cuota con la consulta exacta<br>";
            }
        }
    }
    
    // 4. Verificar estructura de tabla
    echo "<h3>🏗️ Estructura de prestamo_detalle:</h3>";
    $stmt = $conexion->prepare("DESCRIBE prestamo_detalle");
    $stmt->execute();
    $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($columnas as $columna) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($columna['Field']) . "</td>";
        echo "<td>" . htmlspecialchars($columna['Type']) . "</td>";
        echo "<td>" . htmlspecialchars($columna['Null']) . "</td>";
        echo "<td>" . htmlspecialchars($columna['Key']) . "</td>";
        echo "<td>" . htmlspecialchars($columna['Default']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<h3>📝 Instrucciones:</h3>";
echo "<ol>";
echo "<li>Ejecuta este archivo y revisa los datos mostrados</li>";
echo "<li>Verifica que existan préstamos y cuotas</li>";
echo "<li>Anota el número de préstamo y cuota que quieres usar para el abono</li>";
echo "<li>Si no hay datos, necesitas crear préstamos primero</li>";
echo "</ol>";
?> 