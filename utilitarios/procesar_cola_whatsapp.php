<?php
/**
 * PROCESADOR DE COLA WHATSAPP PARA CRON
 * CrediCrece - Procesamiento automático cada 5 minutos
 * 
 * USO EN CRON:
 * Cada 5 minutos: php /ruta/a/CrediCrece/utilitarios/procesar_cola_whatsapp.php
 */

// Configuración inicial
error_reporting(E_ALL);
ini_set('display_errors', 0); // No mostrar errores en CRON
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../ajax/cron_whatsapp.log');

// Log de inicio
$log_mensaje = "\n" . date('Y-m-d H:i:s') . " - INICIANDO PROCESAMIENTO COLA WHATSAPP\n";
file_put_contents(__DIR__ . '/../ajax/cron_whatsapp.log', $log_mensaje, FILE_APPEND);

try {
    // Verificar que estemos en el directorio correcto
    $ruta_base = dirname(__DIR__);
    if (!file_exists($ruta_base . '/ajax/whatsapp_ajax.php')) {
        throw new Exception("No se encuentra el archivo whatsapp_ajax.php");
    }

    // Incluir configuración
    if (file_exists($ruta_base . '/config/whatsapp_config.php')) {
        require_once $ruta_base . '/config/whatsapp_config.php';
    }

    // Verificar que WhatsApp esté configurado
    if (!defined('MAYTAPI_TOKEN') || empty(MAYTAPI_TOKEN) || MAYTAPI_TOKEN === 'TU_TOKEN_AQUI') {
        $log_mensaje = date('Y-m-d H:i:s') . " - WhatsApp no configurado, saltando procesamiento\n";
        file_put_contents(__DIR__ . '/../ajax/cron_whatsapp.log', $log_mensaje, FILE_APPEND);
        exit(0);
    }

    // Simular petición AJAX para procesar cola
    $_POST['accion'] = 'procesar_cola';
    $_SERVER['REQUEST_METHOD'] = 'POST';
    
    // Capturar salida del procesamiento
    ob_start();
    include $ruta_base . '/ajax/whatsapp_ajax.php';
    $resultado = ob_get_clean();

    // Log del resultado
    $log_mensaje = date('Y-m-d H:i:s') . " - RESULTADO: " . substr($resultado, 0, 200) . "\n";
    file_put_contents(__DIR__ . '/../ajax/cron_whatsapp.log', $log_mensaje, FILE_APPEND);

    // Procesar recordatorios automáticos si es hora apropiada
    $hora_actual = (int)date('H');
    $minuto_actual = (int)date('i');
    
    // Enviar recordatorios solo de 8 AM a 6 PM, cada 30 minutos
    if ($hora_actual >= 8 && $hora_actual <= 18 && $minuto_actual % 30 == 0) {
        $_POST['accion'] = 'enviar_recordatorios_masivos';
        
        ob_start();
        include $ruta_base . '/ajax/whatsapp_ajax.php';
        $resultado_recordatorios = ob_get_clean();
        
        $log_mensaje = date('Y-m-d H:i:s') . " - RECORDATORIOS: " . substr($resultado_recordatorios, 0, 200) . "\n";
        file_put_contents(__DIR__ . '/../ajax/cron_whatsapp.log', $log_mensaje, FILE_APPEND);
    }

    // Limpiar logs antiguos (más de 7 días)
    $archivo_log = __DIR__ . '/../ajax/cron_whatsapp.log';
    if (file_exists($archivo_log) && filemtime($archivo_log) < (time() - 7 * 24 * 3600)) {
        $lineas = file($archivo_log);
        if (count($lineas) > 1000) {
            // Mantener solo las últimas 500 líneas
            $nuevas_lineas = array_slice($lineas, -500);
            file_put_contents($archivo_log, implode('', $nuevas_lineas));
        }
    }

    $log_mensaje = date('Y-m-d H:i:s') . " - PROCESAMIENTO COMPLETADO EXITOSAMENTE\n";
    file_put_contents(__DIR__ . '/../ajax/cron_whatsapp.log', $log_mensaje, FILE_APPEND);

} catch (Exception $e) {
    // Log de error
    $log_mensaje = date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage() . "\n";
    file_put_contents(__DIR__ . '/../ajax/cron_whatsapp.log', $log_mensaje, FILE_APPEND);
    
    // Salir con código de error
    exit(1);
}

// Salir exitosamente
exit(0);
?> 