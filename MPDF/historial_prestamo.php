<?php
require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require 'numeroletras/CifrasEnLetras.php';

$v = new CifrasEnLetras();
$mpdf = new \Mpdf\Mpdf();

$codigo = $_GET['codigo'];

$query = "SELECT
    pc.*, 
    DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS pres_fecha_registro,
    DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') AS pres_f_emision,
    c.cliente_nombres, c.cliente_dni,
    fp.fpago_descripcion,
    mo.moneda_nombre, mo.moneda_simbolo,
    empresa.confi_razon, empresa.confi_ruc, empresa.confi_direccion, empresa.config_correo,
    u.usuario
FROM prestamo_cabecera pc
INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario,
empresa 
WHERE pc.nro_prestamo = '$codigo'";

$resultado = $mysqli->query($query);

if ($row1 = $resultado->fetch_assoc()) {
    $montoEnLetras = $v->convertirEurosEnLetras($row1['pres_monto_total']);

    $html = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Tabla de Cuotas</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #000; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    td, th { padding: 6px; border: 1px solid #000; text-align: left; }
    .no-border { border: none; }
    .header-table td { border: none; vertical-align: top; }
    .title { font-size: 16px; font-weight: bold; text-align: center; text-decoration: underline; }
    .section-title { background-color: #f0f0f0; font-weight: bold; }
    .right { text-align: right; }
    footer { margin-top: 30px; text-align: center; font-style: italic; font-size: 10px; }
    .firma { margin-top: 60px; text-align: center; }
    .firma .linea { border-top: 1px solid #000; width: 200px; margin: 0 auto; margin-top: 10px; }
  </style>
</head>
<body>

  <table class="header-table">
    <tr>
      <td width="25%">
        <img src="img/logo_empresa.png" width="100">
      </td>
      <td width="50%" class="title">TABLA DE CUOTAS</td>
      <td width="25%" class="right">
        <strong>Prestamo Nro:</strong> {$row1['nro_prestamo']}
      </td>
    </tr>
  </table>

  <table>
    <tr>
      <td width="40%">
        <strong>Cliente:</strong> {$row1['cliente_nombres']}<br>
        <strong>Documento:</strong> {$row1['cliente_dni']}<br>
        <strong>Fecha Préstamo:</strong> {$row1['pres_fecha_registro']}<br>
        <strong>Moneda:</strong> {$row1['moneda_nombre']}
        <strong>Monto:</strong> {$row1['moneda_simbolo']} {$row1['pres_monto']}<br>
        <strong>En Letras:</strong> {$montoEnLetras}<br>
        <strong>Monto Total:</strong> {$row1['moneda_simbolo']} {$row1['pres_monto_total']}<br>
        <strong>Monto Cuota:</strong> {$row1['moneda_simbolo']} {$row1['pres_monto_cuota']}<br>
        <strong>Forma de Pago:</strong> {$row1['fpago_descripcion']}<br>
 
      </td>
      <td width="25%" class="left">
        <!--<strong>Usuario:</strong> {$row1['usuario']}<br>-->
        <strong>Fecha Emisión:</strong> {$row1['pres_f_emision']}<br>
        <strong>Estado:</strong> {$row1['pres_estado']}
        <strong>Nro Cuotas:</strong> {$row1['pres_cuotas']}<br>
        <strong>Interés (%):</strong> {$row1['pres_interes']}<br>
        <strong>Monto Interés:</strong> {$row1['moneda_simbolo']} {$row1['pres_monto_interes']}<br>
      </td>
    </tr>
  </table>

  <h3 style="margin-top: 20px; text-align:center;">Detalle de Cuotas</h3>
 
  <table>
    <thead class="section-title">
      <tr>
        <th>NRO CUOTA</th>
        <th>FECHA</th>
        <th>MONTO</th>
        <th>ESTADO</th>
      </tr>
    </thead>
    <tbody>
HTML;

    $query2 = "SELECT pdetalle_nro_cuota, DATE_FORMAT(pdetalle_fecha, '%d/%m/%Y') as fecha, pdetalle_monto_cuota, pdetalle_estado_cuota FROM prestamo_detalle WHERE nro_prestamo = '$codigo'";
    $resultado2 = $mysqli->query($query2);

    while ($row2 = $resultado2->fetch_assoc()) {
        $html .= "
        <tr>
            <td>{$row2['pdetalle_nro_cuota']}</td>
            <td>{$row2['fecha']}</td>
            <td class='right'>".number_format($row2['pdetalle_monto_cuota'], 2)."</td>
            <td>{$row2['pdetalle_estado_cuota']}</td>
        </tr>";
    }

    $html .= <<<HTML
    </tbody>
  </table>

  <div class="firma">
    <p>Firma Autorizada</p>
    <div class="linea"></div>
  </div>

  <footer>
    Documento generado automáticamente por el sistema - {$row1['confi_razon']}<br>
    Dirección: {$row1['confi_direccion']} | RUC: {$row1['confi_ruc']}
  </footer>

</body>
</html>
HTML;

    $mpdf->WriteHTML($html);
    $mpdf->Output();

} else {
    echo "No se encontró el préstamo.";
}
