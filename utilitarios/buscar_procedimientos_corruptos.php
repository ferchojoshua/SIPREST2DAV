<?php
/**
 * Script para encontrar TODOS los procedimientos almacenados corruptos que contengan 'c.cliente_nombres'
 */

require_once "modelos/conexion.php";

echo "<h2>🔍 BUSCANDO PROCEDIMIENTOS CORRUPTOS</h2>";
echo "<p>Buscando todos los procedimientos que contengan 'c.cliente_nombres'...</p>";
echo "<hr>";

try {
    $pdo = Conexion::conectar();
    
    // Buscar todos los procedimientos almacenados en la base de datos
    $sql = "SELECT ROUTINE_NAME, ROUTINE_DEFINITION 
            FROM INFORMATION_SCHEMA.ROUTINES 
            WHERE ROUTINE_SCHEMA = 'credicrece' 
            AND ROUTINE_TYPE = 'PROCEDURE'
            ORDER BY ROUTINE_NAME";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $procedimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>📋 ANÁLISIS DE PROCEDIMIENTOS</h3>";
    echo "<p><strong>Total de procedimientos encontrados:</strong> " . count($procedimientos) . "</p>";
    
    $corruptos = [];
    $limpios = [];
    
    foreach ($procedimientos as $proc) {
        $nombre = $proc['ROUTINE_NAME'];
        $definicion = $proc['ROUTINE_DEFINITION'];
        
        // Buscar 'c.cliente_nombres' en la definición
        if (strpos($definicion, 'c.cliente_nombres') !== false) {
            $corruptos[] = $nombre;
            echo "<p>❌ <strong>CORRUPTO:</strong> <code>$nombre</code> - Contiene 'c.cliente_nombres'</p>";
        } else {
            $limpios[] = $nombre;
        }
    }
    
    echo "<hr>";
    echo "<h3>📊 RESUMEN DEL ANÁLISIS</h3>";
    echo "<p>✅ <strong>Procedimientos limpios:</strong> " . count($limpios) . "</p>";
    echo "<p>❌ <strong>Procedimientos corruptos:</strong> " . count($corruptos) . "</p>";
    
    if (count($corruptos) > 0) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>⚠️ PROCEDIMIENTOS CORRUPTOS ENCONTRADOS</h4>";
        echo "<p>Los siguientes procedimientos contienen el error 'c.cliente_nombres':</p>";
        echo "<ul>";
        foreach ($corruptos as $proc) {
            echo "<li><code>$proc</code></li>";
        }
        echo "</ul>";
        echo "</div>";
        
        // Mostrar definiciones corruptas para análisis
        echo "<h3>🔍 DEFINICIONES CORRUPTAS</h3>";
        foreach ($procedimientos as $proc) {
            if (in_array($proc['ROUTINE_NAME'], $corruptos)) {
                $nombre = $proc['ROUTINE_NAME'];
                $definicion = htmlspecialchars($proc['ROUTINE_DEFINITION']);
                
                echo "<h4>Procedimiento: <code>$nombre</code></h4>";
                echo "<textarea readonly style='width: 100%; height: 200px; font-family: monospace; font-size: 12px;'>$definicion</textarea>";
                echo "<hr>";
            }
        }
        
    } else {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>✅ ¡TODOS LOS PROCEDIMIENTOS ESTÁN LIMPIOS!</h4>";
        echo "<p>No se encontraron procedimientos con 'c.cliente_nombres'.</p>";
        echo "<p>El error puede estar ocurriendo en otro lugar del sistema.</p>";
        echo "</div>";
    }
    
    // Mostrar todos los procedimientos para referencia
    echo "<h3>📋 LISTA COMPLETA DE PROCEDIMIENTOS</h3>";
    echo "<div style='max-height: 300px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;'>";
    echo "<ol>";
    foreach ($limpios as $proc) {
        echo "<li>✅ <code>$proc</code></li>";
    }
    foreach ($corruptos as $proc) {
        echo "<li>❌ <code>$proc</code> (CORRUPTO)</li>";
    }
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ ERROR</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Volver al Sistema</a></p>";
?> 