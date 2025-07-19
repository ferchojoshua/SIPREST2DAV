<?php
require_once "../controladores/grupos_reportes_controlador.php";
require_once "../modelos/grupos_reportes_modelo.php";

class AjaxGruposReportes{

    /*=============================================
    EDITAR GRUPO
    =============================================*/ 
    public $idGrupo;

    public function ajaxEditarGrupo(){
        $item = "grupo_id";
        $valor = $this->idGrupo;
        $respuesta = ControladorGruposReportes::ctrMostrarGrupos($item, $valor);
        echo json_encode($respuesta);
    }

    /*=============================================
    AGREGAR MIEMBRO
    =============================================*/
    public $idGrupoMiembro;
    public $emailMiembro;
    public $nombreMiembro;

    public function ajaxAgregarMiembro(){
        $datos = array(
            "grupo_id"    => $this->idGrupoMiembro,
            "email"       => $this->emailMiembro,
            "nombre"      => $this->nombreMiembro
        );
        $respuesta = ModeloGruposReportes::mdlAgregarMiembro("reporte_grupo_miembros", $datos);
        echo $respuesta;
    }

    /*=============================================
    BORRAR MIEMBRO
    =============================================*/
    public $idMiembro;

    public function ajaxBorrarMiembro(){
        $respuesta = ModeloGruposReportes::mdlBorrarMiembro("reporte_grupo_miembros", $this->idMiembro);
        echo $respuesta;
    }

    /*=============================================
    MOSTRAR MIEMBROS
    =============================================*/
    public $idGrupoParaMiembros;

    public function ajaxMostrarMiembros(){
        $item = "grupo_id";
        $valor = $this->idGrupoParaMiembros;
        $respuesta = ControladorGruposReportes::ctrMostrarMiembros($item, $valor);
        echo json_encode($respuesta);
    }
}


/*=============================================
OBJETOS AJAX
=============================================*/
if(isset($_POST["idGrupo"])){
    $editar = new AjaxGruposReportes();
    $editar -> idGrupo = $_POST["idGrupo"];
    $editar -> ajaxEditarGrupo();
}

if(isset($_POST["accion"]) && $_POST["accion"] == "agregarMiembro"){
    $agregar = new AjaxGruposReportes();
    $agregar -> idGrupoMiembro = $_POST["idGrupoMiembro"];
    $agregar -> emailMiembro = $_POST["emailMiembro"];
    $agregar -> nombreMiembro = $_POST["nombreMiembro"];
    $agregar -> ajaxAgregarMiembro();
}

if(isset($_POST["accion"]) && $_POST["accion"] == "obtener_todos_los_grupos"){
    $grupos = ControladorGruposReportes::ctrMostrarGrupos(null, null);
    echo json_encode($grupos);
}

if(isset($_POST["idMiembro"])){
    $borrar = new AjaxGruposReportes();
    $borrar -> idMiembro = $_POST["idMiembro"];
    $borrar -> ajaxBorrarMiembro();
}

if(isset($_POST["idGrupoParaMiembros"])){
    $mostrar = new AjaxGruposReportes();
    $mostrar -> idGrupoParaMiembros = $_POST["idGrupoParaMiembros"];
    $mostrar -> ajaxMostrarMiembros();
} 