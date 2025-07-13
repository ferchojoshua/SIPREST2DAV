<?php
// Habilitar la visualización de todos los errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require 'numeroletras/CifrasEnLetras.php';

$v = new CifrasEnLetras();
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

$codigo = $_GET['codigo'];

$query = "SELECT
    pc.*, 
    DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS pres_fecha_registro,
    DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') AS pres_f_emision,
    c.cliente_nombres, c.cliente_dni, c.cliente_direccion,
    fp.fpago_descripcion,
    mo.moneda_nombre, mo.moneda_simbolo,
    empresa.confi_razon, empresa.confi_ruc, empresa.confi_direccion, 
    empresa.config_correo, empresa.config_celular,
    u.usuario
FROM prestamo_cabecera pc
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario,
empresa 
WHERE pc.nro_prestamo = '$codigo'";

$resultado = $mysqli->query($query);

// Verificar si la consulta falló
if ($resultado === false) {
    die("Error en la consulta SQL: " . $mysqli->error);
}

if ($row1 = $resultado->fetch_assoc()) {
    $montoEnLetras = $v->convertirEurosEnLetras($row1['pres_monto_total']);

    $html = '
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
        <title>Historial de Préstamo</title>
  <style>
            body { 
                font-family: Arial, sans-serif; 
                font-size: 12px; 
                color: #333; 
                margin: 20px;
                line-height: 1.4;
            }
            
            .header-empresa {
                text-align: center;
                border-bottom: 3px solid #2c3e50;
                padding-bottom: 15px; /* Reducido de 20px */
                margin-bottom: 25px; /* Reducido de 30px */
            }
            
            .logo-empresa {
                width: 90px; /* Ligeramente más pequeño */
                height: auto;
                margin-bottom: 10px; /* Ajustado */
            }
            
            .razon-social {
                font-size: 22px; /* Ligeramente más pequeño */
                font-weight: bold;
                color: #2c3e50;
                margin: 8px 0;
            }
            
            .info-empresa {
                font-size: 13px; /* Ligeramente más pequeño */
                color: #7f8c8d;
                margin: 4px 0;
            }
            
            .titulo-documento {
                font-size: 18px; /* Ligeramente más pequeño */
                font-weight: bold;
                text-align: center;
                color: #2c3e50;
                margin: 25px 0; /* Ajustado */
                padding: 12px; /* Ajustado */
                background-color: #ecf0f1;
                border-radius: 6px; /* Ligeramente más pequeño */
                border-left: 4px solid #3498db;
            }
            
            .info-prestamo {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px; /* Reducido de 30px */
                margin: 25px 0; /* Ajustado */
                padding: 15px; /* Ajustado */
                background-color: #f8f9fa;
                border-radius: 8px;
            }
            
            .info-item {
                margin-bottom: 10px; /* Ajustado */
                padding: 6px 0;
                border-bottom: 1px dashed #ced4da; /* Cambio a línea punteada */
            }
            
            .info-label {
                font-weight: bold;
                color: #2c3e50;
                display: inline-block;
                width: 130px; /* Ligeramente más estrecho */
            }
            
            .info-valor {
                color: #34495e;
            }
            
            .monto-letras {
                background-color: #e8f5e8;
                padding: 12px; /* Ajustado */
                border-radius: 8px;
                margin: 18px 0;
                border-left: 4px solid #27ae60;
            }
            
            .tabla-cuotas {
                width: 100%;
                border-collapse: collapse;
                margin: 25px 0; /* Ajustado */
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            
            .tabla-cuotas th,
            .tabla-cuotas td {
                border: 1px solid #dcdcdc; /* Color de borde más suave */
                padding: 10px 8px; /* Ajustado */
                text-align: left;
            }
            
            .tabla-cuotas th {
                background-color: #3498db;
                color: white;
                font-weight: bold;
                text-align: center;
            }
            
            .tabla-cuotas tr:nth-child(even) {
                background-color: #f8f9fa;
            }
            
            .tabla-cuotas tr:hover {
                background-color: #e3f2fd;
            }
            
            .estado-pagado {
                color: #27ae60;
                font-weight: bold;
            }
            
            .estado-pendiente {
                color: #e74c3c;
                font-weight: bold;
            }
            
            .seccion-firmas {
                margin-top: 50px; /* Ajustado */
                text-align: center;
            }
            
            .firma-linea {
                border-top: 1px solid #7f8c8d; /* Borde más suave */
                width: 200px; /* Más estrecho */
                margin: 30px auto 10px;
            }
            
            .firma-texto {
                font-size: 13px; /* Ligeramente más pequeño */
                color: #7f8c8d;
                font-weight: bold;
            }
            
            .pie-documento {
                margin-top: 30px; /* Ajustado */
                text-align: center;
                font-size: 9px; /* Ligeramente más pequeño */
                color: #95a5a6;
                border-top: 1px solid #ecf0f1;
                padding-top: 12px; /* Ajustado */
            }
            
            .text-right { text-align: right; }
            .text-center { text-align: center; }
  </style>
</head>
<body>
        <div class="header-empresa">
            <img src="img/logo.png" class="logo-empresa" alt="Logo Empresa">
            <div class="razon-social">' . $row1['confi_razon'] . '</div>
            <div class="info-empresa">RUC: ' . $row1['confi_ruc'] . '</div>
            <div class="info-empresa">' . $row1['confi_direccion'] . '</div>
            <div class="info-empresa">Email: ' . $row1['config_correo'] . '</div>
        </div>
        
        <div class="titulo-documento">
            HISTORIAL DE PRÉSTAMO N° ' . $row1['nro_prestamo'] . '
        </div>
        
        <div class="info-prestamo">
            <div>
                <div class="info-item">
                    <span class="info-label">Cliente:</span>
                    <span class="info-valor">' . $row1['cliente_nombres'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Documento:</span>
                    <span class="info-valor">' . $row1['cliente_dni'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Dirección:</span>
                    <span class="info-valor">' . $row1['cliente_direccion'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha Préstamo:</span>
                    <span class="info-valor">' . $row1['pres_fecha_registro'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Fecha Emisión:</span>
                    <span class="info-valor">' . $row1['pres_f_emision'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Moneda:</span>
                    <span class="info-valor">' . $row1['moneda_nombre'] . '</span>
                </div>
            </div>
            <div>
                <div class="info-item">
                    <span class="info-label">Monto Préstamo:</span>
                    <span class="info-valor">' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pres_monto'], 2) . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Interés (%):</span>
                    <span class="info-valor">' . $row1['pres_interes'] . '%</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Monto Interés:</span>
                    <span class="info-valor">' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pres_monto_interes'], 2) . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Monto Total:</span>
                    <span class="info-valor">' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pres_monto_total'], 2) . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Nro. Cuotas:</span>
                    <span class="info-valor">' . $row1['pres_cuotas'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Monto Cuota:</span>
                    <span class="info-valor">' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pres_monto_cuota'], 2) . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Forma de Pago:</span>
                    <span class="info-valor">' . $row1['fpago_descripcion'] . '</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Estado:</span>
                    <span class="info-valor">' . $row1['pres_estado'] . '</span>
                </div>
            </div>
        </div>
        
        <div class="monto-letras">
            <strong>Monto en Letras:</strong> ' . $montoEnLetras . '
        </div>
        
        <h3 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Detalle de Cuotas</h3>
        
        <table class="tabla-cuotas">
            <thead>
                <tr>
                    <th>N° CUOTA</th>
                    <th>FECHA VENCIMIENTO</th>
                    <th>MONTO CUOTA</th>
        <th>ESTADO</th>
      </tr>
    </thead>
            <tbody>';

    $query2 = "SELECT 
                    pdetalle_nro_cuota, 
                    DATE_FORMAT(pdetalle_fecha, '%d/%m/%Y') as fecha, 
                    pdetalle_monto_cuota, 
                    pdetalle_estado_cuota 
                FROM prestamo_detalle 
                WHERE nro_prestamo = '$codigo' 
                ORDER BY pdetalle_nro_cuota";
    $resultado2 = $mysqli->query($query2);

    while ($row2 = $resultado2->fetch_assoc()) {
        $estadoClass = $row2['pdetalle_estado_cuota'] == 'pagado' ? 'estado-pagado' : 'estado-pendiente';
        $html .= "
        <tr>
            <td class='text-center'>{$row2['pdetalle_nro_cuota']}</td>
            <td class='text-center'>{$row2['fecha']}</td>
            <td class='text-right'>" . number_format($row2['pdetalle_monto_cuota'], 2) . "</td>
            <td class='text-center $estadoClass'>" . strtoupper($row2['pdetalle_estado_cuota']) . "</td>
        </tr>";
    }

    $html .= '
    </tbody>
  </table>

        <div class="seccion-firmas">
            <div class="firma-linea"></div>
            <div class="firma-texto">Firma Autorizada</div>
  </div>

        <div class="pie-documento">
            Documento generado automáticamente por el sistema - ' . $row1['confi_razon'] . '<br>
            Dirección: ' . $row1['confi_direccion'] . ' | RUC: ' . $row1['confi_ruc'] . ' | Email: ' . $row1['config_correo'] . '<br>
            Fecha de generación: ' . date('d/m/Y H:i:s') . '
        </div>
</body>
    </html>';

    $mpdf->WriteHTML($html);
    $mpdf->Output('historial_prestamo.pdf', 'I'); // Enviar el PDF al navegador

} else {
    echo "Error: No se encontró el préstamo con código: " . htmlspecialchars($codigo);
}

$mysqli->close();
?>
