<?php
namespace Catatumbo\Driverdb;
use Catatumbo\Driverdb\ConfigDatabase;
use Catatumbo\Core\Common;
/**
 * @propiedad: PROPIETARIO DEL CODIGO
 * @Autor: Gregorio Bolivar
 * @email: elalconxvii@gmail.com
 * @Fecha de Creacion: 02/11/2015
 * @Auditado por: Gregorio J Bolívar B
 * @Descripción: conexion de la base de datos
 * @package: ConexionDataBase.class
 * @version: 1.2
 */

class Database
{
    //creamos la conexión a la base de datos prueba
    public $conn;
    public function __construct()
    {
        try{
            self::constructConexion();
            try {
                $a = func_get_args();
                $index = $a[0];
                $config = new ConfigDatabase();
                $v=$config->$index();
                if($v->driv=='sqlite'){
                    $this->conn = new \PDO($v->dbna);
                }else{
                    $this->conn = new \PDO($v->driv.':host='.$v->host.';dbname='.$v->dbna.';charset=utf8',''.$v->user.'',''.$v->pass.'');
                }

            }catch(\PDOException $e){
                header('Content-Type: text/html; charset=utf-8');
                die( "ERROR: " . $e->getMessage());
            }
        }catch(Exception $e){
            header('Content-Type: text/html; charset=utf-8');
            echo( "Excepción capturada: " . $e->getMessage());
            die();

        }
        return $this->conn;
    }

    public function constructConexion(){
        // Construimos automaticamente el archivo ConfigDatabaseTMP.php
        self::constructConfigDataBase();
        // Validamos que el archivo temporal creado anteriormente sea el mismo de la conexion de lo contrario procedemos a copiar el tmp
        self::validateFileIdentico();
        $file = __DIR__.'/ConfigDatabase.php';
        //include_once $file;
    }
    public function constructConfigDataBase(){
        if (file_exists($file = __DIR__.'/../../config/databases.ini')) {
            $config = parse_ini_file(__DIR__.'/../../config/databases.ini', true);
            // Validamos que los dos archivos no existen

            $ar = fopen(__DIR__."/ConfigDatabaseTmp.php", "w+") or die("Problemas en la creaci&oacute;n del archivo  " . $file);
            // Inicio la escritura en el activo
            fputs($ar,'<?php'. PHP_EOL);
            fputs($ar,'namespace Catatumbo\Driverdb;'. PHP_EOL);
            fputs($ar,'/**'. PHP_EOL);
            fputs($ar,'* @propiedad: catatumbo'. PHP_EOL);
            fputs($ar,'* @Autor: Gregorio Bolivar'. PHP_EOL);
            fputs($ar,'* @email: elalconxvii@gmail.com'. PHP_EOL);
            fputs($ar,'* @Fecha de Creacion: ' . date('d/m/Y') . ''. PHP_EOL);
            fputs($ar,'* @Auditado por: Gregorio J Bolívar B'. PHP_EOL);
            fputs($ar,'* @Descripción: Generado por el generador de codigo de router de webStores'. PHP_EOL);
            fputs($ar,'* @package: ConfigDatabase.php'. PHP_EOL);
            fputs($ar,'* @version: 1.0'. PHP_EOL);
            fputs($ar,'* @webAutor: http://www.gregoriobolivar.com.ve/'. PHP_EOL);
            fputs($ar,'* @BlogAutor: http://gbbolivar.wordpress.com/'. PHP_EOL);
            fputs($ar,'*/ '. PHP_EOL);
            // capturador del get que esta pasando por parametro
            fputs($ar, 'class ConfigDatabase'. PHP_EOL);
            fputs($ar, '  {'. PHP_EOL);
            fputs($ar, '  public $driv, $host, $port, $user, $pass,  $dbname, $dbh;'. PHP_EOL);
            fputs($ar, '  function __construct()'. PHP_EOL.'{'. PHP_EOL);
            fputs($ar, '    $this->driv; $this->host; $this->port; $this->user; $this->pass;  $this->dbname; $this->dbh;'. PHP_EOL);
            fputs($ar, '  }'. PHP_EOL);
            foreach ($config as $key => $value):
                fputs($ar, '  /** Inicio  del method  de ' . $key . '  */'. PHP_EOL);
                fputs($ar, '  public function '.$key.'()'.PHP_EOL.'{'. PHP_EOL);
                fputs($ar, "    // Driver de Conexion con la de base de datos". PHP_EOL);
                self::validateDriverConexion($value['driv']);
                fputs($ar, '    $this->driv = \''.$value['driv'].'\';'. PHP_EOL);
                fputs($ar, "    // IP o HOST de comunicacion con el servidor de base de datos". PHP_EOL);
                fputs($ar, '    $this->host = \''.$value['host'].'\';'. PHP_EOL);
                fputs($ar, "    // Puerto de comunicacion con el servidor de base de datos". PHP_EOL);
                fputs($ar, '    $this->port = \''.$value['port'].'\';'. PHP_EOL);
                fputs($ar, "    // Nombre base de datos". PHP_EOL);
                fputs($ar, '    $this->dbna = \''.$value['dbh'].'\';'. PHP_EOL);
                fputs($ar, "    // Usuario de acceso a la base de datos". PHP_EOL);
                fputs($ar, '    $this->user = \''.$value['user'].'\';'. PHP_EOL);
                fputs($ar, "    // Clave de ac  ceso a la base de datos". PHP_EOL);
                fputs($ar, '    $this->pass = \''.$value['pasw'].'\';'. PHP_EOL);
                fputs($ar, '    return $this;'. PHP_EOL);
                fputs($ar, '  }'. PHP_EOL);
                fputs($ar, '  /** Fin del caso del method de ' . $key . ' */'. PHP_EOL);
            endforeach;
            fputs($ar, "  }". PHP_EOL);
            fputs($ar, "?>". PHP_EOL);
            // Cierro el archivo y la escritura
            fclose($ar);
        }else {
            throw new Exception('El archivo <b>ConfigDatabase.php</b> se esta construyendo.');
        }
        return true;
    }

    public function validateFileIdentico(){
        $fileCofNol = __DIR__.'/ConfigDatabase.php';
        $fileTmpNol = __DIR__.'/ConfigDatabaseTmp.php';
        $fileCofMd5 = md5(@file_get_contents($fileCofNol));
        $fileTmpMd5 = md5(file_get_contents($fileTmpNol));
        if($fileCofMd5 != $fileTmpMd5){
            copy($fileTmpNol, $fileCofNol);
        }
        return true;
    }

    public function validateDriverConexion($driver){
        $arrayName = \PDO::getAvailableDrivers();
        if (!in_array($driver, $arrayName)) {
            throw new Exception('El archivo <b>databases.ini</b> solicitaron el driver <b>'.$driver.'</b> por PDO que no esta soportado por el servidor.');
        }
    }
    //función para cerrar una conexión pdo
    public function close_con()
    {
        $this->conn = null;
        return $this->conn;
    }
}
?>
