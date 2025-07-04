<?php
// Test básico de PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Test PHP Básico</h1>";
echo "<p>PHP funciona: ✅</p>";
echo "<p>Versión: " . phpversion() . "</p>";

try {
    require_once 'modelos/conexion.php';
    echo "<p>Archivo conexion.php cargado: ✅</p>";
    
    $conn = Conexion::conectar();
    echo "<p>Conexión PDO: ✅</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "<p>Test completado</p>";
?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; }
h1, h2 { color: #333; }
p { margin: 8px 0; }
</style> 