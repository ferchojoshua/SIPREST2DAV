<?php

require('class.phpmailer.php');
include("class.smtp.php");


require_once("conexion.php");
//require '../conexion_reportes/r_conexion.php';
//require_once("../controladores/caja_controlador.php");
require_once ("caja_modelo.php");


class Email extends PHPMailer{
    

    //TODO: variable que contiene el correo del destinatario
    protected $gCorreo = 'gmasiasdeveloper@gmail.com';
    protected $gContrasena = 'sasiavmahljllpww';
    
     public function ctrCorreo ($caja_id){

        $caja = new CajaModelo();
        $datos = $caja->mdlCorreo ($caja_id);
        foreach ($datos as $row){
            $razon = $row["confi_razon"];
            $cajaid = $row["caja_id"];
            $f_aper = $row["caja_f_apertura"];
            $h_aper = $row["caja_hora_apertura"];
            $f_cier = $row["caja_f_cierre"];
            $h_cier = $row["caja_hora_cierre"];
            $monto_ini = $row["caja_monto_inicial"];
            $monto_pres = $row["caja_prestamo"];
            $coun_pres = $row["caja_count_prestamo"];
            $monto_ing = $row["caja_monto_ingreso"];
            $coun_ing = $row["caja_count_ingreso"];
            $monto_egre = $row["caja__monto_egreso"];
            $coun_egre = $row["caja_count_egreso"];
            $monto_total = $row["caja_monto_total"];
            $correo = $row["caja_correo"];//correo al cual se enviara
           // var_dump($razon);
            
        } var_dump($correo);
        die();
       // try {
        //TODO: IGual//
        //$this->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->IsSMTP();
        $this->Host = 'smtp.gmail.com';//Aqui el server
        $this->Port = 587;//Aqui el puerto
        $this->SMTPAuth = true;
        $this->Username = $this->gCorreo;
        $this->Password = $this->gContrasena;
        $this->From = $this->gCorreo;
        $this->SMTPSecure = '';
        //$this->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $this->FromName =  "Prueba envio ";
        $this->CharSet = 'UTF8';
        $this->addAddress($correo);
        $this->WordWrap = 50;
        $this->IsHTML(true);
        $this->Subject = "Ejm";
        //Igual//
        $cuerpo = file_get_contents('../MPDF/historial_pres.html'); 
        
        //$cuerpo = str_replace("xnroticket", $id, $cuerpo);
        $cuerpo = str_replace("lblRazon", $razon, $cuerpo);
        $cuerpo = str_replace("lblf_aper", $f_aper, $cuerpo);
        $cuerpo = str_replace("lblh_aper", $h_aper, $cuerpo);
        $cuerpo = str_replace("lblf_cier", $f_cier, $cuerpo);
        $cuerpo = str_replace("lblh_cier", $h_cier, $cuerpo);
        $cuerpo = str_replace("lblmonto_aper", $monto_ini, $cuerpo);
        $cuerpo = str_replace("lblmonto_pres", $monto_pres, $cuerpo);
        $cuerpo = str_replace("lblcoun_pres", $coun_pres, $cuerpo);
        $cuerpo = str_replace("lblmonto_ingre", $monto_ing, $cuerpo);
        $cuerpo = str_replace("lblcoun_ingre", $coun_ing, $cuerpo);
        $cuerpo = str_replace("lblmonto_egre", $monto_egre, $cuerpo);
        $cuerpo = str_replace("lblcoun_egre", $coun_egre, $cuerpo);
        $cuerpo = str_replace("lblmonto_total", $monto_total, $cuerpo);

        $this->Body = $cuerpo;
        $this->AltBody = strip_tags("Enviando correo de prueba de caja cerrada");
        return $this->Send();
         echo'Message has been sent';
        // var_dump($this);
    // } catch (Exception $e) {
    //     echo "Message could not be sent. Mailer Error: {$this->ErrorInfo}";
    // }
    }
}

?>