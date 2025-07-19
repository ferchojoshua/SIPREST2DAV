<?php
require_once "../utilitarios/calculadora_prestamos.php";
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Calculadora de Préstamos</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Simulador de Préstamos</h3>
                    </div>
                    <div class="card-body">
                        <form id="formCalculadora" method="post">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Moneda:</label>
                                        <select class="form-control" name="moneda" id="moneda" required></select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Sistema de Amortización:</label>
                                        <select class="form-control" name="sistema" id="sistema">
                                            <option value="">Seleccione un sistema</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Monto del Préstamo:</label>
                                        <input type="number" class="form-control" name="monto" id="monto" step="0.01" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tasa de Interés Anual (%):</label>
                                        <input type="number" class="form-control" name="tasa" id="tasa" step="0.01" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Plazo (meses):</label>
                                        <input type="number" class="form-control" name="plazo" id="plazo" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Fecha de Inicio:</label>
                                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">Calcular</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div id="resultados" style="display:none;">
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4>Resultados del Cálculo</h4>
                                    <div id="resumen" class="mb-4"></div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="tablaAmortizacion">
                                            <thead>
                                                <tr>
                                                    <th>Fecha</th>
                                                    <th>Cuota</th>
                                                    <th>Capital</th>
                                                    <th>Interés</th>
                                                    <th>Saldo</th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
$(document).ready(function() {
    let simboloMoneda = 'S/'; // Valor por defecto

    // Cargar monedas en el select
    $.ajax({
        url: '../ajax/moneda_ajax.php',
        type: 'POST',
        data: {accion: 5},
        dataType: 'json',
        success: function(monedas) {
            let options = '';
            monedas.forEach(function(moneda, idx) {
                let selected = idx === 0 ? 'selected' : '';
                options += `<option value="${moneda.moneda_id}" data-simbolo="${moneda.moneda_simbolo}" ${selected}>${moneda.moneda_nombre} (${moneda.moneda_abrevia})</option>`;
            });
            $('#moneda').html(options);
            simboloMoneda = $('#moneda option:selected').data('simbolo') || 'S/';
        }
    });

    // Cambiar símbolo al cambiar moneda
    $('#moneda').on('change', function() {
        simboloMoneda = $('#moneda option:selected').data('simbolo') || 'S/';
    });

    // Cargar tipos de cálculo al iniciar
    $.ajax({
        url: 'ajax/prestamo_ajax.php',
        type: 'POST',
        data: {
            'accion': 'obtener_tipos_calculo'
        },
        dataType: 'json',
        success: function(response) {
            var options = '<option value="">Seleccione un sistema</option>';
            response.forEach(function(tipo) {
                options += '<option value="' + tipo.nombre + '">' + tipo.descripcion + '</option>';
            });
            $('#sistema').html(options);
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar tipos de cálculo:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron cargar los sistemas de amortización'
            });
            $('#sistema').prop('disabled', true);
        }
    });

    $('#formCalculadora').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '../ajax/calculadora_ajax.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if(response.error) {
                    Swal.fire('Error', response.error, 'error');
                    return;
                }
                
                // Mostrar resumen
                let resumen = `
                    <p><strong>Monto del préstamo:</strong> ${simboloMoneda} ${formatNumber(response.monto)}</p>
                    <p><strong>Tasa de interés anual:</strong> ${response.tasa}%</p>
                    <p><strong>Plazo:</strong> ${response.plazo} meses</p>
                    ${response.cuotaInicial ? `<p><strong>Cuota inicial:</strong> ${simboloMoneda} ${formatNumber(response.cuotaInicial)}</p>` : ''}
                    <p><strong>Monto total a pagar:</strong> ${simboloMoneda} ${formatNumber(response.totalPagar)}</p>
                    <p><strong>Interés total:</strong> ${simboloMoneda} ${formatNumber(response.totalIntereses)}</p>
                `;
                $('#resumen').html(resumen);
                
                // Limpiar y llenar tabla
                let tabla = $('#tablaAmortizacion').DataTable();
                tabla.clear();
                
                response.tablaAmortizacion.forEach(function(fila) {
                    tabla.row.add([
                        fila.fecha,
                        simboloMoneda + ' ' + formatNumber(fila.cuota),
                        simboloMoneda + ' ' + formatNumber(fila.capital),
                        simboloMoneda + ' ' + formatNumber(fila.interes),
                        simboloMoneda + ' ' + formatNumber(fila.saldo)
                    ]);
                });
                
                tabla.draw();
                $('#resultados').show();
            },
            error: function() {
                Swal.fire('Error', 'Hubo un error al procesar la solicitud', 'error');
            }
        });
    });
    
    // Inicializar DataTable
    $('#tablaAmortizacion').DataTable({
        "ordering": false,
        "pageLength": 12,
        "language": {
            "url": "../assets/plugins/datatables/Spanish.json"
        }
    });
    
    // Función para formatear números
    function formatNumber(num) {
        return new Intl.NumberFormat('es-PE', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(num);
    }
});
</script> 