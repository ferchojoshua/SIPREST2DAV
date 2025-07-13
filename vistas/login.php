<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="vistas/assets/login/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="vistas/assets/login/css/style.css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
    <title>SIPREST - Inicio de sesión</title>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 40px;
            width: 100%;
            max-width: 450px;
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 50%;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            margin-bottom: 20px;
        }

        .title {
            color: #343a40;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
            text-align: left;
        }

        .form-group .icon-left {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #007bff;
            font-size: 18px;
            z-index: 10;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 15px 50px 15px 50px;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.9);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            background: white;
            outline: none;
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
            color: #6c757d;
            font-size: 16px;
            z-index: 20;
            background: white;
            padding: 6px;
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .password-toggle:hover {
            color: #007bff;
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 15px;
            padding: 15px 30px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        .forgot-password {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 20px;
        }

        .forgot-password:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        .alert {
            background: rgba(255, 193, 7, 0.1);
            color: #856404;
            border-left: 4px solid #ffc107;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 25px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 15px;
            width: 90%;
            max-width: 450px;
            position: relative;
        }

        .close {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
        }

        .close:hover {
            color: #000;
        }

        .modal h3 {
            margin-bottom: 20px;
            text-align: center;
            color: #343a40;
        }

        .modal label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #343a40;
            font-size: 14px;
        }

        .modal input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 15px;
            font-size: 14px;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        .modal input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .info-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-item strong {
            display: block;
            margin-bottom: 5px;
        }

        .info-value {
            padding: 8px 12px;
            background: white;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }

        .btn-group {
            text-align: center;
            margin-top: 20px;
        }

        .btn-secondary {
            background: #6c757d;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            color: white;
            margin: 0 5px;
            cursor: pointer;
        }

        @media (max-width: 576px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .title {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <form method="POST" id="frmLogin">
            <?php
            require_once 'conexion_reportes/r_conexion.php';
            
            // Obtener logo de la empresa
            $query = "SELECT config_logo, confi_razon FROM empresa WHERE confi_id = 1";
            $resultado = $mysqli->query($query);
            
            $logo_path = 'vistas/assets/login/img/avatar.svg';
            $empresa_nombre = 'SIPREST';
            
            if ($resultado && $row = $resultado->fetch_assoc()) {
                if (!empty($row['config_logo']) && file_exists('uploads/logos/' . $row['config_logo'])) {
                    $logo_path = 'uploads/logos/' . $row['config_logo'];
                }
                if (!empty($row['confi_razon'])) {
                    $empresa_nombre = $row['confi_razon'];
                }
            }
            ?>
            
            <img src="<?php echo $logo_path; ?>" alt="Logo Empresa" class="logo">
            <h2 class="title"><?php echo $empresa_nombre; ?></h2>
            <p class="subtitle">Sistema de Préstamos</p>
            
            <!-- MENSAJE DE ERROR -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    Usuario o contraseña incorrectos
                </div>
            <?php endif; ?>
            
            <!-- CAMPO USUARIO -->
            <div class="form-group">
                <i class="fas fa-user icon-left"></i>
                <input 
                    id="usuario" 
                    type="text" 
                    class="form-control" 
                    name="loginUsuario" 
                    placeholder="Ingrese su usuario" 
                    autocomplete="username" 
                    required>
                </div>

            <!-- CAMPO CONTRASEÑA -->
            <div class="form-group">
                <i class="fas fa-lock icon-left"></i>
                <input 
                    id="password" 
                    type="password" 
                    class="form-control" 
                    name="loginPassword" 
                    placeholder="Ingrese su contraseña" 
                    autocomplete="current-password" 
                    required>
                <i class="fas fa-eye password-toggle" onclick="togglePassword()" id="toggleIcon"></i>
            </div>

            <!-- ENLACE OLVIDÉ CONTRASEÑA -->
            <a href="#" class="forgot-password" onclick="abrirModal()">
                <i class="fas fa-question-circle"></i>
                ¿Olvidó su contraseña?
            </a>
            
            <!-- BOTÓN ENVIAR -->
            <button type="button" name="btningresar" id="btnIngresar" class="btn-primary">
                INICIAR SESIÓN
            </button>
        </form>
    </div>

    <!-- Modal Recuperar Contraseña -->
    <div id="modalRecuperacion" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <h3>
                <i class="fas fa-key" style="color: #007bff;"></i>
                Recuperar Contraseña
            </h3>
            
            <p style="text-align: center; margin-bottom: 25px; color: #6c757d; font-size: 14px;">
                Ingrese su nombre de usuario para restablecer su contraseña
            </p>
            
            <form id="formRecuperacion" onsubmit="return false;">
                <div style="margin-bottom: 25px;">
                    <label>
                        <i class="fas fa-user" style="color: #007bff; margin-right: 8px;"></i>
                        Nombre de usuario:
                    </label>
                    <input 
                        type="text" 
                        id="usuarioRecuperacion" 
                        placeholder="Ingrese su usuario"
                        autocomplete="username">
                    
                    <div style="margin-top: 20px;">
                        <label style="display: block; margin-bottom: 10px;">
                            <input type="radio" name="metodoRecuperacion" value="manual" checked> 
                            Establecer nueva contraseña manualmente
                        </label>
                        
                        <div id="opcionManual">
                            <label>
                                <i class="fas fa-lock" style="color: #28a745; margin-right: 8px;"></i>
                                Nueva contraseña:
                            </label>
                            <input 
                                type="password" 
                                id="nuevaPassword" 
                                placeholder="Ingrese nueva contraseña"
                                autocomplete="new-password">
                            
                            <label>
                                <i class="fas fa-lock" style="color: #ffc107; margin-right: 8px;"></i>
                                Confirmar contraseña:
                            </label>
                            <input 
                                type="password" 
                                id="confirmarPassword" 
                                placeholder="Confirme nueva contraseña"
                                autocomplete="new-password">
                        </div>
                        
                        <label style="display: block; margin-top: 15px; margin-bottom: 10px;">
                            <input type="radio" name="metodoRecuperacion" value="email"> 
                            Recibir contraseña temporal por correo electrónico
                        </label>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn-primary" onclick="resetearPassword()" style="width: 48%; margin-right: 4%;">
                        <i class="fas fa-check"></i>
                        Resetear
                    </button>
                    <button type="button" class="btn-secondary" onclick="cerrarModal()" style="width: 48%;">
                        <i class="fas fa-times"></i>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Focus automático en usuario
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('usuario').focus();
        });

        // Toggle contraseña
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Modal functions
        function abrirModal() {
            document.getElementById('modalRecuperacion').style.display = 'block';
        }

        function cerrarModal() {
            document.getElementById('modalRecuperacion').style.display = 'none';
            document.getElementById('usuarioRecuperacion').value = '';
            document.getElementById('nuevaPassword').value = '';
            document.getElementById('confirmarPassword').value = '';
        }

        function resetearPassword() {
            const usuario = document.getElementById('usuarioRecuperacion').value.trim();
            const metodoRecuperacion = document.querySelector('input[name="metodoRecuperacion"]:checked').value;
            
            if (!usuario) {
                alert('Por favor, ingrese su nombre de usuario.');
                return;
            }
            
            if (metodoRecuperacion === 'manual') {
                const nuevaPassword = document.getElementById('nuevaPassword').value.trim();
                const confirmarPassword = document.getElementById('confirmarPassword').value.trim();
                
                if (!nuevaPassword) {
                    alert('Por favor, ingrese una nueva contraseña.');
                    return;
                }

                if (nuevaPassword.length < 6) {
                    alert('La contraseña debe tener al menos 6 caracteres.');
                    return;
                }

                if (nuevaPassword !== confirmarPassword) {
                    alert('Las contraseñas no coinciden. Verifique e intente nuevamente.');
                    return;
                }

                // Llamada AJAX para resetear la contraseña manualmente
                const formData = new FormData();
                formData.append('usuario', usuario);
                formData.append('nueva_password', nuevaPassword);

                fetch('ajax/reset_password_ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ Contraseña restablecida exitosamente!\n\n' +
                              'Usuario: ' + usuario + '\n' +
                              'Su nueva contraseña ha sido guardada.\n\n' +
                              'Ahora puede iniciar sesión con su nueva contraseña.');
                        cerrarModal();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Error al procesar la solicitud. Intente nuevamente.');
                });
            } else {
                // Llamada AJAX para enviar contraseña temporal por correo
                const formData = new FormData();
                formData.append('usuario', usuario);

                fetch('ajax/password_reset_email.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('✅ ' + data.message + '\n\n' +
                              'Por favor, revise su bandeja de entrada y siga las instrucciones para completar el proceso de recuperación de contraseña.');
                        cerrarModal();
                    } else {
                        alert('❌ Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('❌ Error al procesar la solicitud. Intente nuevamente.');
                });
            }
        }
        
        // Toggle opciones de recuperación
        document.querySelectorAll('input[name="metodoRecuperacion"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const opcionManual = document.getElementById('opcionManual');
                if (this.value === 'manual') {
                    opcionManual.style.display = 'block';
                } else {
                    opcionManual.style.display = 'none';
                }
            });
        });
        
        // Cerrar modal con click fuera
        window.onclick = function(event) {
            const modal = document.getElementById('modalRecuperacion');
            if (event.target === modal) {
                cerrarModal();
            }
        }

        // Enter para enviar
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('form').submit();
            }
        });

        // Enter en campos del modal de recuperación
        document.addEventListener('DOMContentLoaded', function() {
            const modalInputs = ['usuarioRecuperacion', 'nuevaPassword', 'confirmarPassword'];
            modalInputs.forEach(function(inputId) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            resetearPassword();
                        }
                    });
                }
            });
        });

        // Validación
        document.querySelector('form').addEventListener('submit', function(e) {
            const usuario = document.getElementById('usuario').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!usuario || !password) {
                e.preventDefault();
                alert('Complete todos los campos');
                return false;
            }
        });
    </script>

    <!-- jQuery -->
    <script src="vistas/assets/plugins/jquery/jquery.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="vistas/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- PLANTILLA DE SWEETALERT -->
    <script src="vistas/assets/dist/js/plantilla.js"></script>

    <script>
        // VALIDACIÓN DE LOGIN CON AJAX
        $("#btnIngresar").on("click", function() {

            //OBTENER DATOS DEL FORMULARIO
            var data = new FormData($('#frmLogin')[0]);

            //ENVIARLOS AL CONTROLADOR
            $.ajax({
                url: "ajax/usuario_ajax.php",
                type: "POST",
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#btnIngresar").html(
                        '<i class="fas fa-spinner fa-spin"></i> Ingresando...');
                    $("#btnIngresar").attr("disabled", true);
                },
                success: function(respuesta) {

                    $("#btnIngresar").html('INICIAR SESIÓN');
                    $("#btnIngresar").attr("disabled", false);

                    console.log("Respuesta del servidor:", respuesta);
                    
                    try {
                        var response = JSON.parse(respuesta);
                        if (response.status == "success") {
                            window.location.href = "./";
                        } else {
                            fncSweetAlert("error", "Usuario y/o password inválido");
                        }
                    } catch (e) {
                        console.error("Error al parsear JSON:", e);
                        console.error("Respuesta recibida:", respuesta);
                        alert("Respuesta del servidor:\n\n" + respuesta);
                        fncSweetAlert("error", "Error inesperado en la respuesta del servidor.");
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    $("#btnIngresar").html('INICIAR SESIÓN');
                    $("#btnIngresar").attr("disabled", false);
                    fncSweetAlert("error", "Error en la solicitud: " + textStatus);
                }
            });

        });
    </script>
</body>

</html>

