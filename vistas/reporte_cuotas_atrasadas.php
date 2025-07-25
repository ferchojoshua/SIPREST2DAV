<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION["usuario"])) {
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">🔴 Reporte Cuotas Atrasadas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Cuotas Atrasadas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        
        <!-- Alerta de Estado -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Cuotas Atrasadas del Sistema</h5>
                    <span id="resumen_atrasos">Cargando información de cuotas atrasadas...</span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Consulta</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Fecha de Corte:</label>
                                    <input type="date" id="fecha_corte" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><i class="fas fa-calendar-times text-danger"></i> Días de Atraso Mínimo:</label>
                                    <select id="dias_atraso" class="form-control select2" style="width: 100%;">
                                        <option value="1">🟢 Desde 1 día (Inmediato)</option>
                                        <option value="7">🟡 Desde 7 días (Semanal)</option>
                                        <option value="15">🟠 Desde 15 días (Quincenal)</option>
                                        <option value="30" selected>🔴 Desde 30 días (Mensual)</option>
                                        <option value="60">🚨 Desde 60 días (Bimestral)</option>
                                        <option value="90">⚠️ Desde 90 días (Crítico)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Sucursal:</label>
                                    <select id="filtro_sucursal" class="form-control select2">
                                        <option value="">Todas las sucursales</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-danger" onclick="generarReporte()">
                                        <i class="fas fa-search"></i> Consultar Atrasos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Atrasos -->
        <div class="row mb-3" id="estadisticas_atrasos" style="display: none;">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 id="atrasos_leves">0</h3>
                        <p>Atrasos Leves (1-30 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3 id="atrasos_moderados">0</h3>
                        <p>Atrasos Moderados (31-90 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 id="atrasos_criticos">0</h3>
                        <p>Atrasos Críticos (+90 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Resultados -->
        <div class="row" id="area_resultados" style="display: none;">
            <div class="col-12">
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table"></i> Detalle de Cuotas Atrasadas
                        </h3>
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-primary btn-sm" onclick="enviarCorreoReporteAtrasos()">
                                    <i class="fas fa-envelope"></i> Correo
                                </button>
                                <button type="button" class="btn btn-success btn-sm" onclick="exportarExcel()">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-info btn-sm" onclick="listaGestion()">
                                    <i class="fas fa-phone"></i> Lista Gestión
                                </button>
                                <button type="button" class="btn btn-warning btn-sm" onclick="planAccion()">
                                    <i class="fas fa-clipboard-list"></i> Plan Acción
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_atrasos" class="table table-striped table-bordered table-hover">
                                <thead class="bg-danger text-white">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Teléfono</th>
                                        <th>Préstamo</th>
                                        <th>Cuota</th>
                                        <th>Fecha Programada</th>
                                        <th>Días Atraso</th>
                                        <th>Monto Atrasado</th>
                                        <th>Cobrador</th>
                                        <th>Nivel</th>
                                        <th>Acciones</th>
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

        <!-- Modal Plan de Acción -->
        <div class="modal fade" id="modal_plan_accion" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">
                            <i class="fas fa-clipboard-list"></i> Plan de Acción para Atrasos
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-phone"></i> Prioridad Alta (90+ días)</h6>
                                <div id="lista_prioridad_alta" class="border p-2 mb-3" style="max-height: 200px; overflow-y: auto;">
                                    <!-- Se llena dinámicamente -->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-exclamation-triangle"></i> Prioridad Media (30-89 días)</h6>
                                <div id="lista_prioridad_media" class="border p-2 mb-3" style="max-height: 200px; overflow-y: auto;">
                                    <!-- Se llena dinámicamente -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <h6><i class="fas fa-chart-bar"></i> Resumen por Cobrador</h6>
                                <div class="table-responsive">
                                    <table id="tabla_resumen_cobradores" class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>Cobrador</th>
                                                <th>Cuotas Atrasadas</th>
                                                <th>Monto Total</th>
                                                <th>Promedio Días</th>
                                                <th>Acción Recomendada</th>
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
</div>
<?php require_once "modulos/footer.php"; ?>
<script>
$(document).ready(function() {
    cargarSucursales();
    $('.select2').select2();
    cargarResumenAtrasos();
});

function cargarSucursales() {
    $.ajax({
        url: 'ajax/sucursales_ajax.php',
        type: 'GET',
        data: { accion: 'listar' },
        dataType: 'json',
        success: function(respuesta) {
            var opciones = '<option value="">Todas las sucursales</option>';
            respuesta.forEach(function(sucursal) {
                opciones += `<option value="${sucursal.id}">${sucursal.nombre}</option>`;
            });
            $('#filtro_sucursal').html(opciones);
        },
        error: function() {
            console.error('Error al cargar sucursales');
        }
    });
}

function cargarResumenAtrasos() {
    $.ajax({
        url: 'ajax/reportes_ajax.php',
        method: 'POST',
        data: {
            accion: 'reporte_cuotas_atrasadas',
            fecha: new Date().toISOString().split('T')[0],
            dias_minimo: 1
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta && respuesta.length > 0) {
                var totalAtrasos = respuesta.length;
                var montoTotal = respuesta.reduce((sum, item) => sum + parseFloat(item.monto_atrasado || 0), 0);
                
                $('#resumen_atrasos').html(`
                    <strong>${totalAtrasos}</strong> cuotas atrasadas por un monto total de 
                    <strong>C$ ${montoTotal.toLocaleString('es-NI')}</strong>. 
                    <a href="#" onclick="generarReporte()" class="alert-link">Ver detalles completos</a>
                `);
            } else {
                $('#resumen_atrasos').html('✅ No hay cuotas atrasadas en el sistema.');
            }
        },
        error: function() {
            $('#resumen_atrasos').text('Error al cargar resumen de atrasos.');
        }
    });
}

function generarReporte() {
    var fecha = $('#fecha_corte').val();
    var diasMinimo = $('#dias_atraso').val();
    var sucursalId = $('#filtro_sucursal').val();
    
    if (!fecha) {
        Swal.fire('Atención', 'Debe seleccionar una fecha de corte.', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Analizando atrasos...',
        text: 'Consultando cuotas atrasadas.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: 'ajax/reportes_ajax.php',
        method: 'POST',
        data: {
            accion: 'reporte_cuotas_atrasadas',
            fecha: fecha,
            dias_minimo: diasMinimo,
            sucursal_id: sucursalId
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
                calcularEstadisticas(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron cuotas atrasadas con los filtros seleccionados.', 'info');
                $('#area_resultados').hide();
                $('#estadisticas_atrasos').hide();
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el reporte. Intente nuevamente.', 'error');
        }
    });
}

function mostrarResultados(datos) {
    // Destruir tabla existente
    if ($.fn.DataTable.isDataTable('#tabla_atrasos')) {
        $('#tabla_atrasos').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_atrasos tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        var diasAtraso = parseInt(item.dias_atraso || 0);
        var nivelAtraso = '';
        var badgeClass = '';
        
        if (diasAtraso <= 30) {
            nivelAtraso = '🟡 LEVE';
            badgeClass = 'warning';
        } else if (diasAtraso <= 90) {
            nivelAtraso = '🟠 MODERADO';
            badgeClass = 'orange';
        } else {
            nivelAtraso = '🔴 CRÍTICO';
            badgeClass = 'danger';
        }
        
        var fila = `
            <tr>
                <td>${item.cliente_nombre || ''}</td>
                <td>${item.telefono || 'N/A'}</td>
                <td>${item.nro_prestamo || ''}</td>
                <td>${item.nro_cuota || ''}</td>
                <td>${item.fecha_programada || ''}</td>
                <td><strong>${diasAtraso}</strong> días</td>
                <td>C$ ${parseFloat(item.monto_atrasado || 0).toLocaleString('es-NI')}</td>
                <td>${item.cobrador || 'Sin asignar'}</td>
                <td><span class="badge badge-${badgeClass}">${nivelAtraso}</span></td>
                <td>
                    <button class="btn btn-info btn-xs" onclick="verDetalleAtraso('${item.nro_prestamo}')" title="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-warning btn-xs" onclick="contactarCliente('${item.telefono}')" title="Contactar">
                        <i class="fas fa-phone"></i>
                    </button>
                    <button class="btn btn-success btn-xs" onclick="registrarGestion('${item.cliente_id}')" title="Registrar gestión">
                        <i class="fas fa-edit"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tabla_atrasos tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_atrasos').DataTable({
        language: {
            url: "vistas/assets/plugins/datatables/i18n/Spanish.json"
        },
        responsive: true,
        order: [[5, "desc"]], // Ordenar por días de atraso descendente
        pageLength: 25
    });
    
    $('#area_resultados').show();
}

function calcularEstadisticas(datos) {
    var atrasosLeves = datos.filter(item => parseInt(item.dias_atraso) <= 30).length;
    var atrasosModerados = datos.filter(item => parseInt(item.dias_atraso) > 30 && parseInt(item.dias_atraso) <= 90).length;
    var atrasosCriticos = datos.filter(item => parseInt(item.dias_atraso) > 90).length;
    
    $('#atrasos_leves').text(atrasosLeves);
    $('#atrasos_moderados').text(atrasosModerados);
    $('#atrasos_criticos').text(atrasosCriticos);
    
    $('#estadisticas_atrasos').show();
}

function planAccion() {
    if (!$('#area_resultados').is(':visible')) {
        Swal.fire('Atención', 'Debe generar el reporte primero.', 'warning');
        return;
    }
    
    // Obtener datos de la tabla
    var datos = [];
    $('#tabla_atrasos tbody tr').each(function() {
        var $row = $(this);
        datos.push({
            cliente: $row.find('td:eq(0)').text(),
            telefono: $row.find('td:eq(1)').text(),
            prestamo: $row.find('td:eq(2)').text(),
            dias: parseInt($row.find('td:eq(5)').text()),
            monto: $row.find('td:eq(6)').text(),
            cobrador: $row.find('td:eq(7)').text()
        });
    });
    
    // Separar por prioridad
    var prioridadAlta = datos.filter(item => item.dias > 90);
    var prioridadMedia = datos.filter(item => item.dias > 30 && item.dias <= 90);
    
    // Llenar listas de prioridad
    $('#lista_prioridad_alta').html('');
    prioridadAlta.forEach(function(item) {
        $('#lista_prioridad_alta').append(`
            <div class="mb-2 p-2 bg-light">
                <strong>${item.cliente}</strong> - ${item.telefono}<br>
                <small>Préstamo: ${item.prestamo} | ${item.dias} días | ${item.monto}</small>
            </div>
        `);
    });
    
    $('#lista_prioridad_media').html('');
    prioridadMedia.forEach(function(item) {
        $('#lista_prioridad_media').append(`
            <div class="mb-2 p-2 bg-light">
                <strong>${item.cliente}</strong> - ${item.telefono}<br>
                <small>Préstamo: ${item.prestamo} | ${item.dias} días | ${item.monto}</small>
            </div>
        `);
    });
    
    // Resumen por cobrador
    var resumenCobradores = {};
    datos.forEach(function(item) {
        if (!resumenCobradores[item.cobrador]) {
            resumenCobradores[item.cobrador] = {
                cuotas: 0,
                monto: 0,
                dias: []
            };
        }
        resumenCobradores[item.cobrador].cuotas++;
        resumenCobradores[item.cobrador].monto += parseFloat(item.monto.replace(/[^\d.-]/g, ''));
        resumenCobradores[item.cobrador].dias.push(item.dias);
    });
    
    $('#tabla_resumen_cobradores tbody').empty();
    Object.keys(resumenCobradores).forEach(function(cobrador) {
        var data = resumenCobradores[cobrador];
        var promedioDias = data.dias.reduce((a, b) => a + b, 0) / data.dias.length;
        var accion = promedioDias > 60 ? 'Seguimiento Inmediato' : 'Gestión Regular';
        
        $('#tabla_resumen_cobradores tbody').append(`
            <tr>
                <td>${cobrador}</td>
                <td>${data.cuotas}</td>
                <td>C$ ${data.monto.toLocaleString('es-NI')}</td>
                <td>${Math.round(promedioDias)} días</td>
                <td><span class="badge badge-${promedioDias > 60 ? 'danger' : 'warning'}">${accion}</span></td>
            </tr>
        `);
    });
    
    $('#modal_plan_accion').modal('show');
}

function verDetalleAtraso(nroPrestamo) {
    Swal.fire('Información', `Ver detalle del préstamo: ${nroPrestamo}`, 'info');
}

function contactarCliente(telefono) {
    if (telefono && telefono !== 'N/A') {
        Swal.fire({
            title: 'Contactar Cliente',
            html: `
                <p>Teléfono: <strong>${telefono}</strong></p>
                <p>¿Cómo desea contactar al cliente?</p>
            `,
            showCancelButton: true,
            confirmButtonText: 'Llamar',
            cancelButtonText: 'WhatsApp',
            showDenyButton: true,
            denyButtonText: 'Copiar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.open(`tel:${telefono}`);
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.open(`https://wa.me/${telefono.replace(/\D/g, '')}`);
            } else if (result.isDenied) {
                navigator.clipboard.writeText(telefono);
                Swal.fire('¡Copiado!', 'Número copiado al portapapeles', 'success');
            }
        });
    } else {
        Swal.fire('Sin teléfono', 'Este cliente no tiene teléfono registrado.', 'warning');
    }
}

function registrarGestion(clienteId) {
    Swal.fire('Función', 'Registrar gestión de cobranza en desarrollo.', 'info');
}

function listaGestion() {
    Swal.fire('Información', 'Función para generar lista de gestión en desarrollo.', 'info');
}

function enviarCorreoReporteAtrasos() {
    if (!$('#area_resultados').is(':visible')) {
        Swal.fire('Atención', 'Debe generar el reporte primero.', 'warning');
        return;
    }
    
    var fecha = $('#fecha_corte').val();
    var sucursal = $('#filtro_sucursal option:selected').text();
    
    Swal.fire({
        title: 'Enviar Reporte por Correo',
        html: `
            <div class="form-group text-left">
                <label for="emailDestino">Correo electrónico de destino:</label>
                <input type="email" id="emailDestino" class="form-control" placeholder="ejemplo@correo.com">
            </div>
            <div class="form-group text-left">
                <label for="asuntoCorreo">Asunto:</label>
                <input type="text" id="asuntoCorreo" class="form-control" value="Reporte de Cuotas Atrasadas - ${fecha} (${sucursal})">
            </div>
            <div class="form-group text-left">
                <label for="mensajeCorreo">Mensaje (opcional):</label>
                <textarea id="mensajeCorreo" class="form-control" rows="3" placeholder="Mensaje adicional..."></textarea>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Enviar',
        cancelButtonText: 'Cancelar',
        focusConfirm: false,
        preConfirm: () => {
            const email = document.getElementById('emailDestino').value;
            const asunto = document.getElementById('asuntoCorreo').value;
            const mensaje = document.getElementById('mensajeCorreo').value;
            
            if (!email) {
                Swal.showValidationMessage('Debe ingresar un correo electrónico');
                return false;
            }
            
            if (!asunto) {
                Swal.showValidationMessage('Debe ingresar un asunto');
                return false;
            }
            
            return { email: email, asunto: asunto, mensaje: mensaje };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Enviando correo...',
                text: 'Por favor espere.',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: 'ajax/reportes_ajax.php',
                method: 'POST',
                data: {
                    accion: 'enviar_correo_reporte_atrasos',
                    fecha: fecha,
                    sucursal_id: $('#filtro_sucursal').val(),
                    email_destino: result.value.email,
                    asunto: result.value.asunto,
                    mensaje: result.value.mensaje
                },
                dataType: 'json',
                success: function(respuesta) {
                    Swal.close();
                    
                    if (respuesta.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correo enviado exitosamente',
                            text: respuesta.mensaje
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error al enviar correo',
                            text: respuesta.mensaje || 'Ocurrió un error inesperado'
                        });
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Error al enviar el correo electrónico.', 'error');
                }
            });
        }
    });
}

function exportarExcel() {
    Swal.fire('Información', 'Función de exportar a Excel en desarrollo.', 'info');
}

function exportarPDF() {
    Swal.fire('Información', 'Función de exportar a PDF en desarrollo.', 'info');
}
</script>

<?php 
} else {
    header("Location: index.php");
}
?> 