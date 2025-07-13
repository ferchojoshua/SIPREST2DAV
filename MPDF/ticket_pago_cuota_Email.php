<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 130]]);

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
                DATE_FORMAT(pdetalle_fecha_registro, '%d/%m/%y %r') as pdetalle_fecha_registro,
                pd.pdetalle_saldo_cuota,
                pd.pdetalle_cant_cuota_pagada ,
                c.cliente_correo,
                empresa.config_celular
                FROM
                prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
                INNER JOIN prestamo_detalle pd ON pd.nro_prestamo = pc.nro_prestamo,
                empresa 
                WHERE
                pc.nro_prestamo ='".$_GET['codigo']."' and pd.pdetalle_nro_cuota = '".$_GET['cuota']."'";

	$resultado = $mysqli ->query($query);
while ($row1 = $resultado-> fetch_assoc()){
    $confi_razon = $row1['confi_razon'];
    $confi_ruc = $row1['confi_ruc'];
    $nro_pre = $row1['nro_prestamo'];
    $f_registro = $row1['pdetalle_fecha_registro'];
    $nombCli = $row1['cliente_nombres'];
    $cliente_dni = $row1['cliente_dni'];
    $fpago = $row1['fpago_descripcion'];
    $moned = $row1['moneda_nombre'];
    $ncuota = $row1['pdetalle_nro_cuota'];
    $c_cuo_paga = $row1['pdetalle_cant_cuota_pagada'];
    $pres_cuotas = $row1['pres_cuotas'];
    $moned_s = $row1['moneda_simbolo'];
    $monto_Cuo = $row1['pdetalle_monto_cuota'];
    $saldo_cuo = $row1['pdetalle_saldo_cuota'];

    

    $correoEmpresa = $row1['config_correo'];
    $correoCliente = $row1['cliente_correo'];
   
    $celEm = $row1['config_celular'];
    



$html.='
	<h4 style="text-align:center;display: inline-block;margin: 0px;padding: 0px; "> '.$row1['confi_razon'].'</h4>
	<h5 style="text-align:center;display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">R.U.C '.$row1['confi_ruc'].'</h5><br>
	------------------------------------------<br>

    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-size:12px">N. Prestamo:&nbsp; '.$row1['nro_prestamo'].' &nbsp;</h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px; ">Fecha : '.$row1['pdetalle_fecha_registro'].'</h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;">Prestatario:&nbsp; <br>  '.$row1['cliente_nombres'].' </h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;">Documento:&nbsp;  '.$row1['cliente_dni'].'</h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;"> F. Pago: '.$row1['fpago_descripcion'].'</h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;"> Moneda: &nbsp;  '.$row1['moneda_nombre'].'</h6>
	
        ------------------------------------------<br>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;"> Nro Cuota:&nbsp;  '.$row1['pdetalle_nro_cuota'].'</h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;">Cuotas restantes:&nbsp; '.$row1['pdetalle_cant_cuota_pagada'].' /'.$row1['pres_cuotas'].' </h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;"><b>Monto Pagado :&nbsp; '.$row1['moneda_simbolo'].' &nbsp;'.$row1['pdetalle_monto_cuota'].'</b></h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;"> Saldo por Pagar :&nbsp; '.$row1['moneda_simbolo'].' &nbsp;'.$row1['pdetalle_saldo_cuota'].'</h6><br><br>

';


     
 }




// $css = file_get_contents('css/style_cuota.css');
// $mpdf->WriteHTML($css,1);
$mpdf->SetMargins(0,0,8);
$mpdf->WriteHTML(utf8_encode($html));

$razon_S = $confi_razon;
$ruc = $confi_ruc;
$nro_prestamo = $nro_pre;
$fecha = $f_registro;
$nombreCliente =   $nombCli;
$clienteDni = $cliente_dni;
$formaPago = $fpago;
$moneda = $moned;
$nro_cuota =$ncuota;
$cant_cuotas_pagadas =  $c_cuo_paga;
$cuotas =  $pres_cuotas;
$simbolo_mone = $moned_s;
$monto_cuotas = $monto_Cuo;
$saldo_Cuotas = $saldo_cuo;



$correoE = $correoEmpresa;
$correoCli = $correoCliente;
$celular = $celEm;

$pdf = $mpdf->Output("", 'S');
$mpdf->Output();




sendEmail($pdf,$correoE, $correoCli, $razon_S, $ruc, $nro_prestamo, $fecha, $nombreCliente, $clienteDni, $formaPago, $moneda, $nro_cuota, $cant_cuotas_pagadas, $cuotas, $simbolo_mone, $monto_cuotas , $saldo_Cuotas);

function sendEmail($pdf,$correoE, $correoCli,  $razon_S, $ruc, $nro_prestamo, $fecha, $nombreCliente, $clienteDni, $formaPago, $moneda, $nro_cuota, $cant_cuotas_pagadas, $cuotas, $simbolo_mone, $monto_cuotas , $saldo_Cuotas)
{
	$mail = new PHPMailer(true);

	try {
		$mail = new PHPMailer();
		//Server settings
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;
		//Enable SMTP authentication
		$mail->Username   = $correoE;                     //SMTP username
		$mail->Password   = 'Sipresta2025';                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom($correoE, $razon_S); //DE:
		$mail->addAddress($correoCli, 'Correo Cliente'); //PARA:
		$mail->addStringAttachment($pdf, "attachment.pdf"); //ARCHIVO PDF

		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'RECIBO DE CUOTA PAGADA NRO: ' .$nro_cuota;


		$mail->Body    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
                            <html xmlns="">
                            <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                            <title>SISTEMA DE PRESTAMOS - SIPRESTA</title>
                            </head>
                            <body bgcolor="#EDEDED" style="background-color:#EDEDED; margin:0;">
                            <table align="center" width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color:#FFF;">
                            <tr>
                                <td>
                                <table width="670" border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tr>
                                    <td>
                                        <table width="670" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                        </table>
                                        <table width="670" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="15"></td>
                                        </tr>
                                        </table>
                                        <table width="650" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#FFFFFF">
                                        <tr>
                                            <td>
                                            <table width="628" border="0" cellspacing="0" cellpadding="0" align="center">
                                                <tr>
                                                <td height="20"></td>
                                                </tr>
                                            </table>
                                            <table width="628" border="0" cellspacing="0" cellpadding="0" align="center">
                                                <tr>
                                                <td height="10" style="text-align:center; font-family: Arial,  Verdana, sans-serif;   line-height: 18px; color: #000000; border-top: 1px dotted #999;">
                                                    <h3>'.$razon_S.'</h3>
                                                    <p>R.U.C &nbsp; '.$ruc.'</p>
                                                </td>
                                                </tr>
                                                <tr>
                                                <td style="font-family: Arial, Verdana, sans-serif; font-size:12px; line-height: 18px; color: #000000; border-top: 1px dotted #999; border-bottom: 1px dotted #999;">
                                                    <h3><b>Nro. Prestamo &nbsp;: &nbsp;&nbsp; '.$nro_prestamo.' </b></h3>
                                                    <p><b>Fecha &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$fecha.'</b></p>
                                                    <p><b>Cliente &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$nombreCliente.'</b></p>
                                                    <p><b>Documento &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$clienteDni.'</b></p>
                                                    <p><b>F. Pago &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$formaPago.'</b></p>
                                                    <p><b>Moneda &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$moneda.'</b></p>
                                                </td>
                                                </tr>
                                                <tr>
                                                <td style="font-family: Arial, Verdana, sans-serif; font-size:12px; line-height: 18px; color: #000000; border-top: 1px dotted #999; border-bottom: 1px dotted #999;">
                                                    <p><b>Nro Cuota &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$nro_cuota.'</b></p>
                                                    <p><b>Cuotas Restantes &nbsp;: &nbsp;&nbsp; '.$cant_cuotas_pagadas.' / '.$cuotas.'</b></p>
                                                    <h3><b>Monto Pagado &nbsp;&nbsp;: &nbsp;&nbsp; '.$simbolo_mone.' '. $monto_cuotas.'</b></h3>
                                                    <p><b>Saldo por Pagar &nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp; '.$simbolo_mone.' '.$saldo_Cuotas.'</b></p>
                                                </td>               
                                                </tr>
                                            </table>
                                            </td>
                                        </tr>
                                        </table>
                                        <table width="628" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="20"></td>
                                        </tr>
                                        </table>
                                        <table width="628" border="0" cellspacing="0" cellpadding="0" align="center">
                                        <tr>
                                            <td height="12"></td>
                                        </tr>
                                        </table>
                                    </td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                            </table>
                            </td>
                            </tr>
                            </table>
                            </body>
                            </html>';

		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

		$mail->send();
		echo 'Correo Enviado';
	} catch (Exception $e) {
		echo "Error al enviar el correo: {$mail->ErrorInfo}";
	}
}
