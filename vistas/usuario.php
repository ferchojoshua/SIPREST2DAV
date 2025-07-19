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
                              <table id="tbl_usuarios" class="table display table-hover text-nowrap compact w-100 rounded">
                                  <thead class="bg-gradient-info text-white">
                                      <tr>
                                          <th>Id</th>
                                          <th>Nombres</th>
                                          <th>Apellidos</th>
                                          <th>Usuario</th>
                                          <th>clave</th>
                                          <th>id Rol</th>
                                          <th>Rol</th>
                                          <th>Sucursal</th>
                                          <th>Estado</th>
                                          <th class="text-center">Opciones</th>
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
<div class="modal fade" id="modal_registro_usuario" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header bg-gray py-1 align-items-center">
                <h5 class="modal-title" id="titulo_modal_usuario">Registro de Usuarios</h5>
                <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_usuario" data-bs-dismiss="modal" aria-label="Close">
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
                                <input type="text" class=" form-control form-control-sm" id="text_nombres" name="text_nombres" placeholder="Nombres" required>
                                <div class="invalid-feedback">Debe ingresar los nonbres del usuario</div>

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label for="" class="">
                                    <span class="small"> Apellidos</span><span class="text-danger">*</span>
                                </label>
                                <input type="text" class=" form-control form-control-sm" id="text_apellidos" name="text_apellidos" placeholder="Apellidos" required>
                                <div class="invalid-feedback">Debe ingresar los apellido del usuario</div>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label for="" class="">
                                    <span class="small"> Usuario</span><span class="text-danger">*</span>
                                </label>
                                <input type="text" class=" form-control form-control-sm" id="text_usuario" name="text_usuario" placeholder="Usuario" required>
                                <div class="invalid-feedback">Debe ingresar el usuario</div>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-2" id="iptclave">
                                <label for="ipclave" class="">
                                    <span class="small"> Clave</span><span class="text-danger">*</span>
                                </label>
                                <input type="password" class=" form-control form-control-sm" id="text_clave" name="text_clave" placeholder="Clave" required>
                                <div class="invalid-feedback">Debe ingresar una clave</div>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label for="ipperfil" class="">
                                    <span class="small">Perfil</span><span class="text-danger">*</span>
                                </label>
                                <select name="" id="select_perfil" class="form-select form-select-sm" aria-label=".form-select-sm example" required></select>

                                <div class="invalid-feedback">Seleccione un perfil</div>

                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label for="select_sucursal" class="">
                                    <span class="small">Sucursal</span><span class="text-danger">*</span>
                                </label>
                                <select name="select_sucursal" id="select_sucursal" class="form-select form-select-sm" aria-label=".form-select-sm example" required>
                                    <!-- Las sucursales se cargarán aquí dinámicamente -->
                                </select>
                                <div class="invalid-feedback">Seleccione una sucursal</div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_usuario">Cerrar</button>
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
                <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_clave" data-bs-dismiss="modal" aria-label="Close">
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
                                <input type="password" class=" form-control form-control-sm" id="text_clave_nueva" placeholder="Clave nueva" required>
                                <div class="invalid-feedback">Debe ingresar la nueva clave</div>

                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-2">
                                <label for="" class="">
                                    <span class="small"> Repetir Clave</span><span class="text-danger">*</span>
                                </label>
                                <input type="password" class=" form-control form-control-sm" id="text_clave_repetir" placeholder="Repetir clave" required>
                                <div class="invalid-feedback">Debe ingresar la misma clave </div>

                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_clave">Cerrar</button>
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

    // Funciones para cargar perfiles y sucursales (encapsuladas para reutilización)
    function cargarPerfiles(callback) {
        $.ajax({
            url: "ajax/usuario_ajax.php",
            method: 'POST',
            data: { 'accion': 'listar_perfiles' },
            dataType: 'json',
            success: function(respuesta) {
                if (Array.isArray(respuesta)) {
                    var options = '<option selected value="">Seleccione un perfil</option>';
                    respuesta.forEach(function(perfil) {
                        options += '<option value="' + perfil.id_perfil + '">' + perfil.descripcion + '</option>';
                    });
                    $("#select_perfil").html(options);
                    
                    if (typeof callback === 'function') {
                        callback();
                    }
                } else {
                    console.error("Error: La respuesta no es un array", respuesta);
                    $("#select_perfil").html('<option value="">Error al cargar perfiles</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar perfiles:", error);
                $("#select_perfil").html('<option value="">Error al cargar perfiles</option>');
            }
        });
    }

    function cargarSucursales(callback) {
        $.ajax({
            url: "ajax/sucursales_ajax.php",
            type: "GET",
            data: { 'accion': 'listar' },
            dataType: 'json',
            success: function(respuesta) {
                var options = '<option value="">--seleccione--</option>';
                respuesta.forEach(function(sucursal) {
                    options += '<option value=' + sucursal.id + '>' + sucursal.nombre + '</option>';
                });
                $("#select_sucursal").html(options); // Usar .html para reemplazar
                if (typeof callback === 'function') {
                    callback();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar sucursales:", error);
                $("#select_sucursal").html('<option value="">Error al cargar sucursales</option>');
            }
        });
    }

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

        // Cargar perfiles y sucursales al inicio
        cargarPerfiles();
        cargarSucursales();

        /*===================================================================*/
        // REGISTRO DE USUARIOS
        /*===================================================================*/
        $("#btnregistrar_usuario").on('click', function() {
            var forms = document.querySelectorAll('.needs-validation')
            
            // Validar el formulario
            var form = forms[0];
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            // Si el formulario es válido, proceder con el registro/actualización
            Swal.fire({
                title: accion == 1 ? '¿Está seguro de registrar el usuario?' : '¿Está seguro de modificar el usuario?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: accion == 1 ? 'Si, registrar' : 'Si, modificar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var datos = new FormData();

                    datos.append("accion", accion == 1 ? 2 : 3); // acción 2 es para registrar usuario, 3 para actualizar
                    datos.append("id_usuario", $("#id_usuario").val());
                    datos.append("nombre_usuario", $("#text_nombres").val());
                    datos.append("apellido_usuario", $("#text_apellidos").val());
                    datos.append("usuario", $("#text_usuario").val());
                    if(accion == 1) {
                        datos.append("clave", $("#text_clave").val());
                    }
                    datos.append("id_perfil_usuario", $("#select_perfil").val());
                    datos.append("sucursal_id", $("#select_sucursal").val());

                    $.ajax({
                        url: "ajax/usuario_ajax.php",
                        method: "POST",
                        data: datos,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success: function(respuesta) {
                            if (respuesta == "ok") {
                                // Limpiar el formulario
                                $("#id_usuario").val("");
                                $("#text_nombres").val("");
                                $("#text_apellidos").val("");
                                $("#text_usuario").val("");
                                $("#text_clave").val("");
                                $("#select_perfil").val("");
                                $("#select_sucursal").val("");
                                
                                // Restablecer validación
                                form.classList.remove('was-validated');
                                
                                // Cerrar modal
                                $("#modal_registro_usuario").modal('hide');
                                
                                // Mostrar mensaje de éxito
                                Toast.fire({
                                    icon: 'success',
                                    title: accion == 1 ? 'El usuario se registró correctamente' : 'El usuario se modificó correctamente'
                                });

                                // Recargar la tabla
                                tbl_usuarios.ajax.reload();
                                
                                // Restablecer el modo a registro
                                accion = 1;
                                $("#btnregistrar_usuario").html("Registrar");
                                $("#titulo_modal_usuario").html("Registro de Usuario");
                                $("#iptclave").show();
                            } else {
                                Toast.fire({
                                    icon: 'error',
                                    title: accion == 1 ? 'Error al registrar el usuario: ' : 'Error al modificar el usuario: ' + respuesta
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en la petición:", error);
                            console.error("Respuesta del servidor:", xhr.responseText);
                            Toast.fire({
                                icon: 'error',
                                title: accion == 1 ? 'Error al registrar el usuario' : 'Error al modificar el usuario'
                            });
                        }
                    });
                }
            });
        });

    });

    /*===================================================================*/
    // ABRIR MODAL DE REGISTRO
    /*===================================================================*/
    $("#abrirmodal_usuario").on('click', function() {
        accion = 1;
        $("#iptclave").show();
        $("#titulo_modal_usuario").html("Registro de Usuario");
        $("#modal_registro_usuario").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modal_registro_usuario").modal('show');
    })

    /*===================================================================*/
    // CARGAR DATATABLE
    /*===================================================================*/
    tbl_usuarios = $("#tbl_usuarios").DataTable({
        dom: 'Bfrtip',
        buttons: [{
            extend: 'excel',
            title: function() {
                var printTitle = 'Listado de Usuarios';
                return printTitle
            },
            text: 'Exportar a Excel',
            className: 'btn-success',
            exportOptions: {
                columns: [1, 2, 3, 6, 7, 8]
            },
        }, {
            extend: 'print',
            text: 'Imprimir',
            title: nombreEmpresa,
            exportOptions: {
                columns: [1, 2, 3, 6, 7, 8]
            },
            customize: function(win) {
                $(win.document.body).css('font-size', '10pt')
                    .prepend(
                        '<img src="" style="position:absolute; top:0; left:0;" />'
                    );

                $(win.document.body).find('table')
                    .addClass('compact')
                    .css('font-size', 'inherit');

                $(win.document.body).find('h1').css('text-align', 'center');

            },
            messageTop: 'Direccion: ' + direccionEmpresa +
                '</br> Listado de Usuarios'
        }, ],
        ajax: {
            url: "ajax/usuario_ajax.php",
            dataSrc: "",
            type: "POST",
            data: {
                'accion': 1
            },
            error: function(xhr, error, thrown) {
                console.error('Error en DataTable AJAX:', error);
                console.error('Respuesta del servidor:', xhr.responseText);
                console.error('Estado HTTP:', xhr.status);

                // Mostrar mensaje de error al usuario
                Swal.fire({
                    icon: 'error',
                    title: 'Error al cargar usuarios',
                    text: 'No se pudieron cargar los datos. Revise la consola para más detalles.',
                });
            }
        },
        columns: [
            { data: 'id_usuario' },
            { data: 'nombre_usuario' },
            { data: 'apellido_usuario' },
            { data: 'usuario' },
            { data: 'clave' },
            { data: 'id_perfil_usuario' },
            { data: 'descripcion' },
            { data: 'sucursal_nombre' },
            { 
                data: 'estado_texto',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return row.estado == '1' ? 
                             '<span class="badge bg-danger">Inactivo</span>': 
                            '<span class="badge bg-success">Activo</span>' ;
                    }
                    return data;
                }
            },
            { data: 'opciones' }
        ],
        columnDefs: [{
                targets: 9,
                createdCell: function(td, cellData, rowData, row, col) {
                    if (rowData.estado == '1') {
                        $(td).html(
                            "<center>" +
                            "<span class='text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cambiar Clave'> " +
                            "<i class='fas fa-key fs-6 btn_cambiar_clave'></i> " +
                            "</span>" +
                            "<span class='text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Usuario'> " +
                            "<i class='fas fa-pencil-alt fs-6 btn_editar_usuario'></i> " +
                            "</span>" +
                            "<span class='text-danger px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Inactivar Usuario'> " +
                            "<i class='fas fa-trash-alt fs-6 btn_baja_usuario'> </i> " +
                            "</span>" +
                            "</center>"
                        );
                    } else {
                        $(td).html(
                            "<center>" +
                            "<span class='text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Activar Usuario'> " +
                            "<i class='fas fa-arrow-up fs-6 btn_alta_usuario'> </i> " +
                            "</span>" +
                            "<span class='text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Usuario'> " +
                            "<i class='fas fa-pencil-alt fs-6 btn_editar_usuario'></i> " +
                            "</span>" +
                            "</center>"
                        )
                    }
                }
            },
            {
                targets: [0, 4, 5], //OCULTA SOLO ID, CLAVE Y ID_PERFIL
                visible: false
            }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
    });

    /*===================================================================*/
    // ALTA USUARIO
    /*===================================================================*/
    $('#tbl_usuarios').on('click', '.btn_alta_usuario', function() {
        var data = tbl_usuarios.row($(this).parents('tr')).data();
        var id_usuario = data[0];

        Swal.fire({
            title: '¿Está seguro de dar de alta al usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                var datos = new FormData();

                datos.append("accion", 4);
                datos.append("id_usuario", id_usuario);

                $.ajax({
                    url: "ajax/usuario_ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta > 0) {

                            Toast.fire({
                                icon: 'success',
                                title: 'El usuario se dio de alta correctamente'
                            })
                            tbl_usuarios.ajax.reload();

                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'El usuario no se pudo dar de alta'
                            })
                        }

                    }
                });

            }
        })
    })

    /*===================================================================*/
    // BAJA USUARIO
    /*===================================================================*/
    $('#tbl_usuarios').on('click', '.btn_baja_usuario', function() {

        var data = tbl_usuarios.row($(this).parents('tr')).data();
        var id_usuario = data[0];

        Swal.fire({
            title: '¿Está seguro de dar de baja al usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, registrar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                var datos = new FormData();

                datos.append("accion", 5);
                datos.append("id_usuario", id_usuario);

                $.ajax({
                    url: "ajax/usuario_ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(respuesta) {
                        if (respuesta > 0) {

                            Toast.fire({
                                icon: 'success',
                                title: 'El usuario se dio de baja correctamente'
                            })
                            tbl_usuarios.ajax.reload();

                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'El usuario no se pudo dar de baja'
                            })
                        }

                    }
                });

            }
        })
    })

    /*===================================================================*/
    // EDITAR USUARIO
    /*===================================================================*/
    $('#tbl_usuarios').on('click', '.btn_editar_usuario', function() {
        var data = tbl_usuarios.row($(this).parents('tr')).data();
        accion = 2; // Modo edición
        $("#iptclave").hide();
        $("#titulo_modal_usuario").html("Editar Usuario");
        $("#btnregistrar_usuario").html("Modificar"); // Cambiar texto del botón
        
        // Limpiar validaciones previas
        var forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.classList.remove('was-validated');
        });
        
        // Establecer valores del usuario
        $("#id_usuario").val(data.id_usuario);
        $("#text_nombres").val(data.nombre_usuario);
        $("#text_apellidos").val(data.apellido_usuario);
        $("#text_usuario").val(data.usuario);
        
        // Cargar perfiles y seleccionar el del usuario
        cargarPerfiles(function() {
            $("#select_perfil").val(data.id_perfil_usuario);
        });
        
        // Cargar sucursales y seleccionar la del usuario
        cargarSucursales(function() {
            $("#select_sucursal").val(data.sucursal_id);
        });

        $("#modal_registro_usuario").modal('show');
    });

    /*===================================================================*/
    // LIMPIAR MODAL AL CERRAR
    /*===================================================================*/
    $("#btncerrarmodal_usuario, #btncerrar_usuario").on('click', function() {
        // Limpiar campos
        $("#id_usuario").val("");
        $("#text_nombres").val("");
        $("#text_apellidos").val("");
        $("#text_usuario").val("");
        $("#text_clave").val("");
        $("#select_perfil").val("");
        $("#select_sucursal").val("");
        
        // Restablecer validación
        var forms = document.querySelectorAll('.needs-validation');
        forms.forEach(function(form) {
            form.classList.remove('was-validated');
        });
        
        // Restablecer botón y título para nuevo registro
        $("#btnregistrar_usuario").html("Registrar");
        $("#titulo_modal_usuario").html("Registro de Usuario");
        $("#iptclave").show();
        accion = 1; // Modo registro
    });
    $("#btncerrarmodal_clave, #btncerrar_clave").on('click', function() {
        $("#text_clave_nueva").val("");
        $("#text_clave_repetir").val("");
    })

    /*===================================================================*/
    // CAMBIAR CLAVE
    /*===================================================================*/
    $("#btnregistrar_clave").on('click', function() {
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                if ($("#text_clave_nueva").val() == $("#text_clave_repetir").val()) {
                    if (form.checkValidity()) {

                        Swal.fire({
                            title: '¿Está seguro de cambiar la clave?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, registrar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {

                                var datos = new FormData();

                                datos.append("accion", 6);
                                datos.append("id_usuario_clave", $("#id_usuario_clave")
                                    .val());
                                datos.append("clave_nueva", $("#text_clave_nueva").val());


                                $.ajax({
                                    url: "ajax/usuario_ajax.php",
                                    method: "POST",
                                    data: datos,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    dataType: 'json',
                                    success: function(respuesta) {
                                        if (respuesta > 0) {
                                            $("#text_clave_nueva").val("");
                                            $("#text_clave_repetir").val("");

                                            Toast.fire({
                                                icon: 'success',
                                                title: 'La clave se cambio correctamente'
                                            })
                                            tbl_usuarios.ajax.reload();
                                            $("#modal_cambiar_clave").modal(
                                                'hide');

                                        } else {
                                            Toast.fire({
                                                icon: 'error',
                                                title: 'La clave no se pudo cambiar'
                                            })
                                        }

                                    }
                                });

                            }
                        })

                    } else {
                        form.classList.add('was-validated');
                    }
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: 'Las claves no son iguales'
                    })
                }
            })
    });


    $('#tbl_usuarios').on('click', '.btn_cambiar_clave', function() {
        var data = tbl_usuarios.row($(this).parents('tr')).data();

        $("#modal_cambiar_clave").modal('show');
        $("#id_usuario_clave").val(data[0]);

    })
</script>