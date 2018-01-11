<?php
namespace Catatumbo\Complements\Database;
use Catatumbo\Complements\Database\Query AS Query;
use Catatumbo\Core\Commun\{
    All, Logs
};
/**
 * Representa un modelo generico algo similar a orm, para setiar valores campos y tablas
 */
class Comun{


    public $database;
    public $db;
    public $query;
    public $campoid;
    public $tabla;
    public $campos;
    public $json;
    public $todos;
    public $where;
    public $usuario;
    public $active;
    use Logs;


    public function __construct($id = false)
    {


        $this->database = $this->db;
        $this->db = get_class($this);
        $this->id = true;
        $this->active = 'Model';
        $this->query = new Query();
        /*echo "<pre>";
        print_r($this); die();*/

       /* if (isset($_SESSION["usuario"])) {
            $this->usuario = $_SESSION["usuario"];
        }*/

        if ($this->tabla && $this->campoid && $this->campos) {
            if(!empty($this->tabla)){ $this->validarExisteTable();}
            if (is_array($this->campoid)) {
                foreach ($this->campoid as $campoid) {
                    $this->$campoid = $id[$campoid] ?? false;

                    if (!$this->$campoid) {
                        $this->id = false;
                    }
                }
            } else {
                $campoid = $this->campoid;
                $this->$campoid = $id ?? false;

                if (!$this->$campoid) {
                    $this->id = false;
                }
            }

            $this->leer();
            //return $this;
        }
    }

    /**
     * Agregar o Actualizar dependendo de los valores
     * @return devuelve el Id
     */
    public function guardar() {
        if ($this->validarTiposDeDatos()) {

            if (!$this->existe()) {
                $fields = '';
                $values = '';

                foreach ($this->campos as $key) {
                    if (!$this->esId($key)) {
                        $fields .= $key . ',';

                        if (isset($this->$key)) {
                            if ($this->$key === 'NULL') {
                                $values .= 'NULL,';
                            } else {
                                $values .= '\'' . $this->escape($this->$key) . '\',';
                            }
                        } else {
                            $values .= 'NULL,';
                        }
                    }
                }

                $fields = substr($fields, 0, -1);
                $values = substr($values, 0, -1);

               $query = "INSERT INTO " . $this->tabla . " (" . $fields . ") VALUES(" . $values . ")";

                $this->execute($query);

                if (!is_array($this->campoid)) {
                    $campoid = $this->campoid;

                    if (!isset($this->$campoid)) {
                        $this->$campoid = $this->lastId() ?? 0;
                    } else {
                        $this->id = $this->$campoid;
                    }
                } else {
                    $cmpId = $this->campoid[0];
                    $this->id = $this->$cmpId;
                }
            } else {

                if (is_array($this->campos)) {
                    $set = [];
                    foreach ($this->campos as $campo) {
                        if (isset($this->$campo) && !$this->esId($campo)) {
                            if (gettype($this->$campo) == "object" && get_class($this->$campo) == "DateTime") {
                                $set[] = $campo . " = '" . ($this->$campo->format("Ymd") ?? '') . "'";
                            } else {
                                if ($this->$campo == 'NULL') {
                                    $set[] = $campo . " = " . $this->$campo;
                                } else {
                                    $set[] = $campo . " = '" . ($this->$campo ?? '') . "'";
                                }
                            }
                        }
                    }
                    $set = implode(", ", $set);
                } else {
                    $campo = $this->campos;
                    $set = $campo . " = '" . $this->$campo . "'";
                }

                $query = "UPDATE " . $this->tabla;
                $query .= " SET " . $set;
                $query .= " WHERE " . $this->where;
                $this->execute($query);
                $this->free();
            }
        } else {
            return -1;
        }

    }

    /**
     * Borrar elementos de la tablas
     * @return type
     */
    public function borrar() {
        $this->where();
        if ($this->existe()) {
            $query = "DELETE FROM " . $this->tabla;
            $query .= " WHERE " . $this->where;
            $this->execute($query);
        }
    }

    /**
     * Procesar los valores y devolver en formato Json
     * @return type
     */
    public function obtenerJSON() {
        $this->where();

        $query = "SELECT " . (is_array($this->campos) ? implode(',', $this->campos) : $this->campos) . ",";
        $query .= is_array($this->campoid) ? implode(',', $this->campoid) : $this->campoid;
        $query .= " FROM " . $this->tabla;
        $query .= " WHERE " . $this->where;
        $this->get($query);

        if ($this->numRows() > 0) {
            $row = $this->fetch();

            foreach ($row as $col => $val) {
                if (gettype($val) == "object" && get_class($val) == "DateTime") {
                    $row->$col = $val->format("d/m/Y");
                }
            }

            $json = json_encode($row);
            $this->free();
            return $json;
        }
    }

    /**
     * Permite executar un sql puro desde el elemento
     * @param type $query 
     * @return type
     */
    public function executeQuery($query)
    {
      $this->todos = array();
      $this->get($query);
      $datos = [];           
      while ($row = $this->fetch()) {
          foreach ($row as $col => $val) {
              if (gettype($val) == "object" && get_class($val) == "DateTime") {
                  $row->$col = $val->format("d/m/Y");
              }
          }
          $this->todos[] = $row;

      }
     $this->free();
     return  $this->todos;
    }

    public function leerValor($campo) 
    {
        if (isset($this->$campo)) {
            return $this->$campo;
        } else {
            return false;
        }
    }

    public function fijarValores($data = false)
    {
        //print_r($data); die();
        if(!$data) $data = $_POST;
        foreach ($data as $campo => $valor) {
            $this->fijarValor($campo,trim($valor));
        }
    }

    /*  */
    public function fijarValor($campo, $valor)
    {
        if (is_string($valor)) {
            preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valor, $fechas);
            if (count($fechas) > 0) {
                $this->$campo = $this->dateToAnsi($valor);
            } else {
                $this->$campo = trim($valor);
            }
        } else {
            $this->$campo = trim($valor);
        }
    }

    public function leer() {
        $this->where();
        $query = "SELECT " . (is_array($this->campos) ? implode(',', $this->campos) : $this->campos) . ",";
        $query .= is_array($this->campoid) ? implode(',', $this->campoid) : $this->campoid;
        $query .= " FROM " . $this->tabla;
        $query .= " WHERE " . $this->where;

        $this->get($query);
        if ($this->numRows() > 0) {
            $row = $this->fetch();
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
            $this->free();
        } else {
            $this->id = false;
        }
    }

    public function leerTodos($datos=NULL) {
        $this->todos = array();
        $q = new Query();
        $q->select((is_array($this->campos) ? implode(',', $this->campos) : $this->campos) . "," . (is_array($this->campoid) ? implode(',', $this->campoid) : $this->campoid));
        $q->from($this->tabla);
        // Elemento necesario para filtrar los valores
        if(isset($datos->campo)){
            $valores = explode('--',$datos->campo);
            $q->where("$valores[0]=$valores[1]");
        }

        $this->get($q->query());

        while ($row = $this->fetch()) {
            foreach ($row as $col => $val) {
                if (gettype($val) == "object" && get_class($val) == "DateTime") {
                    $row->$col = $val->format("d/m/Y");
                }
            }
            $this->todos[] = $row;
        }

        $this->free();

        return $this->todos;
    }

    /* PAsar un objeto date a ansi*/
    public function dateToAnsi($date) {
        $separador = substr($date, 2, 1);
        $date = explode(" ", $date);
        $date = explode($separador, is_array($date) ? $date[0] : $date);

        $dia = str_pad($date[0], 2, "0", STR_PAD_LEFT);
        $mes = str_pad($date[1], 2, "0", STR_PAD_LEFT);
        $ano = $date[2];
        return $ano . $mes . $dia;
    }

    /* dia mes anio pasar un string a anio */
    public function stringToDate($s) {
        $separador = substr($s, 2, 1);
        $s = explode(" ", $s);
        $s = explode($separador, is_array($s) ? $s[0] : $s);
        return mktime(0, 0, 0, $s[1], $s[0], $s[2]);
    }

    public function ansiToDate($s) {
        $a = substr($s, 0, 4);
        $m = substr($s, 4, 2);
        $d = substr($s, 6, 2);
        return mktime(0, 0, 0, $m, $d, $a);
    }

    protected function TimeToMinute($horas) {
        $horas = explode(":", $horas);
        $hora = $horas[0];
        $min = $horas[1];

        return $hora * 60 + $min;
    }

    protected function MinutesToTime($mins) {
        $horas = floor($mins / 60);
        $minutos = str_pad($mins - $horas * 60, 2, "0", STR_PAD_LEFT);
        $horas = str_pad($horas, 2, "0", STR_PAD_LEFT);

        return $horas . ':' . $minutos;
    }

    /**
     * Permite verificar si existe una entidad 
     * @return boolean
     */
    protected function existeTable() {
        $query = "SELECT (CASE COUNT(TABLE_NAME) WHEN 1 THEN 'SI' ELSE 'NO' END) AS existe
 FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".$this->tabla."'";
        $a=$this->get($query);
       
        $existe = $this->fetch();
        $this->free();
        return $existe->existe;
    }

    private function validarExisteTable(){
        $a=$this->existeTable();
        if($a=='NO'){
            $obj = array('modelo'=>get_class($this),'app'=>APP,'entidad'=>$this->tabla,'entidad'=>$this->tabla,'database'=>$this->database);

            $msj=All::getMsjException($this->active,'app-model-vacia',$obj);
            $this->logError($msj.' '.__CLASS__.' '.__LINE__);
            die("\n".$msj);
        }
    }

    public function existe() {
        $id = $this->where();
        $query = "SELECT * FROM " . $this->tabla;
        $query .= " WHERE " . $this->where;
        $query;
        $this->get($query);
        $existe = ($this->numRows() > 0);
        $this->free();
        return $existe && $id;
    }

    public function where() {
        $id = true;
        if (is_array($this->campoid)) {
            $where = [];
            foreach ($this->campoid as $campoid) {
                if (gettype($this->$campoid) == "object" && get_class($this->$campoid) == "DateTime") {
                    $where[] = $campoid . " = '" . ($this->$campoid->format("Ymd") ?? '') . "'";
                } else {
                    $where[] = $campoid . " = '" . ($this->$campoid ?? '') . "'";
                }

                if (gettype($this->$campoid) == 'string' && (trim($this->$campoid) ?? '') === '') {
                    $id = false;
                }
            }
            $where = implode(" AND ", $where);
        } else {
            $campoid = (isset($this->campoid))?$this->campoid:'';
            $where = $campoid . " = '" . (trim($campoid) ?? '') . "'";
            if ((trim($campoid) ?? '') === '') {
                $id = false;
            }
        }

        $this->where = $where;
        return $id;
    }

    public function debug($msg)
    {
        $sql = "INSERT INTO debug (datos) VALUES ('".$msg."')";
        $this->get($sql);
    }

    public function grabarLegajosSeleccionados($idFiltro, $legajos) 
    {
        $query = "select isnull(max(idFiltroSel),0) + 1 as idFiltroSel from seleccionfiltro";
        $this->get($query);
        $row = $this->fetch();

        $idFiltroSel = $row->idFiltroSel;

        $query = "";

        for ($i = 0; $i < count($legajos); $i++) {
            $query .= "INSERT INTO seleccionfiltro(idFiltro, idFiltroSel, legajo) values ('" . $idFiltro . "','" . $idFiltroSel . "','" . $legajos[$i] . "'); \n";
        }
        $this->execute_multi($query);

        $this->free();

        return $idFiltroSel;
    }

    public function leerId() 
    {
        $campoid = $this->campoid;
        return $this->$campoid;
    }

    public function esFecha($dato) 
    {
        $es = [];
        preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dato, $es);
        return count($es) > 0;
    }

    public function esFechaHora($dato) 
    {
        $es = [];
        preg_match('/^\d{2}[\/\-]\d{2}[\/\-]\d{4}\s\d{2}:\d{2}(:\d{2})?$/', $dato, $es);

        if (count($es) == 0) {
            preg_match('/^\d{4}[\/\-]\d{2}[\/\-]\d{2}\s\d{2}:\d{2}(:\d{2})?$/', $dato, $es);
            if (count($es) > 0) {
                return 2;
            }
        } else {
            return 1;
        }

        return 0;
    }

    public function esHora($dato) 
    {
        $es = [];
        preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $dato, $es);
        return count($es) > 0;
    }

    private function esFechaValida($dato) 
    {
        if (gettype($dato) == "object") {
            return get_class($dato) == "DateTime";
        } else {
            $separador = substr($dato, 2, 1);
            if ($this->esFecha($dato)) {
                $fecha = explode($separador, $dato);
            } else {
                if (strlen($dato) == 8) {
                    $fecha[0] = substr($dato, -2);
                    $fecha[1] = substr($dato, 4, 2);
                    $fecha[2] = substr($dato, 0, 4);
                } else {
                    $fecha[0] = 0;
                    $fecha[1] = 0;
                    $fecha[2] = 0;
                }
            }

            return checkdate($fecha[1], $fecha[0], $fecha[2]);
        }
    }

    private function esFechaHoraValida($dato) 
    {
        $fecha[0] = 0;
        $fecha[1] = 0;
        $fecha[2] = 0;

        $hora[0] = 0;
        $hora[1] = 0;
        $hora[2] = 0;

        $formato = $this->esFechaHora($dato);

        if ($formato > 0) {
            switch ($formato) {
                case 1:
                $separador = substr($dato, 2, 1);
                break;
                case 2:
                $separador = substr($dato, 4, 1);
                break;
            }

            $fechaHora = explode(" ", $dato);
            $fecha = explode($separador, $fechaHora[0]);
            $hora = $fechaHora[1];

            switch ($formato) {
                case 1:
                $ano = $fecha[2];
                $mes = $fecha[1];
                $dia = $fecha[0];
                break;
                case 2:
                $ano = $fecha[0];
                $mes = $fecha[1];
                $dia = $fecha[2];
                break;
            }

            $es = [];
            preg_match('/^(?:[01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $hora, $es);
            return count($es) > 0 && checkdate($mes, $dia, $ano);
        } else {
            return false;
        }
    }

    private function esId($key) 
    {
        return false;
        if (is_array($this->campoid)) {
            return in_array($key, $this->campoid);
        } else {
            return $key === $this->campoid;
        }
    }

    /**
     * Permite visualizar la descripcion de la entidad con las columnas y definicion de la entidad
     * @param string $entidad, valor de la entidad 
     * @return objeto $elemento, valores de la entidad
     */
    public function showColumns($entidad,$schema=''){
        $tmp = $this->describe($entidad,$schema);
        $elemento=(object)$this->executeQuery($tmp);
        return $elemento;
    }

    /**
     * Permite extraer la estructura de la tabla donde estas conectada que sea diferente a las entidades que necesitamos
     * @return object $elemento
     */
    public function informationschema(){
        $sql = "SELECT * from INFORMATION_SCHEMA.TABLES";// WHERE TABLE_NAME NOT IN('ho_campos','ho_relacion','ho_entidad', 'ho_campos_type')";
        $elemento=(object)$this->executeQuery($sql);
        return $elemento;
    }

    private function validarTiposDeDatos() 
    {
        $valido = true;
        $rs = $this->describe($this->tabla);

        while ($r = $this->fetch()) {
            $campo = $r->Field;
            $tipo = $r->Type;
            $null = $r->Null;
            $key = $r->Key;
            $default = $r->Default;
            $extra = $r->Extra;

            if ($extra != 'auto_increment') {
                preg_match('/\(-?\d*\)/', $tipo, $m);
                $largo = preg_replace("/\(/", "", preg_replace("/\)/", "", $m[0]));
                preg_match('/.*\(/', $tipo, $m);
                $tipo = preg_replace("/\(/", "", preg_replace("/\)/", "", $m[0]));



                if (isset($this->$campo)) {
                    if (!(($this->$campo === 'NULL' || $this->$campo === '') && $null === 'YES')) {
                        switch ($tipo) {
                            case 'char':
                            case 'varchar':
                            if (strlen($this->$campo) > $largo) {
                                $valido = false;
                            }
                            break;
                            case 'int':
                            case 'decimal':
                            if (!is_numeric($this->$campo)) {
                                $valido = false;
                            }
                            break;
                            case 'datetime':
                            if (!$this->esFechaValida($this->$campo) && !$this->esFechaHoraValida($this->$campo)) {
                                $valido = false;
                            }
                            break;
                        }
                    } else {
                        if ($this->$campo === '') {
                            $this->$campo = 'NULL';
                        }
                    }
                }
            }
        }

        return $valido;
    }
    /**
     * Elemto encargado de procesar informacion de base de datos y gestion de ese elemento
     * @param $defineData
     */
    function createEntidades($define)
    {

    }

}

?>