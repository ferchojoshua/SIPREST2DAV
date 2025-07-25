<?php

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require 'numeroletras/CifrasEnLetras.php';

$v = new CifrasEnLetras();
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);

$query = "SELECT
                pc.pres_id,
                pc.nro_prestamo,
                pc.cliente_id,
                c.cliente_nombres,
                c.cliente_dni,
                c.cliente_direccion,
                pc.pres_monto,
                pc.pres_interes,
                pc.pres_cuotas,
                pc.pres_fecha_registro,
                DAY(pc.pres_fecha_registro ) as dia,
				MONTH(pc.pres_fecha_registro ) as mes,
				YEAR(pc.pres_fecha_registro ) as anio,
				case month(pc.pres_fecha_registro) 
									WHEN 1 THEN 'Enero'
									WHEN 2 THEN  'Febrero'
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
                pc.fpago_id,
                fp.fpago_descripcion,
                pc.pres_f_emision,
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
                pc.pres_estado,
				pc.id_usuario,
				u.usuario
                FROM
                prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
				INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario,
                empresa 
                WHERE
                pc.nro_prestamo = '".$_GET['codigo']."'";

$resultado = $mysqli->query($query);

if ($row1 = $resultado->fetch_assoc()) {
    $montoEnLetras = $v->convertirEurosEnLetras($row1['pres_monto']);

    // Verificar si existe logo de empresa personalizado
    $logo_empresa = '';
    if (!empty($row1['config_logo']) && file_exists('../uploads/logos/' . $row1['config_logo'])) {
        $logo_empresa = '../uploads/logos/' . $row1['config_logo'];
    } else {
        // Logo por defecto si no existe logo de empresa
        $logo_empresa = 'img/logo.png';
    }

    $html = '
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="utf-8">
        <title>Contrato de Préstamo</title>
        <style>
            body { 
                font-family: "Times New Roman", serif; 
                font-size: 12px; 
                color: #333; 
                margin: 30px;
                line-height: 1.6;
                text-align: justify;
            }
            
            .header-empresa {
                text-align: center;
                border-bottom: 3px solid #2c3e50;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            
            .logo-empresa {
                width: 120px;
                height: auto;
                margin-bottom: 15px;
            }
            
            .razon-social {
                font-size: 20px;
                font-weight: bold;
                color: #2c3e50;
                margin: 10px 0;
            }
            
            .info-empresa {
                font-size: 12px;
                color: #7f8c8d;
                margin: 5px 0;
            }
            
            .titulo-contrato {
                font-size: 18px;
                font-weight: bold;
                text-align: center;
                color: #2c3e50;
                margin: 30px 0;
                padding: 15px;
                background-color: #ecf0f1;
                border-radius: 8px;
                border-left: 5px solid #3498db;
            }
            
            .datos-contrato {
                background-color: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                margin: 25px 0;
                border-left: 4px solid #3498db;
            }
            
            .dato-item {
                margin-bottom: 8px;
                font-size: 11px;
            }
            
            .dato-label {
                font-weight: bold;
                color: #2c3e50;
                display: inline-block;
                width: 120px;
            }
            
            .clausula {
                margin: 20px 0;
                text-indent: 0;
            }
            
            .clausula-titulo {
                font-weight: bold;
                color: #2c3e50;
                text-decoration: underline;
                margin-bottom: 10px;
            }
            
            .clausula-texto {
                text-align: justify;
                margin-bottom: 15px;
                line-height: 1.7;
            }
            
            .monto-destacado {
                background-color: #e8f5e8;
                padding: 10px;
                border-radius: 5px;
                font-weight: bold;
                color: #27ae60;
                display: inline-block;
                margin: 5px 0;
            }
            
            .seccion-firmas {
                margin-top: 80px;
                text-align: center;
            }
            
            .firma-tabla {
                width: 100%;
                margin-top: 60px;
            }
            
            .firma-celda {
                text-align: center;
                vertical-align: bottom;
                padding: 40px 20px 0;
            }
            
            .firma-linea {
                border-top: 2px solid #2c3e50;
                width: 200px;
                margin: 0 auto 10px;
            }
            
            .firma-texto {
                font-weight: bold;
                color: #2c3e50;
                margin-bottom: 5px;
            }
            
            .firma-nombre {
                font-size: 11px;
                color: #7f8c8d;
            }
            
            .pie-contrato {
                margin-top: 40px;
                text-align: center;
                font-size: 10px;
                color: #95a5a6;
                padding-top: 20px;
                border-top: 1px solid #bdc3c7;
            }
            
            .destacado {
                font-weight: bold;
                color: #2c3e50;
            }
        </style>
    </head>
    <body>
        <div class="header-empresa">
            <img src="' . $logo_empresa . '" class="logo-empresa" alt="Logo Empresa">';
        
        $html .= '<div class="razon-social">' . $row1['confi_razon'] . '</div>
            <div class="info-empresa">RUC: ' . $row1['confi_ruc'] . '</div>
            <div class="info-empresa">' . $row1['confi_direccion'] . '</div>';
            
        // Agregar celular si existe
        if (!empty($row1['config_celular'])) {
            $html .= '<div class="info-empresa">Tel: ' . $row1['config_celular'] . '</div>';
        }
        
        // Agregar email si existe
        if (!empty($row1['config_correo'])) {
            $html .= '<div class="info-empresa">Email: ' . $row1['config_correo'] . '</div>';
        }
        
        $html .= '</div>
        
        <div class="titulo-contrato">
            CONTRATO DE PRÉSTAMO N° ' . $row1['nro_prestamo'] . '
        </div>
        
        <div class="datos-contrato">
            <div class="dato-item">
                <span class="dato-label">Cliente:</span> ' . $row1['cliente_nombres'] . '
            </div>
            <div class="dato-item">
                <span class="dato-label">Documento:</span> ' . $row1['cliente_dni'] . '
            </div>
            <div class="dato-item">
                <span class="dato-label">Dirección:</span> ' . $row1['cliente_direccion'] . '
            </div>
            <div class="dato-item">
                <span class="dato-label">Monto:</span> ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pres_monto'], 2) . '
            </div>
            <div class="dato-item">
                <span class="dato-label">Interés:</span> ' . $row1['pres_interes'] . '% | 
                <span class="dato-label">Cuotas:</span> ' . $row1['pres_cuotas'] . ' | 
                <span class="dato-label">F. Pago:</span> ' . $row1['fpago_descripcion'] . '
            </div>
        </div>
        
        <div class="clausula-texto">
            Conste por el presente documento que se suscribe, el contrato de préstamo de dinero que celebra 
            de una parte la EMPRESA <span class="destacado">' . $row1['confi_razon'] . '</span> de la otra parte el (la) Sr.(A) 
            <span class="destacado">' . $row1['cliente_nombres'] . '</span> con Nro de Documento: 
            <span class="destacado">' . $row1['cliente_dni'] . '</span> con domicilio en: 
            <span class="destacado">' . $row1['cliente_direccion'] . '</span>, quien en adelante se le 
            denominará EL CLIENTE, para los efectos a que se contrae la cláusula adicional del presente, 
            y los señores CLIENTES en los términos y condiciones siguientes:
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">PRIMERO:</div>
            <div class="clausula-texto">
                <span class="destacado">' . $row1['confi_razon'] . '</span> es una empresa jurídica cuyo objeto social es la 
                prestación de toda clase de servicios financieros, otorgando o colocando créditos mediante 
                préstamos con garantías reales y otros, con aplicación de tasas de interés acorde a las 
                disposiciones vigentes, créditos dirigidos a personas naturales y jurídicas.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">SEGUNDO:</div>
            <div class="clausula-texto">
                Del préstamo, <span class="destacado">' . $row1['confi_razon'] . '</span> a solicitud del(los) CLIENTE(s), 
                aprobó otorgarles un préstamo con el fin de que el(los) CLIENTE(s), puedan utilizarlo como consumo personal, 
                activo fijo y/o capital de trabajo, dinero que es entregado en efectivo, sin utilizar medio de pago 
                alguno, el que estará representado en una o más letras de pagos y/o pagaré.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">TERCERO:</div>
            <div class="clausula-texto">
                El préstamo otorgado es de: 
                <div class="monto-destacado">
                    ' . $row1['moneda_simbolo'] . ' ' . number_format($row1['pres_monto'], 2) . ' ' . $row1['moneda_nombre'] . '<br>
                    (' . $montoEnLetras . ')
                </div>
                con una tasa del <span class="destacado">' . $row1['pres_interes'] . '%</span> que generan cuotas del crédito otorgado.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">CUARTO:</div>
            <div class="clausula-texto">
                El(los) CLIENTE(s), se compromete(n) a devolver el préstamo otorgado en el plazo de 
                <span class="destacado">' . $row1['pres_cuotas'] . ' Cuotas</span> mediante amortizaciones 
                <span class="destacado">' . $row1['fpago_descripcion'] . '</span> de acuerdo el cronograma entregado por la empresa.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">QUINTO:</div>
            <div class="clausula-texto">
                El(los) CLIENTE(s), se comprometen a pagar sus cuotas (letras) puntualmente a 
                <span class="destacado">' . $row1['confi_razon'] . '</span>. Ante el incumplimiento del pago de una o más cuotas (letras) 
                sucesivas, el(los) CLIENTE(s) se someterán al pago de los intereses, moras y más gastos 
                causados por los trámites pertinentes.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">SEXTO:</div>
            <div class="clausula-texto">
                En caso que el(los) cliente(s) no cumpliesen las condiciones de los pagos señalados según 
                el cronograma, se obligará a informar al Sistema Central de Riesgo-INFOCORP por morosidad a los 
                clientes, Cónyuges y/o Avalista(s).
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">SÉPTIMO (DECLARACIONES):</div>
            <div class="clausula-texto">
                Cliente, Cónyuge y/o Avalistas dejan expresa constancia que constituyen fianza solidaria, 
                indivisible e ilimitada y por plazo indeterminado a favor de LOS CLIENTES, con el objeto de responder 
                solidariamente por el cumplimiento de todas las obligaciones que éstos asumen con 
                <span class="destacado">' . $row1['confi_razon'] . '</span> en virtud del otorgamiento del crédito a que se refiere el presente contrato.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">OCTAVO (CLÁUSULAS ESPECIALES):</div>
            <div class="clausula-texto">
                EL DEUDOR declara que: a) <span class="destacado">' . $row1['confi_razon'] . '</span> ha cumplido con sus deberes en materiales de información y transparencia establecidos en la ley de protección de los Derechos de las Personas Consumidoras y Usuarios y en la Norma sobre Transparencia en las Operaciones de Micro Finanzas, b) Ha recibido de <span class="destacado">' . $row1['confi_razon'] . '</span> 1- copia de cronograma de pagos, 2- Ha sido informado de las tasas de interés, condiciones del préstamo, comisiones, gastos, penalidades en caso que aplique y demás información necesaria sobre las características, términos y condiciones inherentes al préstamo aquí regulado, d) Haber leído y entendido el presente contrato y f) Cumplirá con la obligación adquirida en estricto apego a las estipulaciones pactadas en este contrato suscrito, incluyendo pagar lo adeudado en tiempo, modo, y condiciones establecidas en el presente contrato.
            </div>
        </div>
        
        <div class="clausula">
            <div class="clausula-titulo">NOVENO (ACEPTACIÓN CONJUNTA):</div>
            <div class="clausula-texto">
                Por medio del presente contrato EL ACREEDOR y EL DEUDOR, aceptamos conjuntamente todas las declaraciones y obligaciones, en los términos establecidos en las cláusulas anteriores. En señal de aceptación y voluntad, firmamos todos.
            </div>
        </div>
        
        <div class="clausula-texto" style="margin-top: 30px;">
            Se suscribe el presente contrato, en <span class="destacado">' . $row1['confi_direccion'] . '</span>, 
            el <span class="destacado">' . $row1['dia'] . ' de ' . $row1['mesnombre'] . ' del ' . $row1['anio'] . '</span>.
        </div>
        
        <table class="firma-tabla">
            <tr>
                <td class="firma-celda">
                    <div class="firma-linea"></div>
                    <div class="firma-texto">' . $row1['confi_razon'] . '</div>
                    <div class="firma-nombre">EL ACREEDOR</div>
                </td>
                <td class="firma-celda">
                    <div class="firma-linea"></div>
                    <div class="firma-texto">' . $row1['cliente_nombres'] . '</div>
                    <div class="firma-nombre">EL DEUDOR<br>' . $row1['cliente_dni'] . '</div>
                </td>
            </tr>
        </table>
        
        <div class="pie-contrato">
            Documento generado automáticamente por el sistema - ' . $row1['confi_razon'] . '<br>
            Dirección: ' . $row1['confi_direccion'] . ' | RUC: ' . $row1['confi_ruc'] . ' | Email: ' . $row1['config_correo'] . '<br>
            Fecha de generación: ' . date('d/m/Y H:i:s') . '
        </div>
    </body>
    </html>';

    $mpdf->WriteHTML($html);
    $mpdf->Output();
} else {
    echo "No se encontró el préstamo.";
}
?>
