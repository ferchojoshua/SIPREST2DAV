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
    try {
        $array_cuota = explode(",", $pdetalle_nro_cuota);
        $array_monto = explode(",", $pdetalle_monto_cuota);
        $array_fecha = explode(",", $pdetalle_fecha);

        // Validar que los arrays tengan la misma longitud
        if (count($array_cuota) !== count($array_monto) || count($array_monto) !== count($array_fecha)) {
            throw new Exception("Error en los datos: los arrays no tienen la misma longitud");
        }

        $conn = Conexion::conectar();
        $conn->beginTransaction();

        try {
            for ($i = 0; $i < count($array_cuota); $i++) {
                $resultado = PrestamoModelo::mdlRegistrarPrestamoDetalle(
                    $nro_prestamo,
                    trim($array_cuota[$i]),
                    trim($array_monto[$i]),
                    trim($array_fecha[$i])
                );

                if ($resultado !== "ok") {
                    throw new Exception("Error al guardar el detalle de la cuota " . ($i + 1));
                }
            }

            $conn->commit();
            return "ok";

        } catch (Exception $e) {
            $conn->rollBack();
            error_log("Error en ctrRegistrarPrestamoDetalle: " . $e->getMessage());
            return $e->getMessage();
        }
    } catch (Exception $e) {
        error_log("Error en ctrRegistrarPrestamoDetalle: " . $e->getMessage());
        return $e->getMessage();
    }
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

  /*===================================================================*/
  // CARGAR SELECTS DINÁMICOS (Forma Pago, Moneda)
  /*===================================================================*/
  static public function ctrCargarSelect($tabla)
  {
      try {
          $respuesta = PrestamoModelo::mdlCargarSelect($tabla);
          if (empty($respuesta)) {
              throw new Exception("No hay datos configurados para " . $tabla);
          }
          return $respuesta;
      } catch (Exception $e) {
          error_log("Error en ctrCargarSelect: " . $e->getMessage());
          throw $e;
      }
  }

  /*===================================================================*/
  // OBTENER TIPOS DE CÁLCULO (Amortización)
  /*===================================================================*/
  static public function ctrObtenerTiposCalculo()
  {
      try {
          $tiposCalculo = PrestamoModelo::mdlObtenerTiposCalculo();
          if (empty($tiposCalculo)) {
              throw new Exception("No hay tipos de cálculo configurados");
          }
          return $tiposCalculo;
      } catch (Exception $e) {
          error_log("Error en ctrObtenerTiposCalculo: " . $e->getMessage());
          throw $e;
      }
  }

} // FIN DE LA CLASE
