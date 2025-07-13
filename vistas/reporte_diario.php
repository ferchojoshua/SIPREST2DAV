<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">   
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Reporte Ingreso e Egresos</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte Ingreso e Egreso</li>
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
                        <h3 class="card-title">Reporte Ingreso e Egreso</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fechaReporteDiario">Seleccionar Fecha:</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaReporteDiario" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" id="btnFiltrarReporteDiario">Filtrar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 table-responsive">
                            <table id="tbl_reporte_diario" class="table display table-hover text-nowrap compact w-100 rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>Tipo Operación</th>
                                        <th>Cantidad</th>
                                        <th>Monto Capital</th>
                                        <th>Monto Interés</th>
                                        <th>Monto Total</th>
                                        <th>Símbolo Moneda</th>
                                        <th>Nombre Moneda</th>
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
    var tablaReporteDiario;

    function cargarReporteDiario(fecha) {
        if (tablaReporteDiario) {
            tablaReporteDiario.destroy();
        }

        var data = new FormData();
        data.append('accion', 10); // ID para Reporte Diario en ajax/reportes_ajax.php
        data.append('fecha', fecha);

        $.ajax({
            url: "ajax/reportes_ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(respuesta) {
                console.log("Respuesta Reporte Diario:", respuesta);
                var dataSet = [];
                if (respuesta && !respuesta.error) {
                    $.each(respuesta, function(index, item) {
                        dataSet.push([
                            item.tipo_operacion,
                            item.cantidad,
                            parseFloat(item.monto_capital).toFixed(2),
                            parseFloat(item.monto_interes).toFixed(2),
                            parseFloat(item.monto_total).toFixed(2),
                            item.moneda_simbolo,
                            item.moneda_nombre
                        ]);
                    });
                } else if (respuesta.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.error,
                    });
                }

                tablaReporteDiario = $('#tbl_reporte_diario').DataTable({
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
                    text: 'No se pudo cargar el reporte diario. Intenta de nuevo más tarde.',
                });
            }
        });
    }

    // Cargar reporte al inicio con la fecha actual
    cargarReporteDiario($('#fechaReporteDiario').val());

    $('#btnFiltrarReporteDiario').on('click', function() {
        var fechaSeleccionada = $('#fechaReporteDiario').val();
        cargarReporteDiario(fechaSeleccionada);
    });

    // Cambiar el estilo de los botones de DataTables
    $('.dt-buttons').addClass('btn-group-sm'); // Agrega la clase 'btn-group-sm' al contenedor de botones

});
</script> 