<?php
// Script para verificar y configurar el menú básico
require_once "../conexion_reportes/r_conexion.php";

try {
    $pdo = Conexion::conectar();
    
    echo "<h2>🔍 VERIFICACIÓN DEL MENÚ</h2>";
    
    // 1. Verificar módulos existentes
    echo "<h3>📋 Módulos Existentes:</h3>";
    $stmt = $pdo->prepare("SELECT id, modulo, vista, icon_menu, orden, padre_id FROM modulos ORDER BY orden");
    $stmt->execute();
    $modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>ID</th><th>Módulo</th><th>Vista</th><th>Icono</th><th>Orden</th><th>Padre ID</th></tr>";
    foreach ($modulos as $modulo) {
        echo "<tr>";
        echo "<td>{$modulo['id']}</td>";
        echo "<td>{$modulo['modulo']}</td>";
        echo "<td>{$modulo['vista']}</td>";
        echo "<td>{$modulo['icon_menu']}</td>";
        echo "<td>{$modulo['orden']}</td>";
        echo "<td>{$modulo['padre_id']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 2. Verificar perfiles
    echo "<h3>👥 Perfiles Existentes:</h3>";
    $stmt = $pdo->prepare("SELECT id_perfil, descripcion FROM perfiles");
    $stmt->execute();
    $perfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID Perfil</th><th>Descripción</th></tr>";
    foreach ($perfiles as $perfil) {
        echo "<tr><td>{$perfil['id_perfil']}</td><td>{$perfil['descripcion']}</td></tr>";
    }
    echo "</table>";
    
    // 3. Verificar asignaciones de perfil_modulo
    echo "<h3>🔗 Asignaciones Perfil-Módulo:</h3>";
    $stmt = $pdo->prepare("
        SELECT pm.id_perfil, p.descripcion as perfil, pm.id_modulo, m.modulo, pm.vista_inicio, pm.estado
        FROM perfil_modulo pm
        INNER JOIN perfiles p ON pm.id_perfil = p.id_perfil
        INNER JOIN modulos m ON pm.id_modulo = m.id
        ORDER BY pm.id_perfil, m.orden
    ");
    $stmt->execute();
    $asignaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Perfil ID</th><th>Perfil</th><th>Módulo ID</th><th>Módulo</th><th>Vista Inicio</th><th>Estado</th></tr>";
    foreach ($asignaciones as $asig) {
        echo "<tr>";
        echo "<td>{$asig['id_perfil']}</td>";
        echo "<td>{$asig['perfil']}</td>";
        echo "<td>{$asig['id_modulo']}</td>";
        echo "<td>{$asig['modulo']}</td>";
        echo "<td>{$asig['vista_inicio']}</td>";
        echo "<td>{$asig['estado']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 4. Verificar usuario específico
    echo "<h3>👤 Usuario Actual (ID 1):</h3>";
    $stmt = $pdo->prepare("
        SELECT u.id_usuario, u.nombre_usuario, u.id_perfil_usuario, p.descripcion as perfil
        FROM usuarios u
        INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
        WHERE u.id_usuario = 1
    ");
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($usuario) {
        echo "<p><strong>Usuario:</strong> {$usuario['nombre_usuario']}</p>";
        echo "<p><strong>Perfil:</strong> {$usuario['perfil']} (ID: {$usuario['id_perfil_usuario']})</p>";
    } else {
        echo "<p><strong>⚠️ Usuario con ID 1 no encontrado</strong></p>";
    }
    
    // 5. CONFIGURAR MENÚ BÁSICO SI NO EXISTE
    echo "<h3>🔧 Configurando Menú Básico...</h3>";
    
    // Insertar módulos básicos
    $modulos_basicos = [
        ['Dashboard', 0, 'dashboard.php', 'fas fa-tachometer-alt', 1],
        ['Caja', 0, 'caja.php', 'fas fa-cash-register', 2],
        ['Clientes', 0, 'administrar_clientes.php', 'fas fa-users', 3],
        ['Prestamos', 0, 'administrar_prestamos.php', 'fas fa-hand-holding-usd', 4]
    ];
    
    foreach ($modulos_basicos as $mod) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO modulos (modulo, padre_id, vista, icon_menu, orden) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $resultado = $stmt->execute($mod);
        echo "<p>✅ Módulo '{$mod[0]}' configurado</p>";
    }
    
    // Asignar permisos al perfil Administrador
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO perfil_modulo (id_perfil, id_modulo, vista_inicio, estado)
        SELECT 1, m.id, 
               CASE WHEN m.vista = 'dashboard.php' THEN 1 ELSE 0 END,
               1
        FROM modulos m 
        WHERE m.modulo IN ('Dashboard', 'Caja', 'Clientes', 'Prestamos')
    ");
    $stmt->execute();
    echo "<p>✅ Permisos asignados al perfil Administrador</p>";
    
    echo "<h3>🎉 Configuración completada. Recarga la página para ver el menú.</h3>";
    echo "<p><a href='../index.php'>← Volver al sistema</a></p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?> 