<?php

require_once __DIR__ . "/conexion.php";

class ModeloReportesFinancieros {

    public static function mdlObtenerSucursales() {
        $stmt = Conexion::conectar()->prepare("SELECT id as sucursal_id, nombre as sucursal_nombre FROM sucursales WHERE estado = 'activa' ORDER BY nombre");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlObtenerRutas($sucursal_id) {
        $stmt = Conexion::conectar()->prepare("SELECT ruta_id, ruta_nombre FROM rutas WHERE sucursal_id = :sucursal_id AND ruta_estado = 'activa' ORDER BY ruta_nombre");
        $stmt->bindParam(":sucursal_id", $sucursal_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlBuscarClientes($busqueda) {
        $stmt = Conexion::conectar()->prepare("SELECT cliente_id as id, cliente_nombres as text FROM clientes WHERE cliente_nombres LIKE :busqueda OR cliente_dni LIKE :busqueda LIMIT 10");
        $busqueda_param = "%" . $busqueda . "%";
        $stmt->bindParam(":busqueda", $busqueda_param, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteClientesMora($datos) {
        $query = "
            SELECT
                c.cliente_nombres AS Cliente,
                c.cliente_cel AS Celular,
                pc.nro_prestamo AS 'Nro Prestamo',
                pd.pdetalle_nro_cuota AS 'Cuota Vencida',
                pd.pdetalle_fecha AS 'Fecha Vencimiento',
                pd.pdetalle_monto_cuota AS 'Monto Cuota',
                DATEDIFF(CURDATE(), pd.pdetalle_fecha) AS 'Dias de Mora'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
            AND pd.pdetalle_fecha < CURDATE()
            AND pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }
        
        $query .= " ORDER BY c.cliente_nombres, pd.pdetalle_fecha ASC";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteMoraPorColector($datos) {
        $query = "
            SELECT
                u.nombre_usuario AS Colector,
                COUNT(pd.pdetalle_id) AS 'Cuotas Vencidas',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto en Mora'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
            LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
            WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
            AND pd.pdetalle_fecha < CURDATE()
            AND pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        $query .= " GROUP BY u.nombre_usuario ORDER BY u.nombre_usuario";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteMoraPorRuta($datos) {
        $query = "
            SELECT
                r.ruta_nombre AS Ruta,
                COUNT(pd.pdetalle_id) AS 'Cuotas Vencidas',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto en Mora',
                AVG(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) AS 'Promedio Dias Mora'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
            AND pd.pdetalle_fecha < CURDATE()
            AND pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND r.ruta_id = :ruta_id";
        }

        $query .= " GROUP BY r.ruta_id, r.ruta_nombre ORDER BY r.ruta_nombre";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePagosDelDia($datos) {
        $fecha = $datos['fecha_inicio']; // Se espera una sola fecha para "pagos del día"
        $query = "
            SELECT
                pd.pdetalle_fecha_registro AS 'fecha_pago',
                c.cliente_nombres,
                pd.nro_prestamo,
                pd.pdetalle_nro_cuota AS 'cuota_pagada',
                pd.pdetalle_monto_cuota AS 'monto_pagado',
                u.nombre_usuario AS 'colector_nombre',
                r.ruta_nombre
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
            LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
            WHERE pd.pdetalle_estado_cuota = 'PAGADO' AND DATE(pd.pdetalle_fecha_registro) = :fecha
        ";

        // Filtrar por sucursal específica si se especifica
        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        
        // Filtrar por ruta específica si se especifica
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        $query .= " ORDER BY pd.pdetalle_fecha_registro DESC, c.cliente_nombres ASC";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteCobranzaPorColector($datos) {
        $query = "
            SELECT
                u.nombre_usuario AS 'Colector',
                COUNT(pd.pdetalle_id) AS 'Pagos Recibidos',
                SUM(pd.pdetalle_monto_cuota) AS 'Total Cobrado',
                AVG(pd.pdetalle_monto_cuota) AS 'Promedio por Pago'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
            LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
            WHERE DATE(pd.pdetalle_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin
            AND pd.pdetalle_estado_cuota = 'pagada'
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        $query .= " GROUP BY u.id_usuario, u.nombre_usuario ORDER BY SUM(pd.pdetalle_monto_cuota) DESC";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteMontoColocado($datos) {
        $query = "
            SELECT
                DATE(pc.pres_fecha_registro) AS 'Fecha',
                COUNT(pc.nro_prestamo) AS 'Prestamos Otorgados',
                SUM(pc.pres_monto) AS 'Monto Total Colocado',
                AVG(pc.pres_monto) AS 'Monto Promedio'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pc.pres_aprobacion = 1
            AND DATE(pc.pres_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        $query .= " GROUP BY DATE(pc.pres_fecha_registro) ORDER BY DATE(pc.pres_fecha_registro) DESC";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePrestamosVigentes($datos) {
        $query = "
            SELECT
                c.cliente_nombres AS 'Cliente',
                pc.nro_prestamo AS 'Nro. Prestamo',
                pc.pres_monto AS 'Monto Prestamo',
                pc.pres_fecha_registro AS 'Fecha Registro',
                COUNT(pd.pdetalle_id) AS 'Total Cuotas',
                SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PAGADO' THEN 1 ELSE 0 END) AS 'Cuotas Pagadas',
                COUNT(pd.pdetalle_id) - SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PAGADO' THEN 1 ELSE 0 END) AS 'Cuotas Pendientes'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pc.pres_aprobacion = 1
            AND pc.pres_estado = 'VIGENTE'
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        $query .= " GROUP BY pc.nro_prestamo ORDER BY pc.pres_fecha_registro DESC";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteCarteraVencida($datos) {
        $query = "
            SELECT
                c.cliente_nombres AS 'Cliente',
                pc.nro_prestamo AS 'Nro. Prestamo',
                COUNT(pd.pdetalle_id) AS 'Cuotas Vencidas',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto Vencido',
                MIN(pd.pdetalle_fecha) AS 'Primera Cuota Vencida',
                MAX(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) AS 'Dias Mora Maximos'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
            AND pd.pdetalle_fecha < CURDATE()
            AND pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        $query .= " GROUP BY pc.nro_prestamo ORDER BY SUM(pd.pdetalle_monto_cuota) DESC";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteResumenCartera($datos) {
        $query = "
            SELECT
                'Prestamos Vigentes' AS 'Concepto',
                COUNT(DISTINCT pc.nro_prestamo) AS 'Cantidad',
                SUM(pc.pres_monto) AS 'Monto Total'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pc.pres_aprobacion = 1 AND pc.pres_estado = 'VIGENTE'
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        $query .= "
            UNION ALL
            SELECT
                'Cartera Vencida' AS 'Concepto',
                COUNT(DISTINCT pc.nro_prestamo) AS 'Cantidad',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto Total'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pd.pdetalle_estado_cuota = 'PENDIENTE' AND pd.pdetalle_fecha < CURDATE()
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id_2";
        }

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
            $stmt->bindParam(":sucursal_id_2", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteDiario($datos) {
        $query = "
            SELECT
                'Pagos Recibidos' AS 'Concepto',
                COUNT(pd.pdetalle_id) AS 'Cantidad',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            WHERE DATE(pd.pdetalle_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin
            AND pd.pdetalle_estado_cuota = 'pagada'
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        $query .= "
            UNION ALL
            SELECT
                'Prestamos Otorgados' AS 'Concepto',
                COUNT(pc.nro_prestamo) AS 'Cantidad',
                SUM(pc.pres_monto) AS 'Monto'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            WHERE pc.pres_aprobacion = 1
            AND DATE(pc.pres_fecha_registro) BETWEEN :fecha_inicio_2 AND :fecha_fin_2
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id_2";
        }

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_inicio_2", $datos['fecha_inicio'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin_2", $datos['fecha_fin'], PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
            $stmt->bindParam(":sucursal_id_2", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteCajaDiaria($datos) {
        $query = "
            SELECT
                DATE(pd.pdetalle_fecha_registro) AS 'Fecha',
                'Ingresos por Pagos' AS 'Concepto',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE DATE(pd.pdetalle_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin
            AND pd.pdetalle_estado_cuota = 'pagada'
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        $query .= " GROUP BY DATE(pd.pdetalle_fecha_registro) ORDER BY DATE(pd.pdetalle_fecha_registro) DESC";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteEstadoCuentaCliente($datos) {
        $cliente_id = $datos['cliente_id'] ?? null;
        
        if (!$cliente_id) {
            return ["error" => "Debe seleccionar un cliente para generar el estado de cuenta"];
        }

        $query = "
            SELECT
                pd.pdetalle_nro_cuota AS 'Cuota',
                pd.pdetalle_fecha AS 'Fecha Vencimiento',
                pd.pdetalle_monto_cuota AS 'Monto Cuota',
                pd.pdetalle_estado_cuota AS 'Estado',
                CASE 
                    WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' AND pd.pdetalle_fecha < CURDATE() 
                    THEN DATEDIFF(CURDATE(), pd.pdetalle_fecha)
                    ELSE 0
                END AS 'Dias Mora'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            WHERE pc.cliente_id = :cliente_id
            AND pc.pres_aprobacion = 1
            ORDER BY pd.pdetalle_nro_cuota
        ";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteMoraPorSucursal($datos) {
        $query = "
            SELECT
                COALESCE(s.nombre, 'Sin Sucursal') AS 'Sucursal',
                COUNT(pd.pdetalle_id) AS 'Cuotas Vencidas',
                SUM(pd.pdetalle_monto_cuota) AS 'Monto en Mora',
                AVG(DATEDIFF(CURDATE(), pd.pdetalle_fecha)) AS 'Promedio Dias Mora'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN sucursales s ON r.sucursal_id = s.id
            WHERE pd.pdetalle_estado_cuota = 'PENDIENTE'
            AND pd.pdetalle_fecha < CURDATE()
            AND pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        $query .= " GROUP BY s.id, s.nombre ORDER BY s.nombre";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePendientesDelDia($datos) {
        // Si no se especifica fecha, usar la fecha actual
        $fecha = !empty($datos['fecha_inicio']) ? $datos['fecha_inicio'] : date('Y-m-d');
        
        $query = "
            SELECT
                DATE(pd.pdetalle_fecha) AS 'Fecha Vencimiento',
                c.cliente_nombres AS 'Cliente',
                c.cliente_cel AS 'Celular',
                pc.nro_prestamo AS 'Nro Prestamo',
                pd.pdetalle_nro_cuota AS 'Nro Cuota',
                pd.pdetalle_monto_cuota AS 'Monto Cuota',
                DATEDIFF(CURDATE(), pd.pdetalle_fecha) AS 'Dias de Atraso',
                r.ruta_nombre AS 'Ruta',
                s.nombre AS 'Sucursal'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN sucursales s ON r.sucursal_id = s.id
            WHERE DATE(pd.pdetalle_fecha) = :fecha
            AND pd.pdetalle_estado_cuota = 'PENDIENTE'
            AND pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        $query .= " ORDER BY pd.pdetalle_fecha ASC, c.cliente_nombres ASC";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha", $fecha, PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteCobranzaPorRuta($datos) {
        $query = "
            SELECT
                r.ruta_nombre AS 'Ruta',
                COUNT(pd.pdetalle_id) AS 'Pagos Recibidos',
                SUM(pd.pdetalle_monto_cuota) AS 'Total Cobrado',
                AVG(pd.pdetalle_monto_cuota) AS 'Promedio por Pago'
            FROM prestamo_detalle pd
            JOIN prestamo_cabecera pc ON pd.nro_prestamo = pc.nro_prestamo
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE DATE(pd.pdetalle_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin
            AND pd.pdetalle_estado_cuota = 'pagada'
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND r.ruta_id = :ruta_id";
        }

        $query .= " GROUP BY r.ruta_id, r.ruta_nombre ORDER BY SUM(pd.pdetalle_monto_cuota) DESC";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePrestamosActivos($datos) {
        $query = "
            SELECT 
                pc.nro_prestamo AS 'Nro Prestamo',
                c.cliente_nombres AS 'Cliente',
                c.cliente_cel AS 'Teléfono',
                pc.pres_monto AS 'Monto Prestamo',
                pc.pres_monto_total AS 'Monto Total',
                pc.pres_monto_restante AS 'Saldo Pendiente',
                pc.pres_cuotas AS 'Total Cuotas',
                pc.pres_cuotas_pagadas AS 'Cuotas Pagadas',
                pc.pres_cuotas_restante AS 'Cuotas Pendientes',
                pc.pres_fecha_registro AS 'Fecha Otorgamiento',
                MAX(CASE 
                    WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' 
                    THEN pd.pdetalle_fecha 
                    END) AS 'Próximo Vencimiento',
                r.ruta_nombre AS 'Ruta',
                s.nombre AS 'Sucursal'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN sucursales s ON r.sucursal_id = s.id
            WHERE pc.pres_estado = 'VIGENTE'
            AND pc.pres_aprobacion = 1
        ";

        // Filtrar por fecha si se especifica
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            $query .= " AND DATE(pc.pres_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin";
        }

        // Filtrar por sucursal si se especifica
        if (!empty($datos['sucursal_id'])) {
            $query .= " AND s.id = :sucursal_id";
        }

        // Filtrar por ruta si se especifica
        if (!empty($datos['ruta_id'])) {
            $query .= " AND r.ruta_id = :ruta_id";
        }

        // Agrupar por préstamo
        $query .= " GROUP BY pc.nro_prestamo, c.cliente_nombres, c.cliente_cel, pc.pres_monto, 
                    pc.pres_monto_total, pc.pres_monto_restante, pc.pres_cuotas, 
                    pc.pres_cuotas_pagadas, pc.pres_cuotas_restante, pc.pres_fecha_registro,
                    r.ruta_nombre, s.nombre";

        // Ordenar por próximo vencimiento (más cercano primero)
        $query .= " ORDER BY MAX(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' THEN pd.pdetalle_fecha END) ASC";

        $stmt = Conexion::conectar()->prepare($query);

        // Bind de parámetros de fecha si existen
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);
        }

        // Bind de parámetros de filtros
        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePrestamosPorCliente($datos) {
        $cliente_id = $datos['cliente_id'] ?? null;
        
        if (!$cliente_id) {
            return ["error" => "Debe seleccionar un cliente para generar este reporte"];
        }

        $query = "
            SELECT
                pc.nro_prestamo AS 'Nro. Prestamo',
                pc.pres_monto AS 'Monto',
                pc.pres_cuotas AS 'Cuotas',
                pc.pres_fecha_registro AS 'Fecha',
                pc.pres_estado AS 'Estado',
                COUNT(pd.pdetalle_id) AS 'Total Cuotas',
                SUM(CASE WHEN pd.pdetalle_estado_cuota = 'pagada' THEN 1 ELSE 0 END) AS 'Cuotas Pagadas'
            FROM prestamo_cabecera pc
            LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
            WHERE pc.cliente_id = :cliente_id
            AND pc.pres_aprobacion = 1
            GROUP BY pc.nro_prestamo
            ORDER BY pc.pres_fecha_registro DESC
        ";

        $stmt = Conexion::conectar()->prepare($query);
        $stmt->bindParam(":cliente_id", $cliente_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePrestamosPorSucursal($datos) {
        $query = "
            SELECT 
                s.nombre AS 'Sucursal',
                COUNT(DISTINCT pc.nro_prestamo) AS 'Total Prestamos',
                SUM(pc.pres_monto) AS 'Monto Total',
                AVG(pc.pres_monto) AS 'Monto Promedio',
                COUNT(DISTINCT c.cliente_id) AS 'Total Clientes',
                SUM(CASE WHEN pc.pres_estado = 'VIGENTE' THEN 1 ELSE 0 END) AS 'Prestamos Activos',
                SUM(CASE WHEN pc.pres_estado IN ('CANCELADO', 'CONDONADO', 'REFINANCIADO') THEN 1 ELSE 0 END) AS 'Prestamos Finalizados'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN sucursales s ON r.sucursal_id = s.id
            WHERE pc.pres_aprobacion = 1
        ";

        // Filtrar por fecha si se especifica
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            $query .= " AND DATE(pc.pres_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin";
        }

        // Filtrar por sucursal específica si se especifica
        if (!empty($datos['sucursal_id'])) {
            $query .= " AND s.id = :sucursal_id";
        }

        // Agrupar por sucursal
        $query .= " GROUP BY s.id, s.nombre";

        // Ordenar por monto total descendente
        $query .= " ORDER BY SUM(pc.pres_monto) DESC";

        $stmt = Conexion::conectar()->prepare($query);

        // Bind de parámetros de fecha si existen
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);
        }

        // Bind de parámetro de sucursal si existe
        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReporteSaldosPendientes($datos) {
        $query = "
            SELECT
                c.cliente_nombres AS 'Cliente',
                pc.nro_prestamo AS 'Nro Prestamo',
                SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' THEN pd.pdetalle_monto_cuota ELSE 0 END) AS 'Saldo Pendiente',
                COUNT(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' THEN 1 END) AS 'Cuotas Pendientes',
                MIN(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' THEN pd.pdetalle_fecha END) AS 'Proxima Cuota'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            WHERE pc.pres_aprobacion = 1
        ";

        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        $query .= " GROUP BY pc.nro_prestamo 
                   HAVING SUM(CASE WHEN pd.pdetalle_estado_cuota = 'PENDIENTE' THEN pd.pdetalle_monto_cuota ELSE 0 END) > 0
                   ORDER BY c.cliente_nombres";

        $stmt = Conexion::conectar()->prepare($query);

        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function mdlReportePrestamosFinalizados($datos) {
        $query = "
            SELECT 
                pc.nro_prestamo AS 'Nro Prestamo',
                c.cliente_nombres AS 'Cliente',
                pc.pres_monto AS 'Monto Prestamo',
                pc.pres_fecha_registro AS 'Fecha Otorgamiento',
                MAX(pd.pdetalle_fecha_registro) AS 'Fecha Finalización',
                pc.pres_cuotas AS 'Total Cuotas',
                pc.pres_cuotas_pagadas AS 'Cuotas Pagadas',
                pc.pres_monto_total AS 'Monto Total',
                r.ruta_nombre AS 'Ruta',
                s.nombre AS 'Sucursal',
                CASE 
                    WHEN pc.pres_estado = 'CANCELADO' THEN 'Pagado completamente'
                    WHEN pc.pres_estado = 'CONDONADO' THEN 'Condonado'
                    WHEN pc.pres_estado = 'REFINANCIADO' THEN 'Refinanciado'
                    ELSE pc.pres_estado
                END AS 'Estado'
            FROM prestamo_cabecera pc
            JOIN clientes c ON pc.cliente_id = c.cliente_id
            LEFT JOIN prestamo_detalle pd ON pc.nro_prestamo = pd.nro_prestamo
            LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id
            LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
            LEFT JOIN sucursales s ON r.sucursal_id = s.id
            WHERE pc.pres_estado IN ('CANCELADO', 'CONDONADO', 'REFINANCIADO')
            AND pc.pres_aprobacion = 1
        ";

        // Filtrar por fecha si se especifica
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            $query .= " AND DATE(pc.pres_fecha_registro) BETWEEN :fecha_inicio AND :fecha_fin";
        }

        // Filtrar por sucursal si se especifica
        if (!empty($datos['sucursal_id'])) {
            $query .= " AND r.sucursal_id = :sucursal_id";
        }

        // Filtrar por ruta si se especifica
        if (!empty($datos['ruta_id'])) {
            $query .= " AND cr.ruta_id = :ruta_id";
        }

        // Agrupar por préstamo para obtener la última fecha de pago
        $query .= " GROUP BY pc.nro_prestamo, c.cliente_nombres, pc.pres_monto, pc.pres_fecha_registro, 
                    pc.pres_cuotas, pc.pres_cuotas_pagadas, pc.pres_monto_total, r.ruta_nombre, s.nombre, pc.pres_estado";

        // Ordenar por la última fecha de pago descendente
        $query .= " ORDER BY MAX(pd.pdetalle_fecha_registro) DESC";

        $stmt = Conexion::conectar()->prepare($query);

        // Bind de parámetros de fecha si existen
        if (!empty($datos['fecha_inicio']) && !empty($datos['fecha_fin'])) {
            $stmt->bindParam(":fecha_inicio", $datos['fecha_inicio'], PDO::PARAM_STR);
            $stmt->bindParam(":fecha_fin", $datos['fecha_fin'], PDO::PARAM_STR);
        }

        // Bind de parámetros de filtros
        if (!empty($datos['sucursal_id'])) {
            $stmt->bindParam(":sucursal_id", $datos['sucursal_id'], PDO::PARAM_INT);
        }
        if (!empty($datos['ruta_id'])) {
            $stmt->bindParam(":ruta_id", $datos['ruta_id'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 
