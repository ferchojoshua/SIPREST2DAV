  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1 class="m-0">Dashboard</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                      <li class="breadcrumb-item active">Dashboard</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
      <div class="container-fluid">
          <div class="row">
              <!-- TARJETA DE CAJA -->
              <div class="col-lg-3">
                  <div class="small-box bg-info">
                      <div class="inner">
                         
                        <h4 id="totalcaja">222</h4>
                        <p>Caja</p>

                         
                      </div>
                      <div class="icon">
                          <i class="ion ion-clipboard"></i>
                      </div>
                      <a style="cursor:pointer;" onclick="CargarContenido('vistas/caja.php','content-wrapper')" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>

              <!-- TARJETA DE CLIENTES -->
              <div class="col-lg-3">
                  <div class="small-box bg-success">
                      <div class="inner">
                          <h4 id="totalclientes">222</h4>

                          <p>Clientes</p>
                      </div>
                      <div class="icon">
                          <i class="ion ion-clipboard"></i>
                      </div>
                      <a style="cursor:pointer;" onclick="CargarContenido('vistas/cliente.php','content-wrapper')" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>

              <!-- TARJETA DE PRESTAMOS -->
              <div class="col-lg-3">
                  <div class="small-box bg-warning">
                      <div class="inner">
                          <h4 id="totalprestamos">222</h4>

                          <p>Prestamos</p>
                      </div>
                      <div class="icon">
                          <i class="ion ion-clipboard"></i>
                      </div>
                      <a style="cursor:pointer;" class="small-box-footer"> Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>

              <!-- TARJETA PRESTAMOS DEL DIA -->
              <div class="col-lg-3">
                  <div class="small-box bg-danger">
                      <div class="inner">
                          <h4 id="totalacobrar">222</h4>


                          <p>Total a cobrar</p>
                      </div>
                      <div class="icon">
                          <i class="ion ion-bag"></i>
                      </div>
                      <a style="cursor:pointer;" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>
          </div>
          <!-- TERMINA TARJETAS -->


          <!-- GRAFICO DE PRESTAMOS -->
          <div class="row">
              <div class="col-12">
                  <div class="card card-info">
                      <div class="card-header">
                          <h3 class="card-title" id="titulo_grafico">Ventas del dia</h3>
                          <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                              </button>

                          </div>
                      </div>
                      <div class="card-body">
                          <div class="chart">
                              <canvas id="barChart" style="min-height:250px; height: 300px; max-height:350px; width: 100%">

                              </canvas>
                          </div>
                      </div>

                  </div>
              </div>
          </div>
          <!-- TERMINA GRAFICO DE PRESTAMOS -->


          <!-- TABLA CLIENTES CON MAS PRESTAMOS -->
          <div class="row">
              <div class="col-md-6">
                  <div class="card card-info">
                      <div class="card-header">
                          <h3 class="card-title">Clientes con Prestamos</h3>
                          <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                              </button>

                          </div>
                      </div>
                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="table display table-hover text-nowrap compact  w-100  rounded" id="tbl_clientes">
                                  <thead>
                                      <tr>
                                          <th>Cédula</th>
                                          <th>Cliente</th>
                                          <th>Cant. Prest</th>
                                          <th>Total Prestamos</th>
                                      </tr>
                                  </thead>
                                  <tbody>

                                  </tbody>
                              </table>
                          </div>
                      </div>

                  </div>

              </div>
              <!-- TABLA CUOTAS VENCIDAS -->
              <div class="col-md-6">
                  <div class="card card-info">
                      <div class="card-header">
                          <h3 class="card-title">Cuotas vencidas</h3>
                          <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                              </button>

                          </div>
                      </div>
                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="table display table-hover text-nowrap compact  w-100  rounded" id="tbl_cuotas_vencidas">
                                  <thead>
                                      <tr>
                                          <th>id</th>
                                          <th class="all">Cédula</th>
                                          <th class="desktop">Cliente</th>
                                          <th class="all">Nro Prestamo</th>
                                          <th>Nro Cuota</th>
                                          <th class="all">Fecha</th>
                                          <th>Monto</th>
                                          <th>id usu</th>
                                          <th>Cobrador</th>
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
  </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <script>
      var tbl_cuotas_vencidas;
      $(document).ready(function() {
          // Verificar que Chart.js esté cargado
          if (typeof Chart === 'undefined') {
              console.error('Chart.js no está cargado');
              return;
          }

          /*************************************
           * TARJETAS AJAX EN TABLERO
           *************************************/
          $.ajax({
              url: "ajax/dashboard_ajax.php",
              method: 'POST',
              dataType: 'json',
              success: function(respuesta) {
                  //console.log("respuesta", respuesta);
                  //capturamos lo del array(respuesta) y enviamos a los ID
                  $("#totalcaja").html('C$ ' + respuesta[0]['caja']);
                  $("#totalclientes").html(respuesta[0]['clientes']);
                  $("#totalprestamos").html(respuesta[0]['prestamospen']);
                  $("#totalacobrar").html('C$ ' + respuesta[0]['totalacobrar']);

              }
          });

          /*************************************
           * ACTUALIZAR EN AUTOMATICO LAS TARJETAS 
           *************************************/
          // setInterval(() => {
          //     $.ajax({
          //         url: "ajax/dashboard_ajax.php",
          //         method: 'POST',
          //         dataType: 'json',
          //         success: function(respuesta) {
          //             // console.log("respuesta", respuesta);
          //             //capturamos lo del array(respuesta) y enviamos a los ID
          //             $("#totalcompras").html('S./ ' + respuesta[0]['totalcompras']);
          //             $("#totalventas").html('S./ ' + respuesta[0]['totalventas']);
          //             $("#totalganancias").html('S./ ' + respuesta[0]['totalganancias']);
          //             $("#totalventasdias").html('S./ ' + respuesta[0]['ventasdeldia']);

          //         }
          //     });

          // }, 10000);


          /*************************************
           * GRAFICO PRESTAMOS DEL MESS
           *************************************/
          $.ajax({
              url: "ajax/dashboard_ajax.php",
              method: 'POST',
              data: {
                  'accion': 1
              }, //optener datos
              dataType: 'json',
              success: function(respuesta) {
                  // console.log("respuesta", respuesta);
                  //capturamos lo del array(respuesta) y enviamos a los ID
                  var fecha_pres = [];
                  var total_prest = [];
                  var sumatotalprestamomes = 0;

                  for (let i = 0; i < respuesta.length; i++) {
                      fecha_pres.push(respuesta[i]['fecha']);
                      total_prest.push(respuesta[i]['totalprestamo']);
                      sumatotalprestamomes = parseFloat(sumatotalprestamomes) + parseFloat(respuesta[i]['totalprestamo']);

                  }
                  // console.log(sumatoalventasmes);
                  $("#titulo_grafico").html('Prestamos Total del Mes:  C$/.  ' + sumatotalprestamomes.toString().replace(/\d(?=(\d{3})+\.)/g, "$&,"));

                  // Verificar que el canvas exista
                  var canvas = document.getElementById('barChart');
                  if (!canvas) {
                      console.error('El elemento canvas #barChart no existe');
                      return;
                  }

                  try {
                      var barChartCanvas = canvas.getContext('2d');
                      if (!barChartCanvas) {
                          console.error('No se pudo obtener el contexto 2d del canvas');
                          return;
                      }

                      var areaChartData = {
                          labels: fecha_pres,
                          datasets: [{
                              label: 'Prestamos del dia',
                              backgroundColor: 'rgba(60,141,188,0.9)',
                              data: total_prest
                          }]
                      }

                      var barChartData = $.extend(true, {}, areaChartData);
                      var temp0 = areaChartData.datasets[0];
                      barChartData.datasets[0] = temp0;

                      var barChartOptions = {
                          maintainAspectRatio: false,
                          responsive: true,
                          events: false,
                          legend: {
                              display: true
                          },
                          scales: {
                              xAxes: [{
                                  stacked: true,
                              }],
                              yAxes: [{
                                  stacked: true
                              }]
                          },
                          animation: {
                              duration: 500,
                              easing: "easeOutQuart",
                              onComplete: function() {
                                  var ctx = this.chart.ctx;
                                  ctx.font = Chart.helpers.fontString(Chart.defaults.global
                                      .defaultFontFamily, 'normal',
                                      Chart.defaults.global.defaultFontFamily);
                                  ctx.textAlign = 'center';
                                  ctx.textBaseline = 'bottom';

                                  this.data.datasets.forEach(function(dataset) {
                                      for (var i = 0; i < dataset.data.length; i++) {
                                          var model = dataset._meta[Object.keys(dataset
                                                  ._meta)[0]].data[i]._model,
                                              scale_max = dataset._meta[Object.keys(dataset
                                                  ._meta)[0]].data[i]._yScale.maxHeight;
                                          ctx.fillStyle = '#444';
                                          var y_pos = model.y - 5;
                                          // Make sure data value does not get overflown and hidden
                                          // when the bar's value is too close to max value of scale
                                          // Note: The y value is reverse, it counts from top down
                                          if ((scale_max - model.y) / scale_max >= 0.93)
                                              y_pos = model.y + 20;
                                          ctx.fillText(dataset.data[i], model.x, y_pos);
                                      }
                                  });
                              }
                          }
                      }

                      new Chart(barChartCanvas, {
                          type: 'bar',
                          data: barChartData,
                          options: barChartOptions
                      });
                  } catch (error) {
                      console.error('Error al crear el gráfico:', error);
                  }

              }
          });


          /*************************************
           * CLIENTES CON MAS PRESTAMOS 
           *************************************/
          $.ajax({
              url: "ajax/dashboard_ajax.php",
              method: 'POST',
              data: {
                  'accion': 2
              }, //optener datos
              dataType: 'json',
              success: function(respuesta) {
                  //  console.log("respuesta", respuesta);
                  //capturamos lo del array(respuesta) y enviamos a los tr

                  for (let i = 0; i < respuesta.length; i++) {
                      filas = '<tr>' +

                          '<td>' + respuesta[i]["cliente_dni"] + '</td>' +
                          '<td>' + respuesta[i]["cliente_nombres"] + '</td>' +
                          '<td>' + respuesta[i]["cant"] + '</td>' +
                          '<td>S/. ' + respuesta[i]["total"] + '</td>' +
                          '</tr>'
                      $("#tbl_clientes tbody").append(filas);

                  }

              }
          });





          /***************************************************************************
           * INICIAR DATATABLE CLIENTES
           ******************************************************************************/
          var tbl_cuotas_vencidas = $("#tbl_cuotas_vencidas").DataTable({
              responsive: true,
              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Listado de Cuotas vencidas',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5, 6, 8]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5, 6, 8]
                      },
                      "download": 'open'
                  },
                  'pageLength',
              ],
              ajax: {
                  url: "ajax/dashboard_ajax.php",
                  type: "POST",
                  dataSrc: "",
                  data: {
                      'accion': 3
                  }, //LISTAR 
                  //  dataType: 'json',
              },
              columnDefs: [{
                  targets: 0,
                  visible: false
              }, {
                  targets: 7,
                  visible: false

              }, ],
              "order": [
                  [0, 'desc']
              ],
              lengthMenu: [5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              select: true
          });


      })

    //   function cap() {

    //       var id_usuario = $("#text_Idprincipal").val();
    //       //console.log(id_usuario);

    //       $.ajax({
    //           async: false,
    //           url: "ajax/dashboard_ajax.php",
    //           method: "POST",
    //           data: {
    //               'accion': 4,
    //               'id_usuario': id_usuario
    //           },
    //           dataType: 'json',
    //           success: function(respuesta) {
    //               //console.log(respuesta);
    //               document.getElementById('lbl_contador').innerHTML = respuesta.length;
    //               let llenardata = "";
    //               if (respuesta.length > 0) {
    //                   for (let i = 0; i < respuesta.length; i++) {
    //                       llenardata += '<a href="#" class="dropdown-item">' +
    //                           '<div class="media">' +
    //                           '<div class="media-body">' +
    //                           '<h4 class="dropdown-item-title">' +
    //                           '<b>Nro Prestamo: </b>' + respuesta[i][0] + '' +
    //                           '<span class="float-right text-sm text-warning"><i class="fas fa-folder-minus"></i></i></span>' +
    //                           '</h4>' +
    //                           '<p class="text-sm"><b>Cliente: </b>' + respuesta[i][2] + ' </p>' +
    //                           '<p class="text-sm"><b>Nro Cuota: </b>' + respuesta[i][3] + ' | ' + respuesta[i][5] + '</p>' +


    //                           '<p class="text-sm text-muted"><i class="fas fa-calendar-alt"></i> Fecha: ' + respuesta[i][4] + ' </p>' +
    //                           ' </div>' +
    //                           '</div>' +

    //                           '</a>' +
    //                           '<div class="dropdown-divider"></div>';
    //                   }
    //                   document.getElementById('div_cuerpo').innerHTML = llenardata;

    //               } else {
    //                   llenardata += "<option value=''>No se encontraron datos</option>";
    //                   document.getElementById('div_cuerpo').innerHTML = llenardata;
    //                   //  document.getElementById('select_rol_editar').innerHTML = llenardata;

    //               }

    //           }
    //       });
    //   }



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