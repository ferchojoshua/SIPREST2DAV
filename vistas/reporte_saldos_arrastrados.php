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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Rango de Fechas:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="rango_fechas_saldos">
                                    </div>
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
    // Inicializar el Daterangepicker
    $('#rango_fechas_saldos').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY',
            "applyLabel": "Aplicar",
            "cancelLabel": "Cancelar",
            "fromLabel": "Desde",
            "toLabel": "Hasta",
            "customRangeLabel": "Rango Personalizado",
            "daysOfWeek": ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
            "monthNames": ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            "firstDay": 1
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
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
    });

    // Evento del botón buscar
    $("#btn_buscar_saldos").on('click', function() {
        var rango_fechas = $("#rango_fechas_saldos").val().split(' - ');
        var fecha_inicio = moment(rango_fechas[0], 'DD/MM/YYYY').format('YYYY-MM-DD');
        var fecha_fin = moment(rango_fechas[1], 'DD/MM/YYYY').format('YYYY-MM-DD');

        // Aquí iría la llamada AJAX para obtener los datos
        // Por ahora, mostraremos datos de ejemplo
        
        // Simulación de llamada AJAX
        $.ajax({
            url: 'ajax/reportes_ajax.php', // Este archivo aún no existe
            type: 'POST',
            data: {
                accion: 'reporte_saldos_arrastrados',
                fecha_inicio: fecha_inicio,
                fecha_fin: fecha_fin
            },
            dataType: 'json',
            success: function(data) {
                tblReporteSaldos.clear().draw();
                if (data && data.length > 0) {
                    tblReporteSaldos.rows.add(data).draw();
                } else {
                    // Manejar caso sin datos
                }
            },
            error: function(xhr, status, error) {
                // Manejar error
                console.error("Error en AJAX:", status, error);
            }
        });
    });
});
</script> 