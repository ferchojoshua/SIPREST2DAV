<?php

// Iniciar sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Headers
header('Content-Type: application/json; charset=utf-8');

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['estado' => 'error', 'mensaje' => 'Método no permitido']);
    exit;
}

// Verificar sesión
if (!isset($_SESSION["usuario"])) {
    echo json_encode(['estado' => 'error', 'mensaje' => 'Sesión no válida']);
    exit;
}

try {
    $accion = $_POST['accion'] ?? '';
    
    switch ($accion) {
        case 'verificar_permisos':
            // Solo permitir a administradores (perfil_id = 1)
            $es_admin = ($_SESSION["usuario"]->id_perfil_usuario == 1);
            
            echo json_encode([
                'estado' => 'ok',
                'permisos' => [
                    'puede_anular' => $es_admin,
                    'requiere_justificacion' => true,
                    'es_administrador' => $es_admin
                ]
            ]);
            break;
            
        case 'anular_pago':
            // Verificar que sea administrador
            if ($_SESSION["usuario"]->id_perfil_usuario != 1) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No tiene permisos para anular pagos'
                ]);
                exit;
            }
            
            // Validar datos
            if (empty($_POST['nro_prestamo']) || empty($_POST['nro_cuota']) || empty($_POST['motivo'])) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Datos incompletos'
                ]);
                exit;
            }
            
            $nro_prestamo = trim($_POST['nro_prestamo']);
            $nro_cuota = intval($_POST['nro_cuota']);
            $motivo = trim($_POST['motivo']);
            
            // Validar justificación
            if (strlen($motivo) < 10) {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'La justificación debe tener al menos 10 caracteres'
                ]);
                exit;
            }
            
            // Incluir el modelo de administrar préstamos para usar su funcionalidad
            require_once "../modelos/admin_prestamos_modelo.php";
            require_once "../modelos/conexion.php";
            
            try {
                // Obtener detalles del pago a anular
                $pdo = Conexion::conectar();
                
                // Verificar que la cuota esté pagada
                $stmt = $pdo->prepare("
                    SELECT pdetalle_id, pdetalle_estado_cuota, pdetalle_monto_cuota 
                    FROM prestamo_detalle 
                    WHERE nro_prestamo = ? AND pdetalle_nro_cuota = ? AND pdetalle_estado_cuota = 'pagada'
                ");
                $stmt->execute([$nro_prestamo, $nro_cuota]);
                $cuota = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (!$cuota) {
                    echo json_encode([
                        'estado' => 'error',
                        'mensaje' => 'Cuota no encontrada o no está pagada'
                    ]);
                    exit;
                }
                
                // Iniciar transacción
                $pdo->beginTransaction();
                
                // Obtener información del préstamo antes de anular
                $stmt_prestamo = $pdo->prepare("
                    SELECT pc.pres_cuotas_pagadas, pc.pres_cuotas_restante, 
                           pc.pres_monto_restante, pc.pres_estado, pc.pres_estado_caja
                    FROM prestamo_cabecera pc 
                    WHERE pc.nro_prestamo = ?
                ");
                $stmt_prestamo->execute([$nro_prestamo]);
                $prestamo_info = $stmt_prestamo->fetch(PDO::FETCH_ASSOC);
                
                // Actualizar estado de la cuota a pendiente
                $stmt = $pdo->prepare("
                    UPDATE prestamo_detalle 
                    SET pdetalle_estado_cuota = 'pendiente',
                        pdetalle_fecha_pago = NULL,
                        pdetalle_saldo_cuota = pdetalle_monto_cuota
                    WHERE pdetalle_id = ?
                ");
                $stmt->execute([$cuota['pdetalle_id']]);
                
                // ROLLBACK: Actualizar contador de cuotas pagadas y restantes
                $stmt = $pdo->prepare("
                    UPDATE prestamo_cabecera 
                    SET pres_cuotas_pagadas = pres_cuotas_pagadas - 1,
                        pres_cuotas_restante = pres_cuotas_restante + 1,
                        pres_monto_restante = pres_monto_restante + ?,
                        pres_estado = 'VIGENTE',
                        pres_estado_caja = 'VIGENTE'
                    WHERE nro_prestamo = ?
                ");
                $stmt->execute([$cuota['pdetalle_monto_cuota'], $nro_prestamo]);
                
                // Si el préstamo estaba finalizado, volverlo a vigente
                if ($prestamo_info['pres_estado'] == 'FINALIZADO') {
                    $stmt = $pdo->prepare("
                        UPDATE prestamo_cabecera 
                        SET pres_estado = 'VIGENTE',
                            pres_estado_caja = 'VIGENTE'
                        WHERE nro_prestamo = ?
                    ");
                    $stmt->execute([$nro_prestamo]);
                }
                
                // Registrar en auditoría si existe la tabla
                $stmt_check = $pdo->prepare("SHOW TABLES LIKE 'anulaciones_auditoria'");
                $stmt_check->execute();
                if ($stmt_check->fetch()) {
                    $stmt_audit = $pdo->prepare("
                        INSERT INTO anulaciones_auditoria 
                        (tipo_documento, documento_id, usuario_id, motivo, datos_originales, sucursal_id, ip_usuario)
                        VALUES (?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    $datos_originales = json_encode([
                        'nro_prestamo' => $nro_prestamo,
                        'nro_cuota' => $nro_cuota,
                        'monto_cuota' => $cuota['pdetalle_monto_cuota'],
                        'estado_anterior' => 'pagada',
                        'rollback_prestamo' => [
                            'cuotas_pagadas_antes' => $prestamo_info['pres_cuotas_pagadas'],
                            'cuotas_restante_antes' => $prestamo_info['pres_cuotas_restante'],
                            'monto_restante_antes' => $prestamo_info['pres_monto_restante'],
                            'estado_antes' => $prestamo_info['pres_estado']
                        ]
                    ]);
                    
                    $stmt_audit->execute([
                        'pago',
                        $cuota['pdetalle_id'],
                        $_SESSION["usuario"]->id_usuario,
                        $motivo,
                        $datos_originales,
                        $_SESSION["usuario"]->sucursal_id ?? 1,
                        $_SERVER['REMOTE_ADDR'] ?? 'localhost'
                    ]);
                }
                
                // Confirmar transacción
                $pdo->commit();
                
                echo json_encode([
                    'estado' => 'ok',
                    'mensaje' => 'Pago anulado correctamente. Se ha revertido el saldo del préstamo y actualizado el estado.'
                ]);
                
            } catch (Exception $e) {
                // Rollback en caso de error
                if ($pdo && $pdo->inTransaction()) {
                    $pdo->rollback();
                }
                
                error_log("Error anulando pago: " . $e->getMessage());
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'Error al anular el pago: ' . $e->getMessage()
                ]);
            }
            
            break;
            
        default:
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Acción no válida'
            ]);
    }
    
} catch (Exception $e) {
    error_log("Error en anulaciones_ajax_temp.php: " . $e->getMessage());
    echo json_encode([
        'estado' => 'error',
        'mensaje' => 'Error interno: ' . $e->getMessage()
    ]);
}
?> 