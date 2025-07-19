<?php
require_once "../utilitarios/calculadora_prestamos.php";

// Configurar headers para API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Función para enviar respuesta JSON
function enviarRespuesta($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Validar método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    enviarRespuesta(['error' => 'Método no permitido. Use POST'], 405);
}

try {
    // Validar que el contenido sea JSON si se envía como tal
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
    if (strpos($contentType, 'application/json') !== false) {
        $input = json_decode(file_get_contents('php://input'), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON inválido");
        }
    } else {
        $input = $_POST;
    }
    
    // Validar y sanitizar datos de entrada
    $monto = filter_var($input['monto'] ?? null, FILTER_VALIDATE_FLOAT);
    $tasa = filter_var($input['tasa'] ?? null, FILTER_VALIDATE_FLOAT);
    $plazo = filter_var($input['plazo'] ?? null, FILTER_VALIDATE_INT);
    $sistema = filter_var($input['sistema'] ?? null, FILTER_SANITIZE_STRING);
    $fechaInicio = filter_var($input['fecha_inicio'] ?? null, FILTER_SANITIZE_STRING);
    
    // Validar campos requeridos
    if ($monto === false || $tasa === false || $plazo === false || empty($sistema) || empty($fechaInicio)) {
        throw new Exception("Todos los campos son requeridos y deben tener formato válido");
    }
    
    // Validar rangos de valores
    if ($monto <= 0) {
        throw new Exception("El monto debe ser mayor a cero");
    }
    
    if ($tasa <= 0 || $tasa > 100) {
        throw new Exception("La tasa debe estar entre 0.01% y 100%");
    }
    
    if ($plazo <= 0 || $plazo > 600) {
        throw new Exception("El plazo debe estar entre 1 y 600 períodos");
    }
    
    // Validar sistema de amortización
    $sistemasValidos = ['frances', 'aleman', 'americano'];
    if (!in_array(strtolower($sistema), $sistemasValidos)) {
        throw new Exception("Sistema de amortización no válido. Use: " . implode(', ', $sistemasValidos));
    }
    
    // Validar y convertir fecha
    $fecha = DateTime::createFromFormat('Y-m-d', $fechaInicio);
    if (!$fecha) {
        $fecha = DateTime::createFromFormat('d/m/Y', $fechaInicio);
    }
    
    if (!$fecha) {
        throw new Exception("Formato de fecha inválido. Use YYYY-MM-DD o DD/MM/YYYY");
    }
    
    // Validar que la fecha no sea anterior a hoy
    $hoy = new DateTime();
    if ($fecha < $hoy) {
        throw new Exception("La fecha de inicio no puede ser anterior a hoy");
    }
    
    $fechaFormateada = $fecha->format('d/m/Y');
    
    // Validar que la clase existe
    if (!class_exists('CalculadoraPrestamos')) {
        throw new Exception("Error interno: Calculadora no disponible");
    }
    
    // Calcular amortización
    $resultado = CalculadoraPrestamos::calcularAmortizacion(
        $monto,
        $tasa,
        $plazo,
        $sistema,
        $fechaFormateada
    );
    
    // Validar que el resultado sea válido
    if (!is_array($resultado)) {
        throw new Exception("Error en el cálculo de amortización");
    }
    
    // Preparar respuesta exitosa
    $respuesta = [
        'success' => true,
        'data' => [
            'parametros' => [
                'monto' => $monto,
                'tasa' => $tasa,
                'plazo' => $plazo,
                'sistema' => $sistema,
                'fecha_inicio' => $fechaFormateada
            ],
            'resultado' => $resultado
        ],
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    enviarRespuesta($respuesta);
    
} catch (Exception $e) {
    // Log del error (opcional)
    error_log("Error en cálculo de préstamo: " . $e->getMessage());
    
    enviarRespuesta([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], 400);
}
?>