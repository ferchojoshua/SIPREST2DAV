<?php


class DashboardControlador {

    /*===================================================================*/
    //TRAER DATOS PARA LAS CAJAS 
    /*===================================================================*/
    static public function ctrListaDashboard(){
        $datos = DashboardModelo::mdlListaDashboard();
        return $datos;
    }

    /*===================================================================*/
    //TRAER DATOS PARA LAS CAJAS CON FILTROS
    /*===================================================================*/
    static public function ctrListaDashboardFiltrado($sucursalId, $periodo){
        $datos = DashboardModelo::mdlListaDashboardFiltrado($sucursalId, $periodo);
        return $datos;
    }


    /*===================================================================*/
    //PRESTAMOS DEL MES - BARRAS
    /*===================================================================*/
    static public function ctrListaPrestamosmesactual(){
        $prestamosmesactual = DashboardModelo::mdlListaPrestamosmesactual();
        return $prestamosmesactual;
    }

    /*===================================================================*/
    //PRESTAMOS DEL MES - BARRAS CON FILTROS
    /*===================================================================*/
    static public function ctrListaPrestamosmesactualFiltrado($sucursalId, $periodo){
        $prestamosmesactual = DashboardModelo::mdlListaPrestamosmesactualFiltrado($sucursalId, $periodo);
        return $prestamosmesactual;
    }


    /*===================================================================*/
    //CLIENTES CON PRESTAMOS EN DATATABLE
    /*===================================================================*/
    static public function ctrClientesConPrestamos(){
        $ClientesConPrestamos = DashboardModelo::mdlClientesConPrestamos();
        return $ClientesConPrestamos;
    }


    /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    static public function ctrCuotasVencidas(){
        $CuotasVencidas = DashboardModelo::mdlCuotasVencidas();
        return $CuotasVencidas;
    }

     /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    static public function ctrNotificacion($id_usuario){
        $Notificacion = DashboardModelo::mdlNotificacion($id_usuario);
        return $Notificacion;
    }

    /*===================================================================*/
    //KPIs GERENCIALES CON FILTROS
    /*===================================================================*/
    static public function ctrListaKpisFiltrados($sucursalId, $periodo){
        $kpis = DashboardModelo::mdlListaKpisFiltrados($sucursalId, $periodo);
        return $kpis;
    }
}