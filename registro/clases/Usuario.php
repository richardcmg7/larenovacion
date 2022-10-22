<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Usuario
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

		$obj->clave = utf8_decode($obj->clave);

		$municipio = $obj->municipio->id;
		if ($obj->tipo == '1' || $obj->tipo == '5') {
			$municipio = 0;
		}

		$consulta = "SELECT count(*) as cantidad FROM usuarios WHERE eliminado=1 AND informacion_personal_id='" . $obj->persona . "' ";
		$cnx->Consultar($consulta);
		$row = mysqli_fetch_array($cnx->resultado);
		$existe = $row['cantidad'];

		if ($existe == 0) {
			$sql = "insert into usuarios (informacion_personal_id,clave,tipo,estado,municipio_id,eliminado) values ('" . $obj->persona . "','" . $obj->clave . "','" . $obj->tipo . "','a','" . $municipio . "',1)";
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

		$sql = "SELECT u.id,ip.id as idPersona,ip.documento,ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,ip.segundo_apellido,u.tipo,u.estado,
		CASE WHEN u.tipo=1 THEN 'ADMINISTRADOR' WHEN u.tipo=2 THEN 'LIDER' WHEN u.tipo=3 THEN 'COLABORADOR' WHEN u.tipo=4 THEN 'MARKETING' ELSE 'ESCRUTINIO' END as tipoNombre,
		CASE WHEN u.estado='a' THEN 'ACTIVO' ELSE 'INACTIVO' END as estadoNombre,
        u.municipio_id,m.nombre as municipioNombre,m.departamento_id,d.nombre as departamentoNombre
		FROM usuarios u 
        INNER JOIN informacion_personal ip on u.informacion_personal_id = ip.id
        LEFT JOIN municipios m on u.municipio_id = m.id
        LEFT JOIN departamentos d on m.departamento_id = d.id
        WHERE u.eliminado=1 ORDER BY ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,ip.segundo_apellido ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {
			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->idPersona = $resultado[$i]["idPersona"];
			$nb->usuario = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"] . ' ' . $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];
			$nb->estado = $resultado[$i]["estado"];
			$nb->estadoNombre = $resultado[$i]["estadoNombre"];
			$nb->tipo = $resultado[$i]["tipo"];
			$nb->tipoNombre = $resultado[$i]["tipoNombre"];

			if ($resultado[$i]["tipo"] != 2 && $resultado[$i]["tipo"] != '2' && $resultado[$i]["tipo"] != 3 && $resultado[$i]["tipo"] != '3' && $resultado[$i]["tipo"] != 4 && $resultado[$i]["tipo"] != '4') {
				$resultado[$i]["municipio_id"] = null;
				$resultado[$i]["municipioNombre"] = null;
				$resultado[$i]["departamento_id"] = null;
				$resultado[$i]["departamentoNombre"] = null;
				$lugar = 'N/A';
			} else {
				$lugar = $resultado[$i]["municipioNombre"] . ", " . $resultado[$i]["departamentoNombre"];
			}

			$nb->municipio = new stdClass();
			$nb->municipio->id = $resultado[$i]["municipio_id"];
			$nb->municipio->nombre = $resultado[$i]["municipioNombre"];

			$nb->departamento = new stdClass();
			$nb->departamento->id = $resultado[$i]["departamento_id"];
			$nb->departamento->nombre = $resultado[$i]["departamentoNombre"];

			$nb->lugar = $lugar;

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function editarInfo($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al editar el registro';
		$cnx->Sentencia("START TRANSACTION");

		$obj->nombre = utf8_decode($obj->nombre);

		$municipio = $obj->municipio->id;
		if ($obj->tipo == '1' || $obj->tipo == '5') {
			$municipio = 0;
		}

		$sql = "UPDATE usuarios SET tipo='" . $obj->tipo . "',estado='" . $obj->estado . "',municipio_id='" . $municipio . "' WHERE id=" . $obj->id . " ";
		if (!$cnx->Sentencia($sql)) {
			$num_error = $num_error + 1;
			$consola = $consola . ' Error al editar';
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

		$sql = "UPDATE usuarios SET eliminado=0 WHERE id=" . $obj->id . " ";
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

	function editarCrede($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al editar el registro';
		$cnx->Sentencia("START TRANSACTION");

		$obj->usuario = utf8_decode($obj->usuario);
		$obj->clave = utf8_decode($obj->clave);
		$obj->actual = utf8_decode($obj->actual);

		$actualiza = 1;
		if ($obj->cambio == '2' || $obj->cambio == '2') {
			$consulta = "SELECT COUNT(*) as cantidad FROM usuarios u INNER JOIN informacion_personal i on u.informacion_personal_id = i.id
			WHERE i.documento = '" . $obj->usuario . "' AND u.clave = '" . $obj->actual . "' AND u.eliminado=1 ";
			$cnx->Consultar($consulta);
			$row = mysqli_fetch_array($cnx->resultado);
			$existe = $row['cantidad'];

			if ($existe == 0) {
				$num_error = $num_error + 1;
				$mensaje = utf8_decode('El campo Contraseña Actual fue digitado de forma incorrecta.');
				$actualiza = 2;
			}
		}

		if ($actualiza == 1) {
			$sql = "UPDATE usuarios SET clave='" . $obj->clave . "' WHERE id=" . $obj->id . " ";
			if (!$cnx->Sentencia($sql)) {
				$num_error = $num_error + 1;
				$consola = $consola . ' Error al editar';
			}
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

	function consultarPersona($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT ip.id,ip.documento,ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,ip.segundo_apellido
		FROM informacion_personal ip
        WHERE ip.documento = '" . $obj->documento . "' ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {
			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"] . ' ' . $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];
			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarPersonaApoyo($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$usrStr = $_SESSION["usuarioLogin"];
		$condicion = " WHERE iu.municipio_id IS NOT NULL ";
		if ($usrStr[0]["tipo"] == 3 || $usrStr[0]["tipo"] == '3') {
			$condicion = " WHERE iu.municipio_id = '" . $usrStr[0]["municipio_id"] . "' ";
		}
		if ($obj->busqueda == 1 || $obj->busqueda == '1') {
			$condicion = $condicion . " AND ip.documento = '" . $obj->documento . "' ";
		}

		$sql = "SELECT ip.id,ip.documento,ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,ip.segundo_apellido
		FROM informacion_personal ip
		LEFT JOIN informacion_ubicacion iu on iu.informacion_personal_id = ip.id
        " . $condicion . " ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {
			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"] . ' ' . $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];
			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarLider($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT ip.id,ip.documento,ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,
		ip.segundo_apellido
		FROM usuarios u 
		INNER JOIN informacion_personal ip ON u.informacion_personal_id=ip.id
		WHERE u.tipo = 2 AND u.municipio_id = '" . $obj->municipio . "' AND u.eliminado = 1 
		AND u.estado = 'a' 
		ORDER BY ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,
		ip.segundo_apellido ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();

		$nb = new stdClass();
		$nb->id = 48;
		$nb->documento = '83089672';
		$nb->nombre = 'VICTOR RAMON VARGAS SALAZAR';
		$listado[] = $nb;

		for ($i = 0; $i < count($resultado); $i++) {
			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"] . ' ' . $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];
			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarLiderGeneral($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT ip.id,ip.documento,ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,
		ip.segundo_apellido,m.nombre as municipioNombre,d.nombre as departamentoNombre
		FROM usuarios u 
		INNER JOIN informacion_personal ip ON u.informacion_personal_id=ip.id
		LEFT JOIN municipios m on u.municipio_id = m.id
        LEFT JOIN departamentos d on m.departamento_id = d.id
		WHERE u.tipo = 2 AND u.eliminado = 1 
		AND u.estado = 'a' 
		ORDER BY ip.primer_nombre,ip.segundo_nombre,ip.primer_apellido,
		ip.segundo_apellido ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();

		$nb = new stdClass();
		$nb->id = 48;
		$nb->documento = '83089672';
		$nb->nombre = 'VICTOR RAMON VARGAS SALAZAR';
		$nb->municipio = 'CAMPOALEGRE, HUILA';
		$listado[] = $nb;

		for ($i = 0; $i < count($resultado); $i++) {
			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"] . ' ' . $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];
			$nb->municipio = $resultado[$i]["municipioNombre"] . ',' . $resultado[$i]["departamentoNombre"];
			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}
}//FIn Clase
