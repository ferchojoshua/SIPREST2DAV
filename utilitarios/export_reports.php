<?php
require_once __DIR__ . "/../modelos/conexion.php";
require_once __DIR__ . "/../modelos/configuracion_modelo.php";
require_once __DIR__ . "/../controladores/configuracion_controlador.php";

/**
 * Utilidad centralizada para exportaciones de reportes
 * Incluye automáticamente datos de empresa, logo, subtotales y totales
 */
class ExportReports
{
    private $empresa_data;
    private $logo_path;

    public function __construct()
    {
        $this->loadCompanyData();
    }

    /**
     * Cargar datos de la empresa
     */
    private function loadCompanyData()
    {
        try {
            $this->empresa_data = ConfiguracionControlador::ctrObtenerDataEmpresa();
            
            // Establecer ruta del logo
            if (!empty($this->empresa_data->config_logo) && 
                file_exists(__DIR__ . "/../uploads/logos/" . $this->empresa_data->config_logo)) {
                $this->logo_path = __DIR__ . "/../uploads/logos/" . $this->empresa_data->config_logo;
            } else {
                $this->logo_path = __DIR__ . "/../vistas/assets/img/default-logo.png";
            }
        } catch (Exception $e) {
            error_log("Error cargando datos de empresa: " . $e->getMessage());
            $this->empresa_data = (object)[
                'confi_razon' => 'Sistema de Préstamos',
                'confi_ruc' => 'No configurado',
                'confi_direccion' => 'No configurado',
                'config_correo' => 'admin@sistema.com',
                'config_celular' => 'No configurado',
                'config_logo' => null
            ];
            $this->logo_path = __DIR__ . "/../vistas/assets/img/default-logo.png";
        }
    }

    /**
     * Generar encabezado HTML para reportes PDF
     */
    public function generatePDFHeader($titulo_reporte, $fecha_reporte = null)
    {
        $fecha_reporte = $fecha_reporte ?: date('d/m/Y H:i:s');
        
        return '
        <div class="header-empresa" style="text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 15px; margin-bottom: 20px;">
            <img src="' . $this->logo_path . '" style="width: 80px; height: auto; margin-bottom: 10px;" alt="Logo Empresa">
            <div style="font-size: 18px; font-weight: bold; color: #2c3e50; margin: 5px 0;">' . 
                htmlspecialchars($this->empresa_data->confi_razon) . '</div>
            <div style="font-size: 14px; color: #7f8c8d;">RUC: ' . 
                htmlspecialchars($this->empresa_data->confi_ruc) . '</div>
            <div style="font-size: 12px; color: #7f8c8d; margin: 5px 0;">' . 
                htmlspecialchars($this->empresa_data->confi_direccion) . '</div>
            <div style="font-size: 12px; color: #7f8c8d;">Tel: ' . 
                htmlspecialchars($this->empresa_data->config_celular) . ' | Email: ' . 
                htmlspecialchars($this->empresa_data->config_correo) . '</div>
        </div>
        <div style="text-align: center; font-size: 16px; font-weight: bold; color: #2c3e50; margin: 20px 0; padding: 10px; background-color: #ecf0f1; border-radius: 5px;">
            ' . htmlspecialchars($titulo_reporte) . '
        </div>
        <div style="text-align: right; font-size: 10px; color: #7f8c8d; margin-bottom: 15px;">
            Generado el: ' . $fecha_reporte . '
        </div>';
    }

    /**
     * Generar pie de página para reportes PDF
     */
    public function generatePDFFooter()
    {
        return '
        <div style="margin-top: 30px; text-align: center; font-size: 9px; color: #95a5a6; border-top: 1px solid #bdc3c7; padding-top: 15px;">
            <p>Este reporte fue generado automáticamente por el Sistema de Préstamos</p>
            <p>' . htmlspecialchars($this->empresa_data->confi_razon) . ' - ' . 
                htmlspecialchars($this->empresa_data->config_correo) . '</p>
        </div>';
    }

    /**
     * Calcular totales de un array de datos
     */
    public function calculateTotals($data, $numeric_columns)
    {
        $totals = [];
        
        foreach ($numeric_columns as $column) {
            $totals[$column] = 0;
            foreach ($data as $row) {
                if (isset($row[$column]) && is_numeric($row[$column])) {
                    $totals[$column] += (float)$row[$column];
                }
            }
        }
        
        return $totals;
    }

    /**
     * Formatear número como moneda
     */
    public function formatCurrency($amount, $symbol = 'C$')
    {
        return $symbol . ' ' . number_format((float)$amount, 2, '.', ',');
    }

    /**
     * Generar tabla HTML con totales para PDF
     */
    public function generateTableWithTotals($data, $headers, $numeric_columns = [], $titulo = "")
    {
        if (empty($data)) {
            return '<p style="text-align: center; color: #e74c3c;">No hay datos disponibles para mostrar.</p>';
        }

        $html = '';
        
        if ($titulo) {
            $html .= '<h3 style="color: #2c3e50; margin: 20px 0 10px 0;">' . htmlspecialchars($titulo) . '</h3>';
        }

        $html .= '
        <table style="width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 11px;">
            <thead>
                <tr style="background-color: #3498db; color: white;">';
        
        foreach ($headers as $header) {
            $html .= '<th style="border: 1px solid #bdc3c7; padding: 8px; text-align: center; font-weight: bold;">' . 
                     htmlspecialchars($header) . '</th>';
        }
        
        $html .= '</tr></thead><tbody>';

        // Filas de datos
        $row_count = 0;
        foreach ($data as $row) {
            $bg_color = ($row_count % 2 == 0) ? '#f8f9fa' : 'white';
            $html .= '<tr style="background-color: ' . $bg_color . ';">';
            
            foreach (array_keys($headers) as $key) {
                $value = $row[$key] ?? '';
                
                // Formatear valores numéricos como moneda si corresponde
                if (in_array($key, $numeric_columns) && is_numeric($value)) {
                    $value = $this->formatCurrency($value);
                }
                
                $html .= '<td style="border: 1px solid #bdc3c7; padding: 6px; text-align: ' . 
                         (in_array($key, $numeric_columns) ? 'right' : 'left') . ';">' . 
                         htmlspecialchars($value) . '</td>';
            }
            
            $html .= '</tr>';
            $row_count++;
        }

        // Fila de totales si hay columnas numéricas
        if (!empty($numeric_columns)) {
            $totals = $this->calculateTotals($data, $numeric_columns);
            $html .= '<tr style="background-color: #2c3e50; color: white; font-weight: bold;">';
            
            foreach (array_keys($headers) as $key) {
                if (in_array($key, $numeric_columns)) {
                    $html .= '<td style="border: 1px solid #bdc3c7; padding: 8px; text-align: right;">' . 
                             $this->formatCurrency($totals[$key]) . '</td>';
                } elseif ($key === array_keys($headers)[0]) {
                    $html .= '<td style="border: 1px solid #bdc3c7; padding: 8px; text-align: center; font-weight: bold;">TOTALES</td>';
                } else {
                    $html .= '<td style="border: 1px solid #bdc3c7; padding: 8px;"></td>';
                }
            }
            
            $html .= '</tr>';
        }

        $html .= '</tbody></table>';

        // Resumen estadístico
        if (!empty($numeric_columns)) {
            $html .= '<div style="margin-top: 20px; padding: 15px; background-color: #ecf0f1; border-radius: 5px;">';
            $html .= '<h4 style="color: #2c3e50; margin: 0 0 10px 0;">Resumen Estadístico</h4>';
            $html .= '<div style="display: flex; justify-content: space-between; flex-wrap: wrap;">';
            
            foreach ($numeric_columns as $column) {
                $total = $totals[$column];
                $count = count($data);
                $promedio = $count > 0 ? $total / $count : 0;
                
                $html .= '<div style="margin: 5px; padding: 10px; background-color: white; border-radius: 3px; min-width: 200px;">';
                $html .= '<strong>' . $headers[$column] . ':</strong><br>';
                $html .= 'Total: ' . $this->formatCurrency($total) . '<br>';
                $html .= 'Promedio: ' . $this->formatCurrency($promedio) . '<br>';
                $html .= 'Registros: ' . number_format($count) . '</div>';
            }
            
            $html .= '</div></div>';
        }

        return $html;
    }

    /**
     * Configurar botones de DataTables con datos de empresa
     */
    public function getDataTablesButtonsConfig($titulo_reporte, $filename_prefix = 'reporte')
    {
        $fecha_actual = date('Y-m-d_H-i-s');
        
        return [
            [
                'extend' => 'excelHtml5',
                'title' => $titulo_reporte . ' - ' . $this->empresa_data->confi_razon,
                'text' => '<i class="fas fa-file-excel"></i> Excel',
                'className' => 'btn btn-success btn-sm mr-1',
                'filename' => $filename_prefix . '_' . $fecha_actual,
                'exportOptions' => [
                    'columns' => ':visible'
                ]
            ],
            [
                'extend' => 'pdfHtml5',
                'title' => $titulo_reporte,
                'text' => '<i class="fas fa-file-pdf"></i> PDF',
                'className' => 'btn btn-danger btn-sm mr-1',
                'filename' => $filename_prefix . '_' . $fecha_actual,
                'orientation' => 'landscape',
                'pageSize' => 'A4',
                'exportOptions' => [
                    'columns' => ':visible'
                ],
                'customize' => 'function(doc) {
                    // Agregar encabezado de empresa
                    doc.content.splice(0, 0, {
                        margin: [0, 0, 0, 12],
                        alignment: "center",
                        stack: [
                            {
                                text: "' . addslashes($this->empresa_data->confi_razon) . '",
                                style: "header"
                            },
                            {
                                text: "RUC: ' . addslashes($this->empresa_data->confi_ruc) . '",
                                style: "subheader"
                            },
                            {
                                text: "' . addslashes($this->empresa_data->confi_direccion) . '",
                                style: "subheader"
                            }
                        ]
                    });
                    
                    // Estilos
                    doc.styles.header = {
                        fontSize: 18,
                        bold: true,
                        color: "#2c3e50"
                    };
                    doc.styles.subheader = {
                        fontSize: 12,
                        color: "#7f8c8d"
                    };
                }'
            ],
            [
                'extend' => 'print',
                'text' => '<i class="fas fa-print"></i> Imprimir',
                'className' => 'btn btn-primary btn-sm mr-1',
                'title' => $titulo_reporte . ' - ' . $this->empresa_data->confi_razon,
                'exportOptions' => [
                    'columns' => ':visible'
                ]
            ]
        ];
    }

    /**
     * Generar reporte completo en formato PDF usando MPDF
     */
    public function generatePDFReport($data, $headers, $titulo_reporte, $numeric_columns = [], $subtitulo = "")
    {
        require_once __DIR__ . "/../MPDF/vendor/autoload.php";
        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'orientation' => 'L',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20
        ]);

        // CSS para el PDF
        $css = '
        <style>
            body { font-family: Arial, sans-serif; font-size: 10px; }
            .header-empresa { text-align: center; border-bottom: 2px solid #2c3e50; padding-bottom: 15px; margin-bottom: 20px; }
            .logo-empresa { width: 60px; height: auto; }
            .titulo-reporte { font-size: 14px; font-weight: bold; text-align: center; color: #2c3e50; margin: 15px 0; }
            .tabla-datos { width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 9px; }
            .tabla-datos th { background-color: #3498db; color: white; padding: 6px; text-align: center; border: 1px solid #2c3e50; }
            .tabla-datos td { padding: 4px; border: 1px solid #bdc3c7; }
            .tabla-datos tr:nth-child(even) { background-color: #f8f9fa; }
            .totales { background-color: #2c3e50; color: white; font-weight: bold; }
            .resumen { margin-top: 15px; padding: 10px; background-color: #ecf0f1; border-radius: 3px; }
            .text-right { text-align: right; }
            .text-center { text-align: center; }
        </style>';

        // Construir HTML
        $html = $css;
        $html .= $this->generatePDFHeader($titulo_reporte);
        
        if ($subtitulo) {
            $html .= '<div class="titulo-reporte">' . htmlspecialchars($subtitulo) . '</div>';
        }
        
        $html .= $this->generateTableWithTotals($data, $headers, $numeric_columns);
        $html .= $this->generatePDFFooter();

        $mpdf->WriteHTML($html);
        
        return $mpdf;
    }

    /**
     * Obtener datos de empresa para JavaScript
     */
    public function getCompanyDataForJS()
    {
        return [
            'nombre' => $this->empresa_data->confi_razon,
            'ruc' => $this->empresa_data->confi_ruc,
            'direccion' => $this->empresa_data->confi_direccion,
            'telefono' => $this->empresa_data->config_celular,
            'email' => $this->empresa_data->config_correo,
            'logo_url' => !empty($this->empresa_data->config_logo) ? 
                         'uploads/logos/' . $this->empresa_data->config_logo : 
                         'vistas/assets/img/default-logo.png'
        ];
    }
} 