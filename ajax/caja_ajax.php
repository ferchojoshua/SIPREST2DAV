<?php

require_once "../controladores/caja_controlador.php";
require_once "../modelos/caja_modelo.php";
//require_once("../modelos/email_caja.php");
//$CierreCorreo2 = new Email();

class AjaxCaja
{

    /*===================================================================*/
    //LISTAR CAJA EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    public function  ListarAperturaCaja()
    {
        $ListarCaja = CajaControlador::ctrListarAperturaCaja();
        echo json_encode($ListarCaja);
    }

    /*===================================================================*/
    //REGISTRAR CAJA
    /*===================================================================*/
    public function ajaxRegistrarCaja()
    {
        $RegCaja = CajaControlador::ctrRegistrarCaja(
            $this->caja_descripcion,
            $this->caja_monto_inicial

        );
        echo json_encode($RegCaja);
    }


    /*===================================================================*/
    //TRAER DATOS FINALES PARA CERRAR LA CAJA
    /*===================================================================*/
    public function ajaxObtenerDataCierreCaja(){
        $DataCierreCaja = CajaControlador::ctrObtenerDataCierreCaja();
        echo json_encode($DataCierreCaja, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //REGISTRAR CERRAR LA CAJA
    /*===================================================================*/
    public function ajaxCerrarCaja()
    {
        $CerrarCaja = CajaControlador::ctrCerrarCaja(
            $this->caja_monto_ingreso,
            $this->caja_prestamo,
            $this->caja__monto_egreso,
            $this->caja_monto_total,
            $this->caja_count_prestamo,
            $this->caja_count_ingreso,
            $this->caja_count_egreso,
            $this->caja_interes

        );
        echo json_encode($CerrarCaja);
    }


    /*===================================================================*/
    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    /*===================================================================*/
    public function ajaxObtenerDataEstadoCaja(){
        $DataEstadoCaja = CajaControlador::ctrObtenerDataEstadoCaja();
        echo json_encode($DataEstadoCaja, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
     //OBTENER   ID DE LA CAJA
    /*===================================================================*/
    public function ajaxObtenerIDCaja(){
        $traerIdCaja = CajaControlador::ctrObtenerIDCaja();
        echo json_encode($traerIdCaja, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //VER  PRESTAMO POR CAJA ID
    /*===================================================================*/
    public function ajaxPrestamoPorCajaID($caja_id)
    {
        $PrestamosporCajaID = CajaControlador::ctrPrestamoPorCajaID($caja_id);
        echo json_encode($PrestamosporCajaID, JSON_UNESCAPED_UNICODE);
    }

     /*===================================================================*/
    //VER  MOVIMIENTOS POR CAJA ID
    /*===================================================================*/
    public function ajaxMovimientosPorCajaID($caja_id)
    {
        $MovimientosporCajaID = CajaControlador::ctrMovimientosPorCajaID($caja_id);
        echo json_encode($MovimientosporCajaID, JSON_UNESCAPED_UNICODE);
    }


}


if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR DATOS 
    $ListarCaja = new AjaxCaja();
    $ListarCaja->ListarAperturaCaja();


} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA REGISTRAR LA CAJA
    $RegCaja = new AjaxCaja();
    $RegCaja->caja_descripcion = $_POST["caja_descripcion"];
    $RegCaja->caja_monto_inicial = $_POST["caja_monto_inicial"];
    $RegCaja->ajaxRegistrarCaja();


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) {   //TRAER DATOS DEL CIERRE DE CAJA
    $DataCierreCaja = new AjaxCaja();
    $DataCierreCaja->ajaxObtenerDataCierreCaja(); 


} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { //PARA REGISTRAR EL  CERRAR LA CAJA
    $CerrarCaja = new AjaxCaja();
    $CerrarCaja->caja_monto_ingreso = $_POST["caja_monto_ingreso"];
    $CerrarCaja->caja_prestamo = $_POST["caja_prestamo"];
    $CerrarCaja->caja__monto_egreso = $_POST["caja__monto_egreso"];
    $CerrarCaja->caja_monto_total = $_POST["caja_monto_total"];
    $CerrarCaja->caja_count_prestamo = $_POST["caja_count_prestamo"];
    $CerrarCaja->caja_count_ingreso = $_POST["caja_count_ingreso"];
    $CerrarCaja->caja_count_egreso = $_POST["caja_count_egreso"];
    $CerrarCaja->caja_interes = $_POST["caja_interes"];
    $CerrarCaja->ajaxCerrarCaja();


} else if (isset($_POST['accion']) && $_POST['accion'] == 5) {    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    $DataEstadoCaja = new AjaxCaja();
    $DataEstadoCaja->ajaxObtenerDataEstadoCaja(); 

} else if (isset($_POST['accion']) && $_POST['accion'] == 6) {   //OBTENER   ID DE LA CAJA
    $traerIdCaja = new AjaxCaja();
    $traerIdCaja->ajaxObtenerIDCaja(); //creamos el metodo

} else if (isset($_POST['accion']) && $_POST['accion'] == 7) {       ///VER  PRESTAMO POR CAJA ID
    $PrestamosporCajaID = new AjaxCaja();
    $PrestamosporCajaID->ajaxPrestamoPorCajaID($_POST["caja_id"]);


}else if (isset($_POST['accion']) && $_POST['accion'] == 8) {       ///VER  MOVIMIENTOS POR CAJA ID
    $MovimientosporCajaID = new AjaxCaja();
    $MovimientosporCajaID->ajaxMovimientosPorCajaID($_POST["caja_id"]);


}

