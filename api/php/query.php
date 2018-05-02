<?php

    class query {

        private $action;
        private $type;
        private $fields;
        private $table;
        private $where;
        private $limit;
        private $join;
        private $set;
        private $select;
        private $subquery;

        public function __construct($type = motorBD) {
            $this->type = $type;
            $this->action = false;
        }

        public function select($fields = false) {
            $this->action = 'SELECT';
            $this->fields = $fields;
            return $this;
        }

        public function update($table = false) {
            $this->action = 'UPDATE';
            $this->table = $table;
            return $this;
        }

        public function delete($table = false) {
            $this->action = 'DELETE';
            $this->table = $table;
            return $this;
        }

        public function insert($table = false, $fields = false) {
            $this->action = 'INSERT';
            $this->table = $table;
            $this->$fields = $fields;
            return $this;
        }

        public function from($table = false) {
            $this->table = $table;
            return $this;
        }

        public function where($where = false) {
            $this->where = $where;
            return $this;
        }

        public function limit($limit = false) {
            $this->limit = $limit;
            return $this;
        }

        public function top($limit = false) {
            $this->limit = $limit;
            return $this;
        }

        public function join($type = 'LEFT', $join = false) {
            $this->join[] = array('type' => $type, 'join' => $join);
            return $this;
        }

        public function on($on = false) {
            if (isset($this->join)) {
                $i = count($this->join) - 1;
                $this->join[$i]['on'] = $on;
            }
            return $this;
        }

        public function set($set = false) {
            $this->set = $set;
            return $this;
        }

        public function groupby($groupby = false) {
            $this->groupby = $groupby;
            return $this;
        }

        public function having($having = false) {
            $this->having = $having;
            return $this;
        }

        public function orderby($orderby = false) {
            $this->orderby = $orderby;
            return $this;
        }

        public function values($values = false) {
            $this->values = $values;
            return $this;
        }

        public function subquery($subquery = false) {
            $this->subquery = $subquery;
            return $this;
        }

        public function exec($exec = false) {
            $this->action = "%EXEC " . $exec;
            return $this;
        }

        public function query() {
            $query = $this->action . " ";

            if (in_array($this->action, array('SELECT', 'UPDATE', 'DELETE'))) {
                switch ($this->action) {
                    case 'SELECT':
                        if ($this->type == 'sql') {
                            if (isset($this->limit)) {
                                if (strpos($this->limit, ",") !== false) {
                                    $this->limit = explode(",", $this->limit);
                                    $this->limit = $this->limit[1];
                                }
                                $query .= "TOP (" . $this->limit . ") ";
                            }
                        }
                        if (isset($this->fields)) {
                            $query .= $this->fields . " ";
                        }
                        break;
                    case 'UPDATE':
                        if (isset($this->set)) {
                            $query .= "SET " . $this->set . " ";
                        }
                        break;
                    case 'DELETE':

                        break;
                }

                if (isset($this->table)) {
                    $query .= "FROM " . $this->table . " ";
                }

                if (isset($this->join)) {
                    foreach ($this->join as $j) {
                        $query .= $j['type'] . " JOIN " . $j['join'] . " ON " . $j['on'] . " ";
                    }
                }

                if (isset($this->where)) {
                    $query .= "WHERE " . $this->where . " ";
                }

                if (isset($this->groupby)) {
                    $query .= "GROUP BY " . $this->groupby . " ";
                    if (isset($this->having)) {
                        $query .= "HAVING " . $this->having . " ";
                    }
                }

                if (isset($this->orderby)) {
                    $query .= "ORDER BY " . $this->orderby . " ";
                }

                if ($this->type == 'maria') {
                    if (isset($this->limit)) {
                        $query .= "LIMIT " . $this->limit . " ";
                    }
                }
            } else {
                if ($this->action == 'INSERT') {
                    $query .= ' INTO ' . $this->table . '(' . $this->fields . ')';
                    if (isset($this->values)) {
                        $query .= ' VALUES (' . $this->values . ')';
                    } else {
                        $query .= $this->subquery;
                    }
                }
            }

            $query = $this->reemplazarClaves($query);

            return $query;
        }

        private function reemplazarClaves($query) {
            switch ($this->type) {
                case 'sql':
                    $query = str_replace("%IFNULL", "ISNULL", $query);
                    $query = str_replace("%CURRENT_DATE", "GETDATE()", $query);
                    $query = str_replace("%CURRENT_TIME", "GETDATE()", $query);
                    $query = str_replace("%DAYOFWEEK(", "DATEPART(dw,", $query);
                    $query = str_replace("%EXEC", "EXEC ", $query);
                    break;
                case 'maria':
                    $query = str_replace("%IFNULL", "IFNULL", $query);
                    $query = str_replace("%CURRENT_DATE", "CURRENT_DATE", $query);
                    $query = str_replace("%CURRENT_TIME", "CURRENT_TIME", $query);
                    $query = str_replace("%DAYOFWEEK(", "DAYOFWEEK(", $query);
                    $query = str_replace("%EXEC", "CALL ", $query);
                    break;
            }

            return $query;
        }

    }

?>