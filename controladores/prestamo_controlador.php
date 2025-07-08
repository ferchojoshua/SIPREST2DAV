<?php

class PrestamoControlador
{

  /*===================================================================*/
  // REGISTRAR CABECERA PRESTAMO
  /*===================================================================*/
  static public function ctrRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo)
  {

    $prestamo = PrestamoModelo::mdlRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo);
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

  /*===================================================================*/
  //OBTENER TIPOS DE CALCULO
  /*===================================================================*/
  static public function ctrObtenerTiposCalculo()
  {
    $tiposCalculo = PrestamoModelo::mdlObtenerTiposCalculo();
    return $tiposCalculo;
  }

  /*===================================================================*/
  //CALCULAR AMORTIZACION
  /*===================================================================*/
  static public function ctrCalcularAmortizacion($monto, $interes, $cuotas, $tipoCalculo, $fechaInicio, $formaPago)
  {
    require_once "utilitarios/calculadora_prestamos.php";
    
    try {
      $calculadora = new CalculadoraPrestamos();
      $resultado = $calculadora->calcularAmortizacion(
        floatval($monto),
        floatval($interes),
        intval($cuotas),
        $tipoCalculo,
        $fechaInicio,
        $formaPago
      );
      
      return array(
        'status' => 'ok',
        'data' => $resultado
      );
      
    } catch (Exception $e) {
      return array(
        'status' => 'error',
        'message' => $e->getMessage()
      );
    }
  }
}
