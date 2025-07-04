<?php


class AdminPrestamosControlador
{

    /*===================================================================*/
    //LISTAR PRESTAMOS POR ID DEL USUARIO
    /*===================================================================*/
    static public function ctrListarPrestamoPorUsuario($id_usuario)
    {
        $listPrestamosporUsuario = AdminPrestamosModelo::mdlListarPrestamoPorUsuario($id_usuario);
        return $listPrestamosporUsuario;
    }


    /*===================================================================*/
    //VER DETALLE DEL PRESTAMO
    /*===================================================================*/
    static public function ctrDetallePrestamo($nro_prestamo)
    {
        $detallePrestamo =  AdminPrestamosModelo::mdlDetallePrestamo($nro_prestamo);
        return $detallePrestamo;
    }


    /*===================================================================*/
    //PAGAR CUOTA DEL PRESTAMOP
    /*===================================================================*/
    static public function ctrPagarCuota($nro_prestamo, $pdetalle_nro_cuota)
    {
        $PagarCuota = AdminPrestamosModelo::mdlPagarCuota($nro_prestamo, $pdetalle_nro_cuota);
        return $PagarCuota;
    }


    /*===================================================================*/
    //OBTENER CUOTAS PAGADAS
    /*===================================================================*/
    static public function ctrObtenerCuotasPagadas($nro_prestamo)
    {
        $CuotasPagadas = AdminPrestamosModelo::mdlObtenerCuotasPagadas($nro_prestamo);
        return $CuotasPagadas;
    }



    /*===================================================================*/
    // LIQUIDAR PRESTAMO
    /*===================================================================*/
    static public function ctrLiquidarPrestamo($nro_prestamo, $pdetalle_nro_cuota)
    {
        $array_cuota =  explode(",", $pdetalle_nro_cuota);
        //  $array_monto =   explode(",", $pdetalle_monto_cuota);
        //  $array_fecha =   explode(",", $pdetalle_fecha);

        for ($i = 0; $i < count($array_cuota); $i++) {
            $LiquidarPrestamo = AdminPrestamosModelo::mdlLiquidarPrestamo($nro_prestamo, $array_cuota[$i]); //llamamos al metodo del modelo
        }

        return $LiquidarPrestamo;

    }

    /*===================================================================*/
    // REGISTRAR ABONO DE CUOTA
    /*===================================================================*/
    static public function ctrRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar)
    {
        $RegistrarAbono = AdminPrestamosModelo::mdlRegistrarAbono($nro_prestamo, $pdetalle_nro_cuota, $monto_a_abonar);
        return $RegistrarAbono;
    }
}
