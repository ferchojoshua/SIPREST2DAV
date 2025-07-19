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
                    <h4 class="m-0">Reporte de Cierre Mensual</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte de Cierre Mensual</li>
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
                            <h3 class="card-title">Cierre Mensual de Cobranza y Mora</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="mesCierre" class="col-sm-2 col-form-label">Mes:</label>
                                <div class="col-sm-4">
                                    <select class="form-control" id="mesCierre">
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
                                <label for="anioCierre" class="col-sm-2 col-form-label">Año:</label>
                                <div class="col-sm-4">
                                    <input type="number" class="form-control" id="anioCierre" value="<?php echo date('Y'); ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnGenerarCierre">
                                        <i class="fas fa-search"></i> Generar Cierre
                                    </button>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card card-success card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Monto Total Cobrado en el Mes</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Monto Cobrado:</strong> <span id="montoCobradoMes">C$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card card-danger card-outline">
                                        <div class="card-header">
                                            <h5 class="card-title"><i class="fas fa-exclamation-triangle"></i> Mora al Cierre del Mes</h5>
                                        </div>
                                        <div class="card-body">
                                            <p><strong>Clientes en Mora:</strong> <span id="clientesEnMora">0</span></p>
                                            <p><strong>Monto en Mora:</strong> <span id="montoMoraFinMes">C$ 0.00</span></p>
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
    $('#mesCierre').val(new Date().getMonth() + 1);
    $('#anioCierre').val(new Date().getFullYear());

    $('#btnGenerarCierre').on('click', function() {
        var mes = $('#mesCierre').val();
        var anio = $('#anioCierre').val();

        Swal.fire({
            title: 'Cargando cierre mensual...',
            text: 'Por favor espere.',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: 'ajax/reporte_cierre_ajax.php',
            type: 'POST',
            dataType: 'json',
            data: {
                accion: 'obtener_cierre',
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
                var montoCobrado = response.monto_cobrado || {};
                var moraFinMes = response.mora_fin_mes || {};

                $('#montoCobradoMes').text('C$ ' + (montoCobrado.monto_cobrado_mes !== undefined ? parseFloat(montoCobrado.monto_cobrado_mes).toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00'));
                $('#clientesEnMora').text(moraFinMes.clientes_en_mora !== undefined ? moraFinMes.clientes_en_mora.toLocaleString() : '0');
                $('#montoMoraFinMes').text('C$ ' + (moraFinMes.monto_mora_fin_mes !== undefined ? parseFloat(moraFinMes.monto_mora_fin_mes).toLocaleString('es-NI', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : '0.00'));
            },
            error: function(xhr, status, error) {
                Swal.close();
                Swal.fire('Error', 'Error al obtener el cierre mensual. Intente nuevamente.', 'error');
                console.error("AJAX Error: ", status, error, xhr.responseText);
            }
        });
    });

    // Initial load
    $('#btnGenerarCierre').trigger('click');
});
</script> 