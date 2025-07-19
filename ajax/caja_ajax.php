<?php

require_once "../controladores/caja_controlador.php";
require_once "../modelos/caja_modelo.php";
//require_once("../modelos/email_caja.php");
//$CierreCorreo2 = new Email();

class AjaxCaja
{

    /*===================================================================*/
    //LISTAR CAJA EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    public function  ListarAperturaCaja()
    {
        $ListarCaja = CajaControlador::ctrListarAperturaCaja();
        echo json_encode($ListarCaja);
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
    //VER  PRESTAMO POR CAJA ID
    /*===================================================================*/
    public function ajaxPrestamoPorCajaID($caja_id)
    {
        $PrestamosporCajaID = CajaControlador::ctrPrestamoPorCajaID($caja_id);
        echo json_encode($PrestamosporCajaID, JSON_UNESCAPED_UNICODE);
    }

     /*===================================================================*/
    //VER  MOVIMIENTOS POR CAJA ID
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
    // VERIFICAR PERMISOS DE USUARIO PARA OPERACIONES DE CAJA
    /*===================================================================*/
    public function ajaxVerificarPermisosCaja()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $id_usuario = $_SESSION['id_usuario'];
        $accion = $this->accion ?? 'ABRIR_CAJA';
        $monto = $this->monto ?? 0;

        $permisos = CajaControlador::ctrVerificarPermisosCaja($id_usuario, $accion, $monto);
        
        echo json_encode([
            'success' => true,
            'permisos' => $permisos
        ]);
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
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        $dashboard = CajaControlador::ctrObtenerDashboardCaja($id_usuario);
        
        echo json_encode($dashboard, JSON_UNESCAPED_UNICODE);
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

        $conteo_id = CajaControlador::ctrRegistrarConteoFisico(
            $this->caja_id,
            $_SESSION['id_usuario'],
            $this->tipo_conteo,
            $this->saldo_sistema,
            $this->saldo_fisico,
            $this->denominaciones ?? null,
            $this->observaciones ?? null
        );
        echo json_encode([
            'success' => $conteo_id > 0,
            'message' => $conteo_id > 0 ? 'Conteo físico registrado con éxito' : 'Error al registrar conteo físico',
            'conteo_id' => $conteo_id
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
    // OBTENER CONTEXTO DE USUARIO (ROLES, PERMISOS ESPECÍFICOS)
    /*===================================================================*/
    public function ajaxObtenerContextoUsuario()
    {
        $id_usuario = $_SESSION['id_usuario'] ?? null;
        $contexto = CajaControlador::ctrObtenerContextoUsuario($id_usuario);
        echo json_encode($contexto, JSON_UNESCAPED_UNICODE);
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
        $estadisticas = CajaControlador::ctrObtenerEstadisticasRapidas();
        echo json_encode($estadisticas, JSON_UNESCAPED_UNICODE);
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

        $resultado = CajaControlador::ctrRegistrarCajaSucursal(
            $this->caja_descripcion,
            $this->caja_monto_inicial,
            $_SESSION['id_usuario']
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
    // GENERAR CIERRE DE DÍA
    /*===================================================================*/
    public function ajaxGenerarCierreDia()
    {
        if (!isset($_SESSION['id_usuario'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Sesión no válida'
            ]);
            return;
        }

        $fecha_cierre = $this->fecha_cierre ?? date('Y-m-d');
        $observaciones = $this->observaciones ?? '';

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
}

//instanciamos para que se ejecute la funcion
if (isset($_POST['accion']) && $_POST['accion'] == 1) {        //LISTAR CAJA EN DATATABLE
    $ListarCaja = new AjaxCaja();
    $ListarCaja->ListarAperturaCaja();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 2) {    //REGISTRAR CAJA
    $RegCaja = new AjaxCaja();
    $RegCaja->caja_descripcion = $_POST["caja_descripcion"];
    $RegCaja->caja_monto_inicial = $_POST["caja_monto_inicial"];
    $RegCaja->ajaxRegistrarCaja();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 3) {    //TRAER DATOS FINALES PARA CERRAR LA CAJA
    $DataCierreCaja = new AjaxCaja(); //clase
    $DataCierreCaja->ajaxObtenerDataCierreCaja();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 4) {    //REGISTRAR CERRAR LA CAJA
    $CerrarCaja = new AjaxCaja(); //clase
    $CerrarCaja->caja_monto_ingreso = $_POST["caja_monto_ingreso"];
    $CerrarCaja->caja_prestamo = $_POST["caja_prestamo"];
    $CerrarCaja->caja__monto_egreso = $_POST["caja__monto_egreso"];
    $CerrarCaja->caja_monto_total = $_POST["caja_monto_total"];
    $CerrarCaja->caja_count_prestamo = $_POST["caja_count_prestamo"];
    $CerrarCaja->caja_count_ingreso = $_POST["caja_count_ingreso"];
    $CerrarCaja->caja_count_egreso = $_POST["caja_count_egreso"];
    $CerrarCaja->caja_interes = $_POST["caja_interes"];
    $CerrarCaja->ajaxCerrarCaja();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 5) {    //ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    $DataEstadoCaja = new AjaxCaja(); //clase
    $DataEstadoCaja->ajaxObtenerDataEstadoCaja();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 6) {    //OBTENER   ID DE LA CAJA
    $traerIdCaja = new AjaxCaja(); //clase
    $traerIdCaja->ajaxObtenerIDCaja();


} else  if (isset($_POST['accion']) && $_POST['accion'] == 7) {    //VER DETALLE DL PRESTAMO EN MODAL
    $PrestamosporCajaID = new AjaxCaja(); //clase
    $PrestamosporCajaID->ajaxPrestamoPorCajaID($_POST["caja_id"]);


} else  if (isset($_POST['accion']) && $_POST['accion'] == 8) {    //VER DETALLE DL PRESTAMO EN MODAL
    $MovimientosporCajaID = new AjaxCaja(); //clase
    $MovimientosporCajaID->ajaxMovimientosPorCajaID($_POST["caja_id"]);

} else if (isset($_POST['accion']) && $_POST['accion'] == 9) {
    $estadisticasRapidas = new AjaxCaja();
    $estadisticasRapidas->ajaxObtenerEstadisticasRapidas();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'registrar_conteo_fisico') {
    $conteoFisico = new AjaxCaja();
    $conteoFisico->caja_id = $_POST['caja_id'];
    $conteoFisico->tipo_conteo = $_POST['tipo_conteo'];
    $conteoFisico->saldo_sistema = $_POST['saldo_sistema'];
    $conteoFisico->saldo_fisico = $_POST['saldo_fisico'];
    $conteoFisico->denominaciones = $_POST['denominaciones'] ?? null;
    $conteoFisico->observaciones = $_POST['observaciones'] ?? null;
    $conteoFisico->ajaxRegistrarConteoFisico();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'generar_reporte_caja') {
    $generarReporte = new AjaxCaja();
    $generarReporte->ajaxGenerarReporteCaja();


} else if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_permisos_caja') {
    $permisosCaja = new AjaxCaja();
    $permisosCaja->accion = $_POST['sub_accion'];
    $permisosCaja->monto = $_POST['monto'] ?? 0;
    $permisosCaja->ajaxVerificarPermisosCaja();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'registrar_caja_validada') {
    $registrarCajaValidada = new AjaxCaja();
    $registrarCajaValidada->caja_descripcion = $_POST['caja_descripcion'];
    $registrarCajaValidada->caja_monto_inicial = $_POST['caja_monto_inicial'];
    $registrarCajaValidada->validacion_fisica = filter_var($_POST['validacion_fisica'], FILTER_VALIDATE_BOOLEAN);
    $registrarCajaValidada->observaciones = $_POST['observaciones'];
    $registrarCajaValidada->ajaxRegistrarCajaConValidaciones();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'cerrar_caja_validada') {
    $cerrarCajaValidada = new AjaxCaja();
    $cerrarCajaValidada->caja_monto_ingreso = $_POST['caja_monto_ingreso'];
    $cerrarCajaValidada->caja_prestamo = $_POST['caja_prestamo'];
    $cerrarCajaValidada->caja__monto_egreso = $_POST['caja__monto_egreso'];
    $cerrarCajaValidada->caja_monto_total = $_POST['caja_monto_total'];
    $cerrarCajaValidada->caja_count_prestamo = $_POST['caja_count_prestamo'];
    $cerrarCajaValidada->caja_count_ingreso = $_POST['caja_count_ingreso'];
    $cerrarCajaValidada->caja_count_egreso = $_POST['caja_count_egreso'];
    $cerrarCajaValidada->caja_interes = $_POST['caja_interes'];
    $cerrarCajaValidada->validacion_fisica = filter_var($_POST['validacion_fisica'], FILTER_VALIDATE_BOOLEAN);
    $cerrarCajaValidada->observaciones = $_POST['observaciones'];
    $cerrarCajaValidada->ajaxCerrarCajaConValidaciones();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_dashboard_caja') {
    $dashboardCaja = new AjaxCaja();
    $dashboardCaja->ajaxObtenerDashboardCaja();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'listar_alertas_pendientes') {
    $alertasPendientes = new AjaxCaja();
    $alertasPendientes->ajaxListarAlertasPendientes();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'marcar_alerta_leida') {
    $marcarAlertaLeida = new AjaxCaja();
    $marcarAlertaLeida->alerta_id = $_POST['alerta_id'];
    $marcarAlertaLeida->ajaxMarcarAlertaLeida();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_contexto_usuario') {
    $contextoUsuario = new AjaxCaja();
    $contextoUsuario->ajaxObtenerContextoUsuario();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_estado_sistema_caja') {
    $estadoSistemaCaja = new AjaxCaja();
    $estadoSistemaCaja->ajaxVerificarEstadoSistemaCaja();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'listar_cajas_por_sucursal') {
    $listarCajasPorSucursal = new AjaxCaja();
    $listarCajasPorSucursal->ajaxListarCajasPorSucursal();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'registrar_caja_sucursal') {
    $registrarCajaSucursal = new AjaxCaja();
    $registrarCajaSucursal->caja_descripcion = $_POST['caja_descripcion'];
    $registrarCajaSucursal->caja_monto_inicial = $_POST['caja_monto_inicial'];
    $registrarCajaSucursal->ajaxRegistrarCajaSucursal();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'verificar_acceso_caja') {
    $verificarAccesoCaja = new AjaxCaja();
    $verificarAccesoCaja->sucursal_id = $_POST['sucursal_id'] ?? null;
    $verificarAccesoCaja->ajaxVerificarAccesoCaja();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'generar_cierre_dia') {
    $generarCierreDia = new AjaxCaja();
    $generarCierreDia->fecha_cierre = $_POST['fecha_cierre'] ?? date('Y-m-d');
    $generarCierreDia->observaciones = $_POST['observaciones'] ?? '';
    $generarCierreDia->ajaxGenerarCierreDia();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'listar_cierres_dia') {
    $listarCierresDia = new AjaxCaja();
    $listarCierresDia->ajaxListarCierresDia();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_resumen_dia') {
    $obtenerResumenDia = new AjaxCaja();
    $obtenerResumenDia->ajaxObtenerResumenDia();
} else if (isset($_POST['accion']) && $_POST['accion'] == 'obtener_sucursal_usuario') {
    $obtenerSucursalUsuario = new AjaxCaja();
    $obtenerSucursalUsuario->ajaxObtenerSucursalUsuario();
} else {
    $datos = new AjaxCaja();        //TRAER DATOS PARA LAS CAJAS 
    $datos->getDatosDashboard();
}

