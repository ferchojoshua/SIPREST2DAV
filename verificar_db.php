<!DOCTYPE html>
<html>
<head>
    <title>Verificación de Base de Datos - Tabla Clientes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { color: red; }
        .success { color: green; }
        .warning { color: orange; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .missing { background-color: #ffebee; }
        .present { background-color: #e8f5e8; }
    </style>
</head>
<body>
    <h1>Verificación de Base de Datos - Tabla Clientes</h1>
    
    <?php
    try {
        require_once 'modelos/conexion.php';
        
        echo "<h2 class='success'>✅ Conexión exitosa a la base de datos</h2>";
        
        $conexion = Conexion::conectar();
        
        // Verificar si la tabla existe
        $stmt = $conexion->prepare("SHOW TABLES LIKE 'clientes'");
        $stmt->execute();
        $tablaExiste = $stmt->fetch();
        
        if (!$tablaExiste) {
            echo "<h2 class='error'>❌ ERROR: La tabla 'clientes' no existe en la base de datos.</h2>";
            exit;
        }
        
        echo "<h2 class='success'>✅ La tabla 'clientes' existe</h2>";
        
        // Obtener estructura de la tabla
        $stmt = $conexion->prepare("DESCRIBE clientes");
        $stmt->execute();
        $columnas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<h3>📋 Estructura actual de la tabla clientes:</h3>";
        echo "<table>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th><th>Default</th><th>Extra</th></tr>";
        
        foreach ($columnas as $columna) {
            echo "<tr>";
            echo "<td>{$columna['Field']}</td>";
            echo "<td>{$columna['Type']}</td>";
            echo "<td>{$columna['Null']}</td>";
            echo "<td>{$columna['Key']}</td>";
            echo "<td>" . ($columna['Default'] ?? 'NULL') . "</td>";
            echo "<td>{$columna['Extra']}</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Verificar campos específicos que necesita la aplicación
        $camposNecesarios = [
            'cliente_empresa_laboral',
            'cliente_cargo_laboral', 
            'cliente_tel_laboral',
            'cliente_dir_laboral',
            'cliente_refe_per_nombre',
            'cliente_refe_per_cel',
            'cliente_refe_per_dir',
            'cliente_refe_fami_nombre',
            'cliente_refe_fami_cel',
            'cliente_refe_fami_dir'
        ];
        
        $camposExistentes = array_column($columnas, 'Field');
        $camposFaltantes = array_diff($camposNecesarios, $camposExistentes);
        
        echo "<h3>🔍 Verificación de campos necesarios:</h3>";
        
        if (empty($camposFaltantes)) {
            echo "<p class='success'>✅ Todos los campos necesarios están presentes.</p>";
        } else {
            echo "<p class='error'>❌ Los siguientes campos están faltando:</p>";
            echo "<ul>";
            foreach ($camposFaltantes as $campo) {
                echo "<li class='error'>$campo</li>";
            }
            echo "</ul>";
            
            echo "<h3>🔧 Script SQL para agregar campos faltantes:</h3>";
            echo "<textarea style='width: 100%; height: 200px; font-family: monospace;'>";
            foreach ($camposFaltantes as $campo) {
                echo "ALTER TABLE `clientes` ADD COLUMN `$campo` VARCHAR(255) DEFAULT NULL;\n";
            }
            echo "</textarea>";
            
            echo "<p class='warning'>⚠️ <strong>Instrucciones:</strong></p>";
            echo "<ol>";
            echo "<li>Copia el script SQL de arriba</li>";
            echo "<li>Abre phpMyAdmin o tu gestor de base de datos</li>";
            echo "<li>Selecciona la base de datos del sistema</li>";
            echo "<li>Ejecuta el script SQL</li>";
            echo "<li>Vuelve a cargar esta página para verificar</li>";
            echo "</ol>";
        }
        
        // Verificar algunos registros de ejemplo
        echo "<h3>📊 Datos de ejemplo en la tabla:</h3>";
        $stmt = $conexion->prepare("SELECT cliente_id, cliente_nombres, cliente_dni, cliente_cel, cliente_correo FROM clientes LIMIT 5");
        $stmt->execute();
        $ejemplos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($ejemplos) {
            echo "<table>";
            echo "<tr><th>ID</th><th>Nombres</th><th>DNI</th><th>Celular</th><th>Correo</th></tr>";
            foreach ($ejemplos as $ejemplo) {
                echo "<tr>";
                echo "<td>{$ejemplo['cliente_id']}</td>";
                echo "<td>{$ejemplo['cliente_nombres']}</td>";
                echo "<td>{$ejemplo['cliente_dni']}</td>";
                echo "<td>{$ejemplo['cliente_cel']}</td>";
                echo "<td>{$ejemplo['cliente_correo']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No hay datos en la tabla clientes.</p>";
        }
        
    } catch (Exception $e) {
        echo "<h2 class='error'>❌ ERROR: " . $e->getMessage() . "</h2>";
        echo "<p>Archivo: " . $e->getFile() . "</p>";
        echo "<p>Línea: " . $e->getLine() . "</p>";
    }
    ?>
    
    <hr>
    <p><small>Verificación realizada el: <?php echo date('Y-m-d H:i:s'); ?></small></p>
</body>
</html> 