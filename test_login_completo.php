<?php
session_start();
require_once 'modelos/conexion.php';
require_once 'modelos/usuario_modelo.php';

echo "<h2>🧪 Test Login Completo - GUNNER</h2>";
echo "<hr>";

// Simular el login exactamente como lo hace el controlador
$usuario = 'Gunner';
$password_raw = '123456';
$password_hash = crypt($password_raw, '$2a$07$azybxcags23425sdg23sdfhsd$');

echo "<h3>📝 Datos de Login</h3>";
echo "<p><strong>Usuario:</strong> $usuario</p>";
echo "<p><strong>Contraseña:</strong> $password_raw</p>";
echo "<p><strong>Hash:</strong> $password_hash</p>";

echo "<h3>🔴 Test Método Original</h3>";
$respuesta1 = UsuarioModelo::mdlIniciarSesion($usuario, $password_hash);
echo "<p><strong>Resultados:</strong> " . count($respuesta1) . " registros</p>";

if (count($respuesta1) > 0) {
    echo "<p style='color: green;'>✅ <strong>Método original FUNCIONA</strong></p>";
    $_SESSION["usuario"] = $respuesta1[0];
    echo "<p>✅ Sesión creada exitosamente</p>";
} else {
    echo "<p style='color: red;'>❌ Método original FALLA</p>";
    
    echo "<h3>🟡 Test Método Simplificado</h3>";
    $respuesta2 = UsuarioModelo::mdlIniciarSesionSimple($usuario, $password_hash);
    echo "<p><strong>Resultados:</strong> " . count($respuesta2) . " registros</p>";
    
    if (count($respuesta2) > 0) {
        echo "<p style='color: green;'>✅ <strong>Método simplificado FUNCIONA</strong></p>";
        $_SESSION["usuario"] = $respuesta2[0];
        echo "<p>✅ Sesión creada exitosamente</p>";
    } else {
        echo "<p style='color: red;'>❌ Ambos métodos FALLAN - Revisar configuración</p>";
    }
}

// Resultado final
if (isset($_SESSION["usuario"])) {
    echo "<h3 style='color: green;'>✅ SESIÓN ACTIVA - LOGIN EXITOSO</h3>";
    echo "<div style='background: #d1e7dd; padding: 15px; border-radius: 8px;'>";
    echo "<p><strong>🎉 ¡LOGIN FUNCIONÓ!</strong></p>";
    echo "<p>Credenciales correctas:</p>";
    echo "<p><strong>Usuario:</strong> Gunner</p>";
    echo "<p><strong>Contraseña:</strong> 123456</p>";
    echo "</div>";
    
    echo "<h4>🔗 Ir al Sistema:</h4>";
    echo "<p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>ACCEDER AL SISTEMA</a></p>";
} else {
    echo "<h3 style='color: red;'>❌ PROBLEMA EN EL LOGIN</h3>";
}

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h2, h3, h4 { color: #333; }
p { margin: 8px 0; }
</style> 