<?php

class precinto extends comun{

	public function __construct($id = false) {
	    $this->tabla = 'precinto';
	    $this->campos = array('numero_serie','telefono','apikey','lectura','escritura','chip','estado');
	    $this->campoid = 'id';
	    parent::__construct($id);
	}

	public function guardar() {
		$this->apikey = md5($this->numero_serie);
		$this->lectura = substr($this->apikey, 0, 16);
		$this->escritura = substr($this->apikey, -16);
	    parent::guardar();
	}

	public function leerPorKey($key, $modo){
		$q = new query();
		$q->select($this->campoid)->from($this->tabla)->where(($modo == 0 ? 'lectura' : 'escritura') . " = '" . $key . "'");

		$this->db->get($q->query());

		if($this->db->numRows() > 0) {
			$campoid = $this->campoid;
		  	$row = $this->db->fetch();
		  	$id = $row->$campoid;
		} else {
		    $id = false;
		}

		return $id;
	}

}
?>