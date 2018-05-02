<?php

    class DB {

        private $conn;
        private $resultSet;
        private $result;
        private $inited;
        public $error;
        public $errorno;
        public $message;
        public $motor;
        var $host;
        var $user;
        var $pass;
        var $db;
        var $encoding;

        function __construct($host = host, $user = user, $pass = pass, $db = db, $encoding = encoding, $motor = motorBD) {
            $this->host = $host;
            $this->user = $user;
            $this->pass = $pass;
            $this->db = $db;
            $this->encoding = $encoding;
            $this->motor = $motor;

            switch ($this->motor) {
                case "maria":
                    $this->conn = new mysqli($host, $user, $pass, $db);

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
                    $this->conn = sqlsrv_connect($this->host, array("Database" => $this->db, "CharacterSet" => $this->encoding, "UID" => $this->user, "PWD" => $this->pass));

                    if (!$this->conn) {
                        $this->error = true;
                        $this->inited = false;
                        $this->message = sqlsrv_errors();
                    } else {
                        $this->inited = true;
                        $this->error = false;
                    }

                    break;
            }
        }

        function init() {
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

        function get($query) {
            if (!$this->inited) {
                $this->init();
            }

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

                    if ($this->resultSet === false) {
                        $this->error = true;
                        $this->inited = false;
                        $this->message = sqlsrv_errors();
                    } else {
                        $this->error = false;
                        $this->errorno = 0;
                        $this->message = '';
                    }

                    break;
            }

            return $this->resultSet;
        }

        function exec($query) {
            if (!$this->inited) {
                $this->init();
            }

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

                    if (sqlsrv_errors()) {
                        $this->error = true;
                        $this->inited = false;
                        $this->message = sqlsrv_errors();
                    } else {
                        $this->error = false;
                        $this->errorno = 0;
                        $this->message = '';
                    }

                    break;
            }

            //return $this->result;
        }

        function exec_multi($query) {
            if (!$this->inited) {
                $this->init();
            }
            $this->result = $this->conn->multi_query($query);
            return $this->result;
        }

        function fetch($resultSet = false) {
            if (!$resultSet) {
                $resultSet = $this->resultSet;
            }

            switch (motorBD) {
                case "maria":
                    return $resultSet->fetch_object();
                    break;
                case "sql":
                    if ($resultSet) {
                        return sqlsrv_fetch_object($resultSet);
                    }
                    break;
            }
        }

        function numRows($resultSet = false) {
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

        function lastId() {
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

        function escape($query) {
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

        function free($resultSet = false) {
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
                    sqlsrv_free_stmt($resultSet);
                    break;
            }
        }

        function close() {
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

    }

?>
