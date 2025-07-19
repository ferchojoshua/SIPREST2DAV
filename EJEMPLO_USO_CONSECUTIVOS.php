<?php
/**
 * EJEMPLO DE USO DEL SISTEMA DE CONSECUTIVOS POR SUCURSAL
 * =========================================================
 * 
 * Este archivo muestra cómo implementar el nuevo sistema de consecutivos
 * que automáticamente toma la sucursal del usuario logueado.
 */

// Incluir el modelo de consecutivos
require_once "modelos/consecutivos_modelo.php";

session_start(); // Asegurar que la sesión esté iniciada

echo "<h1>🏢 SISTEMA DE CONSECUTIVOS POR SUCURSAL</h1>";
echo "<h2>Usuario actual: " . ($_SESSION["usuario"]->nombre_usuario ?? "No logueado") . "</h2>";
echo "<h2>Sucursal: " . ($_SESSION["usuario"]->sucursal_id ?? "No definida") . "</h2>";

echo "<hr>";

/*=================================================================*/
// EJEMPLO 1: GENERAR NÚMEROS DE DOCUMENTO (FORMA SIMPLE)
/*=================================================================*/
echo "<h3>📄 EJEMPLO 1: Generar números de documento</h3>";

// Obtener información de la sucursal del usuario
$info_sucursal = ConsecutivosModelo::mdlObtenerInfoSucursalUsuario();
if ($info_sucursal) {
    echo "<p><strong>Sucursal:</strong> {$info_sucursal->codigo} - {$info_sucursal->nombre}</p>";
}

// Generar números sin especificar sucursal (toma automáticamente la del usuario)
$numero_prestamo = ConsecutivosModelo::mdlGenerarNumeroPrestamo();
$numero_recibo = ConsecutivosModelo::mdlGenerarNumeroRecibo();
$numero_voucher = ConsecutivosModelo::mdlGenerarNumeroVoucher();

echo "<ul>";
echo "<li><strong>Próximo préstamo:</strong> $numero_prestamo</li>";
echo "<li><strong>Próximo recibo:</strong> $numero_recibo</li>";
echo "<li><strong>Próximo voucher:</strong> $numero_voucher</li>";
echo "</ul>";

/*=================================================================*/
// EJEMPLO 2: PROCESO COMPLETO DE CREACIÓN DE PRÉSTAMO
/*=================================================================*/
echo "<h3>💰 EJEMPLO 2: Proceso completo de creación de préstamo</h3>";

// PASO 1: Obtener el número de préstamo
$numero_prestamo = ConsecutivosModelo::mdlGenerarNumeroPrestamo();
echo "<p>🔢 <strong>Paso 1:</strong> Número asignado: $numero_prestamo</p>";

// PASO 2: Simular creación del préstamo
echo "<p>💾 <strong>Paso 2:</strong> Guardando préstamo en la base de datos...</p>";
// Aquí iría la lógica para guardar el préstamo
// INSERT INTO prestamos (numero_prestamo, cliente_id, monto, ...) VALUES (...)

// PASO 3: Confirmar uso del consecutivo (incrementarlo)
$resultado_confirmacion = ConsecutivosModelo::mdlConfirmarUsoPrestamo();
if ($resultado_confirmacion) {
    echo "<p>✅ <strong>Paso 3:</strong> Consecutivo confirmado e incrementado correctamente</p>";
} else {
    echo "<p>❌ <strong>Paso 3:</strong> Error al confirmar consecutivo</p>";
}

/*=================================================================*/
// EJEMPLO 3: MANEJO DE ERRORES Y CASOS ESPECIALES
/*=================================================================*/
echo "<h3>⚠️ EJEMPLO 3: Manejo de casos especiales</h3>";

// Caso: Trabajar con sucursal específica (para administradores)
$sucursal_especifica = 1; // ID de sucursal específica
$numero_prestamo_admin = ConsecutivosModelo::mdlGenerarNumeroDocumento('prestamo', $sucursal_especifica);
echo "<p><strong>Para sucursal específica (ID: $sucursal_especifica):</strong> $numero_prestamo_admin</p>";

// Obtener todos los consecutivos de la sucursal del usuario
$consecutivos_actuales = ConsecutivosModelo::mdlObtenerConsecutivosSucursal();
if ($consecutivos_actuales) {
    echo "<h4>📊 Estado actual de consecutivos:</h4>";
    echo "<ul>";
    echo "<li><strong>Préstamos:</strong> {$consecutivos_actuales->consecutivo_prestamos}</li>";
    echo "<li><strong>Recibos:</strong> {$consecutivos_actuales->consecutivo_recibos}</li>";
    echo "<li><strong>Vouchers:</strong> {$consecutivos_actuales->consecutivo_vouchers}</li>";
    echo "</ul>";
}

/*=================================================================*/
// EJEMPLO 4: INTEGRACIÓN EN MÓDULOS EXISTENTES
/*=================================================================*/
echo "<h3>🔗 EJEMPLO 4: Cómo integrar en módulos existentes</h3>";

echo "<h4>A) En el modelo de préstamos:</h4>";
echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
echo "// En prestamos_modelo.php
static public function mdlRegistrarPrestamo(\$datos) {
    try {
        // 1. Obtener número de préstamo
        \$numero = ConsecutivosModelo::mdlGenerarNumeroPrestamo();
        
        // 2. Preparar datos
        \$datos['numero_prestamo'] = \$numero;
        
        // 3. Insertar préstamo
        \$stmt = Conexion::conectar()->prepare(\"INSERT INTO prestamos (...) VALUES (...)\");
        // ... binding de parámetros ...
        \$resultado = \$stmt->execute();
        
        // 4. Si todo salió bien, confirmar consecutivo
        if (\$resultado) {
            ConsecutivosModelo::mdlConfirmarUsoPrestamo();
            return \$numero;
        }
        
        return false;
    } catch (Exception \$e) {
        error_log(\"Error: \" . \$e->getMessage());
        return false;
    }
}";
echo "</pre>";

echo "<h4>B) En el controlador AJAX:</h4>";
echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
echo "// En prestamos_ajax.php
if (\$_POST['accion'] == 'crear_prestamo') {
    // Incluir modelo de consecutivos
    require_once '../modelos/consecutivos_modelo.php';
    
    // El número se genera automáticamente para la sucursal del usuario
    \$numero_prestamo = PrestamosModelo::mdlRegistrarPrestamo(\$_POST);
    
    if (\$numero_prestamo) {
        echo json_encode([
            'estado' => 'exito',
            'numero_prestamo' => \$numero_prestamo,
            'mensaje' => 'Préstamo creado exitosamente'
        ]);
    } else {
        echo json_encode([
            'estado' => 'error',
            'mensaje' => 'Error al crear préstamo'
        ]);
    }
}";
echo "</pre>";

/*=================================================================*/
// EJEMPLO 5: VENTAJAS DEL NUEVO SISTEMA
/*=================================================================*/
echo "<h3>✨ VENTAJAS DEL NUEVO SISTEMA</h3>";

echo "<div style='background: #e8f5e8; padding: 15px; border-radius: 5px; border-left: 4px solid #4CAF50;'>";
echo "<h4>🎯 Automatización:</h4>";
echo "<ul>";
echo "<li>No necesitas especificar la sucursal en cada llamada</li>";
echo "<li>Toma automáticamente la sucursal del usuario logueado</li>";
echo "<li>Reduce errores por sucursal incorrecta</li>";
echo "</ul>";

echo "<h4>🏢 Organización:</h4>";
echo "<ul>";
echo "<li>Cada sucursal maneja sus propios consecutivos</li>";
echo "<li>Números con formato: LE001-00000001, CHI001-00000001</li>";
echo "<li>Fácil identificación del origen del documento</li>";
echo "</ul>";

echo "<h4>🔧 Compatibilidad:</h4>";
echo "<ul>";
echo "<li>Mantiene compatibilidad con sistema anterior</li>";
echo "<li>Funciones opcionales para especificar sucursal</li>";
echo "<li>Migración gradual sin romper código existente</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<p><em>💡 Para implementar en producción, ejecutar primero el script SQL: sql/mejoras_consecutivos_por_sucursal.sql</em></p>";

?> 