<?php 
include_once('./php/inc.php');
$fecha = date('Y-m-d H:i:s');
$info = $_GET['info'] ?? false;
$key = $_GET['key'] ?? false;
$datos = $_GET['datos'] ?? false;
$e = new estado();

if($key && $datos && strlen($datos) == 21){
	
		$e->fijarValor('fecha', $fecha);
		$e->fijarValor('apikey', $key);
		$e->fijarValor('datos', $datos);
		$e->fijarValor('modo', 1);
		$e->leerIdConKey();
		
		if($e->leerValor('id')){
			$e->guardar();
			echo "ok";
		}else{
			echo "Key Inválida";
		}
		
		$l = new lectura();
		$l->fijarValor('fecha', $fecha);
		$l->fijarValor('apikey', $key);
		$l->fijarValor('modo', '1');
		$l->fijarValor('datos', $datos);

		$l->guardar();
		
}else{
			echo "Datos Inválidos";
			$l = new lectura();
			$l->fijarValor('fecha', $fecha);
			$l->fijarValor('apikey', $key);
			$l->fijarValor('modo', '-1');
			$l->fijarValor('datos', $datos);
			$l->guardar();			
			if($info=='OK')
			{
				$l->updateStatus();
			}
}



?>