<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Requerir archivos necesarios, incluyendo la configuración central de correo
require_once __DIR__ . '/../utilitarios/email_config.php';
require_once __DIR__ . '/../PHPMailer/src/Exception.php';
require_once __DIR__ . '/../PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../modelos/conexion.php';
require_once __DIR__ . '/../controladores/configuracion_controlador.php';
require_once __DIR__ . '/../modelos/configuracion_modelo.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PasswordResetEmail {
    
    public function handleRequest() {
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['usuario_o_correo'])) {
            $this->sendPasswordResetEmail($_POST['usuario_o_correo']);
        }
    }

    private function sendPasswordResetEmail($userOrEmail) {
        // ... (código existente para buscar usuario y generar contraseña)
        // 1. Validar que el campo no esté vacío
        if (empty($userOrEmail)) {
            echo json_encode(['error' => 'Por favor, ingrese su usuario o correo electrónico.']);
            return;
        }

        // 2. Buscar al usuario en la base de datos
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE nombre_usuario = :user OR correo = :email");
        $stmt->bindParam(':user', $userOrEmail, PDO::PARAM_STR);
        $stmt->bindParam(':email', $userOrEmail, PDO::PARAM_STR);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user_data) {
            echo json_encode(['error' => 'El usuario o correo electrónico no se encuentra registrado.']);
            return;
        }

        // 3. Generar una contraseña temporal segura
        $temporal_password = bin2hex(random_bytes(5)); // 10 caracteres

        // 4. Actualizar la contraseña en la base de datos (hasheada)
        $hashed_password = password_hash($temporal_password, PASSWORD_DEFAULT);
        $stmt_update = Conexion::conectar()->prepare("UPDATE usuarios SET password = :password WHERE id_usuario = :id");
        $stmt_update->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt_update->bindParam(':id', $user_data['id_usuario'], PDO::PARAM_INT);
        $stmt_update->execute();
        
        // 5. Enviar correo electrónico con la contraseña temporal
        $mail = new PHPMailer(true);
        
        try {
            // Usar la configuración central
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            
            // Configuración de remitente y destinatario
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($user_data['correo'], $user_data['nombre_usuario']);
            
            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de Contraseña - SIPREST';
            
            // Obtener el nombre de la empresa para el correo
            $empresa = $this->obtenerDatosEmpresa();
            
            $mail->Body = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                    <h2 style="color: #333;">Restablecimiento de Contraseña para ' . htmlspecialchars($empresa['nombre']) . '</h2>
                    <p>Hola ' . htmlspecialchars($user_data['nombre_usuario']) . ',</p>
                    <p>Has solicitado restablecer tu contraseña. A continuación, te proporcionamos una nueva contraseña temporal:</p>
                    <p style="text-align: center; font-size: 20px; font-weight: bold; background-color: #f2f2f2; padding: 10px; border-radius: 5px;">
                        ' . $temporal_password . '
                    </p>
                    <p>Te recomendamos encarecidamente que inicies sesión con esta contraseña y la cambies de inmediato en la sección de tu perfil.</p>
                    <hr>
                    <p style="font-size: 12px; color: #777;">Si no solicitaste este cambio, puedes ignorar este correo electrónico de forma segura.</p>
                </div>
            ';
            
            $mail->send();
            echo json_encode(['exito' => 'Se ha enviado una nueva contraseña a tu correo electrónico.']);
            
        } catch (Exception $e) {
            echo json_encode(['error' => 'No se pudo enviar el correo. Por favor, contacta al administrador. Mailer Error: ' . $mail->ErrorInfo]);
        }
    }

    private function obtenerDatosEmpresa() {
        $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
        return [
            'nombre' => $empresa->confi_razon ?? 'Sistema de Préstamos'
        ];
    }
}

$passwordReset = new PasswordResetEmail();
$passwordReset->handleRequest(); 