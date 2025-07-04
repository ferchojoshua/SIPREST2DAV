<?php

/**
 * Configuración de WhatsApp/Twilio
 * 
 * Para configurar el envío de mensajes por WhatsApp:
 * 1. Crear una cuenta en Twilio: https://www.twilio.com/
 * 2. Obtener el Account SID y Auth Token desde el Dashboard de Twilio
 * 3. Configurar WhatsApp Sandbox o obtener un número de WhatsApp Business API
 * 4. Reemplazar los valores de ejemplo con tus credenciales reales
 */

// Configuración de Twilio
define('TWILIO_ACCOUNT_SID', 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'); // Tu Account SID de Twilio
define('TWILIO_AUTH_TOKEN', 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');  // Tu Auth Token de Twilio
define('TWILIO_WHATSAPP_NUMBER', 'whatsapp:+14155238886');        // Número de WhatsApp de Twilio

// Configuración del país para formateo de números
define('CODIGO_PAIS_DEFAULT', '+505'); // Código de país por defecto (Nicaragua: +505, Perú: +51, México: +52, Colombia: +57, etc.)

// Configuración de activación
define('WHATSAPP_ACTIVO', false); // Cambiar a true para activar el envío de mensajes

/**
 * INSTRUCCIONES DE CONFIGURACIÓN:
 * 
 * 1. SANDBOX DE TWILIO (Para pruebas):
 *    - Ve a https://console.twilio.com/us1/develop/sms/try-it-out/whatsapp-learn
 *    - Usa el número proporcionado: whatsapp:+14155238886
 *    - Los usuarios deben enviar un código específico para unirse al sandbox
 * 
 * 2. WHATSAPP BUSINESS API (Para producción):
 *    - Necesita aprobación de WhatsApp
 *    - Requiere un número de teléfono comercial verificado
 *    - Proceso más complejo pero sin limitaciones
 * 
 * 3. FORMATEO DE NÚMEROS NICARAGUA:
 *    - Los números nicaragüenses tienen 8 dígitos
 *    - Formato: +505[8 dígitos] (ej: +50587654321)
 *    - La función formatearNumeroTelefono() agrega automáticamente el código +505
 * 
 * 4. ACTIVACIÓN:
 *    - Cambiar WHATSAPP_ACTIVO a true una vez configurado
 *    - Esto permite enviar mensajes automáticamente
 *    - Si está en false, los mensajes se registran en el log pero no se envían
 */

// Función para obtener la configuración
function obtenerConfigWhatsApp()
{
    return [
        'account_sid' => TWILIO_ACCOUNT_SID,
        'auth_token' => TWILIO_AUTH_TOKEN,
        'whatsapp_number' => TWILIO_WHATSAPP_NUMBER,
        'codigo_pais' => CODIGO_PAIS_DEFAULT,
        'activo' => WHATSAPP_ACTIVO
    ];
}

// Función para validar la configuración
function validarConfigWhatsApp()
{
    $config = obtenerConfigWhatsApp();
    
    if (!$config['activo']) {
        return ['valido' => false, 'mensaje' => 'WhatsApp desactivado en configuración'];
    }
    
    if ($config['account_sid'] === 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') {
        return ['valido' => false, 'mensaje' => 'Account SID no configurado'];
    }
    
    if ($config['auth_token'] === 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx') {
        return ['valido' => false, 'mensaje' => 'Auth Token no configurado'];
    }
    
    if (empty($config['whatsapp_number'])) {
        return ['valido' => false, 'mensaje' => 'Número de WhatsApp no configurado'];
    }
    
    return ['valido' => true, 'mensaje' => 'Configuración correcta'];
}

?> 