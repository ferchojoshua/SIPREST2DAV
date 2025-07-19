<?php
// Test para verificar la funcionalidad de abono
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>🧪 Test de Funcionalidad de Abono</h2>";

// Simular datos POST
$_POST = [
    'accion' => '6',
    'nro_prestamo' => 'P001',
    'pdetalle_nro_cuota' => '1',
    'monto_a_abonar' => '100.00',
    'tipo_abono' => 'normal'
];

echo "<h3>📋 Datos de prueba:</h3>";
echo "<pre>" . print_r($_POST, true) . "</pre>";

// Verificar si los archivos existen
echo "<h3>📁 Verificación de archivos:</h3>";
$archivos = [
    'controladores/admin_prestamos_controlador.php',
    'modelos/admin_prestamos_modelo.php',
    'modelos/conexion.php'
];

foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        echo "✅ {$archivo} - Existe<br>";
    } else {
        echo "❌ {$archivo} - No existe<br>";
    }
}

// Test de conexión a base de datos
echo "<h3>🔌 Test de conexión a base de datos:</h3>";
try {
    require_once 'modelos/conexion.php';
    $conexion = Conexion::conectar();
    echo "✅ Conexión exitosa<br>";
    
    // Verificar si existe la tabla prestamo_detalle
    $stmt = $conexion->prepare("SHOW TABLES LIKE 'prestamo_detalle'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'prestamo_detalle' existe<br>";
    } else {
        echo "❌ Tabla 'prestamo_detalle' no existe<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}

// Test del controlador
echo "<h3>🎛️ Test del controlador:</h3>";
try {
    require_once 'controladores/admin_prestamos_controlador.php';
    echo "✅ Controlador cargado correctamente<br>";
    
    // Verificar si el método existe
    if (method_exists('AdminPrestamosControlador', 'ctrRegistrarAbono')) {
        echo "✅ Método 'ctrRegistrarAbono' existe<br>";
    } else {
        echo "❌ Método 'ctrRegistrarAbono' no existe<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error al cargar controlador: " . $e->getMessage() . "<br>";
}

// Test del modelo
echo "<h3>📊 Test del modelo:</h3>";
try {
    require_once 'modelos/admin_prestamos_modelo.php';
    echo "✅ Modelo cargado correctamente<br>";
    
    // Verificar si el método existe
    if (method_exists('AdminPrestamosModelo', 'mdlRegistrarAbono')) {
        echo "✅ Método 'mdlRegistrarAbono' existe<br>";
    } else {
        echo "❌ Método 'mdlRegistrarAbono' no existe<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error al cargar modelo: " . $e->getMessage() . "<br>";
}

// Test de la clase AJAX
echo "<h3>🔄 Test de la clase AJAX:</h3>";
try {
    require_once 'ajax/admin_prestamos_ajax.php';
    echo "✅ Clase AJAX cargada correctamente<br>";
    
    // Verificar si la clase existe
    if (class_exists('AjaxAdminPrestamos')) {
        echo "✅ Clase 'AjaxAdminPrestamos' existe<br>";
        
        $ajax = new AjaxAdminPrestamos();
        if (method_exists($ajax, 'ajaxRegistrarAbono')) {
            echo "✅ Método 'ajaxRegistrarAbono' existe<br>";
        } else {
            echo "❌ Método 'ajaxRegistrarAbono' no existe<br>";
        }
    } else {
        echo "❌ Clase 'AjaxAdminPrestamos' no existe<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error al cargar clase AJAX: " . $e->getMessage() . "<br>";
}

echo "<h3>📝 Instrucciones:</h3>";
echo "<ol>";
echo "<li>Ejecuta este archivo desde tu navegador</li>";
echo "<li>Revisa los resultados y anota cualquier error</li>";
echo "<li>Si todo está en verde, el problema puede estar en los datos específicos</li>";
echo "<li>Si hay errores rojos, esos son los problemas a resolver</li>";
echo "</ol>";

echo "<h3>🔍 Próximos pasos:</h3>";
echo "<p>Si este test pasa, intenta hacer un abono real y revisa los logs de error de Apache/PHP para más detalles.</p>";
?> 