<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0"><i class="fas fa-undo-alt text-info"></i> Reporte de Recuperación</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active">Recuperación</li>
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
                <div class="card card-success card-outline shadow fade-in-up">
                    <div class="card-header bg-gradient-success">
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
                        <!-- Filtros Mejorados -->
                        <div class="row align-items-end mb-3">
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fecha_inicial_recuperacion" class="form-label">
                                    <i class="fas fa-calendar-alt text-success mr-1"></i>
                                    <span class="font-weight-bold">Fecha Inicial:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_inicial_recuperacion" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fecha_final_recuperacion" class="form-label">
                                    <i class="fas fa-calendar-check text-success mr-1"></i>
                                    <span class="font-weight-bold">Fecha Final:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_final_recuperacion" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-coins text-success mr-1"></i>
                                    <span class="font-weight-bold">Moneda:</span>
                                </label>
                                <select class="form-control" id="select_moneda_recuperacion">
                                    <option value="">💰 Todas</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label class="form-label text-white">.</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-success btn-block" id="btnFiltrarRecuperacion">
                                        <i class="fas fa-search mr-1"></i>Buscar Recuperaciones
                                    </button>
                                    <button class="btn btn-secondary" id="btnLimpiarRecuperacion" title="Limpiar filtros">
                                        <i class="fas fa-broom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Resultados -->
                        <div class="row mb-3" id="info_resultados_recuperacion" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span id="texto_resultados_recuperacion"></span>
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
                                        <i class="fas fa-table mr-2"></i>Resultados de Recuperaciones
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="tbl_reporte_recuperacion" class="table table-striped table-hover mb-0">
                                            <thead class="bg-gradient-success text-white">
                                                <tr>
                                                    <th><i class="fas fa-user mr-1"></i>Cliente</th>
                                                    <th><i class="fas fa-hashtag mr-1"></i>Nro Préstamo</th>
                                                    <th><i class="fas fa-list-ol mr-1"></i>Nro Cuota</th>
                                                    <th><i class="fas fa-money-bill-wave mr-1"></i>Monto Pagado</th>
                                                    <th><i class="fas fa-calendar mr-1"></i>Fecha de Pago</th>
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
        CargarMonedasRecuperacion();
        
        // Botón limpiar filtros
        $('#btnLimpiarRecuperacion').on('click', function() {
            $('#fecha_inicial_recuperacion').val('<?php echo date('Y-m-d'); ?>');
            $('#fecha_final_recuperacion').val('<?php echo date('Y-m-d'); ?>');
            $('#select_moneda_recuperacion').val('');
            $('#info_resultados_recuperacion').hide();
            
            // Destruir y reinicializar tabla vacía
            if ($.fn.DataTable.isDataTable('#tbl_reporte_recuperacion')) {
                $('#tbl_reporte_recuperacion').DataTable().destroy();
                $('#tbl_reporte_recuperacion tbody').empty();
            }
            
            Toast.fire({
                icon: 'info',
                title: 'Filtros limpiados correctamente'
            });
        });

        $("#btnFiltrarRecuperacion").on('click', function() {
            var fecha_inicial = $("#fecha_inicial_recuperacion").val();
            var fecha_final = $("#fecha_final_recuperacion").val();
            var moneda_filtro = $("#select_moneda_recuperacion").val();
            
            // Validar fechas
            if (!fecha_inicial || !fecha_final) {
                Toast.fire({
                    icon: 'warning',
                    title: 'Debe seleccionar ambas fechas para generar el reporte'
                });
                return;
            }
            
            if (fecha_inicial > fecha_final) {
                Toast.fire({
                    icon: 'error',
                    title: 'La fecha inicial no puede ser mayor que la fecha final'
                });
                return;
            }
            
            // Mostrar información de filtros aplicados
            var info_texto = `Recuperaciones del <strong>${fecha_inicial}</strong> al <strong>${fecha_final}</strong>`;
            if (moneda_filtro) {
                info_texto += ` | Moneda: <strong>${$("#select_moneda_recuperacion option:selected").text()}</strong>`;
            }
            
            $("#texto_resultados_recuperacion").html(info_texto);
            $("#info_resultados_recuperacion").show();
            
            // Destruir la tabla si ya existe
            if ($.fn.DataTable.isDataTable('#tbl_reporte_recuperacion')) {
                $('#tbl_reporte_recuperacion').DataTable().destroy();
                $('#tbl_reporte_recuperacion tbody').empty();
            }
            
            // Configuración mejorada de DataTables
            $.ajax({
                url: "ajax/reportes_ajax.php",
                type: "POST",
                data: {
                    'accion': 8, // Accion para recuperacion
                    'fecha_inicial': fecha_inicial,
                    'fecha_final': fecha_final
                },
                dataType: 'json',
                success: function(data) {
                    console.log("Datos recibidos:", data);
                    console.log("Tipo de datos:", typeof data);
                    console.log("Es array:", Array.isArray(data));
                    
                    if (data && data.error) {
                        console.error("Error del servidor:", data.error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Error: ' + data.error
                        });
                        return;
                    }
                    
                    if (data && Array.isArray(data) && data.length > 0) {
                        console.log("Primer elemento:", data[0]);
                        console.log("Claves disponibles:", Object.keys(data[0]));
                        
                        // Filtrar por moneda si se seleccionó
                        var dataFiltrada = data;
                        if (moneda_filtro) {
                            dataFiltrada = data.filter(function(item) {
                                return item.moneda_simbolo && item.moneda_simbolo.includes(moneda_filtro);
                            });
                        }
                        
                        // Inicializar DataTables con diseño mejorado
                        $("#tbl_reporte_recuperacion").DataTable({
                            data: dataFiltrada,
                            columns: [
                                { data: 'cliente_nombres' },
                                { data: 'nro_prestamo' },
                                { data: 'pdetalle_nro_cuota' },
                                { 
                                    data: 'pago_monto',
                                    render: function(data, type, row) {
                                        return row.moneda_simbolo + ' ' + parseFloat(data).toFixed(2);
                                    }
                                },
                                { data: 'pago_fecha' },
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
                                    title: 'Reporte de Recuperación - ' + fecha_inicial + ' al ' + fecha_final,
                                    text: '<i class="fas fa-file-excel text-success"></i> Excel',
                                    className: 'btn btn-success btn-sm mr-1',
                                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                                    titleAttr: 'Exportar a Excel'
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Reporte de Recuperación',
                                    text: '<i class="fas fa-file-pdf text-danger"></i> PDF',
                                    className: 'btn btn-danger btn-sm mr-1',
                                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] },
                                    orientation: 'landscape',
                                    pageSize: 'A4'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print text-primary"></i> Imprimir',
                                    className: 'btn btn-primary btn-sm mr-1',
                                    titleAttr: 'Imprimir reporte',
                                    exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
                                },
                                {
                                    extend: 'pageLength',
                                    className: 'btn btn-info btn-sm'
                                }
                            ],
                            lengthMenu: [10, 25, 50, 100],
                            pageLength: 10,
                            language: idioma_espanol,
                            select: true,
                            order: [[4, 'desc']] // Ordenar por fecha de pago descendente
                        });
                        
                        Toast.fire({
                            icon: 'success',
                            title: `Se encontraron ${dataFiltrada.length} registros de recuperación`
                        });
                        
                    } else {
                        console.error("No se recibieron datos o el formato es incorrecto");
                        Toast.fire({
                            icon: 'info',
                            title: 'No se encontraron registros de recuperación para el rango de fechas seleccionado.'
                        });
                        
                        // Inicializar tabla vacía
                        $("#tbl_reporte_recuperacion").DataTable({
                            data: [],
                            columns: [
                                { data: 'cliente_nombres' },
                                { data: 'nro_prestamo' },
                                { data: 'pdetalle_nro_cuota' },
                                { data: 'pago_monto' },
                                { data: 'pago_fecha' },
                                { data: 'moneda_simbolo' }
                            ],
                            responsive: true,
                            language: idioma_espanol
                        });
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
        });

        /*===================================================================*/
        //CARGAR MONEDAS PARA FILTRO
        /*===================================================================*/
        function CargarMonedasRecuperacion() {
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
                    
                    $("#select_moneda_recuperacion").html(options);
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