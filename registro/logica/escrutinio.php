<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//guardar
	case 1:
		include("../clases/Escrutinio.php");
		$clase = new Escrutinio();
		$resultado = $clase->guardar($obj);
		echo $resultado;
	break;
	//consultar
	case 2:
		include("../clases/Escrutinio.php");
		$clase = new Escrutinio();
		$lista = json_encode($clase->consultar($obj));
		echo $lista;
	break;
	//eliminar
	case 3:
		include("../clases/Escrutinio.php");
		$clase = new Escrutinio();
		$resultado = $clase->eliminar($obj);
		echo $resultado;
	break;
}


?>