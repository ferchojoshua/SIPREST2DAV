<?php

require_once "conexion.php";

class DashboardCobradoresModelo
{
    /**
     * Obtener métricas generales del dashboard
     */
    static public function mdlObtenerMetricasGenerales($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    COALESCE(SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as total_cobrado,
                    COALESCE(SUM(CASE WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as total_mora,
                    COALESCE(SUM(pd.cuota_monto), 0) as total_esperado,
                    COUNT(DISTINCT CASE WHEN ur.usuario_id IS NOT NULL THEN ur.usuario_id END) as cobradores_activos,
                    COUNT(DISTINCT c.cliente_id) as total_clientes,
                    CASE 
                        WHEN SUM(pd.cuota_monto) > 0 
                        THEN ROUND((SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END) / SUM(pd.cuota_monto)) * 100, 2)
                        ELSE 0 
                    END as eficiencia_cobro
                FROM prestamo_cabecera pc
                INNER JOIN prestamo_detalle pd ON pc.pres_id = pd.prestamo_id
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id AND cr.estado = 'activo'
                LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
                LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
                LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
                LEFT JOIN sucursales s ON r.sucursal_id = s.id
                WHERE pc.pres_aprobacion = 'aprobado'
                AND pd.cuota_fecha_vencimiento BETWEEN :fecha_inicio AND :fecha_fin
            ";
            
            // Aplicar filtros dinámicos
            $params = [
                ':fecha_inicio' => $filtros['fecha_inicio'],
                ':fecha_fin' => $filtros['fecha_fin']
            ];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            if (!empty($filtros['ruta_id'])) {
                $sql .= " AND r.ruta_id = :ruta_id";
                $params[':ruta_id'] = $filtros['ruta_id'];
            }
            
            if (!empty($filtros['cobrador_id'])) {
                $sql .= " AND u.id_usuario = :cobrador_id";
                $params[':cobrador_id'] = $filtros['cobrador_id'];
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Asegurar que no hay valores null
            foreach ($resultado as $key => $value) {
                if ($value === null) {
                    $resultado[$key] = 0;
                }
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerMetricasGenerales: " . $e->getMessage());
            throw new Exception("Error al obtener métricas generales");
        }
    }

    /**
     * Obtener cobros por cobrador para gráfico de pastel
     */
    static public function mdlObtenerCobrosPorCobrador($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as cobrador_nombre,
                    u.usuario as cobrador_usuario,
                    u.id_usuario as cobrador_id,
                    s.nombre as sucursal_nombre,
                    r.ruta_nombre,
                    COALESCE(SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as total_cobrado,
                    COUNT(DISTINCT c.cliente_id) as total_clientes,
                    COUNT(DISTINCT pd.prestamo_detalle_id) as total_cuotas
                FROM usuarios u
                INNER JOIN usuarios_rutas ur ON u.id_usuario = ur.usuario_id AND ur.estado = 'activo'
                INNER JOIN rutas r ON ur.ruta_id = r.ruta_id
                INNER JOIN sucursales s ON r.sucursal_id = s.id
                LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id AND cr.estado = 'activo'
                LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
                LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_aprobacion = 'aprobado'
                LEFT JOIN prestamo_detalle pd ON pc.pres_id = pd.prestamo_id 
                    AND pd.cuota_fecha_vencimiento BETWEEN :fecha_inicio AND :fecha_fin
                WHERE u.estado = 1
            ";
            
            // Aplicar filtros dinámicos
            $params = [
                ':fecha_inicio' => $filtros['fecha_inicio'],
                ':fecha_fin' => $filtros['fecha_fin']
            ];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            if (!empty($filtros['ruta_id'])) {
                $sql .= " AND r.ruta_id = :ruta_id";
                $params[':ruta_id'] = $filtros['ruta_id'];
            }
            
            if (!empty($filtros['cobrador_id'])) {
                $sql .= " AND u.id_usuario = :cobrador_id";
                $params[':cobrador_id'] = $filtros['cobrador_id'];
            }
            
            $sql .= "
                GROUP BY u.id_usuario, u.nombre_usuario, u.apellido_usuario, u.usuario, s.nombre, r.ruta_nombre
                HAVING total_cobrado > 0
                ORDER BY total_cobrado DESC
                LIMIT 20
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerCobrosPorCobrador: " . $e->getMessage());
            throw new Exception("Error al obtener cobros por cobrador");
        }
    }

    /**
     * Obtener mora por cobrador para gráfico de pastel
     */
    static public function mdlObtenerMoraPorCobrador($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as cobrador_nombre,
                    u.usuario as cobrador_usuario,
                    u.id_usuario as cobrador_id,
                    s.nombre as sucursal_nombre,
                    r.ruta_nombre,
                    COALESCE(SUM(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN pd.cuota_monto ELSE 0 
                    END), 0) as total_mora,
                    COUNT(DISTINCT CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN c.cliente_id 
                    END) as clientes_con_mora,
                    AVG(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN DATEDIFF(CURDATE(), pd.cuota_fecha_vencimiento) 
                    END) as dias_mora_promedio
                FROM usuarios u
                INNER JOIN usuarios_rutas ur ON u.id_usuario = ur.usuario_id AND ur.estado = 'activo'
                INNER JOIN rutas r ON ur.ruta_id = r.ruta_id
                INNER JOIN sucursales s ON r.sucursal_id = s.id
                LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id AND cr.estado = 'activo'
                LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
                LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_aprobacion = 'aprobado'
                LEFT JOIN prestamo_detalle pd ON pc.pres_id = pd.prestamo_id 
                    AND pd.cuota_fecha_vencimiento <= :fecha_fin
                WHERE u.estado = 1
            ";
            
            // Aplicar filtros dinámicos
            $params = [
                ':fecha_fin' => $filtros['fecha_fin']
            ];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            if (!empty($filtros['ruta_id'])) {
                $sql .= " AND r.ruta_id = :ruta_id";
                $params[':ruta_id'] = $filtros['ruta_id'];
            }
            
            if (!empty($filtros['cobrador_id'])) {
                $sql .= " AND u.id_usuario = :cobrador_id";
                $params[':cobrador_id'] = $filtros['cobrador_id'];
            }
            
            $sql .= "
                GROUP BY u.id_usuario, u.nombre_usuario, u.apellido_usuario, u.usuario, s.nombre, r.ruta_nombre
                HAVING total_mora > 0
                ORDER BY total_mora DESC
                LIMIT 20
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerMoraPorCobrador: " . $e->getMessage());
            throw new Exception("Error al obtener mora por cobrador");
        }
    }

    /**
     * Obtener comparación diaria para gráfico de líneas
     */
    static public function mdlObtenerComparacionDiaria($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    DATE(pd.cuota_fecha_vencimiento) as fecha,
                    COALESCE(SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as cobros,
                    COALESCE(SUM(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN pd.cuota_monto ELSE 0 
                    END), 0) as mora,
                    COUNT(DISTINCT c.cliente_id) as clientes_atendidos
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.prestamo_id = pc.pres_id
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id AND cr.estado = 'activo'
                LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
                LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
                LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
                LEFT JOIN sucursales s ON r.sucursal_id = s.id
                WHERE pc.pres_aprobacion = 'aprobado'
                AND pd.cuota_fecha_vencimiento BETWEEN :fecha_inicio AND :fecha_fin
            ";
            
            // Aplicar filtros dinámicos
            $params = [
                ':fecha_inicio' => $filtros['fecha_inicio'],
                ':fecha_fin' => $filtros['fecha_fin']
            ];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            if (!empty($filtros['ruta_id'])) {
                $sql .= " AND r.ruta_id = :ruta_id";
                $params[':ruta_id'] = $filtros['ruta_id'];
            }
            
            if (!empty($filtros['cobrador_id'])) {
                $sql .= " AND u.id_usuario = :cobrador_id";
                $params[':cobrador_id'] = $filtros['cobrador_id'];
            }
            
            $sql .= "
                GROUP BY DATE(pd.cuota_fecha_vencimiento)
                ORDER BY fecha ASC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerComparacionDiaria: " . $e->getMessage());
            throw new Exception("Error al obtener comparación diaria");
        }
    }

    /**
     * Obtener tabla de rendimiento detallado
     */
    static public function mdlObtenerTablaRendimiento($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    u.id_usuario as cobrador_id,
                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as cobrador_nombre,
                    u.usuario as cobrador_usuario,
                    s.nombre as sucursal_nombre,
                    GROUP_CONCAT(DISTINCT r.ruta_nombre SEPARATOR ', ') as ruta_nombre,
                    COALESCE(SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as total_cobrado,
                    COALESCE(SUM(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN pd.cuota_monto ELSE 0 
                    END), 0) as total_mora,
                    COALESCE(SUM(pd.cuota_monto), 0) as total_esperado,
                    COUNT(DISTINCT c.cliente_id) as total_clientes,
                    COUNT(DISTINCT CASE WHEN pc.pres_estado = 'cobrado' THEN c.cliente_id END) as clientes_al_dia,
                    COUNT(DISTINCT CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN c.cliente_id 
                    END) as clientes_con_mora,
                    AVG(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN DATEDIFF(CURDATE(), pd.cuota_fecha_vencimiento) 
                    END) as dias_mora_promedio,
                    MAX(pd.cuota_fecha_vencimiento) as ultima_actividad
                FROM usuarios u
                INNER JOIN usuarios_rutas ur ON u.id_usuario = ur.usuario_id AND ur.estado = 'activo'
                INNER JOIN rutas r ON ur.ruta_id = r.ruta_id
                INNER JOIN sucursales s ON r.sucursal_id = s.id
                LEFT JOIN clientes_rutas cr ON r.ruta_id = cr.ruta_id AND cr.estado = 'activo'
                LEFT JOIN clientes c ON cr.cliente_id = c.cliente_id
                LEFT JOIN prestamo_cabecera pc ON c.cliente_id = pc.cliente_id AND pc.pres_aprobacion = 'aprobado'
                LEFT JOIN prestamo_detalle pd ON pc.pres_id = pd.prestamo_id 
                    AND pd.cuota_fecha_vencimiento BETWEEN :fecha_inicio AND :fecha_fin
                WHERE u.estado = 1
            ";
            
            // Aplicar filtros dinámicos
            $params = [
                ':fecha_inicio' => $filtros['fecha_inicio'],
                ':fecha_fin' => $filtros['fecha_fin']
            ];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            if (!empty($filtros['ruta_id'])) {
                $sql .= " AND r.ruta_id = :ruta_id";
                $params[':ruta_id'] = $filtros['ruta_id'];
            }
            
            if (!empty($filtros['cobrador_id'])) {
                $sql .= " AND u.id_usuario = :cobrador_id";
                $params[':cobrador_id'] = $filtros['cobrador_id'];
            }
            
            $sql .= "
                GROUP BY u.id_usuario, u.nombre_usuario, u.apellido_usuario, u.usuario, s.nombre
                ORDER BY total_cobrado DESC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerTablaRendimiento: " . $e->getMessage());
            throw new Exception("Error al obtener tabla de rendimiento");
        }
    }

    /**
     * Listar cobradores por sucursal
     */
    static public function mdlListarCobradoresPorSucursal($sucursal_id)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT DISTINCT
                    u.id_usuario as usuario_id,
                    CONCAT(u.nombre_usuario, ' ', u.apellido_usuario) as nombre_completo,
                    u.usuario,
                    s.nombre as sucursal_nombre,
                    COUNT(DISTINCT r.ruta_id) as total_rutas_asignadas
                FROM usuarios u
                INNER JOIN usuarios_rutas ur ON u.id_usuario = ur.usuario_id AND ur.estado = 'activo'
                INNER JOIN rutas r ON ur.ruta_id = r.ruta_id
                INNER JOIN sucursales s ON r.sucursal_id = s.id
                WHERE u.estado = 1 
                AND s.id = :sucursal_id
                GROUP BY u.id_usuario, u.nombre_usuario, u.apellido_usuario, u.usuario, s.nombre
                ORDER BY u.nombre_usuario, u.apellido_usuario
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':sucursal_id', $sucursal_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlListarCobradoresPorSucursal: " . $e->getMessage());
            throw new Exception("Error al listar cobradores por sucursal");
        }
    }

    /**
     * Obtener resumen ejecutivo
     */
    static public function mdlObtenerResumenEjecutivo($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    COALESCE(SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as total_cobrado,
                    COALESCE(SUM(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN pd.cuota_monto ELSE 0 
                    END), 0) as total_mora,
                    COALESCE(SUM(pd.cuota_monto), 0) as total_esperado,
                    COALESCE(SUM(pc.pres_monto), 0) as total_cartera,
                    COUNT(DISTINCT u.id_usuario) as total_cobradores,
                    COUNT(DISTINCT r.ruta_id) as total_rutas_activas,
                    COUNT(DISTINCT c.cliente_id) as total_clientes,
                    COUNT(DISTINCT s.id) as total_sucursales,
                    AVG(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN DATEDIFF(CURDATE(), pd.cuota_fecha_vencimiento) 
                    END) as dias_mora_promedio_global
                FROM prestamo_cabecera pc
                INNER JOIN prestamo_detalle pd ON pc.pres_id = pd.prestamo_id
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id AND cr.estado = 'activo'
                LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
                LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
                LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
                LEFT JOIN sucursales s ON r.sucursal_id = s.id
                WHERE pc.pres_aprobacion = 'aprobado'
                AND pd.cuota_fecha_vencimiento BETWEEN :fecha_inicio AND :fecha_fin
            ";
            
            // Aplicar filtros dinámicos
            $params = [
                ':fecha_inicio' => $filtros['fecha_inicio'],
                ':fecha_fin' => $filtros['fecha_fin']
            ];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            if (!empty($filtros['ruta_id'])) {
                $sql .= " AND r.ruta_id = :ruta_id";
                $params[':ruta_id'] = $filtros['ruta_id'];
            }
            
            if (!empty($filtros['cobrador_id'])) {
                $sql .= " AND u.id_usuario = :cobrador_id";
                $params[':cobrador_id'] = $filtros['cobrador_id'];
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerResumenEjecutivo: " . $e->getMessage());
            throw new Exception("Error al obtener resumen ejecutivo");
        }
    }

    /**
     * Calcular cobertura de rutas
     */
    static public function mdlCalcularCoberturaRutas($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    COUNT(DISTINCT r.ruta_id) as rutas_con_cobradores,
                    (SELECT COUNT(*) FROM rutas WHERE ruta_estado = 'activa') as total_rutas_activas
                FROM rutas r
                INNER JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
                INNER JOIN usuarios u ON ur.usuario_id = u.id_usuario AND u.estado = 1
                WHERE r.ruta_estado = 'activa'
            ";
            
            $params = [];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND r.sucursal_id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado['total_rutas_activas'] > 0) {
                return round(($resultado['rutas_con_cobradores'] / $resultado['total_rutas_activas']) * 100, 2);
            }
            
            return 0;
            
        } catch (Exception $e) {
            error_log("Error en mdlCalcularCoberturaRutas: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Obtener tendencias mensuales para análisis avanzado
     */
    static public function mdlObtenerTendenciasMensuales($filtros)
    {
        try {
            $pdo = Conexion::conectar();
            
            $sql = "
                SELECT 
                    DATE_FORMAT(pd.cuota_fecha_vencimiento, '%Y-%m') as mes,
                    COALESCE(SUM(CASE WHEN pc.pres_estado = 'cobrado' THEN pd.cuota_monto ELSE 0 END), 0) as cobros,
                    COALESCE(SUM(CASE 
                        WHEN pd.cuota_fecha_vencimiento < CURDATE() AND pc.pres_estado != 'cobrado' 
                        THEN pd.cuota_monto ELSE 0 
                    END), 0) as mora,
                    COUNT(DISTINCT c.cliente_id) as clientes_activos
                FROM prestamo_detalle pd
                INNER JOIN prestamo_cabecera pc ON pd.prestamo_id = pc.pres_id
                INNER JOIN clientes c ON pc.cliente_id = c.cliente_id
                LEFT JOIN clientes_rutas cr ON c.cliente_id = cr.cliente_id AND cr.estado = 'activo'
                LEFT JOIN rutas r ON cr.ruta_id = r.ruta_id
                LEFT JOIN usuarios_rutas ur ON r.ruta_id = ur.ruta_id AND ur.estado = 'activo'
                LEFT JOIN usuarios u ON ur.usuario_id = u.id_usuario
                LEFT JOIN sucursales s ON r.sucursal_id = s.id
                WHERE pc.pres_aprobacion = 'aprobado'
                AND pd.cuota_fecha_vencimiento >= DATE_SUB(:fecha_fin, INTERVAL 12 MONTH)
                AND pd.cuota_fecha_vencimiento <= :fecha_fin
            ";
            
            $params = [':fecha_fin' => $filtros['fecha_fin']];
            
            if (!empty($filtros['sucursal_id'])) {
                $sql .= " AND s.id = :sucursal_id";
                $params[':sucursal_id'] = $filtros['sucursal_id'];
            }
            
            $sql .= "
                GROUP BY DATE_FORMAT(pd.cuota_fecha_vencimiento, '%Y-%m')
                ORDER BY mes ASC
            ";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Error en mdlObtenerTendenciasMensuales: " . $e->getMessage());
            throw new Exception("Error al obtener tendencias mensuales");
        }
    }
}
?> 