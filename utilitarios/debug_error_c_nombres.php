<?php
/**
 * Script de depuración específico para el error c.cliente_nombres en sucursales
 */

require_once "modelos/conexion.php";

echo "<h2>🐛 DEBUG: Error c.cliente_nombres en Sucursales</h2>";
echo "<p>Probando paso a paso cada consulta para encontrar el error exacto...</p>";
echo "<hr>";

try {
    $pdo = Conexion::conectar();
    echo "<p>✅ <strong>Conexión establecida correctamente</strong></p>";
    
    // 1. Probar consulta básica de sucursales
    echo "<h3>1️⃣ PRUEBA: Consulta básica de sucursales</h3>";
    try {
        $stmt = $pdo->prepare("SELECT id, nombre, codigo FROM sucursales WHERE estado = 'activa'");
        $stmt->execute();
        $sucursales_basico = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>✅ Consulta básica OK - " . count($sucursales_basico) . " sucursales encontradas</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error en consulta básica: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    // 2. Probar subconsulta de rutas
    echo "<h3>2️⃣ PRUEBA: Subconsulta de rutas</h3>";
    try {
        $stmt = $pdo->prepare("SELECT id, (SELECT COUNT(*) FROM rutas WHERE sucursal_id = s.id AND ruta_estado = 'activa') as total_rutas FROM sucursales s WHERE s.estado = 'activa'");
        $stmt->execute();
        $test_rutas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>✅ Subconsulta de rutas OK</p>";
    } catch (Exception $e) {
        echo "<p>❌ Error en subconsulta de rutas: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    // 3. Probar subconsulta de usuarios (ESTA ES LA SOSPECHOSA)
    echo "<h3>3️⃣ PRUEBA: Subconsulta de usuarios</h3>";
    try {
        $stmt = $pdo->prepare("SELECT id, (SELECT COUNT(DISTINCT u.id_usuario) FROM usuarios u WHERE u.sucursal_id = s.id AND u.estado = 1) as total_usuarios FROM sucursales s WHERE s.estado = 'activa'");
        $stmt->execute();
        $test_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>✅ Subconsulta de usuarios OK</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>ERROR EN SUBCONSULTA DE USUARIOS:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>🎯 <strong>¡ESTE ES EL PROBLEMA!</strong></p>";
    }
    
    // 4. Probar la consulta completa original
    echo "<h3>4️⃣ PRUEBA: Consulta completa de sucursales (como está en el modelo)</h3>";
    try {
        $sql_completa = "
        SELECT 
            s.id as sucursal_id,
            s.nombre as sucursal_nombre,
            s.codigo as sucursal_codigo,
            s.direccion as sucursal_direccion,
            s.telefono as sucursal_telefono,
            CONCAT(s.codigo, ' - ', s.nombre) as texto_completo,
            CASE 
                WHEN s.direccion IS NOT NULL AND s.direccion != '' 
                THEN CONCAT(s.codigo, ' - ', s.nombre, ' (', s.direccion, ')') 
                ELSE CONCAT(s.codigo, ' - ', s.nombre)
            END as texto_descriptivo,
            (SELECT COUNT(*) FROM rutas WHERE sucursal_id = s.id AND ruta_estado = 'activa') as total_rutas,
            (SELECT COUNT(DISTINCT u.id_usuario) FROM usuarios u WHERE u.sucursal_id = s.id AND u.estado = 1) as total_usuarios
        FROM sucursales s 
        WHERE s.estado = 'activa' 
        ORDER BY s.nombre";
        
        $stmt = $pdo->prepare($sql_completa);
        $stmt->execute();
        $resultado_completo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "<p>✅ Consulta completa OK - " . count($resultado_completo) . " sucursales con datos completos</p>";
        
        // Mostrar una muestra de los datos
        if (!empty($resultado_completo)) {
            echo "<h4>📋 Muestra de datos obtenidos:</h4>";
            echo "<pre>" . htmlspecialchars(json_encode($resultado_completo[0], JSON_PRETTY_PRINT)) . "</pre>";
        }
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>ERROR EN CONSULTA COMPLETA:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>🎯 <strong>AQUÍ ESTÁ EL PROBLEMA!</strong></p>";
        
        // Analizar el error específicamente
        if (strpos($e->getMessage(), 'c.cliente_nombres') !== false) {
            echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
            echo "<h4>🔍 ANÁLISIS DEL ERROR</h4>";
            echo "<p>El error 'c.cliente_nombres' sugiere que hay:</p>";
            echo "<ul>";
            echo "<li>❌ Un procedimiento almacenado corrupto que se ejecuta automáticamente</li>";
            echo "<li>❌ Un trigger en alguna de las tablas que contiene 'c.cliente_nombres'</li>";
            echo "<li>❌ Una vista que está usando el alias 'c.cliente_nombres' incorrectamente</li>";
            echo "</ul>";
            echo "</div>";
        }
    }
    
    // 5. Verificar si hay triggers en las tablas
    echo "<h3>5️⃣ PRUEBA: Verificar triggers en tablas</h3>";
    try {
        $stmt = $pdo->prepare("SHOW TRIGGERS LIKE 'sucursales'");
        $stmt->execute();
        $triggers_sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $stmt = $pdo->prepare("SHOW TRIGGERS LIKE 'usuarios'");
        $stmt->execute();
        $triggers_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<p>📋 Triggers en sucursales: " . count($triggers_sucursales) . "</p>";
        echo "<p>📋 Triggers en usuarios: " . count($triggers_usuarios) . "</p>";
        
        if (!empty($triggers_sucursales) || !empty($triggers_usuarios)) {
            echo "<h4>⚠️ TRIGGERS ENCONTRADOS - POSIBLE CAUSA:</h4>";
            foreach (array_merge($triggers_sucursales, $triggers_usuarios) as $trigger) {
                echo "<p>🔧 <strong>{$trigger['Trigger']}:</strong> {$trigger['Event']} en {$trigger['Table']}</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p>❌ Error verificando triggers: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    // 6. Probar el método del modelo directamente
    echo "<h3>6️⃣ PRUEBA: Método del modelo directamente</h3>";
    try {
        require_once "modelos/sucursales_modelo.php";
        $resultado_modelo = SucursalModelo::mdlListarSucursalesActivasCompletas();
        echo "<p>✅ Método del modelo OK - " . count($resultado_modelo) . " resultados</p>";
    } catch (Exception $e) {
        echo "<p>❌ <strong>ERROR EN MÉTODO DEL MODELO:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "<p>🎯 <strong>CONFIRMADO: El error está en el modelo de sucursales</strong></p>";
    }
    
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ ERROR CRÍTICO DE CONEXIÓN</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Volver al Sistema</a></p>";
?> 