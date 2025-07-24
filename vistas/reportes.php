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
                <h1 class="m-0">📊 Reporte Pivot</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Pivot</li>
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
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Configuración del Reporte</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Usuario/Cobrador:</label>
                                    <select id="select_usuario" class="form-control select2">
                                        <option value="">Todos los usuarios</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Año:</label>
                                    <select id="select_anio" class="form-control select2">
                                        <option value="">Seleccionar año</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tipo de Reporte:</label>
                                    <select id="tipo_reporte" class="form-control">
                                        <option value="mensual">Resumen Mensual</option>
                                        <option value="trimestral">Resumen Trimestral</option>
                                        <option value="anual">Resumen Anual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-info" onclick="generarReporte()">
                                        <i class="fas fa-chart-bar"></i> Generar Pivot
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Resultados -->
        <div class="row" id="area_resultados" style="display: none;">
            
            <!-- Gráfico -->
            <div class="col-md-8">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line"></i> Gráfico de Tendencias
                        </h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPivot" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Resumen -->
            <div class="col-md-4">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-calculator"></i> Resumen Ejecutivo
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="info-box bg-success">
                            <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Colocado</span>
                                <span class="info-box-number" id="total_colocado">C$ 0</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-info">
                            <span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Cobrado</span>
                                <span class="info-box-number" id="total_cobrado">C$ 0</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-warning">
                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Clientes Atendidos</span>
                                <span class="info-box-number" id="clientes_atendidos">0</span>
                            </div>
                        </div>
                        
                        <div class="info-box bg-purple">
                            <span class="info-box-icon"><i class="fas fa-percentage"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Eficiencia</span>
                                <span class="info-box-number" id="eficiencia">0%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla Detallada -->
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table"></i> Datos Detallados
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="exportarExcel()">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_pivot" class="table table-striped table-bordered table-hover">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Período</th>
                                        <th>Usuario</th>
                                        <th>Préstamos</th>
                                        <th>Monto Colocado</th>
                                        <th>Cuotas Cobradas</th>
                                        <th>Monto Cobrado</th>
                                        <th>Clientes</th>
                                        <th>Eficiencia</th>
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
var chartPivot = null;

$(document).ready(function() {
    cargarUsuarios();
    cargarAnios();
    $('.select2').select2();
});

function cargarUsuarios() {
    $.ajax({
        url: 'ajax/reportes_ajax.php',
        method: 'POST',
        data: { accion: 4 },
        dataType: 'json',
        success: function(respuesta) {
            $('#select_usuario').append('<option value="">Todos los usuarios</option>');
            respuesta.forEach(function(usuario) {
                $('#select_usuario').append(`<option value="${usuario.id}">${usuario.nombre}</option>`);
            });
        },
        error: function() {
            console.error('Error al cargar usuarios');
        }
    });
}

function cargarAnios() {
    $.ajax({
        url: 'ajax/reportes_ajax.php',
        method: 'POST',
        data: { accion: 5 },
        dataType: 'json',
        success: function(respuesta) {
            $('#select_anio').append('<option value="">Seleccionar año</option>');
            respuesta.forEach(function(anio) {
                $('#select_anio').append(`<option value="${anio.anio}">${anio.anio}</option>`);
            });
            
            // Seleccionar año actual por defecto
            $('#select_anio').val(new Date().getFullYear()).trigger('change');
        },
        error: function() {
            console.error('Error al cargar años');
        }
    });
}

function generarReporte() {
    var usuario = $('#select_usuario').val();
    var anio = $('#select_anio').val();
    var tipoReporte = $('#tipo_reporte').val();
    
    if (!anio) {
        Swal.fire('Atención', 'Debe seleccionar un año para generar el reporte.', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Generando reporte pivot...',
        text: 'Procesando datos...',
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
            accion: 3,
            usuario: usuario,
            anio: anio,
            tipo: tipoReporte
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
                generarGrafico(respuesta);
                calcularResumen(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron datos para los filtros seleccionados.', 'info');
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
    if ($.fn.DataTable.isDataTable('#tabla_pivot')) {
        $('#tabla_pivot').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_pivot tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        var eficiencia = item.monto_colocado > 0 ? 
            ((item.monto_cobrado / item.monto_colocado) * 100).toFixed(1) : 0;
            
        var fila = `
            <tr>
                <td>${item.periodo || ''}</td>
                <td>${item.usuario || 'Todos'}</td>
                <td>${item.prestamos || 0}</td>
                <td>C$ ${parseFloat(item.monto_colocado || 0).toLocaleString('es-NI')}</td>
                <td>${item.cuotas_cobradas || 0}</td>
                <td>C$ ${parseFloat(item.monto_cobrado || 0).toLocaleString('es-NI')}</td>
                <td>${item.clientes || 0}</td>
                <td><span class="badge badge-${eficiencia >= 80 ? 'success' : eficiencia >= 60 ? 'warning' : 'danger'}">${eficiencia}%</span></td>
            </tr>
        `;
        $('#tabla_pivot tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_pivot').DataTable({
        language: {
            url: "vistas/assets/plugins/datatables/i18n/Spanish.json"
        },
        responsive: true,
        order: [[0, "asc"]]
    });
    
    $('#area_resultados').show();
}

function generarGrafico(datos) {
    // Destruir gráfico existente
    if (chartPivot) {
        chartPivot.destroy();
    }
    
    var ctx = document.getElementById('chartPivot').getContext('2d');
    var labels = datos.map(item => item.periodo);
    var montoColocado = datos.map(item => parseFloat(item.monto_colocado || 0));
    var montoCobrado = datos.map(item => parseFloat(item.monto_cobrado || 0));
    
    chartPivot = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Monto Colocado',
                data: montoColocado,
                borderColor: 'rgb(54, 162, 235)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1
            }, {
                label: 'Monto Cobrado',
                data: montoCobrado,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'C$ ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function calcularResumen(datos) {
    var totalColocado = datos.reduce((sum, item) => sum + parseFloat(item.monto_colocado || 0), 0);
    var totalCobrado = datos.reduce((sum, item) => sum + parseFloat(item.monto_cobrado || 0), 0);
    var clientesAtendidos = datos.reduce((sum, item) => sum + parseInt(item.clientes || 0), 0);
    var eficienciaPromedio = totalColocado > 0 ? ((totalCobrado / totalColocado) * 100).toFixed(1) : 0;
    
    $('#total_colocado').text('C$ ' + totalColocado.toLocaleString('es-NI'));
    $('#total_cobrado').text('C$ ' + totalCobrado.toLocaleString('es-NI'));
    $('#clientes_atendidos').text(clientesAtendidos.toLocaleString());
    $('#eficiencia').text(eficienciaPromedio + '%');
}

function exportarExcel() {
    Swal.fire('Información', 'Función de exportar a Excel en desarrollo.', 'info');
}

function exportarPDF() {
    Swal.fire('Información', 'Función de exportar a PDF en desarrollo.', 'info');
}
</script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php 
} else {
    header("Location: index.php");
}
?> 