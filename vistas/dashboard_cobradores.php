<!-- No se necesita el bloque PHP de sesión aquí, la plantilla principal ya lo gestiona -->

<!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="dashboard-header">
                <div class="row align-items-center">
                    <div class="col-sm-8">
                        <h1 class="m-0">
                            <i class="fas fa-chart-pie"></i>
                            Dashboard de Cobradores
                        </h1>
                        <p class="mb-0">Análisis de desempeño y comparativas de cobros</p>
                    </div>
                    <div class="col-sm-4 text-right">
                        <button class="refresh-btn" id="refreshData" title="Actualizar datos">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <span class="ml-2">
                            <small>Última actualización: <span id="lastUpdate">--:--</span></small>
                        </span>
                    </div>
                </div>
                
                <!-- BREADCRUMB Y NAVEGACIÓN -->
                <div class="row mt-2">
                    <div class="col-sm-12">
                        <ol class="breadcrumb float-sm-right bg-transparent">
                            <li class="breadcrumb-item">
                                <a href="#" onclick="CargarContenido('vistas/dashboard.php','content-wrapper')" 
                                   class="text-primary" title="Ver dashboard ejecutivo">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard Ejecutivo
                                </a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard Cobradores</li>
                        </ol>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid dashboard-content">
            <!-- Filtros -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card card-outline card-primary collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i>
                                Filtros Avanzados
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Sucursal</label>
                                        <select class="form-control select2" id="filtro_sucursal" name="filtro_sucursal">
                                            <!-- Opciones se cargan dinámicamente -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Ruta</label>
                                        <select class="form-control select2" id="filtro_ruta" name="filtro_ruta">
                                            <option value="">Todas las rutas</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cobrador</label>
                                        <select class="form-control select2" id="filtro_cobrador" name="filtro_cobrador">
                                            <option value="">Todos los cobradores</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Período</label>
                                        <select class="form-control select2" id="filtro_periodo" name="filtro_periodo">
                                            <option value="hoy">Hoy</option>
                                            <option value="semana" selected>Esta Semana</option>
                                            <option value="mes">Este Mes</option>
                                            <option value="trimestre">Este Trimestre</option>
                                            <option value="anio">Este Año</option>
                                            <option value="personalizado">Personalizado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="rango_fechas" style="display: none;">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha Inicio</label>
                                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fecha Fin</label>
                                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary" id="btn_aplicar_filtros">
                                        <i class="fas fa-search"></i> Aplicar Filtros
                                    </button>
                                    <button class="btn btn-secondary" id="btn_limpiar_filtros">
                                        <i class="fas fa-eraser"></i> Limpiar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MÉTRICAS PRINCIPALES DE COBRANZA -->
            <div class="row mb-4">
                <!-- Cobranza del Día -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 id="cobranza_dia">$--</h3>
                            <p>Cobranza del Día</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <a href="#" class="small-box-footer" onclick="CargarContenido('vistas/reportes_financieros.php','content-wrapper')">
                            Ver Detalle <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Clientes en Mora -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 id="clientes_mora">--</h3>
                            <p>Clientes en Mora</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="#" class="small-box-footer" onclick="CargarContenido('vistas/reportes_financieros.php','content-wrapper')">
                            Ver Reporte <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Efectividad de Cobro -->
                <div class="col-lg-4 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 id="efectividad_cobro">--%</h3>
                            <p>Efectividad de Cobro</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <a href="#" class="small-box-footer">
                            Esta Semana <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- GRÁFICOS Y ESTADÍSTICAS -->
            <div class="row">
                <!-- Gráfico de Cobros por Cobrador -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie"></i>
                                Distribución de Cobros
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="cobrosChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Gráfico de Mora por Cobrador -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie"></i>
                                Distribución de Mora
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="moraChart" style="min-height: 250px; height: 250px; max-height: 250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Comparación Mensual -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line"></i>
                                Comparación Mensual
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="comparacionChart" style="min-height: 300px; height: 300px; max-height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

</div>

<!-- Scripts específicos del dashboard -->
<script src="vistas/assets/dist/js/dashboard-charts.js"></script>
<script>
    $(document).ready(function() {
        // Inicializar componentes solo si no están ya inicializados
        if (!$('#filtro_sucursal').data('select2')) {
            inicializarFiltros();
            inicializarEventos();
        }
        cargarDashboard();
        
        // Auto-actualización
        if (window.updateInterval) {
            clearInterval(window.updateInterval);
        }
        window.updateInterval = setInterval(cargarDashboard, 300000); // 5 minutos
    });

    function inicializarFiltros() {
        // Inicializar Select2 para filtros
        $('#filtro_sucursal, #filtro_ruta, #filtro_cobrador, #filtro_periodo').select2({
            width: '100%',
            placeholder: function() {
                return $(this).attr('placeholder') || 'Seleccionar...';
            }
        });

        // Cargar sucursales
        cargarSucursales();
        
        // Mostrar/ocultar rango de fechas personalizado
        $('#filtro_periodo').on('change', function() {
            if ($(this).val() === 'personalizado') {
                $('#rango_fechas').show();
            } else {
                $('#rango_fechas').hide();
            }
        });
    }

    function inicializarEventos() {
        // Botón aplicar filtros
        $('#btn_aplicar_filtros').on('click', function() {
            cargarDashboard();
        });

        // Botón limpiar filtros
        $('#btn_limpiar_filtros').on('click', function() {
            $('#filtro_sucursal, #filtro_ruta, #filtro_cobrador').val(null).trigger('change');
            $('#filtro_periodo').val('semana').trigger('change');
            cargarDashboard();
        });

        // Refresh manual
        $('#refreshData').on('click', function() {
            $(this).find('i').addClass('fa-spin');
            cargarDashboard();
            setTimeout(() => {
                $(this).find('i').removeClass('fa-spin');
            }, 1000);
        });

        // Cambio de sucursal carga rutas
        $('#filtro_sucursal').on('change', function() {
            const sucursalId = $(this).val();
            cargarRutasPorSucursal(sucursalId);
        });

        // Cambio de ruta carga cobradores
        $('#filtro_ruta').on('change', function() {
            const rutaId = $(this).val();
            cargarCobradoresPorRuta(rutaId);
        });
    }
    
    function cargarDashboard() {
        actualizarMetricasPrincipales();
        actualizarGraficos();
        actualizarTimestamp();
    }

    function actualizarMetricasPrincipales() {
        console.log('[Dashboard Cobradores] Actualizando métricas principales...');
        // TODO: Implementar llamada AJAX real para métricas
        // Por ahora, valores simulados o placeholders se mantienen
        $.ajax({
            url: 'ajax/dashboard_cobradores_ajax.php', // Asumiendo un nuevo AJAX para cobradores
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'obtener_metricas_cobradores',
                sucursal_id: $('#filtro_sucursal').val(),
                ruta_id: $('#filtro_ruta').val(),
                cobrador_id: $('#filtro_cobrador').val(),
                periodo: $('#filtro_periodo').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val()
            },
            success: function(response) {
                console.log('[Dashboard Cobradores] Respuesta métricas principales:', response);
                if (response.success) {
                    $('#cobranza_dia').text(response.cobranza_dia || '$--');
                    $('#clientes_mora').text(response.clientes_mora || '--');
                    $('#efectividad_cobro').text(response.efectividad_cobro || '--%');
                } else {
                    console.error('[Dashboard Cobradores] Error al cargar métricas:', response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('[Dashboard Cobradores] Error AJAX métricas:', error);
                console.error('[Dashboard Cobradores] Respuesta del servidor métricas:', xhr.responseText);
            }
        });
    }

    function actualizarGraficos() {
        console.log('[Dashboard Cobradores] Actualizando gráficos...');
        // TODO: Implementar llamada AJAX real para gráficos
        $.ajax({
            url: 'ajax/dashboard_cobradores_ajax.php', // Asumiendo un nuevo AJAX para cobradores
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'obtener_graficos_cobradores',
                sucursal_id: $('#filtro_sucursal').val(),
                ruta_id: $('#filtro_ruta').val(),
                cobrador_id: $('#filtro_cobrador').val(),
                periodo: $('#filtro_periodo').val(),
                fecha_inicio: $('#fecha_inicio').val(),
                fecha_fin: $('#fecha_fin').val()
            },
            success: function(response) {
                console.log('[Dashboard Cobradores] Respuesta gráficos:', response);
                // Lógica para actualizar los gráficos (cobrosChart, moraChart, comparacionChart)
            },
            error: function(xhr, status, error) {
                console.error('[Dashboard Cobradores] Error AJAX gráficos:', error);
                console.error('[Dashboard Cobradores] Respuesta del servidor gráficos:', xhr.responseText);
            }
        });
    }

    function actualizarTimestamp() {
        const ahora = new Date();
        const opciones = { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: false 
        };
        $('#lastUpdate').text(ahora.toLocaleTimeString('es-ES', opciones));
    }

    // Función para cargar sucursales específica del dashboard
    function cargarSucursales(selector = '#filtro_sucursal') {
        console.log('[Dashboard] Cargando sucursales...');
        $.ajax({
            url: 'ajax/aprobacion_ajax.php',
            type: 'GET',
            data: { accion: 'listar_sucursales' },
            dataType: 'json',
            success: function(response) {
                const select = $(selector);
                select.empty().append('<option value="">-- Todas las Sucursales --</option>');
                
                console.log('[Dashboard] Respuesta de sucursales:', response);
                
                if (response && Array.isArray(response) && response.length > 0) {
                    response.forEach(function(sucursal) {
                        // Usar los nombres de campos correctos de la respuesta
                        const sucursalId = sucursal.sucursal_id || sucursal.id;
                        const sucursalNombre = sucursal.sucursal_nombre || sucursal.nombre;
                        const sucursalTexto = sucursal.texto_descriptivo || sucursal.texto_completo || sucursalNombre;
                        
                        if (sucursalId && sucursalNombre) {
                            select.append(`<option value="${sucursalId}">${sucursalTexto}</option>`);
                        }
                    });
                    console.log(`[Dashboard] ✅ Cargadas ${response.length} sucursales`);
                } else {
                    console.warn('[Dashboard] No se encontraron sucursales o respuesta inválida');
                }
            },
            error: function(xhr, status, error) {
                console.error('[Dashboard] Error al cargar sucursales:', error);
                console.error('[Dashboard] Respuesta del servidor:', xhr.responseText);
                $(selector).empty().append('<option value="">Error al cargar sucursales</option>');
            }
        });
    }

    // Función para cargar rutas por sucursal específica del dashboard
    function cargarRutasPorSucursal(sucursalId, selector = '#filtro_ruta') {
        console.log('[Dashboard] Cargando rutas para sucursal:', sucursalId);
        const selectRuta = $(selector);
        
        if (!sucursalId) {
            selectRuta.empty().append('<option value="">-- Todas las Rutas --</option>');
            $('#filtro_cobrador').empty().append('<option value="">-- Todos los Cobradores --</option>');
            return;
        }

        $.ajax({
            url: 'ajax/aprobacion_ajax.php',
            type: 'POST',
            data: { 
                accion: 'listar_rutas_sucursal',
                sucursal_id: sucursalId 
            },
            dataType: 'json',
            success: function(response) {
                selectRuta.empty().append('<option value="">-- Todas las Rutas --</option>');
                
                if (response && Array.isArray(response) && response.length > 0) {
                    response.forEach(function(ruta) {
                        const textoRuta = ruta.texto_descriptivo || ruta.ruta_nombre || `Ruta ${ruta.ruta_id}`;
                        selectRuta.append(`<option value="${ruta.id}">${ruta.nombre_ruta}</option>`);
                    });
                    console.log(`[Dashboard] ✅ Cargadas ${response.length} rutas`);
                } else {
                    console.warn('[Dashboard] No se encontraron rutas para la sucursal');
                }
                
                // Limpiar combo de cobradores cuando cambia la sucursal
                $('#filtro_cobrador').empty().append('<option value="">-- Todos los Cobradores --</option>');
            },
            error: function(xhr, status, error) {
                console.error('[Dashboard] Error al cargar rutas:', error);
                selectRuta.empty().append('<option value="">Error al cargar rutas</option>');
            }
        });
    }

    // Función para cargar cobradores por ruta específica del dashboard
    function cargarCobradoresPorRuta(rutaId, selector = '#filtro_cobrador') {
        console.log('[Dashboard] Cargando cobradores para ruta:', rutaId);
        const selectCobrador = $(selector);
        
        if (!rutaId) {
            selectCobrador.empty().append('<option value="">-- Todos los Cobradores --</option>');
            return;
        }

        // Para el dashboard, cargaremos todos los cobradores activos en lugar de por ruta específica
        // ya que el endpoint de cobradores por ruta podría no estar implementado
        $.ajax({
            url: 'ajax/aprobacion_ajax.php',
            type: 'GET',
            data: { accion: 'listar_cobradores' },
            dataType: 'json',
            success: function(response) {
                selectCobrador.empty().append('<option value="">-- Todos los Cobradores --</option>');
                
                if (response.estado === 'ok' && Array.isArray(response.data) && response.data.length > 0) {
                    response.data.forEach(function(cobrador) {
                        selectCobrador.append(`<option value="${cobrador.id_usuario}">${cobrador.nombre_usuario}</option>`);
                    });
                    console.log(`[Dashboard] ✅ Cargados ${response.data.length} cobradores`);
                } else if (response.estado === 'info') {
                    console.info('[Dashboard] Info:', response.mensaje);
                } else {
                    console.warn('[Dashboard] No se encontraron cobradores');
                }
            },
            error: function(xhr, status, error) {
                console.error('[Dashboard] Error al cargar cobradores:', error);
                selectCobrador.empty().append('<option value="">Error al cargar cobradores</option>');
            }
        });
    }
</script> 