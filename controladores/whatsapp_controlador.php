<?php

require_once "../config/whatsapp_config.php";
require_once "../modelos/whatsapp_modelo.php";

class WhatsAppControlador
{
    /*===================================================================*/
    // ENVIAR MENSAJE WHATSAPP
    /*===================================================================*/
    static public function ctrEnviarMensaje($telefono, $mensaje, $tipo = 'text')
    {
        // Verificar que el cliente no esté en opt-out
        if (WhatsAppModelo::mdlVerificarOptOut($telefono)) {
            return ['success' => false, 'message' => 'Cliente ha optado por no recibir mensajes'];
        }
        
        // Enviar mensaje
        $resultado = WhatsAppModelo::mdlEnviarMensaje($telefono, $mensaje, $tipo);
        
        // Registrar en BD
        $estado = $resultado['success'] ? 'enviado' : 'error';
        WhatsAppModelo::mdlRegistrarMensajeEnviado($telefono, $mensaje, $tipo, $estado);
        
        return $resultado;
    }
    
    /*===================================================================*/
    // NOTIFICAR PRÉSTAMO APROBADO
    /*===================================================================*/
    static public function ctrNotificarPrestamoAprobado($cliente_id, $monto, $cuotas)
    {
        global $MENSAJES_WHATSAPP;
        
        $cliente = WhatsAppModelo::mdlObtenerDatosCliente($cliente_id);
        
        if (!$cliente || empty($cliente['cliente_cel'])) {
            return ['success' => false, 'message' => 'Cliente sin teléfono registrado'];
        }
        
        $mensaje = str_replace(
            ['{monto}', '{cuotas}'],
            [$monto, $cuotas],
            $MENSAJES_WHATSAPP['prestamo_aprobado']
        );
        
        return self::ctrEnviarMensaje($cliente['cliente_cel'], $mensaje);
    }
    
    /*===================================================================*/
    // ENVIAR RECORDATORIO DE PAGO
    /*===================================================================*/
    static public function ctrEnviarRecordatorioPago($cliente_id, $monto, $fecha_vencimiento)
    {
        global $MENSAJES_WHATSAPP;
        
        $cliente = WhatsAppModelo::mdlObtenerDatosCliente($cliente_id);
        
        if (!$cliente || empty($cliente['cliente_cel'])) {
            return ['success' => false, 'message' => 'Cliente sin teléfono registrado'];
        }
        
        $mensaje = str_replace(
            ['{cliente}', '{monto}', '{fecha}'],
            [$cliente['cliente_nombres'], $monto, $fecha_vencimiento],
            $MENSAJES_WHATSAPP['recordatorio_pago']
        );
        
        return self::ctrEnviarMensaje($cliente['cliente_cel'], $mensaje);
    }
}
?> 