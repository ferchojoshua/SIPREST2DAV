  <!-- Content Header (Page header) -->
  <div class="content-header">
      <div class="container-fluid">
          <div class="row mb-2">
              <div class="col-sm-6">
                  <h4 class="m-0">Administrar Prestamos</h4>
              </div><!-- /.col -->
              <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
                      <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
                      <li class="breadcrumb-item active">Administrar Prestamos</li>
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
                          <h3 class="card-title">Listado de Prestamos por usuario</h3>

                      </div>
                      <div class=" card-body">
                          <input type="text" id="id_usuario" hidden>
                          <div class="table-responsive">
                              <table id="tbl_ls_prestamos" class="table display table-hover text-nowrap compact  w-100  rounded">
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
                                          <th class="text-center">Opciones</th>
                                          <th>pres_monto_cuota</th>
                                          <th>pres_monto_interes</th>
                                          <th>pres_monto_total</th>
                                          <th>pres_cuotas_pagadas</th>
                                          <th>reimpreso_admin</th>
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
                                  <span class="small"> Cuota</span>
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
                      <div class="col-md-2">
                          <label for="">&nbsp;</label><br>
                          <button type="button" class="btn btn-danger btn-sm" id="btnLiquidar" style="display:none;">
                              <i class="fas fa-check-double"></i> Liquidar
                          </button>
                      </div>

                      <!-- <div class="col-md-2">
                          <div class="form-group mb-2">
                              <label for="" class="">
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                              </label>
                              <button type="button" class="btn btn-primary btn-sm" id="btnregistrar_cliente">Registrar</button>


                          </div>
                      </div> -->
                  </div>
                  <div class="row">
                      <div class="table-responsive">
                          <table id="prestamo_detalle" class="table display table-hover text-nowrap compact  w-100  rounded" style="width: 100%;">
                              <thead class="bg-gradient-info text-white">
                                  <tr>
                                      <th>Id</th>
                                      <th>Nro prestamo</th>
                                      <th>Cuota</th>
                                      <th>Fecha</th>
                                      <th>Monto</th>
                                      <th>Saldo Pendiente</th>
                                      <th>Estado</th>
                                      <th class="text-cetner">Opciones</th>
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

  <!-- MODAL ABONAR CUOTA-->
  <div class="modal fade" id="modal_abonar_cuota" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-md" role="document">
          <div class="modal-content">
              <div class="modal-header bg-gray py-1 align-items-center">
                  <h5 class="modal-title" id="titulo_modal_abono">Abonar Cuota</h5>
                  <button type="button" class="close  text-white border-0 fs-5" data-bs-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Nro Cuota:</span>
                                  <input type="text" class="form-control form-control-sm" id="nro_cuota_abono" disabled>
                              </label>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Monto Cuota Original:</span>
                                  <input type="text" class="form-control form-control-sm" id="monto_cuota_original" disabled>
                              </label>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Saldo Pendiente:</span>
                                  <input type="text" class="form-control form-control-sm" id="saldo_pendiente_abono" disabled>
                              </label>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Monto a Abonar:</span>
                                  <input type="number" step="0.01" class="form-control form-control-sm" id="monto_a_abonar" placeholder="Ingrese monto a abonar">
                              </label>
                              <div class="d-flex justify-content-between mt-2">
                                  <button type="button" class="btn btn-info btn-sm" id="btnAbonoCompleto">
                                      <i class="fas fa-coins"></i> Pago Completo
                                  </button>
                                  <button type="button" class="btn btn-warning btn-sm" id="btnAbonoMitad">
                                      <i class="fas fa-divide"></i> 50%
                                  </button>
                                  <button type="button" class="btn btn-success btn-sm" id="btnAbonoMinimo">
                                      <i class="fas fa-percentage"></i> 25%
                                  </button>
                              </div>
                          </div>
                      </div>
                      <div class="col-lg-12">
                          <div class="form-group mb-2">
                              <label for="" class="">
                                  <span class="small">Tipo de Abono:</span>
                                  <select class="form-control form-control-sm" id="tipo_abono">
                                      <option value="normal">Normal - Solo para esta cuota</option>
                                      <option value="extraordinario">Extraordinario - Aplicar a múltiples cuotas</option>
                                  </select>
                              </label>
                              <small class="text-muted">
                                  <i class="fas fa-info-circle"></i> 
                                  El abono extraordinario se aplicará primero a la cuota actual y el excedente a las siguientes cuotas.
                              </small>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-success btn-sm" id="btnRegistrarAbono">Registrar Abono</button>
              </div>
          </div>
      </div>
  </div>
  <!-- fin Modal Abono -->

  <script>
      var accion;
      var tbl_ls_prestamos, prestamo_detalle_dt;

      var Toast = Swal.mixin({
          toast: true,
          position: 'top',
          showConfirmButton: false,
          timer: 3000
      });


      $(document).ready(function() {
        
          // Debug: Verificar si ID_USUARIO_GLOBAL está definido
          console.log("Verificando ID_USUARIO_GLOBAL:", typeof ID_USUARIO_GLOBAL !== 'undefined' ? ID_USUARIO_GLOBAL : 'NO DEFINIDO');
          
          var id_usuario = typeof ID_USUARIO_GLOBAL !== 'undefined' ? ID_USUARIO_GLOBAL : 1; // Valor por defecto para testing
          console.log("ID de usuario que se usará:", id_usuario);

          /***************************************************************************
           * INICIAR DATATABLE PRÉSTAMOS - CONFIGURACIÓN SIMPLIFICADA
           ******************************************************************************/
          tbl_ls_prestamos = $("#tbl_ls_prestamos").DataTable({
              responsive: true,
              ajax: {
                  url: "ajax/admin_prestamos_ajax.php",
                  dataSrc: function(json) {
                      // Filtrar solo préstamos aprobados
                      return json.filter(function(item) {
                          return item.estado && item.estado.toLowerCase() === 'aprobado';
                      });
                  },
                  type: "POST",
                  data: {
                      'accion': 1,
                      'id_usuario': id_usuario
                  },
                  error: function(xhr, error, thrown) {
                      console.error('Error en la llamada AJAX:', error);
                      console.error('Detalles:', thrown);
                      console.error('Respuesta del servidor:', xhr.responseText);
                      
                      Toast.fire({
                          icon: 'error',
                          title: 'Error al cargar los datos de préstamos'
                      });
                  }
              },
              columns: [
                  { data: 'pres_id' },
                  { data: 'nro_prestamo' },
                  { data: 'cliente_id' },
                  { data: 'cliente_nombres' },
                  { data: 'pres_monto' },
                  { data: 'pres_interes' },
                  { data: 'pres_cuotas' },
                  { data: 'fpago_id' },
                  { data: 'fpago_descripcion' },
                  { data: 'id_usuario' },
                  { data: 'usuario' },
                  { data: 'fecha' },
                  { data: 'estado' },
                  { data: null },
                  { data: 'pres_monto_cuota' },
                  { data: 'pres_monto_interes' },
                  { data: 'pres_monto_total' },
                  { data: 'pres_cuotas_pagadas' },
                  { data: 'reimpreso_admin' }
              ],
              columnDefs: [
                  {
                      targets: [0, 2, 7, 9, 14, 15, 16, 17, 18],
                      visible: false,
                      searchable: false
                  },
                  {
                      targets: 12,
                      render: function(data, type, row) {
                          if (data == 'aprobado') {
                              return "<span class='badge badge-success'>APROBADO</span>"
                          } else if (data == 'pendiente') {
                              return "<span class='badge badge-warning'>PENDIENTE</span>"
                          } else if (data == 'anulado') {
                              return "<span class='badge badge-danger'>ANULADO</span>"
                          } else {
                              return "<span class='badge badge-info'>FINALIZADO</span>"
                          }
                      }
                  },
                  {
                      targets: 13,
                      sortable: false,
                      render: function(data, type, row) {
                          var opciones = "<center>" +
                              "<span class='btnVerDetallePrestamo text-success px-1' style='cursor:pointer;' data-bs-toggle='modal' data-bs-target='#modal_detalle_prestamo' title='Ver Detalle'> " +
                              "<i class='fas fa-search fs-6'></i> " +
                              "</span> " +
                              "<span class='btnImprimirContrato text-primary px-1' style='cursor:pointer;' data-id_prestamo='" + row.pres_id + "' title='Imprimir Contrato'> " +
                              "<i class='fas fa-print fs-6'></i> " +
                              "</span> ";
                          
                          // Solo agregar icono de correo para préstamos aprobados
                          if (row.estado && row.estado.toLowerCase() === 'aprobado') {
                              opciones += "<span class='btnEnviarTablaCorreo text-warning px-1' style='cursor:pointer;' data-nro_prestamo='" + row.nro_prestamo + "' title='Enviar Tabla de Pagos por Correo'> " +
                                  "<i class='fas fa-envelope fs-6'></i> " +
                                  "</span> ";
                          }
                          
                          opciones += "</center>";
                          return opciones;
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
          // EVENTO PARA VER DETALLE DE PRÉSTAMO
          /*===================================================================*/
          $("#tbl_ls_prestamos tbody").on('click', '.btnVerDetallePrestamo', function() {

              var data = tbl_ls_prestamos.row($(this).parents('tr')).data();
              console.log("Datos del préstamo seleccionado:", data);

              $("#modal_detalle_prestamo").modal('show');

              $("#text_nro_prestamo_d").val(data.nro_prestamo);
              $("#text_cliente_d").val(data.cliente_nombres);
              $("#text_monto_d").val(data.pres_monto + ".00");
              $("#text_interes_d").val(data.pres_interes + " %");
              $("#text_cuota_d").val(data.pres_cuotas);
              $("#text_fpago__d").val(data.fpago_descripcion);
              $("#text_fecha__d").val(data.fecha);
              $("#text_monto_cuota__d").val(data.pres_monto_cuota);
              $("#text_monto_interes__d").val(data.pres_monto_interes);
              $("#text_monto_total__d").val(data.pres_monto_total);
              $("#text_cuotas_pagadas__d").val(data.pres_cuotas_pagadas);

              Traer_Detalle(data.nro_prestamo);
              CargarCantCuotasPagadas();
          });

          /*===================================================================*/
          // EVENTO PARA PAGAR CUOTA COMPLETA
          /*===================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnPagarCuota', function() {

              var data = prestamo_detalle_dt.row($(this).parents('tr')).data();
              var nro_prestamo = data.nro_prestamo;
              var pdetalle_nro_cuota = data.pdetalle_nro_cuota;
              var estado = data.pdetalle_estado_cuota;
              
              console.log("Datos para pagar cuota:", nro_prestamo, pdetalle_nro_cuota, estado);

              Swal.fire({
                  title: '¿Desea pagar la cuota N° "' + pdetalle_nro_cuota + '"?',
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonColor: '#dc3545',
                  confirmButtonText: 'Sí, Pagar',
                  cancelButtonText: 'Cancelar',
              }).then((result) => {

                  if (result.isConfirmed) {
                      $.ajax({
                          url: "ajax/admin_prestamos_ajax.php",
                          method: "POST",
                          data: {
                              'accion': 3,
                              'nro_prestamo': nro_prestamo,
                              'pdetalle_nro_cuota': pdetalle_nro_cuota
                          },
                          dataType: 'json',
                          success: function(respuesta) {
                              console.log("Respuesta del pago:", respuesta);

                              if (respuesta.status == "ok") {
                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Cuota pagada correctamente'
                                  });
                                  
                                  prestamo_detalle_dt.ajax.reload();
                                  CargarCantCuotasPagadas();
                                  tbl_ls_prestamos.ajax.reload();

                                  // Generar voucher automáticamente
                                  setTimeout(function() {
                                      window.open("MPDF/ticket_pago_cuota.php?codigo=" + nro_prestamo + "&cuota=" + pdetalle_nro_cuota + "#zoom=100", 
                                                 "Voucher de Pago", 
                                                 "scrollbars=NO,resizable=YES,width=800,height=600");
                                  }, 1000);

                                  // Enviar mensaje por WhatsApp si están disponibles los datos
                                  if (respuesta.whatsapp_data) {
                                      enviarWhatsApp(respuesta.whatsapp_data);
                                  }
                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al pagar la cuota: ' + (respuesta.message || 'Error desconocido')
                                  });
                              }
                          },
                          error: function(xhr, textStatus, errorThrown) {
                              console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Error de comunicación con el servidor'
                              });
                          }
                      });
                  }
              });
          });

          /*===================================================================*/
          // EVENTO PARA ABONAR CUOTA (PAGO PARCIAL)
          /*===================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnAbonarCuota', function() {

              var data = prestamo_detalle_dt.row($(this).parents('tr')).data();
              
              // Guardar datos globalmente para el modal
              window.cuotaSeleccionada = {
                  nro_prestamo: data.nro_prestamo,
                  pdetalle_nro_cuota: data.pdetalle_nro_cuota,
                  pdetalle_monto_cuota: data.pdetalle_monto_cuota,
                  pdetalle_saldo_cuota: data.pdetalle_saldo_cuota
              };

              // Llenar los campos del modal
              $("#nro_cuota_abono").val(data.pdetalle_nro_cuota);
              $("#monto_cuota_original").val(data.pdetalle_monto_cuota);
              $("#saldo_pendiente_abono").val(data.pdetalle_saldo_cuota);
              $("#monto_a_abonar").val("");
              $("#tipo_abono").val("normal");
          });

          /*===================================================================*/
          // EVENTOS PARA LOS BOTONES DE ABONO RÁPIDO
          /*===================================================================*/
          $("#btnAbonoCompleto").on('click', function() {
              var saldoPendiente = parseFloat($("#saldo_pendiente_abono").val()) || 0;
              $("#monto_a_abonar").val(saldoPendiente.toFixed(2));
          });

          $("#btnAbonoMitad").on('click', function() {
              var saldoPendiente = parseFloat($("#saldo_pendiente_abono").val()) || 0;
              var mitad = saldoPendiente * 0.5;
              $("#monto_a_abonar").val(mitad.toFixed(2));
          });

          $("#btnAbonoMinimo").on('click', function() {
              var saldoPendiente = parseFloat($("#saldo_pendiente_abono").val()) || 0;
              var minimo = saldoPendiente * 0.25;
              $("#monto_a_abonar").val(minimo.toFixed(2));
          });

          /*===================================================================*/
          // EVENTO PARA REGISTRAR ABONO
          /*===================================================================*/
          $("#btnRegistrarAbono").on('click', function() {
              var monto_a_abonar = parseFloat($("#monto_a_abonar").val()) || 0;
              var saldo_pendiente = parseFloat($("#saldo_pendiente_abono").val()) || 0;
              var tipo_abono = $("#tipo_abono").val();

              if (monto_a_abonar <= 0) {
                  Toast.fire({
                      icon: 'error',
                      title: 'Debe ingresar un monto válido para abonar'
                  });
                  return;
              }

              if (monto_a_abonar > saldo_pendiente) {
                  Toast.fire({
                      icon: 'error',
                      title: 'El monto a abonar no puede ser mayor al saldo pendiente'
                  });
                  return;
              }

              Swal.fire({
                  title: '¿Confirma el abono por $' + monto_a_abonar.toFixed(2) + '?',
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonColor: '#dc3545',
                  confirmButtonText: 'Sí, Registrar',
                  cancelButtonText: 'Cancelar'
              }).then((result) => {
                  if (result.isConfirmed) {
                      $.ajax({
                          url: "ajax/admin_prestamos_ajax.php",
                          method: "POST",
                          data: {
                              'accion': 6,
                              'nro_prestamo': window.cuotaSeleccionada.nro_prestamo,
                              'pdetalle_nro_cuota': window.cuotaSeleccionada.pdetalle_nro_cuota,
                              'monto_a_abonar': monto_a_abonar,
                              'tipo_abono': tipo_abono
                          },
                          dataType: 'json',
                          success: function(respuesta) {
                              console.log("Respuesta del abono:", respuesta);

                              if (respuesta.status == "ok") {
                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Abono registrado correctamente'
                                  });
                                  
                                  $("#modal_abonar_cuota").modal('hide');
                                  prestamo_detalle_dt.ajax.reload();
                                  CargarCantCuotasPagadas();
                                  tbl_ls_prestamos.ajax.reload();

                                  // Generar voucher de abono automáticamente
                                  setTimeout(function() {
                                      window.open("MPDF/ticket_abono_cuota.php?codigo=" + window.cuotaSeleccionada.nro_prestamo + "&cuota=" + window.cuotaSeleccionada.pdetalle_nro_cuota + "#zoom=100", 
                                                 "Voucher de Abono", 
                                                 "scrollbars=NO,resizable=YES,width=800,height=600");
                                  }, 1000);

                                  // Enviar mensaje por WhatsApp si están disponibles los datos
                                  if (respuesta.whatsapp_data) {
                                      enviarWhatsApp(respuesta.whatsapp_data);
                                  }
                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al registrar el abono: ' + (respuesta.message || 'Error desconocido')
                                  });
                              }
                          },
                          error: function(xhr, textStatus, errorThrown) {
                              console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Error de comunicación con el servidor'
                              });
                          }
                      });
                  }
              });
          });

          /*===================================================================*/
          // EVENTO PARA IMPRIMIR RECIBO DE CUOTA PAGADA
          /*===================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnImprimirRecibo', function() {
              var data = prestamo_detalle_dt.row($(this).parents('tr')).data();
              
              window.open("MPDF/ticket_pago_cuota.php?codigo=" + data.nro_prestamo + "&cuota=" + data.pdetalle_nro_cuota + "#zoom=100", 
                         "Recibo de Pago", 
                         "scrollbars=NO,resizable=YES,width=800,height=600");
          });

          /*===================================================================*/
          // EVENTO PARA IMPRIMIR VOUCHER DE ABONO
          /*===================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnImprimirVoucherAbono', function() {
              var data = prestamo_detalle_dt.row($(this).parents('tr')).data();
              
              window.open("MPDF/ticket_abono_cuota.php?codigo=" + data.nro_prestamo + "&cuota=" + data.pdetalle_nro_cuota + "#zoom=100", 
                         "Voucher de Abono", 
                         "scrollbars=NO,resizable=YES,width=800,height=600");
          });

          /*===================================================================*/
          // EVENTO PARA ENVIAR CORREO DE CUOTA PAGADA
          /*===================================================================*/
          $("#prestamo_detalle tbody").on('click', '.btnEnviarCorreoCuotaP', function() {
              var data = prestamo_detalle_dt.row($(this).parents('tr')).data();
              
              Swal.fire({
                  title: '¿Enviar recibo por correo?',
                  text: 'Se enviará el recibo de la cuota ' + data.pdetalle_nro_cuota + ' al cliente',
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonColor: '#dc3545',
                  confirmButtonText: 'Sí, Enviar',
                  cancelButtonText: 'Cancelar'
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Aquí iría la lógica para enviar por correo
                      Toast.fire({
                          icon: 'info',
                          title: 'Funcionalidad de envío por correo en desarrollo'
                      });
                  }
              });
          });

          /*===================================================================*/
          // EVENTO PARA BOTÓN LIQUIDAR PRÉSTAMO
          /*===================================================================*/
          $("#btnLiquidar").on('click', function() {
              LiquidarCuotas();
          });

          /*===================================================================*/
          // EVENTO PARA IMPRIMIR CONTRATO
          /*===================================================================*/
          $("#tbl_ls_prestamos tbody").on('click', '.btnImprimirContrato', function() {
              var data = tbl_ls_prestamos.row($(this).parents('tr')).data();
              var id_prestamo = $(this).data('id_prestamo') || data.pres_id;
              
              console.log("Imprimiendo contrato para préstamo ID:", id_prestamo);
              
              window.open("MPDF/contrato.php?codigo=" + data.nro_prestamo + "#zoom=100", 
                         "Contrato de Préstamo", 
                         "scrollbars=YES,resizable=YES,width=900,height=700");
          });

          /*===================================================================*/
          // EVENTO PARA ENVIAR TABLA DE PAGOS POR CORREO
          /*===================================================================*/
          $("#tbl_ls_prestamos tbody").on('click', '.btnEnviarTablaCorreo', function() {
              var data = tbl_ls_prestamos.row($(this).parents('tr')).data();
              var nro_prestamo = $(this).data('nro_prestamo') || data.nro_prestamo;
              
              Swal.fire({
                  title: '¿Enviar tabla de pagos por correo?',
                  html: 'Se enviará la tabla de pagos del préstamo <strong>' + nro_prestamo + '</strong> al cliente <strong>' + data.cliente_nombres + '</strong>',
                  icon: 'question',
                  showCancelButton: true,
                  confirmButtonColor: '#28a745',
                  cancelButtonColor: '#dc3545',
                  confirmButtonText: 'Sí, Enviar',
                  cancelButtonText: 'Cancelar'
              }).then((result) => {
                  if (result.isConfirmed) {
                      // Mostrar loading
                      Swal.fire({
                          title: 'Enviando correo...',
                          text: 'Por favor espere',
                          allowOutsideClick: false,
                          didOpen: () => {
                              Swal.showLoading()
                          }
                      });

                      $.ajax({
                          url: "ajax/admin_prestamos_ajax.php",
                          method: "POST",
                          data: {
                              'accion': 8, // Nueva acción para enviar tabla por correo
                              'nro_prestamo': nro_prestamo,
                              'cliente_nombres': data.cliente_nombres
                          },
                          dataType: 'json',
                          success: function(respuesta) {
                              Swal.close();
                              
                              if (respuesta.status == "ok") {
                                  Toast.fire({
                                      icon: 'success',
                                      title: 'Tabla de pagos enviada correctamente por correo'
                                  });
                              } else {
                                  Toast.fire({
                                      icon: 'error',
                                      title: 'Error al enviar correo: ' + (respuesta.message || 'Error desconocido')
                                  });
                              }
                          },
                          error: function(xhr, textStatus, errorThrown) {
                              Swal.close();
                              console.error("Error en envío de correo:", textStatus, errorThrown);
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Error de comunicación con el servidor'
                              });
                          }
                      });
                  }
              });
          });

      }); // FIN DOCUMENT READY

      /*===================================================================*/
      // FUNCIÓN PARA TRAER EL DETALLE DE UN PRÉSTAMO
      /*===================================================================*/
      function Traer_Detalle(nro_prestamo) {
          console.log("Cargando detalle para préstamo:", nro_prestamo);
          
          // Verificar que la tabla existe
          if (!$("#prestamo_detalle").length) {
              console.error("La tabla #prestamo_detalle no existe en el DOM");
              return;
          }
          
          // Verificar que la tabla tenga thead y tbody
          if (!$("#prestamo_detalle thead").length || !$("#prestamo_detalle tbody").length) {
              console.error("La tabla #prestamo_detalle no tiene la estructura correcta (thead/tbody)");
              return;
          }
          
          // Verificar número de columnas
          var numColumnas = $("#prestamo_detalle thead th").length;
          console.log("Número de columnas en la tabla:", numColumnas);
          
          if ($.fn.DataTable.isDataTable('#prestamo_detalle')) {
              $('#prestamo_detalle').DataTable().destroy();
          }
          
          try {
              prestamo_detalle_dt = $("#prestamo_detalle").DataTable({
                  destroy: true,
                  dom: 'tp',
              ajax: {
                  url: "ajax/admin_prestamos_ajax.php",
                  type: "POST",
                  data: {
                      'accion': 2,
                      'nro_prestamo': nro_prestamo
                  },
                  dataSrc: function(json) {
                      console.log("Datos recibidos para prestamo_detalle:", json);
                      console.log("Tipo de datos:", typeof json);
                      console.log("Es array:", Array.isArray(json));
                      if (json && json.length > 0) {
                          console.log("Primer elemento:", json[0]);
                      }
                      return json;
                  },
                  error: function(xhr, error, thrown) {
                      console.error("Error al cargar detalle:", error);
                      console.error("Respuesta del servidor:", xhr.responseText);
                      console.error("Estado:", xhr.status);
                  }
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
              columnDefs: [
                  { targets: [0, 1], visible: false },
                  { 
                      targets: 6, 
                      render: function(data) {
                          if (data == 'pagada') return "<span class='badge badge-success'>pagada</span>";
                          if (data == 'parcialmente_pagada') return "<span class='badge badge-info'>parcialmente pagada</span>";
                          return "<span class='badge badge-danger'>pendiente</span>";
                      }
                  },
                  { 
                      targets: 7, 
                      sortable: false, 
                      render: function(data, type, row) {
                          if (row.pdetalle_estado_cuota == 'pagada') {
                              return "<center>" +
                                  "<span class='text-secondary px-1 disabled' data-bs-toggle='tooltip' data-bs-placement='top' title='Cuota Pagada'> " +
                                  "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnImprimirRecibo text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Ticket'> " +
                                  "<i class='far fa-file-alt fs-6'></i> " +
                                  "</span>" +
                                  "<span class='btnEnviarCorreoCuotaP text-warning px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Enviar Recibo por Correo'> " +
                                  "<i class='fas fa-envelope fs-6'></i> " +
                                  "</span>" +
                                  "</center>";
                          } else if (row.pdetalle_estado_cuota == 'parcialmente_pagada') {
                              return "<center>" +
                                  "<span class='btnPagarCuota text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Pagar Cuota Completa'> " +
                                  "<i class='fas fa-dollar-sign fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnAbonarCuota text-warning px-1' style='cursor:pointer;' data-bs-toggle='modal' data-bs-target='#modal_abonar_cuota' title='Hacer Abono'> " +
                                  "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnImprimirVoucherAbono text-info px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Imprimir Voucher de Abono'> " +
                                  "<i class='far fa-file-alt fs-6'></i> " +
                                  "</span>" +
                                  "</center>";
                          } else {
                              return "<center>" +
                                  "<span class='btnPagarCuota text-success px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Pagar Cuota Completa'> " +
                                  "<i class='fas fa-dollar-sign fs-6'></i> " +
                                  "</span> " +
                                  "<span class='btnAbonarCuota text-warning px-1' style='cursor:pointer;' data-bs-toggle='modal' data-bs-target='#modal_abonar_cuota' title='Hacer Abono'> " +
                                  "<i class='fas fa-hand-holding-usd fs-6'></i> " +
                                  "</span> " +
                                  "<span class='text-secondary px-1' data-bs-toggle='tooltip' data-bs-placement='top' title='Sin comprobantes'> " +
                                  "<i class='far fa-file-alt fs-6'></i> " +
                                  "</span>" +
                                  "</center>";
                          }
                      }
                  }
              ],
              language: idioma_espanol,
              select: true
              });
          } catch (error) {
              console.error("Error al inicializar DataTables:", error);
              console.error("Stack trace:", error.stack);
              
              // Mostrar mensaje de error al usuario
              $("#prestamo_detalle tbody").html(
                  "<tr><td colspan='8' class='text-center text-danger'>Error al cargar los datos. Consulte la consola para más detalles.</td></tr>"
              );
          }
      }

      /*===================================================================*/
      // FUNCIÓN PARA CARGAR CANTIDAD DE CUOTAS PAGADAS
      /*===================================================================*/
      function CargarCantCuotasPagadas() {
          var nro_prestamo = $('#text_nro_prestamo_d').val();
          
          if (!nro_prestamo) {
              console.log("No hay número de préstamo para cargar cuotas pagadas");
              return;
          }

          $.ajax({
              async: false,
              url: "ajax/admin_prestamos_ajax.php",
              method: "POST",
              data: {
                  'accion': 4,
                  'nro_prestamo': nro_prestamo
              },
              dataType: 'json',
              success: function(respuesta) {
                  console.log("Cuotas pagadas:", respuesta);

                  if (respuesta && respuesta.pdetalle_estado_cuota !== undefined) {
                      $("#text_cuotas_pagadas__d").val(respuesta.pdetalle_estado_cuota);

                      if (respuesta.pendiente == 0) {
                          $("#btnLiquidar").hide();
                      } else {
                          $("#btnLiquidar").show();
                      }
                  }
              },
              error: function(xhr, status, error) {
                  console.error("Error al cargar cuotas pagadas:", error);
              }
          });
      }

      /*===================================================================*/
      // FUNCIÓN PARA LIQUIDAR TOTALMENTE EL PRÉSTAMO
      /*===================================================================*/
      function LiquidarCuotas() {
          var nro_prestamo = $("#text_nro_prestamo_d").val();
          var arreglo_cuota = [];

          // Recopilar todas las cuotas pendientes
          $("#prestamo_detalle tbody tr").each(function(i, e) {
              var cuota = $(this).find('td').eq(2).text(); // Columna de número de cuota
              var estado = $(this).find('td').eq(6).text(); // Columna de estado
              
              if (cuota && !estado.includes('pagada')) {
                  arreglo_cuota.push(cuota);
              }
          });

          var pdetalle_nro_cuota = arreglo_cuota.join(',');
          console.log("Cuotas a liquidar:", pdetalle_nro_cuota);

          if (arreglo_cuota.length === 0) {
              Toast.fire({
                  icon: 'info',
                  title: 'No hay cuotas pendientes para liquidar'
              });
              return;
          }

          Swal.fire({
              title: '¿Está seguro que desea liquidar totalmente el préstamo "' + nro_prestamo + '"?',
              text: 'Se pagarán ' + arreglo_cuota.length + ' cuotas pendientes',
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#28a745',
              cancelButtonColor: '#dc3545',
              confirmButtonText: 'Sí, Liquidar',
              cancelButtonText: 'Cancelar',
          }).then((result) => {

              if (result.isConfirmed) {
                  $.ajax({
                      url: "ajax/admin_prestamos_ajax.php",
                      method: "POST",
                      data: {
                          accion: 5,
                          nro_prestamo: nro_prestamo,
                          pdetalle_nro_cuota: pdetalle_nro_cuota
                      },
                      dataType: 'json',
                      success: function(respuesta) {
                          console.log("Respuesta liquidación:", respuesta);

                          if (respuesta == "ok") {
                              Toast.fire({
                                  icon: 'success',
                                  title: 'Préstamo liquidado correctamente'
                              });
                              
                              CargarCantCuotasPagadas();
                              prestamo_detalle_dt.ajax.reload();
                              tbl_ls_prestamos.ajax.reload();
                          } else {
                              Toast.fire({
                                  icon: 'error',
                                  title: 'Error al liquidar el préstamo'
                              });
                          }
                      },
                      error: function(xhr, textStatus, errorThrown) {
                          console.error("Error en liquidación:", textStatus, errorThrown);
                          Toast.fire({
                              icon: 'error',
                              title: 'Error de comunicación con el servidor'
                          });
                      }
                  });
              }
          });
      }

      /*===================================================================*/
      // FUNCIÓN PARA ENVIAR WHATSAPP
      /*===================================================================*/
      function enviarWhatsApp(datosWhatsApp) {
          try {
              if (datosWhatsApp && datosWhatsApp.telefono && datosWhatsApp.mensaje) {
                  // Limpiar número de teléfono (quitar espacios, guiones, etc.)
                  var telefono = datosWhatsApp.telefono.replace(/\D/g, '');
                  
                  // Asegurar formato internacional (agregar código de país si no existe)
                  if (!telefono.startsWith('591')) { // Código de Bolivia
                      telefono = '591' + telefono;
                  }
                  
                  // Codificar mensaje para URL
                  var mensaje = encodeURIComponent(datosWhatsApp.mensaje);
                  
                  // Construir URL de WhatsApp
                  var urlWhatsApp = 'https://api.whatsapp.com/send?phone=' + telefono + '&text=' + mensaje;
                  
                  // Mostrar confirmación antes de enviar
                  Swal.fire({
                      title: '¿Enviar mensaje por WhatsApp?',
                      html: '<strong>Número:</strong> +' + telefono + '<br><br><strong>Mensaje:</strong><br>' + datosWhatsApp.mensaje,
                      icon: 'question',
                      showCancelButton: true,
                      confirmButtonColor: '#25D366',
                      cancelButtonColor: '#dc3545',
                      confirmButtonText: '<i class="fab fa-whatsapp"></i> Enviar',
                      cancelButtonText: 'Cancelar'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          // Abrir WhatsApp en nueva ventana
                          window.open(urlWhatsApp, '_blank');
                          
                          Toast.fire({
                              icon: 'success',
                              title: 'Mensaje enviado a WhatsApp'
                          });
                      }
                  });
              } else {
                  console.warn("Datos de WhatsApp incompletos:", datosWhatsApp);
              }
          } catch (error) {
              console.error("Error al enviar WhatsApp:", error);
              Toast.fire({
                  icon: 'error',
                  title: 'Error al enviar mensaje por WhatsApp'
              });
          }
      }

      // Definición del idioma español para DataTables
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
