<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">
                  <i class="fas fa-tachometer-alt"></i>
                  Dashboard Ejecutivo
              </h1>
              <p class="mb-0 text-muted">Visión general del negocio y métricas clave</p>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right bg-transparent">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Dashboard Ejecutivo</li>
                    <li class="breadcrumb-item">
                        <a href="#" onclick="CargarContenido('vistas/dashboard_cobradores.php','content-wrapper')" 
                           class="text-info" title="Ver análisis detallado de cobradores">
                            <i class="fas fa-chart-pie"></i> Dashboard Cobradores
                        </a>
                    </li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
        
        <!-- NUEVO: Filtros del Dashboard -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-primary card-outline collapsed-card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros del Dashboard</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sucursal:</label>
                                    <select id="filtro_sucursal_dashboard" class="form-control select2">
                                        <option value="">📊 Todas las sucursales (Vista Global)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Período:</label>
                                    <select id="filtro_periodo_dashboard" class="form-control">
                                        <option value="hoy">📅 Hoy</option>
                                        <option value="semana">📊 Esta Semana</option>
                                        <option value="mes" selected>📈 Este Mes</option>
                                        <option value="trimestre">📋 Este Trimestre</option>
                                        <option value="año">📊 Este Año</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button class="btn btn-primary" id="btn_aplicar_filtros_dashboard">
                                        <i class="fas fa-search"></i> Aplicar Filtros
                                    </button>
                                    <button class="btn btn-secondary ml-2" id="btn_limpiar_filtros_dashboard">
                                        <i class="fas fa-eraser"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                      <p>💼 Caja</p>
                      <small class="text-info-light">Dinero disponible en caja</small>
                    </div>
                    <div class="icon">
                      <i class="ion ion-bag"></i>
                    </div>
                  <a href="#" onclick="CargarContenido('vistas/caja.php','content-wrapper')" 
                     class="small-box-footer" title="Ver módulo de caja">
                      Gestionar Caja <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
            </div>
          <!-- ./col -->
            <div class="col-lg-3">
              <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                      <h3 id="total_clientes"></h3>
                        <p>👥 Clientes</p>
                        <small class="text-success-light">Total clientes registrados</small>
                    </div>
                    <div class="icon">
                      <i class="ion ion-stats-bars"></i>
                    </div>
                  <a href="#" onclick="CargarContenido('vistas/cliente.php','content-wrapper')" 
                     class="small-box-footer" title="Ver módulo de clientes">
                      Ver Clientes <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
            </div>
          <!-- ./col -->
            <div class="col-lg-3">
              <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                      <h3 id="total_prestamos"></h3>
                        <p>💰 Préstamos</p>
                        <small class="text-warning-light">Préstamos activos vigentes</small>
                    </div>
                    <div class="icon">
                      <i class="ion ion-person-add"></i>
                    </div>
                  <a href="#" onclick="CargarContenido('vistas/administrar_prestamos.php','content-wrapper')" 
                     class="small-box-footer" title="Ver préstamos activos">
                      Ver Préstamos <i class="fas fa-arrow-circle-right"></i>
                  </a>
                </div>
            </div>
          <!-- ./col -->
            <div class="col-lg-3">
              <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                      <h3 id="total_cobrar"></h3>
                        <p>🔴 Total a cobrar</p>
                        <small class="text-danger-light">Monto pendiente de cobro</small>
                    </div>
                    <div class="icon">
                      <i class="ion ion-pie-graph"></i>
                  </div>
                  <a href="#" onclick="CargarContenido('vistas/dashboard_cobradores.php','content-wrapper')" 
                     class="small-box-footer" title="Ver análisis detallado de cobranza">
                      Análisis Detallado <i class="fas fa-chart-pie"></i>
                  </a>
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
                      <p>💜 Saldo Cartera</p>
                      <small class="text-light">Valor total cartera préstamos</small>
                  </div>
                  <div class="icon">
                      <i class="fas fa-wallet"></i>
                  </div>
                  <a href="#" onclick="CargarContenido('vistas/reportes_financieros.php','content-wrapper')" 
                     class="small-box-footer" title="Ver reportes financieros detallados">
                      Ver Reportes <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
          </div>
          <div class="col-lg-3">
              <div class="small-box bg-fuchsia">
                  <div class="inner">
                      <h3 id="clientes_activos">0</h3>
                      <p>🩷 Clientes Activos</p>
                      <small class="text-light">Clientes con préstamos vigentes</small>
                  </div>
                  <div class="icon">
                      <i class="fas fa-users"></i>
                  </div>
                  <a href="#" onclick="CargarContenido('vistas/administrar_prestamos.php','content-wrapper')" 
                     class="small-box-footer" title="Ver clientes con préstamos activos">
                      Ver Activos <i class="fas fa-arrow-circle-right"></i>
                  </a>
              </div>
          </div>
          <div class="col-lg-3">
              <div class="small-box bg-orange">
                  <div class="inner">
                      <h3 id="monto_en_mora">C$ 0.00</h3>
                      <p>🟠 Monto en Mora</p>
                      <small class="text-light">Dinero vencido no cobrado</small>
                  </div>
                  <div class="icon">
                      <i class="fas fa-exclamation-triangle"></i>
                  </div>
                                          <a href="#" onclick="CargarContenido('vistas/reportes_financieros.php','content-wrapper')" 
                     class="small-box-footer" title="Ver reporte detallado de mora">
                      Ver Mora <i class="fas fa-chart-pie"></i>
                  </a>
              </div>
          </div>
          <div class="col-lg-3">
              <div class="small-box bg-maroon">
                  <div class="inner">
                      <h3 id="porcentaje_mora">0.00%</h3>
                      <p>🟣 Porcentaje de Mora</p>
                      <small class="text-light">% mora sobre cartera total</small>
                  </div>
                  <div class="icon">
                      <i class="fas fa-chart-pie"></i>
                    </div>
                    <a href="#" onclick="CargarContenido('vistas/dashboard_cobradores.php','content-wrapper')" 
                       class="small-box-footer" title="Analizar eficiencia de cobro">
                        Análisis Eficiencia <i class="fas fa-chart-line"></i>
                    </a>
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

        <!-- NUEVO: Callout informativo -->
        <div class="row">
            <div class="col-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Información de las Métricas:</h5>
                    <p>
                        <strong>💼 Caja:</strong> Dinero físico disponible en caja registradora<br>
                        <strong>👥 Clientes:</strong> Total de clientes registrados en el sistema<br>
                        <strong>💰 Préstamos:</strong> Número de préstamos activos y vigentes<br>
                        <strong>🔴 Total a cobrar:</strong> Monto pendiente de todas las cuotas por cobrar<br>
                        <strong>💜 Saldo Cartera:</strong> Valor total de la cartera de préstamos<br>
                        <strong>🩷 Clientes Activos:</strong> Clientes que tienen préstamos vigentes<br>
                        <strong>🟠 Monto en Mora:</strong> Dinero de cuotas vencidas no cobradas<br>
                        <strong>🟣 Porcentaje de Mora:</strong> Porcentaje de mora respecto al total de cartera
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /.content -->

<script>
    $(document).ready(function() {
      // Solo ejecutar si estamos en la página del dashboard
      if ($('#saldo_cartera').length > 0 && $('#barChart').length > 0) {
        console.log('🚀 Inicializando Dashboard Ejecutivo...');
        
        // Inicializar filtros
        inicializarFiltrosDashboard();
        
        // Funciones para cargar datos del dashboard ejecutivo
        cargarTarjetas();
        cargarGrafico();
        cargarKpisGerenciales();
      } else {
        console.log('Dashboard no detectado, omitiendo inicialización de scripts.');
      }
      
      /* ========================================================================================
      PETICION AJAX PARA TRAER LOS DATOS DE LAS TABLAS DE CLIENTES Y CUOTAS VENCIDAS
      ========================================================================================*/
      if ($('#tbl_cuotas_vencidas').length > 0) {
        $.ajax({
            url: "ajax/dashboard_ajax.php",
            type: "POST",
            data: {
                'accion': 3
            }, // traer datos para la tabla de cuotas vencidas
            dataType: 'json',
            success: function(respuesta) {
                if (!Array.isArray(respuesta)) {
                    console.error("La respuesta para la tabla de cuotas vencidas no es un array:", respuesta);
                    return;
                }
                for (let i = 0; i < respuesta.length; i++) {
                    filas = '<tr>' +
                        '<td>' + respuesta[i]["nro_prestamo"] + '</td>' +
                        '<td>' + respuesta[i]["cliente_nombres"] + '</td>' +
                        '<td>' + respuesta[i]["pdetalle_nro_cuota"] + '</td>' +
                        '<td>' + 'C$ ' + parseFloat(respuesta[i]["pdetalle_monto_cuota"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                        '<td>' + respuesta[i]["pdetalle_fecha"] + '</td>' +
                        '</tr>'
                    $("#tbl_cuotas_vencidas tbody").append(filas);
                }
            }
        });
      }
    });

  // NUEVO: Inicializar filtros del dashboard
  function inicializarFiltrosDashboard() {
        // Inicializar Select2
        $('#filtro_sucursal_dashboard').select2({
            width: '100%',
            placeholder: 'Seleccionar sucursal...'
        });
        
        // Cargar sucursales
        cargarSucursalesDashboard();
        
        // Eventos de filtros
        $('#btn_aplicar_filtros_dashboard').on('click', function() {
            console.log('🔍 Aplicando filtros del dashboard...');
            cargarTarjetasConFiltros();
            cargarGraficoConFiltros();
            cargarKpisGerencialesConFiltros();
            
            // Mostrar mensaje de éxito
            Swal.fire({
                icon: 'success',
                title: 'Filtros Aplicados',
                text: 'Dashboard actualizado con filtros seleccionados',
                timer: 2000,
                showConfirmButton: false
            });
        });
        
        $('#btn_limpiar_filtros_dashboard').on('click', function() {
            console.log('🧹 Limpiando filtros del dashboard...');
            $('#filtro_sucursal_dashboard').val('').trigger('change');
            $('#filtro_periodo_dashboard').val('mes');
            
            // Recargar datos sin filtros
            cargarTarjetas();
            cargarGrafico();
            cargarKpisGerenciales();
            
            Swal.fire({
                icon: 'info',
                title: 'Filtros Limpiados',
                text: 'Vista global restaurada',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

  // NUEVO: Cargar sucursales para el filtro
  function cargarSucursalesDashboard() {
        $.ajax({
            url: 'ajax/aprobacion_ajax.php',
            type: 'GET',
            data: { accion: 'listar_sucursales' },
            dataType: 'json',
            success: function(response) {
                const select = $('#filtro_sucursal_dashboard');
                select.find('option:gt(0)').remove(); // Mantener primera opción
                
                if (response && Array.isArray(response) && response.length > 0) {
                    response.forEach(function(sucursal) {
                        const sucursalId = sucursal.sucursal_id || sucursal.id;
                        const sucursalNombre = sucursal.sucursal_nombre || sucursal.nombre;
                        const sucursalTexto = sucursal.texto_descriptivo || sucursalNombre;
                        
                        if (sucursalId && sucursalNombre) {
                            select.append(`<option value="${sucursalId}">🏢 ${sucursalTexto}</option>`);
                        }
                    });
                    console.log(`✅ Cargadas ${response.length} sucursales en filtro dashboard`);
                } else {
                    console.warn('No se encontraron sucursales para el filtro');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error al cargar sucursales para filtro:', error);
            }
        });
    }

  // NUEVO: Cargar tarjetas con filtros
  function cargarTarjetasConFiltros() {
        const sucursalId = $('#filtro_sucursal_dashboard').val();
        const periodo = $('#filtro_periodo_dashboard').val();
        
        console.log('📊 Cargando tarjetas con filtros:', { sucursalId, periodo });
        
        // Si no hay sucursal seleccionada, usar función original
        if (!sucursalId) {
            cargarTarjetas();
            return;
        }
        
        // TODO: Aquí se implementaría la llamada AJAX con filtros
        // Por ahora, usamos la función original
        // cargarTarjetas(); // Comentado para implementar la llamada con filtros

        $.ajax({
            url: "ajax/dashboard_ajax.php",
            method: 'POST',
            dataType: 'json',
            data: { accion: 'cargar_tarjetas_filtradas', sucursal_id: sucursalId, periodo: periodo },
            success: function(respuesta) {
                   if (!Array.isArray(respuesta) || respuesta.length === 0) {
                    $("#total_caja").html('C$ 0.00');
                    $("#total_clientes").html('0');
                    $("#total_prestamos").html('0');
                    $("#total_cobrar").html('C$ 0.00');
                    return;
                }
                $("#total_caja").html('C$ ' + parseFloat(respuesta[0]["caja"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#total_clientes").html(respuesta[0]["clientes"] || 0);
                $("#total_prestamos").html(respuesta[0]["prestamos"] || 0);
                $("#total_cobrar").html('C$ ' + parseFloat(respuesta[0]["total_cobrar"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
            },
            error: function(xhr, status, error) {
                console.error('[cargarTarjetasConFiltros] Error al cargar tarjetas filtradas:', error);
                console.error('[cargarTarjetasConFiltros] Respuesta del servidor:', xhr.responseText);
                $("#total_caja").html('C$ 0.00');
                $("#total_clientes").html('0');
                $("#total_prestamos").html('0');
                $("#total_cobrar").html('C$ 0.00');
            }
        });
    }

  // NUEVO: Cargar gráfico con filtros
  function cargarGraficoConFiltros() {
        const sucursalId = $('#filtro_sucursal_dashboard').val();
        const periodo = $('#filtro_periodo_dashboard').val();
        
        console.log('📈 Cargando gráfico con filtros:', { sucursalId, periodo });
        
        // Si no hay sucursal seleccionada, usar función original
        if (!sucursalId) {
            cargarGrafico();
            return;
        }
        
        // TODO: Aquí se implementaría la llamada AJAX con filtros
        // Por ahora, usamos la función original
        // cargarGrafico(); // Comentado para implementar la llamada con filtros

        $.ajax({
            url: "ajax/dashboard_ajax.php",
            method: 'POST',
            dataType: 'json',
            data: { accion: 'cargar_grafico_filtrado', sucursal_id: sucursalId, periodo: periodo },
            success: function(respuesta) {
                    // Logic to update the chart with filtered data
            },
            error: function(xhr, status, error) {
                console.error('[cargarGraficoConFiltros] Error al cargar gráfico filtrado:', error);
                console.error('[cargarGraficoConFiltros] Respuesta del servidor:', xhr.responseText);
            }
        });
    }

  // NUEVO: Cargar KPIs con filtros
  function cargarKpisGerencialesConFiltros() {
        const sucursalId = $('#filtro_sucursal_dashboard').val();
        const periodo = $('#filtro_periodo_dashboard').val();
        
        console.log('📊 Cargando KPIs con filtros:', { sucursalId, periodo });
        
        // Si no hay sucursal seleccionada, usar función original
        if (!sucursalId) {
            cargarKpisGerenciales();
            return;
        }
        
        // TODO: Aquí se implementaría la llamada AJAX con filtros
        // Por ahora, usamos la función original
        // cargarKpisGerenciales(); // Comentado para implementar la llamada con filtros

        $.ajax({
            url: "ajax/dashboard_ajax.php",
            method: 'POST',
            dataType: 'json',
            data: { accion: 'cargar_kpis_filtrados', sucursal_id: sucursalId, periodo: periodo },
            success: function(respuesta) {
                // Logic to update KPIs with filtered data
                if (!Array.isArray(respuesta) || respuesta.length === 0) {
                    $("#saldo_cartera").html('C$ 0.00');
                    $("#clientes_activos").html('0');
                    $("#monto_en_mora").html('C$ 0.00');
                    $("#porcentaje_mora").html('0.00%');
                    return;
                }
                $("#saldo_cartera").html('C$ ' + parseFloat(respuesta[0]["saldo_cartera"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#clientes_activos").html(respuesta[0]["clientes_activos"] || 0);
                $("#monto_en_mora").html('C$ ' + parseFloat(respuesta[0]["monto_en_mora"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#porcentaje_mora").html(parseFloat(respuesta[0]["porcentaje_mora"] || 0).toFixed(2) + '%');
            },
            error: function(xhr, status, error) {
                console.error('[cargarKpisGerencialesConFiltros] Error al cargar KPIs filtrados:', error);
                console.error('[cargarKpisGerencialesConFiltros] Respuesta del servidor:', xhr.responseText);
                $("#saldo_cartera").html('C$ 0.00');
                $("#clientes_activos").html('0');
                $("#monto_en_mora").html('C$ 0.00');
                $("#porcentaje_mora").html('0.00%');
            }
        });
    }

  /* =======================================================
  PETICION AJAX PARA CARGAR TARJETAS (ORIGINAL)
  =======================================================*/
  function cargarTarjetas() {
        // Doble verificación por si se llama desde otro lugar
        if ($('#total_caja').length === 0) return;

        $.ajax({
            url: "ajax/dashboard_ajax.php",
            method: 'POST',
            dataType: 'json',
            success: function(respuesta) {

              
              // Validar que la respuesta sea un array y tenga datos
              if (!Array.isArray(respuesta) || respuesta.length === 0) {

                // Mostrar valores por defecto
                $("#total_caja").html('C$ 0.00');
                $("#total_clientes").html('0');
                $("#total_prestamos").html('0');
                $("#total_cobrar").html('C$ 0.00');
                return;
              }
              
              $("#total_caja").html('C$ ' + parseFloat(respuesta[0]["caja"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
              $("#total_clientes").html(respuesta[0]["clientes"] || 0);
              $("#total_prestamos").html(respuesta[0]["prestamos"] || 0);
              $("#total_cobrar").html('C$ ' + parseFloat(respuesta[0]["total_cobrar"] || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
          },
                      error: function(xhr, status, error) {
                // Mostrar valores por defecto en caso de error
              $("#total_caja").html('C$ 0.00');
              $("#total_clientes").html('0');
              $("#total_prestamos").html('0');
              $("#total_cobrar").html('C$ 0.00');
          }
      });
  }

  /* =======================================================
  PETICION AJAX PARA CARGAR GRAFICO DE BARRAS (ORIGINAL)
  =======================================================*/
  function cargarGrafico() {
        // Doble verificación
        if ($('#barChart').length === 0) return;

        $.ajax({
            url: "ajax/dashboard_ajax.php",
            method: 'POST',
            data: {
                'accion': 1
            }, //parametro para obtener los datos del grafico
            dataType: 'json',
            success: function(respuesta) {
              // Validar que la respuesta sea un array
              if (!Array.isArray(respuesta)) {
                return;
              }
              
              var fecha_prestamo = [];
              var monto_prestamo = [];
              var total_prestamos_mes = 0;

                for (let i = 0; i < respuesta.length; i++) {
                  fecha_prestamo.push(respuesta[i]['fecha']);
                  monto_prestamo.push(respuesta[i]['total_prestamo']);
                  total_prestamos_mes = parseFloat(total_prestamos_mes) + parseFloat(respuesta[i]['total_prestamo'] || 0);
              }

              $("#total_mes").html('Prestamos Total del Mes: C$/ ' + total_prestamos_mes.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));

              // Validar que el elemento canvas existe
              var canvasElement = $('#barChart');
              if (canvasElement.length === 0) {
                return;
              }

              var barChartCanvas = canvasElement.get(0).getContext('2d');
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
                    })

          });
      }

     /* =======================================================
      PETICION AJAX PARA CARGAR KPIs GERENCIALES (ORIGINAL)
      =======================================================*/
      function cargarKpisGerenciales() {
        // Doble verificación por si se llama desde otro lugar
        if ($('#saldo_cartera').length === 0) return;

          $.ajax({
            url: "ajax/reportes_ajax.php",
              method: 'POST',
            data: {
                accion: 'obtener_kpis_gerenciales'
            },
              dataType: 'json',
              success: function(respuesta) {
                // Validar que la respuesta sea un objeto
                if (!respuesta || typeof respuesta !== 'object') {
                  // Establecer valores a cero para evitar que se queden cargando
                  $("#saldo_cartera").html('C$ 0.00');
                  $("#clientes_activos").html('0');
                  $("#monto_en_mora").html('C$ 0.00');
                  $("#porcentaje_mora").html('0.00%');
                  return;
                }
                
                $("#saldo_cartera").html('C$ ' + parseFloat(respuesta.saldo_cartera || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#clientes_activos").html(respuesta.clientes_activos || 0);
                $("#monto_en_mora").html('C$ ' + parseFloat(respuesta.monto_en_mora || 0).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
                $("#porcentaje_mora").html(parseFloat(respuesta.porcentaje_mora || 0).toFixed(2) + '%');
            },
            error: function(xhr, status, error) {
                // Mostrar valores por defecto en caso de error
                $("#saldo_cartera").html('C$ 0.00');
                $("#clientes_activos").html('0');
                $("#monto_en_mora").html('C$ 0.00');
                $("#porcentaje_mora").html('0.00%');
            }
        });
      }
  </script>