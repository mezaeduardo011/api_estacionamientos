<?php

class lectura extends comun{

	public function __construct($id = false) {
	    $this->tabla = 'lectura';
	    $this->campos = array('fecha','apikey','modo','datos');
	    $this->campoid = false;
	    parent::__construct($id);
	}

	public function ultimaFechaLecturaPorKey(){
		$q = new query();
		$q->select("fecha")->top("1")->from("lectura")->where("apikey = '" . $this->apikey . "'")->orderby("fecha DESC");
		$this->db->get($q->query());
		
		if($this->db->numRows() > 0) {
		  	$row = $this->db->fetch();
		  	$fecha = $row->fecha;
		} else {
		    $fecha = false;
		}

		return $fecha;
	}
	
		public function debugmsg($msg)
	{
		$this->db->exec("INSERT INTO app_msg (msg) VALUES ('".$msg."')");
	}
	
	public function getLecturas(){
		die("llega");
		$q = new query();
		
		$this->db->get("SELECT l.*,p.numero_serie from lectura  as l
						INNER JOIN precinto AS p ON p.lectura = l.apikey
						OR p.lectura = l.apikey
						ORDER BY l.fecha DESC");
		
		if($this->db->numRows() > 0) {
		  	$row = $this->db->fetch();
		  	$datos = $row;
		} else {
		    $datos = '';
		}

		return $fecha;
	}	


	public function leerIdConKey(){
		$p = new precinto();
		$this->id = $p->leerPorKey($this->apikey, $this->modo);
	}
	
    public function updateStatus()
	{
		$this->leerIdConKey();
		$this->db->get("UPDATE eventos SET actualizado = 0 WHERE id = '".$this->id."'");
	}	
}
?>