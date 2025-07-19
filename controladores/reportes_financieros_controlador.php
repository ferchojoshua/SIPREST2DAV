<?php

class ControladorReportesFinancieros {

    public static function ctrObtenerSucursales() {
        return ModeloReportesFinancieros::mdlObtenerSucursales();
    }

    public static function ctrObtenerRutas($sucursal_id) {
        return ModeloReportesFinancieros::mdlObtenerRutas($sucursal_id);
    }

    public static function ctrBuscarClientes($busqueda) {
        return ModeloReportesFinancieros::mdlBuscarClientes($busqueda);
    }
    
    public static function ctrGenerarReporte($datos) {
        $tipoReporte = $datos['tipo_reporte'];
        
        // Para reportes diarios, si no se especifica fecha, usar la fecha actual
        $reportesDiarios = ['pagos_del_dia', 'pendientes_del_dia', 'cobranza_por_colector'];
        
        if (in_array($tipoReporte, $reportesDiarios)) {
            if (empty($datos['fecha_inicio'])) {
                $datos['fecha_inicio'] = date('Y-m-d');
            }
            if (empty($datos['fecha_fin'])) {
                $datos['fecha_fin'] = $datos['fecha_inicio']; // Mismo día
            }
        }
        
        switch ($tipoReporte) {
            case 'clientes_mora':
                return ModeloReportesFinancieros::mdlReporteClientesMora($datos);
            case 'mora_por_colector':
                return ModeloReportesFinancieros::mdlReporteMoraPorColector($datos);
            case 'mora_por_ruta':
                return ModeloReportesFinancieros::mdlReporteMoraPorRuta($datos);
            case 'mora_por_sucursal':
                return ModeloReportesFinancieros::mdlReporteMoraPorSucursal($datos);
            case 'pagos_del_dia':
                return ModeloReportesFinancieros::mdlReportePagosDelDia($datos);
            case 'pendientes_del_dia':
                return ModeloReportesFinancieros::mdlReportePendientesDelDia($datos);
            case 'cobranza_por_colector':
                return ModeloReportesFinancieros::mdlReporteCobranzaPorColector($datos);
            case 'cobranza_por_ruta':
                return ModeloReportesFinancieros::mdlReporteCobranzaPorRuta($datos);
            case 'monto_colocado':
                return ModeloReportesFinancieros::mdlReporteMontoColocado($datos);
            case 'prestamos_vigentes':
                return ModeloReportesFinancieros::mdlReportePrestamosVigentes($datos);
            case 'cartera_vencida':
                return ModeloReportesFinancieros::mdlReporteCarteraVencida($datos);
            case 'resumen_cartera':
                return ModeloReportesFinancieros::mdlReporteResumenCartera($datos);
            case 'reporte_diario':
                return ModeloReportesFinancieros::mdlReporteDiario($datos);
            case 'caja_diaria':
                return ModeloReportesFinancieros::mdlReporteCajaDiaria($datos);
            case 'prestamos_activos':
                return ModeloReportesFinancieros::mdlReportePrestamosActivos($datos);
            case 'prestamos_finalizados':
                return ModeloReportesFinancieros::mdlReportePrestamosFinalizados($datos);
            case 'prestamos_por_cliente':
                if (!empty($datos['cliente_id'])) {
                    return ModeloReportesFinancieros::mdlReportePrestamosPorCliente($datos);
                } else {
                    return ['error' => 'Debe seleccionar un cliente para este reporte.'];
                }
            case 'prestamos_por_sucursal':
                return ModeloReportesFinancieros::mdlReportePrestamosPorSucursal($datos);
            case 'estado_cuenta_cliente':
                if (!empty($datos['cliente_id'])) {
                    return ModeloReportesFinancieros::mdlReporteEstadoCuentaCliente($datos);
                } else {
                    return ['error' => 'Debe seleccionar un cliente para este reporte.'];
                }
            case 'saldos_pendientes':
                return ModeloReportesFinancieros::mdlReporteSaldosPendientes($datos);
            default:
                return ['error' => 'Tipo de reporte no válido: ' . $tipoReporte];
        }
    }
}
?> 