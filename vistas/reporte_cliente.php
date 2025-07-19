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
                <h1 class="m-0">📊 Reporte por Cliente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Por Cliente</li>
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
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Seleccionar Cliente:</label>
                                    <select id="select_cliente" class="form-control select2" style="width: 100%;">
                                        <option value="">Buscar y seleccionar cliente...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-primary" onclick="generarReporteCliente()">
                                        <i class="fas fa-search"></i> Generar Reporte
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
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title" id="titulo_reporte">
                            <i class="fas fa-table"></i> Historial del Cliente
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
                            <table id="tabla_reporte_cliente" class="table table-striped table-bordered table-hover">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Préstamo</th>
                                        <th>Fecha</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Saldo</th>
                                        <th>Acciones</th>
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
    // Inicializar Select2 para búsqueda de clientes
    $('#select_cliente').select2({
        placeholder: 'Buscar cliente por nombre o DNI...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: 'ajax/clientes_ajax.php',
            type: 'POST', // <--- Añadir esta línea
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'buscar_clientes',
                    busqueda: params.term
                };
            },
            processResults: function (data) {
                if (data.error) {
                    return { results: [] }; // Retorna un array vacío si hay un error
                }
                // Asegurarse de que 'data' sea un array. Si es un objeto con una clave 'results', usarla.
                // Algunos Select2 esperan 'results' anidado, otros directamente el array.
                const results = Array.isArray(data) ? data : (data.results || []);
                
                return {
                    results: results
                };
            },
            cache: true
        }
    });
});

function generarReporteCliente() {
    var clienteId = $('#select_cliente').val();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente para generar el reporte.', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Generando reporte...',
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
            accion: 1,
            cliente_id: clienteId
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron préstamos para este cliente.', 'info');
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el reporte. Intente nuevamente.', 'error');
        }
    });
}

function mostrarResultados(datos) {
    // Destruir tabla existente si hay una
    if ($.fn.DataTable.isDataTable('#tabla_reporte_cliente')) {
        $('#tabla_reporte_cliente').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_reporte_cliente tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        var fila = `
            <tr>
                <td>${item.nro_prestamo || ''}</td>
                <td>${item.fecha_prestamo || ''}</td>
                <td>C$ ${parseFloat(item.monto_prestamo || 0).toLocaleString('es-NI')}</td>
                <td><span class="badge badge-${item.estado === 'Pagado' ? 'success' : 'warning'}">${item.estado || ''}</span></td>
                <td>C$ ${parseFloat(item.saldo_pendiente || 0).toLocaleString('es-NI')}</td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="verDetalle('${item.nro_prestamo}')">
                        <i class="fas fa-eye"></i> Ver
                    </button>
                </td>
            </tr>
        `;
        $('#tabla_reporte_cliente tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_reporte_cliente').DataTable({
        language: {
            url: "vistas/assets/plugins/datatables/i18n/Spanish.json" // <--- Ruta actualizada
        },
        responsive: true,
        order: [[1, "desc"]]
    });
    
    // Mostrar área de resultados
    $('#area_resultados').show();
}

function verDetalle(nroPrestamo) {
    // Redirigir o abrir modal con detalle del préstamo
    Swal.fire('Información', `Ver detalle del préstamo: ${nroPrestamo}`, 'info');
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