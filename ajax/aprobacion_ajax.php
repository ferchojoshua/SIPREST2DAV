<?php

require_once "../controladores/aprobacion_controlador.php";
require_once "../modelos/aprobacion_modelo.php";

class AjaxAprobacion
{

    /*===================================================================*/
    //LISTAR PRESTAMOS EN DATATABLE POR APROBACION
    /*===================================================================*/
    public function  ListarPrestamosPorAprobacion($fecha_ini,$fecha_fin)
    {
        $aprobacionP = AprobacionControlador::ctrListarPrestamosPorAprobacion($fecha_ini,$fecha_fin);
        echo json_encode($aprobacionP);
    }


    /*===================================================================*/
    //APROBAR PRESTAMO CON ASIGNACIÓN DE RUTA Y COBRADOR
    /*===================================================================*/
    public function ajaxAprobarPrestamoConAsignacion()
    {
        try {
            // Validar campos requeridos
            $camposRequeridos = ['nro_prestamo', 'sucursal_asignada_id', 'ruta_asignada_id', 'cobrador_asignado_id'];
            foreach ($camposRequeridos as $campo) {
                if (!isset($this->$campo) || empty($this->$campo)) {
                    throw new Exception("El campo {$campo} es requerido");
                }
            }

            // Llamar al controlador para aprobar con asignación
            $resultado = AprobacionControlador::ctrAprobarPrestamoConAsignacion(
                $this->nro_prestamo,
                $this->sucursal_asignada_id,
                $this->ruta_asignada_id,
                $this->cobrador_asignado_id,
                $this->observaciones_asignacion ?? ''
            );
            
            echo json_encode($resultado);
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
    }

    /*===================================================================*/
    //APROBAR PRESTAMO (MÉTODO ORIGINAL)
    /*===================================================================*/
    public function ajaxActualizarEstadoPrest()
    {
        $Aprobar = AprobacionControlador::ctrActualizarEstadoPrest($this->nro_prestamo);
        echo json_encode($Aprobar);
    }


    /*===================================================================*/
    //DESAPROBAR PRESTAMO
    /*===================================================================*/
    public function ajaxDesaprobarPrest()
    {
        $Desaprobar = AprobacionControlador::ctrDesaprobarPrest($this->nro_prestamo);
        echo json_encode($Desaprobar);
    }


    /*===================================================================*/
     //ANULAR PRESTAMO
     /*===================================================================*/
     public function ajaxAnularPrest()
     {
         $AnularP = AprobacionControlador::ctrAnularPrest($this->nro_prestamo);
         echo json_encode($AnularP);
     }

    /*===================================================================*/
    //LISTAR SUCURSALES ACTIVAS PARA COMBO
    /*===================================================================*/
    public function ajaxListarSucursalesActivas()
    {
        try {
            require_once "../controladores/sucursales_controlador.php";
            require_once "../modelos/sucursales_modelo.php";
            
            $sucursales = SucursalControlador::ctrListarSucursalesActivasCompletas();
            echo json_encode($sucursales);
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar sucursales: ' . $e->getMessage()
            ]);
        }
    }

    /*===================================================================*/
    //LISTAR RUTAS POR SUCURSAL PARA COMBO
    /*===================================================================*/
    public function ajaxListarRutasPorSucursal()
    {
        try {
            if (!isset($this->sucursal_id) || empty($this->sucursal_id)) {
                throw new Exception("ID de sucursal requerido");
            }

            require_once "../controladores/rutas_controlador.php";
            require_once "../modelos/rutas_modelo.php";
            
            $rutas = RutasControlador::ctrListarRutasActivasCompletas($this->sucursal_id);
            echo json_encode($rutas);
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar rutas: ' . $e->getMessage()
            ]);
        }
    }

    /*===================================================================*/
    //LISTAR COBRADORES POR RUTA PARA COMBO
    /*===================================================================*/
    public function ajaxListarCobradoresPorRuta()
    {
        try {
            if (!isset($this->ruta_id) || empty($this->ruta_id)) {
                throw new Exception("ID de ruta requerido");
            }

            require_once "../controladores/rutas_controlador.php";
            require_once "../modelos/rutas_modelo.php";
            
            $cobradores = RutasControlador::ctrListarUsuariosRutaCompletos($this->ruta_id);
            echo json_encode($cobradores);
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => 'Error al cargar cobradores: ' . $e->getMessage()
            ]);
        }
    }

    /*===================================================================*/
    //LISTAR TODOS LOS USUARIOS (COBRADORES)
    /*===================================================================*/
    public function ajaxListarCobradoresActivos()
    {
        $cobradores = AprobacionModelo::mdlListarUsuariosActivos();
        echo json_encode($cobradores);
    }

    /*===================================================================*/
    //OBTENER DATOS COMPLETOS DEL PRÉSTAMO PARA PLAN DE PAGO
    /*===================================================================*/
    public function ajaxObtenerDatosCompletoPrestamo()
    {
        try {
            if (empty($this->nro_prestamo)) {
                throw new Exception("Número de préstamo requerido");
            }

            $datos = AprobacionModelo::mdlObtenerDatosCompletoPrestamo($this->nro_prestamo);
            
            if ($datos) {
                echo json_encode([
                    'estado' => 'ok',
                    'data' => $datos
                ]);
            } else {
                echo json_encode([
                    'estado' => 'error',
                    'mensaje' => 'No se encontraron datos del préstamo'
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'estado' => 'error',
                'mensaje' => $e->getMessage()
            ]);
        }
    }

}

if (isset($_POST['accion']) && $_POST['accion'] == 1) { //LISTAR  PRESTAMOS POR APROBACION
    $aprobacionP = new AjaxAprobacion();
    $aprobacionP->ListarPrestamosPorAprobacion($_POST["fecha_ini"],$_POST["fecha_fin"]);
    

} else if (isset($_POST['accion']) && $_POST['accion'] == 2) { //PARA APROBAR EL PRESTAMO
    $Aprobar = new AjaxAprobacion();
    $Aprobar->nro_prestamo = $_POST["nro_prestamo"];
    $Aprobar->ajaxActualizarEstadoPrest();


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) { //PARA DESAPROBAR EL PRESTAMO
    $Desaprobar = new AjaxAprobacion();
    $Desaprobar->nro_prestamo = $_POST["nro_prestamo"];
    $Desaprobar->ajaxDesaprobarPrest();


} else if (isset($_POST['accion']) && $_POST['accion'] == 4) { //PARA ANULAR EL PRESTAMO
    $AnularP = new AjaxAprobacion();
    $AnularP->nro_prestamo = $_POST["nro_prestamo"];
    $AnularP->ajaxAnularPrest();

} else if (isset($_POST['accion']) && $_POST['accion'] == 5) { //APROBAR CON ASIGNACIÓN
    $AprobarConAsignacion = new AjaxAprobacion();
    $AprobarConAsignacion->nro_prestamo = $_POST["nro_prestamo"];
    $AprobarConAsignacion->sucursal_asignada_id = $_POST["sucursal_asignada_id"];
    $AprobarConAsignacion->ruta_asignada_id = $_POST["ruta_asignada_id"];
    $AprobarConAsignacion->cobrador_asignado_id = $_POST["cobrador_asignado_id"];
    $AprobarConAsignacion->observaciones_asignacion = $_POST["observaciones_asignacion"] ?? '';
    $AprobarConAsignacion->ajaxAprobarPrestamoConAsignacion();

} else if (isset($_GET['accion']) && $_GET['accion'] == 'listar_sucursales') { //LISTAR SUCURSALES
    $ajax = new AjaxAprobacion();
    $ajax->ajaxListarSucursalesActivas();

} else if (isset($_POST['accion']) && $_POST['accion'] == 'listar_rutas_sucursal') { //LISTAR RUTAS POR SUCURSAL
    $ajax = new AjaxAprobacion();
    $ajax->sucursal_id = $_POST["sucursal_id"];
    $ajax->ajaxListarRutasPorSucursal();

} else if (isset($_GET['accion']) && $_GET['accion'] == 'listar_cobradores') { //LISTAR COBRADORES ACTIVOS
    $ajax = new AjaxAprobacion();
    $ajax->ajaxListarCobradoresActivos();

} else if (isset($_GET['accion']) && $_GET['accion'] == 'obtener_datos_prestamo') { //OBTENER DATOS COMPLETOS DEL PRÉSTAMO
    $ajax = new AjaxAprobacion();
    $ajax->nro_prestamo = $_GET['nro_prestamo'];
    $ajax->ajaxObtenerDatosCompletoPrestamo();

}
