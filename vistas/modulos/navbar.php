<!-- Navbar -->
<!-- <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a style="cursor:pointer;" class="nav-link" onclick="CargarContenido('vistas/dashboard.php','content-wrapper')">
                Dashboar
            </a>
        </li>
    </ul>
</nav> -->
<!-- /.navbar -->



<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

    </ul>

    <ul class="navbar-nav ml-auto">



        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-danger navbar-badge" id="lbl_contador">1</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div id="div_cuerpo">

                </div>



                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Cuotas Pendientes por Pagar</a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">

                <!-- llamamos el nombre del usuario (sesion ya creada) -->
                <?php echo $_SESSION["usuario"]->usuario  ?> <i class="fas fa-caret-down"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <!-- <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item" style="font-size:large;" onclick="cargar_contenido('contenido_principal','usuario/mantenimiento_perfil.php')">
                    <i class="fas fa-user-cog mr-2"></i>
                    <span class="text-muted text-sm">Perfil</span>
                </a> -->
                <div class="dropdown-divider"></div>
                <a href="http://localhost/siprest?cerrar_sesion=1" class="dropdown-item" style="font-size:large;">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span class="text-muted text-sm">Cerrar Sesion</span>
                </a>
                <div class="dropdown-divider"></div>

            </div>
        </li>

        <!-- <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li> -->
    </ul>
</nav>

<script>

$(document).ready(function() {
    Notificaciones();



})
    function Notificaciones() {

        var id_usuario = $("#text_Idprincipal").val();
        //console.log(id_usuario);

        $.ajax({
            async: false,
            url: "ajax/dashboard_ajax.php",
            method: "POST",
            data: {
                'accion': 4,
                'id_usuario': id_usuario
            },
            dataType: 'json',
            success: function(respuesta) {
                //console.log(respuesta);
                document.getElementById('lbl_contador').innerHTML = respuesta.length;
                let llenardata = "";
                if (respuesta.length > 0) {
                    for (let i = 0; i < respuesta.length; i++) {
                        llenardata += '<a href="#" class="dropdown-item">' +
                            '<div class="media">' +
                            '<div class="media-body">' +
                            '<h4 class="dropdown-item-title">' +
                            '<b>Nro Prestamo: </b>' + respuesta[i][0] + '' +
                            '<span class="float-right text-sm text-warning"><i class="fas fa-folder-minus"></i></i></span>' +
                            '</h4>' +
                            '<p class="text-sm"><b>Cliente: </b>' + respuesta[i][2] + ' </p>' +
                            '<p class="text-sm"><b>Nro Cuota: ' + respuesta[i][3] + '</b> <b>| Monto: ' + respuesta[i][5] + '</b></p>' +
                            '<p class="text-sm text-muted"><i class="fas fa-calendar-alt"></i> Fecha: ' + respuesta[i][4] + ' </p>' +
                            ' </div>' +
                            '</div>' +

                            '</a>' +
                            '<div class="dropdown-divider"></div>';
                    }
                    document.getElementById('div_cuerpo').innerHTML = llenardata;

                } else {
                    llenardata += "<option value=''>No se encontraron datos</option>";
                    document.getElementById('div_cuerpo').innerHTML = llenardata;
                    //  document.getElementById('select_rol_editar').innerHTML = llenardata;

                }

            }
        });
    }
</script>