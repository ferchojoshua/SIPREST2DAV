<?php

require_once "../modelos/reporte_proyeccion_modelo.php";

class ReporteProyeccionControlador {

    static public function ctrObtenerProyeccionMensual($mes, $anio) {
        $respuesta = ReporteProyeccionModelo::mdlObtenerProyeccionMensual($mes, $anio);
        return $respuesta;
    }

}

?> 