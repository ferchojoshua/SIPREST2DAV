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
    //PRESTAMOS DEL MES - BARRAS
    /*===================================================================*/
    static public function ctrListaPrestamosmesactual(){
        $prestamosmesactual = DashboardModelo::mdlListaPrestamosmesactual();
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
}