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
                <h1 class="m-0">📊 Reporte por Cliente</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="#">Reportes</a></li>
                    <li class="breadcrumb-item active">Por Cliente</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        
        <!-- Filtros -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-filter"></i> Filtros</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Seleccionar Cliente:</label>
                                    <select id="select_cliente" class="form-control select2" style="width: 100%;">
                                        <option value="">Buscar y seleccionar cliente...</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" class="btn btn-primary" onclick="generarReporteCliente()">
                                        <i class="fas fa-search"></i> Generar Reporte
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Área de Resultados -->
        <div class="row" id="area_resultados" style="display: none;">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title" id="titulo_reporte">
                            <i class="fas fa-table"></i> Historial del Cliente
                        </h3>
                                                 <div class="card-tools">
                             <button type="button" class="btn btn-success btn-sm" onclick="exportarExcel()">
                                 <i class="fas fa-file-excel"></i> Excel
                             </button>
                             <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDF()">
                                 <i class="fas fa-file-pdf"></i> PDF
                             </button>
                             <button type="button" class="btn btn-info btn-sm" onclick="imprimirReporte()">
                                 <i class="fas fa-print"></i> Imprimir
                             </button>
                             <button type="button" class="btn btn-warning btn-sm" onclick="enviarCorreoReporteCliente()">
                                 <i class="fas fa-envelope"></i> Enviar por Correo
                             </button>
                         </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tabla_reporte_cliente" class="table table-striped table-bordered table-hover">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Préstamo</th>
                                        <th>Cliente</th>
                                        <th>Fecha Apertura</th>
                                        <th>Fecha Vencimiento</th>
                                        <th>Monto</th>
                                        <th>Estado</th>
                                        <th>Saldo</th>
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
    // Inicializar Select2 para búsqueda de clientes
    $('#select_cliente').select2({
        placeholder: 'Buscar cliente por nombre o DNI...',
        allowClear: true,
        minimumInputLength: 2,
        ajax: {
            url: 'ajax/clientes_ajax.php',
            type: 'POST', // <--- Añadir esta línea
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    accion: 'buscar_clientes',
                    busqueda: params.term
                };
            },
            processResults: function (data) {
                if (data.error) {
                    return { results: [] }; // Retorna un array vacío si hay un error
                }
                // Asegurarse de que 'data' sea un array. Si es un objeto con una clave 'results', usarla.
                // Algunos Select2 esperan 'results' anidado, otros directamente el array.
                const results = Array.isArray(data) ? data : (data.results || []);
                
                return {
                    results: results
                };
            },
            cache: true
        }
    });
});

function generarReporteCliente() {
    var clienteId = $('#select_cliente').val();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente para generar el reporte.', 'warning');
        return;
    }
    
    // Mostrar indicador de carga
    Swal.fire({
        title: 'Generando reporte...',
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
            accion: 1,
            cliente_id: clienteId
        },
        dataType: 'json',
        success: function(respuesta) {
            Swal.close();
            
            if (respuesta && respuesta.length > 0) {
                mostrarResultados(respuesta);
            } else {
                Swal.fire('Sin resultados', 'No se encontraron préstamos para este cliente.', 'info');
            }
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el reporte. Intente nuevamente.', 'error');
        }
    });
}

function mostrarResultados(datos) {
    // Destruir tabla existente si hay una
    if ($.fn.DataTable.isDataTable('#tabla_reporte_cliente')) {
        $('#tabla_reporte_cliente').DataTable().destroy();
    }
    
    // Limpiar tabla
    $('#tabla_reporte_cliente tbody').empty();
    
    // Llenar tabla con datos
    datos.forEach(function(item) {
        // Obtener símbolo de moneda (si está disponible, sino usar por defecto)
        var simboloMoneda = item.moneda_simbolo || 'C$';
        
        var fila = `
            <tr>
                <td>${item.nro_prestamo || ''}</td>
                <td>${item.cliente_nombres || ''}</td>
                <td>${item.fecha_apertura || item.femision || ''}</td>
                <td>${item.fecha_vencimiento || 'No calculada'}</td>
                <td>${simboloMoneda} ${parseFloat(item.monto_prestamo || 0).toLocaleString('es-NI')}</td>
                <td><span class="badge badge-${item.estado === 'Pagado' || item.estado === 'finalizado' ? 'success' : item.estado === 'aprobado' ? 'info' : 'warning'}">${item.estado || ''}</span></td>
                <td>${simboloMoneda} ${parseFloat(item.saldo_pendiente || 0).toLocaleString('es-NI')}</td>
                <td>
                    <button class="btn btn-info btn-sm" onclick="verDetalle('${item.nro_prestamo}')">
                        <i class="fas fa-eye"></i> Ver
                    </button>
                </td>
            </tr>
        `;
        $('#tabla_reporte_cliente tbody').append(fila);
    });
    
    // Inicializar DataTable
    $('#tabla_reporte_cliente').DataTable({
        language: {
            url: "vistas/assets/plugins/datatables/i18n/Spanish.json" // <--- Ruta actualizada
        },
        responsive: true,
        order: [[2, "desc"]], // Ordenar por fecha de apertura descendente
        columnDefs: [
            {
                targets: [4, 6], // Columnas de monto y saldo
                className: 'text-right'
            },
            {
                targets: [5], // Columna de estado
                className: 'text-center'
            },
            {
                targets: [7], // Columna de acciones
                orderable: false
            }
        ]
    });
    
    // Mostrar área de resultados
    $('#area_resultados').show();
}

function verDetalle(nroPrestamo) {
    if (!nroPrestamo) {
        Swal.fire('Error', 'Número de préstamo no válido.', 'error');
        return;
    }
    
    // Abrir ventana con el detalle completo del préstamo
    var url = `MPDF/historial_prestamo_nuevo.php?codigo=${nroPrestamo}`;
    var ventanaDetalle = window.open(url, 'detalle_prestamo', 'width=900,height=700,scrollbars=yes,resizable=yes');
    
    if (!ventanaDetalle) {
        Swal.fire('Error', 'No se pudo abrir la ventana de detalle. Verifique que no esté bloqueada por el navegador.', 'error');
    }
}

function exportarExcel() {
    var clienteId = $('#select_cliente').val();
    var clienteNombre = $('#select_cliente option:selected').text();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente primero.', 'warning');
        return;
    }
    
    Swal.fire({
        title: 'Generando Excel...',
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
            accion: 'exportar_excel_cliente',
            cliente_id: clienteId,
            cliente_nombre: clienteNombre
        },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            Swal.close();
            
            // Crear URL para descarga
            var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var url = window.URL.createObjectURL(blob);
            
            // Crear elemento de descarga
            var a = document.createElement('a');
            a.href = url;
            a.download = `Historial_Cliente_${clienteNombre.replace(/[^a-zA-Z0-9]/g, '_')}_${new Date().toISOString().split('T')[0]}.xlsx`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            Toast.fire({
                icon: 'success',
                title: 'Excel generado exitosamente'
            });
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el archivo Excel.', 'error');
        }
    });
}

function exportarPDF() {
    var clienteId = $('#select_cliente').val();
    var clienteNombre = $('#select_cliente option:selected').text();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente primero.', 'warning');
        return;
    }
    
    Swal.fire({
        title: 'Generando PDF...',
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
            accion: 'exportar_pdf_cliente',
            cliente_id: clienteId,
            cliente_nombre: clienteNombre
        },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            Swal.close();
            
            // Crear URL para descarga
            var blob = new Blob([data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            
            // Crear elemento de descarga
            var a = document.createElement('a');
            a.href = url;
            a.download = `Historial_Cliente_${clienteNombre.replace(/[^a-zA-Z0-9]/g, '_')}_${new Date().toISOString().split('T')[0]}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            Toast.fire({
                icon: 'success',
                title: 'PDF generado exitosamente'
            });
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el archivo PDF.', 'error');
        }
    });
}

function imprimirReporte() {
    var clienteId = $('#select_cliente').val();
    var clienteNombre = $('#select_cliente option:selected').text();
    
    if (!clienteId) {
        Swal.fire('Atención', 'Debe seleccionar un cliente primero.', 'warning');
        return;
    }
    
    // Abrir ventana de impresión con reporte profesional
    var url = `ajax/reportes_ajax.php?accion=imprimir_cliente&cliente_id=${clienteId}&cliente_nombre=${encodeURIComponent(clienteNombre)}`;
    var ventanaImpresion = window.open(url, 'impresion', 'width=800,height=600,scrollbars=yes');
    
    if (ventanaImpresion) {
        ventanaImpresion.addEventListener('load', function() {
            ventanaImpresion.print();
        });
    } else {
                 Swal.fire('Error', 'No se pudo abrir la ventana de impresión. Verifique que no esté bloqueada por el navegador.', 'error');
     }
 }
 
 function enviarCorreoReporteCliente() {
     var clienteId = $('#select_cliente').val();
     var clienteNombre = $('#select_cliente option:selected').text();
     
     if (!clienteId) {
         Swal.fire('Atención', 'Debe seleccionar un cliente primero.', 'warning');
         return;
     }
     
     Swal.fire({
         title: 'Enviar Reporte por Correo',
         html: `
             <div class="form-group text-left">
                 <label for="emailDestino">Correo electrónico de destino:</label>
                 <input type="email" id="emailDestino" class="form-control" placeholder="ejemplo@correo.com">
             </div>
             <div class="form-group text-left">
                 <label for="asuntoCorreo">Asunto:</label>
                 <input type="text" id="asuntoCorreo" class="form-control" value="Historial del Cliente - ${clienteNombre}">
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
                     accion: 'enviar_correo_reporte_cliente',
                     cliente_id: clienteId,
                     cliente_nombre: clienteNombre,
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
</script>

<?php 
} else {
    header("Location: index.php");
}
?> 