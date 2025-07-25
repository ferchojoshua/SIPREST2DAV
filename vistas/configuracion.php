  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0">Configuracion</h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">Configuracion</li>
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
                          <h3 class="card-title">Configuracion de la empresa</h3>

                      </div>
                      <form action="" class="form-horizontal">
                          <div class=" card-body">
                              <form class="needs-validation" novalidate>
                                  <div class="row">
                                      <div class="col-md-8">
                                          <div class="form-group mb-2">
                                              <label for="" class="">

                                                  <span class="small"> Razon Social</span><span class="text-danger">*</span>
                                              </label>
                                              <input type="text" name="" id="confi_id" hidden>
                                              <input type="text" class=" form-control form-control-sm" id="text_razon" name="text_razon" required>
                                              <div class="invalid-feedback">Debe ingresar la Razon social</div>

                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group mb-2">
                                              <label for="" class="">

                                                  <span class="small">Ruc</span><span class="text-danger">*</span>
                                              </label>
                                              <input type="text" class=" form-control form-control-sm" id="text_ruc" name="text_ruc" required>
                                              <div class="invalid-feedback">Debe ingresar el ruc</div>

                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group mb-2">
                                              <label for="" class="">

                                                  <span class="small">Direccion</span><span class="text-danger">*</span>
                                              </label>
                                              <input type="text" class=" form-control form-control-sm" id="text_direccion" name="text_direccion" required>
                                              <div class="invalid-feedback">Debe ingresar la Direccion</div>

                                          </div>
                                      </div>
                                      <div class="col-md-2">
                                          <div class="form-group mb-2">
                                              <label for="" class="">

                                                  <span class="small">Moneda</span><span class="text-danger">*</span>
                                              </label>
                                              <input type="text" class=" form-control form-control-sm" id="text_moneda" name="text_moneda" required>
                                              <div class="invalid-feedback">Debe ingresar la Moneda</div>

                                          </div>
                                      </div>


                                      <!-- Campo de correlativo removido - Ahora se maneja automáticamente por sucursal -->
                                      <div class="col-lg-4">
                                          <div class="form-group mb-2">
                                              <label for="" class="">

                                                  <span class="small">Correo</span><span class="text-danger">*</span>
                                              </label>
                                              <input type="text" class=" form-control form-control-sm" id="text_correo" name="text_correo" required>
                                              <div class="invalid-feedback">Debe ingresar la Direccion</div>

                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group mb-2">
                                              <label for="" class="">
                                                  <span class="small">Logo de la Empresa</span>
                                              </label>
                                              <input type="file" class="form-control form-control-sm" id="file_logo" name="file_logo" accept="image/*">
                                              <small class="text-muted">Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB</small>
                                          </div>
                                      </div>
                                      <div class="col-md-6">
                                          <div class="form-group mb-2">
                                              <label class="small">Vista Previa del Logo:</label>
                                              <div class="text-center">
                                                  <img id="preview_logo" src="vistas/assets/img/default-logo.png" alt="Logo" style="max-width: 150px; max-height: 100px; border: 1px solid #ddd; padding: 5px; border-radius: 5px;">
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </form>

                          </div>
                      </form>
                      <div class="card-footer">
                          <!-- <button class="btn btn-info btn-sm float-left" id="btnGuardar"><i class="fas fa-save"></i> Guardar</button> -->
                          <button class="btn btn-info btn-sm float-left" id="btnGuardar">Guardar</button>
                          <!-- <a class="btn btn-primary btn-sm" style="width:120px;" id="btnGuardar2">Buscar</a> -->
                      </div>
                  </div>
              </div>

          </div>

      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <?php require_once "modulos/footer.php"; ?>

  <script>
      var accion;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });
      $(document).ready(function() {
          // clearInterval(corre);

          /* ======================================================================================
            TRAER LOS DATOS
            ======================================================================================*/
          CargarDatosEmpresa();

          // Vista previa del logo
          $("#file_logo").change(function() {
              var file = this.files[0];
              if (file) {
                  // Validar tipo de archivo
                  var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                  if (!validTypes.includes(file.type)) {
                      Toast.fire({
                          icon: 'error',
                          title: 'Formato de archivo no válido. Use JPG, PNG o GIF.'
                      });
                      this.value = '';
                      return;
                  }
                  
                  // Validar tamaño (2MB)
                  if (file.size > 2 * 1024 * 1024) {
                      Toast.fire({
                          icon: 'error',
                          title: 'El archivo es muy grande. Máximo 2MB.'
                      });
                      this.value = '';
                      return;
                  }
                  
                  var reader = new FileReader();
                  reader.onload = function(e) {
                      $('#preview_logo').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(file);
              }
          });

          // Vista previa del logo
          $("#file_logo").change(function() {
              var file = this.files[0];
              if (file) {
                  // Validar tipo de archivo
                  var validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                  if (!validTypes.includes(file.type)) {
                      Toast.fire({
                          icon: 'error',
                          title: 'Formato de archivo no válido. Use JPG, PNG o GIF.'
                      });
                      this.value = '';
                      return;
                  }
                  
                  // Validar tamaño (2MB)
                  if (file.size > 2 * 1024 * 1024) {
                      Toast.fire({
                          icon: 'error',
                          title: 'El archivo es muy grande. Máximo 2MB.'
                      });
                      this.value = '';
                      return;
                  }
                  
                  var reader = new FileReader();
                  reader.onload = function(e) {
                      $('#preview_logo').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(file);
              }
          });

          $("#btnCalcular").on('click', function() {
              /// alert('dsds');


          })



          /*===================================================================*/
          //EVENTO QUE GUARDA LOS DATOS DEL PRODUCTO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
          /*===================================================================*/
          document.getElementById("btnGuardar").addEventListener("click", function() {
              var razon = $("#text_razon").val();
              var correo = $("#text_correo").val();
              var direc = $("#text_direccion").val();
              var moneda = $("#text_moneda").val();

              if (razon == "") {
                  Toast.fire({
                      icon: 'error',
                      title: 'Ingresar una razon de la empresa'
                  });
                  $("#text_razon").focus();
              } else if (correo == "") {
                Toast.fire({
                      icon: 'error',
                      title: 'Debe ingresar un correo'
                  });
                  $("#text_correo").focus();

              } else if (direc == "") {
                Toast.fire({
                      icon: 'error',
                      title: 'Ingresar la dirección'
                  });
                  $("#text_direccion").focus();

              }else if (moneda == "") {
                Toast.fire({
                      icon: 'error',
                      title: 'Ingresar la moneda'
                  });
                  $("#text_moneda").focus();

              } else {



                  //console.log("Listo para registrar el producto")
                  Swal.fire({
                      title: 'Esta seguro de Actualizar la informacion?',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Si',
                      cancelButtonText: 'Cancelar',
                  }).then((result) => {
                      if (result.isConfirmed) {

                          var datos = new FormData();

                          datos.append("accion", 2);
                          datos.append("confi_id", $("#confi_id").val()); //id
                          datos.append("confi_razon", $("#text_razon").val()); //nombre
                          datos.append("confi_ruc", $("#text_ruc").val()); //peso
                          datos.append("confi_direccion", $("#text_direccion").val());
                          // Correlativo removido - ahora se maneja por sucursal
                          datos.append("config_correo", $("#text_correo").val());
                          datos.append("config_moneda", $("#text_moneda").val());
                          
                          // Agregar logo si se seleccionó
                          var logoFile = $("#file_logo")[0].files[0];
                          if (logoFile) {
                              datos.append("config_logo", logoFile);
                          }


                          $.ajax({
                              url: "ajax/configuracion_ajax.php",
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
                                          title: 'Datos Actualizados'
                                          //title: titulo_msj
                                      });

                                      $(".needs-validation").removeClass(
                                          "was-validated");

                                  } else {
                                      Toast.fire({
                                          icon: 'error',
                                          title: 'no se pudo Actualizar'
                                      });
                                  }

                              }
                          });
                      }
                  })


              }


          });


      })

      //FUNCIONES

      /*===================================================================*/
      //FUNCION PARA CARGAR DATOS DE LA EMPRESA EN TEXBOX
      /*===================================================================*/
      function CargarDatosEmpresa() {

          $.ajax({
              async: false,
              url: "ajax/configuracion_ajax.php",
              method: "POST",
              data: {
                  'accion': 1
              },
              dataType: 'json',
              success: function(respuesta) {

                  confi_id = respuesta["confi_id"];
                  razon_social = respuesta["confi_razon"]; //campos de base
                  ruc = respuesta["confi_ruc"];
                  direccion = respuesta["confi_direccion"];
                  // nro_correlativo removido - ahora se maneja por sucursal
                  email = respuesta["config_correo"];
                  moneda = respuesta["config_moneda"];

                  $("#confi_id").val(confi_id);
                  $("#text_razon").val(razon_social); //SETEAMOS EN LOS TEXTBOX
                  $("#text_ruc").val(ruc);
                  $("#text_direccion").val(direccion);
                  // $("#text_correlativo").val(nro_correlativo); - removido
                  $("#text_correo").val(email);
                  $("#text_moneda").val(moneda);
                  
                  // Mostrar logo actual si existe
                  console.log("Datos recibidos del AJAX:", respuesta);
                  if (respuesta["config_logo"] && respuesta["config_logo"] !== '') {
                      console.log("Logo encontrado:", respuesta["config_logo"]);
                      var logoPath = 'uploads/logos/' + respuesta["config_logo"];
                      console.log("Ruta del logo:", logoPath);
                      $("#preview_logo").attr('src', logoPath);
                      
                      // Verificar si la imagen se carga correctamente
                      $("#preview_logo").on('load', function() {
                          console.log("Logo cargado exitosamente");
                      }).on('error', function() {
                          console.error("Error al cargar el logo:", logoPath);
                          $("#preview_logo").attr('src', 'vistas/assets/img/default-logo.png');
                      });
                  } else {
                      console.log("No se encontró logo en la respuesta");
                  }
              }
          });
      }
  </script>