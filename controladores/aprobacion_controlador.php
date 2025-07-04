<?php

class AprobacionControlador
{

    /*===================================================================*/
     //LISTAR PRESTAMOS  CON PROCEDURE  EN DATATABLE
     /*===================================================================*/
     static public function ctrListarPrestamosPorAprobacion($fecha_ini,$fecha_fin)
     {
         $aprobacionP =  AprobacionModelo::mdlListarPrestamosPorAprobacion($fecha_ini,$fecha_fin);
         return $aprobacionP;
     }


     /*===================================================================*/
    //APROBAR PRESTAMO
    /*===================================================================*/
    static public function ctrActualizarEstadoPrest($nro_prestamo)
    {
        $Aprobar = AprobacionModelo::mdlActualizarEstadoPrest($nro_prestamo);
        return $Aprobar;
    }


    /*===================================================================*/
      //DESAPROBAR PRESTAMO
    /*===================================================================*/
      static public function ctrDesaprobarPrest($nro_prestamo)
      {
          $Desaprobar = AprobacionModelo::mdlDesaprobarPrest($nro_prestamo);
          return $Desaprobar;
      }


    /*===================================================================*/
      //DESAPROBAR PRESTAMO
    /*===================================================================*/
      static public function ctrAnularPrest($nro_prestamo)
      {
          $AnularP = AprobacionModelo::mdlAnularPrest($nro_prestamo);
          return $AnularP;
      }


}