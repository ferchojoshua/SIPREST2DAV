<?php 
$mysqli = new mysqli('localhost','root','','dbprestamo');
if (mysqli_connect_errno()) {
	echo 'Conexion Fallida: ', mysqli_connect_error();
	exit();
	// code...
}





 ?>