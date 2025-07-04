<?php 
//llamamos al controlador
require_once "controladores/plantilla_controlador.php";

require_once "controladores/usuario_controlador.php";
require_once "modelos/usuario_modelo.php";


//instanciamos
$plantilla = new PlantillaControlador();
$plantilla  -> CargarPlantilla();

