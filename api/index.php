<?php 
include_once('./php/inc.php');
$fecha = date('Y-m-d H:i:s');
die();


for ($i=101; $i <= 700 ; $i++) { 
	echo 'ST#00'.$i;
	echo "<br>";
	$p->fijarValor('numero_serie', 'ST#00'.$i);
	$p->guardar();
}

?>