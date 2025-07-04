<?php


class AdminPrestamosControlador
{

    /*===================================================================*/
    //LISTAR PRESTAMOS POR ID DEL USUARIO
    /*===================================================================*/
    static public function ctrListarPrestamoPorUsuario($id_usuario)
    {
        $listPrestamosporUsuario = AdminPrestamosModelo::mdlListarPrestamoPorUsuario($id_usuario);
        return $listPrestamosporUsuario;
    }


    /*===================================================================*/
    //VER DETALLE DEL PRESTAMO
    /*===================================================================*/
    static public function ctrDetallePrestamo($nro_prestamo)
    {
        $detallePrestamo =  AdminPrestamosModelo::mdlDetallePrestamo($nro_prestamo);
        return $detallePrestamo;
    }


    /*===================================================================*/
    //PAGAR CUOTA DEL PRESTAMOP
    /*===================================================================*/
    static public function ctrPagarCuota($nro_prestamo, $pdetalle_nro_cuota)
    {
        // Obtener información antes del pago para WhatsApp
        $infoWhatsApp = AdminPrestamosModelo::mdlObtenerInfoWhatsApp($nro_prestamo, $pdetalle_nro_cuota);
        
        // Procesar el pago
        $PagarCuota = AdminPrestamosModelo::mdlPagarCuota($nro_prestamo, $pdetalle_nro_cuota);
        
        // Si el pago fue exitoso y tenemos información completa, enviar WhatsApp
        if ($PagarCuota === "ok" && $infoWhatsApp && !empty($infoWhatsApp['cliente_celular'])) {
            self::enviarWhatsAppPagoCuota($infoWhatsApp);
        }
        
        return $PagarCuota;
    }

    /*===================================================================*/
    //ENVIAR WHATSAPP AL PAGAR CUOTA
    /*===================================================================*/
    private static function enviarWhatsAppPagoCuota($infoWhatsApp)
    {
        try {
            // Incluir la clase de WhatsApp
            require_once "../utilitarios/WhatsAppAPI.php";
            
            $whatsapp = new WhatsAppAPI();
            
            // Verificar si la configuración está completa
            if (!$whatsapp->validarConfiguracion()) {
                error_log("WhatsApp: Configuración incompleta - revisar credenciales de Twilio");
                return false;
            }
            
            // Calcular saldo restante del préstamo completo
            $saldo_restante = floatval($infoWhatsApp['saldo_total_prestamo']) ?: 0;
            
            // Enviar mensaje
            $resultado = $whatsapp->enviarMensajePagoCuota(
                $infoWhatsApp['cliente_celular'],
                $infoWhatsApp['cliente_nombres'],
                $infoWhatsApp['nro_prestamo'],
                $infoWhatsApp['pdetalle_nro_cuota'],
                floatval($infoWhatsApp['pdetalle_monto_cuota']),
                $saldo_restante,
                $infoWhatsApp['moneda_simbolo']
            );
            
            if ($resultado) {
                error_log("WhatsApp enviado exitosamente a " . $infoWhatsApp['cliente_nombres']);
            } else {
                error_log("Error al enviar WhatsApp a " . $infoWhatsApp['cliente_nombres']);
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en envío de WhatsApp: " . $e->getMessage());
            return false;
        }
    }


    /*===================================================================*/
    //OBTENER CUOTAS PAGADAS
    /*===================================================================*/
    static public function ctrObtenerCuotasPagadas($nro_prestamo)
    {
        $CuotasPagadas = AdminPrestamosModelo::mdlObtenerCuotasPagadas($nro_prestamo);
        return $CuotasPagadas;
    }



    /*===================================================================*/
    // LIQUIDAR PRESTAMO
    /*===================================================================*/
    static public function ctrLiquidarPrestamo($nro_prestamo, $pdetalle_nro_cuota)
    {
        $array_cuota =  explode(",", $pdetalle_nro_cuota);
        //  $array_monto =   explode(",", $pdetalle_monto_cuota);
        //  $array_fecha =   explode(",", $pdetalle_fecha);

        for ($i = 0; $i < count($array_cuota); $i++) {
            $LiquidarPrestamo = AdminPrestamosModelo::mdlLiquidarPrestamo($nro_prestamo, $array_cuota[$i]); //llamamos al metodo del modelo
        }

        return $LiquidarPrestamo;

    }

    /*===================================================================*/
    // REGISTRAR ABONO DE CUOTA
    /*===================================================================*/
    static public function ctrRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar)
    {
        // Obtener información antes del abono para WhatsApp
        $infoWhatsApp = AdminPrestamosModelo::mdlObtenerInfoWhatsApp($nro_prestamo, $pdetalle_nro_cuota);
        
        // Procesar el abono
        $RegistrarAbono = AdminPrestamosModelo::mdlRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar);
        
        // Si el abono fue exitoso y tenemos información completa, enviar WhatsApp
        if ($RegistrarAbono === "ok" && $infoWhatsApp && !empty($infoWhatsApp['cliente_celular'])) {
            // Modificar la información para reflejar el abono
            $infoWhatsApp['pdetalle_monto_cuota'] = $monto_a_abonar; // El monto abonado
            self::enviarWhatsAppAbono($infoWhatsApp);
        }
        
        return $RegistrarAbono;
    }

    /*===================================================================*/
    //ENVIAR WHATSAPP AL REGISTRAR ABONO
    /*===================================================================*/
    private static function enviarWhatsAppAbono($infoWhatsApp)
    {
        try {
            // Incluir la clase de WhatsApp
            require_once "../utilitarios/WhatsAppAPI.php";
            
            $whatsapp = new WhatsAppAPI();
            
            // Verificar si la configuración está completa
            if (!$whatsapp->validarConfiguracion()) {
                error_log("WhatsApp: Configuración incompleta - revisar credenciales de Twilio");
                return false;
            }
            
            // Calcular saldo restante del préstamo completo
            $saldo_restante = floatval($infoWhatsApp['saldo_total_prestamo']) ?: 0;
            
            // Crear mensaje específico para abono
            $mensaje = $whatsapp->crearMensajeAbono(
                $infoWhatsApp['cliente_nombres'],
                $infoWhatsApp['nro_prestamo'],
                $infoWhatsApp['pdetalle_nro_cuota'],
                floatval($infoWhatsApp['pdetalle_monto_cuota']), // Monto del abono
                $saldo_restante,
                $infoWhatsApp['moneda_simbolo']
            );
            
            // Formatear número y enviar
            $numero_formateado = $whatsapp->formatearNumeroTelefono($infoWhatsApp['cliente_celular']);
            $resultado = $whatsapp->enviarMensaje($numero_formateado, $mensaje);
            
            if ($resultado) {
                error_log("WhatsApp de abono enviado exitosamente a " . $infoWhatsApp['cliente_nombres']);
            } else {
                error_log("Error al enviar WhatsApp de abono a " . $infoWhatsApp['cliente_nombres']);
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en envío de WhatsApp de abono: " . $e->getMessage());
            return false;
        }
    }
}
