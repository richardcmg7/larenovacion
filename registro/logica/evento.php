<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//guardar
	case 1:
		include("../clases/Evento.php");
		$clase = new Evento();
		$resultado = $clase->guardar($obj);
		echo $resultado;
	break;
	//consultar
	case 2:
		include("../clases/Evento.php");
		$clase = new Evento();
		$lista = json_encode($clase->consultar($obj));
		echo $lista;
	break;
	//consultar personal apoyo
	case 3:
		include("../clases/Evento.php");
		$clase = new Evento();
		$lista = json_encode($clase->consultarPersonalApoyo($obj));
		echo $lista;
	break;
	//editar
	case 4:
		include("../clases/Evento.php");
		$clase = new Evento();
		$resultado = $clase->editar($obj);
		echo $resultado;
	break;
	//realizar
	case 5:
		include("../clases/Evento.php");
		$clase = new Evento();
		$resultado = $clase->realizarPersonal($obj);
		echo $resultado;
	break;
	//realizar
	case 6:
		include("../clases/Evento.php");
		$clase = new Evento();
		$resultado = $clase->eliminar($obj);
		echo $resultado;
	break;
}


?>