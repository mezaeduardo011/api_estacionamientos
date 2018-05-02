<?php

class evento extends comun{
    
    public function __construct($id = false) {
	    $this->tabla = 'eventos';
	    $this->campos = array('id','fecha','salida_1','salida_2','contenedor','modo','tiempo','mem_manager','chasis','gprs','config','ssid_name','ssid_pass','telefono','host','port','actualizado');
	    $this->campoid = false;
	    parent::__construct($id);
	}

	public function leerEvento($fecha){
		$gsm = date('ymdHis') . date('Z') / 60 / 60 ;
		$this->ultimaLectura = $fecha;
		$q = new query();

		$q->select(implode(',',$this->campos))->top(1)->from($this->tabla)->where("id = " . $this->id . " AND fecha = '".date('Y-m-d')."'")->orderby("fecha DESC");
		$this->db->get($q->query());
	
		if($this->db->numRows() > 0) {
		  	$row = $this->db->fetch();

		  	foreach($row as $k => $v){
		  		$this->$k = $v;
		  	}
			
			
			if($row->actualizado==1)
			{
				
				$datos = $gsm.';';				
				$db = new DB();
				$db->get("SELECT * FROM campos");
				$campos = $db->fetch();
			    $campos = explode(" ",$campos->campos);
				if(array_search('gprs', $campos)){$datos .= $row->gprs.';';}else{$datos .= ';';}
				if(array_search('mem_manager', $campos)){$datos .= $row->mem_manager.';';}else{$datos .= ';';}
				if(array_search('chasis', $campos)){$datos .= $row->chasis.';';}else{$datos .= ';';}
				if(array_search('config', $campos)){$datos .= $row->config.';';}else{$datos .= ';';}
				if(array_search('ssid_name', $campos)){$datos .= $row->ssid_name.';';}else{$datos .= ';';}
				if(array_search('ssid_pass', $campos)){$datos .= $row->ssid_pass.';';}else{$datos .= ';';}
				if(array_search('telefono', $campos)){$datos .= $row->telefono.';';}else{$datos .= ';';}
				if(array_search('host', $campos)){$datos .= $row->host.';';}else{$datos .= ';';}
				if(array_search('port', $campos)){$datos .= $row->port.';';}else{$datos .= ';';}
				

			}else{
				$datos = $gsm;
			}
		} else {
			$datos = $gsm;
		}

		return $datos;		
	}	 


	
	public function leerUltimoEvento(){
		$q = new query();
		$q->select("*")->top(1)->from($this->tabla)->where("id = " . $this->id)->orderby("fecha DESC");
		
		$this->db->get($q->query());
		//$datos[];
		if($this->db->numRows() > 0) {
		  	$row = $this->db->fetch();
			//$datos[] = $row;
		}
	}

	public function leerIdConKey(){
		$p = new precinto();
		$this->id = $p->leerPorKey($this->apikey, $this->modo);
	}

    public function updateStatus()
	{
		$this->leerIdConKey();
		$db->get("UPDATE ".$this->tabla." SET actualizado = 0 WHERE id = '".$this->id."'");
	}
}
?>