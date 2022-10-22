<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	//consultar grupo sanguineo
	case 1:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarGrupoSanguineo($obj));
		echo $lista;
	break;
	//consultar sexo
	case 2:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarSexo($obj));
		echo $lista;
	break;
	//consultar orientacion sexual
	case 3:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarOrientacionSexual($obj));
		echo $lista;
	break;
	//consultar estado civil
	case 4:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarEstadoCivil($obj));
		echo $lista;
	break;
	//consultar persona
	case 5:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarPersona($obj));
		echo $lista;
	break;
	//guardar persona
	case 6:
		include("../clases/Registro.php");
		$clase = new Registro();
		$resultado = $clase->guardarPersona($obj);
		echo $resultado;
	break;
	//consultar departamento
	case 7:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarDepartamento($obj));
		echo $lista;
	break;
	//consultar municipio
	case 8:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarMunicipio($obj));
		echo $lista;
	break;
	//consultar municipio
	case 9:
		include("../clases/Registro.php");
		$clase = new Registro();
		$lista = json_encode($clase->consultarLider($obj));
		echo $lista;
	break;
}


?>