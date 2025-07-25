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
                          <h3 class="card-title">Ingresos / Egresos</h3>
                          <button class="btn btn-info btn-sm float-right" id="abrirmodal_movimientos"><i class="fas fa-plus"></i>
                              Nuevo</button>
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
                              <table id="tbl_movimientos" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-gradient-info text-white">
                                      <tr>
                                          <th>Id movi</th>
                                          <th>Tipo Movi.</th>
                                          <th>Descripcion</th>
                                          <th>Monto</th>
                                          <th>Fecha</th>
                                          <th>Estado Caja</th>
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


  <!-- MODAL REGISTRAR CLIENTES-->
  <div class="modal fade" id="modal_registro_movimientos" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_movimientos">Registro de Mivimientos</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_movimientos" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">

                          <div class="col-md-12">
                              <div class="form-group mb-2">
                                  <input type="text" id="movimientos_id" hidden>
                                  <input type="text" id="id_caja" hidden>
                                  <label for="" class="">
                                      <span class="small"> Tipo Movimiento</span>
                                  </label>
                                  <select name="" id="select_tipo_movi" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                                      <option value="">Seleccione...</option>
                                      <option value="INGRESO">INGRESO</option>
                                      <option value="EGRESO">EGRESO</option>
                                  </select>

                                  <div class="invalid-feedback">Seleccione un tipo de movimiento</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small">Descripcion </span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_descripcion" placeholder="Descripcion de Movimiento" required>
                                  <div class="invalid-feedback">Debe ingresar una descripcion</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="number" class=" form-control form-control-sm" id="text_monto" min="0" placeholder="Monto " required>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>



                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_movimientos">Cerrar</button>
                  <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_movimientos">Registrar</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->
<?php require_once "modulos/footer.php"; ?>
  <script>
      var accion;
      var tbl_movimientos;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });
      $(document).ready(function() {

        CargarEstadoCaja();
        $('.js-example-basic-single').select2();

        /*===================================================================*/
        // FILTRAR MOVIMIENTOS POR CLIENTE AL CAMBIAR SELECTOR O HACER CLIC EN BUSCAR
        /*===================================================================*/
        $("#btnFiltrar").on('click', function() {
            if ($("#select_clientes").val() == '') {
                Toast.fire({
                    icon: 'error',
                    title: ' Debe Seleccionar un cliente'
                })
                $("#select_clientes").focus();

            } else {
                tbl_movimientos.ajax.reload(); // Recargar la tabla de movimientos
            }
        });

        $('#select_clientes').on('change', function() {
            tbl_movimientos.ajax.reload(); // Recargar la tabla de movimientos al cambiar el cliente
        });

          /*===================================================================*/
          // INICIAMOS EL DATATABLE
          /*===================================================================*/
          tbl_movimientos = $("#tbl_movimientos").DataTable({
              responsive: true,

              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Lista de Movimientos de Caja',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5]
                      },
                      "download": 'open'
                  },
                  'pageLength',
              ],
              ajax: {
                  url: "ajax/movimientos_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1,
                      'cliente_id': $("#select_clientes").val()
                  }, //LISTAR 
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  },
                  {
                      targets: 6, //columna 2
                      sortable: false, //no ordene
                      render: function(td, cellData, rowData, row, col) {
                          if (rowData[5] == 'VIGENTE') {
                              return "<center>" +
                                  "<span class='btnEditarMovimientos  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Movimiento '> " +
                                  "<i class='fas fa-pencil-alt fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnEliminarMovimientos  text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Movimiento '> " +
                                  "<i class='fas fa-trash fs-6'> </i> " +
                                  "</span>" +
                                  "</center>"

                          } else {
                              return "<center>" +
                                  "<span class='text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-pencil-alt fs-6'></i> " +
                                  "</span> " +
                                  "<span class=' text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-trash fs-6'> </i> " +
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

          /*===================================================================*/
          //EVENTO PARA ABRIR EL MODAL DE REGISTRAR AL DAR CLICK EN BOTON NUEVO
          /*===================================================================*/
          $("#abrirmodal_movimientos").on('click', function() {
              AbrirModalRegistroMovimientos();
          })

          /*===================================================================*/
          //EVENTO QUE GUARDA Y ACTUALIZA LOS DATOS DEL MODULO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
          /*===================================================================*/
          document.getElementById("btnregistrar_movimientos").addEventListener("click", function() {

              // Get the forms we want to add validation styles to
              var forms = document.getElementsByClassName('needs-validation');
              // Loop over them and prevent submission
              var validation = Array.prototype.filter.call(forms, function(form) {

                  //si se ingresan todos los datos 
                  if (form.checkValidity() === true) {

                      Swal.fire({
                          title: 'Esta seguro de ' + titulo_modal + ' el Movimiento?',
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
                              datos.append("movimientos_id", $("#movimientos_id").val()); //id
                              datos.append("movi_tipo", $("#select_tipo_movi").val()); //modulo
                              datos.append("movi_descripcion", $("#text_descripcion").val());
                              datos.append("movi_monto", $("#text_monto").val());
                              datos.append("caja_id", $("#id_caja").val());


                              if (accion == 2) {
                                  var titulo_msj = "El Movimiento se registro de forma correcta"

                              }

                              if (accion == 3) {
                                  var titulo_msj = "El Movimiento se actualizo correctamente"

                              }
                              $.ajax({
                                  url: "ajax/movimientos_ajax.php",
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
                                              //title: 'El Movimiento se registro de forma correcta'
                                              title: titulo_msj
                                          });

                                          tbl_movimientos.ajax.reload(); //recargamos el datatable 

                                          $("#modal_registro_movimientos").modal('hide');

                                          $("#movimientos_id").val("");
                                          $("#select_tipo_movi").val("");
                                          $("#text_descripcion").val("");
                                          $("#text_monto").val("");

                                          $(".needs-validation").removeClass("was-validated");

                                      } else {
                                          Toast.fire({
                                              icon: 'error',
                                              title: 'El Movimiento no se pudo registrar'
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
           EVENTO AL DAR CLICK EN EL BOTON EDITAR MOVIMIENTO
          =========================================================================================*/
          $("#tbl_movimientos tbody").on('click', '.btnEditarMovimientos', function() {

              accion = 3; //seteamos la accion para editar
              titulo_modal = 'Actualizar';
              $("#modal_registro_moneda").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_registro_movimientos").modal('show'); //modal de registrar producto
              $("#titulo_modal_movimientos").html('Actualizar Movimientos');
              $("#btnregistrar_movimientos").html('Actualizar');

              //var data = table.row($(this).parents('tr')).data();

              if (tbl_movimientos.row(this).child.isShown()) {
                  var data = tbl_movimientos.row(this).data();
              } else {
                  var data = tbl_movimientos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  //  console.log("🚀 ~ file: CLIENTE.php ~ line 363 ~ $ ~ data", data);
              }

              $("#movimientos_id").val(data[0]);
              $("#select_tipo_movi").val(data[1]);
              $("#text_descripcion").val(data[2]);
              $("#text_monto").val(data[3]);
              // $("#text_direccion").attr('hidden', true); //ocultamos el tex 
              //$("#text_descripcion").val(data[4]);

          })




          /* ======================================================================================
           EVENTO AL DAR CLICK EN EL BOTON ELIMINAR MOVIMIENTO
          =========================================================================================*/
          $("#tbl_movimientos tbody").on('click', '.btnEliminarMovimientos', function() {

              accion = 4; //seteamos la accion para Eliminar

              if (tbl_movimientos.row(this).child.isShown()) {
                  var data = tbl_movimientos.row(this).data();
              } else {
                  var data = tbl_movimientos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  //  console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
              }

              var movimientos_id = data[0];
              //var  tipo_mov = data[1];
              // console.log(id_usuario);

              Swal.fire({
                  title: 'Desea Eliminar el Movimiento "' + data[2] + '" ?',
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
                      datos.append("movimientos_id", movimientos_id); //jalamos el codigo que declaramos mas arriba


                      $.ajax({
                          url: "ajax/movimientos_ajax.php",
                          method: "POST",
                          data: datos, //enviamos lo de la variable datos
                          cache: false,
                          contentType: false,
                          processData: false,
                          dataType: 'json',
                          success: function(respuesta) {

                              if (respuesta > 0) {

                                  if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                      Toast.fire({
                                          icon: 'success',
                                          title: 'Movimiento Eliminado'
                                      });

                                      tbl_movimientos.ajax.reload(); //recargamos el datatable 

                                  } else {
                                      Toast.fire({
                                          icon: 'warning',
                                          title: 'El "' + data[1] + '"  esta Cerrado por Caja, revisar'
                                      });
                                  }

                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al Eliminar Movimiento'

                                  });
                              }

                              //   if (respuesta == "ok") {

                              //       Toast.fire({
                              //           icon: 'success',
                              //           title: 'El Movimiento se Elimino de forma correcta'
                              //           // title: titulo_msj
                              //       });

                              //       tbl_movimientos.ajax.reload(); //recargamos el datatable

                              //   } else {
                              //       Toast.fire({
                              //           icon: 'error',
                              //           title: 'El Movimiento no se pudo Eliminar'
                              //       });
                              //   }

                          }
                      });

                  }
              })

          })



          /* ======================================================================================
           EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
          =========================================================================================*/
          $("#btncerrarmodal_movimientos, #btncerrar_movimientos").on('click', function() {
              $("#movimientos_id").val("");
              $("#select_tipo_movi").val("");
              $("#text_descripcion").val("");
              $("#text_monto").val("");

          })

          /*===================================================================*/
          //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
          /*===================================================================*/
          document.getElementById("btncerrar_movimientos").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })
          document.getElementById("btncerrarmodal_movimientos").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })



      }) //FIN DOCUMENT READY

      ////////////////////////////////////////////////FUNCIONES MOVIMIENTOS////////////////////////////////////////////////////

      function AbrirModalRegistroMovimientos() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_registro_movimientos").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_registro_movimientos").modal('show'); //abrimos el modal
          $("#titulo_modal_movimientos").html('Registrar Movimientos');
          $("#btnregistrar_movimientos").html('Registrar');
          accion = 2; // guardar
          titulo_modal = "Registrar";
          CargarId_Caja();
      }

      /*===================================================================*/
      //FUNCION PARA CARGAR TRAER EL ID CAJA
      /*===================================================================*/
      function CargarId_Caja() {

          $.ajax({
              async: false,
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: {
                  'accion': 6
              },
              dataType: 'json',
              success: function(respuesta) {

                  caja_id = respuesta["caja_id"];


                  $("#id_caja").val(caja_id);
              }
          });
      }


      /*===================================================================*/
      //SI ESTA APERTURADA O NO UNA CAJA
      /*===================================================================*/
      function CargarEstadoCaja() {

          $.ajax({
              async: false,
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: {
                  'accion': 5
              },
              dataType: 'json',
              success: function(respuesta) {
                  //console.log(respuesta);

                  caja_estado = respuesta["caja_estado"];
                  //console.log(caja_estado);
                  // $("#text_correo").val(email);

                  if (caja_estado == 'VIGENTE') {

                  } else {
                      Swal.fire({
                          position: 'center',
                          icon: 'error',
                          title: 'Mensaje de Error',
                          text: 'Debe aperturar una caja, se redireccionara a la ventana',
                          showConfirmButton: false,
                          //timer: 1500
                      })

                      //$("#CargarContenido").load('vistas/caja.php','content-wrapper');
                      CargarContenido('vistas/caja.php', 'content-wrapper');
                  }
              }
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