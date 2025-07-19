<?php

require_once __DIR__ . "/conexion.php";


class PrestamoModelo
{

    /*===================================================================*/
    //REGISTRAR PRESTAMO
    /*===================================================================*/
    static public function mdlRegistrarPrestamo($nro_prestamo, $cliente_id, $pres_monto, $pres_cuotas, $pres_interes, $fpago_id, $moneda_id, $pres_f_emision, $pres_monto_cuota, $pres_monto_interes, $pres_monto_total, $id_usuario, $caja_id, $tipo_calculo)
    {
        //print_r($nro_boleta);
        $fecha = date('Y-m-d H:i:s');
        // date("d/m/Y H:i:s"). 
        try {
            //INSERTAMOS LA CABECERA
            $stmt = Conexion::conectar()->prepare("INSERT INTO prestamo_cabecera (nro_prestamo, cliente_id, pres_monto, pres_cuotas, pres_interes, fpago_id, moneda_id, pres_f_emision, pres_monto_cuota, pres_monto_interes, pres_monto_total, pres_estado, pres_estatus, id_usuario, pres_aprobacion, pres_fecha_registro, pres_estado_caja, caja_id, tipo_calculo) 
                                                VALUES (:nro_prestamo, :cliente_id, :pres_monto, :pres_cuotas, :pres_interes, :fpago_id, :moneda_id, :pres_f_emision, :pres_monto_cuota, :pres_monto_interes, :pres_monto_total, 'Pendiente', '1', :id_usuario, 'pendiente', CURRENT_TIME(), 'VIGENTE', :caja_id, :tipo_calculo)");

            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto", $pres_monto, PDO::PARAM_STR);
            $stmt->bindParam(":pres_cuotas", $pres_cuotas, PDO::PARAM_STR);
            $stmt->bindParam(":pres_interes", $pres_interes, PDO::PARAM_STR);
            $stmt->bindParam(":fpago_id", $fpago_id, PDO::PARAM_STR);
            $stmt->bindParam(":moneda_id", $moneda_id, PDO::PARAM_STR);
            $stmt->bindParam(":pres_f_emision", $pres_f_emision, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto_cuota", $pres_monto_cuota, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto_interes", $pres_monto_interes, PDO::PARAM_STR);
            $stmt->bindParam(":pres_monto_total", $pres_monto_total, PDO::PARAM_STR);
            $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_STR);
            $stmt->bindParam(":caja_id", $caja_id, PDO::PARAM_STR);
            $stmt->bindParam(":tipo_calculo", $tipo_calculo, PDO::PARAM_STR);

            if ($stmt->execute()) {

                $stmt = null; //colocamos en null porque se va a usar de nuevo para actualizar correlativo

                //ACTUALIZAMOS EL CORRELATIVO
                $stmt = Conexion::conectar()->prepare("UPDATE empresa SET confi_correlativo = LPAD(confi_correlativo + 1,8,'0')");

                if ($stmt->execute()) {
                    //$stmt = Conexion::conectar()->prepare("UPDATE clientes SET cliente_estado_prestamo =  'con prestamo' where cliente_id = :cliente_id");
                    $stmt = Conexion::conectar()->prepare('call SP_ACTUALIZAR_ESTADO_CLIENTE_PRESTAMO(:cliente_id)');
                    $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        $resultado = "Se registro el prestamo correctamente.";
                    } else {
                        $resultado = "Error rrrr";
                    }
                }
            } else {
                $resultado = "Error al registrar el prestamo";
            }
        } catch (Exception $e) {
            $resultado = 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }
        return $resultado;

        $stmt = null;
    }




    /*===================================================================*/
    //REGISTRAR DETALLE DEL PRESTAMO
    /*===================================================================*/
    static public function mdlRegistrarPrestamoDetalle($nro_prestamo, $pdetalle_nro_cuota, $pdetalle_monto_cuota, $pdetalle_fecha)
    {
        try {
            $conn = Conexion::conectar();

            $stmt = $conn->prepare("INSERT INTO prestamo_detalle(
                nro_prestamo, 
                pdetalle_nro_cuota, 
                pdetalle_monto_cuota, 
                pdetalle_fecha, 
                pdetalle_estado_cuota, 
                pdetalle_liquidar, 
                pdetalle_caja, 
                pdetalle_aprobacion, 
                pdetalle_saldo_cuota
            ) VALUES (
                :nro_prestamo,
                :pdetalle_nro_cuota,
                :pdetalle_monto_cuota,
                :pdetalle_fecha, 
                'pendiente', 
                '0', 
                'VIGENTE', 
                'pendiente', 
                :pdetalle_saldo_cuota
            )");

            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_nro_cuota", $pdetalle_nro_cuota, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_monto_cuota", $pdetalle_monto_cuota, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_fecha", $pdetalle_fecha, PDO::PARAM_STR);
            $stmt->bindParam(":pdetalle_saldo_cuota", $pdetalle_monto_cuota, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                return "error al guardar detalle";
            }

            return "ok";

        } catch (Exception $e) {
            error_log("Error en mdlRegistrarPrestamoDetalle: " . $e->getMessage());
            return 'Error: ' . $e->getMessage();
        }
    }




    /*===================================================================*/
    //VALIDAD SI HAY MONTO DISPONIBLE EN CAJA
    /*===================================================================*/
    static public function mdlValidarMontoPrestamo()
    {
        $smt = Conexion::conectar()->prepare('call SP_ALERTA_PRESTAMO_CAJA()');
        $smt->execute();
        return $smt->fetch(PDO::FETCH_OBJ);
    }

    /*===================================================================*/
    // CARGAR SELECTS DINÁMICOS (Forma Pago, Moneda)
    /*===================================================================*/
    static public function mdlCargarSelect($tabla)
    {
        // Whitelist de tablas permitidas para evitar inyección SQL
        $tablasPermitidas = ['forma_pago', 'moneda'];
        if (!in_array($tabla, $tablasPermitidas)) {
            return "error: tabla no permitida";
        }

        if ($tabla == 'forma_pago') {
            $stmt = Conexion::conectar()->prepare("SELECT fpago_id, fpago_descripcion FROM forma_pago ORDER BY fpago_id");
        }

        if ($tabla == 'moneda') {
            $stmt = Conexion::conectar()->prepare("SELECT moneda_id, moneda_Descripcion as moneda_descripcion, moneda_simbolo FROM moneda ORDER BY moneda_id");
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Cambiado a FETCH_ASSOC para devolver array asociativo
    }

    /*===================================================================*/
    // OBTENER TIPOS DE CÁLCULO (Amortización)
    /*===================================================================*/
    static public function mdlObtenerTiposCalculo()
    {
        try {
            $conexion = Conexion::conectar();
            
            // Verificar si la tabla existe
            $stmt = $conexion->prepare("SHOW TABLES LIKE 'tipos_calculo_interes'");
            $stmt->execute();
            $tablaExiste = $stmt->fetch();
            
            if (!$tablaExiste) {
                // Crear la tabla si no existe
                self::crearTablaTiposCalculo($conexion);
            }
            
            // Obtener los tipos de cálculo
            $stmt = $conexion->prepare("SELECT 
                tipo_calculo_id as id,
                tipo_calculo_nombre as nombre, 
                tipo_calculo_descripcion as descripcion 
                FROM tipos_calculo_interes 
                WHERE tipo_calculo_estado = 1 
                ORDER BY tipo_calculo_id");
            
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($resultados)) {
                // Insertar datos por defecto si no hay registros
                self::insertarDatosPorDefecto($conexion);
                
                // Volver a consultar
                $stmt->execute();
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            
            return $resultados;
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerTiposCalculo: " . $e->getMessage());
            throw new Exception("Error al obtener tipos de cálculo: " . $e->getMessage());
        }
    }
    
    /*===================================================================*/
    // CREAR TABLA TIPOS_CALCULO_INTERES
    /*===================================================================*/
    private static function crearTablaTiposCalculo($conexion)
    {
        $sql = "CREATE TABLE tipos_calculo_interes (
            tipo_calculo_id INT AUTO_INCREMENT PRIMARY KEY,
            tipo_calculo_nombre VARCHAR(100) NOT NULL,
            tipo_calculo_descripcion TEXT DEFAULT NULL,
            tipo_calculo_estado TINYINT(1) DEFAULT 1
        )";
        
        $stmt = $conexion->prepare($sql);
        $stmt->execute();
        
        // Insertar datos por defecto
        self::insertarDatosPorDefecto($conexion);
    }
    
    /*===================================================================*/
    // INSERTAR DATOS POR DEFECTO EN TIPOS_CALCULO_INTERES
    /*===================================================================*/
    private static function insertarDatosPorDefecto($conexion)
    {
        $datos = [
            [1, 'FRANCES', 'Sistema Francés - Cuotas fijas', 1],
            [2, 'ALEMAN', 'Sistema Alemán - Capital fijo', 1],
            [3, 'AMERICANO', 'Sistema Americano - Solo intereses', 1],
            [4, 'SIMPLE', 'Sistema Simple - Interés fijo', 1],
            [5, 'COMPUESTO', 'Sistema Compuesto - Interés sobre interés', 1],
            [6, 'FLAT', 'Sistema Flat - Interés siempre sobre capital original', 1]
        ];
        
        $sql = "INSERT INTO tipos_calculo_interes (tipo_calculo_id, tipo_calculo_nombre, tipo_calculo_descripcion, tipo_calculo_estado) VALUES (?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        
        foreach ($datos as $fila) {
            $stmt->execute($fila);
        }
    }
    
    /*===================================================================*/
    // OBTENER CONFIGURACIÓN DE PERÍODOS DE PAGO
    /*===================================================================*/
    static public function mdlObtenerPeriodosPago()
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT 
                fpago_id,
                fpago_descripcion,
                CASE 
                    WHEN fpago_id = 1 THEN 365
                    WHEN fpago_id = 2 THEN 52
                    WHEN fpago_id = 3 THEN 24
                    WHEN fpago_id = 4 THEN 12
                    WHEN fpago_id = 5 THEN 6
                    WHEN fpago_id = 6 THEN 2
                    WHEN fpago_id = 7 THEN 1
                    ELSE 12
                END as periodos_por_anio
                FROM forma_pago 
                ORDER BY fpago_id");
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerPeriodosPago: " . $e->getMessage());
            throw new Exception("Error al obtener configuración de períodos: " . $e->getMessage());
        }
    }

} // FIN DE LA CLASE
