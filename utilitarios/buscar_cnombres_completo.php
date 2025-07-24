<?php
/**
 * BÚSQUEDA COMPLETA DE REFERENCIAS A 'c.nombres'
 * ==============================================
 * 
 * Este script busca TODAS las referencias a 'c.nombres' en:
 * 1. Stored Procedures
 * 2. Funciones
 * 3. Vistas
 * 4. Código PHP
 */

require_once "../modelos/conexion.php";

echo "🔍 BÚSQUEDA COMPLETA DE REFERENCIAS A 'c.nombres'\n";
echo str_repeat("=", 60) . "\n\n";

try {
    $pdo = Conexion::conectar();
    
    echo "📋 1. BUSCANDO EN STORED PROCEDURES...\n";
    
    // Obtener todos los stored procedures
    $stmt = $pdo->prepare("SHOW PROCEDURE STATUS WHERE Db = DATABASE()");
    $stmt->execute();
    $procedures = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $procedures_con_error = [];
    
    foreach ($procedures as $procedure) {
        $nombre_sp = $procedure['Name'];
        echo "   🔍 Verificando SP: $nombre_sp... ";
        
        try {
            // Obtener la definición del procedimiento
            $stmt = $pdo->prepare("SHOW CREATE PROCEDURE `$nombre_sp`");
            $stmt->execute();
            $definicion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($definicion && strpos($definicion['Create Procedure'], 'c.nombres') !== false) {
                $procedures_con_error[] = $nombre_sp;
                echo "❌ ENCONTRADO!\n";
                echo "      📄 DEFINICIÓN PROBLEMÁTICA:\n";
                echo "      " . str_repeat("-", 50) . "\n";
                $lineas = explode("\n", $definicion['Create Procedure']);
                foreach ($lineas as $num => $linea) {
                    if (strpos($linea, 'c.nombres') !== false) {
                        echo "      LÍNEA " . ($num + 1) . ": " . trim($linea) . " ← ❌\n";
                    }
                }
                echo "      " . str_repeat("-", 50) . "\n\n";
            } else {
                echo "✅\n";
            }
        } catch (Exception $e) {
            echo "⚠️ Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📋 2. BUSCANDO EN FUNCIONES...\n";
    
    // Obtener todas las funciones
    $stmt = $pdo->prepare("SHOW FUNCTION STATUS WHERE Db = DATABASE()");
    $stmt->execute();
    $functions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $functions_con_error = [];
    
    foreach ($functions as $function) {
        $nombre_fn = $function['Name'];
        echo "   🔍 Verificando Function: $nombre_fn... ";
        
        try {
            $stmt = $pdo->prepare("SHOW CREATE FUNCTION `$nombre_fn`");
            $stmt->execute();
            $definicion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($definicion && strpos($definicion['Create Function'], 'c.nombres') !== false) {
                $functions_con_error[] = $nombre_fn;
                echo "❌ ENCONTRADO!\n";
            } else {
                echo "✅\n";
            }
        } catch (Exception $e) {
            echo "⚠️ Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📋 3. BUSCANDO EN VISTAS...\n";
    
    // Obtener todas las vistas
    $stmt = $pdo->prepare("SHOW FULL TABLES WHERE Table_type = 'VIEW'");
    $stmt->execute();
    $views = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $views_con_error = [];
    
    foreach ($views as $view) {
        $nombre_view = $view['Tables_in_' . $pdo->query('SELECT DATABASE()')->fetchColumn()];
        echo "   🔍 Verificando Vista: $nombre_view... ";
        
        try {
            $stmt = $pdo->prepare("SHOW CREATE VIEW `$nombre_view`");
            $stmt->execute();
            $definicion = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($definicion && strpos($definicion['Create View'], 'c.nombres') !== false) {
                $views_con_error[] = $nombre_view;
                echo "❌ ENCONTRADO!\n";
            } else {
                echo "✅\n";
            }
        } catch (Exception $e) {
            echo "⚠️ Error: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n📋 4. BUSCANDO EN ARCHIVOS PHP...\n";
    
    // Buscar en archivos PHP
    $directorios = [
        __DIR__ . "/../ajax/",
        __DIR__ . "/../modelos/", 
        __DIR__ . "/../controladores/",
        __DIR__ . "/../vistas/"
    ];
    
    $archivos_con_error = [];
    
    foreach ($directorios as $directorio) {
        if (is_dir($directorio)) {
            $archivos = glob($directorio . "*.php");
            foreach ($archivos as $archivo) {
                $contenido = file_get_contents($archivo);
                if (strpos($contenido, 'c.nombres') !== false) {
                    $archivos_con_error[] = $archivo;
                    echo "   ❌ ENCONTRADO en: " . basename($archivo) . "\n";
                }
            }
        }
    }
    
    echo "\n📋 5. RESUMEN DE RESULTADOS...\n";
    echo str_repeat("-", 40) . "\n";
    
    if (!empty($procedures_con_error)) {
        echo "❌ STORED PROCEDURES CON 'c.nombres':\n";
        foreach ($procedures_con_error as $sp) {
            echo "   - $sp\n";
        }
        echo "\n";
    }
    
    if (!empty($functions_con_error)) {
        echo "❌ FUNCIONES CON 'c.nombres':\n";
        foreach ($functions_con_error as $fn) {
            echo "   - $fn\n";
        }
        echo "\n";
    }
    
    if (!empty($views_con_error)) {
        echo "❌ VISTAS CON 'c.nombres':\n";
        foreach ($views_con_error as $vw) {
            echo "   - $vw\n";
        }
        echo "\n";
    }
    
    if (!empty($archivos_con_error)) {
        echo "❌ ARCHIVOS PHP CON 'c.nombres':\n";
        foreach ($archivos_con_error as $archivo) {
            echo "   - " . basename($archivo) . "\n";
        }
        echo "\n";
    }
    
    if (empty($procedures_con_error) && empty($functions_con_error) && 
        empty($views_con_error) && empty($archivos_con_error)) {
        echo "✅ NO se encontraron referencias a 'c.nombres'\n";
        echo "❓ El error podría estar en:\n";
        echo "   - Triggers\n";
        echo "   - Código JavaScript\n";
        echo "   - Queries dinámicos\n";
    } else {
        echo "🎯 TOTAL DE ELEMENTOS CON ERROR:\n";
        echo "   📊 Stored Procedures: " . count($procedures_con_error) . "\n";
        echo "   📊 Funciones: " . count($functions_con_error) . "\n";
        echo "   📊 Vistas: " . count($views_con_error) . "\n";
        echo "   📊 Archivos PHP: " . count($archivos_con_error) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "📝 Búsqueda completada el " . date('Y-m-d H:i:s') . "\n";
?> 