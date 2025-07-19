<?php

session_start();

if (isset($_GET["cerrar_sesion"]) && $_GET["cerrar_sesion"] == 1) {

    session_destroy();

    echo '
            <script>
                window.location = "http://localhost/siprest";
            </script>        
        ';
}
?>


<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistema</title>
    <link rel="shortcut icon" href="vistas/assets/dist/img/icon.png" type="image/x-icon">




    <!-- Google Font: Source Sans Pro -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"> -->
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="vistas/assets/plugins/fontawesome-free/css/all.min.css">

    <!-- SWEEALERT-->
    <link rel="stylesheet" href="vistas/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">

    <!-- Jquery CSS -->
    <link rel="stylesheet" href="vistas/assets/plugins/jquery-ui/css/jquery-ui.css">

    <!-- Bootstrap 5 -->
    <!-- <link rel="stylesheet" href="vistas/assets/dist/css/bootstrap.min.css"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="vistas/assets/plugins/bootstrap.min.css"></script> -->


    <!-- JSTREE CSS -->
    <link rel="stylesheet" href="vistas/assets/dist/css/style.min.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" /> -->

    <!-- ============================================================
        ESTILOS PARA USO DE DATATABLES JS
    ===============================================================-->
    <link rel="stylesheet" href="vistas/assets/dist/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="vistas/assets/dist/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="vistas/assets/dist/css/buttons.dataTables.min.css">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.0.0/css/buttons.dataTables.min.css"> -->

    <!-- stylo  -->
    <link rel="stylesheet" href="vistas/assets/dist/css/style.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="vistas/assets/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="vistas/assets/plugins/select2/css/select2.min.css">

    <!-- ESTILOS ESTÁNDAR DEL SISTEMA - BOTONES Y ELEMENTOS REDONDEADOS -->
    <link rel="stylesheet" href="vistas/assets/css/sistema-estandar.css">


    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="vistas/assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="vistas/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- CHART -->
    <script src="vistas/assets/plugins/chart.js/Chart.min.js"></script>

    <!-- InputMask -->
    <script src="vistas/assets/plugins/moment/moment.min.js"></script>
    <script src="vistas/assets/plugins/inputmask/jquery.inputmask.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="vistas/assets/plugins/sweetalert2/sweetalert2.min.js"></script>


    <!-- jquery UI -->
    <script src="vistas/assets/plugins/jquery-ui/js/jquery-ui.js"></script>

    <!-- JS Bootstrap 5 -->
    <!-- <script src="vistas/assets/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- <script src="vistas/assets/plugins/bootstrap.bundle.min.js"></script> -->


    <!-- JSTREE JS -->
    <script src="vistas/assets/dist/js/jstree.min.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script> -->

    <!-- ============================================================
    =LIBRERIAS PARA USO DE DATATABLES JS
    ===============================================================-->
    <script src="vistas/assets/dist/js/jquery.dataTables.min.js"></script>
    <script src="vistas/assets/dist/js/dataTables.responsive.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.11.0/js/jquery.dataTables.min.js"></script>         -->
    <!-- <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script> -->


    <!-- ============================================================
    =LIBRERIAS PARA EXPORTAR A ARCHIVOS
    ===============================================================-->
    <script src="vistas/assets/dist/js/dataTables.buttons.min.js"></script>
    <script src="vistas/assets/dist/js/jszip.min.js"></script>
    <script src="vistas/assets/dist/js/buttons.html5.min.js"></script>
    <script src="vistas/assets/dist/js/buttons.print.min.js"></script>
    <!-- <script src="https://cdn.datatables.net/buttons/2.0.0/js/dataTables.buttons.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.0.0/js/buttons.print.min.js"></script> -->

    <!-- AdminLTE App -->
    <script src="vistas/assets/dist/js/adminlte.min.js"></script>
    <!-- <script src="../utilitarios/sum().js"></script> -->

    <!-- PLANTILLA DE SWEETALERT -->
    <script src="vistas/assets/dist/js/plantilla.js"></script>


    <script type="text/javascript" src="vistas/assets/plugins/select2/js/select2.full.min.js"></script>

    <!-- CSS personalizado para corregir espaciado del dashboard -->
    <link rel="stylesheet" href="vistas/assets/css/fix-dashboard-spacing.css">
    
    <!-- CSS personalizado para corregir menús treeview con Bootstrap 5 -->
    <link rel="stylesheet" href="vistas/assets/css/fix-menu-treeview.css">

    <?php if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->id_usuario)) : ?>
        <script>
            // Evitar redeclaración de ID_USUARIO_GLOBAL
            if (typeof ID_USUARIO_GLOBAL === 'undefined') {
                window.ID_USUARIO_GLOBAL = <?php echo json_encode($_SESSION["usuario"]->id_usuario); ?>;
                //console.log("ID_USUARIO_GLOBAL inicializado: ", ID_USUARIO_GLOBAL);
            }
        </script>
    <?php endif; ?>
</head>
<!-- usuario campo de la base -->
<?php 
    // CHEQUEO DE ROBUSTEZ: SI LA SESIÓN NO ES UN OBJETO, LA DESTRUIMOS PARA EVITAR ERRORES
    if(isset($_SESSION["usuario"]) && !is_object($_SESSION["usuario"])){
        session_destroy();
        echo '
            <script>
                window.location = "http://localhost/siprest";
            </script>        
        ';
        exit(); // DETENEMOS LA EJECUCIÓN
    }

    if (isset($_SESSION["usuario"])) :  
?>

    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">

            <?php
            include "modulos/navbar.php";
            include "modulos/aside.php";
            ?>


            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- vista de inicio - por defecto la que esta en base -->
                <?php
                $ruta = "";
                
                // Verificar si hay una vista en la sesión
                if (isset($_SESSION["usuario"]) && is_object($_SESSION["usuario"]) && 
                    isset($_SESSION["usuario"]->vista) && !empty($_SESSION["usuario"]->vista)) {
                    $ruta = "vistas/".$_SESSION["usuario"]->vista;
                } else {
                    // Vista por defecto si no hay vista en sesión o sesión inválida
                    $ruta = "vistas/dashboard.php";
                    
                    // Log para debugging
                    if (!isset($_SESSION["usuario"])) {
                        error_log("Sesión de usuario no existe");
                    } elseif (!is_object($_SESSION["usuario"])) {
                        error_log("Sesión de usuario no es un objeto");
                    } elseif (!isset($_SESSION["usuario"]->vista)) {
                        error_log("Propiedad 'vista' no existe en sesión de usuario");
                    } else {
                        error_log("Propiedad 'vista' está vacía");
                    }
                }
                
                // Permitir sobrescribir con parámetro GET
                if(isset($_GET["ruta"]) && !empty($_GET["ruta"])){
                    $ruta = "vistas/".$_GET["ruta"];
                }
                
                // Verificar que el archivo existe antes de incluirlo
                if (file_exists($ruta)) {
                    include $ruta;
                } else {
                    echo '<div class="content-header">';
                    echo '<div class="container-fluid">';
                    echo '<div class="alert alert-warning">';
                    echo '<h4><i class="icon fa fa-warning"></i> Vista no encontrada</h4>';
                    echo 'La vista solicitada no existe: ' . htmlspecialchars($ruta);
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    
                    // Log del error para debugging
                    error_log("Vista no encontrada: $ruta");
                    
                    // Intentar cargar vista por defecto
                    if (file_exists("vistas/dashboard.php")) {
                        include "vistas/dashboard.php";
                    } else {
                        echo '<div class="content-header">';
                        echo '<div class="container-fluid">';
                        echo '<h1>Sistema de Préstamos</h1>';
                        echo '<p>Bienvenido al sistema. Por favor, configure la vista por defecto.</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                }
                ?>

            </div>
            <!-- /.content-wrapper <php include "modulos/footer.php"; ?> -->




        </div>
        <!-- ./wrapper -->

        <script>
            $(document).ready(function(){
                try {
                    let ultimaPagina = localStorage.getItem("ultimaPagina");
                    if(ultimaPagina){
                        $(".content-wrapper").load(ultimaPagina);
                    } else {
                        // If no page in localStorage, load the default from session
                        <?php 
                        $vista_default = isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->vista) ? $_SESSION["usuario"]->vista : 'dashboard.php';
                        ?>
                        $(".content-wrapper").load("vistas/<?php echo htmlspecialchars($vista_default, ENT_QUOTES, 'UTF-8'); ?>");
                    }
                } catch(error) {
                    console.error("Error en document.ready:", error);
                    $(".content-wrapper").load("vistas/dashboard.php");
                }
            });

            function CargarContenido(pagina, contenedor, id_perfil, id_modulo) {
                // Solución robusta para evitar errores de appendChild con contenido JavaScript
                console.log("Cargando página:", pagina);
                
                // Usar una aproximación más segura para cargar el contenido
                $.ajax({
                    url: pagina,
                    type: 'GET',
                    dataType: 'html',
                    cache: false,
                    beforeSend: function() {
                        // Prevenir conflictos de variables globales antes de cargar nueva vista
                        window.tempCombosMejoradosLoaded = window.CombosMejoradosLoaded;
                    },
                    success: function(data, textStatus, xhr) {
                        try {
                            // Limpiar el contenedor antes de insertar nuevo contenido
                            $("." + contenedor).empty();
                            
                            // Filtrar scripts problemáticos antes de insertar
                            var tempDiv = $('<div>').html(data);
                            
                            // Prevenir inclusiones duplicadas de scripts críticos
                            tempDiv.find('script[src]').each(function() {
                                var scriptSrc = $(this).attr('src');
                                if (scriptSrc && (
                                    scriptSrc.includes('combos-mejorados.js') ||
                                    scriptSrc.includes('jquery.min.js') ||
                                    scriptSrc.includes('select2.full.min.js')
                                )) {
                                    console.log('[CargarContenido] Removiendo inclusión duplicada de:', scriptSrc);
                                    $(this).remove();
                                }
                            });
                            
                            // Insertar el contenido filtrado de forma segura
                            $("." + contenedor).html(tempDiv.html());
                            
                            // Guardar la página en localStorage solo si fue exitosa
                            localStorage.setItem("ultimaPagina", pagina);
                            console.log("Vista cargada exitosamente: " + pagina);
                            
                        } catch (error) {
                            console.error("Error al procesar el contenido:", error);
                            // Restaurar estado si hubo error
                            if (window.tempCombosMejoradosLoaded) {
                                window.CombosMejoradosLoaded = window.tempCombosMejoradosLoaded;
                            }
                            mostrarErrorCarga(contenedor, pagina, "Error al procesar el contenido");
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.error("Error al cargar vista:", pagina, "Status:", textStatus, "Error:", errorThrown);
                        console.error("Código de estado:", xhr.status);
                        console.error("Respuesta del servidor:", xhr.responseText);
                        
                        // Restaurar estado si hubo error
                        if (window.tempCombosMejoradosLoaded) {
                            window.CombosMejoradosLoaded = window.tempCombosMejoradosLoaded;
                        }
                        
                        // Mostrar mensaje de error detallado
                        mostrarErrorCarga(contenedor, pagina, textStatus + " - " + errorThrown);
                    }
                });
            }
            
            // Función auxiliar para mostrar errores de carga
            function mostrarErrorCarga(contenedor, pagina, error) {
                var errorHtml = 
                    '<div class="content-header">' +
                    '<div class="container-fluid">' +
                    '<div class="alert alert-danger">' +
                    '<h4><i class="icon fas fa-exclamation-triangle"></i> Error al cargar vista</h4>' +
                    '<p>No se pudo cargar la vista: <strong>' + pagina + '</strong></p>' +
                    '<p><strong>Error:</strong> ' + error + '</p>' +
                    '<div class="mt-3">' +
                    '<button class="btn btn-primary" onclick="location.reload();">' +
                    '<i class="fas fa-sync-alt"></i> Recargar página' +
                    '</button> ' +
                    '<button class="btn btn-secondary" onclick="CargarContenido(\'vistas/dashboard.php\', \'' + contenedor + '\');">' +
                    '<i class="fas fa-home"></i> Ir al inicio' +
                    '</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
                
                $("." + contenedor).html(errorHtml);
            }
            /********************************************************************/
            // PARA BLOQUEAR ANTICLICK F12 CTR U
            /********************************************************************/
            // document.oncontextmenu = function() {
            //     return false
            // };

            // onkeydown = e => {
            //     let tecla = e.which || e.keyCode;

            //     // Evaluar si se ha presionado la tecla Ctrl:
            //     if (e.ctrlKey) {
            //         // Evitar el comportamiento por defecto del nevagador:
            //         e.preventDefault();
            //         e.stopPropagation();

            //         // Mostrar el resultado de la combinación de las teclas:
            //         if (tecla === 85)// U
            //             console.log(" ");

            //         if (tecla === 83) //S
            //             console.log(" ");

            //         if (tecla === 123) //F12
            //             console.log(" ");
            //     }
            // }


            // $(document).keydown(function(event) {
            //     if (event.keyCode == 123) { // Prevent F12
            //         return false;
            //     } else if (event.ctrlKey && event.shiftKey && event.keyCode == 73) { // Prevent Ctrl+Shift+I        
            //         return false;
            //     }
            // });

            // Debugging básico para identificar problemas
            console.log("Sistema iniciado - CargarContenido disponible");
        </script>
        
        <!-- PLANTILLA DE SWEETALERT -->
        <script src="vistas/assets/dist/js/plantilla.js"></script>
        
        <!-- Script de protección para evitar redeclaraciones -->
        <script>
            // Protección global más simple y efectiva
            if (typeof window.CombosMejoradosScriptLoaded === 'undefined') {
                window.CombosMejoradosScriptLoaded = true;
                console.log('[Sistema] Cargando combos-mejorados.js por primera vez');
                
                // Cargar el script de combos solo una vez
                var script = document.createElement('script');
                script.src = 'vistas/assets/dist/js/combos-mejorados.js';
                script.onload = function() {
                    console.log('[Sistema] combos-mejorados.js cargado exitosamente');
                };
                script.onerror = function() {
                    console.error('[Sistema] Error al cargar combos-mejorados.js');
                };
                document.head.appendChild(script);
            } else {
                console.log('[Sistema] combos-mejorados.js ya cargado previamente');
            }
        </script>
    </body>

<?php else : ?>

    <body>
        <?php include "vistas/login.php" ?>
    </body>

<?php endif; ?>


</html>