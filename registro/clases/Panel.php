<?php
@session_start();
$usrStr = $_SESSION["usuarioLogin"];

//Conexión a datos
require_once('../conexion/cls_config.php');
//Manejo de datos
class Panel
{

	function consultarLogin($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}
		$cnx2 = new cls_config();
		if (!$cnx2->Conectar()) {
			return false;
		}

		$sql = "SELECT 
			u.id,
			ip.documento,
			ip.primer_nombre,
			ip.segundo_nombre,
			ip.primer_apellido,
			ip.segundo_apellido,
			u.tipo,
			ip.fotografia,
			CASE WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'LIDER' WHEN u.tipo=3 THEN 'COLABORADOR' WHEN u.tipo=4 THEN 'MARKETING' ELSE 'ESCRUTINIO' END as tipoNombre,
			u.municipio_id,
			CASE 
				WHEN u.tipo=2 THEN CONCAT(m.nombre, ', ', d.nombre) 
				WHEN u.tipo=3 THEN CONCAT(m.nombre, ', ', d.nombre)
				WHEN u.tipo=4 THEN CONCAT(m.nombre, ', ', d.nombre) 
				ELSE '' 
			END as municipioNombre 
		FROM usuarios u 
		INNER join informacion_personal ip on u.informacion_personal_id = ip.id
		LEFT JOIN municipios m on u.municipio_id = m.id
        LEFT JOIN departamentos d on m.departamento_id = d.id 
		WHERE ip.documento='" . $obj->usuLogin . "' 
		AND u.clave='" . $obj->usuContra . "' 
		AND u.estado='a' AND u.eliminado=1";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$result = $cnx->json;
		$count = 0;
		date_default_timezone_set("America/Bogota");
		$token = date("Y") . date("m") . date("d") . "_" . date("h") . date("i") . date("s");
		for ($i = 0; $i < count($result); $i++) {
			$result[$i]["ip"] = $token;
			if ($result[$i]["fotografia"] == '' || $result[$i]["fotografia"] == null) {
				$result[$i]["fotografia"] = 'dist/img/user.jpg';
			} else {
				$result[$i]["fotografia"] = 'imagenes/' . $result[$i]["fotografia"];
			}
			$count++;
		}
		if ($count > 0) {
			$sql2 = "UPDATE loguin SET estado=0 WHERE usuario='" . $obj->usuLogin . "' ";
			if (!$cnx2->Sentencia($sql2)) {
				$num_error = $num_error + 1;
				$consola = $consola . ' Error al editar';
			}

			$sql3 = "insert into loguin (usuario,ip,estado) values ('" . $obj->usuLogin . "','" . $token . "',1)";
			if (!$cnx2->Sentencia($sql3)) {
				$num_error = $num_error + 1;
				$consola = $consola . ' Error al editar';
			}
		}

		$cnx->Desconectar();
		$cnx2->Desconectar();
		return $result;
	}

	function consultarUsuarioLogueado($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$usrStr = $_SESSION["usuarioLogin"];

		$sql = "SELECT id
		FROM loguin WHERE usuario='" . $usrStr[0]["documento"] . "' AND ip='" . $usrStr[0]["ip"] . "' AND estado=0 ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}

	function salir($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al guardar el registro';
		$cnx->Sentencia("START TRANSACTION");

		$usrStr = $_SESSION["usuarioLogin"];

		$sql = "UPDATE loguin SET estado=0 WHERE usuario='" . $usrStr[0]["documento"] . "' AND ip='" . $usrStr[0]["ip"] . "' ";
		if (!$cnx->Sentencia($sql)) {
			$num_error = $num_error + 1;
			$consola = $consola . ' Error al editar';
		}

		if ($num_error == 0) {
			$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se guardo correctamente\", \"consola\": \"$consola\"}";
		} else {
			$cnx->Sentencia("ROLLBACK");
			return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		$cnx->Desconectar();
	}
}//FIn Clase
