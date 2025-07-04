<?php

require_once "../controladores/usuario_controlador.php";
require_once "../modelos/usuario_modelo.php";

class AjaxUsuario
{
    public $nombre_usuario;
    public $apellido_usuario;
    public $usuario;
    public $clave;
    public $id_perfil_usuario;

    /*===================================================================*/
    //LISTAR EN DATATABLE LOS USUARIOS
    /*===================================================================*/
    public function  getListarUsuarios()
    {
        $Usuario = UsuarioControlador::ctrListarUsuarios();
        echo json_encode($Usuario);
    }

    /*===================================================================*/
    //LISTAR PERFILES EN COMBOBOX DE USUARIO
    /*===================================================================*/
    public function ListarSelectPerfiles()
    {
        $perfiles = UsuarioControlador::ctrListarSelectPerfiles();
        echo json_encode($perfiles, JSON_UNESCAPED_UNICODE);
    }



    /*===================================================================*/
    //REGISTRAR USUARIO
    /*===================================================================*/
    public function ajaxRegistrarUsuario()
    {
        $usuario = UsuarioControlador::ctrRegistrarUsuario(
            $this->nombre_usuario,
            $this->apellido_usuario,
            $this->usuario,
            $this->clave,
            $this->id_perfil_usuario

        );
        echo json_encode($usuario);
    }

    
    /*===================================================================*/
    //ACTUALIZAR USUARIO
    /*===================================================================*/
    public function ajaxActualizarUsuario($data)
    {
        $table = "usuarios"; //TABLA
        $id = $_POST["id_usuario"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_usuario"; //CAMPO DE LA BASEbien bebe

        $respuesta = UsuarioControlador::ctrActualizarUsuario($table, $data, $id, $nameId);

        echo json_encode($respuesta);
    }

     /*===================================================================*/
    //ELIMINAR USUARIO
    /*===================================================================*/
    public function ajaxEliminarUsuario(){
        $table = "usuarios"; //TABLA
        $id = $_POST["id_usuario"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_usuario"; //CAMPO DE LA BASEbien bebe
        $respuesta = UsuarioControlador::ctrEliminarUsuario($table, $id, $nameId);

        echo json_encode($respuesta);

    }


    /*===================================================================*/
    //ACTUALIZAR CLAVE DEL USUARIO
    /*===================================================================*/
    public function ajaxActualizarClaveUsuario($data){
        $table = "usuarios"; //TABLA
        $id = $_POST["id_usuario"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_usuario"; //CAMPO DE LA BASEbien Debe

        $respuesta = UsuarioControlador::ctrActualizarClaveUsuario( $table,$data, $id, $nameId);

        echo json_encode($respuesta);

    }
}





if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR USUARIOS EN DATA TABLE
    $Usuario = new AjaxUsuario();
    $Usuario->getListarUsuarios();

} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA REGISTRAR EL USUARIO

    $registroUsuario = new AjaxUsuario();
    $registroUsuario->nombre_usuario = $_POST["nombre_usuario"];
    $registroUsuario->apellido_usuario = $_POST["apellido_usuario"];
    $registroUsuario->usuario = $_POST["usuario"];
    $registroUsuario->clave= crypt($_POST["clave"],'$2a$07$azybxcags23425sdg23sdfhsd$');
    //$registroUsuario->clave= password_hash($_POST['clave'],PASSWORD_DEFAULT,['cost'=>12]);$password = crypt($_POST["loginPassword"],'$2a$07$azybxcags23425sdg23sdfhsd$');
    $registroUsuario->id_perfil_usuario = $_POST["id_perfil_usuario"];

    $registroUsuario->ajaxRegistrarUsuario();

}else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //ACTUALIZAR USUARIO

    $actualizarUsuario = new AjaxUsuario();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "nombre_usuario" => $_POST["nombre_usuario"],
        "apellido_usuario" => $_POST["apellido_usuario"],
        "usuario" => $_POST["usuario"],
        "id_perfil_usuario" => $_POST["id_perfil_usuario"]

    );
    $actualizarUsuario->ajaxActualizarUsuario($data);

}else if (isset($_POST['accion']) && $_POST['accion'] == 4) {//ELIMINAR UN USUARIO

    
    $eliminarUsuario = new AjaxUsuario();
    $eliminarUsuario -> ajaxEliminarUsuario();


}else if (isset($_POST['accion']) && $_POST['accion'] == 5) { //ACTUALIZAR CLAVE

    $actualizarClave = new AjaxUsuario();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "clave" =>crypt($_POST["clave"],'$2a$07$azybxcags23425sdg23sdfhsd$')

    );
    $actualizarClave->ajaxActualizarClaveUsuario($data);

}  else {
    $selectPerfiles = new AjaxUsuario();
    $selectPerfiles->ListarSelectPerfiles(); //LISTAR PERFILES EN COMBO

}
