<?php

function encodeUrl($array){
	$url_r=serialize($array); //n° serie para enviar y recivir misma cadena o datos. 
	$url_r=urlencode($url_r);
	$url_r=base64_encode($url_r);  
	return $url_r;
}
function decodeUrl($array){
        
    $url_r=base64_decode($array);
    $url_r=urldecode($url_r);
    $url_r=unserialize($url_r);
    return $url_r;
}

function href($url){
	header('Location:'.$url);
}


function setTextUtf8($str){ 
    if (mb_detect_encoding($str, 'UTF-8', true)) // detectar si es UTF8
        $str = $str;
    else
        $str = utf8_encode($str);
    //$str = preg_replace('[Â]','',$str);
    return($str);
}

/**
* Devuelve la fecha o fecha y hora del momento.
* @param  string $format  formato de fecha.
* @return string $date  Formato de fecha.
* @return string $dateTime  Formato de fecha y hora.
*/
function now($format="Y-m-d H:i:s")
{
    return date($format);
}

function showDatabases($config)
{
    $dbDump = new dumpDB($config);
    if ($dbDump->PDOStatus() === true){
        $res=$dbDump->getDBS();
        echo json_encode($res);
    }
    else{
         $msg = setTextUtf8($dbDump->getMsg());
         print_r($msg);
    }
    exit();

}

function backup($config){
    
         $dbDump = new dumpDB($config);
         if ($dbDump->PDOstatus() === true){
             if( $dbDump->backup() ){
                 $fecha = date("Y-m-d_H.i.s"); //Obtenemos la fecha y hora
                 $mi_zip="".$dbDump->createZip( "download/{$config['database']}_$fecha.zip", $config['file']);//name_zip, name_archive
                 //href($mi_zip); // Redireccionamos para descargar el Arcivo ZIP
                 $dbDump->downloadFile($mi_zip);
             }
             else{
                 $_POST["response"]=$dbDump->getMsg();
                 href(BASE_URL.'?r='.encodeUrl( $_POST ) ); 
             }
         }
         else{
             $_POST["response"]=$dbDump->getMsg();
             href(BASE_URL.'?r='.encodeUrl( $_POST ) );
         }
     
}

function restore($config){
    $input_sql = $_FILES['file_db']['tmp_name']; //Archivo temporal .sql
	$_POST["checked"]='checked';
    $dbDump = new dumpDB($config);

    if ($dbDump->PDOstatus() === true){
        $res = $dbDump->restore( $input_sql,false);
        if( $res ){
            $_POST["response"]='restored';
            href(BASE_URL.'?r='.encodeUrl( $_POST ) );
        }
        else{
            $_POST["response"]=$dbDump->getMsg();
            href(BASE_URL.'?r='.encodeUrl( $_POST ) ); 
        }
    }
    else{
        $_POST["response"]=$dbDump->getMsg();
        href(BASE_URL.'?r='.encodeUrl( $_POST ) );
    }
}

?>