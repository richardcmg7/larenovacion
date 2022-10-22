<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//guardar
	case 1:
		include("../clases/Registro.php");
		$clase = new Registro();
		$resultado = $clase->guardar($obj);
		echo $resultado;
	break;
	//consultar
	case 2:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultar($obj));
		echo $lista;
	break;
	//consultar alertas
	case 3:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarAlertas($obj));
		echo $lista;
	break;
	//consultar intencion voto
	case 4:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarIntencionVoto($obj));
		echo $lista;
	break;
	//consultar reporte
	case 5:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarReporte($obj));
		echo $lista;
	break;
	//consultar seguidores
	case 6:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarSeguidor($obj));
		echo $lista;
	break;
	//asignar lider
	case 7:
		include("../clases/Registro.php");
		$clase = new Registro();
		$resultado = $clase->asignarLider($obj);
		echo $resultado;
	break;
}


?>