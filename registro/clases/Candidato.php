<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Candidato
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

		$consulta = "SELECT count(*) as cantidad FROM candidatos WHERE eliminado=1 AND categoria_id='" . $obj->categoria->id . "' AND nombre='" . $obj->nombre . "' ";
		$cnx->Consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "insert into candidatos (nombre,categoria_id,valor,estado,eliminado) values ('" . $obj->nombre . "','" . $obj->categoria->id . "','" . $obj->valor->id . "','a',1)";
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

		$sql = "SELECT 
			c.id,
		    c.nombre,
		    c.categoria_id,cp.nombre as categoriaNombre,cp.ubicacion,
		    c.valor,
		    c.estado,
		    CASE WHEN c.estado='a' THEN 'ACTIVO' ELSE 'INACTIVO' END as estadoNombre,
		    p.nombre as paisNombre,
		    d.nombre as departamentoNombre,
		    m.nombre as municipioNombre,dm.nombre as departamentoMunicipioNombre
		FROM candidatos c
		INNER JOIN categorias_politicas cp on c.categoria_id = cp.id
		LEFT JOIN paises p on c.valor = p.id
		LEFT JOIN departamentos d on c.valor = d.id
		LEFT JOIN municipios m on c.valor = m.id
		LEFT JOIN departamentos dm on m.departamento_id = dm.id
		WHERE c.eliminado=1 ORDER BY cp.nombre,c.nombre ASC";

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

			$nb->categoria = new stdClass();
			$nb->categoria->id = $resultado[$i]["categoria_id"];
			$nb->categoria->nombre = $resultado[$i]["categoriaNombre"];
			$nb->categoria->ubicacion = $resultado[$i]["ubicacion"];

			$nb->valor = new stdClass();
			$nb->valor->id = $resultado[$i]["valor"];
			if ($resultado[$i]["ubicacion"] == 'Municipal') {
				$nb->valor->nombre = $resultado[$i]["municipioNombre"] . ', ' . $resultado[$i]["departamentoMunicipioNombre"];
			} else  if ($resultado[$i]["ubicacion"] == 'Departamental') {
				$nb->valor->nombre = $resultado[$i]["departamentoNombre"];
			} else {
				$nb->valor->nombre = $resultado[$i]["paisNombre"];
			}

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

		$consulta = "SELECT count(*) as cantidad FROM candidatos WHERE id!=" . $obj->id . " AND categoria_id='" . $obj->categoria->id . "' AND eliminado=1 AND nombre='" . $obj->nombre . "' ";
		$cnx->Consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "UPDATE candidatos SET nombre='" . $obj->nombre . "',estado='" . $obj->estado . "' WHERE id=" . $obj->id . " ";
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

		$sql = "UPDATE candidatos SET eliminado=0 WHERE id=" . $obj->id . " ";
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

		if ($obj->categoria->ubicacion == 'Municipal') {
			$valor = $obj->municipio->id;
		} elseif ($obj->categoria->ubicacion == 'Departamental') {
			$valor = $obj->departamento->id;
		} else {
			$valor = 1;
		}

		$sql = "SELECT 
			c.id,
		    c.nombre
		FROM candidatos c
		WHERE c.categoria_id = '" . $obj->categoria->id . "'
		AND c.valor = '" . $valor . "'
		AND c.estado = 'a'
		AND c.eliminado=1 
		ORDER BY c.nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->nombre = $resultado[$i]["nombre"];

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}
}//FIn Clase
