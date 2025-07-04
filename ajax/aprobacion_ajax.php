<?php

require_once "../controladores/aprobacion_controlador.php";
require_once "../modelos/aprobacion_modelo.php";

class AjaxAprobacion
{

    /*===================================================================*/
    //LISTAR PRESTAMOS EN DATATABLE POR APROBACION
    /*===================================================================*/
    public function  ListarPrestamosPorAprobacion($fecha_ini,$fecha_fin)
    {
        $aprobacionP = AprobacionControlador::ctrListarPrestamosPorAprobacion($fecha_ini,$fecha_fin);
        echo json_encode($aprobacionP);
    }


    /*===================================================================*/
    //APROBAR PRESTAMO
    /*===================================================================*/
    public function ajaxActualizarEstadoPrest()
    {
        $Aprobar = AprobacionControlador::ctrActualizarEstadoPrest($this->nro_prestamo);
        echo json_encode($Aprobar);
    }


    /*===================================================================*/
    //DESAPROBAR PRESTAMO
    /*===================================================================*/
    public function ajaxDesaprobarPrest()
    {
        $Desaprobar = AprobacionControlador::ctrDesaprobarPrest($this->nro_prestamo);
        echo json_encode($Desaprobar);
    }


    /*===================================================================*/
     //ANULAR PRESTAMO
     /*===================================================================*/
     public function ajaxAnularPrest()
     {
         $AnularP = AprobacionControlador::ctrAnularPrest($this->nro_prestamo);
         echo json_encode($AnularP);
     }
}

if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR  PRESTAMOS POR APROBACION
    $aprobacionP = new AjaxAprobacion();
    $aprobacionP->ListarPrestamosPorAprobacion($_POST["fecha_ini"],$_POST["fecha_fin"]);
    

} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA APROBAR EL PRESTAMO
    $Aprobar = new AjaxAprobacion();
    $Aprobar->nro_prestamo = $_POST["nro_prestamo"];
    $Aprobar->ajaxActualizarEstadoPrest();


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //PARA DESAPROBAR EL PRESTAMO
    $Desaprobar = new AjaxAprobacion();
    $Desaprobar->nro_prestamo = $_POST["nro_prestamo"];
    $Desaprobar->ajaxDesaprobarPrest();


} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { //PARA ANULAR EL PRESTAMO
    $AnularP = new AjaxAprobacion();
    $AnularP->nro_prestamo = $_POST["nro_prestamo"];
    $AnularP->ajaxAnularPrest();
}
