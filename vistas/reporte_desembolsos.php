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
                    <h4 class="m-0">Reporte de Desembolsos</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                        <li class="breadcrumb-item active">Reporte de Desembolsos</li>
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
                            <h3 class="card-title">Desembolsos Diarios e Históricos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" id="exportarExcelDesembolsos">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" id="exportarPdfDesembolsos">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" class="btn btn-info btn-sm" id="imprimirDesembolsos">
                                    <i class="fas fa-print"></i> Imprimir
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="fechaInicio" class="col-sm-2 col-form-label">Fecha Inicio:</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control" id="fechaInicio">
                                </div>
                                <label for="fechaFin" class="col-sm-2 col-form-label">Fecha Fin:</label>
                                <div class="col-sm-4">
                                    <input type="date" class="form-control" id="fechaFin">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12 text-right">
                                    <button type="button" class="btn btn-primary" id="btnFiltrarDesembolsos">
                                        <i class="fas fa-filter"></i> Filtrar
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="tbl_desembolsos" class="table table-striped table-bordered table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Número Préstamo</th>
                                            <th>Cliente</th>
                                            <th>Monto Desembolsado</th>
                                            <th>Fecha Desembolso</th>
                                            <th>Fecha Registro Préstamo</th>
                                            <th>Estado Aprobación</th>
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

<?php require_once "modulos/footer.php"; ?>

<!-- REQUIRED SCRIPTS -->
<script src="vistas/assets/plugins/jquery/jquery.min.js"></script>
<script src="vistas/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="vistas/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="vistas/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="vistas/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="vistas/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="vistas/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="vistas/assets/plugins/jszip/jszip.min.js"></script>
<script src="vistas/assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="vistas/assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="vistas/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="vistas/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="vistas/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="vistas/assets/plugins/moment/moment.min.js"></script>
<script src="vistas/assets/plugins/inputmask/jquery.inputmask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    var tablaDesembolsos;

    // Función robusta para cerrar modales de desembolsos
    function cerrarModalDesebolsos(modalId, origen) {
        let modalCerrado = false;
        
        // Método 1: Swal.close() estándar
        try {
            if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                Swal.close();
                modalCerrado = true;
            }
        } catch (e) {
            console.error('Error con Swal.close():', e);
        }
        
        // Método 2: Limpieza manual del DOM (backup)
        setTimeout(function() {
            const containers = $('.swal2-container');
            if (containers.length > 0) {
                containers.remove();
                modalCerrado = true;
            }
            
            // Limpiar clases del body y html
            $('body').removeClass('swal2-shown swal2-height-auto');
            $('html').removeClass('swal2-shown swal2-height-auto');
        }, 100);
    }

    // Función mejorada para cargar desembolsos con manejo robusto de errores
    function cargarDesembolsos(fechaInicio = '', fechaFin = '') {
        try {
            // Verificar si SweetAlert2 está disponible
            if (typeof Swal === 'undefined') {
                alert('Error: SweetAlert2 no está disponible. Recargue la página.');
                return;
            }
            
            // Forzar cierre de cualquier modal anterior
            if (Swal.isVisible()) {
                Swal.close();
            }
            
            // Limpiar cualquier modal residual en el DOM
            $('.swal2-container').remove();
            $('body').removeClass('swal2-shown swal2-height-auto');
            $('html').removeClass('swal2-shown swal2-height-auto');
            
            // Crear modal con ID único para rastreo
            const modalId = 'modal-desembolsos-' + Date.now();
            Swal.fire({
                title: 'Cargando datos...',
                text: 'Por favor espere.',
                allowEscapeKey: false,
                allowOutsideClick: false,
                showConfirmButton: false,
                customClass: {
                    container: modalId
                },
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Limpiar tabla existente
            if ($.fn.DataTable.isDataTable('#tbl_desembolsos')) {
                $('#tbl_desembolsos').DataTable().destroy();
            }

            tablaDesembolsos = $('#tbl_desembolsos').DataTable({
                "ajax": {
                    "url": "ajax/reportes_ajax.php",
                    "type": "POST",
                    "data": function(d) {
                        d.accion = "obtener_desembolsos";
                        d.fecha_inicio = fechaInicio;
                        d.fecha_fin = fechaFin;
                        return d;
                    },
                    "dataSrc": "",
                    "success": function(response) {
                        // Cerrar modal de múltiples formas para garantizar cierre
                        cerrarModalDesebolsos(modalId, 'success');
                        
                        if (response.error) {
                            Swal.fire('Error', response.message, 'error');
                            return;
                        }
                    },
                    "error": function(xhr, status, error) {
                        // Cerrar modal de múltiples formas
                        cerrarModalDesebolsos(modalId, 'error');
                        
                        Swal.fire('Error', 'Error al cargar los datos. Intente nuevamente.', 'error');
                        console.error("Error AJAX:", status, error);
                    },
                    "complete": function() {
                        // No necesario cerrar aquí, ya se hace en success/error
                    }
                },
                "columns": [
                    { "data": "nro_prestamo" },
                    { "data": "cliente_nombres" },
                    { "data": "monto_desembolsado" },
                    { "data": "fecha_desembolso" },
                    { "data": "fecha_registro_prestamo" },
                    { "data": "pres_aprobacion" }
                ],
                "order": [[3, "desc"]], // Order by fecha_desembolso descending
                "language": {
                    "url": "vistas/assets/plugins/datatables/i18n/Spanish.json"
                },
                "dom": 'Bfrtip',
                "buttons": [
                    'colvis' // Only column visibility, custom buttons are in HTML
                ],
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "drawCallback": function(settings) {
                    // Tabla dibujada correctamente
                }
            });
        } catch (e) {
            console.error('Error inesperado en cargarDesembolsos:', e);
            alert('Error inesperado al cargar los datos. Intente nuevamente.');
        }
    }

        // Cargar desembolsos al cargar la página (todos históricos)
        cargarDesembolsos();

        // Evento de clic para el botón de filtrar
        $('#btnFiltrarDesembolsos').on('click', function() {
            var fechaInicio = $('#fechaInicio').val();
            var fechaFin = $('#fechaFin').val();
            cargarDesembolsos(fechaInicio, fechaFin);
        });

        // Custom export buttons handlers
        $('#exportarExcelDesembolsos').on('click', function() {
            tablaDesembolsos.button('.buttons-excel').trigger();
        });

        $('#exportarPdfDesembolsos').on('click', function() {
            tablaDesembolsos.button('.buttons-pdf').trigger();
        });

        $('#imprimirDesembolsos').on('click', function() {
            tablaDesembolsos.button('.buttons-print').trigger();
        });
    });
</script> 