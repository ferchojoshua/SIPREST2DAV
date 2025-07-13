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

    <?php if (isset($_SESSION["usuario"]) && isset($_SESSION["usuario"]->id_usuario)) : ?>
        <script>
            const ID_USUARIO_GLOBAL = <?php echo json_encode($_SESSION["usuario"]->id_usuario); ?>;
            console.log("ID_USUARIO_GLOBAL: ", ID_USUARIO_GLOBAL);
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
                $ruta = "vistas/".$_SESSION["usuario"]->vista;
                if(isset($_GET["ruta"])){
                    $ruta = "vistas/".$_GET["ruta"];
                }
                include $ruta;
                ?>

            </div>
            <!-- /.content-wrapper <php include "modulos/footer.php"; ?> -->




        </div>
        <!-- ./wrapper -->

        <script>
            $(document).ready(function(){
                let ultimaPagina = localStorage.getItem("ultimaPagina");
                if(ultimaPagina){
                    $(".content-wrapper").load(ultimaPagina);
                } else {
                    // If no page in localStorage, load the default from session
                    $(".content-wrapper").load("vistas/<?php echo $_SESSION["usuario"]->vista; ?>");
                }
            });

            function CargarContenido(pagina, contenedor, id_perfil, id_modulo) {
                $("." + contenedor).load(pagina);
                localStorage.setItem("ultimaPagina", pagina); // Guardar la última página en localStorage


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

            
        </script>
        
        <!-- PLANTILLA DE SWEETALERT -->
        <script src="vistas/assets/dist/js/plantilla.js"></script>
    </body>

<?php else : ?>

    <body>
        <?php include "vistas/login.php" ?>
    </body>

<?php endif; ?>


</html>