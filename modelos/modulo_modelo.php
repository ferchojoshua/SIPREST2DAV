<?php

require_once "conexion.php";

class ModuloModelo{

     /*===================================================================*/
    //OBTENER MODULOS
    /*===================================================================*/
    static public function mdlObtenerModulos(){
        try {
            $stmt = Conexion::conectar()->prepare("
                WITH RECURSIVE ModulosJerarquia AS (
                    -- Obtener módulos padre
                    SELECT 
                        id,
                        modulo,
                        padre_id,
                        vista,
                        icon_menu,
                        orden,
                        0 as nivel
                    FROM modulos 
                    WHERE (padre_id IS NULL OR padre_id = 0)
                    
                    UNION ALL
                    
                    -- Obtener módulos hijos
                    SELECT 
                        m.id,
                        m.modulo,
                        m.padre_id,
                        m.vista,
                        m.icon_menu,
                        m.orden,
                        mj.nivel + 1
                    FROM modulos m
                    INNER JOIN ModulosJerarquia mj ON m.padre_id = mj.id
                )
                SELECT 
                    id as id,
                    CASE 
                        WHEN padre_id IS NULL OR padre_id = 0 THEN '#'
                        ELSE CAST(padre_id AS CHAR)
                    END as parent,
                    modulo as text,
                    vista,
                    COALESCE(icon_menu, 'fas fa-circle') as icon_menu,
                    orden,
                    nivel
                FROM ModulosJerarquia
                ORDER BY nivel, orden
            ");

            if (!$stmt->execute()) {
                $error = $stmt->errorInfo();
                error_log("Error al obtener módulos: " . print_r($error, true));
                return array(
                    'error' => true,
                    'mensaje' => 'Error al consultar módulos: ' . $error[2]
                );
            }

            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            if (empty($resultados)) {
                return array(
                    array(
                        'id' => '1',
                        'parent' => '#',
                        'text' => 'No hay módulos configurados',
                        'vista' => '',
                        'icon_menu' => 'fas fa-exclamation-triangle',
                        'orden' => 1,
                        'nivel' => 0
                    )
                );
            }

            // Formatear los resultados para el árbol
            foreach ($resultados as &$modulo) {
                $modulo->state = array(
                    'opened' => true
                );
                $modulo->icon = $modulo->icon_menu;
            }

            return $resultados;

        } catch (Exception $e) {
            error_log("Excepción al obtener módulos: " . $e->getMessage());
            return array(
                'error' => true,
                'mensaje' => 'Error del sistema: ' . $e->getMessage()
            );
        }
    }


    /*===================================================================*/
     //MODULOS SEGUN EL PERFIL - ADMIN O VENDE
     /*===================================================================*/
    static public function mdlObtenerModulosPorPerfil($id_perfil){
        try {
            // Consulta para obtener los módulos asignados al perfil
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    m.id,
                    m.modulo,
                    CASE 
                        WHEN pm.id_perfil IS NOT NULL THEN '1'
                        WHEN p.descripcion IN ('Administrador', 'Developer Senior', 'Super Administrador') THEN '1'
                        ELSE '0'
                    END as sel
                FROM modulos m
                LEFT JOIN perfil_modulo pm ON m.id = pm.id_modulo AND pm.id_perfil = :id_perfil
                LEFT JOIN perfiles p ON p.id_perfil = :id_perfil
                ORDER BY m.orden
            ");
            
            $stmt->bindParam(":id_perfil", $id_perfil, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                error_log("Error al ejecutar consulta de módulos por perfil: " . print_r($stmt->errorInfo(), true));
                return [];
            }
            
            $resultados = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            if (empty($resultados)) {
                error_log("No se encontraron módulos para el perfil " . $id_perfil);
            }
            
            return $resultados;
        } catch (Exception $e) {
            error_log("Error en mdlObtenerModulosPorPerfil: " . $e->getMessage());
            return [];
        }
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