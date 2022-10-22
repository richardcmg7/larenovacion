<?php
session_start();

header("Content-Type:application/json");
$obj =json_decode(file_get_contents('php://input'));

switch($obj->accion){
	case 1:
		include("../clases/Panel.php");
		$panel = new Panel();
		$usuario = $panel->consultarLogin($obj);
		$_SESSION["usuarioLogin"] = $usuario;
		echo $usuario;
	break;
	case 2:
		$usrStr = $_SESSION["usuarioLogin"];
		$user = array('codigoPerfilUsuario'=> $usrStr[0]["tipo"],'nombre'=> $usrStr[0]["documento"],'foto'=> $usrStr[0]["fotografia"],
		'codigoUsuario'=> $usrStr[0]["id"], 'nombrePerfilUsuario'=>$usrStr[0]["tipoNombre"],'nombrePersona'=> $usrStr[0]["primer_nombre"].' '.$usrStr[0]["segundo_nombre"].' '.$usrStr[0]["primer_apellido"].' '.$usrStr[0]["segundo_apellido"],'ip'=> $usrStr[0]["ip"], 'municipioId'=> $usrStr[0]["municipio_id"], 'municipioNombre'=> $usrStr[0]["municipioNombre"]);
		$usuario = json_encode($user);
		echo $usuario;
	break;
	case 3:
		include("../clases/Panel.php");
		$clase = new Panel();
		$lista = json_encode($clase->consultarUsuarioLogueado($obj));
		echo $lista;
	break;
	case 4:
		include("../clases/Panel.php");
		$clase = new Panel();
		$resultado = $clase->salir($obj);
		echo $resultado;
	break;
}

?>