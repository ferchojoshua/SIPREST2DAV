<?php

require_once "../controladores/movimientos_controlador.php";
require_once "../modelos/movimientos_modelo.php";

class AjaxMovimientos
{

    /*===================================================================*/
     //LISTAR MOVIMIENTOS EN DATATABLE 
     /*===================================================================*/
     public function  ListarMovimientos()
     {
         $Movimientos = MovimientosControlador::ctrListarMovimientos();
         echo json_encode($Movimientos);
     }


     /*===================================================================*/
     //REGISTRAR MOVIMIENTOS
     /*===================================================================*/
    public function ajaxRegistraMovi()
    {
        $registroMovi = MovimientosControlador::ctrRegistrarMovi(
            $this->movi_tipo,
            $this->movi_descripcion,
            $this->movi_monto,
            $this->caja_id
         );
        echo json_encode($registroMovi);
    }



    /*===================================================================*/
    //ACTUALIZAR MOVIMIENTOS
    /*===================================================================*/
    public function ajaxActualizarMovi($data)
    {
        $table = "movimientos"; //TABLA
        $id = $_POST["movimientos_id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "movimientos_id"; //CAMPO DE LA BASE

        $respuesta = MovimientosControlador::ctrActualizarMovi($table, $data, $id, $nameId);

        echo json_encode($respuesta);
    }


    /*===================================================================*/
    //ELIMINAR MONEDAS
    /*===================================================================*/
    public function ajaxEliminarMovi()
    {
       $respuesta = MovimientosControlador::ctrEliminarMovi($this->movimientos_id);

        echo json_encode($respuesta);
    }



}


if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR CLIENTE EN DATATABLE DE CLIENTE
    $Movimientos = new AjaxMovimientos();
    $Movimientos->ListarMovimientos();


} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA REGISTRAR LA MONEDA

    $registroMovi = new AjaxMovimientos();
    $registroMovi->movi_tipo = $_POST["movi_tipo"];
    $registroMovi->movi_descripcion = $_POST["movi_descripcion"];
    $registroMovi->movi_monto = $_POST["movi_monto"];
    $registroMovi->caja_id = $_POST["caja_id"];
    $registroMovi->ajaxRegistraMovi();


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //ACTUALIZAR LA MONEDA

    $actualizarMovi = new AjaxMovimientos();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "movi_tipo" => $_POST["movi_tipo"],
        "movi_descripcion" => $_POST["movi_descripcion"],
        "movi_monto" => $_POST["movi_monto"]
       );
    $actualizarMovi->ajaxActualizarMovi($data);
} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { //ELIMINAR UNA MONEDA


    $eliminarMovi = new AjaxMovimientos();
    $eliminarMovi->movimientos_id = $_POST["movimientos_id"];
    $eliminarMovi->ajaxEliminarMovi();
}