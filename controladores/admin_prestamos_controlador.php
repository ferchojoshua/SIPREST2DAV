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
            $infoWhatsApp = AdminPrestamosModelo::mdlObtenerInfoParaWhatsApp($nro_prestamo, $pdetalle_nro_cuota);
            if ($infoWhatsApp) {
                // Para un pago completo, el monto pagado es el de la cuota.
                $infoWhatsApp['monto_pagado'] = $infoWhatsApp['pdetalle_monto_cuota'];
            }
            return array("status" => "ok", "whatsapp_data" => $infoWhatsApp);
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
            }
            return array("status" => "ok", "whatsapp_data" => $infoWhatsApp);
        } else {
            return array("status" => "error", "message" => $RegistrarAbono);
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
            $infoPrestamo = AdminPrestamosModelo::mdlObtenerInfoPrestamo($nro_prestamo);
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
