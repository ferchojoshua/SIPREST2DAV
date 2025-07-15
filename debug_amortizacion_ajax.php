<?php

// Activar reporte de errores para ver cualquier problema
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simular un POST request como si viniera del frontend
$_POST['accion'] = 'calcular_amortizacion';
$_POST['monto'] = 10000; // Ejemplo de monto
$_POST['interes'] = 24; // Ejemplo de interés anual
$_POST['cuotas'] = 12; // Ejemplo de 12 cuotas
$_POST['tipo_calculo'] = 'FRANCES'; // O 'ALEMAN', 'FLAT', etc.
$_POST['fecha_inicio'] = '2024-01-01'; // Fecha de inicio
$_POST['fpago'] = '4'; // Forma de pago: 1=Diario, 2=Semanal, 3=Quincenal, 4=Mensual

echo "=== INICIANDO DEBUG DE AJAX/PRESTAMO_AJAX.PHP ===
";
echo "Datos POST simulados:
";
foreach ($_POST as $key => $value) {
    echo "  $key: $value
";
}
echo "
";

// Verificar que el archivo a incluir existe
$ajaxFilePath = 'ajax/prestamo_ajax.php';
if (!file_exists($ajaxFilePath)) {
    echo "ERROR: El archivo $ajaxFilePath no se encontró.
";
    exit;
} else {
    echo "INFO: El archivo $ajaxFilePath fue encontrado.
";
}

// Capturar la salida del script AJAX
ob_start();
include $ajaxFilePath; // Incluir el script AJAX
$output = ob_get_clean();

// Imprimir la salida cruda para inspección
echo "=== SALIDA CRUDA DEL SCRIPT AJAX ===
";
echo $output . "
";

// Intentar decodificar el JSON
$decoded_output = json_decode($output, true);

// Verificar si la decodificación fue exitosa
if (json_last_error() === JSON_ERROR_NONE) {
    echo "=== JSON DECODIFICADO EXITOSAMENTE ===
";
    print_r($decoded_output);
} else {
    echo "=== ERROR AL DECODIFICAR JSON ===
";
    echo "Error de JSON: " . json_last_error_msg() . "
";
    echo "Salida que causó el error:
";
    echo $output . "
"; // Mostrar la salida completa que falló al decodificar
}

echo "=== FIN DEL DEBUG ===
";

?> 