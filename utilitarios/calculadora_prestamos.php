<?php

class CalculadoraPrestamos {
    
    /**
     * Calcula la amortización según el sistema seleccionado
     */
    public static function calcularAmortizacion($principal, $tasaAnual, $plazo, $sistema, $fechaInicio, $formaPago = '4') {
        
        // Para que coincida con el simulador de referencia, la tasa de interés (ej: 4%) se trata como
        // la tasa efectiva para el período de pago (ej: 4% mensual) para TODOS los sistemas.
        $tasaPeriodo = $tasaAnual / 100;
        
        // Calcular cuota según el sistema seleccionado
        switch($sistema) {
            case 'FRANCES':
                return self::calcularSistemaFrances($principal, $tasaPeriodo, $plazo, $fechaInicio);
            case 'ALEMAN':
                return self::calcularSistemaAleman($principal, $tasaPeriodo, $plazo, $fechaInicio);
            case 'AMERICANO':
                return self::calcularSistemaAmericano($principal, $tasaPeriodo, $plazo, $fechaInicio);
            case 'SIMPLE':
                return self::calcularSistemaSimple($principal, $tasaPeriodo, $plazo, $fechaInicio);
            case 'COMPUESTO':
                return self::calcularSistemaCompuesto($principal, $tasaPeriodo, $plazo, $fechaInicio);
            default:
                throw new Exception("Sistema de amortización no válido");
        }
    }

    /**
     * Calcula la tasa por período según la forma de pago
     */
    private static function calcularTasaPeriodo($tasaAnual, $formaPago) {
        $tasaDecimal = $tasaAnual / 100;
        
        switch ($formaPago) {
            case '1': // DIARIO
                return $tasaDecimal / 365;
            case '2': // SEMANAL
                return $tasaDecimal / 52;
            case '3': // QUINCENAL
                return $tasaDecimal / 24;
            case '4': // MENSUAL
                return $tasaDecimal / 12;
            case '5': // BIMESTRAL
                return $tasaDecimal / 6;
            case '6': // SEMESTRAL
                return $tasaDecimal / 2;
            case '7': // ANUAL
                return $tasaDecimal;
            default:
                return $tasaDecimal / 12; // Por defecto mensual
        }
    }

    /**
     * Sistema Francés: Cuota fija, amortización creciente, interés decreciente
     */
    private static function calcularSistemaFrances($principal, $tasaPeriodo, $plazo, $fechaInicio) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaInicio);
        
        if (!$fecha) {
            $fecha = new DateTime($fechaInicio);
        }

        // Calcular cuota fija
        if ($tasaPeriodo == 0) {
            $cuotaFija = $principal / $plazo;
        } else {
            $cuotaFija = $principal * ($tasaPeriodo * pow(1 + $tasaPeriodo, $plazo)) / (pow(1 + $tasaPeriodo, $plazo) - 1);
        }
        
        for ($i = 1; $i <= $plazo; $i++) {
            // Calcular interés del período
            $interesCuota = $saldoRestante * $tasaPeriodo;
            
            // La amortización es la diferencia entre la cuota y el interés
            $amortizacion = $cuotaFija - $interesCuota;
            
            // Actualizar saldo
            $saldoRestante -= $amortizacion;
            
            // Ajustar el saldo final para evitar decimales
            if ($i == $plazo) {
                $amortizacion += $saldoRestante;
                $saldoRestante = 0;
            }
            
            $totalIntereses += $interesCuota;
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fecha->format('d/m/Y'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacion, 2),
                'interes' => round($interesCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
            
            $fecha->modify('+1 month');
        }
        
        return [
            'cuota_inicial' => round($cuotaFija, 2),
            'total_pagar' => round($principal + $totalIntereses, 2),
            'total_intereses' => round($totalIntereses, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }

    /**
     * Sistema Alemán: Amortización fija, interés decreciente
     */
    private static function calcularSistemaAleman($principal, $tasaPeriodo, $plazo, $fechaInicio) {
        $amortizacionFija = $principal / $plazo;
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaInicio);
        
        if (!$fecha) {
            $fecha = new DateTime($fechaInicio);
        }
        
        for ($i = 1; $i <= $plazo; $i++) {
            // El interés se calcula sobre el saldo restante usando la tasa del período
            $interesCuota = $saldoRestante * $tasaPeriodo;
            
            // La cuota es la suma de la amortización fija más el interés del período
            $cuota = $amortizacionFija + $interesCuota;
            
            // Actualizar saldo restante
            $saldoRestante -= $amortizacionFija;
            
            // Ajustar el saldo final para evitar decimales
            if ($i == $plazo) {
                $saldoRestante = 0;
            }
            
            $totalIntereses += $interesCuota;
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fecha->format('d/m/Y'),
                'monto' => round($cuota, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
            
            $fecha->modify('+1 month');
        }
        
        return [
            'cuota_inicial' => round($amortizacionFija + ($principal * $tasaPeriodo), 2),
            'total_pagar' => round($principal + $totalIntereses, 2),
            'total_intereses' => round($totalIntereses, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }

    /**
     * Sistema Americano: Solo intereses y capital al final
     */
    private static function calcularSistemaAmericano($principal, $tasaPeriodo, $plazo, $fechaInicio) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaInicio);
        
        if (!$fecha) {
            $fecha = new DateTime($fechaInicio);
        }
        
        $interesFijo = $principal * $tasaPeriodo;
        
        for ($i = 1; $i <= $plazo; $i++) {
            $amortizacion = ($i == $plazo) ? $principal : 0;
            $cuota = ($i == $plazo) ? $interesFijo + $principal : $interesFijo;
            
            $totalIntereses += $interesFijo;
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fecha->format('d/m/Y'),
                'monto' => round($cuota, 2),
                'capital' => round($amortizacion, 2),
                'interes' => round($interesFijo, 2),
                'saldo' => round($i == $plazo ? 0 : $saldoRestante, 2)
            ];
            
            $fecha->modify('+1 month');
        }
        
        return [
            'cuota_inicial' => round($interesFijo, 2),
            'total_pagar' => round($principal + $totalIntereses, 2),
            'total_intereses' => round($totalIntereses, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }

    /**
     * Sistema Simple: Interés total distribuido uniformemente
     */
    private static function calcularSistemaSimple($principal, $tasaPeriodo, $plazo, $fechaInicio) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaInicio);
        
        if (!$fecha) {
            $fecha = new DateTime($fechaInicio);
        }
        
        // Calcular interés total
        $interesTotal = $principal * $tasaPeriodo * $plazo;
        
        // Distribuir uniformemente
        $amortizacionFija = $principal / $plazo;
        $interesFijo = $interesTotal / $plazo;
        $cuotaFija = $amortizacionFija + $interesFijo;
        
        for ($i = 1; $i <= $plazo; $i++) {
            $saldoRestante -= $amortizacionFija;
            
            if ($i == $plazo) {
                $saldoRestante = 0;
            }
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fecha->format('d/m/Y'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesFijo, 2),
                'saldo' => round($saldoRestante, 2)
            ];
            
            $fecha->modify('+1 month');
        }
        
        return [
            'cuota_inicial' => round($cuotaFija, 2),
            'total_pagar' => round($principal + $interesTotal, 2),
            'total_intereses' => round($interesTotal, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }

    /**
     * Sistema Compuesto: Interés sobre interés
     */
    private static function calcularSistemaCompuesto($principal, $tasaPeriodo, $plazo, $fechaInicio) {
        $tablaAmortizacion = [];
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaInicio);
        
        if (!$fecha) {
            $fecha = new DateTime($fechaInicio);
        }
        
        // Calcular monto final con interés compuesto
        $montoFinal = $principal * pow(1 + $tasaPeriodo, $plazo);
        $interesTotal = $montoFinal - $principal;
        
        // Distribuir el capital y el interés
        $amortizacionFija = $principal / $plazo;
        $interesPorCuota = $interesTotal / $plazo;
        $cuotaFija = $amortizacionFija + $interesPorCuota;
        
        $saldoRestante = $principal;
        
        for ($i = 1; $i <= $plazo; $i++) {
            $saldoRestante -= $amortizacionFija;
            
            if ($i == $plazo) {
                $saldoRestante = 0;
            }
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fecha->format('d/m/Y'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesPorCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
            
            $fecha->modify('+1 month');
        }
        
        return [
            'cuota_inicial' => round($cuotaFija, 2),
            'total_pagar' => round($montoFinal, 2),
            'total_intereses' => round($interesTotal, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }
}
?> 