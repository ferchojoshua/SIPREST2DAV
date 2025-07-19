<?php
// Evitar cualquier salida antes de los headers
ob_start();

// Desactivar la visualización de errores en la salida
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Asegurarnos de que la respuesta sea JSON
header('Content-Type: application/json');

// Capturar errores fatales
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_end_clean();
        echo json_encode([
            'status' => 'error',
            'message' => 'Error interno del servidor',
            'debug_info' => $error['message']
        ]);
    }
});

// Normalizar la ruta del document root para que funcione en diferentes sistemas operativos
$documentRoot = str_replace('\\\\', '/', $_SERVER['DOCUMENT_ROOT']);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    require_once $documentRoot . "/siprest/controladores/reportes_financieros_controlador.php";
    require_once $documentRoot . "/siprest/modelos/reportes_financieros_modelo.php";
    require_once $documentRoot . "/siprest/controladores/grupos_reportes_controlador.php";
    require_once $documentRoot . "/siprest/modelos/grupos_reportes_modelo.php";
    require_once $documentRoot . "/siprest/controladores/configuracion_controlador.php";
    require_once $documentRoot . "/siprest/modelos/configuracion_modelo.php";
    require_once $documentRoot . '/siprest/utilitarios/email_config.php';
    require_once $documentRoot . '/siprest/PHPMailer/src/Exception.php';
    require_once $documentRoot . '/siprest/PHPMailer/src/PHPMailer.php';
    require_once $documentRoot . '/siprest/PHPMailer/src/SMTP.php';

    class AjaxReportesFinancieros {

        public function ajaxHandler() {
            if (isset($_POST["accion"])) {
                switch ($_POST["accion"]) {
                    case 'obtener_sucursales':
                        $this->obtenerSucursales();
                        break;
                    case 'obtener_rutas':
                        if (isset($_POST['sucursal_id'])) {
                            $this->obtenerRutas($_POST['sucursal_id']);
                        }
                        break;
                    case 'generar_reporte':
                        $this->generarReporte();
                        break;
                    case 'enviar_reporte_email':
                        $this->enviarReporteEmail();
                        break;
                    case 'buscar_clientes':
                        if (isset($_POST['busqueda'])) {
                            $this->buscarClientes($_POST['busqueda']);
                        }
                        break;
                    case 'obtener_grupos_correo':
                        $this->obtenerGruposCorreo();
                        break;
                }
            }
        }

        private function obtenerSucursales() {
            $respuesta = ControladorReportesFinancieros::ctrObtenerSucursales();
            echo json_encode($respuesta);
        }

        private function obtenerRutas($sucursal_id) {
            $respuesta = ControladorReportesFinancieros::ctrObtenerRutas($sucursal_id);
            echo json_encode($respuesta);
        }

        private function generarReporte() {
            $datos = [
                "tipo_reporte" => $_POST["tipo_reporte"],
                "fecha_inicio" => $_POST["fecha_inicio"] ?? null,
                "fecha_fin" => $_POST["fecha_fin"] ?? null,
                "sucursal_id" => $_POST["sucursal_id"] ?? null,
                "ruta_id" => $_POST["ruta_id"] ?? null,
                "cliente_id" => $_POST["cliente_id"] ?? null
            ];
            
            try {
                $respuesta = ControladorReportesFinancieros::ctrGenerarReporte($datos);
                
                // Verificar si hay error en la respuesta
                if (isset($respuesta['error'])) {
                    echo json_encode($respuesta);
                    return;
                }
                
                // Calcular totales y subtotales si el reporte contiene datos numéricos
                $respuesta_mejorada = $this->agregarTotalesYSubtotales($respuesta, $datos["tipo_reporte"]);
                
                echo json_encode($respuesta_mejorada);
            } catch (Exception $e) {
                echo json_encode([
                    'error' => 'Error al generar el reporte: ' . $e->getMessage(),
                    'tipo_error' => 'SERVER_ERROR'
                ]);
            }
        }

        /**
         * Agregar totales y subtotales a los datos del reporte
         */
        private function agregarTotalesYSubtotales($datos, $tipo_reporte) {
            if (empty($datos) || !is_array($datos)) {
                return $datos;
            }

            // Configuración de columnas numéricas por tipo de reporte
            $columnas_numericas = $this->getColumnasNumericas($tipo_reporte);
            
            if (empty($columnas_numericas)) {
                return [
                    'data' => $datos,
                    'total_registros' => count($datos)
                ];
            }

            // Calcular totales
            $totales = [];
            $contadores = [];
            
            foreach ($columnas_numericas as $columna) {
                $totales[$columna] = 0;
                $contadores[$columna] = 0;
                
                foreach ($datos as $fila) {
                    if (isset($fila[$columna]) && is_numeric($fila[$columna])) {
                        $totales[$columna] += (float)$fila[$columna];
                        $contadores[$columna]++;
                    }
                }
            }

            // Calcular promedios
            $promedios = [];
            foreach ($totales as $columna => $total) {
                $promedios[$columna] = $contadores[$columna] > 0 ? $total / $contadores[$columna] : 0;
            }

            // Obtener datos de empresa para el encabezado
            $datos_empresa = $this->obtenerDatosEmpresa();

            return [
                'data' => $datos,
                'totales' => $totales,
                'promedios' => $promedios,
                'total_registros' => count($datos),
                'empresa' => $datos_empresa,
                'tipo_reporte' => $tipo_reporte,
                'fecha_generacion' => date('Y-m-d H:i:s'),
                'resumen' => $this->generarResumenReporte($datos, $totales, $tipo_reporte)
            ];
        }

        /**
         * Obtener columnas numéricas según el tipo de reporte
         */
        private function getColumnasNumericas($tipo_reporte) {
            $configuracion = [
                'clientes_mora' => ['Monto Vencido', 'Días de Mora'],
                'mora_por_colector' => ['Cuotas Vencidas', 'Monto en Mora', 'Promedio Dias Mora'],
                'mora_por_ruta' => ['Cuotas Vencidas', 'Monto en Mora'],
                'mora_por_sucursal' => ['Cuotas Vencidas', 'Monto en Mora', 'Promedio Dias Mora'],
                'pagos_del_dia' => ['Monto Pagado'],
                'pendientes_del_dia' => ['Monto Cuota'],
                'cobranza_por_colector' => ['Pagos Recibidos', 'Total Cobrado', 'Promedio por Pago'],
                'prestamos_vigentes' => ['Monto Prestamo', 'Total Cuotas', 'Cuotas Pagadas', 'Cuotas Pendientes'],
                'prestamos_vencidos' => ['Monto Prestamo', 'Saldo Pendiente'],
                'prestamos_del_dia' => ['Monto'],
                'prestamos_por_sucursal' => ['Total Prestamos', 'Monto Total', 'Monto Promedio'],
                'saldos_pendientes' => ['Saldo Pendiente', 'Cuotas Pendientes'],
                'historial_pagos' => ['Monto'],
                'resumen_cartera' => ['Préstamos Activos', 'Cartera Total', 'Finalizados', 'Clientes Activos', 'Promedio']
            ];

            return $configuracion[$tipo_reporte] ?? [];
        }

        /**
         * Generar resumen ejecutivo del reporte
         */
        private function generarResumenReporte($datos, $totales, $tipo_reporte) {
            $resumen = [
                'total_registros' => count($datos),
                'tipo_reporte' => $tipo_reporte,
                'descripcion' => $this->getDescripcionReporte($tipo_reporte)
            ];

            // Agregar métricas específicas según el tipo de reporte
            switch ($tipo_reporte) {
                case 'clientes_mora':
                    $resumen['clientes_en_mora'] = count($datos);
                    $resumen['monto_total_mora'] = $totales['Monto Vencido'] ?? 0;
                    break;
                    
                case 'pagos_del_dia':
                    $resumen['total_pagos'] = count($datos);
                    $resumen['monto_total_cobrado'] = $totales['Monto Pagado'] ?? 0;
                    break;
                    
                case 'prestamos_vigentes':
                    $resumen['prestamos_activos'] = count($datos);
                    $resumen['cartera_total'] = $totales['Monto Prestamo'] ?? 0;
                    break;
            }

            return $resumen;
        }

        /**
         * Obtener descripción del reporte
         */
        private function getDescripcionReporte($tipo_reporte) {
            $descripciones = [
                'clientes_mora' => 'Lista de clientes con pagos vencidos',
                'mora_por_colector' => 'Análisis de mora agrupado por colector',
                'mora_por_ruta' => 'Análisis de mora agrupado por ruta',
                'mora_por_sucursal' => 'Análisis de mora agrupado por sucursal',
                'pagos_del_dia' => 'Pagos recibidos en el día especificado',
                'pendientes_del_dia' => 'Cuotas pendientes del día',
                'cobranza_por_colector' => 'Resumen de cobranza por colector',
                'prestamos_vigentes' => 'Lista de préstamos activos',
                'prestamos_vencidos' => 'Préstamos con cuotas vencidas',
                'prestamos_del_dia' => 'Préstamos otorgados en el día',
                'prestamos_por_sucursal' => 'Resumen de préstamos por sucursal',
                'saldos_pendientes' => 'Saldos pendientes de pago',
                'historial_pagos' => 'Historial cronológico de pagos',
                'resumen_cartera' => 'Resumen ejecutivo de la cartera'
            ];

            return $descripciones[$tipo_reporte] ?? 'Reporte personalizado';
        }

        /**
         * Obtener datos básicos de la empresa
         */
        private function obtenerDatosEmpresa() {
            try {
                $empresa = ConfiguracionControlador::ctrObtenerDataEmpresa();
                
                return [
                    'nombre' => $empresa->confi_razon ?? 'Sistema de Préstamos',
                    'ruc' => $empresa->confi_ruc ?? 'No configurado',
                    'direccion' => $empresa->confi_direccion ?? 'Dirección no configurada',
                    'telefono' => $empresa->config_celular ?? 'No configurado',
                    'email' => $empresa->config_correo ?? 'admin@sistema.com',
                    'logo' => $empresa->config_logo ?? null
                ];
            } catch (Exception $e) {
                return [
                    'nombre' => 'Sistema de Préstamos',
                    'ruc' => 'No configurado',
                    'direccion' => 'Dirección no configurada',
                    'telefono' => 'No configurado',
                    'email' => 'admin@sistema.com',
                    'logo' => null
                ];
            }
        }

        private function buscarClientes($busqueda) {
            $respuesta = ControladorReportesFinancieros::ctrBuscarClientes($busqueda);
            echo json_encode($respuesta);
        }

        private function obtenerGruposCorreo() {
            $respuesta = ControladorGruposReportes::ctrMostrarGrupos(null, null);
            echo json_encode($respuesta);
        }

        private function enviarReporteEmail() {
            if (EMAIL_ACTIVO !== true || (defined('SMTP_HOST') && SMTP_HOST === 'smtp.example.com')) {
                echo json_encode(['error' => 'La configuración de envío de correo no está activa o completa. Revise utilitarios/email_config.php']);
                return;
            }

            $grupoId = $_POST['grupo_id'];
            $asunto = $_POST['asunto'];
            $mensaje = $_POST['mensaje'];
            $reporteData = json_decode($_POST['reporte_data'], true);
            $reporteColumnas = json_decode($_POST['reporte_columnas'], true);
            $reporteTitulo = $_POST['reporte_titulo'];

            $miembros = ControladorGruposReportes::ctrMostrarMiembros('grupo_id', $grupoId);

            if (empty($miembros)) {
                echo json_encode(['error' => 'El grupo seleccionado no tiene miembros.']);
                return;
            }

            // Obtener datos de la empresa
            $empresa = $this->obtenerDatosEmpresa();
            
            // Crear el cuerpo del correo en formato HTML con la tabla del reporte
            $htmlBody = '<h2>' . htmlspecialchars($empresa['nombre']) . '</h2>';
            $htmlBody .= '<h4>' . htmlspecialchars($reporteTitulo) . '</h4>';
            $htmlBody .= '<p>' . nl2br(htmlspecialchars($mensaje)) . '</p><hr>';
            $htmlBody .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">';
            $htmlBody .= '<thead><tr style="background-color: #3c8dbc; color: white;">';
            foreach ($reporteColumnas as $columna) {
                $htmlBody .= '<th>' . htmlspecialchars($columna) . '</th>';
            }
            $htmlBody .= '</tr></thead><tbody>';
            foreach ($reporteData as $fila) {
                $htmlBody .= '<tr>';
                foreach ($fila as $celda) {
                    $htmlBody .= '<td>' . htmlspecialchars($celda) . '</td>';
                }
                $htmlBody .= '</tr>';
            }
            $htmlBody .= '</tbody></table><hr>';
            $htmlBody .= '<p style="font-size: 10px; color: #777;">Reporte generado el ' . date('d/m/Y H:i:s') . '</p>';
            $htmlBody .= '<p style="font-size: 10px; color: #777;">' . htmlspecialchars($empresa['direccion']) . ' | ' . htmlspecialchars($empresa['telefono']) . ' | ' . htmlspecialchars($empresa['email']) . '</p>';

            $mail = new PHPMailer(true);
            try {
                // Configuración del servidor
                $mail->SMTPDebug = 0;  // Desactivamos el debug ya que el correo funciona
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USERNAME;
                $mail->Password   = SMTP_PASSWORD;
                $mail->SMTPSecure = SMTP_SECURE;
                $mail->Port       = SMTP_PORT;
                $mail->CharSet    = 'UTF-8';

                // Remitente
                $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);

                // Destinatarios
                $destinatariosValidos = 0;
                foreach ($miembros as $miembro) {
                    try {
                        $mail->addAddress($miembro['miembro_email'], $miembro['miembro_nombre']);
                        $destinatariosValidos++;
                    } catch (Exception $e) {
                        // Log error de destinatario inválido pero continuar con los demás
                        error_log("Error al agregar destinatario {$miembro['miembro_email']}: " . $e->getMessage());
                    }
                }
                
                if ($destinatariosValidos === 0) {
                    echo json_encode(['status' => 'error', 'message' => 'No se encontraron destinatarios válidos']);
                    return;
                }
                
                // Contenido
                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body    = $htmlBody;
                $mail->AltBody = 'Para ver este reporte, por favor, use un cliente de correo compatible con HTML.';

                // Intentar enviar el correo
                if(!$mail->send()) {
                    throw new Exception($mail->ErrorInfo);
                }

                // Si llegamos aquí, el correo se envió correctamente
                echo json_encode([
                    'status' => 'success',
                    'message' => 'El correo ha sido enviado correctamente a ' . $destinatariosValidos . ' destinatario(s).',
                    'destinatarios' => $destinatariosValidos
                ]);

            } catch (Exception $e) {
                // Capturar cualquier error y enviar una respuesta estructurada
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al enviar el correo',
                    'debug_info' => $e->getMessage()
                ]);
            }
        }
    }

    $ajax = new AjaxReportesFinancieros();
    $ajax->ajaxHandler();
} catch (Exception $e) {
    // Capturar errores de require_once o inicialización
    echo json_encode([
        'status' => 'error',
        'message' => 'Error de inicialización del sistema',
        'debug_info' => $e->getMessage()
    ]);
}
?> 