<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//guardar
	case 1:
		include("../clases/CodigoArea.php");
		$clase = new CodigoArea();
		$resultado = $clase->guardar($obj);
		echo $resultado;
	break;
	//consultar
	case 2:
		include("../clases/CodigoArea.php");
		$clase = new CodigoArea();
		$lista = json_encode($clase->consultar($obj));
		echo $lista;
	break;
	//editar
	case 3:
		include("../clases/CodigoArea.php");
		$clase = new CodigoArea();
		$resultado = $clase->editar($obj);
		echo $resultado;
	break;
	//eliminar
	case 4:
		include("../clases/CodigoArea.php");
		$clase = new CodigoArea();
		$resultado = $clase->eliminar($obj);
		echo $resultado;
	break;
	//consultar publico
	case 5:
		include("../clases/CodigoArea.php");
		$clase = new CodigoArea();
		$lista = json_encode($clase->consultarPublico($obj));
		echo $lista;
	break;
}


?>