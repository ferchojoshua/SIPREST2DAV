<?php

require_once "conexion.php";


class UsuarioModelo
{

    /*===================================================================*/
    //PARA EL INICIO DE SESION
    /*===================================================================*/
    static public function mdlIniciarSesion($usuario, $password)
    {
        try {
            $stmt_user = Conexion::conectar()->prepare("
                SELECT u.*, 
                       p.descripcion as perfil,
                       p.id_perfil,
                       s.id as sucursal_id,
                       s.nombre as sucursal_nombre,
                       s.codigo as sucursal_codigo
                FROM usuarios u 
                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                LEFT JOIN sucursales s ON u.sucursal_id = s.id
                WHERE u.usuario = :usuario 
                AND u.estado = 1
            ");
            
            $stmt_user->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $stmt_user->execute();
            $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

            if (!$user_data) {
                error_log("Usuario no encontrado: " . $usuario);
                return null;
            }

            // Verificar la contraseña
            if (hash_equals($user_data['clave'], crypt($password, $user_data['clave']))) {
                // Convertir a objeto y agregar información adicional
                $user_object = (object) $user_data;
                
                // Obtener vista inicial
                $stmt_vista = Conexion::conectar()->prepare("
                    SELECT m.vista
                    FROM usuarios u 
                    INNER JOIN perfil_modulo pm ON pm.id_perfil = u.id_perfil_usuario
                    INNER JOIN modulos m ON m.id = pm.id_modulo
                    WHERE u.id_usuario = :id_usuario
                    AND vista_inicio = 1
                    LIMIT 1
                ");
                
                $stmt_vista->bindParam(":id_usuario", $user_data['id_usuario'], PDO::PARAM_INT);
                $stmt_vista->execute();
                $vista_data = $stmt_vista->fetch(PDO::FETCH_ASSOC);
                
                // Agregar vista al objeto de usuario
                $user_object->vista = $vista_data ? $vista_data['vista'] : 'dashboard.php';
                
                error_log("Login exitoso para usuario: " . $usuario . " con perfil: " . $user_object->perfil);
                return $user_object;
            }
            
            error_log("Contraseña incorrecta para usuario: " . $usuario);
            return null;
            
        } catch (Exception $e) {
            error_log("Error en mdlIniciarSesion: " . $e->getMessage());
            return null;
        }
    }


    /*===================================================================*/
    //PARA EL INICIO DE SESION - VERSION SIMPLIFICADA PARA RESET
    /*===================================================================*/
    static public function mdlIniciarSesionSimple($usuario, $password)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT u.*, 
                       p.descripcion as perfil,
                       p.id_perfil,
                       s.id as sucursal_id,
                       s.nombre as sucursal_nombre,
                       s.codigo as sucursal_codigo
                FROM usuarios u 
                LEFT JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                LEFT JOIN sucursales s ON u.sucursal_id = s.id
                WHERE u.usuario = :usuario
                AND u.estado = 1
            ");

            $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user_data) {
                error_log("Usuario no encontrado (simple): " . $usuario);
                return null;
            }

            // Verificar la contraseña
            if (hash_equals($user_data['clave'], crypt($password, $user_data['clave']))) {
                // Convertir a objeto y agregar información adicional
                $user_object = (object) $user_data;
                
                // Obtener vista inicial
                $stmt_vista = Conexion::conectar()->prepare("
                    SELECT m.vista
                    FROM usuarios u 
                    INNER JOIN perfil_modulo pm ON pm.id_perfil = u.id_perfil_usuario
                    INNER JOIN modulos m ON m.id = pm.id_modulo
                    WHERE u.id_usuario = :id_usuario
                    AND vista_inicio = 1
                    LIMIT 1
                ");
                
                $stmt_vista->bindParam(":id_usuario", $user_data['id_usuario'], PDO::PARAM_INT);
                $stmt_vista->execute();
                $vista_data = $stmt_vista->fetch(PDO::FETCH_ASSOC);
                
                // Agregar vista al objeto de usuario
                $user_object->vista = $vista_data ? $vista_data['vista'] : 'dashboard.php';
                
                error_log("Login simple exitoso para usuario: " . $usuario . " con perfil: " . $user_object->perfil);
                return $user_object;
            }
            
            error_log("Contraseña incorrecta (simple) para usuario: " . $usuario);
            return null;
            
        } catch (Exception $e) {
            error_log("Error en mdlIniciarSesionSimple: " . $e->getMessage());
            return null;
        }
    }


    /*===================================================================*/
    //OBTENEMOS LOS MENUS -  PADRES
    /*===================================================================*/
    static public function mdlObtenerMenuUsuario($id_usuario)
    {

        $stmt = Conexion::conectar()->prepare("SELECT m.id,
                                                    m.modulo,
                                                    m.icon_menu,
                                                    m.vista,
                                                    pm.vista_inicio
                                                    from usuarios u inner join perfiles p on u.id_perfil_usuario = p.id_perfil
                                                    inner join perfil_modulo pm on pm.id_perfil = p.id_perfil
                                                    inner join modulos m on m.id = pm.id_modulo
                                                    where u.id_usuario = :id_usuario
                                                    and (m.padre_id is null or m.padre_id = 0)
                                                    order by m.orden");

        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }



    /*===================================================================*/
    //OBTENEMOS LOS SUBMENUS -  HIJOS
    /*===================================================================*/
    static public function mdlObtenerSubMenuUsuario($idMenu,$id_usuario)
    {

        $stmt = Conexion::conectar()->prepare("SELECT m.id,m.modulo,m.icon_menu,m.vista,pm.vista_inicio
                                                from usuarios u inner join perfiles p on u.id_perfil_usuario = p.id_perfil
                                                inner join perfil_modulo pm on pm.id_perfil = p.id_perfil
                                                inner join modulos m on m.id = pm.id_modulo
                                                where u.id_usuario = :id_usuario
                                                and m.padre_id = :idMenu
                                                order by m.orden");

        $stmt->bindParam(":idMenu", $idMenu, PDO::PARAM_STR);
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS);
    }



    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlListarUsuarios()
    {
        $smt = Conexion::conectar()->prepare('call SP_LISTAR_USUARIOS()');
        $smt->execute();
        return $smt->fetchAll(PDO::FETCH_ASSOC);

  
    }

    /*===================================================================*/
    //LISTAR PERFILES EN COMBOBOX
    /*===================================================================*/
    static public function mdlListarSelectPerfiles()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id_perfil, descripcion 
                                                 FROM perfiles 
                                                 WHERE estado = 1 
                                                 ORDER BY descripcion");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error en mdlListarSelectPerfiles: " . $e->getMessage());
            throw $e;
        } finally {
            if ($stmt) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }



    /*===================================================================*/
    //REGISTRAR USUARIOS
    /*===================================================================*/
    static public function mdlRegistrarUsuario($nombre_usuario, $apellido_usuario, $usuario, $clave, $id_perfil_usuario, $sucursal_id, $telefono_whatsapp, $whatsapp_activo, $whatsapp_admin, $cedula, $ciudad, $direccion, $profesion, $cargo, $celular, $fecha_ingreso, $numero_seguro, $forma_pago)
    {
        try {
            $stmt = Conexion::conectar()->prepare("INSERT INTO usuarios(
                nombre_usuario, 
                apellido_usuario, 
                usuario, 
                clave, 
                id_perfil_usuario, 
                sucursal_id,
                telefono_whatsapp,
                whatsapp_activo,
                whatsapp_admin,
                cedula,
                ciudad,
                direccion,
                profesion,
                cargo,
                celular,
                fecha_ingreso,
                numero_seguro,
                forma_pago,
                estado
            ) VALUES (
                :nombre_usuario, 
                :apellido_usuario, 
                :usuario, 
                :clave, 
                :id_perfil_usuario, 
                :sucursal_id,
                :telefono_whatsapp,
                :whatsapp_activo,
                :whatsapp_admin,
                :cedula,
                :ciudad,
                :direccion,
                :profesion,
                :cargo,
                :celular,
                :fecha_ingreso,
                :numero_seguro,
                :forma_pago,
                1
            )");

            $stmt->bindParam(":nombre_usuario", $nombre_usuario, PDO::PARAM_STR);
            $stmt->bindParam(":apellido_usuario", $apellido_usuario, PDO::PARAM_STR);
            $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
            $stmt->bindParam(":clave", $clave, PDO::PARAM_STR);
            $stmt->bindParam(":id_perfil_usuario", $id_perfil_usuario, PDO::PARAM_INT);
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->bindParam(":telefono_whatsapp", $telefono_whatsapp, PDO::PARAM_STR);
            $stmt->bindParam(":whatsapp_activo", $whatsapp_activo, PDO::PARAM_INT);
            $stmt->bindParam(":whatsapp_admin", $whatsapp_admin, PDO::PARAM_INT);
            $stmt->bindParam(":cedula", $cedula, PDO::PARAM_STR);
            $stmt->bindParam(":ciudad", $ciudad, PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $direccion, PDO::PARAM_STR);
            $stmt->bindParam(":profesion", $profesion, PDO::PARAM_STR);
            $stmt->bindParam(":cargo", $cargo, PDO::PARAM_STR);
            $stmt->bindParam(":celular", $celular, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_ingreso", $fecha_ingreso, PDO::PARAM_STR);
            $stmt->bindParam(":numero_seguro", $numero_seguro, PDO::PARAM_STR);
            $stmt->bindParam(":forma_pago", $forma_pago, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return "ok";
            } else {
                $error = $stmt->errorInfo();
                error_log("Error al registrar usuario: " . print_r($error, true));
                return "error: " . $error[2];
            }
        } catch (Exception $e) {
            error_log("Excepción al registrar usuario: " . $e->getMessage());
            return "error: " . $e->getMessage();
        } finally {
            if ($stmt) {
                $stmt->closeCursor();
                $stmt = null;
            }
        }
    }
 
 
     /*=============================================
    ACTUALIZAR DATOS DEL USUARIO
    =============================================*/
  
static public function mdlActualizarUsuario($table, $data, $id, $nameId)
{
    try {
        // Campos permitidos para actualizar (lista completa basada en tu formulario)
        $camposPermitidos = [
            'nombre_usuario',
            'apellido_usuario', 
            'usuario',
            'clave', // Solo si se proporciona
            'id_perfil_usuario',
            'sucursal_id',
            'cedula',
            'celular',
            'ciudad',
            'direccion',
            'profesion',
            'cargo',
            'fecha_ingreso',
            'numero_seguro',
            'forma_pago',
            'telefono_whatsapp',
            'whatsapp_activo',
            'whatsapp_admin',
            'estado'
        ];

        // Filtrar solo los campos permitidos que vienen en $data
        $datosLimpios = array();
        foreach ($data as $key => $value) {
            if (in_array($key, $camposPermitidos)) {
                // Si es la clave y está vacía, no la incluimos en la actualización
                if ($key === 'clave' && empty($value)) {
                    continue;
                }
                $datosLimpios[$key] = $value;
            }
        }

        // Verificar que hay datos para actualizar
        if (empty($datosLimpios)) {
            return array("error" => "No hay datos válidos para actualizar");
        }

        // Construir la consulta SET dinámicamente
        $set = "";
        foreach ($datosLimpios as $key => $value) {
            $set .= $key . " = :" . $key . ", ";
        }
        $set = rtrim($set, ", "); // Quitar la última coma y espacio

        // Preparar la consulta SQL
        $sql = "UPDATE $table SET $set WHERE $nameId = :$nameId";
        $stmt = Conexion::conectar()->prepare($sql);

        // Vincular parámetros
        foreach ($datosLimpios as $key => $value) {
            // Determinar el tipo de parámetro
            if ($key === 'id_perfil_usuario' || $key === 'sucursal_id' || 
                $key === 'whatsapp_activo' || $key === 'whatsapp_admin' || 
                $key === 'estado') {
                $stmt->bindParam(":" . $key, $datosLimpios[$key], PDO::PARAM_INT);
            } else {
                $stmt->bindParam(":" . $key, $datosLimpios[$key], PDO::PARAM_STR);
            }
        }

        // Vincular el ID
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return "ok";
        } else {
            $errorInfo = $stmt->errorInfo();
            return array(
                "error" => "Error en la base de datos",
                "details" => $errorInfo[2],
                "sql_state" => $errorInfo[0],
                "error_code" => $errorInfo[1]
            );
        }

    } catch (PDOException $e) {
        return array(
            "error" => "Error de PDO: " . $e->getMessage(),
            "code" => $e->getCode()
        );
    } catch (Exception $e) {
        return array(
            "error" => "Error general: " . $e->getMessage()
        );
    }
}

    /*=============================================
    ACTIVAR USUARIO
    =============================================*/
    static public function mdlActivarUsuario($table, $id, $nameId)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $table SET estado = 1 WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }

    /*=============================================
    DAR DE BAJA USUARIO
    =============================================*/
    static public function mdlBajaUsuario($table, $id, $nameId)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $table SET estado = 0 WHERE $nameId = :$nameId");
        $stmt->bindParam(":" . $nameId, $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return "ok";
        } else {
            return Conexion::conectar()->errorInfo();
        }
    }

    /*=============================================
    ELIMINAR DATOS DEL USUARIO
    =============================================*/

    static public function mdlEliminarUsuario($table, $id, $nameId)
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
    ACTUALIZAR DATOS DEL USUARIO
    =============================================*/
    static public function mdlActualizarClaveUsuario($table, $data, $id, $nameId)
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
    VERIFICAR SI USUARIO EXISTE PARA RESET
    =============================================*/
    static public function mdlVerificarUsuarioExiste($usuario)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM usuarios WHERE usuario = :usuario");
        $stmt->bindParam(":usuario", $usuario, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /*===================================================================*/
    // OBTENER TODOS LOS USUARIOS CON PERFIL "COLECTOR"
    /*===================================================================*/
    static public function mdlObtenerColectores()
    {
        $stmt = Conexion::conectar()->prepare("SELECT u.id_usuario, u.nombre_usuario, u.apellido_usuario
                                            FROM usuarios u
                                            INNER JOIN perfiles p ON u.id_perfil_usuario = p.id_perfil
                                            WHERE p.descripcion = 'Cobrador' AND u.estado = 1
                                            ORDER BY u.nombre_usuario ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
