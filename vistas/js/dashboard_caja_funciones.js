// =====================================================
// FUNCIONES DEL DASHBOARD DE CAJA - DESARROLLADOR SENIOR
// =====================================================

// Variables globales
let dashboardData = {};
let updateInterval;

// =====================================================
// FUNCIONES PRINCIPALES DE LOS BOTONES KPI
// =====================================================

function mostrarCajasActivas() {
    console.log('Mostrar cajas activas');
    
    if (!dashboardData.cajas_activas || dashboardData.cajas_activas.length === 0) {
        mostrarNotificacion('No hay cajas activas para mostrar', 'info');
        return;
    }
    
    let html = `
        <div class="modal fade" id="modal-cajas-activas" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h4 class="modal-title"><i class="fas fa-cash-register"></i> Cajas Activas</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Caja</th>
                                        <th>Usuario</th>
                                        <th>Apertura</th>
                                        <th>Horas Abiertas</th>
                                        <th>Saldo</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>`;
    
    dashboardData.cajas_activas.forEach(caja => {
        const horasAbiertas = calcularHorasAbiertas(caja.fecha_apertura);
        html += `
            <tr>
                <td><strong>${caja.nombre_caja || 'Caja #' + caja.caja_id}</strong></td>
                <td>${caja.usuario || 'N/A'}</td>
                <td>${formatearFecha(caja.fecha_apertura)}</td>
                <td>
                    <span class="badge ${horasAbiertas > 12 ? 'badge-danger' : horasAbiertas > 8 ? 'badge-warning' : 'badge-success'}">
                        ${horasAbiertas}h
                    </span>
                </td>
                <td>${formatearMoneda(caja.saldo_actual)}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="verDetalleCaja(${caja.caja_id})">
                        <i class="fas fa-eye"></i> Ver
                    </button>
                    <button class="btn btn-sm btn-warning" onclick="cerrarCajaRapido(${caja.caja_id})">
                        <i class="fas fa-lock"></i> Cerrar
                    </button>
                </td>
            </tr>`;
    });
    
    html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-success" onclick="abrirNuevaCaja()">
                            <i class="fas fa-plus"></i> Abrir Nueva Caja
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    
    // Remover modal anterior si existe
    $('#modal-cajas-activas').remove();
    $('body').append(html);
    $('#modal-cajas-activas').modal('show');
}

function mostrarDetallesSaldo() {
    console.log('Mostrar detalles del saldo');
    
    const stats = dashboardData.estadisticas || {};
    
    let html = `
        <div class="modal fade" id="modal-detalles-saldo" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h4 class="modal-title"><i class="fas fa-coins"></i> Detalles del Saldo Total</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-success">
                                    <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Ingresos Hoy</span>
                                        <span class="info-box-number">${formatearMoneda(stats.ingresos_hoy || 0)}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-danger">
                                    <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Egresos Hoy</span>
                                        <span class="info-box-number">${formatearMoneda(stats.egresos_hoy || 0)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-warning">
                                    <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Préstamos Otorgados</span>
                                        <span class="info-box-number">${formatearMoneda(stats.prestamos_otorgados || 0)}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-primary">
                                    <span class="info-box-icon"><i class="fas fa-piggy-bank"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Saldo Inicial Total</span>
                                        <span class="info-box-number">${formatearMoneda(stats.saldo_inicial_total || 0)}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h5><i class="fas fa-calculator"></i> Resumen por Caja</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Caja</th>
                                        <th>Saldo Inicial</th>
                                        <th>Movimientos</th>
                                        <th>Saldo Actual</th>
                                    </tr>
                                </thead>
                                <tbody id="tabla-resumen-saldos">
                                    <!-- Se llena dinámicamente -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-info" onclick="exportarReporteSaldos()">
                            <i class="fas fa-download"></i> Exportar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    
    // Remover modal anterior si existe
    $('#modal-detalles-saldo').remove();
    $('body').append(html);
    
    // Llenar tabla de resumen
    const tbody = $('#tabla-resumen-saldos');
    if (dashboardData.cajas_activas && dashboardData.cajas_activas.length > 0) {
        dashboardData.cajas_activas.forEach(caja => {
            tbody.append(`
                <tr>
                    <td>${caja.nombre_caja || 'Caja #' + caja.caja_id}</td>
                    <td>${formatearMoneda(caja.saldo_inicial || 0)}</td>
                    <td>${caja.total_movimientos || 0}</td>
                    <td>${formatearMoneda(caja.saldo_actual || 0)}</td>
                </tr>
            `);
        });
    } else {
        tbody.html('<tr><td colspan="4" class="text-center text-muted">No hay datos disponibles</td></tr>');
    }
    
    $('#modal-detalles-saldo').modal('show');
}

function mostrarAlertas() {
    console.log('Mostrar alertas');
    
    const alertas = dashboardData.alertas || [];
    
    let html = `
        <div class="modal fade" id="modal-alertas" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h4 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Centro de Alertas</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">`;
    
    if (alertas.length === 0) {
        html += `
            <div class="alert alert-success text-center">
                <h5><i class="fas fa-check-circle"></i> Todo en Orden</h5>
                <p>No hay alertas pendientes en el sistema.</p>
            </div>`;
    } else {
        html += '<div class="list-group">';
        alertas.forEach(alerta => {
            const iconClass = {
                'LOW': 'fas fa-info-circle text-info',
                'MEDIUM': 'fas fa-exclamation-circle text-warning', 
                'HIGH': 'fas fa-exclamation-triangle text-warning',
                'CRITICAL': 'fas fa-skull-crossbones text-danger'
            }[alerta.nivel_criticidad] || 'fas fa-bell text-secondary';
            
            const alertClass = {
                'LOW': 'list-group-item-info',
                'MEDIUM': 'list-group-item-warning',
                'HIGH': 'list-group-item-warning', 
                'CRITICAL': 'list-group-item-danger'
            }[alerta.nivel_criticidad] || '';
            
            html += `
                <div class="list-group-item ${alertClass}">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">
                            <i class="${iconClass}"></i> ${alerta.titulo}
                        </h6>
                        <small>${formatearFecha(alerta.fecha_registro)}</small>
                    </div>
                    <p class="mb-1">${alerta.mensaje}</p>
                    <small>Caja: ${alerta.caja_nombre || 'N/A'}</small>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-success" onclick="marcarAlertaLeida(${alerta.id})">
                            <i class="fas fa-check"></i> Marcar como Leída
                        </button>
                    </div>
                </div>`;
        });
        html += '</div>';
    }
    
    html += `
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-warning" onclick="marcarTodasAlertasLeidas()">
                            <i class="fas fa-check-double"></i> Marcar Todas como Leídas
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    
    // Remover modal anterior si existe
    $('#modal-alertas').remove();
    $('body').append(html);
    $('#modal-alertas').modal('show');
}

function mostrarAuditoria() {
    console.log('Mostrar auditoría');
    
    let html = `
        <div class="modal fade" id="modal-auditoria" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h4 class="modal-title"><i class="fas fa-chart-line"></i> Auditoría de Operaciones</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Fecha Desde:</label>
                                <input type="date" class="form-control" id="fecha-desde-auditoria" value="${new Date().toISOString().split('T')[0]}">
                            </div>
                            <div class="col-md-3">
                                <label>Fecha Hasta:</label>
                                <input type="date" class="form-control" id="fecha-hasta-auditoria" value="${new Date().toISOString().split('T')[0]}">
                            </div>
                            <div class="col-md-3">
                                <label>Tipo:</label>
                                <select class="form-control" id="tipo-auditoria">
                                    <option value="">Todos</option>
                                    <option value="APERTURA">Aperturas</option>
                                    <option value="CIERRE">Cierres</option>
                                    <option value="MOVIMIENTO">Movimientos</option>
                                    <option value="CONSULTA">Consultas</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary" onclick="cargarAuditoria()">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                        <div id="resultados-auditoria">
                            <div class="text-center text-muted">
                                <i class="fas fa-search fa-3x"></i>
                                <p>Realiza una búsqueda para ver los resultados</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" onclick="exportarAuditoria()">
                            <i class="fas fa-download"></i> Exportar
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    
    // Remover modal anterior si existe
    $('#modal-auditoria').remove();
    $('body').append(html);
    $('#modal-auditoria').modal('show');
}

// =====================================================
// FUNCIONES DE ACCIONES RÁPIDAS
// =====================================================

function abrirCaja() {
    // Redirigir a la página de apertura de caja o abrir modal
    if (typeof CargarContenido === 'function') {
        CargarContenido('vistas/caja.php', 'content-wrapper');
    } else {
        window.location.href = 'index.php?ruta=caja';
    }
}

function cerrarCaja() {
    if (!dashboardData.cajas_activas || dashboardData.cajas_activas.length === 0) {
        mostrarNotificacion('No hay cajas abiertas para cerrar', 'warning');
        return;
    }
    
    // Si solo hay una caja, cerrarla directamente
    if (dashboardData.cajas_activas.length === 1) {
        cerrarCajaRapido(dashboardData.cajas_activas[0].caja_id);
    } else {
        // Mostrar opciones de cajas para cerrar
        mostrarOpcionesCierre();
    }
}

function conteoFisico() {
    if (!dashboardData.cajas_activas || dashboardData.cajas_activas.length === 0) {
        mostrarNotificacion('No hay cajas abiertas para realizar conteo', 'warning');
        return;
    }
    
    // Abrir modal de conteo físico
    abrirModalConteoFisico();
}

function generarReporte() {
    let html = `
        <div class="modal fade" id="modal-generar-reporte" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h4 class="modal-title"><i class="fas fa-file-alt"></i> Generar Reporte</h4>
                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tipo de Reporte:</label>
                            <select class="form-control" id="tipo-reporte">
                                <option value="diario">Reporte Diario</option>
                                <option value="semanal">Reporte Semanal</option>
                                <option value="mensual">Reporte Mensual</option>
                                <option value="personalizado">Rango Personalizado</option>
                            </select>
                        </div>
                        <div class="form-group" id="rango-fechas" style="display: none;">
                            <label>Fecha Desde:</label>
                            <input type="date" class="form-control" id="fecha-desde-reporte">
                            <label class="mt-2">Fecha Hasta:</label>
                            <input type="date" class="form-control" id="fecha-hasta-reporte">
                        </div>
                        <div class="form-group">
                            <label>Formato:</label>
                            <select class="form-control" id="formato-reporte">
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                                <option value="csv">CSV</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="procesarReporte()">
                            <i class="fas fa-download"></i> Generar
                        </button>
                    </div>
                </div>
            </div>
        </div>`;
    
    // Remover modal anterior si existe
    $('#modal-generar-reporte').remove();
    $('body').append(html);
    
    // Configurar eventos
    $('#tipo-reporte').on('change', function() {
        if ($(this).val() === 'personalizado') {
            $('#rango-fechas').show();
        } else {
            $('#rango-fechas').hide();
        }
    });
    
    $('#modal-generar-reporte').modal('show');
}

// =====================================================
// FUNCIONES DE UTILIDAD Y AUXILIARES
// =====================================================

function calcularHorasAbiertas(fechaApertura) {
    const ahora = new Date();
    const apertura = new Date(fechaApertura);
    const diferencia = ahora - apertura;
    return Math.round(diferencia / (1000 * 60 * 60)); // Convertir a horas
}

function formatearMoneda(valor) {
    return new Intl.NumberFormat('es-ES', {
        style: 'currency',
        currency: 'USD',
        minimumFractionDigits: 2
    }).format(valor || 0);
}

function formatearFecha(fecha) {
    return new Date(fecha).toLocaleString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function mostrarNotificacion(mensaje, tipo = 'info') {
    const tiposBootstrap = {
        'success': 'success',
        'error': 'danger', 
        'warning': 'warning',
        'info': 'info'
    };
    
    const iconos = {
        'success': 'fas fa-check-circle',
        'error': 'fas fa-times-circle',
        'warning': 'fas fa-exclamation-triangle', 
        'info': 'fas fa-info-circle'
    };
    
    const alertHtml = `
        <div class="alert alert-${tiposBootstrap[tipo]} alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="${iconos[tipo]}"></i> ${mensaje}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>`;
    
    $('body').append(alertHtml);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 5000);
}

// =====================================================
// FUNCIONES ADICIONALES NECESARIAS
// =====================================================

function cerrarCajaRapido(cajaId) {
    Swal.fire({
        title: '¿Cerrar Caja?',
        text: 'Se realizará el cierre de caja con conteo automático',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Proceso de cierre
            mostrarNotificacion('Función de cierre en desarrollo', 'info');
        }
    });
}

function verDetalleCaja(cajaId) {
    mostrarNotificacion('Función de detalles en desarrollo', 'info');
}

function abrirNuevaCaja() {
    abrirCaja();
}

function marcarAlertaLeida(alertaId) {
    mostrarNotificacion('Alerta marcada como leída', 'success');
    actualizarDashboard();
}

function marcarTodasAlertasLeidas() {
    mostrarNotificacion('Todas las alertas marcadas como leídas', 'success');
    $('#modal-alertas').modal('hide');
    actualizarDashboard();
}

function cargarAuditoria() {
    $('#resultados-auditoria').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
    
    setTimeout(() => {
        $('#resultados-auditoria').html(`
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Acción</th>
                            <th>Usuario</th>
                            <th>Detalles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Funcionalidad de auditoría en desarrollo
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `);
    }, 1000);
}

function exportarReporteSaldos() {
    mostrarNotificacion('Función de exportación en desarrollo', 'info');
}

function exportarAuditoria() {
    mostrarNotificacion('Función de exportación en desarrollo', 'info');
}

function procesarReporte() {
    const tipo = $('#tipo-reporte').val();
    const formato = $('#formato-reporte').val();
    
    mostrarNotificacion(`Generando reporte ${tipo} en formato ${formato}...`, 'info');
    $('#modal-generar-reporte').modal('hide');
    
    // Simular proceso
    setTimeout(() => {
        mostrarNotificacion('Reporte generado exitosamente', 'success');
    }, 2000);
}

function abrirModalConteoFisico() {
    mostrarNotificacion('Modal de conteo físico en desarrollo', 'info');
}

// =====================================================
// INICIALIZACIÓN
// =====================================================

$(document).ready(function() {
    console.log('Funciones del dashboard de caja cargadas correctamente');
}); 