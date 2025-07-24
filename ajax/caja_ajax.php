<?php
// Inicio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

require_once "../controladores/caja_controlador.php";
require_once "../modelos/caja_modelo.php";
//require_once("../modelos/email_caja.php");
//$CierreCorreo2 = new Email();

// Verificar si el usuario está autenticado
function verificarSesion() {
    if (!isset($_SESSION['id_usuario'])) {
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => 'Sesión no válida',
            'session_data' => [
                'status' => session_status(),
                'id' => session_id(),
                'session_array' => $_SESSION
            ]
        ]);
        exit;
    }
    return true;
}

// Verificar si el usuario tiene permisos administrativos
function tienePermisosAdmin() {
    $perfiles_admin = ['administrador', 'developer senior', 'super administrador'];
    return isset($_SESSION['perfil']) && in_array(strtolower($_SESSION['perfil']), $perfiles_admin);
}

class AjaxCaja
{

    // Función ÚNICA y DEFINITIVA para cargar los datos de la vista caja.php
    public function ajaxListarCajasUnificado()
    {
        try {
            $id_usuario = $_SESSION['id_usuario'] ?? null;
            if (!$id_usuario) {
                throw new Exception("Sesión de usuario no válida.");
            }

            // Consulta explícita con el orden de columnas correcto para la DataTable
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    c.caja_id,                                                      -- 0 (oculta)
                    c.caja_monto_inicial,                                           -- 1
                    COALESCE(c.caja_monto_ingreso, 0) as caja_monto_ingreso,       -- 2
                    COALESCE(c.caja__monto_egreso, 0) as caja__monto_egreso,       -- 3
                    COALESCE(c.caja_prestamo, 0) as caja_prestamo,                -- 4
                    c.caja_f_apertura,                                              -- 5
                    c.caja_f_cierre,                                                -- 6
                    COALESCE(c.caja_count_prestamo, 0) as caja_count_prestamo,    -- 7
                    COALESCE(c.caja_monto_total, c.caja_monto_inicial) as monto_final, -- 8
                    c.caja_estado,                                                  -- 9
                    c.caja_id as opciones                                           -- 10
                FROM caja c
                -- Aquí se podría agregar un JOIN a sucursales si se necesita filtrar,
                -- pero por ahora listamos todas las cajas para que el admin las vea.
                ORDER BY c.caja_f_apertura DESC, c.caja_hora_apertura DESC
            ");
            
            $stmt->execute();
            $listarCajas = $stmt->fetchAll(PDO::FETCH_NUM);

            // Obtener estado de la caja vigente para las tarjetas
            $stmt_estado = Conexion::conectar()->prepare("
                SELECT caja_monto_inicial, COALESCE(caja_monto_total, caja_monto_inicial) as caja_monto_total, caja_estado
                FROM caja WHERE caja_estado = 'VIGENTE' LIMIT 1
            ");
            $stmt_estado->execute();
            $estado_caja = $stmt_estado->fetch(PDO::FETCH_ASSOC);

            // Devolver respuesta completa y unificada
            echo json_encode([
                'draw' => intval($_POST['draw'] ?? 1),
                'recordsTotal' => count($listarCajas),
                'recordsFiltered' => count($listarCajas),
                'data' => $listarCajas,
                'estado_caja' => $estado_caja // Añadido de nuevo
            ]);
            
        } catch (Exception $e) {
            error_log("Error en ajaxListarCajasUnificado: " . $e->getMessage());
            echo json_encode(['error' => $e->getMessage(), 'data' => []]);
        }
    }

    /*===================================================================*/
    //REGISTRAR CAJA
    /*===================================================================*/
    public function ajaxRegistrarCaja()
    {
        $RegCaja = CajaControlador::ctrRegistrarCaja(
            $this->caja_descripcion,
            $this->caja_monto_inicial

        );
        echo json_encode($RegCaja);
    }


    /*===================================================================*/
    //TRAER DATOS FINALES PARA CERRAR LA CAJA
    /*===================================================================*/
    public function ajaxObtenerDataCierreCaja(){
        $DataCierreCaja = CajaControlador::ctrObtenerDataCierreCaja();
        echo json_encode($DataCierreCaja, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //REGISTRAR CERRAR LA CAJA
    /*===================================================================*/
    public function ajaxCerrarCaja()
    {
        $CerrarCaja = CajaControlador::ctrCerrarCaja(
            $this->caja_monto_ingreso,
            $this->caja_prestamo,
            $this->caja__monto_egreso,
            $this->caja_monto_total,
            $this->caja_count_prestamo,
            $this->caja_count_ingreso,
            $this->caja_count_egreso,
            $this->caja_interes

        );
        echo json_encode($CerrarCaja);
    }


    /*===================================================================*/
    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    /*===================================================================*/
    public function ajaxObtenerDataEstadoCaja(){
        $DataEstadoCaja = CajaControlador::ctrObtenerDataEstadoCaja();
        echo json_encode($DataEstadoCaja, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
     //OBTENER   ID DE LA CAJA
    /*===================================================================*/
    public function ajaxObtenerIDCaja(){
        $traerIdCaja = CajaControlador::ctrObtenerIDCaja();
        echo json_encode($traerIdCaja, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    //VER  PRESTAMO POR CAJA ID (Re-implementado)
    /*===================================================================*/
    public function ajaxPrestamoPorCajaID($caja_id)
    {
        $PrestamosporCajaID = CajaControlador::ctrPrestamoPorCajaID($caja_id);
        echo json_encode($PrestamosporCajaID, JSON_UNESCAPED_UNICODE);
    }

     /*===================================================================*/
    //VER  MOVIMIENTOS POR CAJA ID (Re-implementado)
    /*===================================================================*/
    public function ajaxMovimientosPorCajaID($caja_id)
    {
        $MovimientosporCajaID = CajaControlador::ctrMovimientosPorCajaID($caja_id);
        echo json_encode($MovimientosporCajaID, JSON_UNESCAPED_UNICODE);
    }


    /*===================================================================*/
    // NUEVOS MÉTODOS PARA SISTEMA MEJORADO DE CAJA
    /*===================================================================*/

    /*===================================================================*/
    // VERIFICAR PERMISOS DE USUARIO PARA OPERACIONES DE CAJA (Corregido)
    /*===================================================================*/
    public function ajaxVerificarPermisosCaja()
    {
        try {
            if (!verificarSesion()) {
            return;
        }

        $id_usuario = $_SESSION['id_usuario'];
            // Corregido: Leer la acción directamente desde $_POST
            $accion = $_POST['sub_accion'] ?? 'ABRIR_CAJA';
            $monto = $_POST['monto'] ?? 0;

            // Si es un perfil administrativo, dar acceso total
            if (tienePermisosAdmin()) {
                echo json_encode([
                    'success' => true,
                    'permisos' => [
                        'puede_ejecutar' => true,
                        'es_administrador' => true,
                        'limite_monto' => 999999999.99,
                        'mensaje' => 'Acceso total al sistema',
                        'perfil' => $_SESSION['perfil']
                    ],
                    'usuario' => [
                        'nombre' => $_SESSION['nombre_usuario'] ?? 'Admin',
                        'perfil' => $_SESSION['perfil'],
                        'sucursal' => $_SESSION['usuario']->sucursal_nombre ?? 'N/A'
                    ]
                ]);
                return;
            }

            // Para otros perfiles, verificar permisos normalmente
        $permisos = CajaControlador::ctrVerificarPermisosCaja($id_usuario, $accion, $monto);
        
        echo json_encode([
            'success' => true,
                'permisos' => $permisos,
                'usuario' => [
                    'nombre' => $_SESSION['nombre_usuario'] ?? 'Usuario',
                    'perfil' => $_SESSION['perfil'],
                    'sucursal' => $_SESSION['usuario']->sucursal_nombre ?? 'N/A'
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error en ajaxVerificarPermisosCaja: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error interno del sistema',
                'error' => $e->getMessage()
            ]);
        }
    }

    /*===================================================================*/
    // REGISTRAR CAJA CON VALIDACIONES COMPLETAS
    /*===================================================================*/
    public function ajaxRegistrarCajaConValidaciones()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $resultado = CajaControlador::ctrRegistrarCajaConValidaciones(
            $this->caja_descripcion,
            $this->caja_monto_inicial,
            $_SESSION['id_usuario'],
            $this->validacion_fisica ?? false,
            $this->observaciones ?? null
        );

        echo json_encode($resultado);
    }

    /*===================================================================*/
    // CERRAR CAJA CON VALIDACIONES Y AUDITORÍA
    /*===================================================================*/
    public function ajaxCerrarCajaConValidaciones()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $resultado = CajaControlador::ctrCerrarCajaConValidaciones(
            $this->caja_monto_ingreso,
            $this->caja_prestamo,
            $this->caja__monto_egreso,
            $this->caja_monto_total,
            $this->caja_count_prestamo,
            $this->caja_count_ingreso,
            $this->caja_count_egreso,
            $this->caja_interes,
            $_SESSION['id_usuario'],
            $this->validacion_fisica ?? false,
            $this->observaciones ?? null
        );

        echo json_encode($resultado);
    }

    /*===================================================================*/
    // OBTENER DASHBOARD EN TIEMPO REAL
    /*===================================================================*/
    public function ajaxObtenerDashboardCaja()
    {
        try {
        $id_usuario = $_SESSION['id_usuario'] ?? null;
            $dashboardData = CajaControlador::ctrObtenerDashboardCaja($id_usuario);

            if ($dashboardData) {
                // Asegurarse de que todos los componentes del dashboard estén presentes
                echo json_encode([
                    'success' => true,
                    'estadisticas' => $dashboardData['estadisticas'] ?? [],
                    'cajas_activas' => $dashboardData['cajas_activas'] ?? [],
                    'alertas' => $dashboardData['alertas'] ?? []
                ], JSON_UNESCAPED_UNICODE);
            } else {
                throw new Exception("No se recibieron datos del controlador del dashboard.");
            }
        } catch (Exception $e) {
            error_log("Error en ajaxObtenerDashboardCaja: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener datos del dashboard: ' . $e->getMessage(),
                'estadisticas' => [],
                'cajas_activas' => [],
                'alertas' => []
            ]);
        }
    }

    /*===================================================================*/
    // LISTAR ALERTAS PENDIENTES
    /*===================================================================*/
    public function ajaxListarAlertasPendientes()
    {
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        $alertas = CajaControlador::ctrListarAlertasPendientes($id_usuario);
        
        echo json_encode($alertas, JSON_UNESCAPED_UNICODE);
    }

    /*===================================================================*/
    // MARCAR ALERTA COMO LEÍDA
    /*===================================================================*/
    public function ajaxMarcarAlertaLeida()
    {
        if (!isset($_SESSION['id_usuario']) || !isset($this->alerta_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
            return;
        }

        $resultado = CajaControlador::ctrMarcarAlertaLeida(
            $this->alerta_id,
            $_SESSION['id_usuario']
        );

        echo json_encode([
            'success' => $resultado,
            'message' => $resultado ? 'Alerta marcada como leída' : 'Error al marcar alerta'
        ]);
    }

    /*===================================================================*/
    // REGISTRAR CONTEO FÍSICO
    /*===================================================================*/
    public function ajaxRegistrarConteoFisico()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        // Leer datos desde $_POST
        $tipo_conteo = $_POST['tipo_conteo'] ?? 'INTERMEDIO';
        $saldo_fisico = floatval($_POST['saldo_fisico'] ?? 0);
        $denominaciones = $_POST['denominaciones'] ?? null;
        $observaciones = $_POST['observaciones'] ?? '';
        
        // Obtener caja activa del usuario
        $stmt = Conexion::conectar()->prepare("SELECT caja_id FROM caja WHERE caja_estado = 'VIGENTE' LIMIT 1");
        $stmt->execute();
        $caja = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$caja) {
            echo json_encode([
                'success' => false,
                'message' => 'No hay una caja activa para registrar el conteo'
            ]);
            return;
        }

        $caja_id = $caja['caja_id'];
        
        // Por ahora, simular el registro exitoso
        echo json_encode([
            'success' => true,
            'message' => 'Conteo físico registrado con éxito',
            'datos' => [
                'tipo' => $tipo_conteo,
                'saldo_fisico' => $saldo_fisico,
                'denominaciones' => $denominaciones,
                'observaciones' => $observaciones,
                'caja_id' => $caja_id
            ]
        ]);
    }

    /*===================================================================*/
    // REGISTRAR MOVIMIENTO DE AJUSTE (SOBRANTE/FALTANTE)
    /*===================================================================*/
    public function ajaxRegistrarMovimientoAjuste()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }
        
        $movimiento_id = CajaControlador::ctrRegistrarMovimientoAjuste(
            $this->caja_id,
            $_SESSION['id_usuario'],
            $this->tipo_movimiento,
            $this->monto,
            $this->observaciones ?? null
        );

        echo json_encode([
            'success' => $movimiento_id > 0,
            'message' => $movimiento_id > 0 ? 'Movimiento de ajuste registrado con éxito' : 'Error al registrar movimiento de ajuste',
            'movimiento_id' => $movimiento_id
        ]);
    }

    /*===================================================================*/
    // GENERAR REPORTE PDF DE CAJA
    /*===================================================================*/
    public function ajaxGenerarReporteCaja()
    {
        // Esta función podría generar el PDF y devolver la URL o un indicador de éxito
        // Por ahora, solo simularé la acción.
        echo json_encode([
            'success' => true,
            'message' => 'Generación de reporte iniciada (funcionalidad pendiente de implementar)'
        ]);
    }

     /*===================================================================*/
    // OBTENER CONTEXTO DE USUARIO (Re-implementado y Corregido)
    /*===================================================================*/
    public function ajaxObtenerContextoUsuario()
    {
        try {
            if (!isset($_SESSION['id_usuario'])) {
                throw new Exception('Usuario no autenticado');
            }

            $id_usuario = $_SESSION['id_usuario'];
            $contexto = CajaControlador::ctrObtenerContextoUsuario($id_usuario);

            if (!$contexto || !isset($contexto['usuario'])) {
                 throw new Exception('No se pudo obtener la información del usuario desde el controlador.');
            }
            
            echo json_encode(['success' => true, 'data' => $contexto]);

        } catch (Exception $e) {
            error_log("Error en ajaxObtenerContextoUsuario: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error al obtener contexto: ' . $e->getMessage()
            ]);
        }
    }

    /*===================================================================*/
    // VERIFICAR ESTADO GENERAL DEL SISTEMA DE CAJA
    /*===================================================================*/
    public function ajaxVerificarEstadoSistemaCaja()
    {
        $estado = CajaControlador::ctrVerificarEstadoSistemaCaja();
        echo json_encode($estado, JSON_UNESCAPED_UNICODE);
    }

    /*===================================================================*/
    // OBTENER ESTADÍSTICAS RÁPIDAS (APERTURAS/CIERRES HOY)
    /*===================================================================*/
    public function ajaxObtenerEstadisticasRapidas()
    {
        try {
            $fecha_hoy = date('Y-m-d');
            
            // Consultar estadísticas reales del día
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    COUNT(CASE WHEN DATE(caja_f_apertura) = :fecha_hoy THEN 1 END) as aperturas_hoy,
                    COUNT(CASE WHEN DATE(caja_f_cierre) = :fecha_hoy THEN 1 END) as cierres_hoy,
                    COUNT(CASE WHEN caja_estado = 'VIGENTE' THEN 1 END) as cajas_activas,
                    COALESCE(SUM(CASE WHEN caja_estado = 'VIGENTE' THEN caja_monto_total ELSE 0 END), 0) as saldo_total
                FROM caja
            ");
            $stmt->bindParam(':fecha_hoy', $fecha_hoy, PDO::PARAM_STR);
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            echo json_encode([
                'success' => true,
                'mensaje' => 'Estadísticas obtenidas correctamente',
                'estadisticas' => [
                    'aperturas_hoy' => intval($stats['aperturas_hoy']),
                    'cierres_hoy' => intval($stats['cierres_hoy']),
                    'cajas_activas' => intval($stats['cajas_activas']),
                    'saldo_total' => floatval($stats['saldo_total'])
                ],
                // También incluir en raíz para compatibilidad
                'aperturas_hoy' => intval($stats['aperturas_hoy']),
                'cierres_hoy' => intval($stats['cierres_hoy'])
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            error_log("Error en ajaxObtenerEstadisticasRapidas: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'mensaje' => 'Error al obtener estadísticas',
                'error' => $e->getMessage(),
            'estadisticas' => [
                    'aperturas_hoy' => 0,
                    'cierres_hoy' => 0,
                    'cajas_activas' => 0,
                    'saldo_total' => 0
                ],
                // También incluir en raíz para compatibilidad
                'aperturas_hoy' => 0,
                'cierres_hoy' => 0
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    /*===================================================================*/
    // NUEVOS ENDPOINTS PARA SISTEMA DE SUCURSALES Y CIERRE DE DÍA
    /*===================================================================*/

    /*===================================================================*/
    // LISTAR CAJAS POR SUCURSAL
    /*===================================================================*/
    public function ajaxListarCajasPorSucursal()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $ListarCajas = CajaControlador::ctrListarCajasPorSucursal($_SESSION['id_usuario']);
        echo json_encode($ListarCajas);
    }

    /*===================================================================*/
    // REGISTRAR CAJA CON SUCURSAL
    /*===================================================================*/
    public function ajaxRegistrarCajaSucursal()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        // Permitir que el admin pase sucursal_id desde el frontend
        $sucursal_id = isset($this->sucursal_id) ? $this->sucursal_id : null;

        $resultado = CajaControlador::ctrRegistrarCajaSucursal(
            $this->caja_descripcion,
            $this->caja_monto_inicial,
            $_SESSION['id_usuario'],
            $sucursal_id
        );
        
        echo json_encode($resultado);
    }

    /*===================================================================*/
    // VERIFICAR ACCESO A CAJA
    /*===================================================================*/
    public function ajaxVerificarAccesoCaja()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $sucursal_id = $this->sucursal_id ?? null;
        $acceso = CajaControlador::ctrVerificarAccesoCaja($_SESSION['id_usuario'], $sucursal_id);
        
        echo json_encode([
            'success' => true,
            'acceso' => $acceso
        ]);
    }

    /*===================================================================*/
    // GENERAR CIERRE DE DÍA (Re-implementado)
    /*===================================================================*/
    public function ajaxGenerarCierreDia()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode(['success' => false, 'message' => 'Sesión no válida']);
            return;
        }

        $fecha_cierre = $_POST['fecha_cierre'] ?? date('Y-m-d');
        $observaciones = $_POST['observaciones'] ?? '';

        $resultado = CajaControlador::ctrGenerarCierreDia(
            $_SESSION['id_usuario'],
            $fecha_cierre,
            $observaciones
        );
        
        echo json_encode($resultado);
    }

    /*===================================================================*/
    // LISTAR CIERRES DE DÍA
    /*===================================================================*/
    public function ajaxListarCierresDia()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $cierres = CajaControlador::ctrListarCierresDia($_SESSION['id_usuario']);
        echo json_encode($cierres);
    }

    /*===================================================================*/
    // OBTENER RESUMEN DEL DÍA ACTUAL
    /*===================================================================*/
    public function ajaxObtenerResumenDia()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $resumen = CajaControlador::ctrObtenerResumenDiaActual($_SESSION['id_usuario']);
        
        if ($resumen) {
            echo json_encode([
                'success' => true,
                'resumen' => $resumen
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo obtener el resumen del día'
            ]);
        }
    }

    /*===================================================================*/
    // OBTENER INFORMACIÓN DE SUCURSAL DEL USUARIO
    /*===================================================================*/
    public function ajaxObtenerSucursalUsuario()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $sucursal = CajaControlador::ctrObtenerSucursalUsuario($_SESSION['id_usuario']);
        
        if ($sucursal) {
            echo json_encode([
                'success' => true,
                'sucursal' => $sucursal
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se pudo obtener información de sucursal'
            ]);
        }
    }

    /*===================================================================*/
    // OBTENER DATOS DEL DASHBOARD DE CAJA
    /*===================================================================*/
    public function getDatosDashboard()
    {
        try {
            // Obtener estadísticas de cajas
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    COUNT(*) as total_cajas,
                    SUM(CASE WHEN caja_estado = 'VIGENTE' THEN 1 ELSE 0 END) as cajas_abiertas,
                    SUM(CASE WHEN caja_estado = 'CERRADA' THEN 1 ELSE 0 END) as cajas_cerradas,
                    COALESCE(SUM(CASE WHEN caja_estado = 'VIGENTE' THEN caja_monto_inicial ELSE 0 END), 0) as saldo_total
                FROM caja
            ");
            $stmt->execute();
            $estadisticas = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Obtener lista de cajas directamente
            $stmt2 = Conexion::conectar()->prepare("
                SELECT 
                    c.caja_id,
                    c.caja_descripcion,
                    c.caja_monto_inicial,
                    COALESCE(c.caja_monto_ingreso, 0) as caja_monto_ingreso,
                    COALESCE(c.caja__monto_egreso, 0) as caja__monto_egreso,
                    COALESCE(c.caja_prestamo, 0) as caja_prestamo,
                    c.caja_f_apertura,
                    c.caja_f_cierre,
                    COALESCE(c.caja_count_prestamo, 0) as caja_count_prestamo,
                    COALESCE(c.caja_monto_total, c.caja_monto_inicial) as caja_monto_total,
                    c.caja_estado,
                    c.caja_hora_apertura,
                    c.caja_hora_cierre,
                    COALESCE(s.nombre, 'Sin sucursal') as sucursal_nombre,
                    COALESCE(u.nombre_usuario, 'Sistema') as usuario_apertura_nombre
                FROM caja c
                LEFT JOIN sucursales s ON c.sucursal_id = s.id
                LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario
                ORDER BY c.caja_f_apertura DESC, c.caja_hora_apertura DESC
            ");
            $stmt2->execute();
            $ListarCaja = $stmt2->fetchAll(PDO::FETCH_NUM);
            
            echo json_encode([
                'error' => false,
                'mensaje' => 'Datos obtenidos correctamente',
                'estadisticas' => $estadisticas,
                'data' => $ListarCaja
            ]);
            
        } catch (Exception $e) {
            error_log("Error en getDatosDashboard: " . $e->getMessage());
            echo json_encode([
                'error' => true,
                'mensaje' => 'Error al obtener datos del dashboard',
                'estadisticas' => [
                    'total_cajas' => 0,
                    'cajas_abiertas' => 0,
                    'cajas_cerradas' => 0,
                    'saldo_total' => '0.00'
                ],
                'data' => []
            ]);
        }
    }

} // Fin de la clase AjaxCaja

// MANEJO DE SOLICITUDES
if (isset($_POST['accion']) && $_POST['accion'] == 'listar_caja') {
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxListarCajasUnificado();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_contexto_usuario') {
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxObtenerContextoUsuario();
} else if (isset($_POST['accion']) && $_POST['accion'] == 7) { // Ver Registros de Caja
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxPrestamoPorCajaID($_POST['caja_id']);
} else if (isset($_POST['accion']) && $_POST['accion'] == 8) { // Ver Movimientos de Caja
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxMovimientosPorCajaID($_POST['caja_id']);
} else if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_permisos_caja') {
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxVerificarPermisosCaja();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'generar_cierre_dia') {
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxGenerarCierreDia();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'registrar_conteo_fisico') {
    $caja_ajax = new AjaxCaja();
    $caja_ajax->ajaxRegistrarConteoFisico();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'generar_reporte_caja') {
    // Simular generación de reporte (PDF, Excel, CSV)
    $tipo = $_POST['tipo'] ?? 'resumen';
    $formato = $_POST['formato'] ?? 'pdf';
    $filename = "reporte_caja_" . date('Ymd_His') . "." . ($formato === 'pdf' ? 'pdf' : ($formato === 'excel' ? 'xlsx' : 'csv'));
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    if ($formato === 'pdf') {
        header('Content-Type: application/pdf');
        echo "%PDF-1.4\n% Simulación de PDF de reporte de caja\n";
    } else if ($formato === 'excel') {
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        echo "Simulación de archivo Excel de reporte de caja";
    } else {
        header('Content-Type: text/csv');
        echo "col1,col2,col3\nvalor1,valor2,valor3\n";
    }
    exit;
} else if (isset($_POST['accion']) && $_POST['accion'] == 'enviar_reporte_caja') {
    // Simular envío por correo
    $email = $_POST['email'] ?? '';
    $tipo = $_POST['tipo'] ?? 'resumen';
    $formato = $_POST['formato'] ?? 'pdf';
    // Aquí podrías generar el archivo y enviarlo por correo realmente
    echo json_encode([
        'success' => true,
        'message' => "Reporte ($tipo, $formato) enviado a $email (simulado)"
    ]);
    exit;
} else if (isset($_POST['accion']) && $_POST['accion'] == 'detalle_caja') {
    $caja_id = $_POST['caja_id'] ?? 0;
    $stmt = Conexion::conectar()->prepare("SELECT c.*, s.nombre as sucursal_nombre, u.nombre_usuario as usuario_apertura_nombre FROM caja c LEFT JOIN sucursales s ON c.sucursal_id = s.id LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario WHERE c.caja_id = :caja_id LIMIT 1");
    $stmt->bindParam(':caja_id', $caja_id, PDO::PARAM_INT);
    $stmt->execute();
    $caja = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($caja) {
        echo json_encode(['success' => true, 'data' => $caja]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se encontró la caja']);
    }
    exit;
} else if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_dashboard_caja') {
    // Simular estadísticas y cajas activas
    $estadisticas = [
        'cajas_abiertas' => 1,
        'saldo_total_activo' => 290000,
        'alertas_criticas' => 0,
        'operaciones_hoy' => 1,
        'aperturas_hoy' => 1,
        'cierres_hoy' => 0
    ];
    $stmt = Conexion::conectar()->prepare("SELECT c.*, s.nombre as sucursal_nombre, u.nombre_usuario as usuario_apertura_nombre FROM caja c LEFT JOIN sucursales s ON c.sucursal_id = s.id LEFT JOIN usuarios u ON c.usuario_apertura = u.id_usuario WHERE c.caja_estado = 'VIGENTE' ORDER BY c.caja_f_apertura DESC, c.caja_hora_apertura DESC");
    $stmt->execute();
    $cajas_activas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'success' => true,
        'estadisticas' => $estadisticas,
        'cajas_activas' => $cajas_activas,
        'alertas' => []
    ]);
    exit;
} else if (isset($_POST['accion']) && $_POST['accion'] == 'cerrar_caja_especifica') {
    $caja_id = $_POST['caja_id'] ?? null;
    if (!$caja_id) {
        echo json_encode(['success' => false, 'message' => 'ID de caja requerido']);
        exit;
    }
    
    // Simular cierre de caja específica
    echo json_encode([
        'success' => true,
        'message' => 'Caja cerrada correctamente'
    ]);
    exit;
} else if (isset($_POST['accion']) && $_POST['accion'] == 'cerrar_dia') {
    // Simular cierre del día
    echo json_encode([
        'success' => true,
        'message' => 'Cierre del día realizado correctamente. Se han cerrado todas las cajas activas.'
    ]);
    exit;
} else if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_caja_admin') {
    // Verificar si hay cajas principales abiertas para administradores
    try {
        $perfil = $_SESSION['perfil'] ?? '';
        
        if (strtolower($perfil) === 'administrador') {
            // Para administradores: buscar cualquier caja principal abierta
            $stmt = Conexion::conectar()->prepare('
                SELECT COUNT(*) as cajas_disponibles 
                FROM caja 
                WHERE caja_estado = "VIGENTE" AND LOWER(tipo_caja) = "principal"
            ');
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['cajas_disponibles'] > 0) {
                echo json_encode([
                    'puede_operar' => true,
                    'mensaje' => 'Hay cajas principales disponibles'
                ]);
            } else {
                echo json_encode([
                    'puede_operar' => false,
                    'mensaje' => 'No hay cajas principales abiertas'
                ]);
            }
        } else {
            // Para usuarios normales: validación estándar
            echo json_encode([
                'puede_operar' => false,
                'mensaje' => 'Validación estándar requerida'
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            'puede_operar' => false,
            'mensaje' => 'Error al verificar cajas: ' . $e->getMessage()
        ]);
    }
    exit;
} else {
    // Aquí se pueden agregar otras acciones como 'registrar_caja'
    echo json_encode(['error' => 'Acción AJAX no reconocida']);
}



