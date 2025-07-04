<?php

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

	$resultado = $mysqli ->query($query);
while ($row1 = $resultado-> fetch_assoc()){



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


    <h5 style="text-align:center;display: inline-block;margin: 0px;padding: 0px; ">Firma _____________________</h5><br>';
     
 }




// $css = file_get_contents('css/style_cuota.css');
// $mpdf->WriteHTML($css,1);
$mpdf->SetMargins(0,0,8);

$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output();