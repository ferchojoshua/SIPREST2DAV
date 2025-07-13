<?php
// Habilitar la visualización de todos los errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';

// Configuración adaptable para impresoras térmicas
// El ancho se ajustará automáticamente según la impresora
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A7', // Formato pequeño que se adapta bien a impresoras térmicas
    'margin_left' => 3,
    'margin_right' => 3,
    'margin_top' => 5,
    'margin_bottom' => 5,
    'margin_header' => 0,
    'margin_footer' => 0,
    'adjustPassiveFont' => true // Permite ajustar fuentes para mejor compatibilidad
]);

$query = "SELECT
                pc.pres_id,
                pc.nro_prestamo,
                pc.cliente_id,
                c.cliente_nombres,
                c.cliente_dni,
                pc.pres_monto,
                pc.pres_interes,
                pc.pres_cuotas,
                pc.fpago_id,
                fp.fpago_descripcion,
                DATE( pc.pres_f_emision ) AS fecha,
                pc.pres_aprobacion AS estado,
                pc.pres_monto_cuota,
                pc.pres_monto_interes,
                pc.pres_monto_total,
                pc.pres_cuotas_pagadas,
                empresa.confi_id,
                empresa.confi_razon,
                empresa.confi_ruc,
                empresa.confi_direccion,
                empresa.config_correo,
                empresa.config_celular,
                empresa.config_logo,
                pc.moneda_id,
                mo.moneda_nombre,
                mo.moneda_simbolo,
                pc.pres_monto_restante,
                pc.pres_cuotas_restante,

                pc.nro_prestamo as nro_p_detalle,
                pd.pdetalle_nro_cuota,
                pd.pdetalle_fecha,
                pd.pdetalle_monto_cuota,
                pd.pdetalle_estado_cuota,
                pd.pdetalle_fecha_registro,
                DATE_FORMAT(pdetalle_fecha_registro, '%d/%m/%Y %H:%i') as pdetalle_fecha_registro_format,
                pd.pdetalle_saldo_cuota,
                pd.pdetalle_cant_cuota_pagada 
                FROM
                prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
                INNER JOIN prestamo_detalle pd ON pd.nro_prestamo = pc.nro_prestamo,
                empresa 
                WHERE
                pc.nro_prestamo ='".$_GET['codigo']."' and pd.pdetalle_nro_cuota = '".$_GET['cuota']."'";

$resultado = $mysqli->query($query);

// Verificar si la consulta falló
if ($resultado === false) {
    die("Error en la consulta SQL: " . $mysqli->error);
}

// Fix syntax error around line 327 - removing malformed HTML/PHP mixing
if (empty($resultados)) {
    echo "Error: No se encontraron datos para el préstamo";
    exit();
}

// Continue with normal processing
$datos = $resultados[0];

if ($row1 = $resultado->fetch_assoc()) {
    // Verificar si existe logo de empresa personalizado
    $logo_empresa = '';
    if (!empty($row1['config_logo']) && file_exists('../uploads/logos/' . $row1['config_logo'])) {
        $logo_empresa = '../uploads/logos/' . $row1['config_logo'];
    } else {
        // Logo por defecto si no existe logo de empresa
        $logo_empresa = 'img/logo.png';
    }

    // Obtener datos de la cuota pagada
    $nro_cuota = (int)$row1['pdetalle_nro_cuota'];
    $principal = 0;
    $interes = 0;
    $mantenimiento = 0;
    $comision = 0;

    // Calcular desglose usando la calculadora de préstamos
    require_once __DIR__ . '/../utilitarios/calculadora_prestamos.php';
    $tabla = CalculadoraPrestamos::calcularAmortizacion(
        $row1['pres_monto'],
        $row1['pres_interes'],
        $row1['pres_cuotas'],
        $row1['tipo_calculo'] ?? 'FRANCES',
        $row1['fecha'],
        $row1['fpago_id']
    );
    if (isset($tabla['tabla_amortizacion'][$nro_cuota-1])) {
        $principal = $tabla['tabla_amortizacion'][$nro_cuota-1]['capital'];
        $interes = $tabla['tabla_amortizacion'][$nro_cuota-1]['interes'];
    }

    // Obtener nombre de la cajera desde sesión
    session_start();
    $cajera = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '---';

    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Recibo de Pago de Cuota</title>
        <style>
            @page {
                margin: 0;
                padding: 0;
            }
            body {
                font-family: Arial, sans-serif;
                font-size: 10px;
                margin: 0;
                padding: 5px;
                color: #000;
                line-height: 1.2;
            }
            
            .recibo-container {
                width: 100%;
                margin: 0 auto;
                padding: 3px;
            }
            
            .recibo-header {
                text-align: center;
                border-bottom: 1px dashed #000;
                padding-bottom: 5px;
                margin-bottom: 5px;
            }
            
            .recibo-logo {
                max-width: 40px;
                max-height: 40px;
                margin: 0 auto 3px;
                display: block;
            }
            
            .recibo-empresa {
                font-size: 11px;
                font-weight: bold;
                margin: 2px 0;
                text-transform: uppercase;
            }
            
            .recibo-ruc {
                font-size: 9px;
                margin: 1px 0;
            }
            
            .recibo-direccion {
                font-size: 8px;
                margin: 1px 0;
            }
            
            .recibo-contacto {
                font-size: 8px;
                margin: 1px 0;
            }
            
            .recibo-titulo {
                font-size: 10px;
                font-weight: bold;
                text-align: center;
                margin: 5px 0;
                padding: 3px;
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
            }
            
            .recibo-seccion {
                margin: 5px 0;
                border-bottom: 1px dashed #ccc;
                padding-bottom: 3px;
            }
            
            .recibo-info {
                margin: 2px 0;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }
            
            .recibo-info-label {
                font-weight: bold;
                width: 45%;
                font-size: 9px;
            }
            
            .recibo-info-value {
                width: 55%;
                text-align: right;
                font-size: 9px;
            }
            
            .recibo-total {
                text-align: center;
                font-size: 11px;
                font-weight: bold;
                margin: 5px 0;
                padding: 5px;
                border-top: 1px dashed #000;
                border-bottom: 1px dashed #000;
            }
            
            .recibo-firma {
                margin-top: 10px;
                text-align: center;
            }
            
            .recibo-firma-linea {
                border-top: 1px solid #000;
                width: 120px;
                margin: 10px auto 5px;
            }
            
            .recibo-pie {
                text-align: center;
                font-size: 7px;
                margin-top: 5px;
                padding-top: 5px;
                border-top: 1px dashed #ccc;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class="recibo-container">
            <div class="recibo-header">
                <img src="' . $logo_empresa . '" class="recibo-logo" alt="Logo">
                <div class="recibo-empresa">' . $row1['confi_razon'] . '</div>
                <div class="recibo-ruc">RUC: ' . $row1['confi_ruc'] . '</div>
                <div class="recibo-direccion">' . $row1['confi_direccion'] . '</div>';
                
            // Agregar teléfono si existe
            if (!empty($row1['config_celular'])) {
                $html .= '<div class="recibo-contacto">Tel: ' . $row1['config_celular'] . '</div>';
            }
            
            // Agregar email si existe
            if (!empty($row1['config_correo'])) {
                $html .= '<div class="recibo-contacto">Email: ' . $row1['config_correo'] . '</div>';
            }
            
            $html .= '</div>
            
            <div class="recibo-titulo">RECIBO DE PAGO DE CUOTA</div>
            
            <div class="recibo-seccion">
                <div class="recibo-info">
                    <span class="recibo-info-label">Nro. Préstamo:</span>
                    <span class="recibo-info-value">' . $row1['nro_prestamo'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Fecha de Pago:</span>
                    <span class="recibo-info-value">' . $row1['pdetalle_fecha_registro_format'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Recibo N°:</span>
                    <span class="recibo-info-value">' . str_pad($row1['nro_prestamo'] . $row1['pdetalle_nro_cuota'], 8, '0', STR_PAD_LEFT) . '</span>
                </div>
            </div>
            
            <div class="recibo-seccion">
                <div class="recibo-info">
                    <span class="recibo-info-label">Cliente:</span>
                    <span class="recibo-info-value" style="font-size: 8px;">' . $row1['cliente_nombres'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Cedula:</span>
                    <span class="recibo-info-value">' . $row1['cliente_dni'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Forma de Pago:</span>
                    <span class="recibo-info-value">' . $row1['fpago_descripcion'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Moneda:</span>
                    <span class="recibo-info-value">' . $row1['moneda_nombre'] . '</span>
                </div>
            </div>
            
            <div class="recibo-seccion">
                <div class="recibo-info">
                    <span class="recibo-info-label">Cuota N°:</span>
                    <span class="recibo-info-value">' . $row1['pdetalle_nro_cuota'] . ' de ' . $row1['pres_cuotas'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Cuotas Pagadas:</span>
                    <span class="recibo-info-value">' . $row1['pdetalle_cant_cuota_pagada'] . ' de ' . $row1['pres_cuotas'] . '</span>
                </div>
                <div class="recibo-info">
                    <span class="recibo-info-label">Saldo Pendiente:</span>
                    <span class="recibo-info-value">' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pdetalle_saldo_cuota'], 2) . '</span>
                </div>
            </div>
            
            <div class="recibo-info"><span class="recibo-info-label">Cajera:</span><span class="recibo-info-value">' . $cajera . '</span></div>';
            $html .= '<div class="recibo-seccion" style="margin-bottom:0;">
    <div class="recibo-info"><span class="recibo-info-label">PRINCIPAL:</span><span class="recibo-info-value">' . number_format($principal,2) . '</span></div>
    <div class="recibo-info"><span class="recibo-info-label">MANT. VALOR:</span><span class="recibo-info-value">' . number_format($mantenimiento,2) . '</span></div>
    <div class="recibo-info"><span class="recibo-info-label">INTERESES:</span><span class="recibo-info-value">' . number_format($interes,2) . '</span></div>
    <div class="recibo-info"><span class="recibo-info-label">INT. MORAT.:</span><span class="recibo-info-value">0.00</span></div>
    <div class="recibo-info"><span class="recibo-info-label">COMISION:</span><span class="recibo-info-value">0.00</span></div>
</div>';
            
            <div class="recibo-total">
                MONTO PAGADO<br>
                ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pdetalle_monto_cuota'], 2) . '
            </div>
            
            <div class="recibo-firma">
                <div class="recibo-firma-linea"></div>
                <div style="font-size: 8px; font-weight: bold;">FIRMA AUTORIZADA</div>
                <div style="font-size: 7px; margin-top: 2px;">' . $row1['confi_razon'] . '</div>
            </div>
            
            <div class="recibo-pie">
                Documento generado automáticamente<br>
                ' . date('d/m/Y H:i:s') . '<br>
                Gracias por su pago puntual
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
    
    // Configuración para impresión directa
    $mpdf->Output('recibo_pago.pdf', 'I'); // Enviar el PDF al navegador
    
} else {
    echo "Error: No se encontraron datos para el préstamo con código: " . htmlspecialchars($_GET['codigo']) . " y cuota: " . htmlspecialchars($_GET['cuota']);
}

$mysqli->close();

?>