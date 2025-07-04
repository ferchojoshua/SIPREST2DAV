  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <!-- <div class="col-sm-6">
                  <h4 class="m-0">Reporte por Cliente</h4>
              </div> -->
              <!-- /.col -->
              <!-- <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item ">Reportes</li>
                      <li class="breadcrumb-item active">Por Cliente</li>
                  </ol>
              </div> -->
              <!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <div class="content pb-2">
      <div class="container-fluid">
          <div class="row p-0 m-0">
              <div class="col-md-12">
                  <div class="card card-info card-outline shadow ">
                      <div class="card-header">
                          <h3 class="card-title">Reporte Prestamo por Clientes</h3>

                      </div>
                      <div class=" card-body">
                          <div class="row">
                              <div class="col-md-6">
                                  <label for="select_clientes">
                                      <span class="small">Cliente:</span>
                                  </label>
                                  <div class="d-flex align-items-end" style="gap: 10px;">
                                        <select class="form-control js-example-basic-single" id="select_clientes"></select>
                                        <a class="btn btn-primary" id="btnFiltrar">Buscar</a>
                                    </div>
                                     <div class="invalid-feedback">Seleccione un Cliente</div>
                              </div>
                          </div><br>
                          <div class="col-12 table-responsive">
                              <table id="tbl_report_cliente" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-info text-left">
                                      <tr>
                                          <th>Id</th>
                                          <th>Nro Prestamo</th>
                                          <th>id cliente</th>
                                          <th class="all">Cliente</th>
                                          <th>Monto</th>
                                          <th>Fecha</th>
                                          <th>Total</th>
                                          <th>Cuota</th>
                                          <th>Cant. Cuota</th>
                                          <th>id fpago</th>
                                          <th>F. Pago</th>
                                          <th>Estado</th>
                                          <!-- <th>Descripcion</th>
                                          <th>Descripcion</th>  -->
                                          <th class="text-cetner">Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody class="small text left">
                                  </tbody>
                              </table>

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

          $('.js-example-basic-single').select2();

          // Trigger search on Enter key press in the select_clientes field
          $('#select_clientes').on('select2:close', function(e) {
              // Check if the user pressed Enter (keyCode 13) after selecting
              // This requires listening to a keyup event or similar on the search input of select2
              // For simplicity, we'll trigger the search on any change/selection if not explicitly an Enter press on the main element
              if (e.originalEvent && e.originalEvent.keyCode === 13) {
                  $('#btnFiltrar').click();
              }
          });

          $('#select_clientes').on('change', function() {
              // This ensures search is triggered even if not specifically Enter after selection
              $('#btnFiltrar').click();
          });

          /*===================================================================*/
          //FILTRAR AL DAR CLICK EN EL BOTON
          /*===================================================================*/
          $("#btnFiltrar").on('click', function() {
              tbl_report_cliente.destroy();

              if ($("#select_clientes").val() == '') {
                  Toast.fire({
                      icon: 'error',
                      title: ' Debe Seleccionar un cliente'
                  })
                  $("#select_clientes").focus();

              } else {

                  cliente_id = $("#select_clientes").val();
              }

              tbl_report_cliente = $("#tbl_report_cliente").DataTable({
                  responsive: true,

                  dom: 'Bfrtip',
                  buttons: [{
                          "extend": 'excelHtml5',
                          "title": 'Reporte de Prestamos por Cliente',
                          "exportOptions": {
                              'columns': [1, 3, 4,5,6,7,8,10,11]
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
                          targets: 12, //columna 2
                          sortable: false, //no ordene
                          render: function(data, type, full, meta) {
                              return "<center>" +
                                  "<span class='btnVerPrestamo  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Moneda '> " +
                                  "<i class='fa fa-eye fs-6 fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnCronogramaPagos text-warning px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cronograma de Pagos'> " +
                                  "<i class='fas fa-file-invoice-dollarfas fa-file-invoice-dollar fs-6'> </i> " + 
                                  "</span>" +

                                  "</center>"
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

                  var options = '<option selected value="">Seleccione un Cliente</option>';

                  for (let index = 0; index < respuesta.length; index++) {
                      options = options + '<option value=' + respuesta[index][0] + '>' + respuesta[index][
                          1
                      ] + '</option>';

                  }

                  $("#select_clientes").append(options);

              }
          });


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
          $('#tbl_report_cliente').on('click', '.btnCronogramaPagos', function() { //class foto tiene que ir en el boton

            if (tbl_report_cliente.row(this).child.isShown()) {
                var data = tbl_report_cliente.row(this).data();
            } else {
                var data = tbl_report_cliente.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
            }

            window.open("MPDF/historial_prestamo.php?codigo=" + data[1] + "#zoom=100", "Cronograma de Pagos ", "scrollbards=NO");

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