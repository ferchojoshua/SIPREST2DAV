<?php
// Diagnóstico simple para Reportes Financieros
session_start();

echo "<h1>🔍 DIAGNÓSTICO REPORTES FINANCIEROS</h1>";

// 1. Verificar sesión
echo "<h2>1. SESIÓN DE USUARIO:</h2>";
if (isset($_SESSION["usuario"])) {
    echo "✅ Sesión activa: " . $_SESSION["usuario"]->usuario_usuario . "<br>";
    echo "✅ ID Usuario: " . $_SESSION["usuario"]->id_usuario . "<br>";
} else {
    echo "❌ No hay sesión activa<br>";
}

// 2. Verificar archivos
echo "<h2>2. ARCHIVOS NECESARIOS:</h2>";
$archivos = [
    'vistas/reportes_financieros.php',
    'ajax/reportes_financieros_ajax.php', 
    'controladores/reportes_financieros_controlador.php',
    'modelos/reportes_financieros_modelo.php'
];

foreach ($archivos as $archivo) {
    if (file_exists($archivo)) {
        $size = round(filesize($archivo) / 1024, 2);
        echo "✅ $archivo (${size} KB)<br>";
    } else {
        echo "❌ $archivo - NO EXISTE<br>";
    }
}

// 3. Verificar base de datos
echo "<h2>3. BASE DE DATOS:</h2>";
try {
    require_once "modelos/conexion.php";
    $conexion = Conexion::conectar();
    
    // Verificar módulo
    $stmt = $conexion->prepare("SELECT id, modulo, vista, padre_id FROM modulos WHERE vista = 'reportes_financieros.php'");
    $stmt->execute();
    $modulo = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($modulo) {
        echo "✅ Módulo en DB: ID " . $modulo['id'] . " - " . $modulo['modulo'] . "<br>";
        
        // Verificar permisos
        $stmt2 = $conexion->prepare("SELECT COUNT(*) as total FROM perfil_modulo WHERE id_modulo = :id_modulo");
        $stmt2->bindParam(":id_modulo", $modulo['id']);
        $stmt2->execute();
        $permisos = $stmt2->fetch(PDO::FETCH_ASSOC);
        
        echo "✅ Permisos asignados: " . $permisos['total'] . "<br>";
    } else {
        echo "❌ Módulo NO existe en base de datos<br>";
    }
    
    // Verificar tablas necesarias
    $tablas = ['clientes', 'prestamo_cabecera', 'prestamo_detalle', 'sucursales'];
    foreach ($tablas as $tabla) {
        $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM $tabla");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "✅ Tabla $tabla: " . $count['total'] . " registros<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error de conexión DB: " . $e->getMessage() . "<br>";
}

// 4. Test AJAX
echo "<h2>4. TEST AJAX:</h2>";
echo "<button onclick='testAjax()' style='padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px;'>Probar Conexión AJAX</button>";
echo "<div id='resultado_ajax' style='margin-top: 10px; padding: 10px; background: #f8f9fa; border-radius: 5px;'></div>";

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function testAjax() {
    $('#resultado_ajax').html('🔄 Probando conexión...');
    
    $.ajax({
        url: 'ajax/reportes_financieros_ajax.php',
        method: 'POST',
        data: { accion: 'obtener_sucursales' },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta && Array.isArray(respuesta)) {
                $('#resultado_ajax').html('✅ AJAX funciona correctamente - ' + respuesta.length + ' sucursales encontradas');
            } else {
                $('#resultado_ajax').html('⚠️ AJAX responde pero formato inesperado: ' + JSON.stringify(respuesta));
            }
        },
        error: function(xhr, status, error) {
            $('#resultado_ajax').html('❌ Error AJAX: ' + error + ' - Status: ' + status);
        }
    });
}
</script>

<h2>5. SOLUCIÓN RÁPIDA:</h2>
<p>Si el diagnóstico muestra errores, ejecuta estos pasos:</p>
<ol>
    <li><strong>Eliminar duplicados:</strong> <code>sql/insertar_unico_reporte_financiero.sql</code></li>
    <li><strong>Verificar permisos:</strong> Asegúrate de tener acceso al módulo</li>
    <li><strong>Limpiar caché:</strong> Ctrl+F5 en el navegador</li>
    <li><strong>Verificar consola:</strong> F12 → Console para errores JavaScript</li>
</ol> 