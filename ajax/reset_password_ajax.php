<?php

require_once "../controladores/usuario_controlador.php";
require_once "../modelos/usuario_modelo.php";

class AjaxResetPassword{

    public $usuario;
    public $nueva_password;

    public function ajaxResetearPassword(){

        // 1. Validar que el usuario existe
        $user_data = UsuarioModelo::mdlVerificarUsuarioExiste($this->usuario);

        if(!$user_data){
            echo json_encode(['success' => false, 'message' => 'El usuario proporcionado no existe.']);
            return;
        }

        // 2. Encriptar la nueva contraseña de forma segura
        $password_encriptada = crypt($this->nueva_password, '$2a$07$azybxcags23425sdg23sdfhsd$');
        
        // 3. Actualizar la contraseña en la base de datos
        $tabla = "usuarios";
        $data = ["clave" => $password_encriptada];
        $id = $user_data['id_usuario'];
        $nameId = "id_usuario";
        
        $respuesta = UsuarioModelo::mdlActualizarClaveUsuario($tabla, $data, $id, $nameId);

        if($respuesta == "ok"){
            echo json_encode([
                'success' => true, 
                'message' => 'Contraseña actualizada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al actualizar la contraseña.'
            ]);
        }
    }
}

if(isset($_POST['usuario']) && isset($_POST['nueva_password'])){
    $reset = new AjaxResetPassword();
    $reset->usuario = $_POST['usuario'];
    $reset->nueva_password = $_POST['nueva_password'];
    $reset->ajaxResetearPassword();
} else {
    echo json_encode(['success' => false, 'message' => 'Faltan parámetros.']);
} 
 