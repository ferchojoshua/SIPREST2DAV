<!-- Content Header (Page header) -->
<div class="content-header">

	<div class="container-fluid">

		<div class="row mb-2">

			<div class="col-sm-6">

				<h4 class="m-0">Administrar Modulos y Perfiles</h4>

			</div><!-- /.col -->

			<div class="col-sm-6">

				<ol class="breadcrumb float-sm-right">

					<li class="breadcrumb-item"><a href="#">Inicio</a>
					</li>

					<li class="breadcrumb-item active">Administrar Modulos y Perfiles</li>

				</ol>

			</div><!-- /.col -->

		</div><!-- /.row -->

	</div>

</div>

<div class="content">

	<div class="container-fluid">

		<ul class="nav nav-tabs" id="tabs-asignar-modulos-perfil" role="tablist">

			<li class="nav-item">
				<a class="nav-link" id="content-perfiles-tab" data-toggle="pill" href="#content-perfiles" role="tab" aria-controls="content-perfiles" aria-selected="false">Perfiles</a>
			</li>

			<li class="nav-item">
				<a class="nav-link " id="content-modulos-tab" data-toggle="pill" href="#content-modulos" role="tab" aria-controls="content-modulos" aria-selected="false">Modulos</a>
			</li>

			<li class="nav-item">
				<a class="nav-link active" id="content-modulo-perfil-tab" data-toggle="pill" href="#content-modulo-perfil" role="tab" aria-controls="content-modulo-perfil" aria-selected="false">Asignar Modulo a Perfil</a>
			</li>

		</ul>

		<div class="tab-content" id="tabsContent-asignar-modulos-perfil">
			<!--============================================================================================================================================
            CONTENIDO PARA PERFILES 
            =============================================================================================================================================-->
			<div class="tab-pane fade mt-4 px-4" id="content-perfiles" role="tabpanel" aria-labelledby="content-perfiles-tab">
				<div class="row">
					<!--LISTADO DE PERFILES -->
					<div class="col-md-12">
						<div class="card card-info card-outline shadow">

							<div class="card-header">

								<h3 class="card-title">Listado de Perfiles</h3>
								<button class="btn btn-info btn-sm float-right" id="abrirmodalPerfil"><i class="fas fa-plus"></i>
									Nuevo</button>

							</div>

							<div class="card-body">
								<!-- <div class="table-responsive"> -->
								<table id="tblPerfiles" class="table display table-hover text-nowrap compact  w-100  rounded ">

									<thead class="bg-info text-left">
										<th>Id</th>
										<th>Perfil</th>
										<th>Estado</th>
										<th class="text-center">Opciones</th>
									</thead>
									<tbody class="small text left">

									</tbody>

								</table>

								<!-- </div> -->



							</div>

						</div>

					</div>
				</div>
			</div>


			<!--============================================================================================================================================
            CONTENIDO PARA MODULOS 
            =============================================================================================================================================-->
			<div class="tab-pane fade  mt-4 px-4" id="content-modulos" role="tabpanel" aria-labelledby="content-modulos-tab">

				<div class="row">

					<!--LISTADO DE MODULOS -->
					<div class="col-md-8">

						<div class="card card-info card-outline shadow">

							<div class="card-header">

								<h3 class="card-title">Listado de Modulos</h3>
								<button class="btn btn-info btn-sm float-right" id="abrirmodal"><i class="fas fa-plus"></i>
									Nuevo</button>

							</div>

							<div class="card-body">
								<!-- <div class="table-responsive"> -->
								<table id="tblModulos" class="table display table-hover text-nowrap compact  w-100  rounded">

									<thead class="bg-info text-left">
										<th class="text-center">Acciones</th>
										<th>id</th>
										<th>orden</th>
										<th>Modulo</th>
										<th>Modulo Padre</th>
										<th>Vista</th>
										<th>Icono</th>
									</thead>
									<tbody class="small text left">

									</tbody>

								</table>

								<!-- </div> -->



							</div>

						</div>

					</div>
					<!--/.FIN-->



					<!--ARBOL DE MODULOS PARA REORGANIZAR -->
					<div class="col-md-4">

						<div class="card card-info card-outline shadow">

							<div class="card-header">

								<h3 class="card-title"> Organizar Módulos</h3>

							</div>

							<div class="card-body">

								<div class="">

									<div>Modulos del Sistema</div>

									<div class="" id="arbolModulos"></div>

								</div>

								<hr>

								<div class="row">

									<div class="row m-2">

										<div class="col-md-6">

											<button class="btn btn-success btn-sm  m-0 p-0 w-100" id="btnReordenarModulos">Organizar Modulos</button>

										</div>

										<div class="col-md-6">

											<button class="btn btn-warning btn-sm m-0 p-0 w-100" id="btnReiniciar">Estado Inicial</button>

										</div>

									</div>

									<!-- <div class="col-md-12">

										<div class="text-center">

											<button id="btnReordenarModulos" class="btn btn-success btn-sm" style="width: 100%;">Organizar Módulos</button>

											<button id="btnReiniciar" class="btn btn-sm btn-warning mt-3 " style="width: 100%;">Estado Inicial</button>
										</div>

									</div> -->

								</div>

							</div>

						</div>

					</div>
					<!--/. col-md-3 -->

				</div>
				<!--/.row -->

			</div><!-- /#content-modulos -->


			<!--============================================================================================================================================
            CONTENIDO PARA ASIGNAR MODULOS  A PERFILES
            =============================================================================================================================================-->
			<div class="tab-pane fade active show mt-4 px-4" id="content-modulo-perfil" role="tabpanel" aria-labelledby="content-modulo-perfil-tab">

				<div class="row">

					<div class="col-md-8">

						<div class="card card-info card-outline shadow">

							<div class="card-header">

								<h3 class="card-title"></i> Listado de Perfiles</h3>

							</div>

							<div class="card-body">
								<div class=" table-responsive">
									<table id="tbl_perfiles_asignar" class="table display table-hover text-nowrap compact  w-100  rounded">

										<thead class="bg-info text-left">
											<th>id Perfil</th>
											<th>Perfil</th>
											<th>Estado</th>
											<th class="text-center">Opciones</th>
										</thead>

										<tbody class="small text left">

										</tbody>

									</table>

								</div>



							</div>

						</div>

					</div>

					<div class="col-md-4">

						<div class="card card-info card-outline shadow" style="display:none" id="card-modulos">

							<div class="card-header">

								<h3 class="card-title"></i> Modulos del Sistema</h3>

							</div>

							<div class="card-body" id="card-body-modulos">

								<div class="row m-2">

									<div class="col-md-6">

										<button class="btn btn-success btn-small  m-0 p-0 w-100" id="marcar_modulos">Marcar todo</button>

									</div>

									<div class="col-md-6">

										<button class="btn btn-danger btn-small m-0 p-0 w-100" id="desmarcar_modulos">Desmarcar todo</button>

									</div>

								</div>

								<!-- AQUI SE CARGAN TODOS LOS MODULOS DEL SISTEMA -->
								<div id="modulos" class="demo"></div>

								<div class="row m-2">

									<div class="col-md-12">

										<div class="form-group">

											<label>Seleccione el modulo de inicio</label>
											<select class="custom-select" id="select_modulos">
											</select>

										</div>

									</div>

								</div>

								<div class="row m-2">

									<div class="col-md-12">

										<button class="btn btn-success btn-small w-50 text-center" id="asignar_modulos">Asignar</button>

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


<!-- MODAL REGISTRAR MODULOS-->
<div class="modal fade" id="modal_registro_modulo" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header bg-gray py-1 align-items-center">
				<h5 class="modal-title" id="titulo_modal_modulo">Registro de Modulos</h5>
				<button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="needs-validation" novalidate>
					<div class="row">
						<div class="col-md-12">

							<div class="form-group mb-2">

								<label for="iptModulo" class="m-0 p-0 col-sm-12 col-form-label-sm"><span class="small">Modulo</span><span class="text-danger">*</span></label>

								<div class="input-group  m-0">
									<input type="text" id="id_modulo" hidden>
									<input type="text" class="form-control form-control-sm" name="iptModulo" id="iptModulo" required>
									<div class="input-group-append">
										<span class="input-group-text bg-info"><i class="fas fa-laptop text-white fs-6"></i></span>
									</div>
									<div class="invalid-feedback">Debe ingresar el modulo</div>
								</div>

							</div>

						</div>

						<div class="col-md-12">

							<div class="form-group mb-2">

								<label for="iptVistaModulo" class="m-0 p-0 col-sm-12 col-form-label-sm"><span class="small">Vista PHP</span></label>
								<div class="input-group  m-0">
									<input type="text" class="form-control form-control-sm" name="iptVistaModulo" id="iptVistaModulo">
									<div class="input-group-append">
										<span class="input-group-text bg-info"><i class="fas fa-code text-white fs-6"></i></span>
									</div>
								</div>

							</div>

						</div>

						<div class="col-md-12">

							<div class="form-group mb-2">

								<label for="iptIconoModulo" class="m-0 p-0 col-sm-12 col-form-label-sm"><span class="small">Icono</span><span class="text-danger">*</span></label>
								<div class="input-group  m-0">
									<input type="text" placeholder="<i class='far fa-circle'></i>" value="<i class='far fa-circle'></i>" name="iptIconoModulo" class="form-control form-control-sm" id="iptIconoModulo" required>
									<div class="input-group-append">
										<span class="input-group-text bg-info" id="spn_icono_modulo"><i class="far fa-circle"></i></span>
									</div>
									<div class="invalid-feedback">Debe ingresar el ícono del modulo</div>
								</div>

							</div>

						</div>





					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar">Cerrar</button>
				<button type="button" class="btn btn-info btn-sm" id="btnregistrar">Registrar</button>
			</div>
		</div>
	</div>
</div>
<!-- fin Modal -->

<!-- MODAL REGISTRAR PERFIL-->
<div class="modal fade" id="modal_registro_perfil" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content">
			<div class="modal-header bg-gray py-1 align-items-center">
				<h5 class="modal-title" id="titulo_modal_perfil">Registro de Perfiles</h5>
				<button type="button" class="close  text-white border-0 fs-5" id="btncerrarmodal_perfil" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="needs-validation" novalidate>
					<div class="row">
						<div class="col-md-12">

							<div class="form-group mb-2">

								<label for="iptPerfil" class="m-0 p-0 col-sm-12 col-form-label-sm"><span class="small">Descripcion</span><span class="text-danger">*</span></label>

								<div class="input-group  m-0">
									<input type="text" id="id_perfil" hidden>
									<input type="text" class="form-control form-control-sm" name="iptPerfil" id="iptPerfil" placeholder="Nombre del perfil" required>
									<div class="invalid-feedback">Debe ingresar el Perfil</div>
								</div>

							</div>

						</div>

					</div>
				</form>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btncerrar_perfil">Cerrar</button>
				<button type="button" class="btn btn-info btn-sm" id="btnregistrar_perfil">Registrar</button>
			</div>

		</div>
	</div>
</div>
<!-- fin Modal -->

<script>
	/* =============================================================
		VARIABLES GLOBALES
		============================================================= */
	var tbl_perfiles_asignar, tbl_modulos, tbl_perfil, titulo_modal, modulos_usuario, modulos_sistema;
	var accion;

	var Toast = Swal.mixin({
		toast: true,
		position: 'top',
		showConfirmButton: false,
		timer: 3000
	});

	$(document).ready(function() {

		/*===================================================================*/
		//EVENTO PARA ABRIR EL MODAL DE REGISTRAR
		/*===================================================================*/
		$("#abrirmodal").on('click', function() {
			AbrirModalRegistroModulos();
		})

		$("#abrirmodalPerfil").on('click', function() {
			AbrirModalRegistroPerfiles();
		})

		$('.nav-tabs a').on('shown.bs.tab', function(event){
		var referencia = $(event.target).attr("href"); 
		if(referencia == "#content-perfiles")
				tbl_perfil.columns.adjust();
		if(referencia == "#content-modulos")
			tbl_modulos.columns.adjust();
		if(referencia == "#content-modulo-perfil")
			tbl_perfiles_asignar.columns.adjust();
		});



		/* =============================================================
		FUNCIONES PARA LAS CARGAS INICIALES DE DATATABLES, ARBOL DE MODULOS Y REAJUSTE DE CABECERAS DE DATATABLES
		============================================================= */
		cargarDataTables();
		ajustarHeadersDataTables($('#tblModulos'));
		ajustarHeadersDataTables($('#tblPerfiles'));
		iniciarArbolModulos();


		/* =============================================================
		VARIABLES PARA REGISTRAR EL PERFIL Y LOS MODULOS SELECCIOMADOS
		============================================================= */
		var idPerfil = 0;
		var selectedElmsIds = [];

		/* =============================================================
        EVENTO PARA SELECCIONAR UN PERFIL DEL DATATABLE Y MOSTRAR LOS MODULOS ASIGNADOS EN EL ARBOL DE MODULOS
        ============================================================= */
		$('#tbl_perfiles_asignar tbody').on('click', '.btnSeleccionarPerfil', function() {

			var data = tbl_perfiles_asignar.row($(this).parents('tr')).data();
			//console.log(data);

			if ($(this).parents('tr').hasClass('selected')) {

				$(this).parents('tr').removeClass('selected');

				$('#modulos').jstree("deselect_all", false);

				$("#select_modulos option").remove();

				idPerfil = 0;

				$("#card-modulos").css("display", "none");

			} else {

				tbl_perfiles_asignar.$('tr.selected').removeClass('selected');

				$(this).parents('tr').addClass('selected');

				idPerfil = data[0];

				$("#card-modulos").css("display", "block"); //MOSTRAMOS EL ALRBOL DE MODULOS DEL SISTEMA

				// alert(idPerfil);

				$.ajax({
					async: false,
					url: "ajax/modulo_ajax.php",
					method: 'POST',
					data: {
						accion: 2,
						id_perfil: idPerfil
					},
					dataType: 'json',
					success: function(respuesta) {
						// console.log(respuesta);

						modulos_usuario = respuesta;

						seleccionarModulosPerfil(idPerfil);
					}
				});

			}
		})


		/* =============================================================
        EVENTO QUE SE DISPARA CADA VEZ QUE HAY UN CAMBIO EN EL ARBOL DE MODULOS
        ============================================================= */
		$("#modulos").on("changed.jstree", function(evt, data) {

			$("#select_modulos option").remove();

			var selectedElms = $('#modulos').jstree("get_selected", true);

			// console.log(selectedElms);

			$.each(selectedElms, function() {

				for (let i = 0; i < modulos_sistema.length; i++) {

					if (modulos_sistema[i]["id"] == this.id && modulos_sistema[i]["vista"]) {

						$('#select_modulos').append($('<option>', {
							value: this.id,
							text: this.text
						}));
					}
				}

			})

			if ($("#select_modulos").has('option').length <= 0) {

				$('#select_modulos').append($('<option>', {
					value: 0,
					text: "--No hay modulos seleccionados--"
				}));
			}


		})

		/* =============================================================
        EVENTO PARA MARCAR TODOS LOS CHECKBOX DEL ARBOL DE MODULOS
        ============================================================= */
		$("#marcar_modulos").on('click', function() {
			$('#modulos').jstree('select_all');
		})

		/* =============================================================
		EVENTO PARA DESMARCAR TODOS LOS CHECKBOX DEL ARBOL DE MODULOS
		============================================================= */
		$("#desmarcar_modulos").on('click', function() {

			$('#modulos').jstree("deselect_all", false);
			$("#select_modulos option").remove();

			$('#select_modulos').append($('<option>', {
				value: 0,
				text: "--No hay modulos seleccionados--"
			}));
		})


		/* =============================================================
        REGISTRO EN BASE DE DATOS DE LOS MODULOS ASOCIADOS AL PERFIL 
        ============================================================= */
		$("#asignar_modulos").on('click', function() {

			// alert("entro al evento")
			selectedElmsIds = []
			var selectedElms = $('#modulos').jstree("get_selected", true);

			$.each(selectedElms, function() {

				selectedElmsIds.push(this.id);

				if (this.parent != "#") {
					selectedElmsIds.push(this.parent);
				}

			});

			//quitamos valores duplicados
			let modulosSeleccionados = [...new Set(selectedElmsIds)];

			let modulo_inicio = $("#select_modulos").val();

			// console.log(modulosSeleccionados);

			if (idPerfil != 0 && modulosSeleccionados.length > 0) {
				registrarPerfilModulos(modulosSeleccionados, idPerfil, modulo_inicio);
			} else {
				Swal.fire({
					position: 'center',
					icon: 'warning',
					title: 'Debe seleccionar el perfil y modulos a registrar',
					showConfirmButton: false,
					timer: 3000
				})
			}

		})




		fnCargarArbolModulos();

		/* =============================================================
        REORGANIZAR MODULOS DEL SISTEMA
        ============================================================= */
		$("#btnReordenarModulos").on('click', function() {
			fnOrganizarModulos();
		})

		/* =============================================================
        REINICIALIZAR MODULOS DEL SISTEMA EN EL JSTREE
        ============================================================= */
		$("#btnReiniciar").on('click', function() {
			actualizarArbolModulos();
		})

		/*=============================================================
        VISTA PREVIA DEL ICONO DE LA VISTA
        ==============================================================*/
		$("#iptIconoModulo").change(function() {

			$("#spn_icono_modulo").html($("#iptIconoModulo").val())

			if ($("#iptIconoModulo").val().length === 0) {
				$("#spn_icono_modulo").html("<i class='far fa-circle'></i>")
			}
		})


		//////////////////////////////////////////////////////////////////////////////////////PARA MODULOSS////////////////////////////////////////////////////////////
		/*===================================================================*/
		//EVENTO QUE GUARDA LOS DATOS DEL MODULO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
		/*===================================================================*/
		document.getElementById("btnregistrar").addEventListener("click", function() {

			// Get the forms we want to add validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {

				//si se ingresan todos los datos 
				if (form.checkValidity() === true) {

					// console.log("Listo para registrar el producto")
					Swal.fire({
						title: 'Está seguro de registrar el Modulo?',
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
							datos.append("id", $("#id_modulo").val()); //id
							datos.append("modulo", $("#iptModulo").val()); //modulo
							datos.append("vista", $("#iptVistaModulo").val()); //vista
							datos.append("icon_menu", $('#spn_icono_modulo i').attr('class'));

							//alert($('#spn_icono_modulo i').attr('class'));



							if (accion == 5) {
								var titulo_msj = "El Modulo  se registro correctamente"
							}

							if (accion == 6) {
								var titulo_msj = "El Modulo se actualizo correctamente"
							}
							$.ajax({
								url: "ajax/modulo_ajax.php",
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
											//title: 'El Modulo se registro de forma correcta'
											title: titulo_msj
										});

										tbl_modulos.ajax.reload(); //recargamos el datatable
										//recargamos arbol de modulos - MANTENIMIENTO MODULOS
										actualizarArbolModulos();
										tbl_perfiles_asignar.ajax.reload(); 

										//recargamos arbol de modulos - MANTENIMIENTO MODULOS ASIGNADOS A PERFILES                                
										actualizarArbolModulosPerfiles();


										$("#modal_registro_modulo").modal('hide');

										$("#iptModulo").val("");
										$("#iptVistaModulo").val("");
										$("#iptIconoModulo").val("");
										$("#id_modulo").val("");

										$(".needs-validation").removeClass("was-validated");

									} else {
										Toast.fire({
											icon: 'error',
											title: 'El Modulo no se pudo registrar'
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
            EVENTO AL DAR CLICK EN EL BOTON EDITAR MODULOS
           =========================================================================================*/
		$("#tblModulos tbody").on('click', '.btnSeleccionarModulo', function() {
			//alert('entro');

			accion = 6; //seteamos la accion para editar
			$("#modal_registro_modulo").modal({
				backdrop: 'static',
				keyboard: false
			});
			$("#modal_registro_modulo").modal('show'); //modal de registrar producto
			$("#titulo_modal_modulo").html('Actualizar Modulo');
			$("#btnregistrar").html('Actualizar');

			//var data = table.row($(this).parents('tr')).data();

			if (tbl_modulos.row(this).child.isShown()) {
				var data = tbl_modulos.row(this).data();
			} else {
				var data = tbl_modulos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
				console.log("🚀 ~ file: modulo perfiles.php ~ line 751 ~ $ ~ data", data);
			}

			$("#id_modulo").val(data[1]);
			$("#iptModulo").val(data[3]);
			$("#iptVistaModulo").val(data[5]);
			//$("#iptIconoModulo").val(data[6]);
			$("#iptIconoModulo").val("<i class='"+data[6]+"'></i>");
			//("<i class='far fa-circle'></i>")


		})


		/* ======================================================================================
                EVENTO AL DAR CLICK EN EL BOTON ELIMINAR MODULO
                =========================================================================================*/
		$("#tblModulos tbody").on('click', '.btnEliminarModulo', function() {

			accion = 7; //seteamos la accion para editar

			if (tbl_modulos.row(this).child.isShown()) {
				var data = tbl_modulos.row(this).data();
			} else {
				var data = tbl_modulos.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
				//   console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
			}

			var id = data[1];
			//console.log(id);

			Swal.fire({
				title: 'Desea Eliminar eL Modulo "' + data[3] + '" ?',
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
					datos.append("id", id); //jalamos el codigo que declaramos mas arriba

					/* NO SE ELIMNARA SI ALGUN USUARIO TIENE ACCESO A ESE MODULO
					*/
					$.ajax({
						url: "ajax/modulo_ajax.php",
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
									title: 'El modulo se Elimino de forma correcta'
								});

								tbl_modulos.ajax.reload(); //recargamos el datatable
								//recargamos arbol de modulos - MANTENIMIENTO MODULOS
								actualizarArbolModulos();
								tbl_perfiles_asignar.ajax.reload(); 

								//recargamos arbol de modulos - MANTENIMIENTO MODULOS ASIGNADOS A PERFILES                                
								actualizarArbolModulosPerfiles();

							} else {
								Toast.fire({
									icon: 'error',
									title: 'El modulo no se pudo Eliminar'
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
		$("#btncerrarmodal, #btncerrar").on('click', function() {
			$("#id_modulo").val("");
			$("#iptModulo").val("");
			$("#iptVistaModulo").val("");
			$("#iptIconoModulo").val("");
			$("#spn_icono_modulo").val("");
		})

		/*===================================================================*/
		//EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
		/*===================================================================*/
		document.getElementById("btncerrar").addEventListener("click", function() {
			$(".needs-validation").removeClass("was-validated");
		})
		document.getElementById("btncerrarmodal").addEventListener("click", function() {
			$(".needs-validation").removeClass("was-validated");
		})


		//////////////////////////////////////////////////////////////////////////////////////PARA PERFILES////////////////////////////////////////////////////////////

		/*===================================================================*/
		//EVENTO QUE GUARDA Y ACTUALIZA LOS DATOS DEL MODULO, PREVIA VALIDACION DEL INGRESO DE LOS DATOS OBLIGATORIOS
		/*===================================================================*/
		document.getElementById("btnregistrar_perfil").addEventListener("click", function() {

			// Get the forms we want to add validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {

				//si se ingresan todos los datos 
				if (form.checkValidity() === true) {

					Swal.fire({
						title: 'Esta seguro de ' + titulo_modal + ' el Perfil?',
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
							datos.append("id_perfil", $("#id_perfil").val()); //id
							datos.append("descripcion", $("#iptPerfil").val()); //modulo

							if (accion == 2) {
								var titulo_msj = "El Perfil  se registro correctamente"
								//var titulo_modal = "Registrar";
							}

							if (accion == 3) {
								var titulo_msj = "El Perfil se actualizo correctamente"
								//var titulo_modal = "Actualizar";
							}
							$.ajax({
								url: "ajax/perfil_ajax.php",
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
											//title: 'El Perfil se registro de forma correcta'
											title: titulo_msj
										});

										tbl_perfil.ajax.reload(); //recargamos el datatable 
										tbl_perfiles_asignar.ajax.reload();


										$("#modal_registro_perfil").modal('hide');

										$("#id_perfil").val("");
										$("#iptPerfil").val("");

										$(".needs-validation").removeClass("was-validated");

									} else {
										Toast.fire({
											icon: 'error',
											title: 'El Perfil no se pudo registrar'
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
            EVENTO AL DAR CLICK EN EL BOTON EDITAR PERFIL
           =========================================================================================*/
		$("#tblPerfiles tbody").on('click', '.btnEditarPerfil', function() {
			//alert('entro');

			accion = 3; //seteamos la accion para editar
			titulo_modal = "Actualizar";
			$("#modal_registro_perfil").modal({
				backdrop: 'static',
				keyboard: false
			});
			$("#modal_registro_perfil").modal('show'); //abrimos el modal
			$("#titulo_modal_perfil").html('Actualizar Perfil');
			$("#btnregistrar_perfil").html('Actualizar');



			//var data = table.row($(this).parents('tr')).data();

			if (tbl_perfil.row(this).child.isShown()) {
				var data = tbl_perfil.row(this).data();
			} else {
				var data = tbl_perfil.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
				//console.log("🚀 ~ file: modulo perfiles.php ~ line 751 ~ $ ~ data", data);
			}

			$("#id_perfil").val(data[0]);
			$("#iptPerfil").val(data[1]);



		})



		/* ======================================================================================
                EVENTO AL DAR CLICK EN EL BOTON ELIMINAR MODULO
                =========================================================================================*/
		$("#tblPerfiles tbody").on('click', '.btnEliminarPerfil', function() {

			accion = 4; //seteamos la accion para editar

			if (tbl_perfil.row(this).child.isShown()) {
				var data = tbl_perfil.row(this).data();
			} else {
				var data = tbl_perfil.row($(this).parents('tr')).data(); //OBTENER EL ARRAY CON LOS DATOS DE CADA COLUMNA DEL DATATABLE
				//   console.log("🚀 ~ file: productos.php ~ line 751 ~ $ ~ data", data);
			}

			var id_perfil = data[0];
			//console.log(id);

			Swal.fire({
				title: 'Desea Eliminar eL perfil "' + data[1] + '" ?',
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
					datos.append("id_perfil", id_perfil); //jalamos el codigo que declaramos mas arriba

					$.ajax({
						url: "ajax/perfil_ajax.php",
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
									title: 'El perfil se Elimino de forma correcta'
								});

								tbl_perfil.ajax.reload(); //recargamos el datatable 
								tbl_perfiles_asignar.ajax.reload();

							} else {
								Toast.fire({
									icon: 'error',
									title: 'El perfil no se pudo Eliminar'
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
		$("#btncerrarmodal_perfil, #btncerrar_perfil").on('click', function() {
			$("#id_perfil").val("");
			$("#iptPerfil").val("");

		})

		/*===================================================================*/
		//EVENTO QUE LIMPIA LOS MENSAJES DE ALERTA DE INGRESO DE DATOS DE CADA INPUT AL CANCELAR LA VENTANA MODAL
		/*===================================================================*/
		document.getElementById("btncerrar_perfil").addEventListener("click", function() {
			$(".needs-validation").removeClass("was-validated");
		})
		document.getElementById("btncerrarmodal_perfil").addEventListener("click", function() {
			$(".needs-validation").removeClass("was-validated");
		})






	}) // FIND OCMUENT READY


	function cargarDataTables() {

		tbl_perfiles_asignar = $('#tbl_perfiles_asignar').DataTable({
			ajax: {
				async: false,
				url: 'ajax/perfil_ajax.php',
				type: 'POST',
				dataType: 'json',
				dataSrc: "",
				data: {
					accion: 1
				}
			},
			columnDefs: [{
					targets: 2,
					sortable: false,
					createdCell: function(td, cellData, rowData, row, col) {

						if (parseInt(rowData[2]) == 1) {
							$(td).html("Activo")
						} else {
							$(td).html("Inactivo")
						}

					}
				},
				{
					targets: 3,
					sortable: false,
					render: function(data, type, full, meta) {
						return "<center>" +
							"<span class='btnSeleccionarPerfil text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Seleccionar perfil'> " +
							"<i class='fas fa-check fs-5'></i> " +
							"</span> " +
							"</center>";
					}
				}
			],
			"language": idioma_espanol,
			select: true
		});


		tbl_modulos = $('#tblModulos').DataTable({

			ajax: {
				async: false,
				url: 'ajax/modulo_ajax.php',
				type: 'POST',
				dataType: 'json',
				dataSrc: "",
				data: {
					'accion': 3
				}
			},
			columnDefs: [{
				targets: 0,
				sortable: false,
				render: function(data, type, full, meta) {
					return "<center>" +
						"<span class='fas fa-edit fs-6 btnSeleccionarModulo text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Seleccionar Módulo'> " +
						"</span> " +
						"<span class='fas fa-trash fs-6 btnEliminarModulo text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Módulo'> " +
						"</span>" +
						"</center>";
				}
			}],
			scrollX: true,
			order: [
				[2, 'asc']
			],
			lengthMenu: [0, 5, 10, 15, 20, 50],
			pageLength: 20,
			"language": idioma_espanol,
			select: true
		});

		tbl_perfil = $('#tblPerfiles').DataTable({

			ajax: {
				async: false,
				url: 'ajax/perfil_ajax.php',
				type: 'POST',
				dataType: 'json',
				dataSrc: "",
				data: {
					'accion': 1
				}
			},
			columnDefs: [{
				targets: 2,
				sortable: false,
				createdCell: function(td, cellData, rowData, row, col) {

					if (parseInt(rowData[2]) == 1) {
						$(td).html("Activo")
					} else {
						$(td).html("Inactivo")
					}

				}
			}, {
				targets: 3,
				sortable: false,
				render: function(data, type, full, meta) {
					return "<center>" +
						"<span class='far fa-edit fs-6 btnEditarPerfil text-primary px-1' style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Seleccionar Módulo'> " +
						"</span> " +
						"<span class='fas fa-trash fs-6 btnEliminarPerfil text-danger px-1'style='cursor:pointer;' data-bs-toggle='tooltip' data-bs-placement='top' title='Eliminar Módulo'> " +
						"</span>" +
						"</center>";
				}
			}],
			scrollX: true,
			order: [
				[2, 'asc']
			],
			lengthMenu: [0, 5, 10, 15, 20, 50],
			pageLength: 10,
			"language": idioma_espanol,
			select: true
		});
	}

	//AJUSTAR LA CABECERA DEL DATATABLE
	function ajustarHeadersDataTables(element) {

		var observer = window.ResizeObserver ? new ResizeObserver(function(entries) {
			entries.forEach(function(entry) {
				$(entry.target).DataTable().columns.adjust();
			});
		}) : null;

		// Function to add a datatable to the ResizeObserver entries array
		resizeHandler = function($table) {
			if (observer)
				observer.observe($table[0]);
		};

		// Initiate additional resize handling on datatable
		resizeHandler(element);

	}


	function iniciarArbolModulos() {

		$.ajax({
			async: false,
			url: "ajax/modulo_ajax.php",
			method: 'POST',
			data: {
				accion: 1
			},
			dataType: 'json',
			success: function(respuesta) {

				modulos_sistema = respuesta;

				// console.log(respuesta);

				// inline data demo
				$('#modulos').jstree({
					'core': {
						"check_callback": true,
						'data': modulos_sistema
					},
					"checkbox": {
						"keep_selected_style": true,
					},
					"types": {
						"default": {
							"icon": "fas fa-laptop text-warning"
						}
					},
					"plugins": ["wholerow", "checkbox", "types", "changed"]

				}).bind("loaded.jstree", function(event, data) {
					// you get two params - event & data - check the core docs for a detailed description
					$(this).jstree("open_all");
				});

			}
		})
	}

	function seleccionarModulosPerfil(pin_idPerfil) {

		$('#modulos').jstree('deselect_all');
		// console.log("modulos_sistema",modulos_sistema);
		// console.log("modulos_usuario",modulos_usuario);
		//console.log("pin_idPerfil", pin_idPerfil);

		for (let i = 0; i < modulos_sistema.length; i++) {
			//console.log("modulos_sistema[i]['id']", modulos_sistema[i]["id"]);
			if (parseInt(modulos_sistema[i]["id"]) == parseInt(modulos_usuario[i]["id"]) && parseInt(modulos_usuario[i]["sel"]) == 1) {
				$("#modulos").jstree("select_node", modulos_sistema[i]["id"]);
			}
		}

		/*OCULTAMOS LA OPCION DE MODULOS Y PERFILES PARA EL PERFIL DE ADMINISTRADOR*/
		if (pin_idPerfil == 1) { //SOLO PERFIL ADMINISTRADOR
			$("#modulos").jstree(true).hide_node(13);
		} else {
			$('#modulos').jstree(true).show_all();
		}

	}

	function actualizarArbolModulosPerfiles() {

		$.ajax({
			async: false,
			url: "ajax/modulo.ajax.php",
			method: 'POST',
			data: {
				accion: 1
			},
			dataType: 'json',
			success: function(respuesta) {
				modulos_sistema = respuesta;

				// console.log(modulos_sistema);

				$('#modulos').jstree(true).settings.core.data = modulos_sistema;
				$('#modulos').jstree(true).refresh();
			}
		});

	}

	function registrarPerfilModulos(modulosSeleccionados, idPerfil, idModulo_inicio) {
		$.ajax({
			async: false,
			url: "ajax/perfil_modulo_ajax.php",
			method: 'POST',
			data: {
				accion: 1,
				id_modulosSeleccionados: modulosSeleccionados,
				id_Perfil: idPerfil,
				id_modulo_inicio: idModulo_inicio
			},
			dataType: 'json',
			success: function(respuesta) {

				if (respuesta > 0) {

					Swal.fire({
						position: 'center',
						icon: 'success',
						title: 'Se registro correctamente',
						showConfirmButton: false,
						timer: 2000
					})

					$("#select_modulos option").remove();
					$('#modulos').jstree("deselect_all", false);
					tbl_perfiles_asignar.ajax.reload();
					$("#card-modulos").css("display", "none");

				} else {
					Swal.fire({
						position: 'center',
						icon: 'error',
						title: 'Error al registrar',
						showConfirmButton: false,
						timer: 3000
					})

				}

			}
		});
	}


	function fnCargarArbolModulos() {

		var dataSource;

		$.ajax({
			async: false,
			url: "ajax/modulo_ajax.php",
			method: 'POST',
			data: {
				accion: 1
			},
			dataType: 'json',
			success: function(respuesta) {

				dataSource = respuesta;
				//	console.log("🚀 ~ file: modulos_perfiles.php ~ line 793 ~ fnCargarArbolModulos ~ dataSource", dataSource)
			}
		});


		/*
		$.jstree.defaults.core.check_callback:
			Determina lo que sucede cuando un usuario intenta modificar la estructura del árbol .
			Si se deja como false se impiden todas las operaciones como crear, renombrar, eliminar, mover o copiar.
			Puede configurar esto en true para permitir todas las interacciones o usar una función para tener un mejor control.
		*/
		$('#arbolModulos').jstree({
			"core": {
				"check_callback": true,
				"data": dataSource
			},
			"types": {
				"default": {
					"icon": "fas fa-laptop"
				},
				"file": {
					"icon": "fas fa-laptop"
				}
			},
			"plugins": ["types", "dnd"]
		}).bind('ready.jstree', function(e, data) {
			$('#arbolModulos').jstree('open_all')
		})



	}

	function actualizarArbolModulos() {

		$.ajax({
			async: false,
			url: "ajax/modulo_ajax.php",
			method: 'POST',
			data: {
				accion: 1
			},
			dataType: 'json',
			success: function(respuesta) {

				$('#arbolModulos').jstree(true).settings.core.data = respuesta;
				$('#arbolModulos').jstree(true).refresh();
			}
		});

	}


	function fnOrganizarModulos() {

		var array_modulos = [];

		var reg_id, reg_padre_id, reg_orden;

		var v = $("#arbolModulos").jstree(true).get_json('#', {
			'flat': true
		});

		//console.log("🚀 ~ file: modulos_perfiles.php ~ line 1074 ~ fnOrganizarModulos ~ v", v)

		for (i = 0; i < v.length; i++) {

			var z = v[i];
			//console.log("🚀 ~ file: modulos_perfiles.php ~ line 871 ~ fnOrganizarModulos ~ z", z)

			//asignamos el id, el padre Id y el nombre del modulo
			reg_id = z["id"];
			reg_padre_id = z["parent"];
			reg_orden = i;

			array_modulos[i] = reg_id + ';' + reg_padre_id + ';' + reg_orden;

		}



		//console.log("🚀 ~ file: modulos_perfiles.php ~ line 713 ~ $ ~ array_modulos", array_modulos)

		/*REGISTRAMOS LOS MODULOS CON EL NUEVO ORDENAMIENTO */
		$.ajax({
			async: false,
			url: "ajax/modulo_ajax.php",
			method: 'POST',
			data: {
				accion: 4,
				modulos: array_modulos
			},
			dataType: 'json',
			success: function(respuesta) {

				if (respuesta > 0) {

					Swal.fire({
						position: 'center',
						icon: 'success',
						title: 'Se registró correctamente',
						showConfirmButton: false,
						timer: 1500
					})

					tbl_modulos.ajax.reload();

					//recargamos arbol de modulos - MANTENIMIENTO MODULOS ASIGNADOS A PERFILES                                
					actualizarArbolModulosPerfiles();

				} else {

					Swal.fire({
						position: 'center',
						icon: 'error',
						title: 'Error al registrar',
						showConfirmButton: false,
						timer: 1500
					})

				}



			}
		});

	}



	//REGISTRAR LOS MODULOS EN LOS MODALES 

	function AbrirModalRegistroModulos() {
		//para que no se nos salga del modal haciendo click a los costados
		$("#modal_registro_modulo").modal({
			backdrop: 'static',
			keyboard: false
		});
		$("#modal_registro_modulo").modal('show'); //abrimos el modal
		$("#titulo_modal_modulo").html('Registrar Modulos');
		$("#btnregistrar").html('Registrar');
		accion = 5; // guardar
	}

	function AbrirModalRegistroPerfiles() {
		//para que no se nos salga del modal haciendo click a los costados
		$("#modal_registro_perfil").modal({
			backdrop: 'static',
			keyboard: false
		});
		$("#modal_registro_perfil").modal('show'); //abrimos el modal
		$("#titulo_modal_perfil").html('Registrar Perfil');
		$("#btnregistrar_perfil").html('Registrar');
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

	/*===================================================================*/
	// ACTIVAR ASIGNACION DE MODULOS AL CAMBIAR SELECTOR DE MODULOS
	/*===================================================================*/
	$('#select_modulos').on('change', function() {
		$('#asignar_modulos').click();
	});

	/*===================================================================*/
	// EVENTO CLICK DEL BOTON ASIGNAR MODULOS
	/*===================================================================*/
	$("#asignar_modulos").on('click', function() {
		// Aquí puedes agregar la lógica que deseas ejecutar cuando se asigna un módulo
		// Por ejemplo, una llamada AJAX para guardar la asignación
	});

</script>