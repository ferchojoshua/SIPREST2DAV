<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 150]]);

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
                pd.pdetalle_cant_cuota_pagada,
                c.cliente_correo
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

if ($row1 = $resultado->fetch_assoc()) {
    // Verificar si existe logo de empresa personalizado
    $logo_empresa = '';
    if (!empty($row1['config_logo']) && file_exists('../uploads/logos/' . $row1['config_logo'])) {
        $logo_empresa = '../uploads/logos/' . $row1['config_logo'];
    } else {
        // Logo por defecto si no existe logo de empresa
        $logo_empresa = 'img/logo.png';
    }

    $html = '<!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Recibo de Pago de Cuota</title>
        <style>
            body {
                font-family: "Arial", sans-serif;
                font-size: 10px;
                margin: 0;
                padding: 10px;
                color: #000;
                line-height: 1.3;
            }
            
            .recibo-container {
                width: 100%;
                max-width: 280px;
                margin: 0 auto;
                border: 1px solid #000;
                padding: 8px;
            }
            
            .recibo-header {
                text-align: center;
                border-bottom: 1px solid #000;
                padding-bottom: 8px;
                margin-bottom: 10px;
            }
            
            .recibo-logo {
                width: 50px;
                height: auto;
                margin-bottom: 5px;
            }
            
            .recibo-empresa {
                font-size: 12px;
                font-weight: bold;
                margin: 3px 0;
                text-transform: uppercase;
            }
            
            .recibo-ruc {
                font-size: 9px;
                margin: 2px 0;
            }
            
            .recibo-direccion {
                font-size: 8px;
                margin: 2px 0;
            }
            
            .recibo-contacto {
                font-size: 8px;
                margin: 2px 0;
            }
            
            .recibo-titulo {
                font-size: 11px;
                font-weight: bold;
                text-align: center;
                margin: 8px 0;
                padding: 5px;
                border: 1px solid #000;
                background-color: #f5f5f5;
            }
            
            .recibo-seccion {
                margin: 8px 0;
                border-bottom: 1px dashed #ccc;
                padding-bottom: 6px;
            }
            
            .recibo-info {
                margin: 3px 0;
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
                font-size: 12px;
                font-weight: bold;
                margin: 10px 0;
                padding: 8px;
                border: 2px solid #000;
                background-color: #f0f0f0;
            }
            
            .recibo-firma {
                margin-top: 15px;
                text-align: center;
            }
            
            .recibo-firma-linea {
                border-top: 1px solid #000;
                width: 150px;
                margin: 15px auto 8px;
            }
            
            .recibo-pie {
                text-align: center;
                font-size: 8px;
                margin-top: 10px;
                border-top: 1px dashed #ccc;
                padding-top: 8px;
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
                    <span class="recibo-info-label">Documento:</span>
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
            
            <div class="recibo-total">
                MONTO PAGADO<br>
                ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pdetalle_monto_cuota'], 2) . '
            </div>
            
            <div class="recibo-firma">
                <div class="recibo-firma-linea"></div>
                <div style="font-size: 9px; font-weight: bold;">FIRMA AUTORIZADA</div>
                <div style="font-size: 8px; margin-top: 3px;">' . $row1['confi_razon'] . '</div>
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
    $pdf = $mpdf->Output("", 'S');
    
    // Enviar email
    sendEmail(
        $pdf,
        $row1['config_correo'], 
        $row1['cliente_correo'], 
        $row1['confi_razon'],
        $row1['confi_ruc'],
        $row1['nro_prestamo'],
        $row1['pdetalle_fecha_registro_format'],
        $row1['cliente_nombres'],
        $row1['cliente_dni'],
        $row1['fpago_descripcion'],
        $row1['moneda_nombre'],
        $row1['pdetalle_nro_cuota'],
        $row1['pdetalle_cant_cuota_pagada'],
        $row1['pres_cuotas'],
        $row1['moneda_simbolo'],
        $row1['pdetalle_monto_cuota'],
        $row1['pdetalle_saldo_cuota'],
        $row1['confi_direccion'],
        $row1['config_celular']
    );
    
    $mpdf->Output();
} else {
    echo "Error: No se encontraron datos del préstamo.";
}

function sendEmail($pdf, $correoE, $correoCli, $razon_S, $ruc, $nro_prestamo, $fecha, $nombreCliente, $clienteDni, $formaPago, $moneda, $nro_cuota, $cant_cuotas_pagadas, $cuotas, $simbolo_mone, $monto_cuotas, $saldo_Cuotas, $direccion, $celular)
{
    $mail = new PHPMailer(true);

    try {
        $mail = new PHPMailer();
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = $correoE;
        $mail->Password   = 'Sipresta2025';  // Configurar la contraseña
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom($correoE, $razon_S);
        $mail->addAddress($correoCli, $nombreCliente);
        $mail->addStringAttachment($pdf, "recibo_cuota_" . $nro_prestamo . "_" . $nro_cuota . ".pdf");

        $mail->isHTML(true);
        $mail->Subject = 'RECIBO DE CUOTA PAGADA N° ' . $nro_cuota . ' - PRÉSTAMO ' . $nro_prestamo;

        $mail->Body = '
        <!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Recibo de Pago - ' . $razon_S . '</title>
            <style>
                body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4f4f4; }
                .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
                .header { background: linear-gradient(135deg, #2c3e50, #3498db); color: white; padding: 30px; text-align: center; }
                .header h1 { margin: 0; font-size: 24px; }
                .header p { margin: 5px 0 0; font-size: 14px; opacity: 0.9; }
                .content { padding: 30px; }
                .card { background-color: #f8f9fa; border-left: 4px solid #3498db; padding: 20px; margin: 20px 0; border-radius: 5px; }
                .info-row { display: flex; justify-content: space-between; margin: 10px 0; padding: 8px 0; border-bottom: 1px solid #e9ecef; }
                .info-label { font-weight: bold; color: #2c3e50; }
                .info-value { color: #34495e; }
                .highlight { background: linear-gradient(135deg, #27ae60, #2ecc71); color: white; padding: 20px; text-align: center; border-radius: 8px; margin: 20px 0; }
                .highlight h2 { margin: 0; font-size: 20px; }
                .highlight p { margin: 5px 0 0; font-size: 16px; }
                .footer { background-color: #2c3e50; color: white; padding: 20px; text-align: center; font-size: 12px; }
                .footer a { color: #3498db; text-decoration: none; }
                @media only screen and (max-width: 600px) {
                    .info-row { flex-direction: column; }
                    .content { padding: 20px; }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h1>' . $razon_S . '</h1>
                    <p>RUC: ' . $ruc . '</p>
                    <p>' . $direccion . '</p>
                </div>
                
                <div class="content">
                    <h2 style="color: #2c3e50; text-align: center; margin-bottom: 30px;">
                        ✅ Confirmación de Pago Recibido
                    </h2>
                    
                    <p style="color: #7f8c8d; text-align: center; margin-bottom: 30px;">
                        Estimado(a) <strong>' . $nombreCliente . '</strong>, hemos recibido su pago correspondiente a la cuota de su préstamo.
                    </p>
                    
                    <div class="card">
                        <h3 style="color: #2c3e50; margin-top: 0;">📋 Información del Préstamo</h3>
                        <div class="info-row">
                            <span class="info-label">Número de Préstamo:</span>
                            <span class="info-value">' . $nro_prestamo . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Fecha de Pago:</span>
                            <span class="info-value">' . $fecha . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Cliente:</span>
                            <span class="info-value">' . $nombreCliente . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Documento:</span>
                            <span class="info-value">' . $clienteDni . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Forma de Pago:</span>
                            <span class="info-value">' . $formaPago . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Moneda:</span>
                            <span class="info-value">' . $moneda . '</span>
                        </div>
                    </div>
                    
                    <div class="card">
                        <h3 style="color: #2c3e50; margin-top: 0;">💰 Detalle del Pago</h3>
                        <div class="info-row">
                            <span class="info-label">Número de Cuota:</span>
                            <span class="info-value">' . $nro_cuota . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Cuotas Pagadas:</span>
                            <span class="info-value">' . $cant_cuotas_pagadas . ' de ' . $cuotas . '</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Saldo Pendiente:</span>
                            <span class="info-value">' . $simbolo_mone . ' ' . number_format($saldo_Cuotas, 2) . '</span>
                        </div>
                    </div>
                    
                    <div class="highlight">
                        <h2>MONTO PAGADO</h2>
                        <p>' . $simbolo_mone . ' ' . number_format($monto_cuotas, 2) . '</p>
                    </div>
                    
                    <div style="background-color: #e8f5e8; border: 1px solid #27ae60; border-radius: 5px; padding: 15px; margin: 20px 0;">
                        <p style="margin: 0; color: #27ae60; font-weight: bold; text-align: center;">
                            ✅ Su pago ha sido procesado exitosamente
                        </p>
                        <p style="margin: 10px 0 0; color: #2c3e50; text-align: center; font-size: 14px;">
                            Adjunto encontrará el recibo oficial en formato PDF
                        </p>
                    </div>
                    
                    <p style="color: #7f8c8d; font-size: 14px; text-align: center; margin-top: 30px;">
                        Gracias por su puntualidad en los pagos. Si tiene alguna consulta, no dude en contactarnos.
                    </p>
                </div>
                
                <div class="footer">
                    <p><strong>' . $razon_S . '</strong></p>
                    <p>' . $direccion . '</p>
                    <p>RUC: ' . $ruc . ' | Tel: ' . $celular . '</p>
                    <p>Email: <a href="mailto:' . $correoE . '">' . $correoE . '</a></p>
                    <p style="margin-top: 15px; font-size: 11px; opacity: 0.8;">
                        Este es un mensaje automático, por favor no responda a este correo.
                    </p>
                </div>
            </div>
        </body>
        </html>';

        $mail->AltBody = 'Estimado(a) ' . $nombreCliente . ', hemos recibido su pago de la cuota ' . $nro_cuota . ' por el monto de ' . $simbolo_mone . ' ' . number_format($monto_cuotas, 2) . '. Adjunto encontrará el recibo en PDF.';

        $mail->send();
        echo 'Correo enviado exitosamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>

