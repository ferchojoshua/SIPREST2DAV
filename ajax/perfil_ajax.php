<?php

require_once "../controladores/perfil_controlador.php";
require_once "../modelos/perfil_modelo.php";

class AjaxPerfiles
{
    /*===================================================================*/
    //OBTENER PERFILES
    /*===================================================================*/
    public function ajaxObtenerPerfiles(){

        $perfiles = PerfilControlador::ctrObtenerPerfiles();

        echo json_encode($perfiles);
    }


    /*===================================================================*/
    //REGISTRAR PERFIL
    /*===================================================================*/
    public function ajaxRegistrarPerfil()
    {
        $perfil = PerfilControlador::ctrRegistrarPerfil(
            $this->descripcion

        );
        echo json_encode($perfil);
    }

    /*===================================================================*/
    //ACTUALIZAR PERFIL
    /*===================================================================*/
    public function ajaxActualizarPerfil($data)
    {
        $table = "perfiles"; //TABLA
        $id = $_POST["id_perfil"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_perfil"; //CAMPO DE LA BASEbien bebe

        $respuesta = PerfilControlador::ctrActualizarPerfil($table, $data, $id, $nameId);

        echo json_encode($respuesta);
    }


    /*===================================================================*/
    //ELIMINAR PERFIL
    /*===================================================================*/
    public function ajaxEliminarPerfil(){
        $table = "perfiles"; //TABLA
        $id = $_POST["id_perfil"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_perfil"; //CAMPO DE LA BASE
        $respuesta = PerfilControlador::ctrEliminarPerfil($table, $id, $nameId);

        echo json_encode($respuesta);

    }
   
}


if(isset($_POST['accion']) && $_POST['accion'] == 1){

    $perfiles = new AjaxPerfiles;    
    $perfiles->ajaxObtenerPerfiles();

}else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA REGISTRAR PERFIL

    $registroPerfil = new AjaxPerfiles();
    $registroPerfil->descripcion = $_POST["descripcion"];
    $registroPerfil->ajaxRegistrarPerfil();

}else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //ACTUALIZAR PERFIL

    $actualizarPerfil = new AjaxPerfiles();
    $data = array(
        // "id_categoria_producto" => $_POST["id_categoria_producto"],//campo de tabla y la variable definida en el registrar
        "descripcion" => $_POST["descripcion"],
       // "estado" => $_POST["estado"],

    );
    $actualizarPerfil->ajaxActualizarPerfil($data);

}else if (isset($_POST['accion']) && $_POST['accion'] == 4) {//ELIMINAR PERFIL

    //ELIMINAR UN PERFIL
    $eliminarPerfil  = new AjaxPerfiles();
    $eliminarPerfil -> ajaxEliminarPerfil();


}