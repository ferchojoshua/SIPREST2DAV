<?php
// Estas inclusiones no son necesarias cuando se carga como vista parcial
// require_once "modulos/header.php";
// require_once "modulos/navbar.php";
// require_once "modulos/aside.php";
?>

<div class="content-wrapper">
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

    <div class="content">
    <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalEstadoCuenta">
                                <i class="fas fa-search"></i> Buscar Cliente
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl_estado_cuenta_cliente" class="table table-striped table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Descripción</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
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
        </div>
    </div>
</div>

<!-- Modal Estado de Cuenta -->
<div class="modal fade" id="modalEstadoCuenta" tabindex="-1" role="dialog" aria-labelledby="modalEstadoCuentaLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title" id="modalEstadoCuentaLabel">
                    <i class="fas fa-file-invoice"></i> Estado de Cuenta por Cliente
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="selectClientesEstadoCuenta">Seleccionar Cliente:</label>
                    <select class="form-control select2bs4" id="selectClientesEstadoCuenta" style="width: 100%;">
                        <option value="">Seleccione un cliente</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btnGenerarEstadoCuenta">
                    <i class="fas fa-file-alt"></i> Generar Estado de Cuenta
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once "modulos/footer.php"; ?>

<!-- REQUIRED SCRIPTS -->
<script src="vistas/assets/plugins/jquery/jquery.min.js"></script>
<script src="vistas/assets/plugins/select2/js/select2.full.min.js"></script>
<script src="vistas/assets/plugins/select2/js/i18n/es.js"></script>

<script>
$(document).ready(function() {
    console.log("Documento listo - Inicializando estado de cuenta");
    
    // Variable para la tabla
    var tablaEstadoCuenta;
    
    // Función para cargar clientes
    function cargarClientes() {
        console.log("Iniciando carga de clientes...");
        
    $.ajax({
        url: "ajax/clientes_ajax.php",
        type: "POST",
            dataType: "json",
            data: {
                accion: "ListarSelectClientes"
            },
            beforeSend: function() {
                console.log("Enviando petición AJAX...");
                $('#selectClientesEstadoCuenta').html('<option value="">Cargando clientes...</option>');
            },
            success: function(response) {
                console.log("Respuesta recibida:", response);
                
                if (response.error) {
                    console.error("Error en respuesta:", response.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al cargar clientes: ' + response.message
                    });
                    return;
                }
                
                var select = $('#selectClientesEstadoCuenta');
                select.empty();
                select.append('<option value="">Seleccione un cliente</option>');
                
                if (Array.isArray(response) && response.length > 0) {
                    console.log("Agregando " + response.length + " clientes al select");
                    response.forEach(function(cliente) {
                        console.log("Agregando cliente:", cliente.cliente_id, cliente.cliente_nombres);
                        select.append(new Option(cliente.cliente_nombres, cliente.cliente_id));
                    });
                } else {
                    console.log("No se encontraron clientes o respuesta no es array");
                    select.append('<option value="">No hay clientes disponibles</option>');
                }
                
                // Reinicializar Select2 después de agregar opciones
                select.select2({
                    theme: 'bootstrap4',
                    placeholder: "Seleccione un cliente",
                    allowClear: true,
                    width: '100%'
                });
            },
            error: function(xhr, status, error) {
                console.error("Error en petición AJAX:");
                console.error("Status:", status);
                console.error("Error:", error);
                console.error("Respuesta del servidor:", xhr.responseText);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor para cargar los clientes.'
                });
            }
        });
    }

    // Inicializar Select2 básico
    $('#selectClientesEstadoCuenta').select2({
        theme: 'bootstrap4',
        placeholder: "Seleccione un cliente",
        allowClear: true,
        width: '100%'
    });
    
    // Cargar clientes cuando se abre el modal
    $('#modalEstadoCuenta').on('show.bs.modal', function() {
        console.log("Modal abierto - Cargando clientes...");
        cargarClientes();
    });
    
    // Manejar clic en botón generar
    $('#btnGenerarEstadoCuenta').on('click', function() {
        var clienteId = $('#selectClientesEstadoCuenta').val();
        var clienteNombre = $('#selectClientesEstadoCuenta option:selected').text();
        
        console.log("Cliente seleccionado:", clienteId, clienteNombre);
        
        if (!clienteId) {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Por favor, seleccione un cliente'
            });
            return;
        }

        // Cerrar el modal
        $('#modalEstadoCuenta').modal('hide');
        
        // Cargar el estado de cuenta
        cargarEstadoCuenta(clienteId);
    });

    function cargarEstadoCuenta(clienteId) {
        console.log("Cargando estado de cuenta para cliente:", clienteId);
        
        if ($.fn.DataTable.isDataTable('#tbl_estado_cuenta_cliente')) {
            $('#tbl_estado_cuenta_cliente').DataTable().destroy();
        }

        // Por ahora, solo mostrar un mensaje de que la funcionalidad está en desarrollo
        Swal.fire({
            icon: 'info',
            title: 'Funcionalidad en desarrollo',
            text: 'La carga del estado de cuenta está en desarrollo. Cliente seleccionado: ' + clienteId
        });
        
        // Aquí iría el código para cargar el estado de cuenta real
        /*
        tablaEstadoCuenta = $('#tbl_estado_cuenta_cliente').DataTable({
            ajax: {
                url: "ajax/estado_cuenta_ajax.php",
                type: "POST",
                data: {
                    accion: "obtener_estado_cuenta",
                    cliente_id: clienteId
                },
                dataSrc: ""
            },
            columns: [
                { data: "fecha" },
                { data: "descripcion" },
                { data: "monto" },
                { data: "estado" }
            ],
            order: [[0, "desc"]],
            language: {
                url: "vistas/assets/plugins/datatables/i18n/Spanish.json"
            },
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });
        */
    }
});
</script> 