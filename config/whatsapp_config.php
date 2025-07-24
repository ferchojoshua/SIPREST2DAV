<?php
// CONFIGURACIÓN WHATSAPP - CrediCrece
// Usando infraestructura existente del sistema

if (!defined('WHATSAPP_CONFIG_LOADED')) {
    define('WHATSAPP_CONFIG_LOADED', true);
    
    // Configuración Maytapi
    define('MAYTAPI_PRODUCT_ID', 'YOUR_PRODUCT_ID');
    define('MAYTAPI_API_TOKEN', 'YOUR_API_TOKEN');
    define('MAYTAPI_PHONE_ID', 'YOUR_PHONE_ID');
    define('MAYTAPI_BASE_URL', 'https://api.maytapi.com/api');
    
    // Webhook para recibir mensajes
    define('WEBHOOK_URL', $_SERVER['HTTP_HOST'] . '/ajax/whatsapp_webhook.php');
    
    // Configuración horarios
    define('WHATSAPP_HORA_INICIO', 8);  // 8 AM
    define('WHATSAPP_HORA_FIN', 18);    // 6 PM
    
    // Mensajes predefinidos
    $MENSAJES_WHATSAPP = [
        'prestamo_aprobado' => '¡Felicidades! Su préstamo ha sido aprobado. Monto: ${monto}. Cuotas: {cuotas}. Nos comunicaremos pronto.',
        'recordatorio_pago' => 'Estimado {cliente}, le recordamos que tiene una cuota pendiente por ${monto} con vencimiento {fecha}.',
        'cierre_caja' => 'Cierre de caja: {fecha} - Total: ${total}. Usuario: {usuario}.'
    ];
}
?> 