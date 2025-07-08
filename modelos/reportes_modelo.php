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
    static public function mdlCuotasPagadasReport()
    {
        try {
            $stmt = Conexion::conectar()->prepare("
                SELECT
                    c.cliente_nombres,
                    pc.nro_prestamo,
                    pd.pdetalle_nro_cuota,
                    pd.pdetalle_monto_cuota,
                    pd.pdetalle_fecha_registro,
                    pd.pdetalle_estado_cuota,
                    m.moneda_simbolo
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pd.pdetalle_estado_cuota = 'pagada'
                ORDER BY pd.pdetalle_fecha_registro DESC
            ");

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
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
            $stmt = Conexion::conectar()->prepare("
                SELECT
                    c.cliente_id,
                    c.cliente_nombres as cliente_nombres,
                    pc.nro_prestamo,
                    pd.pdetalle_nro_cuota,
                    pd.pdetalle_fecha,
                    pd.pdetalle_monto_cuota,
                    pd.pdetalle_saldo_cuota,
                    DATEDIFF(CURDATE(), pd.pdetalle_fecha) AS dias_mora,
                    m.moneda_simbolo
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE pd.pdetalle_estado_cuota = 'pendiente'
                  AND pd.pdetalle_fecha < CURDATE()
                ORDER BY dias_mora DESC
            ");

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return 'Excepción capturada: ' .  $e->getMessage() . "\n";
        }

        $stmt = null;
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
            $stmt = Conexion::conectar()->prepare("
                SELECT
                    'PRÉSTAMOS' as tipo_operacion,
                    COUNT(pc.pres_id) as cantidad,
                    ROUND(IFNULL(SUM(pc.pres_monto),0),2) as monto_capital,
                    ROUND(IFNULL(SUM(pc.pres_monto_interes),0),2) as monto_interes,
                    ROUND(IFNULL(SUM(pc.pres_monto_total),0),2) as monto_total,
                    m.moneda_simbolo,
                    m.moneda_nombre
                FROM prestamo_cabecera pc
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE DATE(pc.pres_fecha_registro) = :fecha
                AND pc.pres_aprobacion IN ('aprobado', 'finalizado')
                GROUP BY m.moneda_id, m.moneda_simbolo, m.moneda_nombre

                UNION ALL

                SELECT
                    'PAGOS DE CUOTAS' as tipo_operacion,
                    COUNT(pd.pdetalle_id) as cantidad,
                    ROUND(IFNULL(SUM(pd.pdetalle_monto_cuota),0),2) as monto_capital,
                    0 as monto_interes,
                    ROUND(IFNULL(SUM(pd.pdetalle_monto_cuota),0),2) as monto_total,
                    m.moneda_simbolo,
                    m.moneda_nombre
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                WHERE DATE(pd.pdetalle_fecha_registro) = :fecha2
                AND pd.pdetalle_estado_cuota = 'pagada'
                GROUP BY m.moneda_id, m.moneda_simbolo, m.moneda_nombre

                UNION ALL

                SELECT
                    'INGRESOS' as tipo_operacion,
                    COUNT(mv.movimientos_id) as cantidad,
                    ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_capital,
                    0 as monto_interes,
                    ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_total,
                    '$' as moneda_simbolo,
                    'Mixta' as moneda_nombre
                FROM movimientos mv
                WHERE DATE(mv.movi_fecha) = :fecha3
                AND mv.movi_tipo = 'INGRESO'

                UNION ALL

                SELECT
                    'EGRESOS' as tipo_operacion,
                    COUNT(mv.movimientos_id) as cantidad,
                    ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_capital,
                    0 as monto_interes,
                    ROUND(IFNULL(SUM(mv.movi_monto),0),2) as monto_total,
                    '$' as moneda_simbolo,
                    'Mixta' as moneda_nombre
                FROM movimientos mv
                WHERE DATE(mv.movi_fecha) = :fecha4
                AND mv.movi_tipo = 'EGRESO'

                ORDER BY tipo_operacion
            ");

            $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":fecha2", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":fecha3", $fecha, PDO::PARAM_STR);
            $stmt->bindParam(":fecha4", $fecha, PDO::PARAM_STR);
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
            $stmt = Conexion::conectar()->prepare("
                SELECT
                    -- Información del préstamo
                    pc.pres_id,
                    pc.nro_prestamo,
                    pc.cliente_id,
                    c.cliente_nombres,
                    c.cliente_dni,
                    c.cliente_celular,
                    c.cliente_direccion,
                    
                    -- Datos financieros del préstamo
                    pc.pres_monto,
                    pc.pres_interes,
                    pc.pres_monto_interes,
                    pc.pres_monto_total,
                    pc.pres_monto_cuota,
                    pc.pres_cuotas,
                    pc.pres_cuotas_pagadas,
                    
                    -- Fechas importantes
                    DATE_FORMAT(pc.pres_fecha_registro, '%d/%m/%Y') as fecha_registro,
                    DATE_FORMAT(pc.pres_f_emision, '%d/%m/%Y') as fecha_emision,
                    
                    -- Estado y forma de pago
                    pc.pres_aprobacion as estado,
                    fp.fpago_descripcion,
                    m.moneda_simbolo,
                    m.moneda_nombre,
                    u.usuario,
                    
                    -- Cálculos de saldo
                    ROUND((pc.pres_monto_total - (pc.pres_cuotas_pagadas * pc.pres_monto_cuota)), 2) as saldo_pendiente,
                    ROUND((pc.pres_cuotas_pagadas * pc.pres_monto_cuota), 2) as monto_pagado,
                    (pc.pres_cuotas - pc.pres_cuotas_pagadas) as cuotas_pendientes,
                    
                    -- Porcentaje de avance
                    ROUND((pc.pres_cuotas_pagadas / pc.pres_cuotas * 100), 2) as porcentaje_avance
                    
                FROM prestamo_cabecera pc
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                INNER JOIN forma_pago fp ON pc.fpago_id = fp.fpago_id
                INNER JOIN moneda m ON pc.moneda_id = m.moneda_id
                INNER JOIN usuarios u ON pc.id_usuario = u.id_usuario
                WHERE pc.cliente_id = :cliente_id
                ORDER BY pc.pres_fecha_registro DESC
            ");

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
                    DATE_FORMAT(pd.pdetalle_fecha_programada, '%d/%m/%Y') as fecha_programada,
                    DATE_FORMAT(pd.pdetalle_fecha_registro, '%d/%m/%Y %H:%i:%s') as fecha_pago,
                    pd.pdetalle_estado_cuota,
                    
                    -- Información adicional
                    pc.pres_monto_cuota,
                    m.moneda_simbolo,
                    
                    -- Días de mora (si aplica)
                    CASE 
                        WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha_programada < CURDATE() 
                        THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha_programada)
                        ELSE 0 
                    END as dias_mora,
                    
                    -- Estado visual
                    CASE 
                        WHEN pd.pdetalle_estado_cuota = 'pagada' THEN 'success'
                        WHEN pd.pdetalle_estado_cuota = 'pendiente' AND pd.pdetalle_fecha_programada < CURDATE() THEN 'danger'
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
}
