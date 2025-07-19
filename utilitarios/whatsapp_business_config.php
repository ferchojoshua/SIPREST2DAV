<?php

/**
 * Configuración para la API de WhatsApp Business Cloud.
 *
 * --- INSTRUCCIONES DE CONFIGURACIÓN RÁPIDA ---
 * 1.  Ve a Facebook Developers: https://developers.facebook.com/apps/
 * 2.  Crea una App de tipo "Business" y agrégale el producto "WhatsApp".
 * 3.  En el panel de WhatsApp, ve a la sección "Introducción a la API".
 * 4.  Copia los siguientes valores y pégalos aquí:
 *     - Token de acceso temporal (dura 24h, ideal para pruebas).
 *     - ID del número de teléfono.
 * 5.  ¡IMPORTANTE! Para un token permanente, debes crear un "Usuario del sistema".
 * 
 * Este archivo contiene información sensible. Asegúrate de que no sea accesible públicamente.
 */

// -----------------------------------------------------------------------------
// --- CONFIGURACIÓN REQUERIDA ---
// -----------------------------------------------------------------------------

/**
 * @var string Tu token de acceso. Obtenido desde el panel de tu App en Facebook Developers.
 * Ejemplo: 'EAA...Z'
 */
define('WHATSAPP_ACCESS_TOKEN', 'EAAc75KbGfMwBPNy3Oku0KdeT3i1ZAoIW4iHViXP0ZBmgrs9LZCkUx0GoEmQYa1omzAZBqzKNwYQw9nbmAVVifQzmHAkH15g5YkqN68SnvquQKBmA0bGNJFZB9n6mYoZAHfJLcZCt3Wgngq9yALYZBcUU5jImntmBjkqZBxinFS052ZBwO9hqQCTliTxgCxTIZCHwmj3aAZDZD');

/**
 * @var string El ID de tu número de teléfono de WhatsApp.
 * También se encuentra en la sección "Introducción a la API".
 */
define('WHATSAPP_PHONE_NUMBER_ID', '+15551887562');

/**
 * @var bool Activa o desactiva globalmente el envío de mensajes.
 * Cambia a `true` cuando estés listo para enviar mensajes reales.
 */
define('WHATSAPP_BUSINESS_ACTIVO', false);

// -----------------------------------------------------------------------------
// --- CONFIGURACIÓN OPCIONAL ---
// -----------------------------------------------------------------------------

/**
 * @var string La versión de la API de Graph que deseas usar.
 * Se recomienda usar una versión reciente.
 */
define('WHATSAPP_API_VERSION', 'v18.0');

/**
 * @var string El código de país por defecto para formatear los números.
 * Para Nicaragua es '505'.
 */
define('WHATSAPP_CODIGO_PAIS', '505');

// -----------------------------------------------------------------------------
// --- FUNCIONES AUXILIARES (No modificar) ---
// -----------------------------------------------------------------------------

/**
 * Devuelve la configuración en un array para ser usada por la clase de la API.
 * @return array
 */
function obtenerConfigWhatsAppBusiness()
{
    return [
        'access_token'      => WHATSAPP_ACCESS_TOKEN,
        'phone_number_id'   => WHATSAPP_PHONE_NUMBER_ID,
        'activo'            => WHATSAPP_BUSINESS_ACTIVO,
        'api_version'       => WHATSAPP_API_VERSION,
        'codigo_pais'       => WHATSAPP_CODIGO_PAIS
    ];
}

/**
 * Valida que la configuración básica esté completa antes de intentar enviar mensajes.
 * @return array Un array con el estado de validación y un mensaje.
 */
function validar_configuracion_whatsapp() {
    // Comprueba si las constantes están definidas
    if (!defined('WHATSAPP_ACCESS_TOKEN') || !defined('WHATSAPP_PHONE_NUMBER_ID')) {
        return ['valido' => false, 'mensaje' => 'Las constantes de configuración de WhatsApp no están definidas.'];
    }

    // Comprueba si los valores son los de por defecto o están vacíos.
    if (WHATSAPP_ACCESS_TOKEN === 'TU_TOKEN_DE_ACCESO_AQUI' || empty(WHATSAPP_ACCESS_TOKEN)) {
        return ['valido' => false, 'mensaje' => 'El Token de Acceso (WHATSAPP_ACCESS_TOKEN) no ha sido configurado.'];
    }
    
    if (WHATSAPP_PHONE_NUMBER_ID === 'EL_ID_DE_TU_NUMERO_DE_TELEFONO_AQUI' || empty(WHATSAPP_PHONE_NUMBER_ID)) {
        return ['valido' => false, 'mensaje' => 'El ID del Número de Teléfono (WHATSAPP_PHONE_NUMBER_ID) no ha sido configurado.'];
    }

    return ['valido' => true, 'mensaje' => 'La configuración de WhatsApp es correcta.'];
}

?> 