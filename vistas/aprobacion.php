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
                                        <th>Nro Prestamo</th>
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


<!-- MODAL DETALLE PRESTAMO-->
<div class="modal fade" id="modal_detalle_prestamo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray py-1 align-items-center">
                <h5 class="modal-title" id="titulo_modal_cliente">Detalle de cuotas a Pagar</h5>
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
                                <span class="small"> Monto Pres.</span>
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
                            <thead class="bg-info text-left">
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
                APROBAR PRESTAMO
               =========================================================================================*/
        $("#tbl_aprobacion_pres tbody").on('click', '.btnAprobar', function() {

            accion = 2; //seteamos la accion para Eliminar

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
                title: 'Desea Aprobar el Prestamo "' + data[1] + '" ?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                confirmButtonText: 'Si, Aprobar',
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
                                    title: 'Prestamo Aprobado'
                                    // title: titulo_msj
                                });

                                tbl_aprobacion_pres.ajax.reload(); //recargamos el datatable
                                
                                // Imprimir contrato automáticamente después de aprobar
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
                                        // Abrir el contrato en una nueva ventana
                                        window.open("MPDF/contrato.php?codigo=" + nro_prestamo + "#zoom=100", "Contrato", "scrollbars=NO,resizable=YES");
                                    }
                                });

                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: 'Error al Aprobar Prestamo'
                                });
                            }

                        }
                    });

                }
            })

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
                console.log("Datos recibidos para tbl_detalle_prestamo:", json);
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
        let primerDia = new Date(f.getFullYear(), f.getMonth() + 1, 0).getDate();
        if (d < 10) {
            d = '0' + d;
        }
        if (mes < 10) {
            mes = '0' + mes;
        }

        document.getElementById('text_fecha_I').value = anio + "-" + mes + "-" + d;
        document.getElementById('text_fecha_F').value = anio + "-" + mes + "-" + d;
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
        select: {
            rows: "%d fila seleccionada"
        },
        "sProcessing": "Procesando...",
        "sLengthMenu": "Ver _MENU_ ",
        "sZeroRecords": "No se encontraron resultados",
        "sEmptyTable": "No hay informacion en esta tabla",
        "sInfo": "Mostrando (_START_ a _END_) total de _TOTAL_ registros",
        "sInfoEmpty": "Registros del (0 al 0) total de 0 registros",
        "sInfoFiltered": "(Filtrado de un total de _MAX_ registros)",
        "SInfoPostFix": "",
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
</script>