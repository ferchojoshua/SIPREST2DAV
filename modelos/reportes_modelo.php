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
}
