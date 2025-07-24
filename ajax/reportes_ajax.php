<?php

require_once "../controladores/reportes_controlador.php";
require_once "../modelos/reportes_modelo.php";
require_once "../controladores/configuracion_controlador.php";
require_once "../modelos/configuracion_modelo.php";

class AjaxReportes
{

    /*===================================================================*/
    //OBTENER REPORTE DE CLIENTES MOROSOS
    /*===================================================================*/
    public function ajaxObtenerReporteMorosos()
    {
        $reporteMorosos = ReportesControlador::ctrObtenerReporteMorosos();
        echo json_encode($reporteMorosos);
    }

    /*===================================================================*/
    //OBTENER REPORTE DE RECUPERACION
    /*===================================================================*/
    public function ajaxObtenerReporteRecuperacion()
    {
        $reporteRecuperacion = ReportesControlador::ctrObtenerReporteRecuperacion($_POST["fecha_inicial"], $_POST["fecha_final"]);
        echo json_encode($reporteRecuperacion);
    }


    /*===================================================================*/
    //LISTAR REPORTE POR CLIENTE
    /*===================================================================*/
    public function  ReportePorCliente($cliente_id)
    {
        $reporteporCliente = Reportescontrolador::ctrReportePorCliente($cliente_id);
        echo json_encode($reporteporCliente);
    }


    /*===================================================================*/
    //LISTAR REPORTE CUOTAS PAGADAS
    /*===================================================================*/
    public function  ListarCuotasPagadasReport()
    {
        $reportecuotasPagadas = Reportescontrolador::ctrCuotasPagadasReport();
        echo json_encode($reportecuotasPagadas);
    }


    /*===================================================================*/
    //LISTAR REPORTE PIVOT
    /*===================================================================*/
    public function  ListarReportePivot()
    {
        $reportePivot = Reportescontrolador::ctrReportePivot();
        echo json_encode($reportePivot);
    }


    /*===================================================================*/
    //LISTAR SELECT USUARIO RECORD EN COMBO
    /*===================================================================*/
    public function ListarSelectUsuario()
    {
        $selectUsuario = Reportescontrolador::ctrListarSelectUsuario();
        echo json_encode($selectUsuario, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //LISTAR SELECT AÑOS RECORD EN COMBO
    /*===================================================================*/
    public function ListarSelectAnio()
    {
        $selectAnio = Reportescontrolador::ctrListarSelectAnio();
        echo json_encode($selectAnio, JSON_UNESCAPED_UNICODE);
    }



    /*===================================================================*/
    //LISTAR  REPORTE RECOR POR USUARIO
    /*===================================================================*/
    public function  ReporteRecordUsu($id_usuario, $anio)
    {
        $reportepoRecord = Reportescontrolador::ctrReporteRecordUsu($id_usuario, $anio);
        echo json_encode($reportepoRecord);
    }

    /*===================================================================*/
    // REPORTE DE SALDOS ARRASTRADOS
    /*===================================================================*/
    public function ajaxReporteSaldosArrastrados()
    {
        if (isset($_POST["fecha_inicio"]) && isset($_POST["fecha_fin"])) {
            $fecha_inicio = $_POST["fecha_inicio"];
            $fecha_fin = $_POST["fecha_fin"];

            $reporte = ReportesControlador::ctrReporteSaldosArrastrados($fecha_inicio, $fecha_fin);

            // Datatables espera un array 'data'
            $response = [
                "data" => $reporte
            ];

            echo json_encode($response["data"]);
        }
    }
}

if (isset($_POST['accion']) && $_POST['accion'] == 'reporte_saldos_arrastrados') {
    $reporte = new AjaxReportes();
    $reporte->ajaxReporteSaldosArrastrados();
    return; // Añadido para evitar que continue al switch
}

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case 1: //LISTAR  REPORTE DE PRESTAMOS POR CLIENTE
            $reporteporCliente = new AjaxReportes();
            $reporteporCliente->ReportePorCliente($_POST["cliente_id"]);
            break;

        case 2: //LISTAR  REPORTE CUOTAS PAGADAS
            $reportecuotasPagadas = new AjaxReportes();
            $fechaInicial = $_POST["fecha_inicial"] ?? null;
            $fechaFinal = $_POST["fecha_final"] ?? null;
            $reportecuotasPagadas->ListarCuotasPagadasReport($fechaInicial, $fechaFinal);
            break;

        case 3: //PIVOT
            $reportePivot = new AjaxReportes();
            $reportePivot->ListarReportePivot();
            break;

        case 4:  // LISTAR SELECT USUARIO RECORD EN COMBO
            $selectUsuario = new AjaxReportes();
            $selectUsuario->ListarSelectUsuario();
            break;

        case 5:   // LISTAR SELECT AÑOS RECORD EN COMBO
            $selectAnio = new AjaxReportes();
            $selectAnio->ListarSelectAnio();
            break;

        case 6: //LISTAR  REPORTE RECOR POR USUARIO
            $reportepoRecord = new AjaxReportes();
            $reportepoRecord->ReporteRecordUsu($_POST["id_usuario"], $_POST["anio"]);
            break;
            
        case 7: //REPORTE MOROSOS
            $reporteMorosos = ReportesControlador::ctrObtenerReporteMorosos();
            echo json_encode($reporteMorosos);
            break;

        case 8: //REPORTE RECUPERACION
            $reporteRecuperacion = ReportesControlador::ctrObtenerReporteRecuperacion($_POST["fecha_inicial"], $_POST["fecha_final"]);
            echo json_encode($reporteRecuperacion);
            break;

        case 9: //OBTENER MONEDAS
            $monedas = ReportesControlador::ctrObtenerMonedas();
            echo json_encode($monedas);
            break;

        case 10: //REPORTE DIARIO
            $reporteDiario = ReportesControlador::ctrObtenerReporteDiario($_POST["fecha"]);
            echo json_encode($reporteDiario);
            break;

        case 11: //ESTADO DE CUENTA POR CLIENTE
            $estadoCuenta = ReportesControlador::ctrObtenerEstadoCuentaCliente($_POST["cliente_id"]);
            echo json_encode($estadoCuenta);
            break;

        case 12: //DETALLE DE CUOTAS POR PRÉSTAMO
            $detalleCuotas = ReportesControlador::ctrObtenerDetalleCuotasPrestamo($_POST["nro_prestamo"]);
            echo json_encode($detalleCuotas);
            break;
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === 'reporte_cobranza_diaria') {
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $reporte = ReportesControlador::ctrReporteCobranzaDiaria($fecha);
    echo json_encode($reporte, JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'reporte_cuotas_atrasadas') {
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $reporte = ReportesControlador::ctrReporteCuotasAtrasadas($fecha);
    echo json_encode($reporte, JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'get_dashboard_kpis') {
    require_once "../controladores/reportes_controlador.php";
    $id_colector = isset($_POST['id_colector']) && !empty($_POST['id_colector']) ? $_POST['id_colector'] : null;
    $kpis = ReportesControlador::ctrGetDashboardKpis($id_colector);
    echo json_encode($kpis, JSON_UNESCAPED_UNICODE);
    exit;
}

/*===================================================================*/
// NUEVAS FUNCIONES DE EXPORTACIÓN PROFESIONAL
/*===================================================================*/

// Exportar historial de cliente a Excel
if (isset($_POST['accion']) && $_POST['accion'] === 'exportar_excel_cliente') {
    exportarExcelCliente($_POST['cliente_id'], $_POST['cliente_nombre']);
    exit;
}

// Exportar historial de cliente a PDF
if (isset($_POST['accion']) && $_POST['accion'] === 'exportar_pdf_cliente') {
    exportarPDFCliente($_POST['cliente_id'], $_POST['cliente_nombre']);
    exit;
}

// Imprimir historial de cliente
if (isset($_GET['accion']) && $_GET['accion'] === 'imprimir_cliente') {
    imprimirCliente($_GET['cliente_id'], $_GET['cliente_nombre']);
    exit;
}

/**
 * Función para exportar historial de cliente a Excel
 */
function exportarExcelCliente($cliente_id, $cliente_nombre) {
    require_once "../vendor/autoload.php";
    
    try {
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte
        $datos = ReportesControlador::ctrReportePorCliente($cliente_id);
        
        // Crear nuevo spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar información de la empresa
        $sheet->setCellValue('A1', $empresa->confi_razon ?? 'Sistema de Préstamos');
        $sheet->setCellValue('A2', 'RUC: ' . ($empresa->confi_ruc ?? 'No configurado'));
        $sheet->setCellValue('A3', $empresa->confi_direccion ?? 'Dirección no configurada');
        $sheet->setCellValue('A4', 'Teléfono: ' . ($empresa->config_celular ?? 'No configurado'));
        
        // Título del reporte
        $sheet->setCellValue('A6', 'HISTORIAL DE PRÉSTAMOS DEL CLIENTE');
        $sheet->setCellValue('A7', 'Cliente: ' . $cliente_nombre);
        $sheet->setCellValue('A8', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
        
        // Encabezados de la tabla
        $row = 10;
        $headers = ['N° Préstamo', 'Cliente', 'Fecha Apertura', 'Fecha Vencimiento', 'Monto', 'Estado', 'Saldo Pendiente'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        
        // Aplicar estilos a los encabezados
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '366092']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        // Llenar datos
        $row++;
        $totalMonto = 0;
        $totalSaldo = 0;
        
        if (!empty($datos)) {
            foreach ($datos as $item) {
                $monto = floatval($item['monto_prestamo'] ?? 0);
                $saldo = floatval($item['saldo_pendiente'] ?? 0);
                
                $sheet->setCellValue('A' . $row, $item['nro_prestamo'] ?? '');
                $sheet->setCellValue('B' . $row, $item['cliente_nombres'] ?? '');
                $sheet->setCellValue('C' . $row, $item['fecha_apertura'] ?? $item['femision'] ?? '');
                $sheet->setCellValue('D' . $row, $item['fecha_vencimiento'] ?? 'No calculada');
                $sheet->setCellValue('E' . $row, $monto);
                $sheet->setCellValue('F' . $row, $item['estado'] ?? '');
                $sheet->setCellValue('G' . $row, $saldo);
                
                $totalMonto += $monto;
                $totalSaldo += $saldo;
                $row++;
            }
        }
        
        // Totales
        $row++;
        $sheet->setCellValue('A' . $row, 'TOTALES:');
        $sheet->setCellValue('E' . $row, $totalMonto);
        $sheet->setCellValue('G' . $row, $totalSaldo);
        
        // Aplicar estilos a los totales
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => 'EEEEEE']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        // Ajustar ancho de columnas
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generar archivo
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Historial_Cliente_" . str_replace([' ', '.', ','], '_', $cliente_nombre) . "_" . date('Y-m-d') . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        
    } catch (Exception $e) {
        error_log("Error generando Excel: " . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        echo "Error al generar el archivo Excel";
    }
}

/**
 * Función para exportar historial de cliente a PDF
 */
function exportarPDFCliente($cliente_id, $cliente_nombre) {
    require_once "../MPDF/vendor/autoload.php";
    
    try {
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte
        $datos = ReportesControlador::ctrReportePorCliente($cliente_id);
        
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20
        ]);
        
        // Configurar logo
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        // Generar HTML
        $html = generarHTMLHistorialCliente($empresa, $cliente_nombre, $datos, $logoPath);
        
        // Escribir HTML al PDF
        $mpdf->WriteHTML($html);
        
        // Configurar nombre del archivo
        $filename = "Historial_Cliente_" . str_replace([' ', '.', ','], '_', $cliente_nombre) . "_" . date('Y-m-d') . ".pdf";
        
        // Configurar headers para descarga
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Enviar PDF
        $mpdf->Output($filename, 'D');
        
    } catch (Exception $e) {
        error_log("Error generando PDF: " . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        echo "Error al generar el archivo PDF";
    }
}

/**
 * Función para imprimir historial de cliente
 */
function imprimirCliente($cliente_id, $cliente_nombre) {
    try {
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte
        $datos = ReportesControlador::ctrReportePorCliente($cliente_id);
        
        // Configurar logo
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        // Generar HTML para impresión
        $html = generarHTMLHistorialCliente($empresa, $cliente_nombre, $datos, $logoPath, true);
        
        // Mostrar HTML
        echo $html;
        
    } catch (Exception $e) {
        error_log("Error generando reporte de impresión: " . $e->getMessage());
        echo "<h3>Error al generar el reporte</h3>";
    }
}

/**
 * Función auxiliar para generar HTML del historial
 */
function generarHTMLHistorialCliente($empresa, $cliente_nombre, $datos, $logoPath, $paraImpresion = false) {
    $fecha_actual = date('d/m/Y H:i:s');
    $totalMonto = 0;
    $totalSaldo = 0;
    
    // Calcular totales
    if (!empty($datos)) {
        foreach ($datos as $item) {
            $totalMonto += floatval($item['monto_prestamo'] ?? 0);
            $totalSaldo += floatval($item['saldo_pendiente'] ?? 0);
        }
    }
    
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Historial del Cliente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-height: 60px; margin-bottom: 10px; }
        .empresa-info { font-size: 12px; color: #666; margin-bottom: 20px; }
        .titulo { font-size: 18px; font-weight: bold; color: #333; margin: 20px 0; }
        .cliente-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #366092; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .totales { background-color: #e9ecef !important; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 11px; }
        .badge-success { background: #28a745; color: white; }
        .badge-warning { background: #ffc107; color: #212529; }
        .footer { margin-top: 30px; font-size: 10px; color: #666; text-align: center; }
        ' . ($paraImpresion ? '@media print { body { margin: 0; } .no-print { display: none; } }' : '') . '
    </style>
</head>
<body>
    <div class="header">
        <img src="' . $logoPath . '" class="logo" alt="Logo">
        <h2>' . ($empresa->confi_razon ?? 'Sistema de Préstamos') . '</h2>
        <div class="empresa-info">
            RUC: ' . ($empresa->confi_ruc ?? 'No configurado') . ' | 
            ' . ($empresa->confi_direccion ?? 'Dirección no configurada') . ' | 
            Teléfono: ' . ($empresa->config_celular ?? 'No configurado') . '
        </div>
    </div>
    
    <div class="titulo text-center">HISTORIAL DE PRÉSTAMOS DEL CLIENTE</div>
    
    <div class="cliente-info">
        <strong>Cliente:</strong> ' . htmlspecialchars($cliente_nombre) . '<br>
        <strong>Fecha de generación:</strong> ' . $fecha_actual . '
    </div>
    
    <table>
        <thead>
            <tr>
                <th>N° Préstamo</th>
                <th>Cliente</th>
                <th>Fecha Apertura</th>
                <th>Fecha Vencimiento</th>
                <th class="text-right">Monto</th>
                <th class="text-center">Estado</th>
                <th class="text-right">Saldo Pendiente</th>
            </tr>
        </thead>
        <tbody>';

    if (!empty($datos)) {
        foreach ($datos as $item) {
            $estado_class = ($item['estado'] === 'Pagado' || $item['estado'] === 'finalizado') ? 'badge-success' : 
                           ($item['estado'] === 'aprobado' ? 'badge-info' : 'badge-warning');
            
            // Obtener símbolo de moneda
            $simboloMoneda = $item['moneda_simbolo'] ?? 'C$';
            
            $html .= '<tr>
                <td>' . htmlspecialchars($item['nro_prestamo'] ?? '') . '</td>
                <td>' . htmlspecialchars($item['cliente_nombres'] ?? '') . '</td>
                <td>' . htmlspecialchars($item['fecha_apertura'] ?? $item['femision'] ?? '') . '</td>
                <td>' . htmlspecialchars($item['fecha_vencimiento'] ?? 'No calculada') . '</td>
                <td class="text-right">' . $simboloMoneda . ' ' . number_format(floatval($item['monto_prestamo'] ?? 0), 2) . '</td>
                <td class="text-center"><span class="badge ' . $estado_class . '">' . htmlspecialchars($item['estado'] ?? '') . '</span></td>
                <td class="text-right">' . $simboloMoneda . ' ' . number_format(floatval($item['saldo_pendiente'] ?? 0), 2) . '</td>
            </tr>';
        }
    } else {
        $html .= '<tr><td colspan="7" class="text-center">No se encontraron préstamos para este cliente</td></tr>';
    }

    // Obtener símbolo de moneda del primer elemento para totales
    $simboloMonedaTotales = 'C$';
    if (!empty($datos) && isset($datos[0]['moneda_simbolo'])) {
        $simboloMonedaTotales = $datos[0]['moneda_simbolo'];
    }
    
    $html .= '<tr class="totales">
                <td colspan="4"><strong>TOTALES:</strong></td>
                <td class="text-right"><strong>' . $simboloMonedaTotales . ' ' . number_format($totalMonto, 2) . '</strong></td>
                <td></td>
                <td class="text-right"><strong>' . $simboloMonedaTotales . ' ' . number_format($totalSaldo, 2) . '</strong></td>
            </tr>
        </tbody>
    </table>
    
    <div class="footer">
        Reporte generado el ' . $fecha_actual . ' por el Sistema de Préstamos ' . ($empresa->confi_razon ?? '') . '
    </div>
    
    ' . ($paraImpresion ? '<script>window.addEventListener("load", function() { window.print(); });</script>' : '') . '
</body>
</html>';

    return $html;
}

/*===================================================================*/
// NUEVAS FUNCIONES DE EXPORTACIÓN PARA REPORTE DIARIO
/*===================================================================*/

// Exportar reporte diario a Excel
if (isset($_POST['accion']) && $_POST['accion'] === 'exportar_excel_reporte_diario') {
    exportarExcelReporteDiario($_POST['fecha']);
    exit;
}

// Exportar reporte diario a PDF
if (isset($_POST['accion']) && $_POST['accion'] === 'exportar_pdf_reporte_diario') {
    exportarPDFReporteDiario($_POST['fecha']);
    exit;
}

// Imprimir reporte diario
if (isset($_GET['accion']) && $_GET['accion'] === 'imprimir_reporte_diario') {
    imprimirReporteDiario($_GET['fecha']);
    exit;
}

// Enviar reporte diario por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviar_correo_reporte_diario') {
    enviarCorreoReporteDiario($_POST['fecha'], $_POST['email_destino'], $_POST['asunto'], $_POST['mensaje']);
    exit;
}

// Enviar reporte de cliente por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviar_correo_reporte_cliente') {
    enviarCorreoReporteCliente($_POST['cliente_id'], $_POST['cliente_nombre'], $_POST['email_destino'], $_POST['asunto'], $_POST['mensaje']);
    exit;
}

// Enviar reporte de cobranza por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviar_correo_reporte_cobranza') {
    enviarCorreoReporteCobranza($_POST['fecha'], $_POST['sucursal_id'], $_POST['email_destino'], $_POST['asunto'], $_POST['mensaje']);
    exit;
}

// Enviar reporte de cuotas atrasadas por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviar_correo_reporte_atrasos') {
    enviarCorreoReporteAtrasos($_POST['fecha'], $_POST['sucursal_id'], $_POST['email_destino'], $_POST['asunto'], $_POST['mensaje']);
    exit;
}

// Enviar reporte de recuperación por correo
if (isset($_POST['accion']) && $_POST['accion'] === 'enviar_correo_reporte_recuperacion') {
    enviarCorreoReporteRecuperacion($_POST['fecha_inicial'], $_POST['fecha_final'], $_POST['moneda_filtro'], $_POST['sucursal_id'], $_POST['email_destino'], $_POST['asunto'], $_POST['mensaje']);
    exit;
}

/**
 * Función para exportar reporte diario a Excel
 */
function exportarExcelReporteDiario($fecha) {
    require_once "../vendor/autoload.php";
    
    try {
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte
        $datos = ReportesControlador::ctrObtenerReporteDiario($fecha);
        
        // Crear nuevo spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Configurar información de la empresa
        $sheet->setCellValue('A1', $empresa->confi_razon ?? 'Sistema de Préstamos');
        $sheet->setCellValue('A2', 'RUC: ' . ($empresa->confi_ruc ?? 'No configurado'));
        $sheet->setCellValue('A3', $empresa->confi_direccion ?? 'Dirección no configurada');
        $sheet->setCellValue('A4', 'Teléfono: ' . ($empresa->config_celular ?? 'No configurado'));
        
        // Título del reporte
        $sheet->setCellValue('A6', 'REPORTE DIARIO DE INGRESOS Y EGRESOS');
        $sheet->setCellValue('A7', 'Fecha: ' . date('d/m/Y', strtotime($fecha)));
        $sheet->setCellValue('A8', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
        
        // Encabezados de la tabla
        $row = 10;
        $headers = ['Tipo Operación', 'Cantidad', 'Monto Capital', 'Monto Interés', 'Monto Total', 'Símbolo Moneda', 'Nombre Moneda'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $row, $header);
            $col++;
        }
        
        // Aplicar estilos a los encabezados
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '366092']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ]);
        
        // Llenar datos
        $row++;
        if (!empty($datos)) {
            foreach ($datos as $item) {
                $sheet->setCellValue('A' . $row, $item['tipo_operacion'] ?? '');
                $sheet->setCellValue('B' . $row, $item['cantidad'] ?? 0);
                $sheet->setCellValue('C' . $row, floatval($item['monto_capital'] ?? 0));
                $sheet->setCellValue('D' . $row, floatval($item['monto_interes'] ?? 0));
                $sheet->setCellValue('E' . $row, floatval($item['monto_total'] ?? 0));
                $sheet->setCellValue('F' . $row, $item['moneda_simbolo'] ?? '');
                $sheet->setCellValue('G' . $row, $item['moneda_nombre'] ?? '');
                $row++;
            }
        }
        
        // Ajustar ancho de columnas
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Generar archivo
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $filename = "Reporte_Diario_" . $fecha . ".xlsx";
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        
    } catch (Exception $e) {
        error_log("Error generando Excel reporte diario: " . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        echo "Error al generar el archivo Excel";
    }
}

/**
 * Función para exportar reporte diario a PDF
 */
function exportarPDFReporteDiario($fecha) {
    require_once "../MPDF/vendor/autoload.php";
    
    try {
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte
        $datos = ReportesControlador::ctrObtenerReporteDiario($fecha);
        
        // Crear instancia de mPDF
        $mpdf = new \Mpdf\Mpdf([
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20
        ]);
        
        // Configurar logo
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        // Generar HTML
        $html = generarHTMLReporteDiario($empresa, $fecha, $datos, $logoPath);
        
        // Escribir HTML al PDF
        $mpdf->WriteHTML($html);
        
        // Configurar nombre del archivo
        $filename = "Reporte_Diario_" . $fecha . ".pdf";
        
        // Configurar headers para descarga
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');
        
        // Enviar PDF
        $mpdf->Output($filename, 'D');
        
    } catch (Exception $e) {
        error_log("Error generando PDF reporte diario: " . $e->getMessage());
        header('HTTP/1.1 500 Internal Server Error');
        echo "Error al generar el archivo PDF";
    }
}

/**
 * Función para imprimir reporte diario
 */
function imprimirReporteDiario($fecha) {
    try {
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte
        $datos = ReportesControlador::ctrObtenerReporteDiario($fecha);
        
        // Configurar logo
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        // Generar HTML para impresión
        $html = generarHTMLReporteDiario($empresa, $fecha, $datos, $logoPath, true);
        
        // Mostrar HTML
        echo $html;
        
    } catch (Exception $e) {
        error_log("Error generando reporte diario de impresión: " . $e->getMessage());
        echo "<h3>Error al generar el reporte</h3>";
    }
}

/**
 * Función para enviar reporte diario por correo
 */
function enviarCorreoReporteDiario($fecha, $email_destino, $asunto, $mensaje) {
    require_once "../PHPMailer/src/PHPMailer.php";
    require_once "../PHPMailer/src/SMTP.php";
    require_once "../PHPMailer/src/Exception.php";
    require_once "../utilitarios/email_config.php";
    
    try {
        // Verificar configuración de correo
        if (EMAIL_ACTIVO !== true || SMTP_HOST === 'smtp.example.com') {
            throw new Exception('El correo electrónico no está configurado. Consulte con el administrador.');
        }
        
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte para adjuntar
        $datos = ReportesControlador::ctrObtenerReporteDiario($fecha);
        
        // Configurar PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Configurar destinatarios
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
        $mail->addAddress($email_destino);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        
        $cuerpo_mensaje = "
        <h2>Reporte Diario - " . date('d/m/Y', strtotime($fecha)) . "</h2>
        <p><strong>Empresa:</strong> " . ($empresa->confi_razon ?? 'Sistema de Préstamos') . "</p>
        <p><strong>Fecha del reporte:</strong> " . date('d/m/Y', strtotime($fecha)) . "</p>
        ";
        
        if (!empty($mensaje)) {
            $cuerpo_mensaje .= "<p><strong>Mensaje:</strong> " . nl2br(htmlspecialchars($mensaje)) . "</p>";
        }
        
        $cuerpo_mensaje .= "<p>El reporte se adjunta en formato PDF.</p>";
        
        $mail->Body = $cuerpo_mensaje;
        
        // Generar PDF adjunto
        require_once "../MPDF/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf();
        
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        $html = generarHTMLReporteDiario($empresa, $fecha, $datos, $logoPath);
        $mpdf->WriteHTML($html);
        
        $pdf_content = $mpdf->Output('', 'S');
        $mail->addStringAttachment($pdf_content, "Reporte_Diario_" . $fecha . ".pdf");
        
        $mail->send();
        
        echo json_encode([
            'success' => true,
            'mensaje' => 'Correo enviado exitosamente'
        ]);
        
    } catch (Exception $e) {
        error_log("Error enviando correo reporte diario: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al enviar el correo: ' . $e->getMessage()
        ]);
    }
}

/**
 * Función auxiliar para generar HTML del reporte diario
 */
function generarHTMLReporteDiario($empresa, $fecha, $datos, $logoPath, $paraImpresion = false) {
    $fecha_reporte = date('d/m/Y', strtotime($fecha));
    $fecha_actual = date('d/m/Y H:i:s');
    
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte Diario - ' . $fecha_reporte . '</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-height: 60px; margin-bottom: 10px; }
        .empresa-info { font-size: 12px; color: #666; margin-bottom: 20px; }
        .titulo { font-size: 18px; font-weight: bold; color: #333; margin: 20px 0; }
        .fecha-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
        th { background-color: #366092; color: white; font-weight: bold; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; font-size: 10px; color: #666; text-align: center; }
        ' . ($paraImpresion ? '@media print { body { margin: 0; } .no-print { display: none; } }' : '') . '
    </style>
</head>
<body>
    <div class="header">
        <img src="' . $logoPath . '" class="logo" alt="Logo">
        <h2>' . ($empresa->confi_razon ?? 'Sistema de Préstamos') . '</h2>
        <div class="empresa-info">
            RUC: ' . ($empresa->confi_ruc ?? 'No configurado') . ' | 
            ' . ($empresa->confi_direccion ?? 'Dirección no configurada') . ' | 
            Teléfono: ' . ($empresa->config_celular ?? 'No configurado') . '
        </div>
    </div>
    
    <div class="titulo text-center">REPORTE DIARIO DE INGRESOS Y EGRESOS</div>
    
    <div class="fecha-info">
        <strong>Fecha del reporte:</strong> ' . $fecha_reporte . '<br>
        <strong>Fecha de generación:</strong> ' . $fecha_actual . '
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Tipo Operación</th>
                <th class="text-center">Cantidad</th>
                <th class="text-right">Monto Capital</th>
                <th class="text-right">Monto Interés</th>
                <th class="text-right">Monto Total</th>
                <th class="text-center">Moneda</th>
            </tr>
        </thead>
        <tbody>';

    if (!empty($datos)) {
        foreach ($datos as $item) {
            $html .= '<tr>
                <td>' . htmlspecialchars($item['tipo_operacion'] ?? '') . '</td>
                <td class="text-center">' . ($item['cantidad'] ?? 0) . '</td>
                <td class="text-right">' . ($item['moneda_simbolo'] ?? '') . ' ' . number_format(floatval($item['monto_capital'] ?? 0), 2) . '</td>
                <td class="text-right">' . ($item['moneda_simbolo'] ?? '') . ' ' . number_format(floatval($item['monto_interes'] ?? 0), 2) . '</td>
                <td class="text-right">' . ($item['moneda_simbolo'] ?? '') . ' ' . number_format(floatval($item['monto_total'] ?? 0), 2) . '</td>
                <td class="text-center">' . htmlspecialchars($item['moneda_nombre'] ?? '') . '</td>
            </tr>';
        }
    } else {
        $html .= '<tr><td colspan="6" class="text-center">No se encontraron movimientos para esta fecha</td></tr>';
    }

    $html .= '</tbody>
    </table>
    
    <div class="footer">
        Reporte generado el ' . $fecha_actual . ' por el Sistema de Préstamos ' . ($empresa->confi_razon ?? '') . '
    </div>
    
    ' . ($paraImpresion ? '<script>window.addEventListener("load", function() { window.print(); });</script>' : '') . '
</body>
</html>';

    return $html;
}

/**
 * Función para enviar reporte de cliente por correo
 */
function enviarCorreoReporteCliente($cliente_id, $cliente_nombre, $email_destino, $asunto, $mensaje) {
    require_once "../PHPMailer/src/PHPMailer.php";
    require_once "../PHPMailer/src/SMTP.php";
    require_once "../PHPMailer/src/Exception.php";
    require_once "../utilitarios/email_config.php";
    
    try {
        // Verificar configuración de correo
        if (EMAIL_ACTIVO !== true || SMTP_HOST === 'smtp.example.com') {
            throw new Exception('El correo electrónico no está configurado. Consulte con el administrador.');
        }
        
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte para adjuntar
        $datos = ReportesControlador::ctrReportePorCliente($cliente_id);
        
        // Configurar PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Configurar destinatarios
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
        $mail->addAddress($email_destino);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        
        $cuerpo_mensaje = "
        <h2>Historial del Cliente: " . htmlspecialchars($cliente_nombre) . "</h2>
        <p><strong>Empresa:</strong> " . ($empresa->confi_razon ?? 'Sistema de Préstamos') . "</p>
        <p><strong>Cliente:</strong> " . htmlspecialchars($cliente_nombre) . "</p>
        <p><strong>Fecha de generación:</strong> " . date('d/m/Y H:i:s') . "</p>
        ";
        
        if (!empty($mensaje)) {
            $cuerpo_mensaje .= "<p><strong>Mensaje:</strong> " . nl2br(htmlspecialchars($mensaje)) . "</p>";
        }
        
        $cuerpo_mensaje .= "<p>El historial del cliente se adjunta en formato PDF.</p>";
        
        $mail->Body = $cuerpo_mensaje;
        
        // Generar PDF adjunto
        require_once "../MPDF/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf();
        
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        $html = generarHTMLHistorialCliente($empresa, $cliente_nombre, $datos, $logoPath);
        $mpdf->WriteHTML($html);
        
        $pdf_content = $mpdf->Output('', 'S');
        $mail->addStringAttachment($pdf_content, "Historial_Cliente_" . str_replace([' ', '.', ','], '_', $cliente_nombre) . "_" . date('Y-m-d') . ".pdf");
        
        $mail->send();
        
        echo json_encode([
            'success' => true,
            'mensaje' => 'Correo enviado exitosamente'
        ]);
        
    } catch (Exception $e) {
        error_log("Error enviando correo reporte cliente: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al enviar el correo: ' . $e->getMessage()
        ]);
    }
}

/**
 * Función para enviar reporte de cobranza por correo
 */
function enviarCorreoReporteCobranza($fecha, $sucursal_id, $email_destino, $asunto, $mensaje) {
    require_once "../PHPMailer/src/PHPMailer.php";
    require_once "../PHPMailer/src/SMTP.php";
    require_once "../PHPMailer/src/Exception.php";
    require_once "../utilitarios/email_config.php";
    
    try {
        // Verificar configuración de correo
        if (EMAIL_ACTIVO !== true || SMTP_HOST === 'smtp.example.com') {
            throw new Exception('El correo electrónico no está configurado. Consulte con el administrador.');
        }
        
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte de cobranza
        $datos = ReportesControlador::ctrReporteCobranzaDiaria($fecha);
        
        // Configurar PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Configurar destinatarios
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
        $mail->addAddress($email_destino);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        
        $sucursal_nombre = "Todas las sucursales";
        if (!empty($sucursal_id)) {
            // Obtener nombre de sucursal (opcional - implementar si es necesario)
            $sucursal_nombre = "Sucursal ID: " . $sucursal_id;
        }
        
        $cuerpo_mensaje = "
        <h2>Reporte de Cobranza Diaria</h2>
        <p><strong>Empresa:</strong> " . ($empresa->confi_razon ?? 'Sistema de Préstamos') . "</p>
        <p><strong>Fecha:</strong> " . date('d/m/Y', strtotime($fecha)) . "</p>
        <p><strong>Sucursal:</strong> " . htmlspecialchars($sucursal_nombre) . "</p>
        <p><strong>Fecha de generación:</strong> " . date('d/m/Y H:i:s') . "</p>
        ";
        
        if (!empty($mensaje)) {
            $cuerpo_mensaje .= "<p><strong>Mensaje:</strong> " . nl2br(htmlspecialchars($mensaje)) . "</p>";
        }
        
        // Calcular resumen del reporte
        if (is_array($datos) && count($datos) > 0) {
            $total_cobrado = array_sum(array_column($datos, 'monto_pago'));
            $cuotas_cobradas = count($datos);
            $clientes_unicos = count(array_unique(array_column($datos, 'cliente_nombre')));
            
            $cuerpo_mensaje .= "
            <h3>Resumen de Cobranza</h3>
            <ul>
                <li><strong>Total Cobrado:</strong> C$ " . number_format($total_cobrado, 2) . "</li>
                <li><strong>Cuotas Cobradas:</strong> " . $cuotas_cobradas . "</li>
                <li><strong>Clientes Atendidos:</strong> " . $clientes_unicos . "</li>
            </ul>
            ";
        }
        
        $cuerpo_mensaje .= "<p>El reporte de cobranza se adjunta en formato PDF.</p>";
        
        $mail->Body = $cuerpo_mensaje;
        
        // Generar PDF adjunto
        require_once "../MPDF/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf();
        
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        $html = generarHTMLReporteCobranza($empresa, $fecha, $sucursal_nombre, $datos, $logoPath);
        $mpdf->WriteHTML($html);
        
        $pdf_content = $mpdf->Output('', 'S');
        $mail->addStringAttachment($pdf_content, "Reporte_Cobranza_" . str_replace('-', '_', $fecha) . ".pdf");
        
        $mail->send();
        
        echo json_encode([
            'success' => true,
            'mensaje' => 'Correo enviado exitosamente'
        ]);
        
    } catch (Exception $e) {
        error_log("Error enviando correo reporte cobranza: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al enviar el correo: ' . $e->getMessage()
        ]);
    }
}

/**
 * Función para generar HTML del reporte de cobranza
 */
function generarHTMLReporteCobranza($empresa, $fecha, $sucursal_nombre, $datos, $logoPath) {
    $logoData = '';
    if (file_exists($logoPath)) {
        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
    
    $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 100px; max-height: 100px; }
        .empresa-info { margin: 10px 0; }
        .reporte-titulo { color: #333; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .total-row { background-color: #e8f5e8; font-weight: bold; }
        .resumen { background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">';
    
    if ($logoData) {
        $html .= '<img src="' . $logoData . '" alt="Logo" class="logo"><br>';
    }
    
    $html .= '
        <div class="empresa-info">
            <h2>' . ($empresa->confi_razon ?? 'Sistema de Préstamos') . '</h2>
            <p>RUC: ' . ($empresa->confi_ruc ?? '') . '</p>
            <p>' . ($empresa->confi_direccion ?? '') . '</p>
        </div>
    </div>
    
    <h2 class="reporte-titulo">REPORTE DE COBRANZA DIARIA</h2>
    
    <div class="resumen">
        <p><strong>Fecha:</strong> ' . date('d/m/Y', strtotime($fecha)) . '</p>
        <p><strong>Sucursal:</strong> ' . htmlspecialchars($sucursal_nombre) . '</p>
        <p><strong>Generado el:</strong> ' . date('d/m/Y H:i:s') . '</p>
    </div>';
    
    if (is_array($datos) && count($datos) > 0) {
        $total_cobrado = array_sum(array_column($datos, 'monto_pago'));
        $cuotas_cobradas = count($datos);
        $clientes_unicos = count(array_unique(array_column($datos, 'cliente_nombre')));
        
        $html .= '
        <div class="resumen">
            <h3>Resumen de Cobranza</h3>
            <p><strong>Total Cobrado:</strong> C$ ' . number_format($total_cobrado, 2) . '</p>
            <p><strong>Cuotas Cobradas:</strong> ' . $cuotas_cobradas . '</p>
            <p><strong>Clientes Atendidos:</strong> ' . $clientes_unicos . '</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Cliente</th>
                    <th>Préstamo</th>
                    <th>N° Cuota</th>
                    <th>Monto</th>
                    <th>Cobrador</th>
                    <th>Sucursal</th>
                    <th>Tipo Pago</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($datos as $fila) {
            $html .= '<tr>
                <td>' . (isset($fila['hora_pago']) ? $fila['hora_pago'] : '-') . '</td>
                <td>' . htmlspecialchars($fila['cliente_nombre'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['nro_prestamo'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['nro_cuota'] ?? '') . '</td>
                <td>C$ ' . number_format(floatval($fila['monto_pago'] ?? 0), 2) . '</td>
                <td>' . htmlspecialchars($fila['cobrador'] ?? 'N/A') . '</td>
                <td>' . htmlspecialchars($fila['sucursal'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['tipo_pago'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['estado'] ?? '') . '</td>
            </tr>';
        }
        
        $html .= '
            <tr class="total-row">
                <td colspan="4"><strong>TOTAL GENERAL</strong></td>
                <td><strong>C$ ' . number_format($total_cobrado, 2) . '</strong></td>
                <td colspan="4"><strong>' . $cuotas_cobradas . ' cuotas</strong></td>
            </tr>
            </tbody>
        </table>';
    } else {
        $html .= '<p><em>No se registraron cobros para la fecha seleccionada.</em></p>';
    }
    
    $html .= '
</body>
</html>';

    return $html;
}

/**
 * Función para enviar reporte de cuotas atrasadas por correo
 */
function enviarCorreoReporteAtrasos($fecha, $sucursal_id, $email_destino, $asunto, $mensaje) {
    require_once "../PHPMailer/src/PHPMailer.php";
    require_once "../PHPMailer/src/SMTP.php";
    require_once "../PHPMailer/src/Exception.php";
    require_once "../utilitarios/email_config.php";
    
    try {
        // Verificar configuración de correo
        if (EMAIL_ACTIVO !== true || SMTP_HOST === 'smtp.example.com') {
            throw new Exception('El correo electrónico no está configurado. Consulte con el administrador.');
        }
        
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte de cuotas atrasadas
        $datos = ReportesControlador::ctrReporteCuotasAtrasadas($fecha);
        
        // Configurar PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Configurar destinatarios
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
        $mail->addAddress($email_destino);
        
        // Contenido del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        
        $sucursal_nombre = "Todas las sucursales";
        if (!empty($sucursal_id)) {
            $sucursal_nombre = "Sucursal ID: " . $sucursal_id;
        }
        
        $cuerpo_mensaje = "
        <h2>Reporte de Cuotas Atrasadas</h2>
        <p><strong>Empresa:</strong> " . ($empresa->confi_razon ?? 'Sistema de Préstamos') . "</p>
        <p><strong>Fecha de corte:</strong> " . date('d/m/Y', strtotime($fecha)) . "</p>
        <p><strong>Sucursal:</strong> " . htmlspecialchars($sucursal_nombre) . "</p>
        <p><strong>Fecha de generación:</strong> " . date('d/m/Y H:i:s') . "</p>
        ";
        
        if (!empty($mensaje)) {
            $cuerpo_mensaje .= "<p><strong>Mensaje:</strong> " . nl2br(htmlspecialchars($mensaje)) . "</p>";
        }
        
        // Calcular resumen del reporte
        if (is_array($datos) && count($datos) > 0) {
            $total_atrasado = array_sum(array_column($datos, 'monto_atrasado'));
            $cuotas_atrasadas = count($datos);
            $clientes_unicos = count(array_unique(array_column($datos, 'cliente_nombre')));
            
            // Calcular estadísticas por nivel de atraso
            $atrasos_leves = count(array_filter($datos, function($item) { return $item['dias_atraso'] <= 30; }));
            $atrasos_moderados = count(array_filter($datos, function($item) { return $item['dias_atraso'] > 30 && $item['dias_atraso'] <= 90; }));
            $atrasos_criticos = count(array_filter($datos, function($item) { return $item['dias_atraso'] > 90; }));
            
            $cuerpo_mensaje .= "
            <h3>Resumen de Cuotas Atrasadas</h3>
            <ul>
                <li><strong>Total Atrasado:</strong> C$ " . number_format($total_atrasado, 2) . "</li>
                <li><strong>Cuotas Atrasadas:</strong> " . $cuotas_atrasadas . "</li>
                <li><strong>Clientes Afectados:</strong> " . $clientes_unicos . "</li>
            </ul>
            <h4>Niveles de Atraso:</h4>
            <ul>
                <li>🟡 <strong>Atrasos Leves (1-30 días):</strong> " . $atrasos_leves . " cuotas</li>
                <li>🟠 <strong>Atrasos Moderados (31-90 días):</strong> " . $atrasos_moderados . " cuotas</li>
                <li>🔴 <strong>Atrasos Críticos (+90 días):</strong> " . $atrasos_criticos . " cuotas</li>
            </ul>
            ";
        }
        
        $cuerpo_mensaje .= "<p>El reporte de cuotas atrasadas se adjunta en formato PDF.</p>";
        
        $mail->Body = $cuerpo_mensaje;
        
        // Generar PDF adjunto
        require_once "../MPDF/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf();
        
        $logoPath = '';
        if (!empty($empresa->config_logo) && file_exists("../uploads/logos/" . $empresa->config_logo)) {
            $logoPath = "../uploads/logos/" . $empresa->config_logo;
        } else {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        $html = generarHTMLReporteAtrasos($empresa, $fecha, $sucursal_nombre, $datos, $logoPath);
        $mpdf->WriteHTML($html);
        
        $pdf_content = $mpdf->Output('', 'S');
        $mail->addStringAttachment($pdf_content, "Reporte_Cuotas_Atrasadas_" . str_replace('-', '_', $fecha) . ".pdf");
        
        $mail->send();
        
        echo json_encode([
            'success' => true,
            'mensaje' => 'Correo enviado exitosamente'
        ]);
        
    } catch (Exception $e) {
        error_log("Error enviando correo reporte atrasos: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'mensaje' => 'Error al enviar el correo: ' . $e->getMessage()
        ]);
    }
}

/**
 * Función para generar HTML del reporte de cuotas atrasadas
 */
function generarHTMLReporteAtrasos($empresa, $fecha, $sucursal_nombre, $datos, $logoPath) {
    $logoData = '';
    if (file_exists($logoPath)) {
        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
    
    $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .logo { max-width: 100px; max-height: 100px; }
        .empresa-info { margin: 10px 0; }
        .reporte-titulo { color: #333; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #dc3545; color: white; font-weight: bold; }
        .total-row { background-color: #f8d7da; font-weight: bold; }
        .resumen { background-color: #f9f9f9; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .nivel-leve { background-color: #fff3cd; }
        .nivel-moderado { background-color: #fde7e7; }
        .nivel-critico { background-color: #f8d7da; }
    </style>
</head>
<body>
    <div class="header">';
    
    if ($logoData) {
        $html .= '<img src="' . $logoData . '" alt="Logo" class="logo"><br>';
    }
    
    $html .= '
        <div class="empresa-info">
            <h2>' . ($empresa->confi_razon ?? 'Sistema de Préstamos') . '</h2>
            <p>RUC: ' . ($empresa->confi_ruc ?? '') . '</p>
            <p>' . ($empresa->confi_direccion ?? '') . '</p>
        </div>
    </div>
    
    <h2 class="reporte-titulo">REPORTE DE CUOTAS ATRASADAS</h2>
    
    <div class="resumen">
        <p><strong>Fecha de corte:</strong> ' . date('d/m/Y', strtotime($fecha)) . '</p>
        <p><strong>Sucursal:</strong> ' . htmlspecialchars($sucursal_nombre) . '</p>
        <p><strong>Generado el:</strong> ' . date('d/m/Y H:i:s') . '</p>
    </div>';
    
    if (is_array($datos) && count($datos) > 0) {
        $total_atrasado = array_sum(array_column($datos, 'monto_atrasado'));
        $cuotas_atrasadas = count($datos);
        $clientes_unicos = count(array_unique(array_column($datos, 'cliente_nombre')));
        
        // Calcular estadísticas por nivel
        $atrasos_leves = count(array_filter($datos, function($item) { return $item['dias_atraso'] <= 30; }));
        $atrasos_moderados = count(array_filter($datos, function($item) { return $item['dias_atraso'] > 30 && $item['dias_atraso'] <= 90; }));
        $atrasos_criticos = count(array_filter($datos, function($item) { return $item['dias_atraso'] > 90; }));
        
        $html .= '
        <div class="resumen">
            <h3>Resumen de Cuotas Atrasadas</h3>
            <p><strong>Total Atrasado:</strong> C$ ' . number_format($total_atrasado, 2) . '</p>
            <p><strong>Cuotas Atrasadas:</strong> ' . $cuotas_atrasadas . '</p>
            <p><strong>Clientes Afectados:</strong> ' . $clientes_unicos . '</p>
            <h4>Niveles de Atraso:</h4>
            <p>🟡 <strong>Leves (1-30 días):</strong> ' . $atrasos_leves . ' cuotas</p>
            <p>🟠 <strong>Moderados (31-90 días):</strong> ' . $atrasos_moderados . ' cuotas</p>
            <p>🔴 <strong>Críticos (+90 días):</strong> ' . $atrasos_criticos . ' cuotas</p>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>Préstamo</th>
                    <th>Cuota</th>
                    <th>F. Programada</th>
                    <th>Días Atraso</th>
                    <th>Monto</th>
                    <th>Cobrador</th>
                    <th>Nivel</th>
                </tr>
            </thead>
            <tbody>';
        
        foreach ($datos as $fila) {
            $dias_atraso = intval($fila['dias_atraso'] ?? 0);
            $nivel_class = '';
            $nivel_texto = '';
            
            if ($dias_atraso <= 30) {
                $nivel_class = 'nivel-leve';
                $nivel_texto = '🟡 LEVE';
            } elseif ($dias_atraso <= 90) {
                $nivel_class = 'nivel-moderado';
                $nivel_texto = '🟠 MODERADO';
            } else {
                $nivel_class = 'nivel-critico';
                $nivel_texto = '🔴 CRÍTICO';
            }
            
            $html .= '<tr class="' . $nivel_class . '">
                <td>' . htmlspecialchars($fila['cliente_nombre'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['telefono'] ?? 'N/A') . '</td>
                <td>' . htmlspecialchars($fila['nro_prestamo'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['nro_cuota'] ?? '') . '</td>
                <td>' . htmlspecialchars($fila['fecha_programada'] ?? '') . '</td>
                <td>' . $dias_atraso . ' días</td>
                <td>C$ ' . number_format(floatval($fila['monto_atrasado'] ?? 0), 2) . '</td>
                <td>' . htmlspecialchars($fila['cobrador'] ?? 'Sin asignar') . '</td>
                <td>' . $nivel_texto . '</td>
            </tr>';
        }
        
        $html .= '
            <tr class="total-row">
                <td colspan="6"><strong>TOTAL GENERAL</strong></td>
                <td><strong>C$ ' . number_format($total_atrasado, 2) . '</strong></td>
                <td colspan="2"><strong>' . $cuotas_atrasadas . ' cuotas</strong></td>
            </tr>
            </tbody>
        </table>';
    } else {
        $html .= '<p><em>✅ No se encontraron cuotas atrasadas para la fecha seleccionada.</em></p>';
    }
    
    $html .= '
</body>
</html>';

    return $html;
}

/**
 * Función para enviar reporte de recuperación por correo
 */
function enviarCorreoReporteRecuperacion($fecha_inicial, $fecha_final, $moneda_filtro, $sucursal_id, $email_destino, $asunto, $mensaje) {
    require_once "../PHPMailer/src/PHPMailer.php";
    require_once "../PHPMailer/src/SMTP.php";
    require_once "../PHPMailer/src/Exception.php";
    require_once "../utilitarios/email_config.php";
    
    try {
        // Verificar configuración de correo
        if (EMAIL_ACTIVO !== true || SMTP_HOST === 'smtp.example.com') {
            throw new Exception('El correo electrónico no está configurado. Consulte con el administrador.');
        }
        
        // Obtener datos de la empresa
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        
        // Obtener datos del reporte de recuperación
        $datos = ReportesControlador::ctrObtenerReporteRecuperacion($fecha_inicial, $fecha_final);
        
        // Filtrar por moneda si se especificó
        if (!empty($moneda_filtro) && is_array($datos)) {
            $datos = array_filter($datos, function($item) use ($moneda_filtro) {
                return isset($item['moneda_simbolo']) && strpos($item['moneda_simbolo'], $moneda_filtro) !== false;
            });
        }
        
        // Obtener nombre de sucursal desde los datos
        $sucursal_nombre = "Todas las Sucursales";
        if (!empty($sucursal_id) && is_array($datos) && count($datos) > 0) {
            // Buscar el nombre de sucursal en los primeros datos disponibles
            $primera_sucursal = reset($datos);
            if (isset($primera_sucursal['sucursal_nombre'])) {
                $sucursal_nombre = $primera_sucursal['sucursal_nombre'];
            }
        }
        
        // Configurar PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        // Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        // Destinatarios
        $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
        $mail->addAddress($email_destino);
        
        // Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = nl2br($mensaje);
        
        // Generar PDF adjunto
        require_once "../MPDF/vendor/autoload.php";
        $mpdf = new \Mpdf\Mpdf(['orientation' => 'L', 'format' => 'A4']);
        
        // Buscar logo de la empresa
        $logoPath = "../uploads/logos/" . ($empresa->confi_logo ?? '');
        if (!file_exists($logoPath) || empty($empresa->confi_logo)) {
            $logoPath = "../vistas/assets/img/default-logo.png";
        }
        
        $html = generarHTMLReporteRecuperacion($empresa, $fecha_inicial, $fecha_final, $moneda_filtro, $sucursal_nombre, $datos, $logoPath);
        $mpdf->WriteHTML($html);
        
        $pdf_content = $mpdf->Output('', 'S');
        $filename = "Reporte_Recuperacion_" . str_replace('-', '_', $fecha_inicial) . "_al_" . str_replace('-', '_', $fecha_final) . ".pdf";
        $mail->addStringAttachment($pdf_content, $filename);
        
        $mail->send();
        
        echo json_encode([
            'success' => true,
            'message' => 'Correo enviado exitosamente'
        ]);
        
    } catch (Exception $e) {
        error_log("Error enviando correo reporte recuperación: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'message' => 'Error al enviar el correo: ' . $e->getMessage()
        ]);
    }
}

/**
 * Función para generar HTML del reporte de recuperación
 */
function generarHTMLReporteRecuperacion($empresa, $fecha_inicial, $fecha_final, $moneda_filtro, $sucursal_nombre, $datos, $logoPath) {
    $logoData = '';
    if (file_exists($logoPath)) {
        $logoData = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
    }
    
    $html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #28a745; padding-bottom: 20px; }
        .logo { max-width: 100px; max-height: 100px; margin-bottom: 10px; }
        .empresa-info { margin: 10px 0; }
        .empresa-info h2 { margin: 5px 0; color: #28a745; }
        .empresa-info p { margin: 2px 0; color: #666; }
        .reporte-titulo { color: #28a745; margin: 20px 0; text-align: center; font-size: 18px; font-weight: bold; }
        .filtros-info { background-color: #f8f9fa; padding: 15px; margin: 20px 0; border-radius: 5px; border-left: 5px solid #28a745; }
        .filtros-info h3 { color: #28a745; margin-top: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        th { background-color: #28a745; color: white; font-weight: bold; text-align: center; }
        .total-row { background-color: #d4edda; font-weight: bold; }
        .resumen { background-color: #e8f5e8; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .resumen h3 { color: #28a745; margin-top: 0; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">';
    
    if ($logoData) {
        $html .= '<img src="' . $logoData . '" alt="Logo" class="logo"><br>';
    }
    
    $html .= '
        <div class="empresa-info">
            <h2>' . ($empresa->confi_razon ?? 'Sistema de Préstamos') . '</h2>
            <p><strong>RUC:</strong> ' . ($empresa->confi_ruc ?? '') . '</p>
            <p><strong>Dirección:</strong> ' . ($empresa->confi_direccion ?? '') . '</p>
            <p><strong>Teléfono:</strong> ' . ($empresa->confi_telefono ?? '') . '</p>
        </div>
    </div>
    
    <h2 class="reporte-titulo">📈 REPORTE DE RECUPERACIÓN</h2>
    
    <div class="filtros-info">
        <h3>Información del Reporte</h3>
        <p><strong>📅 Período:</strong> ' . date('d/m/Y', strtotime($fecha_inicial)) . ' al ' . date('d/m/Y', strtotime($fecha_final)) . '</p>
        <p><strong>🏢 Sucursal:</strong> ' . htmlspecialchars($sucursal_nombre) . '</p>';
        
    if (!empty($moneda_filtro)) {
        $html .= '<p><strong>💰 Moneda:</strong> ' . htmlspecialchars($moneda_filtro) . '</p>';
    }
    
    $html .= '
        <p><strong>📋 Generado el:</strong> ' . date('d/m/Y H:i:s') . '</p>
    </div>';
    
    if (is_array($datos) && count($datos) > 0) {
        $total_recuperado = array_sum(array_column($datos, 'pago_monto'));
        $cuotas_recuperadas = count($datos);
        $clientes_unicos = count(array_unique(array_column($datos, 'cliente_nombres')));
        $prestamos_unicos = count(array_unique(array_column($datos, 'nro_prestamo')));
        
        $html .= '
        <div class="resumen">
            <h3>📊 Resumen de Recuperaciones</h3>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p><strong>💰 Total Recuperado:</strong> C$ ' . number_format($total_recuperado, 2) . '</p>
                    <p><strong>🔢 Cuotas Recuperadas:</strong> ' . $cuotas_recuperadas . '</p>
                </div>
                <div>
                    <p><strong>👥 Clientes que Pagaron:</strong> ' . $clientes_unicos . '</p>
                    <p><strong>📄 Préstamos Involucrados:</strong> ' . $prestamos_unicos . '</p>
                </div>
            </div>
        </div>
        
                 <table>
             <thead>
                 <tr>
                     <th>Cliente</th>
                     <th>Nro. Préstamo</th>
                     <th>Nro. Cuota</th>
                     <th>Monto Pagado</th>
                     <th>Fecha de Pago</th>
                     <th>Sucursal</th>
                     <th>Moneda</th>
                 </tr>
             </thead>
             <tbody>';
         
         foreach ($datos as $fila) {
             $html .= '<tr>
                 <td>' . htmlspecialchars($fila['cliente_nombres'] ?? '') . '</td>
                 <td class="text-center">' . htmlspecialchars($fila['nro_prestamo'] ?? '') . '</td>
                 <td class="text-center">' . htmlspecialchars($fila['pdetalle_nro_cuota'] ?? '') . '</td>
                 <td class="text-right">' . htmlspecialchars($fila['moneda_simbolo'] ?? 'C$') . ' ' . number_format(floatval($fila['pago_monto'] ?? 0), 2) . '</td>
                 <td class="text-center">' . date('d/m/Y', strtotime($fila['pago_fecha'] ?? '')) . '</td>
                 <td class="text-center">' . htmlspecialchars($fila['sucursal_nombre'] ?? 'N/A') . '</td>
                 <td class="text-center">' . htmlspecialchars($fila['moneda_simbolo'] ?? '') . '</td>
             </tr>';
         }
         
         $html .= '
             <tr class="total-row">
                 <td colspan="4"><strong>TOTAL GENERAL</strong></td>
                 <td class="text-right"><strong>C$ ' . number_format($total_recuperado, 2) . '</strong></td>
                 <td colspan="2" class="text-center"><strong>' . $cuotas_recuperadas . ' cuotas</strong></td>
             </tr>
             </tbody>
         </table>';
    } else {
        $html .= '<div class="resumen">
            <p style="text-align: center; font-size: 16px;">✅ <strong>No se registraron recuperaciones para el período seleccionado.</strong></p>
        </div>';
    }
    
    $html .= '
    <div class="footer">
        <p>Este reporte fue generado automáticamente por el Sistema de Préstamos CrediCrece</p>
        <p>Fecha de generación: ' . date('d/m/Y H:i:s') . '</p>
    </div>
</body>
</html>';

    return $html;
}

// Handler for obtener_kpis_gerenciales action (dashboard compatibility)
if (isset($_POST['accion']) && $_POST['accion'] === 'obtener_kpis_gerenciales') {
    require_once "../controladores/reportes_controlador.php";
    
    try {
        $kpis = ReportesControlador::ctrObtenerKpisGerenciales();
        echo json_encode($kpis, JSON_UNESCAPED_UNICODE);
    } catch (Exception $e) {
        error_log("Error en obtener_kpis_gerenciales: " . $e->getMessage());
        echo json_encode([
            'saldo_cartera' => 0,
            'clientes_activos' => 0,
            'monto_en_mora' => 0,
            'porcentaje_mora' => 0
        ], JSON_UNESCAPED_UNICODE);
    }
    exit;
}
