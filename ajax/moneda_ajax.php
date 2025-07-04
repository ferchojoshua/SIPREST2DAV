<?php

require_once "../controladores/moneda_controlador.php";
require_once "../modelos/moneda_modelo.php";

class AjaxMoneda
{


    /*===================================================================*/
    //LISTAR MONEDAS EN DATATABLE 
    /*===================================================================*/
    public function  ListarMonedas()
    {
        $moneda = MonedaControlador::ctrListarMoneda();
        echo json_encode($moneda);
    }


    /*===================================================================*/
    //REGISTRAR MONEDAS
    /*===================================================================*/
    public function ajaxRegistraMoneda()
    {
        $moneda = MonedaControlador::ctrRegistrarMoneda(
            $this->moneda_nombre,
            $this->moneda_abrevia,
            $this->moneda_simbolo,
            $this->moneda_Descripcion
        );
        echo json_encode($moneda);
    }


    /*===================================================================*/
    //ACTUALIZAR MONEDAS
    /*===================================================================*/
    public function ajaxActualizarMoneda($data)
    {
        $table = "moneda"; //TABLA
        $id = $_POST["moneda_id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "moneda_id"; //CAMPO DE LA BASE
        $respuesta = MonedaControlador::ctrActualizarMoneda($table, $data, $id, $nameId);
        echo json_encode($respuesta);
    }


    /*===================================================================*/
    //ELIMINAR MONEDAS
    /*===================================================================*/
    public function ajaxEliminarMoneda()
    {
        $table = "moneda"; //TABLA
        $id = $_POST["moneda_id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "moneda_id"; //CAMPO DE LA BASE 
        $respuesta = MonedaControlador::ctrEliminarMoneda($table, $id, $nameId);
        echo json_encode($respuesta);
    }


    /*===================================================================*/
    //LISTAR SELECT EN COMBO
    /*===================================================================*/
    public function ListarSelectMoneda()
    {
        $Moneda = MonedaControlador::ctrListarSelectMoneda();
        echo json_encode($Moneda, JSON_UNESCAPED_UNICODE);
    }
}





if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR CLIENTE EN DATATABLE DE CLIENTE
    $moneda = new AjaxMoneda();
    $moneda->ListarMonedas();


} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA REGISTRAR LA MONEDA
    $registroMoneda = new AjaxMoneda();
    $registroMoneda->moneda_nombre = $_POST["moneda_nombre"];
    $registroMoneda->moneda_abrevia = $_POST["moneda_abrevia"];
    $registroMoneda->moneda_simbolo = $_POST["moneda_simbolo"];
    $registroMoneda->moneda_Descripcion = $_POST["moneda_Descripcion"];
    $registroMoneda->ajaxRegistraMoneda();


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //ACTUALIZAR LA MONEDA
    $actualizaroMoneda = new AjaxMoneda();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "moneda_nombre" => $_POST["moneda_nombre"],
        "moneda_abrevia" => $_POST["moneda_abrevia"],
        "moneda_simbolo" => $_POST["moneda_simbolo"],
        "moneda_Descripcion" => $_POST["moneda_Descripcion"]
    );
    $actualizaroMoneda->ajaxActualizarMoneda($data);


} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { //ELIMINAR UNA MONEDA
    $eliminarMoneda = new AjaxMoneda();
    $eliminarMoneda->ajaxEliminarMoneda();


} else {

    $Moneda = new AjaxMoneda(); // SELECT EN COMBO
    $Moneda->ListarSelectMoneda();
}
