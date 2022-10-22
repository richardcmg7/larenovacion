<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Home
{


	function consultar($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT
			count(*) as cantidad
		FROM informacion_personal ip
		INNER JOIN informacion_ubicacion iu on ip.id = iu.informacion_personal_id
		INNER JOIN informacion_demografica id on ip.id = id.informacion_personal_id
		INNER JOIN informacion_lugar_votacion iv on ip.id = iv.informacion_personal_id";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$registrosCompletos = 0;
		for ($i = 0; $i < count($resultado); $i++) {
			$registrosCompletos = $resultado[$i]["cantidad"];
		}

		$sql = "SELECT
			count(*) as cantidad
		FROM informacion_personal ip
		LEFT JOIN informacion_ubicacion iu on ip.id = iu.informacion_personal_id
		LEFT JOIN informacion_demografica id on ip.id = id.informacion_personal_id
		LEFT JOIN informacion_lugar_votacion iv on ip.id = iv.informacion_personal_id
        WHERE iu.direccion_residencia IS NULL
        AND id.id IS NULL
        AND iv.id IS NULL";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$registrosSeguidores = 0;
		for ($i = 0; $i < count($resultado); $i++) {
			$registrosSeguidores = $resultado[$i]["cantidad"];
		}

		$sql = "SELECT
			count(*) as cantidad
		FROM informacion_personal ip
		LEFT JOIN informacion_ubicacion iu on ip.id = iu.informacion_personal_id
        WHERE iu.direccion_residencia IS NULL";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$registrosSinUbicacion = 0;
		for ($i = 0; $i < count($resultado); $i++) {
			$registrosSinUbicacion = $resultado[$i]["cantidad"];
		}

		$sql = "SELECT
			count(*) as cantidad
		FROM informacion_personal ip
		LEFT JOIN informacion_demografica id on ip.id = id.informacion_personal_id
        WHERE id.id IS NULL";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$registrosSinDemografica = 0;
		for ($i = 0; $i < count($resultado); $i++) {
			$registrosSinDemografica = $resultado[$i]["cantidad"];
		}

		$sql = "SELECT
			count(*) as cantidad
		FROM informacion_personal ip
		LEFT JOIN informacion_lugar_votacion iv on ip.id = iv.informacion_personal_id
        WHERE iv.id IS NULL";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$registrosSinVotacion = 0;
		for ($i = 0; $i < count($resultado); $i++) {
			$registrosSinVotacion = $resultado[$i]["cantidad"];
		}

		$cnx->Desconectar();

		return "{\"registrosCompletos\": \"$registrosCompletos\", \"registrosSeguidores\": \"$registrosSeguidores\", \"registrosSinUbicacion\": \"$registrosSinUbicacion\", \"registrosSinDemografica\": \"$registrosSinDemografica\", \"registrosSinVotacion\": \"$registrosSinVotacion\"}";
	}
}//FIn Clase
