<?php
header("Content-Type:application/json");
header('Content-type: text/html; charset=UTF-8');
header('Content-type: text/html; charset=ISO-8859-1');
class cls_config
{
	//Variables que vienen de usuario
	var $motor_bd; //Motor de BD
	var $codigo_usuario; //Código de usuario de SEIN
	var $licencia; //Licencia de empresa
	var $identidad_empresa; //Identidad de empresa
	var $nombre_empresa; //Nombre de empresa
	var $identidad_licencia; //Identidad de licencia
	var $nombre_licencia; //Nombre de licencia
	var $nombre_funcion; //Nombre de función

	//Variables locales
	var $conexion; //Conexión de BD
	var $jsonData = array();

	var $json;
	var $resultado; //Resultado de consulta
	var $registros; //Cantidad de registros encontrados en consulta
	var $id; //Último id agregado a la tabla

	var $servidor_bd = "localhost";
	var $nombre_bd = "larenogc_registro";
	var $usuario_bd = "larenogc_admin";
	var $clave_bd = 'Drj65432_lrnv';

	//Conexión a BD
	function Conectar()
	{
		error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);

		if (!($this->conexion = mysqli_connect($this->servidor_bd, $this->usuario_bd, $this->clave_bd, $this->nombre_bd))) {
			return false;
		}

		/*if (!($this->conexion=mysqli_connect($this->servidor_bd, $this->usuario_bd, $this->clave_bd,true))){
		    return false;
		}*/

		/*if(!mysqli_select_db($this->nombre_bd, $this->conexion)){  //Si falla la apertura de la bd
	    	return false;
		}*/

		return true;
	}

	//Consultar a BD
	function Consultar($sql)
	{

		error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
		$this->jsonData = null;
		$this->id = 0; //Blanqueamos el último id
		$this->resultado = mysqli_query($this->conexion, $sql);

		if (!$this->resultado) {
			return false;
		}
		$i = 0;
		while ($this->registros = mysqli_fetch_assoc($this->resultado)) {
			$this->jsonData[$i] = array_map('utf8_encode', $this->registros);
			$i++;
		}
		$this->json = $this->jsonData;

		return true;
	}

	//Consultar a BD
	function Sentencia($sql)
	{

		error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
		$this->id = 0; //Blanqueamos el último id
		$this->resultado = mysqli_query($this->conexion, $sql);
		//Si el query no se ejecutó correctamente
		if (!$this->resultado) {
			return false;
		}
		//$this->registros=mysqli_fetch_row($this->resultado);
		//$this->registros=array_map('utf8_encode', $this->registros);

		return $this->resultado;
	}

	//Consultar a BD
	function Query($sql)
	{

		error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
		$this->jsonData = null;
		$this->id = 0; //Blanqueamos el último id
		$this->resultado = mysqli_query($this->conexion, $sql);
		if (!$this->resultado) {
			return false;
		}
		$i = 0;
		while ($this->registros = mysqli_fetch_assoc($this->resultado)) {
			$this->jsonData[] = array_map('utf8_encode', $this->registros);
			$i++;
		}
		$this->json = $this->jsonData;

		return true;
	}

	//Cerrar conexión a BD
	function Desconectar()
	{
		mysqli_close($this->conexion);
	}
}
