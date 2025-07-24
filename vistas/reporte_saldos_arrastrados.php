<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Reporte de Saldos Arrastrados</h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item">Reportes</li>
                    <li class="breadcrumb-item active">Reporte de Saldos Arrastrados</li>
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
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Criterios de Búsqueda</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-alt text-info"></i> Fecha Inicio:</label>
                                    <input type="date" class="form-control" id="fecha_inicio_saldos" value="<?php echo date('Y-m-01'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-check text-info"></i> Fecha Fin:</label>
                                    <input type="date" class="form-control" id="fecha_fin_saldos" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-week text-info"></i> Rango Rápido:</label>
                                    <select class="form-control" id="rango_rapido_saldos">
                                        <option value="">Seleccionar período...</option>
                                        <option value="hoy">📅 Hoy</option>
                                        <option value="ayer">📅 Ayer</option>
                                        <option value="semana">📊 Esta semana</option>
                                        <option value="mes" selected>📊 Este mes</option>
                                        <option value="mes_anterior">📊 Mes anterior</option>
                                        <option value="trimestre">📊 Este trimestre</option>
                                        <option value="ano">📊 Este año</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button class="btn btn-info btn-block" id="btn_buscar_saldos">
                                        <i class="fas fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Resultados de la Búsqueda</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl_reporte_saldos" class="table table-striped w-100 shadow">
                                <thead class="bg-info">
                                    <tr>
                                        <th>ID Log</th>
                                        <th>Nro. Préstamo</th>
                                        <th>Cuota Origen</th>
                                        <th>Cuota Destino</th>
                                        <th>Monto Arrastrado</th>
                                        <th>Fecha de Movimiento</th>
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
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<script>
$(document).ready(function() {
    // Manejar rango rápido
    $('#rango_rapido_saldos').on('change', function() {
        var rango = $(this).val();
        var fechaInicio, fechaFin;
        var hoy = new Date();
        
        switch(rango) {
            case 'hoy':
                fechaInicio = fechaFin = hoy.toISOString().split('T')[0];
                break;
            case 'ayer':
                var ayer = new Date(hoy);
                ayer.setDate(hoy.getDate() - 1);
                fechaInicio = fechaFin = ayer.toISOString().split('T')[0];
                break;
            case 'semana':
                var inicioSemana = new Date(hoy);
                inicioSemana.setDate(hoy.getDate() - hoy.getDay());
                fechaInicio = inicioSemana.toISOString().split('T')[0];
                fechaFin = hoy.toISOString().split('T')[0];
                break;
            case 'mes':
                fechaInicio = hoy.getFullYear() + '-' + String(hoy.getMonth() + 1).padStart(2, '0') + '-01';
                fechaFin = hoy.toISOString().split('T')[0];
                break;
            case 'mes_anterior':
                var mesAnterior = new Date(hoy);
                mesAnterior.setMonth(hoy.getMonth() - 1);
                fechaInicio = mesAnterior.getFullYear() + '-' + String(mesAnterior.getMonth() + 1).padStart(2, '0') + '-01';
                var ultimoDiaMesAnterior = new Date(hoy.getFullYear(), hoy.getMonth(), 0);
                fechaFin = ultimoDiaMesAnterior.toISOString().split('T')[0];
                break;
            case 'trimestre':
                var mesActual = hoy.getMonth();
                var inicioTrimestre = Math.floor(mesActual / 3) * 3;
                fechaInicio = hoy.getFullYear() + '-' + String(inicioTrimestre + 1).padStart(2, '0') + '-01';
                fechaFin = hoy.toISOString().split('T')[0];
                break;
            case 'ano':
                fechaInicio = hoy.getFullYear() + '-01-01';
                fechaFin = hoy.toISOString().split('T')[0];
                break;
        }
        
        if (fechaInicio && fechaFin) {
            $('#fecha_inicio_saldos').val(fechaInicio);
            $('#fecha_fin_saldos').val(fechaFin);
        }
    });

    // Inicializar la tabla de resultados
    var tblReporteSaldos = $("#tbl_reporte_saldos").DataTable({
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [{
            "extend": 'excelHtml5',
            "title": 'Reporte de Saldos Arrastrados',
            "exportOptions": {
                "columns": [1, 2, 3, 4, 5]
            }
        }, {
            "extend": 'print',
            "title": 'Reporte de Saldos Arrastrados',
            "exportOptions": {
                "columns": [1, 2, 3, 4, 5]
            }
        }, 'pageLength'],
        "language": {
            "url": "vistas/assets/plugins/datatables/i18n/Spanish.json"
        }
    });

    // Evento del botón buscar
    $("#btn_buscar_saldos").on('click', function() {
        var fecha_inicio = $("#fecha_inicio_saldos").val();
        var fecha_fin = $("#fecha_fin_saldos").val();
        
        if (!fecha_inicio || !fecha_fin) {
            Swal.fire('Error', 'Por favor seleccione ambas fechas.', 'warning');
            return;
        }
        
        if (fecha_inicio > fecha_fin) {
            Swal.fire('Error', 'La fecha de inicio no puede ser mayor a la fecha fin.', 'warning');
            return;
        }
        
        // Mostrar indicador de carga
        Swal.fire({
            title: 'Consultando...',
            text: 'Obteniendo saldos arrastrados.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        // Aquí iría la llamada AJAX para obtener los datos
        // Por ahora, mostraremos datos de ejemplo
        
        // Llamada AJAX
        $.ajax({
            url: 'ajax/reportes_ajax.php',
            type: 'POST',
            data: {
                accion: 'reporte_saldos_arrastrados',
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin
            },
            dataType: 'json',
            success: function(data) {
                Swal.close();
                tblReporteSaldos.clear().draw();
                if (data && data.length > 0) {
                    tblReporteSaldos.rows.add(data).draw();
                    Swal.fire('Éxito', `Se encontraron ${data.length} registros de saldos arrastrados.`, 'success');
                } else {
                    Swal.fire('Sin resultados', 'No se encontraron saldos arrastrados en el período seleccionado.', 'info');
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error("Error en AJAX:", status, error);
                Swal.fire('Error', 'Error al consultar los datos. Intente nuevamente.', 'error');
            }
        });
    });
});
</script> 