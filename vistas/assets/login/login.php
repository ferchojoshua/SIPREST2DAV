<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
    <title>SIPREST - Inicio de sesión</title>
    
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --info-color: #17a2b8;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            
            --border-radius: 15px;
            --border-radius-lg: 20px;
            --shadow: 0 8px 25px rgba(0,0,0,0.15);
            --shadow-lg: 0 15px 35px rgba(0,0,0,0.2);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            transition: var(--transition);
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.25);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-empresa {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 50%;
            box-shadow: var(--shadow);
            margin-bottom: 15px;
            transition: var(--transition);
        }

        .logo-empresa:hover {
            transform: scale(1.05);
        }

        .login-title {
            color: var(--dark-color);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            text-align: center;
        }

        .login-subtitle {
            color: var(--secondary-color);
            font-size: 16px;
            font-weight: 400;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .input-group {
            position: relative;
        }

        .input-group-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            font-size: 18px;
            z-index: 2;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: var(--border-radius);
            padding: 15px 15px 15px 50px;
            font-size: 16px;
            font-weight: 500;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.9);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            transform: scale(1.02);
            background: white;
        }

        .form-control::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--secondary-color);
            font-size: 18px;
            transition: var(--transition);
            z-index: 2;
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        .btn-login {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-color), #0056b3);
            border: none;
            border-radius: var(--border-radius);
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: var(--transition);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: linear-gradient(135deg, #0056b3, var(--primary-color));
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 20px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .forgot-password a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .alert {
            border-radius: var(--border-radius);
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-warning {
            background: linear-gradient(135deg, rgba(255, 193, 7, 0.1), rgba(224, 168, 0, 0.1));
            color: #856404;
            border-left: 4px solid var(--warning-color);
        }

        .wave {
            position: fixed;
            bottom: 0;
            left: 0;
            height: 100%;
            z-index: -1;
            opacity: 0.1;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 25px;
                margin: 10px;
            }
            
            .login-title {
                font-size: 24px;
            }
            
            .form-control {
                padding: 12px 12px 12px 45px;
                font-size: 14px;
            }
            
            .btn-login {
                padding: 12px 25px;
                font-size: 14px;
            }
        }

        /* Animaciones */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 30px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }

        .login-container {
            animation: fadeInUp 0.6s ease-out;
        }

        .form-group {
            animation: fadeInUp 0.6s ease-out;
            animation-fill-mode: both;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .btn-login { animation-delay: 0.4s; }
    </style>
</head>

<body>
    <img class="wave" src="img/wave.png">
    
    <div class="login-container">
        <form method="POST" action="../inicio.php">
            <?php
            require_once '../../../conexion_reportes/r_conexion.php';
            
            // Obtener logo de la empresa
            $query = "SELECT config_logo, confi_razon FROM empresa WHERE confi_id = 1";
            $resultado = $mysqli->query($query);
            
            $logo_path = 'img/avatar.svg'; // Logo por defecto
            $empresa_nombre = 'SIPREST';
            
            if ($resultado && $row = $resultado->fetch_assoc()) {
                if (!empty($row['config_logo']) && file_exists('../../../uploads/logos/' . $row['config_logo'])) {
                    $logo_path = '../../../uploads/logos/' . $row['config_logo'];
                }
                if (!empty($row['confi_razon'])) {
                    $empresa_nombre = $row['confi_razon'];
                }
            }
            ?>
            
            <div class="logo-container">
                <img src="<?php echo $logo_path; ?>" class="logo-empresa" alt="Logo Empresa">
                <h1 class="login-title"><?php echo $empresa_nombre; ?></h1>
                <p class="login-subtitle">Sistema de Préstamos</p>
        </div>

            <!-- MENSAJE DE ERROR -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <small>Usuario o contraseña incorrectos</small>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-user input-group-icon"></i>
                    <input 
                        id="usuario" 
                        type="text"
                        class="form-control" 
                        name="usuario"
                        placeholder="Ingrese su usuario"
                        title="Ingrese su nombre de usuario" 
                        autocomplete="username" 
                        required
                        value="">
                </div>
                </div>

            <div class="form-group">
                <div class="input-group">
                    <i class="fas fa-lock input-group-icon"></i>
                    <input 
                        type="password" 
                        id="password" 
                        class="form-control"
                        name="password" 
                        placeholder="Ingrese su contraseña"
                        title="Ingrese su contraseña para ingresar" 
                        autocomplete="current-password"
                        required>
                    <i class="fas fa-eye password-toggle" onclick="togglePassword()" id="toggleIcon"></i>
                </div>
            </div>

            <button 
                name="btningresar" 
                class="btn btn-login" 
                title="Click para ingresar" 
                type="submit">
                <i class="fas fa-sign-in-alt mr-2"></i>
                Iniciar Sesión
            </button>

            <div class="forgot-password">
                <a href="#" onclick="alert('Contacte al administrador del sistema')">
                    <i class="fas fa-question-circle mr-1"></i>
                    ¿Olvidó su contraseña?
                </a>
            </div>
            </form>
    </div>

    <script src="js/fontawesome.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Focus automático en el campo usuario
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('usuario').focus();
        });

        // Validación del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!usuario || !password) {
                e.preventDefault();
                alert('Por favor, complete todos los campos');
                return false;
            }
        });
    </script>

</body>

</html>
