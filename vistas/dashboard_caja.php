<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-cash-register"></i> Dashboard de Caja</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard Caja</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <!-- Incluir funciones JavaScript del dashboard -->
    <script src="vistas/js/dashboard_caja_funciones.js"></script>
    <div class="container-fluid">

        <!-- Alertas y Notificaciones -->
        <div class="row" id="alertas-container" style="display: none;">
            <div class="col-12">
                <div class="alert alert-dismissible" id="alerta-principal">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Alertas del Sistema</h5>
                    <div id="alertas-content"></div>
                </div>
            </div>
        </div>

        <!-- Verificación de Permisos -->
        <div class="row mb-3" id="permisos-info" style="display: none;">
            <div class="col-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-shield-alt"></i> Estado de Permisos</h5>
                    <div id="permisos-content"></div>
                </div>
            </div>
        </div>

        <!-- KPIs Principales -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="kpi-cajas-abiertas">-</h3>
                        <p>Cajas Abiertas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <a href="#" class="small-box-footer" onclick="mostrarCajasActivas()">
                        Ver detalle <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="kpi-saldo-total">-</h3>
                        <p>Saldo Total Activo</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-coins"></i>
                    </div>
                    <a href="#" class="small-box-footer" onclick="mostrarDetallesSaldo()">
                        Ver detalle <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="kpi-alertas-criticas">-</h3>
                        <p>Alertas Críticas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <a href="#" class="small-box-footer" onclick="mostrarAlertas()">
                        Ver alertas <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-primary">
                    <div class="inner">
                        <h3 id="kpi-operaciones-hoy">-</h3>
                        <p>Operaciones Hoy</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <a href="#" class="small-box-footer" onclick="mostrarAuditoria()">
                        Ver auditoría <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" onclick="actualizarDashboard()">
                                <i class="fas fa-sync-alt" id="refresh-icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <button type="button" class="btn btn-success btn-block" id="btn-abrir-caja" onclick="abrirCaja()">
                                    <i class="fas fa-unlock"></i> Abrir Caja
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-warning btn-block" id="btn-cerrar-caja" onclick="cerrarCaja()">
                                    <i class="fas fa-lock"></i> Cerrar Caja
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-info btn-block" onclick="realizarConteoFisico()">
                                    <i class="fas fa-calculator"></i> Conteo Físico
                                </button>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary btn-block" onclick="generarReporte()">
                                    <i class="fas fa-file-pdf"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cajas Activas y Estadísticas -->
        <div class="row">
            <!-- Cajas Activas -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-cash-register"></i> Cajas Activas</h3>
                        <div class="card-tools">
                            <span class="badge badge-success" id="badge-tiempo-real">En tiempo real</span>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap" id="tabla-cajas-activas">
                            <thead>
                                <tr>
                                    <th>Caja</th>
                                    <th>Usuario</th>
                                    <th>Apertura</th>
                                    <th>Horas Abiertas</th>
                                    <th>Saldo</th>
                                    <th>Alertas</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-cajas-activas">
                                <tr>
                                    <td colspan="7" class="text-center">
                                        <i class="fas fa-spinner fa-spin"></i> Cargando...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Panel de Alertas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bell"></i> Alertas Recientes</h3>
                        <div class="card-tools">
                            <span class="badge badge-warning" id="badge-alertas-count">0</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group" id="lista-alertas" style="max-height: 400px; overflow-y: auto;">
                            <div class="list-group-item text-center">
                                <i class="fas fa-spinner fa-spin"></i> Cargando alertas...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas del Día -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas del Día</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="description-block">
                                    <h5 class="description-header" id="stat-aperturas-hoy">-</h5>
                                    <span class="description-text">Aperturas Hoy</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="description-block">
                                    <h5 class="description-header" id="stat-cierres-hoy">-</h5>
                                    <span class="description-text">Cierres Hoy</span>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-success" id="progress-operaciones" style="width: 0%"></div>
                                </div>
                                <span class="float-left">Eficiencia Operacional</span>
                                <span class="float-right" id="porcentaje-eficiencia">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-clock"></i> Estado del Sistema</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box">
                            <span class="info-box-icon bg-success" id="sistema-estado-icon">
                                <i class="fas fa-check"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Estado del Sistema</span>
                                <span class="info-box-number" id="sistema-estado-text">Operativo</span>
                                <div class="progress">
                                    <div class="progress-bar bg-success" id="sistema-progreso" style="width: 100%"></div>
                                </div>
                                <span class="progress-description" id="sistema-descripcion">
                                    Todas las operaciones funcionando correctamente
                                </span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-clock"></i> Última actualización: 
                                <span id="ultima-actualizacion">-</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Modal para Abrir Caja con Validaciones -->
<div class="modal fade" id="modal-abrir-caja-avanzado" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-unlock"></i> Apertura de Caja Avanzada
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-abrir-caja-avanzado">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descripción de la Caja</label>
                                <input type="text" class="form-control" id="caja-descripcion" 
                                       value="Apertura de Caja" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Monto Inicial</label>
                                <input type="number" class="form-control" id="caja-monto-inicial" 
                                       step="0.01" min="0" required>
                                <small class="text-muted">Límite máximo: <span id="limite-apertura">-</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea class="form-control" id="caja-observaciones" rows="3" 
                                          placeholder="Observaciones adicionales sobre la apertura..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="validacion-fisica-apertura">
                                <label class="form-check-label" for="validacion-fisica-apertura">
                                    He realizado el conteo físico del dinero
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAperturaCaja()">
                    <i class="fas fa-unlock"></i> Abrir Caja
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Conteo Físico -->
<div class="modal fade" id="modal-conteo-fisico" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h4 class="modal-title">
                    <i class="fas fa-calculator"></i> Conteo Físico de Caja
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-conteo-fisico">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Información</h5>
                        Saldo del Sistema: <strong id="saldo-sistema-conteo">-</strong>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tipo de Conteo</label>
                                <select class="form-control" id="tipo-conteo">
                                    <option value="INTERMEDIO">Conteo Intermedio</option>
                                    <option value="CIERRE">Conteo de Cierre</option>
                                    <option value="SUPERVISION">Supervisión</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Saldo Físico Contado</label>
                                <input type="number" class="form-control" id="saldo-fisico" 
                                       step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones del Conteo</label>
                                <textarea class="form-control" id="observaciones-conteo" rows="3" 
                                          placeholder="Detalles del conteo, denominaciones, discrepancias..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="diferencia-conteo" style="display: none;">
                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Diferencia Detectada</h5>
                                <p>Diferencia: <strong id="diferencia-monto">-</strong></p>
                                <p>Se generará una alerta automáticamente.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="confirmarConteoFisico()">
                    <i class="fas fa-save"></i> Registrar Conteo
                </button>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para Dashboard de Caja -->
<script src="vistas/js/dashboard_caja_funciones.js"></script>
<script>
    // Variables globales
    let updateInterval = null;
    let userContext = null;

    $(document).ready(function() {
        // Inicialización del dashboard
        inicializarDashboard();
        configurarActualizacionAutomatica();

        // Inicializar componentes Select2
        // ... existing code ...
    });

    // Función principal de inicialización
    function inicializarDashboard() {
        console.log('Inicializando Dashboard de Caja...');
        
        // Obtener contexto del usuario
        obtenerContextoUsuario();
        
        // Cargar datos del dashboard
        actualizarDashboard();
        
        // Configurar eventos
        configurarEventos();
    }

    // Obtener contexto del usuario (permisos, datos)
    function obtenerContextoUsuario() {
        $.ajax({
            url: 'ajax/caja_ajax.php',
            method: 'POST',
            data: { accion: 16 },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    userContext = response;
                    console.log('Contexto de usuario cargado:', userContext);
                    
                    // Actualizar UI según permisos
                    actualizarUIPermisos();
                    
                    // Mostrar alertas si las hay
                    if (response.alertas_pendientes > 0) {
                        mostrarAlertasEnBanner(response.alertas);
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error('Error obteniendo contexto:', error);
            }
        });
    }

    // Actualizar UI según permisos del usuario
    function actualizarUIPermisos() {
        if (!userContext || !userContext.permisos) return;
        
        const permisos = userContext.permisos;
        
        // Habilitar/deshabilitar botones según permisos
        $('#btn-abrir-caja').prop('disabled', !permisos.puede_abrir);
        $('#btn-cerrar-caja').prop('disabled', !permisos.puede_cerrar);
        
        // Mostrar información de permisos si no es administrador
        if (!permisos.es_administrador) {
            $('#permisos-info').show();
            $('#permisos-content').html(`
                <div class="row">
                    <div class="col-md-6">
                        <strong>Permisos de Caja:</strong><br>
                        <span class="text-${permisos.puede_abrir ? 'success' : 'danger'}">
                            <i class="fas fa-${permisos.puede_abrir ? 'check' : 'times'}"></i> 
                            Abrir Caja
                        </span><br>
                        <span class="text-${permisos.puede_cerrar ? 'success' : 'danger'}">
                            <i class="fas fa-${permisos.puede_cerrar ? 'check' : 'times'}"></i> 
                            Cerrar Caja
                        </span><br>
                        <span class="text-${permisos.puede_supervisar ? 'success' : 'danger'}">
                            <i class="fas fa-${permisos.puede_supervisar ? 'check' : 'times'}"></i> 
                            Supervisar
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Límites:</strong><br>
                        Apertura: ${formatearMoneda(permisos.limite_apertura)}<br>
                        Cierre: ${formatearMoneda(permisos.limite_cerrar)}
                    </div>
                </div>
            `);
        }
    }

    // Mostrar alertas en banner superior
    function mostrarAlertasEnBanner(alertas) {
        if (!alertas || alertas.length === 0) return;
        
        const alertasPrioritarias = alertas.filter(a => 
            ['URGENT', 'CRITICAL'].includes(a.nivel_criticidad)
        );
        
        if (alertasPrioritarias.length > 0) {
            let contenido = '<ul class="mb-0">';
            alertasPrioritarias.forEach(alerta => {
                contenido += `<li><strong>${alerta.titulo}:</strong> ${alerta.mensaje}</li>`;
            });
            contenido += '</ul>';
            
            $('#alertas-content').html(contenido);
            $('#alerta-principal').removeClass().addClass(`alert alert-${
                alertasPrioritarias[0].bootstrap_class
            } alert-dismissible`);
            $('#alertas-container').show();
        }
    }

    // Actualizar datos del dashboard
    function actualizarDashboard() {
        console.log('Actualizando dashboard...');
        
        // Mostrar spinner en el botón de actualizar
        $('#refresh-icon').addClass('fa-spin');
        
        $.ajax({
            url: 'ajax/caja_ajax.php',
            method: 'POST',
            data: { accion: 11 },
            dataType: 'json',
            success: function(response) {
                // dashboardData = response; // Removed as per edit hint
                console.log('Datos del dashboard cargados:', response);
                
                // Actualizar KPIs
                actualizarKPIs(response.estadisticas);
                
                // Actualizar tabla de cajas activas
                actualizarTablaCajasActivas(response.cajas_activas);
                
                // Actualizar alertas
                actualizarListaAlertas(response.alertas);
                
                // Actualizar estadísticas del día
                actualizarEstadisticasDia(response.estadisticas);
                
                // Actualizar timestamp
                $('#ultima-actualizacion').text(new Date().toLocaleString());
                
            },
            error: function(xhr, status, error) {
                console.error('Error actualizando dashboard:', error);
                mostrarNotificacion('Error al actualizar el dashboard', 'error');
            },
            complete: function() {
                $('#refresh-icon').removeClass('fa-spin');
            }
        });
    }

    // Actualizar KPIs principales
    function actualizarKPIs(stats) {
        $('#kpi-cajas-abiertas').text(stats.cajas_abiertas || 0);
        $('#kpi-saldo-total').text(formatearMoneda(stats.saldo_total_activo || 0));
        $('#kpi-alertas-criticas').text(stats.alertas_criticas || 0);
        $('#kpi-operaciones-hoy').text(stats.operaciones_hoy || 0);
        
        // Cambiar colores según valores
        if (stats.alertas_criticas > 0) {
            $('#kpi-alertas-criticas').parent().parent().removeClass('bg-warning').addClass('bg-danger');
        } else {
            $('#kpi-alertas-criticas').parent().parent().removeClass('bg-danger').addClass('bg-warning');
        }
    }

    // Actualizar tabla de cajas activas
    function actualizarTablaCajasActivas(cajas) {
        const tbody = $('#tbody-cajas-activas');
        tbody.empty();
        
        if (!cajas || cajas.length === 0) {
            tbody.html(`
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        <i class="fas fa-inbox"></i> No hay cajas abiertas
                    </td>
                </tr>
            `);
            return;
        }
        
        cajas.forEach(caja => {
            const horasColor = caja.horas_abierta > 12 ? 'text-warning' : 
                              caja.horas_abierta > 24 ? 'text-danger' : 'text-success';
            
            const alertasBadge = caja.alertas_pendientes > 0 ? 
                `<span class="badge badge-warning">${caja.alertas_pendientes}</span>` : 
                '<span class="badge badge-success">0</span>';
            
            tbody.append(`
                <tr>
                    <td>
                        <strong>${caja.caja_descripcion}</strong><br>
                        <small class="text-muted">ID: ${caja.caja_id}</small>
                    </td>
                    <td>
                        ${caja.usuario_apertura_nombre || 'N/A'}<br>
                        <small class="text-muted">Apertura</small>
                    </td>
                    <td>
                        ${caja.caja_f_apertura}<br>
                        <small class="text-muted">${caja.caja_hora_apertura}</small>
                    </td>
                    <td class="${horasColor}">
                        <strong>${caja.horas_abierta || 0}h</strong>
                    </td>
                    <td>
                        <strong>${formatearMoneda(caja.caja_monto_inicial || 0)}</strong>
                    </td>
                    <td>${alertasBadge}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-sm" onclick="verDetalleCaja(${caja.caja_id})" 
                                    title="Ver detalles">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="cerrarCajaEspecifica(${caja.caja_id})"
                                    title="Cerrar caja" ${!userContext?.permisos?.puede_cerrar ? 'disabled' : ''}>
                                <i class="fas fa-lock"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `);
        });
    }

    // Actualizar lista de alertas
    function actualizarListaAlertas(alertas) {
        const lista = $('#lista-alertas');
        lista.empty();
        
        $('#badge-alertas-count').text(alertas?.length || 0);
        
        if (!alertas || alertas.length === 0) {
            lista.html(`
                <div class="list-group-item text-center text-muted">
                    <i class="fas fa-check-circle"></i> No hay alertas pendientes
                </div>
            `);
            return;
        }
        
        alertas.forEach(alerta => {
            const iconoNivel = {
                'URGENT': 'fas fa-exclamation-circle text-danger',
                'CRITICAL': 'fas fa-exclamation-triangle text-warning',
                'WARNING': 'fas fa-info-circle text-info',
                'INFO': 'fas fa-info text-secondary'
            };
            
            lista.append(`
                <div class="list-group-item list-group-item-action" onclick="marcarAlertaLeida(${alerta.alerta_id})">
                    <div class="d-flex w-100 justify-content-between">
                        <h6 class="mb-1">
                            <i class="${iconoNivel[alerta.nivel_criticidad] || iconoNivel['INFO']}"></i>
                            ${alerta.titulo}
                        </h6>
                        <small>${formatearFecha(alerta.fecha_generacion)}</small>
                    </div>
                    <p class="mb-1">${alerta.mensaje}</p>
                    <small>Caja: ${alerta.caja_descripcion}</small>
                </div>
            `);
        });
    }

    // Actualizar estadísticas del día
    function actualizarEstadisticasDia(stats) {
        $('#stat-aperturas-hoy').text(stats.aperturas_hoy || 0);
        $('#stat-cierres-hoy').text(stats.cierres_hoy || 0);
        
        // Calcular eficiencia (cierres vs aperturas)
        const aperturas = stats.aperturas_hoy || 0;
        const cierres = stats.cierres_hoy || 0;
        const eficiencia = aperturas > 0 ? Math.round((cierres / aperturas) * 100) : 100;
        
        $('#progress-operaciones').css('width', eficiencia + '%');
        $('#porcentaje-eficiencia').text(eficiencia + '%');
        
        // Actualizar estado del sistema
        if (stats.alertas_criticas > 5) {
            $('#sistema-estado-icon').removeClass('bg-success').addClass('bg-danger');
            $('#sistema-estado-icon i').removeClass('fa-check').addClass('fa-exclamation-triangle');
            $('#sistema-estado-text').text('Atención Requerida');
            $('#sistema-progreso').removeClass('bg-success').addClass('bg-danger');
            $('#sistema-descripcion').text(`${stats.alertas_criticas} alertas críticas pendientes`);
        } else if (stats.alertas_criticas > 0) {
            $('#sistema-estado-icon').removeClass('bg-success bg-danger').addClass('bg-warning');
            $('#sistema-estado-icon i').removeClass('fa-check fa-exclamation-triangle').addClass('fa-exclamation');
            $('#sistema-estado-text').text('Advertencias');
            $('#sistema-progreso').removeClass('bg-success bg-danger').addClass('bg-warning');
            $('#sistema-descripcion').text(`${stats.alertas_criticas} alertas requieren atención`);
        }
    }

    // Configurar actualización automática
    function configurarActualizacionAutomatica() {
        // Actualizar cada 30 segundos
        updateInterval = setInterval(actualizarDashboard, 30000);
        
        // Pausar cuando la ventana no esté visible
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                if (updateInterval) {
                    clearInterval(updateInterval);
                    updateInterval = null;
                }
            } else {
                if (!updateInterval) {
                    updateInterval = setInterval(actualizarDashboard, 30000);
                    actualizarDashboard(); // Actualizar inmediatamente
                }
            }
        });
    }

    // Funciones de acciones
    function abrirCaja() {
        if (!userContext?.permisos?.puede_abrir) {
            mostrarNotificacion('No tiene permisos para abrir caja', 'error');
            return;
        }
        
        // Establecer límite en el modal
        $('#limite-apertura').text(formatearMoneda(userContext.permisos.limite_apertura));
        $('#caja-monto-inicial').attr('max', userContext.permisos.limite_apertura);
        
        $('#modal-abrir-caja-avanzado').modal('show');
    }

    function confirmarAperturaCaja() {
        const form = $('#form-abrir-caja-avanzado');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        
        const datos = {
            accion: 9,
            caja_descripcion: $('#caja-descripcion').val(),
            caja_monto_inicial: parseFloat($('#caja-monto-inicial').val()),
            validacion_fisica: $('#validacion-fisica-apertura').is(':checked'),
            observaciones: $('#caja-observaciones').val()
        };
        
        $.ajax({
            url: 'ajax/caja_ajax.php',
            method: 'POST',
            data: datos,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    mostrarNotificacion('Caja abierta correctamente', 'success');
                    $('#modal-abrir-caja-avanzado').modal('hide');
                    actualizarDashboard();
                } else {
                    mostrarNotificacion(response.message || 'Error al abrir caja', 'error');
                }
            },
            error: function() {
                mostrarNotificacion('Error de conexión', 'error');
            }
        });
    }

    function realizarConteoFisico() {
        if (!userContext?.cajas_activas || userContext.cajas_activas.length === 0) {
            mostrarNotificacion('No hay cajas abiertas para realizar conteo', 'warning');
            return;
        }
        
        // Calcular saldo del sistema (simplificado)
        const saldoSistema = userContext.cajas_activas[0].caja_monto_inicial || 0;
        $('#saldo-sistema-conteo').text(formatearMoneda(saldoSistema));
        
        $('#modal-conteo-fisico').modal('show');
    }

    function confirmarConteoFisico() {
        const form = $('#form-conteo-fisico');
        if (!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }
        
        const saldoSistema = parseFloat($('#saldo-sistema-conteo').text().replace(/[^\d.-]/g, ''));
        const saldoFisico = parseFloat($('#saldo-fisico').val());
        const diferencia = Math.abs(saldoFisico - saldoSistema);
        
        if (diferencia > 0) {
            $('#diferencia-monto').text(formatearMoneda(diferencia));
            $('#diferencia-conteo').show();
        }
        
        const datos = {
            accion: 14,
            caja_id: userContext.cajas_activas[0].caja_id,
            tipo_conteo: $('#tipo-conteo').val(),
            saldo_sistema: saldoSistema,
            saldo_fisico: saldoFisico,
            observaciones: $('#observaciones-conteo').val()
        };
        
        $.ajax({
            url: 'ajax/caja_ajax.php',
            method: 'POST',
            data: datos,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    mostrarNotificacion('Conteo físico registrado correctamente', 'success');
                    $('#modal-conteo-fisico').modal('hide');
                    actualizarDashboard();
                } else {
                    mostrarNotificacion(response.message || 'Error al registrar conteo', 'error');
                }
            },
            error: function() {
                mostrarNotificacion('Error de conexión', 'error');
            }
        });
    }

    function marcarAlertaLeida(alertaId) {
        $.ajax({
            url: 'ajax/caja_ajax.php',
            method: 'POST',
            data: {
                accion: 13,
                alerta_id: alertaId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    actualizarDashboard();
                }
            }
        });
    }

    // Funciones de utilidad
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
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function mostrarNotificacion(mensaje, tipo = 'info') {
        // Implementar sistema de notificaciones toast
        console.log(`[${tipo.toUpperCase()}] ${mensaje}`);
        
        // Por ahora usar alert simple, luego se puede mejorar con toastr
        if (tipo === 'error') {
            alert('Error: ' + mensaje);
        } else if (tipo === 'success') {
            alert('Éxito: ' + mensaje);
        } else {
            alert(mensaje);
        }
    }

    // Limpiar intervalos al salir
    $(window).on('beforeunload', function() {
        if (updateInterval) {
            clearInterval(updateInterval);
        }
    });
</script>

<style>
.small-box h3 {
    font-size: 2.2rem !important;
}

.description-block {
    text-align: center;
}

.list-group-item {
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    border-left-color: #007bff;
    background-color: #f8f9fa;
}

#badge-tiempo-real {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.7; }
    100% { opacity: 1; }
}

.card-header .card-tools .badge {
    margin-left: 10px;
}

.progress {
    height: 8px;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}
</style> 