  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0">Clientes</h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">Clientes</li>
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
                          <h3 class="card-title">Listado de Clientes</h3>
                          <button class="btn btn-info btn-sm float-right" id="abrirmodal_cliente"><i class="fas fa-plus"></i>
                              Nuevo</button>
                      </div>
                      <div class=" card-body">
                          <div class="table-responsive">
                              <table id="tbl_clientes" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-gradient-info text-white">
                                      <tr>
                                          <th>Id</th>
                                          <th>Nombre</th>
                                          <th>Cédula</th>
                                          <th>Celular</th>
                                          <th>Prestamo</th>
                                          <th>Estado</th>
                                          <th>Direccion</th>
                                          <th>Correo</th>                                        
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
          </div>

      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <!-- MODAL REGISTRAR CLIENTE-->
  <div class="modal fade" id="modal_registro_cliente" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cliente">Registro de Clientes</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_cliente" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <!-- FORMULARIO CLIENTE -->
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <input type="text" id="id_cliente" hidden>
                                      <span class="small"> Nombres</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_nombres" name="text_nombres" placeholder="Nombres" required>
                                  <div class="invalid-feedback">Debe ingresar un nonbres del cliente</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Documento</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_documento" name="text_documento" placeholder="Documento" required>
                                  <div class="invalid-feedback">Debe ingresar el documento del cliente</div>

                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Celular</span><span class="text-danger">*</span>
                                  </label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text bg-success text-white font-weight-bold">+505</span>
                                      </div>
                                      <input type="tel" class="form-control form-control-sm telefono-format" id="text_cel" name="text_cel" placeholder="87654321" maxlength="8" pattern="[0-9]{8}" required>
                                  </div>
                                  <div class="invalid-feedback">Debe ingresar un celular válido (8 dígitos)</div>
                                  <small class="text-muted">Formato: 8 dígitos sin espacios ni guiones</small>
                              </div>
                          </div>

                          <div class="col-lg-12">
                              <div class="form-group mb-2" id="iptclave">
                                  <label for="ipclave" class="">
                                      <span class="small"> Direccion</span><span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_direccion" name="text_direccion" placeholder="Direccion" required>
                                  <div class="invalid-feedback">Debe ingresar una direccion</div>

                              </div>
                          </div>
                          <div class="col-lg-12">
                              <div class="form-group mb-2" id="iptclave">
                                  <label for="ipclave" class="">
                                      <span class="small"> Correo</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_correo" name="text_correo" placeholder="Correo">


                              </div>
                          </div>

                      </div>
                    
                      <!-- FORMULARIO DATOS LABORALES -->
                      <div class="row">
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Empresa Laboral</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_empresa_laboral" name="text_empresa_laboral" placeholder="Empresa Laboral">
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cargo</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cargo_laboral" name="text_cargo_laboral" placeholder="Cargo">
                              </div>
                          </div>
                           <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Telefono Laboral</span>
                                  </label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text bg-info text-white font-weight-bold">+505</span>
                                      </div>
                                      <input type="tel" class="form-control form-control-sm telefono-format" id="text_tel_laboral" name="text_tel_laboral" placeholder="22345678" maxlength="8" pattern="[0-9]{8}">
                                  </div>
                                  <small class="text-muted">8 dígitos (opcional)</small>
                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Direccion Laboral</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_dir_laboral" name="text_dir_laboral" placeholder="Direccion Laboral">
                              </div>
                          </div>
                      </div>
                      <br>
                      <h5 style="text-align:center;">Informacion de las Referencias</h5>
                      <br>
                      <!-- FORMULARIO REFERENCIAS -->
                      <div class="row">

                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">

                                      <span class="small"> Referencia Personal</span><span class="text-danger"> *</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_refe_per_e" placeholder="Referencia Personal">
                                  <!-- <div class="invalid-feedback">Debe ingresar un nonbre de la referencia personal</div> -->

                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Nro. Celular</span><span class="text-danger"> *</span>
                                  </label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text bg-success text-white font-weight-bold">+505</span>
                                      </div>
                                      <input type="tel" class="form-control form-control-sm telefono-format" id="text_nro_cel_per_e" placeholder="87654321" maxlength="8" pattern="[0-9]{8}">
                                  </div>
                                  <small class="text-muted">8 dígitos</small>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Dirección Referencia Personal</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_refe_per_dir" placeholder="Dirección Referencia Personal">
                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Referencia Familiar</span><span class="text-danger"> *</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_refe_fami_e" placeholder="Referencia Familiar">
                                  <div class="invalid-feedback">Debe ingresar un nonbre de la referencia familiar </div>

                              </div>
                          </div>

                          <div class="col-md-6">
                              <div class="form-group mb-2" id="iptclave">
                                  <label for="ipclave" class="">
                                      <span class="small"> Nro. Celular Familiar</span><span class="text-danger"> *</span>
                                  </label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text bg-secondary text-white font-weight-bold">+505</span>
                                      </div>
                                      <input type="tel" class="form-control form-control-sm telefono-format" id="text_nro_cel_fami_e" placeholder="87654321" maxlength="8" pattern="[0-9]{8}">
                                  </div>
                                  <small class="text-muted">8 dígitos</small>
                              </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Dirección Referencia Familiar</span>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_refe_fami_dir" placeholder="Dirección Referencia Familiar">
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_cliente">Cerrar</button>
                  <!-- <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</button> -->
                  <div class="form-group m-0"><a class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</a></div>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <!-- MODAL REGISTRAR REFERENCIAS-->
  <div class="modal fade" id="modal_registro_referencia" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_referencia">Registrar Referencias</h5>
                  <button type="button" class="close text-white border-0 fs-5" id="btncerrarmodal_referencia" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <input type="hidden" id="id_refe">
                      
                      <h5 class="text-center text-info mb-3">
                          <i class="fas fa-users"></i> Información de Referencias
                      </h5>
                      
                      <div class="row">
                          <!-- REFERENCIA PERSONAL -->
                          <div class="col-12">
                              <h6 class="bg-light p-2 rounded">
                                  <i class="fas fa-user text-primary"></i> Referencia Personal
                              </h6>
                          </div>
                          
                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label class="small font-weight-bold">
                                      Nombre Completo <span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class="form-control form-control-sm" id="text_refe_personal" placeholder="Nombre de referencia personal" required>
                                  <div class="invalid-feedback">Debe ingresar el nombre de la referencia personal</div>
                              </div>
                          </div>
                          
                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label class="small font-weight-bold">
                                      Teléfono <span class="text-danger">*</span>
                                  </label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text bg-primary text-white font-weight-bold">+505</span>
                                      </div>
                                      <input type="tel" class="form-control form-control-sm telefono-format" id="text_cel_personal" placeholder="87654321" maxlength="8" pattern="[0-9]{8}" required>
                                  </div>
                                  <small class="text-muted">8 dígitos</small>
                                  <div class="invalid-feedback">Debe ingresar un teléfono válido</div>
                              </div>
                          </div>
                          
                          <div class="col-md-12">
                              <div class="form-group mb-3">
                                  <label class="small font-weight-bold">
                                      Dirección
                                  </label>
                                  <input type="text" class="form-control form-control-sm" id="text_dir_personal" placeholder="Dirección de referencia personal">
                              </div>
                          </div>
                          
                          <!-- REFERENCIA FAMILIAR -->
                          <div class="col-12 mt-3">
                              <h6 class="bg-light p-2 rounded">
                                  <i class="fas fa-home text-success"></i> Referencia Familiar
                              </h6>
                          </div>
                          
                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label class="small font-weight-bold">
                                      Nombre Completo <span class="text-danger">*</span>
                                  </label>
                                  <input type="text" class="form-control form-control-sm" id="text_refe_familiar" placeholder="Nombre de referencia familiar" required>
                                  <div class="invalid-feedback">Debe ingresar el nombre de la referencia familiar</div>
                              </div>
                          </div>
                          
                          <div class="col-md-6">
                              <div class="form-group mb-2">
                                  <label class="small font-weight-bold">
                                      Teléfono <span class="text-danger">*</span>
                                  </label>
                                  <div class="input-group">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text bg-success text-white font-weight-bold">+505</span>
                                      </div>
                                      <input type="tel" class="form-control form-control-sm telefono-format" id="text_cel_familiar" placeholder="87654321" maxlength="8" pattern="[0-9]{8}" required>
                                  </div>
                                  <small class="text-muted">8 dígitos</small>
                                  <div class="invalid-feedback">Debe ingresar un teléfono válido</div>
                              </div>
                          </div>
                          
                          <div class="col-md-12">
                              <div class="form-group mb-2">
                                  <label class="small font-weight-bold">
                                      Dirección
                                  </label>
                                  <input type="text" class="form-control form-control-sm" id="text_dir_familiar" placeholder="Dirección de referencia familiar">
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_referencia">Cerrar</button>
                  <button type="button" class="btn btn-success btn-sm" id="btnregistrar_referencia">
                      <i class="fas fa-save"></i> Registrar Referencias
                  </button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal Referencias -->

  <!-- Estilos CSS para mejorar la apariencia de los campos de teléfono -->
  <style>
  .input-group-text {
      border-right: none;
  }
  .telefono-format {
      border-left: none;
  }
  .telefono-format:focus {
      border-left: none;
      box-shadow: none;
  }
  .input-group:focus-within .input-group-text {
      border-color: #80bdff;
  }
  </style>

<?php require_once "modulos/footer.php"; ?>

  <script>
      var accion;
      var tbl_clientes, titulo_modal;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });

      $(document).ready(function() {

          /***************************************************************************
           * FORMATEAR CAMPOS DE TELÉFONO - SOLO NÚMEROS, 8 DÍGITOS
           ******************************************************************************/
          $('.telefono-format').on('input', function() {
              // Remover cualquier caracter que no sea número
              var valor = this.value.replace(/[^0-9]/g, '');
              
              // Limitar a 8 dígitos
              if (valor.length > 8) {
                  valor = valor.substring(0, 8);
              }
              
              this.value = valor;
              
              // Validación visual
              if (valor.length === 8) {
                  $(this).removeClass('is-invalid').addClass('is-valid');
              } else if (valor.length > 0) {
                  $(this).removeClass('is-valid').addClass('is-invalid');
              } else {
                  $(this).removeClass('is-valid is-invalid');
              }
          });

          // Prevenir pegar contenido no numérico
          $('.telefono-format').on('paste', function(e) {
              e.preventDefault();
              var paste = (e.clipboardData || window.clipboardData).getData('text');
              var numericPaste = paste.replace(/[^0-9]/g, '').substring(0, 8);
              this.value = numericPaste;
              $(this).trigger('input');
          });

          // Prevenir teclas no numéricas
          $('.telefono-format').on('keypress', function(e) {
              // Permitir teclas de control (backspace, delete, tab, escape, enter, etc.)
              if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                  // Permitir Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                  (e.keyCode === 65 && e.ctrlKey === true) ||
                  (e.keyCode === 67 && e.ctrlKey === true) ||
                  (e.keyCode === 86 && e.ctrlKey === true) ||
                  (e.keyCode === 88 && e.ctrlKey === true)) {
                  return;
              }
              // Asegurar que solo son números (0-9)
              if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                  e.preventDefault();
              }
          });

          /***************************************************************************
           * FUNCIÓN PARA OBTENER TELÉFONO COMPLETO CON CÓDIGO DE PAÍS
           ******************************************************************************/
          function obtenerTelefonoCompleto(campoId) {
              var numero = $(campoId).val();
              return numero ? '+505' + numero : '';
          }

          /***************************************************************************
           * FUNCIÓN PARA ESTABLECER TELÉFONO EN CAMPO (REMOVER +505 SI EXISTE)
           ******************************************************************************/
          function establecerTelefono(campoId, telefonoCompleto) {
              if (telefonoCompleto) {
                  // Remover +505 si existe para mostrar solo los 8 dígitos
                  var numero = telefonoCompleto.replace(/^\+505/, '');
                  $(campoId).val(numero);
              }
          }

          /***************************************************************************
           * INICIAR DATATABLE CLIENTES
           ******************************************************************************/
          var tbl_clientes = $("#tbl_clientes").DataTable({
            responsive: true,
              dom: 'Bfrtip',
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Reporte Clientes',
                      "exportOptions": {
                          'columns': [1, 2, 3,4,5, 6, 7]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3,4,5, 6, 7]
                      },
                      "download": 'open'
                  },
                  'pageLength',
              ],
              ajax: {
                  url: "ajax/clientes_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1
                  }, //LISTAR 
                  error: function(xhr, error, thrown) {
                      console.error('Error en DataTable AJAX:', error);
                      console.error('Respuesta del servidor:', xhr.responseText);
                      console.error('Estado HTTP:', xhr.status);
                      
                      // Mostrar mensaje de error al usuario
                      Swal.fire({
                          icon: 'error',
                          title: 'Error al cargar clientes',
                          text: 'No se pudieron cargar los datos. Revise la consola para más detalles.',
                      });
                  }
              },
              columns: [
                  { data: 'cliente_id' },
                  { data: 'cliente_nombres' },
                  { data: 'cliente_dni' },
                  { data: 'cliente_cel' },
                  { data: 'cliente_estado_prestamo' },
                  { data: 'cliente_estatus' },
                  { data: 'cliente_direccion' },
                  { data: 'cliente_correo' },
                  { data: 'opciones' } // Columna de opciones
              ],
              columnDefs: [{
                      targets: 0,
                      visible: false

                  },{
                      targets: 4,
                      visible: false

                  },{
                      targets: 6,
                      visible: false

                  },
                  {
                      targets: 7,
                      visible: true

                  },
                  {
                      targets: 8,
                      sortable: false,
                      render: function(data, type, full, meta) {
                          return "<center>" +
                              "<span class='btnVerCliente text-info px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Cliente'> " +
                              "<i class='fas fa-eye fs-6'></i> " +
                              "</span> " +
                              "<span class='btnEditarCliente  text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Editar Cliente'> " +
                              "<i class='fas fa-pencil-alt fs-6'></i> " +
                              "</span> " +
                              "<span class='btnReferencia text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Gestionar Referencias'> " +
                              "<i class='fas fa-users fs-6'></i> " +
                              "</span> " +
                              "<span class='btnEliminarCliente text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Cliente'> " +
                              "<i class='fas fa-trash fs-6'> </i> " +
                              "</span>" +

                              "</center>"
                      }
                  }
              ],

              lengthMenu: [5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              select: true
          });


          /*===================================================================*/
          //EVENTO PARA ABRIR EL MODAL DE REGISTRAR AL DAR CLICK EN BOTON NUEVO
          /*===================================================================*/
          $("#abrirmodal_cliente").on('click', function() {
              AbrirModalRegistroCliente();
              // Asegurarse de que los campos estén habilitados y el botón visible al abrir para registro
              $("#modal_registro_cliente .form-control, #modal_registro_cliente .form-select").prop('disabled', false);
              $("#btnregistrar_cliente").show();
              $("#titulo_modal_cliente").html('Registro de Usuarios');
              $("#btnregistrar_cliente").html('Registrar');
          })

          /*===================================================================*/
          //DUPLICADO DE DOCUMENTOS
          /*===================================================================*/
          $("#text_documento").change(function() {

              var document = $("#text_documento").val();

              // console.log(codBarra);
              $.ajax({
                  async: false,
                  url: "ajax/clientes_ajax.php",
                  method: "POST",
                  data: {
                      'accion': 5,
                      'cliente_dni': document
                      //  'cantidad_a_comprar': cantidad
                  },

                  dataType: 'json',
                  success: function(respuesta) {
                      //console.log(respuesta);
                      if (parseInt(respuesta['ex']) > 0) {
                          Toast.fire({
                              icon: 'error',
                              title: ' El Documento ' + document + '  ya se encuentra registrado'
                          })

                          $("#text_documento").val("");
                          $("#text_documento").focus();

                      } else {
                          //  console.log('dfgdfgdfg');
                      }
                  }
              });

          })



          /*===================================================================*/
          //EVENTO QUE GUARDA Y ACTUALIZA LOS DATOS DEL MODULO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
          /*===================================================================*/
          document.getElementById("btnregistrar_cliente").addEventListener("click", function() {

              // Get the forms we want to add validation styles to
              var forms = document.getElementsByClassName('needs-validation');
              // Loop over them and prevent submission
              var validation = Array.prototype.filter.call(forms, function(form) {

                  //si se ingresan todos los datos 
                  if (form.checkValidity() === true) {

                      Swal.fire({
                          title: 'Esta seguro de ' + titulo_modal + ' el Cliente?',
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
                              datos.append("cliente_id", $("#id_cliente").val()); //id
                              datos.append("cliente_nombres", $("#text_nombres").val()); //modulo
                              datos.append("cliente_dni", $("#text_documento").val());
                              // Enviar teléfonos con código de país completo
                              datos.append("cliente_cel", obtenerTelefonoCompleto("#text_cel"));
                              datos.append("cliente_direccion", $("#text_direccion").val());
                              datos.append("cliente_correo", $("#text_correo").val());

                              // Nuevos campos de Información Laboral
                              datos.append("cliente_empresa_laboral", $("#text_empresa_laboral").val());
                              datos.append("cliente_cargo_laboral", $("#text_cargo_laboral").val());
                              datos.append("cliente_tel_laboral", obtenerTelefonoCompleto("#text_tel_laboral"));
                              datos.append("cliente_dir_laboral", $("#text_dir_laboral").val());

                              // Nuevos campos de Referencia Personal
                              datos.append("cliente_refe_per_nombre", $("#text_refe_per_e").val());
                              datos.append("cliente_refe_per_cel", obtenerTelefonoCompleto("#text_nro_cel_per_e"));
                              datos.append("cliente_refe_per_dir", $("#text_refe_per_dir").val());

                              // Nuevos campos de Referencia Familiar
                              datos.append("cliente_refe_fami_nombre", $("#text_refe_fami_e").val());
                              datos.append("cliente_refe_fami_cel", obtenerTelefonoCompleto("#text_nro_cel_fami_e"));
                              datos.append("cliente_refe_fami_dir", $("#text_refe_fami_dir").val());


                              if (accion == 2) {
                                  var titulo_msj = "El Cliente  se registro correctamente"

                              }

                              if (accion == 3) {
                                  var titulo_msj = "El Cliente se actualizo correctamente"

                              }
                              $.ajax({
                                  url: "ajax/clientes_ajax.php",
                                  method: "POST",
                                  data: datos, //enviamos lo de la variable datos
                                  cache: false,
                                  contentType: false,
                                  processData: false,
                                  dataType: 'json',
                                  success: function(respuesta) {
                                      console.log("Respuesta del servidor:", respuesta);

                                      if (respuesta == "ok") {

                                          Toast.fire({
                                              icon: 'success',
                                              //title: 'El Cliente se registro de forma correcta'
                                              title: titulo_msj
                                          });

                                          tbl_clientes.ajax.reload(); //recargamos el datatable 

                                          $("#modal_registro_cliente").modal('hide');

                                          // Limpiar todos los campos del formulario
                                          $("#id_cliente").val("");
                                          $("#text_nombres").val("");
                                          $("#text_documento").val("");
                                          $("#text_cel").val("");
                                          $("#text_direccion").val("");
                                          $("#text_correo").val("");
                                          $("#text_empresa_laboral").val("");
                                          $("#text_cargo_laboral").val("");
                                          $("#text_tel_laboral").val("");
                                          $("#text_dir_laboral").val("");
                                          $("#text_refe_per_e").val("");
                                          $("#text_nro_cel_per_e").val("");
                                          $("#text_refe_per_dir").val("");
                                          $("#text_refe_fami_e").val("");
                                          $("#text_nro_cel_fami_e").val("");
                                          $("#text_refe_fami_dir").val("");

                                          $(".needs-validation").removeClass("was-validated");

                                      } else {
                                          // Mostrar más información del error
                                          var mensajeError = 'El Cliente no se pudo ' + (accion == 2 ? 'registrar' : 'actualizar');
                                          
                                          if (respuesta && respuesta.error) {
                                              mensajeError += ': ' + respuesta.error;
                                          } else if (Array.isArray(respuesta)) {
                                              mensajeError += ': ' + respuesta.join(', ');
                                          } else if (typeof respuesta === 'object') {
                                              mensajeError += ': ' + JSON.stringify(respuesta);
                                          }

                                          Toast.fire({
                                              icon: 'error',
                                              title: mensajeError
                                          });
                                      }

                                  },
                                  error: function(xhr, status, error) {
                                      console.log("Error AJAX:", error);
                                      console.log("Status:", status);
                                      console.log("Response:", xhr.responseText);
                                      
                                      Toast.fire({
                                          icon: 'error',
                                          title: 'Error de comunicación con el servidor: ' + error
                                      });
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
           EVENTO AL DAR CLICK EN EL BOTON EDITAR CLIENTE
          =========================================================================================*/
          $("#tbl_clientes tbody").on('click', '.btnEditarCliente', function() {

              accion = 3; //seteamos la accion para editar
              titulo_modal = 'Actualizar';
              $("#modal_registro_cliente").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_registro_cliente").modal('show'); //modal de registrar producto
              $("#titulo_modal_cliente").html('Actualizar Cliente');
              $("#btnregistrar_cliente").html('Actualizar');

              // Habilitar todos los campos del formulario para edición
              $("#modal_registro_cliente .form-control, #modal_registro_cliente .form-select").prop('disabled', false);
              $("#btnregistrar_cliente").show();

              if (tbl_clientes.row(this).child.isShown()) {
                  var data = tbl_clientes.row(this).data();
              } else {
                  var data = tbl_clientes.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }

              $("#id_cliente").val(data.cliente_id);
              $("#text_nombres").val(data.cliente_nombres);
              $("#text_documento").val(data.cliente_dni);
              
              // Formatear teléfonos para edición (remover +505 para mostrar solo 8 dígitos)
              establecerTelefono("#text_cel", data.cliente_cel);
              
              $("#text_direccion").val(data.cliente_direccion);
              $("#text_correo").val(data.cliente_correo);

              // Limpiar campos laborales (no disponibles en el SP actual)
              $("#text_empresa_laboral").val('');
              $("#text_cargo_laboral").val('');
              $("#text_tel_laboral").val('');
              $("#text_dir_laboral").val('');

              // Usar los campos de referencia disponibles
              $("#text_refe_per_e").val(data.cliente_refe || ''); 
              establecerTelefono("#text_nro_cel_per_e", data.cliente_cel_refe);
              $("#text_refe_per_dir").val(''); // No disponible

              // Limpiar campos familiares (no disponibles en el SP actual)
              $("#text_refe_fami_e").val('');
              $("#text_nro_cel_fami_e").val('');
              $("#text_refe_fami_dir").val('');

          })


          /* ======================================================================================
           EVENTO AL DAR CLICK EN EL BOTON VER CLIENTE
          =========================================================================================*/
          $("#tbl_clientes tbody").on('click', '.btnVerCliente', function() {

              accion = 0; // No hay acción de registro/actualización
              titulo_modal = 'Ver Cliente';
              $("#modal_registro_cliente").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_registro_cliente").modal('show'); //modal de registrar producto
              $("#titulo_modal_cliente").html('Ver Cliente');
              $("#btnregistrar_cliente").hide(); // Ocultar botón de registrar/actualizar

              // Deshabilitar todos los campos del formulario para solo visualización
              $("#modal_registro_cliente .form-control, #modal_registro_cliente .form-select").prop('disabled', true);

              if (tbl_clientes.row(this).child.isShown()) {
                  var data = tbl_clientes.row(this).data();
              } else {
                  var data = tbl_clientes.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }

              $("#id_cliente").val(data.cliente_id);
              $("#text_nombres").val(data.cliente_nombres);
              $("#text_documento").val(data.cliente_dni);
              
              // Formatear teléfonos para visualización (remover +505 para mostrar solo 8 dígitos)
              establecerTelefono("#text_cel", data.cliente_cel);
              
              $("#text_direccion").val(data.cliente_direccion);
              $("#text_correo").val(data.cliente_correo);

              // Limpiar campos laborales (no disponibles en el SP actual)
              $("#text_empresa_laboral").val('');
              $("#text_cargo_laboral").val('');
              $("#text_tel_laboral").val('');
              $("#text_dir_laboral").val('');

              // Usar los campos de referencia disponibles
              $("#text_refe_per_e").val(data.cliente_refe || ''); 
              establecerTelefono("#text_nro_cel_per_e", data.cliente_cel_refe);
              $("#text_refe_per_dir").val(''); // No disponible

              // Limpiar campos familiares (no disponibles en el SP actual)
              $("#text_refe_fami_e").val('');
              $("#text_nro_cel_fami_e").val('');
              $("#text_refe_fami_dir").val('');

          })


          /* ======================================================================================
           EVENTO AL DAR CLICK EN EL BOTON ELIMINAR CLIENTE
          =========================================================================================*/
          $("#tbl_clientes tbody").on('click', '.btnEliminarCliente', function() {

              accion = 4; //seteamos la accion para Eliminar

              if (tbl_clientes.row(this).child.isShown()) {
                  var data = tbl_clientes.row(this).data();
              } else {
                  var data = tbl_clientes.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }

              var cliente_id = data.cliente_id;

              Swal.fire({
                  title: 'Desea Eliminar el Cliente "' + data.cliente_nombres + '" ?',
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
                      datos.append("cliente_id", cliente_id);


                      $.ajax({
                          url: "ajax/clientes_ajax.php",
                          method: "POST",
                          data: datos, 
                          cache: false,
                          contentType: false,
                          processData: false,
                          dataType: 'json',
                          success: function(respuesta) {

                              if (respuesta == "ok") {

                                  Toast.fire({
                                      icon: 'success',
                                      title: 'El Cliente se Elimino de forma correcta'
                                  });

                                  tbl_clientes.ajax.reload(); 

                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'El Cliente no se pudo Eliminar'
                                  });
                              }

                          }
                      });

                  }
              })

          })


          /* ======================================================================================
           AGREGAR UNA REFERENCIA AL CLIENTE
          =========================================================================================*/
          $("#tbl_clientes tbody").on('click', '.btnReferencia', function() {

              accion = 8; //seteamos la accion para editar
              titulo_modal = 'Registrar';
              $("#modal_registro_referencia").modal({
                  backdrop: 'static',
                  keyboard: false
              });
              $("#modal_registro_referencia").modal('show'); //modal de registrar producto
              $("#titulo_modal_referencia").html('Registrar Referencias');
              $("#btnregistrar_referencia").html('<i class="fas fa-save"></i> Registrar Referencias');

              if (tbl_clientes.row(this).child.isShown()) {
                  var data = tbl_clientes.row(this).data();
              } else {
                  var data = tbl_clientes.row($(this).parents('tr')).data(); 
              }
              var cliente_id = data.cliente_id;
              var cliente_nombre = data.cliente_nombres;
              $("#id_refe").val(cliente_id);

              // Limpiar campos del modal
              $("#text_refe_personal").val("");
              $("#text_cel_personal").val("");
              $("#text_dir_personal").val("");
              $("#text_refe_familiar").val("");
              $("#text_cel_familiar").val("");
              $("#text_dir_familiar").val("");
              
              // Actualizar título con nombre del cliente
              $("#titulo_modal_referencia").html('Registrar Referencias - ' + cliente_nombre);

          })

          /* ======================================================================================
           REGISTRAR REFERENCIAS DEL CLIENTE
          =========================================================================================*/
          $("#btnregistrar_referencia").on('click', function() {
              var form = $("#modal_registro_referencia .needs-validation")[0];
              
              if (form.checkValidity()) {
                  
                  Swal.fire({
                      title: '¿Está seguro de registrar las referencias?',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Sí, registrar',
                      cancelButtonText: 'Cancelar',
                  }).then((result) => {

                      if (result.isConfirmed) {

                          var datos = new FormData();

                          datos.append("accion", 8);
                          datos.append("cliente_id", $("#id_refe").val());
                          datos.append("refe_personal", $("#text_refe_personal").val());
                          datos.append("refe_cel_per", obtenerTelefonoCompleto("#text_cel_personal"));
                          datos.append("refe_per_dir", $("#text_dir_personal").val());
                          datos.append("refe_familiar", $("#text_refe_familiar").val());
                          datos.append("refe_cel_fami", obtenerTelefonoCompleto("#text_cel_familiar"));
                          datos.append("refe_fami_dir", $("#text_dir_familiar").val());

                          // Datos Laborales de la Referencia
                          datos.append("refe_empresa_laboral", $("#text_refe_empresa_laboral").val());
                          datos.append("refe_cargo_laboral", $("#text_refe_cargo_laboral").val());
                          datos.append("refe_tel_laboral", obtenerTelefonoCompleto("#text_refe_tel_laboral"));
                          datos.append("refe_dir_laboral", $("#text_refe_dir_laboral").val());

                          $.ajax({
                              url: "ajax/clientes_ajax.php",
                              method: "POST",
                              data: datos,
                              cache: false,
                              contentType: false,
                              processData: false,
                              dataType: 'json',
                              success: function(respuesta) {

                                  if (respuesta == "ok") {

                                      Toast.fire({
                                          icon: 'success',
                                          title: 'Las referencias se registraron correctamente'
                                      });

                                      $("#modal_registro_referencia").modal('hide');
                                      tbl_clientes.ajax.reload();

                                  } else {
                                      Toast.fire({
                                          icon: 'error',
                                          title: 'Error al registrar las referencias: ' + respuesta
                                      });
                                  }
                              },
                              error: function(jqXHR, textStatus, errorThrown) {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error de comunicación con el servidor'
                                  });
                              }
                          });
                      }
                  })

              } else {
                  $(".needs-validation").addClass("was-validated");
                  Toast.fire({
                      icon: 'warning',
                      title: 'Por favor complete todos los campos obligatorios'
                  });
              }
          })


          /* ======================================================================================
           EVENTO AL CERRAR MODAL DE REFERENCIAS
          =========================================================================================*/
          $("#btncerrarmodal_referencia, #btncerrar_referencia").on('click', function() {
              $("#id_refe").val("");
              $("#text_refe_personal").val("");
              $("#text_cel_personal").val("");
              $("#text_dir_personal").val("");
              $("#text_refe_familiar").val("");
              $("#text_cel_familiar").val("");
              $("#text_dir_familiar").val("");
              $("#text_refe_empresa_laboral").val("");
              $("#text_refe_cargo_laboral").val("");
              $("#text_refe_tel_laboral").val("");
              $("#text_refe_dir_laboral").val("");
              $(".needs-validation").removeClass("was-validated");
          })


          /* ======================================================================================
            EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
           =========================================================================================*/
          $("#btncerrarmodal_cliente, #btncerrar_cliente").on('click', function() {
              $("#id_cliente").val("");
              $("#text_nombres").val("");
              $("#text_documento").val("");
              $("#text_cel").val("");
              $("#text_direccion").val("");
              $("#text_correo").val("");
              $("#text_refe_per_e").val("");
              $("#text_nro_cel_per_e").val("");
              $("#text_empresa_laboral").val("");
              $("#text_cargo_laboral").val("");
              $("#text_tel_laboral").val("");
              $("#text_dir_laboral").val("");
              $("#text_refe_per_dir").val("");
              $("#text_refe_fami_e").val("");
              $("#text_nro_cel_fami_e").val("");
              $("#text_refe_fami_dir").val("");
          })
         

          /*===================================================================*/
          //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
          /*===================================================================*/
          document.getElementById("btncerrar_cliente").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
              // Volver a habilitar los campos y mostrar el botón al cerrar, en caso de que se haya visto un cliente
              $("#modal_registro_cliente .form-control, #modal_registro_cliente .form-select").prop('disabled', false);
              $("#btnregistrar_cliente").show();
          })
          document.getElementById("btncerrarmodal_cliente").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
              // Volver a habilitar los campos y mostrar el botón al cerrar, en caso de que se haya visto un cliente
              $("#modal_registro_cliente .form-control, #modal_registro_cliente .form-select").prop('disabled', false);
              $("#btnregistrar_cliente").show();
          })

          




      }) // FIN DOCUMEN READY










      //////////////////////////////////////////////////////////////////// FUNCIONES///////////////////////////////////////////////////////////////////////////////////////////////

      function AbrirModalRegistroCliente() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_registro_cliente").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_registro_cliente").modal('show'); //abrimos el modal
          $("#titulo_modal_cliente").html('Registrar Cliente');
          $("#btnregistrar_cliente").html('Registrar');
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