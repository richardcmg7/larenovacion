<?php

class Usuario {
	public $codigoUsuario;
	public $nombreUsuario;
	public $codigoPerfilUsuario;
	public $nombrePerfilUsuario;
	public $nombrePersona;

    public function __fromString($arr) {
    	$this->codigoUsuario = $arr[0]; 
    	$this->nombreUsuario = $arr[1];
    	$this->codigoPerfilUsuario = $arr[2];
    	$this->nombrePerfilUsuario = $arr[3];
    	$this->nombrePersona = $arr[4];
    }

	public function setCodigoUsuario($codigoUsuario){
		$this->codigoUsuario = $codigoUsuario;
	}
	public function getCodigoUsuario() {
		return $this->codigoUsuario;
	}

	public function setNombreUsuario($nombreUsuario){
		$this->nombreUsuario = $nombreUsuario;
	}
	public function getNombreUsuario() {
		return $this->nombreUsuario;
	}

	public function setCodigoPerfilUsuario($codigoPerfilUsuario){
		$this->codigoPerfilUsuario = $codigoPerfilUsuario;
	}
	public function getCodigoPerfilUsuario() {
		return $this->codigoPerfilUsuario;
	}

	public function setNombrePerfilUsuario($nombrePerfilUsuario){
		$this->nombrePerfilUsuario = $nombrePerfilUsuario;
	}
	public function getNombrePerfilUsuario() {
		return $this->nombrePerfilUsuario;
	}

	public function setNombrePersona($nombrePersona){
		$this->nombrePersona = $nombrePersona;
	}
	public function getNombrePersona() {
		return $this->nombrePersona;
	}

}

?>