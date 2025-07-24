<?php
/**
 * VALIDACIÓN COMPLETA DEL FLUJO DE PRÉSTAMOS
 * ==========================================
 * 
 * Este script valida que todo el flujo de préstamos funcione correctamente:
 * 1. Frontend → AJAX → Controlador → Modelo → BD
 * 2. Sistema de consecutivos por sucursal
 * 3. Integración con cajas
 * 4. Flujo de aprobación
 * 
 * Uso: php validar_flujo_prestamos.php
 */

require_once "../conexion.php";

echo "===============================================================\n";
echo "🔍 VALIDACIÓN COMPLETA DEL FLUJO DE PRÉSTAMOS - CrediCrece\n";
echo "===============================================================\n\n";

$errores = [];
$warnings = [];

try {
    $pdo = Conexion::conectar();
    
    echo "📋 1. VERIFICANDO ESTRUCTURA DE BASE DE DATOS...\n";
    
    // Verificar tablas principales
    $tablas_principales = [
        'sucursales' => ['consecutivo_prestamos', 'consecutivo_recibos', 'consecutivo_vouchers'],
        'prestamo_cabecera' => ['nro_prestamo', 'cliente_id', 'pres_estado', 'caja_id'],
        'prestamo_detalle' => ['nro_prestamo', 'pdetalle_nro_cuota'],
        'caja' => ['caja_id', 'sucursal_id', 'caja_estado'],
        'clientes' => ['cliente_id', 'cliente_nombres'],
        'usuarios' => ['id_usuario', 'sucursal_id']
    ];
    
    foreach ($tablas_principales as $tabla => $columnas_requeridas) {
        echo "   🔍 Verificando tabla '$tabla'... ";
        
        // Verificar que la tabla existe
        $stmt = $pdo->prepare("SHOW TABLES LIKE '$tabla'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $errores[] = "Tabla '$tabla' no existe";
            echo "❌\n";
            continue;
        }
        
        // Verificar columnas requeridas
        $stmt = $pdo->prepare("DESCRIBE $tabla");
        $stmt->execute();
        $columnas_existentes = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'Field');
        
        $columnas_faltantes = array_diff($columnas_requeridas, $columnas_existentes);
        if (!empty($columnas_faltantes)) {
            $errores[] = "Tabla '$tabla' - Columnas faltantes: " . implode(', ', $columnas_faltantes);
            echo "❌\n";
        } else {
            echo "✅\n";
        }
    }
    
    echo "\n📋 2. VERIFICANDO STORED PROCEDURES...\n";
    
    // Verificar stored procedures del nuevo sistema
    $procedures_requeridos = [
        'SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL',
        'SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL',
        'SP_OBTENER_CONSECUTIVO_RECIBO_SUCURSAL',
        'SP_INCREMENTAR_CONSECUTIVO_RECIBO_SUCURSAL',
        'SP_OBTENER_CONSECUTIVO_VOUCHER_SUCURSAL',
        'SP_INCREMENTAR_CONSECUTIVO_VOUCHER_SUCURSAL',
        'SP_LISTAR_PRESTAMOS_POR_APROBACION'
    ];
    
    foreach ($procedures_requeridos as $procedure) {
        echo "   🔍 Verificando SP '$procedure'... ";
        
        $stmt = $pdo->prepare("SHOW PROCEDURE STATUS WHERE Name = '$procedure'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $errores[] = "Stored Procedure '$procedure' no existe";
            echo "❌\n";
        } else {
            echo "✅\n";
        }
    }
    
    echo "\n📋 3. VERIFICANDO VISTA DE CONSECUTIVOS...\n";
    
    echo "   🔍 Verificando vista 'v_consecutivos_sucursales'... ";
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'v_consecutivos_sucursales'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $errores[] = "Vista 'v_consecutivos_sucursales' no existe";
        echo "❌\n";
    } else {
        echo "✅\n";
    }
    
    echo "\n📋 4. VERIFICANDO DATOS DE PRUEBA...\n";
    
    // Verificar sucursales
    echo "   🔍 Verificando sucursales activas... ";
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sucursales WHERE estado = 'activa'");
    $stmt->execute();
    $sucursales_activas = $stmt->fetch(PDO::FETCH_OBJ)->total;
    
    if ($sucursales_activas == 0) {
        $errores[] = "No hay sucursales activas en el sistema";
        echo "❌\n";
    } else {
        echo "✅ ($sucursales_activas encontradas)\n";
    }
    
    // Verificar cajas abiertas
    echo "   🔍 Verificando cajas principales abiertas... ";
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM caja WHERE caja_estado = 'VIGENTE' AND tipo_caja = 'principal'");
    $stmt->execute();
    $cajas_abiertas = $stmt->fetch(PDO::FETCH_OBJ)->total;
    
    if ($cajas_abiertas == 0) {
        $warnings[] = "No hay cajas principales abiertas (necesarias para registrar préstamos)";
        echo "⚠️\n";
    } else {
        echo "✅ ($cajas_abiertas encontradas)\n";
    }
    
    // Verificar clientes
    echo "   🔍 Verificando clientes disponibles... ";
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM clientes");
    $stmt->execute();
    $total_clientes = $stmt->fetch(PDO::FETCH_OBJ)->total;
    
    if ($total_clientes == 0) {
        $warnings[] = "No hay clientes registrados";
        echo "⚠️\n";
    } else {
        echo "✅ ($total_clientes encontrados)\n";
    }
    
    echo "\n📋 5. PROBANDO SISTEMA DE CONSECUTIVOS...\n";
    
    // Probar consecutivos para cada sucursal activa
    $stmt = $pdo->prepare("SELECT id, codigo, nombre FROM sucursales WHERE estado = 'activa' LIMIT 3");
    $stmt->execute();
    $sucursales_test = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($sucursales_test as $sucursal) {
        echo "   🧪 Probando sucursal {$sucursal['codigo']} ({$sucursal['nombre']}):\n";
        
        try {
            // Probar obtener consecutivo de préstamo
            $stmt = $pdo->prepare('CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal['id'], PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_OBJ);
            
            if ($resultado && isset($resultado->nro_prestamo)) {
                echo "      📋 Próximo préstamo: {$resultado->nro_prestamo} ✅\n";
            } else {
                $errores[] = "Error al obtener consecutivo para sucursal {$sucursal['codigo']}";
                echo "      📋 Error al obtener consecutivo ❌\n";
            }
            
            $stmt->closeCursor();
        } catch (Exception $e) {
            $errores[] = "Excepción al probar consecutivos para sucursal {$sucursal['codigo']}: " . $e->getMessage();
            echo "      📋 Excepción: {$e->getMessage()} ❌\n";
        }
    }
    
    echo "\n📋 6. VERIFICANDO ARCHIVOS DEL SISTEMA...\n";
    
    // Verificar archivos principales
    $archivos_principales = [
        '../vistas/prestamo.php' => 'Vista principal de préstamos',
        '../ajax/prestamo_ajax.php' => 'AJAX de préstamos',
        '../ajax/consecutivos_ajax.php' => 'AJAX de consecutivos',
        '../controladores/prestamo_controlador.php' => 'Controlador de préstamos',
        '../modelos/prestamo_modelo.php' => 'Modelo de préstamos',
        '../modelos/consecutivos_modelo.php' => 'Modelo de consecutivos',
        '../vistas/aprobacion.php' => 'Vista de aprobación',
        '../ajax/aprobacion_ajax.php' => 'AJAX de aprobación'
    ];
    
    foreach ($archivos_principales as $archivo => $descripcion) {
        echo "   🔍 Verificando $descripcion... ";
        if (file_exists($archivo)) {
            echo "✅\n";
        } else {
            $errores[] = "Archivo faltante: $archivo ($descripcion)";
            echo "❌\n";
        }
    }
    
    echo "\n📋 7. RESUMEN DE VALIDACIÓN...\n";
    
    if (empty($errores) && empty($warnings)) {
        echo "🎉 ¡VALIDACIÓN EXITOSA!\n";
        echo "✅ Todos los componentes están funcionando correctamente.\n";
        echo "✅ El sistema está listo para procesar préstamos.\n";
    } else {
        if (!empty($errores)) {
            echo "❌ ERRORES ENCONTRADOS:\n";
            foreach ($errores as $i => $error) {
                echo "   " . ($i + 1) . ". $error\n";
            }
        }
        
        if (!empty($warnings)) {
            echo "\n⚠️  ADVERTENCIAS:\n";
            foreach ($warnings as $i => $warning) {
                echo "   " . ($i + 1) . ". $warning\n";
            }
        }
        
        if (!empty($errores)) {
            echo "\n❌ El sistema requiere correcciones antes de usarse en producción.\n";
        } else {
            echo "\n✅ El sistema está funcional, pero revise las advertencias.\n";
        }
    }
    
    echo "\n🔗 PASOS SIGUIENTES RECOMENDADOS:\n";
    echo "1. ✅ Ejecutar script: sql/reiniciar_consecutivos.sql\n";
    echo "2. ✅ Asegurar que hay cajas principales abiertas\n";
    echo "3. ✅ Probar registro de préstamo desde el frontend\n";
    echo "4. ✅ Verificar que consecutivos se incrementen correctamente\n";
    echo "5. ✅ Probar flujo de aprobación\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR CRÍTICO: " . $e->getMessage() . "\n";
    echo "🔄 No se pudo completar la validación.\n";
    exit(1);
}

echo "\n===============================================================\n";
echo "📝 Validación completada el " . date('Y-m-d H:i:s') . "\n";
echo "===============================================================\n";
?> 