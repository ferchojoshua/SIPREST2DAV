<?php
// =====================================================
// DIAGNÓSTICO DE COMBOS EN REPORTES FINANCIEROS
// =====================================================
// Usar este archivo para verificar que los combos funcionen

session_start();

// Verificar si el usuario tiene acceso
if (!isset($_SESSION["usuario"])) {
    die("Error: No hay sesión activa. Haz login primero.");
}

echo "<h2>🔧 DIAGNÓSTICO DE COMBOS - REPORTES FINANCIEROS</h2>";

// Incluir archivos necesarios
require_once "modelos/conexion.php";

try {
    // 1. VERIFICAR TABLA SUCURSALES
    echo "<h3>1. Verificación de Tabla Sucursales:</h3>";
    $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as total FROM sucursales");
    $stmt->execute();
    $total_sucursales = $stmt->fetch()['total'];
    
    echo "✅ Total de sucursales: <strong>$total_sucursales</strong><br>";
    
    if ($total_sucursales > 0) {
        $stmt = Conexion::conectar()->prepare("SELECT id, nombre, estado FROM sucursales LIMIT 5");
        $stmt->execute();
        $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<ul>";
        foreach ($sucursales as $sucursal) {
            $icono = $sucursal['estado'] == 'activa' ? '✅' : '❌';
            echo "<li>$icono ID: {$sucursal['id']} - {$sucursal['nombre']} ({$sucursal['estado']})</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ <strong>No hay sucursales en la base de datos</strong><br>";
    }
    
    // 2. VERIFICAR TABLA RUTAS
    echo "<h3>2. Verificación de Tabla Rutas:</h3>";
    $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as total FROM rutas");
    $stmt->execute();
    $total_rutas = $stmt->fetch()['total'];
    
    echo "✅ Total de rutas: <strong>$total_rutas</strong><br>";
    
    if ($total_rutas > 0) {
        $stmt = Conexion::conectar()->prepare("
            SELECT r.ruta_id, r.ruta_nombre, r.sucursal_id, s.nombre as sucursal_nombre, r.ruta_estado 
            FROM rutas r 
            LEFT JOIN sucursales s ON r.sucursal_id = s.id 
            LIMIT 5
        ");
        $stmt->execute();
        $rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<ul>";
        foreach ($rutas as $ruta) {
            $icono = $ruta['ruta_estado'] == 'activa' ? '✅' : '❌';
            echo "<li>$icono Ruta: {$ruta['ruta_nombre']} - Sucursal: {$ruta['sucursal_nombre']} ({$ruta['ruta_estado']})</li>";
        }
        echo "</ul>";
    } else {
        echo "❌ <strong>No hay rutas en la base de datos</strong><br>";
    }
    
    // 3. VERIFICAR ARCHIVO AJAX
    echo "<h3>3. Verificación del Archivo AJAX:</h3>";
    
    $ajax_file = "ajax/reportes_financieros_ajax.php";
    if (file_exists($ajax_file)) {
        echo "✅ Archivo AJAX existe: <strong>$ajax_file</strong><br>";
        
        // Simular llamada AJAX para sucursales
        $_POST['accion'] = 'obtener_sucursales';
        
        ob_start();
        include $ajax_file;
        $output = ob_get_clean();
        
        if (!empty($output)) {
            $data = json_decode($output, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                echo "✅ AJAX responde correctamente para sucursales<br>";
                echo "📊 Sucursales devueltas: <strong>" . count($data) . "</strong><br>";
            } else {
                echo "❌ AJAX no devuelve JSON válido<br>";
                echo "Respuesta: <pre>$output</pre>";
            }
        } else {
            echo "❌ AJAX no devuelve datos<br>";
        }
        
    } else {
        echo "❌ <strong>Archivo AJAX no existe</strong>: $ajax_file<br>";
    }
    
    // 4. TEST DE CONECTIVIDAD
    echo "<h3>4. Test de Conectividad:</h3>";
    echo "<button onclick='testAjax()'>🧪 Probar AJAX en Vivo</button><br>";
    echo "<div id='resultado-ajax'></div>";
    
    // 5. SOLUCIONES PROPUESTAS
    echo "<h3>5. Soluciones si hay Problemas:</h3>";
    
    if ($total_sucursales == 0) {
        echo "<div style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;'>";
        echo "<strong>⚠️ No hay sucursales:</strong><br>";
        echo "Ejecuta este SQL:<br>";
        echo "<code>INSERT INTO sucursales (nombre, estado) VALUES ('Sucursal Principal', 'activa');</code>";
        echo "</div><br>";
    }
    
    if ($total_rutas == 0) {
        echo "<div style='background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;'>";
        echo "<strong>⚠️ No hay rutas:</strong><br>";
        echo "Ejecuta este SQL:<br>";
        echo "<code>INSERT INTO rutas (ruta_nombre, sucursal_id, ruta_estado) VALUES ('Ruta 1', 1, 'activa');</code>";
        echo "</div><br>";
    }
    
    if (!file_exists($ajax_file)) {
        echo "<div style='background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545;'>";
        echo "<strong>❌ Archivo AJAX faltante:</strong><br>";
        echo "El archivo <code>$ajax_file</code> no existe. Necesitas crearlo o verificar la ruta.";
        echo "</div><br>";
    }

} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545;'>";
    echo "<strong>❌ Error de Base de Datos:</strong><br>";
    echo $e->getMessage();
    echo "</div>";
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testAjax() {
    $('#resultado-ajax').html('🔄 Probando...');
    
    $.ajax({
        url: 'ajax/reportes_financieros_ajax.php',
        method: 'POST',
        data: { accion: 'obtener_sucursales' },
        dataType: 'json',
        success: function(respuesta) {
            if (Array.isArray(respuesta) && respuesta.length > 0) {
                $('#resultado-ajax').html(
                    '<div style="background: #d4edda; padding: 10px; border-left: 4px solid #28a745;">' +
                    '<strong>✅ AJAX funciona correctamente!</strong><br>' +
                    'Sucursales encontradas: <strong>' + respuesta.length + '</strong><br>' +
                    'Primera sucursal: ' + respuesta[0].sucursal_nombre +
                    '</div>'
                );
            } else {
                $('#resultado-ajax').html(
                    '<div style="background: #fff3cd; padding: 10px; border-left: 4px solid #ffc107;">' +
                    '<strong>⚠️ AJAX responde pero sin datos</strong><br>' +
                    'Respuesta: ' + JSON.stringify(respuesta) +
                    '</div>'
                );
            }
        },
        error: function(xhr, status, error) {
            $('#resultado-ajax').html(
                '<div style="background: #f8d7da; padding: 10px; border-left: 4px solid #dc3545;">' +
                '<strong>❌ Error en AJAX:</strong><br>' +
                'Estado: ' + status + '<br>' +
                'Error: ' + error + '<br>' +
                'Respuesta: ' + xhr.responseText +
                '</div>'
            );
        }
    });
}
</script>

<hr>
<p><strong>💡 Cómo usar este diagnóstico:</strong></p>
<ol>
    <li>Ejecuta este archivo: <code>http://localhost/siprest/diagnostico_combos_reportes.php</code></li>
    <li>Revisa cada sección (debe mostrar ✅ verde)</li>
    <li>Si hay ❌ rojos, sigue las soluciones propuestas</li>
    <li>Haz clic en "🧪 Probar AJAX en Vivo" para verificar conectividad</li>
    <li>Una vez todo esté ✅, elimina este archivo</li>
</ol> 