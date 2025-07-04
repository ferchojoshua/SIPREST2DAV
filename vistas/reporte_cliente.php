<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0"><i class="fas fa-chart-line text-info"></i> Reporte de Préstamos por Cliente</h4>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item">Reportes</li>
                      <li class="breadcrumb-item active">Por Cliente</li>
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
                  <div class="card card-info card-outline shadow">
                      <div class="card-header bg-gradient-info">
                          <h3 class="card-title text-white">
                              <i class="fas fa-users mr-2"></i>Filtros de Búsqueda
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
                              <div class="col-lg-4 col-md-6 mb-3">
                                  <label for="select_clientes" class="form-label">
                                      <i class="fas fa-user text-info mr-1"></i>
                                      <span class="font-weight-bold">Seleccionar Cliente:</span>
                                  </label>
                                  <select class="form-control js-example-basic-single" id="select_clientes">
                                      <option value="">🔍 Buscar cliente...</option>
                                  </select>
                                  <div class="invalid-feedback">Debe seleccionar un cliente</div>
                              </div>
                              
                              <div class="col-lg-3 col-md-6 mb-3">
                                  <label class="form-label">
                                      <i class="fas fa-filter text-info mr-1"></i>
                                      <span class="font-weight-bold">Estado del Préstamo:</span>
                                  </label>
                                  <select class="form-control" id="select_estado">
                                      <option value="">🔍 Todos los estados</option>
                                      <option value="aprobado">✅ Aprobado</option>
                                      <option value="pendiente">⏳ Pendiente</option>
                                      <option value="anulado">❌ Anulado</option>
                                      <option value="finalizado">🏁 Finalizado</option>
                                  </select>
                              </div>
                              
                              <div class="col-lg-2 col-md-6 mb-3">
                                  <label class="form-label">
                                      <i class="fas fa-coins text-info mr-1"></i>
                                      <span class="font-weight-bold">Moneda:</span>
                                  </label>
                                  <select class="form-control" id="select_moneda">
                                      <option value="">💰 Todas</option>
                                  </select>
                              </div>
                              
                              <div class="col-lg-3 col-md-6 mb-3">
                                  <label class="form-label text-white">.</label>
                                  <div class="d-flex gap-2">
                                      <button class="btn btn-primary btn-block" id="btnFiltrar">
                                          <i class="fas fa-search mr-1"></i>Buscar Préstamos
                                      </button>
                                      <button class="btn btn-secondary" id="btnLimpiar" title="Limpiar filtros">
                                          <i class="fas fa-broom"></i>
                                      </button>
                                  </div>
                              </div>
                          </div>
                          
                          <!-- Información de Resultados -->
                          <div class="row mb-3" id="info_resultados" style="display: none;">
                              <div class="col-12">
                                  <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
                                      <i class="fas fa-info-circle mr-2"></i>
                                      <span id="texto_resultados"></span>
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
                                          <i class="fas fa-table mr-2"></i>Resultados de la Búsqueda
                                      </h5>
                                  </div>
                                  <div class="card-body p-0">
                                      <div class="table-responsive">
                                          <table id="tbl_report_cliente" class="table table-striped table-hover mb-0">
                                              <thead class="bg-gradient-info text-white">
                                      <tr>
                                                      <th style="display: none;">Id</th>
                                                      <th><i class="fas fa-hashtag mr-1"></i>N° Préstamo</th>
                                                      <th style="display: none;">id cliente</th>
                                                      <th><i class="fas fa-user mr-1"></i>Cliente</th>
                                                      <th><i class="fas fa-money-bill-wave mr-1"></i>Monto</th>
                                                      <th><i class="fas fa-calendar mr-1"></i>Fecha</th>
                                                      <th><i class="fas fa-calculator mr-1"></i>Total</th>
                                                      <th><i class="fas fa-hand-holding-usd mr-1"></i>Cuota</th>
                                                      <th><i class="fas fa-list-ol mr-1"></i>Cant.</th>
                                                      <th style="display: none;">id fpago</th>
                                                      <th><i class="fas fa-credit-card mr-1"></i>F. Pago</th>
                                                      <th><i class="fas fa-info-circle mr-1"></i>Estado</th>
                                                      <th class="text-center"><i class="fas fa-cogs mr-1"></i>Acciones</th>
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

  <!-- MODAL DETALLE PRESTAMO-->
  <div class="modal fade" id="modal_detalle_prestamo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cliente">Detalle de cuotas a Pagar</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_detalle" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <!-- <form class="needs-validation" novalidate> -->
                  <div class="row">
                      <div class="col-lg-4">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <input type="text" id="" hidden>
                                  <span class="small"> Nro Prestamo</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_nro_prestamo_d" placeholder="Nro_prestamo" disabled>

                          </div>
                      </div>
                      <div class="col-lg-8">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Cliente</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_cliente_d" placeholder="Documento" disabled>

                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Monto Pres.</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_monto_d" placeholder="Documento" disabled>


                          </div>
                      </div>

                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Interes</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_interes_d" name="text_glosa" placeholder="Descripcion" disabled>


                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Nro. Cuota</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_cuota_d" name="text_glosa" placeholder="Descripcion" disabled>

                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Forma de Pago</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_fpago__d" name="text_glosa" placeholder="Descripcion" disabled>

                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Fecha Emision</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_fecha__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Monto Cuota</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_monto_cuota__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Monto Interes</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_monto_interes__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Monto Total</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_monto_total__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                          </div>
                      </div>
                      <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small"> Cuotas Pagadas</span>
                              </label>
                              <input type="text" class=" form-control form-control-sm" id="text_cuotas_pagadas__d" name="text_fecha__d" placeholder="" disabled>


                          </div>
                      </div>
                  </div>
                  <div class="row">
                      <div class="table-responsive">
                          <table id="tbl_detalle_prestamo" class="table display table-hover text-nowrap compact  w-100  rounded" style="width:100%;">
                              <thead class="bg-info text-left">
                                  <tr>
                                      <th>Id</th>
                                      <th style="width:40%;">Nro prestamo</th>
                                      <th>Cuota</th>
                                      <th style="width:10%;">Fecha</th>
                                      <th>Monto</th>
                                      <th>Estado</th>
                                      <!-- <th class="text-cetner">Opciones</th> -->
                                  </tr>
                              </thead>
                              <tbody class="small text left">
                              </tbody>
                          </table>

                      </div>


                  </div>
                  <!-- </form> -->
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_detallee">Cerrar</button>
                  <!-- <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</button> -->
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->


  <script>
      var accion;
      var tbl_report_cliente, cliente_id;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });

      $(document).ready(function() {
          ReporteCliente();
          CargarMonedas();

          // Inicializar Select2 con configuración mejorada
          $('.js-example-basic-single').select2({
              placeholder: "🔍 Buscar cliente...",
              allowClear: true,
              width: '100%',
              language: {
                  noResults: function() {
                      return "No se encontraron clientes";
                  },
                  searching: function() {
                      return "Buscando...";
                  }
              }
          });

          // Evento para buscar automáticamente al seleccionar cliente
          $('#select_clientes').on('select2:select', function() {
              if ($(this).val()) {
                  $('#btnFiltrar').click();
              }
          });

          // Botón limpiar filtros
          $('#btnLimpiar').on('click', function() {
              $('#select_clientes').val('').trigger('change');
              $('#select_estado').val('');
              $('#select_moneda').val('');
              $('#info_resultados').hide();
              
              // Destruir y reinicializar tabla vacía
              if ($.fn.DataTable.isDataTable('#tbl_report_cliente')) {
                  tbl_report_cliente.destroy();
              }
              ReporteCliente();
              
              Toast.fire({
                  icon: 'info',
                  title: 'Filtros limpiados correctamente'
              });
          });

          /*===================================================================*/
          //FILTRAR AL DAR CLICK EN EL BOTON
          /*===================================================================*/
          $("#btnFiltrar").on('click', function() {
              // Validar que se haya seleccionado un cliente
              if ($("#select_clientes").val() == '') {
                  Toast.fire({
                      icon: 'warning',
                      title: 'Debe seleccionar un cliente para generar el reporte'
                  });
                  $("#select_clientes").focus();
                  return;
              }

              // Destruir tabla existente
              if ($.fn.DataTable.isDataTable('#tbl_report_cliente')) {
                  tbl_report_cliente.destroy();
              }

              // Obtener valores de filtros
                  cliente_id = $("#select_clientes").val();
              var estado_filtro = $("#select_estado").val();
              var moneda_filtro = $("#select_moneda").val();
              
              // Mostrar información de filtros aplicados
              var cliente_nombre = $("#select_clientes option:selected").text();
              var info_texto = `Mostrando préstamos para: <strong>${cliente_nombre}</strong>`;
              
              if (estado_filtro) {
                  info_texto += ` | Estado: <strong>${$("#select_estado option:selected").text()}</strong>`;
              }
              if (moneda_filtro) {
                  info_texto += ` | Moneda: <strong>${$("#select_moneda option:selected").text()}</strong>`;
              }
              
              $("#texto_resultados").html(info_texto);
              $("#info_resultados").show();

              tbl_report_cliente = $("#tbl_report_cliente").DataTable({
                  responsive: true,
                  processing: true,
                  dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                       '<"row"<"col-sm-12"B>>' +
                       '<"row"<"col-sm-12"tr>>' +
                       '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                  buttons: [
                      {
                          extend: 'excelHtml5',
                          title: 'Reporte de Préstamos por Cliente - ' + cliente_nombre,
                          text: '<i class="fas fa-file-excel text-success"></i> Excel',
                          className: 'btn btn-success btn-sm mr-1',
                          exportOptions: {
                              columns: [1, 3, 4, 5, 6, 7, 8, 10, 11]
                          },
                          titleAttr: 'Exportar a Excel'
                      },
                      {
                          extend: 'pdfHtml5',
                          title: 'Reporte de Préstamos por Cliente',
                          text: '<i class="fas fa-file-pdf text-danger"></i> PDF',
                          className: 'btn btn-danger btn-sm mr-1',
                          exportOptions: {
                              columns: [1, 3, 4, 5, 6, 7, 8, 10, 11]
                          },
                          orientation: 'landscape',
                          pageSize: 'A4'
                      },
                      {
                          extend: 'print',
                          text: '<i class="fas fa-print text-primary"></i> Imprimir',
                          className: 'btn btn-primary btn-sm mr-1',
                          titleAttr: 'Imprimir reporte',
                          exportOptions: {
                              columns: [1, 3, 4, 5, 6, 7, 8, 10, 11]
                          }
                      },
                      {
                          extend: 'pageLength',
                          className: 'btn btn-info btn-sm'
                      }
                  ],
                  ajax: {
                      url: "ajax/reportes_ajax.php",
                      dataSrc: "",
                      type: "POST",
                      data: {
                          'accion': 1,
                          'cliente_id': cliente_id
                      }, //LISTAR 
                  },
                  columnDefs: [{
                          targets: 0,
                          visible: false

                      },
                      {
                          targets: 2,
                          visible: false

                      },
                      {
                          targets: 9,
                          visible: false

                      },{
                        targets: 11,
                        //sortable: false,
                        createdCell: function(td, cellData, rowData, row, col) {

                            if (rowData[11] == 'aprobado') {
                                $(td).html("<span class='badge badge-success'>aprobado</span>")
                            } else if (rowData[11] == 'pendiente') {
                                $(td).html("<span class='badge badge-warning'>pendiente</span>")
                            } else if (rowData[11] == 'anulado') {
                                $(td).html("<span class='badge badge-danger'>anulado</span>")
                            } else {
                                $(td).html("<span class='badge badge-info'>finalizado</span>")

                            }

                        }
                    },
                      {
                          targets: 12, //columna acciones
                          sortable: false,
                          render: function(data, type, full, meta) {
                              return `
                                  <div class="btn-group" role="group">
                                      <button class="btn btn-info btn-sm btnVerPrestamo" 
                                              data-toggle="tooltip" title="Ver Detalles del Préstamo">
                                          <i class="fas fa-eye"></i>
                                      </button>
                                      <button class="btn btn-warning btn-sm btnCronogramaPagos" 
                                              data-toggle="tooltip" title="Cronograma de Pagos">
                                          <i class="fas fa-file-invoice-dollar"></i>
                                      </button>
                                      <button class="btn btn-success btn-sm btnContrato" 
                                              data-toggle="tooltip" title="Generar Contrato">
                                          <i class="fas fa-file-contract"></i>
                                      </button>
                                  </div>
                              `;
                          }
                      }
                  ],
                  "order": [
                      [0, 'desc']
                  ],
                  lengthMenu: [0, 5, 10, 15, 20, 50],
                  "pageLength": 10,
                  "language": idioma_espanol,
                  select: true
              });
          })


          /*===================================================================*/
          //CARGAR CLIENTES
          /*===================================================================*/
          $.ajax({
              url: "ajax/clientes_ajax.php",
              cache: false,
              contentType: false,
              processData: false,
              dataType: 'json',
              success: function(respuesta) {
                  var options = '<option value="">🔍 Buscar cliente...</option>';

                  for (let index = 0; index < respuesta.length; index++) {
                      options += `<option value="${respuesta[index][0]}">${respuesta[index][1]}</option>`;
                  }

                  $("#select_clientes").html(options);
              }
          });

          /*===================================================================*/
          //CARGAR MONEDAS
          /*===================================================================*/
          function CargarMonedas() {
              $.ajax({
                  url: "ajax/reportes_ajax.php",
                  type: "POST",
                  data: { 'accion': 9 }, // Nueva acción para obtener monedas
                  dataType: 'json',
                  success: function(respuesta) {
                      var options = '<option value="">💰 Todas las monedas</option>';
                      
                      if (respuesta && respuesta.length > 0) {
                          for (let index = 0; index < respuesta.length; index++) {
                              options += `<option value="${respuesta[index].moneda_id}">${respuesta[index].moneda_simbolo} - ${respuesta[index].moneda_nombre}</option>`;
                          }
                  }

                      $("#select_moneda").html(options);
                  },
                  error: function() {
                      console.log("Error al cargar monedas");
              }
          });
          }


          /* ======================================================================================
            VER DETALLE DE PAGOS  -
          =========================================================================================*/
          $("#tbl_report_cliente tbody").on('click', '.btnVerPrestamo', function() {
              //tbl_report_cliente.destroy();
              //accion = 2; //seteamos la accion para Eliminar

              if (tbl_report_cliente.row(this).child.isShown()) {
                  var data = tbl_report_cliente.row(this).data();
              } else {
                  var data = tbl_report_cliente.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  // console.log(data);
              }

              $("#modal_detalle_prestamo").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_detalle_prestamo").modal('show'); //abrimos el modal*/

              $("#text_nro_prestamo_d").val(data[1]);
              $("#text_cliente_d").val(data[3]);
              $("#text_monto_d").val(data[4] + ".00");
              $("#text_interes_d").val(data[13] + " %");
              $("#text_cuota_d").val(data[8]);
              $("#text_fpago__d").val(data[10]);
              $("#text_fecha__d").val(data[16]);
              $("#text_monto_cuota__d").val(data[7]);
              $("#text_monto_interes__d").val(data[14]);
              $("#text_monto_total__d").val(data[6]);
              $("#text_cuotas_pagadas__d").val(data[15]);


              Traer_Detalle(data[1]);


          })


          /* ======================================================================================
          IMPRIMIR TICKET DE CRONOGRAMA DE PAGOS
          =========================================================================================*/
          $('#tbl_report_cliente').on('click', '.btnCronogramaPagos', function() {
              if (tbl_report_cliente.row(this).child.isShown()) {
                  var data = tbl_report_cliente.row(this).data();
              } else {
                  var data = tbl_report_cliente.row($(this).parents('tr')).data();
              }

              window.open("MPDF/historial_prestamo_nuevo.php?codigo=" + data[1] + "#zoom=100", 
                         "Cronograma de Pagos", 
                         "width=1000,height=700,scrollbars=yes,resizable=yes");
          });

          /* ======================================================================================
          GENERAR CONTRATO DE PRÉSTAMO
          =========================================================================================*/
          $('#tbl_report_cliente').on('click', '.btnContrato', function() {
            if (tbl_report_cliente.row(this).child.isShown()) {
                var data = tbl_report_cliente.row(this).data();
            } else {
                  var data = tbl_report_cliente.row($(this).parents('tr')).data();
            }

              window.open("MPDF/contrato_mejorado.php?codigo=" + data[1] + "#zoom=100", 
                         "Contrato de Préstamo", 
                         "width=1000,height=700,scrollbars=yes,resizable=yes");
            });




      }) //FIN DOCUMENT READY

      function ReporteCliente() {
          cliente_id = $("#select_clientes").val();
          //  console.log(cliente_id);

          tbl_report_cliente = $("#tbl_report_cliente").DataTable({
              responsive: true,

              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte de Prestamos por Cliente',
                      "exportOptions": {
                          'columns': [1,3,4,5,6,7, 8,10,11]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1,3,4,5,6,7, 8,10,11]
                      },
                      "download": 'open'
                  },
                  'pageLength',
              ],
              ajax: {
                  url: "ajax/reportes_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1,
                      'cliente_id': cliente_id
                  }, //LISTAR 
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  },
                  {
                      targets: 2,
                      visible: false

                  },
                  {
                      targets: 9,
                      visible: false

                  }
                  // {
                  //     targets: 5, //columna 2
                  //     sortable: false, //no ordene
                  //     render: function(data, type, full, meta) {
                  //         return "<center>" +
                  //             "<span class='btnEditarMoneda  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Moneda '> " +
                  //             "<i class='fas fa-pencil-alt fs-6'></i> " +
                  //             "</span> " +
                  //             "<span class='btnEliminarMoneda  text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Moneda '> " +
                  //             "<i class='fas fa-trash fs-6'> </i> " +
                  //             "</span>" +
                  //             "</center>"
                  //     }
              ],
              "order": [
                  [0, 'desc']
              ],
              lengthMenu: [0, 5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              select: true
          });



      }


      function Traer_Detalle(nro_prestamo) {
          tbl_detalle_prestamo = $("#tbl_detalle_prestamo").DataTable({
              responsive: true,
              destroy: true,
              searching: false,
              dom: 'tp',
              ajax: {
                  url: "ajax/admin_prestamos_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 2,
                      'nro_prestamo': nro_prestamo
                  }, //LISTAR 
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  }, {
                      targets: 4, // Columna de Monto
                      render: function(data, type, row) {
                          return row[6] + ' ' + data; // Combina el símbolo de la moneda (rowData[6]) con el monto (data)
                      }
                  }, {
                      targets: 5,
                      //sortable: false,
                      createdCell: function(td, cellData, rowData, row, col) {

                          if (rowData[5] == 'pagada') {
                              $(td).html("<span class='badge badge-success'>pagada</span>")
                          } else {
                              $(td).html("<span class='badge badge-danger'>pendiente</span>")
                          }

                      }
                  }
                  //   {
                  //       targets: 6, //columna 2
                  //       sortable: false, //no ordene
                  //       render: function(td, cellData, rowData, row, col) {

                  //           if (rowData[5] == 'pagada') {
                  //               return "<center>" +
                  //                   "<span class='text-secondary px-1 disabled'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                  //                   "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                  //                   "</span> " +
                  //                   "<span class='btnImprimirRecibo text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Ticket'> " +
                  //                   "<i class='far fa-file-alt fs-6'> </i> " +
                  //                   "</span>" +
                  //                   "</center>"
                  //           } else {
                  //               return "<center>" +
                  //                   "<span class='btnPagarCuta text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Pagar Cuota'> " +
                  //                   "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                  //                   "</span> " +
                  //                   "<span class=' text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
                  //                   "<i class='far fa-file-alt fs-6'> </i> " +
                  //                   "</span>" +
                  //                   "</center>"
                  //           }
                  //       }
                  //   }
              ],

              "language": idioma_espanol,
              select: true
          });
      }



      //FUNCIONES

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

  <style>
      .card-header.bg-gradient-info {
          background: linear-gradient(45deg, #17a2b8, #007bff) !important;
      }
      
      .card-header.bg-gradient-secondary {
          background: linear-gradient(45deg, #6c757d, #495057) !important;
      }
      
      .btn-group .btn {
          margin-right: 2px;
      }
      
      .btn-group .btn:last-child {
          margin-right: 0;
      }
      
      .form-label {
          font-weight: 600;
          margin-bottom: 5px;
      }
      
      .table thead th {
          border-bottom: 2px solid #dee2e6;
          font-weight: 600;
          font-size: 0.9rem;
      }
      
      .table tbody td {
          vertical-align: middle;
          font-size: 0.85rem;
      }
      
      .badge {
          font-size: 0.75rem;
          padding: 0.35em 0.65em;
      }
      
      .alert-info {
          border-left: 4px solid #17a2b8;
      }
      
      .select2-container--default .select2-selection--single {
          height: 38px;
          border: 1px solid #ced4da;
      }
      
      .select2-container--default .select2-selection--single .select2-selection__rendered {
          line-height: 36px;
          padding-left: 12px;
      }
      
      .select2-container--default .select2-selection--single .select2-selection__arrow {
          height: 36px;
      }
      
      .dataTables_wrapper .dataTables_filter input {
          border: 1px solid #ced4da;
          border-radius: 0.25rem;
          padding: 0.375rem 0.75rem;
      }
      
      .dataTables_wrapper .dataTables_length select {
          border: 1px solid #ced4da;
          border-radius: 0.25rem;
          padding: 0.375rem 0.75rem;
      }
  </style>