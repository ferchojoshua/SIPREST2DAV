(function() {
// =====================================================
// FUNCIONES DEL DASHBOARD DE CAJA - DESARROLLADOR SENIOR
// =====================================================

// Variables locales
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
    // Verificar permisos primero
    $.ajax({
        url: "ajax/caja_ajax.php",
        method: "POST",
        data: {
            accion: "verificar_permisos_caja",
            sub_accion: "ABRIR_CAJA"
        },
        dataType: 'json',
        success: function(response) {
            if (response.success && response.permisos.puede_ejecutar) {
                // Mostrar el modal de apertura
                $("#modal_abrir_caja").modal('show');
                
                // Cargar sucursales si es necesario
                cargarSucursalesModal();
                
                // Establecer valores por defecto
                $("#text_descripcion").val('Apertura de Caja');
                $("#text_monto_ini").focus();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Permisos',
                    text: 'No tiene los permisos necesarios para abrir caja'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron verificar los permisos'
            });
        }
    });
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

// Exponer al ámbito global
window.mostrarNotificacion = mostrarNotificacion;

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

function verDetalleCaja(caja_id) {
    $('#detalle-caja-body').html('<div class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>');
    $('#modal-detalle-caja').modal('show');
    $.ajax({
        url: 'ajax/caja_ajax.php',
        method: 'POST',
        dataType: 'json',
        data: { accion: 'detalle_caja', caja_id },
        success: function(response) {
            if (response.success && response.data) {
                const c = response.data;
                let html = `<div class='mb-2'><strong>${c.caja_descripcion}</strong> <span class='badge badge-${c.caja_estado === 'VIGENTE' ? 'success' : 'secondary'}'>${c.caja_estado}</span></div>`;
                html += `<div><b>Usuario:</b> ${c.usuario_apertura_nombre || 'N/A'}</div>`;
                html += `<div><b>Fecha Apertura:</b> ${c.caja_f_apertura} ${c.caja_hora_apertura || ''}</div>`;
                html += `<div><b>Sucursal:</b> ${c.sucursal_nombre || 'N/A'}</div>`;
                html += `<hr class='my-2'>`;
                html += `<div><b>Monto Inicial:</b> ${formatearMoneda(c.caja_monto_inicial)}</div>`;
                html += `<div><b>Ingresos:</b> ${formatearMoneda(c.caja_monto_ingreso)}</div>`;
                html += `<div><b>Egresos:</b> ${formatearMoneda(c.caja__monto_egreso)}</div>`;
                html += `<div><b>Préstamos:</b> ${formatearMoneda(c.caja_prestamo)}</div>`;
                html += `<div><b>Saldo Actual:</b> ${formatearMoneda(c.caja_monto_total)}</div>`;
                if (c.observaciones_apertura) html += `<div><b>Obs. Apertura:</b> ${c.observaciones_apertura}</div>`;
                if (c.observaciones_cierre) html += `<div><b>Obs. Cierre:</b> ${c.observaciones_cierre}</div>`;
                $('#detalle-caja-body').html(html);
            } else {
                $('#detalle-caja-body').html('<div class="text-danger">No se encontraron detalles de la caja.</div>');
            }
        },
        error: function() {
            $('#detalle-caja-body').html('<div class="text-danger">Error al obtener detalles de la caja.</div>');
        }
    });
};

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

// === CONTEO FÍSICO CON DENOMINACIONES ===
window.realizarConteoFisico = function() {
    // Limpiar el formulario
    $('#form-conteo-fisico')[0].reset();
    $('#total-denominaciones').text('0');
    $('#tabla-denominaciones .denom-total').text('0');
    $('#modal-conteo-fisico').modal('show');
};

// Función para cerrar el modal manualmente
window.cerrarModalConteoFisico = function() {
    $('#modal-conteo-fisico').modal('hide');
};

// Event listeners para cerrar el modal
$(document).ready(function() {
    // Botón X del modal
    $('#modal-conteo-fisico .close').on('click', function() {
        $('#modal-conteo-fisico').modal('hide');
    });
    
    // Botón Cancelar del modal
    $('#modal-conteo-fisico .btn-secondary').on('click', function() {
        $('#modal-conteo-fisico').modal('hide');
    });
});

// Calcular total de denominaciones en tiempo real
$(document).on('input', '.input-denom', function() {
    let total = 0;
    $('#tabla-denominaciones tbody tr').each(function() {
        const valor = parseFloat($(this).find('.input-denom').data('valor'));
        const cantidad = parseInt($(this).find('.input-denom').val()) || 0;
        const subtotal = valor * cantidad;
        $(this).find('.denom-total').text(subtotal);
        total += subtotal;
    });
    $('#total-denominaciones').text(total);
});

window.confirmarConteoFisico = function() {
    const tipo = $('#tipo-conteo').val();
    const saldoFisico = parseFloat($('#saldo-fisico').val()) || 0;
    const observaciones = $('#observaciones-conteo').val();
    // Denominaciones
    let denominaciones = [];
    $('#tabla-denominaciones tbody tr').each(function() {
        const valor = parseFloat($(this).find('.input-denom').data('valor'));
        const cantidad = parseInt($(this).find('.input-denom').val()) || 0;
        if (cantidad > 0) {
            denominaciones.push({ valor, cantidad });
        }
    });
    // Validación básica
    if (saldoFisico <= 0) {
        Swal.fire('Error', 'Ingrese el saldo físico contado.', 'warning');
        return;
    }
    Swal.fire({
        title: '¿Registrar conteo físico?',
        text: 'Esta acción guardará el conteo físico de la caja.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Registrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/caja_ajax.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    accion: 'registrar_conteo_fisico',
                    tipo_conteo: tipo,
                    saldo_fisico: saldoFisico,
                    denominaciones: JSON.stringify(denominaciones),
                    observaciones: observaciones
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Éxito', response.message || 'Conteo físico registrado correctamente.', 'success');
                        $('#modal-conteo-fisico').modal('hide');
                        if (typeof window.actualizarDashboard === 'function') {
                            window.actualizarDashboard();
                        }
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo registrar el conteo físico.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                }
            });
        }
    });
};

// === GENERAR REPORTE ===
window.generarReporte = function() {
    $('#form-generar-reporte')[0].reset();
    $('#modal-generar-reporte').modal('show');
};

// Descargar reporte
$(document).on('click', '#btn-descargar-reporte', function() {
    const tipo = $('#tipo-reporte').val();
    const formato = $('#formato-reporte').val();
    if (!tipo || !formato) {
        Swal.fire('Error', 'Seleccione tipo y formato de reporte.', 'warning');
        return;
    }
    Swal.fire({ title: 'Generando reporte...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    $.ajax({
        url: 'ajax/caja_ajax.php',
        method: 'POST',
        data: { accion: 'generar_reporte_caja', tipo, formato },
        xhrFields: { responseType: 'blob' },
        success: function(data, status, xhr) {
            Swal.close();
            // Descargar archivo
            const blob = new Blob([data], { type: xhr.getResponseHeader('Content-Type') });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reporte_caja.${formato === 'pdf' ? 'pdf' : formato === 'excel' ? 'xlsx' : 'csv'}`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        },
        error: function() {
            Swal.fire('Error', 'No se pudo generar el reporte.', 'error');
        }
    });
});

// Enviar reporte por correo
$(document).on('click', '#btn-enviar-reporte', function() {
    const tipo = $('#tipo-reporte').val();
    const formato = $('#formato-reporte').val();
    const email = $('#email-reporte').val();
    if (!tipo || !formato) {
        Swal.fire('Error', 'Seleccione tipo y formato de reporte.', 'warning');
        return;
    }
    if (!email || !email.includes('@')) {
        Swal.fire('Error', 'Ingrese un correo válido.', 'warning');
        return;
    }
    Swal.fire({ title: 'Enviando reporte...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
    $.ajax({
        url: 'ajax/caja_ajax.php',
        method: 'POST',
        data: { accion: 'enviar_reporte_caja', tipo, formato, email },
        dataType: 'json',
        success: function(response) {
            Swal.close();
            if (response.success) {
                Swal.fire('Éxito', response.message || 'Reporte enviado correctamente.', 'success');
                $('#modal-generar-reporte').modal('hide');
            } else {
                Swal.fire('Error', response.message || 'No se pudo enviar el reporte.', 'error');
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        }
    });
});

window.cerrarCajaEspecifica = function(caja_id) {
    Swal.fire({
        title: '¿Cerrar esta caja?',
        text: 'Esta acción cerrará la caja seleccionada.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/caja_ajax.php',
                method: 'POST',
                dataType: 'json',
                data: { accion: 'cerrar_caja_especifica', caja_id },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Éxito', response.message || 'Caja cerrada correctamente.', 'success');
                        if (typeof window.actualizarDashboard === 'function') window.actualizarDashboard();
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo cerrar la caja.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                }
            });
        }
    });
};

window.verDetalleCaja = function(caja_id) {
    $.ajax({
        url: 'ajax/caja_ajax.php',
        method: 'POST',
        dataType: 'json',
        data: { accion: 'detalle_caja', caja_id },
        success: function(response) {
            if (response.success) {
                const data = response.data;
                $('#detalleCajaModalLabel').text(`Detalle de Caja - ${data.nombre || 'ID: ' + caja_id}`);
                
                // Llenar los datos del modal
                $('#detalle_caja_id').text(data.id || caja_id);
                $('#detalle_usuario').text(data.usuario || 'No disponible');
                $('#detalle_sucursal').text(data.sucursal || 'No disponible');
                $('#detalle_fecha_apertura').text(data.fecha_apertura || 'No disponible');
                $('#detalle_saldo_inicial').text(data.saldo_inicial || '$0');
                $('#detalle_saldo_actual').text(data.saldo_actual || '$0');
                $('#detalle_total_ingresos').text(data.total_ingresos || '$0');
                $('#detalle_total_egresos').text(data.total_egresos || '$0');
                $('#detalle_estado').text(data.estado || 'Activa');
                
                // Mostrar el modal
                $('#detalleCajaModal').modal('show');
            } else {
                Swal.fire('Error', response.message || 'No se pudieron obtener los detalles de la caja.', 'error');
            }
        },
        error: function() {
            Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
        }
    });
};

window.cerrarCaja = function() {
    Swal.fire({
        title: '¿Realizar cierre del día?',
        text: 'Esta acción cerrará todas las cajas activas y generará el reporte de cierre.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, cerrar día',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/caja_ajax.php',
                method: 'POST',
                dataType: 'json',
                data: { accion: 'cerrar_dia' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Éxito', response.message || 'Cierre del día realizado correctamente.', 'success');
                        if (typeof window.actualizarDashboard === 'function') window.actualizarDashboard();
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo realizar el cierre del día.', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'No se pudo conectar con el servidor.', 'error');
                }
            });
        }
    });
};

// =====================================================
// INICIALIZACIÓN
// =====================================================

// Función para obtener el contexto del usuario
function obtenerContextoUsuario() {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: "ajax/caja_ajax.php",
            method: "POST",
            data: {
                accion: "obtener_contexto_usuario"
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    resolve(response.data);
                } else {
                    reject(new Error(response.message || 'Error al obtener contexto'));
                }
            },
            error: function(xhr, status, error) {
                console.error('Error obteniendo contexto:', error);
                if (xhr.status === 401 || xhr.responseJSON?.message?.includes('sesión')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Sesión Expirada',
                        text: 'Su sesión ha expirado. Por favor, inicie sesión nuevamente.',
                        showConfirmButton: true,
                        confirmButtonText: 'Iniciar Sesión',
                        allowOutsideClick: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'login.php';
                        }
                    });
                } else {
                    reject(new Error('Error al obtener contexto: ' + error));
                }
            }
        });
    });
}

// Función para inicializar el dashboard
async function inicializarDashboard() {
    try {
        const contexto = await obtenerContextoUsuario();
        
        // Actualizar información del usuario en la interfaz
        $('#usuario-nombre').text(contexto.usuario.nombre);
        $('#usuario-perfil').text(contexto.usuario.perfil);
        if (contexto.usuario.sucursal) {
            $('#usuario-sucursal').text(contexto.usuario.sucursal);
        }

        // Configurar permisos y accesos
        if (contexto.usuario.es_admin || contexto.permisos_caja.puede_ejecutar) {
            $('.btn-caja').show();
            $('.btn-admin').show();
        } else {
            $('.btn-caja').hide();
            $('.btn-admin').hide();
        }

        // Inicializar otros componentes del dashboard
        actualizarEstadisticas();
        inicializarEventos();
        
    } catch (error) {
        console.error('Error inicializando dashboard:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al inicializar el dashboard',
            showConfirmButton: true,
            confirmButtonText: 'Reintentar',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.reload();
            }
        });
    }
}

// Función para actualizar la interfaz con estadísticas
function actualizarInterfazEstadisticas(estadisticas) {
    try {
        console.log('Actualizando interfaz con estadísticas:', estadisticas);
        
        // Actualizar las tarjetas del dashboard
        if (estadisticas.cajas_abiertas !== undefined) {
            $('.card-dashboard .info-box-number, .small-box h3').each(function() {
                const $this = $(this);
                const parent = $this.closest('.small-box, .info-box');
                
                // Buscar por clases o texto para identificar cada tarjeta
                if (parent.find('.small-box-footer, .info-box-text').text().includes('Estado de Caja') || 
                    parent.hasClass('bg-info') || 
                    $this.text().includes('$')) {
                    
                    // Esta es la tarjeta de estado de caja
                    if (estadisticas.cajas_abiertas > 0) {
                        $this.text('$' + parseFloat(estadisticas.saldo_total || 0).toLocaleString('es-CO', {minimumFractionDigits: 2}));
                        parent.find('.progress-description, small').text('CON CAJA');
                    } else {
                        $this.text('$0.00');
                        parent.find('.progress-description, small').text('SIN CAJA');
                    }
                }
            });
        }
        
        // Actualizar información adicional si existe
        $('#cajas_abiertas_count').text(estadisticas.cajas_abiertas || 0);
        $('#cajas_cerradas_count').text(estadisticas.cajas_cerradas || 0);
        $('#saldo_total_display').text('$' + parseFloat(estadisticas.saldo_total || 0).toLocaleString('es-CO', {minimumFractionDigits: 2}));
        
    } catch (error) {
        console.error('Error actualizando interfaz de estadísticas:', error);
    }
}

// Función para actualizar estadísticas
function actualizarEstadisticas() {
    $.ajax({
        url: "ajax/caja_ajax.php",
        method: "POST",
        data: {
            accion: "obtener_dashboard_caja"
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                actualizarInterfazEstadisticas(response.data);
            }
        },
        error: function(error) {
            console.error('Error actualizando estadísticas:', error);
        }
    });
}

// Función para inicializar eventos
function inicializarEventos() {
    // Evento para actualizar automáticamente
    setInterval(actualizarEstadisticas, 60000); // Actualizar cada minuto

    // Otros eventos del dashboard
    $('.btn-refresh').on('click', actualizarEstadisticas);
}

// =================================================================================================
// INICIALIZACIÓN Y MANEJO DE EVENTOS CENTRALIZADO
// =================================================================================================
(function($) {
    // Formatear moneda: redefinir solo si no está ya en global_functions.js
    if (typeof window.formatearMoneda === 'undefined') {
        window.formatearMoneda = function(valor) {
            return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(valor);
        };
    }

    // Las funciones de acción (abrirCaja, cerrarCaja, etc.) ya están definidas como proxies en global_functions.js.
    // Aquí nos aseguramos de que los listeners de jQuery llamen a las funciones globales.

    // Manejo de eventos centralizado usando delegación de eventos
    // Esto asegura que los botones funcionen incluso si se cargan dinámicamente.
    $(document).on('click', '#btn-abrir-caja', function() {
        window.abrirCaja();
    });
    $(document).on('click', '#btn-cerrar-caja', function() {
        window.cerrarCaja();
    });
    $(document).on('click', '#btn-realizar-conteo', function() {
        window.realizarConteoFisico();
    });
    $(document).on('click', '#btn-generar-reporte', function() {
        window.generarReporte();
    });

    // Inicializar el dashboard cuando la página se carga
    $(document).ready(function() {
        // La inicialización ahora se hace a través de la función global
        if (typeof inicializarDashboard === 'function') {
            inicializarDashboard();
        } else {
            console.error("Error: inicializarDashboard no está definida.");
        }
    });

})(jQuery);

})(); 