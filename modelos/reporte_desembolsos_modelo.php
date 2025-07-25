<?php

require_once "conexion.php";

class ReporteDesembolsosModelo {

    static public function mdlObtenerDesembolsos($fechaInicio = '', $fechaFin = '') {
        $sql = "SELECT * FROM v_desembolsos_reporte";
        $params = [];

        if (!empty($fechaInicio) && !empty($fechaFin)) {
            $sql .= " WHERE fecha_desembolso BETWEEN :fecha_inicio AND :fecha_fin";
            $params[":fecha_inicio"] = $fechaInicio;
            $params[":fecha_fin"] = $fechaFin;
        } else if (!empty($fechaInicio)) {
            $sql .= " WHERE fecha_desembolso >= :fecha_inicio";
            $params[":fecha_inicio"] = $fechaInicio;
        } else if (!empty($fechaFin)) {
            $sql .= " WHERE fecha_desembolso <= :fecha_fin";
            $params[":fecha_fin"] = $fechaFin;
        }

        $stmt = Conexion::conectar()->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>