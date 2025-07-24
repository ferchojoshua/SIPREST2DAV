<?php
/**
 * IMPLEMENTAR SISTEMA COMPLETO DE CONSECUTIVOS POR SUCURSAL
 * =========================================================
 * 
 * Este script ejecuta la implementación del sistema de consecutivos
 * basado en la nomenclatura de las sucursales existentes.
 */

require_once "modelos/conexion.php";

echo "<h2>🚀 IMPLEMENTANDO SISTEMA DE CONSECUTIVOS POR SUCURSAL</h2>";
echo "<p>Ejecutando implementación completa del sistema...</p>";
echo "<hr>";

try {
    $pdo = Conexion::conectar();
    $errores = 0;
    $exitos = 0;

    echo "<h3>📋 PASO 1: VERIFICAR SUCURSALES EXISTENTES</h3>";
    
    // Verificar sucursales actuales
    $stmt = $pdo->prepare("SELECT id, nombre, codigo FROM sucursales WHERE estado = 'activa'");
    $stmt->execute();
    $sucursales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<p><strong>Sucursales encontradas:</strong></p>";
    echo "<ul>";
    foreach ($sucursales as $sucursal) {
        echo "<li>ID: {$sucursal['id']} - {$sucursal['nombre']} - Código: {$sucursal['codigo']}</li>";
    }
    echo "</ul>";

    echo "<h3>🔧 PASO 2: AGREGAR CAMPOS DE CONSECUTIVOS A TABLA SUCURSALES</h3>";
    
    try {
        $sql_alter = "ALTER TABLE `sucursales` 
                     ADD COLUMN `consecutivo_prestamos` INT(11) DEFAULT 1 COMMENT 'Consecutivo de préstamos por sucursal',
                     ADD COLUMN `consecutivo_recibos` INT(11) DEFAULT 1 COMMENT 'Consecutivo de recibos por sucursal', 
                     ADD COLUMN `consecutivo_vouchers` INT(11) DEFAULT 1 COMMENT 'Consecutivo de vouchers por sucursal'";
        
        $pdo->exec($sql_alter);
        echo "<p>✅ Campos de consecutivos agregados a tabla sucursales</p>";
        $exitos++;
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
            echo "<p>ℹ️ Los campos de consecutivos ya existen en la tabla sucursales</p>";
            $exitos++;
        } else {
            echo "<p>❌ Error agregando campos: " . htmlspecialchars($e->getMessage()) . "</p>";
            $errores++;
        }
    }

    echo "<h3>👁️ PASO 3: CREAR VISTA v_consecutivos_sucursales</h3>";
    
    try {
        $sql_vista = "CREATE OR REPLACE VIEW `v_consecutivos_sucursales` AS
                     SELECT 
                         s.id as sucursal_id,
                         s.nombre as sucursal_nombre,
                         s.codigo as sucursal_codigo,
                         s.consecutivo_prestamos,
                         s.consecutivo_recibos,
                         s.consecutivo_vouchers,
                         CONCAT(s.codigo, '-', LPAD(s.consecutivo_prestamos, 8, '0')) as proximo_nro_prestamo,
                         CONCAT('R-', s.codigo, '-', LPAD(s.consecutivo_recibos, 8, '0')) as proximo_nro_recibo,
                         CONCAT('V-', s.codigo, '-', LPAD(s.consecutivo_vouchers, 8, '0')) as proximo_nro_voucher,
                         s.estado as sucursal_estado
                     FROM sucursales s 
                     WHERE s.estado = 'activa'";
        
        $pdo->exec($sql_vista);
        echo "<p>✅ Vista v_consecutivos_sucursales creada exitosamente</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando vista: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    echo "<h3>⚙️ PASO 4: CREAR STORED PROCEDURES</h3>";

    // Crear SP para obtener consecutivo de préstamo
    try {
        $sql_sp_obtener = "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL`(IN `p_sucursal_id` INT)
        BEGIN
            DECLARE v_consecutivo INT DEFAULT 1;
            DECLARE v_codigo_sucursal VARCHAR(20) DEFAULT 'SUC';
            
            SELECT consecutivo_prestamos, codigo 
            INTO v_consecutivo, v_codigo_sucursal
            FROM sucursales 
            WHERE id = p_sucursal_id AND estado = 'activa';
            
            IF v_consecutivo IS NULL THEN
                SET v_consecutivo = 1;
                SET v_codigo_sucursal = 'DEF';
            END IF;
            
            SELECT CONCAT(v_codigo_sucursal, '-', LPAD(v_consecutivo, 8, '0')) as nro_prestamo,
                   v_consecutivo as consecutivo_actual,
                   v_codigo_sucursal as codigo_sucursal,
                   p_sucursal_id as sucursal_id;
        END";
        
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL");
        $pdo->exec($sql_sp_obtener);
        echo "<p>✅ SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL creado</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando SP obtener préstamo: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    // Crear SP para incrementar consecutivo de préstamo
    try {
        $sql_sp_incrementar = "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL`(IN `p_sucursal_id` INT)
        BEGIN
            DECLARE v_existe INT DEFAULT 0;
            
            SELECT COUNT(*) INTO v_existe 
            FROM sucursales 
            WHERE id = p_sucursal_id AND estado = 'activa';
            
            IF v_existe > 0 THEN
                UPDATE sucursales 
                SET consecutivo_prestamos = consecutivo_prestamos + 1 
                WHERE id = p_sucursal_id;
                
                SELECT 'ok' as resultado, consecutivo_prestamos as nuevo_consecutivo
                FROM sucursales 
                WHERE id = p_sucursal_id;
            ELSE
                SELECT 'error' as resultado, 'Sucursal no encontrada o inactiva' as mensaje;
            END IF;
        END";
        
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL");
        $pdo->exec($sql_sp_incrementar);
        echo "<p>✅ SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL creado</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando SP incrementar préstamo: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    // Crear SPs para recibos
    try {
        $sql_sp_recibo_obtener = "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_CONSECUTIVO_RECIBO_SUCURSAL`(IN `p_sucursal_id` INT)
        BEGIN
            DECLARE v_consecutivo INT DEFAULT 1;
            DECLARE v_codigo_sucursal VARCHAR(20) DEFAULT 'SUC';
            
            SELECT consecutivo_recibos, codigo 
            INTO v_consecutivo, v_codigo_sucursal
            FROM sucursales 
            WHERE id = p_sucursal_id AND estado = 'activa';
            
            IF v_consecutivo IS NULL THEN
                SET v_consecutivo = 1;
                SET v_codigo_sucursal = 'DEF';
            END IF;
            
            SELECT CONCAT('R-', v_codigo_sucursal, '-', LPAD(v_consecutivo, 8, '0')) as nro_recibo,
                   v_consecutivo as consecutivo_actual,
                   v_codigo_sucursal as codigo_sucursal,
                   p_sucursal_id as sucursal_id;
        END";
        
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_OBTENER_CONSECUTIVO_RECIBO_SUCURSAL");
        $pdo->exec($sql_sp_recibo_obtener);
        echo "<p>✅ SP_OBTENER_CONSECUTIVO_RECIBO_SUCURSAL creado</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando SP obtener recibo: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    try {
        $sql_sp_recibo_incrementar = "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INCREMENTAR_CONSECUTIVO_RECIBO_SUCURSAL`(IN `p_sucursal_id` INT)
        BEGIN
            DECLARE v_existe INT DEFAULT 0;
            
            SELECT COUNT(*) INTO v_existe 
            FROM sucursales 
            WHERE id = p_sucursal_id AND estado = 'activa';
            
            IF v_existe > 0 THEN
                UPDATE sucursales 
                SET consecutivo_recibos = consecutivo_recibos + 1 
                WHERE id = p_sucursal_id;
                
                SELECT 'ok' as resultado, consecutivo_recibos as nuevo_consecutivo
                FROM sucursales 
                WHERE id = p_sucursal_id;
            ELSE
                SELECT 'error' as resultado, 'Sucursal no encontrada o inactiva' as mensaje;
            END IF;
        END";
        
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_INCREMENTAR_CONSECUTIVO_RECIBO_SUCURSAL");
        $pdo->exec($sql_sp_recibo_incrementar);
        echo "<p>✅ SP_INCREMENTAR_CONSECUTIVO_RECIBO_SUCURSAL creado</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando SP incrementar recibo: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    // Crear SPs para vouchers
    try {
        $sql_sp_voucher_obtener = "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_CONSECUTIVO_VOUCHER_SUCURSAL`(IN `p_sucursal_id` INT)
        BEGIN
            DECLARE v_consecutivo INT DEFAULT 1;
            DECLARE v_codigo_sucursal VARCHAR(20) DEFAULT 'SUC';
            
            SELECT consecutivo_vouchers, codigo 
            INTO v_consecutivo, v_codigo_sucursal
            FROM sucursales 
            WHERE id = p_sucursal_id AND estado = 'activa';
            
            IF v_consecutivo IS NULL THEN
                SET v_consecutivo = 1;
                SET v_codigo_sucursal = 'DEF';
            END IF;
            
            SELECT CONCAT('V-', v_codigo_sucursal, '-', LPAD(v_consecutivo, 8, '0')) as nro_voucher,
                   v_consecutivo as consecutivo_actual,
                   v_codigo_sucursal as codigo_sucursal,
                   p_sucursal_id as sucursal_id;
        END";
        
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_OBTENER_CONSECUTIVO_VOUCHER_SUCURSAL");
        $pdo->exec($sql_sp_voucher_obtener);
        echo "<p>✅ SP_OBTENER_CONSECUTIVO_VOUCHER_SUCURSAL creado</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando SP obtener voucher: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    try {
        $sql_sp_voucher_incrementar = "CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_INCREMENTAR_CONSECUTIVO_VOUCHER_SUCURSAL`(IN `p_sucursal_id` INT)
        BEGIN
            DECLARE v_existe INT DEFAULT 0;
            
            SELECT COUNT(*) INTO v_existe 
            FROM sucursales 
            WHERE id = p_sucursal_id AND estado = 'activa';
            
            IF v_existe > 0 THEN
                UPDATE sucursales 
                SET consecutivo_vouchers = consecutivo_vouchers + 1 
                WHERE id = p_sucursal_id;
                
                SELECT 'ok' as resultado, consecutivo_vouchers as nuevo_consecutivo
                FROM sucursales 
                WHERE id = p_sucursal_id;
            ELSE
                SELECT 'error' as resultado, 'Sucursal no encontrada o inactiva' as mensaje;
            END IF;
        END";
        
        $pdo->exec("DROP PROCEDURE IF EXISTS SP_INCREMENTAR_CONSECUTIVO_VOUCHER_SUCURSAL");
        $pdo->exec($sql_sp_voucher_incrementar);
        echo "<p>✅ SP_INCREMENTAR_CONSECUTIVO_VOUCHER_SUCURSAL creado</p>";
        $exitos++;
    } catch (PDOException $e) {
        echo "<p>❌ Error creando SP incrementar voucher: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    echo "<h3>🧪 PASO 5: PRUEBAS DE FUNCIONAMIENTO</h3>";

    echo "<h4>📊 Estado actual de la vista:</h4>";
    try {
        $stmt = $pdo->prepare("SELECT * FROM v_consecutivos_sucursales");
        $stmt->execute();
        $vista_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($vista_data)) {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr style='background: #f0f0f0;'>";
            echo "<th>Sucursal</th><th>Código</th><th>Próximo Préstamo</th><th>Próximo Recibo</th><th>Próximo Voucher</th>";
            echo "</tr>";
            foreach ($vista_data as $row) {
                echo "<tr>";
                echo "<td>{$row['sucursal_nombre']}</td>";
                echo "<td>{$row['sucursal_codigo']}</td>";
                echo "<td>{$row['proximo_nro_prestamo']}</td>";
                echo "<td>{$row['proximo_nro_recibo']}</td>";
                echo "<td>{$row['proximo_nro_voucher']}</td>";
                echo "</tr>";
            }
            echo "</table>";
            $exitos++;
        } else {
            echo "<p>⚠️ La vista no retorna datos</p>";
            $errores++;
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error consultando vista: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    echo "<h4>🔄 Prueba de obtener consecutivo para León (ID: 1):</h4>";
    try {
        $stmt = $pdo->prepare("CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(1)");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo "<p>✅ <strong>León:</strong> {$resultado['nro_prestamo']} (Consecutivo: {$resultado['consecutivo_actual']})</p>";
            $exitos++;
        } else {
            echo "<p>❌ No se obtuvo resultado para León</p>";
            $errores++;
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error probando SP León: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    echo "<h4>🔄 Prueba de obtener consecutivo para Chinandega (ID: 2):</h4>";
    try {
        $stmt = $pdo->prepare("CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(2)");
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($resultado) {
            echo "<p>✅ <strong>Chinandega:</strong> {$resultado['nro_prestamo']} (Consecutivo: {$resultado['consecutivo_actual']})</p>";
            $exitos++;
        } else {
            echo "<p>❌ No se obtuvo resultado para Chinandega</p>";
            $errores++;
        }
    } catch (PDOException $e) {
        echo "<p>❌ Error probando SP Chinandega: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    echo "<h4>🧪 Prueba del modelo PHP ConsecutivosModelo:</h4>";
    
    // Incluir y probar el modelo de consecutivos
    try {
        require_once "modelos/consecutivos_modelo.php";
        
        // Simular sesión para prueba
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $_SESSION["usuario"] = (object) [
            'id_usuario' => 1,
            'sucursal_id' => 1,
            'nombre_usuario' => 'Admin Test'
        ];
        
        $numero_prestamo = ConsecutivosModelo::mdlGenerarNumeroPrestamo();
        $numero_recibo = ConsecutivosModelo::mdlGenerarNumeroRecibo();
        $numero_voucher = ConsecutivosModelo::mdlGenerarNumeroVoucher();
        
        echo "<p>✅ <strong>Modelo PHP funcionando:</strong></p>";
        echo "<ul>";
        echo "<li><strong>Próximo préstamo:</strong> $numero_prestamo</li>";
        echo "<li><strong>Próximo recibo:</strong> $numero_recibo</li>";
        echo "<li><strong>Próximo voucher:</strong> $numero_voucher</li>";
        echo "</ul>";
        $exitos++;
        
    } catch (Exception $e) {
        echo "<p>❌ Error probando modelo PHP: " . htmlspecialchars($e->getMessage()) . "</p>";
        $errores++;
    }

    echo "<hr>";
    echo "<h3>📊 RESUMEN DE IMPLEMENTACIÓN</h3>";
    echo "<p>✅ <strong>Operaciones exitosas:</strong> $exitos</p>";
    echo "<p>" . ($errores > 0 ? "❌" : "✅") . " <strong>Errores:</strong> $errores</p>";

    if ($errores === 0) {
        echo "<div style='background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>🎉 ¡IMPLEMENTACIÓN COMPLETADA EXITOSAMENTE!</h4>";
        echo "<p>El sistema de consecutivos por sucursal está completamente funcional.</p>";
        echo "<p><strong>Características implementadas:</strong></p>";
        echo "<ul>";
        echo "<li>✅ Consecutivos automáticos por sucursal</li>";
        echo "<li>✅ Nomenclatura personalizada: LE001-xxxxxxxx, CH001-xxxxxxxx</li>";
        echo "<li>✅ Stored procedures completos</li>";
        echo "<li>✅ Vista de consulta v_consecutivos_sucursales</li>";
        echo "<li>✅ Compatibilidad total con consecutivos_modelo.php</li>";
        echo "</ul>";
        echo "</div>";

        echo "<h4>🚀 PRÓXIMOS PASOS</h4>";
        echo "<ol>";
        echo "<li><strong>Probar módulo de préstamos:</strong> <a href='vistas/prestamo.php' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>🔗 Ir a Préstamos</a></li>";
        echo "<li><strong>Verificar numeración automática</strong> al crear nuevos préstamos</li>";
        echo "<li><strong>Confirmar</strong> que cada sucursal maneja sus propios consecutivos</li>";
        echo "</ol>";
    } else {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
        echo "<h4>⚠️ IMPLEMENTACIÓN CON ADVERTENCIAS</h4>";
        echo "<p>La implementación se completó pero algunos componentes requieren atención.</p>";
        echo "<p>Revisa los errores reportados arriba y corrígelos manualmente.</p>";
        echo "</div>";
    }

} catch (Exception $e) {
    echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; margin: 20px 0; border-radius: 5px;'>";
    echo "<h3>❌ ERROR CRÍTICO EN LA IMPLEMENTACIÓN</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
}

echo "<hr>";
echo "<p><a href='index.php' style='background: #6c757d; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px;'>← Volver al Sistema</a></p>";
?> 