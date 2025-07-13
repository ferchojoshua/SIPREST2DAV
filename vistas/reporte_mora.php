<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Reporte por Mora</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte por Mora</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content pb-2">
    <div class="container-fluid">
        <div class="row p-0 m-0">
            <div class="col-md-12">
                <div class="card card-info card-outline shadow ">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title">Listado de Clientes Morosos</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-12 table-responsive">
                            <table id="tbl_reporte_morosos" class="table display table-hover text-nowrap compact w-100 rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>ID Cliente</th>
                                        <th>Cliente</th>
                                        <th>Nro. Préstamo</th>
                                        <th>Nro. Cuota</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Monto Cuota</th>
                                        <th>Saldo Cuota</th>
                                        <th>Días de Mora</th>
                                        <th>Cuotas Pendientes</th>
                                        <th>Moneda</th>
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
$(document).ready(function() {
    var tablaReporteMorosos;

    function cargarReporteMorosos() {
        if (tablaReporteMorosos) {
            tablaReporteMorosos.destroy();
        }

        var data = new FormData();
        data.append('accion', 7); // ID para Reporte Morosos en ajax/reportes_ajax.php

        $.ajax({
            url: "ajax/reportes_ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(respuesta) {
                console.log("Respuesta Reporte Morosos:", respuesta);
                var dataSet = [];
                if (respuesta && !respuesta.error) {
                    $.each(respuesta, function(index, item) {
                        dataSet.push([
                            item.cliente_id,
                            item.cliente_nombres,
                            item.nro_prestamo,
                            item.pdetalle_nro_cuota,
                            item.fecha_vencimiento,
                            parseFloat(item.pdetalle_monto_cuota).toFixed(2),
                            parseFloat(item.pdetalle_saldo_cuota).toFixed(2),
                            item.dias_mora,
                            item.cuotas_pendientes_prestamo,
                            item.moneda_simbolo
                        ]);
                    });
                } else if (respuesta.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.error,
                    });
                }

                tablaReporteMorosos = $('#tbl_reporte_morosos').DataTable({
                    data: dataSet,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                    "responsive": true,
                    "autoWidth": false,
                    "deferRender": true,
                    "retrieve": true,
                    "dom": '<"row"<"col-sm-5"l><"col-sm-7"f>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-6"i><"col-sm-6"p>>' +
                           'Bfrtip',
                    "buttons": [
                        'copy', 'csv', 'excel', 'print'
                    ],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown, jqXHR.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo cargar el reporte de morosos. Intenta de nuevo más tarde.',
                });
            }
        });
    }

    // Cargar reporte al inicio
    cargarReporteMorosos();

    // Cambiar el estilo de los botones de DataTables
    $('.dt-buttons').addClass('btn-group-sm'); // Agrega la clase 'btn-group-sm' al contenedor de botones
});
</script> 