<?php
session_start();
session_destroy();

echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Sesión Limpiada</title>
    <link href='https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap' rel='stylesheet'>
    <style>
        body { font-family: 'Poppins', sans-serif; margin: 20px; text-align: center; background-color: #f4f6f9; color: #333; }
        .container { background: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); display: inline-block; }
        h1 { color: #28a745; }
        p { margin: 15px 0; font-size: 1.1em; }
        a { background: #007bff; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: 500; transition: background 0.3s; }
        a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🧹 Sesión Limpiada Correctamente</h1>
        <p>La sesión ha sido eliminada por completo.</p>
        <p>Ahora puedes volver al sistema e iniciar sesión de nuevo.</p>
        <hr style='border-top: 1px solid #eee; margin: 20px 0;'>
        <a href='index.php'>IR AL LOGIN</a>
    </div>
</body>
</html>
";
?> 