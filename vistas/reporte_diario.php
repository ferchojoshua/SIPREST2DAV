<!-- Incluir estilos estándar -->
<link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">   
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Reporte Ingreso e Egresos</h4>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                    <li class="breadcrumb-item active">Reporte Ingreso e Egreso</li>
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
                        <h3 class="card-title">Reporte Ingreso e Egreso</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" onclick="exportarExcelReporteDiario()">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="exportarPDFReporteDiario()">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                            <button type="button" class="btn btn-info btn-sm" onclick="imprimirReporteDiario()">
                                <i class="fas fa-print"></i> Imprimir
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" onclick="enviarCorreoReporteDiario()">
                                <i class="fas fa-envelope"></i> Enviar por Correo
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="fechaReporteDiario">Seleccionar Fecha:</label>
                                    <input type="date" class="form-control form-control-sm" id="fechaReporteDiario" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <div class="form-group">
                                    <button class="btn btn-primary btn-sm" id="btnFiltrarReporteDiario">Filtrar</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 table-responsive">
                            <table id="tbl_reporte_diario" class="table display table-hover text-nowrap compact w-100 rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>Tipo Operación</th>
                                        <th>Cantidad</th>
                                        <th>Monto Capital</th>
                                        <th>Monto Interés</th>
                                        <th>Monto Total</th>                                     
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
<?php require_once "modulos/footer.php"; ?>
<script>
$(document).ready(function() {
    var tablaReporteDiario;

    function cargarReporteDiario(fecha) {
        if (tablaReporteDiario) {
            tablaReporteDiario.destroy();
        }

        var data = new FormData();
        data.append('accion', 10); // ID para Reporte Diario en ajax/reportes_ajax.php
        data.append('fecha', fecha);

        $.ajax({
            url: "ajax/reportes_ajax.php",
            method: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(respuesta) {
                console.log("Respuesta Reporte Diario:", respuesta);
                var dataSet = [];
                if (respuesta && !respuesta.error) {
                    $.each(respuesta, function(index, item) {
                        dataSet.push([
                            item.tipo_operacion,
                            item.cantidad,
                            parseFloat(item.monto_capital).toFixed(2),
                            parseFloat(item.monto_interes).toFixed(2),
                            parseFloat(item.monto_total).toFixed(2)
                        ]);
                    });
                } else if (respuesta.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: respuesta.error,
                    });
                }

                tablaReporteDiario = $('#tbl_reporte_diario').DataTable({
                    data: dataSet,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
                    "responsive": true,
                    "autoWidth": false,
                    "deferRender": true,
                    "retrieve": true,
                    "dom": '<"row"<"col-sm-5"l><"col-sm-7"f>>' +
                           '<"row"<"col-sm-12"tr>>' +
                           '<"row"<"col-sm-6"i><"col-sm-6"p>>',
                    "language": {
                        "url": "vistas/assets/plugins/datatables/i18n/Spanish.json"
                    }
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", textStatus, errorThrown, jqXHR.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Conexión',
                    text: 'No se pudo cargar el reporte diario. Intenta de nuevo más tarde.',
                });
            }
        });
    }

    // Cargar reporte al inicio con la fecha actual
    cargarReporteDiario($('#fechaReporteDiario').val());

    $('#btnFiltrarReporteDiario').on('click', function() {
        var fechaSeleccionada = $('#fechaReporteDiario').val();
        cargarReporteDiario(fechaSeleccionada);
    });

    // Variable global para almacenar la fecha actual del reporte
    window.fechaReporteActual = $('#fechaReporteDiario').val();

    // Actualizar fecha global cuando se filtre
    $('#btnFiltrarReporteDiario').on('click', function() {
        window.fechaReporteActual = $('#fechaReporteDiario').val();
    });

});

// Funciones para exportación profesional
function exportarExcelReporteDiario() {
    var fecha = window.fechaReporteActual || $('#fechaReporteDiario').val();
    
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
            accion: 'exportar_excel_reporte_diario',
            fecha: fecha
        },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            Swal.close();
            
            var blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            var url = window.URL.createObjectURL(blob);
            
            var a = document.createElement('a');
            a.href = url;
            a.download = `Reporte_Diario_${fecha}.xlsx`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            Swal.fire({
                icon: 'success',
                title: 'Excel generado exitosamente',
                showConfirmButton: false,
                timer: 1500
            });
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el archivo Excel.', 'error');
        }
    });
}

function exportarPDFReporteDiario() {
    var fecha = window.fechaReporteActual || $('#fechaReporteDiario').val();
    
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
            accion: 'exportar_pdf_reporte_diario',
            fecha: fecha
        },
        xhrFields: {
            responseType: 'blob'
        },
        success: function(data, status, xhr) {
            Swal.close();
            
            var blob = new Blob([data], { type: 'application/pdf' });
            var url = window.URL.createObjectURL(blob);
            
            var a = document.createElement('a');
            a.href = url;
            a.download = `Reporte_Diario_${fecha}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            Swal.fire({
                icon: 'success',
                title: 'PDF generado exitosamente',
                showConfirmButton: false,
                timer: 1500
            });
        },
        error: function() {
            Swal.close();
            Swal.fire('Error', 'Error al generar el archivo PDF.', 'error');
        }
    });
}

function imprimirReporteDiario() {
    var fecha = window.fechaReporteActual || $('#fechaReporteDiario').val();
    
    var url = `ajax/reportes_ajax.php?accion=imprimir_reporte_diario&fecha=${fecha}`;
    var ventanaImpresion = window.open(url, 'impresion', 'width=800,height=600,scrollbars=yes');
    
    if (ventanaImpresion) {
        ventanaImpresion.addEventListener('load', function() {
            ventanaImpresion.print();
        });
    } else {
        Swal.fire('Error', 'No se pudo abrir la ventana de impresión. Verifique que no esté bloqueada por el navegador.', 'error');
    }
}

function enviarCorreoReporteDiario() {
    var fecha = window.fechaReporteActual || $('#fechaReporteDiario').val();
    
    Swal.fire({
        title: 'Enviar Reporte por Correo',
        html: `
            <div class="form-group text-left">
                <label for="emailDestino">Correo electrónico de destino:</label>
                <input type="email" id="emailDestino" class="form-control" placeholder="ejemplo@correo.com">
            </div>
            <div class="form-group text-left">
                <label for="asuntoCorreo">Asunto:</label>
                <input type="text" id="asuntoCorreo" class="form-control" value="Reporte Diario - ${fecha}">
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
                    accion: 'enviar_correo_reporte_diario',
                    fecha: fecha,
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