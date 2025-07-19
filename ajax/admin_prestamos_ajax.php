<?php

// Blindaje completo para asegurar solo JSON válido
if (ob_get_level()) {
    ob_clean();
}
header('Content-Type: application/json');

// Activar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../controladores/admin_prestamos_controlador.php';
require_once __DIR__ . '/../modelos/admin_prestamos_modelo.php';

class AjaxAdminPrestamos
{

    /*===================================================================*/
    //LISTAR PRESTAMOS POR ID DEL USUARIO
    /*===================================================================*/
    public function ajaxListarPrestamoPorUsuario($id_usuario)
    {
        try {
            $listPrestamosporUsuario = AdminPrestamosControlador::ctrListarPrestamoPorUsuario($id_usuario);
            
            // Verificar que no haya salida antes del json_encode
            if (ob_get_length()) ob_clean();
            
            echo json_encode($listPrestamosporUsuario, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
            exit;
        }
    }


    /*===================================================================*/
    //VER DETALLE DEL PRESTAMO
    /*===================================================================*/
    public function ajaxDetallePrestamo($nro_prestamo)
    {
        try {
            error_log("Solicitando detalle para préstamo: " . $nro_prestamo);
            $detallePrestamo = AdminPrestamosControlador::ctrDetallePrestamo($nro_prestamo);
            error_log("Resultado del detalle: " . print_r($detallePrestamo, true));
            
            // Verificar que no haya salida antes del json_encode
            if (ob_get_length()) ob_clean();
            
            echo json_encode($detallePrestamo, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            error_log("Error en ajaxDetallePrestamo: " . $e->getMessage());
            echo json_encode(array("error" => $e->getMessage()));
            exit;
        }
    }



    /*===================================================================*/
    //PAGAR CUOTA DEL PRESTAMO
    /*===================================================================*/
    public function ajaxPagarCuota()
    {
        try {
            $PagarCuota = AdminPrestamosControlador::ctrPagarCuota($this->nro_prestamo, $this->pdetalle_nro_cuota);
            
            // Verificar que no haya salida antes del json_encode
            if (ob_get_length()) ob_clean();
            
            echo json_encode($PagarCuota);
            exit;
        } catch (Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
            exit;
        }
    }


    /*===================================================================*/
    //OBTENER CUOTAS PAGADAS
    /*===================================================================*/
    public function ajaxObtenerCuotasPagadas($nro_prestamo){
        try {
            $CuotasPagadas = AdminPrestamosControlador::ctrObtenerCuotasPagadas($nro_prestamo);
            
            // Verificar que no haya salida antes del json_encode
            if (ob_get_length()) ob_clean();
            
            echo json_encode($CuotasPagadas, JSON_UNESCAPED_UNICODE);
            exit;
        } catch (Exception $e) {
            echo json_encode(array("error" => $e->getMessage()));
            exit;
        }
    }


    /*===================================================================*/
     // LIQUIDAR PRESTAMO
     /*===================================================================*/
     public function ajaxLiquidarPrestamo($nro_prestamo, $pdetalle_nro_cuota)
     {   
         try {
             $LiquidarPrestamo = AdminPrestamosControlador::ctrLiquidarPrestamo($nro_prestamo, $pdetalle_nro_cuota);
             
             // Verificar que no haya salida antes del json_encode
             if (ob_get_length()) ob_clean();
             
             echo json_encode($LiquidarPrestamo);
             exit;
         } catch (Exception $e) {
             echo json_encode(array("error" => $e->getMessage()));
             exit;
         }
     }

     /*===================================================================*/
     // REGISTRAR ABONO DE CUOTA
     /*===================================================================*/
     public function ajaxRegistrarAbono()
     {
         try {
             // Validar parámetros requeridos
             if (empty($this->nro_prestamo) || empty($this->pdetalle_nro_cuota) || empty($this->monto_a_abonar)) {
                 throw new Exception("Faltan parámetros requeridos para registrar el abono");
             }
             
             // Validar que el monto sea numérico y positivo
             if (!is_numeric($this->monto_a_abonar) || floatval($this->monto_a_abonar) <= 0) {
                 throw new Exception("El monto del abono debe ser un número positivo");
             }
             
             $RegistrarAbono = AdminPrestamosControlador::ctrRegistrarAbono(
                 $this->nro_prestamo,
                 $this->pdetalle_nro_cuota,
                 $this->monto_a_abonar,
                 $this->tipo_abono
             );
             
             // Verificar que no haya salida antes del json_encode
             if (ob_get_length()) ob_clean();
             
             echo json_encode($RegistrarAbono);
             exit;
         } catch (Exception $e) {
             error_log("Error en ajaxRegistrarAbono: " . $e->getMessage());
             echo json_encode(array("status" => "error", "message" => $e->getMessage()));
             exit;
         }
     }

     /*===================================================================*/
     // REIMPRIMIR CONTRATO (ADMIN)
     /*===================================================================*/
     public function ajaxReimprimirContratoAdmin($id_prestamo)
     {
         try {
             $ReimprimirContrato = AdminPrestamosControlador::ctrReimprimirContratoAdmin($id_prestamo);
             
             // Verificar que no haya salida antes del json_encode
             if (ob_get_length()) ob_clean();
             
             echo json_encode($ReimprimirContrato);
             exit;
         } catch (Exception $e) {
             echo json_encode(array("error" => $e->getMessage()));
             exit;
         }
     }

     /*===================================================================*/
     // ENVIAR TABLA DE PAGOS POR CORREO
     /*===================================================================*/
     public function ajaxEnviarTablaCorreo($nro_prestamo, $cliente_nombres)
     {
         try {
             $EnviarTablaCorreo = AdminPrestamosControlador::ctrEnviarTablaCorreo($nro_prestamo, $cliente_nombres);
             
             // Verificar que no haya salida antes del json_encode
             if (ob_get_length()) ob_clean();
             
             echo json_encode($EnviarTablaCorreo);
             exit;
         } catch (Exception $e) {
             echo json_encode(array("error" => $e->getMessage()));
             exit;
         }
     }
}

// Iniciar buffer de salida para capturar cualquier echo no deseado
ob_start();

if (isset($_POST["accion"]) && $_POST["accion"] == 1) {             //LISTAR PRESTAMOS POR ID DEL USUARIO
    $listPrestamosporUsuario = new AjaxAdminPrestamos();
    $listPrestamosporUsuario->ajaxListarPrestamoPorUsuario($_POST["id_usuario"]);


} else if (isset($_POST['accion']) && $_POST['accion'] == 2) {       ///VER DETALLE DEL PRESTAMO
    $detallePrestamo = new AjaxAdminPrestamos();
    $detallePrestamo->ajaxDetallePrestamo($_POST["nro_prestamo"]);


} else if (isset($_POST['accion']) && $_POST['accion'] == 3) {       //PAGAR CUOTA DEL PRESTAMO
    $PagarCuota = new AjaxAdminPrestamos();
    $PagarCuota->nro_prestamo = $_POST["nro_prestamo"];
    $PagarCuota->pdetalle_nro_cuota = $_POST["pdetalle_nro_cuota"];
    $PagarCuota->ajaxPagarCuota();

    
}else if (isset($_POST['accion']) && $_POST['accion'] == 4) {        //OBTENER CUOTAS PAGADAS
    $CuotasPagadas = new AjaxAdminPrestamos();
    $CuotasPagadas->ajaxObtenerCuotasPagadas($_POST["nro_prestamo"]); 


}else if (isset($_POST["accion"]) && $_POST["accion"] == 5) {        // LIQUIDAR PRESTAMO
    $LiquidarPrestamo = new AjaxAdminPrestamos();
    $LiquidarPrestamo->ajaxLiquidarPrestamo( $_POST['nro_prestamo'],  $_POST['pdetalle_nro_cuota'] );



} else if (isset($_POST["accion"]) && $_POST["accion"] == 6) { // REGISTRAR ABONO DE CUOTA
    error_log("Iniciando registrar abono - Datos recibidos: " . json_encode($_POST));
    
    $RegistrarAbono = new AjaxAdminPrestamos();
    $RegistrarAbono->nro_prestamo = $_POST["nro_prestamo"];
    $RegistrarAbono->pdetalle_nro_cuota = $_POST["pdetalle_nro_cuota"];
    $RegistrarAbono->monto_a_abonar = $_POST["monto_a_abonar"];
    $RegistrarAbono->tipo_abono = $_POST["tipo_abono"] ?? 'normal';
    $RegistrarAbono->ajaxRegistrarAbono();

} else if (isset($_POST["accion"]) && $_POST["accion"] == 7) { // REIMPRIMIR CONTRATO (ADMIN)
    $ReimprimirContrato = new AjaxAdminPrestamos();
    $ReimprimirContrato->ajaxReimprimirContratoAdmin($_POST["id_prestamo"]);

} else if (isset($_POST["accion"]) && $_POST["accion"] == 8) { // ENVIAR TABLA DE PAGOS POR CORREO
    $EnviarTablaCorreo = new AjaxAdminPrestamos();
    $EnviarTablaCorreo->ajaxEnviarTablaCorreo($_POST["nro_prestamo"], $_POST["cliente_nombres"]);
}
