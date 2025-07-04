<?php

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require 'numeroletras/CifrasEnLetras.php';
//Incluímos la clase pago
$v = new CifrasEnLetras();
$mpdf = new \Mpdf\Mpdf();
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
                pc.pres_f_emision ,
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
while ($row1 = $resultado->fetch_assoc()) {




    $html = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Contrato</title>
    <link rel="stylesheet"  media="all" />
  </head>
  <body>
    <div class="content" style="text-align:center;"> 
        <h4> CONTRATO DE PRESTAMO N&ordm; &nbsp;' . $row1['nro_prestamo'] . '  </h4>
    </div>
    <div class="cuerpo" > 
        <p align="left"> Conste por el presente documento que se suscribe, el contrato de pr&eacute;stamo de dinero que celebra 
        de una parte la  EMPRESA <b>' . $row1['confi_razon'] . ' </b>  de  la otra parte  el (la) Sr.(A) <b>' . $row1['cliente_nombres'] . ' </b> 
        Con   Nro de Documento :   <b>' . $row1['cliente_dni'] . ' </b>   con   domicilio   en:   <b>' . $row1['cliente_direccion'] . ' </b>, 
        quien en   adelante   se   le   denominar&aacute;  EL  CLIENTE,   para  los  efectos   a   que  se  contrae  la  cl&aacute;usula adicional del presente, y los se&ntilde;ores CLIENTES 
        en los t&eacute;rminos y condiciones siguientes: </p>

        <p align="left"> <b><u>PRIMERO:</u></b>       <b>' . $row1['confi_razon'] . ' </b> es   una   empresa   jur&iacute;dica   cuyo   objeto   social   es   la 
        prestaci&oacute;n   de toda   clase de servicios financieros, otorgando o colocando   cr&eacute;ditos mediante 
        pr&eacute;stamos   con   garant&iacute;as   reales   y   otros, con   aplicaci&oacute;n   de   tasas   de   inter&eacute;s   acorde   a   las 
        disposiciones vigentes, cr&eacute;ditos dirigidos a personas naturales y jur&iacute;dicas. </p>

        <p align="left"> <b><u>SEGUNDO:</u></b>  Del pr&eacute;stamo, <b>' . $row1['confi_razon'] . ' </b> a solicitud del(los) CLIENTE(s), aprob&oacute; otorgarles 
        un pr&eacute;stamo con el fin de que el(los) CLIENTE(s), puedan utilizarlo como consumo personal, activo 
        fijo   y/o   capital   de trabajo, dinero que es entregado en   efectivo, sin utilizar medio de pago 
        alguno, el que estar&aacute; representado en una o m&aacute;s letras de pagos y/o pagar&eacute;. </p>

        <p align="left"> <b><u>TERCERO:</u></b> El pr&eacute;stamo otorgado es de <b>'.$row1['moneda_simbolo'].'. '.$row1['pres_monto'].'.00 '.$row1['moneda_nombre'].'</b> con una tasa del 
        <b>'.$row1['pres_interes'].'%</b> que generan cuotas del cr&eacute;dito otorgado.  </p>

        <p align="left"> <b><u>CUARTO:</u></b>   El(los) CLIENTE(s), se compromete(n) a devolver el pr&eacute;stamo otorgado en el plazo de <b>'.$row1['pres_cuotas'].' Cuotas</b> 
        mediante amortizaciones <b>' . $row1['fpago_descripcion'] . '</b>  de acuerdo el cronograma entregado por la empresa.</p>

        <p align="left"> <b><u>QUINTO:</u></b>   El(los) CLIENTE(s), se comprometen   a pagar sus   cuotas (letras) puntualmente a 
        <b>' . $row1['confi_razon'] . ',</b> Ante   el   incumplimiento   del   pago   de   una   o   m&aacute;s   cuotas (letras) 
        sucesivas, el(los) CLIENTE(s) se   someter&aacute;n   al pago   de   los   intereses, moras   y   m&aacute;s   gastos 
        causados por los tr&aacute;mites pertinentes.   </p>

        <p align="left"> <b><u>SEXTO:</u></b>     En caso que el(los) cliente(s) no cumpliesen las condiciones de los pagos se&ntilde;alados seg&uacute;n 
        el cronograma, se obligar&aacute; a informar al Sistema Central de Riesgo-INFOCORP por morosidad a los 
        clientes, C&oacute;nyuges y/o Avalista(s).  </p>

        <p align="left"> <b><u>SEPTIMO(DECLARACIÓNES):</u></b> Cliente, C&oacute;nyuge y/o Avalistas dejan expresa constancia que constituyen fianza solidaria, 
        indivisible e ilimitada y por plazo indeterminado a favor de LOS CLIENTES, con el objeto de responder 
        solidariamente por el cumplimiento de todas las obligaciones que &eacute;stos asumen con <b>' . $row1['confi_razon'] . '</b> 
        en virtud del otorgamiento del cr&eacute;dito a que se refiere el presente contrato.  </p>

           <p align="left"> <b><u>OCTAVA(CLAUSULA ESPECIALES):</u></b> EL DEUDOR declara que: a),<b>' . $row1['confi_razon'] . '</b>  
         ha cumplido con sus deberes en materiales de información y transparencia establecidos en la ley de protección de los Derechos de las
         Personas Consumidoras y Usuarios y en la Norma sobre Transparencia en las Operaciones de Micro Finanzas, b) 
         Ha recibido de <b>' . $row1['confi_razon'] . '</b>  1- copia de cronograma de pagos, 2- Ha sido informado de las tasas de interés,
         condiciones del préstamo, comisiones, gastos, penalidades en caso que aplique y demás información necesaria sobre las características, 
         términos y condiciones inherentes al préstamo aquí regulado, d)  Haber leído y entendido el presente contrato y f) Cumplirá con la obligación 
         adquirida en estricto apego a las estipulaciones pactadas en este contrato suscrito, incluyendo pagar lo adeudado en tiempo, modo, y condiciones
          establecidas en el presente contrato.  </p>

        <p align="left"> <b><u>NOVENA (ACEPTACIÓN CONJUNTA): :</u></b>Por medio del presente contrato EL ACREEDOR y EL DEUDOR, 
        aceptamos conjuntamente tosas las declaraciones y obligaciones, en los términos establecidos en las clausulas anteriores. En señal de aceptación y voluntad, firmamos todos.  </p>

        <p align="left">Se   suscribe   el   presente   contrato, en   ' . $row1['confi_direccion'] . ',       ' . $row1['dia'] . '   de ' . $row1['mesnombre'] . ' del ' . $row1['anio'] . '.  </p>
        <p align="center"> </p>
        <p align="center"> </p>
        <p align="center"> </p>
        <p align="center"> </p>
        <p align="center">  ___________________________   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    ___________________________</p>
        <p align="center">  &nbsp;'.$row1['confi_razon'].'   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;   '.$row1['cliente_nombres'].'</p>
        <p align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$row1['cliente_dni'].' </p>
       

    </div>
  
 	
 
    ';
    $html .= '
  
  </body>
</html>';
}
//$css = file_get_contents('css/style_coti.css');
// $mpdf->WriteHTML($css,1);
//$mpdf->WriteHTML(1);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output();
