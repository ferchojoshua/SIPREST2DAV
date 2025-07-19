<?php

/**
 * Clase para envío de mensajes por WhatsApp usando la API oficial de WhatsApp Business Cloud.
 * Esta implementación se enfoca únicamente en el envío de mensajes de texto (notificaciones).
 * No depende de Twilio ni de otras plataformas.
 */
class WhatsAppBusinessAPI
{
    private $accessToken;
    private $phoneNumberId;
    private $apiVersion;

    /**
     * Carga la configuración necesaria desde un archivo externo.
     */
    public function __construct()
    {
        // Cargar archivo de configuración
        require_once __DIR__ . '/whatsapp_business_config.php';
        
        $config = obtenerConfigWhatsAppBusiness();
        $this->accessToken = $config['access_token'];
        $this->phoneNumberId = $config['phone_number_id'];
        $this->apiVersion = $config['api_version'] ?? 'v18.0'; // Usa v18.0 o una versión más reciente
    }

    /**
     * Envía una notificación de texto a un número de WhatsApp.
     *
     * @param string $numeroDestino Número de teléfono del cliente (sin el código de país).
     * @param string $mensaje El texto del mensaje que se enviará.
     * @return bool True si el mensaje se envió con éxito, False en caso de error.
     */
    public function enviarMensajeTexto($numeroDestino, $mensaje)
    {
        try {
            // Formatea el número para que incluya el código de país de Nicaragua
            $numeroFormateado = $this->formatearNumero($numeroDestino);
            
            // Estructura de datos para la API de WhatsApp
            $data = [
                'messaging_product' => 'whatsapp',
                'to' => $numeroFormateado,
                'type' => 'text',
                'text' => [
                    'preview_url' => false, // No mostrar vistas previas de enlaces
                    'body' => $mensaje
                ]
            ];

            // Envía la petición a la API y devuelve el resultado
            return $this->enviarPeticionAPI($data);
            
        } catch (Exception $e) {
            error_log("Error al enviar mensaje de WhatsApp: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Prepara el mensaje de confirmación de pago de una cuota.
     *
     * @return string El mensaje formateado listo para enviar.
     */
    public function crearMensajePago($clienteNombre, $nroPrestamo, $nroCuota, $montoPagado, $saldoRestante, $monedaSimbolo)
    {
        $fechaActual = date('d/m/Y H:i A');
        
        $mensaje  = "🎉 *PAGO CONFIRMADO* 🎉\n\n";
        $mensaje .= "Estimado(a) *{$clienteNombre}*,\n\n";
        $mensaje .= "Hemos recibido su pago correspondiente a:\n\n";
        $mensaje .= "📋 *Préstamo:* {$nroPrestamo}\n";
        $mensaje .= "🔢 *Cuota N°:* {$nroCuota}\n";
        $mensaje .= "💰 *Monto Pagado:* {$monedaSimbolo} " . number_format($montoPagado, 2) . "\n";
        $mensaje .= "💳 *Saldo Actual:* {$monedaSimbolo} " . number_format($saldoRestante, 2) . "\n\n";
        $mensaje .= "🗓️ *Fecha de Pago:* {$fechaActual}\n\n";
        
        if ($saldoRestante <= 0) {
            $mensaje .= "🎊 *¡FELICITACIONES!* Ha completado el pago de su préstamo.\n\n";
        } else {
            $mensaje .= "📌 *Recordatorio:* Su próxima cuota vence según el cronograma.\n\n";
        }
        
        $mensaje .= "Gracias por su confianza. 🙏\n";
        $mensaje .= "_Este es un mensaje automático._";

        return $mensaje;
    }

    /**
     * Formatea un número de teléfono para el estándar internacional (E.164)
     * requerido por la API, usando el código de Nicaragua (+505).
     *
     * @param string $telefono Número de 8 dígitos.
     * @return string Número formateado (ej: 50588887777).
     */
    private function formatearNumero($telefono)
    {
        // Eliminar caracteres no numéricos
        $telefonoLimpio = preg_replace('/[^0-9]/', '', $telefono);
        
        // Si el número ya tiene el código de país, usarlo. Si no, agregarlo.
        if (strlen($telefonoLimpio) > 8 && strpos($telefonoLimpio, '505') === 0) {
            return $telefonoLimpio;
        } else {
            return '505' . $telefonoLimpio;
        }
    }

    /**
     * Realiza la llamada a la API de WhatsApp usando cURL.
     *
     * @param array $data Los datos del mensaje a enviar.
     * @return bool Resultado del envío.
     */
    private function enviarPeticionAPI($data)
    {
        $url = "https://graph.facebook.com/{$this->apiVersion}/{$this->phoneNumberId}/messages";
        
        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json'
        ];

        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false, // Cambiar a true en producción si el certificado es válido
            CURLOPT_TIMEOUT => 30
        ]);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            error_log("Error cURL en WhatsApp Business API: " . $error);
            return false;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            $responseData = json_decode($response, true);
            if (isset($responseData['messages'][0]['id'])) {
                error_log("Mensaje de WhatsApp enviado. ID: " . $responseData['messages'][0]['id']);
                return true;
            }
        } else {
            error_log("Error HTTP en WhatsApp Business API: Código {$httpCode} - Respuesta: " . $response);
        }

        return false;
    }
}
?> 