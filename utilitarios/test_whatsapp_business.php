    <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Test de API de WhatsApp Business</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; max-width: 800px; margin: 20px auto; padding: 0 15px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1, h2 { color: #1a73e8; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .status { padding: 15px; margin: 20px 0; border-radius: 5px; border: 1px solid; }
        .success { background-color: #e6f4ea; border-color: #b7e1c3; color: #155724; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .warning { background-color: #fff3cd; border-color: #ffeeba; color: #856404; }
        code { background-color: #f1f1f1; padding: 2px 6px; border-radius: 4px; font-family: "SFMono-Regular", Consolas, "Liberation Mono", Menlo, Courier, monospace; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; margin-bottom: 5px; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #1a73e8; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; }
        button:hover { background-color: #1558b8; }
        .step { margin-top: 15px; }
        ol { padding-left: 20px; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Test de API de WhatsApp Business</h1>
        <p>Esta página te ayuda a verificar tu configuración y a enviar un mensaje de prueba usando la API oficial de WhatsApp.</p>

        <?php
        require_once 'whatsapp_business_config.php';
        $configMessage = '';
        $isConfigValid = false;

        try {
            $validation = validarConfigWhatsAppBusiness();
            if ($validation['valido']) {
                $isConfigValid = true;
                $configMessage = "<div class='status success'>✅ " . htmlspecialchars($validation['mensaje']) . "</div>";
            } else {
                $configMessage = "<div class='status error'>❌ " . htmlspecialchars($validation['mensaje']) . "</div>";
            }
        } catch (Exception $e) {
            $configMessage = "<div class='status error'>❌ Error fatal: " . $e->getMessage() . "</div>";
        }
        
        echo $configMessage;
        ?>

        <h2>📋 Pasos para la Configuración</h2>
        <ol>
            <li>Ve a <strong><a href="https://developers.facebook.com/apps/" target="_blank">Facebook Developers</a></strong>, crea una App tipo "Business" y agrégale el producto "WhatsApp".</li>
            <li>En la sección "Introducción a la API", copia el <strong>Token de acceso</strong> y el <strong>ID del número de teléfono</strong>.</li>
            <li>Abre el archivo <code>utilitarios/whatsapp_business_config.php</code>.</li>
            <li>Pega tus credenciales en las constantes <code>WHATSAPP_ACCESS_TOKEN</code> y <code>WHATSAPP_PHONE_NUMBER_ID</code>.</li>
            <li>Cambia <code>define('WHATSAPP_BUSINESS_ACTIVO', false);</code> a <code>true</code>.</li>
            <li>Guarda el archivo y recarga esta página para verificar la configuración.</li>
        </ol>

        <?php if ($isConfigValid): ?>
            <h2>🧪 Enviar Mensaje de Prueba</h2>
            <p>La configuración es correcta. Ahora puedes enviar un mensaje para probar la conexión.</p>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['numero'])) {
                echo "<div class='status warning'>Enviando mensaje...</div>";
                try {
                    require_once 'WhatsAppBusinessAPI.php';
                    $whatsapp = new WhatsAppBusinessAPI();
                    $resultado = $whatsapp->enviarMensajeTexto(
                        $_POST['numero'],
                        "¡Hola! 👋 Este es un mensaje de prueba desde el sistema de préstamos. Si lo recibiste, la API de WhatsApp está funcionando correctamente. ✅"
                    );
                    
                    if ($resultado) {
                        echo "<div class='status success'>¡Éxito! Mensaje enviado correctamente al número +505" . htmlspecialchars($_POST['numero']) . ".</div>";
                    } else {
                        echo "<div class='status error'>Error al enviar. Revisa el archivo de log de errores de tu servidor para más detalles.</div>";
                    }
                } catch (Exception $e) {
                    echo "<div class='status error'>❌ Error fatal al intentar enviar: " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <form method="POST">
                <div class="form-group">
                    <label for="numero">Número de WhatsApp de Nicaragua (8 dígitos):</label>
                    <input type="text" id="numero" name="numero" placeholder="Ej: 88887777" required pattern="\d{8}">
                </div>
                <button type="submit">Enviar Mensaje de Prueba</button>
            </form>
        <?php else: ?>
            <h2>🧪 Enviar Mensaje de Prueba</h2>
            <div class="status error">Completa la configuración (pasos 1-6) para poder enviar un mensaje de prueba.</div>
        <?php endif; ?>
    </div>
</body>
</html> 