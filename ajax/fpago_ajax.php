<?php
require_once "../controladores/fpago_controlador.php";
require_once "../modelos/fpago_modelo.php";

class AjaxFPago
{

    /*===================================================================*/
    //LISTAR SELECT EN COMBO
    /*===================================================================*/
    public function ListarSelectFormaPago()
    {
        $fpago = FPagoControlador::ctrListarSelectFormaPago();
        echo json_encode($fpago, JSON_UNESCAPED_UNICODE);
    }
}



$fpago = new AjaxFPago(); // SELECT EN COMBO
$fpago->ListarSelectFormaPago();