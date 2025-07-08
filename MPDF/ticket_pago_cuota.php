<?php

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
} else {
    echo "Error: No se encontraron datos del préstamo.";
}
?>