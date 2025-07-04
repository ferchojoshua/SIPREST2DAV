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
                          <h3 class="card-title">Pivot General Prestamos</h3>

                      </div>
                      <div class=" card-body">
                          <!-- <div class="row">
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="">
                                          <span class="small">Cliente:</span>
                                      </label>
                                      <select class="form-control form-control-sm js-example-basic-single" id="select_clientes" style="width: 100%"> </select>
                                      <div class="invalid-feedback">Seleccione un Cliente</div>
                                  </div>
                              </div>

                              <div class="col-md-8 d-flex flex-row align-items-center justify-content-end">
                                  <div class="form-group m-0"><a class="btn btn-primary btn-sm" style="width:120px;" id="btnFiltrar">Buscar</a></div>
                              </div>
                          </div><br> -->
                          <div class="col-12 table-responsive">
                              <table id="tbl_reporte_pivot" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-info text-left">
                                      <tr>
                                          <th>Año</th>
                                          <th>Enero</th>
                                          <th>Febrero</th>
                                          <th>Marzo</th>
                                          <th>Abril</th>
                                          <th>Mayo</th>
                                          <th>Junio</th>
                                          <th>Julio</th>
                                          <th>Agosto</th>
                                          <th>Set.</th>
                                          <th>Oct.</th>
                                          <th>Nov.</th>
                                          <th>Dic.</th>
                                          <th class="text-center">Total</th>
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

  <!-- Main content -->
  <div class="content pb-2">
      <div class="container-fluid">
          <div class="row p-0 m-0">
              <div class="col-md-12">
                  <div class="card card-info card-outline shadow ">
                      <div class="card-header">
                          <h3 class="card-title">Record Por usuario</h3>

                      </div>
                      <div class=" card-body">
                          <div class="row">
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="">
                                          <span class="small">Usuario:</span>
                                      </label>
                                      <select class="form-control form-control-sm js-example-basic-single" id="select_usuario" style="width: 100%"> </select>
                                  </div>
                              </div>

                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label for="">
                                          <span class="small">Año:</span>
                                      </label>
                                      <select class="form-control form-control-sm js-example-basic-single" id="select_anio" style="width: 100%"> </select>

                                  </div>
                              </div>

                              <div class="col-md-4 d-flex flex-row align-items-center justify-content-end">
                                  <label for="">&nbsp;</label><br>
                                  <div class="form-group m-0"><a class="btn btn-info btn-sm" style="width:120px;" id="btnBuscar"><i class="fas fa-search"></i></a></div>
                                  <!-- <button class="btn btn-info btn-sm" onclick="Listar_record_usuario();validar2();"><i class="fas fa-search"></i></button> -->
                              </div>
                          </div><br>

                          <div class="col-12 table-responsive">
                              <table id="tbl_reporte_record_usu" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-info text-left">
                                      <tr>
                                          <th>Año</th>
                                          <th>Mes</th>
                                          <th>Usuario</th>
                                          <th>Cant. Prest.</th>
                                          <th class="text-center">Total</th>
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
      var tbl_reporte_pivot, tbl_reporte_record_usu;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });


      $(document).ready(function() {
          ReporteRecorUsuario();
          $('.js-example-basic-single').select2();

          /*===================================================================*/
          // ACTIVAR BUSQUEDA AL CAMBIAR SELECTOR DE USUARIO
          /*===================================================================*/
          $('#select_usuario').on('change', function() {
              $('#btnBuscar').click();
          });

          /*===================================================================*/
          // ACTIVAR BUSQUEDA AL CAMBIAR SELECTOR DE AÑO
          /*===================================================================*/
          $('#select_anio').on('change', function() {
              $('#btnBuscar').click();
          });

          /*===================================================================*/
          // EVENTO CLICK DEL BOTON BUSCAR
          /*===================================================================*/
          $("#btnBuscar").on('click', function() {
              ReporteRecorUsuario(); // Llama a la función para recargar la tabla
          });

          /***************************************************************************
           * INICIAR DATATABLE REPORTE PIVOT
           ******************************************************************************/
          var tbl_reporte_pivot = $("#tbl_reporte_pivot").DataTable({
            responsive: true,
              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte Pivot',
                      "exportOptions": {
                          'columns': [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]
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
                      'accion': 3
                  }, //LISTAR 
              },
              /*columnDefs: [
                {
                      targets: 8, //columna 2
                      sortable: false, //no ordene
                      render: function(data, type, full, meta) {
                          return "<center>" +
                              "<span class='btnEditarCliente  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Cliente'> " +
                              "<i class='fas fa-pencil-alt fs-6'></i> " +
                              "</span> " +
                              "<span class='btnEliminarCliente text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Cliente'> " +
                              "<i class='fas fa-trash fs-6'> </i> " +
                              "</span>" +

                              "</center>"
                      }
                  }
              ],*/

              lengthMenu: [5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              select: true
          });

          /*===================================================================*/
          //SOLICITUD AJAX PARA CARGAR USUARIOS EN COMBO
          /*===================================================================*/
          $.ajax({
              url: "ajax/reportes_ajax.php",
              method: "POST",
              cache: false,
              //contentType: false,
              //processData: false,
              data: {
                  'accion': 4
              },
              dataType: 'json',
              success: function(respuesta) {
                  //console.log(respuesta);

                  var options = '<option selected value="">Seleccione un usuario</option>';

                  if (respuesta.length > 0) {
                      for (let i = 0; i < respuesta.length; i++) {
                          options += "<option value='" + respuesta[i][0] + "'>" + respuesta[i][1] + "</option>";
                      }
                      document.getElementById('select_usuario').innerHTML = options;
                  } else {
                      options += "<option value=''>No se encontraron datos</option>";
                     // document.getElementById('select_usuario').innerHTML = options;


                  }

                  /* for (let index = 0; index < respuesta.length; index++) {
                       options = options + '<option value=' + respuesta[index][0] + '>' + respuesta[index][1] + '</option>';
                   }
                   $("#select_usuario").append(options);*/

              }
          });

          /*===================================================================*/
          //SOLICITUD AJAX PARA CARGAR AÑOS DE PRESTAMOS EN COMBO
          /*===================================================================*/
          $.ajax({
              url: "ajax/reportes_ajax.php",
              method: "POST",
              cache: false,
              //contentType: false,
              //processData: false,
              data: {
                  'accion': 5
              },
              dataType: 'json',
              success: function(respuesta) {
                  // console.log(respuesta);

                  var options = '<option selected value="">Seleccione un año</option>';

                  if (respuesta.length > 0) {
                      for (let i = 0; i < respuesta.length; i++) {
                          options += "<option>" + respuesta[i][0] + "</option>";
                      }
                      document.getElementById('select_anio').innerHTML = options;
                  } else {
                      options += "<option value=''>No se encontraron datos</option>";
                     // document.getElementById('select_anio').innerHTML = options;


                  }

                /*  for (let index = 0; index < respuesta.length; index++) {
                      options = options + '<option>' + respuesta[index][0] + '</option>';
                  }
                  $("#select_anio").append(options);*/

              }
          });




          /*===================================================================*/
          //FILTRAR AL DAR CLICK EN EL BOTON
          /*===================================================================*/
          $("#btnBuscar").on('click', function() {
              ReporteRecorUsuario();
              validar();

          })

      });



      ////////////////////FUNCIONES///////////////////////////////

      function ReporteRecorUsuario() {
          var id_usuario = document.getElementById('select_usuario').value;
          var anio = document.getElementById('select_anio').value;

          tbl_reporte_record_usu = $("#tbl_reporte_record_usu").DataTable({
              responsive: true,
              destroy: true,
              //retrieve: true,
              //searching: false,
              paging: false,
              async: false,
              processing: true,

              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte prestamos por usuario y año',
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
                  url: "ajax/reportes_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 6,
                      'id_usuario': id_usuario,
                      'anio': anio

                  }, //LISTAR 
              },
              lengthMenu: [0, 5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              select: true
          });



      }



      function validar() {
    		let id_usuario = document.getElementById('select_usuario').value;
    		let anio = document.getElementById('select_anio').value;
    		if (id_usuario.length == 0) {
    			
                Swal.fire({
                  position: 'center',
                  icon: 'error',
                  title: 'Debe Seleccionar un Usuario',
                  showConfirmButton: true,
                  timer: 1500
              })
              $("#select_usuario").focus();
    		}
    		if (anio.length == 0) {
    			Toast.fire({
                  icon: 'error',
                  title: ' Debe Seleccionar un Año'
              })
              $("#select_anio").focus();
    		}
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