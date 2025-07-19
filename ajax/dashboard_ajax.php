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
    //TRAER DATOS PARA LAS CAJAS CON FILTROS
    /*===================================================================*/
    public function getDatosDashboardFiltrados($sucursalId, $periodo)
    {
        $datos = DashboardControlador::ctrListaDashboardFiltrado($sucursalId, $periodo);
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
    //PRESTAMOS DEL MES - BARRAS CON FILTROS
    /*===================================================================*/
    public function getDatosPrestamosdelmesFiltrados($sucursalId, $periodo)
    {
        $prestamosmesactual = DashboardControlador::ctrListaPrestamosmesactualFiltrado($sucursalId, $periodo);
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

    /*===================================================================*/
    //KPIs GERENCIALES CON FILTROS
    /*===================================================================*/
    public function getKpisFiltrados($sucursalId, $periodo)
    {
        $kpis = DashboardControlador::ctrListaKpisFiltrados($sucursalId, $periodo);
        echo json_encode($kpis);
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


} else if (isset($_POST['accion']) && $_POST['accion'] == 'cargar_tarjetas_filtradas') {
    $dashboardFiltrado = new AjaxDashboard();
    $dashboardFiltrado->getDatosDashboardFiltrados($_POST['sucursal_id'], $_POST['periodo']);
} else if (isset($_POST['accion']) && $_POST['accion'] == 'cargar_grafico_filtrado') {
    $graficoFiltrado = new AjaxDashboard();
    $graficoFiltrado->getDatosPrestamosdelmesFiltrados($_POST['sucursal_id'], $_POST['periodo']);
} else if (isset($_POST['accion']) && $_POST['accion'] == 'cargar_kpis_filtrados') {
    $kpisFiltrados = new AjaxDashboard();
    $kpisFiltrados->getKpisFiltrados($_POST['sucursal_id'], $_POST['periodo']);
} else {
    $datos = new AjaxDashboard();        //TRAER DATOS PARA LAS CAJAS 
    $datos->getDatosDashboard();
}
