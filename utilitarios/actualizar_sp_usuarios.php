<?php
/**
 * Script para actualizar el procedimiento almacenado SP_LISTAR_USUARIOS
 * Este script debe ejecutarse una vez para actualizar el procedimiento con los nuevos campos
 */

require_once 'modelos/conexion.php';

try {
    // Conectar a la base de datos
    $pdo = Conexion::conectar();
    
    // Primero eliminar el procedimiento existente
    $pdo->exec("DROP PROCEDURE IF EXISTS `SP_LISTAR_USUARIOS`");
    
    // Crear el nuevo procedimiento
    $sql = "
    CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIOS` ()   
    BEGIN
        SELECT
            u.id_usuario,
            u.nombre_usuario,
            u.apellido_usuario,
            u.usuario, 
            u.clave,
            u.id_perfil_usuario, 
            p.descripcion,
            u.sucursal_id,
            COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
            CASE 
                WHEN u.estado = 1 THEN 'Activo'
                ELSE 'Inactivo'
            END as estado_texto,
            u.cedula,
            u.celular,
            u.cargo,
            u.telefono_whatsapp,
            u.whatsapp_activo,
            u.whatsapp_admin,
            u.ciudad,
            u.direccion,
            u.profesion,
            u.fecha_ingreso,
            u.numero_seguro,
            u.forma_pago,
            '' as opciones
        FROM usuarios u
        INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
        LEFT JOIN sucursales s ON u.sucursal_id = s.id
        ORDER BY u.id_usuario ASC;
    END";
    
    $result = $pdo->exec($sql);
    
    if ($result !== false) {
        echo "✅ Procedimiento almacenado SP_LISTAR_USUARIOS actualizado correctamente\n";
        echo "📋 Campos agregados:\n";
        echo "   - cedula\n";
        echo "   - celular\n";
        echo "   - cargo\n";
        echo "   - telefono_whatsapp\n";
        echo "   - whatsapp_activo\n";
        echo "   - whatsapp_admin\n";
        echo "   - ciudad\n";
        echo "   - direccion\n";
        echo "   - profesion\n";
        echo "   - fecha_ingreso\n";
        echo "   - numero_seguro\n";
        echo "   - forma_pago\n";
        
        // Verificar que el procedimiento funciona
        $stmt = $pdo->prepare('CALL SP_LISTAR_USUARIOS()');
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($usuarios)) {
            echo "✅ Verificación exitosa: El procedimiento devuelve " . count($usuarios) . " usuarios\n";
            
            // Mostrar las columnas del primer usuario para verificar
            if (isset($usuarios[0])) {
                $columnas = array_keys($usuarios[0]);
                echo "📊 Columnas disponibles: " . implode(', ', $columnas) . "\n";
            }
        } else {
            echo "⚠️  El procedimiento no devuelve usuarios (puede ser normal si no hay usuarios)\n";
        }
        
    } else {
        throw new Exception("Error al ejecutar el script SQL");
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "🔧 Solución: Ejecutar manualmente en phpMyAdmin:\n";
    echo "1. Ir a la base de datos\n";
    echo "2. Ejecutar: DROP PROCEDURE IF EXISTS `SP_LISTAR_USUARIOS`\n";
    echo "3. Ejecutar el contenido del archivo sql/actualizar_sp_usuarios.sql\n";
}

echo "\n🎯 Pasos siguientes:\n";
echo "1. Verificar que el DataTable de usuarios funciona correctamente\n";
echo "2. Probar el registro de un nuevo usuario con todos los campos\n";
echo "3. Verificar que la edición de usuarios carga todos los campos\n";
?> 