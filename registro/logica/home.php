<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//consultar
	case 1:
		include("../clases/Home.php");
		$clase = new Home();
		$resultado = $clase->consultar($obj);
		echo $resultado;
	break;
}


?>