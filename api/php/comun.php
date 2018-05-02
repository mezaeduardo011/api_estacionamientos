<?php

    class comun {

        public $db;
        public $id;
        public $campoid;
        public $tabla;
        public $campos;
        public $json;
        public $todos;
        protected $where;
        protected $usuario;

        public function __construct($id = false) {
            global $db;
            $this->db = $db;
            $this->id = true;

            if (isset($_SESSION["usuario"])) {
                $this->usuario = $_SESSION["usuario"];
            }

            if ($this->tabla && $this->campoid && $this->campos) {
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
            }
        }

        public function guardar() {

            if (!$this->existe()) {
                $fields = '';
                $values = '';

                foreach ($this->campos as $key) {
                    $fields .= $key . ',';

                    if (isset($this->$key)) {
                        if ($this->$key === 'NULL') {
                            $values .= 'NULL,';
                        } else {
                            $values .= '\'' . $this->db->escape($this->$key) . '\',';
                        }
                    }
                }

                $fields = substr($fields, 0, -1);
                $values = substr($values, 0, -1);

                $query = "INSERT INTO " . $this->tabla . " (" . $fields . ") VALUES(" . $values . ")";
                $this->db->exec($query);

                if($this->campoid){
                    if (!is_array($this->campoid)) {
                        $campoid = $this->campoid;

                        if (!isset($this->$campoid)) {
                            $this->$campoid = $this->db->lastId() ?? 0;
                        } else {
                            $this->id = $this->$campoid;
                        }
                    } else {
                        $cmpId = $this->campoid[0];
                        $this->id = $this->$cmpId;
                    }                    
                }else{
                    $this->id = $this->campoid;
                }
            } else {

                if (is_array($this->campos)) {
                    $set = [];
                    foreach ($this->campos as $campo) {
                        if (isset($this->$campo)) {
                            if (gettype($this->$campo) == "object" && get_class($this->$campo) == "DateTime") {
                                $set[] = $campo . " = '" . ($this->$campo->format("Ymd") ?? '') . "'";
                            }else{
                                $set[] = $campo . " = '" . ($this->$campo ?? '') . "'";    
                            }
                        }
                    }
                    $set = implode(", ", $set);
                } else {
                    $campo = $this->campo;
                    $set = $campo . " = '" . $this->$campo . "'";
                }

                $query = "UPDATE " . $this->tabla;
                $query .= " SET " . $set;
                $query .= " WHERE " . $this->where;
                $this->db->exec($query);
            }
        }

        public function borrar() {
            $this->where();

            if ($this->existe()) {
                $query = "DELETE FROM " . $this->tabla;
                $query .= " WHERE " . $this->where;
                $this->db->exec($query);
            }
        }

        public function obtenerJSON() {
            $this->where();
            if ($this->id) {
                $query = "SELECT " . implode(',', $this->campos) . " FROM " . $this->tabla;
                $query .= " WHERE " . $this->where;

                $this->db->get($query);
                
                if ($this->db->numRows() > 0) {
                    $row = $this->db->fetch();

                    foreach ($row as $col => $val) {
                        if (gettype($val) == "object" && get_class($val) == "DateTime") {
                            $row->$col = $val->format("d/m/Y");
                        }
                    }

                    $json = json_encode($row);
                    $this->db->free();
                    return $json;
                }
            }
        }

        public function leerValor($campo) {
            if ($this->id != '0') {
                return $this->$campo;
            } else {
                return false;
            }
        }

        public function fijarValor($campo, $valor) {
            if (is_string($valor)) {
                preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valor, $fechas);
                if (count($fechas) > 0) {
                    $this->$campo = $this->dateToAnsi($valor);
                } else {
                    $this->$campo = $valor;
                }
            } else {
                $this->$campo = $valor;
            }
        }

        public function leer() {
            $this->where();

            if ($this->id) {
                $query = "SELECT " . (is_array($this->campos) ? implode(',', $this->campos) : $this->campos) . ",";
                $query .= is_array($this->campoid) ? implode(',', $this->campoid) : $this->campoid;
                $query .= " FROM " . $this->tabla;
                $query .= " WHERE " . $this->where;

                $this->db->get($query);
                if ($this->db->numRows() > 0) {
                    $row = $this->db->fetch();
                    foreach ($row as $key => $value) {
                        $this->$key = $value;
                    }
                    $this->db->free();
                } else {
                    $this->id = false;
                }
            }
        }

        public function leerTodos() {
            $this->todos = array();

            $campos = is_array($this->campos) ? implode(',', $this->campos) : array ($this->campos);
            if($this->campoid){
                if(is_array($this->campoid)){
                    array_merge($campos, $this->campoid);
                }else{
                    array_push($campos, $this->campoid);
                }
            }

            $q = new query();
            $q->select(implode(',', $campos));
            $q->from($this->tabla);

            $this->db->get($q->query());

            while ($row = $this->db->fetch()) {
                foreach ($row as $col => $val) {
                    if (gettype($val) == "object" && get_class($val) == "DateTime") {
                        $row->$col = $val->format("d/m/Y");
                    }
                }
                $this->todos[] = $row;
            }

            $this->db->free();

            return $this->todos;
        }

        protected function dateToAnsi($date) {
            $separador = substr($date, 2, 1);
            $date = explode(" ", $date);
            $date = explode($separador, is_array($date) ? $date[0] : $date);

            $dia = str_pad($date[0], 2, "0", STR_PAD_LEFT);
            $mes = str_pad($date[1], 2, "0", STR_PAD_LEFT);
            $ano = $date[2];
            return $ano . $mes . $dia;
        }

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

        public function existe() {
            if($this->campoid){
                $this->where();
                $query = "SELECT * FROM " . $this->tabla;
                $query .= " WHERE " . $this->where;    

                $this->db->get($query);

                $existe = ($this->db->numRows() > 0);
                $this->db->free();                
            }else{
                $existe = false;
            }

            return $existe;
        }

        protected function where() {
            if($this->campoid){
                if (is_array($this->campoid)) {
                    $where = [];
                    foreach ($this->campoid as $campoid) {
                        if (gettype($this->$campoid) == "object" && get_class($this->$campoid) == "DateTime") {
                            $where[] = $campoid . " = '" . ($this->$campoid->format("Ymd") ?? '') . "'";
                        }else{
                            $where[] = $campoid . " = '" . ($this->$campoid ?? '') . "'";    
                        }
                    }
                    $where = implode(" AND ", $where);
                } else {
                    $campoid = $this->campoid;
                    $where = $campoid . " = '" . (trim($this->$campoid) ?? '') . "'";
                }
            }else{
                $this->where = false;
            }

            $this->where = $where;
        }

        public function grabarLegajosSeleccionados($idFiltro, $legajos) {
            $query = "select coalesce(max(idFiltroSel),0) + 1 as idFiltroSel from seleccionfiltro";
            $this->db->get($query);
            $row = $this->db->fetch();

            $idFiltroSel = $row->idFiltroSel;

            $query = "";

            for ($i = 0; $i < count($legajos); $i++) {
                $query .= "INSERT INTO seleccionfiltro(idFiltro, idFiltroSel, legajo) values ('" . $idFiltro . "','" . $idFiltroSel . "','" . $legajos[$i] . "'); \n";
            }
            $this->db->exec_multi($query);

            $this->db->free();

            return $idFiltroSel;
        }

        public function esFecha($dato) {
            preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $dato, $es);
            return count($es) > 0;
        }

        public function leerId() {
            $campoid = $this->campoid;
            return $this->$campoid;
        }

        public function esFechaHora($dato) {
            preg_match('/^\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2}(:\d{2})?$/', $dato, $es);
            return count($es) > 0;
        }

        public function esHora($dato) {
            preg_match('/^\d{2}:\d{2}(:\d{2})?$/', $dato, $es);
            return count($es) > 0;
        }

        public function dump(){
            $dump = '';
            foreach ($this->campos as $key) {
                if (isset($this->$key)) {
                    $dump .= $key . '=';
                    
                    if ($this->$key === 'NULL') {
                        $dump .= 'NULL';
                    } else {
                        $dump .= $this->$key;
                    }
                    
                    $dump .= ";";
                }
            }

            if($this->campoid){
                if (is_array($this->campoid)) {
                    foreach ($this->campoid as $campoid) {
                        if(isset($this->$campoid)){
                            $dump .= $campoid . "=";
                            
                            if (gettype($this->$campoid) == "object" && get_class($this->$campoid) == "DateTime") {
                                $dump .= $this->$campoid->format("Ymd");
                            }else{
                                $dump .= $this->$campoid;
                            }

                            $dump .= ";";                            
                        }
                    }
                } else {
                    $campoid = $this->campoid;
                    if(isset($this->$campoid)){
                        $dump .= $campoid . "=" . trim($this->$campoid) . ";";
                    }
                }
            }

            return $dump;         
        }

		
		
        public function json(){
            $json = '';

            foreach ($this->campos as $key) {

                if (isset($this->$key)) {
                    if ($this->$key === 'NULL') {
                        $json[$key] = 'NULL';
                    } else {
                        $json[$key] = trim($this->$key);
                    }
                }
            }

            if($this->campoid){
                if (is_array($this->campoid)) {
                    foreach ($this->campoid as $campoid) {
                        if(isset($this->$campoid)){
                            if (gettype($this->$campoid) == "object" && get_class($this->$campoid) == "DateTime") {
                                $json[$campoid] = $this->$campoid->format("Ymd");
                            }else{
                                $json[$campoid] = trim($this->$campoid);
                            }
                        }
                    }
                } else {
                    $campoid = $this->campoid;
                    if(isset($this->$campoid)){
                        $json[$campoid] = trim($this->$campoid);
                    }
                }
            }

            return json_encode($json);
        }

    }

?>