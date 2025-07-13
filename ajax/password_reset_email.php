<?php
require_once "../controladores/usuario_controlador.php";
require_once "../modelos/usuario_modelo.php";
require_once "../PHPMailer/src/Exception.php";
require_once "../PHPMailer/src/PHPMailer.php";
require_once "../PHPMailer/src/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AjaxPasswordResetEmail {

    public $usuario;

    public function enviarCorreoRestablecimiento() {
        // 1. Verificar que el usuario existe y obtener sus datos
        $user_data = UsuarioModelo::mdlVerificarUsuarioExiste($this->usuario);

        if (!$user_data) {
            echo json_encode(['success' => false, 'message' => 'El usuario proporcionado no existe.']);
            return;
        }

        // Verificar que el usuario tenga un correo electrónico
        if (empty($user_data['correo'])) {
            echo json_encode(['success' => false, 'message' => 'El usuario no tiene un correo electrónico registrado.']);
            return;
        }

        // 2. Generar una contraseña temporal aleatoria
        $temp_password = $this->generarClaveAleatoria();
        
        // 3. Encriptar la contraseña temporal
        $password_encriptada = crypt($temp_password, '$2a$07$azybxcags23425sdg23sdfhsd$');
        
        // 4. Actualizar la contraseña en la base de datos
        $tabla = "usuarios";
        $data = ["clave" => $password_encriptada];
        $id = $user_data['id_usuario'];
        $nameId = "id_usuario";
        
        $respuesta = UsuarioModelo::mdlActualizarClaveUsuario($tabla, $data, $id, $nameId);

        if ($respuesta != "ok") {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al actualizar la contraseña en la base de datos.'
            ]);
            return;
        }

        // 5. Enviar correo electrónico con la contraseña temporal
        $mail = new PHPMailer(true);
        
        try {
            // Configuración del servidor
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Cambiar según tu servidor SMTP
            $mail->SMTPAuth = true;
            $mail->Username = 'siprestaitsolutions@gmail.com'; // Cambiar por tu correo
            $mail->Password = 'Sipresta2025'; // Cambiar por tu contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Configuración de remitente y destinatario
            $mail->setFrom('tu_correo@gmail.com', 'SIPREST - Sistema de Préstamos');
            $mail->addAddress($user_data['correo'], $user_data['nombre_usuario'] . ' ' . $user_data['apellido_usuario']);
            
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de Contraseña - SIPREST';
            
            // Obtener el nombre de la empresa para el correo
            $empresa = $this->obtenerDatosEmpresa();
            
            $mail->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <h2 style="color: #3498db;">Restablecimiento de Contraseña</h2>
                    <p style="font-size: 16px;">' . $empresa['nombre'] . '</p>
                </div>
                
                <p>Estimado/a <strong>' . $user_data['nombre_usuario'] . ' ' . $user_data['apellido_usuario'] . '</strong>,</p>
                
                <p>Hemos recibido una solicitud para restablecer la contraseña de su cuenta en el sistema SIPREST.</p>
                
                <p>Su nueva contraseña temporal es: <strong style="background-color: #f8f9fa; padding: 5px 10px; border-radius: 3px;">' . $temp_password . '</strong></p>
                
                <p>Por favor, inicie sesión con esta contraseña temporal y cámbiela inmediatamente por una contraseña segura de su elección.</p>
                
                <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;">
                    <p style="margin: 0; color: #555;">
                        <strong>Nota de seguridad:</strong> Si usted no solicitó este cambio de contraseña, por favor contacte al administrador del sistema inmediatamente.
                    </p>
                </div>
                
                <p>Saludos cordiales,</p>
                <p><strong>' . $empresa['nombre'] . '</strong><br>Sistema de Préstamos</p>
                
                <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #777; font-size: 12px;">
                    <p>Este es un correo automático, por favor no responda a este mensaje.</p>
                </div>
            </div>';
            
            $mail->send();
            
            echo json_encode([
                'success' => true, 
                'message' => 'Se ha enviado una contraseña temporal a su correo electrónico.'
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al enviar el correo: ' . $mail->ErrorInfo
            ]);
        }
    }
    
    /**
     * Genera una contraseña aleatoria segura
     */
    private function generarClaveAleatoria($longitud = 8) {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()';
        $clave = '';
        $max = strlen($caracteres) - 1;
        
        for ($i = 0; $i < $longitud; $i++) {
            $clave .= $caracteres[random_int(0, $max)];
        }
        
        return $clave;
    }
    
    /**
     * Obtiene los datos de la empresa para el correo
     */
    private function obtenerDatosEmpresa() {
        $mysqli = new mysqli('localhost', 'root', '', 'dbprestamo');
        
        if ($mysqli->connect_error) {
            return ['nombre' => 'SIPREST'];
        }
        
        $query = "SELECT confi_razon FROM empresa WHERE confi_id = 1";
        $resultado = $mysqli->query($query);
        
        if ($resultado && $row = $resultado->fetch_assoc()) {
            return ['nombre' => $row['confi_razon']];
        }
        
        return ['nombre' => 'SIPREST'];
    }
}

// Procesar la solicitud
if (isset($_POST['usuario'])) {
    $reset = new AjaxPasswordResetEmail();
    $reset->usuario = $_POST['usuario'];
    $reset->enviarCorreoRestablecimiento();
} else {
    echo json_encode(['success' => false, 'message' => 'Falta el parámetro de usuario.']);
} 