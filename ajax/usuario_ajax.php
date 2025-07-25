<?php
// Limpiar cualquier output previo
if (ob_get_level()) {
    ob_clean();
}

// Headers para JSON limpio
header('Content-Type: application/json; charset=utf-8');

// Iniciar sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
session_start();
}

require_once "../controladores/usuario_controlador.php";
require_once "../modelos/usuario_modelo.php";

class AjaxUsuario
{
    public $nombre_usuario;
    public $apellido_usuario;
    public $usuario;
    public $clave;
    public $id_perfil_usuario;
    public $sucursal_id;
    public $telefono_whatsapp;
    public $whatsapp_activo;
    public $whatsapp_admin;
    public $cedula;
    public $ciudad;
    public $direccion;
    public $profesion;
    public $cargo;
    public $celular;
    public $fecha_ingreso;
    public $numero_seguro;
    public $forma_pago;

    /*===================================================================*/
    //LISTAR EN DATATABLE LOS USUARIOS
    /*===================================================================*/
    public function  getListarUsuarios()
    {
        try {
        $Usuario = UsuarioControlador::ctrListarUsuarios();
            
            // Verificar si hay datos
            if ($Usuario === false || $Usuario === null) {
                echo json_encode(array('error' => 'No se pudieron obtener los usuarios'));
                return;
            }
            
            echo json_encode($Usuario, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            echo json_encode(array('error' => 'Error interno: ' . $e->getMessage()));
        }
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
            $this->id_perfil_usuario,
            $this->sucursal_id,
            $this->telefono_whatsapp,
            $this->whatsapp_activo,
            $this->whatsapp_admin,
            $this->cedula,
            $this->ciudad,
            $this->direccion,
            $this->profesion,
            $this->cargo,
            $this->celular,
            $this->fecha_ingreso,
            $this->numero_seguro,
            $this->forma_pago
        );
        echo json_encode($usuario);
    }

    
    /*===================================================================*/
    //ACTUALIZAR USUARIO
    /*===================================================================*/
    public function ajaxActualizarUsuario($data)
    {
        try {
        $table = "usuarios"; //TABLA
        $id = $_POST["id_usuario"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_usuario"; //CAMPO DE LA BASE

           if (empty($id)) {
            echo json_encode(array("error" => "ID de usuario requerido"));
            return;
        }
          $respuesta = UsuarioControlador::ctrActualizarUsuario($table, $data, $id, $nameId);

        // Retornar respuesta en formato JSON
        echo json_encode($respuesta);

    } catch (Exception $e) {
        // Manejo de errores
        echo json_encode(array("error" => "Error al actualizar usuario: " . $e->getMessage()));
    }
}
     /*===================================================================*/
    //ACTIVAR USUARIO
    /*===================================================================*/
    public function ajaxActivarUsuario(){
        $table = "usuarios"; //TABLA
        $id = $_POST["id_usuario"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_usuario"; //CAMPO DE LA BASE
        $respuesta = UsuarioControlador::ctrActivarUsuario($table, $id, $nameId);

        echo json_encode($respuesta);
    }

    /*===================================================================*/
    //DAR DE BAJA USUARIO
    /*===================================================================*/
    public function ajaxBajaUsuario(){
        $table = "usuarios"; //TABLA
        $id = $_POST["id_usuario"]; //LO QUE VIENE DE PRODUCTOS.PHP
        $nameId = "id_usuario"; //CAMPO DE LA BASE
        $respuesta = UsuarioControlador::ctrBajaUsuario($table, $id, $nameId);

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





if(isset($_POST["loginUsuario"])){
    
    $usuario = trim($_POST["loginUsuario"]);
    $password = trim($_POST["loginPassword"]);

    $respuesta = UsuarioControlador::login($usuario, $password);
    
    if($respuesta && !empty($respuesta) && is_object($respuesta)){
        // Guardar datos completos en la sesión
        $_SESSION["id_usuario"] = $respuesta->id_usuario;
        $_SESSION["nombre_usuario"] = $respuesta->nombre_usuario;
        $_SESSION["perfil"] = $respuesta->perfil;
        $_SESSION["usuario"] = $respuesta;
        $_SESSION["sucursal_id"] = $respuesta->sucursal_id;
        $_SESSION["ultima_actividad"] = time();
        
        $resultado = [
            "status" => "success",
            "message" => "Login exitoso",
            "debug_info" => [
                "session_id" => session_id(),
                "user_data" => [
                    "id" => $_SESSION["id_usuario"],
                    "nombre" => $_SESSION["nombre_usuario"],
                    "perfil" => $_SESSION["perfil"]
                ]
            ]
        ];
    } else {
        $resultado = [
            "status" => "error",
            "message" => "Usuario o contraseña incorrectos"
        ];
    }

    echo json_encode($resultado);

} else if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR USUARIOS EN DATA TABLE
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
    $registroUsuario->sucursal_id = $_POST["sucursal_id"];
    $registroUsuario->telefono_whatsapp = $_POST["telefono_whatsapp"] ?? '';
    $registroUsuario->whatsapp_activo = $_POST["whatsapp_activo"] ?? 0;
    $registroUsuario->whatsapp_admin = $_POST["whatsapp_admin"] ?? 0;
    $registroUsuario->cedula = $_POST["cedula"] ?? '';
    $registroUsuario->ciudad = $_POST["ciudad"] ?? '';
    $registroUsuario->direccion = $_POST["direccion"] ?? '';
    $registroUsuario->profesion = $_POST["profesion"] ?? '';
    $registroUsuario->cargo = $_POST["cargo"] ?? '';
    $registroUsuario->celular = $_POST["celular"] ?? '';
    $registroUsuario->fecha_ingreso = $_POST["fecha_ingreso"] ?? '';
    $registroUsuario->numero_seguro = $_POST["numero_seguro"] ?? '';
    $registroUsuario->forma_pago = $_POST["forma_pago"] ?? '';

    $registroUsuario->ajaxRegistrarUsuario();

} else if (isset($_POST['accion']) && $_POST['accion'] == 'listar_perfiles') { //LISTAR PERFILES EN COMBOBOX
    $Usuario = new AjaxUsuario();
    $Usuario->ListarSelectPerfiles();

} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //ACTUALIZAR USUARIO

    $actualizarUsuario = new AjaxUsuario();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "nombre_usuario" => $_POST["nombre_usuario"],
        "apellido_usuario" => $_POST["apellido_usuario"],
        "usuario" => $_POST["usuario"],
        "id_perfil_usuario" => $_POST["id_perfil_usuario"],
        "sucursal_id" => $_POST["sucursal_id"],
        "telefono_whatsapp" => $_POST["telefono_whatsapp"] ?? '',
        "whatsapp_activo" => $_POST["whatsapp_activo"] ?? 0,
        "whatsapp_admin" => $_POST["whatsapp_admin"] ?? 0,
        "cedula" => $_POST["cedula"] ?? '',
        "ciudad" => $_POST["ciudad"] ?? '',
        "direccion" => $_POST["direccion"] ?? '',
        "profesion" => $_POST["profesion"] ?? '',
        "cargo" => $_POST["cargo"] ?? '',
        "celular" => $_POST["celular"] ?? '',
        "fecha_ingreso" => $_POST["fecha_ingreso"] ?? '',
        "numero_seguro" => $_POST["numero_seguro"] ?? '',
        "forma_pago" => $_POST["forma_pago"] ?? ''
    );
    
    // Agregar estado si se envió
    if (isset($_POST["estado"])) {
        $data["estado"] = $_POST["estado"];
    }
    
    $actualizarUsuario->ajaxActualizarUsuario($data);

}else if (isset($_POST['accion']) && $_POST['accion'] == 4) {//ACTIVAR USUARIO

    $activarUsuario = new AjaxUsuario();
    $activarUsuario->ajaxActivarUsuario();

}else if (isset($_POST['accion']) && $_POST['accion'] == 5) { //DAR DE BAJA USUARIO

    $bajaUsuario = new AjaxUsuario();
    $bajaUsuario->ajaxBajaUsuario();

}else if (isset($_POST['accion']) && $_POST['accion'] == 6) { //ACTUALIZAR CLAVE

    $actualizarClave = new AjaxUsuario();
    $data = array(
        // campo de tabla y la variable definida en el registrar
        "clave" =>crypt($_POST["clave"],'$2a$07$azybxcags23425sdg23sdfhsd$')

    );
    $actualizarClave->ajaxActualizarClaveUsuario($data);

} else {
    // Acción no válida o no especificada
    echo json_encode(array('error' => 'Acción no válida o no especificada'));
}
