<?php
$dir = dirname(__FILE__) . '\\';
include_once $dir . 'config.php';
include_once $dir . "db.php";
include_once $dir . "query.php";
include_once $dir . "comun.php";
include_once $dir . "precinto.php";
include_once $dir . "evento.php";
include_once $dir . "estado.php";
include_once $dir . "lectura.php";

date_default_timezone_set(TIMEZONE);
$db = new DB(host, user, pass, db);
?>