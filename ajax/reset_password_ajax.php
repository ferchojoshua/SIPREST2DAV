<?php
require_once '../conexion_reportes/r_conexion.php';

header('Content-Type: application/json');

if ($_POST) {
    $usuario = trim($_POST['usuario']);
    $nueva_password = trim($_POST['nueva_password']);
    
    // Validaciones básicas
    if (empty($usuario) || empty($nueva_password)) {
        echo json_encode(['success' => false, 'message' => 'Usuario y contraseña son requeridos']);
        exit;
    }
    
    if (strlen($nueva_password) < 6) {
        echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
        exit;
    }
    
    try {
        // Verificar que el usuario existe
        $query_check = "SELECT id_usuario FROM usuarios WHERE usuario = ?";
        $stmt_check = $mysqli->prepare($query_check);
        $stmt_check->bind_param("s", $usuario);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();
        
        if ($result_check->num_rows == 0) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            exit;
        }
        
        // Encriptar la nueva contraseña usando el mismo método del sistema
        $password_encriptada = crypt($nueva_password, '$2a$07$azybxcags23425sdg23sdfhsd$');
        
        // Actualizar la contraseña
        $query_update = "UPDATE usuarios SET clave = ? WHERE usuario = ?";
        $stmt_update = $mysqli->prepare($query_update);
        $stmt_update->bind_param("ss", $password_encriptada, $usuario);
        
        if ($stmt_update->execute()) {
            echo json_encode([
                'success' => true, 
                'message' => 'Contraseña actualizada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Error al actualizar la contraseña'
            ]);
        }
        
        $stmt_check->close();
        $stmt_update->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false, 
            'message' => 'Error del servidor: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}

$mysqli->close();
?> 
 