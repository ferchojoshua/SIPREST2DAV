<?php

require_once "conexion.php";

class ReporteCierreModelo {

    static public function mdlObtenerCierreMensual($mes, $anio) {
        $stmt = Conexion::conectar()->prepare("CALL SP_REPORTE_CIERRE_MENSUAL(:mes, :anio)");
        $stmt->bindParam(":mes", $mes, PDO::PARAM_INT);
        $stmt->bindParam(":anio", $anio, PDO::PARAM_INT);
        $stmt->execute();
        
        // Primer result set: monto cobrado
        $monto_cobrado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Segundo result set: mora
        $stmt->nextRowset();
        $mora_fin_mes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt->closeCursor();
        $stmt = null;
        
        return [
            'monto_cobrado' => $monto_cobrado[0] ?? [],
            'mora_fin_mes' => $mora_fin_mes[0] ?? []
        ];
    }

}

?> 