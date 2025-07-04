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
                          <h3 class="card-title">Reporte Cuotas Pagadas</h3>

                      </div>
                      <div class=" card-body">
                          <div class="row align-items-end">
                              <div class="col-md-6 d-flex">
                                  <div class="form-group flex-grow-1 me-2">
                                      <label for="">
                                          <span class="small">Cliente:</span>
                                      </label>
                                      <select class="form-control js-example-basic-single" id="select_clientes"> </select>
                                      <div class="invalid-feedback">Seleccione un Cliente</div>
                                  </div>
                                  <div class="form-group m-0">
                                      <a class="btn btn-primary" id="btnFiltrar">Buscar</a>
                              </div>
                              </div>
                          </div><br>
                          <div class="col-12 table-responsive">
                              <table id="tbl_report_cuotas_P" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-info text-left">
                                      <tr>
                                          <th>Id client</th>
                                          <th>Cliente</th>
                                          <th>Nro Prestamo</th>
                                          <th>Nro Cuota</th>
                                          <th>Monto</th>
                                          <th>Fecha</th>
                                          <th>moneda id</th>
                                          <th>Moneda</th>

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

  <script>
      var accion;
      var tbl_report_cuotas_P, cliente_id;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });
      $(document).ready(function() {
        ReporteCuotasPagadas();

        $('.js-example-basic-single').select2();

        /*===================================================================*/
        //FILTRAR AL DAR CLICK EN EL BOTON O CAMBIAR SELECTOR
        /*===================================================================*/
        $("#btnFiltrar").on('click', function() {
            if ($("#select_clientes").val() == '') {
                Toast.fire({
                    icon: 'error',
                    title: ' Debe Seleccionar un cliente'
                })
                $("#select_clientes").focus();

            } else {
                ReporteCuotasPagadas();
            }
        })

        $('#select_clientes').on('change', function() {
            ReporteCuotasPagadas();
        });


         /* ======================================================================================
          IMPRIMIR TICKET DE CUOTA PAGADA
          =========================================================================================*/
          $('#tbl_report_cuotas_P').on('click', '.btnImprimirRecibo', function() { //class foto tiene que ir en el boton

            if (tbl_report_cuotas_P.row(this).child.isShown()) {
                var data = tbl_report_cuotas_P.row(this).data();
            } else {
                var data = tbl_report_cuotas_P.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                //console.log(data);
            }

            window.open("MPDF/ticket_pago_cuota.php?codigo=" + data[2] + "&cuota=" + data[3] + "#zoom=100", "Recibo de Pago ", "scrollbards=NO");

            });


      }) // FIN DOCUMENT


      function ReporteCuotasPagadas() {
          cliente_id = $("#select_clientes").val();
          //  console.log(cliente_id);

          tbl_report_cuotas_P = $("#tbl_report_cuotas_P").DataTable({
              responsive: true,

              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte de Cuotas Pagadas',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4,5,7]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4,5,7]   
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
                      'accion': 2,
                      'cliente_id': cliente_id
                  }, //LISTAR 
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  },
                  {
                      targets: 6,
                      visible: false

                  },
                  {
                      targets: 8, //columna 2
                      sortable: false, //no ordene
                      render: function(data, type, full, meta) {
                          return "<center>" +
                              "<span class='btnImprimirRecibo  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Ticket '> " +
                              "<i class='fas fa-file-invoice-dollarfas fa-file-invoice-dollar fs-6'></i> " +
                              "</span> " +
                            //   "<span class='btnEliminarMoneda  text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Moneda '> " +
                            //   "<i class='fas fa-trash fs-6'> </i> " +
                            //   "</span>" +
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



      }

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