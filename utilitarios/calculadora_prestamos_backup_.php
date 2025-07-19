<?php

class CalculadoraPrestamos {
    
    /**
     * Calcula la amortización según el sistema seleccionado.
     * Esta función es ahora autónoma y no depende de la base de datos.
     *
     * @param float $principal El monto del préstamo.
     * @param float $tasaAnual La tasa de interés anual (ej: 4.5 para 4.5%).
     * @param int $plazo El número de cuotas.
     * @param string $sistema El sistema de amortización ('FRANCES', 'ALEMAN', etc.).
     * @param string $fechaInicio La fecha del primer pago en formato 'Y-m-d'.
     * @param string $formaPago El código de la forma de pago ('1' para Diario, '4' para Mensual, etc.).
     * @return array Un array con los detalles de la amortización.
     * @throws Exception Si el sistema de amortización no es válido.
     */
    public static function calcularAmortizacion($principal, $tasaAnual, $plazo, $sistema, $fechaInicio, $formaPago = '4') {
        
        // Convertir la tasa anual a una tasa por período, según la forma de pago.
        $tasaPeriodo = self::calcularTasaPeriodo($tasaAnual, $formaPago);
        
        // Array de sistemas de amortización válidos. No se consulta la BD.
        $sistemasValidos = ['FRANCES', 'ALEMAN', 'FLAT', 'SIMPLE', 'AMERICANO', 'COMPUESTO', 'SOBRE SALDO'];
        
        // Normalizar y validar el sistema de amortización.
        $sistema = strtoupper(trim($sistema));
        if (!in_array($sistema, $sistemasValidos)) {
            // Si el sistema no es válido, se usa 'FRANCES' como fallback seguro.
            error_log("Sistema de amortización '$sistema' no válido, usando FRANCES por defecto.");
            $sistema = 'FRANCES';
        }
        
        // Calcular cuota según el sistema seleccionado
        switch($sistema) {
            case 'FRANCES':
                return self::calcularSistemaFrances($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            case 'ALEMAN':
                return self::calcularSistemaAleman($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            case 'FLAT':
                return self::calcularSistemaFlat($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            case 'SIMPLE':
                return self::calcularSistemaSimple($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            case 'AMERICANO':
                return self::calcularSistemaAmericano($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            case 'COMPUESTO':
                return self::calcularSistemaCompuesto($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            case 'SOBRE SALDO':
                return self::calcularSistemaSobreSaldo($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
            default:
                // Este caso es redundante gracias a la validación anterior, pero es una buena práctica.
                return self::calcularSistemaFrances($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago);
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
           //  case '5': // BIMESTRAL
           //      return $tasaDecimal / 6;
          //   case '6': // SEMESTRAL
           //      return $tasaDecimal / 2;
           //  case '7': // ANUAL
            //     return $tasaDecimal;
            default:
                return $tasaDecimal / 12; // Por defecto mensual
        }
    }
    
    /**
     * Ajusta el plazo según la forma de pago
     */
    private static function ajustarPlazoPorFormaPago($plazo, $formaPago) {
        // Convertir el plazo a días según la forma de pago
        switch ($formaPago) {
            case '1': // DIARIO
                return $plazo; // Cada cuota es diaria
            case '2': // SEMANAL
                return $plazo * 7; // Cada cuota es semanal (7 días)
            case '3': // QUINCENAL
                return $plazo * 15; // Cada cuota es quincenal (15 días)
            case '4': // MENSUAL
                return $plazo * 30; // Cada cuota es mensual (aproximadamente 30 días)
            case '5': // BIMESTRAL
                return $plazo * 60; // Cada cuota es bimestral (aproximadamente 60 días)
            case '6': // SEMESTRAL
                return $plazo * 180; // Cada cuota es semestral (aproximadamente 180 días)
            case '7': // ANUAL
                return $plazo * 365; // Cada cuota es anual (aproximadamente 365 días)
            default:
                return $plazo * 30; // Por defecto mensual
        }
    }
    
    /**
     * Calcula la siguiente fecha de pago según la forma de pago
     */
    private static function calcularSiguienteFecha($fechaActual, $formaPago, $numeroCuota) {
        $fecha = clone $fechaActual;
        
        switch ($formaPago) {
            case '1': // DIARIO
                $fecha->modify('+' . $numeroCuota . ' day');
                break;
            case '2': // SEMANAL
                $fecha->modify('+' . $numeroCuota . ' week');
                break;
            case '3': // QUINCENAL
                $fecha->modify('+' . ($numeroCuota * 15) . ' day');
                break;
            case '4': // MENSUAL
                $fecha->modify('+' . $numeroCuota . ' month');
                break;
            case '5': // BIMESTRAL
                $fecha->modify('+' . ($numeroCuota * 2) . ' month');
                break;
            case '6': // SEMESTRAL
                $fecha->modify('+' . ($numeroCuota * 6) . ' month');
                break;
            case '7': // ANUAL
                $fecha->modify('+' . $numeroCuota . ' year');
                break;
            default:
                $fecha->modify('+' . $numeroCuota . ' month'); // Por defecto mensual
        }
        
        return $fecha;
    }

    /**
     * Sistema Francés: Cuota fija, amortización creciente, interés decreciente
     */
    private static function calcularSistemaFrances($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = new DateTime($fechaInicio);
        
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
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacion, 2),
                'interes' => round($interesCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
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
    private static function calcularSistemaAleman($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $amortizacionFija = $principal / $plazo;
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = new DateTime($fechaInicio);
        
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
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuota, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
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
    private static function calcularSistemaAmericano($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = new DateTime($fechaInicio);
        
        $interesFijo = $principal * $tasaPeriodo;
        
        for ($i = 1; $i <= $plazo; $i++) {
            $amortizacion = ($i == $plazo) ? $principal : 0;
            $cuota = ($i == $plazo) ? $interesFijo + $principal : $interesFijo;
            
            $totalIntereses += $interesFijo;
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuota, 2),
                'capital' => round($amortizacion, 2),
                'interes' => round($interesFijo, 2),
                'saldo' => round($i == $plazo ? 0 : $saldoRestante, 2)
            ];
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
    private static function calcularSistemaSimple($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $fecha = new DateTime($fechaInicio);
        
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
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesFijo, 2),
                'saldo' => round($saldoRestante, 2)
            ];
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
    private static function calcularSistemaCompuesto($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $tablaAmortizacion = [];
        $fecha = new DateTime($fechaInicio);
        
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
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesPorCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
        }
        
        return [
            'cuota_inicial' => round($cuotaFija, 2),
            'total_pagar' => round($montoFinal, 2),
            'total_intereses' => round($interesTotal, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }

    /**
     * Sistema Flat: Interés siempre sobre el capital original, cuota variable
     */
    private static function calcularSistemaFlat($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = new DateTime($fechaInicio);
        
        // En FLAT, el interés se calcula siempre sobre el capital original
        $interesFijo = $principal * $tasaPeriodo;
        $amortizacionFija = $principal / $plazo;
        
        for ($i = 1; $i <= $plazo; $i++) {
            // La cuota es la suma de amortización fija + interés fijo
            $cuota = $amortizacionFija + $interesFijo;
            
            // Actualizar saldo
            $saldoRestante -= $amortizacionFija;
            
            // Ajustar el saldo final para evitar decimales
            if ($i == $plazo) {
                $saldoRestante = 0;
            }
            
            $totalIntereses += $interesFijo;
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuota, 2),
                'capital' => round($amortizacionFija, 2),
                'interes' => round($interesFijo, 2),
                'saldo' => round($saldoRestante, 2)
            ];
        }
        
        return [
            'cuota_inicial' => round($cuota, 2),
            'total_pagar' => round($principal + $totalIntereses, 2),
            'total_intereses' => round($totalIntereses, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }

    /**
     * Sistema Sobre Saldo: Interés sobre el saldo restante, amortización variable
     */
    private static function calcularSistemaSobreSaldo($principal, $tasaPeriodo, $plazo, $fechaInicio, $formaPago) {
        $tablaAmortizacion = [];
        $saldoRestante = $principal;
        $totalIntereses = 0;
        $fecha = new DateTime($fechaInicio);
        
        // Calcular cuota fija (similar al sistema francés pero con interés sobre saldo)
        if ($tasaPeriodo == 0) {
            $cuotaFija = $principal / $plazo;
        } else {
            $cuotaFija = $principal * ($tasaPeriodo * pow(1 + $tasaPeriodo, $plazo)) / (pow(1 + $tasaPeriodo, $plazo) - 1);
        }
        
        for ($i = 1; $i <= $plazo; $i++) {
            // Calcular interés del período sobre el saldo restante
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
            
            // Calcular fecha según forma de pago
            $fechaPago = self::calcularSiguienteFecha($fecha, $formaPago, $i - 1);
            
            $tablaAmortizacion[] = [
                'nro_cuota' => $i,
                'fecha' => $fechaPago->format('Y-m-d'),
                'monto' => round($cuotaFija, 2),
                'capital' => round($amortizacion, 2),
                'interes' => round($interesCuota, 2),
                'saldo' => round($saldoRestante, 2)
            ];
        }
        
        return [
            'cuota_inicial' => round($cuotaFija, 2),
            'total_pagar' => round($principal + $totalIntereses, 2),
            'total_intereses' => round($totalIntereses, 2),
            'tabla_amortizacion' => $tablaAmortizacion
        ];
    }
    
    /**
     * Obtiene una página específica de la tabla de amortización
     * @param array $tablaCompleta La tabla de amortización completa
     * @param int $pagina El número de página (comenzando desde 1)
     * @param int $porPagina Cantidad de registros por página
     * @return array Los registros de la página solicitada y metadatos de paginación
     */
    public static function paginarTablaAmortizacion($tablaCompleta, $pagina = 1, $porPagina = 12) {
        $totalRegistros = count($tablaCompleta);
        $totalPaginas = ceil($totalRegistros / $porPagina);
        
        // Validar que la página solicitada sea válida
        $pagina = max(1, min($pagina, $totalPaginas));
        
        $inicio = ($pagina - 1) * $porPagina;
        $fin = min($inicio + $porPagina, $totalRegistros);
        
        $registrosPagina = array_slice($tablaCompleta, $inicio, $porPagina);
        
        return [
            'registros' => $registrosPagina,
            'paginacion' => [
                'pagina_actual' => $pagina,
                'total_paginas' => $totalPaginas,
                'por_pagina' => $porPagina,
                'total_registros' => $totalRegistros
            ]
        ];
    }
}
?> 