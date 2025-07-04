<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';


require_once __DIR__ . '/vendor/autoload.php';
require '../conexion_reportes/r_conexion.php';
//require 'numeroletras/CifrasEnLetras.php';
//Incluímos la clase pago
//$v=new CifrasEnLetras(); 
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, 120]]);
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
				empresa.config_correo,
				caja.caja_interes
				FROM
				caja,
				empresa
	
				WHERE caja.caja_id =   '".$_GET['codigo']."'";
	
$estado = "";

	$resultado = $mysqli ->query($query);

    if (!$resultado) {
        die("Error en la consulta principal: " . $mysqli->error);
    }

while ($row1 = $resultado-> fetch_assoc()){
	$estado = $row1['caja_estado'];
	$correoEmpresa = $row1['config_correo'];
    $nombreUsuario = "N/A"; // No se puede obtener de la BD actual

    // Convertir fechas y horas a formato SQL para las consultas de rango
    $apertura_f_h = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $row1['caja_f_apertura']) . ' ' . $row1['caja_hora_apertura']));
    $cierre_f_h = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $row1['caja_f_cierre']) . ' ' . $row1['caja_hora_cierre']));

    // Consulta para préstamos por moneda
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

    // Consulta para ingresos por moneda
    $query_ingresos_moneda = "SELECT
                                m.moneda_simbolo,
                                SUM(mov.movi_monto) AS total_ingreso_moneda
                            FROM
                                movimientos mov
                            INNER JOIN
                                moneda m ON mov.moneda_id = m.moneda_id
                            WHERE
                                mov.movi_tipo = 'INGRESO' AND mov.movi_f_registro BETWEEN '$apertura_f_h' AND '$cierre_f_h'
                            GROUP BY
                                m.moneda_simbolo";
    $resultado_ingresos_moneda = $mysqli->query($query_ingresos_moneda);

    // Consulta para egresos por moneda (eliminada temporalmente)
    // $query_egresos_moneda = "SELECT
    //                             m.moneda_simbolo,
    //                             SUM(mov.movi_monto) AS total_egreso_moneda
    //                         FROM
    //                             movimientos mov
    //                         INNER JOIN
    //                             moneda m ON mov.moneda_id = m.moneda_id
    //                         WHERE
    //                             mov.movi_tipo = 'EGRESO' AND mov.movi_f_registro BETWEEN '$apertura_f_h' AND '$cierre_f_h'
    //                         GROUP BY
    //                             m.moneda_simbolo";
    // $resultado_egresos_moneda = $mysqli->query($query_egresos_moneda);


	//para ver el logo en la i,presion
	//<h3 style="text-align:center;display: inline-block;margin: 0px;padding: 0px; "><img src="../'.$row1['config_foto'].'" width="45px"></h3><br>

$html.='
<style>
		@page {
		margin: 10mm;
		margin-header: 0mm;
		margin-footer: 0mm;
		odd-footer-name: html_myfooter1;
		}

</style>	
	<h5 style="text-align:center;display: inline-block;margin: 0px;padding: 0px; ">'.$row1['confi_razon'].'</h5><br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Arqueo de Caja<br>
	-----------------------------------------<br>
	
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-size:11px">Ticket N.:&nbsp; 000'.$row1['caja_id'].'&nbsp;</h6>
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Apertura&nbsp;:&nbsp; '.$row1['caja_f_apertura'].' - '.$row1['caja_hora_apertura'].'</h6>
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Cierre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; '.$row1['caja_f_cierre'].' - '.$row1['caja_hora_cierre'].'</h6>
    <h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Cajero&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; ' . $nombreUsuario . '</h6>
	
		 
	------------------------------------------<br>
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Monto Apertura&nbsp; : &nbsp;&nbsp;&nbsp;S/  '.$row1['caja_monto_inicial'].'</h6> 
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Monto Interes&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;S/ '.$row1['caja_interes'].'</h6> 
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Monto Prestamo&nbsp;&nbsp;: &nbsp;&nbsp;S/ '.$row1['caja_prestamo'].'&nbsp;('.$row1['caja_count_prestamo'].')</h6>
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Monto Ingresos&nbsp;&nbsp;&nbsp;: &nbsp;&nbsp;S/  '.$row1['caja_monto_ingreso'].'&nbsp;&nbsp;('.$row1['caja_count_ingreso'].')</h6>
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">Monto Egresos&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;S/  '.$row1['caja__monto_egreso'].'&nbsp;&nbsp;('.$row1['caja_count_egreso'].')</h6></b>
	------------------------------------------<br>
	<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-size:11px">Monto Total&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;S/  '.$row1['caja_monto_total'].' </h6>
	

	';

    $html .= '<br><h6 style="display: inline-block;margin: 0px;padding: 0px;  font-size:11px">Detalle de Préstamos por Moneda:</h6>';
    if ($resultado_prestamos_moneda->num_rows > 0) {
        while ($row_prestamo_moneda = $resultado_prestamos_moneda->fetch_assoc()) {
            $html .= '<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row_prestamo_moneda['moneda_simbolo'] . ' ' . $row_prestamo_moneda['total_prestamo_moneda'] . '</h6>';
        }
    } else {
        $html .= '<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No hay préstamos para este período.</h6>';
    }

    // Detalle de Ingresos por Moneda (eliminado temporalmente)
    // $html .= '<br><h6 style="display: inline-block;margin: 0px;padding: 0px;  font-size:11px">Detalle de Ingresos por Moneda:</h6>';
    // if ($resultado_ingresos_moneda->num_rows > 0) {
    //     while ($row_ingreso_moneda = $resultado_ingresos_moneda->fetch_assoc()) {
    //         $html .= '<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row_ingreso_moneda['moneda_simbolo'] . ' ' . $row_ingreso_moneda['total_ingreso_moneda'] . '</h6>';
    //     }
    // } else {
    //     $html .= '<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No hay ingresos para este período.</h6>';
    // }

    // Detalle de Egresos por Moneda (eliminado temporalmente)
    // $html .= '<br><h6 style="display: inline-block;margin: 0px;padding: 0px;  font-size:11px">Detalle de Egresos por Moneda:</h6>';
    // if ($resultado_egresos_moneda->num_rows > 0) {
    //     while ($row_egreso_moneda = $resultado_egresos_moneda->fetch_assoc()) {
    //         $html .= '<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row_egreso_moneda['moneda_simbolo'] . ' ' . $row_egreso_moneda['total_egreso_moneda'] . '</h6>';
    //     }
    // } else {
    //     $html .= '<h6 style="display: inline-block;margin: 0px;padding: 0px;  font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;No hay egresos para este período.</h6>';
    // }

}

//$css = file_get_contents('');
//$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output();
//$pdf = $mpdf->Output("", 'D');

