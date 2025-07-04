<?php
/**
 * Script de prueba para WhatsApp
 * 
 * Este archivo permite verificar si la configuración de WhatsApp está correcta
 * sin necesidad de realizar un pago real.
 */

require_once 'WhatsAppAPI.php';
require_once 'whatsapp_config.php';

// Verificar configuración
echo "<h2>🧪 Prueba de Configuración WhatsApp</h2>";
echo "<hr>";

$validacion = validarConfigWhatsApp();

echo "<h3>1. Validación de Configuración:</h3>";
if ($validacion['valido']) {
    echo "<p style='color: green;'>✅ " . $validacion['mensaje'] . "</p>";
} else {
    echo "<p style='color: red;'>❌ " . $validacion['mensaje'] . "</p>";
    echo "<p><strong>Acción requerida:</strong> Revisar el archivo utilitarios/whatsapp_config.php</p>";
}

// Mostrar configuración actual (sin mostrar credenciales completas)
echo "<h3>2. Configuración Actual:</h3>";
$config = obtenerConfigWhatsApp();

echo "<ul>";
echo "<li><strong>Account SID:</strong> " . substr($config['account_sid'], 0, 8) . "..." . "</li>";
echo "<li><strong>Auth Token:</strong> " . (strlen($config['auth_token']) > 10 ? "Configurado (" . strlen($config['auth_token']) . " caracteres)" : "No configurado") . "</li>";
echo "<li><strong>Número WhatsApp:</strong> " . $config['whatsapp_number'] . "</li>";
echo "<li><strong>Código País:</strong> " . $config['codigo_pais'] . "</li>";
echo "<li><strong>Estado:</strong> " . ($config['activo'] ? "🟢 Activo" : "🔴 Inactivo") . "</li>";
echo "</ul>";

// Prueba de formateo de número
echo "<h3>3. Prueba de Formateo de Números:</h3>";
if ($validacion['valido']) {
    $whatsapp = new WhatsAppAPI();
    
    $numeros_prueba = [
        '987654321',
        '+51987654321',
        '51 987 654 321',
        '+51-987-654-321'
    ];
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Número Original</th><th>Número Formateado</th></tr>";
    
    foreach ($numeros_prueba as $numero) {
        $formateado = $whatsapp->formatearNumeroTelefono($numero);
        echo "<tr><td>$numero</td><td>$formateado</td></tr>";
    }
    
    echo "</table>";
}

// Prueba de mensaje
echo "<h3>4. Preview del Mensaje:</h3>";
if ($validacion['valido']) {
    $whatsapp = new WhatsAppAPI();
    
    // Datos de ejemplo
    $mensaje_pago = $whatsapp->crearMensajePago(
        'Juan Pérez García',
        'PR-2024-001',
        3,
        250.00,
        1750.00,
        'S/'
    );
    
    $mensaje_abono = $whatsapp->crearMensajeAbono(
        'María López Torres',
        'PR-2024-002',
        2,
        100.00,
        1900.00,
        'S/'
    );
    
    echo "<h4>📱 Mensaje de Pago Completo:</h4>";
    echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px; white-space: pre-line; font-family: monospace;'>";
    echo htmlspecialchars($mensaje_pago);
    echo "</div>";
    
    echo "<h4>💰 Mensaje de Abono Parcial:</h4>";
    echo "<div style='background: #f0f0f0; padding: 15px; border-radius: 5px; white-space: pre-line; font-family: monospace;'>";
    echo htmlspecialchars($mensaje_abono);
    echo "</div>";
}

// Instrucciones
echo "<h3>5. Próximos Pasos:</h3>";
echo "<ol>";

if (!$validacion['valido']) {
    echo "<li>Configurar las credenciales en <code>utilitarios/whatsapp_config.php</code></li>";
    echo "<li>Cambiar <code>WHATSAPP_ACTIVO</code> a <code>true</code></li>";
} else {
    echo "<li>✅ Configuración completada</li>";
}

echo "<li>Para pruebas: Configurar Sandbox de Twilio y unir números de prueba</li>";
echo "<li>Para producción: Solicitar WhatsApp Business API</li>";
echo "<li>Verificar que los clientes tengan números de celular registrados</li>";
echo "<li>Realizar un pago de prueba para verificar el envío automático</li>";
echo "</ol>";

echo "<h3>6. Enlaces Útiles:</h3>";
echo "<ul>";
echo "<li><a href='https://console.twilio.com/' target='_blank'>Twilio Console</a></li>";
echo "<li><a href='https://www.twilio.com/docs/whatsapp/sandbox' target='_blank'>WhatsApp Sandbox</a></li>";
echo "<li><a href='https://www.twilio.com/docs/whatsapp' target='_blank'>Documentación WhatsApp</a></li>";
echo "</ul>";

echo "<hr>";
echo "<p><small>💡 <strong>Tip:</strong> Revisa los logs del servidor para ver el resultado de los envíos de WhatsApp.</small></p>";

?>

<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        line-height: 1.6;
    }
    h2 { color: #333; }
    h3 { color: #666; margin-top: 30px; }
    h4 { color: #888; }
    table { margin: 15px 0; }
    th, td { padding: 8px 12px; text-align: left; }
    th { background-color: #f5f5f5; }
    code { background-color: #f0f0f0; padding: 2px 5px; border-radius: 3px; }
    ul, ol { margin: 15px 0; }
    li { margin: 5px 0; }
</style> 