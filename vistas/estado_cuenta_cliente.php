<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Estado de Cuenta por Cliente</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Estado de Cuenta por Cliente</li>
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
                        <h3 class="card-title">Estado de Cuenta Detallado</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="selectClientesEstadoCuenta">Seleccionar Cliente:</label>
                                    <select class="form-control form-control-sm" id="selectClientesEstadoCuenta" style="width: 100%;"></select>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" id="btnFiltrarEstadoCuenta">Filtrar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 table-responsive">
                            <table id="tbl_estado_cuenta_cliente" class="table display table-hover text-nowrap compact w-100 rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>Nro. Préstamo</th>
                                        <th>Cliente</th>
                                        <th>DNI</th>
                                        <th>Monto</th>
                                        <th>Interés (%)</th>
                                        <th>Monto Interés</th>
                                        <th>Monto Total</th>
                                        <th>Monto Cuota</th>
                                        <th>Cuotas</th>
                                        <th>Cuotas Pagadas</th>
                                        <th>Fecha Registro</th>
                                        <th>Fecha Emisión</th>
                                        <th>Estado</th>
                                        <th>Forma Pago</th>
                                        <th>Símbolo Moneda</th>
                                        <th>Nombre Moneda</th>
                                        <th>Usuario</th>
                                        <th>Saldo Pendiente</th>
                                        <th>Monto Pagado</th>
                                        <th>Cuotas Pendientes</th>
                                        <th>% Avance</th>
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
    var tablaEstadoCuentaCliente;

    // Cargar clientes en el select2
    $.ajax({
        url: "ajax/clientes_ajax.php",
        type: "POST",
        data: { 'accion': 4 }, // Acción para listar clientes en select2
        dataType: 'json',
        success: function(respuesta) {
            var opciones = '<option value="">Seleccione un Cliente</option>';
            $.each(respuesta, function(index, cliente) {
                opciones += '<option value="' + cliente.cliente_id + '">' + cliente.cliente_nombres + '</option>';
            });
            $('#selectClientesEstadoCuenta').html(opciones).select2();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error al cargar clientes:", textStatus, errorThrown, jqXHR.responseText);
        }
    });

    function cargarEstadoCuenta(cliente_id) {
        if (tablaEstadoCuentaCliente) {
            tablaEstadoCuentaCliente.destroy();
        }

        var data = new FormData();
        data.append('accion', 11); // ID para Estado de Cuenta por Cliente en ajax/reportes_ajax.php
        data.append('cliente_id', cliente_id);

        $.ajax({
            url: "ajax/reportes_ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(respuesta) {
                console.log("Respuesta Estado de Cuenta:", respuesta);
                var dataSet = [];
                if (respuesta && !respuesta.error) {
                    $.each(respuesta, function(index, item) {
                        dataSet.push([
                            item.nro_prestamo,
                            item.cliente_nombres,
                            item.cliente_dni,
                            parseFloat(item.pres_monto).toFixed(2),
                            parseFloat(item.pres_interes).toFixed(2),
                            parseFloat(item.pres_monto_interes).toFixed(2),
                            parseFloat(item.pres_monto_total).toFixed(2),
                            parseFloat(item.pres_monto_cuota).toFixed(2),
                            item.pres_cuotas,
                            item.pres_cuotas_pagadas,
                            item.fecha_registro,
                            item.fecha_emision,
                            item.estado,
                            item.fpago_descripcion,
                            item.moneda_simbolo,
                            item.moneda_nombre,
                            item.usuario,
                            parseFloat(item.saldo_pendiente).toFixed(2),
                            parseFloat(item.monto_pagado).toFixed(2),
                            item.cuotas_pendientes,
                            parseFloat(item.porcentaje_avance).toFixed(2)
                        ]);
                    });
                } else if (respuesta.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.error,
                    });
                }

                tablaEstadoCuentaCliente = $('#tbl_estado_cuenta_cliente').DataTable({
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
                    text: 'No se pudo cargar el estado de cuenta. Intenta de nuevo más tarde.',
                });
            }
        });
    }

    $('#btnFiltrarEstadoCuenta').on('click', function() {
        var clienteSeleccionado = $('#selectClientesEstadoCuenta').val();
        if (clienteSeleccionado) {
            cargarEstadoCuenta(clienteSeleccionado);
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor, seleccione un cliente para generar el estado de cuenta.',
            });
        }
    });

    // Cambiar el estilo de los botones de DataTables
    $('.dt-buttons').addClass('btn-group-sm'); // Agrega la clase 'btn-group-sm' al contenedor de botones
});
</script> 