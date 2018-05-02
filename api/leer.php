<?php 
include_once('./php/inc.php');
$fecha = date('Y-m-d H:i:s');

$key = $_GET['key'] ?? false;

if($key){
	$l = new lectura();
	
	$l->fijarValor('fecha', $fecha);
	$l->fijarValor('apikey', $key);
	$l->fijarValor('modo', '0');

	$ultimaLectura = $l->ultimaFechaLecturaPorKey();
	
	if($ultimaLectura){
		
		$ultimaLectura = $ultimaLectura->format('Y-m-d H:i:s');

	}else{
		$ultimaLectura = '1900-01-01 00:00:00';
	}
	
	$e = new evento();
	$e->fijarValor('fecha', $fecha);
	$e->fijarValor('apikey', $key);
	$e->fijarValor('modo', '0');
	$e->leerIdConKey();

	if($e->leerValor('id')){
		$datos = $e->leerEvento($ultimaLectura);
		echo "JPH: " . $datos;
	}else{
		echo "Key Inválida";
	}
	$l->fijarValor('datos', $datos);
	$l->guardar();

}else{
	echo "Datos Inválidos";
}


?>