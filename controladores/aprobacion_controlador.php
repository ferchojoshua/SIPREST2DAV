<?php

class AprobacionControlador
{

    /*===================================================================*/
     //LISTAR PRESTAMOS  CON PROCEDURE  EN DATATABLE
     /*===================================================================*/
     static public function ctrListarPrestamosPorAprobacion($fecha_ini,$fecha_fin)
     {
         $aprobacionP =  AprobacionModelo::mdlListarPrestamosPorAprobacion($fecha_ini,$fecha_fin);
         return $aprobacionP;
     }


     /*===================================================================*/
    //APROBAR PRESTAMO
    /*===================================================================*/
    static public function ctrActualizarEstadoPrest($nro_prestamo)
    {
        $Aprobar = AprobacionModelo::mdlActualizarEstadoPrest($nro_prestamo);
        return $Aprobar;
    }


    /*===================================================================*/
    //APROBAR PRESTAMO CON ASIGNACIÓN DE RUTA Y COBRADOR
    /*===================================================================*/
    static public function ctrAprobarPrestamoConAsignacion($nro_prestamo, $sucursal_asignada_id, $ruta_asignada_id, $cobrador_asignado_id, $observaciones_asignacion = '')
    {
        try {
            // Validar que los IDs sean numéricos y válidos
            if (!is_numeric($sucursal_asignada_id) || $sucursal_asignada_id <= 0) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'ID de sucursal inválido'
                ];
            }

            if (!is_numeric($ruta_asignada_id) || $ruta_asignada_id <= 0) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'ID de ruta inválido'
                ];
            }

            if (!is_numeric($cobrador_asignado_id) || $cobrador_asignado_id <= 0) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'ID de cobrador inválido'
                ];
            }

            // Validar que el préstamo existe y está pendiente
            $prestamo = AprobacionModelo::mdlValidarPrestamoParaAprobacion($nro_prestamo);
            if (!$prestamo) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'Préstamo no encontrado o ya procesado'
                ];
            }

            // Validar que la ruta pertenece a la sucursal
            $rutaValida = AprobacionModelo::mdlValidarRutaSucursal($ruta_asignada_id, $sucursal_asignada_id);
            if (!$rutaValida) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'La ruta seleccionada no pertenece a la sucursal'
                ];
            }

            // Validar que el cobrador está asignado a la ruta
            $cobradorValido = AprobacionModelo::mdlValidarCobradorRuta($cobrador_asignado_id, $ruta_asignada_id);
            if (!$cobradorValido) {
                return [
                    'estado' => 'error',
                    'mensaje' => 'El cobrador seleccionado no está asignado a la ruta'
                ];
            }

            // Aprobar préstamo con asignación usando el método seguro
            $resultado = AprobacionModelo::mdlAprobarPrestamoConAsignacionSeguro(
                $nro_prestamo,
                $sucursal_asignada_id,
                $ruta_asignada_id,
                $cobrador_asignado_id,
                $observaciones_asignacion
            );

            if ($resultado['status'] === "ok") {
                return [
                    'estado' => 'ok',
                    'mensaje' => $resultado['message'],
                    'database_updated' => $resultado['has_assignment']
                ];
            } else {
                return [
                    'estado' => 'error',
                    'mensaje' => $resultado['message']
                ];
            }

        } catch (Exception $e) {
            return [
                'estado' => 'error',
                'mensaje' => 'Error interno: ' . $e->getMessage()
            ];
        }
    }


     /*===================================================================*/
      //DESAPROBAR PRESTAMO
    /*===================================================================*/
      static public function ctrDesaprobarPrest($nro_prestamo)
      {
          require_once "../modelos/aprobacion_modelo.php";

          $current_status = AprobacionModelo::mdlGetPrestamoAprobacionStatus($nro_prestamo);

          if ($current_status === 'anulado') {
              return [
                  'estado' => 'error',
                  'mensaje' => 'No se puede desaprobar un préstamo que ya ha sido anulado.'
              ];
          }

          $Desaprobar = AprobacionModelo::mdlDesaprobarPrest($nro_prestamo);
          
          if ($Desaprobar > 0) {
             if ($Desaprobar == 1) {
                 return [
                     'estado' => 'ok',
                     'mensaje' => 'Préstamo desaprobado exitosamente.'
                 ];
             } else {
                 return [
                     'estado' => 'error',
                     'mensaje' => 'El préstamo ya tiene cuotas pagadas, no se puede desaprobar.'
                 ];
             }
          } else {
              return [
                  'estado' => 'error',
                  'mensaje' => 'Error al desaprobar el préstamo.'
              ];
          }
      }


    /*===================================================================*/
      //DESAPROBAR PRESTAMO
    /*===================================================================*/
      static public function ctrAnularPrest($nro_prestamo)
      {
          $AnularP = AprobacionModelo::mdlAnularPrest($nro_prestamo);
          return $AnularP;
      }


}