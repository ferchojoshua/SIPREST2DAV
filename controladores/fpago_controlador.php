<?php

class FPagoControlador
{

    /*===================================================================*/
     //SELECT     FORMA DE PAGO 
     /*===================================================================*/
     static public function ctrListarSelectFormaPago()
     {
         $fpago = FPagoModelo::mdlListarSelectFormaPago();
         return $fpago;
     }
}