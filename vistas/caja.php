  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h1 class="m-0">Caja</h1>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                      <li class="breadcrumb-item active">Caja</li>
                  </ol>
              </div><!-- /.col -->
          </div><!-- /.row -->
      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content pb-2">
      <div class="container-fluid">
          
          <!-- Resumen del Día Actual -->
          <div class="row mb-3" id="resumen-dia-actual">
              <div class="col-lg-3 col-6">
                  <div class="small-box bg-info">
                      <div class="inner">
                          <h3 id="resumen-prestamos-count">0</h3>
                          <p>Préstamos del Día</p>
                          <small id="resumen-prestamos-monto">$0.00</small>
                      </div>
                      <div class="icon"><i class="fas fa-hand-holding-usd"></i></div>
                  </div>
              </div>
              <div class="col-lg-3 col-6">
                  <div class="small-box bg-success">
                      <div class="inner">
                          <h3 id="resumen-pagos-count">0</h3>
                          <p>Pagos Recibidos</p>
                          <small id="resumen-pagos-monto">$0.00</small>
                      </div>
                      <div class="icon"><i class="fas fa-coins"></i></div>
                  </div>
              </div>
              <div class="col-lg-3 col-6">
                  <div class="small-box bg-warning">
                      <div class="inner">
                          <h3 id="resumen-ingresos-count">0</h3>
                          <p>Ingresos Extra</p>
                          <small id="resumen-ingresos-monto">$0.00</small>
                      </div>
                      <div class="icon"><i class="fas fa-plus-circle"></i></div>
                  </div>
              </div>
              <div class="col-lg-3 col-6">
                  <div class="small-box bg-danger">
                      <div class="inner">
                          <h3 id="resumen-egresos-count">0</h3>
                          <p>Egresos del Día</p>
                          <small id="resumen-egresos-monto">$0.00</small>
                      </div>
                      <div class="icon"><i class="fas fa-minus-circle"></i></div>
                  </div>
              </div>
          </div>

          <!-- Estado de Caja y Acciones -->
          <div class="row mb-3">
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header bg-info">
                          <h3 class="card-title">
                              <i class="fas fa-cash-register"></i> Estado de Caja Actual
                              <small class="text-light" id="info-sucursal-header"></small>
                          </h3>
                      </div>
                      <div class="card-body">
                          <div class="row text-center">
                              <div class="col-4">
                                  <strong>Monto Inicial:</strong><br>
                                  <span class="h4 text-primary" id="caja-monto-inicial">$0.00</span>
                              </div>
                              <div class="col-4">
                                  <strong>Saldo Actual:</strong><br>
                                  <span class="h4 text-success" id="caja-saldo-actual">$0.00</span>
                              </div>
                              <div class="col-4">
                                  <strong>Estado:</strong><br>
                                  <span class="badge badge-secondary" id="caja-estado">Sin Caja</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-4">
                  <div class="card">
                      <div class="card-header bg-secondary">
                          <h3 class="card-title">
                              <i class="fas fa-tools"></i> Acciones Rápidas
                          </h3>
                      </div>
                      <div class="card-body p-2">
                          <button type="button" class="btn btn-warning btn-sm btn-block mb-2" id="btnCierreDiaRapido">
                              <i class="fas fa-calendar-check"></i> Cierre de Día
                          </button>
                          <button type="button" class="btn btn-info btn-sm btn-block mb-2" onclick="CargarContenido('vistas/dashboard_caja.php','content-wrapper')">
                              <i class="fas fa-tachometer-alt"></i> Dashboard
                          </button>
                          <button type="button" class="btn btn-secondary btn-sm btn-block" id="btnActualizarDatos">
                              <i class="fas fa-sync"></i> Actualizar
                          </button>
                      </div>
                  </div>
              </div>
          </div>

          <div class="row p-0 m-0">
              <div class="col-md-12">
                  <div class="card card-info card-outline shadow ">
                      <div class="card-header bg-gradient-info">
                          <h3 class="card-title">Aperturar de Caja</h3>
                          <button class="btn btn-info btn-sm float-right" id="abrirmodal_caja"><i class="fas fa-plus"></i>
                              Aperturar</button>
                      </div>
                      <div class=" card-body">
                          <!-- <div class="row align-items-end">
                              <div class="col-md-6 d-flex">
                                  <div class="form-group flex-grow-1 me-2">
                                      <label for="">
                                          <span class="small">Cliente:</span>
                                      </label>
                                      <select class="form-control js-example-basic-single" id="select_clientes"> </select>
                                      <div class="invalid-feedback">Seleccione un Cliente</div>
                                  </div>
                                  <div class="form-group m-0">
                                      <a class="btn btn-primary" id="btnFiltrar">Buscar</a>
                                  </div>
                              </div>
                          </div><br> -->
                          <div class="col-12 table-responsive">
                              <table id="tbl_caja" class="table display table-hover text-nowrap compact  w-100  rounded">
                                  <thead class="bg-gradient-info text-white">
                                      <tr>
                                          <th>Id caja</th>
                                          <th>Monto Ini.</th>
                                          <th>Ingreso</th>
                                          <th>Egreso</th>
                                          <th>Monto Prestamo</th>
                                          <th>F. Apertura</th>
                                          <th>F. Cierre</th>
                                          <th>Cant. Prest</th>
                                          <th>Monto Total</th>
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

          </div>

      </div><!-- /.container-fluid -->
  </div>
  <!-- /.content -->

  <!-- Modal abrir caja MEJORADO -->
  <div class="modal fade" id="modal_abrir_caja" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-success py-2 align-items-center">
                  <h5 class="modal-title text-white" id="titulo_modal_cliente">
                      <i class="fas fa-unlock"></i> Apertura de Caja Avanzada
                  </h5>
                  <button type="button" class="close text-white border-0 fs-5" id="btncerrarmodal_cliente" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <!-- Información de Permisos -->
                  <div class="alert alert-info" id="info-permisos" style="display: none;">
                      <h6><i class="fas fa-shield-alt"></i> Estado de Permisos</h6>
                      <div id="permisos-details"></div>
                  </div>
                  
                  <form class="needs-validation" novalidate id="form-apertura-caja">
                      <div class="row">
                          <div class="col-lg-6">
                              <div class="form-group mb-3">
                                  <label for="text_descripcion" class="form-label">
                                      <i class="fas fa-tag"></i> Descripción de la Caja
                                  </label>
                                  <input type="text" 
                                         class="form-control form-control-sm" 
                                         id="text_descripcion" 
                                         name="text_descripcion" 
                                         value="Apertura de Caja"
                                         required>
                                  <div class="invalid-feedback">Debe ingresar una descripción</div>
                              </div>
                          </div>

                          <div class="col-lg-6">
                              <div class="form-group mb-3">
                                  <label for="text_monto_ini" class="form-label">
                                      <i class="fas fa-dollar-sign"></i> Monto Inicial
                                  </label>
                                  <input type="number" 
                                         class="form-control form-control-sm" 
                                         id="text_monto_ini" 
                                         name="text_monto_ini" 
                                         placeholder="0.00" 
                                         step="0.01" 
                                         min="0"
                                         required>
                                  <small class="text-muted">
                                      Límite máximo: <span id="limite-usuario">Verificando...</span>
                                  </small>
                                  <div class="invalid-feedback">Debe ingresar un monto válido</div>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-lg-6">
                              <div class="form-group mb-3">
                                  <label for="select_sucursal" class="form-label">
                                      <i class="fas fa-building"></i> Sucursal
                                  </label>
                                  <select class="form-control form-control-sm" id="select_sucursal" name="select_sucursal">
                                      <option value="">Seleccionar sucursal...</option>
                                  </select>
                                  <small class="text-muted">Seleccione la sucursal para esta caja</small>
                              </div>
                          </div>

                          <div class="col-lg-6">
                              <div class="form-group mb-3">
                                  <label for="select_tipo_caja" class="form-label">
                                      <i class="fas fa-cash-register"></i> Tipo de Caja
                                  </label>
                                  <select class="form-control form-control-sm" id="select_tipo_caja" name="select_tipo_caja">
                                      <option value="principal">Caja Principal</option>
                                      <option value="secundaria">Caja Secundaria</option>
                                      <option value="temporal">Caja Temporal</option>
                                  </select>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-3">
                                  <label for="text_observaciones" class="form-label">
                                      <i class="fas fa-comment"></i> Observaciones
                                  </label>
                                  <textarea class="form-control form-control-sm" 
                                            id="text_observaciones" 
                                            name="text_observaciones" 
                                            rows="3" 
                                            placeholder="Observaciones adicionales sobre la apertura..."></textarea>
                              </div>
                          </div>
                      </div>

                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-check">
                                  <input type="checkbox" 
                                         class="form-check-input" 
                                         id="check_validacion_fisica">
                                  <label class="form-check-label" for="check_validacion_fisica">
                                      <i class="fas fa-calculator"></i> He realizado el conteo físico del dinero
                                  </label>
                                  <small class="form-text text-muted d-block">
                                      Marque esta casilla si ha contado físicamente el dinero inicial
                                  </small>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_cliente">
                      <i class="fas fa-times"></i> Cancelar
                  </button>
                  <button class="btn btn-success btn-sm" id="btnregistrar_caja">
                      <i class="fas fa-unlock"></i> <span id="btn-text">Abrir Caja</span>
                  </button>
              </div>
          </div>
      </div>
  </div>

  <!-- Modal CERRA caja -->
  <div class="modal fade" id="modal_cerrar_caja" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog " role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cerrar">Cerrar Caja</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_caja_cierre" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form class="needs-validation" novalidate>
                      <div class="row">
                          <div class="col-lg-12">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Apertura
                                  </label>
                                  <input type="text" id="caja_id" hidden>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_aper" name="text_monto_aper" disabled>

                              </div>
                          </div>

                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Prestamo
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_prestamo" name="text_monto_prestamo" placeholder="Monto Prestamo" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cantidad de Prestamo
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cant_prestamo" name="text_cant_prestamo" placeholder="Cant.Prestamo" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Ingreso
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_ingreso" placeholder="Monto Ingreso" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cant. Ingresos
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cant_ingreso" placeholder="Cant. Ingresos" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Monto Egreso
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_egreso" placeholder="Monto Egreso" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Cant. Egresos
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_cant_egreso" placeholder="Cant. Egresos" disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> <b>Monto Total</b>
                                  </label>
                                  <input type="text" class=" form-control form-control-sm" id="text_monto_total" placeholder="Monto Total" required disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>
                          <!-- <div class="col-lg-6">
                              <div class="form-group mb-2">
                              <label for="">&nbsp;</label><br>
                            <button class="btn btn-success btn-sm " onmousemove="Sumar();" onclick="Sumar();"><i
                                    class="fas fa-plus"></i> Calcular</button>

                              </div>
                          </div> -->


                          <div class="col-lg-6">
                              <div class="form-group mb-2">
                                  <label for="" class="">
                                      <span class="small"> Interes de Prestamo
                                  </label>

                                  <input type="text" class=" form-control form-control-sm" id="text_interes" placeholder="Monto Total con interes" required disabled>
                                  <div class="invalid-feedback">Debe ingresar un monto</div>

                              </div>
                          </div>

                          <!-- <fieldset class="border p-2" style=" border: 1px solid #337ab7 !important;">
                              <legend class="float-none w-auto p-2" style=" font-size: 1.2em !important; font-weight: bold !important; ">Datos del Prestamo</legend>
                              <div class="col-lg-6">
                                  <div class="form-group mb-2">
                                      <label for="" class="">
                                          <span class="small"> Monto Total
                                      </label>
                                      <input type="text" class=" form-control form-control-sm" id="text_cant_egreso" placeholder="Monto Total" required>
                                      <div class="invalid-feedback">Debe ingresar un monto</div>

                                  </div>
                              </div>
                          </fieldset> -->




                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_caja_cierre">Cerrar</button>
                  <button class="btn btn-primary btn-sm btn-activa" id="btnCerrar_caja">Cerrar Caja</button>
                  <!-- <a class="btn btn-primary btn-sm"  id=" btnregistrar_caja">Cerrar Caja</a> -->
                  <!-- <div class="form-group m-0"> -->
                  <!-- <a class="btn btn-primary btn-sm"  id=" btnregistrar_caja">Registrar</a> -->
                  <!-- </div> -->

              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->


  <!-- Modal VER PRESTAMOS Y MOVIMIENTOS DE caja -->
  <div class="modal fade" id="modal_registros_caja" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_cerrar">Ver Registros de la Caja</h5>
                  <button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_caja_cierre" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="col-12 table-responsive">
                      <table id="tbl_resgitros_caja" class="table display table-hover text-nowrap compact  w-100  rounded">
                          <thead class="bg-gradient-info text-white">
                              <tr>
                                  <th>#Prestamo</th>
                                  <th>id cli</th>
                                  <th>Cliente</th>
                                  <th>Monto</th>
                                  <th>Interes</th>
                                  <th>Monto Total</th>
                                  <th>Fecha</th>
                                  <th>caja id</th>
                                  <th>Estado</th>
                              </tr>
                          </thead>
                          <tbody class="small text left">

                          </tbody>
                          <tfoot>
                              <tr>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th>Total:</th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                              </tr>
                          </tfoot>
                      </table>

                  </div>
                  <br>

                  <div class="col-12 table-responsive">
                      <table id="tbl_resgitros_movi" class="table display table-hover text-nowrap compact  w-100  rounded">
                          <thead class="bg-gradient-info text-white">
                              <tr>
                                  <th>Tipo</th>
                                  <th>Descripcion</th>
                                  <th>Monto</th>
                                  <th>Fecha</th>
                                  <th>caja id</th>
                              </tr>
                          </thead>
                          <tbody class="small text left">

                          </tbody>
                          
                      </table>

                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_caja_cierre">Cerrar</button>
                  <!-- <button class="btn btn-primary btn-sm btn-activa" id="btnCerrar_caja">Cerrar Caja</button> -->


              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal -->

  <!-- Modal Detalles del Saldo Total -->
  <div class="modal fade" id="modal_saldo_total" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
              <div class="modal-header bg-info">
                  <h4 class="modal-title text-white">
                      <i class="fas fa-calculator"></i> Detalles del Saldo Total
                  </h4>
                  <button type="button" class="close text-white" data-dismiss="modal">
                      <span>&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <!-- Tarjetas de resumen -->
                  <div class="row mb-4">
                      <div class="col-md-3">
                          <div class="card text-white bg-success">
                              <div class="card-body text-center">
                                  <i class="fas fa-arrow-up fa-2x mb-2"></i>
                                  <h5>Ingresos Hoy</h5>
                                  <h4 id="ingresos_hoy">0,00 US$</h4>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="card text-white bg-danger">
                              <div class="card-body text-center">
                                  <i class="fas fa-arrow-down fa-2x mb-2"></i>
                                  <h5>Egresos Hoy</h5>
                                  <h4 id="egresos_hoy">0,00 US$</h4>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="card text-white bg-warning">
                              <div class="card-body text-center">
                                  <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
                                  <h5>Préstamos Otorgados</h5>
                                  <h4 id="prestamos_otorgados">0,00 US$</h4>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-3">
                          <div class="card text-white bg-primary">
                              <div class="card-body text-center">
                                  <i class="fas fa-piggy-bank fa-2x mb-2"></i>
                                  <h5>Saldo Inicial Total</h5>
                                  <h4 id="saldo_inicial_total">0,00 US$</h4>
                              </div>
                          </div>
                      </div>
                  </div>

                  <!-- Tabla de resumen por caja -->
                  <div class="card">
                      <div class="card-header">
                          <h5><i class="fas fa-table"></i> Resumen por Caja</h5>
                      </div>
                      <div class="card-body">
                          <div class="table-responsive">
                              <table class="table table-striped table-hover" id="tabla_resumen_cajas">
                                  <thead class="bg-info text-white">
                                      <tr>
                                          <th>Caja</th>
                                          <th>Saldo Inicial</th>
                                          <th>Movimientos</th>
                                          <th>Saldo Actual</th>
                                          <th>Estado</th>
                                          <th>Última Apertura</th>
                                          <th>Acciones</th>
                                      </tr>
                                  </thead>
                                  <tbody id="tbody_resumen_cajas">
                                      <tr>
                                          <td colspan="7" class="text-center">
                                              <i class="fas fa-spinner fa-spin"></i> Cargando datos...
                                          </td>
                                      </tr>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">
                      <i class="fas fa-times"></i> Cerrar
                  </button>
                  <button type="button" class="btn btn-success" id="btn_exportar_resumen">
                      <i class="fas fa-download"></i> Exportar Reporte
                  </button>
              </div>
          </div>
      </div>
  </div>

  <!-- Modal Configuración de Cajas por Sucursal -->
  <div class="modal fade" id="modal_config_sucursales" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header bg-info">
                  <h4 class="modal-title text-white">
                      <i class="fas fa-cogs"></i> Configuración de Cajas por Sucursal
                  </h4>
                  <button type="button" class="close text-white" data-dismiss="modal">
                      <span>&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-4">
                          <div class="card">
                              <div class="card-header">
                                  <h5>Agregar Nueva Caja</h5>
                              </div>
                              <div class="card-body">
                                  <form id="form-nueva-caja">
                                      <div class="form-group">
                                          <label>Sucursal</label>
                                          <select class="form-control" id="nueva-caja-sucursal" required>
                                              <option value="">Seleccionar...</option>
                                          </select>
                                      </div>
                                      <div class="form-group">
                                          <label>Nombre de Caja</label>
                                          <input type="text" class="form-control" id="nueva-caja-nombre" 
                                                 placeholder="Ej: Caja Principal" required>
                                      </div>
                                      <div class="form-group">
                                          <label>Código</label>
                                          <input type="text" class="form-control" id="nueva-caja-codigo" 
                                                 placeholder="Ej: CP-001" required>
                                      </div>
                                      <div class="form-group">
                                          <label>Tipo</label>
                                          <select class="form-control" id="nueva-caja-tipo">
                                              <option value="principal">Principal</option>
                                              <option value="secundaria">Secundaria</option>
                                              <option value="temporal">Temporal</option>
                                          </select>
                                      </div>
                                      <div class="form-group">
                                          <label>Ubicación Física</label>
                                          <input type="text" class="form-control" id="nueva-caja-ubicacion" 
                                                 placeholder="Ej: Planta baja, recepción">
                                      </div>
                                      <button type="submit" class="btn btn-primary">
                                          <i class="fas fa-plus"></i> Agregar Caja
                                      </button>
                                  </form>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-8">
                          <div class="card">
                              <div class="card-header">
                                  <h5>Cajas Configuradas</h5>
                              </div>
                              <div class="card-body">
                                  <table class="table table-sm table-hover" id="tabla-cajas-sucursales">
                                      <thead>
                                          <tr>
                                              <th>Sucursal</th>
                                              <th>Nombre</th>
                                              <th>Código</th>
                                              <th>Tipo</th>
                                              <th>Estado</th>
                                              <th>Acciones</th>
                                          </tr>
                                      </thead>
                                      <tbody id="tbody-cajas-sucursales">
                                          <tr>
                                              <td colspan="6" class="text-center">
                                                  <i class="fas fa-spinner fa-spin"></i> Cargando...
                                              </td>
                                          </tr>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div>
      </div>
  </div>

  <script>
      var accion;
      var tbl_caja, tbl_resgitros_caja, tbl_resgitros_movi;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });
      $(document).ready(function() {

          $('.js-example-basic-single').select2();

          // NUEVO: Funcionalidad para botones de acción
          $('#btnConteoFisico').on('click', function() {
              Swal.fire({
                  icon: 'info',
                  title: 'Conteo Físico',
                  text: 'Funcionalidad de conteo físico por implementar.',
                  showConfirmButton: true
              });
          });

          $('#btnGenerarReporteCaja').on('click', function() {
              Swal.fire({
                  icon: 'info',
                  title: 'Generar Reporte',
                  text: 'Funcionalidad de generación de reporte por implementar.',
                  showConfirmButton: true
              });
          });

          /*===================================================================*/
          // FILTRAR CAJA POR CLIENTE AL CAMBIAR SELECTOR O HACER CLIC EN BUSCAR
          /*===================================================================*/
          $("#btnFiltrar").on('click', function() {
              if ($("#select_clientes").val() == '') {
                  Toast.fire({
                      icon: 'error',
                      title: ' Debe Seleccionar un cliente'
                  })
                  $("#select_clientes").focus();

              } else {
                  tbl_caja.ajax.reload(); // Recargar la tabla de caja
              }
          });

          $('#select_clientes').on('change', function() {
              tbl_caja.ajax.reload(); // Recargar la tabla de caja al cambiar el cliente
          });

          /*===================================================================*/
          // INICIAMOS EL DATATABLE
          /*===================================================================*/
          tbl_caja = $("#tbl_caja").DataTable({
              responsive: true,

              dom: 'Bfrtip',
              select: true,
              buttons: [{
                      "extend": 'excelHtml5',
                      "title": 'Arqueo de Caja',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
                      },
                      "text": '<i class="fa fa-file-excel"></i>',
                      "titleAttr": 'Exportar a Excel'
                  },
                  {
                      "extend": 'print',
                      "text": '<i class="fa fa-print"></i> ',
                      "titleAttr": 'Imprimir',
                      "exportOptions": {
                          'columns': [1, 2, 3, 4, 5, 6, 7, 8, 9]
                      },
                      "download": 'open'
                  },

                  'pageLength',
              ],
              ajax: {
                  url: "ajax/caja_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 1

                  }, //LISTAR 
                  success: function(response) {
                      console.log('[tbl_caja] Respuesta AJAX (accion 1):', response);
                  },
                  error: function(xhr, status, error) {
                      console.error('[tbl_caja] Error AJAX (accion 1):', error);
                      console.error('[tbl_caja] Respuesta del servidor:', xhr.responseText);
                  }
              },
              columnDefs: [{
                      targets: 0,
                      visible: false

                  },
                  {
                      targets: 9,
                      //sortable: false,
                      createdCell: function(td, cellData, rowData, row, col) {

                          if (rowData[9] == 'VIGENTE') {
                              $(td).html("<span class='badge badge-success'>VIGENTE</span>")
                          } else {
                              $(td).html("<span class='badge badge-danger'>CERRADO</span>")
                          }

                      }
                  }, {
                      targets: 10, //columna 2
                      sortable: false, //no ordene
                      render: function(td, cellData, rowData, row, col) {

                          if (rowData[9] == 'VIGENTE') {
                              return "<center>" +
                                  "<span class='btnCerrarCaja text-warning px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Cerrar Caja'> " +
                                  "<i class='fas fa-lock fs-6'></i> " +
                                  "</span> " +
                                  "<span class='text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir'> " +
                                  "<i class='fa fa-print fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Registros de Caja'> " +
                                  "<i class='fa fa-eye fs-6'> </i> " +
                                  "</span>" +
                                  "</center>"
                          } else { //pendiente
                              return "<center>" +
                                  "<span class='text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' > " +
                                  "<i class='fas fa-lock fs-6'></i> " +
                                  "</span> " +
                                  "<span class='ImprimirCaja text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Cierre de Caja'> " +
                                  "<i class='fa fa-print fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='EnviarCorreo text-warning px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Enviar por Correo'> " +
                                  "<i class='fas fa-envelope fs-6'> </i> " +
                                  "</span>" +
                                  "<span class='btnVerRegistrosC text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Ver Registros de Caja ' > " +
                                  "<i class='fa fa-eye fs-6'> </i> " +
                                  "</span>" +
                                  "</center>"

                          }
                      }
                  }

              ],
              "order": [
                  [0, 'desc']
              ],
              lengthMenu: [0, 5, 10, 15, 20, 50],
              "pageLength": 10,
              "language": idioma_espanol,
              // Callback para aplicar estilos a las cabeceras después de la inicialización de DataTables
              "initComplete": function(settings, json) {
                  $('#tbl_caja thead th').css({
                      'background-color': '#17a2b8',
                      'color': '#ffffff',
                      'background-image': 'none' // Eliminar iconos de ordenamiento si los hay
                  });
              }

          });

          /*===================================================================*/
          // ABRIR MODAL caja
          /*===================================================================*/
          $("#abrirmodal_caja").on('click', function() {
              AbrirModalAbrirCaja();
              //AbrirModalAbrirCerrarCaja()

          })



          /*===================================================================*/
          // ABRIR MODAL CERRAR CAJA
          /*===================================================================*/
          // CERRAR CAJA - FUNCIONALIDAD MEJORADA
          /*===================================================================*/
          // Este evento ahora es manejado por la función configurarEventosNuevos()
          // en el JavaScript mejorado al final del archivo

          /*===================================================================*/
          // ABRIR MODAL CERRAR CAJA
          /*===================================================================*/
          $('#tbl_caja').on('click', '.btnVerRegistrosC', function() { //class foto tiene que ir en el boton
              //accion = 7;
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data();
              } else {
                  var data = tbl_caja.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
              }


              var caja_id = data[0]
             // console.log(caja_id);

              AbrirModalVerRegistrosPorCaja();
              Traer_RegistrosporIDCaja(caja_id)
              Traer_MovimientosporIDCaja(caja_id);

          });



          /*===================================================================*/
          //EVENTO QUE GUARDAAR APERTURA CAJA
          /*===================================================================*/

          document.getElementById("btnregistrar_caja").addEventListener("click", function() {


              var monto = $('#text_monto_ini').val();


              if (monto == "") {
                  Toast.fire({
                      icon: 'warning',
                      title: 'Digitar un monto para aperturar la caja'

                  });
                  //  $('#btnregistrar_caja').show();
                  // document.getElementsByClassName("btn-activa")[0].focus();

              } else {
                  // console.log("Listo para registrar el producto")
                  Swal.fire({
                      title: 'Esta seguro de Apertura Caja',
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                      confirmButtonText: 'Si',
                      cancelButtonText: 'Cancelar',
                  }).then((result) => {

                      if (result.isConfirmed) {

                          var datos = new FormData();

                          datos.append("accion", accion);
                          datos.append("caja_descripcion", $("#text_descripcion").val());
                          datos.append("caja_monto_inicial", $("#text_monto_ini").val());

                          $.ajax({
                              url: "ajax/caja_ajax.php",
                              method: "POST",
                              data: datos, //enviamos lo de la variable datos
                              cache: false,
                              contentType: false,
                              processData: false,
                              dataType: 'json',
                              success: function(respuesta) {
                                  // console.log(respuesta);

                                  if (respuesta > 0) {
                                      if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                          Toast.fire({
                                              icon: 'success',
                                              title: 'Caja Aperturada'
                                          });

                                          tbl_caja.ajax.reload(); //recargamos el datatable
                                          $("#modal_abrir_caja").modal('hide');
                                          $("#text_monto_ini").val("");

                                      } else {
                                          Toast.fire({
                                              icon: 'warning',
                                              title: 'Ya tienes una caja Aperturada'
                                          });
                                          $("#text_monto_ini").val("");
                                      }

                                  } else {
                                      Toast.fire({
                                          icon: 'error',
                                          title: 'No se pudo Aperturar la Caja'

                                      });
                                  }


                              }
                          });

                      }
                  })
              }


          });



          /*===================================================================*/
          //EVENTO QUE CIERRA LA CAJA
          /*===================================================================*/
          $("#btnCerrar_caja").on('click', function() {

              Swal.fire({
                  title: '¿Está seguro de Cerrar Caja?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Sí',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {

                  if (result.isConfirmed) {

                      var caja_monto_ingreso = $("#text_monto_ingreso").val();
                      var caja_prestamo = $("#text_monto_prestamo").val();
                      var caja__monto_egreso = $("#text_monto_egreso").val();
                      var caja_monto_total = $("#text_monto_total").val();
                      var caja_count_prestamo = $("#text_cant_prestamo").val();
                      var caja_count_ingreso = $("#text_cant_ingreso").val();
                      var caja_count_egreso = $("#text_cant_egreso").val();
                      var caja_interes = $("#text_interes").val();

                      var datos = new FormData();
                      datos.append("accion", 4); //PARA REGISTRAR EL  CERRAR LA CAJA
                      datos.append("caja_monto_ingreso", caja_monto_ingreso);
                      datos.append("caja_prestamo", caja_prestamo);
                      datos.append("caja__monto_egreso", caja__monto_egreso);
                      datos.append("caja_monto_total", caja_monto_total);
                      datos.append("caja_count_prestamo", caja_count_prestamo);
                      datos.append("caja_count_ingreso", caja_count_ingreso);
                      datos.append("caja_count_egreso", caja_count_egreso);
                      datos.append("caja_interes", caja_interes);

                      $.ajax({
                          url: "ajax/caja_ajax.php",
                          method: "POST",
                          data: datos,
                          cache: false,
                          contentType: false,
                          processData: false,
                          dataType: 'json',
                          success: function(respuesta) {

                              if (respuesta == 1) { //validamos la respuesta del procedure si retorna 1 o 2
                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Caja Cerrada Correctamente'
                                  });
                                  $("#modal_cerrar_caja").modal('hide');
                                  tbl_caja.ajax.reload(); //recargamos la tabla
                                  $("#caja_id").val("");
                                  $("#text_monto_ingreso").val("");
                                  $("#text_monto_prestamo").val("");
                                  $("#text_monto_egreso").val("");
                                  $("#text_monto_total").val("");
                                  $("#text_cant_prestamo").val("");
                                  $("#text_cant_ingreso").val("");
                                  $("#text_cant_egreso").val("");
                                  $("#text_interes").val("");
                              } else if (respuesta == 2) {
                                  Toast.fire({
                                      icon: 'warning',
                                      title: 'Tienes Préstamos Aprobados que no han Finalizado'
                                  });
                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al cerrar la caja'
                                  });
                              }

                          }
                      });

                  }
              })

          })



          /********************************************************************
          		IMPRIMIR COMPROBANTE
          ********************************************************************/
          $('#tbl_caja').on('click', '.ImprimirCaja', function() { //class foto tiene que ir en el boton
              var data = tbl_caja.row($(this).parents('tr')).data(); //tama単o de escritorio
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data(); //para celular y usas el responsive datatable

              }

              window.open("MPDF/reporte_arqueocaja_mejorado.php?codigo=" + parseInt(data[0]) + "#zoom=120", "Arqueo de Caja", "scrollbards=NO");





          });



          /********************************************************************
          		ENVIAR POR CORREO
          ********************************************************************/
          $('#tbl_caja').on('click', '.EnviarCorreo', function() { //class foto tiene que ir en el boton
              var data = tbl_caja.row($(this).parents('tr')).data(); //tama単o de escritorio
              if (tbl_caja.row(this).child.isShown()) {
                  var data = tbl_caja.row(this).data(); //para celular y usas el responsive datatable

              }

              Swal.fire({
                  title: 'Esta seguro de Enviar el correo?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#8FCE00',
                  cancelButtonColor: '#d50',
                  confirmButtonText: 'Si',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.open("MPDF/reporte_arqueocaja_Email.php?codigo=" + parseInt(data[0]) + "#zoom=120", "Arqueo de Caja", "scrollbards=NO");
                      Toast.fire({
                          icon: 'success',
                          title: 'Correo enviado correctamente'
                      });


                  }

              })

          });


          /* ======================================================================================
           EVENTO QUE LIMPIA EL INPUT  AL CERRAR LA VENTANA MODAL
          =========================================================================================*/
          $("#btncerrarmodal_caja_cierre, #btncerrar_caja_cierre").on('click', function() {
              $("#text_monto_aper").val("");
              $("#text_monto_prestamo").val("");
              $("#text_cant_prestamo").val("");
              $("#text_monto_ingreso").val("");
              $("#text_cant_ingreso").val("");
              $("#text_monto_egreso").val("");
              $("#text_cant_egreso").val("");
              $("#text_monto_total").val("");
              $("#text_interes").val("");
          })


          /*===================================================================*/
          //EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
          /*===================================================================*/
          document.getElementById("btncerrar_caja_cierre").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })
          document.getElementById("btncerrarmodal_caja_cierre").addEventListener("click", function() {
              $(".needs-validation").removeClass("was-validated");
          })





      }) // FIN DOCUMENT READY


      // NUEVO: Cargar estadísticas rápidas del día
      function cargarEstadisticasRapidas() {
          $.ajax({
              url: "ajax/caja_ajax.php",
              method: "POST",
              dataType: "json",
              data: { accion: 9 }, // Acción para obtener estadísticas rápidas
              success: function(response) {
                  console.log('[Estadisticas Rapidas] Respuesta AJAX:', response);
                  if (response && response.success) {
                      $('#aperturas_hoy').text(response.aperturas_hoy);
                      $('#cierres_hoy').text(response.cierres_hoy);
                      // Implementar lógica para eficiencia operacional si los datos están disponibles
                      // Por ahora, se mantendrá en 0%
                  } else {
                      console.warn('[Estadisticas Rapidas] No se pudieron cargar las estadísticas.', response.message);
                      $('#aperturas_hoy').text('--');
                      $('#cierres_hoy').text('--');
                  }
              },
              error: function(xhr, status, error) {
                  console.error('[Estadisticas Rapidas] Error AJAX:', error);
                  console.error('[Estadisticas Rapidas] Respuesta del servidor:', xhr.responseText);
                  $('#aperturas_hoy').text('Error');
                  $('#cierres_hoy').text('Error');
              }
          });
      }

      // Llamar a la función al cargar la página
      cargarEstadisticasRapidas();

      // FUNCIONES

      /********************************************************************
            ABRIR MODAL abrir caja
      ********************************************************************/
      function AbrirModalAbrirCaja() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_abrir_caja").modal({
              backdrop: 'static',
              keyboard: false
          });
          
          // CARGAR SUCURSALES ANTES DE MOSTRAR EL MODAL
          cargarSucursalesModal();
          
          $("#modal_abrir_caja").modal('show'); //abrimos el modal

          $("#text_descripcion").val('Apertura de Caja');
          $("#text_monto_ini").focus();
          accion = 2;
      }
      
      /********************************************************************
            CARGAR SUCURSALES EN EL MODAL
      ********************************************************************/
      function cargarSucursalesModal() {
          console.log('[Caja] Cargando sucursales para modal de apertura...');
          
          // Mostrar indicador de carga
          $("#select_sucursal").html('<option value="">🔄 Cargando sucursales...</option>');
          
          $.ajax({
              url: "ajax/aprobacion_ajax.php",
              type: "GET",
              data: { accion: 'listar_sucursales' },
              dataType: 'json',
              success: function(response) {
                  console.log('[Caja] Respuesta de sucursales:', response);
                  
                  let opciones = '<option value="">-- Seleccionar sucursal --</option>';
                  
                  if (Array.isArray(response) && response.length > 0) {
                      response.forEach(function(sucursal) {
                          // Usar estructura real de la tabla sucursales
                          const sucursalId = sucursal.sucursal_id || sucursal.id;
                          const textoDescriptivo = sucursal.texto_descriptivo || 
                                                 sucursal.texto_completo || 
                                                 sucursal.sucursal_nombre ||
                                                 sucursal.nombre;
                          
                          if (sucursalId && textoDescriptivo) {
                              opciones += `<option value="${sucursalId}">${textoDescriptivo}</option>`;
                          }
                      });
                      console.log(`[Caja] ✅ Cargadas ${response.length} sucursales exitosamente`);
                  } else {
                      opciones += '<option value="">No hay sucursales disponibles</option>';
                      console.warn('[Caja] No se encontraron sucursales');
                  }
                  
                  $("#select_sucursal").html(opciones);
              },
              error: function(xhr, status, error) {
                  console.error('[Caja] Error al cargar sucursales:', error);
                  console.error('[Caja] Respuesta del servidor:', xhr.responseText);
                  $("#select_sucursal").html('<option value="">Error al cargar sucursales</option>');
                  
                  // Mostrar notificación de error
                  if (typeof Swal !== 'undefined') {
                      Swal.fire({
                          icon: 'error',
                          title: 'Error',
                          text: 'No se pudieron cargar las sucursales. Verifique su conexión.',
                          timer: 3000,
                          showConfirmButton: false
                      });
                  }
              }
          });
      }


      /********************************************************************
            ABRIR MODAL  VER PRESTAMOS POR CAJA 
      ********************************************************************/
      function AbrirModalVerRegistrosPorCaja() {
          $("#modal_registros_caja").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_registros_caja").modal('show'); //abrimos el modal

          $("#text_descripcion").val('Ver Registros de Caja');

          // accion = 2;

      }


      /********************************************************************
            ABRIR MODAL CERRAR CAJA 
      ********************************************************************/
      function AbrirModalAbrirCerrarCaja() {
          //para que no se nos salga del modal haciendo click a los costados
          $("#modal_cerrar_caja").modal({
              backdrop: 'static',
              keyboard: false
          });
          $("#modal_cerrar_caja").modal('show'); //abrimos el modal
          //suma();

      }


      /*===================================================================*/
      //FUNCION PARA CARGAR DATOS CANTIDADES
      /*===================================================================*/
      function CargarDatosCierreCaja() {
          $.ajax({
              async: false,
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: {
                  'accion': 3
              },
              dataType: 'json',
              success: function(respuesta) {

                  // console.log(respuesta);


                  monto_inicial_caja = respuesta["monto_inicial_caja"];
                   suma_prestamo_capital = respuesta["suma_prestamo_capital"]; 
                  suma_total = respuesta["suma_total"];
                  cant_prestamo = respuesta["cant_prestamo"];
                  cant_ingresos = respuesta["cant_ingresos"];
                  suma_ingresos = respuesta["suma_ingresos"];
                  suma_egresos = respuesta["suma_egresos"];
                  cant_egresos = respuesta["cant_egresos"];
                  suma_prestamo_interes = respuesta["suma_prestamo_interes"];

                  $("#text_monto_aper").val(monto_inicial_caja);
                  $("#text_monto_prestamo").val(suma_prestamo_capital); //SETEAMOS EN LOS TEXTBOX
                  //$("#text_monto_prestamo").val(suma_total);
                  $("#text_cant_prestamo").val(cant_prestamo);
                  $("#text_monto_ingreso").val(suma_ingresos);
                  $("#text_cant_ingreso").val(cant_ingresos);
                  $("#text_monto_egreso").val(suma_egresos);
                  $("#text_cant_egreso").val(cant_egresos);
                  $("#text_interes").val(suma_prestamo_interes);


              }
          });
      }



      function Sumar() {
          //var suma = 0;
          monto_inicial_caja = $("#text_monto_aper").val();
          suma_prestamo_capital = $("#text_monto_prestamo").val();
          suma_ingresos = $("#text_monto_ingreso").val();
          suma_egresos = $("#text_monto_egreso").val();
          interes = $("#text_interes").val();

          // suma = monto_inicial_caja + suma_prestamo_capital + suma_ingresos;
          //console.log(suma);
          ope = (parseFloat(monto_inicial_caja) + parseFloat(interes) + parseFloat(suma_ingresos));

          suma = (parseFloat(ope - suma_egresos).toFixed(2));



          $("#text_monto_total").val(suma);

      }

      /*===================================================================*/
      //TRAER TODOS LOS PRESTAMOS FINALIZADOS DE LA CAJA ACTUAL
      /*===================================================================*/
      function Traer_RegistrosporIDCaja(caja_id) {
          tbl_resgitros_caja = $("#tbl_resgitros_caja").DataTable({
              responsive: true,
              destroy: true,
              searching: false,
              dom: 'tp',
              ajax: {
                  url: "ajax/caja_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 7,
                      'caja_id': caja_id
                  }, //LISTAR 
              },
              columnDefs: [
                 {
                      targets: 1,
                      visible: false

                  },{
                      targets: 7,
                      visible: false

                  },
                  {
                  targets: 8,
                  //sortable: false,
                  createdCell: function(td, cellData, rowData, row, col) {

                      if (rowData[8] == 'aprobado') {
                          $(td).html("<span class='badge badge-success'>aprobado</span>")
                      } else if (rowData[8] == 'finalizado') {
                          $(td).html("<span class='badge badge-info'>finalizado</span>")
                      }else {
                          $(td).html("<span class='badge badge-warning'>pendiente</span>")
                      }

                  }
              }

              ],
              "footerCallback": function(row, data, start, end, display) {
                  var api = this.api(),
                      data;
                  var intval = function(i) {
                      return typeof i === 'string' ?
                          i.replace(/[\$,]/g, '') * 1 :
                          typeof i === 'number' ?
                          i : 0;
                  };
                  total = api
                      .column(5)
                      .data()
                      .reduce(function(a, b) {
                          return intval(a) + intval(b);
                      }, 0);
                  pageTotal = api
                      .column(5, {
                          page: 'current'
                      })
                      .data()
                      .reduce(function(a, b) {
                          return intval(a) + intval(b);
                      }, 0);
                  $(api.column(5).footer()).html(
                    '' + pageTotal 
                    //   '' + pageTotal + ' ( ' + total + ' total)'
                  );

              },
              

              "language": idioma_espanol,
              select: true
          });
      }


      /*===================================================================*/
      //TRAER TODOS LOS MOVIMIENTOS DE LA CAJA ACTUAL
      /*===================================================================*/
      function Traer_MovimientosporIDCaja(caja_id) {
        tbl_resgitros_movi = $("#tbl_resgitros_movi").DataTable({
              responsive: true,
              destroy: true,
              searching: false,
              dom: 'tp',
              ajax: {
                  url: "ajax/caja_ajax.php",
                  dataSrc: "",
                  type: "POST",
                  data: {
                      'accion': 8,
                      'caja_id': caja_id
                  }, 
              },
              columnDefs: [
                 {
                      targets: 4,
                      visible: false

                  }
                  

              ],
              
             

              "language": idioma_espanol,
              select: true
          });
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

      /*===================================================================*/
      // NUEVAS FUNCIONALIDADES MEJORADAS
      /*===================================================================*/

      // Variables globales para información de sucursal y caja
      let informacionSucursal = null;
      let estadoCajaActual = null;

      // Inicializar funcionalidades mejoradas al cargar
      $(document).ready(function() {
          inicializarSistemaCompleto();
          configurarEventosNuevos();
      });

      function inicializarSistemaCompleto() {
          obtenerInformacionSucursal();
          cargarResumenDiaActual();
          verificarEstadoCaja();
          
          // Actualizar cada 30 segundos
          setInterval(function() {
              cargarResumenDiaActual();
              verificarEstadoCaja();
          }, 30000);
      }

      function obtenerInformacionSucursal() {
          $.ajax({
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: { accion: "obtener_sucursal_usuario" },
              dataType: 'json',
              success: function(response) {
                  if (response.success) {
                      informacionSucursal = response.sucursal;
                      actualizarInfoSucursal();
                  }
              },
              error: function() {
                  console.error("Error al obtener información de sucursal");
              }
          });
      }

      function actualizarInfoSucursal() {
          if (informacionSucursal) {
              const textoSucursal = informacionSucursal.sucursal_nombre 
                  ? `- ${informacionSucursal.sucursal_codigo}: ${informacionSucursal.sucursal_nombre}`
                  : '- Sucursal Principal';
              
              $('#info-sucursal-header').text(textoSucursal);
          }
      }

      function cargarResumenDiaActual() {
          $.ajax({
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: { accion: "obtener_resumen_dia" },
              dataType: 'json',
              success: function(response) {
                  if (response.success) {
                      actualizarTarjetasResumen(response.resumen);
                  }
              },
              error: function() {
                  // Error silencioso para no molestar al usuario
              }
          });
      }

      function actualizarTarjetasResumen(resumen) {
          // Actualizar tarjetas de resumen
          $('#resumen-prestamos-count').text(resumen.prestamos_otorgados || 0);
          $('#resumen-prestamos-monto').text('$' + parseFloat(resumen.monto_prestamos || 0).toFixed(2));
          
          $('#resumen-pagos-count').text(resumen.pagos_recibidos || 0);
          $('#resumen-pagos-monto').text('$' + parseFloat(resumen.monto_pagos || 0).toFixed(2));
          
          $('#resumen-ingresos-count').text(resumen.ingresos_count || 0);
          $('#resumen-ingresos-monto').text('$' + parseFloat(resumen.ingresos_monto || 0).toFixed(2));
          
          $('#resumen-egresos-count').text(resumen.egresos_count || 0);
          $('#resumen-egresos-monto').text('$' + parseFloat(resumen.egresos_monto || 0).toFixed(2));

          // Actualizar estado de caja
          $('#caja-monto-inicial').text('$' + parseFloat(resumen.monto_inicial_caja || 0).toFixed(2));
          
          const saldoCalculado = parseFloat(resumen.monto_inicial_caja || 0) + 
                                parseFloat(resumen.monto_pagos || 0) + 
                                parseFloat(resumen.ingresos_monto || 0) - 
                                parseFloat(resumen.egresos_monto || 0) - 
                                parseFloat(resumen.monto_prestamos || 0);
          
          $('#caja-saldo-actual').text('$' + saldoCalculado.toFixed(2));
      }

      function verificarEstadoCaja() {
          $.ajax({
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: { accion: "obtenerDataEstadoCaja" },
              dataType: 'json',
              success: function(response) {
                  estadoCajaActual = response;
                  actualizarEstadoCaja(response);
              },
              error: function() {
                  $('#caja-estado').removeClass().addClass('badge badge-danger').text('Error');
              }
          });
      }

      function actualizarEstadoCaja(estado) {
          if (estado && estado.caja_estado === 'VIGENTE') {
              $('#caja-estado').removeClass().addClass('badge badge-success').text('ABIERTA');
              // Habilitar botón cerrar caja si existe
              $('.btnCerrarCaja').prop('disabled', false);
              $('#btnCierreDiaRapido').prop('disabled', false);
          } else {
              $('#caja-estado').removeClass().addClass('badge badge-secondary').text('CERRADA');
              $('#caja-monto-inicial, #caja-saldo-actual').text('$0.00');
              $('#btnCierreDiaRapido').prop('disabled', true);
          }
      }

      function configurarEventosNuevos() {
          // Evento para cierre de día rápido
          $('#btnCierreDiaRapido').on('click', function() {
              if (estadoCajaActual && estadoCajaActual.caja_estado === 'VIGENTE') {
                  prepararCierreDia();
              } else {
                  Toast.fire({
                      icon: 'warning',
                      title: 'No hay caja abierta para hacer cierre de día'
                  });
              }
          });

          // Evento para actualizar datos
          $('#btnActualizarDatos').on('click', function() {
              cargarResumenDiaActual();
              verificarEstadoCaja();
              tbl_caja.ajax.reload();
              Toast.fire({
                  icon: 'success',
                  title: 'Datos actualizados'
              });
          });

          // Mejorar función de cerrar caja existente
          $(document).on('click', '.btnCerrarCaja', function() {
              if (estadoCajaActual && estadoCajaActual.caja_estado === 'VIGENTE') {
                  // Usar la función existente pero mejorada
                  var data = tbl_caja.row($(this).parents('tr')).data();
                  $("#caja_id").val(data[0]);
                  AbrirModalAbrirCerrarCaja();
                  CargarDatosCierreCaja();
                  Sumar();
              } else {
                  Toast.fire({
                      icon: 'warning',
                      title: 'No se puede cerrar una caja que no está abierta'
                  });
              }
          });
      }

      function prepararCierreDia() {
          $.ajax({
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: { accion: "obtener_resumen_dia" },
              dataType: 'json',
              success: function(response) {
                  if (response.success) {
                      const resumen = response.resumen;
                      
                      if (!resumen.puede_cerrar_dia) {
                          Toast.fire({
                              icon: 'info',
                              title: 'El día ya ha sido cerrado'
                          });
                          return;
                      }

                      // Crear modal dinámico para cierre de día
                      mostrarModalCierreDia(resumen);
                  }
              },
              error: function() {
                  Toast.fire({
                      icon: 'error',
                      title: 'Error al obtener datos para cierre'
                  });
              }
          });
      }

      function mostrarModalCierreDia(resumen) {
          const saldoFinal = parseFloat(resumen.monto_inicial_caja || 0) + 
                            parseFloat(resumen.monto_pagos || 0) + 
                            parseFloat(resumen.ingresos_monto || 0) - 
                            parseFloat(resumen.egresos_monto || 0) - 
                            parseFloat(resumen.monto_prestamos || 0);

          Swal.fire({
              title: '<i class="fas fa-calendar-check"></i> Cierre de Día',
              html: `
                  <div class="row">
                      <div class="col-md-6">
                          <h6><i class="fas fa-chart-pie"></i> Resumen del Día</h6>
                          <table class="table table-sm">
                              <tr><td><strong>Préstamos:</strong></td><td>${resumen.prestamos_otorgados || 0}</td><td>$${parseFloat(resumen.monto_prestamos || 0).toFixed(2)}</td></tr>
                              <tr><td><strong>Pagos:</strong></td><td>${resumen.pagos_recibidos || 0}</td><td>$${parseFloat(resumen.monto_pagos || 0).toFixed(2)}</td></tr>
                              <tr><td><strong>Ingresos:</strong></td><td>${resumen.ingresos_count || 0}</td><td>$${parseFloat(resumen.ingresos_monto || 0).toFixed(2)}</td></tr>
                              <tr><td><strong>Egresos:</strong></td><td>${resumen.egresos_count || 0}</td><td>$${parseFloat(resumen.egresos_monto || 0).toFixed(2)}</td></tr>
                          </table>
                      </div>
                      <div class="col-md-6">
                          <h6><i class="fas fa-calculator"></i> Balance</h6>
                          <p><strong>Monto Inicial:</strong> $${parseFloat(resumen.monto_inicial_caja || 0).toFixed(2)}</p>
                          <p><strong>Saldo Final:</strong> <span class="text-success">$${saldoFinal.toFixed(2)}</span></p>
                          <textarea id="swal-observaciones" class="form-control" placeholder="Observaciones (opcional)" rows="3"></textarea>
                      </div>
                  </div>
              `,
              showCancelButton: true,
              confirmButtonText: '<i class="fas fa-check"></i> Generar Cierre',
              cancelButtonText: '<i class="fas fa-times"></i> Cancelar',
              confirmButtonColor: '#28a745',
              cancelButtonColor: '#6c757d',
              width: '800px',
              preConfirm: () => {
                  return $('#swal-observaciones').val();
              }
          }).then((result) => {
              if (result.isConfirmed) {
                  generarCierreDia(result.value);
              }
          });
      }

      function generarCierreDia(observaciones) {
          $.ajax({
              url: "ajax/caja_ajax.php",
              method: "POST",
              data: {
                  accion: "generar_cierre_dia",
                  observaciones: observaciones || ''
              },
              dataType: 'json',
              success: function(response) {
                  if (response.resultado == 1) {
                      Toast.fire({
                          icon: 'success',
                          title: 'Cierre de día generado exitosamente'
                      });
                      cargarResumenDiaActual();
                  } else {
                      Toast.fire({
                          icon: 'error',
                          title: response.mensaje || 'Error al generar cierre de día'
                      });
                  }
              },
              error: function() {
                  Toast.fire({
                      icon: 'error',
                      title: 'Error de conexión'
                  });
              }
          });
      }
  </script>

  <!-- Script para correcciones de modales -->
  <script src="vistas/assets/dist/js/caja-modales-corregidos.js"></script>