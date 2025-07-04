<?php
require_once '../../../conexion_reportes/r_conexion.php';

// Obtener logo de la empresa
$query = "SELECT config_logo FROM empresa WHERE confi_id = 1";
$resultado = $mysqli->query($query);

$logo_path = 'img/avatar.svg'; // Logo por defecto

if ($resultado && $row = $resultado->fetch_assoc()) {
    if (!empty($row['config_logo']) && file_exists('../../../uploads/logos/' . $row['config_logo'])) {
        $logo_path = '../../../uploads/logos/' . $row['config_logo'];
    }
}

echo $logo_path;
?> 