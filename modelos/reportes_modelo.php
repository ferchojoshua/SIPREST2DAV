<?php

require_once "conexion.php";


class ReportesModelo
{

    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlReportePorCliente($cliente_id)
    {
        $stmt = Conexion::conectar()->prepare('call SP_REPORTE_POR_CLIENTE(:cliente_id)');
        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }



    /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlCuotasPagadasReport($fechaInicial = null, $fechaFinal = null)
    {
        try {
            $sql = "SELECT
                        c.cliente_nombres AS cliente_nombre,
                        pc.nro_prestamo,
                        pd.pdetalle_nro_cuota AS nro_cuota,
                        COALESCE(pd.pdetalle_monto_cuota, 0) AS monto_cuota,
                        DATE_FORMAT(pd.pdetalle_fecha_registro, '%d/%m/%Y') AS fecha_pago,
                        pd.pdetalle_estado_cuota,
                        m.moneda_simbolo,
                        u.usuario AS cobrador
                    FROM prestamo_detalle pd
                    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                    INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                    INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                    INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
                    WHERE pd.pdetalle_estado_cuota = 'pagada'";
            
            if ($fechaInicial && $fechaFinal) {
                $sql .= " AND DATE(pd.pdetalle_fecha_registro) BETWEEN :fecha_inicial AND :fecha_final";
            }

            $sql .= " ORDER BY pd.pdetalle_fecha_registro DESC";

            // --- INICIO DE CÓDIGO DE DEPURACIÓN ---
            error_log("[DEBUG_REPORTE_PAGADAS] Fechas recibidas: Inicial = " . ($fechaInicial ?? 'NULL') . ", Final = " . ($fechaFinal ?? 'NULL'));
            error_log("[DEBUG_REPORTE_PAGADAS] SQL construida: " . $sql);
            // --- FIN DE CÓDIGO DE DEPURACIÓN ---

            $stmt = Conexion::conectar()->prepare($sql);
            
            if ($fechaInicial && $fechaFinal) {
                $stmt->bindParam(":fecha_inicial", $fechaInicial, PDO::PARAM_STR);
                $stmt->bindParam(":fecha_final", $fechaFinal, PDO::PARAM_STR);
            }

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // --- INICIO DE CÓDIGO DE DEPURACIÓN ---
            error_log("[DEBUG_REPORTE_PAGADAS] Resultados de la BD: " . json_encode($resultado));
            // --- FIN DE CÓDIGO DE DEPURACIÓN ---

            return $resultado;
        } catch (Exception $e) {
            error_log("Error en mdlCuotasPagadasReport: " . $e->getMessage());
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }
    }


     /*=============================================
    Peticion LISTAR PARA MOSTRAR DATOS EN DATATABLE CON PROCEDURE
    =============================================*/
    static public function mdlReportePivot()
    {
        $stmt = Conexion::conectar()->prepare('call SP_REPORTE_PIVOT()');
        //$stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /*===================================================================*/
    //SELECT  USUARIOS RECORD  EN COMBO
    /*===================================================================*/
    static public function mdlListarSelectUsuario()
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_SELECT_USUARIO_RECORD()');
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /*===================================================================*/
    //SELECT AÑOS RECORD EN COMBO
    /*===================================================================*/
    static public function mdlListarSelectAnio()
    {
        $stmt = Conexion::conectar()->prepare('call SP_LISTAR_SELECT_ANIO_RECORD()');
        $stmt->execute();
        return $stmt->fetchAll();
    }



    /*===================================================================*/
     //LISTAR  REPORTE RECOR POR USUARIO
     /*===================================================================*/
    static public function mdlReporteRecordUsu($id_usuario, $anio)
    {
        $stmt = Conexion::conectar()->prepare('call SP_REPORTE_PRESTAMOS_POR_ANIO_AND_USUARIO(:id_usuario, :anio)');
        $stmt->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(":anio", $anio, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /*===================================================================
    OBTENER REPORTE DE CLIENTES MOROSOS
    ====================================================================*/
    static public function mdlObtenerReporteMorosos()
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_REPORTE_MOROSOS()');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }
    }

    /*===================================================================
    OBTENER REPORTE DE RECUPERACION
    ====================================================================*/
    static public function mdlObtenerReporteRecuperacion($fecha_inicial, $fecha_final)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT
                    c.cliente_nombres,
                    pc.nro_prestamo,
                    pd.pdetalle_nro_cuota,
                    pd.pdetalle_monto_cuota as pago_monto,
                    COALESCE(DATE_FORMAT(pd.pdetalle_fecha_registro, '%Y-%m-%d'), DATE_FORMAT(NOW(), '%Y-%m-%d')) as pago_fecha,
                    m.moneda_simbolo
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pd.pdetalle_estado_cuota = 'pagada'
                  AND (
                      pd.pdetalle_fecha_registro IS NULL 
                      OR DATE(pd.pdetalle_fecha_registro) BETWEEN :fecha_inicial AND :fecha_final
                  )
                ORDER BY pd.pdetalle_fecha_registro DESC
            ");

            $stmt->bindParam(":fecha_inicial", $fecha_inicial, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_final", $fecha_final, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }

        $stmt = null;
    }

    /*===================================================================
    OBTENER TODAS LAS MONEDAS
    ====================================================================*/
    static public function mdlObtenerMonedas()
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT 
                    moneda_id,
                    moneda_nombre,
                    moneda_simbolo
                FROM moneda 
                ORDER BY moneda_nombre ASC
            ");

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }

        $stmt = null;
    }

    /*===================================================================
    MODELO PARA OBTENER REPORTE DIARIO
    ====================================================================*/
    static public function mdlObtenerReporteDiario($fecha)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_REPORTE_DIARIO(:fecha)');
            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }
    }

    /*===================================================================
    MODELO PARA OBTENER ESTADO DE CUENTA DETALLADO POR CLIENTE
    ====================================================================*/
    static public function mdlObtenerEstadoCuentaCliente($cliente_id)
    {
        try {
            $stmt = Conexion::conectar()->prepare('CALL SP_ESTADO_CUENTA_CLIENTE(:cliente_id)');
            $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }
    }

    /*===================================================================
    MODELO PARA OBTENER DETALLE DE CUOTAS POR PRÉSTAMO
    ====================================================================*/
    static public function mdlObtenerDetalleCuotasPrestamo($nro_prestamo)
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT
                    pd.pdetalle_id,
                    pd.nro_prestamo,
                    pd.pdetalle_nro_cuota,
                    pd.pdetalle_monto_cuota,
                    DATE_FORMAT(pd.pdetalle_fecha, '%d/%m/%Y') as fecha_programada,
                    DATE_FORMAT(pd.pdetalle_fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_pago,
                    pd.pdetalle_estado_cuota,
                    
                    -- Información adicional
                    pc.pres_monto_cuota,
                    m.moneda_simbolo,
                    
                    -- Días de mora (si aplica)
                    CASE 
                                        WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE()
                THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
                        ELSE 0 
                    END as dias_mora,
                    
                    -- Estado visual
                    CASE 
                        WHEN pd.pdetalle_estado_cuota = 'pagada' THEN 'success'
                        WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE() THEN 'danger'
                        WHEN pd.pdetalle_estado_cuota = 'pendiente' THEN 'warning'
                        ELSE 'secondary'
                    END as estado_visual
                    
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pd.nro_prestamo = :nro_prestamo
                ORDER BY pd.pdetalle_nro_cuota ASC
            ");

            $stmt->bindParam(":nro_prestamo", $nro_prestamo, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ['error' => 'Excepción capturada: ' .  $e->getMessage()];
        }
    }

    /**
     * Reporte de cobranza diaria: cuotas pendientes para una fecha, agrupadas por promotor
     */
    static public function mdlReporteCobranzaDiaria($fecha)
    {
        $stmt = Conexion::conectar()->prepare('
            SELECT 
                u.usuario AS promotor,
                c.cliente_id,
                c.cliente_nombres,
                pc.nro_prestamo,
                pd.pdetalle_nro_cuota,
                DATE(pd.pdetalle_fecha) AS fecha,
                pd.pdetalle_monto_cuota AS principal,
                0 AS int_mora, -- Ajustar si hay campo de mora
                0 AS int_cte,  -- Ajustar si hay campo de interés corriente
                0 AS comision, -- Ajustar si hay campo de comisión
                pd.pdetalle_monto_cuota AS total
            FROM prestamo_detalle pd
            INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
            INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
            WHERE DATE(pd.pdetalle_fecha) = :fecha
              AND pd.pdetalle_estado_cuota = "pendiente"
            ORDER BY u.usuario, c.cliente_nombres
        ');
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Reporte de cuotas atrasadas por promotor: cuotas pendientes con fecha menor a hoy
     */
    static public function mdlReporteCuotasAtrasadas($fecha)
    {
        $stmt = Conexion::conectar()->prepare('
            SELECT 
                u.usuario AS promotor,
                c.cliente_id,
                c.cliente_nombres,
                c.cliente_direccion,
                c.cliente_celular,
                pc.nro_prestamo,
                pd.pdetalle_nro_cuota,
                DATE(pd.pdetalle_fecha) AS fecha,
                pd.pdetalle_monto_cuota AS principal,
                0 AS interes, -- Ajustar si hay campo de interés
                pd.pdetalle_monto_cuota AS total,
                pd.pdetalle_monto_cuota AS cuota_segun,
                DATEDIFF(:fecha, DATE(pd.pdetalle_fecha)) AS dias_atraso,
                pc.pres_monto_restante AS saldo_total
            FROM prestamo_detalle pd
            INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
            INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
            WHERE DATE(pd.pdetalle_fecha) < :fecha
              AND pd.pdetalle_estado_cuota = "pendiente"
            ORDER BY u.usuario, c.cliente_nombres
        ');
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*===================================================================*/
    // OBTENER KPIs PARA EL DASHBOARD GERENCIAL
    /*===================================================================*/
    static public function mdlObtenerKpisGerenciales($id_colector = null)
    {
        try {
            // Saldo total de cartera (capital pendiente de todos los préstamos aprobados)
            $query_saldo_cartera = "
                SELECT COALESCE(SUM(pres_monto_restante), 0) as saldo_cartera
                FROM prestamo_cabecera
                WHERE pres_aprobacion = 'aprobado'";
            
            // Clientes con préstamos activos
            $query_clientes_activos = "
                SELECT COUNT(DISTINCT cliente_id) as clientes_activos
                FROM prestamo_cabecera
                WHERE pres_aprobacion = 'aprobado'";

            // Monto total en mora (cuotas vencidas y no pagadas)
            $query_monto_mora = "
                SELECT COALESCE(SUM(pdetalle_monto_cuota), 0) as monto_en_mora
                FROM prestamo_detalle
                WHERE pdetalle_estado_cuota = 'pendiente' AND pdetalle_fecha < CURDATE()";

            // Aplicar filtro por colector si se proporciona
            if ($id_colector) {
                $query_saldo_cartera .= " AND id_usuario = :id_colector";
                $query_clientes_activos .= " AND id_usuario = :id_colector";
                $query_monto_mora = "
                    SELECT COALESCE(SUM(pd.pdetalle_monto_cuota), 0) as monto_en_mora
                    FROM prestamo_detalle pd
                    INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                    WHERE pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha < CURDATE()
                    AND pc.id_usuario = :id_colector";
            }
            
            // Ejecutar consultas
            $stmt_saldo = Conexion::conectar()->prepare($query_saldo_cartera);
            $stmt_clientes = Conexion::conectar()->prepare($query_clientes_activos);
            $stmt_mora = Conexion::conectar()->prepare($query_monto_mora);

            if($id_colector){
                $stmt_saldo->bindParam(":id_colector", $id_colector, PDO::PARAM_INT);
                $stmt_clientes->bindParam(":id_colector", $id_colector, PDO::PARAM_INT);
                $stmt_mora->bindParam(":id_colector", $id_colector, PDO::PARAM_INT);
            }

            $stmt_saldo->execute();
            $stmt_clientes->execute();
            $stmt_mora->execute();

            $result_saldo = $stmt_saldo->fetch(PDO::FETCH_ASSOC);
            $result_clientes = $stmt_clientes->fetch(PDO::FETCH_ASSOC);
            $result_mora = $stmt_mora->fetch(PDO::FETCH_ASSOC);

            $saldo_cartera = $result_saldo ? (float)$result_saldo['saldo_cartera'] : 0;
            $clientes_activos = $result_clientes ? (int)$result_clientes['clientes_activos'] : 0;
            $monto_en_mora = $result_mora ? (float)$result_mora['monto_en_mora'] : 0;
            
            // Calcular porcentaje de mora
            $porcentaje_mora = ($saldo_cartera > 0) ? ($monto_en_mora / $saldo_cartera) * 100 : 0;
            
            return [
                "saldo_cartera" => $saldo_cartera,
                "clientes_activos" => $clientes_activos,
                "monto_en_mora" => $monto_en_mora,
                "porcentaje_mora" => $porcentaje_mora
            ];
        } catch (PDOException $e) {
            error_log("Error de base de datos en mdlObtenerKpisGerenciales: " . $e->getMessage());
            // Devolver una estructura válida en caso de error de BD
            return [
                "saldo_cartera" => 0,
                "clientes_activos" => 0,
                "monto_en_mora" => 0,
                "porcentaje_mora" => 0,
                "error" => "DB_ERROR",
                "mensaje" => $e->getMessage()
            ];
        }
    }

    /*===================================================================*/
    // REPORTE DE SALDOS ARRASTRADOS
    /*===================================================================*/
    static public function mdlReporteSaldosArrastrados($fecha_inicio, $fecha_fin)
    {
        try {
            $stmt = Conexion::conectar()->prepare("CALL SP_REPORTE_SALDOS_ARRASTRADOS(:fecha_inicio, :fecha_fin)");
            $stmt->bindParam(":fecha_inicio", $fecha_inicio, PDO::PARAM_STR);
            $stmt->bindParam(":fecha_fin", $fecha_fin, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_NUM);
        } catch (Exception $e) {
            return 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }
    }
}
