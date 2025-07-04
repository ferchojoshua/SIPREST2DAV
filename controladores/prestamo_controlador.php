<?php

class PrestamoControlador
{

  /*===================================================================*/
  // REGISTRAR CABECERA PRESTAMO
  /*===================================================================*/
  static public function ctrRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id)
  {

    $prestamo = PrestamoModelo::mdlRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id);
    return $prestamo;
  }


  /*===================================================================*/
  // REGISTRAR DETALLE PRESTAMO
  /*===================================================================*/
  static public function ctrRegistrarPrestamoDetalle($nro_prestamo, $pdetalle_nro_cuota, $pdetalle_monto_cuota, $pdetalle_fecha)
  {
    $array_cuota =  explode(",", $pdetalle_nro_cuota);
    $array_monto =   explode(",", $pdetalle_monto_cuota);
    $array_fecha =   explode(",", $pdetalle_fecha);

    for ($i = 0; $i < count($array_cuota); $i++) {
      $prestamo = PrestamoModelo::mdlRegistrarPrestamoDetalle($nro_prestamo, $array_cuota[$i], $array_monto[$i], $array_fecha[$i]); //llamamos al metodo del modelo
    }

    return $prestamo;
    var_dump($prestamo);
    //  echo $prestamo;
  }



  /*===================================================================*/
  //VALIDAD SI HAY MONTO DISPONIBLE EN CAJA
  /*===================================================================*/
  static public function ctrValidarMontoPrestamo()
  {
    $ValidarPres = PrestamoModelo::mdlValidarMontoPrestamo();
    return $ValidarPres;
  }
}
