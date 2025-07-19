<?php
/**
 * Configuración para el envío de correos electrónicos utilizando PHPMailer.
 *
 * --- INSTRUCCIONES DE CONFIGURACIÓN ---
 * 1.  Completa los siguientes datos con la información de tu proveedor de correo (Gmail, Outlook, Hostinger, etc.).
 * 2.  Si usas Gmail, es posible que necesites crear una "Contraseña de aplicación" en la configuración de seguridad de tu cuenta de Google.
 * 
 * Este archivo contiene información sensible. Asegúrate de que no sea accesible públicamente.
 */

// -----------------------------------------------------------------------------
// --- CONFIGURACIÓN SMTP REQUERIDA ---
// -----------------------------------------------------------------------------

/**
 * @var bool Activa o desactiva globalmente el envío de correos.
 * Cambia a `true` cuando la configuración esté lista.
 */
define('EMAIL_ACTIVO', true);

/**
 * @var string El servidor SMTP de tu proveedor de correo.
 * Ejemplos: 'smtp.gmail.com', 'smtp.office365.com', 'smtp.hostinger.com'
 */
define('SMTP_HOST', 'smtp.gmail.com');

/**
 * @var string Tu nombre de usuario para el servidor SMTP (generalmente tu correo completo).
 * Ejemplo: 'tu_correo@gmail.com'
 */
define('SMTP_USERNAME', 'siprestaitsolutions@gmail.com');

/**
 * @var string Tu contraseña para el servidor SMTP.
 * Si usas Gmail, debe ser una "Contraseña de aplicación".
 */
define('SMTP_PASSWORD', 'vnuk vlrs tiog srsc');

/**
 * @var string El tipo de encriptación.
 * Opciones: 'tls' o 'ssl'. 'tls' es más común.
 */
define('SMTP_SECURE', 'ssl');

/**
 * @var int El puerto del servidor SMTP.
 * Puertos comunes: 587 (para TLS) o 465 (para SSL).
 */
define('SMTP_PORT', 465);



/**
 * @var string La dirección de correo desde la que se enviarán los mensajes.
 */
define('EMAIL_FROM_ADDRESS', 'siprestaitsolutions@gmail.com');

/**
 * @var string El nombre del remitente que aparecerá en el correo.
 */
define('EMAIL_FROM_NAME', 'SIPREST - Sistema de Préstamos');

?> 