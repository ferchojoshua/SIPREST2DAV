<?php

class DashboardCobradoresControlador
{
    /**
     * Obtener métricas generales del dashboard
     */
    static public function ctrObtenerMetricasGenerales($filtros)
    {
        try {
            // Obtener métricas del período actual
            $metricas_actual = DashboardCobradoresModelo::mdlObtenerMetricasGenerales($filtros);
            
            // Calcular fechas del período anterior para comparación
            $filtros_anterior = self::calcularPeriodoAnterior($filtros);
            $metricas_anterior = DashboardCobradoresModelo::mdlObtenerMetricasGenerales($filtros_anterior);
            
            // Calcular variaciones porcentuales
            $variaciones = self::calcularVariaciones($metricas_actual, $metricas_anterior);
            
            return array_merge($metricas_actual, $variaciones);
            
        } catch (Exception $e) {
            error_log("Error en ctrObtenerMetricasGenerales: " . $e->getMessage());
            throw new Exception("Error al obtener métricas generales");
        }
    }

    /**
     * Obtener datos de cobros por cobrador para gráfico de pastel
     */
    static public function ctrObtenerCobrosPorCobrador($filtros)
    {
        try {
            $datos = DashboardCobradoresModelo::mdlObtenerCobrosPorCobrador($filtros);
            
            // Procesar datos para Chart.js
            $resultado = [
                'labels' => [],
                'datasets' => [{
                    'data' => [],
                    'backgroundColor' => [],
                    'borderColor' => [],
                    'borderWidth' => 2
                }]
            ];
            
            // Paleta de colores profesional
            $colores = [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', 
                '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
                '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
            ];
            
            foreach ($datos as $index => $fila) {
                $resultado['labels'][] = $fila['cobrador_nombre'];
                $resultado['datasets'][0]['data'][] = (float)$fila['total_cobrado'];
                
                $color_index = $index % count($colores);
                $resultado['datasets'][0]['backgroundColor'][] = $colores[$color_index];
                $resultado['datasets'][0]['borderColor'][] = self::darkenColor($colores[$color_index], 20);
            }
            
            // Agregar información adicional
            $resultado['total_cobradores'] = count($datos);
            $resultado['total_monto'] = array_sum($resultado['datasets'][0]['data']);
            $resultado['promedio_por_cobrador'] = $resultado['total_cobradores'] > 0 ? 
                $resultado['total_monto'] / $resultado['total_cobradores'] : 0;
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en ctrObtenerCobrosPorCobrador: " . $e->getMessage());
            throw new Exception("Error al obtener cobros por cobrador");
        }
    }

    /**
     * Obtener datos de mora por cobrador para gráfico de pastel
     */
    static public function ctrObtenerMoraPorCobrador($filtros)
    {
        try {
            $datos = DashboardCobradoresModelo::mdlObtenerMoraPorCobrador($filtros);
            
            // Procesar datos para Chart.js
            $resultado = [
                'labels' => [],
                'datasets' => [{
                    'data' => [],
                    'backgroundColor' => [],
                    'borderColor' => [],
                    'borderWidth' => 2
                }]
            ];
            
            // Paleta de colores para mora (tonos rojos y naranjas)
            $colores_mora = [
                '#FF4444', '#FF6B6B', '#FF8E53', '#FFA726',
                '#FFCC02', '#FFD54F', '#FF7043', '#E57373',
                '#F06292', '#BA68C8', '#9575CD', '#7986CB'
            ];
            
            foreach ($datos as $index => $fila) {
                if ($fila['total_mora'] > 0) { // Solo mostrar cobradores con mora
                    $resultado['labels'][] = $fila['cobrador_nombre'];
                    $resultado['datasets'][0]['data'][] = (float)$fila['total_mora'];
                    
                    $color_index = $index % count($colores_mora);
                    $resultado['datasets'][0]['backgroundColor'][] = $colores_mora[$color_index];
                    $resultado['datasets'][0]['borderColor'][] = self::darkenColor($colores_mora[$color_index], 20);
                }
            }
            
            // Información adicional
            $resultado['total_cobradores_con_mora'] = count($resultado['labels']);
            $resultado['total_mora'] = array_sum($resultado['datasets'][0]['data']);
            $resultado['promedio_mora'] = $resultado['total_cobradores_con_mora'] > 0 ? 
                $resultado['total_mora'] / $resultado['total_cobradores_con_mora'] : 0;
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en ctrObtenerMoraPorCobrador: " . $e->getMessage());
            throw new Exception("Error al obtener mora por cobrador");
        }
    }

    /**
     * Obtener datos de comparación mensual para gráfico de líneas
     */
    static public function ctrObtenerComparacionMensual($filtros)
    {
        try {
            // Obtener datos del mes actual
            $datos_actual = DashboardCobradoresModelo::mdlObtenerComparacionDiaria($filtros);
            
            // Obtener datos del mes anterior
            $filtros_anterior = self::calcularPeriodoAnterior($filtros);
            $datos_anterior = DashboardCobradoresModelo::mdlObtenerComparacionDiaria($filtros_anterior);
            
            // Procesar datos para Chart.js
            $resultado = [
                'labels' => [], // Días del mes
                'datasets' => [
                    [
                        'label' => 'Cobros Mes Actual',
                        'data' => [],
                        'borderColor' => '#36A2EB',
                        'backgroundColor' => 'rgba(54, 162, 235, 0.1)',
                        'borderWidth' => 3,
                        'fill' => true,
                        'tension' => 0.4
                    ],
                    [
                        'label' => 'Cobros Mes Anterior',
                        'data' => [],
                        'borderColor' => '#FF6384',
                        'backgroundColor' => 'rgba(255, 99, 132, 0.1)',
                        'borderWidth' => 3,
                        'fill' => false,
                        'tension' => 0.4,
                        'borderDash' => [5, 5]
                    ],
                    [
                        'label' => 'Mora Mes Actual',
                        'data' => [],
                        'borderColor' => '#FFCE56',
                        'backgroundColor' => 'rgba(255, 206, 86, 0.1)',
                        'borderWidth' => 2,
                        'fill' => false,
                        'tension' => 0.4,
                        'hidden' => true // Oculto por defecto
                    ],
                    [
                        'label' => 'Mora Mes Anterior',
                        'data' => [],
                        'borderColor' => '#FF9F40',
                        'backgroundColor' => 'rgba(255, 159, 64, 0.1)',
                        'borderWidth' => 2,
                        'fill' => false,
                        'tension' => 0.4,
                        'borderDash' => [3, 3],
                        'hidden' => true // Oculto por defecto
                    ]
                ]
            ];
            
            // Generar etiquetas de días
            $dias_mes = date('t', strtotime($filtros['fecha_fin']));
            for ($dia = 1; $dia <= $dias_mes; $dia++) {
                $resultado['labels'][] = $dia;
            }
            
            // Llenar datos del mes actual
            self::llenarDatosComparacion($resultado, $datos_actual, 0, 2); // Índices 0 y 2 para actual
            
            // Llenar datos del mes anterior
            self::llenarDatosComparacion($resultado, $datos_anterior, 1, 3); // Índices 1 y 3 para anterior
            
            // Calcular estadísticas adicionales
            $resultado['estadisticas'] = [
                'total_actual_cobros' => array_sum($resultado['datasets'][0]['data']),
                'total_anterior_cobros' => array_sum($resultado['datasets'][1]['data']),
                'total_actual_mora' => array_sum($resultado['datasets'][2]['data']),
                'total_anterior_mora' => array_sum($resultado['datasets'][3]['data']),
                'dias_transcurridos' => date('j', strtotime($filtros['fecha_fin'])),
                'promedio_diario_actual' => 0,
                'promedio_diario_anterior' => 0
            ];
            
            // Calcular promedios
            if ($resultado['estadisticas']['dias_transcurridos'] > 0) {
                $resultado['estadisticas']['promedio_diario_actual'] = 
                    $resultado['estadisticas']['total_actual_cobros'] / $resultado['estadisticas']['dias_transcurridos'];
            }
            
            $dias_mes_anterior = date('t', strtotime($filtros_anterior['fecha_fin']));
            if ($dias_mes_anterior > 0) {
                $resultado['estadisticas']['promedio_diario_anterior'] = 
                    $resultado['estadisticas']['total_anterior_cobros'] / $dias_mes_anterior;
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en ctrObtenerComparacionMensual: " . $e->getMessage());
            throw new Exception("Error al obtener comparación mensual");
        }
    }

    /**
     * Obtener datos para tabla de rendimiento detallado
     */
    static public function ctrObtenerTablaRendimiento($filtros)
    {
        try {
            $datos = DashboardCobradoresModelo::mdlObtenerTablaRendimiento($filtros);
            
            // Procesar datos y añadir métricas calculadas
            foreach ($datos as &$fila) {
                // Calcular eficiencia de cobro
                $fila['eficiencia'] = self::calcularEficienciaCobro(
                    $fila['total_cobrado'], 
                    $fila['total_esperado']
                );
                
                // Determinar nivel de rendimiento
                $fila['rendimiento_nivel'] = self::determinarRendimiento($fila['eficiencia']);
                $fila['rendimiento_texto'] = self::obtenerTextoRendimiento($fila['rendimiento_nivel']);
                $fila['rendimiento_clase'] = self::obtenerClaseRendimiento($fila['rendimiento_nivel']);
                
                // Formatear montos
                $fila['total_cobrado_formato'] = number_format($fila['total_cobrado'], 2);
                $fila['total_mora_formato'] = number_format($fila['total_mora'], 2);
                $fila['total_esperado_formato'] = number_format($fila['total_esperado'], 2);
                
                // Calcular variación mensual si está disponible
                if (isset($fila['total_mes_anterior'])) {
                    $fila['variacion_mensual'] = self::calcularVariacionPorcentual(
                        $fila['total_cobrado'], 
                        $fila['total_mes_anterior']
                    );
                } else {
                    $fila['variacion_mensual'] = 0;
                }
            }
            
            // Ordenar por eficiencia descendente
            usort($datos, function($a, $b) {
                return $b['eficiencia'] <=> $a['eficiencia'];
            });
            
            return $datos;
            
        } catch (Exception $e) {
            error_log("Error en ctrObtenerTablaRendimiento: " . $e->getMessage());
            throw new Exception("Error al obtener tabla de rendimiento");
        }
    }

    /**
     * Listar cobradores por sucursal
     */
    static public function ctrListarCobradoresPorSucursal($sucursal_id)
    {
        try {
            return DashboardCobradoresModelo::mdlListarCobradoresPorSucursal($sucursal_id);
        } catch (Exception $e) {
            error_log("Error en ctrListarCobradoresPorSucursal: " . $e->getMessage());
            throw new Exception("Error al listar cobradores por sucursal");
        }
    }

    /**
     * Obtener resumen ejecutivo
     */
    static public function ctrObtenerResumenEjecutivo($filtros)
    {
        try {
            $resumen = DashboardCobradoresModelo::mdlObtenerResumenEjecutivo($filtros);
            
            // Calcular KPIs adicionales
            $resumen['kpis'] = [
                'eficiencia_global' => self::calcularEficienciaCobro(
                    $resumen['total_cobrado'], 
                    $resumen['total_esperado']
                ),
                'indice_mora' => self::calcularIndiceMora(
                    $resumen['total_mora'], 
                    $resumen['total_cartera']
                ),
                'productividad_promedio' => $resumen['total_cobradores'] > 0 ? 
                    $resumen['total_cobrado'] / $resumen['total_cobradores'] : 0,
                'cobertura_rutas' => self::calcularCoberturaRutas($filtros)
            ];
            
            return $resumen;
            
        } catch (Exception $e) {
            error_log("Error en ctrObtenerResumenEjecutivo: " . $e->getMessage());
            throw new Exception("Error al obtener resumen ejecutivo");
        }
    }

    // ========== MÉTODOS AUXILIARES ==========

    /**
     * Calcular período anterior para comparaciones
     */
    private static function calcularPeriodoAnterior($filtros)
    {
        $fecha_inicio = new DateTime($filtros['fecha_inicio']);
        $fecha_fin = new DateTime($filtros['fecha_fin']);
        
        // Calcular diferencia en días
        $diff = $fecha_inicio->diff($fecha_fin);
        $dias = $diff->days + 1;
        
        // Restar los días para obtener el período anterior
        $fecha_inicio_anterior = clone $fecha_inicio;
        $fecha_inicio_anterior->sub(new DateInterval('P' . $dias . 'D'));
        
        $fecha_fin_anterior = clone $fecha_inicio;
        $fecha_fin_anterior->sub(new DateInterval('P1D'));
        
        return [
            'sucursal_id' => $filtros['sucursal_id'],
            'ruta_id' => $filtros['ruta_id'],
            'cobrador_id' => $filtros['cobrador_id'],
            'fecha_inicio' => $fecha_inicio_anterior->format('Y-m-d'),
            'fecha_fin' => $fecha_fin_anterior->format('Y-m-d')
        ];
    }

    /**
     * Calcular variaciones porcentuales
     */
    private static function calcularVariaciones($actual, $anterior)
    {
        return [
            'variacion_cobrado' => self::calcularVariacionPorcentual(
                $actual['total_cobrado'], 
                $anterior['total_cobrado']
            ),
            'variacion_mora' => self::calcularVariacionPorcentual(
                $actual['total_mora'], 
                $anterior['total_mora']
            ),
            'variacion_eficiencia' => self::calcularVariacionPorcentual(
                $actual['eficiencia_cobro'], 
                $anterior['eficiencia_cobro']
            )
        ];
    }

    /**
     * Calcular variación porcentual
     */
    private static function calcularVariacionPorcentual($actual, $anterior)
    {
        if ($anterior == 0) {
            return $actual > 0 ? 100 : 0;
        }
        
        return round((($actual - $anterior) / $anterior) * 100, 2);
    }

    /**
     * Calcular eficiencia de cobro
     */
    private static function calcularEficienciaCobro($cobrado, $esperado)
    {
        if ($esperado == 0) return 0;
        return round(($cobrado / $esperado) * 100, 2);
    }

    /**
     * Determinar nivel de rendimiento
     */
    private static function determinarRendimiento($eficiencia)
    {
        if ($eficiencia >= 90) return 'excelente';
        if ($eficiencia >= 75) return 'bueno';
        if ($eficiencia >= 60) return 'regular';
        return 'deficiente';
    }

    /**
     * Obtener texto de rendimiento
     */
    private static function obtenerTextoRendimiento($nivel)
    {
        $textos = [
            'excelente' => 'Excelente',
            'bueno' => 'Bueno',
            'regular' => 'Regular',
            'deficiente' => 'Deficiente'
        ];
        
        return $textos[$nivel] ?? 'No evaluado';
    }

    /**
     * Obtener clase CSS de rendimiento
     */
    private static function obtenerClaseRendimiento($nivel)
    {
        $clases = [
            'excelente' => 'performance-excellent',
            'bueno' => 'performance-good',
            'regular' => 'performance-regular',
            'deficiente' => 'performance-poor'
        ];
        
        return $clases[$nivel] ?? '';
    }

    /**
     * Llenar datos de comparación en el gráfico
     */
    private static function llenarDatosComparacion(&$resultado, $datos, $indice_cobros, $indice_mora)
    {
        // Inicializar arrays con ceros
        $dias_mes = count($resultado['labels']);
        $resultado['datasets'][$indice_cobros]['data'] = array_fill(0, $dias_mes, 0);
        $resultado['datasets'][$indice_mora]['data'] = array_fill(0, $dias_mes, 0);
        
        // Llenar datos reales
        foreach ($datos as $fila) {
            $dia = (int)date('j', strtotime($fila['fecha']));
            if ($dia <= $dias_mes) {
                $resultado['datasets'][$indice_cobros]['data'][$dia - 1] += (float)$fila['cobros'];
                $resultado['datasets'][$indice_mora]['data'][$dia - 1] += (float)$fila['mora'];
            }
        }
    }

    /**
     * Oscurecer color para bordes
     */
    private static function darkenColor($color, $percent)
    {
        $color = ltrim($color, '#');
        $rgb = str_split($color, 2);
        
        foreach ($rgb as &$component) {
            $component = hexdec($component);
            $component = max(0, min(255, $component - ($component * $percent / 100)));
            $component = dechex($component);
            $component = str_pad($component, 2, '0', STR_PAD_LEFT);
        }
        
        return '#' . implode('', $rgb);
    }

    /**
     * Calcular índice de mora
     */
    private static function calcularIndiceMora($mora, $cartera)
    {
        if ($cartera == 0) return 0;
        return round(($mora / $cartera) * 100, 2);
    }

    /**
     * Calcular cobertura de rutas
     */
    private static function calcularCoberturaRutas($filtros)
    {
        try {
            return DashboardCobradoresModelo::mdlCalcularCoberturaRutas($filtros);
        } catch (Exception $e) {
            return 0;
        }
    }
}
?> 