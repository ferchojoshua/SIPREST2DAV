<?php

require_once "../modelos/reporte_cierre_modelo.php";

class ReporteCierreControlador {

    static public function ctrObtenerCierreMensual($mes, $anio) {
        $respuesta = ReporteCierreModelo::mdlObtenerCierreMensual($mes, $anio);
        return $respuesta;
    }

}

?> 