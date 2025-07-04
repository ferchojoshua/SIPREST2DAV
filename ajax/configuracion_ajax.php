<?php
require_once "../controladores/configuracion_controlador.php";
require_once "../modelos/configuracion_modelo.php";
require_once "../vendor/autoload.php";

class AjaxConfiguracion
{

    /*===================================================================*/
    //OBTENER TODOS LOS DATOS DE LA EMPRESA 
    /*===================================================================*/
    public function ajaxObtenerDataEmpresa(){
        $DataEmpresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        echo json_encode($DataEmpresa, JSON_UNESCAPED_UNICODE);
    }



    /*===================================================================*/
    //ACTUALIZAR DATOS DE LA EMPRESA
    /*===================================================================*/
    public function ajaxActualizarConfiguracion($data)
    {
        $table = "empresa"; //TABLA
        $id = $_POST["confi_id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "confi_id"; //CAMPO DE LA BASEbien bebe

        $respuesta = ConfiguracionControlador::ctrActualizarConfiguracion($table, $data, $id, $nameId);

        echo json_encode($respuesta);
    }


    /*===================================================================*/
    //OBTENER CORRELATIVO
    /*===================================================================*/
    public function ajaxObtenerCorrelativo(){
        $correlativo = ConfiguracionControlador::ctrObtenerCorrelativo();
        echo json_encode($correlativo, JSON_UNESCAPED_UNICODE);
    }
}



//instanciamos para que se ejecute la funcion
if (isset($_POST['accion']) && $_POST['accion'] == 1) {    //OBTENER TODOS LOS DATOS DE LA EMPRESA 
    $DataEmpresa = new AjaxConfiguracion();
    $DataEmpresa->ajaxObtenerDataEmpresa(); //creamos el metodo

}else if (isset($_POST['accion']) && $_POST['accion'] == 2) {       //ACTUALIZAR DATOS DE LA EMPRESA

    $actualizarConfig = new AjaxConfiguracion();
    $data = array(
        "confi_razon" => $_POST["confi_razon"],
        "confi_ruc" => $_POST["confi_ruc"],
        "confi_direccion" => $_POST["confi_direccion"],
        "confi_correlativo" => $_POST["confi_correlativo"],
        "confi_correlativo" => $_POST["confi_correlativo"],
        "config_correo" => $_POST["config_correo"],
        "config_moneda" => $_POST["config_moneda"]

    );
    $actualizarConfig->ajaxActualizarConfiguracion($data);

}else if (isset($_POST['accion']) && $_POST['accion'] == 3) {   //OBTENER CORRELATIVO
    $correlativo = new AjaxConfiguracion();
    $correlativo->ajaxObtenerCorrelativo(); //creamos el metodo

}
