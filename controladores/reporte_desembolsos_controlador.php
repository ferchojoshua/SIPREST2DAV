<?php

require_once "../modelos/reporte_desembolsos_modelo.php";

class ReporteDesembolsosControlador {

    static public function ctrObtenerDesembolsos() {
        $fechaInicio = isset($_POST["fecha_inicio"]) ? $_POST["fecha_inicio"] : '';
        $fechaFin = isset($_POST["fecha_fin"]) ? $_POST["fecha_fin"] : '';

        $respuesta = ReporteDesembolsosModelo::mdlObtenerDesembolsos($fechaInicio, $fechaFin);
        return $respuesta;
    }

}

?> 