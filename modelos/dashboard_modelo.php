<?php

require_once "conexion.php";
class DashboardModelo {
    

    /*===================================================================*/
    //TRAER DATOS PARA LAS CAJAS 
    /*===================================================================*/
    static public function mdlListaDashboard(){
        $smt = Conexion::conectar()->prepare('call SP_DATOS_DASHBOARD()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*===================================================================*/
    //TRAER DATOS PARA LAS CAJAS CON FILTROS
    /*===================================================================*/
    static public function mdlListaDashboardFiltrado($sucursalId, $periodo){
        $query = 'CALL SP_DATOS_DASHBOARD_FILTRADO(?, ?)';
        $smt = Conexion::conectar()->prepare($query);
        $smt->bindParam(1, $sucursalId, PDO::PARAM_INT);
        $smt->bindParam(2, $periodo, PDO::PARAM_STR);
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*===================================================================*/
    //PRESTAMOS DEL MES - BARRAS
    /*===================================================================*/
    static public function mdlListaPrestamosmesactual(){
        $smt = Conexion::conectar()->prepare('call SP_PRESTAMOS_MES_ACTUAL()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*===================================================================*/
    //PRESTAMOS DEL MES - BARRAS CON FILTROS
    /*===================================================================*/
    static public function mdlListaPrestamosmesactualFiltrado($sucursalId, $periodo){
        $query = 'CALL SP_PRESTAMOS_MES_ACTUAL_FILTRADO(?, ?)';
        $smt = Conexion::conectar()->prepare($query);
        $smt->bindParam(1, $sucursalId, PDO::PARAM_INT);
        $smt->bindParam(2, $periodo, PDO::PARAM_STR);
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);
    }


     /*===================================================================*/
    //CLIENTES CON PRESTAMOS EN DATATABLE
    /*===================================================================*/
    static public function mdlClientesConPrestamos(){
        $stmt = Conexion::conectar()->prepare('call SP_CLIENTES_CON_PRESTAMOS()');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    static public function mdlCuotasVencidas(){
        $stmt = Conexion::conectar()->prepare('call SP_CUOTAS_VENCIDAS()');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



     /*===================================================================*/
    //LISTAR CUOTAS VENCIDAS EN DATATABLE
    /*===================================================================*/
    static public function mdlNotificacion($id_usuario){
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_NOTIFICACION(:id_usuario)');
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        //return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /*===================================================================*/
    //KPIs GERENCIALES CON FILTROS
    /*===================================================================*/
    static public function mdlListaKpisFiltrados($sucursalId, $periodo){
        $query = 'CALL SP_KPIs_FILTRADOS(?, ?)';
        $smt = Conexion::conectar()->prepare($query);
        $smt->bindParam(1, $sucursalId, PDO::PARAM_INT);
        $smt->bindParam(2, $periodo, PDO::PARAM_STR);
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);
    }
}