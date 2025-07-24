<?php
$menuUsuario = UsuarioControlador::ctrObtenerMenuUsuario($_SESSION["usuario"]->id_usuario);

// var_dump($menuUsuario);
?>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="vistas/assets/dist/img/logo2.svg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .9">
        <span class="brand-text font-weight-light">SIPRESTA</span>
    </a>
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            <img src="vistas/assets/dist/img/userperfil.svg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <h6 class="text-warning"><?php echo $_SESSION["usuario"]->nombre_usuario  ?></h6>
            <input type="text" value="<?php echo $_SESSION["usuario"]->id_usuario;  ?>" id="text_Idprincipal" hidden>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false" id="sidebarMenu">

                <!-- El bucle dinámico se encargará de renderizar el menú desde la BD -->
                <?php foreach ($menuUsuario as $menu) : ?>
                    
                    <li class="nav-item <?php if(empty($menu->vista)) echo 'has-treeview'; ?>">

                        <a style="cursor: pointer;" 
                            class="nav-link <?php if($menu->vista_inicio == 1) : ?>
                                                <?php echo 'active'; ?>
                                            <?php endif; ?>"
                            <?php if(!empty($menu->vista) && $menu->vista !== 'dashboard_mejorado.php') : ?>
                                onclick="CargarContenido('vistas/<?php echo $menu->vista; ?>','content-wrapper')" 
                            <?php else: ?>
                                href="#"
                            <?php endif; ?>>
                            <i class="nav-icon <?php echo $menu->icon_menu; ?>"></i>
                            <p>
                                <?php echo $menu->modulo ?>
                                <?php if(empty($menu->vista)) : ?>
                                    <i class="right fas fa-angle-left"></i> 
                                <?php endif; ?>
                            </p>
                        </a>

                        <?php if(empty($menu->vista)) : ?>

                            <?php
                                $subMenuUsuario = UsuarioControlador::ctrObtenerSubMenuUsuario($menu->id,$_SESSION["usuario"]->id_usuario);
                            ?>

                            <ul class="nav nav-treeview">

                                <?php foreach ($subMenuUsuario as $subMenu) : ?>
                                    <li class="nav-item">
                                        <a style="cursor: pointer;" class="nav-link" onclick="CargarContenido('vistas/<?php echo $subMenu->vista ?>','content-wrapper')">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p><?php echo $subMenu->modulo ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>

                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
$(document).ready(function() {
    try {
        // Remover inicializaciones previas del treeview
        if ($('#sidebarMenu').data('lte.treeview')) {
            $('#sidebarMenu').off('.lte.treeview');
        }
        
        // Configuración del treeview
        const treeviewConfig = {
            trigger: '[data-widget="treeview"] .nav-link',
            animationSpeed: 300,
            accordion: true,
            expandSidebar: false,
            sidebarButtonSelector: '[data-widget="pushmenu"]'
        };

        // Función para manejar el toggle de menús
        function toggleTreeview(e) {
            try {
                e.preventDefault();
                e.stopPropagation();
                
                const $link = $(this);
                const $li = $link.closest('.nav-item');
                const $treeview = $li.find('> .nav-treeview');
                
                // Si no hay submenu, no hacer nada
                if ($treeview.length === 0) {
                    return;
                }
                
                const isOpen = $li.hasClass('menu-open');
                
                // Si accordion está activado, cerrar otros menús
                if (treeviewConfig.accordion) {
                    const $openMenus = $li.siblings('.menu-open');
                    $openMenus.removeClass('menu-open menu-is-opening');
                    $openMenus.find('> .nav-treeview').slideUp(treeviewConfig.animationSpeed);
                }
                
                if (isOpen) {
                    // Cerrar menú
                    $li.removeClass('menu-open menu-is-opening');
                    $treeview.slideUp(treeviewConfig.animationSpeed);
                } else {
                    // Abrir menú
                    $li.addClass('menu-is-opening');
                    $treeview.slideDown(treeviewConfig.animationSpeed, function() {
                        $li.addClass('menu-open').removeClass('menu-is-opening');
                    });
                }
            } catch (error) {
                console.error('Error en toggleTreeview:', error);
            }
        }
        
        // Remover eventos anteriores
        $(document).off('click', '.nav-item.has-treeview > .nav-link');
        $('.nav-link').off('click');
        
        // Asignar nuevos eventos
        $(document).on('click', '.nav-item.has-treeview > .nav-link', toggleTreeview);
        
        // Manejar clicks en enlaces normales
        $('.nav-link').on('click', function(e) {
            try {
                if (!$(this).closest('.nav-item').hasClass('has-treeview')) {
                    $('.nav-link').removeClass('active');
                    $(this).addClass('active');
                }
            } catch (error) {
                console.error('Error en click handler:', error);
            }
        });
        
        // Inicializar estado de menús
        $('.nav-item.has-treeview').each(function() {
            try {
                const $li = $(this);
                const $treeview = $li.find('> .nav-treeview');
                
                if ($treeview.find('.nav-link.active').length > 0) {
                    $li.addClass('menu-open');
                    $treeview.show();
                }
            } catch (error) {
                console.error('Error inicializando menú:', error);
            }
        });
    } catch (error) {
        console.error('Error general en inicialización del menú:', error);
    }
});
</script>