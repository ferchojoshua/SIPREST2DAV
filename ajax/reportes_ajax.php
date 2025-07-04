<?php

require_once "../controladores/reportes_controlador.php";
require_once "../modelos/reportes_modelo.php";

class AjaxReportes
{

    /*===================================================================*/
    //LISTAR REPORTE POR CLIENTE
    /*===================================================================*/
    public function  ReportePorCliente($cliente_id)
    {
        $reporteporCliente = Reportescontrolador::ctrReportePorCliente($cliente_id);
        echo json_encode($reporteporCliente);
    }


    /*===================================================================*/
    //LISTAR REPORTE CUOTAS PAGADAS
    /*===================================================================*/
    public function  ListarCuotasPagadasReport()
    {
        $reportecuotasPagadas = Reportescontrolador::ctrCuotasPagadasReport();
        echo json_encode($reportecuotasPagadas);
    }


    /*===================================================================*/
    //LISTAR REPORTE PIVOT
    /*===================================================================*/
    public function  ListarReportePivot()
    {
        $reportePivot = Reportescontrolador::ctrReportePivot();
        echo json_encode($reportePivot);
    }


    /*===================================================================*/
    //LISTAR SELECT USUARIO RECORD EN COMBO
    /*===================================================================*/
    public function ListarSelectUsuario()
    {
        $selectUsuario = Reportescontrolador::ctrListarSelectUsuario();
        echo json_encode($selectUsuario, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //LISTAR SELECT AÑOS RECORD EN COMBO
    /*===================================================================*/
    public function ListarSelectAnio()
    {
        $selectAnio = Reportescontrolador::ctrListarSelectAnio();
        echo json_encode($selectAnio, JSON_UNESCAPED_UNICODE);
    }



    /*===================================================================*/
    //LISTAR  REPORTE RECOR POR USUARIO
    /*===================================================================*/
    public function  ReporteRecordUsu($id_usuario, $anio)
    {
        $reportepoRecord = Reportescontrolador::ctrReporteRecordUsu($id_usuario, $anio);
        echo json_encode($reportepoRecord);
    }
}

if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR  REPORTE DE PRESTAMOS POR CLIENTE
    $reporteporCliente = new AjaxReportes();
    $reporteporCliente->ReportePorCliente($_POST["cliente_id"]);

} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //LISTAR  REPORTE CUOTAS PAGADAS
    $reportecuotasPagadas = new AjaxReportes();
    $reportecuotasPagadas->ListarCuotasPagadasReport();

} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //PIVOT
    $reportePivot = new AjaxReportes();
    $reportePivot->ListarReportePivot();

} else if (isset($_POST['accion']) && $_POST['accion'] == 4) {  // LISTAR SELECT USUARIO RECORD EN COMBO
    $selectUsuario = new AjaxReportes(); 
    $selectUsuario->ListarSelectUsuario();

} else if (isset($_POST['accion']) && $_POST['accion'] == 5) {   // LISTAR SELECT AÑOS RECORD EN COMBO
    $selectAnio = new AjaxReportes();
    $selectAnio->ListarSelectAnio();

} else if (isset($_POST['accion']) && $_POST['accion'] == 6) { //LISTAR  REPORTE RECOR POR USUARIO
    $reportepoRecord = new AjaxReportes();
    $reportepoRecord->ReporteRecordUsu($_POST["id_usuario"],$_POST["anio"]);

}
