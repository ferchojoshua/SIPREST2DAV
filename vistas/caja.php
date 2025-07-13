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
                      <div class="card-header bg-gradient-info">
                          <h3 class="card-title">Aperturar de Caja</h3>
                          <button class="btn btn-info btn-sm float-right" id="abrirmodal_caja"><i class="fas fa-plus"></i>
                              Aperturar</button>
                      </div>
                      <div class=" card-body">
                          <!-- <div class="row align-items-end">
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
                          </div><br> -->
                          <div class="col-12 table-responsive">
                              <table id="tbl_caja" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-gradient-info text-white">
                                      <tr>
                                          <th>Id caja</th>
                                          <th>Monto Ini.</th>
                                          <th>Ingreso</th>
                                          <th>Egreso</th>
                                          <th>Monto Prestamo</th>
                                          <th>F. Apertura</th>
                                          <th>F. Cierre</th>
                                          <th>Cant. Prest</th>
                                          <th>Monto Total</th>
                                          <th>Estado</th>
                                          <th class="text-center">Opciones</th>
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

  <!-- Modal abrir caja -->
  <div class="modal fade" id="modal_abrir_caja" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cliente">Registro Apertura de Caja</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_cliente" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Descripcion
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_descripcion" name="text_descripcion" required disabled>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto
                                  </label>
                                  <input type="number" class=" form-control form-control-sm" id="text_monto_ini" name="text_monto_ini" placeholder="Monto Apertura" required>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>

                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_cliente">Cerrar</button>
                  <button class="btn btn-primary btn-sm btn-activa" id="btnregistrar_caja">Registrar</button>
                  <!-- <div class="form-group m-0"> -->
                  <!-- <a class="btn btn-primary btn-sm"  id=" btnregistrar_caja">Registrar</a> -->
                  <!-- </div> -->

              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <!-- Modal CERRA caja -->
  <div class="modal fade" id="modal_cerrar_caja" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cerrar">Cerrar Caja</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_caja_cierre" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Apertura
                                  </label>
                                  <input type="text" id="caja_id" hidden>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_aper" name="text_monto_aper" disabled>

                              </div>
                          </div>

                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Prestamo
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_prestamo" name="text_monto_prestamo" placeholder="Monto Prestamo" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cantidad de Prestamo
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cant_prestamo" name="text_cant_prestamo" placeholder="Cant.Prestamo" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Ingreso
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_ingreso" placeholder="Monto Ingreso" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cant. Ingresos
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cant_ingreso" placeholder="Cant. Ingresos" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Egreso
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_egreso" placeholder="Monto Egreso" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cant. Egresos
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cant_egreso" placeholder="Cant. Egresos" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> <b>Monto Total</b>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_total" placeholder="Monto Total" required disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <!-- <div class="col-lg-6">
                              <div class="form-group mb-2">
                              <label for="">&nbsp;</label><br>
                            <button class="btn btn-success btn-sm " onmousemove="Sumar();" onclick="Sumar();"><i
                                    class="fas fa-plus"></i> Calcular</button>

                              </div>
                          </div> -->


                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Interes de Prestamo
                                  </label>

                                  <input type="text" class=" form-control form-control-sm" id="text_interes" placeholder="Monto Total con interes" required disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>

                          <!-- <fieldset class="border p-2" style=" border: 1px solid #337ab7 !important;">
                              <legend class="float-none w-auto p-2" style=" font-size: 1.2em !important; font-weight: bold !important; ">Datos del Prestamo</legend>
                              <div class="col-lg-6">
                                  <div class="form-group mb-2">
                                      <label for="" class="">
                                          <span class="small"> Monto Total
                                      </label>
                                      <input type="text" class=" form-control form-control-sm" id="text_cant_egreso" placeholder="Monto Total" required>
                                      <div class="invalid-feedback">Debe ingresar un monto</div>

                                  </div>
                              </div>
                          </fieldset> -->




                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_caja_cierre">Cerrar</button>
                  <button class="btn btn-primary btn-sm btn-activa" id="btnCerrar_caja">Cerrar Caja</button>
                  <!-- <a class="btn btn-primary btn-sm"  id=" btnregistrar_caja">Cerrar Caja</a> -->
                  <!-- <div class="form-group m-0"> -->
                  <!-- <a class="btn btn-primary btn-sm"  id=" btnregistrar_caja">Registrar</a> -->
                  <!-- </div> -->

              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->


  <!-- Modal VER PRESTAMOS Y MOVIMIENTOS DE caja -->
  <div class="modal fade" id="modal_registros_caja" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cerrar">Ver Registros de la Caja</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_caja_cierre" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="col-12 table-responsive">
                      <table id="tbl_resgitros_caja" class="table display table-hover text-nowrap compact  w-100  rounded">
                          <thead class="bg-gradient-info text-white">
                              <tr>
                                  <th>Nro Prestamo</th>
                                  <th>id cli</th>
                                  <th>Cliente</th>
                                  <th>Monto</th>
                                  <th>Interes</th>
                                  <th>Monto Total</th>
                                  <th>Fecha</th>
                                  <th>caja id</th>
                                  <th>Estado</th>
                              </tr>
                          </thead>
                          <tbody class="small text left">

                          </tbody>
                          <tfoot>
                              <tr>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th>Total:</th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                              </tr>
                          </tfoot>
                      </table>

                  </div>
                  <br>

                  <div class="col-12 table-responsive">
                      <table id="tbl_resgitros_movi" class="table display table-hover text-nowrap compact  w-100  rounded">
                          <thead class="bg-gradient-info text-white">
                              <tr>
                                  <th>Tipo</th>
                                  <th>Descripcion</th>
                                  <th>Monto</th>
                                  <th>Fecha</th>
                                  <th>caja id</th>
                              </tr>
                          </thead>
                          <tbody class="small text left">

                          </tbody>
                          
                      </table>

                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_caja_cierre">Cerrar</button>
                  <!-- <button class="btn btn-primary btn-sm btn-activa" id="btnCerrar_caja">Cerrar Caja</button> -->


              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <script>
      var accion;
      var tbl_caja, tbl_resgitros_caja, tbl_resgitros_movi;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });
      $(document).ready(function() {

          $('.js-example-basic-single').select2();

          /*===================================================================*/
          // FILTRAR CAJA POR CLIENTE AL CAMBIAR SELECTOR O HACER CLIC EN BUSCAR
          /*===================================================================*/
          $("#btnFiltrar").on('click', function() {
              if ($("#select_clientes").val() == '') {
                  Toast.fire({
                      icon: 'error',
                      title: ' Debe Seleccionar un cliente'
                  })
                  $("#select_clientes").focus();

              } else {
                  tbl_caja.ajax.reload(); // Recargar la tabla de caja
              }
          });

          $('#select_clientes').on('change', function() {
              tbl_caja.ajax.reload(); // Recargar la tabla de caja al cambiar el cliente
          });

          /*===================================================================*/
          // INICIAMOS EL DATATABLE
          /*===================================================================*/
          tbl_caja = $("#tbl_caja").DataTable({
              responsive: true,

              dom: 'Bfrtip',
              select: true,
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Arqueo de Caja',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
                      },
                      "download": 'open'
                  },

                  'pageLength',
              ],
              ajax: {
                  url: "ajax/caja_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1

                  }, //LISTAR 
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  },
                  {
                      targets: 9,
                      //sortable: false,
                      createdCell: function(td, cellData, rowData, row, col) {

                          if (rowData[9] == 'VIGENTE') {
                              $(td).html("<span class='badge badge-success'>VIGENTE</span>")
                          } else {
                              $(td).html("<span class='badge badge-danger'>CERRADO</span>")
                          }

                      }
                  }, {
                      targets: 10, //columna 2
                      sortable: false, //no ordene
                      render: function(td, cellData, rowData, row, col) {

                          if (rowData[9] == 'VIGENTE') {
                              return "<center>" +
                                  "<span class='CerrarCaja  text-info px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cerrar Caja '> " +
                                  "<i class='fas fa-lock fs-6'></i> " +
                                  "</span> " +
                                  "<span class='text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fa fa-print fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Registros de Caja ' > " +
                                  "<i class='fa fa-eye fs-6'> </i> " +
                                  "</span>" +

                                  "</center>"
                          } else { //pendiente
                              return "<center>" +
                                  "<span class='text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-lock fs-6'></i> " +
                                  "</span> " +
                                  "<span class='ImprimirCaja text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Cierre de Caja'> " +
                                  "<i class='fa fa-print fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='EnviarCorreo text-warning px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Enviar por Correo'> " +
                                  "<i class='fas fa-envelope fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Registros de Caja ' > " +
                                  "<i class='fa fa-eye fs-6'> </i> " +
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
              // Callback para aplicar estilos a las cabeceras después de la inicialización de DataTables
              "initComplete": function(settings, json) {
                  $('#tbl_caja thead th').css({
                      'background-color': '#17a2b8',
                      'color': '#ffffff',
                      'background-image': 'none' // Eliminar iconos de ordenamiento si los hay
                  });
              }

          });

          /*===================================================================*/
          // ABRIR MODAL caja
          /*===================================================================*/
          $("#abrirmodal_caja").on('click', function() {
              AbrirModalAbrirCaja();
              //AbrirModalAbrirCerrarCaja()

          })



          /*===================================================================*/
          // ABRIR MODAL CERRAR CAJA
          /*===================================================================*/
          $('#tbl_caja').on('click', '.CerrarCaja', function() { //class foto tiene que ir en el boton
              accion = 4;
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data();
              } else {
                  var data = tbl_caja.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }
              $("#caja_id").val(data[0]);

              AbrirModalAbrirCerrarCaja();

              CargarDatosCierreCaja();
              Sumar();
          });

          /*===================================================================*/
          // ABRIR MODAL CERRAR CAJA
          /*===================================================================*/
          $('#tbl_caja').on('click', '.btnVerRegistrosC', function() { //class foto tiene que ir en el boton
              //accion = 7;
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data();
              } else {
                  var data = tbl_caja.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }


              var caja_id = data[0]
             // console.log(caja_id);

              AbrirModalVerRegistrosPorCaja();
              Traer_RegistrosporIDCaja(caja_id)
              Traer_MovimientosporIDCaja(caja_id);

          });



          /*===================================================================*/
          //EVENTO QUE GUARDAAR APERTURA CAJA
          /*===================================================================*/

          document.getElementById("btnregistrar_caja").addEventListener("click", function() {


              var monto = $('#text_monto_ini').val();


              if (monto == "") {
                  Toast.fire({
                      icon: 'warning',
                      title: 'Digitar un monto para aperturar la caja'

                  });
                  //  $('#btnregistrar_caja').show();
                  // document.getElementsByClassName("btn-activa")[0].focus();

              } else {
                  // console.log("Listo para registrar el producto")
                  Swal.fire({
                      title: 'Esta seguro de Apertura Caja',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                      confirmButtonText: 'Si',
                      cancelButtonText: 'Cancelar',
                  }).then((result) => {

                      if (result.isConfirmed) {

                          var datos = new FormData();

                          datos.append("accion", accion);
                          datos.append("caja_descripcion", $("#text_descripcion").val());
                          datos.append("caja_monto_inicial", $("#text_monto_ini").val());

                          $.ajax({
                              url: "ajax/caja_ajax.php",
                              method: "POST",
                              data: datos, //enviamos lo de la variable datos
                              cache: false,
                              contentType: false,
                              processData: false,
                              dataType: 'json',
                              success: function(respuesta) {
                                  // console.log(respuesta);

                                  if (respuesta > 0) {
                                      if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                          Toast.fire({
                                              icon: 'success',
                                              title: 'Caja Aperturada'
                                          });

                                          tbl_caja.ajax.reload(); //recargamos el datatable
                                          $("#modal_abrir_caja").modal('hide');
                                          $("#text_monto_ini").val("");

                                      } else {
                                          Toast.fire({
                                              icon: 'warning',
                                              title: 'Ya tienes una caja Aperturada'
                                          });
                                          $("#text_monto_ini").val("");
                                      }

                                  } else {
                                      Toast.fire({
                                          icon: 'error',
                                          title: 'No se pudo Aperturar la Caja'

                                      });
                                  }


                              }
                          });

                      }
                  })
              }


          });



          /*===================================================================*/
          //EVENTO QUE CIERRA LA CAJA
          /*===================================================================*/
          $("#btnCerrar_caja").on('click', function() {

              Swal.fire({
                  title: '¿Está seguro de Cerrar Caja?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Sí',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {

                  if (result.isConfirmed) {

                      var caja_monto_ingreso = $("#text_monto_ingreso").val();
                      var caja_prestamo = $("#text_monto_prestamo").val();
                      var caja__monto_egreso = $("#text_monto_egreso").val();
                      var caja_monto_total = $("#text_monto_total").val();
                      var caja_count_prestamo = $("#text_cant_prestamo").val();
                      var caja_count_ingreso = $("#text_cant_ingreso").val();
                      var caja_count_egreso = $("#text_cant_egreso").val();
                      var caja_interes = $("#text_interes").val();

                      var datos = new FormData();
                      datos.append("accion", 4); //PARA REGISTRAR EL  CERRAR LA CAJA
                      datos.append("caja_monto_ingreso", caja_monto_ingreso);
                      datos.append("caja_prestamo", caja_prestamo);
                      datos.append("caja__monto_egreso", caja__monto_egreso);
                      datos.append("caja_monto_total", caja_monto_total);
                      datos.append("caja_count_prestamo", caja_count_prestamo);
                      datos.append("caja_count_ingreso", caja_count_ingreso);
                      datos.append("caja_count_egreso", caja_count_egreso);
                      datos.append("caja_interes", caja_interes);

                      $.ajax({
                          url: "ajax/caja_ajax.php",
                          method: "POST",
                          data: datos,
                          cache: false,
                          contentType: false,
                          processData: false,
                          dataType: 'json',
                          success: function(respuesta) {

                              if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Caja Cerrada Correctamente'
                                  });
                                  $("#modal_cerrar_caja").modal('hide');
                                  tbl_caja.ajax.reload(); //recargamos la tabla
                                  $("#caja_id").val("");
                                  $("#text_monto_ingreso").val("");
                                  $("#text_monto_prestamo").val("");
                                  $("#text_monto_egreso").val("");
                                  $("#text_monto_total").val("");
                                  $("#text_cant_prestamo").val("");
                                  $("#text_cant_ingreso").val("");
                                  $("#text_cant_egreso").val("");
                                  $("#text_interes").val("");
                              } else if (respuesta == 2) {
                                  Toast.fire({
                                      icon: 'warning',
                                      title: 'Tienes Préstamos Aprobados que no han Finalizado'
                                  });
                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al cerrar la caja'
                                  });
                              }

                          }
                      });

                  }
              })

          })



          /********************************************************************
          		IMPRIMIR COMPROBANTE
          ********************************************************************/
          $('#tbl_caja').on('click', '.ImprimirCaja', function() { //class foto tiene que ir en el boton
              var data = tbl_caja.row($(this).parents('tr')).data(); //tama単o de escritorio
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data(); //para celular y usas el responsive datatable

              }

              window.open("MPDF/reporte_arqueocaja_mejorado.php?codigo=" + parseInt(data[0]) + "#zoom=120", "Arqueo de Caja", "scrollbards=NO");





          });



          /********************************************************************
          		ENVIAR POR CORREO
          ********************************************************************/
          $('#tbl_caja').on('click', '.EnviarCorreo', function() { //class foto tiene que ir en el boton
              var data = tbl_caja.row($(this).parents('tr')).data(); //tama単o de escritorio
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data(); //para celular y usas el responsive datatable

              }

              Swal.fire({
                  title: 'Esta seguro de Enviar el correo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.open("MPDF/reporte_arqueocaja_Email.php?codigo=" + parseInt(data[0]) + "#zoom=120", "Arqueo de Caja", "scrollbards=NO");
                      Toast.fire({
                          icon: 'success',
                          title: 'Correo enviado correctamente'
                      });


                  }

              })

          });


          /* ======================================================================================
           EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
          =========================================================================================*/
          $("#btncerrarmodal_caja_cierre, #btncerrar_caja_cierre").on('click', function() {
              $("#text_monto_aper").val("");
              $("#text_monto_prestamo").val("");
              $("#text_cant_prestamo").val("");
              $("#text_monto_ingreso").val("");
              $("#text_cant_ingreso").val("");
              $("#text_monto_egreso").val("");
              $("#text_cant_egreso").val("");
              $("#text_monto_total").val("");
              $("#text_interes").val("");
          })


          /*===================================================================*/
          //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
          /*===================================================================*/
          document.getElementById("btncerrar_caja_cierre").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })
          document.getElementById("btncerrarmodal_caja_cierre").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })





      }) // FIN DOCUMENT READY



      // FUNCIONES

      /********************************************************************
            ABRIR MODAL abrir caja
      ********************************************************************/
      function AbrirModalAbrirCaja() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_abrir_caja").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_abrir_caja").modal('show'); //abrimos el modal

          $("#text_descripcion").val('Apertura de Caja');
          $("#text_monto_ini").focus();
          accion = 2;
      }


      /********************************************************************
            ABRIR MODAL  VER PRESTAMOS POR CAJA 
      ********************************************************************/
      function AbrirModalVerRegistrosPorCaja() {
          $("#modal_registros_caja").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_registros_caja").modal('show'); //abrimos el modal

          $("#text_descripcion").val('Ver Registros de Caja');

          // accion = 2;

      }


      /********************************************************************
            ABRIR MODAL CERRAR CAJA 
      ********************************************************************/
      function AbrirModalAbrirCerrarCaja() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_cerrar_caja").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_cerrar_caja").modal('show'); //abrimos el modal
          //suma();

      }


      /*===================================================================*/
      //FUNCION PARA CARGAR DATOS CANTIDADES
      /*===================================================================*/
      function CargarDatosCierreCaja() {
          $.ajax({
              async: false,
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: {
                  'accion': 3
              },
              dataType: 'json',
              success: function(respuesta) {

                  // console.log(respuesta);


                  monto_inicial_caja = respuesta["monto_inicial_caja"];
                   suma_prestamo_capital = respuesta["suma_prestamo_capital"]; 
                  suma_total = respuesta["suma_total"];
                  cant_prestamo = respuesta["cant_prestamo"];
                  cant_ingresos = respuesta["cant_ingresos"];
                  suma_ingresos = respuesta["suma_ingresos"];
                  suma_egresos = respuesta["suma_egresos"];
                  cant_egresos = respuesta["cant_egresos"];
                  suma_prestamo_interes = respuesta["suma_prestamo_interes"];

                  $("#text_monto_aper").val(monto_inicial_caja);
                  $("#text_monto_prestamo").val(suma_prestamo_capital); //SETEAMOS EN LOS TEXTBOX
                  //$("#text_monto_prestamo").val(suma_total);
                  $("#text_cant_prestamo").val(cant_prestamo);
                  $("#text_monto_ingreso").val(suma_ingresos);
                  $("#text_cant_ingreso").val(cant_ingresos);
                  $("#text_monto_egreso").val(suma_egresos);
                  $("#text_cant_egreso").val(cant_egresos);
                  $("#text_interes").val(suma_prestamo_interes);


              }
          });
      }



      function Sumar() {
          //var suma = 0;
          monto_inicial_caja = $("#text_monto_aper").val();
          suma_prestamo_capital = $("#text_monto_prestamo").val();
          suma_ingresos = $("#text_monto_ingreso").val();
          suma_egresos = $("#text_monto_egreso").val();
          interes = $("#text_interes").val();

          // suma = monto_inicial_caja + suma_prestamo_capital + suma_ingresos;
          //console.log(suma);
          ope = (parseFloat(monto_inicial_caja) + parseFloat(interes) + parseFloat(suma_ingresos));

          suma = (parseFloat(ope - suma_egresos).toFixed(2));



          $("#text_monto_total").val(suma);

      }

      /*===================================================================*/
      //TRAER TODOS LOS PRESTAMOS FINALIZADOS DE LA CAJA ACTUAL
      /*===================================================================*/
      function Traer_RegistrosporIDCaja(caja_id) {
          tbl_resgitros_caja = $("#tbl_resgitros_caja").DataTable({
              responsive: true,
              destroy: true,
              searching: false,
              dom: 'tp',
              ajax: {
                  url: "ajax/caja_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 7,
                      'caja_id': caja_id
                  }, //LISTAR 
              },
              columnDefs: [
                 {
                      targets: 1,
                      visible: false

                  },{
                      targets: 7,
                      visible: false

                  },
                  {
                  targets: 8,
                  //sortable: false,
                  createdCell: function(td, cellData, rowData, row, col) {

                      if (rowData[8] == 'aprobado') {
                          $(td).html("<span class='badge badge-success'>aprobado</span>")
                      } else if (rowData[8] == 'finalizado') {
                          $(td).html("<span class='badge badge-info'>finalizado</span>")
                      }else {
                          $(td).html("<span class='badge badge-warning'>pendiente</span>")
                      }

                  }
              }

              ],
              "footerCallback": function(row, data, start, end, display) {
                  var api = this.api(),
                      data;
                  var intval = function(i) {
                      return typeof i === 'string' ?
                          i.replace(/[\$,]/g, '') * 1 :
                          typeof i === 'number' ?
                          i : 0;
                  };
                  total = api
                      .column(5)
                      .data()
                      .reduce(function(a, b) {
                          return intval(a) + intval(b);
                      }, 0);
                  pageTotal = api
                      .column(5, {
                          page: 'current'
                      })
                      .data()
                      .reduce(function(a, b) {
                          return intval(a) + intval(b);
                      }, 0);
                  $(api.column(5).footer()).html(
                    '' + pageTotal 
                    //   '' + pageTotal + ' ( ' + total + ' total)'
                  );

              },
              

              "language": idioma_espanol,
              select: true
          });
      }


      /*===================================================================*/
      //TRAER TODOS LOS MOVIMIENTOS DE LA CAJA ACTUAL
      /*===================================================================*/
      function Traer_MovimientosporIDCaja(caja_id) {
        tbl_resgitros_movi = $("#tbl_resgitros_movi").DataTable({
              responsive: true,
              destroy: true,
              searching: false,
              dom: 'tp',
              ajax: {
                  url: "ajax/caja_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 8,
                      'caja_id': caja_id
                  }, 
              },
              columnDefs: [
                 {
                      targets: 4,
                      visible: false

                  }
                  

              ],
              
             

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