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
                <h1 class="m-0">⚠️ Reporte de Mora</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Reporte Mora</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        
        <!-- Alertas de Mora -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-warning alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Estado de Mora del Sistema</h5>
                    <span id="resumen_mora">Cargando datos de mora...</span>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Consulta</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nivel de Mora:</label>
                                    <select id="nivel_mora" class="form-control">
                                        <option value="">Todos los niveles</option>
                                        <option value="LEVE">🟢 Leve (1-30 días)</option>
                                        <option value="MODERADA">🟡 Moderada (31-60 días)</option>
                                        <option value="ALTA">🟠 Alta (61-90 días)</option>
                                        <option value="CRITICA">🔴 Crítica (+90 días)</option>
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
                                    <label>Ruta:</label>
                                    <select id="filtro_ruta" class="form-control select2" disabled>
                                        <option value="">Todas las rutas</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-warning" onclick="generarReporte()">
                                        <i class="fas fa-search"></i> Consultar Mora
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Estadísticas de Mora -->
        <div class="row mb-3" id="estadisticas_mora" style="display: none;">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3 id="mora_leve">0</h3>
                        <p>Mora Leve (1-30 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3 id="mora_moderada">0</h3>
                        <p>Mora Moderada (31-60 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-orange">
                    <div class="inner">
                        <h3 id="mora_alta">0</h3>
                        <p>Mora Alta (61-90 días)</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-red">
                    <div class="inner">
                        <h3 id="mora_critica">0</h3>
                        <p>Mora Crítica (+90 días)</p>
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
                <div class="card card-warning card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-table"></i> Clientes en Mora
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="exportarExcel()">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button type="button" class="btn btn-info btn-sm" onclick="contactarClientes()">
                                <i class="fas fa-phone"></i> Lista de Contacto
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_mora" class="table table-striped table-bordered table-hover">
                                <thead class="bg-warning text-dark">
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Teléfono</th>
                                        <th>Préstamo</th>
                                        <th>Cuota Vencida</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Monto Vencido</th>
                                        <th>Días Mora</th>
                                        <th>Nivel</th>
                                        <th>Cobrador</th>
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

    </div>
</div>

<script>
$(document).ready(function() {
    cargarResumenMora();
    cargarSucursales();
    $('.select2').select2();
});

function cargarResumenMora() {
    $.ajax({
        url: 'ajax/reportes_ajax.php',
        method: 'POST',
        data: { accion: 7 },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta && respuesta.length > 0) {
                var totalMora = respuesta.reduce((sum, item) => sum + parseFloat(item.monto_vencido || 0), 0);
                var clientesMora = respuesta.length;
                
                $('#resumen_mora').html(`
                    <strong>${clientesMora}</strong> clientes en mora por un total de 
                    <strong>C$ ${totalMora.toLocaleString('es-NI')}</strong>. 
                    <a href="#" onclick="generarReporte()" class="alert-link">Ver detalles</a>
                `);
            }
        },
        error: function() {
            $('#resumen_mora').text('Error al cargar resumen de mora.');
        }
    });
}

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

$('#filtro_sucursal').on('change', function() {
    var idSucursal = $(this).val();
    if (idSucursal) {
        cargarRutas(idSucursal);
        $('#filtro_ruta').prop('disabled', false);
    } else {
        $('#filtro_ruta').empty().append('<option value="">Todas las rutas</option>').prop('disabled', true);
    }
});

function cargarRutas(idSucursal) {
    $.ajax({
        url: 'ajax/rutas_ajax.php',
        type: 'GET',
        data: { accion: 'listar_por_sucursal', sucursal_id: idSucursal },
        dataType: 'json',
        success: function(respuesta) {
            var opciones = '<option value="">Todas las rutas</option>';
            respuesta.forEach(function(ruta) {
                opciones += `<option value="${ruta.id}">${ruta.nombre}</option>`;
            });
            $('#filtro_ruta').html(opciones);
        },
        error: function() {
            console.error('Error al cargar rutas');
        }
    });
}

function generarReporte() {
    var nivelMora = $('#nivel_mora').val();
    var sucursalId = $('#filtro_sucursal').val();
    var rutaId = $('#filtro_ruta').val();
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Consultando mora...',
        text: 'Analizando estado de cartera.',
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
            accion: 7,
            nivel_mora: nivelMora,
            sucursal_id: sucursalId,
            ruta_id: rutaId
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
                calcularEstadisticas(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron clientes en mora con los filtros seleccionados.', 'info');
                $('#area_resultados').hide();
                $('#estadisticas_mora').hide();
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al consultar mora. Intente nuevamente.', 'error');
        }
    });
}

function mostrarResultados(datos) {
    // Destruir tabla existente
    if ($.fn.DataTable.isDataTable('#tabla_mora')) {
        $('#tabla_mora').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_mora tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        var diasMora = parseInt(item.dias_mora || 0);
        var nivelMora = '';
        var badgeClass = '';
        
        if (diasMora <= 30) {
            nivelMora = '🟢 LEVE';
            badgeClass = 'success';
        } else if (diasMora <= 60) {
            nivelMora = '🟡 MODERADA';
            badgeClass = 'warning';
        } else if (diasMora <= 90) {
            nivelMora = '🟠 ALTA';
            badgeClass = 'orange';
        } else {
            nivelMora = '🔴 CRÍTICA';
            badgeClass = 'danger';
        }
        
        var fila = `
            <tr>
                <td>${item.cliente_nombre || ''}</td>
                <td>${item.telefono || 'N/A'}</td>
                <td>${item.nro_prestamo || ''}</td>
                <td>${item.nro_cuota || ''}</td>
                <td>${item.fecha_vencimiento || ''}</td>
                <td>C$ ${parseFloat(item.monto_vencido || 0).toLocaleString('es-NI')}</td>
                <td><strong>${diasMora}</strong> días</td>
                <td><span class="badge badge-${badgeClass}">${nivelMora}</span></td>
                <td>${item.cobrador || 'Sin asignar'}</td>
                <td>
                    <button class="btn btn-info btn-xs" onclick="verDetalle('${item.nro_prestamo}')" title="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-warning btn-xs" onclick="contactarCliente('${item.telefono}')" title="Contactar">
                        <i class="fas fa-phone"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#tabla_mora tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_mora').DataTable({
        language: {
            url: "//cdn.datatables.net/plug-ins/1.10.21/i18n/Spanish.json"
        },
        responsive: true,
        order: [[6, "desc"]], // Ordenar por días de mora descendente
        pageLength: 25
    });
    
    $('#area_resultados').show();
}

function calcularEstadisticas(datos) {
    var moraLeve = datos.filter(item => parseInt(item.dias_mora) <= 30).length;
    var moraModerada = datos.filter(item => parseInt(item.dias_mora) > 30 && parseInt(item.dias_mora) <= 60).length;
    var moraAlta = datos.filter(item => parseInt(item.dias_mora) > 60 && parseInt(item.dias_mora) <= 90).length;
    var moraCritica = datos.filter(item => parseInt(item.dias_mora) > 90).length;
    
    $('#mora_leve').text(moraLeve);
    $('#mora_moderada').text(moraModerada);
    $('#mora_alta').text(moraAlta);
    $('#mora_critica').text(moraCritica);
    
    $('#estadisticas_mora').show();
}

function verDetalle(nroPrestamo) {
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
            denyButtonText: 'Copiar Número'
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

function contactarClientes() {
    Swal.fire('Información', 'Función para generar lista de contacto en desarrollo.', 'info');
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