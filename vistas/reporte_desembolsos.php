<?php
// Estas inclusiones no son necesarias cuando se carga como vista parcial
// require_once "modulos/header.php";
// require_once "modulos/navbar.php";
// require_once "modulos/aside.php";
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">📊 Reporte de Desembolsos</h1>
                    <p class="text-muted">Control y seguimiento de préstamos desembolsados</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                        <li class="breadcrumb-item active">Desembolsos</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            
            <!-- Tarjetas de Resumen -->
            <div class="row mb-3" id="tarjetas_resumen" style="display: none;">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="total_desembolsos">0</h3>
                            <p>Total Desembolsos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="monto_total">C$ 0</h3>
                            <p>Monto Total</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 id="promedio_desembolso">C$ 0</h3>
                            <p>Promedio por Préstamo</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="clientes_unicos">0</h3>
                            <p>Clientes Únicos</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtros Mejorados -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filtros de Consulta
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar-alt text-primary"></i> Fecha Inicio:</label>
                                        <input type="date" class="form-control" id="fechaInicio" value="<?php echo date('Y-m-01'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><i class="fas fa-calendar-check text-primary"></i> Fecha Fin:</label>
                                        <input type="date" class="form-control" id="fechaFin" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><i class="fas fa-clock text-primary"></i> Rango Rápido:</label>
                                        <select class="form-control" id="rangoRapido">
                                            <option value="">Seleccionar período...</option>
                                            <option value="hoy">📅 Hoy</option>
                                            <option value="ayer">📅 Ayer</option>
                                            <option value="semana">📊 Esta semana</option>
                                            <option value="mes">📊 Este mes</option>
                                            <option value="mes_anterior">📊 Mes anterior</option>
                                            <option value="trimestre">📊 Este trimestre</option>
                                            <option value="ano">📊 Este año</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="button" class="btn btn-primary btn-block" id="btnFiltrarDesembolsos">
                                            <i class="fas fa-search"></i> Consultar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Resultados -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">📋 Resultados de Desembolsos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" id="exportarExcelDesembolsos">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="exportarPdfDesembolsos">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="imprimirDesembolsos">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl_desembolsos" class="table table-striped table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Número Préstamo</th>
                                            <th>Cliente</th>
                                            <th>Monto Desembolsado</th>
                                            <th>Fecha Desembolso</th>
                                            <th>Fecha Registro Préstamo</th>
                                            <th>Estado Aprobación</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "modulos/footer.php"; ?>

<script>
$(document).ready(function() {
    var tablaDesembolsos;
    
    console.log('📊 Inicializando Reporte de Desembolsos...');
    
    // Inicializar tabla
    inicializarTabla();
    
    // Configurar eventos
    configurarEventos();
    
    // Cargar datos iniciales del mes actual
    cargarDesembolsos();
});

// Inicializar DataTable
function inicializarTabla() {
    tablaDesembolsos = $('#tbl_desembolsos').DataTable({
        responsive: true,
        processing: true,
        language: {
            url: "vistas/assets/plugins/datatables/i18n/Spanish.json"
        },
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm'
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-info btn-sm'
            }
        ],
        columns: [
            { data: 'nro_prestamo', title: 'N° Préstamo' },
            { data: 'cliente_nombres', title: 'Cliente' },
            { 
                data: 'pres_monto', 
                title: 'Monto',
                render: function(data) {
                    return 'C$ ' + parseFloat(data || 0).toLocaleString('es-NI', {minimumFractionDigits: 2});
                }
            },
            { data: 'fecha_desembolso', title: 'F. Desembolso' },
            { data: 'fecha_registro', title: 'F. Registro' },
            { 
                data: 'pres_estado', 
                title: 'Estado',
                render: function(data) {
                    if (data === 'VIGENTE') return '<span class="badge badge-success">Vigente</span>';
                    if (data === 'CANCELADO') return '<span class="badge badge-info">Cancelado</span>';
                    if (data === 'PENDIENTE') return '<span class="badge badge-warning">Pendiente</span>';
                    return '<span class="badge badge-secondary">' + data + '</span>';
                }
            }
        ]
    });
}

// Configurar eventos
function configurarEventos() {
    // Rango rápido
    $('#rangoRapido').on('change', function() {
        var rango = $(this).val();
        if (rango) {
            var fechas = calcularRangoFechas(rango);
            $('#fechaInicio').val(fechas.inicio);
            $('#fechaFin').val(fechas.fin);
        }
    });
    
    // Botón filtrar
    $('#btnFiltrarDesembolsos').on('click', function() {
        cargarDesembolsos();
    });
    
    // Exportaciones
    $('#exportarExcelDesembolsos').on('click', function() {
        tablaDesembolsos.button('.buttons-excel').trigger();
    });
    
    $('#exportarPdfDesembolsos').on('click', function() {
        tablaDesembolsos.button('.buttons-pdf').trigger();
    });
    
    $('#imprimirDesembolsos').on('click', function() {
        tablaDesembolsos.button('.buttons-print').trigger();
    });
}

// Calcular rangos de fechas
function calcularRangoFechas(rango) {
    var hoy = new Date();
    var inicio, fin;
    
    switch(rango) {
        case 'hoy':
            inicio = fin = hoy.toISOString().split('T')[0];
            break;
        case 'ayer':
            var ayer = new Date(hoy);
            ayer.setDate(hoy.getDate() - 1);
            inicio = fin = ayer.toISOString().split('T')[0];
            break;
        case 'semana':
            var inicioSemana = new Date(hoy);
            inicioSemana.setDate(hoy.getDate() - hoy.getDay());
            inicio = inicioSemana.toISOString().split('T')[0];
            fin = hoy.toISOString().split('T')[0];
            break;
        case 'mes':
            inicio = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0') + '-01';
            fin = hoy.toISOString().split('T')[0];
            break;
        case 'mes_anterior':
            var mesAnterior = new Date(hoy);
            mesAnterior.setMonth(hoy.getMonth() - 1);
            inicio = mesAnterior.getFullYear() + '-' + String(mesAnterior.getMonth() + 1).padStart(2, '0') + '-01';
            var ultimoDia = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
            fin = ultimoDia.toISOString().split('T')[0];
            break;
        case 'trimestre':
            var mesActual = hoy.getMonth();
            var inicioTrimestre = Math.floor(mesActual / 3) * 3;
            inicio = hoy.getFullYear() + '-' + String(inicioTrimestre + 1).padStart(2, '0') + '-01';
            fin = hoy.toISOString().split('T')[0];
            break;
        case 'ano':
            inicio = hoy.getFullYear() + '-01-01';
            fin = hoy.toISOString().split('T')[0];
            break;
        default:
            inicio = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0') + '-01';
            fin = hoy.toISOString().split('T')[0];
    }
    
    return { inicio: inicio, fin: fin };
}

// Cargar datos de desembolsos
function cargarDesembolsos() {
    var fechaInicio = $('#fechaInicio').val();
    var fechaFin = $('#fechaFin').val();
    
    if (!fechaInicio || !fechaFin) {
        Swal.fire('Error', 'Por favor seleccione ambas fechas.', 'warning');
        return;
    }
    
    if (fechaInicio > fechaFin) {
        Swal.fire('Error', 'La fecha de inicio no puede ser mayor a la fecha fin.', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Consultando...',
        text: 'Obteniendo datos de desembolsos.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
    
    $.ajax({
        url: 'ajax/reportes_ajax.php',
        type: 'POST',
        data: {
            accion: 'reporte_desembolsos',
            fecha_inicio: fechaInicio,
            fecha_fin: fechaFin
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && Array.isArray(respuesta)) {
                // Cargar datos en tabla
                tablaDesembolsos.clear().rows.add(respuesta).draw();
                
                // Actualizar tarjetas de resumen
                actualizarResumen(respuesta);
                
                // Mostrar tarjetas
                $('#tarjetas_resumen').show();
                
                if (respuesta.length > 0) {
                    Swal.fire('Éxito', `Se encontraron ${respuesta.length} registros de desembolsos.`, 'success');
                } else {
                    Swal.fire('Sin resultados', 'No se encontraron desembolsos en el período seleccionado.', 'info');
                }
            } else {
                Swal.fire('Error', 'No se pudieron obtener los datos.', 'error');
            }
        },
        error: function(xhr, status, error) {
            Swal.close();
            console.error('Error al cargar desembolsos:', error);
            Swal.fire('Error', 'Error al consultar los datos. Intente nuevamente.', 'error');
        }
    });
}

// Actualizar tarjetas de resumen
function actualizarResumen(datos) {
    if (!datos || datos.length === 0) {
        $('#total_desembolsos').text('0');
        $('#monto_total').text('C$ 0');
        $('#promedio_desembolso').text('C$ 0');
        $('#clientes_unicos').text('0');
        return;
    }
    
    var totalDesembolsos = datos.length;
    var montoTotal = datos.reduce((sum, item) => sum + parseFloat(item.pres_monto || 0), 0);
    var promedioDesembolso = montoTotal / totalDesembolsos;
    var clientesUnicos = new Set(datos.map(item => item.cliente_nombres)).size;
    
    $('#total_desembolsos').text(totalDesembolsos.toLocaleString());
    $('#monto_total').text('C$ ' + montoTotal.toLocaleString('es-NI', {minimumFractionDigits: 2}));
    $('#promedio_desembolso').text('C$ ' + promedioDesembolso.toLocaleString('es-NI', {minimumFractionDigits: 2}));
    $('#clientes_unicos').text(clientesUnicos.toLocaleString());
}
</script> 