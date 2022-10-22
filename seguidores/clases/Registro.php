<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Registro
{

	function consultarGrupoSanguineo($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre FROM grupos_sanguineos WHERE eliminado=1 AND estado='a' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}

	function consultarSexo($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre FROM sexos WHERE eliminado=1 AND estado='a' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}

	function consultarOrientacionSexual($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre FROM generos WHERE eliminado=1 AND estado='a' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}

	function consultarEstadoCivil($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre FROM estados_civil WHERE eliminado=1 AND estado='a' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}

	function consultarPersona($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$where = '';
		$usrStr = $_SESSION["usuarioLogin"];
		if ($usrStr[0]["tipo"] == 2 || $usrStr[0]["tipo"] == '2') {
			$where = " AND (p.lider = '" . $usrStr[0]["documento"] . "' OR p.documento = '" . $usrStr[0]["documento"] . "') ";
		}

		$sql = "SELECT 
			p.id,
			p.documento,
			p.primer_nombre,
			p.segundo_nombre,
			p.primer_apellido,
			p.segundo_apellido,
			p.fecha_nacimiento,
			p.grupo_sanguineo_id,gs.nombre as nombreGrupoSanguineo,
			p.sexo_id,s.nombre as nombreSexo,
			p.orientacion_sexual_id,g.nombre as nombreOrientacionSexual,
			p.estado_civil_id,ec.nombre as nombreEstadoCivil,
			p.correo_electronico,
			p.numero_celular,
			p.numero_whatsapp,
			p.autorizacion_tratamiento_datos_personales
		FROM informacion_personal p
		INNER JOIN grupos_sanguineos gs on p.grupo_sanguineo_id = gs.id
		INNER JOIN sexos s on p.sexo_id = s.id
		INNER JOIN generos g on p.orientacion_sexual_id = g.id
		INNER JOIN estados_civil ec on p.estado_civil_id = ec.id
		WHERE p.documento = '" . $obj->documento . "' " . $where . " ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->primerNombre = $resultado[$i]["primer_nombre"];
			$nb->segundoNombre = $resultado[$i]["segundo_nombre"];
			$nb->primerApellido = $resultado[$i]["primer_apellido"];
			$nb->segundoApellido = $resultado[$i]["segundo_apellido"];
			$nb->fechaNacimiento = $resultado[$i]["fecha_nacimiento"];

			$nb->grupoSanguineo = new stdClass();
			$nb->grupoSanguineo->id = $resultado[$i]["grupo_sanguineo_id"];
			$nb->grupoSanguineo->nombre = $resultado[$i]["nombreGrupoSanguineo"];

			$nb->sexo = new stdClass();
			$nb->sexo->id = $resultado[$i]["sexo_id"];
			$nb->sexo->nombre = $resultado[$i]["nombreSexo"];

			$nb->orientacionSexual = new stdClass();
			$nb->orientacionSexual->id = $resultado[$i]["orientacion_sexual_id"];
			$nb->orientacionSexual->nombre = $resultado[$i]["nombreOrientacionSexual"];

			$nb->estadoCivil = new stdClass();
			$nb->estadoCivil->id = $resultado[$i]["estado_civil_id"];
			$nb->estadoCivil->nombre = $resultado[$i]["nombreEstadoCivil"];

			$nb->correoElectronico = $resultado[$i]["correo_electronico"];
			$nb->numeroCelular = $resultado[$i]["numero_celular"];
			$nb->numeroWhatsapp = $resultado[$i]["numero_whatsapp"];
			if ($resultado[$i]["autorizacion_tratamiento_datos_personales"] != 'SI') {
				$resultado[$i]["autorizacion_tratamiento_datos_personales"] = 'NO';
			}
			$nb->autorizacion = $resultado[$i]["autorizacion_tratamiento_datos_personales"];

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function guardarPersona($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al realizar la operacion.';
		$cnx->Sentencia("START TRANSACTION");

		$id = $obj->id;
		$documento = $obj->documento;
		$primerNombre = utf8_decode($obj->primerNombre);
		$primerNombre = strtoupper($primerNombre);
		$segundoNombre = utf8_decode($obj->segundoNombre);
		$segundoNombre = strtoupper($segundoNombre);
		$primerApellido = utf8_decode($obj->primerApellido);
		$primerApellido = strtoupper($primerApellido);
		$segundoApellido = utf8_decode($obj->segundoApellido);
		$segundoApellido = strtoupper($segundoApellido);
		$fechaNacimiento = $obj->fechaNacimiento;
		$grupoSanguineo = $obj->grupoSanguineo->id;
		$sexo = $obj->sexo->id;
		$orientacionSexual = $obj->orientacionSexual->id;
		$estadoCivil = $obj->estadoCivil->id;
		$correoElectronico = $obj->correoElectronico;
		$numeroCelular = $obj->numeroCelular;
		$numeroWhatsapp = $obj->numeroWhatsapp;
		$municipio = $obj->municipio->id;
		$lider = $obj->lider->documento;

		if ($id == '' || $id == null || $id == undefined) {

			$consulta = "SELECT count(*) as cantidad FROM informacion_personal WHERE documento='" . $documento . "' ";
			$cnx->Consultar($consulta);
			$row = $cnx->json[0];
			$existe = $row['cantidad'];

			if ($existe == 0) {
				$sql = "insert into informacion_personal (documento,primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,fecha_nacimiento,grupo_sanguineo_id,sexo_id,orientacion_sexual_id,estado_civil_id,correo_electronico,numero_celular,numero_whatsapp,autorizacion_tratamiento_datos_personales,lider,fecha) values ('" . $documento . "','" . $primerNombre . "','" . $segundoNombre . "','" . $primerApellido . "','" . $segundoApellido . "','" . $fechaNacimiento . "','" . $grupoSanguineo . "','" . $sexo . "','" . $orientacionSexual . "','" . $estadoCivil . "','" . $correoElectronico . "','" . $numeroCelular . "','" . $numeroWhatsapp . "','SI','" . $lider . "',NOW())";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al guardar';
				}

				$consulta2 = "SELECT id FROM informacion_personal WHERE documento='" . $documento . "' ORDER BY id DESC LIMIT 1 ";
				$cnx->Consultar($consulta2);
				$row = $cnx->json[0];
				$idPersonal = $row['id'];

				$sql2 = "insert into informacion_ubicacion (informacion_personal_id,municipio_id) values ('" . $idPersonal . "','" . $municipio . "')";
				if (!$cnx->Sentencia($sql2)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al guardar ubicacion';
				}
			} else {
				$num_error = $num_error + 1;
				$mensaje = 'El registro que deseas guardar ya esta creado.';
			}
		} else {

			$sql = "UPDATE informacion_personal SET autorizacion_tratamiento_datos_personales='SI' WHERE id=" . $id . " ";
			if (!$cnx->Sentencia($sql)) {
				$num_error = $num_error + 1;
				$consola = $consola . ' Error al editar';
			}
		}

		if ($num_error == 0) {
			$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"Operacion realizada exitosamente.\", \"consola\": \"$consola\"}";
		} else {
			$cnx->Sentencia("ROLLBACK");
			return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		$cnx->Desconectar();
	}

	function consultarDepartamento($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre FROM departamentos WHERE eliminado=1 AND estado='a' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
	}

	function consultarMunicipio($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT id,nombre,estado,
		CASE WHEN estado='a' THEN 'Activo' ELSE 'Inactivo' END as estadoNombre
		FROM municipios
		WHERE eliminado=1 AND estado='a' AND departamento_id='" . $obj->departamento->id . "' ORDER BY nombre ASC";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$cnx->Desconectar();
		return $cnx->json;
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
		WHERE u.tipo = 2 AND u.municipio_id = '" . $obj->municipio->id . "' AND u.eliminado = 1 
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
}//FIn Clase
