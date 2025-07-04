<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
require 'numeroletras/CifrasEnLetras.php';
//Incluímos la clase pago
$v=new CifrasEnLetras(); 
$mpdf = new \Mpdf\Mpdf();
$query = "SELECT
                pc.pres_id,
                pc.nro_prestamo,
                pc.cliente_id,
                c.cliente_nombres,
                c.cliente_dni,
                pc.pres_monto,
                pc.pres_interes,
                pc.pres_cuotas,
               -- pc.pres_fecha_registro,
                DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as pres_fecha_registro,
                pc.fpago_id,
                fp.fpago_descripcion,
                -- pc.pres_f_emision ,
                DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') as pres_f_emision,
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
                u.usuario,
                c.cliente_correo,
                empresa.config_celular
                FROM
                prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda mo ON pc.moneda_id = mo.moneda_id
				INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario,
                empresa 
                WHERE
                pc.nro_prestamo = '".$_GET['codigo']."'";

$resultado = $mysqli ->query($query);
while ($row1 = $resultado-> fetch_assoc()){
  $confi_razon = $row1['confi_razon'];
  $correoCliente = $row1['cliente_correo'];
  $correoEmpresa = $row1['config_correo'];
  $nroPres = $row1['nro_prestamo'];
  $celEm = $row1['config_celular'];
  $nombCli = $row1['cliente_nombres'];
	
	


$html = '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <link rel="stylesheet" href="style.css" media="all" />
  </head>
  <body>
    <header class="clearfix">
    <table style="border-collapse; " border="1" >
	    <thead >
	    	<tr>
            

	    		<th width="20%" style="border-top:0px; border-left:0px; border-bottom:0px; border-right:0px; "></th>

	    		<th width="50%" style="border-top:0px; border-left:0px; border-bottom:0px; border-right:0px; ">
                <h3 style="color:black; text-align:center;"><b><u>TABLA DE CUOTAS</u></b></h3><br>
               
	    		</th>

	    		<th width="30%" style="border-top:0px; border-left:0px; border-bottom:0px; border-right:0px; text-align:center;">
	    			
	    			<h3 style="color:black;">Nro :            '.$row1['nro_prestamo'].' </h3><br>
                 
	    		</th>
	    	</tr>
	    </thead>
    </table>
     

    </header>
 	<table  style="" class="round_table" >
	    <thead >
	    	<tr>
	    
	    		<th width="50%" style="  text-align:left; border-right:0px; ">
                        <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;font-size:12px;color:black; ">Cliente &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp; &nbsp; &nbsp; '.$row1['cliente_nombres'].'</h6>	    			
	    			<br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Documento  &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;  : &nbsp; &nbsp; &nbsp; '.$row1['cliente_dni'].'</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Fecha Prestamo&nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['pres_fecha_registro'].'</h6><br>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Monto  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['moneda_simbolo'].'&nbsp;'.$row1['pres_monto'].'.00</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Interes (%) &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['pres_interes'].'</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Monto Interes &nbsp;&nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['moneda_simbolo'].'&nbsp;'.$row1['pres_monto_interes'].'.00</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Monto Total &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['moneda_simbolo'].'&nbsp;'.$row1['pres_monto_total'].'.00</h6><br>

                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Monto Cuota &nbsp; &nbsp; &nbsp; &nbsp; : &nbsp; &nbsp; &nbsp; '.$row1['moneda_simbolo'].'&nbsp;'.$row1['pres_monto_cuota'].'</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Nro Cuotas  &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; : &nbsp; &nbsp; &nbsp; '.$row1['pres_cuotas'].'</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Forma de Pago&nbsp;&nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['fpago_descripcion'].'</h6>
                    <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Moneda&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;: &nbsp; &nbsp; &nbsp; '.$row1['moneda_nombre'].'</h6>
	    			
	    			
	    		</th>
	    			
	    		</th>
	    		<th width="50%" style="text-align:right; border-left:0px;">
                <h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Usuario  &nbsp;&nbsp; &nbsp;  : &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; '.$row1['usuario'].'</h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Fecha  Emisi&oacute;n&nbsp; &nbsp; &nbsp;&nbsp; : &nbsp; &nbsp; &nbsp; '.$row1['pres_f_emision'].'</h6>   
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">Estado  &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;  : &nbsp; &nbsp; &nbsp; &nbsp; '.$row1['pres_estado'].'</h6><br>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6><br>

                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;"></h6>
                <br><h6 style="display: inline-block;margin: 0px;padding: 0px; font-weight:normal;font-size:12px;color:black;">  &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;   &nbsp; &nbsp; &nbsp; </h6>

	    			
	    		</th>

                

	    	</tr>
	    </thead>
    </table>
 
    <main>
      <table  style="border-collapse; " border="1" >
        <thead>
          <tr>
          
            <th style="color:black;"><b>NRO CUOTA</b></th>
            <th style="color:black;"><b>FECHA</b></th>
            <th style="color:black;"><b>MONTO</b></th>
            <th style="color:black;"><b>ESTADO</b></th>

          </tr>
        </thead>
        <tbody>';
        $query2 = "SELECT
                        pdetalle_id,
                        nro_prestamo,
                        pdetalle_nro_cuota,
                       -- DATE_FORMAT(pdetalle_fecha, '%d/%m/%y  %r') as fecha,
	                      DATE_FORMAT(pdetalle_fecha, '%d/%m/%Y') as fecha,
                        pdetalle_monto_cuota,
                        pdetalle_estado_cuota,
                        pdetalle_fecha_registro 
                    FROM
                        prestamo_detalle
                    WHERE
                        nro_prestamo = '".$_GET['codigo']."' ";

						$contador=0;
						$resultado2 = $mysqli ->query($query2);
						while ($row2 = $resultado2-> fetch_assoc()){
							$contador++;

        $html.='<tr >
           
            <td class="desc" style="color:black;">'.$row2['pdetalle_nro_cuota'].'</td>
            <td class="desc" style="color:black;">'.$row2['fecha'].'</td>
            <td class="desc" style="color:black;">'.round($row2['pdetalle_monto_cuota'],2).'</td>
            <td class="desc" style="color:black;">'.$row2['pdetalle_estado_cuota'].'</td>
            </tr>';
        }
     
  
        

          $html.='
           
        </tbody>
      </table>
     
      
    
    </main>
    <footer>

    </footer>
  </body>
</html>';
}
$css = file_get_contents('css/style_coti.css');
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML(utf8_encode($html));
$razon_S = $confi_razon;
$correoCli = $correoCliente; // correo de la empresa
$correoEmpre = $correoEmpresa;
$nroPrestamo = $nroPres;
$celular = $celEm;
$nombreCliente =   $nombCli;
$pdf = $mpdf->Output("", 'S');
$mpdf->Output();



sendEmail($pdf, $correoCli, $correoEmpre,$nroPrestamo,$celular,$nombreCliente,$razon_S);

function sendEmail($pdf, $correoCli, $correoEmpre,$nroPrestamo,$celular,$nombreCliente,$razon_S)
{
	$mail = new PHPMailer(true);

	try {
		$mail = new PHPMailer();
		//Server settings
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth   = true;
		//Enable SMTP authentication
		$mail->Username   = $correoEmpre;                     //SMTP username
		$mail->Password   = '';   // AQUI SE COLOCA LA CLAVE DEL CORREO
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		//Recipients
		$mail->setFrom($correoEmpre, 'Gunner Bento'); //DE:
		$mail->addAddress($correoCli, 'Correo Developer'); //PARA:
		$mail->addStringAttachment($pdf, "attachment.pdf"); //ARCHIVO PDF

		$mail->isHTML(true);                                  //Set email format to HTML
		$mail->Subject = 'CRONOGRAMA DE CUOTAS DEL PRESTAMO -' .$nroPrestamo;


		$mail->Body    = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" >
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
                                          
                                          </td>
                                          </tr>
                                          <tr>
                                            <td style="font-family: Arial, Verdana, sans-serif; font-size:12px; line-height: 18px; color: #000000; border-top: 1px dotted #999; border-bottom: 1px dotted #999;">
                                              <h2>Cronograma de Cuotas del Prestamo - '.$nroPrestamo.'</h2>
                                              <p><b>Destinatario:</b>&nbsp;&nbsp;  '.$correoEmpre.'</p>
                                              <p><b>Cliente:</b>&nbsp;&nbsp; '.$nombreCliente.' &nbsp;('.$correoCli.')</p>
                                            </br>
                                              <p></p>
                                              <h3><u>Ver el archivo PDF que esta adjunto en este correo</u></h3>
                                              <p>Cualquier duda o consulta por favor escribirnos a <b>'.$celular.'</b></p>
                                              <p>Gracias,</p>
                                              <p>Atentamente,</p>
                                        </table></td>
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
                            </table></td>
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