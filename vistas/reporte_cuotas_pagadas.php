<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0"><i class="fas fa-check-circle text-info"></i> Reporte de Cuotas Pagadas</h4>
            </div>
            <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active">Cuotas Pagadas</li>
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
                <div class="card card-primary card-outline shadow fade-in-up">
                    <div class="card-header bg-gradient-primary">
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
                                <label for="fecha_inicial_cuotas" class="form-label">
                                    <i class="fas fa-calendar-alt text-primary mr-1"></i>
                                    <span class="font-weight-bold">Fecha Inicial:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_inicial_cuotas" value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <div class="col-lg-3 col-md-6 mb-3">
                                <label for="fecha_final_cuotas" class="form-label">
                                    <i class="fas fa-calendar-check text-primary mr-1"></i>
                                    <span class="font-weight-bold">Fecha Final:</span>
                                </label>
                                <input type="date" class="form-control" id="fecha_final_cuotas" value="<?php echo date('Y-m-d'); ?>">
                      </div>
                            <div class="col-lg-2 col-md-6 mb-3">
                                <label class="form-label">
                                    <i class="fas fa-coins text-primary mr-1"></i>
                                    <span class="font-weight-bold">Moneda:</span>
                                      </label>
                                <select class="form-control" id="select_moneda_cuotas">
                                    <option value="">💰 Todas</option>
                                </select>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-3">
                                <label class="form-label text-white">.</label>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-block" id="btnFiltrarCuotas">
                                        <i class="fas fa-search mr-1"></i>Buscar Cuotas Pagadas
                                    </button>
                                    <button class="btn btn-secondary" id="btnLimpiarCuotas" title="Limpiar filtros">
                                        <i class="fas fa-broom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Resultados -->
                        <div class="row mb-3" id="info_resultados_cuotas" style="display: none;">
                            <div class="col-12">
                                <div class="alert alert-primary alert-dismissible fade show mb-0" role="alert">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span id="texto_resultados_cuotas"></span>
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
                                        <i class="fas fa-table mr-2"></i>Resultados de Cuotas Pagadas
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table id="tbl_reporte_cuotas_pagadas" class="table table-striped table-hover mb-0">
                                            <thead class="bg-gradient-primary text-white">
                                                <tr>
                                                    <th><i class="fas fa-user mr-1"></i>Cliente</th>
                                                    <th><i class="fas fa-hashtag mr-1"></i>Nro Préstamo</th>
                                                    <th><i class="fas fa-list-ol mr-1"></i>Nro Cuota</th>
                                                    <th><i class="fas fa-money-bill-wave mr-1"></i>Monto Cuota</th>
                                                    <th><i class="fas fa-calendar mr-1"></i>Fecha Pago</th>
                                                    <th><i class="fas fa-coins mr-1"></i>Moneda</th>
                                                    <th><i class="fas fa-info-circle mr-1"></i>Estado</th>
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
        CargarMonedasCuotas();
        
        // Cargar datos iniciales
        CargarCuotasPagadas();
        
        // Botón limpiar filtros
        $('#btnLimpiarCuotas').on('click', function() {
            $('#fecha_inicial_cuotas').val('<?php echo date('Y-m-d'); ?>');
            $('#fecha_final_cuotas').val('<?php echo date('Y-m-d'); ?>');
            $('#select_moneda_cuotas').val('');
            $('#info_resultados_cuotas').hide();
            
            // Recargar datos
            CargarCuotasPagadas();
            
            Toast.fire({
                icon: 'info',
                title: 'Filtros limpiados correctamente'
            });
        });

        $("#btnFiltrarCuotas").on('click', function() {
            var fecha_inicial = $("#fecha_inicial_cuotas").val();
            var fecha_final = $("#fecha_final_cuotas").val();
            var moneda_filtro = $("#select_moneda_cuotas").val();
            
            // Mostrar información de filtros aplicados
            var info_texto = `Cuotas pagadas`;
            if (fecha_inicial && fecha_final) {
                info_texto += ` del <strong>${fecha_inicial}</strong> al <strong>${fecha_final}</strong>`;
            }
            if (moneda_filtro) {
                info_texto += ` | Moneda: <strong>${$("#select_moneda_cuotas option:selected").text()}</strong>`;
            }
            
            $("#texto_resultados_cuotas").html(info_texto);
            $("#info_resultados_cuotas").show();
            
            CargarCuotasPagadas();
        });

        function CargarCuotasPagadas() {
            // Destruir la tabla si ya existe
            if ($.fn.DataTable.isDataTable('#tbl_reporte_cuotas_pagadas')) {
                $('#tbl_reporte_cuotas_pagadas').DataTable().destroy();
                $('#tbl_reporte_cuotas_pagadas tbody').empty();
            }
            
            // Configuración mejorada de DataTables
            $.ajax({
                url: "ajax/reportes_ajax.php",
                type: "POST",
                data: {
                    'accion': 2 // Accion para cuotas pagadas
                },
                dataType: 'json',
                success: function(data) {
                    console.log("Datos recibidos:", data);
                    
                    if (data && Array.isArray(data)) {
                        // Filtrar por fechas y moneda si se seleccionaron
                        var dataFiltrada = data;
                        var fecha_inicial = $("#fecha_inicial_cuotas").val();
                        var fecha_final = $("#fecha_final_cuotas").val();
                        var moneda_filtro = $("#select_moneda_cuotas").val();
                        
                        if (fecha_inicial && fecha_final) {
                            dataFiltrada = dataFiltrada.filter(function(item) {
                                var fechaPago = item.pdetalle_fecha_registro ? item.pdetalle_fecha_registro.split(' ')[0] : '';
                                return fechaPago >= fecha_inicial && fechaPago <= fecha_final;
                            });
                        }
                        
                        if (moneda_filtro) {
                            dataFiltrada = dataFiltrada.filter(function(item) {
                                return item.moneda_simbolo && item.moneda_simbolo.includes(moneda_filtro);
                            });
                        }
                        
                        // Inicializar DataTables con diseño mejorado
                        $("#tbl_reporte_cuotas_pagadas").DataTable({
                            data: dataFiltrada,
                            columns: [
                                { data: 'cliente_nombres' },
                                { data: 'nro_prestamo' },
                                { data: 'pdetalle_nro_cuota' },
                                { 
                                    data: 'pdetalle_monto_cuota',
                                    render: function(data, type, row) {
                                        return (row.moneda_simbolo || '') + ' ' + parseFloat(data || 0).toFixed(2);
                                    }
                                },
                                { 
                                    data: 'pdetalle_fecha_registro',
                                    render: function(data) {
                                        return data ? data.split(' ')[0] : '';
                                    }
                                },
                                { data: 'moneda_simbolo' },
                                { 
                                    data: 'pdetalle_estado_cuota',
                                    render: function(data) {
                                        if (data === 'pagada') {
                                            return '<span class="badge badge-success">PAGADA</span>';
                                        } else {
                                            return '<span class="badge badge-warning">PENDIENTE</span>';
                                        }
                                    }
                                }
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
                                    title: 'Reporte de Cuotas Pagadas',
                                    text: '<i class="fas fa-file-excel text-success"></i> Excel',
                                    className: 'btn btn-success btn-sm mr-1',
                                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
                                    titleAttr: 'Exportar a Excel'
                                },
                                {
                                    extend: 'pdfHtml5',
                                    title: 'Reporte de Cuotas Pagadas',
                                    text: '<i class="fas fa-file-pdf text-danger"></i> PDF',
                                    className: 'btn btn-danger btn-sm mr-1',
                                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] },
                                    orientation: 'landscape',
                                    pageSize: 'A4'
                                },
                                {
                                    extend: 'print',
                                    text: '<i class="fas fa-print text-primary"></i> Imprimir',
                                    className: 'btn btn-primary btn-sm mr-1',
                                    titleAttr: 'Imprimir reporte',
                                    exportOptions: { columns: [0, 1, 2, 3, 4, 5, 6] }
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
                            title: `Se encontraron ${dataFiltrada.length} cuotas pagadas`
                        });
                        
                    } else {
                        console.error("No se recibieron datos o el formato es incorrecto");
                        Toast.fire({
                            icon: 'info',
                            title: 'No se encontraron cuotas pagadas.'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error en la petición AJAX:", error);
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
        function CargarMonedasCuotas() {
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
                    
                    $("#select_moneda_cuotas").html(options);
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