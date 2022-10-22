<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class BarrioVereda
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

		$consulta = "SELECT count(*) as cantidad FROM barrios_veredas WHERE eliminado=1 AND comuna_id='" . $obj->comuna->id . "' AND nombre='" . $obj->nombre . "' ";
		$cnx->Consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "insert into barrios_veredas (nombre,comuna_id,estado,eliminado,
			zona_residencia_id) values ('" . $obj->nombre . "','" . $obj->comuna->id . "','a',1,
			'" . $obj->zonaResidencia->id . "')";
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
		c.nombre as comunaNombre,b.comuna_id,
		l.nombre as localidadNombre,c.localidad_id,
		m.nombre as municipioNombre,l.municipio_id,
		d.nombre as departamentoNombre,m.departamento_id,
		z.nombre as zonaResidenciaNombre,b.zona_residencia_id
		FROM barrios_veredas b
		INNER JOIN comunas c on b.comuna_id = c.id
		INNER JOIN localidades l on c.localidad_id = l.id
		INNER JOIN municipios m on l.municipio_id = m.id
		INNER JOIN departamentos d on m.departamento_id = d.id
		INNER JOIN zonas_residencia z on b.zona_residencia_id = z.id
		WHERE b.eliminado=1 ORDER BY d.nombre,m.nombre,l.nombre,c.nombre,b.nombre ASC";

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

			$nb->comuna = new stdClass();
			$nb->comuna->id = $resultado[$i]["comuna_id"];
			$nb->comuna->nombre = $resultado[$i]["comunaNombre"];

			$nb->localidad = new stdClass();
			$nb->localidad->id = $resultado[$i]["localidad_id"];
			$nb->localidad->nombre = $resultado[$i]["localidadNombre"];

			$nb->municipio = new stdClass();
			$nb->municipio->id = $resultado[$i]["municipio_id"];
			$nb->municipio->nombre = $resultado[$i]["municipioNombre"];

			$nb->departamento = new stdClass();
			$nb->departamento->id = $resultado[$i]["departamento_id"];
			$nb->departamento->nombre = $resultado[$i]["departamentoNombre"];

			$nb->zonaResidencia = new stdClass();
			$nb->zonaResidencia->id = $resultado[$i]["zona_residencia_id"];
			$nb->zonaResidencia->nombre = $resultado[$i]["zonaResidenciaNombre"];

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

		$consulta = "SELECT count(*) as cantidad FROM barrios_veredas WHERE id!=" . $obj->id . " AND comuna_id='" . $obj->comuna->id . "' AND eliminado=1 AND nombre='" . $obj->nombre . "' ";
		$cnx->consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "UPDATE barrios_veredas SET nombre='" . $obj->nombre . "',comuna_id='" . $obj->comuna->id . "',estado='" . $obj->estado . "',zona_residencia_id='" . $obj->zonaResidencia->id . "' WHERE id=" . $obj->id . " ";
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

		$sql = "UPDATE barrios_veredas SET eliminado=0 WHERE id=" . $obj->id . " ";
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

		$sql = "SELECT b.id,b.nombre,b.estado,z.nombre as zonaResidencia
		FROM barrios_veredas b
		INNER JOIN zonas_residencia z on b.zona_residencia_id = z.id
		WHERE b.eliminado=1 AND b.estado='a' AND b.comuna_id='" . $obj->comuna->id . "' ORDER BY b.nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}
}//FIn Clase
