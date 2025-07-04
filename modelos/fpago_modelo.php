<?php

require_once "conexion.php";

class FPagoModelo
{

    /*===================================================================*/
       //SELECT FORMA DE PAGO EN COMBO
    /*===================================================================*/
       static public function mdlListarSelectFormaPago()
       {
   
           $stmt = Conexion::conectar()->prepare("SELECT fpago_id, 
                                                       fpago_descripcion
                                                   FROM
                                                       forma_pago			
                                                   ORDER BY fpago_id asc");
   
           $stmt->execute();
   
           return $stmt->fetchAll();
       }


}