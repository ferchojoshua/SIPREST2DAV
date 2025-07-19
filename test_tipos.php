<?php
// test_tipos.php
header('Content-Type: text/plain');
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Iniciando prueba para obtener tipos de cálculo...\n\n";

try {
    echo "1. Incluyendo archivos...\n";
    require_once __DIR__ . '/modelos/conexion.php';
    require_once __DIR__ . '/controladores/prestamo_controlador.php';
    require_once __DIR__ . '/modelos/prestamo_modelo.php';
    echo "   - Archivos incluidos correctamente.\n\n";

    echo "2. Verificando clases...\n";
    if (class_exists('PrestamoControlador')) {
        echo "   - Clase PrestamoControlador: OK\n";
    } else {
        echo "   - Clase PrestamoControlador: NO ENCONTRADA\n";
    }
    if (class_exists('PrestamoModelo')) {
        echo "   - Clase PrestamoModelo: OK\n";
    } else {
        echo "   - Clase PrestamoModelo: NO ENCONTRADA\n";
    }
    echo "\n";

    echo "3. Ejecutando controlador...\n";
    $tiposCalculo = PrestamoControlador::ctrObtenerTiposCalculo();
    echo "   - Controlador ejecutado.\n\n";

    echo "4. Revisando resultado...\n";
    if (empty($tiposCalculo)) {
        echo "   - Resultado: VACÍO\n";
    } else {
        echo "   - Resultado: OK\n";
        echo "   - Datos: \n";
        print_r($tiposCalculo);
    }
    echo "\n";

    echo "5. Intentando codificar a JSON...\n";
    $json = json_encode($tiposCalculo);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "   - JSON codificado correctamente.\n";
        echo "   - Salida JSON: " . $json . "\n";
    } else {
        echo "   - ERROR al codificar JSON: " . json_last_error_msg() . "\n";
    }

} catch (Exception $e) {
    echo "\n!!!!!!!!!! SE PRODUJO UNA EXCEPCIÓN !!!!!!!!!!\n";
    echo "Error: " . $e->getMessage() . "\n";
    echo "Archivo: " . $e->getFile() . "\n";
    echo "Línea: " . $e->getLine() . "\n";
    echo "Traza: \n" . $e->getTraceAsString() . "\n";
}

?> 