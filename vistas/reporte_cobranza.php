<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Reporte de Cobranza Diaria</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Reportes</li>
                    <li class="breadcrumb-item active">Cobranza Diaria</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content pb-2">
    <div class="container-fluid">
        <div class="row p-0 m-0">
            <div class="col-md-12">
                <div class="card card-info card-outline shadow">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title">Filtro de Cobranza Diaria</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fecha_reporte"><span class="small">Fecha de Cobranza:</span></label>
                                    <input type="date" class="form-control form-control-sm" id="fecha_reporte" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-8 d-flex flex-row align-items-center justify-content-end">
                                <div class="form-group m-0">
                                    <a class="btn btn-info btn-sm" style="width:120px;" id="btnGenerarReporte"><i class="fas fa-search"></i> Generar</a>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-12 table-responsive">
                            <table id="tbl_reporte_cobranza_diaria" class="table display table-hover text-nowrap compact w-100 rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>Promotor</th>
                                        <th>Código</th>
                                        <th>Nombre Cliente</th>
                                        <th>Dirección</th>
                                        <th>Teléfono</th>
                                        <th>Fecha</th>
                                        <th>Principal</th>
                                        <th>Int. Mora</th>
                                        <th>Int. Cte.</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody class="small text-left">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var tbl_reporte_cobranza_diaria;

    $(document).ready(function() {
        // Inicializar DataTable
        tbl_reporte_cobranza_diaria = $("#tbl_reporte_cobranza_diaria").DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                    "extend": 'excelHtml5',
                    "title": 'Reporte de Cobranza Diaria',
                    "exportOptions": {
                        'columns': ':visible'
                    },
                    "text": '<i class="fa fa-file-excel"></i>',
                    "titleAttr": 'Exportar a Excel'
                },
                {
                    "extend": 'print',
                    "text": '<i class="fa fa-print"></i> ',
                    "titleAttr": 'Imprimir',
                    "exportOptions": {
                        'columns': ':visible'
                    },
                    "download": 'open'
                },
                'pageLength',
            ],
            ajax: {
                url: "ajax/reportes_ajax.php",
                type: "POST",
                data: function(d) {
                    d.accion = 'reporte_cobranza_diaria';
                    d.fecha = $('#fecha_reporte').val();
                },
                dataSrc: ""
            },
            columns: [
                { data: 'promotor' },
                { data: 'cliente_id' },
                { data: 'cliente_nombres' },
                { data: 'cliente_direccion' },
                { data: 'cliente_celular' },
                { data: 'fecha' },
                { data: 'principal', render: $.fn.dataTable.render.number(',', '.', 2, 'C$ ') },
                { data: 'int_mora', render: $.fn.dataTable.render.number(',', '.', 2, 'C$ ') },
                { data: 'int_cte', render: $.fn.dataTable.render.number(',', '.', 2, 'C$ ') },
                { data: 'total', render: $.fn.dataTable.render.number(',', '.', 2, 'C$ ') }
            ],
            lengthMenu: [5, 10, 15, 20, 50],
            "pageLength": 10,
            "language": idioma_espanol,
            select: true
        });

        // Evento para el botón Generar reporte
        $("#btnGenerarReporte").on("click", function() {
            tbl_reporte_cobranza_diaria.ajax.reload();
        });

        // Cargar reporte al iniciar
        tbl_reporte_cobranza_diaria.ajax.reload();

    });
</script> 