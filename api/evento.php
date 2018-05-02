<?php 
include_once('./php/inc.php');
$fecha = date('Y-m-d H:i:s');

$key = $_GET['key'] ?? false;

if($key){
	$l = new lectura();
	$l->fijarValor('fecha', $fecha);
	$l->fijarValor('apikey', $key);
	$l->fijarValor('modo', '0');

	$ultimaLectura = $l->ultimaFechaLecturaPorKey()->format('Y-m-d H:i:s');

	$e = new evento();
	$e->fijarValor('fecha', $fecha);
	$e->fijarValor('apikey', $key);
	$e->fijarValor('modo', '0');
	$e->leerIdConKey();

	if($e->leerValor('id')){
		$e->leerUltimoEvento($ultimaLectura);
		foreach($_GET as $k => $v){
			if($k != 'key'){
				$e->fijarValor($k, $v);
			}
		}

		$e->guardar();
		
		echo $key . ' ' . $e->dump().'<br />';
	}else{
		echo "Key Inválida";
	}

}else{
	echo "Datos Inválidos";
}

?>