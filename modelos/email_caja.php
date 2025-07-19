<?php

// Incluir archivos necesarios, incluyendo la configuración de correo centralizada
require_once __DIR__ . '/../utilitarios/email_config.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once "conexion.php";
require_once "caja_modelo.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailCaja {
    
    public function enviarCorreoCierreCaja($caja_id) {

        $cajaModelo = new CajaModelo();
        $datos = $cajaModelo->mdlCorreo($caja_id);
        
        if (empty($datos)) {
            // Manejar caso donde no se encuentran datos de la caja
            error_log("No se encontraron datos para la caja ID: " . $caja_id);
            return false;
        }

        // Asumimos que mdlCorreo devuelve una sola fila
        $row = $datos[0];
        $correoDestino = $row["correo"];

        if (empty($correoDestino)) {
            error_log("El registro de caja ID: " . $caja_id . " no tiene un correo de destino.");
            return false;
        }

        // Comprobar si el envío de correo está activo
        if (EMAIL_ACTIVO !== true) {
            error_log("El envío de correo está desactivado globalmente.");
            return false;
        }

        $mail = new PHPMailer(true);
       
        try {
            // Configuración del servidor usando constantes globales
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';

            // Remitente y destinatario
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($correoDestino);
            
            // Asunto y cuerpo del correo
            $mail->isHTML(true);
            $mail->Subject = "Reporte de Cierre de Caja - " . $row["confi_razon"];
            
            // Cargar y procesar plantilla HTML
            $cuerpo = file_get_contents('../MPDF/historial_pres.html'); 
            
            $cuerpo = str_replace("lblRazon", $row["confi_razon"], $cuerpo);
            $cuerpo = str_replace("lblf_aper", $row["caja_f_apertura"], $cuerpo);
            $cuerpo = str_replace("lblh_aper", $row["caja_hora_apertura"], $cuerpo);
            $cuerpo = str_replace("lblf_cier", $row["caja_f_cierre"], $cuerpo);
            $cuerpo = str_replace("lblh_cier", $row["caja_hora_cierre"], $cuerpo);
            $cuerpo = str_replace("lblmonto_aper", number_format($row["caja_monto_inicial"], 2), $cuerpo);
            $cuerpo = str_replace("lblmonto_pres", number_format($row["caja_prestamo"], 2), $cuerpo);
            $cuerpo = str_replace("lblcoun_pres", $row["caja_count_prestamo"], $cuerpo);
            $cuerpo = str_replace("lblmonto_ingre", number_format($row["caja_monto_ingreso"], 2), $cuerpo);
            $cuerpo = str_replace("lblcoun_ingre", $row["caja_count_ingreso"], $cuerpo);
            $cuerpo = str_replace("lblmonto_egre", number_format($row["caja__monto_egreso"], 2), $cuerpo);
            $cuerpo = str_replace("lblcoun_egre", $row["caja_count_egreso"], $cuerpo);
            $cuerpo = str_replace("lblmonto_total", number_format($row["caja_monto_total"], 2), $cuerpo);

            $mail->Body = $cuerpo;
            $mail->AltBody = strip_tags("Reporte de Cierre de Caja de " . $row["confi_razon"]);

            $mail->send();
            return true;

        } catch (Exception $e) {
            error_log("Error al enviar correo de cierre de caja: " . $mail->ErrorInfo);
            return false;
        }
    }
}