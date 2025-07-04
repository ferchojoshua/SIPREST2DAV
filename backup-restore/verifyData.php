 <?php

    $host=""; $port=""; $user=""; $pwd=""; $name_db=""; $file_sql="";$res_msg = "";$checked="";  $res_msg = "";

    if(isset($_GET['r']) )
    {
        $data = decodeUrl($_GET['r']); // para no perder los datos del formulario.
        if(isset($data['checked'])) $checked = "checked"; 
        
        $host = $data['host'];
        $port = $data['port'];
        $user = $data['user'];
        $pwd = $data['pwd'];
        $name_db = $data['name_db']; 
      
        $res_msg = setTextUtf8($data['response']); // mensaje que recibira all.js
      
    }else if ( isset($_POST['show_db']) || isset($_POST['backup']) || isset($_POST['restore'])) {

        $host = $_POST['host'];
        $port = $_POST['port'];
        $user = $_POST['user'];
        $pwd = $_POST['pwd'];
        $name_db = $_POST['name_db'];
    }


    if (!file_exists(ROOT_PATH)){echo "No se encontro: ".ROOT_PATH; exit();}

   
?>          