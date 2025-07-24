<?php

require_once "conexion.php";

class WhatsAppModelo
{
    /*===================================================================*/
    // ENVIAR MENSAJE VIA MAYTAPI
    /*===================================================================*/
    static public function mdlEnviarMensaje($telefono, $mensaje, $tipo = 'text')
    {
        try {
            $url = MAYTAPI_BASE_URL . '/' . MAYTAPI_PRODUCT_ID . '/' . MAYTAPI_PHONE_ID . '/sendMessage';
            
            $data = [
                'to_number' => $telefono,
                'type' => $tipo,
                'message' => $mensaje
            ];
            
            $options = [
                'http' => [
                    'header' => [
                        "Content-Type: application/json",
                        "x-maytapi-key: " . MAYTAPI_API_TOKEN
                    ],
                    'method' => 'POST',
                    'content' => json_encode($data)
                ]
            ];
            
            $context = stream_context_create($options);
            $result = file_get_contents($url, false, $context);
            
            return json_decode($result, true);
            
        } catch (Exception $e) {
            error_log("Error enviando WhatsApp: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /*===================================================================*/
    // REGISTRAR MENSAJE ENVIADO EN BD
    /*===================================================================*/
    static public function mdlRegistrarMensajeEnviado($telefono, $mensaje, $tipo, $estado)
    {
        try {
            $stmt = Conexion::conectar()->prepare("INSERT INTO whatsapp_mensajes 
                                                   (telefono, mensaje, tipo, estado, fecha_envio) 
                                                   VALUES (:telefono, :mensaje, :tipo, :estado, NOW())");
            
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->bindParam(":mensaje", $mensaje, PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
            $stmt->bindParam(":estado", $estado, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return Conexion::conectar()->lastInsertId();
            }
            return false;
            
        } catch (Exception $e) {
            error_log("Error registrando mensaje WhatsApp: " . $e->getMessage());
            return false;
        }
    }
    
    /*===================================================================*/
    // OBTENER DATOS DE CLIENTE PARA WHATSAPP
    /*===================================================================*/
    static public function mdlObtenerDatosCliente($cliente_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT cliente_id, cliente_nombres, cliente_dni, cliente_cel 
                                                   FROM clientes 
                                                   WHERE cliente_id = :cliente_id AND cliente_estatus = 1");
            
            $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error obteniendo datos cliente: " . $e->getMessage());
            return false;
        }
    }
    
    /*===================================================================*/
    // VERIFICAR SI CLIENTE ESTÁ EN OPT-OUT
    /*===================================================================*/
    static public function mdlVerificarOptOut($telefono)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT COUNT(*) as count FROM whatsapp_opt_out 
                                                   WHERE telefono = :telefono AND activo = 1");
            
            $stmt->bindParam(":telefono", $telefono, PDO::PARAM_STR);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] > 0;
            
        } catch (Exception $e) {
            error_log("Error verificando opt-out: " . $e->getMessage());
            return false;
        }
    }
}
?> 