<?php
session_start();
require_once "../controladores/notas_debito_controlador.php";
require_once "../modelos/admin_prestamos_modelo.php";

// Activar el reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para registrar errores en un archivo de log
function logError($message) {
    $logFile = __DIR__ . '/debug.log';
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
}

header('Content-Type: application/json');

class AjaxNotasDebito
{
    /*===================================================================*/
    // VALIDAR PRÉSTAMO PARA RECÁLCULO
    /*===================================================================*/
    public function ajaxValidarPrestamo()
    {
        $validacion = NotasDebitoControlador::ctrValidarPrestamoParaRecalculo($this->nro_prestamo);
        echo json_encode($validacion);
    }

    /*===================================================================*/
    // CREAR NOTA DE DÉBITO
    /*===================================================================*/
    public function ajaxCrearNotaDebito()
    {
        $resultado = NotasDebitoControlador::ctrCrearNotaDebito(
            $this->nro_prestamo,
            $this->nuevo_interes,
            $this->nuevas_cuotas,
            $this->motivo,
            $this->id_usuario
        );
        echo json_encode($resultado);
    }

    /*===================================================================*/
    // LISTAR NOTAS DE DÉBITO EN DATATABLE
    /*===================================================================*/
    public function ajaxListarNotasDebito()
    {
        $notas = NotasDebitoControlador::ctrListarNotasDebito();
        
        if (count($notas) > 0) {
            $datos = [];
            foreach ($notas as $nota) {
                $sub_array = array();
                $sub_array[] = $nota["nro_nota_debito"];
                $sub_array[] = $nota["nro_prestamo"];
                $sub_array[] = $nota["cliente_nombres"];
                $sub_array[] = date('d/m/Y', strtotime($nota["fecha_registro"]));
                $sub_array[] = $nota["motivo"];
                $sub_array[] = $nota["moneda_simbolo"] . ' ' . number_format($nota["saldo_capital"], 2);
                $sub_array[] = $nota["moneda_simbolo"] . ' ' . number_format($nota["cuota_nueva"], 2);
                $sub_array[] = $nota["usuario"];
                $sub_array[] = '<div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary btnVerNotaDebito" data-nota="' . $nota["nro_nota_debito"] . '" title="Ver Nota de Débito">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-success btnImprimirNotaDebito" data-nota="' . $nota["nro_nota_debito"] . '" title="Imprimir Nota">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>';
                $datos[] = $sub_array;
            }

            $tabla = array(
                'draw' => 1,
                'recordsTotal' => count($datos),
                'recordsFiltered' => count($datos),
                'data' => $datos
            );

            echo json_encode($tabla);
        } else {
            echo json_encode(array(
                'draw' => 1,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ));
        }
    }
}

try {
    if (!isset($_POST['accion'])) {
        throw new Exception('No se especificó ninguna acción');
    }

    $accion = $_POST['accion'];
    logError("Acción recibida: " . $accion);

    switch ($accion) {
        case 'listar_notas':
            $notas = NotasDebitoControlador::ctrListarNotasDebito();
            logError("Notas obtenidas: " . print_r($notas, true));

            if (!is_array($notas)) {
                $notas = array();
            }

            // Asegurar que cada nota tenga todos los campos necesarios
            $notasFormateadas = array();
            foreach ($notas as $nota) {
                $notaFormateada = array(
                    'nro_nota' => isset($nota['nro_nota']) ? $nota['nro_nota'] : '',
                    'fecha' => isset($nota['fecha_registro']) ? date('d/m/Y H:i', strtotime($nota['fecha_registro'])) : '',
                    'nro_prestamo' => isset($nota['nro_prestamo']) ? $nota['nro_prestamo'] : '',
                    'cliente' => isset($nota['cliente']) ? $nota['cliente'] : '',
                    'monto_original' => isset($nota['monto_original']) ? number_format(floatval($nota['monto_original']), 2) : '0.00',
                    'nuevo_monto' => isset($nota['nuevo_monto']) ? number_format(floatval($nota['nuevo_monto']), 2) : '0.00',
                    'motivo' => isset($nota['motivo']) ? $nota['motivo'] : '',
                    'estado' => isset($nota['estado']) ? $nota['estado'] : 'ACTIVO'
                );
                $notasFormateadas[] = $notaFormateada;
            }

            $response = array('data' => $notasFormateadas);
            logError("Respuesta formateada: " . json_encode($response));
            echo json_encode($response);
            break;

        case 'registrar_nota':
            if (!isset($_POST['nro_prestamo']) || !isset($_POST['nuevo_monto']) || !isset($_POST['motivo'])) {
                throw new Exception('Faltan parámetros requeridos');
            }
            
            $nro_prestamo = $_POST['nro_prestamo'];
            $nuevo_monto = $_POST['nuevo_monto'];
            $motivo = $_POST['motivo'];

            $resultado = NotasDebitoControlador::ctrRegistrarNotaDebito($nro_prestamo, $nuevo_monto, $motivo);
            echo json_encode(array('status' => ($resultado === 'ok'), 'mensaje' => $resultado));
            break;

        case 'anular_nota':
            if (!isset($_POST['nro_nota'])) {
                throw new Exception('Falta el número de nota');
            }
            
            $nro_nota = $_POST['nro_nota'];
            $resultado = NotasDebitoControlador::ctrAnularNotaDebito($nro_nota);
            echo json_encode(array('status' => ($resultado === 'ok'), 'mensaje' => $resultado));
            break;

        case 'obtener_nota':
            if (!isset($_POST['nro_nota'])) {
                throw new Exception('Falta el número de nota');
            }
            
            $nro_nota = $_POST['nro_nota'];
            $nota = NotasDebitoControlador::ctrObtenerNotaDebito($nro_nota);
            if (!$nota) {
                throw new Exception('Nota no encontrada');
            }
            echo json_encode(array('status' => true, 'data' => $nota));
            break;

        default:
            throw new Exception('Acción no válida');
    }
} catch (Exception $e) {
    logError("Error: " . $e->getMessage());
    logError("Stack trace: " . $e->getTraceAsString());
    echo json_encode(array(
        'status' => false,
        'error' => $e->getMessage()
    ));
}
?> 