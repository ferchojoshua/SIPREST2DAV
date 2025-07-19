<?php
// Verificar si la sesión ya está activa antes de iniciarla
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["usuario"])) {
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">📊 Reportes Financieros</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes Financieros</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        
        <!-- Filtros Principales -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros Generales</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Inicial:</label>
                                    <input type="date" id="fecha_inicial" class="form-control" value="<?php echo date('Y-m-01'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha Final:</label>
                                    <input type="date" id="fecha_final" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sucursal:</label>
                                    <select id="filtro_sucursal" class="form-control select2">
                                        <option value="">Todas las sucursales</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ruta:</label>
                                    <select id="filtro_ruta" class="form-control select2" disabled>
                                        <option value="">Seleccione sucursal primero</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Reportes -->
        <div class="row reports-grid">
            
            <!-- Reporte de Mora -->
            <div class="col-lg-6 col-md-12">
                <div class="card card-warning card-outline card-compact">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Reportes de Mora</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-warning btn-sm btn-block" onclick="generarReporte('clientes_mora')"><i class="fas fa-users"></i> Clientes en Mora</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-warning btn-sm btn-block" onclick="generarReporte('mora_por_colector')"><i class="fas fa-user-tie"></i> Mora por Colector</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-warning btn-sm btn-block" onclick="generarReporte('mora_por_ruta')"><i class="fas fa-route"></i> Mora por Ruta</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-warning btn-sm btn-block" onclick="generarReporte('mora_por_sucursal')"><i class="fas fa-building"></i> Mora por Sucursal</button></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes de Cobranza -->
            <div class="col-lg-6 col-md-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-money-bill-wave"></i> Reportes de Cobranza</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-success btn-sm btn-block" onclick="generarReporte('pagos_del_dia')"><i class="fas fa-calendar-day"></i> Pagos del Día</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-success btn-sm btn-block" onclick="generarReporte('pendientes_del_dia')"><i class="fas fa-clock"></i> Pendientes del Día</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-success btn-sm btn-block" onclick="generarReporte('cobranza_por_colector')"><i class="fas fa-user-check"></i> Cobranza por Colector</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-success btn-sm btn-block" onclick="generarReporte('cobranza_por_ruta')"><i class="fas fa-map-marked-alt"></i> Cobranza por Ruta</button></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reportes de Préstamos -->
            <div class="col-lg-6 col-md-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-handshake"></i> Reportes de Préstamos</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-info btn-sm btn-block" onclick="generarReporte('prestamos_por_cliente')"><i class="fas fa-user"></i> Préstamos por Cliente</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-info btn-sm btn-block" onclick="generarReporte('prestamos_activos')"><i class="fas fa-chart-line"></i> Préstamos Activos</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-info btn-sm btn-block" onclick="generarReporte('prestamos_finalizados')"><i class="fas fa-check-circle"></i> Préstamos Finalizados</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-info btn-sm btn-block" onclick="generarReporte('prestamos_por_sucursal')"><i class="fas fa-building"></i> Préstamos por Sucursal</button></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estados de Cuenta -->
            <div class="col-lg-6 col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-file-invoice-dollar"></i> Estados de Cuenta</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-primary btn-sm btn-block" onclick="abrirModalEstadoCuenta()"><i class="fas fa-search"></i> Estado de Cuenta</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-primary btn-sm btn-block" onclick="generarReporte('saldos_pendientes')"><i class="fas fa-balance-scale"></i> Saldos Pendientes</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-primary btn-sm btn-block" onclick="generarReporte('historial_pagos')"><i class="fas fa-history"></i> Historial de Pagos</button></div>
                            <div class="col-md-6 mb-2"><button class="btn btn-outline-primary btn-sm btn-block" onclick="generarReporte('resumen_cartera')"><i class="fas fa-chart-pie"></i> Resumen de Cartera</button></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Área de Resultados -->
        <div class="row" id="area_resultados" style="display: none;">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title" id="titulo_reporte"><i class="fas fa-table"></i> Resultados del Reporte</h3>
                        <div class="card-tools">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-success btn-xs" onclick="abrirModalCorreo()" title="Enviar por correo" data-toggle="tooltip">
                                    <i class="fas fa-envelope"></i>
                                </button>
                                <button type="button" class="btn btn-outline-success btn-xs" onclick="exportarExcel()" title="Exportar a Excel" data-toggle="tooltip">
                                    <i class="fas fa-file-excel"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-xs" onclick="exportarPDF()" title="Exportar a PDF" data-toggle="tooltip">
                                    <i class="fas fa-file-pdf"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-xs" onclick="imprimirReporte()" title="Imprimir" data-toggle="tooltip">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button type="button" class="btn btn-outline-info btn-xs" onclick="mostrarResumenReporte()" title="Ver resumen" data-toggle="tooltip">
                                    <i class="fas fa-chart-bar"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_reporte" class="table table-striped table-bordered table-hover" style="width: 100%;">
                                <thead class="bg-primary text-white">
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Estado de Cuenta por Cliente -->
        <div class="modal fade" id="modal_estado_cuenta" tabindex="-1" role="dialog" aria-labelledby="modalEstadoCuentaLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalEstadoCuentaLabel">
                            <i class="fas fa-file-alt"></i> Estado de Cuenta por Cliente
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="select_cliente_ec">Seleccionar Cliente:</label>
                            <select id="select_cliente_ec" class="form-control select2" style="width: 100%;" aria-describedby="cliente-ec-help">
                                <option value="">Busque y seleccione un cliente...</option>
                            </select>
                            <small id="cliente-ec-help" class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Escriba el nombre o documento del cliente para buscar su estado de cuenta.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="generarEstadoCuentaCliente()">
                            <i class="fas fa-file-alt"></i> Generar Estado de Cuenta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Enviar Correo -->
        <div class="modal fade" id="modal_enviar_correo" tabindex="-1" role="dialog" aria-labelledby="modalCorreoLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCorreoLabel"><i class="fas fa-envelope"></i> Enviar Reporte por Correo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="form_enviar_correo">
                            <div class="form-group">
                                <label for="select_grupo_correo">Seleccionar Grupo de Destinatarios:</label>
                                <select id="select_grupo_correo" class="form-control select2" style="width: 100%;" required>
                                    <option value="">Seleccione un grupo...</option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar un grupo de destinatarios.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="asunto_correo">Asunto del Correo:</label>
                                <input type="text" id="asunto_correo" class="form-control" 
                                       placeholder="Ej: Reporte de Clientes en Mora" 
                                       required maxlength="100">
                                <div class="invalid-feedback">
                                    El asunto es requerido y no puede exceder 100 caracteres.
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="mensaje_correo">Mensaje (Opcional):</label>
                                <textarea id="mensaje_correo" class="form-control" rows="3" 
                                          placeholder="Mensaje adicional para incluir en el correo..."
                                          maxlength="500"></textarea>
                                <small class="form-text text-muted">Máximo 500 caracteres.</small>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-success" onclick="enviarReportePorCorreo()">
                            <i class="fas fa-paper-plane"></i> Enviar Correo
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Seleccionar Cliente -->
        <div class="modal fade" id="modal_seleccionar_cliente" tabindex="-1" role="dialog" aria-labelledby="modalClienteLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalClienteLabel"><i class="fas fa-user"></i> Seleccionar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="select_cliente">Buscar Cliente:</label>
                            <select id="select_cliente" class="form-control select2" style="width: 100%;" aria-describedby="cliente-help">
                                <option value="">Busque y seleccione un cliente...</option>
                            </select>
                            <small id="cliente-help" class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Escriba el nombre o documento del cliente para buscarlo.
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="button" class="btn btn-primary" onclick="generarReporteCliente()">
                            <i class="fas fa-chart-bar"></i> Generar Reporte
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Crear namespace para evitar redeclaraciones
window.ReportesFinancieros = window.ReportesFinancieros || {};

// Inicializar variables solo si no existen
if (!window.ReportesFinancieros.initialized) {
    window.ReportesFinancieros.reporteActual = null;
    window.ReportesFinancieros.datosReporte = [];
    window.ReportesFinancieros.columnasReporte = [];
    window.ReportesFinancieros.tablaReporte = null;
    window.ReportesFinancieros.initialized = true;
}

// Aliases para compatibilidad
var reporteActual = window.ReportesFinancieros.reporteActual;
var datosReporte = window.ReportesFinancieros.datosReporte;
var columnasReporte = window.ReportesFinancieros.columnasReporte;
var tablaReporte = window.ReportesFinancieros.tablaReporte;

$(document).ready(function() {
    // Evitar re-inicialización
    if (window.ReportesFinancieros.domReady) {
        return;
    }
    window.ReportesFinancieros.domReady = true;

    // Inicializar Select2
    $('.select2').select2();

    // Cargar combos iniciales
    cargarSucursales();

    // Lógica para cargar rutas cuando cambia la sucursal
    $('#filtro_sucursal').on('change', function() {
        var idSucursal = $(this).val();
        if (idSucursal) {
            cargarRutas(idSucursal);
            $('#filtro_ruta').prop('disabled', false);
        } else {
            $('#filtro_ruta').empty().append('<option value="">Todas las rutas</option>').prop('disabled', true);
        }
    });
});

function getTituloReporte(tipoReporte) {
    const titulos = {
        'clientes_mora': 'Clientes en Mora',
        'mora_por_colector': 'Mora por Colector',
        'mora_por_ruta': 'Mora por Ruta',
        'mora_por_sucursal': 'Mora por Sucursal',
        'pagos_del_dia': 'Pagos del Día',
        'pendientes_del_dia': 'Pendientes del Día',
        'cobranza_por_colector': 'Cobranza por Colector',
        'cobranza_por_ruta': 'Cobranza por Ruta',
        'prestamos_por_cliente': 'Préstamos por Cliente',
        'prestamos_activos': 'Préstamos Activos',
        'prestamos_finalizados': 'Préstamos Finalizados',
        'prestamos_por_sucursal': 'Préstamos por Sucursal',
        'saldos_pendientes': 'Saldos Pendientes',
        'historial_pagos': 'Historial de Pagos',
        'resumen_cartera': 'Resumen de Cartera',
        'estado_cuenta_cliente': 'Estado de Cuenta del Cliente'
    };
    return titulos[tipoReporte] || 'Reporte Personalizado';
}

function cargarSucursales() {
    $.ajax({
        url: 'ajax/reportes_financieros_ajax.php',
        method: 'POST',
        data: { accion: 'obtener_sucursales' },
        dataType: 'json',
        success: function(respuesta) {
            var select = $('#filtro_sucursal');
            select.find('option:gt(0)').remove(); // Limpiar opciones viejas
            respuesta.forEach(function(sucursal) {
                select.append(new Option(sucursal.sucursal_nombre, sucursal.sucursal_id));
            });
        },
        error: function() {
            console.error('Error al cargar las sucursales.');
            Swal.fire('Error', 'No se pudieron cargar las sucursales', 'error');
        }
    });
}

function cargarRutas(idSucursal) {
    $.ajax({
        url: 'ajax/reportes_financieros_ajax.php',
        method: 'POST',
        data: { accion: 'obtener_rutas', sucursal_id: idSucursal },
        dataType: 'json',
        success: function(respuesta) {
            var select = $('#filtro_ruta');
            select.empty().append('<option value="">Todas las rutas</option>');
            respuesta.forEach(function(ruta) {
                select.append(new Option(ruta.ruta_nombre, ruta.ruta_id));
            });
        },
        error: function() {
            console.error('Error al cargar las rutas.');
            Swal.fire('Error', 'No se pudieron cargar las rutas', 'error');
        }
    });
}

function validarFiltrosReporte() {
    var fechaInicial = $('#fecha_inicial').val();
    var fechaFinal = $('#fecha_final').val();
    
    // Validar fechas cuando son requeridas
    if (fechaInicial && fechaFinal) {
        if (new Date(fechaInicial) > new Date(fechaFinal)) {
            Swal.fire('Atención', 'La fecha inicial no puede ser mayor que la fecha final.', 'warning');
            return false;
        }
    }
    
    return true;
}

function generarReporte(tipoReporte) {
    reporteActual = tipoReporte;
    
    // Validar filtros básicos
    if (!validarFiltrosReporte()) {
        return;
    }
    
    // Reportes que requieren selección de cliente
    var reportesConCliente = ['prestamos_por_cliente', 'estado_cuenta_cliente'];
    
    if (reportesConCliente.includes(tipoReporte)) {
        // Mostrar modal apropiado según el tipo de reporte
        if (tipoReporte === 'estado_cuenta_cliente') {
            $('#modal_estado_cuenta').modal('show');
            inicializarBusquedaClientesEC();
        } else {
            $('#modal_seleccionar_cliente').modal('show');
            inicializarBusquedaClientes();
        }
        return;
    }
    
    // Reportes normales que usan filtros de fecha y sucursal/ruta
    var fechaInicial = $('#fecha_inicial').val();
    var fechaFinal = $('#fecha_final').val();
    var idSucursal = $('#filtro_sucursal').val();
    var idRuta = $('#filtro_ruta').val();

    ejecutarGeneracionReporte(tipoReporte, {
        fecha_inicio: fechaInicial,
        fecha_fin: fechaFinal,
        sucursal_id: idSucursal,
        ruta_id: idRuta
    });
}

function inicializarBusquedaClientes() {
    // Destruir select2 existente si hay uno
    if ($('#select_cliente').hasClass('select2-hidden-accessible')) {
        $('#select_cliente').select2('destroy');
    }
    
    $('#select_cliente').select2({
        placeholder: 'Busque y seleccione un cliente...',
        allowClear: true,
        minimumInputLength: 2,
        language: {
            inputTooShort: function () {
                return "Por favor ingrese al menos 2 caracteres";
            },
            noResults: function () {
                return "No se encontraron clientes";
            },
            searching: function () {
                return "Buscando...";
            }
        },
        ajax: {
            url: 'ajax/reportes_financieros_ajax.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'buscar_clientes',
                    busqueda: params.term
                };
            },
            processResults: function (data) {
                if (!data || data.error) {
                    console.error('Error en búsqueda de clientes:', data);
                    return { results: [] };
                }
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function inicializarBusquedaClientesEC() {
    // Destruir select2 existente si hay uno
    if ($('#select_cliente_ec').hasClass('select2-hidden-accessible')) {
        $('#select_cliente_ec').select2('destroy');
    }
    
    $('#select_cliente_ec').select2({
        placeholder: 'Busque y seleccione un cliente...',
        allowClear: true,
        minimumInputLength: 2,
        language: {
            inputTooShort: function () {
                return "Por favor ingrese al menos 2 caracteres";
            },
            noResults: function () {
                return "No se encontraron clientes";
            },
            searching: function () {
                return "Buscando...";
            }
        },
        ajax: {
            url: 'ajax/reportes_financieros_ajax.php',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'buscar_clientes',
                    busqueda: params.term
                };
            },
            processResults: function (data) {
                if (!data || data.error) {
                    console.error('Error en búsqueda de clientes:', data);
                    return { results: [] };
                }
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function generarReporteCliente() {
    var clienteId = $('#select_cliente').val();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente para generar el reporte.', 'warning');
        return;
    }
    
    $('#modal_seleccionar_cliente').modal('hide');
    
    // Incluir también los filtros de sucursal y ruta para reportes por cliente
    var idSucursal = $('#filtro_sucursal').val();
    var idRuta = $('#filtro_ruta').val();
    
    ejecutarGeneracionReporte(reporteActual, {
        cliente_id: clienteId,
        sucursal_id: idSucursal,
        ruta_id: idRuta
    });
}

function ejecutarGeneracionReporte(tipoReporte, parametros) {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Generando reporte...',
        text: 'Por favor espere mientras se procesan los datos.',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: 'ajax/reportes_financieros_ajax.php',
        method: 'POST',
        data: {
            accion: 'generar_reporte',
            tipo_reporte: tipoReporte,
            ...parametros
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta.error) {
                console.error('Error del servidor:', respuesta.error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error del servidor',
                    text: respuesta.error,
                    footer: respuesta.tipo_error ? `Código: ${respuesta.tipo_error}` : ''
                });
                return;
            }

            // Verificar si la respuesta tiene la nueva estructura con totales
            var datos = respuesta.data || respuesta;
            var totales = respuesta.totales || {};
            var empresa = respuesta.empresa || {};
            var resumen = respuesta.resumen || {};

            if (!Array.isArray(datos) || datos.length === 0) {
                Swal.fire({
                    icon: 'info',
                    title: 'Sin resultados',
                    text: 'No se encontraron datos para los filtros seleccionados.',
                    confirmButtonText: 'Entendido'
                });
                return;
            }

            // Configurar variables globales
            reporteActual = tipoReporte;
            datosReporte = datos;
            
            // Configurar columnas basándose en las claves del primer registro
            var primerRegistro = datos[0];
            columnasReporte = Object.keys(primerRegistro).map(key => ({
                data: key,
                title: key.charAt(0).toUpperCase() + key.slice(1).replace(/_/g, ' ')
            }));

            // Configurar título del reporte
            var tituloReporte = getTituloReporte(tipoReporte);
            $('#titulo_reporte').html('<i class="fas fa-table"></i> ' + tituloReporte);

            // Mostrar información de la empresa si está disponible
            if (empresa.nombre) {
                $('#titulo_reporte').append(
                    '<br><small class="text-muted">' + empresa.nombre + '</small>'
                );
            }

            // Destruir tabla existente si existe
            if (tablaReporte) {
                tablaReporte.destroy();
                tablaReporte = null;
            }

            // Configurar DataTable con botones mejorados
            var configTabla = {
                data: datos,
                columns: columnasReporte,
                responsive: true,
                processing: true,
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json",
                    processing: "Procesando datos...",
                    emptyTable: "No hay datos disponibles"
                },
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                     '<"row"<"col-sm-12"B>>' +
                     '<"row"<"col-sm-12"tr>>' +
                     '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        title: tituloReporte + (empresa.nombre ? ' - ' + empresa.nombre : ''),
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm mr-1',
                        filename: tipoReporte + '_' + new Date().toISOString().split('T')[0],
                        exportOptions: {
                            columns: ':visible'
                        },
                        messageTop: empresa.nombre ? 
                            empresa.nombre + '\n' + 
                            'RUC: ' + empresa.ruc + '\n' + 
                            empresa.direccion + '\n' +
                            'Generado el: ' + new Date().toLocaleString('es-ES') : 
                            null
                    },
                    {
                        extend: 'pdfHtml5',
                        title: tituloReporte,
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm mr-1',
                        filename: tipoReporte + '_' + new Date().toISOString().split('T')[0],
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        },
                        messageTop: empresa.nombre ? 
                            empresa.nombre + ' - RUC: ' + empresa.ruc + '\n' + 
                            empresa.direccion + '\n' +
                            'Generado el: ' + new Date().toLocaleString('es-ES') : 
                            null
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Imprimir',
                        className: 'btn btn-primary btn-sm mr-1',
                        title: tituloReporte + (empresa.nombre ? ' - ' + empresa.nombre : ''),
                        exportOptions: {
                            columns: ':visible'
                        },
                        messageTop: empresa.nombre ? 
                            '<div style="text-align: center; margin-bottom: 20px;">' +
                            '<h3>' + empresa.nombre + '</h3>' +
                            '<p>RUC: ' + empresa.ruc + '<br>' + empresa.direccion + '</p>' +
                            '<p>Generado el: ' + new Date().toLocaleString('es-ES') + '</p>' +
                            '</div>' : 
                            null
                    }
                ],
                pageLength: 15,
                lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, "Todos"]],
                order: [[0, 'desc']],
                footerCallback: function(row, data, start, end, display) {
                    // Crear pie de tabla con totales
                    crearPieTablaConTotales(this.api(), totales);
                }
            };

            // Crear la tabla
            tablaReporte = $('#tabla_reporte').DataTable(configTabla);

            // Mostrar área de resultados
            $('#area_resultados').removeClass('fade-in').addClass('fade-in').show();

            // Mostrar resumen si está disponible
            if (resumen && Object.keys(resumen).length > 0) {
                mostrarResumenEjecutivo(resumen, totales);
            }

            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: 'Reporte generado exitosamente',
                text: `Se encontraron ${datos.length} registros.`,
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });

        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('Error AJAX:', error);
            console.error('Respuesta del servidor:', xhr.responseText);
            
            Swal.fire({
                icon: 'error',
                title: 'Error de comunicación',
                text: 'No se pudo conectar con el servidor. Verifique su conexión e intente nuevamente.',
                footer: `Estado: ${status} | Error: ${error}`
            });
        }
    });
}

function crearPieTablaConTotales(api, totales) {
    if (!totales || Object.keys(totales).length === 0) {
        $(api.table().footer()).hide();
        return;
    }

    $(api.table().footer()).show();
    var footer = $(api.table().footer());
    footer.html(''); // Limpiar pie de página anterior
    
    var $tr = $('<tr>').appendTo(footer);
    
    api.columns().every(function(index) {
        var column = this;
        var columnName = $(column.header()).text().trim();
        var columnDataKey = columnasReporte[index] ? columnasReporte[index].data : '';
        
        var totalValue = totales[columnDataKey];
        
        if (index === 0) {
            $tr.append('<th style="text-align:left;">Totales:</th>');
        } else {
            if (totalValue !== undefined && !isNaN(parseFloat(totalValue))) {
                var formattedTotal = new Intl.NumberFormat('es-NI', {
                    style: 'currency',
                    currency: 'NIO',
                    minimumFractionDigits: 2
                }).format(totalValue);
                $tr.append('<th style="text-align:right;">' + formattedTotal + '</th>');
            } else {
                $tr.append('<th></th>'); // Celda vacía para columnas sin total
            }
        }
    });
}


function mostrarResumenEjecutivo(resumen, totales) {
    if (!resumen || Object.keys(resumen).length === 0) {
        Swal.fire('Sin resumen', 'No hay un resumen ejecutivo disponible para este reporte.', 'info');
        return;
    }

    let htmlContent = '<div class="container-fluid"><div class="row">';

    // Iterar sobre las métricas del resumen
    for (const key in resumen) {
        if (Object.hasOwnProperty.call(resumen, key)) {
            const item = resumen[key];
            const icon = item.icon || 'fas fa-info-circle';
            const color = item.color || 'bg-primary';

            htmlContent += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="info-box shadow ${color}">
                        <span class="info-box-icon"><i class="${icon}"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">${item.label}</span>
                            <span class="info-box-number">${item.value}</span>
                        </div>
                    </div>
                </div>`;
        }
    }

    htmlContent += '</div>';

    // Añadir sección de totales si existen
    if (totales && Object.keys(totales).length > 0) {
        htmlContent += '<hr><h5><i class="fas fa-calculator"></i> Totales Generales</h5><ul class="list-group list-group-flush">';
        for (const key in totales) {
            if (Object.hasOwnProperty.call(totales, key)) {
                const total = totales[key];
                 const formattedTotal = new Intl.NumberFormat('es-NI', {
                    style: 'currency',
                    currency: 'NIO',
                    minimumFractionDigits: 2
                }).format(total);
                
                // Formatear el nombre de la clave para que sea legible
                const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());

                htmlContent += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                    ${label}
                                    <span class="badge badge-success badge-pill">${formattedTotal}</span>
                                </li>`;
            }
        }
        htmlContent += '</ul>';
    }
    
    htmlContent += '</div>';

    Swal.fire({
        title: `<strong>Resumen del Reporte</strong>`,
        html: htmlContent,
        width: '80%',
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: '<i class="fas fa-thumbs-up"></i> ¡Entendido!',
        confirmButtonAriaLabel: 'Thumbs up, great!',
    });
}


function mostrarResultadosReporte(respuesta, tipoReporte) {
    datosReporte = respuesta;
    columnasReporte = Object.keys(datosReporte[0]).map(function(key) {
        return { 
            data: key, 
            title: key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) 
        };
    });

    $('#titulo_reporte').text('Resultados: ' + tipoReporte.replace(/_/g, ' '));
    
    // Calcular totales para columnas numéricas
    var totales = calcularTotalesReporte(datosReporte);
    
    if ($.fn.DataTable.isDataTable('#tabla_reporte')) {
        tablaReporte.destroy();
    }

    tablaReporte = $('#tabla_reporte').DataTable({
        data: datosReporte,
        columns: columnasReporte,
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel"></i> Excel',
                title: 'Reporte_' + tipoReporte
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                title: 'Reporte_' + tipoReporte,
                orientation: 'landscape'
            }
        ],
        footerCallback: function(row, data, start, end, display) {
            var api = this.api();
            
            // Agregar fila de totales si hay columnas numéricas
            if (Object.keys(totales).length > 0) {
                $(api.table().footer()).html(crearFilaTotales(totales, columnasReporte));
            }
        },
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        }
    });

    // Mostrar resumen de totales en una card separada
    if (Object.keys(totales).length > 0) {
        mostrarResumenTotales(totales, tipoReporte);
    }

    $('#area_resultados').show();
}

function calcularTotalesReporte(datos) {
    var totales = {};
    
    if (datos.length === 0) return totales;
    
    // Identificar columnas numéricas
    var columnasNumericas = [];
    Object.keys(datos[0]).forEach(function(columna) {
        var primerValor = datos[0][columna];
        if (!isNaN(parseFloat(primerValor)) && isFinite(primerValor)) {
            columnasNumericas.push(columna);
        }
    });
    
    // Calcular totales
    columnasNumericas.forEach(function(columna) {
        var suma = datos.reduce(function(total, fila) {
            var valor = parseFloat(fila[columna]) || 0;
            return total + valor;
        }, 0);
        totales[columna] = suma;
    });
    
    return totales;
}

function crearFilaTotales(totales, columnas) {
    var filaTotales = '<tr style="background-color: #f8f9fa; font-weight: bold;">';
    
    columnas.forEach(function(columna, index) {
        if (index === 0) {
            filaTotales += '<td>TOTAL GENERAL</td>';
        } else if (totales.hasOwnProperty(columna.data)) {
            var total = totales[columna.data];
            filaTotales += '<td>' + formatearNumero(total) + '</td>';
        } else {
            filaTotales += '<td>-</td>';
        }
    });
    
    filaTotales += '</tr>';
    return filaTotales;
}

function mostrarResumenTotales(totales, tipoReporte) {
    var resumenHtml = '<div class="card mt-3"><div class="card-header"><h5><i class="fas fa-calculator"></i> Resumen General</h5></div><div class="card-body"><div class="row">';
    
    Object.keys(totales).forEach(function(columna) {
        var nombreColumna = columna.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        var valor = formatearNumero(totales[columna]);
        
        resumenHtml += '<div class="col-md-4 mb-2">';
        resumenHtml += '<div class="info-box bg-info">';
        resumenHtml += '<span class="info-box-icon"><i class="fas fa-chart-line"></i></span>';
        resumenHtml += '<div class="info-box-content">';
        resumenHtml += '<span class="info-box-text">' + nombreColumna + '</span>';
        resumenHtml += '<span class="info-box-number">' + valor + '</span>';
        resumenHtml += '</div></div></div>';
    });
    
    resumenHtml += '</div></div></div>';
    
    // Agregar después de la tabla
    $('#area_resultados').append(resumenHtml);
}

function formatearNumero(numero) {
    return new Intl.NumberFormat('es-NI', {
        style: 'decimal',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(numero);
}

function exportarExcel() {
    if (!tablaReporte) {
        Swal.fire('Aviso', 'Primero debe generar un reporte.', 'warning');
        return;
    }
    
    // Usar la funcionalidad nativa de DataTables que ya está configurada
    if (tablaReporte.button && tablaReporte.button('.buttons-excel')) {
        tablaReporte.button('.buttons-excel').trigger();
    } else {
        // Fallback manual si no está disponible
        exportarExcelManual();
    }
}

function exportarExcelManual() {
    try {
        // Obtener datos de la empresa
        $.ajax({
            url: 'ajax/configuracion_ajax.php',
            method: 'POST',
            data: { accion: 1 },
            dataType: 'json',
            success: function(empresa) {
                // Preparar datos para Excel
                var worksheetData = [];
                
                // Agregar encabezado de empresa
                worksheetData.push(['']);
                worksheetData.push([empresa.confi_razon || 'Sistema de Préstamos']);
                worksheetData.push(['RUC: ' + (empresa.confi_ruc || 'No configurado')]);
                worksheetData.push([empresa.confi_direccion || 'Dirección no configurada']);
                worksheetData.push(['Tel: ' + (empresa.config_celular || 'N/A') + ' | Email: ' + (empresa.config_correo || 'N/A')]);
                worksheetData.push(['']);
                worksheetData.push([reporteActual.toUpperCase().replace(/_/g, ' ')]);
                worksheetData.push(['Generado el: ' + new Date().toLocaleString('es-ES')]);
                worksheetData.push(['']);
                
                // Agregar encabezados de columnas
                if (columnasReporte && columnasReporte.length > 0) {
                    var headers = columnasReporte.map(col => col.title || col.data);
                    worksheetData.push(headers);
                    
                    // Agregar datos
                    datosReporte.forEach(function(row) {
                        var rowData = columnasReporte.map(col => {
                            var value = row[col.data] || '';
                            return typeof value === 'number' ? value : String(value);
                        });
                        worksheetData.push(rowData);
                    });
                    
                    // Calcular totales para columnas numéricas
                    var numericColumns = [];
                    var totals = [];
                    
                    columnasReporte.forEach(function(col, index) {
                        var isNumeric = datosReporte.some(row => 
                            typeof row[col.data] === 'number' || 
                            (!isNaN(parseFloat(row[col.data])) && isFinite(row[col.data]))
                        );
                        
                        if (isNumeric) {
                            numericColumns.push(index);
                            var total = datosReporte.reduce((sum, row) => {
                                var value = parseFloat(row[col.data]) || 0;
                                return sum + value;
                            }, 0);
                            totals[index] = total;
                        }
                    });
                    
                    // Agregar fila de totales si hay columnas numéricas
                    if (numericColumns.length > 0) {
                        worksheetData.push(['']); // Fila vacía
                        var totalRow = new Array(columnasReporte.length);
                        totalRow[0] = 'TOTALES';
                        
                        numericColumns.forEach(colIndex => {
                            totalRow[colIndex] = totals[colIndex] || 0;
                        });
                        
                        worksheetData.push(totalRow);
                    }
                }
                
                // Crear y descargar archivo Excel
                var ws = XLSX.utils.aoa_to_sheet(worksheetData);
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Reporte");
                
                // Aplicar estilos básicos
                var range = XLSX.utils.decode_range(ws['!ref']);
                for (var R = range.s.r; R <= range.e.r; ++R) {
                    for (var C = range.s.c; C <= range.e.c; ++C) {
                        var cell_address = XLSX.utils.encode_cell({c: C, r: R});
                        if (!ws[cell_address]) continue;
                        
                        // Estilo para encabezados de empresa
                        if (R >= 1 && R <= 7) {
                            ws[cell_address].s = {
                                font: { bold: true, sz: 12 },
                                alignment: { horizontal: 'center' }
                            };
                        }
                    }
                }
                
                var filename = reporteActual + '_' + new Date().toISOString().split('T')[0] + '.xlsx';
                XLSX.writeFile(wb, filename);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Excel generado',
                    text: 'El archivo se ha descargado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function() {
                // Fallback sin datos de empresa
                var ws = XLSX.utils.json_to_sheet(datosReporte);
                var wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Reporte");
                XLSX.writeFile(wb, reporteActual + ".xlsx");
            }
        });
    } catch (error) {
        console.error('Error al generar Excel:', error);
        Swal.fire('Error', 'No se pudo generar el archivo Excel. Verifique que la librería XLSX esté cargada.', 'error');
    }
}

function exportarPDF() {
    if (!tablaReporte) {
        Swal.fire('Aviso', 'Primero debe generar un reporte.', 'warning');
        return;
    }
    
    // Usar la funcionalidad nativa de DataTables que ya está configurada
    if (tablaReporte.button && tablaReporte.button('.buttons-pdf')) {
        tablaReporte.button('.buttons-pdf').trigger();
    } else {
        // Fallback manual si no está disponible
        exportarPDFManual();
    }
}

function exportarPDFManual() {
    try {
        // Obtener datos de la empresa
        $.ajax({
            url: 'ajax/configuracion_ajax.php',
            method: 'POST',
            data: { accion: 1 },
            dataType: 'json',
            success: function(empresa) {
                if (typeof jsPDF === 'undefined') {
                    Swal.fire('Error', 'La librería jsPDF no está disponible.', 'error');
                    return;
                }
                
                var doc = new jsPDF({
                    orientation: 'landscape',
                    unit: 'mm',
                    format: 'a4'
                });
                
                // Configurar fuentes
                doc.setFont('helvetica');
                
                // Encabezado de empresa
                var yPosition = 20;
                
                // Logo (si está disponible, se puede agregar aquí)
                doc.setFontSize(16);
                doc.setFont('helvetica', 'bold');
                doc.text(empresa.confi_razon || 'Sistema de Préstamos', 150, yPosition, { align: 'center' });
                
                yPosition += 8;
                doc.setFontSize(10);
                doc.setFont('helvetica', 'normal');
                doc.text('RUC: ' + (empresa.confi_ruc || 'No configurado'), 150, yPosition, { align: 'center' });
                
                yPosition += 6;
                doc.text(empresa.confi_direccion || 'Dirección no configurada', 150, yPosition, { align: 'center' });
                
                yPosition += 6;
                doc.text('Tel: ' + (empresa.config_celular || 'N/A') + ' | Email: ' + (empresa.config_correo || 'N/A'), 150, yPosition, { align: 'center' });
                
                yPosition += 15;
                
                // Título del reporte
                doc.setFontSize(14);
                doc.setFont('helvetica', 'bold');
                doc.text(reporteActual.toUpperCase().replace(/_/g, ' '), 150, yPosition, { align: 'center' });
                
                yPosition += 8;
                doc.setFontSize(8);
                doc.setFont('helvetica', 'normal');
                doc.text('Generado el: ' + new Date().toLocaleString('es-ES'), 150, yPosition, { align: 'center' });
                
                yPosition += 15;
                
                // Preparar datos para la tabla
                if (columnasReporte && columnasReporte.length > 0 && datosReporte && datosReporte.length > 0) {
                    var headers = columnasReporte.map(col => col.title || col.data);
                    var data = datosReporte.map(row => 
                        columnasReporte.map(col => {
                            var value = row[col.data] || '';
                            return typeof value === 'number' ? value.toFixed(2) : String(value);
                        })
                    );
                    
                    // Calcular totales
                    var totals = new Array(columnasReporte.length).fill('');
                    totals[0] = 'TOTALES';
                    
                    var hasNumericColumns = false;
                    columnasReporte.forEach(function(col, index) {
                        var isNumeric = datosReporte.some(row => 
                            typeof row[col.data] === 'number' || 
                            (!isNaN(parseFloat(row[col.data])) && isFinite(row[col.data]))
                        );
                        
                        if (isNumeric) {
                            hasNumericColumns = true;
                            var total = datosReporte.reduce((sum, row) => {
                                var value = parseFloat(row[col.data]) || 0;
                                return sum + value;
                            }, 0);
                            totals[index] = total.toFixed(2);
                        }
                    });
                    
                    // Agregar fila de totales si hay columnas numéricas
                    if (hasNumericColumns) {
                        data.push(totals);
                    }
                    
                    // Generar tabla
                    doc.autoTable({
                        head: [headers],
                        body: data,
                        startY: yPosition,
                        styles: {
                            fontSize: 7,
                            cellPadding: 2
                        },
                        headStyles: {
                            fillColor: [52, 152, 219],
                            textColor: 255,
                            fontStyle: 'bold'
                        },
                        footStyles: {
                            fillColor: [44, 62, 80],
                            textColor: 255,
                            fontStyle: 'bold'
                        },
                        alternateRowStyles: {
                            fillColor: [248, 249, 250]
                        },
                        margin: { left: 15, right: 15 }
                    });
                    
                    // Pie de página
                    var finalY = doc.lastAutoTable.finalY + 15;
                    doc.setFontSize(8);
                    doc.setFont('helvetica', 'italic');
                    doc.text('Este reporte fue generado automáticamente por el Sistema de Préstamos', 150, finalY, { align: 'center' });
                    doc.text(empresa.confi_razon + ' - ' + (empresa.config_correo || 'admin@sistema.com'), 150, finalY + 5, { align: 'center' });
                }
                
                // Descargar PDF
                var filename = reporteActual + '_' + new Date().toISOString().split('T')[0] + '.pdf';
                doc.save(filename);
                
                Swal.fire({
                    icon: 'success',
                    title: 'PDF generado',
                    text: 'El archivo se ha descargado correctamente.',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function() {
                // Fallback sin datos de empresa
                if (typeof jsPDF !== 'undefined') {
                    var doc = new jsPDF({ orientation: 'landscape' });
                    doc.autoTable({
                        head: [columnasReporte.map(col => col.title)],
                        body: datosReporte.map(row => columnasReporte.map(col => row[col.data]))
                    });
                    doc.save(reporteActual + '.pdf');
                } else {
                    Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
                }
            }
        });
    } catch (error) {
        console.error('Error al generar PDF:', error);
        Swal.fire('Error', 'No se pudo generar el archivo PDF. Verifique que las librerías estén cargadas.', 'error');
    }
}

function imprimirReporte() {
    if (!tablaReporte) {
        Swal.fire('Aviso', 'Primero debe generar un reporte.', 'warning');
        return;
    }
    tablaReporte.button('.buttons-print').trigger();
}

function abrirModalEstadoCuenta() {
    $('#select_cliente_ec').select2({
        ajax: {
            url: 'ajax/reportes_financieros_ajax.php',
            method: 'POST',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'buscar_clientes',
                    busqueda: params.term
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        placeholder: 'Escriba para buscar un cliente...',
        minimumInputLength: 2
    });
    $('#modal_estado_cuenta').modal('show');
}

function generarEstadoCuentaCliente() {
    var clienteId = $('#select_cliente_ec').val();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente para generar el estado de cuenta.', 'warning');
        return;
    }
    
    $('#modal_estado_cuenta').modal('hide');
    
    ejecutarGeneracionReporte('estado_cuenta_cliente', {
        cliente_id: clienteId
    });
}

function abrirModalCorreo(){
    if (!reporteActual) {
        Swal.fire('Aviso', 'Primero debe generar un reporte para poder enviarlo.', 'warning');
        return;
    }

    // Llenar el asunto automáticamente
    $('#asunto_correo').val('Reporte: ' + reporteActual.replace(/_/g, ' '));
    $('#mensaje_correo').val('');
    
    // Cargar grupos de correo
    $.ajax({
        url: 'ajax/grupos_reportes_ajax.php',
        method: 'POST',
        data: { accion: 'obtener_todos_los_grupos' }, // Necesitaremos crear esta acción
        dataType: 'json',
        success: function(respuesta) {
            var select = $('#select_grupo_correo');
            select.empty();
            if(respuesta && respuesta.length > 0){
                respuesta.forEach(function(grupo) {
                    select.append(new Option(grupo.grupo_nombre, grupo.grupo_id));
                });
            } else {
                select.append('<option value="">No hay grupos creados</option>');
            }
        }
    });

    $('#modal_enviar_correo').modal('show');
}

function enviarReportePorCorreo(){
    var grupoId = $('#select_grupo_correo').val();
    var asunto = $('#asunto_correo').val();
    var mensaje = $('#mensaje_correo').val();

    if(!grupoId){
        Swal.fire('Error', 'Debe seleccionar un grupo de destinatarios.', 'error');
        return;
    }
    if(!asunto){
        Swal.fire('Error', 'El asunto no puede estar vacío.', 'error');
        return;
    }

    Swal.fire({
        title: 'Enviando correo...',
        text: 'Por favor, espere.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Enviar datos al backend
    $.ajax({
        url: 'ajax/reportes_financieros_ajax.php',
        method: 'POST',
        data: {
            accion: 'enviar_reporte_email',
            grupo_id: grupoId,
            asunto: asunto,
            mensaje: mensaje,
            reporte_data: JSON.stringify(datosReporte),
            reporte_columnas: JSON.stringify(columnasReporte.map(c => c.title)),
            reporte_titulo: getTituloReporte(reporteActual)
        },
        dataType: 'json',
        beforeSend: function() {
            Swal.fire({
                title: 'Enviando correo...',
                text: 'Por favor espere...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        },
        success: function(respuesta) {
            Swal.close();
            if(respuesta.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: '¡Enviado!',
                    text: respuesta.message,
                    showConfirmButton: true
                });
                $('#modal_enviar_correo').modal('hide');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: respuesta.message || 'Ocurrió un error al enviar el correo',
                    footer: respuesta.debug_info ? `Detalles: ${respuesta.debug_info}` : '',
                    showConfirmButton: true
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            let errorMessage = 'No se pudo conectar con el servidor.';
            let detailMessage = '';

            if (status === 'parsererror') {
                errorMessage = 'Error en la respuesta del servidor.';
                detailMessage = 'La respuesta no tiene el formato esperado. Por favor, contacte al administrador.';
            } else if (status === 'timeout') {
                errorMessage = 'Tiempo de espera agotado.';
                detailMessage = 'El servidor tardó demasiado en responder. Intente nuevamente.';
            } else if (status === 'error') {
                errorMessage = 'Error de comunicación.';
                detailMessage = 'No se pudo establecer conexión con el servidor.';
            }

            Swal.fire({
                icon: 'error',
                title: errorMessage,
                text: 'Verifique su conexión e intente nuevamente.',
                footer: detailMessage,
                showConfirmButton: true
            });
            
            // Log del error para debugging
            console.error('Error Ajax:', {
                status: status,
                error: error,
                responseText: xhr.responseText
            });
        }
    });
}

// Función para manejar el cierre correcto de modales
function cerrarModales() {
    $('.modal').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
}

// Event listeners para botones de cerrar
$(document).ready(function() {
    // Manejar clicks en botones de cerrar y X
    $('.modal .close, .modal [data-dismiss="modal"]').on('click', function() {
        var modal = $(this).closest('.modal');
        modal.modal('hide');
    });
    
    // Limpiar Select2 cuando se cierre el modal
    $('#modal_seleccionar_cliente').on('hidden.bs.modal', function () {
        if ($('#select_cliente').hasClass('select2-hidden-accessible')) {
            $('#select_cliente').val(null).trigger('change');
        }
    });
    
    $('#modal_estado_cuenta').on('hidden.bs.modal', function () {
        if ($('#select_cliente_ec').hasClass('select2-hidden-accessible')) {
            $('#select_cliente_ec').val(null).trigger('change');
        }
    });
    
    // Validación en tiempo real de fechas
    $('#fecha_inicial, #fecha_final').on('change', function() {
        validarFiltrosReporte();
    });
    
    // Cargar rutas cuando cambie la sucursal
    $('#filtro_sucursal').on('change', function() {
        var sucursalId = $(this).val();
        if (sucursalId) {
            cargarRutas(sucursalId);
        } else {
            $('#filtro_ruta').empty().append('<option value="">Todas las rutas</option>');
        }
    });
});

</script>

<style>
.card {
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,.25);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85em;
    letter-spacing: 0.5px;
}

.badge {
    font-size: 0.8em;
    padding: 0.5em 0.8em;
}

#area_resultados {
    animation: fadeInUp 0.5s ease;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content-header h1 {
    font-weight: 300;
    letter-spacing: 1px;
}

.card-tools .btn {
    font-size: 0.8em;
    padding: 0.25rem 0.5rem;
}

/* Estilo para botones de exportación más pequeños */
.btn-xs {
    padding: 0.25rem 0.4rem !important;
    font-size: 0.75rem !important;
    line-height: 1.2 !important;
    border-radius: 0.2rem !important;
    min-width: 32px !important;
    height: 28px !important;
}

.btn-group-sm .btn-xs {
    margin-right: 2px;
}

.btn-group-sm .btn-xs:last-child {
    margin-right: 0;
}

/* Tarjetas más compactas */
.card-compact {
    margin-bottom: 15px;
}

.card-compact .card-body {
    padding: 12px;
}

.card-compact .card-header {
    padding: 8px 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.card-compact .btn-block {
    padding: 6px 12px;
    font-size: 0.85rem;
    margin-bottom: 8px;
}

/* Mejorar la visualización de las tarjetas de reportes */
.reports-grid .card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    overflow: hidden;
}

.reports-grid .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.reports-grid .card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 10px 15px;
    border-bottom: none;
}

.reports-grid .card-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.reports-grid .card-body {
    padding: 15px;
}

.reports-grid .btn {
    transition: all 0.2s ease;
    border-radius: 6px;
    font-weight: 500;
}

.reports-grid .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

/* Botones de exportación con iconos más visibles */
.card-tools .btn-group .btn i {
    font-size: 12px;
}

.card-tools .btn-group .btn:hover {
    transform: scale(1.05);
    transition: transform 0.1s ease;
}

/* Tooltips personalizados */
.tooltip.show {
    opacity: 1;
}

.tooltip .tooltip-inner {
    background-color: #2c3e50;
    color: white;
    border-radius: 4px;
    padding: 5px 8px;
    font-size: 0.75rem;
}

/* Resumen de reporte */
.resumen-reporte .card {
    margin-bottom: 10px;
    border-radius: 6px;
}

.resumen-reporte .card-body {
    padding: 12px;
}

.resumen-reporte .card-title {
    font-size: 0.9rem;
    margin-bottom: 8px;
}

/* Mejorar la tabla de resultados */
#tabla_reporte {
    font-size: 0.85rem;
}

#tabla_reporte thead th {
    text-align: center; /* Asegurar compatibilidad en todos los navegadores */
}

#tabla_reporte th {
    background-color: #3498db !important;
    color: white !important;
    font-weight: 600;
    padding: 8px;
    text-align: center;
    border: 1px solid #2980b9;
}

#tabla_reporte td {
    padding: 6px 8px;
    border: 1px solid #e3e6f0;
}

#tabla_reporte tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

#tabla_reporte tbody tr:hover {
    background-color: #e3f2fd;
}

#tabla_reporte tfoot th {
    background-color: #2c3e50 !important;
    color: white !important;
    font-weight: bold;
    border: 1px solid #34495e;
}

/* Filtros más compactos */
.filtros-reporte .form-group {
    margin-bottom: 12px;
}

.filtros-reporte .form-control-sm {
    padding: 4px 8px;
    font-size: 0.85rem;
}

.filtros-reporte label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 4px;
}

/* Indicadores de carga mejorados */
.swal2-loader {
    border-color: #3498db transparent #3498db transparent;
}

/* Responsive para dispositivos móviles */
@media (max-width: 768px) {
    .card-compact .card-body {
        padding: 10px;
    }
    
    .btn-xs {
        padding: 0.2rem 0.3rem !important;
        font-size: 0.7rem !important;
        min-width: 28px !important;
        height: 24px !important;
    }
    
    .card-tools .btn-group {
        flex-wrap: wrap;
    }
    
    .card-tools .btn-group .btn {
        margin-bottom: 2px;
    }
    
    #tabla_reporte {
        font-size: 0.75rem;
    }
    
    .reports-grid .col-lg-6 {
        margin-bottom: 10px;
    }
}

/* Animaciones suaves */
.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Swal personalizado para reportes */
.swal-wide {
    width: 90% !important;
    max-width: 1000px !important;
}

.swal2-popup.swal-wide .swal2-html-container {
    overflow-y: auto;
    max-height: 70vh;
}

/* Estilos para alertas de resumen */
.alert-info h5 {
    color: #2c3e50;
    border-bottom: 1px solid #bee5eb;
    padding-bottom: 8px;
    margin-bottom: 15px;
}

/* DataTables customizations */
.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    font-size: 0.85rem;
    padding: 3px 6px;
}

.dataTables_wrapper .dataTables_info {
    font-size: 0.85rem;
    color: #6c757d;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    font-size: 0.85rem;
    padding: 5px 10px;
}

/* Botones de DataTables más compactos */
.dt-buttons .btn {
    padding: 4px 8px !important;
    font-size: 0.8rem !important;
    margin-right: 3px;
}

.dt-buttons .btn i {
    margin-right: 3px;
}

/* Mejoras para Select2 en modales */
.select2-container--default .select2-selection--single {
    height: 35px;
    padding: 5px;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-left: 8px;
    padding-top: 4px;
}

/* Loading overlay mejorado */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.spinner-custom {
    width: 50px;
    height: 50px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 15px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

</script>

<?php
} else {
    echo "Acceso no autorizado.";
}
?>