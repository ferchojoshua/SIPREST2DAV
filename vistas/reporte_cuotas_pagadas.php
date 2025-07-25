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
                <h1 class="m-0">💰 Reporte de Cuotas Pagadas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Cuotas Pagadas</li>
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
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Fecha</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Inicial:</label>
                                    <input type="date" id="fecha_inicial" class="form-control" value="<?php echo date('Y-m-01'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fecha Final:</label>
                                    <input type="date" id="fecha_final" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-success" onclick="generarReporte()">
                                        <i class="fas fa-search"></i> Generar Reporte
                                    </button>
                                    <button type="button" class="btn btn-info" onclick="cargarTodos()">
                                        <i class="fas fa-list"></i> Ver Todos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas Rápidas -->
        <div class="row mb-3" id="estadisticas" style="display: none;">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="total_cuotas">0</h3>
                        <p>Cuotas Pagadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
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
                        <h3 id="clientes_unicos">0</h3>
                        <p>Clientes</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3 id="promedio_cuota">C$ 0</h3>
                        <p>Promedio/Cuota</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calculator"></i>
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
                            <i class="fas fa-table"></i> Historial de Cuotas Pagadas
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="exportarExcel()">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button type="button" class="btn btn-info btn-sm" onclick="imprimirReporte()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_cuotas_pagadas" class="table table-striped table-bordered table-hover">
                                <thead class="bg-success text-white">
                                    <tr>
                                        <th>Fecha Pago</th>
                                        <th>Cliente</th>
                                        <th>Préstamo</th>
                                        <th>N° Cuota</th>
                                        <th>Monto</th>
                                        <th>Cobrador</th>
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

    </div>
</div>
<?php require_once "modulos/footer.php"; ?>
<script>
$(document).ready(function() {
    cargarTodos(); // Cargar datos al inicio
});

function generarReporte() {
    var fechaInicial = $('#fecha_inicial').val();
    var fechaFinal = $('#fecha_final').val();
    
    if (!fechaInicial || !fechaFinal) {
        Swal.fire('Atención', 'Debe seleccionar ambas fechas para generar el reporte.', 'warning');
        return;
    }
    
    if (new Date(fechaInicial) > new Date(fechaFinal)) {
        Swal.fire('Error', 'La fecha inicial no puede ser mayor que la fecha final.', 'error');
        return;
    }
    
    cargarDatos(fechaInicial, fechaFinal);
}

function cargarTodos() {
    cargarDatos();
}

function cargarDatos(fechaInicial = null, fechaFinal = null) {
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Cargando datos...',
        text: 'Por favor espere.',
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
            accion: 2,
            fecha_inicial: fechaInicial,
            fecha_final: fechaFinal
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
                calcularEstadisticas(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron cuotas pagadas en el período seleccionado.', 'info');
                $('#area_resultados').hide();
                $('#estadisticas').hide();
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al cargar los datos. Intente nuevamente.', 'error');
        }
    });
}

function mostrarResultados(datos) {
    // Destruir tabla existente si hay una
    if ($.fn.DataTable.isDataTable('#tabla_cuotas_pagadas')) {
        $('#tabla_cuotas_pagadas').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_cuotas_pagadas tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        var fila = `
            <tr>
                <td>${item.fecha_pago || ''}</td>
                <td>${item.cliente_nombre || ''}</td>
                <td>${item.nro_prestamo || ''}</td>
                <td>${item.nro_cuota || ''}</td>
                <td>C$ ${parseFloat(item.monto_cuota || 0).toLocaleString('es-NI')}</td>
                <td>${item.cobrador || 'N/A'}</td>
                <td><span class="badge badge-success">Pagada</span></td>
            </tr>
        `;
        $('#tabla_cuotas_pagadas tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_cuotas_pagadas').DataTable({
        language: {
            url: "vistas/assets/plugins/datatables/i18n/Spanish.json" // <--- Ruta actualizada
        },
        responsive: true,
        order: [[0, "desc"]] // Ordenar por fecha de pago descendente
    });
    
    // Mostrar área de resultados y estadísticas
    $('#area_resultados').show();
    $('#estadisticas').show(); // Mostrar también las estadísticas si hay resultados
}

function calcularEstadisticas(datos) {
    let totalCuotas = datos.length;
    let montoTotal = 0;
    let clientesUnicos = new Set();

    datos.forEach(function(item) {
        montoTotal += parseFloat(item.monto_cuota || 0);
        clientesUnicos.add(item.cliente_nombre);
    });

    let promedioCuota = totalCuotas > 0 ? montoTotal / totalCuotas : 0;

    $('#total_cuotas').text(totalCuotas);
    $('#monto_total').text('C$ ' + montoTotal.toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $('#clientes_unicos').text(clientesUnicos.size);
    $('#promedio_cuota').text('C$ ' + promedioCuota.toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
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