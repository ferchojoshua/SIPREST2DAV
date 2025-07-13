<?php

require_once "../controladores/reportes_controlador.php";
require_once "../modelos/reportes_modelo.php";

class AjaxReportes
{

    /*===================================================================*/
    //OBTENER REPORTE DE CLIENTES MOROSOS
    /*===================================================================*/
    public function ajaxObtenerReporteMorosos()
    {
        $reporteMorosos = ReportesControlador::ctrObtenerReporteMorosos();
        echo json_encode($reporteMorosos);
    }

    /*===================================================================*/
    //OBTENER REPORTE DE RECUPERACION
    /*===================================================================*/
    public function ajaxObtenerReporteRecuperacion()
    {
        $reporteRecuperacion = ReportesControlador::ctrObtenerReporteRecuperacion($_POST["fecha_inicial"], $_POST["fecha_final"]);
        echo json_encode($reporteRecuperacion);
    }


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

if (isset($_POST['accion'])) {

    switch ($_POST['accion']) {

        case 1: //LISTAR  REPORTE DE PRESTAMOS POR CLIENTE
            $reporteporCliente = new AjaxReportes();
            $reporteporCliente->ReportePorCliente($_POST["cliente_id"]);
            break;

        case 2: //LISTAR  REPORTE CUOTAS PAGADAS
            $reportecuotasPagadas = new AjaxReportes();
            $reportecuotasPagadas->ListarCuotasPagadasReport();
            break;

        case 3: //PIVOT
            $reportePivot = new AjaxReportes();
            $reportePivot->ListarReportePivot();
            break;

        case 4:  // LISTAR SELECT USUARIO RECORD EN COMBO
            $selectUsuario = new AjaxReportes();
            $selectUsuario->ListarSelectUsuario();
            break;

        case 5:   // LISTAR SELECT AÑOS RECORD EN COMBO
            $selectAnio = new AjaxReportes();
            $selectAnio->ListarSelectAnio();
            break;

        case 6: //LISTAR  REPORTE RECOR POR USUARIO
            $reportepoRecord = new AjaxReportes();
            $reportepoRecord->ReporteRecordUsu($_POST["id_usuario"], $_POST["anio"]);
            break;
            
        case 7: //REPORTE MOROSOS
            $reporteMorosos = ReportesControlador::ctrObtenerReporteMorosos();
            echo json_encode($reporteMorosos);
            break;

        case 8: //REPORTE RECUPERACION
            $reporteRecuperacion = ReportesControlador::ctrObtenerReporteRecuperacion($_POST["fecha_inicial"], $_POST["fecha_final"]);
            echo json_encode($reporteRecuperacion);
            break;

        case 9: //OBTENER MONEDAS
            $monedas = ReportesControlador::ctrObtenerMonedas();
            echo json_encode($monedas);
            break;

        case 10: //REPORTE DIARIO
            $reporteDiario = ReportesControlador::ctrObtenerReporteDiario($_POST["fecha"]);
            echo json_encode($reporteDiario);
            break;

        case 11: //ESTADO DE CUENTA POR CLIENTE
            $estadoCuenta = ReportesControlador::ctrObtenerEstadoCuentaCliente($_POST["cliente_id"]);
            echo json_encode($estadoCuenta);
            break;

        case 12: //DETALLE DE CUOTAS POR PRÉSTAMO
            $detalleCuotas = ReportesControlador::ctrObtenerDetalleCuotasPrestamo($_POST["nro_prestamo"]);
            echo json_encode($detalleCuotas);
            break;
    }
}

if (isset($_POST['accion']) && $_POST['accion'] === 'reporte_cobranza_diaria') {
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $reporte = ReportesControlador::ctrReporteCobranzaDiaria($fecha);
    echo json_encode($reporte, JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'reporte_cuotas_atrasadas') {
    $fecha = isset($_POST['fecha']) ? $_POST['fecha'] : date('Y-m-d');
    $reporte = ReportesControlador::ctrReporteCuotasAtrasadas($fecha);
    echo json_encode($reporte, JSON_UNESCAPED_UNICODE);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'get_dashboard_kpis') {
    require_once "../controladores/reportes_controlador.php";
    $id_colector = isset($_POST['id_colector']) && !empty($_POST['id_colector']) ? $_POST['id_colector'] : null;
    $kpis = ReportesControlador::ctrGetDashboardKpis($id_colector);
    echo json_encode($kpis, JSON_UNESCAPED_UNICODE);
    exit;
}
