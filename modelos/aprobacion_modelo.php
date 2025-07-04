<?php

require_once "conexion.php";

class AprobacionModelo
{

    /*===================================================================*/
    //Peticion  MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    static public function mdlListarPrestamosPorAprobacion($fecha_ini, $fecha_fin)
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_PRESTAMOS_POR_APROBACION(:fecha_ini, :fecha_fin)');
        $stmt->bindParam(":fecha_ini", $fecha_ini, PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $fecha_fin, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }



    /*===================================================================*/
    //APROBAR PRESTAMO
    /*===================================================================*/
    static public function mdlActualizarEstadoPrest($nro_prestamo)
    {

        try {

            $stmt = Conexion::conectar()->prepare("UPDATE prestamo_cabecera SET pres_aprobacion = 'aprobado' , pres_estado_caja = 'VIGENTE'  where nro_prestamo = :nro_prestamo");
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);



            if ($stmt->execute()) {
                $stmt = null;
                $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_caja = 'VIGENTE', pdetalle_aprobacion = 'aprobado' where nro_prestamo = :nro_prestamo");

                $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);



                if ($stmt->execute()) {
                    $resultado = "ok";
                } else {
                    $resultado = "error";
                }
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        return $resultado;
        $stmt = null;
    }


    /*===================================================================*/
    //DESARPOBAR PRESTAMO
    /*===================================================================*/
    static public function mdlDesaprobarPrest($nro_prestamo)
    {

        $stmt = Conexion::conectar()->prepare('call SP_DESAPROBAR_PRESTAMO(:nro_prestamo)');
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

        $stmt->execute();

       if ($row = $stmt->fetchColumn()) {
           return $row;
       }
    }



    /*===================================================================*/
    //ANULAR PRESTAMO
    /*===================================================================*/
     static public function mdlAnularPrest($nro_prestamo)
     {

        $stmt = Conexion::conectar()->prepare('call SP_ANULAR_PRESTAMO(:nro_prestamo)');
        $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

        $stmt->execute();

       if ($row = $stmt->fetchColumn()) {
           return $row;
       }
 
        /* try {
             $stmt = Conexion::conectar()->prepare("UPDATE prestamo_cabecera SET pres_aprobacion = 'anulado', pres_estado_caja = '', pres_estado = 'Anulado' where nro_prestamo = :nro_prestamo");
             $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
 
             if ($stmt->execute()) {
                 $stmt = null;
                 $stmt = Conexion::conectar()->prepare("UPDATE prestamo_detalle SET pdetalle_estado_cuota = 'Anulado' where nro_prestamo = :nro_prestamo");
 
                 $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);

                 if ($stmt->execute()) {
                     $resultado = "ok";
                 } else {
                     $resultado = "error";
                 }
             }
         } catch (Exception $e) {
             $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
         }
 
         return $resultado;
         $stmt = null;*/
     }
}
