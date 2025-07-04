<?php

require_once "conexion.php";

class ModuloModelo{

     /*===================================================================*/
    //OBTENER MODULOS
    /*===================================================================*/
    static public function mdlObtenerModulos(){

        $stmt = Conexion::conectar()->prepare("select id as id,
                                                        (case when (padre_id is null or padre_id = 0 ) then '#' else padre_id end) as parent,
                                                        modulo as text,
                                                        vista
                                                from modulos m
                                                order by m.orden");

        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }


    /*===================================================================*/
     //MODULOS SEGUN EL PERFIL - ADMIN O VENDE
     /*===================================================================*/
    static public function mdlObtenerModulosPorPerfil($id_perfil){

        $stmt = Conexion::conectar()->prepare("select id,
                                                    modulo,
                                                    IFNULL(case when (m.vista is null or m.vista = '') then '0' else (
                                                        (select '1' from perfil_modulo pm where pm.id_modulo = m.id and pm.id_perfil = :id_perfil)) end, 0) as sel
                                                from modulos m
                                                order by m.orden");

        $stmt -> bindParam(":id_perfil",$id_perfil,PDO::PARAM_INT);

        $stmt -> execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);

    }


    /*===================================================================*/
    //LISTA DE MODULOS PARA EL DATATABLE
    /*===================================================================*/
    static public function mdlObtenerListaModulos(){

        $stmt = Conexion::conectar()->prepare("	SELECT '' as opciones,
                                                            id,
                                                            orden,
                                                            modulo,
                                                            (select modulo FROM modulos mp where mp.id = m.padre_id) as modulo_padre,
                                                            vista,
                                                            icon_menu
                                                    FROM modulos m
                                                    ORDER BY m.orden");

        $stmt -> execute();

        return $stmt->fetchAll();

    }



    
    /*==============================================================
    FNC PARA REORGANIZAR LOS MODULOS DEL SISTEMA
    ==============================================================*/
    static public function mdlReorganizarModulos($modulos_ordenados){        

        $total_registros = 0;

        foreach($modulos_ordenados as $modulo){
            
            $array_item_modulo = explode(";",$modulo);

            $stmt = Conexion::conectar()->prepare("UPDATE modulos
                                                    SET padre_id = replace(:p_padre_id,'#',0),
                                                        orden = :p_orden
                                                    WHERE id = :p_id");

            $stmt -> bindParam(":p_id",$array_item_modulo[0],PDO::PARAM_INT);            
            $stmt -> bindParam(":p_padre_id",$array_item_modulo[1],PDO::PARAM_INT);
            $stmt -> bindParam(":p_orden",$array_item_modulo[2],PDO::PARAM_INT);

            if($stmt->execute()){
                $total_registros = $total_registros + 1;
            }else{
                $total_registros = 0;
            }

        }        

        return $total_registros;

    }




    /*=============================================
    Peticion INSERT para REGISTRAR DATOS A LA BASE
    =============================================*/
    static public function mdlRegistrarModulos($modulo, $vista, $icon_menu) {
        try {
            //$fecha = date('Y-m-d');

            //CAPTURAMOS EL ULTIMO REGISTRO DE LA COLUMNA ORDEN Y LO SETEAMOS ABAJO
            $stmt = Conexion::conectar()->prepare("SELECT max(orden)
                                                FROM modulos m");

            $stmt -> execute();

            $orden = $stmt->fetch();

            $orden = $orden[0] + 1;

            $stmt = Conexion::conectar()->prepare("INSERT INTO modulos(modulo, 
                                                                          vista, 
                                                                          icon_menu, orden) 
                                                                VALUES (:modulo, 
                                                                        :vista, :icon_menu, :orden)");

            $stmt->bindParam(":modulo", $modulo, PDO::PARAM_STR);
            $stmt->bindParam(":vista", $vista, PDO::PARAM_STR);
            $stmt->bindParam(":icon_menu", $icon_menu, PDO::PARAM_STR);
            $stmt -> bindParam(":orden",$orden,PDO::PARAM_INT);

            if ($stmt->execute()) {
                $resultado = "ok";
            } else {
                $resultado = "error";
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        return $resultado;

        $stmt = null;
    }



    /*=============================================
    Peticion UPDATE: para ACTUALIZAR DATOS
    =============================================*/
    static public function mdlActualizarModulos($table, $data, $id, $nameId)
    {

        $set = "";

        foreach ($data as $key => $value) {
            $set .= $key . " = :" . $key . ","; //DEPENDE DEL ARRAY QUE VIENE DEL AJAX
        }

        $set = substr($set, 0, -1); //QUITA LA COMA

        $stmt = Conexion::conectar()->prepare("UPDATE $table SET $set WHERE $nameId = :$nameId");

        foreach ($data as $key => $value) {
            $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
        }

        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "ok";
        } else {

            return Conexion::conectar()->errorInfo();
        }
    }


    /*=============================================
    Peticion DELETE: PARA ELIMINAR DATOS DE LA TABLA POR ID
    =============================================*/

    static public function mdlEliminarModulo($table, $id, $nameId)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";;
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }









}