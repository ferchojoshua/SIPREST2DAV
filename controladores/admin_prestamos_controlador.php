<?php

require_once __DIR__ . '/../modelos/admin_prestamos_modelo.php';

class AdminPrestamosControlador
{

    /*===================================================================*/
    //LISTAR PRESTAMOS POR ID DEL USUARIO
    /*===================================================================*/
    static public function ctrListarPrestamoPorUsuario($id_usuario)
    {
        $listPrestamosporUsuario = AdminPrestamosModelo::
        mdlListarPrestamoPorUsuario($id_usuario);
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
        $pagoExitoso = AdminPrestamosModelo::mdlPagarCuota($nro_prestamo, $pdetalle_nro_cuota);

        if ($pagoExitoso == "ok") {
            // Intentar enviar notificación por WhatsApp
            $infoWhatsApp = AdminPrestamosModelo::mdlObtenerInfoParaWhatsApp($nro_prestamo, $pdetalle_nro_cuota);
            
            if ($infoWhatsApp) {
                try {
                    // Usar la nueva API de WhatsApp Business
                    require_once __DIR__ . '/../utilitarios/whatsapp_business_config.php';
                    $config = validarConfigWhatsAppBusiness();
                    
                    if ($config['valido']) {
                        require_once __DIR__ . '/../utilitarios/WhatsAppBusinessAPI.php';
                        $whatsapp = new WhatsAppBusinessAPI();
                        
                        $mensaje = $whatsapp->crearMensajePago(
                            $infoWhatsApp['cliente_nombres'],
                            $nro_prestamo,
                            $pdetalle_nro_cuota,
                            $infoWhatsApp['pdetalle_monto_cuota'],
                            $infoWhatsApp['pres_monto_restante'],
                            $infoWhatsApp['moneda_simbolo']
                        );
                        
                        $whatsapp->enviarMensajeTexto($infoWhatsApp['cliente_celular'], $mensaje);
                    } else {
                        // Opcional: registrar que la configuración no es válida si se desea
                        error_log("WhatsApp Business API no configurado o inactivo: " . $config['mensaje']);
                    }
                } catch (Exception $e) {
                    error_log("Error al procesar envío de WhatsApp: " . $e->getMessage());
                }
            }

            return array("status" => "ok");
        } else {
            return array("status" => "error", "message" => $pagoExitoso);
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
    // REGISTRAR ABONO DE CUOTA (NORMAL O EXTRAORDINARIO)
    /*===================================================================*/
    static public function ctrRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar, $tipo_abono = 'normal')
    {
        // Procesar el abono según el tipo
        if ($tipo_abono === 'extraordinario') {
            $RegistrarAbono = AdminPrestamosModelo::mdlRegistrarAbonoExtraordinario($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar);
            return $RegistrarAbono; // Se mantiene la lógica original para este caso complejo.
        } else {
            $RegistrarAbono = AdminPrestamosModelo::mdlRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar);
        }
        
        if ($RegistrarAbono === "ok") {
            $infoWhatsApp = AdminPrestamosModelo::mdlObtenerInfoParaWhatsApp($nro_prestamo, $pdetalle_nro_cuota);
            if ($infoWhatsApp) {
                // Para un abono, el monto pagado es el que se envía como parámetro.
                $infoWhatsApp['monto_pagado'] = $monto_a_abonar;
                
                // Generar mensaje de WhatsApp para abono solo si hay datos completos
                try {
                    // Verificar qué API de WhatsApp usar (Business API o Twilio)
                    if (file_exists(__DIR__ . '/../utilitarios/whatsapp_business_config.php')) {
                        require_once __DIR__ . '/../utilitarios/whatsapp_business_config.php';
                        $config_business = validarConfigWhatsAppBusiness();
                        
                        if ($config_business['valido']) {
                            // Usar WhatsApp Business API (oficial)
                            require_once __DIR__ . '/../utilitarios/WhatsAppBusinessAPI.php';
                            $whatsapp = new WhatsAppBusinessAPI();
                            $mensaje = $whatsapp->crearMensajeAbono(
                                $infoWhatsApp['cliente_nombres'],
                                $nro_prestamo,
                                $pdetalle_nro_cuota,
                                $monto_a_abonar,
                                $infoWhatsApp['pres_monto_restante'],
                                $infoWhatsApp['moneda_simbolo']
                            );
                            
                            // Enviar usando API oficial
                            $enviado = $whatsapp->enviarMensajeTexto(
                                $infoWhatsApp['cliente_celular'],
                                $mensaje
                            );
                            
                            $infoWhatsApp['mensaje'] = $mensaje;
                            $infoWhatsApp['telefono'] = $infoWhatsApp['cliente_celular'];
                            $infoWhatsApp['api_usada'] = 'WhatsApp Business API';
                            $infoWhatsApp['enviado'] = $enviado;
                        } else {
                            error_log("WhatsApp Business API no configurado: " . $config_business['mensaje']);
                            $infoWhatsApp['error'] = "WhatsApp Business API no configurado";
                        }
                    } else {
                        // Fallback a Twilio si no existe configuración Business
                        require_once __DIR__ . '/../utilitarios/WhatsAppAPI.php';
                        $whatsapp = new WhatsAppAPI();
                        $mensaje = $whatsapp->crearMensajeAbono(
                            $infoWhatsApp['cliente_nombres'],
                            $nro_prestamo,
                            $pdetalle_nro_cuota,
                            $monto_a_abonar,
                            $infoWhatsApp['pres_monto_restante'],
                            $infoWhatsApp['moneda_simbolo']
                        );
                        
                        $infoWhatsApp['mensaje'] = $mensaje;
                        $infoWhatsApp['telefono'] = $infoWhatsApp['cliente_celular'];
                        $infoWhatsApp['api_usada'] = 'Twilio (fallback)';
                    }
                } catch (Exception $e) {
                    error_log("Error al generar mensaje WhatsApp: " . $e->getMessage());
                    $infoWhatsApp['error'] = $e->getMessage();
                }
            }
            return array("status" => "ok", "whatsapp_data" => $infoWhatsApp);
        } else {
            // Convertir códigos de error a mensajes amigables
            $mensajes_error = [
                "error_cuota_no_encontrada" => "No se encontró la cuota especificada. Verifique el número de préstamo y cuota.",
                "error_prestamo_no_existe" => "El préstamo especificado no existe en el sistema.",
                "error_no_cuotas_pendientes" => "No hay cuotas pendientes para este préstamo."
            ];
            
            $mensaje = isset($mensajes_error[$RegistrarAbono]) ? $mensajes_error[$RegistrarAbono] : $RegistrarAbono;
            return array("status" => "error", "message" => $mensaje);
        }
    }

    /*===================================================================*/
    // REIMPRIMIR CONTRATO POR ADMINISTRADOR
    /*===================================================================*/
    static public function ctrReimprimirContratoAdmin($id_prestamo){
        // 1. Obtener el estado actual del contrato
        $estadoContrato = AdminPrestamosModelo::mdlObtenerEstadoReimpresion($id_prestamo);

        if ($estadoContrato === false) {
            return array("status" => "error", "message" => "Error al verificar el estado del contrato.");
        }

        if ($estadoContrato['reimpreso_admin'] == 1) {
            return array("status" => "error", "message" => "Este contrato ya ha sido reimpreso previamente.");
        }

        // 2. Actualizar la bandera de reimpresión
        $actualizarReimpresion = AdminPrestamosModelo::mdlActualizarReimpresionAdmin($id_prestamo);

        if ($actualizarReimpresion == "ok") {
            return "ok"; // Para mantener compatibilidad con el JavaScript
        } else {
            return "error";
        }
    }

    /*===================================================================*/
    // ENVIAR TABLA DE PAGOS POR CORREO
    /*===================================================================*/
    static public function ctrEnviarTablaCorreo($nro_prestamo, $cliente_nombres) {
        try {
            // Obtener información del préstamo y cliente
            $infoPrestamo = AdminPrestamosModelo::  mdlObtenerInfoPrestamo($nro_prestamo);
            if (!$infoPrestamo) {
                return array("status" => "error", "message" => "No se encontró información del préstamo.");
            }

            // Obtener el historial de pagos
            $historialPagos = AdminPrestamosModelo::mdlDetallePrestamo($nro_prestamo);
            if (!$historialPagos) {
                return array("status" => "error", "message" => "No se encontró historial de pagos.");
            }

            // Llamar al modelo para enviar correo
            $resultadoEnvio = AdminPrestamosModelo::mdlEnviarTablaCorreo($infoPrestamo, $historialPagos, $cliente_nombres);
            
            if ($resultadoEnvio === "ok") {
                return array("status" => "ok", "message" => "Tabla de pagos enviada correctamente.");
            } else {
                return array("status" => "error", "message" => $resultadoEnvio);
            }
        } catch (Exception $e) {
            return array("status" => "error", "message" => "Error al enviar correo: " . $e->getMessage());
        }
    }
}
