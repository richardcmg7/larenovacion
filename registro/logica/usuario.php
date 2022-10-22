<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//guardar
	case 1:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$resultado = $clase->guardar($obj);
		echo $resultado;
	break;
	//consultar
	case 2:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$lista = json_encode($clase->consultar($obj));
		echo $lista;
	break;
	//editar
	case 3:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$resultado = $clase->editarInfo($obj);
		echo $resultado;
	break;
	//eliminar
	case 4:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$resultado = $clase->eliminar($obj);
		echo $resultado;
	break;
	//editar
	case 5:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$resultado = $clase->editarCrede($obj);
		echo $resultado;
	break;
	//consultar
	case 6:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$lista = json_encode($clase->consultarPersona($obj));
		echo $lista;
	break;
	//consultar personal apoyo
	case 7:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$lista = json_encode($clase->consultarPersonaApoyo($obj));
		echo $lista;
	break;
	//consultar lideres
	case 8:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$lista = json_encode($clase->consultarLider($obj));
		echo $lista;
	break;
	//consultar lideres en general
	case 9:
		include("../clases/Usuario.php");
		$clase = new Usuario();
		$lista = json_encode($clase->consultarLiderGeneral($obj));
		echo $lista;
	break;
}


?>