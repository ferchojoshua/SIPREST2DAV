<?php
session_start();
require_once 'modelos/conexion.php';
require_once 'modelos/usuario_modelo.php';

echo "<h2>🔍 Debug Login SIPREST - ACTUALIZADO</h2>";
echo "<hr>";

// Listar usuarios disponibles
echo "<h3>👥 Usuarios Disponibles en BD</h3>";
try {
    $conn = Conexion::conectar();
    $stmt = $conn->prepare("SELECT id_usuario, nombre_usuario, apellido_usuario, usuario, estado FROM usuarios ORDER BY id_usuario");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($users) > 0) {
        echo "<table style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
        echo "<tr style='background: #f0f0f0;'>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>ID</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Nombre</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Usuario</th>";
        echo "<th style='padding: 8px; border: 1px solid #ddd;'>Estado</th>";
        echo "</tr>";
        
        foreach ($users as $user) {
            $color = $user['estado'] == 1 ? 'green' : 'red';
            $estado_text = $user['estado'] == 1 ? 'Activo' : 'Inactivo';
            echo "<tr>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $user['id_usuario'] . "</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'>" . $user['nombre_usuario'] . " " . $user['apellido_usuario'] . "</td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd;'><strong>" . $user['usuario'] . "</strong></td>";
            echo "<td style='padding: 8px; border: 1px solid #ddd; color: $color;'>$estado_text</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test con usuario Gunner
        echo "<h3>🧪 Test Login con Usuario 'Gunner'</h3>";
        $usuario_test = 'Gunner';
        
        // Obtener la clave actual del usuario Gunner
        $stmt2 = $conn->prepare("SELECT clave FROM usuarios WHERE usuario = :usuario AND estado = 1");
        $stmt2->bindParam(":usuario", $usuario_test, PDO::PARAM_STR);
        $stmt2->execute();
        $user_data = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        if ($user_data) {
            echo "<p><strong>Clave actual en BD:</strong> " . $user_data['clave'] . "</p>";
            
            // Probar contraseñas comunes
            $passwords_comunes = ['123456', '1234', 'admin', 'password', 'gunner', 'Gunner', 'gunner123'];
            echo "<h4>🔍 Probando contraseñas comunes:</h4>";
            
            $password_encontrada = false;
            foreach ($passwords_comunes as $pwd) {
                $hash_test = crypt($pwd, '$2a$07$azybxcags23425sdg23sdfhsd$');
                
                if ($user_data['clave'] === $hash_test) {
                    echo "<p style='color: green; font-size: 18px; font-weight: bold;'>🎉 ¡CONTRASEÑA ENCONTRADA!</p>";
                    echo "<p style='color: green; font-size: 16px;'>✅ Usuario: <strong>Gunner</strong></p>";
                    echo "<p style='color: green; font-size: 16px;'>✅ Contraseña: <strong>$pwd</strong></p>";
                    $password_encontrada = true;
                    break;
                } else {
                    echo "<p>❌ No es: '$pwd'</p>";
                }
            }
            
            if (!$password_encontrada) {
                echo "<p style='color: orange; font-size: 16px;'>⚠️ <strong>Contraseña no encontrada en lista común</strong></p>";
                echo "<h4>🔧 Solución: Usar Reset de Contraseña</h4>";
                echo "<div style='background: #e8f4f8; padding: 15px; border-radius: 8px; margin: 10px 0;'>";
                echo "<p><strong>INSTRUCCIONES:</strong></p>";
                echo "<ol>";
                echo "<li>Ve al login principal</li>";
                echo "<li>Haz clic en '¿Olvidó su contraseña?'</li>";
                echo "<li>Ingresa: <strong>Usuario: Gunner</strong></li>";
                echo "<li>Ingresa: <strong>Nueva contraseña: 123456</strong></li>";
                echo "<li>Confirma: <strong>123456</strong></li>";
                echo "<li>Haz clic en 'Resetear'</li>";
                echo "<li>Luego haz login con Gunner / 123456</li>";
                echo "</ol>";
                echo "</div>";
            }
            
        } else {
            echo "<p style='color: red;'>❌ Usuario 'Gunner' no encontrado</p>";
        }
        
    } else {
        echo "<p style='color: red;'>❌ No hay usuarios en la base de datos</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<h3>🎯 RESUMEN DEL PROBLEMA</h3>";
echo "<div style='background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 4px solid #ffc107;'>";
echo "<p><strong>❌ PROBLEMA IDENTIFICADO:</strong></p>";
echo "<p>No existe usuario 'admin' en la base de datos.</p>";
echo "<p>Los usuarios disponibles son: <strong>Gunner</strong> y <strong>Colector</strong></p>";
echo "</div>";

echo "<div style='background: #d1e7dd; padding: 15px; border-radius: 8px; border-left: 4px solid #198754; margin-top: 10px;'>";
echo "<p><strong>✅ SOLUCIÓN:</strong></p>";
echo "<p>1. Usa el usuario <strong>'Gunner'</strong> para hacer login</p>";
echo "<p>2. Si no sabes la contraseña, usa el reset de contraseña</p>";
echo "<p>3. Establece una nueva contraseña como '123456'</p>";
echo "</div>";

?>

<style>
body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
h2, h3, h4 { color: #333; }
p { margin: 8px 0; }
ol, ul { margin: 10px 0; }
li { margin: 5px 0; }
table { margin: 15px 0; }
th { background: #f8f9fa; font-weight: bold; }
</style> 