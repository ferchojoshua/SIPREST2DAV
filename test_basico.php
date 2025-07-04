<?php
// Test básico para identificar problemas

echo "<h1>TEST BÁSICO - PHP FUNCIONA</h1>";
echo "<p>Si ves esto, PHP está funcionando</p>";
echo "<p>Fecha actual: " . date('Y-m-d H:i:s') . "</p>";

// Test de conexión
echo "<h2>Test de Conexión a BD</h2>";
try {
    $conn = new PDO("mysql:host=localhost;dbname=dbprestamo", "root", "");
    echo "<p style='color: green;'>✅ Conexión a BD exitosa</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
}

// Test de sesiones
echo "<h2>Test de Sesiones</h2>";
session_start();
$_SESSION['test'] = 'funcionando';
if (isset($_SESSION['test'])) {
    echo "<p style='color: green;'>✅ Sesiones funcionando</p>";
} else {
    echo "<p style='color: red;'>❌ Problema con sesiones</p>";
}

echo "<h2>Ir a:</h2>";
echo "<p><a href='index.php'>index.php</a></p>";
echo "<p><a href='login_simple.php'>login_simple.php</a></p>";
echo "<p><a href='debug_login.php'>debug_login.php</a></p>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h1, h3 { color: #333; }
p { margin: 5px 0; }
</style> 