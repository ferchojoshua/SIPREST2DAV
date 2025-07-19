<?php

require_once "conexion.php";

class ClienteModelo
{

    /*===================================================================*/
    //LISTAR CLIENTES EN DATATABLES
    /*===================================================================*/
    static public function mdlListarSelectClientes()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT 
                                                    cliente_id,
                                                    CONCAT(COALESCE(cliente_nombres, ''), ' - ', COALESCE(cliente_dni, '')) as cliente_nombres
                                                FROM 
                                                    clientes 
                                                WHERE 
                                                    cliente_estatus = '1'
                                                ORDER BY 
                                                    cliente_nombres ASC");
            
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $resultado;
            
        } catch (PDOException $e) {
            error_log("Error en mdlListarSelectClientes: " . $e->getMessage());
            return false;
        }
    }



    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlListarClientes($sucursal_id)
    {
        // Por ahora usar SP_LISTAR_CLIENTES_TABLE hasta que se resuelva el tema de sucursales
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_CLIENTES_TABLE()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_NUM);
    }

    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE (ASOCIATIVO)
    =============================================*/
    static public function mdlListarClientesForDataTableAssoc($sucursal_id)
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_CLIENTES_TABLE()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);
    }




    /*=============================================
    REGISTRAR CLIENTE
    =============================================*/
    static public function mdlRegistrarCliente($sucursal_id, $cliente_nombres, $cliente_dni, $cliente_cel, $cliente_direccion, $cliente_correo,
                                                 $cliente_empresa_laboral, $cliente_cargo_laboral, $cliente_tel_laboral, $cliente_dir_laboral,
                                                 $cliente_refe_per_nombre, $cliente_refe_per_cel, $cliente_refe_per_dir,
                                                 $cliente_refe_fami_nombre, $cliente_refe_fami_cel, $cliente_refe_fami_dir)
    {
        try {
            $stmt = Conexion::conectar()->prepare("INSERT INTO clientes  (sucursal_id, cliente_nombres, 
                                                                          cliente_dni,
                                                                          cliente_cel,
                                                                          cliente_direccion ,
                                                                          cliente_correo, 
                                                                          cliente_estatus, 
                                                                          cliente_refe_per_nombre,
                                                                          cliente_refe_per_cel,
                                                                          cliente_refe_per_dir,
                                                                          cliente_empresa_laboral,
                                                                          cliente_cargo_laboral,
                                                                          cliente_tel_laboral,
                                                                          cliente_dir_laboral,
                                                                          cliente_refe_fami_nombre,
                                                                          cliente_refe_fami_cel,
                                                                          cliente_refe_fami_dir)
                                                                VALUES (:sucursal_id, :cliente_nombres, 
                                                                        :cliente_dni,
                                                                        :cliente_cel,
                                                                        :cliente_direccion,
                                                                        :cliente_correo, '1', 
                                                                        :cliente_refe_per_nombre, 
                                                                        :cliente_refe_per_cel,
                                                                        :cliente_refe_per_dir,
                                                                        :cliente_empresa_laboral,
                                                                        :cliente_cargo_laboral,
                                                                        :cliente_tel_laboral,
                                                                        :cliente_dir_laboral,
                                                                        :cliente_refe_fami_nombre,
                                                                        :cliente_refe_fami_cel,
                                                                        :cliente_refe_fami_dir)");

            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->bindParam(":cliente_nombres", $cliente_nombres, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_dni", $cliente_dni, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_cel", $cliente_cel, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_direccion", $cliente_direccion, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_correo", $cliente_correo, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_refe_per_nombre", $cliente_refe_per_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_refe_per_cel", $cliente_refe_per_cel, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_refe_per_dir", $cliente_refe_per_dir, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_empresa_laboral", $cliente_empresa_laboral, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_cargo_laboral", $cliente_cargo_laboral, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_tel_laboral", $cliente_tel_laboral, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_dir_laboral", $cliente_dir_laboral, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_refe_fami_nombre", $cliente_refe_fami_nombre, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_refe_fami_cel", $cliente_refe_fami_cel, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_refe_fami_dir", $cliente_refe_fami_dir, PDO::PARAM_STR);

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
    ACTUALIZAR DATOS DEL CLIENTE
    =============================================*/
    static public function mdlActualizarCliente($table, $data, $id, $nameId)
    {
        try {
            $set = "";

            foreach ($data as $key => $value) {
                $set .= $key . " = :" . $key . ","; //DEPENDE DEL ARRAY QUE VIENE DEL AJAX
            }

            $set = substr($set, 0, -1); //QUITA LA COMA

            $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";
            
            // Debug: Log de la consulta SQL (comentado para producción)
            // error_log("SQL Query: " . $sql);
            // error_log("Data: " . print_r($data, true));
            // error_log("ID: " . $id);

            $stmt = Conexion::conectar()->prepare($sql);

            foreach ($data as $key => $value) {
                $stmt->bindParam(":" . $key, $data[$key], PDO::PARAM_STR);
            }

            $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return "ok";
            } else {
                $errorInfo = $stmt->errorInfo();
                // error_log("Error en actualización: " . print_r($errorInfo, true));
                return array("error" => $errorInfo[2], "code" => $errorInfo[0]);
            }
        } catch (Exception $e) {
            // error_log("Excepción en actualización: " . $e->getMessage());
            return array("error" => $e->getMessage());
        }
    }


    /*=============================================
    ELIMINAR DATOS DEL USUARIO
    =============================================*/

    static public function mdlEliminarCliente($table, $id, $nameId)
    {

        $stmt = Conexion::conectar()->prepare("DELETE FROM $table WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";;
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }


    /*=============================================
    DUPLICADO DEL DOCUMENTO DEL CLIENTE
    =============================================*/
    static public function mdlVerificarDuplicadoDocument($cliente_dni)
    {
        $stmt = Conexion::conectar()->prepare("SELECT   count(*) as ex
                                                FROM clientes c 
                                                 WHERE c.cliente_dni = :cliente_dni");

        $stmt->bindParam(":cliente_dni", $cliente_dni, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ); //devuelve 1 registro
        //  var_dump($stmt);

    }



    /*=============================================
     DATOS A TEXBOX
    =============================================*/
    static public function mdlObtenerDataClienteTexbox($cliente_dni)
    {
        $stmt = Conexion::conectar()->prepare("SELECT 
                                                    cliente_id,
                                                    cliente_dni,
                                                    cliente_nombres,
                                                    cliente_cel,
                                                    cliente_direccion,
                                                    cliente_correo,
                                                    cliente_empresa_laboral,
                                                    cliente_cargo_laboral,
                                                    cliente_tel_laboral,
                                                    cliente_dir_laboral,
                                                    cliente_refe_per_nombre,
                                                    cliente_refe_per_cel,
                                                    cliente_refe_per_dir,
                                                    cliente_refe_fami_nombre,
                                                    cliente_refe_fami_cel,
                                                    cliente_refe_fami_dir
                                                    FROM
                                                        clientes 
                                                        where cliente_dni = :cliente_dni ");

        $stmt->bindParam(":cliente_dni", $cliente_dni, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
        var_dump($stmt);
    }


    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlListarClientesPrestamo()
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_CLIENTES_PRESTAMO()');
        $smt->execute();
        return $smt->fetchAll();
    }



    /*===================================================================*/
    // REGISTRAR REFENCIAS
    /*===================================================================*/
    static public function mdlRegistrarReferencias($cliente_id, $refe_personal, $refe_cel_per, $refe_familiar, $refe_cel_fami, $refe_empresa_laboral, $refe_cargo_laboral, $refe_tel_laboral, $refe_dir_laboral)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO referencias(cliente_id, refe_personal, refe_cel_per, refe_familiar, refe_cel_fami, refe_empresa_laboral, refe_cargo_laboral, refe_tel_laboral, refe_dir_laboral) 
            VALUES(:cliente_id, :refe_personal, :refe_cel_per, :refe_familiar, :refe_cel_fami, :refe_empresa_laboral, :refe_cargo_laboral, :refe_tel_laboral, :refe_dir_laboral)");

        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(":refe_personal", $refe_personal, PDO::PARAM_STR);
        $stmt->bindParam(":refe_cel_per", $refe_cel_per, PDO::PARAM_STR);
        $stmt->bindParam(":refe_familiar", $refe_familiar, PDO::PARAM_STR);
        $stmt->bindParam(":refe_cel_fami", $refe_cel_fami, PDO::PARAM_STR);
        $stmt->bindParam(":refe_empresa_laboral", $refe_empresa_laboral, PDO::PARAM_STR);
        $stmt->bindParam(":refe_cargo_laboral", $refe_cargo_laboral, PDO::PARAM_STR);
        $stmt->bindParam(":refe_tel_laboral", $refe_tel_laboral, PDO::PARAM_STR);
        $stmt->bindParam(":refe_dir_laboral", $refe_dir_laboral, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return "error";
        }
        $stmt = null;
    }


    /*=============================================
     DATOS A TEXBOX DE REFERENCIAS
    =============================================*/
    static public function mdlTraerRefe($cliente_id)
    {
        $stmt = Conexion::conectar()->prepare("SELECT
                                                    cliente_id,
                                                    refe_personal,
                                                    refe_cel_per,
                                                    refe_per_dir,
                                                    refe_familiar,
                                                    refe_cel_fami,
                                                    refe_fami_dir
                                                        
                                                    FROM
                                                        referencias 
                                                        where cliente_id = :cliente_id ");

        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /*===================================================================*/
    //BUSCAR CLIENTES PARA SELECT2
    /*===================================================================*/
    static public function mdlBuscarClientesParaSelect($busqueda)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT 
                                                    cliente_id,
                                                    cliente_nombres,
                                                    cliente_dni
                                                FROM 
                                                    clientes 
                                                WHERE 
                                                    cliente_estatus = '1'
                                                    AND (
                                                        cliente_nombres LIKE :busqueda 
                                                        OR cliente_dni LIKE :busqueda
                                                    )
                                                ORDER BY 
                                                    cliente_nombres ASC
                                                LIMIT 10");
            
            $busqueda_param = "%" . $busqueda . "%";
            $stmt->bindParam(":busqueda", $busqueda_param, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Error en mdlBuscarClientesParaSelect: " . $e->getMessage());
            return false;
        }
    }

}
