<?php
namespace Catatumbo\Complements\Database;
use Catatumbo\Core\Commun\All;
use Catatumbo\Core\Commun\Logs;
use Catatumbo\Core\Store\Cache;
use mysqli;

/**
 * Driver que permite hacer la conexion con la base de datos  
 */
trait Db  {

    private $conn;
    private $resultSet;
    private $result;
    private $inited;
    public $error;
    public $errorno;
    public $message;

    public $motor;
    public $host;   
    public $user;
    public $pass;
    public $db;
    public $encoding;
    use Logs;

    public function connect($datos) {
        $this->host = $datos->host;
        $this->user = $datos->user;
        $this->pass = $datos->pass;
        $this->db = $datos->db;
        $this->encoding = $datos->encoding;
        $this->motor = $datos->motor;

        switch ($this->motor) {
            case "maria":
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

            if ($this->conn->connect_error) {
                $this->error = true;
                $this->inited = false;
                $this->message = mysqli_connect_error();
            } else {
                mysqli_set_charset($this->conn, $this->encoding);
                $this->inited = true;
                $this->error = false;
            }
            break;
            case "sql":
                try {
                    $this->conn = sqlsrv_connect($this->host, array("Database" => $this->db, "CharacterSet" => $this->encoding, "UID" => $this->user, "PWD" => $this->pass));
                    //print_r($this->conn);
                    if (!$this->conn) {
                        $this->error = true;
                        $this->inited = false;
                        $this->message = sqlsrv_errors();
                        $msj = "<b>Exepción Capturada Error en Conexion</b>:, no se puede conectar al servidor de base de datos revisar los parametros de config";
                        $this->logError($msj);
                        throw new \TypeError();

                    } else {
                        $this->inited = true;
                        $this->elserror = false;
                    }
                }catch (\TypeError  $t){
                    All::statusHttp(501);
                    $this->logError($t->getMessage());
                    die($t->getMessage());
                }

            break;

        }

        return $this;
    }
    /**
     * Fijar si existe la conexion a base de datos
     */
    public function init() {
        if (!$this->inited) {
            switch ($this->motor) {
                case "maria":
                $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db) or die(mysqli_connect_error());
                mysqli_set_charset($this->conn, $this->encoding);
                $this->inited = true;
                break;
                case "sql":
                $this->conn = sqlsrv_connect($this->host, array("Database" => $this->db, "CharacterSet" => $this->encoding, "UID" => $this->user, "PWD" => $this->pass, "ReturnDatesAsStrings" => true));
                $this->inited = true;
                break;
            }
        }
    }
    /**
     * Executa el query y trae el valor
     * @param string $query 
     * @return bool boolean
     */
    public function get($query) {
        if (!$this->inited) {
            $this->init();
        }
        try {
            switch ($this->motor) {
                case "maria":
                    $this->resultSet = $this->conn->query($query);

                    if ($this->conn->errno > 0) {
                        $this->error = true;
                        $this->errorno = $this->conn->errno;
                        $this->message = mysqli_error($this->conn);
                    } else {
                        $this->error = false;
                        $this->errorno = 0;
                        $this->message = '';
                    }
                    break;
                case "sql":
                    $this->resultSet = sqlsrv_query($this->conn, $query);
                    // variables de proceso de auditoria de registro de las accciones en base de datos guardar en log
                    //if((bool)Cache::get('debug')){
                        $this->logInfo($query);
                    //  }
                    if (count(sqlsrv_errors())>0) {
                        $erro = sqlsrv_errors();
                        $this->error = true;
                        $this->inited = false;
                        $this->message = sqlsrv_errors();
                        $msj = 'Exepcion capturadad de base de datos, code error:'.$erro[0]['SQLSTATE'].', mensaje:'.$erro[0]['message'];
                        $this->logError($msj);
                        throw new \TypeError($msj);

                    } else {
                        $this->error = false;
                        $this->errorno = 0;
                        $this->message = '';
                    }

                    break;
            }
        }catch (\TypeError $t){
            All::statusHttp(501);
            $this->logError($t->getMessage());
            die($t->getMessage());
        }
    }

    /**
     * Es para ejecutar store procedure
     * @param string $query
     * @return object $this
     */
    public function execute($query) {
        // variables de proceso de auditoria de registro de las accciones en base de datos guardar en log
        if((bool)Cache::get('debug')){
            $this->logInfo($query);
        }
        if (!$this->inited) {
            $this->init();
        }
        try {
            switch ($this->motor) {
                case "maria":
                    $this->result = $this->conn->query($query);

                    if ($this->conn->errno > 0) {
                        $this->error = true;
                        $this->errorno = $this->conn->errno;
                        $this->message = mysqli_error($this->conn);
                    } else {
                        $this->error = false;
                        $this->errorno = 0;
                        $this->message = '';
                    }
                    break;
                case "sql":
                    $this->result = sqlsrv_query($this->conn, $query);
                    if (count(sqlsrv_errors())>0) {
                        $erro = sqlsrv_errors();
                        $this->error = true;
                        $this->inited = false;
                        $this->message = sqlsrv_errors();
                        $msj = 'Exepcion capturadad de base de datos, code error:'.$erro[0]['SQLSTATE'].', mensaje:'.$erro[0]['message'];
                        $this->logError($msj);
                        throw new \TypeError($msj);
                    } else {
                        $this->error = false;
                        $this->errorno = 0;
                        $this->message = '';
                    }
                    break;
            }
        }catch (\TypeError $t){
            All::statusHttp(501);
            die($t->getMessage());
        }

        return $this;
    }

    public function execute_multi($query) {
        if (!$this->inited) {
            $this->init();
        }
        $this->result = $this->conn->multi_query($query);
        return $this->result;
    }

    public function fetchAll(){
        while ($row = $this->db->fetch()) {
            $datos[] = $row;
        }
        return $datos;
    }

    public function fetch($resultSet = false) {
        if (!$resultSet) {
            $resultSet = $this->resultSet;
        }

        switch ($this->motor){
            case "maria":
            return $resultSet->fetch_object();
            break;
            case "sql":
            if ($resultSet){
                return sqlsrv_fetch_object($resultSet);
            }
            break;
        }
        return $this;
    }

    public function numRows($resultSet = false) {
        if (!$resultSet) {
            $resultSet = $this->resultSet;
        }

        switch ($this->motor) {
            case "maria":
            return $resultSet->num_rows;
            break;
            case "sql":
            if (sqlsrv_has_rows($resultSet)) {
                return 1;
            } else {
                return 0;
            }

            break;
        }
    }

    /**
     * Permite estraer el ultimo id del insert 
     * @return type
     */
    public function lastId() {
        if ($this->inited) {
            switch ($this->motor) {
                case "maria":
                return mysqli_insert_id($this->conn);
                break;
                case "sql":
                $this->get("SELECT SCOPE_IDENTITY() AS id");
                $row = $this->fetch();
                return $row->id;
                break;
            }
        } else {
            return false;
        }
    }

    /**
     * Escapar los valores de elementos de caracteres especiales
     * @param type $query 
     * @return type
     */
    public function escape($query) {
        if (!$this->inited) {
            $this->init();
        }

        switch ($this->motor) {
            case "maria":
            return $this->conn->real_escape_string($query);
            break;
            case "sql":
            return $query;
            break;
        }
    }

    public function free($resultSet = false) {
        if (!$resultSet) {
            $resultSet = $this->resultSet;
        }

        switch ($this->motor) {
            case "maria":

            if ($resultSet && $resultSet->num_rows > 0) {
                $resultSet->free();
            }

            do {
                if ($res = $this->conn->store_result()) {
                    $res->fetch_all(MYSQLI_ASSOC);
                    $res->free();
                }
            } while ($this->conn->more_results() && $this->conn->next_result());
            break;
            case "sql":
            @sqlsrv_free_stmt($resultSet);
            break;
        }
    }

    public function close() {
        $this->inited = false;

        switch ($this->motor) {
            case "maria":
            mysqli_close($this->conn);
            break;
            case "sql":
            sqlsrv_close($this->conn);
            break;
        }
    }


    /**
     * Permite describir la estructura de base de datos
     * @param type $table 
     * @return type
     */
    public function describe($table,$schem=''){
        $schema=(!empty($schem))?$schem.'.':'';
        $query = false;
        $q = new Query();
        switch ($this->motor) {
            case "maria":
            $query = "DESCRIBE personal";
            break;
            case "sql":
             $q->select("c.COLUMN_NAME as Field,c.DATA_TYPE + '(' + convert(varchar(10), isnull(c.CHARACTER_MAXIMUM_LENGTH, -1)) + ')' as 'Type',c.IS_NULLABLE as 'Null',isnull(LEFT(k.constraint_type, 3), '') as 'Key',c.COLUMN_DEFAULT as 'Default', case co.is_identity when 1 then 'auto_increment' else '' end as Extra");
            $q->from($schema."INFORMATION_SCHEMA.COLUMNS as c");
            $q->join("left", $schema."INFORMATION_SCHEMA.CONSTRAINT_COLUMN_USAGE  as kc")->on("kc.COLUMN_NAME = c.COLUMN_NAME and kc.TABLE_NAME = c.TABLE_NAME");
            $q->join("left", $schema."INFORMATION_SCHEMA.TABLE_CONSTRAINTS as k")->on("k.CONSTRAINT_NAME = kc.CONSTRAINT_NAME and k.TABLE_NAME = kc.TABLE_NAME");
            $q->join("left", "sys.objects as o")->on("o.name = c.TABLE_NAME");
            $q->join("left", "sys.columns as co")->on("o.object_id = co.object_id and co.name = c.COLUMN_NAME");
            $q->where("c.TABLE_NAME = '" . $table . "'");
            
            $query = $q->query();
            break;
        }
        
         $query ? $this->get($query) : $query;
         return $query;
    }
}

?>
