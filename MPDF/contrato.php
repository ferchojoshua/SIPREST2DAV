<?php
// Habilitar la visualización de todos los errores de PHP para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require 'numeroletras/CifrasEnLetras.php';

$v = new CifrasEnLetras();
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'Letter', 'margin_left' => 20, 'margin_right' => 20, 'margin_top' => 30, 'margin_bottom' => 25]); // Ajustar márgenes si es necesario

// Verificar si se recibió el código del préstamo
if (!isset($_GET['codigo']) || empty($_GET['codigo'])) {
    die("Error: No se proporcionó el código del préstamo.");
}

$query = "SELECT
    pc.*, 
    DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') AS pres_fecha_registro_formateada,
    DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') AS pres_f_emision_formateada,
                DAY(pc.pres_fecha_registro ) as dia,
				MONTH(pc.pres_fecha_registro ) as mes,
				YEAR(pc.pres_fecha_registro ) as anio,
    CASE MONTH(pc.pres_fecha_registro) 
									WHEN 1 THEN 'Enero'
        WHEN 2 THEN 'Febrero'
									WHEN 3 THEN 'Marzo' 
									WHEN 4 THEN 'Abril' 
									WHEN 5 THEN 'Mayo'
									WHEN 6 THEN 'Junio'
									WHEN 7 THEN 'Julio'
									WHEN 8 THEN 'Agosto'
									WHEN 9 THEN 'Septiembre'
									WHEN 10 THEN 'Octubre'
									WHEN 11 THEN 'Noviembre'
									WHEN 12 THEN 'Diciembre'
				END mesnombre,
    c.cliente_nombres, c.cliente_dni, c.cliente_direccion,
                fp.fpago_descripcion,
    mo.moneda_nombre, mo.moneda_simbolo,
    empresa.confi_razon, empresa.confi_ruc, empresa.confi_direccion, 
    empresa.config_correo, empresa.config_celular,
    empresa.config_logo,
				u.usuario
FROM prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
				INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario,
                empresa 
WHERE pc.nro_prestamo = '".htmlspecialchars($_GET['codigo'])."'";

$resultado = $mysqli->query($query);

// Verificar si la consulta falló
if ($resultado === false) {
    die("Error en la consulta SQL: " . $mysqli->error);
}

if ($row1 = $resultado->fetch_assoc()) {
    // Validar campos obligatorios
    $campos_obligatorios = [
        'nro_prestamo' => 'Número de préstamo',
        'cliente_nombres' => 'Nombre del cliente',
        'cliente_dni' => 'DNI del cliente',
        'cliente_direccion' => 'Dirección del cliente',
        'pres_monto' => 'Monto del préstamo',
        'pres_interes' => 'Interés del préstamo',
        'pres_cuotas' => 'Número de cuotas',
        'fpago_descripcion' => 'Forma de pago',
        'confi_razon' => 'Razón social de la empresa',
        'confi_direccion' => 'Dirección de la empresa',
        'moneda_simbolo' => 'Símbolo de moneda',
        'moneda_nombre' => 'Nombre de moneda'
    ];
    
    $campos_faltantes = [];
    
    foreach ($campos_obligatorios as $campo => $descripcion) {
        if (empty($row1[$campo])) {
            $campos_faltantes[] = $descripcion;
        }
    }
    
    if (!empty($campos_faltantes)) {
        die("Error: Faltan campos obligatorios para generar el contrato: " . implode(", ", $campos_faltantes));
    }

    $montoEnLetras = $v->convertirEurosEnLetras($row1['pres_monto']); // Asegúrate de que la función es correcta para tu moneda

    $html = '
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
  <title>Contrato de Préstamo</title>
  <style>
    body { 
        font-family: Arial, sans-serif; 
        font-size: 11pt; 
        color: #333; 
        line-height: 1.5;
    }
    .header-company {
        text-align: left; /* Alineado a la izquierda */
        margin-bottom: 25px;
        display: flex; /* Usar flexbox para alinear el logo y el texto */
        align-items: center;
    }
    .header-company img {
        width: 20px; /* Ancho del logo */
        height: 20px; /* Alto del logo */
        margin-right: 20px; /* Espacio entre el logo y el texto */
        object-fit: contain; /* Asegura que la imagen se ajuste dentro de los límites */
    }
    .header-info {
        text-align: left;
    }
    .header-info h1 {
        font-size: 18pt;
        margin: 0;
        color: #2c3e50;
    }
    .header-info p {
        font-size: 10pt; /* Mismo tamaño que el email */
        margin: 2px 0;
        color: #7f8c8d;
    }
    .title-document {
        font-size: 16pt;
        font-weight: bold;
        text-align: center;
        margin: 20px 0 30px;
        padding: 10px;
        background-color: #f2f2f2;
        border: 1px solid #ddd;
    }
    .clause-title {
        font-size: 12pt; /* Tamaño para los títulos de cláusula */
        font-weight: bold;
        margin-top: 15px;
        margin-bottom: 5px;
    }
    p {
        margin-bottom: 10px;
        text-align: justify;
    }
    .info-block {
        margin-top: 20px;
        padding: 15px;
        border: 1px solid #eee;
        background-color: #f9f9f9;
    }
    .info-item {
        margin-bottom: 5px;
    }
    .info-label {
        font-weight: bold;
        width: 120px;
        display: inline-block;
    }
    .signatures {
        margin-top: 50px;
        width: 100%;
        border-collapse: collapse;
    }
    .signatures td {
        width: 50%;
        text-align: center;
    }
    .signature-line {
        border-top: 1px solid #000;
        width: 150px; /* Reducir ancho de la línea de firma */
        margin: 0 auto 10px; /* Centrar la línea de firma */
    }
    .signature-text {
        font-size: 10pt;
        font-weight: bold;
        color: #555;
    }
    .footer-date {
        font-size: 8pt;
        text-align: right;
        margin-top: 30px;
        color: #888;
    }
  </style>
  </head>
  <body>
    <div class="header-company">
        <img src="../uploads/logos/' . htmlspecialchars($row1['config_logo']) . '" alt="Logo de la Empresa" width="20" height="20">
        <div class="header-info">
            <h1>' . htmlspecialchars($row1['confi_razon']) . '</h1>
            <p>RUC: ' . htmlspecialchars($row1['confi_ruc']) . '</p>
            <p>' . htmlspecialchars($row1['confi_direccion']) . '</p>
            <p>Email: ' . htmlspecialchars($row1['config_correo']) . '</p>
            <p>Contrato N°: ' . htmlspecialchars($row1['nro_prestamo']) . '</p> 
        </div>
    </div>
    
    <div class="title-document"> <!-- Quitar el style="display:none;" para mostrar el título -->
        CONTRATO DE PRÉSTAMO N° ' . htmlspecialchars($row1['nro_prestamo']) . '
    </div>
  
    <p>Conste por el presente documento que se suscribe, el contrato de préstamo de dinero que celebra de una parte la EMPRESA <b>' . htmlspecialchars($row1['confi_razon']) . '</b>, de la otra parte el (la) Sr.(a) <b>' . htmlspecialchars($row1['cliente_nombres']) . '</b> con Nro. de Documento: <b>' . htmlspecialchars($row1['cliente_dni']) . '</b> con domicilio en: <b>' . htmlspecialchars($row1['cliente_direccion']) . '</b>, quien en adelante se le denominará EL CLIENTE, para los efectos a que se contrae la cláusula adicional del presente, y los señores CLIENTES en los términos y condiciones siguientes:</p>

    <div class="section-title">CLÁUSULAS</div>

    <h4 class="clause-title">PRIMERA:</h4>
    <p><b>' . htmlspecialchars($row1['confi_razon']) . '</b> es una empresa jurídica cuyo objeto social es la prestación de toda clase de servicios financieros, otorgando o colocando créditos mediante préstamos con garantías reales y otros, con aplicación de tasas de interés acorde a las disposiciones vigentes, créditos dirigidos a personas naturales y jurídicas.</p>

    <h4 class="clause-title">SEGUNDA:</h4>
    <p>Del préstamo, <b>' . htmlspecialchars($row1['confi_razon']) . '</b> a solicitud del(los) CLIENTE(s), aprobó otorgarles un préstamo con el fin de que el(los) CLIENTE(s), puedan utilizarlo como consumo personal, activo fijo y/o capital de trabajo, dinero que es entregado en efectivo, sin utilizar medio de pago alguno, el que estará representado en una o más letras de pagos y/o pagaré.</p>

    <h4 class="clause-title">TERCERA:</h4>
    <p>El préstamo otorgado es de <b>' . htmlspecialchars($row1['moneda_simbolo']) . ' ' . number_format($row1['pres_monto'], 2) . ' ' . htmlspecialchars($row1['moneda_nombre']) . '</b> con una tasa del <b>' . htmlspecialchars($row1['pres_interes']) . '%</b> que generan cuotas del crédito otorgado.</p>

    <h4 class="clause-title">CUARTA:</h4>
    <p>El(los) CLIENTE(s), se compromete(n) a devolver el préstamo otorgado en el plazo de <b>' . htmlspecialchars($row1['pres_cuotas']) . ' Cuotas</b> mediante amortizaciones <b>' . htmlspecialchars($row1['fpago_descripcion']) . '</b> de acuerdo al cronograma entregado por la empresa.</p>

    <h4 class="clause-title">QUINTA:</h4>
    <p>El(los) CLIENTE(s), se comprometen a pagar sus cuotas (letras) puntualmente a <b>' . htmlspecialchars($row1['confi_razon']) . '</b>. Ante el incumplimiento del pago de una o más cuotas (letras) sucesivas, el(los) CLIENTE(s) se someterán al pago de los intereses, moras y más gastos causados por los trámites pertinentes.</p>

    <h4 class="clause-title">SEXTA:</h4>
    <p>En caso que el(los) cliente(s) no cumpliesen las condiciones de los pagos señalados según el cronograma, se obligará a informar al Sistema Central de Riesgo-INFOCORP por morosidad a los clientes, Cónyuges y/o Avalista(s).</p>

    <h4 class="clause-title">SÉPTIMA (DECLARACIONES):</h4>
    <p>Cliente, Cónyuge y/o Avalistas dejan expresa constancia que constituyen fianza solidaria, indivisible e ilimitada y por plazo indeterminado a favor de LOS CLIENTES, con el objeto de responder solidariamente por el cumplimiento de todas las obligaciones que éstos asumen con <b>' . htmlspecialchars($row1['confi_razon']) . '</b> en virtud del otorgamiento del crédito a que se refiere el presente contrato.</p>

    <h4 class="clause-title">OCTAVA (CLÁUSULAS ESPECIALES):</h4>
    <p>EL DEUDOR declara que: a) <b>' . htmlspecialchars($row1['confi_razon']) . '</b> ha cumplido con sus deberes en materiales de información y transparencia establecidos en la ley de protección de los Derechos de las Personas Consumidoras y Usuarios y en la Norma sobre Transparencia en las Operaciones de Micro Finanzas; b) Ha recibido de <b>' . htmlspecialchars($row1['confi_razon']) . '</b> 1- copia de cronograma de pagos; 2- Ha sido informado de las tasas de interés, condiciones del préstamo, comisiones, gastos, penalidades en caso que aplique y demás información necesaria sobre las características, términos y condiciones inherentes al préstamo aquí regulado; c) Haber leído y entendido el presente contrato; y d) Cumplirá con la obligación adquirida en estricto apego a las estipulaciones pactadas en este contrato suscrito, incluyendo pagar lo adeudado en tiempo, modo y condiciones establecidas en el presente contrato.</p>

    <h4 class="clause-title">NOVENA (ACEPTACIÓN CONJUNTA):</h4>
    <p>Por medio del presente contrato EL ACREEDOR y EL DEUDOR, aceptamos conjuntamente todas las declaraciones y obligaciones, en los términos establecidos en las cláusulas anteriores. En señal de aceptación y voluntad, firmamos todos.</p>

    <p>Se suscribe el presente contrato, en ' . htmlspecialchars($row1['confi_direccion']) . ', ' . htmlspecialchars($row1['dia']) . ' de ' . htmlspecialchars($row1['mesnombre']) . ' del ' . htmlspecialchars($row1['anio']) . '.</p>

    <div class="signatures">
        <table>
            <tr>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-text">' . htmlspecialchars($row1['confi_razon']) . '</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-text">' . htmlspecialchars($row1['cliente_nombres']) . '</div>
                    <div class="signature-text">' . htmlspecialchars($row1['cliente_dni']) . '</div>
                </td>
            </tr>
        </table>
    </div>
  
  </body>
</html>';

    $mpdf->SetHTMLHeader('<div style="text-align: right; font-size: 8pt; color: #888;">CONTRATO DE PRESTAMO N° ' . htmlspecialchars($row1['nro_prestamo']) . '</div>');
    $mpdf->SetFooter('Fecha de Impresión: {DATE j/m/Y} | Página {PAGENO}/{nbpg}');
    $mpdf->WriteHTML($html);
    $mpdf->Output('contrato_prestamo_' . htmlspecialchars($row1['nro_prestamo']) . '.pdf', 'I'); // Enviar el PDF al navegador
} else {
    echo "Error: No se encontró el préstamo con código: " . htmlspecialchars($_GET['codigo']);
}

$mysqli->close();
