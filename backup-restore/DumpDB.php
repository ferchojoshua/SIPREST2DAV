<?php

/**
* @category    Dump.
* @package     DumpDB
* @author      Gilberto Villarreal Rodriguez  <Gil_yeung@outlook.com>
* @link        https://sitegl.com/
* @license     https://www.sitegl.com/framework-gl/?license New  License Open Source
* @description Copia de seguridad y restauración de la base de datos.
* @see         link de documentacion
* @since       Fecha de elaboracion: 01/2/2021
* @version     3.0.0
*/
class DumpDB
{
    private $dbHandler;
    private $config = array( //config default
        'sgdb' => 'mysql',
        'host' => 'localhost',
        'port' => 3306,
        'user' => 'root',
        'password' => '',
        'database' => 'test',
        'charset' => 'set names utf8',
        'file' => 'sql.sql',
        'debug' => true
    );
   
    private $msg = [];
    private $begin; // Hora de inicio
    private $status;
    private $debug;
 
    /**
    * Construct of DumpDB
    * @param array $config
    */
    public function __construct($config = array())
    {
        header('Content-Type: text/html; charset=UTF-8');
        //ini_set("auto_detect_line_endings", false);
        $this->begin = microtime(true);
        $config = is_array($config) ? $config : array();
        $this->config = array_merge($this->config, $config);
        $this->debug = $this->config["debug"];
        $this->conn();

      
    }

    /**
    * Iniciar conexión PDO
    */
    private function conn()
    {
        if (!$this->dbHandler instanceof PDO) {
            /*Data Source Name*/
            $dsn = "{$this->config['sgdb']}:host={$this->config['host']}:{$this->config['port']};" ;
            $opciones = array(
                PDO::ATTR_PERSISTENT=>true , 
                PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
            );
            try {
                $this->dbHandler = new PDO($dsn, $this->config['user'], $this->config['password'], $opciones);
                $this->dbHandler->exec($this->config['charset']);
                $this->status = true;
            } catch (PDOException $e) {
                if($this->debug)
                    $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: ".$e->getLine()." )" );
                $this->setMsg( $e->getMessage() );
                $this->status = false;
            } catch (Exception $e) {
                if($this->debug)
                    $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: ".$e->getLine()." )" );
                $this->setMsg( $e->getMessage() );
                $this->status = false;
            }
        }       
    }

    /**
    * Obtener la Version
    * @return string
    */
    public function getVersion()
    {
        $version = $this->query("SELECT version();");
        return $version[0][0];
    }

    /**
    * Create At
    * @return string
    */
    private function createAt($date = null)
    {
        if ( $date == null) 
            return date('Y-m-d H:i:s', time());
        else
            return $date;
        
    }
 
    /**
    * Asignar el Mensaje de ejecucion
    * @return mixed
    */
    public function setMsg($msg)
    {   
        $this->msg[] = $msg;
    }

    /**
    * Obtener el Mensaje de ejecucion
    * @return mixed
    */
    public function getMsg()
    {   $msg = is_array($this->msg)?implode(' ', $this->msg): $this->msg;
        return $msg;
    }

    /**
    * verifica si hay respuesta del servidor o host
    */
    public function PDOstatus()
    {
        if ($this->status){
            $res=$this->getDBS();
            $db_default=[];
            $db_default[0] = 'information_schema' ;
            $db_default[1] = 'test' ;
            if ($res == $db_default ) {
                if($this->debug)
                    $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: default )" );
                $this->setMsg( "La conexión con el servidor se estableció, pero el usuario o contraseña es incorrecto." );
                return false;
            }
        }
            
        return $this->status;
    }
 
    
    /**
    * Consultar
    * @param string $sql
    * @return mixed
    */
    private function query($sql = '', $selectDB = true )
    {
         try {
            if ($selectDB) 
                $this->dbHandler->exec("USE {$this->config['database']};");
            $stmt = $this->dbHandler->query($sql);
            $list = $stmt->fetchAll(PDO::FETCH_NUM);
            return $list;
         } catch (PDOException $e) {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: ".$e->getLine()." )" );
            $this->setMsg( $e->getMessage() );
         }
            
         
    }

    /**
    * Obtener todas las bases de datos
    * @return array
    */
    public function getDBS()
    {
        $sql = 'SHOW DATABASES';
        $list = $this->query($sql,false);
        $databases = array();
        foreach ($list as $value) {
            $databases[] = $value[0];
        }
        return $databases;
    }
 
   
    /**
    * Obtener todas las tablas
    * @return array
    */
    private function getTables()
    {
        if (!$this->PDOstatus()) return false;
        $sql = "SHOW full TABLES where Table_type='BASE TABLE'";
        $list = $this->query($sql);
        $tables = array();
        foreach ($list as $value) {
            $tables[] = $value[0];
        }
        return $tables;
    }

    /**
    * Obtener todas las vistas
    * @return array
    */
    public function getViews()
    {
        if (!$this->PDOstatus()) return false;
        $sql = "SHOW full TABLES where Table_type='VIEW'";
        $list = $this->query($sql);
        $views = array();
        foreach ($list as $value) {
            $views[] = $value[0];
        }
        return $views;
    }

    /**
    * Obtener todas las funciones
    * @return array
    */
    public function getFunctions()
    {
        if (!$this->PDOstatus()) return false;
        $sql = "SHOW FUNCTION STATUS WHERE Db = '{$this->config['database']}' ;";
        $list = $this->query($sql);
        $functions = array();
        foreach ($list as $value) {
            $functions[] = $value[1];
        }
        return $functions;
    }

    /**
    * Obtener todas los procedures
    * @return array
    */
    private function getProcedures()
    {
        if (!$this->PDOstatus()) return false;
        $sql = "SHOW PROCEDURE STATUS WHERE Db = '{$this->config['database']}' ;";
        $list = $this->query($sql);
        $functions = array();
        foreach ($list as $value) {
            $functions[] = $value[1];
        }
        return $functions;
    }

    /**
    * Obtener todas los triggers
    * @return array
    */
    private function getTriggers()
    {
        if (!$this->PDOstatus()) return false;
        $sql = "SHOW TRIGGERS;";
        $list = $this->query($sql);
        $functions = array();
        foreach ($list as $value) {
            $functions[] = $value[0];
        }
        return $functions;
    }
 
    /**
    * Obtener la declaración de definición del tipo de objeto
    * @param string $name
    * @param string $type
    * @return string $ddl
    */
    private function getDDL($name = '', $type)
    {
        $sql = "";
        $ddl = null;
        switch ($type) {
            case 'table':
                $sql = "SHOW CREATE TABLE `{$name}`";
                $ddl = $this->query($sql)[0][1] . ';';
                break;
            case 'view':
                $sql = "SHOW CREATE VIEW `{$name}`"; 
                $ddl = $this->query($sql)[0][1] . ';';
                break;
            case 'function':
                $sql = "SHOW CREATE FUNCTION `{$name}`";
                $ddl = $this->query($sql)[0][2] . ';';
                break;
            case 'procedure':
                $sql = "SHOW CREATE PROCEDURE `{$name}`";
                $ddl = $this->query($sql)[0][2] . ';';
                break;
            case 'trigger':
                $sql = "SHOW CREATE TRIGGER `{$name}`";
                $ddl = $this->query($sql)[0][2] . ';';
                break;
            default:
                $html = "";
                break;
        }
        return $ddl;
    }

    private function getData($table = '')
    {
        $sql = "SHOW COLUMNS FROM `{$table}`";
        $listColum = $this->query($sql);
        //Campo
        $columns = '';
        // El SQL a devolver
        $query = '';
        foreach ($listColum as $colum) {
            $colum = $this->stringScape($colum[0]);
            $columns .= "`{$colum}`,";
        }
        $columns = substr($columns, 0, -1);
        $data = $this->query("SELECT * FROM `{$table}`");
        if(empty($data)) return null;
           
        $query .= "INSERT INTO `{$table}` ({$columns}) VALUES\r\n";
        $rowData = '';
        foreach ($data as $value) {
            $rowData .= '(';
            $columData = '';
            foreach ($value as $v) {
                $v = $this->stringScape($v);
                $columData .= "'{$v}',";
            }
            $columData = substr($columData, 0, -1);
            $rowData .=  $columData."),\r\n";
        }
        $rowData = substr($rowData, 0, -3);
        $query .= "{$rowData};\r\n";
        return $query;
    }

    private function generateStringTables(){
        
 
        $strTables = "";
        if (!empty( $this->getTables() )) {
            
            foreach ($this->getTables() as $tableName) {
                $ddl = $this->getDDL($tableName, "table");
                $data = $this->getData($tableName);

                $strTables .= "\r\n";
                $strTables .= "-- ----------------------------\r\n";
                $strTables .= "-- Table structure for {$tableName}\r\n";
                $strTables .= "-- ----------------------------\r\n \r\n";
                $strTables .= "DROP TABLE IF EXISTS `{$tableName}`;\r\n";
                $strTables .= $ddl . "\r\n \r\n";
                if (!empty($data)){
                    $strTables .= "-- ----------------------------\r\n";
                    $strTables .= "-- Records for table {$tableName}\r\n";
                    $strTables .= "-- ----------------------------\r\n \r\n";
                    $strTables .= $data . "";
                }
            }
            return $strTables;
        } else {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; )" );
            $this->setMsg( '¡No hay tablas en la base de datos!' );
            return null;
        }
    }

    private function generateStringViews(){
      
        $strViews = "\r\n-- ============================================== VIEWS ================================================\r\n";
        if (!empty($this->getViews())) {
            
            foreach ($this->getViews() as $viewName) {
                $ddl = $this->getDDL($viewName, "view");
                $strViews .= "-- ----------------------------\r\n";
                $strViews .= "-- View structure for {$viewName}\r\n";
                $strViews .= "-- ----------------------------\r\n \r\n";
                $strViews .= "DROP VIEW IF EXISTS `{$viewName}`;\r\n";
                $strViews .= $ddl . "\r\n";
               
            }
            return $strViews;
        } else {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; )" );
            $this->setMsg( '¡No hay vistas la base de datos!' );
            return null;
        }
    }

    private function generateStringFunctions(){
      
        $strFs = "\r\n-- ============================================== FUNCTIONS ================================================\r\n";
        if (!empty($this->getFunctions())) {
            
            foreach ($this->getFunctions() as $functionName) {
                $ddl = $this->getDDL($functionName, "function");
                $strFs .= "-- ----------------------------\r\n";
                $strFs .= "-- Function structure for {$functionName}\r\n";
                $strFs .= "-- ----------------------------\r\n \r\n";
                $strFs .= "DROP FUNCTION IF EXISTS `{$functionName}`;\r\n";
                $strFs .= "DELIMITER ;; \r\n";
                $ddl = substr($ddl,0, -1);
                $strFs .= $ddl . " ;; \r\n";
                $strFs .= "DELIMITER ; \r\n";
               
            }
            return $strFs;
        } else {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; )" );
            $this->setMsg( '¡No hay Funciones la base de datos!' );
            return null;
        }
    }

    private function generateStringProcedures(){
      
        $strPs = "\r\n-- ============================================== PROCEDURES ================================================\r\n";
        if (!empty($this->getProcedures())) {
            
            foreach ($this->getProcedures() as $procedureName) {
                $ddl = $this->getDDL($procedureName, "procedure");
                $strPs .= "-- ----------------------------\r\n";
                $strPs .= "-- Procedure structure for {$procedureName}\r\n";
                $strPs .= "-- ----------------------------\r\n \r\n";
                $strPs .= "DROP PROCEDURE IF EXISTS `{$procedureName}`;\r\n";
                $strPs .= "DELIMITER ;; \r\n";
                $ddl = substr($ddl,0, -1);
                $strPs .= $ddl . " ;; \r\n";
                $strPs .= "DELIMITER ; \r\n";
               
            }
            return $strPs;
        } else {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; )" );
            $this->setMsg( '¡No hay Procedures la base de datos!' );
            return null;
        }
    }

    private function generateStringTriggers(){
      
        $strTs = "\r\n-- ============================================== TRIGGERS ================================================\r\n";
        if (!empty($this->getTriggers())) {
            
            foreach ($this->getTriggers() as $triggerName) {
                $ddl = $this->getDDL($triggerName, "trigger");
                $strTs .= "-- ----------------------------\r\n";
                $strTs .= "-- Trigger structure for {$triggerName}\r\n";
                $strTs .= "-- ----------------------------\r\n \r\n";
                $strTs .= "DROP TRIGGER IF EXISTS `{$triggerName}`;\r\n";
                $strTs .= "DELIMITER ;; \r\n";
                $ddl = substr($ddl,0, -1);
                $strTs .= $ddl . " ;; \r\n";
                $strTs .= "DELIMITER ; \r\n";
               
            }
            return $strTs;
        } else {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; )" );
            $this->setMsg( '¡No hay Triggers la base de datos!' );
            return null;
        }
    }

     /**
    * backup DB
    * @param array $tables
    * @return bool
    */
    public function backup()
    {
        if (!$this->PDOstatus()) return false;
        if ($this->config['database'] == 'all' || $this->config['database'] == 'All') {
            $this->setMsg( "¡Esperalo muy pronto!" ); return false;
        }
        //Empieza a escribir
        return $this->writeToFile();
        
    }

    /**
    * Escribir archivo
    * @param string $strTables
    * @param string $strViews
    */
    private function writeToFile()
    {
        $str = "/*{------------------------------------------------------------------------\r\n";
        $str .= "|  MySQL Database Backup DumpDB\r\n|\r\n";
        $str .= "|  Host: {$this->config['host']}  ";
        $str .= "  Database: {$this->config['database']}  ";
        $str .= "  CreateAt: " . $this->createAt() . "\r\n|\r\n";
        $str .= "|  Server: version " . $this->getVersion() . "\r\n|\r\n";
        $str .= "|  Author of DumpDB: < gil_yeung@outlook.com > Gilberto Villarreal Rodriguez \r\n";
        $str .= "*-------------------------------------------------------------------------}*/";
        $str .= "\r\n \r\n\r\n";
        //$str .= "SET FOREIGN_KEY_CHECKS=0;\r\n\r\n";
        $str .= "SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;\r\n\r\n";

        $str .= "\r\n-- ================================== BEGIN DATABASE {$this->config['database']} =======================================\r\n";
        $str .= "-- DROP DATABASE IF EXISTS `{$this->config['database']}`;\r\n";
        $str .= "-- CREATE DATABASE `{$this->config['database']}`;\r\n";
        $str .= "-- USE `{$this->config['database']}`;\r\n\r\n";
        $str .= "-- DROP TABLE tablename1, tablename2, tablename3, tablename4;\r\n\r\n";
       
        $str .= $this->generateStringTables();
        $str .= "/*{";
        $str .= $this->generateStringViews();
        $str .= $this->generateStringFunctions();
        $str .= $this->generateStringProcedures();
        $str .= $this->generateStringTriggers();
        $str .= "}*/";
        
        $str .= "\r\n-- ================================== END DATABASE {$this->config['database']} ========================================\r\n";
        //$str .= "\r\n \r\nSET FOREIGN_KEY_CHECKS=1;\r\n";
        $str .= "\r\n \r\nSET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;\r\n";

        if (file_put_contents($this->config['file'], $str) ){
            $this->setMsg( '¡Copia de seguridad exitosa! tiempo de ejecucion='.(microtime(true) - $this->begin).'ms' );
            return true;
        }
        else{
           $this->setMsg( '¡Error de copia de seguridad!' );
           return false;
        }
    }

   
    /**
    * Restaurar SQL
    * @param string $path, bool $outComment
    * @return bool
    */
    public function restore($path = '',$outComment = false)
    {
        if (!$this->PDOstatus()) return false;
        if ($this->config['database'] == 'all' || $this->config['database'] == 'All') {
            $this->setMsg( "¡Esperalo muy pronto!" );return false;
        }
        if (!file_exists($path)) {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; )" );
            $this->setMsg( ('¡El archivo SQL no existe!') );
            return false;
        } else {
            $sql = $this->parseSQL($path,$outComment);
            try {

                    $this->createDBIfNoExists();
                    $this->dbHandler->exec("USE {$this->config['database']};");
                    $this->checkPermit(); 
                    $this->dbHandler->beginTransaction();#Inicializa la transaccion
                    
                    //$sql = $this->dbHandler->quote($sql);
                    $res = $this->dbHandler->exec($sql);
            
                    if($res===0){
                        $this->dbHandler->commit();#Fuerza a que los cambios se almacenen en la base de datos
                        $this->setMsg(  '¡Restauración exitosa! tiempo de ejecucion:'. (microtime(true) - $this->begin). 'ms' ); 
                        return true;
 
                     }else{ 
                        $this->dbHandler->rollback();#Vuelve atras la transaccion
                        $this->setMsg( '¡Ocurrio un error al restaurar! tiempo de ejecucion: '. (microtime(true) - $this->begin). 'ms' ); return false;
                     }
                
            } catch (PDOException $e) {
                if($this->debug)
                    $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: ".$e->getLine()." )" );
                $this->setMsg( $e->getMessage() );
                return false;
            }
        }
    }
 
    /**
    * Analizar el archivo SQL en una matriz de declaraciones SQL
    * @param string $path, bool $outComment
    * @return array|mixed|string
    */
    private function parseSQL($path = '',$outComment )
    {
        $sql = file_get_contents($path);
        $sql = explode("\r\n", $sql);
        // Primero eliminar - comentar
        $sql = array_filter($sql, function ($data) {
            if (empty($data) || preg_match('/^--.*/', $data)) {
                return false;
            } else {
                return true;
            }
        });
        $sql = implode(" ", $sql);
        #Eliminar comentario
        $sql = preg_replace('/\h*\/\*{.*?}\*\/\h*/s', ' ', $sql);//eliminar /*{comment}*/;
        if($outComment){
            //$sql = preg_replace('/\/\*!*.*\*\//', ' ', $sql);
            $sql = preg_replace('/\h*\/\*!.*?\*\/;\h*/s', ' ', $sql);//eliminar /*!code*/;
            $sql = preg_replace('/\h*\/\*.*?\*\/\h*/s', ' ', $sql); //eliminar /*comment*/
            $sql="SET FOREIGN_KEY_CHECKS=0;".$sql."SET FOREIGN_KEY_CHECKS=1;";
        }
        $sql = str_replace("DELIMITER",PHP_EOL."\r\n DELIMITER ",$sql);
        return $sql;
    }

    /**
    * Analizar el archivo Si cuenta con permiso CRUD SQL
    */
    public function checkPermit(){
        
          $namedb="gl_test";
          $this->dbHandler->exec("DROP TABLE IF EXISTS `$namedb`;
            CREATE TABLE `$namedb` (
              `id` int(2) NOT NULL AUTO_INCREMENT,
              `updat` int(2) unsigned DEFAULT '1',
              PRIMARY KEY (`id`)
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
             INSERT INTO `$namedb` (`id`) VALUES('1');
             UPDATE `$namedb` SET `updat`='2' WHERE  `id`=1;
             DROP TABLE IF EXISTS `$namedb`;
             ");
    }

    /**
    * Borrar una BD
    * @return bool
    */
    public function dropDB(){
        try {   
            if (!$this->PDOstatus()) return false;
                $db=$this->dbHandler->query("DROP DATABASE IF EXISTS {$this->config['database']} ");
        } catch (PDOException $e) {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: ".$e->getLine()." )" );
            $this->setMsg( $e->getMessage() );
        }
    }

    /**
    * Crear una BD
    * @return bool
    */
    public function createDBIfNoExists(){
        try {
            if (!$this->PDOstatus()) return false;
            $db=$this->dbHandler->query("SHOW DATABASES LIKE '{$this->config['database']}' ");
            $res=$db->rowCount();
            if ($res<1) {
                $this->dbHandler->query("CREATE DATABASE {$this->config['database']} ");
            }
        } catch (PDOException $e) {
            if($this->debug)
                $this->setMsg( "ERROR (class: ".__CLASS__."; method: ".__FUNCTION__."; in line: ".$e->getLine()." )" );
            $this->setMsg( $e->getMessage() );
            //$_POST["response"]=$error;
            //href(BASE_URL.'?r='.encodeUrl( $_POST) );exit();
        }
    }

    /**
    * Crea un archivo zip
    * @param string $name_zip
    * @param string $name_archive
    * @return string $name_zip
    */
    public function createZip( $name_zip, $name_archive){
        $zip = new ZipArchive(); //Objeto de Libreria ZipArchive
        if($zip->open($name_zip,ZIPARCHIVE::CREATE)==true) { //Creamos y abrimos el archivo ZIP
            $zip->addFile($name_archive); //Agregamos el archivo SQL a ZIP
            $zip->close(); //Cerramos el ZIP
            unlink($name_archive); //Eliminamos el archivo temporal 
        } else 
            echo 'Error inesperado'; //Enviamos el mensaje de error
        return $name_zip;
    }

    /**
    * Borrar achivo
    * @param string $fileName
    * @return bool
    */
    public function delFileName($fileName){  
        if (@unlink ($fileName )) { 
            $this->setMsg( $fileName.' ¡Eliminado exitosamente!' );
            return true;
        }
        else{ 
            $this->setMsg( $fileName.' ¡Error al eliminar!' );
            return false;
        }
    }

    /**
    * Descargar copia de seguridad
    * @param string $fileName
    * @return array|mixed|string
    */
    public function downloadFile($fileName) {

        if (file_exists($fileName)){
            ob_clean();
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($fileName));
                header('Content-Transfer-Encoding: binary');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($fileName));
            ob_end_clean();
            flush();
            readfile($fileName);
        }else{
                $this->setMsg(  $fileName." ¡Nose encontro el archivo!" );
        }
    }

    public function stringScape($val){
        $string = str_replace(["\\","'",'"'],["\\\\","\\'",'\\"'],$val); 

        return $string;
    }
}