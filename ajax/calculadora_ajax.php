<?php
require_once "../utilitarios/calculadora_prestamos.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validar datos de entrada
        $monto = filter_input(INPUT_POST, 'monto', FILTER_VALIDATE_FLOAT);
        $tasa = filter_input(INPUT_POST, 'tasa', FILTER_VALIDATE_FLOAT);
        $plazo = filter_input(INPUT_POST, 'plazo', FILTER_VALIDATE_INT);
        $sistema = filter_input(INPUT_POST, 'sistema', FILTER_SANITIZE_STRING);
        $fechaInicio = filter_input(INPUT_POST, 'fecha_inicio', FILTER_SANITIZE_STRING);
        
        if (!$monto || !$tasa || !$plazo || !$sistema || !$fechaInicio) {
            throw new Exception("Todos los campos son requeridos");
        }
        
        if ($monto <= 0 || $tasa <= 0 || $plazo <= 0) {
            throw new Exception("Los valores deben ser mayores a cero");
        }
        
        // Convertir fecha a formato dd/mm/yyyy
        $fecha = new DateTime($fechaInicio);
        $fechaFormateada = $fecha->format('d/m/Y');
        
        // Calcular amortización
        $resultado = CalculadoraPrestamos::calcularAmortizacion(
            $monto,
            $tasa,
            $plazo,
            $sistema,
            $fechaFormateada
        );
        
        // Agregar datos adicionales al resultado
        $resultado['monto'] = $monto;
        $resultado['tasa'] = $tasa;
        $resultado['plazo'] = $plazo;
        
        header('Content-Type: application/json');
        echo json_encode($resultado);
        
    } catch (Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo "Método no permitido";
} 