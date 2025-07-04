<?php
 //require_once("../modelos/conexion.php");
 require_once("../modelos/email_caja.php");
 $CierreCorreo2 = new Email();


class CajaControlador
 {

    /*===================================================================*/
     //LISTAR CAJA EN DATATABLE CON PROCEDURE
    /*===================================================================*/
     static public function ctrListarAperturaCaja()
     {
         $ListarCaja = CajaModelo::mdlListarAperturaCaja();
         return $ListarCaja;
         var_dump('controlador',$ListarCaja);
     }

    /*===================================================================*/
      //REGISTRAR CAJA
    /*===================================================================*/
    static public function ctrRegistrarCaja($caja_descripcion, $caja_monto_inicial)
    {
        $RegCaja = CajaModelo::mdlRegistrarCaja($caja_descripcion, $caja_monto_inicial);
        return $RegCaja;
        //var_dump($RegCaja);
    }


    /*===================================================================*/
    //OBTENER DATOS PARA EL CIERRE DE  CAJA
    /*===================================================================*/
    static public function ctrObtenerDataCierreCaja(){
        $DataCierreCaja = CajaModelo::mdlObtenerDataCierreCaja();
        return $DataCierreCaja;
    }


    /*===================================================================*/
    //CERRAR LA CAJA
    /*===================================================================*/
    static public function ctrCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total,$caja_count_prestamo,$caja_count_ingreso,$caja_count_egreso,$caja_interes)
    {
        $CerrarCaja = CajaModelo::mdlCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso,$caja_interes);
        return $CerrarCaja;
        var_dump($CerrarCaja);
    }


    /*===================================================================*/
    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    /*===================================================================*/
    static public function ctrObtenerDataEstadoCaja(){
        $DataEstadoCaja = CajaModelo::mdlObtenerDataEstadoCaja();
        return $DataEstadoCaja;
    }

  
    /*===================================================================*/
    //OBTENER   ID DE LA CAJA
    /*===================================================================*/
    static public function ctrObtenerIDCaja(){
        $traerIdCaja = CajaModelo::mdlObtenerIDCaja();
        return $traerIdCaja;
    } 


    /*===================================================================*/
    //VER  PRESTAMO POR CAJA ID
    /*===================================================================*/
    static public function ctrPrestamoPorCajaID($caja_id)
    {
        $PrestamosporCajaID =  CajaModelo::mdlPrestamoPorCajaID($caja_id);
        return $PrestamosporCajaID;
    }


    /*===================================================================*/
    //VER  MOVIMIENTOS POR CAJA ID
    /*===================================================================*/
    static public function ctrMovimientosPorCajaID($caja_id)
    {
        $MovimientosporCajaID =  CajaModelo::mdlMovimientosPorCajaID($caja_id);
        return $MovimientosporCajaID;
    }
 

 }