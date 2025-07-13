  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Tablero Principal</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Tablero Principal</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
      <div class="container-fluid">

        <!-- Small boxes (Stat box) -->
          <div class="row">
              <div class="col-lg-3">
                <!-- small box -->
                  <div class="small-box bg-info">
                      <div class="inner">
                        <h3 id="total_caja"></h3>
                        <p>Caja</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-bag"></i>
                      </div>
                    <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>
            <!-- ./col -->
              <div class="col-lg-3">
                <!-- small box -->
                  <div class="small-box bg-success">
                      <div class="inner">
                        <h3 id="total_clientes"></h3>
                          <p>Clientes</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-stats-bars"></i>
                      </div>
                    <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>
            <!-- ./col -->
              <div class="col-lg-3">
                <!-- small box -->
                  <div class="small-box bg-warning">
                      <div class="inner">
                        <h3 id="total_prestamos"></h3>
                          <p>Prestamos</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-person-add"></i>
                      </div>
                    <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                  </div>
              </div>
            <!-- ./col -->
              <div class="col-lg-3">
                <!-- small box -->
                  <div class="small-box bg-danger">
                      <div class="inner">
                        <h3 id="total_cobrar"></h3>
                          <p>Total a cobrar</p>
                      </div>
                      <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                    <a href="#" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Fila para los KPIs Gerenciales -->
        <div class="row">
            <div class="col-lg-3">
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3 id="saldo_cartera">C$ 0.00</h3>
                        <p>Saldo Cartera</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-wallet"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-fuchsia">
                    <div class="inner">
                        <h3 id="clientes_activos">0</h3>
                        <p>Clientes Activos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3 id="monto_en_mora">C$ 0.00</h3>
                        <p>Monto en Mora</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="small-box bg-maroon">
                    <div class="inner">
                        <h3 id="porcentaje_mora">0.00%</h3>
                        <p>Porcentaje de Mora</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-chart-pie"></i>
                      </div>
                  </div>
              </div>
          </div>

          <div class="row">
              <div class="col-12">
                  <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title" id="total_mes"></h3>

                          <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                  <i class="fas fa-minus"></i>
                              </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                          </div>
                      </div>
                      <div class="card-body">
                          <div class="chart">
                            <canvas id="barChart" style="min-height: 250px; height: 300px; max-height: 350px; width: 100%;"></canvas>
                          </div>
                      </div>
                  </div>
              </div>
          </div>


          <div class="row">
            <div class="col-lg-6">
                  <div class="card card-info">
                    <div class="card-header">
                          <h3 class="card-title">Clientes con Prestamos</h3>
                          </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="tbl_clientes_prestamos">
                                  <thead>
                                      <tr>
                                    <th>CÉDULA</th>
                                    <th>CLIENTE</th>
                                    <th>CANT. PREST</th>
                                    <th>TOTAL PRESTAMOS</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
            <div class="col-lg-6">
                  <div class="card card-info">
                    <div class="card-header">
                          <h3 class="card-title">Cuotas vencidas</h3>
                          </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered" id="tbl_cuotas_vencidas">
                                  <thead>
                                      <tr>
                                    <th>CLIENTE</th>
                                    <th>CUOTAS VENCIDAS</th>
                                    <th>MONTO TOTAL</th>
                                      </tr>
                                  </thead>
                            <tbody></tbody>
                              </table>
                          </div>
                      </div>
              </div>
          </div>

      </div>
  </div>
  <!-- /.content -->
  <script>
      $(document).ready(function() {
        cargarTarjetas();
        cargarGrafico();
        cargarKpisGerenciales();
        
        /* ========================================================================================
        PETICION AJAX PARA TRAER LOS DATOS DE LAS TABLAS DE CLIENTES Y CUOTAS VENCIDAS
        ========================================================================================*/
        $.ajax({
            url: "ajax/dashboard_ajax.php",
            type: "POST",
            data: {
                'accion': 2
            }, // traer datos para la tabla de clientes con prestamos
            dataType: 'json',
            success: function(respuesta) {
                for (let i = 0; i < respuesta.length; i++) {
                    filas = '<tr>' +
                        '<td>' + respuesta[i]["cliente_dni"] + '</td>' +
                        '<td>' + respuesta[i]["cliente_nombres"] + '</td>' +
                        '<td>' + respuesta[i]["cant"] + '</td>' +
                        '<td>' + 'C$ ' + parseFloat(respuesta[i]["total"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                        '</tr>'
                    $("#tbl_clientes_prestamos tbody").append(filas);
                }
            }
        });

        $.ajax({
            url: "ajax/dashboard_ajax.php",
            type: "POST",
            data: {
                'accion': 3
            }, // traer datos para la tabla de cuotas vencidas
            dataType: 'json',
            success: function(respuesta) {
                console.log("Datos para 'Cuotas Vencidas':", respuesta);
                for (let i = 0; i < respuesta.length; i++) {
                    filas = '<tr>' +
                        '<td>' + respuesta[i]["cliente_nombres"] + '</td>' +
                        '<td>' + respuesta[i]["cantidad_cuotas"] + '</td>' +
                        '<td>' + 'C$ ' + parseFloat(respuesta[i]["monto_total"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                        '</tr>'
                    $("#tbl_cuotas_vencidas tbody").append(filas);
                }
            }
        });
    });

    /* =======================================================
    PETICION AJAX PARA CARGAR TARJETAS
    =======================================================*/
    function cargarTarjetas() {
          $.ajax({
              url: "ajax/dashboard_ajax.php",
              method: 'POST',
              dataType: 'json',
              success: function(respuesta) {
                $("#total_caja").html('C$ ' + parseFloat(respuesta[0]["caja"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#total_clientes").html(respuesta[0]["clientes"] || 0);
                $("#total_prestamos").html(respuesta[0]["prestamos"] || 0);
                $("#total_cobrar").html('C$ ' + parseFloat(respuesta[0]["total_cobrar"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            }
        });
    }

    /* =======================================================
    PETICION AJAX PARA CARGAR GRAFICO DE BARRAS
    =======================================================*/
    function cargarGrafico() {
          $.ajax({
              url: "ajax/dashboard_ajax.php",
              method: 'POST',
              data: {
                  'accion': 1
            }, //parametro para obtener los datos del grafico
              dataType: 'json',
              success: function(respuesta) {
                var fecha_prestamo = [];
                var monto_prestamo = [];
                var total_prestamos_mes = 0;

                  for (let i = 0; i < respuesta.length; i++) {
                    fecha_prestamo.push(respuesta[i]['fecha']);
                    monto_prestamo.push(respuesta[i]['total_prestamo']);
                    total_prestamos_mes = parseFloat(total_prestamos_mes) + parseFloat(respuesta[i]['total_prestamo'] || 0);
                }

                $("#total_mes").html('Prestamos Total del Mes: C$/ ' + total_prestamos_mes.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

                var barChartCanvas = $('#barChart').get(0).getContext('2d');
                var barChartData = {
                    labels: fecha_prestamo,
                          datasets: [{
                              label: 'Prestamos del dia',
                              backgroundColor: 'rgba(60,141,188,0.9)',
                        data: monto_prestamo
                          }]
                      }

                      var barChartOptions = {
                          maintainAspectRatio: false,
                          responsive: true,
                          legend: {
                              display: true
                          }
                      }

                      new Chart(barChartCanvas, {
                          type: 'bar',
                          data: barChartData,
                          options: barChartOptions
                      });
            }
        });
    }
    
    /* =======================================================
    PETICION AJAX PARA CARGAR KPIs GERENCIALES
    =======================================================*/
    function cargarKpisGerenciales() {
          $.ajax({
            url: "ajax/reportes_ajax.php?accion=obtener_kpis_gerenciales",
              method: 'POST',
            cache: false,
            contentType: false,
            processData: false,
              dataType: 'json',
              success: function(respuesta) {
                $("#saldo_cartera").html('C$ ' + parseFloat(respuesta.saldo_cartera || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#clientes_activos").html(respuesta.clientes_activos || 0);
                $("#monto_en_mora").html('C$ ' + parseFloat(respuesta.monto_en_mora || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#porcentaje_mora").html(parseFloat(respuesta.porcentaje_mora || 0).toFixed(2) + '%');
            }
        });
      }
  </script>