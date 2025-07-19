<?php

require_once "conexion.php";

class ConsecutivosModelo
{
    /**
     * Obtener próximo número de préstamo por sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlObtenerConsecutivoPrestamo($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para préstamos, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('CALL SP_OBTENER_CONSECUTIVO_PRESTAMO_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error al obtener consecutivo de préstamo: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Incrementar consecutivo de préstamo por sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlIncrementarConsecutivoPrestamo($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para incrementar préstamos, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('CALL SP_INCREMENTAR_CONSECUTIVO_PRESTAMO_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al incrementar consecutivo de préstamo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener próximo número de recibo por sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlObtenerConsecutivoRecibo($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para recibos, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('CALL SP_OBTENER_CONSECUTIVO_RECIBO_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error al obtener consecutivo de recibo: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Incrementar consecutivo de recibo por sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlIncrementarConsecutivoRecibo($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para incrementar recibos, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('CALL SP_INCREMENTAR_CONSECUTIVO_RECIBO_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al incrementar consecutivo de recibo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener próximo número de voucher por sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlObtenerConsecutivoVoucher($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para vouchers, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('CALL SP_OBTENER_CONSECUTIVO_VOUCHER_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error al obtener consecutivo de voucher: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Incrementar consecutivo de voucher por sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlIncrementarConsecutivoVoucher($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para incrementar vouchers, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('CALL SP_INCREMENTAR_CONSECUTIVO_VOUCHER_SUCURSAL(:sucursal_id)');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al incrementar consecutivo de voucher: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener todos los consecutivos de una sucursal
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlObtenerConsecutivosSucursal($sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para consultar consecutivos, usando sucursal por defecto (1)");
                }
            }
            
            $stmt = Conexion::conectar()->prepare('SELECT * FROM v_consecutivos_sucursales WHERE sucursal_id = :sucursal_id');
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error al obtener consecutivos de sucursal: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Listar consecutivos de todas las sucursales
     */
    static public function mdlListarConsecutivosSucursales()
    {
        try {
            $stmt = Conexion::conectar()->prepare('SELECT * FROM v_consecutivos_sucursales');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error al listar consecutivos de sucursales: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Resetear consecutivos de una sucursal
     */
    static public function mdlResetearConsecutivosSucursal($sucursal_id, $tipo_consecutivo = 'todos')
    {
        try {
            $pdo = Conexion::conectar();
            
            switch ($tipo_consecutivo) {
                case 'prestamos':
                    $stmt = $pdo->prepare('UPDATE sucursales SET consecutivo_prestamos = 1 WHERE id = :sucursal_id');
                    break;
                case 'recibos':
                    $stmt = $pdo->prepare('UPDATE sucursales SET consecutivo_recibos = 1 WHERE id = :sucursal_id');
                    break;
                case 'vouchers':
                    $stmt = $pdo->prepare('UPDATE sucursales SET consecutivo_vouchers = 1 WHERE id = :sucursal_id');
                    break;
                case 'todos':
                default:
                    $stmt = $pdo->prepare('UPDATE sucursales SET consecutivo_prestamos = 1, consecutivo_recibos = 1, consecutivo_vouchers = 1 WHERE id = :sucursal_id');
                    break;
            }
            
            $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Error al resetear consecutivos: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener consecutivo actual (sistema antiguo para compatibilidad)
     */
    static public function mdlObtenerCorrelativoGlobal()
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_OBTENER_NRO_CORRELATIVO()');
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error al obtener correlativo global: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generar número de documento con formato específico
     * Si no se especifica sucursal, toma la del usuario logueado
     */
    static public function mdlGenerarNumeroDocumento($tipo_documento, $sucursal_id = null)
    {
        try {
            // Si no se especifica sucursal, tomar la del usuario logueado
            if ($sucursal_id === null) {
                if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->sucursal_id)) {
                    $sucursal_id = $_SESSION["usuario"]->sucursal_id;
                } else {
                    // Si no hay sesión, usar sucursal por defecto (1)
                    $sucursal_id = 1;
                    error_log("Consecutivos: No se pudo obtener sucursal del usuario para generar documento, usando sucursal por defecto (1)");
                }
            }
            
            switch (strtolower($tipo_documento)) {
                case 'prestamo':
                    $consecutivo = self::mdlObtenerConsecutivoPrestamo($sucursal_id);
                    return $consecutivo ? $consecutivo->nro_prestamo : null;
                    
                case 'recibo':
                    $consecutivo = self::mdlObtenerConsecutivoRecibo($sucursal_id);
                    return $consecutivo ? $consecutivo->nro_recibo : null;
                    
                case 'voucher':
                    $consecutivo = self::mdlObtenerConsecutivoVoucher($sucursal_id);
                    return $consecutivo ? $consecutivo->nro_voucher : null;
                    
                default:
                    return null;
            }
        } catch (Exception $e) {
            error_log("Error al generar número de documento: " . $e->getMessage());
            return null;
        }
    }

    /*=================================================================*/
    // FUNCIONES DE CONVENIENCIA (sin parámetro de sucursal)
    /*=================================================================*/

    /**
     * Obtener información de la sucursal del usuario actual
     */
    static public function mdlObtenerInfoSucursalUsuario()
    {
        try {
            if (!isset($_SESSION["usuario"]) || !isset($_SESSION["usuario"]->sucursal_id)) {
                return null;
            }

            $stmt = Conexion::conectar()->prepare("SELECT id, codigo, nombre FROM sucursales WHERE id = :sucursal_id");
            $stmt->bindParam(":sucursal_id", $_SESSION["usuario"]->sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (Exception $e) {
            error_log("Error al obtener info de sucursal del usuario: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generar número de préstamo para la sucursal del usuario
     */
    static public function mdlGenerarNumeroPrestamo()
    {
        return self::mdlGenerarNumeroDocumento('prestamo');
    }

    /**
     * Generar número de recibo para la sucursal del usuario
     */
    static public function mdlGenerarNumeroRecibo()
    {
        return self::mdlGenerarNumeroDocumento('recibo');
    }

    /**
     * Generar número de voucher para la sucursal del usuario
     */
    static public function mdlGenerarNumeroVoucher()
    {
        return self::mdlGenerarNumeroDocumento('voucher');
    }

    /**
     * Confirmar uso de consecutivo (incrementar) para préstamo
     */
    static public function mdlConfirmarUsoPrestamo()
    {
        return self::mdlIncrementarConsecutivoPrestamo();
    }

    /**
     * Confirmar uso de consecutivo (incrementar) para recibo
     */
    static public function mdlConfirmarUsoRecibo()
    {
        return self::mdlIncrementarConsecutivoRecibo();
    }

    /**
     * Confirmar uso de consecutivo (incrementar) para voucher
     */
    static public function mdlConfirmarUsoVoucher()
    {
        return self::mdlIncrementarConsecutivoVoucher();
    }
}
?> 