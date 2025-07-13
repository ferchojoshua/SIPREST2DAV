  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0">Usuario</h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">Usuario</li>
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
                          <h3 class="card-title">Listado de Usuarios</h3>
                          <button class="btn btn-info btn-sm float-right" id="abrirmodal_usuario"><i
                                  class="fas fa-plus"></i>
                              Nuevo</button>
                      </div>
                      <div class=" card-body">
                          <div class="table-responsive">
                              <table id="tbl_usuarios" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-gradient-info text-white">
                                      <tr>
                                          <th>Id</th>
                                          <th>Nombres</th>
                                          <th>Apellidos</th>
                                          <th>Usuario</th>
                                          <th>clave</th>
                                          <th>id Rol</th>
                                          <th>Rol</th>
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
              <div class="col-md-4" hidden>
                  <div class="card card-info card-outline shadow ">
                      <div class="card-header">
                          <h3 class="card-title">Registro de categorias</h3>

                      </div>
                      <div class=" card-body"></div>
                  </div>
              </div>
          </div>

      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <!-- MODAL REGISTRAR USUARIOS-->
  <div class="modal fade" id="modal_registro_usuario" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_usuario">Registro de Usuarios</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_usuario"
                      data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <input type="text" id="id_usuario" hidden>
                                      <span class="small"> Nombres</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_nombres"
                                      name="text_nombres" placeholder="Nombres" required>
                                  <div class="invalid-feedback">Debe ingresar los nonbres del usuario</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Apellidos</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_apellidos"
                                      name="text_apellidos" placeholder="Apellidos" required>
                                  <div class="invalid-feedback">Debe ingresar los apellido del usuario</div>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Usuario</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_usuario"
                                      name="text_usuario" placeholder="Usuario" required>
                                  <div class="invalid-feedback">Debe ingresar el usuario</div>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2" id="iptclave">
                                  <label for="ipclave" class="">
                                      <span class="small"> Clave</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="password" class=" form-control form-control-sm" id="text_clave"
                                      name="text_clave" placeholder="Clave" required>
                                  <div class="invalid-feedback">Debe ingresar una clave</div>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="ipperfil" class="">
                                      <span class="small">Perfil</span><span class="text-danger">*</span>
                                  </label>
                                  <select name="" id="select_perfil" class="form-select form-select-sm"
                                      aria-label=".form-select-sm example" required></select>

                                  <div class="invalid-feedback">Seleccione un perfil</div>

                              </div>
                          </div>




                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                      id="btncerrar_usuario">Cerrar</button>
                  <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_usuario">Registrar</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <!-- MODAL CAMBIAR CLAVE USUARIOS-->
  <div class="modal fade" id="modal_cambiar_clave" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="">Cambiar Clave</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_clave"
                      data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <input type="text" id="id_usuario_clave" hidden>
                                      <span class="small"> Clave Nueva</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="password" class=" form-control form-control-sm" id="text_clave_nueva"
                                      placeholder="Clave nueva" required>
                                  <div class="invalid-feedback">Debe ingresar la nueva clave</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Repetir Clave</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="password" class=" form-control form-control-sm" id="text_clave_repetir"
                                      placeholder="Repetir clave" required>
                                  <div class="invalid-feedback">Debe ingresar la misma clave </div>

                              </div>
                          </div>

                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                      id="btncerrar_clave">Cerrar</button>
                  <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_clave">Cambiar</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <script>
var accion;
var tbl_usuarios, titulo_modal;

var Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000
});

var nombreEmpresa = '';
var direccionEmpresa = '';

$(document).ready(function() {

    /*===================================================================*/
    //SOLICITUD AJAX PARA CARGAR DATOS DE LA EMPRESA
    /*===================================================================*/
    $.ajax({
        url: "ajax/configuracion_ajax.php",
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        data: { 'accion': 1 }, //OBTENER DATOS DE LA EMPRESA
        success: function(respuesta) {
            if (respuesta) {
                nombreEmpresa = respuesta.confi_razon;
                direccionEmpresa = respuesta.confi_direccion;
            }
        }
    });

    /*===================================================================*/
    //SOLICITUD AJAX PARA CARGAR SELECT DE PERFILES
    /*===================================================================*/
    $.ajax({
        url: "ajax/usuario_ajax.php",
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(respuesta) {

            var options = '<option selected value="">Seleccione un perfil</option>';

            for (let index = 0; index < respuesta.length; index++) {
                options = options + '<option value=' + respuesta[index][0] + '>' + respuesta[index][
                    1
                ] + '</option>';

            }

            $("#select_perfil").append(options);

        }
    });

    /*===================================================================*/
    // ACTIVAR REGISTRO AL CAMBIAR SELECTOR DE PERFIL O PRESIONAR ENTER
    /*===================================================================*/
    $('#select_perfil').on('change', function() {
        $('#btnregistrar_usuario').click();
    });

    $('#select_perfil').on('select2:close', function(e) {
        if (e.originalEvent && e.originalEvent.keyCode === 13) {
            $('#btnregistrar_usuario').click();
        }
    });

    /***************************************************************************
     * INICIAR DATATABLE USUARIOS
     ******************************************************************************/
    var tbl_usuarios = $("#tbl_usuarios").DataTable({

        dom: 'Bfrtip',
        buttons: [{
                "extend": 'excelHtml5',
                "title": 'Reporte usuarios',
                "exportOptions": {
                    'columns': [1, 2, 3, 6, 7]
                },
                "text": '<i class="fa fa-file-excel"></i>',
                "titleAttr": 'Exportar a Excel'
            },
            {
                "extend": 'print',
                "text": '<i class="fa fa-print"></i> ',
                "titleAttr": 'Imprimir',
                "exportOptions": {
                    'columns': [1, 2, 3, 6, 7]
                },
                "download": 'open',
                "customize": function(win) {
                    $(win.document.body).find('h1').css('text-align', 'center').html('<h3>Reporte de Usuarios</h3>');
                    $(win.document.body).prepend(
                        '<div style="text-align:center;"><h2>' + nombreEmpresa + '</h2><p>' + direccionEmpresa + '</p><p>Fecha: ' + new Date().toLocaleDateString() + '</p></div>'
                    );
                }
            },
            'pageLength',
        ],
        ajax: {
            url: "ajax/usuario_ajax.php",
            dataSrc: "",
            type: "POST",
            data: {
                'accion': 1
            }, //LISTAR USUARIOS
        },
        columnDefs: [{
                targets: 4,
                visible: false

            },
            {
                targets: 5,
                visible: false

            },
            {
                targets: 7,
                //sortable: false,
                createdCell: function(td, cellData, rowData, row, col) {

                    if (parseInt(rowData[7]) == 1) {
                        $(td).html("Activo")
                    } else {
                        $(td).html("Inactivo")
                    }

                }
            },
            {
                targets: 8, //columna 2
                // sortable: false, //no ordene
                render: function(data, type, full, meta) {
                    return "<center>" +
                        "<span class='btnEditarUsuario  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Usuario'> " +
                        "<i class='fas fa-pencil-alt fs-6'></i> " +
                        "</span> " +
                        "<span class='btnCambiarClave  text-warning px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cambiar Clave'> " +
                        "<i class='fas fa-key fs-6'></i> " +
                        "</span> " +
                        "<span class='btnEliminarUsuario text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Usuario'> " +
                        "<i class='fas fa-trash fs-6'> </i> " +
                        "</span>" +
                        "</center>"
                }
            }
        ],
        "order": [
            [0, 'desc']
        ],
        lengthMenu: [0, 5, 10, 15, 20, 50],
        "pageLength": 10,
        "language": idioma_espanol,
        select: true
    });


    /*===================================================================*/
    //EVENTO PARA ABRIR EL MODAL DE REGISTRAR AL DAR CLICK EN BOTON NUEVO
    /*===================================================================*/
    $("#abrirmodal_usuario").on('click', function() {
        AbrirModalRegistrUsuarios();
    })


    /*===================================================================*/
    //EVENTO QUE GUARDA Y ACTUALIZA LOS DATOS DEL MODULO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
    /*===================================================================*/
    document.getElementById("btnregistrar_usuario").addEventListener("click", function() {

        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {

            //si se ingresan todos los datos 
            if (form.checkValidity() === true) {

                Swal.fire({
                    title: 'Esta seguro de ' + titulo_modal + ' el Usuario?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {

                    if (result.isConfirmed) {

                        var datos = new FormData();

                        datos.append("accion", accion);
                        datos.append("id_usuario", $("#id_usuario").val()); //id
                        datos.append("nombre_usuario", $("#text_nombres")
                    .val()); //modulo
                        datos.append("apellido_usuario", $("#text_apellidos").val());
                        datos.append("usuario", $("#text_usuario").val());
                        datos.append("clave", $("#text_clave").val());
                        datos.append("id_perfil_usuario", $("#select_perfil").val());

                        if (accion == 2) {
                            var titulo_msj = "El Usuario  se registro correctamente"
                            //var titulo_modal = "Registrar";
                        }

                        if (accion == 3) {
                            var titulo_msj = "El Usuario se actualizo correctamente"
                            //var titulo_modal = "Actualizar";
                        }
                        $.ajax({
                            url: "ajax/usuario_ajax.php",
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
                                        //title: 'El Usuario se registro de forma correcta'
                                        title: titulo_msj
                                    });

                                    tbl_usuarios.ajax
                                .reload(); //recargamos el datatable 

                                    $("#modal_registro_usuario").modal(
                                        'hide');

                                    $("#id_usuario").val("");
                                    $("#text_nombres").val("");
                                    $("#text_apellidos").val("");
                                    $("#text_usuario").val("");
                                    $("#text_clave").val("");
                                    $("#select_perfil").val("");


                                    $(".needs-validation").removeClass(
                                        "was-validated");

                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'El Usuario no se pudo registrar'
                                    });
                                }

                            }
                        });



                    }
                })


            } else {
                //console.log(" ") //No paso la validacion
            }

            form.classList.add('was-validated');


        });
    });

    /* ======================================================================================
     EVENTO AL DAR CLICK EN EL BOTON EDITAR USUARIO
    =========================================================================================*/
    $("#tbl_usuarios tbody").on('click', '.btnEditarUsuario', function() {

        accion = 3; //seteamos la accion para editar
        titulo_modal = 'Actualizar';
        $("#modal_registro_usuario").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modal_registro_usuario").modal('show'); //modal de registrar producto
        $("#titulo_modal_usuario").html('Actualizar Usuario');
        $("#btnregistrar_usuario").html('Actualizar');

        //var data = table.row($(this).parents('tr')).data();

        if (tbl_usuarios.row(this).child.isShown()) {
            var data = tbl_usuarios.row(this).data();
        } else {
            var data = tbl_usuarios.row($(this).parents('tr'))
        .data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
            //  console.log("🚀 ~ file: categorias.php ~ line 751 ~ $ ~ data", data);
        }

        $("#id_usuario").val(data[0]);
        $("#text_nombres").val(data[1]);
        $("#text_apellidos").val(data[2]);
        $("#text_usuario").val(data[3]);
        $("#iptclave").attr('hidden', true); //ocultamos el tex clave
        $("#text_clave").val(data[4]);
        $("#select_perfil").val(data[5]);

    })


    /* ======================================================================================
     EVENTO CAMBIAR CLAVE DEL USUARIO
    =========================================================================================*/
    $("#tbl_usuarios tbody").on('click', '.btnCambiarClave', function() {

        accion = 5; //seteamos la accion para editar
        $("#modal_cambiar_clave").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modal_cambiar_clave").modal('show'); //modal de registrar producto

        //var data = table.row($(this).parents('tr')).data();

        if (tbl_usuarios.row(this).child.isShown()) {
            var data = tbl_usuarios.row(this).data();
        } else {
            var data = tbl_usuarios.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
            //  console.log("🚀 ~ file: categorias.php ~ line 751 ~ $ ~ data", data);
        }

        $("#id_usuario_clave").val(data[0]); //CAPTURAMOS Y ENVIAMOS AL EVENTO DEL BOTON


    })

    /*===================================================================*/
    //CAMBIAR CLAVE
    /*===================================================================*/
    document.getElementById("btnregistrar_clave").addEventListener("click", function() {

        // Get the forms we want to add validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var clave = $("#text_clave_nueva").val();
       
        var clave_repetir = $("#text_clave_repetir").val();
        // console.log(clave);

        var validation = Array.prototype.filter.call(forms, function(form) {

            if (clave != clave_repetir) {
                Toast.fire({
                    icon: 'error',
                    title: 'Las claves deben ser iguales'
                });

            }

            //console.log(clave,clave_repetir);

            //si se ingresan todos los datos 
            else if (form.checkValidity() === true) {

                Swal.fire({
                    title: 'Esta seguro de cambiar su clave?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {


                    if (result.isConfirmed) {



                        var datos = new FormData();

                        datos.append("accion", accion);
                        datos.append("id_usuario", $("#id_usuario_clave").val());
                        datos.append("clave", $("#text_clave_nueva").val());
                        datos.append("clave_repetir", $("#text_clave_repetir").val());



                        $.ajax({
                            url: "ajax/usuario_ajax.php",
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
                                        title: 'Se actualizo la clave '

                                    });

                                    tbl_usuarios.ajax
                                .reload(); //recargamos el datatable 

                                    $("#modal_cambiar_clave").modal('hide');

                                    $("#id_usuario_clave").val("");
                                    $("#text_clave_nueva").val("");
                                    $("#text_clave_repetir").val("");

                                    $(".needs-validation").removeClass(
                                        "was-validated");

                                } else {
                                    Toast.fire({
                                        icon: 'error',
                                        title: 'No se pudo cambiar la clave'
                                    });
                                }

                            }
                        });



                    }
                })


            } else {
                //console.log(" ") //No paso la validacion
            }

            form.classList.add('was-validated');


        });
    });


    /* ======================================================================================
     EVENTO AL DAR CLICK EN EL BOTON ELIMINAR USUARIO
    =========================================================================================*/
    $("#tbl_usuarios tbody").on('click', '.btnEliminarUsuario', function() {

        accion = 4; //seteamos la accion para editar

        if (tbl_usuarios.row(this).child.isShown()) {
            var data = tbl_usuarios.row(this).data();
        } else {
            var data = tbl_usuarios.row($(this).parents('tr'))
        .data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
            //   console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
        }

        var id_usuario = data[0];
        //  console.log(id_usuario);

        Swal.fire({
            title: 'Desea Eliminar el usuario "' + data[3] + '" ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, Eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {

            if (result.isConfirmed) {

                var datos = new FormData();

                datos.append("accion", accion);
                datos.append("id_usuario",
                id_usuario); //jalamos el codigo que declaramos mas arriba


                $.ajax({
                    url: "ajax/usuario_ajax.php",
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
                                title: 'El usuario se Elimino de forma correcta'
                                // title: titulo_msj
                            });

                            tbl_usuarios.ajax.reload(); //recargamos el datatable

                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'El usuario no se pudo Eliminar'
                            });
                        }

                    }
                });

            }
        })

    })




    /* ======================================================================================
    EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
    =========================================================================================*/
    $("#btncerrarmodal_usuario, #btncerrar_usuario").on('click', function() {
        $("#id_usuario").val("");
        $("#text_nombres").val("");
        $("#text_apellidos").val("");
        $("#text_usuario").val("");
        $("#text_clave").val("");
        $("#select_perfil").val("");
    })
    $("#btncerrarmodal_clave, #btncerrar_clave").on('click', function() {
        $("#id_usuario_clave").val("");
        $("#text_clave_nueva").val("");
        $("#text_clave_repetir").val("");
    })


    /*===================================================================*/
    //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
    /*===================================================================*/
    document.getElementById("btncerrar_usuario").addEventListener("click", function() {
        $(".needs-validation").removeClass("was-validated");
    })
    document.getElementById("btncerrarmodal_usuario").addEventListener("click", function() {
        $(".needs-validation").removeClass("was-validated");
    })
    document.getElementById("btncerrar_clave").addEventListener("click", function() {
        $(".needs-validation").removeClass("was-validated");
    })
    document.getElementById("btncerrarmodal_clave").addEventListener("click", function() {
        $(".needs-validation").removeClass("was-validated");
    })



}) //FIN DOCUMENT READY



function AbrirModalRegistrUsuarios() {
    //para que no se nos salga del modal haciendo click a los costados
    $("#modal_registro_usuario").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#modal_registro_usuario").modal('show'); //abrimos el modal
    $("#titulo_modal_usuario").html('Registrar Usuario');
    $("#btnregistrar_usuario").html('Registrar');
    accion = 2; // guardar
    titulo_modal = "Registrar";
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