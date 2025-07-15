<?php

class ReportesControlador
{

    /*===================================================================*/
    //LISTAR REPORTE POR CLIENTE
    /*===================================================================*/
    static public function ctrReportePorCliente($cliente_id)
    {
        $reporteporCliente =  ReportesModelo::mdlReportePorCliente($cliente_id);
        return $reporteporCliente;
    }


    /*===================================================================*/
    //LISTAR REPORTE CUOTAS PAGADAS
    /*===================================================================*/
    static public function ctrCuotasPagadasReport()
    {
        $reportecuotasPagadas =  ReportesModelo::mdlCuotasPagadasReport();
        return $reportecuotasPagadas;
    }


    /*===================================================================*/
    //LISTAR REPORTE PIVOT
    /*===================================================================*/
    static public function ctrReportePivot()
    {
        $reportePivot =  ReportesModelo::mdlReportePivot();
        return $reportePivot;
    }


    /*===================================================================*/
    //SELECT USUARIO RECORD 
    /*===================================================================*/
    static public function ctrListarSelectUsuario()
    {
        $selectUsuario = ReportesModelo::mdlListarSelectUsuario();
        return $selectUsuario;
       
    }


    /*===================================================================*/
    //SELECT AÑOS RECORD 
    /*===================================================================*/
    static public function ctrListarSelectAnio()
    {
        $selectAnio = ReportesModelo::mdlListarSelectAnio();
        return $selectAnio;
       
    }



    /*===================================================================*/
    //LISTAR  REPORTE RECOR POR USUARIO
    /*===================================================================*/
    static public function ctrReporteRecordUsu($id_usuario, $anio)
    {
        $reportepoRecord =  ReportesModelo::mdlReporteRecordUsu($id_usuario, $anio);
        return $reportepoRecord;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER REPORTE DE MOROSOS
    ====================================================================*/
    static public function ctrObtenerReporteMorosos()
    {
        $respuesta = ReportesModelo::mdlObtenerReporteMorosos();
        return $respuesta;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER REPORTE DE RECUPERACION
    ====================================================================*/
    static public function ctrObtenerReporteRecuperacion($fecha_inicial, $fecha_final)
    {
        $respuesta = ReportesModelo::mdlObtenerReporteRecuperacion($fecha_inicial, $fecha_final);
        return $respuesta;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER MONEDAS
    ====================================================================*/
    static public function ctrObtenerMonedas()
    {
        $respuesta = ReportesModelo::mdlObtenerMonedas();
        return $respuesta;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER REPORTE DIARIO
    ====================================================================*/
    static public function ctrObtenerReporteDiario($fecha)
    {
        $respuesta = ReportesModelo::mdlObtenerReporteDiario($fecha);
        return $respuesta;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER ESTADO DE CUENTA DETALLADO POR CLIENTE
    ====================================================================*/
    static public function ctrObtenerEstadoCuentaCliente($cliente_id)
    {
        $respuesta = ReportesModelo::mdlObtenerEstadoCuentaCliente($cliente_id);
        return $respuesta;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER DETALLE DE CUOTAS POR PRÉSTAMO
    ====================================================================*/
    static public function ctrObtenerDetalleCuotasPrestamo($nro_prestamo)
    {
        $respuesta = ReportesModelo::mdlObtenerDetalleCuotasPrestamo($nro_prestamo);
        return $respuesta;
    }

    /**
     * Reporte de cobranza diaria: cuotas pendientes para una fecha
     */
    static public function ctrReporteCobranzaDiaria($fecha) {
        require_once "../modelos/reportes_modelo.php";
        $reporte = ReportesModelo::mdlReporteCobranzaDiaria($fecha);
        return $reporte;
    }

    /**
     * Reporte de cuotas atrasadas por promotor
     */
    static public function ctrReporteCuotasAtrasadas($fecha) {
        require_once "../modelos/reportes_modelo.php";
        $reporte = ReportesModelo::mdlReporteCuotasAtrasadas($fecha);
        return $reporte;
    }

    /*===================================================================*/
    // REPORTE DE SALDOS ARRASTRADOS
    /*===================================================================*/
    static public function ctrReporteSaldosArrastrados($fecha_inicio, $fecha_fin)
    {
        $reporte = ReportesModelo::mdlReporteSaldosArrastrados($fecha_inicio, $fecha_fin);
        return $reporte;
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER KPIs GERENCIALES DEL DASHBOARD
    ====================================================================*/
    static public function ctrObtenerKpisGerenciales($id_colector = null)
    {
        try {
            $kpis = ReportesModelo::mdlObtenerKpisGerenciales($id_colector);
            return $kpis;
        } catch (Exception $e) {
            error_log("Error en ctrObtenerKpisGerenciales: " . $e->getMessage());
            return [
                'saldo_cartera' => 0,
                'clientes_activos' => 0,
                'monto_en_mora' => 0,
                'porcentaje_mora' => 0
            ];
        }
    }

    /*===================================================================
    LLAMAR AL MODELO PARA OBTENER KPIs DEL DASHBOARD (LEGACY COMPATIBILITY)
    ====================================================================*/
    static public function ctrGetDashboardKpis($id_colector = null)
    {
        return self::ctrObtenerKpisGerenciales($id_colector);
    }
}
