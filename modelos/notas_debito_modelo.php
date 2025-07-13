<?php
require_once "conexion.php";

class NotasDebitoModelo {
    
    static public function mdlRegistrarNotaDebito($nro_prestamo, $nuevo_monto, $motivo) {
        try {
            $stmt = Conexion::conectar()->prepare("INSERT INTO notas_debito (
                nro_nota_debito,
                nro_prestamo,
                motivo,
                monto_total_nuevo,
                id_usuario,
                fecha_registro,
                estado
            ) VALUES (
                :nro_nota_debito,
                :nro_prestamo,
                :motivo,
                :nuevo_monto,
                :id_usuario,
                NOW(),
                'ACTIVO'
            )");
            
            // Generar número de nota de débito
            $nro_nota = 'ND-' . date('Ymd') . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
            
            $stmt->bindParam(":nro_nota_debito", $nro_nota, PDO::PARAM_STR);
            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":motivo", $motivo, PDO::PARAM_STR);
            $stmt->bindParam(":nuevo_monto", $nuevo_monto, PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $_SESSION['id_usuario'], PDO::PARAM_INT);
            
            if($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    static public function mdlListarNotasDebito() {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    nd.nro_nota_debito as nro_nota,
                    nd.fecha_registro,
                    nd.nro_prestamo,
                    c.cliente_nombres as cliente,
                    pc.pres_monto_total as monto_original,
                    nd.monto_total_nuevo as nuevo_monto,
                    nd.motivo,
                    nd.estado
                FROM notas_debito nd
                JOIN prestamo_cabecera pc ON nd.nro_prestamo = pc.nro_prestamo
                JOIN clientes c ON pc.cliente_id = c.cliente_id
                ORDER BY nd.fecha_registro DESC
            ");
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return array();
        }
    }

    static public function mdlAnularNotaDebito($nro_nota) {
        try {
            $stmt = Conexion::conectar()->prepare("
                UPDATE notas_debito 
                SET estado = 'ANULADO' 
                WHERE nro_nota_debito = :nro_nota
            ");
            
            $stmt->bindParam(":nro_nota", $nro_nota, PDO::PARAM_STR);
            
            if($stmt->execute()) {
                return "ok";
            } else {
                return "error";
            }
        } catch (PDOException $e) {
            return "Error: " . $e->getMessage();
        }
    }

    static public function mdlObtenerNotaDebito($nro_nota) {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    nd.*,
                    c.cliente_nombres as cliente,
                    pc.pres_monto_total as monto_original,
                    u.nombre_usuario as usuario
                FROM notas_debito nd
                JOIN prestamo_cabecera pc ON nd.nro_prestamo = pc.nro_prestamo
                JOIN clientes c ON pc.cliente_id = c.cliente_id
                JOIN usuarios u ON nd.id_usuario = u.id_usuario
                WHERE nd.nro_nota_debito = :nro_nota
            ");
            
            $stmt->bindParam(":nro_nota", $nro_nota, PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?> 