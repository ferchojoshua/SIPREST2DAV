<?php

require_once "conexion.php";
class DashboardModelo {
    

    /*===================================================================*/
    //TRAER DATOS PARA LAS CAJAS 
    /*===================================================================*/
    static public function mdlListaDashboard(){
        $smt = Conexion::conectar()->prepare('call SP_DATOS_DASHBOARD()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    //PRESTAMOS DEL MES - BARRAS
    /*===================================================================*/
    static public function mdlListaPrestamosmesactual(){
        $smt = Conexion::conectar()->prepare('call SP_PRESTAMOS_MES_ACTUAL()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_OBJ);
    }


     /*===================================================================*/
    //CLIENTES CON PRESTAMOS EN DATATABLE
    /*===================================================================*/
    static public function mdlClientesConPrestamos(){
        $smt = Conexion::conectar()->prepare('call SP_CLIENTES_CON_PRESTAMOS()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_OBJ);
    }


    /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    static public function mdlCuotasVencidas(){
        $stmt = Conexion::conectar()->prepare('call SP_CUOTAS_VENCIDAS()');
        $stmt->execute();
        return $stmt->fetchAll();
        //return $stmt->fetchAll(PDO::FETCH_OBJ);
    }



     /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    static public function mdlNotificacion($id_usuario){
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_NOTIFICACION(:id_usuario)');
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
        //return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}