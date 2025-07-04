<?php
require_once "../controladores/dashboard_controlador.php";
require_once "../modelos/dashboard_modelo.php";

class AjaxDashboard
{

    /*===================================================================*/
    //TRAER DATOS PARA LAS CAJAS 
    /*===================================================================*/
    public function getDatosDashboard()
    {
        $datos = DashboardControlador::ctrListaDashboard();
        echo json_encode($datos);
    }

    /*===================================================================*/
    //PRESTAMOS DEL MES - BARRAS
    /*===================================================================*/
    public function getDatosPrestamosdelmes()
    {
        $prestamosmesactual = DashboardControlador::ctrListaPrestamosmesactual();
        echo json_encode($prestamosmesactual);
    }



    /*===================================================================*/
    //CLIENTES CON PRESTAMOS EN DATATABLE
    /*===================================================================*/
    public function getClientesConPrestamos()
    {
        $ClientesConPrestamos = DashboardControlador::ctrClientesConPrestamos();
        echo json_encode($ClientesConPrestamos);
    }


    /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    public function getCuotasVencidas()
    {
        $CuotasVencidas = DashboardControlador::ctrCuotasVencidas();
        echo json_encode($CuotasVencidas);
    }

     /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    public function getNotificacion($id_usuario)
    {
        $Notificacion = DashboardControlador::ctrNotificacion($id_usuario);
        echo json_encode($Notificacion);
    }

    
}


//instanciamos para que se ejecute la funcion
if (isset($_POST['accion']) && $_POST['accion'] == 1) {        //PRESTAMOS DEL MES - BARRAS
    $prestamosmesactual = new AjaxDashboard();
    $prestamosmesactual->getDatosPrestamosdelmes();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 2) {    //CLIENTES CON PRESTAMOS EN DATATABLE
    $ClientesConPrestamos = new AjaxDashboard();//clase
    $ClientesConPrestamos->getClientesConPrestamos();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 3) {    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    $CuotasVencidas = new AjaxDashboard();//clase
    $CuotasVencidas->getCuotasVencidas();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 4) {    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    $Notificacion = new AjaxDashboard();//clase
    $Notificacion->getNotificacion($_POST["id_usuario"]);


}  else {
    $datos = new AjaxDashboard();        //TRAER DATOS PARA LAS CAJAS 
    $datos->getDatosDashboard();
}
