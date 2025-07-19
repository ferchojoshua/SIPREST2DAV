<?php
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
                <h1 class="m-0">💵 Reporte Cobranza Diaria</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Cobranza Diaria</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        
        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Consulta</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha de Cobranza:</label>
                                    <input type="date" id="fecha_cobranza" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sucursal:</label>
                                    <select id="filtro_sucursal" class="form-control select2">
                                        <option value="">Todas las sucursales</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-success" onclick="generarReporte()">
                                        <i class="fas fa-search"></i> Generar Reporte
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="reporteHoy()">
                                        <i class="fas fa-calendar-day"></i> Hoy
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resumen de Cobranza -->
        <div class="row mb-3" id="resumen_cobranza" style="display: none;">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="total_cobrado">C$ 0</h3>
                        <p>Total Cobrado</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="cuotas_cobradas">0</h3>
                        <p>Cuotas Cobradas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="clientes_atendidos">0</h3>
                        <p>Clientes Atendidos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3 id="cobradores_activos">0</h3>
                        <p>Cobradores Activos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Resultados -->
        <div class="row" id="area_resultados" style="display: none;">
            <div class="col-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table"></i> Detalle de Cobranza Diaria
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success btn-sm" onclick="exportarExcel()">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="imprimirReporte()">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="resumenPorCobrador()">
                                    <i class="fas fa-chart-bar"></i> Resumen
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_cobranza" class="table table-striped table-bordered table-hover">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>Hora</th>
                                        <th>Cliente</th>
                                        <th>Préstamo</th>
                                        <th>N° Cuota</th>
                                        <th>Monto</th>
                                        <th>Cobrador</th>
                                        <th>Sucursal</th>
                                        <th>Tipo Pago</th>
                                        <th>Estado</th>
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

        <!-- Modal Resumen por Cobrador -->
        <div class="modal fade" id="modal_resumen_cobrador" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-success">
                        <h5 class="modal-title">
                            <i class="fas fa-chart-bar"></i> Resumen por Cobrador
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table id="tabla_resumen_cobrador" class="table table-striped table-bordered">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Cobrador</th>
                                        <th>Cuotas</th>
                                        <th>Monto Total</th>
                                        <th>Clientes</th>
                                        <th>Promedio</th>
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

<script>
$(document).ready(function() {
    cargarSucursales();
    $('.select2').select2();
    reporteHoy(); // Cargar reporte del día actual por defecto
});

function cargarSucursales() {
    $.ajax({
        url: 'ajax/sucursales_ajax.php',
        type: 'GET',
        data: { accion: 'listar' },
        dataType: 'json',
        success: function(respuesta) {
            var opciones = '<option value="">Todas las sucursales</option>';
            respuesta.forEach(function(sucursal) {
                opciones += `<option value="${sucursal.id}">${sucursal.nombre}</option>`;
            });
            $('#filtro_sucursal').html(opciones);
        },
        error: function() {
            console.error('Error al cargar sucursales');
        }
    });
}

function reporteHoy() {
    $('#fecha_cobranza').val(new Date().toISOString().split('T')[0]);
    generarReporte();
}

function generarReporte() {
    var fecha = $('#fecha_cobranza').val();
    var sucursalId = $('#filtro_sucursal').val();
    
    if (!fecha) {
        Swal.fire('Atención', 'Debe seleccionar una fecha para generar el reporte.', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Generando reporte...',
        text: 'Consultando cobranza del día.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: 'ajax/reportes_ajax.php',
        method: 'POST',
        data: {
            accion: 'reporte_cobranza_diaria',
            fecha: fecha,
            sucursal_id: sucursalId
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
                calcularResumen(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron cobros para la fecha seleccionada.', 'info');
                $('#area_resultados').hide();
                $('#resumen_cobranza').hide();
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el reporte. Intente nuevamente.', 'error');
        }
    });
}

function mostrarResultados(datos) {
    // Destruir tabla existente
    if ($.fn.DataTable.isDataTable('#tabla_cobranza')) {
        $('#tabla_cobranza').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_cobranza tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        var fila = `
            <tr>
                <td>${item.hora_pago || ''}</td>
                <td>${item.cliente_nombre || ''}</td>
                <td>${item.nro_prestamo || ''}</td>
                <td>${item.nro_cuota || ''}</td>
                <td>C$ ${parseFloat(item.monto_pago || 0).toLocaleString('es-NI')}</td>
                <td>${item.cobrador || 'N/A'}</td>
                <td>${item.sucursal || 'N/A'}</td>
                <td><span class="badge badge-info">${item.tipo_pago || 'Efectivo'}</span></td>
                <td><span class="badge badge-success">Cobrado</span></td>
            </tr>
        `;
        $('#tabla_cobranza tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_cobranza').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        responsive: true,
        order: [[0, "desc"]], // Ordenar por hora descendente
        pageLength: 25
    });
    
    $('#area_resultados').show();
}

function calcularResumen(datos) {
    var totalCobrado = datos.reduce((sum, item) => sum + parseFloat(item.monto_pago || 0), 0);
    var cuotasCobradas = datos.length;
    var clientesUnicos = [...new Set(datos.map(item => item.cliente_nombre))].length;
    var cobradoresUnicos = [...new Set(datos.map(item => item.cobrador))].filter(c => c && c !== 'N/A').length;
    
    $('#total_cobrado').text('C$ ' + totalCobrado.toLocaleString('es-NI'));
    $('#cuotas_cobradas').text(cuotasCobradas.toLocaleString());
    $('#clientes_atendidos').text(clientesUnicos.toLocaleString());
    $('#cobradores_activos').text(cobradoresUnicos.toLocaleString());
    
    $('#resumen_cobranza').show();
}

function resumenPorCobrador() {
    if (!$('#area_resultados').is(':visible')) {
        Swal.fire('Atención', 'Debe generar el reporte primero.', 'warning');
        return;
    }
    
    // Obtener datos actuales de la tabla
    var datos = $('#tabla_cobranza').DataTable().data().toArray();
    var resumenCobradores = {};
    
    // Agrupar por cobrador
    datos.forEach(function(fila) {
        var cobrador = $(fila[5]).text() || 'Sin cobrador';
        var monto = parseFloat($(fila[4]).text().replace('C$ ', '').replace(/,/g, '')) || 0;
        var cliente = $(fila[1]).text();
        
        if (!resumenCobradores[cobrador]) {
            resumenCobradores[cobrador] = {
                cuotas: 0,
                monto: 0,
                clientes: new Set()
            };
        }
        
        resumenCobradores[cobrador].cuotas++;
        resumenCobradores[cobrador].monto += monto;
        resumenCobradores[cobrador].clientes.add(cliente);
    });
    
    // Llenar tabla de resumen
    $('#tabla_resumen_cobrador tbody').empty();
    Object.keys(resumenCobradores).forEach(function(cobrador) {
        var data = resumenCobradores[cobrador];
        var promedio = data.cuotas > 0 ? data.monto / data.cuotas : 0;
        
        var fila = `
            <tr>
                <td>${cobrador}</td>
                <td>${data.cuotas}</td>
                <td>C$ ${data.monto.toLocaleString('es-NI')}</td>
                <td>${data.clientes.size}</td>
                <td>C$ ${promedio.toLocaleString('es-NI')}</td>
            </tr>
        `;
        $('#tabla_resumen_cobrador tbody').append(fila);
    });
    
    $('#modal_resumen_cobrador').modal('show');
}

function exportarExcel() {
    Swal.fire('Información', 'Función de exportar a Excel en desarrollo.', 'info');
}

function exportarPDF() {
    Swal.fire('Información', 'Función de exportar a PDF en desarrollo.', 'info');
}

function imprimirReporte() {
    window.print();
}
</script>

<?php 
} else {
    header("Location: index.php");
}
?> 