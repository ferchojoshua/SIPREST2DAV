<?php
// Habilitar la visualización de todos los errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';

// Incluir dependencias del proyecto
require_once __DIR__ . '/vendor/autoload.php';
require_once '../conexion_reportes/r_conexion.php';
require_once __DIR__ . '/../utilitarios/email_config.php'; // Configuración de correo

// Configuración para impresora térmica de 80mm de ancho.
// El alto se define como 'P' para que sea proporcional al contenido.
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => [80, 250], // Ancho de 80mm, alto variable  
    'orientation' => 'P',
    'margin_left' => 5,
    'margin_right' => 5,
    'margin_top' => 5,
    'margin_bottom' => 5,   
    'margin_header' => 0,
    'margin_footer' => 0
]);

$query = "SELECT
                pc.pres_id,
                pc.nro_prestamo,
                pc.cliente_id,
                c.cliente_nombres,
                c.cliente_dni,
                c.cliente_correo,
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

// Verificar si se encontraron datos
if ($resultado->num_rows === 0) {
    echo "Error: No se encontraron datos para el préstamo";
    exit();
}

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
    $cajera = '---';
    if (isset($_SESSION['usuario'])) {
        if (is_object($_SESSION['usuario'])) {
            $cajera = $_SESSION['usuario']->usuario ?? $_SESSION['usuario']->nombre_usuario ?? '---';
        } else {
            $cajera = $_SESSION['usuario'];
        }
    }

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
            
            .desglose-tabla {
                width: 100%;
                margin-top: 4px;
            }
            .desglose-tabla td {
                font-size: 9px;
                padding: 1px 0;
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
                <div class="recibo-empresa">' . htmlspecialchars($row1['confi_razon']) . '</div>
                <div class="recibo-ruc">RUC: ' . htmlspecialchars($row1['confi_ruc']) . '</div>
                <div class="recibo-direccion">' . htmlspecialchars($row1['confi_direccion']) . '</div>
                <div class="recibo-contacto">Tel: ' . htmlspecialchars($row1['config_celular']) . ' | Email: ' . htmlspecialchars($row1['config_correo']) . '</div>
            </div>
            
            <div class="recibo-titulo">RECIBO DE PAGO</div>
            
            <div class="recibo-seccion">
                <div class="recibo-info"><span class="recibo-info-label">Nro. Préstamo:</span><span class="recibo-info-value">' . htmlspecialchars($row1['nro_prestamo']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Fecha de Pago:</span><span class="recibo-info-value">' . htmlspecialchars($row1['pdetalle_fecha_registro_format']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Recibo N°:</span><span class="recibo-info-value">' . htmlspecialchars($row1['pres_id']) . str_pad($row1['pdetalle_nro_cuota'], 3, "0", STR_PAD_LEFT) . '</span></div>
            </div>

            <div class="recibo-seccion">
                <div class="recibo-info"><span class="recibo-info-label">Cliente:</span><span class="recibo-info-value">' . htmlspecialchars($row1['cliente_nombres']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Cédula:</span><span class="recibo-info-value">' . htmlspecialchars($row1['cliente_dni']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Forma de Pago:</span><span class="recibo-info-value">' . htmlspecialchars($row1['fpago_descripcion']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Moneda:</span><span class="recibo-info-value">' . htmlspecialchars($row1['moneda_nombre']) . '</span></div>
            </div>

            <div class="recibo-seccion">
                <div class="recibo-info"><span class="recibo-info-label">Cuota N°:</span><span class="recibo-info-value">' . htmlspecialchars($row1['pdetalle_nro_cuota']) . ' de ' . htmlspecialchars($row1['pres_cuotas']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Cuotas Pagadas:</span><span class="recibo-info-value">' . htmlspecialchars($row1['pres_cuotas_pagadas']) . ' de ' . htmlspecialchars($row1['pres_cuotas']) . '</span></div>
                <div class="recibo-info"><span class="recibo-info-label">Cajera:</span><span class="recibo-info-value">' . htmlspecialchars($cajera) . '</span></div>
                
                <table class="desglose-tabla">
                    <tr><td>PRINCIPAL:</td><td style="text-align:right;">' . htmlspecialchars($row1['moneda_simbolo']) . ' ' . number_format($principal, 2) . '</td></tr>
                    <tr><td>INTERESES:</td><td style="text-align:right;">' . htmlspecialchars($row1['moneda_simbolo']) . ' ' . number_format($interes, 2) . '</td></tr>
                    <tr><td>MANT. VALOR:</td><td style="text-align:right;">' . htmlspecialchars($row1['moneda_simbolo']) . ' 0.00</td></tr>
                    <tr><td>INT. MORAT.:</td><td style="text-align:right;">' . htmlspecialchars($row1['moneda_simbolo']) . ' 0.00</td></tr>
                    <tr><td>COMISIÓN:</td><td style="text-align:right;">' . htmlspecialchars($row1['moneda_simbolo']) . ' 0.00</td></tr>
                    <tr><td>TOTAL:</td><td style="text-align:right;">' . htmlspecialchars($row1['moneda_simbolo']) . ' ' . number_format($row1['pdetalle_monto_cuota'], 2) . '</td></tr>
                </table>
            </div>
            
            <div class="recibo-total">
                <div>MONTO PAGADO</div>
                <div>' . htmlspecialchars($row1['moneda_simbolo']) . ' ' . number_format($row1['pdetalle_monto_cuota'], 2) . '</div>
            </div>
            
            <div class="recibo-firma">
                <div class="recibo-firma-linea"></div>
             <!-- <div style="font-size: 8px; font-weight: bold;">FIRMA AUTORIZADA</div> -->
                <div style="font-size: 7px; margin-top: 2px;">' . htmlspecialchars($row1['confi_razon']) . '</div>
            </div>
            
            <div class="recibo-pie">PAGO ELECTRONICAMENTE<br>
                ' . date('d/m/Y H:i:s') . '<br>
                Gracias por su pago puntual
            </div>
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);

    // --- ENVÍO DE CORREO ELECTRÓNICO ---
    // Solo si el envío está activo, el cliente tiene email y la configuración está completa.
    if (EMAIL_ACTIVO === true && !empty($row1['cliente_correo']) && SMTP_HOST !== 'smtp.example.com') {
        
        $pdf_content = $mpdf->Output('', 'S'); // Obtener el PDF como un string
        
        $mail = new PHPMailer(true);
        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            // Remitente y Destinatario
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($row1['cliente_correo'], $row1['cliente_nombres']);

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Recibo de Pago - Préstamo Nro: ' . $row1['nro_prestamo'];
            $mail->Body    = '
                <p>Estimado(a) <strong>' . htmlspecialchars($row1['cliente_nombres']) . '</strong>,</p>
                <p>Adjuntamos a este correo el recibo de pago para la cuota N° ' . htmlspecialchars($row1['pdetalle_nro_cuota']) . ' correspondiente a su préstamo.</p>
                <p>Gracias por su preferencia.</p>
                <p>Atentamente,<br><strong>' . htmlspecialchars($row1['confi_razon']) . '</strong></p>';
            $mail->AltBody = 'Estimado(a) ' . htmlspecialchars($row1['cliente_nombres']) . ",\n\nAdjuntamos a este correo el recibo de pago para la cuota N° " . htmlspecialchars($row1['pdetalle_nro_cuota']) . " correspondiente a su préstamo.\n\nGracias por su preferencia.\n\nAtentamente,\n" . htmlspecialchars($row1['confi_razon']);

            // Adjuntar el PDF
            $mail->addStringAttachment($pdf_content, 'Recibo_Nro_' . $row1['nro_prestamo'] . '.pdf', 'base64', 'application/pdf');

            $mail->send();

        } catch (Exception $e) {
            // No detener la ejecución si el correo falla, solo registrar el error.
            error_log("Error al enviar correo: " . $mail->ErrorInfo);
        }
    }

    // --- MOSTRAR PDF EN EL NAVEGADOR ---
    $mpdf->Output('Recibo_de_Pago.pdf', 'I');

} else {
    echo "No se encontraron datos para el préstamo con el código proporcionado.";
}

$mysqli->close();

?>