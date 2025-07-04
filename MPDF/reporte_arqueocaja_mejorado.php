<?php
require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 180]]);

$query = "SELECT caja.caja_id, 
				caja.caja_descripcion, 
				caja.caja_monto_inicial, 
				caja.caja_prestamo, 
				caja.caja_monto_ingreso,
				caja.caja__monto_egreso, 
				DATE_FORMAT(caja.caja_f_apertura, '%d/%m/%Y') as caja_f_apertura,
				DATE_FORMAT(caja.caja_f_cierre, '%d/%m/%Y') as caja_f_cierre, 
				caja.caja_count_ingreso, 
				caja.caja_count_egreso, 
				caja.caja_count_prestamo,
				caja.caja_monto_total, 
				caja.caja_hora_apertura, 
				caja.caja_estado, 
				caja.caja_hora_cierre, 
				empresa.confi_razon,
				empresa.confi_ruc,
				empresa.confi_direccion,
				empresa.config_correo,
				empresa.config_celular,
				empresa.config_logo,
				caja.caja_interes,
				empresa.config_moneda as moneda_simbolo
				FROM
				caja,
				empresa
				WHERE caja.caja_id = '".$_GET['codigo']."'";

$resultado = $mysqli->query($query);

if (!$resultado) {
    die("Error en la consulta principal: " . $mysqli->error);
}

if ($row1 = $resultado->fetch_assoc()) {
    $estado = $row1['caja_estado'];
    $nombreUsuario = "Cajero"; // Valor por defecto

    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <title>Arqueo de Caja</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 11px;
                line-height: 1.3;
                color: #333;
                margin: 0;
                padding: 8px;
            }
            .ticket-header {
                text-align: center;
                margin-bottom: 15px;
            }
            .ticket-logo {
                width: 60px;
                height: auto;
                margin-bottom: 10px;
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
                margin: 12px 0;
                padding-top: 10px;
            }
            .ticket-titulo {
                text-align: center;
                font-weight: bold;
                font-size: 14px;
                margin: 15px 0;
                color: #2c3e50;
                background-color: #ecf0f1;
                padding: 10px;
                border-radius: 5px;
                border-left: 4px solid #3498db;
            }
            .ticket-info {
                font-size: 10px;
                line-height: 1.4;
                margin: 4px 0;
            }
            .ticket-info-label {
                font-weight: bold;
                color: #2c3e50;
            }
            .ticket-total {
                font-size: 14px;
                font-weight: bold;
                margin: 15px 0;
                text-align: center;
                background: linear-gradient(135deg, #27ae60, #2ecc71);
                color: white;
                padding: 12px;
                border-radius: 6px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            .seccion-detalle {
                background-color: #f8f9fa;
                padding: 12px;
                margin: 10px 0;
                border-radius: 6px;
                border-left: 4px solid #3498db;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
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
        
        <div class="ticket-titulo">ARQUEO DE CAJA</div>
        
        <div class="ticket-separador">
            <div class="ticket-info">
                <span class="ticket-info-label">Ticket N°:</span> ' . str_pad($row1['caja_id'], 6, '0', STR_PAD_LEFT) . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Apertura:</span> ' . $row1['caja_f_apertura'] . ' - ' . $row1['caja_hora_apertura'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Cierre:</span> ' . $row1['caja_f_cierre'] . ' - ' . $row1['caja_hora_cierre'] . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Cajero:</span> ' . $nombreUsuario . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Estado:</span> ' . strtoupper($estado) . '
            </div>
        </div>
        
        <div class="seccion-detalle">
            <div class="ticket-info">
                <span class="ticket-info-label">Monto Apertura:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['caja_monto_inicial'], 2) . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Monto Interés:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['caja_interes'], 2) . '
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Préstamos:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['caja_prestamo'], 2) . ' (' . $row1['caja_count_prestamo'] . ')
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Ingresos:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['caja_monto_ingreso'], 2) . ' (' . $row1['caja_count_ingreso'] . ')
            </div>
            <div class="ticket-info">
                <span class="ticket-info-label">Egresos:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['caja__monto_egreso'], 2) . ' (' . $row1['caja_count_egreso'] . ')
            </div>
        </div>';

    // Consultas para detalles por moneda
    $apertura_f_h = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $row1['caja_f_apertura']) . ' ' . $row1['caja_hora_apertura']));
    $cierre_f_h = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $row1['caja_f_cierre']) . ' ' . $row1['caja_hora_cierre']));

    // Detalle de préstamos por moneda
    $query_prestamos_moneda = "SELECT
                                    m.moneda_simbolo,
                                    SUM(pc.pres_monto_total) AS total_prestamo_moneda
                                FROM
                                    prestamo_cabecera pc
                                INNER JOIN
                                    moneda m ON pc.moneda_id = m.moneda_id
                                WHERE
                                    pc.pres_f_emision BETWEEN '$apertura_f_h' AND '$cierre_f_h'
                                GROUP BY
                                    m.moneda_simbolo";
    $resultado_prestamos_moneda = $mysqli->query($query_prestamos_moneda);

    if ($resultado_prestamos_moneda && $resultado_prestamos_moneda->num_rows > 0) {
        $html .= '<div class="seccion-detalle">
                    <div class="ticket-info">
                        <span class="ticket-info-label">Detalle Préstamos por Moneda:</span>
                    </div>';
        while ($row_prestamo_moneda = $resultado_prestamos_moneda->fetch_assoc()) {
            $html .= '<div class="ticket-info">
                        &nbsp;&nbsp;&nbsp;&nbsp;' . $row_prestamo_moneda['moneda_simbolo'] . ' ' . number_format($row_prestamo_moneda['total_prestamo_moneda'], 2) . '
                      </div>';
        }
        $html .= '</div>';
    }

    $html .= '
        <div class="ticket-total">
            <div style="font-size: 12px; margin-bottom: 5px;">MONTO TOTAL EN CAJA</div>
            <div style="font-size: 18px; font-weight: bold;">' . $row1['moneda_simbolo'] . ' ' . number_format($row1['caja_monto_total'], 2) . '</div>
        </div>
        
        <div class="ticket-pie">
            Documento generado automáticamente<br>
            ' . date('d/m/Y H:i:s') . '<br>
            Sistema de Préstamos
        </div>
    </body>
    </html>';

    $mpdf->WriteHTML($html);
    $mpdf->Output();
} else {
    echo "Error: No se encontraron datos del arqueo de caja.";
}
?> 