<!-- Navbar principal -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Menú izquierdo -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a style="cursor:pointer;" class="nav-link" onclick="CargarContenido('vistas/dashboard.php','content-wrapper')">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
    </ul>

    <!-- Menú derecho -->
    <ul class="navbar-nav ml-auto">
        <!-- Notificaciones -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">0 Notificaciones</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Ver todas las notificaciones</a>
            </div>
        </li>

        <!-- Usuario -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
                <span class="d-none d-md-inline ml-1">
                    <?php echo isset($_SESSION["usuario"]) ? $_SESSION["usuario"]->nombre_usuario : 'Usuario'; ?>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user mr-2"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="vistas/salir.php" class="dropdown-item">
                    <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                </a>
            </div>
        </li>

        <!-- Pantalla completa -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
    </ul>
</nav>