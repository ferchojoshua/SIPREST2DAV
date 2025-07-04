<?php

require_once "../controladores/admin_prestamos_controlador.php";
require_once "../modelos/admin_prestamos_modelo.php";

class AjaxAdminPrestamos
{

    /*===================================================================*/
    //LISTAR PRESTAMOS POR ID DEL USUARIO
    /*===================================================================*/
    public function ajaxListarPrestamoPorUsuario($id_usuario)
    {
        $listPrestamosporUsuario = AdminPrestamosControlador::ctrListarPrestamoPorUsuario($id_usuario);
        echo json_encode($listPrestamosporUsuario, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //VER DETALLE DEL PRESTAMO
    /*===================================================================*/
    public function ajaxDetallePrestamo($nro_prestamo)
    {
        $detallePrestamo = AdminPrestamosControlador::ctrDetallePrestamo($nro_prestamo);
        echo json_encode($detallePrestamo, JSON_UNESCAPED_UNICODE);
    }



    /*===================================================================*/
    //PAGAR CUOTA DEL PRESTAMO
    /*===================================================================*/
    public function ajaxPagarCuota()
    {
        $PagarCuota = AdminPrestamosControlador::ctrPagarCuota($this->nro_prestamo, $this->pdetalle_nro_cuota);
        echo json_encode($PagarCuota);
    }


    /*===================================================================*/
    //OBTENER CUOTAS PAGADAS
    /*===================================================================*/
    public function ajaxObtenerCuotasPagadas($nro_prestamo){
        $CuotasPagadas = AdminPrestamosControlador::ctrObtenerCuotasPagadas($nro_prestamo);
        echo json_encode($CuotasPagadas, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
     // LIQUIDAR PRESTAMO
     /*===================================================================*/
     public function ajaxLiquidarPrestamo($nro_prestamo, $pdetalle_nro_cuota)
     {   
         $LiquidarPrestamo = AdminPrestamosControlador::ctrLiquidarPrestamo($nro_prestamo, $pdetalle_nro_cuota);
         echo json_encode($LiquidarPrestamo);
         //var_dump($LiquidarPrestamo);
     }

     /*===================================================================*/
     // REGISTRAR ABONO DE CUOTA
     /*===================================================================*/
     public function ajaxRegistrarAbono()
     {
         $RegistrarAbono = AdminPrestamosControlador::ctrRegistrarAbono(
             $this->nro_prestamo,
             $this->pdetalle_nro_cuota,
             $this->monto_a_abonar
         );
         echo json_encode($RegistrarAbono);
     }
}




if (isset($_POST["accion"]) && $_POST["accion"] == 1) {             //LISTAR PRESTAMOS POR ID DEL USUARIO
    $listPrestamosporUsuario = new AjaxAdminPrestamos();
    $listPrestamosporUsuario->ajaxListarPrestamoPorUsuario($_POST["id_usuario"]);


} else if (isset($_POST['accion']) && $_POST['accion'] == 2) {       ///VER DETALLE DEL PRESTAMO
    $detallePrestamo = new AjaxAdminPrestamos();
    $detallePrestamo->ajaxDetallePrestamo($_POST["nro_prestamo"]);


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) {       //PAGAR CUOTA DEL PRESTAMO
    $PagarCuota = new AjaxAdminPrestamos();
    $PagarCuota->nro_prestamo = $_POST["nro_prestamo"];
    $PagarCuota->pdetalle_nro_cuota = $_POST["pdetalle_nro_cuota"];
    $PagarCuota->ajaxPagarCuota();

    
}else if (isset($_POST['accion']) && $_POST['accion'] == 4) {        //OBTENER CUOTAS PAGADAS
    $CuotasPagadas = new AjaxAdminPrestamos();
    $CuotasPagadas->ajaxObtenerCuotasPagadas($_POST["nro_prestamo"]); 


}else if (isset($_POST["accion"]) && $_POST["accion"] == 5) {        // LIQUIDAR PRESTAMO
    $LiquidarPrestamo = new AjaxAdminPrestamos();
    $LiquidarPrestamo->ajaxLiquidarPrestamo( $_POST['nro_prestamo'],  $_POST['pdetalle_nro_cuota'] );



} else if (isset($_POST["accion"]) && $_POST["accion"] == 6) { // REGISTRAR ABONO DE CUOTA
    $RegistrarAbono = new AjaxAdminPrestamos();
    $RegistrarAbono->nro_prestamo = $_POST["nro_prestamo"];
    $RegistrarAbono->pdetalle_nro_cuota = $_POST["pdetalle_nro_cuota"];
    $RegistrarAbono->monto_a_abonar = $_POST["monto_a_abonar"];
    $RegistrarAbono->ajaxRegistrarAbono();
}
