<?php

class estado extends comun{
    
    public function __construct($id = false) {
	    $this->tabla = 'estados';
	    $this->campos = array('id','fecha','entrada_1','entrada_2','entrada_3','entrada_4','salida_1','salida_2');
	    $this->campoid = false;
	    parent::__construct($id);
	}

	public function guardar(){
		$this->entrada_1 = substr($this->datos, 0, 1) == 'H' ? '1' : '0';
		$this->entrada_2 = substr($this->datos, 1, 1) == 'H' ? '1' : '0';
		$this->entrada_3 = substr($this->datos, 2, 1) == 'H' ? '1' : '0';
		$this->entrada_4 = 0; //substr($this->datos, 3, 1) == 'H' ? '1' : '0';
		$this->salida_1 = 0; //substr($this->datos, 4, 1) == 'H' ? '1' : '0';
		$this->salida_2 = 0; //substr($this->datos, 5, 1) == 'H' ? '1' : '0';*/

		$tiempo = substr($this->datos, 3, 4);
		$ano = '20' .  substr($this->datos, 7, 2);
		$mes = substr($this->datos, 9, 2);
		$dia = substr($this->datos, 11, 2);
		$hor = substr($this->datos, 13, 2);
		$min = substr($this->datos, 15, 2);
		$seg = substr($this->datos, 17, 2);
		$hus = substr($this->datos, 19, 2);

		$ts = gmmktime($hor,$min,$seg,$mes,$dia,$ano);
		$dif = (60 * 60) * ($hus / 100); // Seconds from GMT
		$ts = $ts - $dif - $tiempo;

		$this->fecha = date('Y-m-d H:i:s', $ts);

		parent::guardar();
	}


	public function leerIdConKey(){
		$p = new precinto();
		$this->id = $p->leerPorKey($this->apikey, $this->modo);
	}
	
	public function debugmsg($msg)
	{
		$this->db->exec("INSERT INTO app_msg (msg) VALUES ('".$msg."')");
	}
}
?>
