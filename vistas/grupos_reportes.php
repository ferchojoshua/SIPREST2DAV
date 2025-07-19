<?php
  require_once '../controladores/grupos_reportes_controlador.php';
  require_once '../modelos/grupos_reportes_modelo.php';
?>
<section class="content-header">
  <h1>
    Administrar Grupos de Correo para Reportes
  </h1>
  <ol class="breadcrumb">
    <li><a href="inicio"><i class="fa fa-dashboard"></i> Inicio</a></li>
    <li class="active">Administrar Grupos</li>
  </ol>
</section>

<section class="content">
  <div class="box">
    <div class="box-header with-border">
      <button class="btn btn-primary" data-toggle="modal" data-target="#modalAgregarGrupo">
        Agregar Grupo
      </button>
    </div>
    <div class="box-body">
      <table class="table table-bordered table-striped dt-responsive tablas">
        <thead>
          <tr>
            <th style="width:10px">#</th>
            <th>Nombre del Grupo</th>
            <th>Descripción</th>
            <th>Miembros</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>

          <?php
          $item = null;
          $valor = null;
          $grupos = ControladorGruposReportes::ctrMostrarGrupos($item, $valor);

          foreach ($grupos as $key => $value) {
            echo '<tr>
                    <td>' . ($key + 1) . '</td>
                    <td>' . $value["grupo_nombre"] . '</td>
                    <td>' . $value["grupo_descripcion"] . '</td>';

            $miembros = ControladorGruposReportes::ctrMostrarMiembros("grupo_id", $value["grupo_id"]);
            echo '<td>' . count($miembros) . '</td>
                  <td>
                    <div class="btn-group">
                      <button class="btn btn-info btn-sm btnVerMiembros" idGrupo="' . $value["grupo_id"] . '" data-toggle="modal" data-target="#modalVerMiembros" title="Ver y administrar miembros"><i class="fa fa-users"></i></button>
                      <button class="btn btn-warning btn-sm btnEditarGrupo" idGrupo="' . $value["grupo_id"] . '" data-toggle="modal" data-target="#modalEditarGrupo" title="Editar grupo"><i class="fa fa-pencil"></i></button>
                      <button class="btn btn-danger btn-sm btnEliminarGrupo" idGrupo="' . $value["grupo_id"] . '" title="Eliminar grupo"><i class="fa fa-times"></i></button>
                    </div>  
                  </td>
                </tr>';
          }
          ?>

        </tbody>
      </table>
    </div>
  </div>
</section>

<!--=====================================
MODAL AGREGAR GRUPO
======================================-->
<div id="modalAgregarGrupo" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <div class="modal-header" style="background:#3c8dbc; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Agregar Grupo</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <!-- ENTRADA PARA EL NOMBRE -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-object-group"></i></span>
                <input type="text" class="form-control" name="nuevoNombre" placeholder="Ingresar nombre del grupo" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-commenting"></i></span>
                <input type="text" class="form-control" name="nuevaDescripcion" placeholder="Ingresar descripción (opcional)">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar Grupo</button>
        </div>
        <?php
        $crearGrupo = new ControladorGruposReportes();
        $crearGrupo->ctrCrearGrupo();
        ?>
      </form>
    </div>
  </div>
</div>

<!--=====================================
MODAL EDITAR GRUPO
======================================-->
<div id="modalEditarGrupo" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post">
        <div class="modal-header" style="background:#f39c12; color:white">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Editar Grupo</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            <input type="hidden" id="idGrupo" name="idGrupo">
            <!-- ENTRADA PARA EL NOMBRE -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-object-group"></i></span>
                <input type="text" class="form-control" id="editarNombre" name="editarNombre" required>
              </div>
            </div>
            <!-- ENTRADA PARA LA DESCRIPCIÓN -->
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-commenting"></i></span>
                <input type="text" class="form-control" id="editarDescripcion" name="editarDescripcion">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Salir</button>
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
        <?php
        $editarGrupo = new ControladorGruposReportes();
        $editarGrupo->ctrEditarGrupo();
        ?>
      </form>
    </div>
  </div>
</div>

<!--=====================================
MODAL VER MIEMBROS
======================================-->
<div id="modalVerMiembros" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#00c0ef; color:white">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="tituloModalMiembros">Miembros del Grupo</h4>
      </div>
      <div class="modal-body">
        <div class="box-body">
          <!-- FORMULARIO PARA AGREGAR MIEMBRO -->
          <form id="formAgregarMiembro">
             <input type="hidden" id="idGrupoParaMiembro" name="idGrupoParaMiembro">
             <div class="row">
                <div class="col-sm-5">
                  <div class="form-group">
                    <input type="email" class="form-control" id="nuevoEmailMiembro" placeholder="correo@ejemplo.com" required>
                  </div>
                </div>
                <div class="col-sm-5">
                  <div class="form-group">
                    <input type="text" class="form-control" id="nuevoNombreMiembro" placeholder="Nombre (Opcional)">
                  </div>
                </div>
                <div class="col-sm-2">
                   <button type="submit" class="btn btn-primary btn-block">Agregar</button>
                </div>
             </div>
          </form>
          <hr>
          <!-- TABLA DE MIEMBROS -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>Email</th>
                  <th>Nombre</th>
                  <th style="width:15px">Acciones</th>
                </tr>
              </thead>
              <tbody id="tablaMiembros">
                <!-- MIEMBROS SE CARGAN AQUÍ CON AJAX -->
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php
$borrarGrupo = new ControladorGruposReportes();
$borrarGrupo->ctrBorrarGrupo();
?>
<script>
  $(document).ready(function() {
    // Lógica para editar grupo
    $('.tablas').on("click", ".btnEditarGrupo", function() {
        var idGrupo = $(this).attr("idGrupo");
        var datos = new FormData();
        datos.append("idGrupo", idGrupo);

        $.ajax({
            url: "ajax/grupos_reportes_ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                $("#idGrupo").val(respuesta["grupo_id"]);
                $("#editarNombre").val(respuesta["grupo_nombre"]);
                $("#editarDescripcion").val(respuesta["grupo_descripcion"]);
            }
        });
    });

    // Lógica para eliminar grupo
    $('.tablas').on("click", ".btnEliminarGrupo", function(){
        var idGrupo = $(this).attr("idGrupo");
        Swal.fire({
            title: '¿Está seguro de borrar el grupo?',
            text: "¡Si no lo está puede cancelar la acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: '¡Sí, borrar grupo!'
        }).then(function(result){
            if (result.value) {
                window.location = "index.php?ruta=grupos-reportes&idGrupo="+idGrupo;
            }
        });
    });

    var idGrupoActual;

    // Lógica para ver miembros
    $('.tablas').on("click", ".btnVerMiembros", function(){
        idGrupoActual = $(this).attr("idGrupo");
        $('#idGrupoParaMiembro').val(idGrupoActual);
        cargarMiembros(idGrupoActual);
    });

    function cargarMiembros(idGrupo) {
        var datos = new FormData();
        datos.append("idGrupoParaMiembros", idGrupo);

        $.ajax({
            url: "ajax/grupos_reportes_ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(respuesta) {
                $('#tablaMiembros').empty();
                respuesta.forEach(function(miembro) {
                    $('#tablaMiembros').append(
                        '<tr>' +
                        '<td>' + miembro.miembro_email + '</td>' +
                        '<td>' + miembro.miembro_nombre + '</td>' +
                        '<td><button class="btn btn-danger btn-xs btnEliminarMiembro" idMiembro="' + miembro.miembro_id + '"><i class="fa fa-times"></i></button></td>' +
                        '</tr>'
                    );
                });
            }
        });
    }

    // Lógica para agregar miembro
    $('#formAgregarMiembro').on('submit', function(e) {
        e.preventDefault();
        var datos = new FormData();
        datos.append("accion", "agregarMiembro");
        datos.append("idGrupoMiembro", idGrupoActual);
        datos.append("emailMiembro", $('#nuevoEmailMiembro').val());
        datos.append("nombreMiembro", $('#nuevoNombreMiembro').val());

        $.ajax({
            url: "ajax/grupos_reportes_ajax.php",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function(respuesta) {
                if (respuesta == "ok") {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Miembro agregado correctamente!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    cargarMiembros(idGrupoActual);
                    $('#nuevoEmailMiembro').val('');
                    $('#nuevoNombreMiembro').val('');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error al agregar miembro!',
                        text: respuesta
                    });
                }
            }
        });
    });

    // Lógica para eliminar miembro
    $('#tablaMiembros').on('click', '.btnEliminarMiembro', function() {
        var idMiembro = $(this).attr('idMiembro');
        var datos = new FormData();
        datos.append("idMiembro", idMiembro);

        Swal.fire({
            title: '¿Está seguro de borrar el miembro?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonText: 'Cancelar',
            confirmButtonText: 'Sí, borrar'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: "ajax/grupos_reportes_ajax.php",
                    method: "POST",
                    data: datos,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(respuesta) {
                        if (respuesta == "ok") {
                            cargarMiembros(idGrupoActual);
                        }
                    }
                });
            }
        });
    });
});
</script> 