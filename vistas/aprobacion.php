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
                                        <th># Prestamo</th>
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
                <div class="row">
                    <div class="table-responsive">
                        <table id="tbl_detalle_prestamo" class="table display table-hover text-nowrap compact  w-100  rounded" style="width:100%;">
                            <thead class="bg-gradient-info text-white">
                                <tr>
                                    <th>Id</th>
                                    <th style="width:40%;">Nro prestamo</th>
                                    <th>Cuota</th>
                                    <th style="width:10%;">Fecha</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <!-- <th class="text-cetner">Opciones</th> -->
                                </tr>
                            </thead>
                            <tbody class="small text left">
                            </tbody>
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
              //tbl_report_cliente.destroy();
              //accion = 2; //seteamos la accion para Eliminar

              if (tbl_aprobacion_pres.row(this).child.isShown()) {
                  var data = tbl_aprobacion_pres.row(this).data();
              } else {
                  var data = tbl_aprobacion_pres.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
                  // console.log(data);
              }

              $("#modal_detalle_prestamo").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_detalle_prestamo").modal('show'); //abrimos el modal*/

               $("#text_nro_prestamo_d").val(data[1]);
               $("#text_cliente_d").val(data[3]);
               $("#text_monto_d").val(data[4] + ".00");
              $("#text_interes_d").val(data[5] + " %");
              $("#text_cuota_d").val(data[6]);
              $("#text_fpago__d").val(data[8]);
              $("#text_fecha__d").val(data[11]);
               $("#text_monto_cuota__d").val(data[14]);
              $("#text_monto_interes__d").val(data[15]);
              $("#text_monto_total__d").val(data[16]);
              $("#text_cuotas_pagadas__d").val(data[17]);


               Traer_Detalle(data[1]);


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
        tbl_detalle_prestamo = $("#tbl_detalle_prestamo").DataTable({
            responsive: true,
            destroy: true,
            searching: false,
            dom: 'tp',
            ajax: {
                url: "ajax/admin_prestamos_ajax.php",
                dataSrc: "",
                type: "POST",
                data: {
                    'accion': 2,
                    'nro_prestamo': nro_prestamo
                }, //LISTAR 
            },
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
            dataSrc: function(json) {
                return json;
            },
            columnDefs: [{
                    targets: [0, 1],
                    visible: false

                }, {
                    targets: 4, // Monto
                    render: function(data, type, row) {
                        return row.moneda_simbolo + ' ' + data;
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
        $.ajax({
            url: 'ajax/aprobacion_ajax.php',
            type: 'GET',
            data: {
                accion: 'obtener_datos_prestamo',
                nro_prestamo: nro_prestamo
            },
            dataType: 'json',
            success: function(response) {
                if (response.estado === 'ok' && response.data) {
                    callback(response.data);
                } else {
                    $("#tbl_amortizacion_aprobacion tbody").html(`
                        <tr><td colspan="3" class="text-center text-warning">
                            <i class="fas fa-exclamation-triangle"></i> No se pudieron obtener los datos del préstamo
                        </td></tr>
                    `);
                }
            },
            error: function(xhr, status, error) {
                $("#tbl_amortizacion_aprobacion tbody").html(`
                    <tr><td colspan="3" class="text-center text-danger">
                        <i class="fas fa-exclamation-triangle"></i> Error de comunicación
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
        obtenerDatosCompletoPrestamo(nro_prestamo, function(datos) {
            cargarPlanPagoModal(
                nro_prestamo, 
                datos.pres_monto || monto, 
                datos.pres_interes, 
                datos.pres_cuotas, 
                datos.fpago_id, 
                datos.pres_f_emision || datos.fecha,
                datos.tipo_calculo || 'FRANCES'
            );
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
                        const rutaId = ruta.ruta_id || ruta.id;
                        const rutaNombre = ruta.ruta_nombre || ruta.nombre;
                        const rutaCodigo = ruta.ruta_codigo || ruta.codigo || '';
                        const textoCompleto = ruta.texto_descriptivo || ruta.texto_completo || rutaNombre;
                        
                        if (rutaId && rutaNombre) {
                            // Agregar atributos data para el template de Select2
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
        // Mostrar indicador de carga
        $("#tbl_amortizacion_aprobacion tbody").html('<tr><td colspan="3" class="text-center"><i class="fas fa-spinner fa-spin"></i> Calculando plan de pago...</td></tr>');
        
        // Calcular cuota simple sin AJAX (provisional hasta arreglar el server)
        const montoFloat = parseFloat(monto);
        const interesFloat = parseFloat(interes);
        const cuotasInt = parseInt(cuotas);
        
        // Cálculo simple de cuota fija
        const interesTotal = (montoFloat * interesFloat / 100);
        const montoTotal = montoFloat + interesTotal;
        const cuotaMensual = montoTotal / cuotasInt;
        
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