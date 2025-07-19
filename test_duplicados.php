<?php
header('Content-Type: text/html; charset=utf-8');
echo "<h1>Test de Verificación de Duplicados</h1>";

// Activar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir archivos
require_once 'modelos/conexion.php';
require_once 'modelos/sucursales_modelo.php';

echo "<h2>1. Probando conexión...</h2>";
try {
    $pdo = Conexion::conectar();
    echo "<p style='color: green;'>✓ Conexión exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    exit;
}

echo "<h2>2. Probando modelo directamente...</h2>";
try {
    $resultado = SucursalModelo::mdlVerificarDuplicados('TEST001', 'Sucursal Test');
    echo "<p style='color: green;'>✓ Función ejecutada</p>";
    echo "<pre>";
    var_dump($resultado);
    echo "</pre>";
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
    echo "<p>Línea: " . $e->getLine() . "</p>";
    echo "<p>Archivo: " . $e->getFile() . "</p>";
}

echo "<h2>3. Probando con datos existentes...</h2>";
try {
    $stmt = $pdo->query("SELECT codigo, nombre FROM sucursales LIMIT 1");
    $sucursal = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($sucursal) {
        echo "<p>Probando con: {$sucursal['codigo']} - {$sucursal['nombre']}</p>";
        $resultado = SucursalModelo::mdlVerificarDuplicados($sucursal['codigo'], $sucursal['nombre']);
        echo "<pre>";
        var_dump($resultado);
        echo "</pre>";
    } else {
        echo "<p>No hay sucursales para probar</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Error: " . $e->getMessage() . "</p>";
}

echo "<h2>4. Probando AJAX directamente...</h2>";
?>
<script>
console.log('Probando AJAX...');
fetch('ajax/sucursales_ajax.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
        accion: 'verificar_duplicados',
        codigo: 'TEST001',
        nombre: 'Sucursal Test',
        id: ''
    })
})
.then(response => response.text())
.then(data => {
    console.log('Respuesta AJAX:', data);
    document.getElementById('ajax-result').innerHTML = '<pre>' + data + '</pre>';
})
.catch(error => {
    console.error('Error AJAX:', error);
    document.getElementById('ajax-result').innerHTML = '<p style="color: red;">Error: ' + error + '</p>';
});
</script>
<div id="ajax-result">
<p>Cargando resultado AJAX...</p>
</div> 