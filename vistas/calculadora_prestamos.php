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
                                        <label>Sistema de Amortización:</label>
                                        <select class="form-control" name="sistema" id="sistema">
                                            <option value="frances">Sistema Francés (Cuota Fija)</option>
                                            <option value="aleman">Sistema Alemán (Amortización Fija)</option>
                                            <option value="americano">Sistema Americano (Interés + Capital Final)</option>
                                            <option value="simple">Sistema Simple (Todo Fijo)</option>
                                            <option value="compuesto">Sistema Compuesto (Interés sobre Interés)</option>
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
                    <p><strong>Monto del préstamo:</strong> S/ ${formatNumber(response.monto)}</p>
                    <p><strong>Tasa de interés anual:</strong> ${response.tasa}%</p>
                    <p><strong>Plazo:</strong> ${response.plazo} meses</p>
                    ${response.cuotaInicial ? `<p><strong>Cuota inicial:</strong> S/ ${formatNumber(response.cuotaInicial)}</p>` : ''}
                    <p><strong>Monto total a pagar:</strong> S/ ${formatNumber(response.totalPagar)}</p>
                    <p><strong>Interés total:</strong> S/ ${formatNumber(response.totalIntereses)}</p>
                `;
                $('#resumen').html(resumen);
                
                // Limpiar y llenar tabla
                let tabla = $('#tablaAmortizacion').DataTable();
                tabla.clear();
                
                response.tablaAmortizacion.forEach(function(fila) {
                    tabla.row.add([
                        fila.fecha,
                        'S/ ' + formatNumber(fila.cuota),
                        'S/ ' + formatNumber(fila.capital),
                        'S/ ' + formatNumber(fila.interes),
                        'S/ ' + formatNumber(fila.saldo)
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