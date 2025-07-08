<?php
session_start();
require_once "../modelos/notas_debito_modelo.php";

class NotasDebitoControlador
{
    /*===================================================================*/
    // CREAR NOTA DE DÉBITO PARA RECÁLCULO DE PRÉSTAMO
    /*===================================================================*/
    static public function ctrCrearNotaDebito($nro_prestamo, $nuevo_interes, $nuevas_cuotas, $motivo, $id_usuario)
    {
        $resultado = AdminPrestamosModelo::mdlCrearNotaDebito($nro_prestamo, $nuevo_interes, $nuevas_cuotas, $motivo, $id_usuario);
        return $resultado;
    }

    /*===================================================================*/
    // OBTENER DATOS PARA NOTA DE DÉBITO PDF
    /*===================================================================*/
    static public function ctrObtenerDatosNotaDebito($nro_nota_debito)
    {
        $datos = AdminPrestamosModelo::mdlObtenerDatosNotaDebito($nro_nota_debito);
        return $datos;
    }

    /*===================================================================*/
    // LISTAR TODAS LAS NOTAS DE DÉBITO
    /*===================================================================*/
    static public function ctrListarNotasDebito()
    {
        return NotasDebitoModelo::mdlListarNotasDebito();
    }

    /*===================================================================*/
    // OBTENER INFORMACIÓN DEL PRÉSTAMO PARA RECÁLCULO
    /*===================================================================*/
    static public function ctrObtenerInfoPrestamo($nro_prestamo)
    {
        $infoPrestamo = AdminPrestamosModelo::mdlDetallePrestamo($nro_prestamo);
        return $infoPrestamo;
    }

    /*===================================================================*/
    // VALIDAR SI EL PRÉSTAMO PUEDE SER RECALCULADO
    /*===================================================================*/
    static public function ctrValidarPrestamoParaRecalculo($nro_prestamo)
    {
        try {
            // Obtener información del préstamo
            $prestamo = AdminPrestamosModelo::mdlDetallePrestamo($nro_prestamo);
            
            if (!$prestamo) {
                return [
                    'status' => 'error',
                    'message' => 'Préstamo no encontrado'
                ];
            }

            // Verificar si el préstamo está completamente pagado
            if ($prestamo[0]['pres_estado'] === 'finalizado' || $prestamo[0]['pres_aprobacion'] === 'finalizado') {
                return [
                    'status' => 'error',
                    'message' => 'El préstamo ya está finalizado y no puede ser recalculado'
                ];
            }

            // Verificar si tiene cuotas pagadas
            $cuotas_pagadas = intval($prestamo[0]['pres_cuotas_pagadas']);
            $total_cuotas = intval($prestamo[0]['pres_cuotas']);
            
            if ($cuotas_pagadas >= $total_cuotas) {
                return [
                    'status' => 'error',
                    'message' => 'Todas las cuotas han sido pagadas, no se puede recalcular'
                ];
            }

            // Calcular saldo pendiente
            $monto_total = floatval($prestamo[0]['pres_monto_total']);
            $monto_pagado = floatval($prestamo[0]['pres_cuotas_pagadas']) * floatval($prestamo[0]['pres_monto_cuota']);
            $saldo_pendiente = $monto_total - $monto_pagado;

            return [
                'status' => 'ok',
                'data' => [
                    'nro_prestamo' => $prestamo[0]['nro_prestamo'],
                    'cliente_nombres' => $prestamo[0]['cliente_nombres'],
                    'monto_original' => $prestamo[0]['pres_monto'],
                    'interes_actual' => $prestamo[0]['pres_interes'],
                    'cuotas_actuales' => $prestamo[0]['pres_cuotas'],
                    'cuota_actual' => $prestamo[0]['pres_monto_cuota'],
                    'cuotas_pagadas' => $cuotas_pagadas,
                    'cuotas_pendientes' => $total_cuotas - $cuotas_pagadas,
                    'saldo_pendiente' => $saldo_pendiente,
                    'moneda_simbolo' => $prestamo[0]['moneda_simbolo']
                ]
            ];

        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error al validar préstamo: ' . $e->getMessage()
            ];
        }
    }

    static public function ctrRegistrarNotaDebito($nro_prestamo, $nuevo_monto, $motivo) {
        return NotasDebitoModelo::mdlRegistrarNotaDebito($nro_prestamo, $nuevo_monto, $motivo);
    }

    static public function ctrAnularNotaDebito($nro_nota) {
        return NotasDebitoModelo::mdlAnularNotaDebito($nro_nota);
    }

    static public function ctrObtenerNotaDebito($nro_nota) {
        return NotasDebitoModelo::mdlObtenerNotaDebito($nro_nota);
    }
} 