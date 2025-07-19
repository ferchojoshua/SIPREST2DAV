<?php
/**
 * SCRIPT DE PRUEBA - Dashboard de Cobradores
 * 
 * Este script verifica que todos los componentes del dashboard
 * estén funcionando correctamente.
 */

// Configuración de errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Prueba del Dashboard de Cobradores</h1>";
echo "<hr>";

// 1. Verificar conexión a base de datos
echo "<h2>1. 🔌 Verificación de Conexión</h2>";
try {
    require_once "modelos/conexion.php";
    $pdo = Conexion::conectar();
    echo "✅ Conexión a base de datos: <strong>OK</strong><br>";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
    exit;
}

// 2. Verificar estructura de tablas
echo "<h2>2. 🗃️ Verificación de Tablas</h2>";

$tablas_requeridas = [
    'prestamo_cabecera',
    'prestamo_detalle', 
    'clientes',
    'rutas',
    'usuarios_rutas',
    'clientes_rutas',
    'usuarios',
    'sucursales'
];

foreach ($tablas_requeridas as $tabla) {
    try {
        $stmt = $pdo->query("SHOW TABLES LIKE '$tabla'");
        if ($stmt->rowCount() > 0) {
            echo "✅ Tabla '$tabla': <strong>Existe</strong><br>";
        } else {
            echo "❌ Tabla '$tabla': <strong>No existe</strong><br>";
        }
    } catch (Exception $e) {
        echo "❌ Error verificando tabla '$tabla': " . $e->getMessage() . "<br>";
    }
}

// 3. Verificar datos de ejemplo
echo "<h2>3. 📊 Verificación de Datos</h2>";

try {
    // Préstamos aprobados
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM prestamo_cabecera WHERE pres_aprobacion = 'aprobado'");
    $prestamos = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Préstamos aprobados: <strong>{$prestamos['total']}</strong><br>";
    
    // Usuarios activos
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios WHERE estado = 1");
    $usuarios = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Usuarios activos: <strong>{$usuarios['total']}</strong><br>";
    
    // Rutas activas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM rutas WHERE ruta_estado = 'activa'");
    $rutas = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Rutas activas: <strong>{$rutas['total']}</strong><br>";
    
    // Sucursales activas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM sucursales WHERE estado = 'activa'");
    $sucursales = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Sucursales activas: <strong>{$sucursales['total']}</strong><br>";
    
} catch (Exception $e) {
    echo "❌ Error verificando datos: " . $e->getMessage() . "<br>";
}

// 4. Probar controlador del dashboard
echo "<h2>4. 🎮 Verificación de Controlador</h2>";

try {
    require_once "controladores/dashboard_cobradores_controlador.php";
    echo "✅ Controlador dashboard: <strong>Cargado</strong><br>";
    
    // Probar método de métricas
    $filtros = [
        'sucursal_id' => null,
        'ruta_id' => null,
        'cobrador_id' => null,
        'fecha_inicio' => date('Y-m-01'),
        'fecha_fin' => date('Y-m-d')
    ];
    
    $metricas = DashboardCobradoresControlador::ctrObtenerMetricasGenerales($filtros);
    echo "✅ Métricas generales: <strong>OK</strong><br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;💰 Total cobrado: $" . number_format($metricas['total_cobrado'], 2) . "<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;⚠️ Total mora: $" . number_format($metricas['total_mora'], 2) . "<br>";
    echo "&nbsp;&nbsp;&nbsp;&nbsp;📈 Eficiencia: " . $metricas['eficiencia_cobro'] . "%<br>";
    
} catch (Exception $e) {
    echo "❌ Error en controlador: " . $e->getMessage() . "<br>";
}

// 5. Probar modelo del dashboard
echo "<h2>5. 🗄️ Verificación de Modelo</h2>";

try {
    require_once "modelos/dashboard_cobradores_modelo.php";
    echo "✅ Modelo dashboard: <strong>Cargado</strong><br>";
    
    // Probar consulta de cobros por cobrador
    $datos_cobros = DashboardCobradoresModelo::mdlObtenerCobrosPorCobrador($filtros);
    echo "✅ Cobros por cobrador: <strong>" . count($datos_cobros) . " registros</strong><br>";
    
    // Probar consulta de mora por cobrador
    $datos_mora = DashboardCobradoresModelo::mdlObtenerMoraPorCobrador($filtros);
    echo "✅ Mora por cobrador: <strong>" . count($datos_mora) . " registros</strong><br>";
    
} catch (Exception $e) {
    echo "❌ Error en modelo: " . $e->getMessage() . "<br>";
}

// 6. Verificar archivos del dashboard
echo "<h2>6. 📁 Verificación de Archivos</h2>";

$archivos_requeridos = [
    'vistas/dashboard_cobradores.php' => 'Vista principal',
    'ajax/dashboard_cobradores_ajax.php' => 'API AJAX',
    'controladores/dashboard_cobradores_controlador.php' => 'Controlador',
    'modelos/dashboard_cobradores_modelo.php' => 'Modelo',
    'vistas/assets/dist/js/dashboard-charts.js' => 'Scripts de gráficos',
    'sql/agregar_dashboard_cobradores_menu.sql' => 'Script de instalación'
];

foreach ($archivos_requeridos as $archivo => $descripcion) {
    if (file_exists($archivo)) {
        $tamaño = round(filesize($archivo) / 1024, 2);
        echo "✅ $descripcion: <strong>Existe</strong> ({$tamaño} KB)<br>";
    } else {
        echo "❌ $descripcion: <strong>No encontrado</strong> ($archivo)<br>";
    }
}

// 7. Probar API endpoints
echo "<h2>7. 🔌 Verificación de APIs</h2>";

$endpoints = [
    'metricas_generales' => 'Métricas generales',
    'cobros_por_cobrador' => 'Cobros por cobrador',
    'mora_por_cobrador' => 'Mora por cobrador',
    'comparacion_mensual' => 'Comparación mensual',
    'tabla_rendimiento' => 'Tabla de rendimiento'
];

foreach ($endpoints as $endpoint => $descripcion) {
    try {
        // Simular llamada AJAX
        $_POST['accion'] = $endpoint;
        $_POST['fecha_inicio'] = date('Y-m-01');
        $_POST['fecha_fin'] = date('Y-m-d');
        
        // Capturar output
        ob_start();
        include_once "ajax/dashboard_cobradores_ajax.php";
        $output = ob_get_clean();
        
        $json = json_decode($output, true);
        if ($json && isset($json['success']) && $json['success']) {
            echo "✅ API $descripcion: <strong>OK</strong><br>";
        } else {
            echo "⚠️ API $descripcion: <strong>Respuesta inválida</strong><br>";
        }
        
    } catch (Exception $e) {
        echo "❌ API $descripcion: <strong>Error</strong> - " . $e->getMessage() . "<br>";
    }
}

// 8. Verificar dependencias JavaScript
echo "<h2>8. 📜 Verificación de Dependencias</h2>";

$dependencias_js = [
    'vistas/assets/plugins/jquery/jquery.min.js' => 'jQuery',
    'vistas/assets/plugins/chart.js/Chart.bundle.min.js' => 'Chart.js',
    'vistas/assets/plugins/select2/js/select2.full.min.js' => 'Select2',
    'vistas/assets/plugins/sweetalert2/sweetalert2.min.js' => 'SweetAlert2',
    'vistas/assets/dist/js/combos-mejorados.js' => 'Combos Mejorados'
];

foreach ($dependencias_js as $archivo => $nombre) {
    if (file_exists($archivo)) {
        echo "✅ $nombre: <strong>Disponible</strong><br>";
    } else {
        echo "⚠️ $nombre: <strong>No encontrado</strong> (opcional)<br>";
    }
}

// 9. Resumen final
echo "<h2>9. 📋 Resumen Final</h2>";

$errores = 0;
$advertencias = 0;

// Contar errores y advertencias del output anterior
$output_completo = ob_get_contents();
$errores = substr_count($output_completo, '❌');
$advertencias = substr_count($output_completo, '⚠️');
$exitos = substr_count($output_completo, '✅');

echo "<div style='background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3>📊 Estadísticas de la Prueba</h3>";
echo "✅ <strong>Éxitos:</strong> $exitos<br>";
echo "⚠️ <strong>Advertencias:</strong> $advertencias<br>";
echo "❌ <strong>Errores:</strong> $errores<br>";

if ($errores == 0 && $advertencias <= 3) {
    echo "<br><div style='color: green; font-weight: bold; font-size: 18px;'>";
    echo "🎉 ¡DASHBOARD LISTO PARA USAR! 🎉";
    echo "</div>";
    echo "<p><strong>Siguiente paso:</strong> Ejecutar el script SQL y acceder al dashboard desde el menú.</p>";
} elseif ($errores == 0) {
    echo "<br><div style='color: orange; font-weight: bold; font-size: 18px;'>";
    echo "⚠️ Dashboard funcional con advertencias menores";
    echo "</div>";
} else {
    echo "<br><div style='color: red; font-weight: bold; font-size: 18px;'>";
    echo "❌ Se encontraron errores que deben corregirse";
    echo "</div>";
}
echo "</div>";

// 10. Instrucciones finales
echo "<h2>10. 🚀 Instrucciones de Implementación</h2>";
echo "<ol>";
echo "<li><strong>Ejecutar SQL:</strong> Importar <code>sql/agregar_dashboard_cobradores_menu.sql</code></li>";
echo "<li><strong>Verificar permisos:</strong> Asegurar que el usuario tenga acceso al módulo</li>";
echo "<li><strong>Acceder al dashboard:</strong> Ir al menú 'Dashboard Cobradores'</li>";
echo "<li><strong>Configurar filtros:</strong> Seleccionar sucursal, ruta o período deseado</li>";
echo "<li><strong>Disfrutar:</strong> Analizar el rendimiento de los cobradores 📊</li>";
echo "</ol>";

echo "<hr>";
echo "<p><em>🧪 Prueba completada - " . date('Y-m-d H:i:s') . "</em></p>";
?> 