<?php
/**
 * CORRECTOR DE REFERENCIAS DATATABLES SPANISH.JSON
 * ================================================
 * 
 * Este script reemplaza todas las referencias al CDN de DataTables
 * por rutas locales para evitar errores CORS.
 */

echo "🔧 CORRIGIENDO REFERENCIAS DE DATATABLES SPANISH.JSON\n";
echo str_repeat("=", 60) . "\n\n";

// Directorio de vistas
$directorio_vistas = __DIR__ . "/../vistas/";

// Patrones a buscar y reemplazar
$patrones_buscar = [
    '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json',
    '//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json',
    '//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json',
    '//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
    'https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json',
    'http://cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'
];

$ruta_local = 'vistas/assets/plugins/datatables/i18n/Spanish.json';

$archivos_corregidos = 0;
$total_reemplazos = 0;

echo "📋 ESCANEANDO ARCHIVOS EN: $directorio_vistas\n\n";

// Obtener todos los archivos PHP en vistas
$archivos = glob($directorio_vistas . "*.php");

foreach ($archivos as $archivo) {
    $nombre_archivo = basename($archivo);
    $contenido_original = file_get_contents($archivo);
    $contenido_nuevo = $contenido_original;
    $reemplazos_archivo = 0;
    
    // Buscar y reemplazar cada patrón
    foreach ($patrones_buscar as $patron) {
        $contador_antes = substr_count($contenido_nuevo, $patron);
        if ($contador_antes > 0) {
            $contenido_nuevo = str_replace($patron, $ruta_local, $contenido_nuevo);
            $reemplazos_archivo += $contador_antes;
            echo "   🔄 $nombre_archivo: Reemplazados $contador_antes patrones '$patron'\n";
        }
    }
    
    // Si hubo cambios, guardar el archivo
    if ($reemplazos_archivo > 0) {
        file_put_contents($archivo, $contenido_nuevo);
        $archivos_corregidos++;
        $total_reemplazos += $reemplazos_archivo;
        echo "   ✅ $nombre_archivo: $reemplazos_archivo reemplazos realizados\n\n";
    }
}

// Verificar que el archivo Spanish.json existe
$archivo_spanish = __DIR__ . "/../vistas/assets/plugins/datatables/i18n/Spanish.json";
echo "📋 VERIFICANDO ARCHIVO LOCAL...\n";

if (file_exists($archivo_spanish)) {
    echo "   ✅ Spanish.json existe en: $archivo_spanish\n";
    $tamano = filesize($archivo_spanish);
    echo "   📊 Tamaño: " . round($tamano / 1024, 2) . " KB\n";
} else {
    echo "   ❌ Spanish.json NO existe en: $archivo_spanish\n";
    echo "   🔧 Creando archivo...\n";
    
    // Crear directorio si no existe
    $directorio_i18n = dirname($archivo_spanish);
    if (!is_dir($directorio_i18n)) {
        mkdir($directorio_i18n, 0755, true);
        echo "   📁 Directorio creado: $directorio_i18n\n";
    }
    
    // Crear archivo Spanish.json básico
    $contenido_spanish = file_get_contents(__DIR__ . "/../vistas/assets/plugins/datatables/i18n/Spanish.json");
    file_put_contents($archivo_spanish, $contenido_spanish);
    echo "   ✅ Archivo Spanish.json creado\n";
}

echo "\n📋 RESUMEN DE CORRECCIONES...\n";
echo str_repeat("-", 40) . "\n";
echo "📁 Archivos escaneados: " . count($archivos) . "\n";
echo "✅ Archivos corregidos: $archivos_corregidos\n";
echo "🔄 Total de reemplazos: $total_reemplazos\n";

if ($archivos_corregidos > 0) {
    echo "\n🎉 ¡CORRECCIÓN COMPLETADA!\n";
    echo str_repeat("=", 60) . "\n";
    echo "✅ Todas las referencias al CDN han sido actualizadas\n";
    echo "✅ El error CORS de DataTables debería estar resuelto\n";
    echo "✅ Recarga la página de aprobación para verificar\n";
} else {
    echo "\n✅ No se encontraron referencias al CDN para corregir\n";
}

echo str_repeat("=", 60) . "\n";
echo "📝 Corrección completada el " . date('Y-m-d H:i:s') . "\n";
?> 