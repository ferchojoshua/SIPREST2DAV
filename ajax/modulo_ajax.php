<?php

require_once "../controladores/modulo_controlador.php";
require_once "../modelos/modulo_modelo.php";

class AjaxModulo
{

    //para guardar
    public $modulo;
    public $vista;
    public $icon_menu;




    /*===================================================================*/
    //MODULOS
    /*===================================================================*/
    public function ajaxObtenerModulos(){

        $modulos = ModuloControlador::ctrObtenerModulos();

        echo json_encode($modulos);
    }


    /*===================================================================*/
    //MODULOS SEGUN EL PERFIL - ADMIN O VENDE
    /*===================================================================*/
    public function ajaxObtenerModulosPorPerfil($id_perfil){

        $modulosPerfil = ModuloControlador::ctrObtenerModulosPorPerfil($id_perfil);

        echo json_encode($modulosPerfil);
    }

    /*===================================================================*/
    //LISTA DE MODULOS PARA EL DATATABLE
    /*===================================================================*/
    public function ajaxObtenerListaModulos(){

        $modulos = ModuloControlador::ctrObtenerListaModulos();

        echo json_encode($modulos);
    }


    /*==============================================================
    FNC PARA REORGANIZAR LOS MODULOS DEL SISTEMA
    ==============================================================*/
    public function ajaxReorganizarModulos($modulos_ordenados){

        $modulosOrdenados = ModuloControlador::ctrReorganizarModulos($modulos_ordenados);

        echo json_encode($modulosOrdenados);
    }


    /*===================================================================*/
    //REGISTRAR MODULOS
    /*===================================================================*/
    public function AjaxRegistrarModulos()
    {
        $modulos = ModuloControlador::ctrRegistrarModulos(
            $this->modulo,
            $this->vista,
            $this->icon_menu

        );
        echo json_encode($modulos);
    }

    
    /*===================================================================*/
    //ACTUALIZAR MODULOS
    /*===================================================================*/
    public function ajaxActualizarModulos($data)
    {
        $table = "modulos"; //TABLA
        $id = $_POST["id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id"; //CAMPO DE LA BASEbien bebe

        $respuesta = ModuloControlador::ctrActualizarModulos($table, $data, $id, $nameId);

        echo json_encode($respuesta);
    }


    
    /*===================================================================*/
    //ELIMINAR MODULO
    /*===================================================================*/
    public function ajaxEliminarModulo(){
        $table = "modulos"; //TABLA
        $id = $_POST["id"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id"; //CAMPO DE LA BASE
        $respuesta = ModuloControlador::ctrEliminarModulo($table, $id, $nameId);
        echo json_encode($respuesta);

    }
   
}


if(isset($_POST['accion']) && $_POST['accion'] == 1){

    $modulos = new AjaxModulo;    
    $modulos->ajaxObtenerModulos();

}else if(isset($_POST['accion']) && $_POST['accion'] == 2){
        
    $modulosPerfil = new AjaxModulo;    
    $modulosPerfil->ajaxObtenerModulosPorPerfil($_POST["id_perfil"]);

}else if(isset($_POST['accion']) && $_POST['accion'] == 3){

    $perfiles = new AjaxModulo;    
    $perfiles->ajaxObtenerListaModulos();

}else if(isset($_POST['accion']) && $_POST['accion'] == 4){ 

    $organizar_modulos = new AjaxModulo;
    $organizar_modulos->ajaxReorganizarModulos($_POST["modulos"]);

}else if (isset($_POST['accion']) && $_POST['accion'] == 5) { //PARA REGISTRAR MODULOS

    $modulos = new AjaxModulo();
    $modulos->modulo = $_POST["modulo"];
    $modulos->vista = $_POST["vista"];
    $modulos->icon_menu = $_POST["icon_menu"];
    $modulos->AjaxRegistrarModulos();

} else if (isset($_POST['accion']) && $_POST['accion'] == 6) { //ACTUALIZAR MODULOS

    $actualizarModulos = new AjaxModulo();
    $data = array(
        // "id_categoria_producto" => $_POST["id_categoria_producto"],//campo de tabla y la variable definida en el registrar
        "modulo" => $_POST["modulo"],
        "vista" => $_POST["vista"],
        "icon_menu" => $_POST["icon_menu"],
    );
    $actualizarModulos->ajaxActualizarModulos($data);

}else if (isset($_POST['accion']) && $_POST['accion'] == 7) {// PARA ELIMINAR MODULOS

    //ELIMINAR UN MODULO
    $eliminarModulo = new AjaxModulo();
    $eliminarModulo -> ajaxEliminarModulo();


}