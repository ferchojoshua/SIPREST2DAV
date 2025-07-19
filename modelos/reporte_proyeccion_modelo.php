<?php

require_once "conexion.php";

class ReporteProyeccionModelo {

    static public function mdlObtenerProyeccionMensual($mes, $anio) {
        $stmt = Conexion::conectar()->prepare("CALL SP_REPORTE_PROYECCION_MENSUAL(:mes, :anio)");
        $stmt->bindParam(":mes", $mes, PDO::PARAM_INT);
        $stmt->bindParam(":anio", $anio, PDO::PARAM_INT);
        $stmt->execute();
        
        // Los stored procedures pueden devolver múltiples result sets.
        // El primer fetchall es para el primer SELECT (clientes a cobrar y monto)
        $clientes_monto = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Avanzar al siguiente result set para el segundo SELECT (préstamos colocados)
        $stmt->nextRowset();
        $prestamos_colocados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt->closeCursor();
        $stmt = null;
        
        // Unir los resultados en un solo array asociativo
        return [
            'proyeccion_cobro' => $clientes_monto[0] ?? [],
            'prestamos_colocados' => $prestamos_colocados[0] ?? []
        ];
    }

}

?> 