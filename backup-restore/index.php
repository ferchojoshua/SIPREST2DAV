<?php 
    define("BASE_URL","/siprest/backup-restore/");
    define("ROOT_PATH",  $_SERVER["DOCUMENT_ROOT"]."/siprest/backup-restore/"); 
    //echo BASE_URL."<br>".ROOT_PATH;exit();
    #Configurar sona horaria.
    date_default_timezone_set('America/Lima');
    setlocale(LC_ALL,"es_ES");
    #Configurar UTF8.
    header('Content-Type: text/html; charset=UTF-8');
    
    require_once('function.php');
    require_once('verifyData.php');
    require_once('DumpDB.php');

    $config = array(
        'sgdb' => 'mysql',
        'host' => $host, //Host del Servidor MySQL
        'port' => $port, //port del Servidor MySQL
        'user' => $user, //Usuario de MySQL
        'password' => $pwd, //Password de Usuario MySQL
        'database' => $name_db, //Nombre de la Base de datos
        'charset' => 'set names utf8', //Establecer UTF-8
        'file' => $name_db.'.sql', //Nombre del archivo backup
        'debug' => false
    );

    if (isset($_POST['show_db']))
        showDatabases($config);
    else if (isset($_POST['backup']))
        backup($config);
    else if (isset($_POST['restore']))
        restore($config);

    ?>
<!DOCTYPE HTML>
<html style="background: #e9ecef">
    <!-- ===============================================================================
    HEADER
    =================================================================================-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"> 

        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="expires" content="-1" />  
        <meta name="viewport" content="width-device-width, user-scalable=no, initial-scale=1.0, maiximum-scale=1.0, minimum-scale=1.0">
        <title> Backup y Restaurar bd </title>
        

        <link href="css/bootstrap.css" rel="stylesheet" type="text/css" >
        <link href="css/login.css" rel="stylesheet" type="text/css" >

        <script src="js/sweetalert.min.js"></script>
    </head>

    <body >
        <div id="loader"></div>
        
        <header class="myheader">
                 <h2 class="mytitle "></h2>
                <br><img class="img-responsive" >
        </header>    
    
        <!-- ===============================================================================
         BACKGROUND IMG
        =================================================================================-->
        <img  class="body">
         <!-- ===============================================================================
         BOX
        =================================================================================-->
        <div class="login-wrap swing">
            <div class="login-html">
                <input id="tab-1" type="radio"  name="tab" class="sign-in" checked><label for="tab-1" class="tab hand">BACKUP</label>

                <input id="tab-2" type="hidden" <?= $checked; ?> name="tab" class="sign-up" ><label for="tab-2" class="tab hand" hidden></label>
                <div class="login-form">
                    <!-- ===============================================================================
                     BACKUP
                    =================================================================================-->
                    <form class="sign-in-htm" action="?" method="POST" name="form-in" onsubmit="return false">
                        <input  name="backup"  type="hidden"  value="1">
                        <div class="group">
                            <label for="host" class="label">Host:</label>
                            <input id="host" name="host"  type="text" class="input threed" value="<?= $host; ?>" placeholder="localhost" required>
                        </div>
                         <div class="group">
                            <label for="port" class="label">Port:</label>
                            <input id="port" name="port"  type="number" class="input threed" value="<?= $port; ?>" placeholder="3306" required>
                        </div>
                         <div class="group">
                            <label for="user" class="label">Usuario:</label>
                            <input id="user" name="user"  type="text" class="input threed" value="<?= $user; ?>" placeholder="root" required>
                        </div>
                         <div class="group">
                            <label for="pwd" class="label">Contraseña:</label>
                            <input id="pwd" name="pwd"  type="password" class="input threed" value="<?= $pwd; ?>"  placeholder="Tu contraseña" >
                        </div>
                        <div id="select_database" class="group">
                            <label for="list_db" class="label">Base de Datos:</label>
                            <select class="input threed" id="list_db" required name="name_db"  >
                                <option hidden="" value="">Seleccione su base de datos</option>
                            </select>
                        </div> 
                         <div id="button_continue" class="group pading">
                            <input type="submit" class="button grow"  value="Continuar">
                        </div>
                        <div id="button_submit" class="group pading">
                            <input type="submit" class="button grow"  value="Descargar Copia de db">
                        </div>
                        <div class="hr"></div>
                        <div class="foot-lnk">
                            <a href="?">Actualizar</a>
                        </div>
                    </form>
                    <!-- ===============================================================================
                     RESTORE
                    =================================================================================-->
                    <form class="sign-up-htm" action="?" method="POST" enctype='multipart/form-data' name="myForm" hidden>
                        <input  name="restore"  type="hidden"  value="1">
                        <div class="group" >
                            <label for="host2" class="label">Host:</label>
                            <input id="host2" type="text" name="host" class="input threed" value="<?= $host; ?>" placeholder="localhost" required>
                        </div>
                         <div class="group">
                            <label for="port2" class="label">Port:</label>
                            <input id="port2" name="port"  type="number" class="input threed" value="<?= $port; ?>" placeholder="3306" required>
                        </div>
                        <div class="group" >
                            <label for="user2" class="label">Usuario:</label>
                            <input id="user2" type="text" name="user" class="input threed" value="<?= $user; ?>" placeholder="root" required>
                        </div>
                         <div class="group" >
                            <label for="pwd2" class="label">Contraseña:</label>
                            <input id="pwd2" type="password" name="pwd" class="input threed" value="<?= $pwd; ?>" placeholder="Tu contraseña" >
                        </div>
                         <div class="group" >
                            <label for="name_db" class="label">Nombre DB:</label>
                            <input id="name_db" type="text" name="name_db" class="input threed" value="<?= $name_db; ?>" placeholder="Nombre de bd" required>
                        </div>
                        <div class="group" >
                            <label for="file_db" class="label">Archivo .sql:</label>
                            <input id="file_db" type="file" accept=".sql" name="file_db" class="input threed" value="<?= $file_sql; ?>" required>
                        </div>
                        <div class="group pading">
                            <input type="submit" class="button grow js-restore" value="Restaurar" >
                        </div>
                       
                    </form>
                </div>  
            </div>
        </div>
        <!-- ===============================================================================
        FOOTER
        =================================================================================-->
        
        <div class="templatemo-content-widget " style="background-color: #e9ecef">
            <br>
            <div class="container">
                 
            <div class="flex-footer">
                <div><h2> </h2></div>
                <div><p> </p></div>
                <div><a href="#" style="color: #fff">  </a></div>
                <div></div>
            </div>
        </div>

        <footer>
               <script type="text/javascript">  var res_msg = `<?= $res_msg ?>`;</script>
               <script src="js/all.js"></script>
        </footer>       
    </body>
</html>