  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0">Administrar Prestamos</h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">Administrar Prestamos</li>
                  </ol>
              </div><!-- /.col -->
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
                          <h3 class="card-title">Listado de Prestamos por usuario</h3>

                      </div>
                      <div class=" card-body">
                          <input type="text" id="id_usuario" hidden>
                          <div class="table-responsive">
                              <table id="tbl_ls_prestamos" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-info text-left">
                                      <tr>
                                          <th>Id</th>
                                          <th>Nro Prestamo</th>
                                          <th>cliente id</th>
                                          <th>Cliente</th>
                                          <th>Monto</th>
                                          <th>Interes</th>
                                          <th>Cuotas</th>
                                          <th>fpago id</th>
                                          <th>F. Pago</th>
                                          <th>usuario id</th>
                                          <th>Usuario</th>
                                          <th>Fecha</th>
                                          <th>Estado</th>
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
                                  <span class="small"> Cuota</span>
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
                      <div class="col-md-2" hidden>
                          <label form="">&nbsp;</label><br>
                          <button type="button" class="btn btn-danger btn-sm" id="btnLiquidar">Liquidar</button>
                          <!-- <button class="btn btn-info btn-sm" ><i class="fas fa-search"></i>Liquidar</button> -->
                      </div>

                      <!-- <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              </label>
                              <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</button>


                          </div>
                      </div> -->
                  </div>
                  <div class="row">
                      <div class="table-responsive">
                          <table id="prestamo_detalle" class="table display table-hover text-nowrap compact  w-100  rounded" style="width: 100%;">
                              <thead class="bg-info text-left">
                                  <tr>
                                      <th>Id</th>
                                      <th>Nro prestamo</th>
                                      <th>Cuota</th>
                                      <th>Fecha</th>
                                      <th>Monto</th>
                                      <th>Saldo Pendiente</th>
                                      <th>Estado</th>
                                      <th class="text-cetner">Opciones</th>
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

  <!-- MODAL ABONAR CUOTA-->
  <div class="modal fade" id="modal_abonar_cuota" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
              <div class="modal-header bg-success py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_abono">Abonar Cuota</h5>
                  <button type="button" class="close  text-white border-0 fs-5" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Nro Cuota:</span>
                                  <input type="text" class="form-control form-control-sm" id="nro_cuota_abono" disabled>
                              </label>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Monto Cuota Original:</span>
                                  <input type="text" class="form-control form-control-sm" id="monto_cuota_original" disabled>
                              </label>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Saldo Pendiente:</span>
                                  <input type="text" class="form-control form-control-sm" id="saldo_pendiente_abono" disabled>
                              </label>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Monto a Abonar:</span>
                                  <input type="number" step="0.01" class="form-control form-control-sm" id="monto_a_abonar" placeholder="Ingrese monto a abonar">
                              </label>
                              <div class="d-flex justify-content-between mt-2">
                                  <button type="button" class="btn btn-info btn-sm" id="btnAbonoCompleto">
                                      <i class="fas fa-coins"></i> Pago Completo
                                  </button>
                                  <button type="button" class="btn btn-warning btn-sm" id="btnAbonoMitad">
                                      <i class="fas fa-divide"></i> 50%
                                  </button>
                                  <button type="button" class="btn btn-success btn-sm" id="btnAbonoMinimo">
                                      <i class="fas fa-percentage"></i> 25%
                                  </button>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Tipo de Abono:</span>
                                  <select class="form-control form-control-sm" id="tipo_abono">
                                      <option value="normal">Normal - Solo para esta cuota</option>
                                      <option value="extraordinario">Extraordinario - Aplicar a múltiples cuotas</option>
                                  </select>
                              </label>
                              <small class="text-muted">
                                  <i class="fas fa-info-circle"></i> 
                                  El abono extraordinario se aplicará primero a la cuota actual y el excedente a las siguientes cuotas.
                              </small>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-success btn-sm" id="btnRegistrarAbono">Registrar Abono</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal Abono -->

  <script>
      var accion;
      var tbl_ls_prestamos, prestamo_detalle_dt;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });


      $(document).ready(function() {
        
          var id_usuario = $("#text_Idprincipal").val();
          //  $("#id_usuario").val(id);


          // var id_usuario =  $("#id_usuario").val();

          /***************************************************************************
           * INICIAR DATATABLE CLIENTES
           ******************************************************************************/
          var tbl_ls_prestamos = $("#tbl_ls_prestamos").DataTable({
              responsive: true,


              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte de Prestamos por usuario',
                      "exportOptions": {
                          'columns': [1, 3, 4.5, 6, 8, 10, 11, 12]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 3, 4.5, 6, 8, 10, 11, 12]
                      },
                      "download": 'open'
                  },
                  'pageLength',
              ],
              ajax: {
                  url: "ajax/admin_prestamos_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1,
                      'id_usuario': id_usuario
                  }, //LISTAR 
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  }, {
                      targets: 2,
                      visible: false

                  }, {
                      targets: 7,
                      visible: false

                  },
                  {
                      targets: 9,
                      visible: false

                  }, {
                      targets: 12,
                      //sortable: false,
                      createdCell: function(td, cellData, rowData, row, col) {

                          if (rowData[12] == 'aprobado') {
                              $(td).html("<span class='badge badge-success'>aprobado</span>")
                          } else if (rowData[12] == 'pendiente') {
                              $(td).html("<span class='badge badge-warning'>pendiente</span>")
                          } else if (rowData[12] == 'anulado') {
                              $(td).html("<span class='badge badge-danger'>anulado</span>")
                          } else {
                              $(td).html("<span class='badge badge-info'>finalizado</span>")

                          }

                      }
                  }, {
                      targets: 13, //columna 2
                      sortable: false, //no ordene
                      render: function(td, cellData, rowData, row, col) {

                          if (rowData[12] == 'aprobado' || rowData[12] == 'finalizado') {
                              return "<center>" +
                                  "<span class='btnPagar text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Pagar Cuota'> " +
                                  "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnCronogramaPagos text-warning px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cronograma de Pagos'> " +
                                  "<i class='fas fa-file-invoice-dollarfas fa-file-invoice-dollar fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='btnContrato text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Contrato'> " +
                                  "<i class='fas fa-book fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='EnviarCorreo text-info px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Enviar cronograma de cuotas por Correo'> " +
                                  "<i class='fas fa-envelope fs-6'> </i> " +
                                  "</span>" +
                                  "</center>"
                          } else { //pendiente
                              return "<center>" +
                                  "<span class=' text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top'> " +
                                  "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                                  "</span> " +
                                  "<span class=' text-secondary px-1'data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-file-invoice-dollar fs-6'> </i> " +
                                  "</span>" +
                                  "<span class=' text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-book fs-6'> </i> " +
                                  "</span>" +
                                  "<span class=' text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-envelope fs-6'> </i> " +
                                  "</span>" +
                                  "</center>"

                          }
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


          /* ======================================================================================
            VER DETALLE DE PAGOS  -
          =========================================================================================*/
          $("#tbl_ls_prestamos tbody").on('click', '.btnPagar', function() {

              //accion = 2; //seteamos la accion para Eliminar

              if (tbl_ls_prestamos.row(this).child.isShown()) {
                  var data = tbl_ls_prestamos.row(this).data();
              } else {
                  var data = tbl_ls_prestamos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  //   console.log(data);
              }

              $("#modal_detalle_prestamo").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_detalle_prestamo").modal('show'); //abrimos el modal*/

              $("#text_nro_prestamo_d").val(data[1]);
              $("#text_cliente_d").val(data[3]);
              $("#text_monto_d").val(data[4] + ".00");
              $("#text_interes_d").val(data[5] + " %");
              $("#text_cuota_d").val(data[6]);
              $("#text_fpago__d").val(data[8]);
              $("#text_fecha__d").val(data[11]);
              $("#text_monto_cuota__d").val(data[14]);
              $("#text_monto_interes__d").val(data[15]);
              $("#text_monto_total__d").val(data[16]);
              $("#text_cuotas_pagadas__d").val(data[17]);


              Traer_Detalle(data[1]);

              CargarCantCuotasPagadas();
          })


          /* ======================================================================================
                PAGAR UNA CUOTA DEL PRESTAMO
            =========================================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnPagarCuta', function() {

              accion = 3; //seteamos la accion para Eliminar

              if (prestamo_detalle_dt.row(this).child.isShown()) {
                  var data = prestamo_detalle_dt.row(this).data();
              } else {
                  var data = prestamo_detalle_dt.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  //  console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
              }

              var nro_prestamo = data[1];
              var pdetalle_nro_cuota = data[2];
              var estado = data[6];
              console.log("Datos para pagar cuota:", nro_prestamo, pdetalle_nro_cuota, estado);

              Swal.fire({
                  title: 'Desea Pagar cuota Nro "' + data[2] + '" ?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si, Pagar',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {

                  if (result.isConfirmed) {

                      var datos = new FormData();

                      datos.append("accion", accion);
                      datos.append("nro_prestamo", nro_prestamo); //jalamos el codigo que declaramos mas arriba
                      datos.append("pdetalle_nro_cuota", pdetalle_nro_cuota);


                      $.ajax({
                          url: "ajax/admin_prestamos_ajax.php",
                          method: "POST",
                          data: datos, //enviamos lo de la variable datos
                          cache: false,
                          contentType: false,
                          processData: false,
                          dataType: 'json',
                          success: function(respuesta) {

                              if (respuesta == "ok") {

                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Cuota Pagada '
                                      // title: titulo_msj
                                  });
                                  CargarCantCuotasPagadas();
                                  Notificaciones();

                                  prestamo_detalle_dt.ajax.reload(); //recargamos el datatable
                                  tbl_ls_prestamos.ajax.reload();

                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al Pagar cuota'
                                  });
                              }

                          }
                      });

                  }
              })

          })


          /* ======================================================================================
          EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
          =========================================================================================*/
          $("#btncerrar_detallee, #btncerrarmodal_detalle").on('click', function() {
              $("#text_descripcion").val("");
              $("#text_nro_prestamo_d").val("");
              $("#text_cliente_d").val("");
              $("#text_monto_d").val("");
              $("#text_interes_d").val("");
              $("#text_cuota_d").val("");
              $("#text_fpago__d").val("");
              $("#text_fecha__d").val("");

              if ($.fn.DataTable.isDataTable('#prestamo_detalle')) {
                  prestamo_detalle_dt.destroy();
              }
              // $('#tbl_detalle_prestamo').empty(); 


          })


          /* ======================================================================================
          IMPRIMIR TICKET DE CUOTA PAGADA
          =========================================================================================*/
          $('#prestamo_detalle').on('click', '.btnImprimirRecibo', function() { //class foto tiene que ir en el boton

              if (prestamo_detalle_dt.row(this).child.isShown()) {
                  var data = prestamo_detalle_dt.row(this).data();
              } else {
                  var data = prestamo_detalle_dt.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }

              window.open("MPDF/ticket_pago_cuota.php?codigo=" + data[1] + "&cuota=" + data[2] + "#zoom=100", "Recibo de Pago ", "scrollbards=NO");

          });

          /* ======================================================================================
          IMPRIMIR TICKET DE CRONOGRAMA DE PAGOS
          =========================================================================================*/
          $('#tbl_ls_prestamos').on('click', '.btnCronogramaPagos', function() { //class foto tiene que ir en el boton

              if (tbl_ls_prestamos.row(this).child.isShown()) {
                  var data = tbl_ls_prestamos.row(this).data();
              } else {
                  var data = tbl_ls_prestamos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }

              window.open("MPDF/historial_prestamo.php?codigo=" + data[1] + "#zoom=100", "Cronograma de Pagos ", "scrollbards=NO");

          });


          /********************************************************************
          		ENVIAR CRONOGRAMA DE PAGOS POR CORREO
          ********************************************************************/
          $('#tbl_ls_prestamos').on('click', '.EnviarCorreo', function() { //class foto tiene que ir en el boton
              var data = tbl_ls_prestamos.row($(this).parents('tr')).data(); //tama単o de escritorio
              if (tbl_ls_prestamos.row(this).child.isShown()) {
                  var data = tbl_ls_prestamos.row(this).data(); //para celular y usas el responsive datatable

              }

              Swal.fire({
                  title: 'Esta seguro de Enviar el cronograma de cuotas por correo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.open("MPDF/historial_prestamo_Email.php?codigo=" + data[1] + "#zoom=100", "Cronograma de Pagos ", "scrollbards=NO");
                      Toast.fire({
                          icon: 'success',
                          title: 'Correo enviado correctamente'
                      });


                  }

              })

          });

          /* ======================================================================================
          IMPRIMIR CONTRATO
          =========================================================================================*/
          $('#tbl_ls_prestamos').on('click', '.btnContrato', function() { //class foto tiene que ir en el boton

              if (tbl_ls_prestamos.row(this).child.isShown()) {
                  var data = tbl_ls_prestamos.row(this).data();
              } else {
                  var data = tbl_ls_prestamos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }

              window.open("MPDF/contrato.php?codigo=" + data[1] + "#zoom=100", "Contrato", "scrollbards=NO");

          });

          /********************************************************************
          		ENVIAR RECIBO DE CUOTA PAGADA POR CORREO
          ********************************************************************/
          $('#prestamo_detalle').on('click', '.EnviarCorreoCuotaP', function() { //class foto tiene que ir en el boton
              var data = prestamo_detalle_dt.row($(this).parents('tr')).data(); //tama単o de escritorio
              if (prestamo_detalle_dt.row(this).child.isShown()) {
                  var data = prestamo_detalle_dt.row(this).data(); //para celular y usas el responsive datatable

              }

              Swal.fire({
                  title: 'Esta seguro de Enviar el Recibo por correo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if (result.isConfirmed) {
                    window.open("MPDF/ticket_pago_cuota_Email.php?codigo=" + data[1] + "&cuota=" + data[2] + "#zoom=100", "Recibo de Pago ", "scrollbards=NO");
                      Toast.fire({
                          icon: 'success',
                          title: 'Correo enviado correctamente'
                      });


                  }

              })

          });


          /* ======================================================================================
            LIQUIDAR TOTALMENTE EL PRESTAMO AL HACER CLICK
          =========================================================================================*/
          $("#btnLiquidar").on('click', function() {
              var nro_prestamo = $("#text_nro_prestamo_d").val();
              var arreglo_cuota = new Array();

              // Usar el API del DataTable para obtener los datos
              prestamo_detalle_dt.rows().data().each(function(row) {
                  arreglo_cuota.push(row[2]); // Índice 2 es pdetalle_nro_cuota
              });

              var pdetalle_nro_cuota = arreglo_cuota.toString();
              console.log("Cuotas a liquidar:", pdetalle_nro_cuota);

              Swal.fire({
                  title: 'Esta seguro que desea liquidar totalmente el prestamo"' + nro_prestamo + '" ?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si, Liquidar',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {

                  if (result.isConfirmed) {
                      $.ajax({
                          url: "ajax/admin_prestamos_ajax.php",
                          method: "POST",
                          data: {
                              accion: 5,
                              nro_prestamo: nro_prestamo,
                              pdetalle_nro_cuota: pdetalle_nro_cuota
                          },
                          async: true,
                          dataType: 'json',
                          success: function(respuesta) {

                              console.log(respuesta);

                              if (respuesta == "ok") {

                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Prestamo Liquidado Correctamente '
                                  });
                                  CargarCantCuotasPagadas();

                                  prestamo_detalle_dt.ajax.reload(); //recargamos el datatable
                                  tbl_ls_prestamos.ajax.reload();

                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al Liquidar Prestamo'
                                  });
                              }

                          }
                      });

                  }

              })
          })

          /* ======================================================================================
            ABONAR UNA CUOTA DEL PRESTAMO
          =========================================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnAbonarCuota', function() {
              console.log("Botón Abonar Cuota clickeado!");
              if (prestamo_detalle_dt.row(this).child.isShown()) {
                  var data = prestamo_detalle_dt.row(this).data();
              } else {
                  var data = prestamo_detalle_dt.row($(this).parents('tr')).data();
              }

              console.log("Datos de la fila:", data);

              var nro_prestamo = data[1];
              var pdetalle_nro_cuota = data[2];
              var pdetalle_monto_cuota = data[4];
              var pdetalle_saldo_cuota = parseFloat(String(data[5]).replace(/[^0-9.-]+/g, ''));
              var simbolo_moneda = data[7];

              // Asignar los valores a los campos del modal de abono
              $("#nro_cuota_abono").val(pdetalle_nro_cuota);
              $("#monto_cuota_original").val(simbolo_moneda + ' ' + pdetalle_monto_cuota);
              $("#saldo_pendiente_abono").val(simbolo_moneda + ' ' + pdetalle_saldo_cuota.toFixed(2));
              $("#monto_a_abonar").val(""); // Limpiar el campo de monto a abonar
              $("#tipo_abono").val("normal"); // Resetear tipo de abono

              // Guardar los datos en el modal (aún útil para btnRegistrarAbono)
              $("#modal_abonar_cuota").data('nro_prestamo', nro_prestamo);
              $("#modal_abonar_cuota").data('pdetalle_nro_cuota', pdetalle_nro_cuota);
              $("#modal_abonar_cuota").data('pdetalle_saldo_cuota', pdetalle_saldo_cuota); // Guardar el saldo actual para validación
              $("#modal_abonar_cuota").data('pdetalle_monto_cuota', pdetalle_monto_cuota); // Guardar para rellenar
              $("#modal_abonar_cuota").data('simbolo_moneda', simbolo_moneda); // Guardar símbolo de moneda

              // Abrir el modal explícitamente
              $("#modal_abonar_cuota").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_abonar_cuota").modal('show'); // Abrir el modal de abono
          });

          /* ======================================================================================
            BOTONES DE ABONO RÁPIDO
          =========================================================================================*/
          $("#btnAbonoCompleto").on('click', function() {
              var saldo_pendiente = parseFloat($("#modal_abonar_cuota").data('pdetalle_saldo_cuota'));
              $("#monto_a_abonar").val(saldo_pendiente.toFixed(2));
          });

          $("#btnAbonoMitad").on('click', function() {
              var saldo_pendiente = parseFloat($("#modal_abonar_cuota").data('pdetalle_saldo_cuota'));
              var mitad = saldo_pendiente * 0.5;
              $("#monto_a_abonar").val(mitad.toFixed(2));
          });

          $("#btnAbonoMinimo").on('click', function() {
              var saldo_pendiente = parseFloat($("#modal_abonar_cuota").data('pdetalle_saldo_cuota'));
              var minimo = saldo_pendiente * 0.25;
              $("#monto_a_abonar").val(minimo.toFixed(2));
          });

          /* ======================================================================================
            REGISTRAR ABONO DE CUOTA
          =========================================================================================*/
          $("#btnRegistrarAbono").on('click', function() {
              var nro_prestamo = $("#modal_abonar_cuota").data('nro_prestamo');
              var pdetalle_nro_cuota = $("#modal_abonar_cuota").data('pdetalle_nro_cuota');
              var pdetalle_saldo_cuota_actual = parseFloat($("#modal_abonar_cuota").data('pdetalle_saldo_cuota'));
              var monto_a_abonar = parseFloat($("#monto_a_abonar").val());
              var tipo_abono = $("#tipo_abono").val();
              var simbolo_moneda = $("#modal_abonar_cuota").data('simbolo_moneda');

              if (isNaN(monto_a_abonar) || monto_a_abonar <= 0) {
                  Toast.fire({
                      icon: 'error',
                      title: 'Ingrese un monto a abonar válido y mayor a cero.'
                  });
                  return;
              }

              // Validación diferente según el tipo de abono
              if (tipo_abono === "normal" && monto_a_abonar > pdetalle_saldo_cuota_actual) {
                  Toast.fire({
                      icon: 'error',
                      title: 'Para abono normal, el monto no puede ser mayor que el saldo pendiente de la cuota (' + simbolo_moneda + ' ' + pdetalle_saldo_cuota_actual.toFixed(2) + ').'
                  });
                  return;
              }

              var mensaje_confirmacion = 'Monto a abonar: <b>' + simbolo_moneda + ' ' + monto_a_abonar.toFixed(2) + '</b><br>';
              mensaje_confirmacion += 'Tipo de abono: <b>' + (tipo_abono === 'extraordinario' ? 'Extraordinario' : 'Normal') + '</b>';
              
              if (tipo_abono === 'extraordinario' && monto_a_abonar > pdetalle_saldo_cuota_actual) {
                  var excedente = monto_a_abonar - pdetalle_saldo_cuota_actual;
                  mensaje_confirmacion += '<br><small class="text-info"><i class="fas fa-info-circle"></i> Excedente de ' + simbolo_moneda + ' ' + excedente.toFixed(2) + ' se aplicará a las siguientes cuotas.</small>';
              }

              Swal.fire({
                  title: '¿Desea registrar este abono para la cuota Nro "' + pdetalle_nro_cuota + '"?',
                  html: mensaje_confirmacion,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si, Abonar',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if (result.isConfirmed) {
                      var datos = new FormData();
                      datos.append("accion", 6); // Nueva acción para registrar abono
                      datos.append("nro_prestamo", nro_prestamo);
                      datos.append("pdetalle_nro_cuota", pdetalle_nro_cuota);
                      datos.append("monto_a_abonar", monto_a_abonar);
                      datos.append("tipo_abono", tipo_abono);

                      $.ajax({
                          url: "ajax/admin_prestamos_ajax.php",
                          method: "POST",
                          data: datos,
                          cache: false,
                          contentType: false,
                          processData: false,
                          dataType: 'json',
                          success: function(respuesta) {
                              console.log("Respuesta del servidor:", respuesta);
                              
                              // Manejo de respuesta para abono normal
                              if (respuesta == "ok") {
                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Abono registrado correctamente.'
                                  });
                                  $("#modal_abonar_cuota").modal('hide');
                                  prestamo_detalle_dt.ajax.reload();
                                  CargarCantCuotasPagadas();
                                  tbl_ls_prestamos.ajax.reload();
                              }
                              // Manejo de respuesta para abono extraordinario
                              else if (typeof respuesta === 'object' && respuesta.status === 'ok') {
                                  var mensaje = 'Abono extraordinario registrado correctamente.<br>';
                                  mensaje += '<strong>Cuotas afectadas:</strong><br>';
                                  
                                  respuesta.cuotas_afectadas.forEach(function(cuota) {
                                      mensaje += '• Cuota ' + cuota.cuota + ': ' + simbolo_moneda + ' ' + cuota.monto_aplicado.toFixed(2);
                                      mensaje += ' (' + (cuota.estado === 'pagada' ? 'Pagada completamente' : 'Pago parcial') + ')<br>';
                                  });
                                  
                                  if (respuesta.monto_sobrante > 0) {
                                      mensaje += '<br><span class="text-warning">⚠️ Sobrante de ' + simbolo_moneda + ' ' + respuesta.monto_sobrante.toFixed(2) + ' (no hay más cuotas pendientes)</span>';
                                  }
                                  
                                  Swal.fire({
                                      icon: 'success',
                                      title: '¡Abono Extraordinario Exitoso!',
                                      html: mensaje,
                                      confirmButtonText: 'Entendido'
                                  });
                                  
                                  $("#modal_abonar_cuota").modal('hide');
                                  prestamo_detalle_dt.ajax.reload();
                                  CargarCantCuotasPagadas();
                                  tbl_ls_prestamos.ajax.reload();
                              }
                              else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al registrar el abono: ' + (respuesta.message || respuesta)
                                  });
                              }
                          },
                          error: function(jqXHR, textStatus, errorThrown) {
                              console.log("Error en la solicitud AJAX:", textStatus, errorThrown);
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Error de comunicación con el servidor.'
                              });
                          }
                      });
                  }
              })
          })

      }) // FIN DOCUMENT READY



      /*===================================================================*/
      //FUNCION PARA TRAER EL DETALLE PARA PAGAR UNA CUOTA
      /*===================================================================*/
      function Traer_Detalle(nro_prestamo) {
          console.log("Traer_Detalle llamado con nro_prestamo:", nro_prestamo);
          
          // Destruir DataTable existente si existe
          if ($.fn.DataTable.isDataTable('#prestamo_detalle')) {
              console.log("Destruyendo DataTable existente");
              $('#prestamo_detalle').DataTable().destroy();
          }
          
          prestamo_detalle_dt = $("#prestamo_detalle").DataTable({
              destroy: true,
              dom: 'tp',
              ajax: {
                  url: "ajax/admin_prestamos_ajax.php",
                  type: "POST",
                  data: {
                      'accion': 2,
                      'nro_prestamo': nro_prestamo
                  },
                  dataSrc: ""
              },
              columnDefs: [
                  { targets: 0, visible: false },
                  { targets: 4, render: function(data, type, row) { return row[7] + ' ' + data; } },
                  { targets: 5, render: function(data, type, row) { return row[7] + ' ' + data; } },
                  { targets: 6, render: function(data) {
                      if (data == 'pagada') return "<span class='badge badge-success'>pagada</span>";
                      if (data == 'parcialmente_pagada') return "<span class='badge badge-info'>parcialmente pagada</span>";
                      return "<span class='badge badge-danger'>pendiente</span>";
                  }},
                  { targets: 7, sortable: false, render: function(data, type, row) {
                      if (row[6] == 'pagada') {
                          return "<center>" +
                              "<span class='text-secondary px-1 disabled'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                              "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                              "</span> " +
                              "<span class='btnImprimirRecibo text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Ticket'> " +
                              "<i class='far fa-file-alt fs-6'> </i> " +
                              "</span>" +
                              "<span class='EnviarCorreoCuotaP text-warning px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Enviar Recibo por Correo'> " +
                              "<i class='fas fa-envelope fs-6'> </i> " +
                              "</span>" +
                              "</center>";
                      } else {
                          return "<center>" +
                              "<span class='btnAbonarCuota text-success px-1' style='cursor:pointer;' data-bs-toggle='modal' data-bs-target='#modal_abonar_cuota' title='Abonar Cuota'> " +
                              "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                              "</span> " +
                              "<span class=' text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
                              "<i class='far fa-file-alt fs-6'> </i> " +
                              "</span>" +
                              "</center>";
                      }
                  }}
              ],
              language: idioma_espanol,
              select: true
          });
      }


      /*===================================================================*/
      //FUNCION PARA CARGAR CANTIDAD DE CUOTAS PAGADAS
      /*===================================================================*/
      function CargarCantCuotasPagadas() {
          var nro_prestamo = $('#text_nro_prestamo_d').val();
          //console.log(nro_prestamo);

          $.ajax({
              async: false,
              url: "ajax/admin_prestamos_ajax.php",
              method: "POST",
              data: {
                  'accion': 4,
                  'nro_prestamo': nro_prestamo
              },
              dataType: 'json',
              success: function(respuesta) {
                  // console.log(respuesta);

                  pdetalle_estado_cuota = respuesta["pdetalle_estado_cuota"];
                  pendiente = respuesta["pendiente"];
                  $("#text_cuotas_pagadas__d").val(pdetalle_estado_cuota);

                  if (pendiente == 0) {

                      $("#btnLiquidar").attr('hidden', true); // ocultar
                  } else {
                      $("#btnLiquidar").attr('hidden', false); //mostrando
                  }
              }
          });
      }


      /*===================================================================*/
      //FUNCION PARA LIQUIDAR TOTALMENTE EL PRESTAMO
      /*===================================================================*/
      function LiquidarCuotas() {
          var count = 0;
          var nro_prestamo = $("#text_nro_prestamo_d").val();

          var arreglo_cuota = new Array();

          $("#prestamo_detalle tbody tr").each(function(i, e) {
              arreglo_cuota.push($(this).find('td').eq(1).text());
              count++;
          })

          var pdetalle_nro_cuota = arreglo_cuota.toString();
          console.log(pdetalle_nro_cuota);

          Swal.fire({
              title: 'Esta seguro que desea liquidar totalmente el prestamo"' + nro_prestamo + '" ?',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
              confirmButtonText: 'Si, Liquidar',
              cancelButtonText: 'Cancelar',
          }).then((result) => {

              if (result.isConfirmed) {
                  $.ajax({
                      url: "ajax/admin_prestamos_ajax.php",
                      method: "POST",
                      data: {
                          accion: 5,
                          nro_prestamo: nro_prestamo,
                          pdetalle_nro_cuota: pdetalle_nro_cuota
                      },
                      async: true,
                      //   cache: false,
                      //   contentType: false,
                      //   processData: false,
                      dataType: 'json',
                      success: function(respuesta) {

                          console.log(respuesta);


                          if (respuesta == "ok") {

                              Toast.fire({
                                  icon: 'success',
                                  title: 'Prestamo Liquidado Correctamente '
                                  // title: titulo_msj
                              });
                              CargarCantCuotasPagadas();

                              prestamo_detalle_dt.ajax.reload(); //recargamos el datatable
                              tbl_ls_prestamos.ajax.reload();

                          } else {
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Error al Liquidar Prestamo'
                              });
                          }



                      }
                  });


              }


          })

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