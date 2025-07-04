  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0">Moneda</h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">moneda</li>
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
                          <h3 class="card-title">Listado de Moneda</h3>
                          <button class="btn btn-info btn-sm float-right" id="abrirmodal_moneda"><i class="fas fa-plus"></i>
                              Nuevo</button>
                      </div>
                      <div class=" card-body">
                          <div class="table-responsive">
                              <table id="tbl_monedas" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-info text-left">
                                      <tr>
                                          <th>Id</th>
                                          <th>Nombre M</th>
                                          <th>Abreviatura</th>
                                          <th>Simbolo</th>
                                          <th>Descripcion</th>
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

  <!-- MODAL REGISTRAR CLIENTES-->
  <div class="modal fade" id="modal_registro_moneda" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_moneda">Registro de Usuarios</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_moneda" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <input type="text" id="moneda_id" hidden>
                                      <span class="small"> Nombre Moneda</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_nombre" name="text_nombre" placeholder="Nombre Moneda" required>
                                  <div class="invalid-feedback">Debe ingresar un nonbres de la moneda</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Abreviatura</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_abreviatura" name="text_abreviatura" placeholder="Abreviatura" required>
                                  <div class="invalid-feedback">Debe ingresar la abreviatura de la moneda</div>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Simbolo</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_simbolo" name="text_simbolo" placeholder="Simbolo de la moneda" required>
                                  <div class="invalid-feedback">Debe ingresar el Simbolo de la moneda </div>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2" id="iptclave">
                                  <label for="ipclave" class="">
                                      <span class="small"> Descripcion</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_descripcion" name="text_descripcion" placeholder="Descripcion">


                              </div>
                          </div>

                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_moneda">Cerrar</button>
                  <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_moneda">Registrar</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->


  <script>
      var accion;
      var tbl_monedas, titulo_modal;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });

      $(document).ready(function() {

          /***************************************************************************
           * INICIAR DATATABLE MONEDAS
           ******************************************************************************/
          var tbl_monedas = $("#tbl_monedas").DataTable({

              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte Monedas',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4]
                      },
                      "download": 'open'
                  },
                  'pageLength',
              ],
              ajax: {
                  url: "ajax/moneda_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1
                  }, //LISTAR 
              },
              columnDefs: [{
                  targets: 0,
                  visible: false

              }, {
                  targets: 5, //columna 2
                  sortable: false, //no ordene
                  render: function(data, type, full, meta) {
                      return "<center>" +
                          "<span class='btnEditarMoneda  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Moneda '> " +
                          "<i class='fas fa-pencil-alt fs-6'></i> " +
                          "</span> " +
                          "<span class='btnEliminarMoneda  text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Moneda '> " +
                          "<i class='fas fa-trash fs-6'> </i> " +
                          "</span>" +
                          "</center>"
                  }
              }],
              "order": [
                  [0, 'desc']
              ],
              lengthMenu: [0, 5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              select: true
          });

          /*===================================================================*/
          //EVENTO PARA ABRIR EL MODAL DE REGISTRAR AL DAR CLICK EN BOTON NUEVO
          /*===================================================================*/
          $("#abrirmodal_moneda").on('click', function() {
              AbrirModalRegistroMoneda();
          })



          /*===================================================================*/
          //EVENTO QUE GUARDA Y ACTUALIZA LOS DATOS DEL MODULO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
          /*===================================================================*/
          document.getElementById("btnregistrar_moneda").addEventListener("click", function() {

              // Get the forms we want to add validation styles to
              var forms = document.getElementsByClassName('needs-validation');
              // Loop over them and prevent submission
              var validation = Array.prototype.filter.call(forms, function(form) {

                  //si se ingresan todos los datos 
                  if (form.checkValidity() === true) {

                      Swal.fire({
                          title: 'Esta seguro de ' + titulo_modal + ' la Moneda?',
                          icon: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Si',
                          cancelButtonText: 'Cancelar',
                      }).then((result) => {

                          if (result.isConfirmed) {

                              var datos = new FormData();

                              datos.append("accion", accion);
                              datos.append("moneda_id", $("#moneda_id").val()); //id
                              datos.append("moneda_nombre", $("#text_nombre").val()); //modulo
                              datos.append("moneda_abrevia", $("#text_abreviatura").val());
                              datos.append("moneda_simbolo", $("#text_simbolo").val());
                              datos.append("moneda_Descripcion", $("#text_descripcion").val());

                              if (accion == 2) {
                                  var titulo_msj = "La moneda  se registro correctamente"

                              }

                              if (accion == 3) {
                                  var titulo_msj = "La moneda  se actualizo correctamente"

                              }
                              $.ajax({
                                  url: "ajax/moneda_ajax.php",
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
                                              // title: 'La moneda se registro de forma correcta'
                                              title: titulo_msj
                                          });

                                          tbl_monedas.ajax.reload(); //recargamos el datatable 

                                          $("#modal_registro_moneda").modal('hide');

                                          $("#moneda_id").val("");
                                          $("#text_nombre").val("");
                                          $("#text_abreviatura").val("");
                                          $("#text_simbolo").val("");
                                          $("#text_descripcion").val("");

                                          $(".needs-validation").removeClass("was-validated");

                                      } else {
                                          Toast.fire({
                                              icon: 'error',
                                              title: 'La moneda no se pudo registrar'
                                          });
                                      }

                                  }
                              });
                          }
                      })


                  } else {
                      //console.log(" ") //No paso la validacion
                  }

                  form.classList.add('was-validated');


              });
          });


          /* ======================================================================================
           EVENTO AL DAR CLICK EN EL BOTON EDITAR CLIENTE
          =========================================================================================*/
          $("#tbl_monedas tbody").on('click', '.btnEditarMoneda', function() {

              accion = 3; //seteamos la accion para editar
              titulo_modal = 'Actualizar';
              $("#modal_registro_moneda").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_registro_moneda").modal('show'); //modal de registrar producto
              $("#titulo_modal_moneda").html('Actualizar Moneda');
              $("#btnregistrar_moneda").html('Actualizar');

              //var data = table.row($(this).parents('tr')).data();

              if (tbl_monedas.row(this).child.isShown()) {
                  var data = tbl_monedas.row(this).data();
              } else {
                  var data = tbl_monedas.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  // console.log("🚀 ~ file: CLIENTE.php ~ line 363 ~ $ ~ data", data);
              }

              $("#moneda_id").val(data[0]);
              $("#text_nombre").val(data[1]);
              $("#text_abreviatura").val(data[2]);
              $("#text_simbolo").val(data[3]);
              // $("#text_direccion").attr('hidden', true); //ocultamos el tex 
              $("#text_descripcion").val(data[4]);

          })


          /* ======================================================================================
           EVENTO AL DAR CLICK EN EL BOTON ELIMINAR CLIENTE
          =========================================================================================*/
          $("#tbl_monedas tbody").on('click', '.btnEliminarMoneda', function() {

              accion = 4; //seteamos la accion para Eliminar

              if (tbl_monedas.row(this).child.isShown()) {
                  var data = tbl_monedas.row(this).data();
              } else {
                  var data = tbl_monedas.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  //   console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
              }

              var moneda_id = data[0];
              //  console.log(id_usuario);

              Swal.fire({
                  title: 'Desea Eliminar la moneda "' + data[1] + '" ?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Si, Eliminar',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {

                  if (result.isConfirmed) {

                      var datos = new FormData();

                      datos.append("accion", accion);
                      datos.append("moneda_id", moneda_id); //jalamos el codigo que declaramos mas arriba


                      $.ajax({
                          url: "ajax/moneda_ajax.php",
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
                                      title: 'La moneda se Elimino de forma correcta'
                                      // title: titulo_msj
                                  });

                                  tbl_monedas.ajax.reload(); //recargamos el datatable

                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'La moneda no se pudo Eliminar'
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
          $("#btncerrarmodal_moneda, #btncerrar_moneda").on('click', function() {
              $("#moneda_id").val("");
              $("#text_nombre").val("");
              $("#text_abreviatura").val("");
              $("#text_simbolo").val("");
              $("#text_descripcion").val("");
          })

          /*===================================================================*/
          //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
          /*===================================================================*/
          document.getElementById("btncerrar_moneda").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })
          document.getElementById("btncerrarmodal_moneda").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })






      }) //FIN DOCUMENTE READY



      ////////////////////////////////////////////////FUNCIONES MONEDAS////////////////////////////////////////////////////

      function AbrirModalRegistroMoneda() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_registro_moneda").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_registro_moneda").modal('show'); //abrimos el modal
          $("#titulo_modal_moneda").html('Registrar Moneda');
          $("#btnregistrar_moneda").html('Registrar');
          accion = 2; // guardar
          titulo_modal = "Registrar";
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