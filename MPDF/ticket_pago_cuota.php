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
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Ticket de Pago</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
                line-height: 1.3;
                color: #333;
                margin: 0;
                padding: 5px;
            }
            .ticket-header {
                text-align: center;
                margin-bottom: 15px;
            }
            .ticket-logo {
                width: 50px;
                height: auto;
                margin-bottom: 8px;
            }
            .ticket-empresa {
                font-size: 14px;
                font-weight: bold;
                margin: 3px 0;
                color: #2c3e50;
            }
            .ticket-ruc {
                font-size: 11px;
                color: #666;
                margin: 2px 0;
            }
            .ticket-direccion {
                font-size: 10px;
                color: #666;
                margin: 2px 0;
            }
            .ticket-separador {
                border-top: 1px dashed #666;
                margin: 10px 0;
                padding-top: 10px;
            }
            .ticket-titulo {
                text-align: center;
                font-weight: bold;
                font-size: 12px;
                margin: 10px 0;
                color: #2c3e50;
            }
            .ticket-info {
                font-size: 10px;
                line-height: 1.4;
                margin: 3px 0;
            }
            .ticket-info-label {
                font-weight: bold;
                color: #2c3e50;
            }
            .ticket-total {
                font-size: 12px;
                font-weight: bold;
                margin: 8px 0;
                text-align: center;
                background-color: #f8f9fa;
                padding: 5px;
                border-radius: 3px;
            }
            .ticket-firma {
                text-align: center;
                margin-top: 20px;
            }
            .ticket-firma-linea {
                border-top: 1px solid #333;
                width: 150px;
                margin: 15px auto 5px;
            }
            .ticket-pie {
                text-align: center;
                font-size: 9px;
                color: #666;
                margin-top: 15px;
                border-top: 1px dashed #ccc;
                padding-top: 8px;
            }
        </style>
    </head>
    <body>
        <div class="ticket-header">';
    
    // Logo de la empresa
    if (!empty($row1['config_logo']) && file_exists('../uploads/logos/' . $row1['config_logo'])) {
        $html .= '<img src="../uploads/logos/' . $row1['config_logo'] . '" class="ticket-logo" alt="Logo">';
    } else {
        // Logo por defecto si no existe logo de empresa
        $html .= '<img src="../vistas/assets/img/default-logo.png" class="ticket-logo" alt="Logo por defecto">';
    }
    
    $html .= '<div class="ticket-empresa">' . $row1['confi_razon'] . '</div>
            <div class="ticket-ruc">RUC: ' . $row1['confi_ruc'] . '</div>
            <div class="ticket-direccion">' . $row1['confi_direccion'] . '</div>
        </div>
        
        <div class="ticket-titulo">RECIBO DE PAGO DE CUOTA</div>
        
        <div class="ticket-separador">
            <div class="ticket-info">
                <span class="ticket-info-label">Nro. Préstamo:</span> ' . $row1['nro_prestamo'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Fecha:</span> ' . $row1['pdetalle_fecha_registro_format'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Cliente:</span><br>' . $row1['cliente_nombres'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Documento:</span> ' . $row1['cliente_dni'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Forma de Pago:</span> ' . $row1['fpago_descripcion'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Moneda:</span> ' . $row1['moneda_nombre'] . '
            </div>
        </div>
        
        <div class="ticket-separador">
            <div class="ticket-info">
                <span class="ticket-info-label">Nro. Cuota:</span> ' . $row1['pdetalle_nro_cuota'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Cuotas Pagadas:</span> ' . $row1['pdetalle_cant_cuota_pagada'] . ' de ' . $row1['pres_cuotas'] . '
            </div>
            <div class="ticket-total">
                <span class="ticket-info-label">MONTO PAGADO</span><br>
                ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pdetalle_monto_cuota'], 2) . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Saldo Pendiente:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pdetalle_saldo_cuota'], 2) . '
            </div>
        </div>
        
        <div class="ticket-firma">
            <div class="ticket-firma-linea"></div>
            <div>Firma Autorizada</div>
        </div>
        
        <div class="ticket-pie">
            Documento generado automáticamente<br>
            Gracias por su pago puntual
        </div>
    </body>
    </html>';
    
    $mpdf->WriteHTML($html);
$mpdf->Output();
} else {
    echo "Error: No se encontraron datos del préstamo.";
}
?>