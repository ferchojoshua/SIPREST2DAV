<?php

require_once "conexion.php";

class PerfilModuloModelo{


    /*===================================================================*/
    //REGISTRAR PERFIL MODULO
    /*===================================================================*/
    static public function mdlRegistrarPerfilModulo($array_idModulos, $idPerfil, $id_modulo_inicio){
        try {
            $total_registros = 0;

            // Primero verificar si es un perfil administrativo
            $stmt = Conexion::conectar()->prepare("
                SELECT descripcion 
                FROM perfiles 
                WHERE id_perfil = :id_perfil
            ");
            $stmt->bindParam(":id_perfil", $idPerfil, PDO::PARAM_INT);
            $stmt->execute();
            $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si es un perfil administrativo, dar acceso a todos los módulos
            if ($perfil && in_array(strtolower($perfil['descripcion']), ['administrador', 'developer senior', 'super administrador'])) {
                // Primero eliminar los módulos existentes
                $stmt = Conexion::conectar()->prepare("DELETE FROM perfil_modulo WHERE id_perfil = :id_perfil");
                $stmt->bindParam(":id_perfil", $idPerfil, PDO::PARAM_INT);
                $stmt->execute();

                // Obtener todos los módulos del sistema
                $stmt = Conexion::conectar()->prepare("SELECT id FROM modulos");
                $stmt->execute();
                $modulos = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Asignar todos los módulos al perfil
                foreach ($modulos as $id_modulo) {
                    $vista_inicio = ($id_modulo == $id_modulo_inicio) ? 1 : 0;
                    
                    $stmt = Conexion::conectar()->prepare("
                        INSERT INTO perfil_modulo(
                            id_perfil,
                            id_modulo,
                            vista_inicio,
                            estado
                        ) VALUES (
                            :id_perfil,
                            :id_modulo,
                            :vista_inicio,
                            1
                        )
                    ");

                    $stmt->bindParam(":id_perfil", $idPerfil, PDO::PARAM_INT);
                    $stmt->bindParam(":id_modulo", $id_modulo, PDO::PARAM_INT);
                    $stmt->bindParam(":vista_inicio", $vista_inicio, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        $total_registros++;
                    }
                }
            } else {
                // Para perfiles no administrativos, usar la lógica original
                $stmt = Conexion::conectar()->prepare("DELETE FROM perfil_modulo WHERE id_perfil = :id_perfil");
                $stmt->bindParam(":id_perfil", $idPerfil, PDO::PARAM_INT);
                $stmt->execute();

                foreach ($array_idModulos as $value) {
                    $vista_inicio = ($value == $id_modulo_inicio) ? 1 : 0;

                    $stmt = Conexion::conectar()->prepare("
                        INSERT INTO perfil_modulo(
                            id_perfil,
                            id_modulo,
                            vista_inicio,
                            estado
                        ) VALUES (
                            :id_perfil,
                            :id_modulo,
                            :vista_inicio,
                            1
                        )
                    ");

                    $stmt->bindParam(":id_perfil", $idPerfil, PDO::PARAM_INT);
                    $stmt->bindParam(":id_modulo", $value, PDO::PARAM_INT);
                    $stmt->bindParam(":vista_inicio", $vista_inicio, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        $total_registros++;
                    }
                }
            }

            return $total_registros;
        } catch (Exception $e) {
            error_log("Error en mdlRegistrarPerfilModulo: " . $e->getMessage());
            return 0;
        }
    }
}