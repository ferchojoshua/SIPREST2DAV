<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Notas de Débito</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Notas de Débito</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content pb-2">
    <div class="container-fluid">
        <div class="row p-0 m-0">
            <div class="col-md-12">
                <div class="card card-info card-outline shadow">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title">Listado de Notas de Débito</h3>
                        <button class="btn btn-info btn-sm float-right" id="abrirmodal_nota_debito">
                            <i class="fas fa-plus"></i> Nueva Nota
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl_notas_debito" class="table display table-hover text-nowrap compact w-100 rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>Nro. Nota</th>
                                        <th>Fecha</th>
                                        <th>Nro. Préstamo</th>
                                        <th>Cliente</th>
                                        <th>Monto Original</th>
                                        <th>Nuevo Monto</th>
                                        <th>Estado</th>
                                        <th class="text-center">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="small text left">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Registrar Nota de Débito -->
<div class="modal fade" id="modal_registro_nota_debito" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray py-1 align-items-center">
                <h5 class="modal-title">Registro de Nota de Débito</h5>
                <button type="button" class="close text-white border-0 fs-5" id="btncerrarmodal_nota" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label class="">
                                    <span class="small">Nro. Préstamo</span><span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control form-control-sm" id="text_nro_prestamo" placeholder="Nro. Préstamo" required>
                                <div class="invalid-feedback">Debe ingresar el número de préstamo</div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label class="">
                                    <span class="small">Nuevo Monto</span><span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control form-control-sm" id="text_nuevo_monto" placeholder="Nuevo Monto" required>
                                <div class="invalid-feedback">Debe ingresar el nuevo monto</div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label class="">
                                    <span class="small">Motivo</span><span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control form-control-sm" id="text_motivo" rows="3" placeholder="Motivo de la nota de débito" required></textarea>
                                <div class="invalid-feedback">Debe ingresar el motivo</div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-info btn-sm" id="btnregistrar_nota">Registrar</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Inicializar DataTable con manejo de errores
    var table = $('#tbl_notas_debito').DataTable({
        ajax: {
            url: 'ajax/notas_debito_ajax.php',
            type: 'POST',
            data: function(d) {
                return {
                    'accion': 'listar_notas'
                };
            },
            dataSrc: function(json) {
                // Verificar si la respuesta es válida
                if (!json || !json.data) {
                    console.error('Respuesta inválida:', json);
                    return [];
                }
                return json.data;
            },
            error: function(xhr, error, thrown) {
                console.error('Error en la llamada AJAX:', error);
                console.error('Detalles:', thrown);
                console.error('Respuesta:', xhr.responseText);
            }
        },
        columns: [
            { data: 'nro_nota' },
            { data: 'fecha' },
            { data: 'nro_prestamo' },
            { data: 'cliente' },
            { 
                data: 'monto_original',
                render: function(data, type, row) {
                    return 'S/ ' + (data || '0.00');
                }
            },
            { 
                data: 'nuevo_monto',
                render: function(data, type, row) {
                    return 'S/ ' + (data || '0.00');
                }
            },
            { 
                data: 'estado',
                render: function(data, type, row) {
                    if (data === 'ACTIVO') {
                        return '<span class="badge badge-success">ACTIVO</span>';
                    } else {
                        return '<span class="badge badge-danger">ANULADO</span>';
                    }
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    let buttons = `
                        <div class="text-center">
                            <button class="btn btn-info btn-sm" onclick="verNota('${row.nro_nota || ''}')">
                                <i class="fas fa-eye"></i>
                            </button>`;
                    
                    if (row.estado === 'ACTIVO') {
                        buttons += `
                            <button class="btn btn-danger btn-sm" onclick="anularNota('${row.nro_nota || ''}')">
                                <i class="fas fa-times"></i>
                            </button>`;
                    }
                    
                    buttons += `</div>`;
                    return buttons;
                }
            }
        ],
        order: [[1, 'desc']],
        language: {
            url: 'vistas/assets/plugins/datatables/i18n/Spanish.json',
            loadingRecords: 'Cargando...',
            processing: 'Procesando...',
            emptyTable: 'No hay datos disponibles',
            zeroRecords: 'No se encontraron registros coincidentes'
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        processing: true,
        serverSide: false,
        pageLength: 10,
        responsive: true
    });

    // Manejar errores de DataTables
    table.on('error.dt', function(e, settings, techNote, message) {
        console.error('Error en DataTables:', message);
    });

    // Abrir modal de registro
    $('#abrirmodal_nota_debito').click(function() {
        $('#modal_registro_nota_debito').modal('show');
    });

    // Registrar nota de débito
    $('#btnregistrar_nota').click(function() {
        var form = $('.needs-validation')[0];
        if (form.checkValidity()) {
            var datos = new FormData();
            datos.append('accion', 'registrar_nota');
            datos.append('nro_prestamo', $('#text_nro_prestamo').val());
            datos.append('nuevo_monto', $('#text_nuevo_monto').val());
            datos.append('motivo', $('#text_motivo').val());

            $.ajax({
                url: 'ajax/notas_debito_ajax.php',
                method: 'POST',
                data: datos,
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta) {
                    if (respuesta == 'ok') {
                        Swal.fire({
                            position: 'center',
                            icon: 'success',
                            title: 'Se registró la nota de débito correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        table.ajax.reload();
                        $('#modal_registro_nota_debito').modal('hide');
                        limpiarFormulario();
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Error al registrar la nota de débito',
                            text: respuesta,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
            });
        }
        form.classList.add('was-validated');
    });

    // Funciones auxiliares
    function limpiarFormulario() {
        $('#text_nro_prestamo').val('');
        $('#text_nuevo_monto').val('');
        $('#text_motivo').val('');
        $('.needs-validation').removeClass('was-validated');
    }

    window.verNota = function(nro_nota) {
        window.location.href = 'MPDF/nota_debito.php?nro_nota=' + nro_nota;
    }

    window.anularNota = function(nro_nota) {
        Swal.fire({
            title: '¿Está seguro de anular esta nota de débito?',
            text: "Esta acción no se puede revertir",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, anular',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData();
                datos.append('accion', 'anular_nota');
                datos.append('nro_nota', nro_nota);

                $.ajax({
                    url: 'ajax/notas_debito_ajax.php',
                    method: 'POST',
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(respuesta) {
                        if (respuesta == 'ok') {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Se anuló la nota de débito correctamente',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'Error al anular la nota de débito',
                                text: respuesta,
                                showConfirmButton: false,
                                timer: 1500
                            });
                        }
                    }
                });
            }
        });
    }
});
</script> 