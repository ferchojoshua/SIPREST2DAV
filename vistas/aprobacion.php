<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h4 class="m-0">Aprobar Solicitud de Prestamos</h4>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                    <li class="breadcrumb-item active">Aprobar Prestamos</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content pb-2">
    <div class="container-fluid">
        <div class="row p-0 m-0">
            <div class="col-md-12">
                <div class="card card-info card-outline shadow ">
                    <div class="card-header bg-gradient-info">
                        <h3 class="card-title">Listado de Prestamos por aprobar</h3>

                    </div>
                    <div class=" card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">
                                        <span class="small">Fecha Inicio:</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>
                                        <input type="date" class="form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" id="text_fecha_I">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">
                                        <span class="small">Fecha Fin:</span>
                                    </label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="far fa-calendar-alt"></i></span></div>
                                        <input type="date" class="form-control form-control-sm" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" id="text_fecha_F">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 d-flex flex-row align-items-center justify-content-end">
                                <div class="form-group m-0"><a class="btn btn-primary btn-sm" style="width:120px;" id="btnFiltrar">Buscar</a></div>
                            </div>
                        </div><br>

                        <div class="table-responsive">
                            <table id="tbl_aprobacion_pres" class="table display table-hover text-nowrap compact  w-100  rounded">
                                <thead class="bg-gradient-info text-white">
                                    <tr>
                                        <th>Id</th>
                                        <th>Nº Prestamo</th>
                                        <th>cliente id</th>
                                        <th>Cliente</th>
                                        <th>Monto</th>
                                        <th>Interes</th>
                                        <th>Cuotas</th>
                                        <th>fpago id</th>
                                        <th>F. Pago</th>
                                        <th>usuario id</th>
                                        <th>Usuario</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th class="text-cetner">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody class="small text left">
                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->

<!-- MODAL DETALLE PRESTAMO-->
<div class="modal fade" id="modal_detalle_prestamo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray py-1 align-items-center">
                <h5 class="modal-title" id="titulo_modal_cliente">Detalle del Prestamo</h5>
                <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_detalle" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <form class="needs-validation" novalidate> -->
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <input type="text" id="" hidden>
                                <span class="small"> Nro Prestamo</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_nro_prestamo_d" placeholder="Nro_prestamo" disabled>

                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Cliente</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_cliente_d" placeholder="Documento" disabled>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Monto Prestamo.</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_monto_d" placeholder="Documento" disabled>


                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Interes</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_interes_d" name="text_glosa" placeholder="Descripcion" disabled>


                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Nro. Cuota</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_cuota_d" name="text_glosa" placeholder="Descripcion" disabled>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Forma de Pago</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_fpago__d" name="text_glosa" placeholder="Descripcion" disabled>

                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Fecha Emision</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_fecha__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Monto Cuota</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_monto_cuota__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Monto Interes</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_monto_interes__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Monto Total</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_monto_total__d" name="text_fecha__d" placeholder="Descripcion" disabled>


                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-2">
                            <label for="" class="">
                                <span class="small"> Cuotas Pagadas</span>
                            </label>
                            <input type="text" class=" form-control form-control-sm" id="text_cuotas_pagadas__d" name="text_fecha__d" placeholder="" disabled>


                        </div>
                    </div>
                </div>
                <!-- Resumen del Préstamo -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card card-outline card-info">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-info-circle"></i> Resumen del Préstamo
                                </h6>
                            </div>
                            <div class="card-body p-2">
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">Monto Prestado:</small><br>
                                        <strong id="resumen_monto_prestado">S/ 0.00</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Total a Pagar:</small><br>
                                        <strong id="resumen_monto_total">S/ 0.00</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Interés Total:</small><br>
                                        <strong id="resumen_interes_total">S/ 0.00</strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">Estado:</small><br>
                                        <span id="resumen_estado" class="badge badge-info">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="table-responsive">
                        <table id="tbl_detalle_prestamo" class="table display table-hover text-nowrap compact  w-100  rounded" style="width:100%;">
                            <thead class="bg-gradient-info text-white">
                                <tr>
                                    <th>Id</th>
                                    <th>Nro prestamo</th>
                                    <th>Cuota</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Saldo</th>
                                    <th>Estado</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody class="small text left">
                            </tbody>
                            <tfoot class="bg-gradient-secondary text-white">
                                <tr>
                                    <th colspan="4" class="text-right"><strong>TOTALES:</strong></th>
                                    <th id="total_monto"><strong>S/ 0.00</strong></th>
                                    <th id="total_saldo"><strong>S/ 0.00</strong></th>
                                    <th id="total_cuotas"><strong>0 cuotas</strong></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>


                </div>
                <!-- </form> -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_detallee">Cerrar</button>
                <!-- <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</button> -->
            </div>
        </div>
    </div>
</div>
<!-- fin Modal -->


<!-- MODAL ASIGNACIÓN DE RUTA Y COBRADOR -->
<div class="modal fade" id="modal_asignacion_ruta" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info py-2 align-items-center">
                <h5 class="modal-title text-white" id="titulo_modal_asignacion">
                    <i class="fas fa-route"></i> Asignar Ruta y Cobrador
                </h5>
                <button type="button" class="close text-white border-0 fs-5" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <input type="hidden" id="nro_prestamo_asignacion" name="nro_prestamo_asignacion">
                    <input type="hidden" id="cliente_id_asignacion" name="cliente_id_asignacion">
                    
                    <!-- Información del préstamo -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info" role="alert">
                                <h6 class="alert-heading"><i class="fas fa-info-circle"></i> Información del Préstamo</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Préstamo:</strong> <span id="info_nro_prestamo">-</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Cliente:</strong> <span id="info_cliente">-</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Monto:</strong> <span id="info_monto">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Formulario de asignación -->
                <form id="form_asignacion" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="select_sucursal_asignacion" class="form-label">
                                    <span class="small">Sucursal *</span>
                                </label>
                                <select class="form-control form-control-sm" id="select_sucursal_asignacion" name="sucursal_asignada_id" required>
                                    <option value="">-- Seleccione sucursal --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar una sucursal
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="select_ruta_asignacion" class="form-label">
                                    <span class="small">Ruta *</span>
                                </label>
                                <select class="form-control form-control-sm" id="select_ruta_asignacion" name="ruta_asignada_id" required>
                                    <option value="">-- Primero seleccione sucursal --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe seleccionar una ruta
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="select_cobrador_asignacion" class="form-label">
                                    <span class="small">Cobrador *</span>
                                </label>
                                <select class="form-control form-control-sm" id="select_cobrador_asignacion" name="cobrador_asignado_id" required>
                                    <option value="">-- Primero seleccione ruta --</option>
                                </select>
                                <div class="invalid-feedback">
                                    Debe asignar un cobrador
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group mb-3">
                                <label for="observaciones_asignacion" class="form-label">
                                    <i class="fas fa-comment"></i> <span class="small">Observaciones de Asignación</span>
                                </label>
                                <textarea class="form-control form-control-sm" id="observaciones_asignacion" name="observaciones_asignacion" rows="2" placeholder="Observaciones adicionales sobre la asignación (opcional)"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Amortización -->
                    <div class="row mt-2">
                        <div class="col-12">
                            <h6 class="text-info"><i class="fas fa-list-ol"></i> Plan de Pagos</h6>
                            <div class="table-responsive" style="max-height: 250px;">
                                <table id="tbl_amortizacion_aprobacion" class="table table-striped table-bordered table-sm w-100">
                                    <thead class="bg-info text-white">
                                        <tr>
                                            <th>Cuota</th>
                                            <th>Fecha de Pago</th>
                                            <th>Monto Cuota</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Las filas se cargarán dinámicamente -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                <small>
                                    <i class="fas fa-exclamation-triangle"></i> 
                                    <strong>Importante:</strong> Al aprobar el préstamo, se asignará automáticamente a la ruta y cobrador seleccionados. 
                                    Esta asignación es necesaria para el proceso de cobranza.
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-success btn-sm" id="btn_aprobar_con_asignacion">
                    <i class="fas fa-check"></i> Aprobar y Asignar
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once "modulos/footer.php"; ?>


<script>
    var accion;
    var tbl_aprobacion_pres, fecha_ini, fecha_fin;

    var Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000
    });

    $(document).ready(function() {
        // Asegurar que jQuery y DataTables estén disponibles
        if (typeof $ === 'undefined') {
            console.error('jQuery no está cargado');
            return;
        }
        
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables no está cargado');
            return;
        }
        
        fechas();
        filtroporFechas();


        /***************************************************************************
         * INICIAR DATATABLE PRESTAMO
         ******************************************************************************/
        // var tbl_aprobacion_pres = $("#tbl_aprobacion_pres").DataTable({
        //     responsive: true,


        //     dom: 'Bfrtip',
        //     buttons: [{
        //             "extend": 'excelHtml5',
        //             "title": 'Reporte Clientes',
        //             "exportOptions": {
        //                 'columns': [1, 2, 3, 6, 7]
        //             },
        //             "text": '<i class="fa fa-file-excel"></i>',
        //             "titleAttr": 'Exportar a Excel'
        //         },
        //         {
        //             "extend": 'print',
        //             "text": '<i class="fa fa-print"></i> ',
        //             "titleAttr": 'Imprimir',
        //             "exportOptions": {
        //                 'columns': [1, 2, 3, 6, 7]
        //             },
        //             "download": 'open'
        //         },
        //         'pageLength',
        //     ],
        //     ajax: {
        //         url: "ajax/aprobacion_ajax.php",
        //         dataSrc: "",
        //         type: "POST",
        //         data: {
        //             'accion': 1
        //         }, //LISTAR 
        //     },
        //     columnDefs: [{
        //             targets: 0,
        //             visible: false

        //         }, {
        //             targets: 2,
        //             visible: false

        //         }, {
        //             targets: 7,
        //             visible: false

        //         },
        //         {
        //             targets: 9,
        //             visible: false

        //         }, {
        //             targets: 12,
        //             //sortable: false,
        //             createdCell: function(td, cellData, rowData, row, col) {

        //                 if (rowData[12] == 'aprobado') {
        //                     $(td).html("<span class='badge badge-success'>aprobado</span>")
        //                 } else {
        //                     $(td).html("<span class='badge badge-danger'>pendiente</span>")
        //                 }

        //             }
        //         }, {
        //             targets: 13, //columna 2
        //             sortable: false, //no ordene
        //             render: function(td, cellData, rowData, row, col) {

        //                 if (rowData[12] == 'aprobado') {
        //                     return "<center>" +
        //                         "<span class='  text-secondary px-1 disabled' data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                         "<i class='fas fa-check-circle fs-6'></i> " +
        //                         "</span> " +
        //                         "<span class='btnDesaprobar  text-danger px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Desaprobar Prestamo'> " +
        //                         "<i class='fas fa-minus-circle fs-6'></i> " +
        //                         "</span> " +
        //                         "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
        //                         "<i class='fa fa-eye fs-6'> </i> " +
        //                         "</span>" +
        //                         "</center>"
        //                 } else {
        //                     return "<center>" +
        //                         "<span class='btnAprobar  text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Aprobar Prestamo'> " +
        //                         "<i class='fas fa-check-circle fs-6'></i> " +
        //                         "</span> " +
        //                         "<span class='  text-secondary px-1 disabled'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                         "<i class='fas fa-minus-circle fs-6'></i> " +
        //                         "</span> " +
        //                         "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
        //                         "<i class='fa fa-eye fs-6'> </i> " +
        //                         "</span>" +
        //                         "</center>"
        //                 }

        //             }

        //         }
        //     ],
        //     "order": [
        //         [0, 'desc']
        //     ],
        //     lengthMenu: [0, 5, 10, 15, 20, 50],
        //     "pageLength": 10,
        //     "language": idioma_espanol,
        //     select: true
        // });

        /* ======================================================================================
                APROBAR PRESTAMO CON ASIGNACIÓN DE RUTA
               =========================================================================================*/
        $("#tbl_aprobacion_pres tbody").on('click', '.btnAprobar', function() {

            if (tbl_aprobacion_pres.row(this).child.isShown()) {
                var data = tbl_aprobacion_pres.row(this).data();
            } else {
                var data = tbl_aprobacion_pres.row($(this).parents('tr')).data();
            }

            var nro_prestamo = data[1],
                cliente = data[3],
                monto = data[4],
                interes = data[5],
                cuotas = data[6],
                fpago_id = data[7],
                usuario_creador_id = data[9],
                fecha_emision = data[11],
                cliente_id = data[2];

            $("#nro_prestamo_asignacion").val(nro_prestamo);
            $("#cliente_id_asignacion").val(cliente_id);
            $("#info_nro_prestamo").text(nro_prestamo);
            $("#info_cliente").text(cliente);
            $("#info_monto").text("C$ " + parseFloat(monto).toLocaleString('es-NI', {minimumFractionDigits: 2}));

            // Limpiar formulario y cargar combos
            $("#form_asignacion")[0].reset();
            $("#form_asignacion").removeClass('was-validated');
            
            // Cargar los combos
            cargarSucursales('#select_sucursal_asignacion');
            $('#select_ruta_asignacion').html('<option value="">Primero seleccione una sucursal</option>');
            $('#select_cobrador_asignacion').html('<option value="">Primero seleccione una ruta</option>');

            // Cargar el plan de pago
            cargarPlanPagoModal(nro_prestamo, monto, interes, cuotas, fpago_id, fecha_emision);

            // Configurar y mostrar modal
            $("#modal_asignacion_ruta").modal({
                backdrop: 'static',
                keyboard: false
            });
            $("#modal_asignacion_ruta").modal('show');

        })


        /* ======================================================================================
              DESAPROBAR PRESTAMO
              =========================================================================================*/
        $("#tbl_aprobacion_pres tbody").on('click', '.btnDesaprobar', function() {

            accion = 3; //seteamos la accion para Eliminar

            if (tbl_aprobacion_pres.row(this).child.isShown()) {
                var data = tbl_aprobacion_pres.row(this).data();
            } else {
                var data = tbl_aprobacion_pres.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                //  console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
            }

            var nro_prestamo = data[1];
            var pres_aprobacion = data[12];
            //  console.log(nro_prestamo,pres_aprobacion );

            Swal.fire({
                title: 'Desea Desaprobar el Prestamo "' + data[1] + '" ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                confirmButtonText: 'Si, Desaprobar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {

                if (result.isConfirmed) {

                    var datos = new FormData();

                    datos.append("accion", accion);
                    datos.append("nro_prestamo", nro_prestamo); //jalamos el codigo que declaramos mas arriba
                    //datos.append("pres_aprobacion", pres_aprobacion);


                    $.ajax({
                        url: "ajax/aprobacion_ajax.php",
                        method: "POST",
                        data: datos, //enviamos lo de la variable datos
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(respuesta) {

                            if (respuesta > 0) {

                                if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Prestamo Desaprobado'
                                    });

                                    tbl_aprobacion_pres.ajax.reload(); //recargamos el datatable 

                                } else {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: 'El Prestamo ya tiene cuotas Pagadas, revisar'
                                    });
                                }

                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error al Desaprobado Prestamo'

                                });
                            }


                            // if (respuesta == "ok") {

                            //     Toast.fire({
                            //         icon: 'success',
                            //         title: 'Prestamo Desaprobado '
                            //         // title: titulo_msj
                            //     });

                            //     tbl_aprobacion_pres.ajax.reload(); //recargamos el datatable

                            // } else {
                            //     Toast.fire({
                            //         icon: 'error',
                            //         title: 'Error al Desaprobado Prestamo'
                            //     });
                            // }

                        }
                    });

                }
            })

        })

        /* ======================================================================================
         //     ANULAR PRESTAMO
              =========================================================================================*/
        $("#tbl_aprobacion_pres tbody").on('click', '.btnAnular', function() {

            accion = 4; //seteamos la accion para Eliminar

            if (tbl_aprobacion_pres.row(this).child.isShown()) {
                var data = tbl_aprobacion_pres.row(this).data();
            } else {
                var data = tbl_aprobacion_pres.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                //  console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
            }

            var nro_prestamo = data[1];
            var pres_aprobacion = data[12];
            //  console.log(nro_prestamo,pres_aprobacion );

            Swal.fire({
                title: 'Desea Anular el Prestamo "' + data[1] + '" ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                confirmButtonText: 'Si, Anular',
                cancelButtonText: 'Cancelar',
            }).then((result) => {

                if (result.isConfirmed) {

                    var datos = new FormData();

                    datos.append("accion", accion);
                    datos.append("nro_prestamo", nro_prestamo); //jalamos el codigo que declaramos mas arriba
                    //datos.append("pres_aprobacion", pres_aprobacion);


                    $.ajax({
                        url: "ajax/aprobacion_ajax.php",
                        method: "POST",
                        data: datos, //enviamos lo de la variable datos
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(respuesta) {

                            if (respuesta == "ok") {

                                Toast.fire({
                                    icon: 'success',
                                    title: 'Prestamo Anulado '
                                    // title: titulo_msj
                                });

                                tbl_aprobacion_pres.ajax.reload(); //recargamos el datatable

                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error al Anular Prestamo'
                                });
                            }

                            // if (respuesta > 0) {

                            //     if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                            //         Toast.fire({
                            //             icon: 'success',
                            //             title: 'Prestamo Anulado'
                            //         });

                            //         tbl_aprobacion_pres.ajax.reload(); //recargamos el datatable 

                            //     } else {
                            //         Toast.fire({
                            //             icon: 'warning',
                            //             title: 'El Prestamo ya tiene cuotas Pagadas, revisar'
                            //         });
                            //     }

                            // } else {
                            //     Toast.fire({
                            //         icon: 'error',
                            //         title: 'Error al Desaprobado Prestamo'

                            //     });
                            // }




                        }
                    });

                }
            })

        })


        /*===================================================================*/
        //FILTRAR AL HACER CLICK EN BOTON
        /*===================================================================*/
        // $("#btnFiltrar").on('click', function() {

        //     tbl_aprobacion_pres.destroy();

        //     if ($("#text_fecha_I").val() == '') {
        //         Toast.fire({
        //             icon: 'error',
        //             title: ' Debe ingresar una fecha de Inicio'
        //         })

        //         if ($("#text_fecha_F").val() == '') {
        //             Toast.fire({
        //                 icon: 'error',
        //                 title: ' Debe ingresar una fecha Fin'
        //             })
        //         }

        //     } else {
        //         fecha_ini = $("#text_fecha_I").val(); //capturamos el valor
        //         fecha_fin = $("#text_fecha_F").val();
        //     }

        //     if ($("#text_fecha_I").val() > $("#text_fecha_F").val()) {
        //         Toast.fire({
        //             icon: 'error',
        //             title: ' Error en rago de fechas, fecha Inicio es mayor a fecha Fin'
        //         })
        //     }

        //     tbl_aprobacion_pres = $("#tbl_aprobacion_pres").DataTable({
        //         responsive: true,


        //         dom: 'Bfrtip',
        //         buttons: [{
        //                 "extend": 'excelHtml5',
        //                 "title": 'Reporte Clientes',
        //                 "exportOptions": {
        //                     'columns': [1, 3, 4, 5, 6, 8, 10, 11, 12]
        //                 },
        //                 "text": '<i class="fa fa-file-excel"></i>',
        //                 "titleAttr": 'Exportar a Excel'
        //             },
        //             {
        //                 "extend": 'print',
        //                 "text": '<i class="fa fa-print"></i> ',
        //                 "titleAttr": 'Imprimir',
        //                 "exportOptions": {
        //                     'columns': [1, 3, 4, 5, 6, 8, 10, 11, 12]
        //                 },
        //                 "download": 'open'
        //             },
        //             'pageLength',
        //         ],
        //         ajax: {
        //             url: "ajax/aprobacion_ajax.php",
        //             dataSrc: "",
        //             type: "POST",
        //             data: {
        //                 'accion': 1,
        //                 'fecha_ini': fecha_ini,
        //                 'fecha_fin': fecha_fin
        //             }, //LISTAR 
        //         },
        //         columnDefs: [{
        //                 targets: 0,
        //                 visible: false

        //             }, {
        //                 targets: 2,
        //                 visible: false

        //             }, {
        //                 targets: 7,
        //                 visible: false

        //             },
        //             {
        //                 targets: 9,
        //                 visible: false

        //             }, {
        //                 targets: 12,
        //                 //sortable: false,
        //                 createdCell: function(td, cellData, rowData, row, col) {

        //                     if (rowData[12] == 'aprobado') {
        //                         $(td).html("<span class='badge badge-success'>aprobado</span>")
        //                     } else if (rowData[12] == 'pendiente') {
        //                         $(td).html("<span class='badge badge-warning'>pendiente</span>")
        //                     } else if (rowData[12] == 'anulado') {
        //                         $(td).html("<span class='badge badge-danger'>anulado</span>")
        //                     } else {
        //                         $(td).html("<span class='badge badge-info'>finalizado</span>")

        //                     }

        //                 }
        //             }, {
        //                 targets: 13, //columna 2
        //                 sortable: false, //no ordene
        //                 render: function(td, cellData, rowData, row, col) {

        //                     if (rowData[12] == 'aprobado') {
        //                         return "<center>" +

        //                             "<span class='btnDesaprobar  text-warning px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Desaprobar Prestamo'> " +
        //                             "<i class='fas fa-minus-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                             "<i class='fas fa-times-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
        //                             "<i class='fa fa-eye fs-6'> </i> " +
        //                             "</span>" +
        //                             "</center>"
        //                     } else if (rowData[12] == 'finalizado') {
        //                         return "<center>" +

        //                             "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                             "<i class='fas fa-check-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                             "<i class='fas fa-times-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
        //                             "<i class='fa fa-eye fs-6'> </i> " +
        //                             "</span>" +
        //                             "</center>"
        //                     } else if (rowData[12] == 'anulado') {
        //                         return "<center>" +

        //                             "<span class='btnDesaprobar  text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Reactivar Prestamo'> " +
        //                             "<i class='fas fa-check-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
        //                             "<i class='fas fa-times-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
        //                             "<i class='fa fa-eye fs-6'> </i> " +
        //                             "</span>" +
        //                             "</center>"
        //                     } else {
        //                         return "<center>" +
        //                             "<span class='btnAprobar  text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Aprobar Prestamo'> " +
        //                             "<i class='fas fa-check-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='btnAnular  text-danger px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Anular Prestamo'> " +
        //                             "<i class='fas fa-times-circle fs-6'></i> " +
        //                             "</span> " +
        //                             "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
        //                             "<i class='fa fa-eye fs-6'> </i> " +
        //                             "</span>" +
        //                             "</center>"
        //                     }

        //                 }

        //             }
        //         ],
        //         "order": [
        //             [0, 'desc']
        //         ],
        //         lengthMenu: [0, 5, 10, 15, 20, 50],
        //         "pageLength": 10,
        //         "language": idioma_espanol,
        //         select: true
        //     });




        // })

        $("#btnFiltrar").on('click', function() {
            filtroporFechas();
            validar();

        })


         /* ======================================================================================
            VER DETALLE DE PAGOS  -
          =========================================================================================*/
          $("#tbl_aprobacion_pres tbody").on('click', '.btnVerDetalle', function() {
              console.log("[DEBUG] Click en btnVerDetalle detectado");
              
              if (tbl_aprobacion_pres.row(this).child.isShown()) {
                  var data = tbl_aprobacion_pres.row(this).data();
              } else {
                  var data = tbl_aprobacion_pres.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }
              
              console.log("[DEBUG] Datos obtenidos de la fila:", data);
              console.log("[DEBUG] Número de préstamo (data[1]):", data[1]);

              // Verificar que tenemos datos
              if (!data || !data[1]) {
                  console.error("[ERROR] No se pudieron obtener los datos de la fila");
                  Toast.fire({
                      icon: 'error',
                      title: 'Error al cargar datos del préstamo'
                  });
                  return;
              }

                             // Obtener datos completos del préstamo
               var nro_prestamo = data[1];
               console.log("[DEBUG] Obteniendo datos completos del préstamo:", nro_prestamo);
               
               // Llamar a la función que obtiene todos los datos
               obtenerDatosCompletoPrestamo(nro_prestamo, function(datosCompletos) {
                   if (datosCompletos) {
                       console.log("[DEBUG] Datos completos recibidos:", datosCompletos);
                       
                       // Poblar los campos del modal con datos completos
                       $("#text_nro_prestamo_d").val(datosCompletos.nro_prestamo || '');
                       $("#text_cliente_d").val(datosCompletos.cliente_nombres || '');
                       $("#text_monto_d").val((datosCompletos.pres_monto || '0') + ".00");
                       $("#text_interes_d").val((datosCompletos.pres_interes || '0') + " %");
                       $("#text_cuota_d").val(datosCompletos.pres_cuotas || '');
                       $("#text_fpago__d").val(datosCompletos.fpago_descripcion || '');
                       $("#text_fecha__d").val(datosCompletos.pres_f_emision || '');
                       $("#text_monto_cuota__d").val(datosCompletos.pres_monto_cuota || '');
                       $("#text_monto_interes__d").val(datosCompletos.pres_monto_interes || '');
                       $("#text_monto_total__d").val(datosCompletos.pres_monto_total || '');
                       $("#text_cuotas_pagadas__d").val('0'); // Este valor viene del detalle
                       
                       console.log("[DEBUG] Campos del modal poblados con datos completos");
                   } else {
                       console.warn("[WARNING] No se pudieron obtener datos completos, usando datos de la tabla");
                       
                       // Fallback: usar datos de la tabla
                       $("#text_nro_prestamo_d").val(data[1] || '');
                       $("#text_cliente_d").val(data[3] || '');
                       $("#text_monto_d").val((data[4] || '0') + ".00");
                       $("#text_interes_d").val((data[5] || '0') + " %");
                       $("#text_cuota_d").val(data[6] || '');
                       $("#text_fpago__d").val(data[8] || '');
                       $("#text_fecha__d").val(data[11] || '');
                       $("#text_monto_cuota__d").val(data[14] || '');
                       $("#text_monto_interes__d").val(data[15] || '');
                       $("#text_monto_total__d").val(data[16] || '');
                       $("#text_cuotas_pagadas__d").val(data[17] || '');
                   }
                   
                   // Cargar el detalle del préstamo
                   Traer_Detalle(nro_prestamo);
               });

              // Mostrar el modal
              $("#modal_detalle_prestamo").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_detalle_prestamo").modal('show');
              
              console.log("[DEBUG] Modal mostrado");
          })



    }) // FIN DOCUMENT READY


    function filtroporFechas() {
        fecha_ini = $("#text_fecha_I").val(); //capturamos el valor
        fecha_fin = $("#text_fecha_F").val();

        // Verificar que DataTables esté disponible
        if (typeof $.fn.DataTable === 'undefined') {
            console.error('DataTables no está disponible');
            return;
        }

        try {
            tbl_aprobacion_pres = $("#tbl_aprobacion_pres").DataTable({
                responsive: true,
                destroy: true,
                //retrieve: true,
                //searching: false,
                paging: false,
                async: false,
                processing: true,


                dom: 'Bfrtip',
                buttons: [{
                        "extend": 'excelHtml5',
                        "title": 'Reporte Clientes',
                        "exportOptions": {
                            'columns': [1, 3, 4, 5, 6, 8, 10, 11, 12]
                        },
                        "text": '<i class="fa fa-file-excel"></i>',
                        "titleAttr": 'Exportar a Excel'
                    },
                    {
                        "extend": 'print',
                        "text": '<i class="fa fa-print"></i> ',
                        "titleAttr": 'Imprimir',
                        "exportOptions": {
                            'columns': [1, 3, 4, 5, 6, 8, 10, 11, 12]
                        },
                        "download": 'open'
                    },
                    'pageLength',
                ],
                ajax: {
                    url: "ajax/aprobacion_ajax.php",
                    dataSrc: "",
                    type: "POST",
                    data: {
                        'accion': 1,
                        'fecha_ini': fecha_ini,
                        'fecha_fin': fecha_fin
                    }, //LISTAR 
                },
                columnDefs: [{
                        targets: 0,
                        visible: false

                    }, {
                        targets: 2,
                        visible: false

                    }, {
                        targets: 7,
                        visible: false

                    },
                    {
                        targets: 9,
                        visible: false

                    }, {
                        targets: 12,
                        //sortable: false,
                        createdCell: function(td, cellData, rowData, row, col) {

                            if (rowData[12] == 'aprobado') {
                                $(td).html("<span class='badge badge-success'>aprobado</span>")
                            } else if (rowData[12] == 'pendiente') {
                                $(td).html("<span class='badge badge-warning'>pendiente</span>")
                            } else if (rowData[12] == 'anulado') {
                                $(td).html("<span class='badge badge-danger'>anulado</span>")
                            } else {
                                $(td).html("<span class='badge badge-info'>finalizado</span>")

                            }
                        }
                    }, {
                        targets: 13, //columna 2
                        sortable: false, //no ordene
                        render: function(td, cellData, rowData, row, col) {

                            if (rowData[12] == 'aprobado') {
                                return "<center>" +

                                    "<span class='btnDesaprobar  text-warning px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Desaprobar Prestamo'> " +
                                    "<i class='fas fa-minus-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                    "<i class='fas fa-times-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
                                    "<i class='fa fa-eye fs-6'> </i> " +
                                    "</span>" +
                                    "</center>"
                            } else if (rowData[12] == 'finalizado') {
                                return "<center>" +

                                    "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                    "<i class='fas fa-check-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                    "<i class='fas fa-times-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
                                    "<i class='fa fa-eye fs-6'> </i> " +
                                    "</span>" +
                                    "</center>"
                            } else if (rowData[12] == 'anulado') {
                                return "<center>" +

                                    "<span class='btnDesaprobar  text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Reactivar Prestamo'> " +
                                    "<i class='fas fa-check-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='  text-secondary px-1'  data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                    "<i class='fas fa-times-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
                                    "<i class='fa fa-eye fs-6'> </i> " +
                                    "</span>" +
                                    "</center>"
                            } else {
                                return "<center>" +
                                    "<span class='btnAprobar  text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Aprobar Prestamo'> " +
                                    "<i class='fas fa-check-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='btnAnular  text-danger px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Anular Prestamo'> " +
                                    "<i class='fas fa-times-circle fs-6'></i> " +
                                    "</span> " +
                                    "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
                                    "<i class='fa fa-eye fs-6'> </i> " +
                                    "</span>" +
                                    "</center>"
                            }

                        }

                    }
                ],

                lengthMenu: [0, 5, 10, 15, 20, 50],
                "pageLength": 10,
                "language": idioma_espanol,
                select: true
            });
        } catch (error) {
            console.error('Error al inicializar DataTables:', error);
            // Manejar el error, por ejemplo, mostrar un mensaje de error en la consola
        }
    }



    function Traer_Detalle(nro_prestamo) {
        console.log("[DEBUG] Traer_Detalle iniciado con nro_prestamo:", nro_prestamo);
        
        // Verificar que el elemento existe
        if ($("#tbl_detalle_prestamo").length === 0) {
            console.error("[ERROR] Elemento #tbl_detalle_prestamo no encontrado");
            return;
        }
        
        console.log("[DEBUG] Elemento tabla encontrado, inicializando DataTable...");
        
        try {
            // Inicializar DataTable vacío
            tbl_detalle_prestamo = $("#tbl_detalle_prestamo").DataTable({
                responsive: true,
                destroy: true,
                searching: false,
                dom: 'tp',
            columns: [
                { data: 'pdetalle_id' },
                { data: 'nro_prestamo' },
                { data: 'pdetalle_nro_cuota' },
                { data: 'pdetalle_fecha' },
                { data: 'pdetalle_monto_cuota' },
                { data: 'pdetalle_saldo_cuota' },
                { data: 'pdetalle_estado_cuota' },
                { data: null }
            ],
            columnDefs: [{
                    targets: [0, 1],
                    visible: false

                                    }, {
                        targets: 4, // Monto
                        render: function(data, type, row) {
                            let monto = parseFloat(data || 0);
                            let montoFormateado = new Intl.NumberFormat('es-PE', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(monto);
                            
                            // Verificar que existe el símbolo de moneda
                            if (!row.moneda_simbolo) {
                                console.warn("[WARNING] No hay símbolo de moneda en fila:", row);
                                return '-- ' + montoFormateado;
                            }
                            
                            return row.moneda_simbolo + ' ' + montoFormateado;
                        }
                    }, {
                        targets: 5, // Saldo
                        render: function(data, type, row) {
                            let saldo = parseFloat(data || 0);
                            let saldoFormateado = new Intl.NumberFormat('es-PE', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }).format(saldo);
                            
                            // Verificar que existe el símbolo de moneda
                            if (!row.moneda_simbolo) {
                                console.warn("[WARNING] No hay símbolo de moneda en fila:", row);
                                return '-- ' + saldoFormateado;
                            }
                            
                            return row.moneda_simbolo + ' ' + saldoFormateado;
                        }
                    }, {
                    targets: 6, // Estado
                    createdCell: function(td, cellData, rowData, row, col) {

                        if (rowData.pdetalle_estado_cuota == 'pagada') {
                            $(td).html("<span class='badge badge-success'>pagada</span>")
                        } else {
                            $(td).html("<span class='badge badge-danger'>pendiente</span>")
                        }

                    }
                },{
                    targets: 7, // Opciones
                    sortable: false, //no ordene
                    render: function(data, type, row) {
                        return "<center>" +
                            "<span class='btnVerDetalle text-primary px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Detalle'> " +
                            "<i class='fa fa-eye fs-6'> </i> " +
                            "</span>" +
                            "</center>"
                    }
                }
                
            ],

            "language": idioma_espanol,
            select: true
        });
        
        console.log("[DEBUG] DataTable inicializado correctamente");
        
        // Ahora cargar los datos por separado
        cargarDatosDetalle(nro_prestamo);
        
        } catch (error) {
            console.error("[ERROR] Error al inicializar DataTable:", error);
        }
    }

    function cargarDatosDetalle(nro_prestamo) {
        console.log("[DEBUG] Cargando datos del detalle para:", nro_prestamo);
        
        $.ajax({
            url: "ajax/admin_prestamos_ajax.php",
            type: "POST",
            data: {
                'accion': 2,
                'nro_prestamo': nro_prestamo
            },
            beforeSend: function() {
                console.log("[DEBUG] Enviando petición AJAX para detalle...");
            },
            success: function(data) {
                console.log("[DEBUG] Datos del detalle recibidos:", data);
                console.log("[DEBUG] Tipo de datos recibidos:", typeof data);
                console.log("[DEBUG] Es array?:", Array.isArray(data));
                console.log("[DEBUG] Longitud:", data ? data.length : 'N/A');
                
                // Inspeccionar el primer elemento para ver la estructura
                if (Array.isArray(data) && data.length > 0) {
                    console.log("[DEBUG] Primer elemento:", data[0]);
                    console.log("[DEBUG] Campos disponibles:", Object.keys(data[0]));
                    console.log("[DEBUG] Moneda símbolo:", data[0].moneda_simbolo);
                    
                    // Limpiar DataTable y agregar nuevos datos
                    tbl_detalle_prestamo.clear();
                    tbl_detalle_prestamo.rows.add(data);
                    tbl_detalle_prestamo.draw();
                    console.log("[DEBUG] Datos agregados al DataTable exitosamente");
                    
                    // Calcular y mostrar totales
                    calcularTotalesDetalle(data);
                } else {
                    console.log("[DEBUG] No hay datos para mostrar");
                    // Resetear totales si no hay datos
                    resetearTotales();
                }
            },
            error: function(xhr, status, error) {
                console.error("[ERROR] Error al cargar detalle:", error);
                console.error("[ERROR] Status:", status);
                console.error("[ERROR] Response:", xhr.responseText);
                console.error("[ERROR] XHR completo:", xhr);
            }
        });
    }

            function calcularTotalesDetalle(data) {
            console.log("[DEBUG] Calculando totales del detalle...");
            console.log("[DEBUG] Data recibida para totales:", data);
            
            if (!data || !Array.isArray(data) || data.length === 0) {
                console.log("[WARNING] No hay datos para calcular totales");
                resetearTotales();
                return;
            }
            
            let totalMonto = 0;
            let totalSaldo = 0;
            let totalCuotas = data.length;
            let simboloMoneda = null; // Se obtendrá de los datos
            
            // Variables para el resumen (tomamos del primer elemento)
            let primerElemento = data[0];
            let montoPrestado = 0;
            let montoTotal = 0;
            let interesTotal = 0;
            
            // Primero obtener la moneda del primer elemento
            if (primerElemento && primerElemento.moneda_simbolo) {
                simboloMoneda = primerElemento.moneda_simbolo;
                console.log("[DEBUG] Símbolo de moneda obtenido:", simboloMoneda);
            } else {
                console.error("[ERROR] No se pudo obtener el símbolo de moneda de los datos");
                console.log("[DEBUG] Primer elemento:", primerElemento);
                // No establecer un valor por defecto, usar null para detectar el error
            }
            
            // Calcular totales de cuotas
            data.forEach(function(cuota, index) {
                let montoCuota = parseFloat(cuota.pdetalle_monto_cuota || 0);
                let saldoCuota = parseFloat(cuota.pdetalle_saldo_cuota || 0);
                
                totalMonto += montoCuota;
                totalSaldo += saldoCuota;
                
                if (index === 0) {
                    console.log("[DEBUG] Primera cuota - Monto:", montoCuota, "Saldo:", saldoCuota);
                }
            });
            
            // Obtener datos del préstamo (están repetidos en cada cuota)
            if (primerElemento) {
                montoPrestado = parseFloat(primerElemento.pres_monto || 0);
                montoTotal = parseFloat(primerElemento.pres_monto_total || 0);
                interesTotal = montoTotal - montoPrestado;
                
                console.log("[DEBUG] Datos del préstamo - Prestado:", montoPrestado, "Total:", montoTotal, "Interés:", interesTotal);
            }
                    
            // Formatear números con comas para miles
            function formatearNumero(numero) {
                return new Intl.NumberFormat('es-PE', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(numero);
            }
            
            // Verificar que tenemos símbolo de moneda antes de mostrar
            if (!simboloMoneda) {
                console.error("[ERROR] No hay símbolo de moneda disponible. No se pueden mostrar los totales.");
                resetearTotales();
                return;
            }
            
            // Actualizar los totales en el footer de la tabla
            $('#total_monto').html(`<strong>${simboloMoneda} ${formatearNumero(totalMonto)}</strong>`);
            $('#total_saldo').html(`<strong>${simboloMoneda} ${formatearNumero(totalSaldo)}</strong>`);
            $('#total_cuotas').html(`<strong>${totalCuotas} cuotas</strong>`);
            
            // Actualizar el resumen del préstamo
            $('#resumen_monto_prestado').text(`${simboloMoneda} ${formatearNumero(montoPrestado)}`);
            $('#resumen_monto_total').text(`${simboloMoneda} ${formatearNumero(montoTotal)}`);
            $('#resumen_interes_total').text(`${simboloMoneda} ${formatearNumero(interesTotal)}`);
            
            // Calcular estado basado en saldos
            let estadoBadge = '';
            if (totalSaldo <= 0) {
                estadoBadge = '<span class="badge badge-success">Pagado Completo</span>';
            } else if (totalSaldo < totalMonto) {
                estadoBadge = '<span class="badge badge-warning">Pago Parcial</span>';
            } else {
                estadoBadge = '<span class="badge badge-danger">Pendiente</span>';
            }
            $('#resumen_estado').html(estadoBadge);
            
            console.log("[DEBUG] Totales calculados - Monto:", totalMonto, "Saldo:", totalSaldo, "Cuotas:", totalCuotas);
            console.log("[DEBUG] Resumen - Prestado:", montoPrestado, "Total:", montoTotal, "Interés:", interesTotal);
            console.log("[DEBUG] Símbol de moneda usado:", simboloMoneda);
        }

            function resetearTotales(simboloMoneda = null) {
            console.log("[DEBUG] Reseteando totales con moneda:", simboloMoneda);
            
            // Si no hay símbolo de moneda, mostrar placeholders sin moneda
            if (!simboloMoneda) {
                $('#total_monto').html('<strong>-- 0.00</strong>');
                $('#total_saldo').html('<strong>-- 0.00</strong>');
                $('#total_cuotas').html('<strong>0 cuotas</strong>');
                
                $('#resumen_monto_prestado').text('-- 0.00');
                $('#resumen_monto_total').text('-- 0.00');
                $('#resumen_interes_total').text('-- 0.00');
                $('#resumen_estado').html('<span class="badge badge-secondary">Sin datos</span>');
                return;
            }
            
            // Resetear totales de la tabla con moneda
            $('#total_monto').html(`<strong>${simboloMoneda} 0.00</strong>`);
            $('#total_saldo').html(`<strong>${simboloMoneda} 0.00</strong>`);
            $('#total_cuotas').html('<strong>0 cuotas</strong>');
            
            // Resetear resumen del préstamo
            $('#resumen_monto_prestado').text(`${simboloMoneda} 0.00`);
            $('#resumen_monto_total').text(`${simboloMoneda} 0.00`);
            $('#resumen_interes_total').text(`${simboloMoneda} 0.00`);
            $('#resumen_estado').html('<span class="badge badge-secondary">-</span>');
        }

    function fechas() {
        var f = new Date();
        var anio = f.getFullYear();
        var mes = f.getMonth() + 1;
        var d = f.getDate();
        
        if (d < 10) {
            d = '0' + d;
        }
        if (mes < 10) {
            mes = '0' + mes;
        }

        // Establecer rango amplio para mostrar todos los préstamos del año
        // Fecha inicio: 1 de enero del año actual
        document.getElementById('text_fecha_I').value = anio + "-01-01";
        // Fecha fin: 31 de diciembre del año actual  
        document.getElementById('text_fecha_F').value = anio + "-12-31";
    }

    function validar() {

        var fecha_ini = document.getElementById('text_fecha_I').value;
        var fecha_fin = document.getElementById('text_fecha_F').value;
        if (fecha_ini.length == 0 || fecha_fin.length == 0) {

            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Seleccione una Fecha de inicio y de fin',
                showConfirmButton: true,
                timer: 1500
            })
            $("#text_fecha_I").focus();
        }
        if (fecha_ini > fecha_fin) {
            Toast.fire({
                icon: 'error',
                title: ' La fecha de inicio no puede ser mayor a la fecha fin'
            })
            $("#text_fecha_I").focus();
        }
    }


    var idioma_espanol = {
        "sProcessing": "Procesando...",
        "sLengthMenu": "Mostrar _MENU_ registros",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "Ningun dato disponible en esta tabla",
        "sInfo": "Del _START_ al _END_ de _TOTAL_ registros",
        "sInfoEmpty": "Del 0 al 0 de 0 registros",
        "sInfoFiltered": "(filtrado de _MAX_ registros)",
        "sInfoPostFix": "",
        "sSearch": "Buscar:",
        "sUrl": "",
        "sInfoThousands": ",",
        "sLoadingRecords": "<b>No se encontraron datos</b>",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Ultimo",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "aria": {
            "sSortAscending": ": ordenar de manera Ascendente",
            "SSortDescending": ": ordenar de manera Descendente ",
        }
    }

    /*===================================================================*/
    // FUNCIONES PARA EL MODAL DE ASIGNACIÓN DE RUTA Y COBRADOR
    /*===================================================================*/
    
    // Inicializar combos mejorados cuando se abra el modal
    // COMENTADO: Ahora usamos configuración Select2 directa en lugar de CombosMejorados
    /*
    $(document).on('show.bs.modal', '#modal_asignacion_ruta', function() {
        // Usar el sistema de combos mejorados
        window.CombosMejorados.configurarCascada(
            '#select_sucursal_asignacion',
            '#select_ruta_asignacion', 
            '#select_cobrador_asignacion',
            {
                sucursal: { dropdownParent: $('#modal_asignacion_ruta') },
                ruta: { dropdownParent: $('#modal_asignacion_ruta') },
                cobrador: { dropdownParent: $('#modal_asignacion_ruta') }
            }
        );
    });
    */

    // Evento del botón aprobar con asignación
    $(document).on('click', '#btn_aprobar_con_asignacion', function() {
        // Validar formulario
        const form = document.getElementById('form_asignacion');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            
            Toast.fire({
                icon: 'warning',
                title: 'Por favor complete todos los campos requeridos'
            });
            return;
        }

        const nro_prestamo = $('#nro_prestamo_asignacion').val();
        const sucursal_asignada_id = $('#select_sucursal_asignacion').val();
        const ruta_asignada_id = $('#select_ruta_asignacion').val();
        const cobrador_asignado_id = $('#select_cobrador_asignacion').val();
        const observaciones_asignacion = $('#observaciones_asignacion').val();

        // Confirmar aprobación con asignación
        Swal.fire({
            title: `¿Aprobar préstamo ${nro_prestamo}?`,
            html: `
                <div class="text-left">
                    <p><strong>Sucursal:</strong> ${$('#select_sucursal_asignacion option:selected').text()}</p>
                    <p><strong>Ruta:</strong> ${$('#select_ruta_asignacion option:selected').text()}</p>
                    <p><strong>Cobrador:</strong> ${$('#select_cobrador_asignacion option:selected').text()}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8FCE00',
            cancelButtonColor: '#d50',
            confirmButtonText: 'Sí, Aprobar y Asignar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Mostrar loading
                Swal.fire({
                    title: 'Procesando...',
                    text: 'Aprobando préstamo y asignando ruta',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Enviar datos
                $.ajax({
                    url: 'ajax/aprobacion_ajax.php',
                    type: 'POST',
                    data: {
                        accion: 5, // Nueva acción para aprobar con asignación
                        nro_prestamo: nro_prestamo,
                        sucursal_asignada_id: sucursal_asignada_id,
                        ruta_asignada_id: ruta_asignada_id,
                        cobrador_asignado_id: cobrador_asignado_id,
                        observaciones_asignacion: observaciones_asignacion
                    },
                    dataType: 'json',
                    success: function(response) {
                        Swal.close();
                        
                        if (response.estado === 'ok') {
                            // Cerrar modal
                            $('#modal_asignacion_ruta').modal('hide');
                            
                            Toast.fire({
                                icon: 'success',
                                title: 'Préstamo aprobado y asignado exitosamente'
                            });

                            // Recargar tabla
                            tbl_aprobacion_pres.ajax.reload();
                            
                            // Preguntar por impresión del contrato
                            setTimeout(() => {
                                Swal.fire({
                                    title: '¿Desea imprimir el contrato ahora?',
                                    text: "El contrato se abrirá en una nueva ventana",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#8FCE00',
                                    cancelButtonColor: '#d50',
                                    confirmButtonText: 'Sí, imprimir',
                                    cancelButtonText: 'No, más tarde'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.open(`MPDF/contrato.php?codigo=${nro_prestamo}#zoom=100`, "Contrato", "scrollbars=NO,resizable=YES");
                                    }
                                });
                            }, 1000);
                            
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: response.mensaje || 'Error al aprobar préstamo'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.close();
                        console.error('Error AJAX:', error);
                        Toast.fire({
                            icon: 'error',
                            title: 'Error de comunicación con el servidor'
                        });
                    }
                });
            }
        });
    });

    // Función para obtener datos completos del préstamo
    function obtenerDatosCompletoPrestamo(nro_prestamo, callback) {
        console.log('[DEBUG] ==========================================');
        console.log('[DEBUG] Iniciando obtenerDatosCompletoPrestamo con nro_prestamo:', nro_prestamo);
        console.log('[DEBUG] Tipo de nro_prestamo:', typeof nro_prestamo);
        console.log('[DEBUG] ¿nro_prestamo está vacío?', !nro_prestamo);
        
        if (!nro_prestamo) {
            console.log('[DEBUG] ERROR: nro_prestamo está vacío');
            $("#tbl_amortizacion_aprobacion tbody").html(`
                <tr><td colspan="3" class="text-center text-danger">
                    <i class="fas fa-exclamation-triangle"></i> Error: Número de préstamo no válido
                </td></tr>
            `);
            return;
        }
        
        const url = 'ajax/aprobacion_ajax.php';
        const data = {
            accion: 9,
            nro_prestamo: nro_prestamo
        };
        
        console.log('[DEBUG] URL de la petición:', url);
        console.log('[DEBUG] Datos de la petición:', data);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                console.log('[DEBUG] Enviando petición AJAX...');
                $("#tbl_amortizacion_aprobacion tbody").html('<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Obteniendo datos del préstamo...</td></tr>');
            },
            success: function(response) {
                console.log('[DEBUG] Respuesta del servidor:', response);
                console.log('[DEBUG] Tipo de respuesta:', typeof response);
                console.log('[DEBUG] ¿response es string?', typeof response === 'string');
                
                // Si la respuesta es string, intentar parsear JSON
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                        console.log('[DEBUG] Respuesta parseada:', response);
                    } catch (e) {
                        console.log('[DEBUG] Error al parsear JSON:', e);
                        $("#tbl_amortizacion_aprobacion tbody").html(`
                            <tr><td colspan="3" class="text-center text-danger">
                                <i class="fas fa-exclamation-triangle"></i> Error: Respuesta del servidor no válida
                            </td></tr>
                        `);
                        return;
                    }
                }
                
                if (response.estado === 'ok' && response.data) {
                    console.log('[DEBUG] Datos del préstamo obtenidos exitosamente:', response.data);
                    callback(response.data);
                } else {
                    console.log('[DEBUG] Error en respuesta del servidor:', response);
                    $("#tbl_amortizacion_aprobacion tbody").html(`
                        <tr><td colspan="3" class="text-center text-warning">
                            <i class="fas fa-exclamation-triangle"></i> No se pudieron obtener los datos del préstamo: ${response.mensaje || 'Error desconocido'}
                        </td></tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                console.log('[DEBUG] Error AJAX:', {xhr: xhr, status: status, error: error});
                console.log('[DEBUG] Status HTTP:', xhr.status);
                console.log('[DEBUG] Status Text:', xhr.statusText);
                console.log('[DEBUG] Respuesta del servidor:', xhr.responseText);
                
                let errorMsg = 'Error de comunicación';
                if (xhr.status === 404) {
                    errorMsg = 'Archivo no encontrado';
                } else if (xhr.status === 500) {
                    errorMsg = 'Error interno del servidor';
                } else if (xhr.responseText) {
                    try {
                        const errorResponse = JSON.parse(xhr.responseText);
                        errorMsg = errorResponse.mensaje || errorMsg;
                    } catch (e) {
                        errorMsg = xhr.responseText.substring(0, 100) + '...';
                    }
                }
                
                $("#tbl_amortizacion_aprobacion tbody").html(`
                    <tr><td colspan="3" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i> ${errorMsg}
                    </td></tr>
                `);
            }
        });
    }

    // Función para abrir el modal de asignación (actualizada)
    $(document).on('click', '.btn_asignar_ruta', function() {
        const nro_prestamo = $(this).data('nro_prestamo');
        const cliente = $(this).data('cliente');
        const monto = $(this).data('monto');
        const cliente_id = $(this).data('cliente_id');
        const usuario_creador_id = $(this).data('usuario_creador_id');
        
        $("#nro_prestamo_asignacion").val(nro_prestamo);
        $("#cliente_id_asignacion").val(cliente_id);
        $("#info_nro_prestamo").text(nro_prestamo);
        $("#info_cliente").text(cliente);
        $("#info_monto").text("C$ " + parseFloat(monto).toLocaleString('es-NI', {minimumFractionDigits: 2}));
        
        // Limpiar formulario
        $("#form_asignacion")[0].reset();
        $("#form_asignacion").removeClass('was-validated');
        $("#tbl_amortizacion_aprobacion tbody").html('<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Obteniendo datos...</td></tr>');
        
        // Habilitar botón de aprobar por defecto
        $("#btn_aprobar_asignacion").prop('disabled', false).text('✔ APROBAR Y ASIGNAR');
        
        // Configurar Select2 para los combos con los mismos templates del Dashboard
        $('#select_sucursal_asignacion').select2({
            dropdownParent: $('#modal_asignacion_ruta'),
            placeholder: 'Seleccione una sucursal',
            width: '100%',
            templateResult: function(data) {
                if (!data.id) return data.text;
                const $option = $(data.element);
                const codigo = $option.data('codigo') || '';
                const nombre = $option.data('nombre') || data.text;
                if (codigo) {
                    return $(`<span>${codigo} - ${nombre}</span>`);
                }
                return $(`<span>${nombre}</span>`);
            },
            templateSelection: function(data) {
                if (!data.id) return data.text;
                const $option = $(data.element);
                const codigo = $option.data('codigo') || '';
                const nombre = $option.data('nombre') || data.text;
                if (codigo) {
                    return `${codigo} - ${nombre}`;
                }
                return nombre;
            }
        });

        $('#select_ruta_asignacion').select2({
            dropdownParent: $('#modal_asignacion_ruta'),
            placeholder: 'Seleccione una ruta',
            width: '100%',
            templateResult: function(data) {
                if (!data.id) return data.text;
                const $option = $(data.element);
                const codigo = $option.data('codigo') || '';
                const nombre = $option.data('nombre') || data.text;
                if (codigo) {
                    return $(`<span>${codigo} - ${nombre}</span>`);
                }
                return $(`<span>${nombre}</span>`);
            },
            templateSelection: function(data) {
                if (!data.id) return data.text;
                const $option = $(data.element);
                const codigo = $option.data('codigo') || '';
                const nombre = $option.data('nombre') || data.text;
                if (codigo) {
                    return `${codigo} - ${nombre}`;
                }
                return nombre;
            }
        });

        $('#select_cobrador_asignacion').select2({
            dropdownParent: $('#modal_asignacion_ruta'),
            placeholder: 'Seleccione un cobrador',
            width: '100%',
            templateResult: function(data) {
                if (!data.id) return data.text;
                const $option = $(data.element);
                const nombre = $option.data('nombre') || data.text;
                const sucursal = $option.data('sucursal') || '';
                const perfil = $option.data('perfil') || '';
                
                let html = `<div class="select2-result-cobrador">
                    <div class="cobrador-nombre">${nombre}</div>`;
                
                if (sucursal && sucursal !== 'Sin sucursal') {
                    html += `<div class="text-muted small">
                        <i class="fas fa-building"></i> ${sucursal}`;
                    if (perfil && perfil !== 'Sin perfil') {
                        html += ` - ${perfil}`;
                    }
                    html += `</div>`;
                }
                
                html += `</div>`;
                return $(html);
            },
            templateSelection: function(data) {
                if (!data.id) return data.text;
                const $option = $(data.element);
                return $option.data('nombre') || data.text;
            }
        });

        // Cargar los combos
        cargarSucursales('#select_sucursal_asignacion');
        $('#select_ruta_asignacion').html('<option value="">-- Primero seleccione sucursal --</option>');
        $('#select_cobrador_asignacion').html('<option value="">-- Primero seleccione ruta --</option>');

        // Obtener datos completos del préstamo y cargar el plan de pago
        console.log('[DEBUG] ==========================================');
        console.log('[DEBUG] Iniciando obtenerDatosCompletoPrestamo para nro_prestamo:', nro_prestamo);
        console.log('[DEBUG] URL de la petición AJAX:', 'ajax/aprobacion_ajax.php?accion=obtener_datos_prestamo&nro_prestamo=' + nro_prestamo);
        
        obtenerDatosCompletoPrestamo(nro_prestamo, function(datos) {
            console.log('[DEBUG] Datos del préstamo recibidos:', datos);
            console.log('[DEBUG] Tipo de datos:', typeof datos);
            console.log('[DEBUG] ¿datos es null/undefined?', datos === null || datos === undefined);
            
            if (!datos) {
                console.log('[DEBUG] ERROR: No se recibieron datos del préstamo');
                $("#tbl_amortizacion_aprobacion tbody").html(`
                    <tr><td colspan="3" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error: No se pudieron obtener los datos del préstamo
                    </td></tr>
                `);
                return;
            }
            
            console.log('[DEBUG] Datos específicos del préstamo:');
            console.log('- pres_monto:', datos.pres_monto);
            console.log('- pres_interes:', datos.pres_interes);
            console.log('- pres_cuotas:', datos.pres_cuotas);
            console.log('- tipo_calculo:', datos.tipo_calculo);
            console.log('- pres_f_emision:', datos.pres_f_emision);
            
            cargarPlanPagoModal(
                nro_prestamo, 
                datos.pres_monto || monto, 
                datos.pres_interes, 
                datos.pres_cuotas, 
                datos.fpago_id, 
                datos.pres_f_emision || datos.fecha,            
                datos.tipo_calculo // Usar el tipo de cálculo real de la BD
            );
            console.log('[DEBUG] ==========================================');
        });

        $("#modal_asignacion_ruta").modal('show');
    });

    /*===================================================================*/
    // FUNCIONES PARA CARGAR COMBOS - ACTUALIZADAS PARA USAR EL MISMO FORMATO DEL DASHBOARD
    /*===================================================================*/
    
    // Función para cargar sucursales
    function cargarSucursales(selectElement) {
        $.ajax({
            url: "ajax/aprobacion_ajax.php",
            type: "GET",
            data: { accion: 'listar_sucursales' },
            dataType: 'json',
            success: function(response) {
                const select = $(selectElement);
                select.empty().append('<option value="">-- Seleccione Sucursal --</option>');
                
                if (response && Array.isArray(response) && response.length > 0) {
                    response.forEach(function(sucursal) {
                        // Usar los nombres de campos correctos de la respuesta
                        const sucursalId = sucursal.sucursal_id || sucursal.id;
                        const sucursalNombre = sucursal.sucursal_nombre || sucursal.nombre;
                        const sucursalCodigo = sucursal.sucursal_codigo || sucursal.codigo || '';
                        const textoCompleto = sucursal.texto_descriptivo || sucursal.texto_completo || sucursalNombre;
                        
                        if (sucursalId && sucursalNombre) {
                            // Agregar atributos data para el template de Select2
                            const option = `<option value="${sucursalId}" data-codigo="${sucursalCodigo}" data-nombre="${sucursalNombre}">${textoCompleto}</option>`;
                            select.append(option);
                        }
                    });
                } else {
                    select.append('<option value="">No hay sucursales disponibles</option>');
                }
            },
            error: function(xhr, status, error) {
                $(selectElement).empty().append('<option value="">Error al cargar sucursales</option>');
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar sucursales'
                });
            }
        });
    }

    // Función para cargar rutas por sucursal
    function cargarRutasPorSucursal(sucursal_id, selectElement) {
        console.log('[Modal Asignación] Cargando rutas para sucursal:', sucursal_id);
        const selectRuta = $(selectElement);
        
        if (!sucursal_id) {
            selectRuta.empty().append('<option value="">-- Primero seleccione sucursal --</option>');
            $('#select_cobrador_asignacion').empty().append('<option value="">-- Primero seleccione ruta --</option>');
            return;
        }

        $.ajax({
            url: "ajax/aprobacion_ajax.php",
            type: "POST",
            data: { 
                accion: 'listar_rutas_sucursal',
                sucursal_id: sucursal_id 
            },
            dataType: 'json',
            success: function(response) {
                selectRuta.empty().append('<option value="">-- Seleccione Ruta --</option>');
                
                if (response && Array.isArray(response) && response.length > 0) {
                    response.forEach(function(ruta) {
                        // USAR SIEMPRE ruta_id y ruta_nombre
                        const rutaId = ruta.ruta_id;
                        const rutaNombre = ruta.ruta_nombre;
                        const rutaCodigo = ruta.ruta_codigo || '';
                        const textoCompleto = rutaCodigo ? (rutaCodigo + ' - ' + rutaNombre) : rutaNombre;
                        if (rutaId && rutaNombre) {
                            const option = `<option value="${rutaId}" data-codigo="${rutaCodigo}" data-nombre="${rutaNombre}">${textoCompleto}</option>`;
                            selectRuta.append(option);
                        }
                    });
                } else {
                    selectRuta.append('<option value="">No hay rutas en esta sucursal</option>');
                }
                // Limpiar combo de cobradores cuando cambia la ruta
                $('#select_cobrador_asignacion').empty().append('<option value="">-- Primero seleccione ruta --</option>');
            },
            error: function(xhr, status, error) {
                selectRuta.empty().append('<option value="">Error al cargar rutas</option>');
                Toast.fire({
                    icon: 'error',
                    title: 'Error al cargar rutas'
                });
            }
        });
    }

    // Función para cargar cobradores
    function cargarCobradores(selectElement) {
        $.ajax({
            url: "ajax/aprobacion_ajax.php",
            type: "GET",
            data: { accion: 'listar_cobradores' },
            dataType: 'json',
            success: function(response) {
                const select = $(selectElement);
                select.empty().append('<option value="">-- Seleccione Cobrador --</option>');
                
                // Manejar el nuevo formato de respuesta
                if (response.estado === 'ok' && Array.isArray(response.data) && response.data.length > 0) {
                    response.data.forEach(function(cobrador) {
                        const cobradorId = cobrador.id_usuario;
                        const cobradorNombre = cobrador.nombre_usuario;
                        const sucursalNombre = cobrador.sucursal_nombre || 'Sin sucursal';
                        const perfilNombre = cobrador.perfil_nombre || 'Sin perfil';
                        
                        if (cobradorId && cobradorNombre) {
                            // Agregar atributos data para el template de Select2
                            const option = `<option value="${cobradorId}" data-nombre="${cobradorNombre}" data-sucursal="${sucursalNombre}" data-perfil="${perfilNombre}">${cobradorNombre}</option>`;
                            select.append(option);
                        }
                    });
                } else if (response.estado === 'info') {
                    select.append('<option value="">No hay cobradores disponibles</option>');
                } else if (response.estado === 'error') {
                    select.append('<option value="">Error del servidor</option>');
                    Toast.fire({
                        icon: 'error',
                        title: response.mensaje || 'Error al cargar cobradores'
                    });
                } else {
                    select.append('<option value="">No hay cobradores disponibles</option>');
                }
            },
            error: function(xhr, status, error) {
                const errorMsg = xhr.responseJSON?.mensaje || 'Error de comunicación con el servidor';
                $(selectElement).empty().append('<option value="">Error al cargar cobradores</option>');
                Toast.fire({
                    icon: 'error',
                    title: errorMsg
                });
            }
        });
    }

    // Eventos para los combos en cascada
    $('#select_sucursal_asignacion').on('change', function() {
        const sucursal_id = $(this).val();
        cargarRutasPorSucursal(sucursal_id, '#select_ruta_asignacion');
        $('#select_cobrador_asignacion').html('<option value="">Primero seleccione una ruta</option>');
    });

    $('#select_ruta_asignacion').on('change', function() {
        cargarCobradores('#select_cobrador_asignacion');
    });

    // Función para cargar el plan de pago en el modal de asignación
    function cargarPlanPagoModal(nro_prestamo, monto, interes, cuotas, fpago_id, fecha_emision, tipo_calculo = 'FRANCES') {
        console.log('[DEBUG] cargarPlanPagoModal - tipo_calculo:', tipo_calculo);
        
        // Mostrar indicador de carga
        $("#tbl_amortizacion_aprobacion tbody").html('<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Calculando plan de pago...</td></tr>');
        
        // Calcular cuota simple sin AJAX (provisional hasta arreglar el server)
        const montoFloat = parseFloat(monto);
        const interesFloat = parseFloat(interes);
        const cuotasInt = parseInt(cuotas);
        
        // Cálculo según el tipo de cálculo real de la BD
        let interesTotal, montoTotal, cuotaMensual;
        
        if (tipo_calculo === 'FRANCES') {
            // Cálculo francés (cuota fija)
            const tasaMensual = interesFloat / 100 / 12;
            const factor = Math.pow(1 + tasaMensual, cuotasInt);
            cuotaMensual = (montoFloat * tasaMensual * factor) / (factor - 1);
            montoTotal = cuotaMensual * cuotasInt;
            interesTotal = montoTotal - montoFloat;
        } else {
            // Cálculo simple (interés directo)
            interesTotal = (montoFloat * interesFloat / 100);
            montoTotal = montoFloat + interesTotal;
            cuotaMensual = montoTotal / cuotasInt;
        }
        
        // Limpiar tabla
        $("#tbl_amortizacion_aprobacion tbody").empty();
        
        // Generar fechas y mostrar las primeras 5 cuotas
        let fechaInicio;
        try {
            fechaInicio = fecha_emision ? new Date(fecha_emision) : new Date();
            // Verificar si la fecha es válida
            if (isNaN(fechaInicio.getTime())) {
                fechaInicio = new Date();
            }
        } catch (e) {
            fechaInicio = new Date();
        }
        
        for (let i = 1; i <= Math.min(cuotasInt, 5); i++) {
            const fechaCuota = new Date(fechaInicio);
            fechaCuota.setMonth(fechaCuota.getMonth() + i);
            
            // Verificar que la fecha sigue siendo válida después de agregar meses
            let fechaFormateada;
            try {
                if (isNaN(fechaCuota.getTime())) {
                    fechaFormateada = new Date().toISOString().split('T')[0];
                } else {
                    fechaFormateada = fechaCuota.toISOString().split('T')[0];
                }
            } catch (e) {
                fechaFormateada = new Date().toISOString().split('T')[0];
            }
            
            const tr = `
                <tr>
                    <td class="text-center">${i}</td>
                    <td class="text-center">${fechaFormateada}</td>
                    <td class="text-right">C$ ${cuotaMensual.toLocaleString('es-NI', {minimumFractionDigits: 2})}</td>
                </tr>
            `;
            $("#tbl_amortizacion_aprobacion tbody").append(tr);
        }
        
        // Si hay más de 5 cuotas, mostrar indicador
        if (cuotasInt > 5) {
            const cuotasRestantes = cuotasInt - 5;
            $("#tbl_amortizacion_aprobacion tbody").append(`
                <tr class="table-info">
                    <td colspan="3" class="text-center">
                        <small><i class="fas fa-ellipsis-h"></i> ${cuotasRestantes} cuotas más...</small>
                    </td>
                </tr>
            `);
        }
        
        // Agregar fila de totales
        $("#tbl_amortizacion_aprobacion tbody").append(`
            <tr class="table-success font-weight-bold">
                <td colspan="2" class="text-right"><strong>TOTAL A PAGAR:</strong></td>
                <td class="text-right"><strong>C$ ${montoTotal.toLocaleString('es-NI', {minimumFractionDigits: 2})}</strong></td>
            </tr>
            <tr class="table-warning">
                <td colspan="2" class="text-right"><strong>TOTAL INTERESES:</strong></td>
                <td class="text-right"><strong>C$ ${interesTotal.toLocaleString('es-NI', {minimumFractionDigits: 2})}</strong></td>
            </tr>
        `);
    }

    // Al abrir el modal de asignación de ruta y cobrador
    $(document).on('show.bs.modal', '#modal_asignacion_ruta', function() {
        var esAdmin = window.userContext && window.userContext.usuario && window.userContext.usuario.es_admin;
        var sucursalUsuario = window.userContext && window.userContext.sucursal_id;

        if (esAdmin) {
            $('#select_sucursal_asignacion').closest('.col-lg-4').show();
            $('#select_sucursal_asignacion').prop('disabled', false);
            cargarSucursales('#select_sucursal_asignacion');
            $('#select_sucursal_asignacion').val('');
            $('#select_ruta_asignacion').html('<option value="">-- Primero seleccione sucursal --</option>');
            $('#select_cobrador_asignacion').html('<option value="">-- Primero seleccione ruta --</option>');
        } else {
            $('#select_sucursal_asignacion').closest('.col-lg-4').hide();
            $('#select_sucursal_asignacion').prop('disabled', true);
            if (sucursalUsuario) {
                $('#select_sucursal_asignacion').val(sucursalUsuario);
                cargarRutasPorSucursal(sucursalUsuario, '#select_ruta_asignacion');
                $('#select_ruta_asignacion').html('<option value="">-- Seleccione Ruta --</option>');
                $('#select_cobrador_asignacion').html('<option value="">-- Primero seleccione ruta --</option>');
            }
        }
    });

    // Evitar que el evento de cambio de sucursal se ejecute si el combo está oculto/deshabilitado
    $('#select_sucursal_asignacion').on('change', function() {
        if ($(this).is(':visible') && !$(this).prop('disabled')) {
            const sucursal_id = $(this).val();
            cargarRutasPorSucursal(sucursal_id, '#select_ruta_asignacion');
            $('#select_cobrador_asignacion').html('<option value="">Primero seleccione una ruta</option>');
        }
    });

    $('#select_ruta_asignacion').on('change', function() {
        cargarCobradores('#select_cobrador_asignacion');
    });

</script>

<style>
/* Estilos para el dropdown de cobradores en Select2 */
.select2-result-cobrador {
    padding: 2px 0;
}

.cobrador-nombre {
    font-weight: 500;
    color: #2c3e50;
}

.select2-result-cobrador .text-muted {
    font-size: 0.85em;
    margin-top: 2px;
}

.select2-result-cobrador .fas {
    width: 12px;
    margin-right: 4px;
}

/* Mejorar la presentación general de los Select2 en el modal */
#modal_asignacion_ruta .select2-container .select2-selection--single {
    height: 38px;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

#modal_asignacion_ruta .select2-container .select2-selection--single .select2-selection__rendered {
    line-height: 36px;
    padding-left: 12px;
    color: #495057;
}

#modal_asignacion_ruta .select2-container .select2-selection--single .select2-selection__arrow {
    height: 36px;
}

#modal_asignacion_ruta .select2-dropdown {
    border-radius: 0.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
</style>