<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Categoria
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

		$consulta = "SELECT count(*) as cantidad FROM categorias_politicas WHERE eliminado=1 AND nombre='" . $obj->nombre . "' ";
		$cnx->Sentencia($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "insert into categorias_politicas (nombre,ubicacion,estado,eliminado) values ('" . $obj->nombre . "','" . $obj->ubicacion . "','a',1)";
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

		$sql = "SELECT m.id,m.nombre,m.estado,m.ubicacion,
		CASE WHEN m.estado='a' THEN 'ACTIVO' ELSE 'INACTIVO' END as estadoNombre
		FROM categorias_politicas m
		WHERE m.eliminado=1 ORDER BY m.ubicacion,m.nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->nombre = $resultado[$i]["nombre"];
			$nb->ubicacion = $resultado[$i]["ubicacion"];
			$nb->estado = $resultado[$i]["estado"];
			$nb->estadoNombre = $resultado[$i]["estadoNombre"];

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

		$consulta = "SELECT count(*) as cantidad FROM categorias_politicas WHERE id!=" . $obj->id . " AND eliminado=1 AND nombre='" . $obj->nombre . "' ";
		$cnx->Consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "UPDATE categorias_politicas SET nombre='" . $obj->nombre . "',ubicacion='" . $obj->ubicacion . "',estado='" . $obj->estado . "' WHERE id=" . $obj->id . " ";
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

		$sql = "UPDATE categorias_politicas SET eliminado=0 WHERE id=" . $obj->id . " ";
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

		$sql = "SELECT id,nombre,estado,ubicacion
		FROM categorias_politicas
		WHERE eliminado=1 AND estado='a' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}
}//FIn Clase
