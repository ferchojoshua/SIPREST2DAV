<?php

/**
 * Clase para envío de mensajes por WhatsApp usando Twilio API
 */
class WhatsAppAPI
{
    private $account_sid;
    private $auth_token;
    private $whatsapp_number;

    public function __construct()
    {
        // Cargar configuración desde archivo de configuración
        require_once __DIR__ . '/whatsapp_config.php';
        
        $config = obtenerConfigWhatsApp();
        $this->account_sid = $config['account_sid'];
        $this->auth_token = $config['auth_token'];
        $this->whatsapp_number = $config['whatsapp_number'];
    }

    /**
     * Enviar mensaje de confirmación de pago de cuota
     * @param string $cliente_telefono - Número de teléfono del cliente
     * @param string $cliente_nombre - Nombre del cliente
     * @param string $nro_prestamo - Número del préstamo
     * @param int $nro_cuota - Número de la cuota pagada
     * @param float $monto_pagado - Monto que se pagó
     * @param float $saldo_restante - Saldo restante del préstamo
     * @param string $moneda_simbolo - Símbolo de la moneda
     * @return boolean - True si se envió correctamente, False si hubo error
     */
    public function enviarMensajePagoCuota($cliente_telefono, $cliente_nombre, $nro_prestamo, $nro_cuota, $monto_pagado, $saldo_restante, $moneda_simbolo)
    {
        try {
            // Formatear el número de teléfono
            $numero_formateado = $this->formatearNumeroTelefono($cliente_telefono);
            
            // Crear el mensaje
            $mensaje = $this->crearMensajePago($cliente_nombre, $nro_prestamo, $nro_cuota, $monto_pagado, $saldo_restante, $moneda_simbolo);
            
            // Enviar mensaje usando cURL
            return $this->enviarMensaje($numero_formateado, $mensaje);
            
        } catch (Exception $e) {
            error_log("Error al enviar WhatsApp: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crear el mensaje de confirmación de pago
     */
    public function crearMensajePago($cliente_nombre, $nro_prestamo, $nro_cuota, $monto_pagado, $saldo_restante, $moneda_simbolo)
    {
        $fecha_actual = date('d/m/Y H:i');
        
        $mensaje = "🎉 *PAGO CONFIRMADO* 🎉\n\n";
        $mensaje .= "Estimado(a) *{$cliente_nombre}*,\n\n";
        $mensaje .= "Hemos recibido su pago correspondiente a:\n\n";
        $mensaje .= "📋 *Préstamo N°:* {$nro_prestamo}\n";
        $mensaje .= "📅 *Cuota N°:* {$nro_cuota}\n";
        $mensaje .= "💰 *Monto Pagado:* {$moneda_simbolo} " . number_format($monto_pagado, 2) . "\n";
        $mensaje .= "💳 *Saldo Restante:* {$moneda_simbolo} " . number_format($saldo_restante, 2) . "\n";
        $mensaje .= "🕐 *Fecha de Pago:* {$fecha_actual}\n\n";
        
        if ($saldo_restante <= 0) {
            $mensaje .= "🎊 ¡FELICITACIONES! Ha completado el pago de su préstamo.\n\n";
        } else {
            $mensaje .= "📌 *Recordatorio:* Su próxima cuota vence según el cronograma establecido.\n\n";
        }
        
        $mensaje .= "Gracias por confiar en nosotros. 🙏\n\n";
        $mensaje .= "_Este es un mensaje automático generado por nuestro sistema._";

        return $mensaje;
    }

    /**
     * Crear mensaje específico para abono parcial
     */
    public function crearMensajeAbono($cliente_nombre, $nro_prestamo, $nro_cuota, $monto_abonado, $saldo_restante, $moneda_simbolo)
    {
        $fecha_actual = date('d/m/Y H:i');
        
        $mensaje = "💰 *ABONO REGISTRADO* 💰\n\n";
        $mensaje .= "Estimado(a) *{$cliente_nombre}*,\n\n";
        $mensaje .= "Hemos recibido su abono parcial:\n\n";
        $mensaje .= "📋 *Préstamo N°:* {$nro_prestamo}\n";
        $mensaje .= "📅 *Cuota N°:* {$nro_cuota}\n";
        $mensaje .= "💸 *Monto Abonado:* {$moneda_simbolo} " . number_format($monto_abonado, 2) . "\n";
        $mensaje .= "💳 *Saldo Restante:* {$moneda_simbolo} " . number_format($saldo_restante, 2) . "\n";
        $mensaje .= "🕐 *Fecha de Abono:* {$fecha_actual}\n\n";
        
        if ($saldo_restante <= 0) {
            $mensaje .= "🎊 ¡FELICITACIONES! Ha completado el pago de su préstamo.\n\n";
        } else {
            $mensaje .= "ℹ️ *Nota:* Este es un abono parcial. Puede continuar abonando cuando guste.\n\n";
        }
        
        $mensaje .= "Gracias por su pago. 🙏\n\n";
        $mensaje .= "_Este es un mensaje automático generado por nuestro sistema._";

        return $mensaje;
    }

    /**
     * Formatear número de teléfono para WhatsApp
     */
    public function formatearNumeroTelefono($telefono)
    {
        // Remover espacios y caracteres especiales
        $telefono = preg_replace('/[^0-9+]/', '', $telefono);
        
        // Si no empieza con +, agregar código de país desde configuración
        if (!str_starts_with($telefono, '+')) {
            require_once __DIR__ . '/whatsapp_config.php';
            $config = obtenerConfigWhatsApp();
            $telefono = $config['codigo_pais'] . $telefono;
        }
        
        return 'whatsapp:' . $telefono;
    }

    /**
     * Enviar mensaje usando la API de Twilio
     */
    public function enviarMensaje($numero_destino, $mensaje)
    {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$this->account_sid}/Messages.json";
        
        $data = array(
            'From' => $this->whatsapp_number,
            'To' => $numero_destino,
            'Body' => $mensaje
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPAUTH => CURL_HTTPAUTH_BASIC,
            CURLOPT_USERPWD => $this->account_sid . ':' . $this->auth_token,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            ),
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_TIMEOUT => 30
        ));

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            error_log("Error cURL WhatsApp: " . $error);
            return false;
        }

        if ($http_code >= 200 && $http_code < 300) {
            $response_data = json_decode($response, true);
            if (isset($response_data['sid'])) {
                error_log("WhatsApp enviado exitosamente. SID: " . $response_data['sid']);
                return true;
            }
        } else {
            error_log("Error HTTP WhatsApp: " . $http_code . " - Response: " . $response);
        }

        return false;
    }

    /**
     * Configurar credenciales de Twilio
     */
    public function configurarCredenciales($account_sid, $auth_token, $whatsapp_number)
    {
        $this->account_sid = $account_sid;
        $this->auth_token = $auth_token;
        $this->whatsapp_number = $whatsapp_number;
    }

    /**
     * Validar configuración
     */
    public function validarConfiguracion()
    {
        return !empty($this->account_sid) && 
               !empty($this->auth_token) && 
               !empty($this->whatsapp_number) &&
               $this->account_sid !== 'ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
    }
}

?> 