<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0"><i class="fas fa-calendar-day text-info"></i> Reporte Diario</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active">Reporte Diario</li>
                </ol>
            </div>
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content pb-2">
    <div class="container-fluid">
        <div class="row p-0 m-0">
            <div class="col-md-12">
                <div class="card card-info card-outline shadow fade-in-up">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title text-white">
                            <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros -->
                        <div class="row align-items-end mb-3">
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label for="fecha_reporte_diario" class="form-label">
                                    <i class="fas fa-calendar-alt text-info mr-1"></i>
                                    <span class="font-weight-bold">Fecha del Reporte:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_reporte_diario" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-8 col-md-6 mb-3">
                                <label class="form-label text-white">.</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-info btn-block" id="btnGenerarReporteDiario">
                                        <i class="fas fa-chart-line mr-1"></i>Generar Reporte Diario
                                    </button>
                                    <button class="btn btn-secondary" id="btnLimpiarDiario" title="Limpiar filtros">
                                        <i class="fas fa-broom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Resultados -->
                        <div class="row mb-3" id="info_resultados_diario" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span id="texto_resultados_diario"></span>
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Resumen de Totales -->
                        <div class="row" id="resumen_totales" style="display: none;">
                            <!-- Préstamos -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-gradient-primary text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-1">Préstamos</h5>
                                                <h3 class="mb-0" id="total_prestamos">0</h3>
                                                <small>Cantidad: <span id="cantidad_prestamos">0</span></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-handshake fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagos de Cuotas -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-gradient-success text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-1">Cuotas Pagadas</h5>
                                                <h3 class="mb-0" id="total_cuotas">0</h3>
                                                <small>Cantidad: <span id="cantidad_cuotas">0</span></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Ingresos -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-gradient-warning text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-1">Ingresos</h5>
                                                <h3 class="mb-0" id="total_ingresos">0</h3>
                                                <small>Cantidad: <span id="cantidad_ingresos">0</span></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-arrow-up fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Egresos -->
                            <div class="col-lg-3 col-md-6 mb-3">
                                <div class="card bg-gradient-danger text-white h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h5 class="card-title mb-1">Egresos</h5>
                                                <h3 class="mb-0" id="total_egresos">0</h3>
                                                <small>Cantidad: <span id="cantidad_egresos">0</span></small>
                                            </div>
                                            <div class="align-self-center">
                                                <i class="fas fa-arrow-down fa-2x opacity-75"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabla de Resultados Detallada -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-gradient-secondary">
                                    <h5 class="card-title text-white mb-0">
                                        <i class="fas fa-table mr-2"></i>Detalle del Reporte Diario
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="tbl_reporte_diario" class="table table-striped table-hover mb-0">
                                            <thead class="bg-gradient-info text-white">
                                                <tr>
                                                    <th><i class="fas fa-cogs mr-1"></i>Tipo Operación</th>
                                                    <th><i class="fas fa-sort-numeric-up mr-1"></i>Cantidad</th>
                                                    <th><i class="fas fa-money-bill-wave mr-1"></i>Monto Capital</th>
                                                    <th><i class="fas fa-percentage mr-1"></i>Monto Interés</th>
                                                    <th><i class="fas fa-calculator mr-1"></i>Monto Total</th>
                                                    <th><i class="fas fa-coins mr-1"></i>Moneda</th>
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
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<script>
    $(document).ready(function() {
        
        // Toast para notificaciones
        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
        
        // Cargar datos iniciales
        CargarReporteDiario();
        
        // Botón limpiar filtros
        $('#btnLimpiarDiario').on('click', function() {
            $('#fecha_reporte_diario').val('<?php echo date('Y-m-d'); ?>');
            $('#info_resultados_diario').hide();
            $('#resumen_totales').hide();
            
            // Recargar datos
            CargarReporteDiario();
            
            Toast.fire({
                icon: 'info',
                title: 'Filtros limpiados correctamente'
            });
        });

        $("#btnGenerarReporteDiario").on('click', function() {
            var fecha = $("#fecha_reporte_diario").val();
            
            if (!fecha) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Por favor seleccione una fecha'
                });
                return;
            }
            
            // Mostrar información de filtros aplicados
            var info_texto = `Reporte diario del <strong>${fecha}</strong>`;
            $("#texto_resultados_diario").html(info_texto);
            $("#info_resultados_diario").show();
            
            CargarReporteDiario();
        });

        function CargarReporteDiario() {
            var fecha = $("#fecha_reporte_diario").val();
            
            // Destruir la tabla si ya existe
            if ($.fn.DataTable.isDataTable('#tbl_reporte_diario')) {
                $('#tbl_reporte_diario').DataTable().destroy();
                $('#tbl_reporte_diario tbody').empty();
            }
            
            $.ajax({
                url: "ajax/reportes_ajax.php",
                type: "POST",
                data: {
                    'accion': 10, // Accion para reporte diario
                    'fecha': fecha
                },
                dataType: 'json',
                success: function(data) {
                    console.log("Datos recibidos:", data);
                    
                    if (data && Array.isArray(data)) {
                        // Inicializar totales
                        var totales = {
                            prestamos: {cantidad: 0, monto: 0},
                            cuotas: {cantidad: 0, monto: 0},
                            ingresos: {cantidad: 0, monto: 0},
                            egresos: {cantidad: 0, monto: 0}
                        };
                        
                        // Procesar datos y calcular totales
                        data.forEach(function(item) {
                            switch(item.tipo_operacion) {
                                case 'PRÉSTAMOS':
                                    totales.prestamos.cantidad += parseInt(item.cantidad);
                                    totales.prestamos.monto += parseFloat(item.monto_total);
                                    break;
                                case 'PAGOS DE CUOTAS':
                                    totales.cuotas.cantidad += parseInt(item.cantidad);
                                    totales.cuotas.monto += parseFloat(item.monto_total);
                                    break;
                                case 'INGRESOS':
                                    totales.ingresos.cantidad += parseInt(item.cantidad);
                                    totales.ingresos.monto += parseFloat(item.monto_total);
                                    break;
                                case 'EGRESOS':
                                    totales.egresos.cantidad += parseInt(item.cantidad);
                                    totales.egresos.monto += parseFloat(item.monto_total);
                                    break;
                            }
                        });
                        
                        // Actualizar resumen
                        $('#cantidad_prestamos').text(totales.prestamos.cantidad);
                        $('#total_prestamos').text('$' + totales.prestamos.monto.toFixed(2));
                        $('#cantidad_cuotas').text(totales.cuotas.cantidad);
                        $('#total_cuotas').text('$' + totales.cuotas.monto.toFixed(2));
                        $('#cantidad_ingresos').text(totales.ingresos.cantidad);
                        $('#total_ingresos').text('$' + totales.ingresos.monto.toFixed(2));
                        $('#cantidad_egresos').text(totales.egresos.cantidad);
                        $('#total_egresos').text('$' + totales.egresos.monto.toFixed(2));
                        
                        $('#resumen_totales').show();
                        
                        // Configurar DataTable
                        $('#tbl_reporte_diario').DataTable({
                            data: data,
                            columns: [
                                { 
                                    data: 'tipo_operacion',
                                    render: function(data, type, row) {
                                        var icon = '';
                                        var color = '';
                                        switch(data) {
                                            case 'PRÉSTAMOS':
                                                icon = '<i class="fas fa-handshake text-primary mr-1"></i>';
                                                break;
                                            case 'PAGOS DE CUOTAS':
                                                icon = '<i class="fas fa-check-circle text-success mr-1"></i>';
                                                break;
                                            case 'INGRESOS':
                                                icon = '<i class="fas fa-arrow-up text-warning mr-1"></i>';
                                                break;
                                            case 'EGRESOS':
                                                icon = '<i class="fas fa-arrow-down text-danger mr-1"></i>';
                                                break;
                                        }
                                        return icon + data;
                                    }
                                },
                                { 
                                    data: 'cantidad',
                                    className: 'text-center',
                                    render: function(data) {
                                        return '<span class="badge badge-info">' + data + '</span>';
                                    }
                                },
                                { 
                                    data: 'monto_capital',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        return row.moneda_simbolo + ' ' + parseFloat(data).toFixed(2);
                                    }
                                },
                                { 
                                    data: 'monto_interes',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        return row.moneda_simbolo + ' ' + parseFloat(data).toFixed(2);
                                    }
                                },
                                { 
                                    data: 'monto_total',
                                    className: 'text-right',
                                    render: function(data, type, row) {
                                        return '<strong>' + row.moneda_simbolo + ' ' + parseFloat(data).toFixed(2) + '</strong>';
                                    }
                                },
                                { 
                                    data: 'moneda_nombre',
                                    className: 'text-center'
                                }
                            ],
                            language: {
                                "decimal": "",
                                "emptyTable": "No hay datos disponibles para la fecha seleccionada",
                                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                                "infoEmpty": "Mostrando 0 a 0 de 0 registros",
                                "infoFiltered": "(filtrado de _MAX_ registros totales)",
                                "infoPostFix": "",
                                "thousands": ",",
                                "lengthMenu": "Mostrar _MENU_ registros",
                                "loadingRecords": "Cargando...",
                                "processing": "Procesando...",
                                "search": "Buscar:",
                                "zeroRecords": "No se encontraron resultados",
                                "paginate": {
                                    "first": "Primero",
                                    "last": "Último",
                                    "next": "Siguiente",
                                    "previous": "Anterior"
                                },
                                "aria": {
                                    "sortAscending": ": activar para ordenar de forma ascendente",
                                    "sortDescending": ": activar para ordenar de forma descendente"
                                }
                            },
                            responsive: true,
                            dom: 'Bfrtip',
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class="fas fa-file-excel"></i> Excel',
                                    className: 'btn btn-success btn-sm',
                                    title: 'Reporte Diario - ' + fecha
                                },
                                {
                                    extend: 'pdfHtml5',
                                    text: '<i class="fas fa-file-pdf"></i> PDF',
                                    className: 'btn btn-danger btn-sm',
                                    title: 'Reporte Diario - ' + fecha,
                                    orientation: 'landscape'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print"></i> Imprimir',
                                    className: 'btn btn-info btn-sm',
                                    title: 'Reporte Diario - ' + fecha
                                }
                            ],
                            order: [[0, 'asc']],
                            pageLength: 25
                        });
                        
                        if (data.length === 0) {
                            $('#resumen_totales').hide();
                            Toast.fire({
                                icon: 'info',
                                title: 'No hay datos para la fecha seleccionada'
                            });
                        }
                    } else {
                        $('#resumen_totales').hide();
                        Toast.fire({
                            icon: 'error',
                            title: 'Error al cargar los datos del reporte'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.log("Error:", error);
                    $('#resumen_totales').hide();
                    Toast.fire({
                        icon: 'error',
                        title: 'Error de conexión al servidor'
                    });
                }
            });
        }
    });
</script> 