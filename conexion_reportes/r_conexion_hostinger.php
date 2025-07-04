<?php 
// CONFIGURACIÓN PARA HOSTINGER
// Reemplaza estos valores con los datos de tu hosting en Hostinger

$servidor = 'localhost'; // O la IP que te proporcione Hostinger
$usuario_bd = 'u123456789_usuario'; // Tu usuario de base de datos de Hostinger
$password_bd = 'tu_password_seguro'; // Tu contraseña de base de datos
$nombre_bd = 'u123456789_dbprestamo'; // Nombre de tu base de datos en Hostinger

$mysqli = new mysqli($servidor, $usuario_bd, $password_bd, $nombre_bd);

// Configurar charset para caracteres especiales (ñ, acentos)
$mysqli->set_charset("utf8");

if (mysqli_connect_errno()) {
    echo 'Error de conexión a la base de datos: ' . mysqli_connect_error();
    exit();
}
?> 