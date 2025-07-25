  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <!-- <div class="col-sm-6">
                  <h4 class="m-0">Prestamo</h4>
              </div>
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">Prestamos</li>
                  </ol>
              </div> -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->


  <!-- Main content -->
  <div class="content pb-2">
      <div class="container-fluid">
          <div class="row p-0 m-0">
              <div class="col-md-12">
                  <div class="card card-info ">
                      <div class="card-header bg-gradient-info">
                          <h3 class="card-title">Registrar Prestamo</h3>

                      </div>
                      <div class=" card-body">
                          <div class="row">

                              <div class="col-md-8">
                                  <div class="card ">
                                      <div class="card-header">
                                          <h6 class="card-title">SOLICITUD DE CREDITO</h6>
                                      </div>
                                      <div class=" card-body">

                                          <!-- PARA BUSCAR EL CLIENTE -->
                                         <div class="row">
                                                        <!-- Nº de Préstamo -->
                                                        <div class="col-md-8 col-10">
                                                            <div class="form-group mb-2">
                                                            <input type="text" name="" id="cliente_id" hidden>
                                                            <input type="text" name="" id="id_usuario" hidden>
                                                            <input type="text" name="" id="id_caja" hidden>

                                                            <input type="text" class="form-control form-control-sm" id="text_nro_prestamo" required disabled>
                                                            </div>
                                                        </div>

                                                        <!-- Cédula -->
                                                        <div class="col-md-8 col-10">
                                                            <div class="form-group mb-2">
                                                            <input type="text" class="form-control form-control-sm" id="text_dni" autocomplete="off"
                                                                name="text_dni" placeholder="Cédula o Nombre del Cliente" required>
                                                            <div class="invalid-feedback">Debe ingresar la Cédula del cliente</div>
                                                            </div>
                                                        </div>

                                                        <!-- Botón buscar cliente -->
                                                        <div class="col-md-2 col-2">
                                                            <div class="form-group mb-2 d-grid">
                                                            <button class="btn btn-info btn-sm" id="abrirmodal_buscar_cliente">
                                                                <i class="fas fa-search"></i>
                                                            </button>
                                                            </div>
                                                        </div>

                                                        <!-- Nombre del cliente -->
                                                        <div class="col-md-8">
                                                            <div class="form-group mb-2">
                                                            <input type="text" class="form-control form-control-sm" id="text_nombre" name="text_nombre"
                                                                placeholder="Nombre del cliente" disabled>
                                                            </div>
                                                        </div>

                                                        <!-- Documento del cliente -->
                                                        <div class="col-md-2">
                                                            <div class="form-group mb-2">
                                                            <input type="text" class="form-control form-control-sm" id="text_doc_dn" name="text_doc_dn"
                                                                placeholder="Cedula" disabled>
                                                            </div>
                                                        </div>

                                                        <!-- Combo de Sucursal (solo visible para admin) -->
                                                        <div class="col-md-3" id="combo-sucursal-container" style="display: none;">
                                                            <div class="form-group mb-2">
                                                            <label for="combo-sucursales" class="form-label fw-semibold text-dark small">
                                                                <i class="fas fa-building me-1 text-primary"></i> Seleccione una Sucursal
                                                            </label>
                                                            <select class="form-select form-select-sm" id="combo-sucursales" required>
                                                                <option value="" disabled selected class="text-muted"> Seleccione una sucursal</option>
                                                                <!-- Opciones dinámicas -->
                                                            </select>
                                                            <div class="invalid-feedback small">
                                                                Por favor, seleccione una sucursal válida.
                                                            </div>
                                                            </div>
                                                        </div>
                                                        </div>


                                          <br>
                                          <hr>
                                          <h5 style="text-align:center;">Informacion del Prestamo</h5>
                                          <br>
                                          <!-- PARA CALCULAR LAS CUOTAS E INTERES DEL PRESTAMO -->
                                          <!-- <form class="needs-validation" novalidate>  -->
                                          <div class="row">
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">

                                                          <span class="small"> Monto Prestamo</span>
                                                      </label>
                                                      <input type="number" class=" form-control form-control-sm"
                                                          id="text_monto" min="0" step="1" placeholder="Monto Prestamo"
                                                          required>

                                                      <div class="invalid-feedback">Debe ingresar el monto del prestamo
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">

                                                          <span class="small"> Interes %</span>
                                                      </label>
                                                      <input type="number" class=" form-control form-control-sm"
                                                          id="text_interes" min="0" placeholder="Interes" required>

                                                      <div class="invalid-feedback">Debe ingresar el interes </div>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">

                                                          <span class="small"> Plazo/Cuotas</span>
                                                      </label>
                                                      <input type="number" class=" form-control form-control-sm"
                                                          id="text_cuotas" min="0" max="50" placeholder="Cantida de Cutas"
                                                          required>

                                                      <div class="invalid-feedback">Debe ingresar el Plazo
                                                      </div>
                                                  </div>
                                              </div>

                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small"> Forma Pago</span>
                                                      </label>
                                                      <select name="" id="select_f_pago"
                                                          class="form-select form-select-sm"
                                                          aria-label=".form-select-sm example" required></select>

                                                      <div class="invalid-feedback">Seleccione una forma de pago</div>

                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small">Moneda</span>
                                                      </label>
                                                      <select name="" id="select_moneda"
                                                          class="form-select form-select-sm"
                                                          aria-label=".form-select-sm example" required></select>

                                                      <div class="invalid-feedback">Seleccione una moneda</div>

                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small">Amortización:</span>
                                                      </label>
                                                      <select name="" id="select_tipo_calculo"
                                                          class="form-select form-select-sm"
                                                          aria-label=".form-select-sm example" required>
                                                      </select>

                                                      <div class="invalid-feedback">Seleccione un tipo de Amortización</div>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small">Fecha Inicio</span>
                                                      </label>
                                                      <input type="date" class="form-control form-control-sm" id="text_fecha" required>
                                                      <div class="invalid-feedback">Ingrese una fecha</div>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small">Monto por Cuota</span>
                                                      </label>
                                                      <input type="text" class="form-control form-control-sm" id="text_monto_por_cuota" readonly>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small">Total Interés</span>
                                                      </label>
                                                      <input type="text" class="form-control form-control-sm" id="text_interes_resultado" readonly>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group mb-2">
                                                      <label for="" class="">
                                                          <span class="small">Monto total a Pagar</span>
                                                      </label>
                                                      <input type="text" class="form-control form-control-sm" id="text_total_resultado" readonly>
                                                  </div>
                                              </div>
                                  
                                              <div class="col-12 d-flex justify-content-end mt-3">
                                                  <button type="button" class="btn btn-danger" id="btnLimpiarCampos" hidden>
                                                      <i class="fas fa-broom"></i> LIMPIAR
                                                  </button>
                                                  <button class="btn btn-success" id="btnRegistrar">
                                                      <i class="fas fa-save"></i> REGISTRAR
                                                  </button>
                                              </div>
                                        </div>
                                         
                                          <br>
                                          <!-- PARA EL DATATABLE  -->
                                        <div class="row">

                                              <div class="table-responsive">
                                                  <table id="tbl_prestamo" class="table table-bordered table-striped table-sm compact" style="width: 100%">
                                                      <thead class="bg-gradient-info text-white">
                                                          <tr>
                                                              <th class="text-center">Nº</th>
                                                              <th class="text-center">Fecha</th>
                                                              <th class="text-center">Monto</th>
                                                              <th class="text-center">Capital</th>
                                                              <th class="text-center">Interés Cuota</th>
                                                              <th class="text-center">Saldo Capital</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody id="tbody_tabla_detalle_pro">
                                                          <tr>
                                                              <td colspan="6" class="text-center text-muted">
                                                                  <i class="fas fa-calculator"></i> Complete los datos y calcule para ver la tabla de amortización
                                                              </td>
                                                          </tr>
                                                      </tbody>
                                                  </table>
                                              </div>
                                              
                                              <!-- Paginación para la tabla de amortización -->
                                              <div class="paginacion-container mt-3" style="display: none;">
                                                  <div class="d-flex justify-content-between align-items-center">
                                                      <div class="pagination-info">
                                                          Mostrando <span id="desde">0</span> a <span id="hasta">0</span> de <span id="total">0</span> cuotas
                                                      </div>
                                                      <div>
                                                          <nav aria-label="Navegación de tabla de amortización">
                                                              <ul class="pagination pagination-sm" id="paginacion-tabla">
                                                                  <!-- Los enlaces de paginación se generarán dinámicamente -->
                                                              </ul>
                                                          </nav>
                                                      </div>
                                                  </div>
                                              </div>
                                         </div>


                                      </div>


                                  </div>
                              </div>

                          </div>


                      </div>
                  </div>
              </div>
          </div>

      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->


  <!-- MODAL LISTAR CLIENTES-->
  <div class="modal fade" id="modal_listar_cliente" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h6 class="modal-title" id="titulo_modal_categorias">Lista de Clientes</h6>&nbsp;&nbsp;
                  <button class="btn btn-success btn-sm " id="abrirmodal_registrar_cliente"><i
                          class="fas fa-plus"></i></button>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal"
                      data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="table-responsive">
                      <table id="tbl_lista_cliente" class="display table-sm nowrap table-striped  w-100 ">
                          <thead class="bg-gradient-info text-white">
                              <tr>
                                  <th>Id</th>
                                  <th>Nombres</th>
                                  <th>Doc</th>
                                  <th>Prestamo</th>
                                  <th>estado</th>
                                  <!-- <th class="text-cetner">Opciones</th> -->
                              </tr>
                          </thead>
                          <tbody class="small text left">
                          </tbody>
                      </table>
                  </div>


              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                      id="btncerrar">Cerrar</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <!-- MODAL REGISTRAR CLIENTE-->
  <div class="modal fade" id="modal_registro_cliente" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cliente">Registro de Usuarios</h5>
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
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal"
                      id="btncerrar_cliente">Cerrar</button>
                  <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->
<?php require_once "modulos/footer.php"; ?>
  <script>
// ============================================================================
// FUNCIONES GLOBALES - DEFINIDAS ANTES DE TODO EL RESTO DEL CÓDIGO
// ============================================================================

// Variables globales
var accion;
var tbl_prestamo, tbl_lista_cliente, fecha_emision, corre;

var Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 3000
});

// Función para verificar si es administrador
function esAdmin() {
    return window.userContext && window.userContext.usuario && window.userContext.usuario.es_admin === true;
}

// Función para cargar sucursales para administradores
function cargarSucursalesAdmin() {
    $.ajax({
        url: 'ajax/aprobacion_ajax.php',
        type: 'GET',
        data: { accion: 'listar_sucursales' },
        dataType: 'json',
        success: function(response) {
            const select = $('#combo-sucursales');
            select.empty().append('<option value="">Seleccione Sucursal</option>');
            
            if (response && Array.isArray(response) && response.length > 0) {
                response.forEach(function(sucursal) {
                    const sucursalId = sucursal.sucursal_id || sucursal.id;
                    const sucursalNombre = sucursal.sucursal_nombre || sucursal.nombre;
                    const sucursalTexto = sucursal.texto_descriptivo || sucursalNombre;
                    
                    if (sucursalId && sucursalNombre) {
                        select.append('<option value="' + sucursalId + '">' + sucursalTexto + '</option>');
                    }
                });
                console.log('✅ Sucursales cargadas para admin');
            } else {
                select.append('<option value="">No hay sucursales disponibles</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar sucursales:', error);
            $('#combo-sucursales').empty().append('<option value="">Error al cargar sucursales</option>');
            Toast.fire({
                icon: 'error',
                title: 'Error al cargar sucursales'
            });
        }
    });
}

// Función para mostrar u ocultar combo según el rol
function configurarComboSucursales() {
    if (esAdmin()) {
        $('#combo-sucursal-container').show();
        cargarSucursalesAdmin();
        $('#combo-sucursales').prop('required', true);
    } else {
        $('#combo-sucursal-container').hide();
        $('#combo-sucursales').prop('required', false);
    }
}

// Función para validar caja para administradores
function CargarEstadoCajaAdmin() {
    if (esAdmin()) {
        $.ajax({
            async: false,
            url: "ajax/caja_ajax.php",
            method: "POST",
            data: {
                'accion': 'verificar_caja_admin'
            },
            dataType: 'json',
            success: function(respuesta) {
                if (respuesta && respuesta.puede_operar === true) {
                    console.log('Admin puede operar con cajas disponibles');
                } else {
                    mostrarErrorCajaNoDisponible();
                }
            },
            error: function() {
                CargarEstadoCaja();
            }
        });
    } else {
        CargarEstadoCaja();
    }
}

function mostrarErrorCajaNoDisponible() {
    Swal.fire({
        position: 'center',
        icon: 'error',
        title: 'Mensaje de Error',
        text: 'Debe aperturar una caja, se redireccionara a la ventana',
        showConfirmButton: false,
    });

    $("#text_monto").attr('disabled', true);
    $("#text_interes").attr('disabled', true);
    $("#text_cuotas").attr('disabled', true);
    $("#select_f_pago").attr('disabled', true);
    $("#select_moneda").attr('disabled', true);
    $("#text_fecha").attr('disabled', true);
    CargarContenido('vistas/caja.php', 'content-wrapper');
}

// ============================================================================
// DOCUMENT READY - INICIALIZACIÓN PRINCIPAL
// ============================================================================
$(document).ready(function() {

    // Cargar datos iniciales
    CargarCorrelativo();
    CargarEstadoCajaAdmin();
    CargarId_Caja();
    fechas();
    
    // Configurar combo de sucursales según el rol del usuario
    configurarComboSucursales();
    
    // Evento para recargar consecutivo cuando administrador cambie sucursal
    $('#combo-sucursales').on('change', function() {
        var sucursalSeleccionada = $(this).val();
        if (sucursalSeleccionada) {
            console.log('🔄 Recargando consecutivo para sucursal:', sucursalSeleccionada);
            CargarCorrelativo();
        } else {
            // Limpiar el campo si no hay sucursal seleccionada
            $("#text_nro_prestamo").val('');
            console.log('⚠️ Sin sucursal seleccionada, consecutivo limpiado');
        }
    });
    
    // Cargar formas de pago al iniciar
    $.ajax({
        url: "ajax/prestamo_ajax.php",
        method: "POST",
        data: {
            'accion': 'cargar_forma_pago'
        },
        dataType: 'json',
        success: function(respuesta) {
            var options = '<option value="">Seleccione forma de pago</option>';
            if (Array.isArray(respuesta)) {
                respuesta.forEach(function(item) {
                    options += '<option value="' + item.fpago_id + '">' + item.fpago_descripcion + '</option>';
                });
                $("#select_f_pago").html(options);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar formas de pago:", error);
        }
    });

    // Cargar monedas al iniciar
    $.ajax({
        url: "ajax/prestamo_ajax.php",
        method: "POST",
        data: {
            'accion': 'cargar_moneda'
        },
        dataType: 'json',
        success: function(respuesta) {
            var options = '<option value="">Seleccione moneda</option>';
            if (Array.isArray(respuesta)) {
                respuesta.forEach(function(item) {
                    options += '<option value="' + item.moneda_id + '">' + item.moneda_descripcion + '</option>';
                });
                $("#select_moneda").html(options);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al cargar monedas:", error);
        }
    });

    // Cargar tipos de cálculo al iniciar
    CargarTiposCalculo();

    var id_usuario = $("#text_Idprincipal").val();
    $("#id_usuario").val(id_usuario);
    $("#btnRegistrar").attr('hidden', true);

    // ============================================================================
    // INICIALIZAR DATATABLE BUSCAR CLIENTES
    // ============================================================================
    var tbl_lista_cliente = $("#tbl_lista_cliente").DataTable({
        ajax: {
            url: "ajax/clientes_ajax.php",
            dataSrc: "",
            type: "POST",
            data: {
                'accion': 7
            },
        },
        columnDefs: [{
            targets: 4,
            visible: false
        }, {
            targets: 3,
            createdCell: function(td, cellData, rowData, row, col) {
                if (rowData[3] == 'con prestamo') {
                    $(td).html("<span class='badge badge-warning'>CON PRESTAMO</span>")
                } else {
                    $(td).html("<span class='badge badge-success'>DISPONIBLE</span>")
                }
            }
        }],
        "order": [
            [0, 'desc']
        ],
        lengthMenu: [0, 5, 10, 15, 20, 50],
        "pageLength": 10,
        "language": idioma_espanol,
        select: true
    });

    // ============================================================================
    // EVENTOS DE LA INTERFAZ
    // ============================================================================

    // Validar cédula cuando cambia el campo
    $("#text_dni").change(function() {
        var document = $("#text_dni").val();
        $.ajax({
            async: false,
            url: "ajax/clientes_ajax.php",
            method: "POST",
            data: {
                'accion': 5,
                'cliente_dni': document
            },
            dataType: 'json',
            success: function(respuesta) {
                if (parseInt(respuesta['ex']) > 0) {
                    CargarDatosCliente();
                    $("#text_dni").val("");
                } else {
                    Toast.fire({
                        icon: 'error',
                        title: ' Documento ' + document + ' no esta registrado'
                    })
                    $("#text_nombre").val("");
                    $("#text_doc_dn").val("");
                    $("#cliente_id").val("");
                    $("#text_dni").val("");
                }
            }
        });
    });

    // Abrir modal buscar clientes
    $("#abrirmodal_buscar_cliente").on('click', function() {
        $("#modal_listar_cliente").modal({
            backdrop: 'static',
            keyboard: false
        });
        $("#modal_listar_cliente").modal('show');
    });

    // Abrir modal registrar cliente
    $("#abrirmodal_registrar_cliente").on('click', function() {
        AbrirModalRegistroCliente();
        $("#modal_registro_cliente .form-control, #modal_registro_cliente .form-select").prop('disabled', false);
        $("#btnregistrar_cliente").show();
        $("#titulo_modal_cliente").html('Registro de Usuarios');
        $("#btnregistrar_cliente").html('Registrar');
    });

    // Doble click en tabla de clientes
    $(document).on('dblclick', '#tbl_lista_cliente tr', function(event) {
        let disponible = $(this).find("td").eq(3).html();

        if (disponible == '<span class="badge badge-success">DISPONIBLE</span>') {
            let idCliente = $(this).find("td").eq(0).html();
            let nombres = $(this).find("td").eq(1).html();
            let dni = $(this).find("td").eq(2).html();

            $("#text_nombre").val(nombres);
            $("#text_doc_dn").val(dni);
            $("#cliente_id").val(idCliente);
            $("#modal_listar_cliente").modal('hide');
        } else {
            Toast.fire({
                icon: 'warning',
                title: 'Cliente Tiene un prestamo Pendiente, Revisar'
            });
        }
    });

    // CÁLCULO AUTOMÁTICO cuando cambien los valores
    $('#text_monto, #text_interes, #text_cuotas, #select_tipo_calculo, #text_fecha, #select_f_pago').on('change keyup', function() {
        var monto = $("#text_monto").val();
        var interes = $("#text_interes").val();
        var cuotas = $("#text_cuotas").val();
        var fpago = $("#select_f_pago").val();
        var tipo_calculo = $("#select_tipo_calculo").val();
        var fecha = $("#text_fecha").val();
        
        if (monto && interes && cuotas && fpago && tipo_calculo && fecha) {
            recalcularPrestamo();
            $("#btnRegistrar").attr('hidden', false);
            $("#btnLimpiarCampos").attr('hidden', false);
        }
    });

    // Registrar préstamo
    $("#btnRegistrar").on('click', function() {
        var clienteId = $("#cliente_id").val();

        if (clienteId === "" || clienteId === null) {
            Toast.fire({
                icon: 'error',
                title: 'Debe seleccionar un cliente para poder registrar el préstamo.'
            });
            return;
        }

        RegistarPrestamo();
        $("#btnLimpiarCampos").attr('hidden', true);
        $("#btnRegistrar").attr('hidden', true);
    });

    // Limpiar campos
    $("#btnLimpiarCampos").on('click', function() {
        LimpiarInputs();
        $("#btnLimpiarCampos").attr('hidden', true);
        $("#btnRegistrar").attr('hidden', true);
    });

    // Enter en DNI abre modal de búsqueda
    $("#text_dni").on('keyup', function(event) {
        if (event.keyCode == 13 || event.which == 13) {
            $("#abrirmodal_buscar_cliente").click();
        }
    });

    // Enter en monto recalcula
    $("#text_monto").on('keyup', function(event) {
        if (event.keyCode == 13 || event.which == 13) {
            recalcularPrestamo();
        }
    });

}); // FIN DOCUMENT READY

// ============================================================================
// FUNCIONES DEL SISTEMA
// ============================================================================

function CargarTiposCalculo() {
    $.ajax({
        url: "ajax/prestamo_ajax.php",
        method: "POST",
        data: {
            'accion': 'obtener_tipos_calculo'
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta.error) {
                $("#select_tipo_calculo").html('<option value="">Error al cargar</option>').prop('disabled', true);
                return;
            }
            
            var options = '<option value="">Seleccione un tipo de cálculo</option>';
            
            if (Array.isArray(respuesta)) {
                respuesta.forEach(function(tipo) {
                    options += '<option value="' + tipo.nombre + '" title="' + tipo.descripcion + '">' + 
                              tipo.descripcion + '</option>';
                });
                $("#select_tipo_calculo").html(options);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar tipos de cálculo:', error);
            $("#select_tipo_calculo").html('<option value="">Error al cargar</option>').prop('disabled', true);
        }
    });
}

function recalcularPrestamo(pagina = 1) {
    var montoPresta = parseFloat($("#text_monto").val()) || 0;
    var cuota = parseInt($("#text_cuotas").val()) || 0;
    var interes = parseFloat($("#text_interes").val()) || 0;
    var fpago = $("#select_f_pago").val();
    var tipoCalculo = $("#select_tipo_calculo").val();
    var fechaInicio = $("#text_fecha").val();

    if (montoPresta <= 0 || cuota <= 0 || interes <= 0 || !fpago || !tipoCalculo || !fechaInicio) {
        return;
    }

    $.ajax({
        url: "ajax/prestamo_ajax.php",
        method: "POST",
        data: {
            'accion': 'calcular_amortizacion',
            'monto': montoPresta,
            'cuotas': cuota,
            'interes': interes,
            'fpago': fpago,
            'tipo_calculo': tipoCalculo,
            'fecha_inicio': fechaInicio,
            'pagina': pagina,
            'por_pagina': 12
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta.error) {
                Swal.fire("Error", respuesta.error, "error");
                return;
            }

            // Actualizar campos con los resultados
            $("#text_monto_por_cuota").val(respuesta.cuota_inicial.toFixed(2));
            $("#text_interes_resultado").val(respuesta.total_intereses.toFixed(2));
            $("#text_total_resultado").val(respuesta.total_pagar.toFixed(2));

            // Limpiar y actualizar la tabla de detalle
            $("#tbody_tabla_detalle_pro").empty();
            
            var totalMonto = 0;
            var totalCapital = 0;
            var totalInteres = 0;
            
            respuesta.tabla_amortizacion.forEach(function(fila) {
                totalMonto += parseFloat(fila.monto);
                totalCapital += parseFloat(fila.capital);
                totalInteres += parseFloat(fila.interes);
                
                var tr = '<tr>' +
                    '<td class="text-center">' + fila.nro_cuota + '</td>' +
                    '<td class="text-center">' + fila.fecha + '</td>' +
                    '<td class="text-right">' + parseFloat(fila.monto).toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                    '<td class="text-right">' + parseFloat(fila.capital).toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                    '<td class="text-right">' + parseFloat(fila.interes).toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                    '<td class="text-right">' + parseFloat(fila.saldo).toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</td>' +
                    '</tr>';
                $("#tbody_tabla_detalle_pro").append(tr);
            });
            
            // Agregar fila de TOTALES al final
            var trTotales = '<tr class="bg-primary text-white font-weight-bold" style="background-color: #007bff !important;">' +
                '<td class="text-center"><strong>TOTALES</strong></td>' +
                '<td class="text-center"><strong>-</strong></td>' +
                '<td class="text-right"><strong>' + totalMonto.toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong></td>' +
                '<td class="text-right"><strong>' + totalCapital.toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong></td>' +
                '<td class="text-right"><strong>' + totalInteres.toLocaleString('es-NI', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong></td>' +
                '<td class="text-right"><strong>-</strong></td>' +
                '</tr>';
            $("#tbody_tabla_detalle_pro").append(trTotales);
        },
        error: function(xhr, status, error) {
            console.error("Error al calcular amortización:", error);
            Swal.fire("Error", "No se pudo calcular la amortización", "error");
        }
    });
}

function CargarDatosCliente(dni = "") {
    if (dni != "") {
        var cliente_dni = dni;
    } else {
        var cliente_dni = $("#text_dni").val();
    }

    $.ajax({
        url: "ajax/clientes_ajax.php",
        method: "POST",
        data: {
            'accion': 6,
            'cliente_dni': cliente_dni
        },
        dataType: 'json',
        success: function(respuesta2) {
            cliente_id = respuesta2["cliente_id"];
            cli_doc = respuesta2["cliente_dni"];
            cliente_nombres = respuesta2["cliente_nombres"];

            $("#cliente_id").val(cliente_id);
            $("#text_nombre").val(cliente_nombres);
            $("#text_doc_dn").val(cli_doc);
        }
    });
}

function RegistarPrestamo() {
    var count = 0;
    var nro_prestamo = $("#text_nro_prestamo").val();
    var cliente_id = $("#cliente_id").val();
    var id_usuario = $("#id_usuario").val();
    var pres_monto = $("#text_monto").val();
    var pres_interes = $("#text_interes").val();
    var pres_cuotas = $("#text_cuotas").val();
    var fpago_id = $("#select_f_pago").val();
    var moneda_id = $("#select_moneda").val();
    var pres_f_emision = $("#text_fecha").val();
    var tipo_calculo = $("#select_tipo_calculo").val();
    var pres_monto_cuota = $("#text_monto_por_cuota").val();
    var pres_monto_interes = $("#text_interes_resultado").val();
    var pres_monto_total = $("#text_total_resultado").val();
    var caja_id = $("#id_caja").val();

    // Validar que el detalle tenga datos
    $("#tbody_tabla_detalle_pro tr").each(function(i, e) {
        count = count + 1;
    });

    // Validaciones mejoradas
    if (!cliente_id || cliente_id <= 0) {
        Toast.fire({ icon: 'warning', title: 'Debe seleccionar un cliente válido.' });
        return;
    }
    if (!pres_monto || isNaN(pres_monto) || pres_monto <= 0) {
        Toast.fire({ icon: 'warning', title: 'Ingrese un monto válido.' });
        return;
    }
    if (!pres_interes || isNaN(pres_interes) || pres_interes < 0) {
        Toast.fire({ icon: 'warning', title: 'Ingrese una tasa de interés válida.' });
        return;
    }
    if (!pres_cuotas || isNaN(pres_cuotas) || pres_cuotas <= 0) {
        Toast.fire({ icon: 'warning', title: 'Ingrese un número de cuotas válido.' });
        return;
    }
    if (!fpago_id) {
        Toast.fire({ icon: 'warning', title: 'Seleccione una forma de pago.' });
        return;
    }
    if (!moneda_id) {
        Toast.fire({ icon: 'warning', title: 'Seleccione una moneda.' });
        return;
    }
    if (!tipo_calculo) {
        Toast.fire({ icon: 'warning', title: 'Seleccione un tipo de amortización.' });
        return;
    }
    if (count === 0) {
        Toast.fire({ icon: 'warning', title: 'No hay datos en la tabla de amortización.' });
        return;
    }
    if (typeof esAdmin === "function" && esAdmin()) {
        var sucursal_seleccionada = $('#combo-sucursales').val();
        if (!sucursal_seleccionada) {
            Toast.fire({ icon: 'warning', title: 'Debe seleccionar una sucursal para el préstamo' });
            return;
        }
    }

    // Mostrar resumen de datos antes de guardar
    var resumen = `<b>Monto:</b> ${parseFloat(pres_monto).toLocaleString('es-NI', {minimumFractionDigits:2})}<br>` +
                  `<b>Interés:</b> ${parseFloat(pres_interes).toLocaleString('es-NI', {minimumFractionDigits:2})}%<br>` +
                  `<b>Cuotas:</b> ${pres_cuotas}<br>` +
                  `<b>Forma de pago:</b> ${$('#select_f_pago option:selected').text()}<br>` +
                  `<b>Moneda:</b> ${$('#select_moneda option:selected').text()}<br>` +
                  `<b>Tipo de cálculo:</b> ${$('#select_tipo_calculo option:selected').text()}<br>` +
                  `<b>Total a pagar:</b> ${parseFloat(pres_monto_total).toLocaleString('es-NI', {minimumFractionDigits:2})}`;
    
    Swal.fire({
        title: '¿Confirmar registro de préstamo?',
        html: resumen,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, registrar',
        cancelButtonText: 'Cancelar',
        focusCancel: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Deshabilitar botón para evitar doble envío
            $("#btnRegistrar").attr('disabled', true);
            var formData = new FormData();
            formData.append("accion", 1);
            formData.append('nro_prestamo', nro_prestamo);
            formData.append('cliente_id', cliente_id);
            formData.append('pres_monto', parseFloat(pres_monto));
            formData.append('pres_cuotas', pres_cuotas);
            formData.append('pres_interes', pres_interes);
            formData.append('fpago_id', fpago_id);
            formData.append('moneda_id', moneda_id);
            formData.append('pres_f_emision', pres_f_emision);
            formData.append('pres_monto_cuota', parseFloat(pres_monto_cuota));
            formData.append('pres_monto_interes', parseFloat(pres_monto_interes));
            formData.append('pres_monto_total', parseFloat(pres_monto_total));
            formData.append('id_usuario', id_usuario);
            formData.append('caja_id', caja_id);
            formData.append('tipo_calculo', tipo_calculo);
            var sucursal_id = null;
            if (typeof esAdmin === "function" && esAdmin()) {
                sucursal_id = $('#combo-sucursales').val();
            } else {
                sucursal_id = window.userContext && window.userContext.sucursal_id ? window.userContext.sucursal_id : null;
            }
            formData.append('sucursal_id', sucursal_id);
            $.ajax({
                url: "ajax/prestamo_ajax.php",
                method: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(respuesta) {
                    if (typeof respuesta === 'string' && respuesta.toLowerCase().includes('error')) {
                        Swal.fire({ icon: 'error', title: 'Error', text: respuesta });
                        $("#btnRegistrar").attr('disabled', false);
                        return;
                    }
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: respuesta,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    RegistrarDetalle();
                    LimpiarInputs();
                    CargarCorrelativo();
                    fechas();
                    $("#tbody_tabla_detalle_pro").empty();
                    $("#tbody_tabla_detalle_pro").html('<tr><td colspan="6" class="text-center text-muted"><i class="fas fa-calculator"></i> Complete los datos y calcule la tabla de amortización</td></tr>');
                    $("#btnRegistrar").attr('disabled', false);
                },
                error: function(xhr, status, error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'No se pudo registrar el préstamo. Intente nuevamente.' });
                    $("#btnRegistrar").attr('disabled', false);
                }
            });
        }
    });
}

function RegistrarDetalle() {
    var count = 0;
    var nro_prestamo = $("#text_nro_prestamo").val();

    var arreglo_cuota = new Array();
    var arreglo_fecha = new Array();
    var arreglo_monto = new Array();

    $("#tbody_tabla_detalle_pro tr").each(function(i, e) {
        // Filtrar la fila de TOTALES - solo procesar filas que tengan número de cuota válido
        var nroCuota = $(this).find('td').eq(0).text();
        if (nroCuota !== "TOTALES" && !isNaN(parseInt(nroCuota))) {
            arreglo_cuota.push(nroCuota);
            // Remover formato de números (comas) y obtener solo el valor numérico
            var montoText = $(this).find('td').eq(2).text().replace(/,/g, '');
            arreglo_monto.push(montoText);
            arreglo_fecha.push($(this).find('td').eq(1).text());
            count++;
        }
    });

    if (count === 0) {
        Toast.fire({
            icon: 'error',
            title: 'No hay datos en la tabla de amortización'
        });
        return;
    }

    var pdetalle_nro_cuota = arreglo_cuota.toString();
    var pdetalle_monto_cuota = arreglo_monto.toString();
    var pdetalle_fecha = arreglo_fecha.toString();

    $.ajax({
        url: "ajax/prestamo_ajax.php",
        method: "POST",
        data: {
            accion: 2,
            nro_prestamo: nro_prestamo,
            pdetalle_nro_cuota: pdetalle_nro_cuota,
            pdetalle_monto_cuota: pdetalle_monto_cuota,
            pdetalle_fecha: pdetalle_fecha
        },
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta === "ok") {
                Toast.fire({
                    icon: 'success',
                    title: 'Detalle del préstamo guardado correctamente'
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Error al guardar el detalle del préstamo'
                });
            }
        },
        error: function(xhr, status, error) {
            Toast.fire({
                icon: 'error',
                title: 'Error al guardar el detalle del préstamo'
            });
        }
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

    document.getElementById('text_fecha').value = anio + "-" + mes + "-" + d;
}

function AbrirModalRegistroCliente() {
    $("#modal_registro_cliente").modal({
        backdrop: 'static',
        keyboard: false
    });
    $("#modal_registro_cliente").modal('show');
    $("#titulo_modal_cliente").html('Registrar Cliente');
    $("#btnregistrar_cliente").html('Registrar');
    accion = 2;
    titulo_modal = "Registrar";
}

function obtenerTelefonoCompleto(campoId) {
    var numero = $(campoId).val();
    return numero ? '+505' + numero : '';
}

function CargarCorrelativo() {
    // DEBUG: Mostrar información del contexto del usuario
    console.log('🔍 UserContext completo:', window.userContext);
    console.log('🔍 Es administrador:', esAdmin());
    console.log('🔍 Sucursal del usuario:', window.userContext?.sucursal_id);
    console.log('🔍 Valor del combo sucursales:', $('#combo-sucursales').val());
    
    // Determinar qué sucursal usar
    var sucursal_id = null;
    
    // Si es administrador y hay sucursal seleccionada
    if (esAdmin()) {
        sucursal_id = $('#combo-sucursales').val();
        console.log('👨‍💼 Modo administrador - Sucursal seleccionada:', sucursal_id);
        if (!sucursal_id) {
            // Si es admin pero no ha seleccionado sucursal, no generar consecutivo aún
            $("#text_nro_prestamo").val('');
            console.log('⚠️ Administrador debe seleccionar sucursal primero');
            return;
        }
    }
    // Si es usuario común, usar su sucursal asignada
    else if (window.userContext && window.userContext.sucursal_id) {
        sucursal_id = window.userContext.sucursal_id;
        console.log('👤 Modo usuario común - Sucursal asignada:', sucursal_id);
    }
    else {
        console.log('❌ No se puede determinar el tipo de usuario o no tiene sucursal asignada');
    }
    
    // Preparar datos para enviar
    var requestData = {
        'accion': 'obtener_numero_prestamo'
    };
    
    // Agregar sucursal_id si está definida
    if (sucursal_id) {
        requestData.sucursal_id = sucursal_id;
    }
    
    // DEBUG: Mostrar datos que se van a enviar
    console.log('📤 Enviando datos al servidor:', requestData);
    
    // Usar el nuevo sistema de consecutivos por sucursal
    $.ajax({
        async: false,
        url: "ajax/consecutivos_ajax.php",
        method: "POST",
        data: requestData,
        dataType: 'json',
        success: function(respuesta) {
            if (respuesta.estado === 'exito') {
                nro_prestamo = respuesta["numero_prestamo"];
                $("#text_nro_prestamo").val(nro_prestamo);
                console.log('✅ Consecutivo por sucursal:', nro_prestamo, 'Sucursal ID:', respuesta.sucursal_id);
            } else {
                console.log('❌ Error en consecutivos, usando sistema antiguo:', respuesta.mensaje);
                console.log('🔍 Debug info:', respuesta.debug);
                // Fallback al sistema antiguo si hay error
                $.ajax({
                    async: false,
                    url: "ajax/configuracion_ajax.php",
                    method: "POST",
                    data: { 'accion': 3 },
                    dataType: 'json',
                    success: function(respuesta) {
                        nro_prestamo = respuesta["nro_prestamo"];
                        $("#text_nro_prestamo").val(nro_prestamo);
                        console.log('⚠️ Usando sistema antiguo:', nro_prestamo);
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.log('❌ Error AJAX, usando sistema antiguo:', error);
            // Fallback al sistema antiguo si hay error
            $.ajax({
                async: false,
                url: "ajax/configuracion_ajax.php",
                method: "POST",
                data: { 'accion': 3 },
                dataType: 'json',
                success: function(respuesta) {
                    nro_prestamo = respuesta["nro_prestamo"];
                    $("#text_nro_prestamo").val(nro_prestamo);
                    console.log('⚠️ Usando sistema antiguo (fallback):', nro_prestamo);
                }
            });
        }
    });
}

function CargarId_Caja() {
    $.ajax({
        async: false,
        url: "ajax/caja_ajax.php",
        method: "POST",
        data: {
            'accion': 6
        },
        dataType: 'json',
        success: function(respuesta) {
            caja_id = respuesta["caja_id"];
            $("#id_caja").val(caja_id);
        }
    });
}

function CargarEstadoCaja() {
    $.ajax({
        async: false,
        url: "ajax/caja_ajax.php",
        method: "POST",
        data: {
            'accion': 5
        },
        dataType: 'json',
        success: function(respuesta) {
            caja_estado = respuesta["caja_estado"];

            if (caja_estado == 'VIGENTE') {

            } else {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Mensaje de Error',
                    text: 'Debe aperturar una caja, se redireccionara a la ventana',
                    showConfirmButton: false,
                });

                $("#text_monto").attr('disabled', true);
                $("#text_interes").attr('disabled', true);
                $("#text_cuotas").attr('disabled', true);
                $("#select_f_pago").attr('disabled', true);
                $("#select_moneda").attr('disabled', true);
                $("#text_fecha").attr('disabled', true);
                CargarContenido('vistas/caja.php', 'content-wrapper');
            }
        }
    });
}

function LimpiarInputs() {
    $("#cliente_id").val("");
    $("#text_nombre").val("");
    $("#text_doc_dn").val("");
    $("#text_monto").val("");
    $("#text_interes").val("");
    $("#text_cuotas").val("");
    $("#select_f_pago").val("");
    $("#select_moneda").val("");
    $("#text_fecha").val("");
    $("#text_monto_por_cuota").val("");
    $("#text_interes_resultado").val("");
    $("#text_total_resultado").val("");
    
    // Limpiar completamente la tabla de amortización y restaurar mensaje inicial
    $("#tbody_tabla_detalle_pro").empty();
    $("#tbody_tabla_detalle_pro").html('<tr><td colspan="6" class="text-center text-muted"><i class="fas fa-calculator"></i> Complete los datos y calcule para ver la tabla de amortización</td></tr>');
    
    $("#text_dni").focus();
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
};

  </script>