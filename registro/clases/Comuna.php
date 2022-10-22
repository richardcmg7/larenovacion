<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Comuna
{

	function guardar($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al guardar el registro';
		$cnx->Sentencia("START TRANSACTION");

		$obj->nombre = utf8_decode($obj->nombre);
		$obj->nombre = strtoupper($obj->nombre);

		$consulta = "SELECT count(*) as cantidad FROM comunas WHERE eliminado=1 AND localidad_id='" . $obj->localidad->id . "' AND nombre='" . $obj->nombre . "' ";
		$cnx->consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "insert into comunas (nombre,localidad_id,estado,eliminado) values ('" . $obj->nombre . "','" . $obj->localidad->id . "','a',1)";
			if (!$cnx->Sentencia($sql)) {
				$num_error = $num_error + 1;
				$consola = $consola . ' Error al guardar';
			}
		} else {
			$num_error = $num_error + 1;
			$mensaje = 'El registro que deseas guardar ya esta creado.';
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

	function consultar($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT b.id,b.nombre,b.estado,
		CASE WHEN b.estado='a' THEN 'ACTIVO' ELSE 'INACTIVO' END as estadoNombre,
		l.nombre as localidadNombre,b.localidad_id,
		m.nombre as municipioNombre,l.municipio_id,
		d.nombre as departamentoNombre,m.departamento_id
		FROM comunas b
		INNER JOIN localidades l on b.localidad_id = l.id
		INNER JOIN municipios m on l.municipio_id = m.id
		INNER JOIN departamentos d on m.departamento_id = d.id
		WHERE b.eliminado=1 ORDER BY d.nombre,m.nombre,l.nombre,b.nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->nombre = $resultado[$i]["nombre"];
			$nb->estado = $resultado[$i]["estado"];
			$nb->estadoNombre = $resultado[$i]["estadoNombre"];

			$nb->localidad = new stdClass();
			$nb->localidad->id = $resultado[$i]["localidad_id"];
			$nb->localidad->nombre = $resultado[$i]["localidadNombre"];

			$nb->municipio = new stdClass();
			$nb->municipio->id = $resultado[$i]["municipio_id"];
			$nb->municipio->nombre = $resultado[$i]["municipioNombre"];

			$nb->departamento = new stdClass();
			$nb->departamento->id = $resultado[$i]["departamento_id"];
			$nb->departamento->nombre = $resultado[$i]["departamentoNombre"];

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function editar($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al editar el registro';
		$cnx->Sentencia("START TRANSACTION");

		$obj->nombre = utf8_decode($obj->nombre);
		$obj->nombre = strtoupper($obj->nombre);

		$consulta = "SELECT count(*) as cantidad FROM comunas WHERE id!=" . $obj->id . " AND localidad_id='" . $obj->localidad->id . "' AND eliminado=1 AND nombre='" . $obj->nombre . "' ";
		$cnx->consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "UPDATE comunas SET nombre='" . $obj->nombre . "',localidad_id='" . $obj->localidad->id . "',estado='" . $obj->estado . "' WHERE id=" . $obj->id . " ";
			if (!$cnx->Sentencia($sql)) {
				$num_error = $num_error + 1;
				$consola = $consola . ' Error al editar';
			}
		} else {
			$num_error = $num_error + 1;
			$mensaje = 'El registro que deseas editar ya esta creado.';
		}

		if ($num_error == 0) {
			$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se edito correctamente\", \"consola\": \"$consola\"}";
		} else {
			$cnx->Sentencia("ROLLBACK");
			return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		$cnx->Desconectar();
	}

	function eliminar($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$cnx->Sentencia("START TRANSACTION");

		$sql = "UPDATE comunas SET eliminado=0 WHERE id=" . $obj->id . " ";
		if (!$cnx->Sentencia($sql)) {
			$num_error = $num_error + 1;
			$mensaje = $mensaje . ' Error al eliminar';
		}

		if ($num_error == 0) {
			$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se elimino correctamente\", \"consola\": \"$mensaje\"}";
		} else {
			$cnx->Sentencia("ROLLBACK");
			return "{\"resultado\": false, \"mensaje\": \"Error al eliminar el registro\", \"consola\": \"$mensaje\"}";
		}

		$cnx->Desconectar();
	}

	function consultarPublico($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre,estado
		FROM comunas
		WHERE eliminado=1 AND estado='a' AND localidad_id='" . $obj->localidad->id . "' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}
}//FIn Clase
