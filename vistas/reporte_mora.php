<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0"><i class="fas fa-exclamation-triangle text-warning"></i> Reporte de Mora</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active">Mora</li>
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
                <div class="card card-warning card-outline shadow fade-in-up">
                    <div class="card-header bg-gradient-warning">
                        <h3 class="card-title text-dark font-weight-bold">
                            <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool text-dark" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Filtros Mejorados -->
                        <div class="row align-items-end mb-3">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fecha_inicial_mora" class="form-label">
                                    <i class="fas fa-calendar-alt text-warning mr-1"></i>
                                    <span class="font-weight-bold">Fecha Inicial:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_inicial_mora" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fecha_final_mora" class="form-label">
                                    <i class="fas fa-calendar-check text-warning mr-1"></i>
                                    <span class="font-weight-bold">Fecha Final:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_final_mora" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-coins text-warning mr-1"></i>
                                    <span class="font-weight-bold">Moneda:</span>
                                </label>
                                <select class="form-control" id="select_moneda_mora">
                                    <option value="">💰 Todas</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label class="form-label text-white">.</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-warning btn-block text-dark font-weight-bold" id="btnFiltrarMora">
                                        <i class="fas fa-search mr-1"></i>Buscar Cuotas en Mora
                                    </button>
                                    <button class="btn btn-secondary" id="btnLimpiarMora" title="Limpiar filtros">
                                        <i class="fas fa-broom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Resultados -->
                        <div class="row mb-3" id="info_resultados_mora" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-warning alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span id="texto_resultados_mora"></span>
                                    <button type="button" class="close" data-dismiss="alert">
                                        <span>&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabla de Resultados Mejorada -->
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-gradient-secondary">
                                    <h5 class="card-title text-white mb-0">
                                        <i class="fas fa-table mr-2"></i>Resultados de Cuotas en Mora
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="tbl_reporte_mora" class="table table-striped table-hover mb-0">
                                            <thead class="bg-gradient-warning text-dark">
                                                <tr>
                                                    <th style="display: none;">ID Cliente</th>
                                                    <th><i class="fas fa-user mr-1"></i>Cliente</th>
                                                    <th><i class="fas fa-hashtag mr-1"></i>Nro Préstamo</th>
                                                    <th><i class="fas fa-list-ol mr-1"></i>Nro Cuota</th>
                                                    <th><i class="fas fa-calendar-times mr-1"></i>Fecha Vencimiento</th>
                                                    <th><i class="fas fa-money-bill-wave mr-1"></i>Monto Cuota</th>
                                                    <th><i class="fas fa-credit-card mr-1"></i>Saldo Pendiente</th>
                                                    <th><i class="fas fa-clock mr-1"></i>Días Mora</th>
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
        
        // Cargar monedas para el filtro
        CargarMonedasMora();
        
        // Cargar datos iniciales
        CargarCuotasMora();
        
        // Botón limpiar filtros
        $('#btnLimpiarMora').on('click', function() {
            $('#fecha_inicial_mora').val('<?php echo date('Y-m-d'); ?>');
            $('#fecha_final_mora').val('<?php echo date('Y-m-d'); ?>');
            $('#select_moneda_mora').val('');
            $('#info_resultados_mora').hide();
            
            // Recargar datos
            CargarCuotasMora();
            
            Toast.fire({
                icon: 'info',
                title: 'Filtros limpiados correctamente'
            });
        });

        $("#btnFiltrarMora").on('click', function() {
            var fecha_inicial = $("#fecha_inicial_mora").val();
            var fecha_final = $("#fecha_final_mora").val();
            var moneda_filtro = $("#select_moneda_mora").val();
            
            // Mostrar información de filtros aplicados
            var info_texto = `Cuotas en mora`;
            if (fecha_inicial && fecha_final) {
                info_texto += ` con vencimiento del <strong>${fecha_inicial}</strong> al <strong>${fecha_final}</strong>`;
            }
            if (moneda_filtro) {
                info_texto += ` | Moneda: <strong>${$("#select_moneda_mora option:selected").text()}</strong>`;
            }
            
            $("#texto_resultados_mora").html(info_texto);
            $("#info_resultados_mora").show();
            
            CargarCuotasMora();
        });

        function CargarCuotasMora() {
            // Destruir la tabla si ya existe
            if ($.fn.DataTable.isDataTable('#tbl_reporte_mora')) {
                $('#tbl_reporte_mora').DataTable().destroy();
                $('#tbl_reporte_mora tbody').empty();
            }
            
            // Configuración mejorada de DataTables
            $.ajax({
                url: "ajax/reportes_ajax.php",
                type: "POST",
                data: {
                    'accion': 7 // Accion para mora
                },
                dataType: 'json',
                success: function(data) {
                    console.log("Datos recibidos:", data);
                    
                    // Si data es un string, es un mensaje de error
                    if (typeof data === 'string') {
                        console.error("Error del servidor:", data);
                        Toast.fire({
                            icon: 'error',
                            title: 'Error: ' + data
                        });
                        return;
                    }
                    
                    // Si data es un array vacío
                    if (Array.isArray(data) && data.length === 0) {
                        console.log("No hay datos para mostrar");
                        Toast.fire({
                            icon: 'info',
                            title: 'No hay clientes morosos para mostrar'
                        });
                        return;
                    }
                    
                    if (data && data.length > 0) {
                        // Filtrar por fechas y moneda si se seleccionaron
                        var dataFiltrada = data;
                        var fecha_inicial = $("#fecha_inicial_mora").val();
                        var fecha_final = $("#fecha_final_mora").val();
                        var moneda_filtro = $("#select_moneda_mora").val();
                        
                        if (fecha_inicial && fecha_final) {
                            dataFiltrada = dataFiltrada.filter(function(item) {
                                var fechaVencimiento = item.pdetalle_fecha || '';
                                return fechaVencimiento >= fecha_inicial && fechaVencimiento <= fecha_final;
                            });
                        }
                        
                        if (moneda_filtro) {
                            dataFiltrada = dataFiltrada.filter(function(item) {
                                return item.moneda_simbolo && item.moneda_simbolo.includes(moneda_filtro);
                            });
                        }
                        
                        // Inicializar DataTables con diseño mejorado
                        $("#tbl_reporte_mora").DataTable({
                            data: dataFiltrada,
                            columns: [
                                { data: 'cliente_id' },
                                { data: 'cliente_nombres' },
                                { data: 'nro_prestamo' },
                                { data: 'pdetalle_nro_cuota' },
                                { data: 'pdetalle_fecha' },
                                { 
                                    data: 'pdetalle_monto_cuota',
                                    render: function(data, type, row) {
                                        return (row.moneda_simbolo || '') + ' ' + parseFloat(data || 0).toFixed(2);
                                    }
                                },
                                { 
                                    data: 'pdetalle_saldo_cuota',
                                    render: function(data, type, row) {
                                        return (row.moneda_simbolo || '') + ' ' + parseFloat(data || 0).toFixed(2);
                                    }
                                },
                                { 
                                    data: 'dias_mora',
                                    render: function(data) {
                                        var clase = 'badge-warning';
                                        if (data > 30) clase = 'badge-danger';
                                        else if (data > 15) clase = 'badge-warning';
                                        else if (data > 0) clase = 'badge-info';
                                        
                                        return `<span class="badge ${clase}">${data} días</span>`;
                                    }
                                },
                                { data: 'moneda_simbolo' }
                            ],
                            responsive: true,
                            processing: true,
                            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                                 '<"row"<"col-sm-12"B>>' +
                                 '<"row"<"col-sm-12"tr>>' +
                                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                            buttons: [
                                {
                                    extend: 'excelHtml5',
                                    title: 'Reporte de Clientes Morosos',
                                    text: '<i class="fas fa-file-excel text-success"></i> Excel',
                                    className: 'btn btn-success btn-sm mr-1',
                                    exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
                                    titleAttr: 'Exportar a Excel'
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Reporte de Clientes Morosos',
                                    text: '<i class="fas fa-file-pdf text-danger"></i> PDF',
                                    className: 'btn btn-danger btn-sm mr-1',
                                    exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] },
                                    orientation: 'landscape',
                                    pageSize: 'A4'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print text-primary"></i> Imprimir',
                                    className: 'btn btn-primary btn-sm mr-1',
                                    titleAttr: 'Imprimir reporte',
                                    exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8] }
                                },
                                {
                                    extend: 'pageLength',
                                    className: 'btn btn-info btn-sm'
                                }
                            ],
                            columnDefs: [
                                { targets: 0, visible: false }
                            ],
                            lengthMenu: [10, 25, 50, 100],
                            pageLength: 10,
                            language: idioma_espanol,
                            select: true,
                            order: [[7, 'desc']] // Ordenar por días de mora descendente
                        });
                        
                        Toast.fire({
                            icon: 'warning',
                            title: `Se encontraron ${dataFiltrada.length} clientes morosos`
                        });
                        
                    } else {
                        console.error("No se recibieron datos o el formato es incorrecto");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX:", error);
                    console.log("Respuesta del servidor:", xhr.responseText);
                    Toast.fire({
                        icon: 'error',
                        title: 'Error de conexión. Intente nuevamente.'
                    });
                }
            });
        }

        /*===================================================================*/
        //CARGAR MONEDAS PARA FILTRO
        /*===================================================================*/
        function CargarMonedasMora() {
            $.ajax({
                url: "ajax/reportes_ajax.php",
                type: "POST",
                data: { 'accion': 9 }, // Acción para obtener monedas
                dataType: 'json',
                success: function(respuesta) {
                    var options = '<option value="">💰 Todas las monedas</option>';
                    
                    if (respuesta && respuesta.length > 0) {
                        for (let index = 0; index < respuesta.length; index++) {
                            options += `<option value="${respuesta[index].moneda_simbolo}">${respuesta[index].moneda_simbolo} - ${respuesta[index].moneda_nombre}</option>`;
                        }
                    }
                    
                    $("#select_moneda_mora").html(options);
                },
                error: function() {
                    console.log("Error al cargar monedas");
                }
            });
        }

    });

    var Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000
    });

    var idioma_espanol = {
        select: {
            rows: "%d fila seleccionada"
        },
        "sProcessing": "Procesando...",
        "sLengthMenu": "Ver _MENU_ ",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "No hay informacion en esta tabla",
        "sInfo": "Mostrando (_START_ a _END_) total de _TOTAL_ registros",
        "sInfoEmpty": "Registros del (0 al 0) total de 0 registros",
        "sInfoFiltered": "(Filtrado de un total de _MAX_ registros)",
        "SInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "<b>No se encontraron datos</b>",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Ultimo",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "aria": {
            "sSortAscending": ": ordenar de manera Ascendente",
            "SSortDescending": ": ordenar de manera Descendente ",
        }
    }
</script> 