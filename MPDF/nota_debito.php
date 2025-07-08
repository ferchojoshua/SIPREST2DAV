<?php

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require '../controladores/notas_debito_controlador.php';
require '../modelos/admin_prestamos_modelo.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

if (!isset($_GET['nota']) || empty($_GET['nota'])) {
    echo "Error: No se especificó el número de nota de débito.";
    exit;
}

$nro_nota_debito = $_GET['nota'];

// Obtener datos de la nota de débito
$datos = NotasDebitoControlador::ctrObtenerDatosNotaDebito($nro_nota_debito);

if (!$datos) {
    echo "Error: No se encontraron datos para la nota de débito especificada.";
    exit;
}

// Verificar si existe logo de empresa personalizado
$logo_empresa = '';
if (!empty($datos['config_logo']) && file_exists('../uploads/logos/' . $datos['config_logo'])) {
    $logo_empresa = '../uploads/logos/' . $datos['config_logo'];
} else {
    // Logo por defecto si no existe logo de empresa
    $logo_empresa = 'img/logo.png';
}

$html = '
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Nota de Débito - ' . $datos['nro_nota_debito'] . '</title>
    <style>
        body { 
            font-family: "Times New Roman", serif; 
            font-size: 11px; 
            color: #333; 
            margin: 30px;
            line-height: 1.4;
        }
        
        .header-empresa {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .logo-empresa {
            width: 100px;
            height: auto;
            margin-bottom: 10px;
        }
        
        .razon-social {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
            margin: 8px 0;
        }
        
        .info-empresa {
            font-size: 10px;
            color: #7f8c8d;
            margin: 3px 0;
        }
        
        .titulo-documento {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            color: #e74c3c;
            margin: 25px 0;
            padding: 12px;
            background-color: #f8f9fa;
            border: 2px solid #e74c3c;
            border-radius: 5px;
        }
        
        .info-nota {
            width: 100%;
            margin: 20px 0;
        }
        
        .info-nota td {
            padding: 5px;
            vertical-align: top;
        }
        
        .label-info {
            font-weight: bold;
            color: #2c3e50;
            width: 150px;
        }
        
        .seccion {
            margin: 25px 0;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            overflow: hidden;
        }
        
        .seccion-titulo {
            background-color: #34495e;
            color: white;
            padding: 10px;
            font-weight: bold;
            font-size: 12px;
        }
        
        .seccion-contenido {
            padding: 15px;
            background-color: #ecf0f1;
        }
        
        .comparacion-tabla {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        
        .comparacion-tabla th,
        .comparacion-tabla td {
            border: 1px solid #bdc3c7;
            padding: 8px;
            text-align: center;
        }
        
        .comparacion-tabla th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        
        .valor-anterior {
            background-color: #f39c12;
            color: white;
            font-weight: bold;
        }
        
        .valor-nuevo {
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }
        
        .motivo-seccion {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .motivo-titulo {
            font-weight: bold;
            color: #856404;
            margin-bottom: 8px;
        }
        
        .resumen-financiero {
            background-color: #d1ecf1;
            border: 1px solid #17a2b8;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
        }
        
        .resumen-titulo {
            font-weight: bold;
            color: #0c5460;
            margin-bottom: 10px;
            font-size: 12px;
        }
        
        .firmas {
            margin-top: 50px;
            text-align: center;
        }
        
        .firma-tabla {
            width: 100%;
            margin-top: 40px;
        }
        
        .firma-celda {
            text-align: center;
            vertical-align: bottom;
            padding: 30px 15px 0;
        }
        
        .firma-linea {
            border-top: 1px solid #2c3e50;
            width: 180px;
            margin: 0 auto 8px;
        }
        
        .firma-texto {
            font-weight: bold;
            color: #2c3e50;
            font-size: 10px;
        }
        
        .pie-documento {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #95a5a6;
            border-top: 1px solid #bdc3c7;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header-empresa">
        <img src="' . $logo_empresa . '" class="logo-empresa" alt="Logo Empresa">
        <div class="razon-social">' . $datos['confi_razon'] . '</div>
        <div class="info-empresa">RUC: ' . $datos['confi_ruc'] . '</div>
        <div class="info-empresa">' . $datos['empresa_direccion'] . '</div>';

// Agregar contactos si existen
if (!empty($datos['config_celular'])) {
    $html .= '<div class="info-empresa">Tel: ' . $datos['config_celular'] . '</div>';
}
if (!empty($datos['config_correo'])) {
    $html .= '<div class="info-empresa">Email: ' . $datos['config_correo'] . '</div>';
}

$html .= '</div>
    
    <div class="titulo-documento">NOTA DE DÉBITO N° ' . $datos['nro_nota_debito'] . '</div>
    
    <table class="info-nota">
        <tr>
            <td class="label-info">Préstamo N°:</td>
            <td>' . $datos['nro_prestamo'] . '</td>
            <td class="label-info">Fecha de Emisión:</td>
            <td>' . date('d/m/Y H:i', strtotime($datos['fecha_registro'])) . '</td>
        </tr>
        <tr>
            <td class="label-info">Cliente:</td>
            <td>' . $datos['cliente_nombres'] . '</td>
            <td class="label-info">Documento:</td>
            <td>' . $datos['cliente_dni'] . '</td>
        </tr>
        <tr>
            <td class="label-info">Dirección:</td>
            <td colspan="3">' . $datos['cliente_direccion'] . '</td>
        </tr>
        <tr>
            <td class="label-info">Procesado por:</td>
            <td>' . $datos['usuario'] . '</td>
            <td class="label-info">Forma de Pago:</td>
            <td>' . $datos['fpago_descripcion'] . '</td>
        </tr>
    </table>
    
    <div class="motivo-seccion">
        <div class="motivo-titulo">MOTIVO DEL AJUSTE:</div>
        <div>' . $datos['motivo'] . '</div>
    </div>
    
    <div class="seccion">
        <div class="seccion-titulo">COMPARACIÓN DE CONDICIONES</div>
        <div class="seccion-contenido">
            <table class="comparacion-tabla">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th>Condición Anterior</th>
                        <th>Condición Nueva</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Tasa de Interés</strong></td>
                        <td class="valor-anterior">' . number_format($datos['interes_anterior'], 2) . '%</td>
                        <td class="valor-nuevo">' . number_format($datos['interes_nuevo'], 2) . '%</td>
                    </tr>
                    <tr>
                        <td><strong>Número de Cuotas</strong></td>
                        <td class="valor-anterior">' . $datos['cuotas_anterior'] . ' cuotas</td>
                        <td class="valor-nuevo">' . $datos['cuotas_nuevas'] . ' cuotas</td>
                    </tr>
                    <tr>
                        <td><strong>Valor de Cuota</strong></td>
                        <td class="valor-anterior">' . $datos['moneda_simbolo'] . ' ' . number_format($datos['cuota_anterior'], 2) . '</td>
                        <td class="valor-nuevo">' . $datos['moneda_simbolo'] . ' ' . number_format($datos['cuota_nueva'], 2) . '</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="resumen-financiero">
        <div class="resumen-titulo">RESUMEN FINANCIERO</div>
        <table style="width: 100%;">
            <tr>
                <td><strong>Saldo de Capital Pendiente:</strong></td>
                <td style="text-align: right;"><strong>' . $datos['moneda_simbolo'] . ' ' . number_format($datos['saldo_capital'], 2) . '</strong></td>
            </tr>
            <tr>
                <td><strong>Nuevos Intereses a Generar:</strong></td>
                <td style="text-align: right;">' . $datos['moneda_simbolo'] . ' ' . number_format($datos['monto_interes_nuevo'], 2) . '</td>
            </tr>
            <tr style="border-top: 1px solid #17a2b8;">
                <td><strong>Nuevo Monto Total a Pagar:</strong></td>
                <td style="text-align: right;"><strong>' . $datos['moneda_simbolo'] . ' ' . number_format($datos['monto_total_nuevo'], 2) . '</strong></td>
            </tr>
        </table>
    </div>
    
    <div style="margin: 25px 0; padding: 10px; border: 1px solid #dc3545; background-color: #f8d7da; color: #721c24; border-radius: 5px;">
        <strong>IMPORTANTE:</strong> Esta nota de débito modifica las condiciones originales del préstamo. 
        El nuevo cronograma de pagos entrará en vigencia a partir de la fecha de emisión de este documento.
    </div>
    
    <div class="firmas">
        <table class="firma-tabla">
            <tr>
                <td class="firma-celda">
                    <div class="firma-linea"></div>
                    <div class="firma-texto">' . $datos['confi_razon'] . '</div>
                    <div class="firma-texto">ACREEDOR</div>
                </td>
                <td class="firma-celda">
                    <div class="firma-linea"></div>
                    <div class="firma-texto">' . $datos['cliente_nombres'] . '</div>
                    <div class="firma-texto">DEUDOR</div>
                    <div class="firma-texto">' . $datos['cliente_dni'] . '</div>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="pie-documento">
        Nota de Débito generada automáticamente por el sistema - ' . $datos['confi_razon'] . '<br>
        Fecha de generación: ' . date('d/m/Y H:i:s') . '<br>
        Este documento modifica las condiciones del préstamo original
    </div>
</body>
</html>';

$mpdf->WriteHTML($html);
$mpdf->Output();
?> 