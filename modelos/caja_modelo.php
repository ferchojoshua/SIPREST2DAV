<?php
require_once "conexion.php";

class CajaModelo 
{

    /*===================================================================*/
    //Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    /*===================================================================*/
    static public function mdlListarAperturaCaja()
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_CAJA()');
        // $stmt -> bindParam(":fecha_ini",$fecha_ini,PDO::PARAM_STR);
        //$stmt -> bindParam(":fecha_fin",$fecha_fin,PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*===================================================================*/
    //Peticion INSERT para REGISTRAR DATOS A LA BASE
    /*===================================================================*/
    static public function mdlRegistrarCaja($caja_descripcion, $caja_monto_inicial)
    {
        $stmt = Conexion::conectar()->prepare('call SP_REGISTRAR_APERTURA_CAJA(:caja_descripcion, :caja_monto_inicial)');
        $stmt->bindParam(":caja_descripcion", $caja_descripcion, PDO::PARAM_STR);
        $stmt->bindParam(":caja_monto_inicial", $caja_monto_inicial, PDO::PARAM_STR);

        $stmt->execute();
        if ($row = $stmt->fetchColumn()) {
            return $row;
        }
    }


    /*===================================================================*/
    //OBTENER TODOS LOS DATOS DE INGRE, EGRE, PRES PARA EL CIERRE
    /*===================================================================*/
    static public function mdlObtenerDataCierreCaja()
    {
        $stmt = Conexion::conectar()->prepare('call SP_REPORTE_LISTAR_TOTAL_CIERRE_CAJA()');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    //CERRAR LA CAJA
    /*===================================================================*/
    static public function mdlCerrarCaja($caja_monto_ingreso, $caja_prestamo, $caja__monto_egreso, $caja_monto_total, $caja_count_prestamo, $caja_count_ingreso, $caja_count_egreso,$caja_interes)
    {
        $stmt = Conexion::conectar()->prepare('call SP_REGISTRAR_CAJA_CIERRE(:caja_monto_ingreso, :caja_prestamo, :caja__monto_egreso, :caja_monto_total ,:caja_count_prestamo, :caja_count_ingreso, :caja_count_egreso, :caja_interes)');

        $stmt->bindParam(":caja_monto_ingreso", $caja_monto_ingreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_prestamo", $caja_prestamo, PDO::PARAM_STR);
        $stmt->bindParam(":caja__monto_egreso", $caja__monto_egreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_monto_total", $caja_monto_total, PDO::PARAM_STR);
        $stmt->bindParam(":caja_count_prestamo", $caja_count_prestamo, PDO::PARAM_STR);
        $stmt->bindParam(":caja_count_ingreso", $caja_count_ingreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_count_egreso", $caja_count_egreso, PDO::PARAM_STR);
        $stmt->bindParam(":caja_interes", $caja_interes, PDO::PARAM_STR);

        $stmt->execute();
        //  if($resultado){
        // 	return 'ok';
        // }else{
        // 	return 'error';
        // }
        if ($row = $stmt->fetchColumn()) {
            return $row;
        }

        //var_dump($stmt);
    }


    /*===================================================================*/
    // ESTADO DE LA CAJA PARA PROCEDER A REALIZAR UN PRESTAMO
    /*===================================================================*/
    static public function mdlObtenerDataEstadoCaja()
    {
        $smt = Conexion::conectar()->prepare('call SP_OBTENER_ESTADO_CAJA()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }



    /*===================================================================*/
    //OBTENER   ID DE LA CAJA
    /*===================================================================*/
    static public function mdlObtenerIDCaja()
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_ID_CAJA_PARA_PRESTAMOS()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    //VER DETALLE DL PRESTAMO EN MODAL
    /*===================================================================*/
    static public function mdlPrestamoPorCajaID($caja_id)
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_PRESTAMOS_POR_CAJA(:caja_id)');
        $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /*===================================================================*/
    //VER DETALLE DL PRESTAMO EN MODAL
    /*===================================================================*/
    static public function mdlMovimientosPorCajaID($caja_id)
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_MOVIMIENTOS_POR_CAJA(:caja_id)');
        $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }


}
