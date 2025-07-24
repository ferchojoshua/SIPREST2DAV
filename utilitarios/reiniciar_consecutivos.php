<?php
/**
 * SCRIPT PARA REINICIAR CONSECUTIVOS DE TODAS LAS SUCURSALES
 * ===========================================================
 * 
 * Este script reinicia los consecutivos de préstamos, recibos y vouchers
 * de todas las sucursales, estableciéndolos en 1.
 * 
 * ⚠️  IMPORTANTE: Ejecutar solo cuando sea necesario reiniciar el sistema
 * 
 * Uso: php reiniciar_consecutivos.php
 */

require_once "../conexion.php";

echo "=============================================================\n";
echo "🔄 REINICIANDO CONSECUTIVOS DE TODAS LAS SUCURSALES\n";
echo "=============================================================\n\n";

try {
    $pdo = Conexion::conectar();
    
    echo "📋 1. Consultando sucursales existentes...\n";
    
    // Obtener todas las sucursales
    $stmt = $pdo->prepare("SELECT id, codigo, nombre, estado FROM sucursales ORDER BY id");
    $stmt->execute();
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($sucursales)) {
        echo "❌ No se encontraron sucursales en el sistema.\n";
        exit(1);
    }
    
    echo "✅ Encontradas " . count($sucursales) . " sucursales:\n";
    foreach ($sucursales as $sucursal) {
        $estado_icon = $sucursal['estado'] == 'activa' ? '🟢' : '🔴';
        echo "   $estado_icon {$sucursal['id']} - {$sucursal['codigo']} - {$sucursal['nombre']} ({$sucursal['estado']})\n";
    }
    echo "\n";
    
    echo "📋 2. Verificando estado actual de consecutivos...\n";
    
    // Mostrar estado actual
    $stmt = $pdo->prepare("
        SELECT 
            id, codigo, nombre,
            consecutivo_prestamos, 
            consecutivo_recibos, 
            consecutivo_vouchers 
        FROM sucursales 
        ORDER BY id
    ");
    $stmt->execute();
    $estados_actuales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "📊 Estado actual:\n";
    echo str_pad("ID", 4) . str_pad("CÓDIGO", 8) . str_pad("PRÉSTAMOS", 12) . str_pad("RECIBOS", 10) . str_pad("VOUCHERS", 10) . "\n";
    echo str_repeat("-", 50) . "\n";
    
    foreach ($estados_actuales as $estado) {
        echo str_pad($estado['id'], 4) . 
             str_pad($estado['codigo'], 8) . 
             str_pad($estado['consecutivo_prestamos'] ?? 'NULL', 12) . 
             str_pad($estado['consecutivo_recibos'] ?? 'NULL', 10) . 
             str_pad($estado['consecutivo_vouchers'] ?? 'NULL', 10) . "\n";
    }
    echo "\n";
    
    echo "⚠️  ¿Está seguro de que desea REINICIAR TODOS los consecutivos a 1?\n";
    echo "   Esto afectará a todas las sucursales y no se puede deshacer.\n";
    echo "   Escriba 'SI' para continuar o cualquier otra cosa para cancelar: ";
    
    // Leer confirmación del usuario
    $confirmacion = trim(fgets(STDIN));
    
    if (strtoupper($confirmacion) !== 'SI') {
        echo "\n❌ Operación cancelada por el usuario.\n";
        exit(0);
    }
    
    echo "\n🔄 3. Reiniciando consecutivos...\n";
    
    // Iniciar transacción para seguridad
    $pdo->beginTransaction();
    
    try {
        // Reiniciar consecutivos de todas las sucursales
        $stmt = $pdo->prepare("
            UPDATE sucursales 
            SET consecutivo_prestamos = 1, 
                consecutivo_recibos = 1, 
                consecutivo_vouchers = 1
        ");
        
        $resultado = $stmt->execute();
        $filas_afectadas = $stmt->rowCount();
        
        if ($resultado) {
            echo "✅ Consecutivos reiniciados exitosamente.\n";
            echo "📊 Sucursales afectadas: $filas_afectadas\n\n";
            
            // Verificar el resultado
            echo "📋 4. Verificando resultado...\n";
            
            $stmt = $pdo->prepare("
                SELECT 
                    id, codigo, nombre,
                    consecutivo_prestamos, 
                    consecutivo_recibos, 
                    consecutivo_vouchers 
                FROM sucursales 
                ORDER BY id
            ");
            $stmt->execute();
            $estados_nuevos = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo "📊 Nuevo estado:\n";
            echo str_pad("ID", 4) . str_pad("CÓDIGO", 8) . str_pad("PRÉSTAMOS", 12) . str_pad("RECIBOS", 10) . str_pad("VOUCHERS", 10) . "\n";
            echo str_repeat("-", 50) . "\n";
            
            $todo_correcto = true;
            foreach ($estados_nuevos as $estado) {
                echo str_pad($estado['id'], 4) . 
                     str_pad($estado['codigo'], 8) . 
                     str_pad($estado['consecutivo_prestamos'], 12) . 
                     str_pad($estado['consecutivo_recibos'], 10) . 
                     str_pad($estado['consecutivo_vouchers'], 10) . "\n";
                
                // Verificar que todos estén en 1
                if ($estado['consecutivo_prestamos'] != 1 || 
                    $estado['consecutivo_recibos'] != 1 || 
                    $estado['consecutivo_vouchers'] != 1) {
                    $todo_correcto = false;
                }
            }
            
            if ($todo_correcto) {
                echo "\n✅ ¡Todos los consecutivos se han reiniciado correctamente a 1!\n";
                
                // Confirmar transacción
                $pdo->commit();
                
                echo "\n🎯 5. Probando sistema de consecutivos...\n";
                
                // Probar generación de números para algunas sucursales
                foreach (array_slice($estados_nuevos, 0, 2) as $sucursal) {
                    echo "🧪 Probando sucursal {$sucursal['codigo']}:\n";
                    
                    // Simular obtención de consecutivo de préstamo
                    $stmt = $pdo->prepare('CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(:sucursal_id)');
                    $stmt->bindParam(":sucursal_id", $sucursal['id'], PDO::PARAM_INT);
                    $stmt->execute();
                    $resultado_prestamo = $stmt->fetch(PDO::FETCH_OBJ);
                    
                    if ($resultado_prestamo) {
                        echo "   📋 Próximo préstamo: {$resultado_prestamo->nro_prestamo}\n";
                    } else {
                        echo "   ❌ Error al obtener consecutivo de préstamo\n";
                    }
                    
                    // Limpiar resultados para la siguiente consulta
                    $stmt->closeCursor();
                }
                
                echo "\n🎉 ¡PROCESO COMPLETADO EXITOSAMENTE!\n";
                echo "=============================================================\n";
                echo "✅ Todos los consecutivos han sido reiniciados.\n";
                echo "🔢 Los próximos números serán:\n";
                echo "   - Préstamos: [CÓDIGO]-00000001\n";
                echo "   - Recibos: R-[CÓDIGO]-00000001\n";
                echo "   - Vouchers: V-[CÓDIGO]-00000001\n";
                echo "=============================================================\n";
                
            } else {
                throw new Exception("Error en la verificación: algunos consecutivos no se establecieron correctamente.");
            }
            
        } else {
            throw new Exception("Error al ejecutar la actualización de consecutivos.");
        }
        
    } catch (Exception $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        throw $e;
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "🔄 La operación ha sido revertida para mantener la integridad de los datos.\n";
    exit(1);
}

echo "\n📝 Log: Consecutivos reiniciados el " . date('Y-m-d H:i:s') . "\n";
?> 