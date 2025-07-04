<?php 
//llamamos al controlador
require_once "controladores/plantilla_controlador.php";

require_once "controladores/usuario_controlador.php";
require_once "modelos/usuario_modelo.php";

// Procesar login si se envía el formulario
$usuario = new UsuarioControlador();
$usuario->login();

//instanciamos
$plantilla = new PlantillaControlador();
$plantilla->CargarPlantilla();

