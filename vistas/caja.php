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
          
          <!-- INICIO DASHBOARD UNIFICADO (Migrado de dashboard_caja.php) -->
          <!-- KPIs Uniformes y Refactorizados -->
<div class="row mb-3">
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-success text-white mb-1"><i class="fas fa-cash-register"></i></div>
                <h4 class="kpi-value" id="kpi-cajas-abiertas">-</h4>
                <div class="kpi-label">Cajas Abiertas</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-info text-white mb-1"><i class="fas fa-coins"></i></div>
                <h4 class="kpi-value" id="kpi-saldo-total">-</h4>
                <div class="kpi-label">Saldo Total Activo</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-warning text-white mb-1"><i class="fas fa-exclamation-triangle"></i></div>
                <h4 class="kpi-value" id="kpi-alertas-criticas">-</h4>
                <div class="kpi-label">Alertas Críticas</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-primary text-white mb-1"><i class="fas fa-chart-line"></i></div>
                <h4 class="kpi-value" id="kpi-operaciones-hoy">-</h4>
                <div class="kpi-label">Operaciones Hoy</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-secondary text-white mb-1"><i class="fas fa-hand-holding-usd"></i></div>
                <h4 class="kpi-value" id="kpi-prestamos-dia">-</h4>
                <div class="kpi-label">Préstamos del Día</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-teal text-white mb-1"><i class="fas fa-coins"></i></div>
                <h4 class="kpi-value" id="kpi-pagos-dia">-</h4>
                <div class="kpi-label">Pagos Recibidos</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-orange text-white mb-1"><i class="fas fa-plus-circle"></i></div>
                <h4 class="kpi-value" id="kpi-ingresos-extra">-</h4>
                <div class="kpi-label">Ingresos Extra</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6 mb-2">
        <div class="card kpi-card shadow-sm h-100">
            <div class="card-body text-center p-2">
                <div class="kpi-icon bg-danger text-white mb-1"><i class="fas fa-minus-circle"></i></div>
                <h4 class="kpi-value" id="kpi-egresos-dia">-</h4>
                <div class="kpi-label">Egresos del Día</div>
            </div>
        </div>
    </div>
</div>

<style>
.kpi-card { border-radius: 0.7rem; min-height: 120px; }
.kpi-icon { width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; margin: 0 auto 0.2rem auto; }
.kpi-value { font-size: 1.5rem; font-weight: bold; margin-bottom: 0.1rem; }
.kpi-label { font-size: 0.95rem; color: #555; }
.bg-teal { background: #20c997 !important; }
.bg-orange { background: #fd7e14 !important; }
</style>
          <!-- Acciones rápidas y estadísticas -->
          <div class="row mb-2">
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-chart-bar"></i> Estadísticas del Día</h3>
                      </div>
                      <div class="card-body">
                          <div class="row">
                              <div class="col-6">
                                  <div class="description-block">
                                      <h5 class="description-header" id="stat-aperturas-hoy">-</h5>
                                      <span class="description-text">Aperturas Hoy</span>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="description-block">
                                      <h5 class="description-header" id="stat-cierres-hoy">-</h5>
                                      <span class="description-text">Cierres Hoy</span>
                                  </div>
                              </div>
                          </div>
                          <div class="row mt-2">
                              <div class="col-12">
                                  <div class="progress">
                                      <div class="progress-bar bg-success" id="progress-operaciones" style="width: 0%"></div>
                                  </div>
                                  <span class="float-left">Eficiencia Operacional</span>
                                  <span class="float-right" id="porcentaje-eficiencia">0%</span>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="col-md-4 d-flex align-items-center justify-content-center">
                  <div class="card w-100">
                      <div class="card-body p-2 text-center">
                          <button class="btn btn-success btn-sm mx-1" id="btn-abrir-caja"><i class="fas fa-lock-open"></i> ABRIR</button>
                          <button class="btn btn-warning btn-sm mx-1" id="btn-cerrar-caja"><i class="fas fa-lock"></i> CERRAR</button>
                          <button class="btn btn-info btn-sm mx-1" id="btn-realizar-conteo"><i class="fas fa-calculator"></i> CONTEO</button>
                          <button class="btn btn-primary btn-sm mx-1" id="btn-generar-reporte"><i class="fas fa-file-alt"></i> REPORTE</button>
                      </div>
                  </div>
              </div>
          </div>
          <!-- Grid de Cajas Activas (al final) -->
          <div class="row mb-2">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title"><i class="fas fa-cash-register"></i> Cajas Activas</h3>
                          <div class="card-tools">
                              <span class="badge badge-success" id="badge-tiempo-real">En tiempo real</span>
                          </div>
                      </div>
                      <div class="card-body table-responsive p-0">
                          <table class="table table-hover table-sm text-nowrap animate-fade-in" id="tabla-cajas-activas">
                              <thead>
                                  <tr>
                                      <th>Caja</th>
                                      <th>Usuario</th>
                                      <th>Apertura</th>
                                      <th>Horas Abiertas</th>
                                      <th>Saldo</th>
                                      <th>Alertas</th>
                                      <th>Acciones</th>
                                  </tr>
                              </thead>
                              <tbody id="tbody-cajas-activas">
                                  <tr>
                                      <td colspan="7" class="text-center">
                                          <i class="fas fa-spinner fa-spin"></i> Cargando...
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
          <!-- FIN DASHBOARD UNIFICADO -->

          <div class="row p-0 m-0">
              <div class="col-md-12">
                  <div class="card card-info card-outline shadow ">
                      <div class="card-header bg-gradient-info">
                          <h3 class="card-title">Aperturar de Caja</h3>
                          <button class="btn btn-info btn-sm float-right btnAbrirCaja" id="btnAbrirCaja">
                              <i class="fas fa-plus"></i> Aperturar
                          </button>
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

  <!-- Modal para Abrir Caja con Validaciones -->
<div class="modal fade" id="modal-abrir-caja-avanzado" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h4 class="modal-title">
                    <i class="fas fa-unlock"></i> Apertura de Caja Avanzada
                </h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-abrir-caja-avanzado">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Descripción de la Caja</label>
                                <input type="text" class="form-control" id="caja-descripcion" 
                                       value="Apertura de Caja" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Monto Inicial</label>
                                <input type="number" class="form-control" id="caja-monto-inicial" 
                                       step="0.01" min="0" required>
                                <small class="text-muted">Límite máximo: <span id="limite-apertura">-</span></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Observaciones</label>
                                <textarea class="form-control" id="caja-observaciones" rows="3" 
                                          placeholder="Observaciones adicionales sobre la apertura..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="validacion-fisica-apertura">
                                <label class="form-check-label" for="validacion-fisica-apertura">
                                    He realizado el conteo físico del dinero
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="confirmarAperturaCaja()">
                    <i class="fas fa-unlock"></i> Abrir Caja
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Conteo Físico con Denominaciones -->
<div class="modal fade" id="modal-conteo-fisico" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info p-2">
        <h5 class="modal-title">
          <i class="fas fa-calculator"></i> Conteo Físico
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-2">
        <form id="form-conteo-fisico">
          <div class="form-group mb-2">
            <label class="mb-1">Tipo de Conteo</label>
            <select class="form-control form-control-sm" id="tipo-conteo">
              <option value="INTERMEDIO">Intermedio</option>
              <option value="CIERRE">Cierre</option>
              <option value="SUPERVISION">Supervisión</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Saldo Físico</label>
            <input type="number" class="form-control form-control-sm" id="saldo-fisico" step="0.01" min="0" required>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Denominaciones</label>
            <div class="table-responsive">
              <table class="table table-sm table-bordered mb-2" id="tabla-denominaciones" style="font-size:0.95em;">
                <thead>
                  <tr>
                    <th style="width:35%">Denom.</th>
                    <th style="width:35%">Cant.</th>
                    <th style="width:30%">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr><td>500</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="500"></td><td class="denom-total">0</td></tr>
                  <tr><td>200</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="200"></td><td class="denom-total">0</td></tr>
                  <tr><td>100</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="100"></td><td class="denom-total">0</td></tr>
                  <tr><td>50</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="50"></td><td class="denom-total">0</td></tr>
                  <tr><td>20</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="20"></td><td class="denom-total">0</td></tr>
                  <tr><td>10</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="10"></td><td class="denom-total">0</td></tr>
                  <tr><td>5</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="5"></td><td class="denom-total">0</td></tr>
                  <tr><td>1</td><td><input type="number" min="0" class="form-control form-control-sm input-denom" data-valor="1"></td><td class="denom-total">0</td></tr>
                </tbody>
              </table>
            </div>
            <div class="text-right font-weight-bold">Total: <span id="total-denominaciones">0</span></div>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Observaciones</label>
            <textarea class="form-control form-control-sm" id="observaciones-conteo" rows="2" placeholder="Detalles..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info btn-sm" onclick="window.confirmarConteoFisico()">
          <i class="fas fa-save"></i> Registrar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Generar Reporte -->
<div class="modal fade" id="modal-generar-reporte" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary p-2">
        <h5 class="modal-title">
          <i class="fas fa-file-alt"></i> Generar Reporte
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-2">
        <form id="form-generar-reporte">
          <div class="form-group mb-2">
            <label class="mb-1">Tipo de Reporte</label>
            <select class="form-control form-control-sm" id="tipo-reporte">
              <option value="resumen">Resumen de Caja</option>
              <option value="movimientos">Movimientos</option>
              <option value="prestamos">Préstamos</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Formato</label>
            <select class="form-control form-control-sm" id="formato-reporte">
              <option value="pdf">PDF</option>
              <option value="excel">Excel</option>
              <option value="csv">CSV</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label class="mb-1">Enviar por correo (opcional)</label>
            <input type="email" class="form-control form-control-sm" id="email-reporte" placeholder="correo@ejemplo.com">
          </div>
        </form>
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-info btn-sm" id="btn-descargar-reporte">
          <i class="fas fa-download"></i> Descargar
        </button>
        <button type="button" class="btn btn-success btn-sm" id="btn-enviar-reporte">
          <i class="fas fa-envelope"></i> Enviar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Detalle de Caja -->
<div class="modal fade" id="modal-detalle-caja" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info p-2">
        <h5 class="modal-title">
          <i class="fas fa-eye"></i> Detalle de Caja
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-2" id="detalle-caja-body">
        <!-- Aquí se cargan los detalles dinámicamente -->
        <div class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>
      </div>
      <div class="modal-footer p-2">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

  <script src="vistas/js/caja_page_functions.js"></script>
  <!-- Script para correcciones de modales -->
  <script src="vistas/assets/dist/js/caja-modales-corregidos.js"></script>
  <script src="vistas/js/dashboard_caja_funciones.js"></script>
  <script>
// Función para cargar sucursales en el modal
function cargarSucursalesModal() {
     $.ajax({
        url: 'ajax/aprobacion_ajax.php',
        type: 'GET',
        data: { accion: 'listar_sucursales' },
        dataType: 'json',
        success: function(response) {
            const select = $('#select_sucursal');
            select.empty().append('<option value="">Seleccionar sucursal...</option>');
            
            if (response && Array.isArray(response) && response.length > 0) {
                response.forEach(function(sucursal) {
                    const sucursalId = sucursal.sucursal_id || sucursal.id;
                    const sucursalNombre = sucursal.sucursal_nombre || sucursal.nombre;
                    
                    if (sucursalId && sucursalNombre) {
                        select.append(`<option value="${sucursalId}">${sucursalNombre}</option>`);
                    }
                });
               } else {
                console.warn('No se encontraron sucursales');
                select.append('<option value="">No hay sucursales disponibles</option>');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al cargar sucursales:', error);
            $('#select_sucursal').empty().append('<option value="">Error al cargar sucursales</option>');
        }
    });
}

// Función global para abrir caja
window.abrirCaja = function() {
    // Verificar permisos primero
    $.ajax({
        url: "ajax/caja_ajax.php",
        method: "POST",
        data: {
            accion: "verificar_permisos_caja",
            sub_accion: "ABRIR_CAJA"
        },
        dataType: 'json',
        success: function(response) {
            // CORRECCIÓN: La lógica ahora busca dentro de response.permisos
            if (response.success && response.permisos && response.permisos.puede_ejecutar) {
                // Mostrar el modal de apertura
                $("#modal_abrir_caja").modal('show');
                
                // Cargar sucursales si es necesario
                if (typeof cargarSucursalesModal === 'function') {
                    cargarSucursalesModal();
                }
                
                // Establecer valores por defecto
                $("#text_descripcion").val('Apertura de Caja');
                $("#text_monto_ini").focus();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de Permisos',
                    // CORRECCIÓN: Usar el mensaje correcto de la respuesta
                    text: response.permisos ? response.permisos.mensaje : 'No tiene los permisos necesarios para abrir caja'
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al verificar permisos:", error);
            console.error("Respuesta del servidor:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudieron verificar los permisos. Por favor, intente nuevamente.'
            });
        }
    });
}

// Asignar eventos cuando el documento esté listo
$(document).ready(function() {
    // Asignar el evento al botón de apertura
    $("#btnAbrirCaja, .btnAbrirCaja").on('click', function(e) {
        e.preventDefault();
        abrirCaja();
    });
});

// Lógica para ocultar la caja principal a usuarios que no sean administradores
$(document).ready(function() {
    // Suponiendo que tienes una variable global userContext o similar con el perfil
    // Si no, deberás obtener el perfil del usuario vía AJAX o PHP
    function esAdmin() {
        // Puedes reemplazar esto por la lógica real de tu sistema
        return window.userContext && window.userContext.perfil && window.userContext.perfil.toLowerCase() === 'administrador';
    }

    // Ocultar filas de la grid de cajas activas que sean tipo principal si no es admin
    function filtrarCajasActivasPorRol() {
        if (!esAdmin()) {
            $('#tabla-cajas-activas tbody tr').each(function() {
                var tipo = $(this).find('td').eq(0).text().toLowerCase();
                // Suponiendo que el tipo de caja está en la primera columna o puedes ajustar el selector
                if (tipo.includes('principal')) {
                    $(this).hide();
                }
            });
        }
    }

    // Llamar al filtrar después de cargar la tabla
    setTimeout(filtrarCajasActivasPorRol, 500); // Ajusta el timeout según tu carga de datos

    // Si usas DataTables, puedes usar drawCallback para filtrar dinámicamente
    if ($.fn.DataTable && $('#tabla-cajas-activas').hasClass('dataTable')) {
        $('#tabla-cajas-activas').on('draw.dt', function() {
            filtrarCajasActivasPorRol();
        });
    }
});

$(document).ready(function() {
    function esAdmin() {
        return window.userContext && window.userContext.perfil && window.userContext.perfil.toLowerCase() === 'administrador';
    }

    // Mostrar u ocultar la grid de cajas activas según el rol
    if (!esAdmin()) {
        // Ocultar la grid completa para no administradores
        $('.card:has(#tabla-cajas-activas)').hide();
    }
});
</script>