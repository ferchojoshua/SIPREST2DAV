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
                    <h4 class="m-0">Reporte de Proyección Mensual</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte de Proyección Mensual</li>
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
                            <h3 class="card-title">Proyección de Cobros y Colocaciones</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="mesProyeccion" class="col-sm-2 col-form-label">Mes:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="mesProyeccion">
                                        <option value="1">Enero</option>
                                        <option value="2">Febrero</option>
                                        <option value="3">Marzo</option>
                                        <option value="4">Abril</option>
                                        <option value="5">Mayo</option>
                                        <option value="6">Junio</option>
                                        <option value="7">Julio</option>
                                        <option value="8">Agosto</option>
                                        <option value="9">Septiembre</option>
                                        <option value="10">Octubre</option>
                                        <option value="11">Noviembre</option>
                                        <option value="12">Diciembre</option>
                                    </select>
                                </div>
                                <label for="anioProyeccion" class="col-sm-2 col-form-label">Año:</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="anioProyeccion" value="<?php echo date('Y'); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnGenerarProyeccion">
                                        <i class="fas fa-search"></i> Generar Proyección
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card card-info card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title"><i class="fas fa-hand-holding-usd"></i> Clientes a Cobrar / Monto Proyectado</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Clientes a Cobrar:</strong> <span id="clientesACobrar">0</span></p>
                                            <p><strong>Monto a Cobrar:</strong> <span id="montoACobrar">C$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title"><i class="fas fa-money-check-alt"></i> Préstamos a Colocar / Monto Colocado</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Cantidad de Préstamos:</strong> <span id="cantidadColocados">0</span></p>
                                            <p><strong>Monto :</strong> <span id="montoColocado">C$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "modulos/footer.php"; ?>

<!-- REQUIRED SCRIPTS -->
<script src="vistas/assets/plugins/jquery/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Set current month and year as default
    $('#mesProyeccion').val(new Date().getMonth() + 1);
    $('#anioProyeccion').val(new Date().getFullYear());

    $('#btnGenerarProyeccion').on('click', function() {
        var mes = $('#mesProyeccion').val();
        var anio = $('#anioProyeccion').val();

        Swal.fire({
            title: 'Cargando proyección...',
            text: 'Por favor espere.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: 'ajax/reporte_proyeccion_ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'obtener_proyeccion',
                mes: mes,
                anio: anio
            },
            success: function(response) {
                Swal.close();
                if (response.error) {
                    Swal.fire('Error', response.message, 'error');
                    return;
                }
                
                // Update UI with data
                var proyeccionCobro = response.proyeccion_cobro || {};
                var prestamosColocados = response.prestamos_colocados || {};

                $('#clientesACobrar').text(proyeccionCobro.clientes_a_cobrar !== undefined ? proyeccionCobro.clientes_a_cobrar.toLocaleString() : '0');
                $('#montoACobrar').text('C$ ' + (proyeccionCobro.monto_a_cobrar !== undefined ? parseFloat(proyeccionCobro.monto_a_cobrar).toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00'));
                $('#cantidadColocados').text(prestamosColocados.cantidad_prestamos_colocados !== undefined ? prestamosColocados.cantidad_prestamos_colocados.toLocaleString() : '0');
                $('#montoColocado').text('C$ ' + (prestamosColocados.monto_prestamos_colocados !== undefined ? parseFloat(prestamosColocados.monto_prestamos_colocados).toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00'));
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire('Error', 'Error al obtener la proyección. Intente nuevamente.', 'error');
                console.error("AJAX Error: ", status, error, xhr.responseText);
            }
        });
    });

    // Initial load
    $('#btnGenerarProyeccion').trigger('click');
});
</script> 