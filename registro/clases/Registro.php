<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Registro
{

	function guardar($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al realizar la operacion.';
		$cnx->Sentencia("START TRANSACTION");

		$id;

		$usrStr = $_SESSION["usuarioLogin"];
		if (($obj->id != '0' || $obj->id != 0) && ($obj->lider != $usrStr[0]["documento"]) && ($obj->informacion->personal->documento != $usrStr[0]["documento"]) && ($usrStr[0]["tipo"] == 2)) {
			$mensaje = 'El lider logueado no tiene permisos para realizar cambios al registro.';
			return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		if ($obj->tab == 1 || $obj->tab == '1') {
			$documento = $obj->informacion->personal->documento;
			$primerNombre = utf8_decode($obj->informacion->personal->primerNombre);
			$primerNombre = strtoupper($primerNombre);
			$segundoNombre = utf8_decode($obj->informacion->personal->segundoNombre);
			$segundoNombre = strtoupper($segundoNombre);
			$primerApellido = utf8_decode($obj->informacion->personal->primerApellido);
			$primerApellido = strtoupper($primerApellido);
			$segundoApellido = utf8_decode($obj->informacion->personal->segundoApellido);
			$segundoApellido = strtoupper($segundoApellido);
			$fechaNacimiento = $obj->informacion->personal->fechaNacimiento;
			$grupoSanguineo = $obj->informacion->personal->grupoSanguineo->id;
			$sexo = $obj->informacion->personal->sexo->id;
			$orientacionSexual = $obj->informacion->personal->orientacionSexual->id;
			$estadoCivil = $obj->informacion->personal->estadoCivil->id;
			$correoElectronico = $obj->informacion->personal->correoElectronico;
			$numeroCelular = $obj->informacion->personal->numeroCelular;
			$numeroWhatsapp = $obj->informacion->personal->numeroWhatsapp;
			$foto = $obj->informacion->personal->foto;

			if ($obj->id == '0' || $obj->id == 0) {

				$consulta = "SELECT count(*) as cantidad FROM informacion_personal WHERE documento='" . $documento . "' ";
				$cnx->Consultar($consulta);
				$row = mysqli_fetch_array($cnx->resultado);
				$existe = $row['cantidad'];

				if ($existe == 0) {
					$sql = "insert into informacion_personal (documento,primer_nombre,segundo_nombre,primer_apellido,segundo_apellido,fecha_nacimiento,grupo_sanguineo_id,sexo_id,orientacion_sexual_id,estado_civil_id,correo_electronico,numero_celular,numero_whatsapp,lider,fotografia) values ('" . $documento . "','" . $primerNombre . "','" . $segundoNombre . "','" . $primerApellido . "','" . $segundoApellido . "','" . $fechaNacimiento . "','" . $grupoSanguineo . "','" . $sexo . "','" . $orientacionSexual . "','" . $estadoCivil . "','" . $correoElectronico . "','" . $numeroCelular . "','" . $numeroWhatsapp . "','" . $obj->lider . "','" . $foto . "')";
					if (!$cnx->Sentencia($sql)) {
						$num_error = $num_error + 1;
						$consola = $consola . ' Error al guardar';
					}

					$consulta2 = "SELECT id FROM informacion_personal WHERE documento='" . $documento . "' limit 1 ";
					$cnx->Consultar($consulta2);
					$row = mysqli_fetch_array($cnx->resultado);
					$id = $row['id'];
				} else {
					$num_error = $num_error + 1;
					$mensaje = 'El registro que deseas guardar ya esta creado.';
				}
			} else {

				$id = $obj->id;

				$consulta = "SELECT count(*) as cantidad FROM informacion_personal WHERE documento='" . $documento . "' AND id != " . $obj->id . " ";
				$cnx->Consultar($consulta);
				$row = mysqli_fetch_array($cnx->resultado);
				$existe = $row['cantidad'];

				if ($existe == 0) {
					$sql = "UPDATE informacion_personal SET documento='" . $documento . "',primer_nombre='" . $primerNombre . "',segundo_nombre='" . $segundoNombre . "',primer_apellido='" . $primerApellido . "',segundo_apellido='" . $segundoApellido . "',fecha_nacimiento='" . $fechaNacimiento . "',grupo_sanguineo_id='" . $grupoSanguineo . "',sexo_id='" . $sexo . "',orientacion_sexual_id='" . $orientacionSexual . "',estado_civil_id='" . $estadoCivil . "',correo_electronico='" . $correoElectronico . "',numero_celular='" . $numeroCelular . "',numero_whatsapp='" . $numeroWhatsapp . "',lider='" . $obj->lider . "',fotografia='" . $foto . "' WHERE id=" . $obj->id . " ";
					if (!$cnx->Sentencia($sql)) {
						$num_error = $num_error + 1;
						$consola = $consola . ' Error al editar';
					}
				} else {
					$num_error = $num_error + 1;
					$mensaje = 'El registro que deseas guardar ya esta creado.';
				}
			}
		} elseif ($obj->tab == 2 || $obj->tab == '2') {
			$id = $obj->id;
			$municipio = $obj->informacion->ubicacion->municipio->id;
			$localidad = $obj->informacion->ubicacion->localidad->id;
			$comuna = $obj->informacion->ubicacion->comuna->id;
			$barrioVereda = $obj->informacion->ubicacion->barrioVereda->id;
			$tipoVivienda = $obj->informacion->ubicacion->tipoVivienda->id;
			$direccionResidencia = utf8_decode($obj->informacion->ubicacion->direccionResidencia);
			$direccionResidencia = strtoupper($direccionResidencia);

			$consulta = "SELECT count(*) as cantidad FROM informacion_ubicacion WHERE informacion_personal_id=" . $id . " ";
			$cnx->Consultar($consulta);
			$row = mysqli_fetch_array($cnx->resultado);
			$existe = $row['cantidad'];

			if ($existe == 0) {
				$sql = "insert into informacion_ubicacion (informacion_personal_id,municipio_id,direccion_residencia,barrio_vereda_id,tipo_vivienda_id,localidad_id,comuna_id) values ('" . $id . "','" . $municipio . "','" . $direccionResidencia . "','" . $barrioVereda . "','" . $tipoVivienda . "','" . $localidad . "','" . $comuna . "')";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al guardar';
				}
			} else {
				$sql = "UPDATE informacion_ubicacion SET municipio_id='" . $municipio . "',direccion_residencia='" . $direccionResidencia . "',barrio_vereda_id='" . $barrioVereda . "',tipo_vivienda_id='" . $tipoVivienda . "',localidad_id='" . $localidad . "',comuna_id='" . $comuna . "' WHERE informacion_personal_id='" . $id . "' ";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al editar';
				}
			}
		} elseif ($obj->tab == 3 || $obj->tab == '3') {
			$id = $obj->id;
			$estrato = $obj->informacion->demografica->estrato->id;
			$grupoSisben = $obj->informacion->demografica->grupoSisben->id;
			$servicioArray = $obj->informacion->demografica->servicio;
			$c = 0;
			$servicio = "";
			foreach ($servicioArray as $valor) {
				if ($c == 0) {
					$servicio = $valor;
				} else {
					$servicio = $servicio . "," . $valor;
				}
				$c++;
			}
			$discapacidad = $obj->informacion->demografica->discapacidad->id;
			$grupoEtnico = $obj->informacion->demografica->grupoEtnico->id;
			$nivelEducativo = $obj->informacion->demografica->nivelEducativo->id;
			$profesionArte = $obj->informacion->demografica->profesionArte->id;
			$ocupacion = $obj->informacion->demografica->ocupacion->id;

			$consulta = "SELECT count(*) as cantidad FROM informacion_demografica WHERE informacion_personal_id=" . $id . " ";
			$cnx->Consultar($consulta);
			$row = mysqli_fetch_array($cnx->resultado);
			$existe = $row['cantidad'];

			if ($existe == 0) {
				$sql = "insert into informacion_demografica (informacion_personal_id,estrado_id,grupo_sisben_id,servicio_id,discapacidad_id,grupo_etnico_id,nivel_educativo_id,profesion_id,ocupacion_id) values ('" . $id . "','" . $estrato . "','" . $grupoSisben . "','" . $servicio . "','" . $discapacidad . "','" . $grupoEtnico . "','" . $nivelEducativo . "','" . $profesionArte . "','" . $ocupacion . "')";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al guardar.';
				}
			} else {
				$sql = "UPDATE informacion_demografica SET estrado_id='" . $estrato . "',grupo_sisben_id='" . $grupoSisben . "',servicio_id='" . $servicio . "',discapacidad_id='" . $discapacidad . "',grupo_etnico_id='" . $grupoEtnico . "',nivel_educativo_id='" . $nivelEducativo . "',profesion_id='" . $profesionArte . "',ocupacion_id='" . $ocupacion . "' WHERE informacion_personal_id='" . $id . "' ";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al editar';
				}
			}
		} elseif ($obj->tab == 4 || $obj->tab == '4') {
			$id = $obj->id;
			$municipio = $obj->informacion->votacion->municipio->id;
			$puesto = $obj->informacion->votacion->puesto->id;
			$mesa = $obj->informacion->votacion->mesa->id;
			$intencionVoto = $obj->informacion->votacion->intencionVoto;

			$consulta = "SELECT count(*) as cantidad FROM informacion_lugar_votacion WHERE informacion_personal_id=" . $id . " ";
			$cnx->Consultar($consulta);
			$row = mysqli_fetch_array($cnx->resultado);
			$existe = $row['cantidad'];

			if ($existe == 0) {
				$sql = "insert into informacion_lugar_votacion (informacion_personal_id,municipio_id,puesto_id,mesa_id) 
				values ('" . $id . "','" . $municipio . "','" . $puesto . "','" . $mesa . "')";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al guardar';
				}

				foreach ($intencionVoto as $item) {
					if (($item->id == 0 || $item->id == '0') && ($item->eliminado == 1 || $item->eliminado == '1')) {
						$sql2 = "insert into intencion_voto (informacion_personal_id,candidato_id,eliminado) 
						values ('" . $id . "','" . $item->candidatoId . "','" . $item->eliminado . "')";
						if (!$cnx->Sentencia($sql2)) {
							$num_error = $num_error + 1;
							$consola = $consola . ' Error al guardar intencionVoto';
						}
					} elseif (($item->id != 0 || $item->id != '0') && ($item->eliminado == 0 || $item->eliminado == '0')) {
						$sql2 = "update intencion_voto set eliminado=0 where informacion_personal_id='" . $id . "' and candidato_id = '" . $item->candidatoId . "')";
						if (!$cnx->Sentencia($sql2)) {
							$num_error = $num_error + 1;
							$consola = $consola . ' Error al guardar intencionVoto';
						}
					}
				}
			} else {
				$sql = "UPDATE informacion_lugar_votacion SET municipio_id='" . $municipio . "',puesto_id='" . $puesto . "',mesa_id='" . $mesa . "' WHERE informacion_personal_id='" . $id . "' ";
				if (!$cnx->Sentencia($sql)) {
					$num_error = $num_error + 1;
					$consola = $consola . ' Error al editar';
				}

				foreach ($intencionVoto as $item) {
					if (($item->id == 0 || $item->id == '0') && ($item->eliminado == 1 || $item->eliminado == '1')) {
						$sql2 = "insert into intencion_voto (informacion_personal_id,candidato_id,eliminado) 
						values ('" . $id . "','" . $item->candidatoId . "','" . $item->eliminado . "')";
						if (!$cnx->Sentencia($sql2)) {
							$num_error = $num_error + 1;
							$consola = $consola . ' Error al guardar intencionVoto';
						}
					} elseif (($item->id != 0 || $item->id != '0') && ($item->eliminado == 0 || $item->eliminado == '0')) {
						$sql2 = "update intencion_voto set eliminado=0 where informacion_personal_id='" . $id . "' and candidato_id = '" . $item->candidatoId . "' ";
						if (!$cnx->Sentencia($sql2)) {
							$num_error = $num_error + 1;
							$consola = $consola . ' Error al editar intencionVoto';
						}
					}
				}
			}
		}

		if ($num_error == 0) {
			$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"Operacion realizada exitosamente.\", \"consola\": \"$consola\", \"id\": \"$id\"}";
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
			p.lider,
			p.fotografia,
			p2.primer_nombre as primer_nombre2,
			p2.segundo_nombre as segundo_nombre2,
			p2.primer_apellido as primer_apellido2,
			p2.segundo_apellido as segundo_apellido2,
			u.id as idUbicacion,
			u.municipio_id,m.nombre as municipioNombre,
			m.departamento_id,d.nombre as departamentoNombre,
			u.localidad_id,l.nombre as localidadNombre,
			u.comuna_id,c.nombre as comunaNombre,
			u.barrio_vereda_id,bv.nombre as barrioVeredaNombre,zr.nombre as zonaResidenciaNombre,
			u.tipo_vivienda_id,tv.nombre as tipoViviendaNombre,
			u.direccion_residencia,
            id.id as idDemografica,
            id.estrado_id,e.nombre as estratoNombre,
            id.grupo_sisben_id,gb.nombre as grupoSisbenNombre,
            id.servicio_id,
            id.discapacidad_id,ds.nombre  as discapacidadNombre,
            id.grupo_etnico_id,gen.nombre as grupoEtnicoNombre,
            id.nivel_educativo_id,ne.nombre as nivelEducativoNombre,
            id.profesion_id,ps.nombre as profesionNombre,
            id.ocupacion_id,os.nombre as ocupacionNombre,
            ilv.id as idLugarVotacion,
            mv.departamento_id as departamentoVotacionId,dv.nombre as departamentoVotacionNombre,
            ilv.municipio_id as municipioVotacionId, mv.nombre as municipioVotacionNombre,
            ilv.puesto_id,pv.nombre puestoNombre,pv.direccion as direccionNombre,
            ilv.mesa_id,ms.nombre as mesaNombre
		FROM informacion_personal p
		INNER JOIN grupos_sanguineos gs on p.grupo_sanguineo_id = gs.id
		INNER JOIN sexos s on p.sexo_id = s.id
		INNER JOIN generos g on p.orientacion_sexual_id = g.id
		INNER JOIN estados_civil ec on p.estado_civil_id = ec.id
		LEFT JOIN informacion_personal p2 on p.lider = p2.documento
        LEFT JOIN informacion_ubicacion u on p.id = u.informacion_personal_id
        LEFT JOIN municipios m on u.municipio_id = m.id
        LEFT join departamentos d on m.departamento_id = d.id
        LEFT JOIN localidades l on u.localidad_id = l.id
        LEFT JOIN comunas c on u.comuna_id = c.id
        LEFT JOIN barrios_veredas bv on u.barrio_vereda_id = bv.id
        LEFT JOIN zonas_residencia zr on bv.zona_residencia_id = zr.id
        LEFT JOIN tipos_vivienda tv on u.tipo_vivienda_id = tv.id
        LEFT JOIN informacion_demografica id on p.id = id.informacion_personal_id
        LEFT JOIN estratos e on id.estrado_id = e.id
        LEFT JOIN grupos_sisben gb on id.grupo_sisben_id = gb.id
        LEFT JOIN discapacidades ds on id.discapacidad_id = ds.id
        LEFT JOIN grupos_etnicos gen on id.grupo_etnico_id = gen.id
        LEFT JOIN niveles_educativos ne on id.nivel_educativo_id = ne.id
        LEFT JOIN ocupaciones os on id.ocupacion_id = os.id
        LEFT JOIN profesiones ps on id.profesion_id = ps.id
        LEFT JOIN informacion_lugar_votacion ilv on p.id = ilv.informacion_personal_id
        LEFT join municipios mv on ilv.municipio_id = mv.id
        LEFT JOIN departamentos dv on mv.departamento_id = dv.id
        LEFT JOIN puestos_votacion pv on ilv.puesto_id = pv.id
        LEFT JOIN mesas_votacion ms on ilv.mesa_id = ms.id
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
			$nb->lider = $resultado[$i]["lider"];
			$nb->nombreLider = $resultado[$i]["primer_nombre2"] . ' ' . $resultado[$i]["segundo_nombre2"] . ' ' . $resultado[$i]["primer_apellido2"] . ' ' . $resultado[$i]["segundo_apellido2"];
			if ($resultado[$i]["fotografia"] == null || $resultado[$i]["fotografia"] == '') {
				$nb->foto = 'dist/img/user.jpg';
				$nb->nombreFoto = '';
			} else {
				$nb->foto = 'imagenes/' . $resultado[$i]["fotografia"];
				$nb->nombreFoto = $resultado[$i]["fotografia"];
			}

			if ($resultado[$i]["idUbicacion"] == "") {
				$resultado[$i]["departamento_id"] = null;
				$resultado[$i]["departamentoNombre"] = null;
				$resultado[$i]["tipo_vivienda_id"] = null;
				$resultado[$i]["tipoViviendaNombre"] = null;
			}

			$nb->departamento = new stdClass();
			$nb->departamento->id = $resultado[$i]["departamento_id"];
			$nb->departamento->nombre = $resultado[$i]["departamentoNombre"];

			$nb->municipio = new stdClass();
			$nb->municipio->id = $resultado[$i]["municipio_id"];
			$nb->municipio->nombre = $resultado[$i]["municipioNombre"];

			$nb->localidad = new stdClass();
			$nb->localidad->id = $resultado[$i]["localidad_id"];
			$nb->localidad->nombre = $resultado[$i]["localidadNombre"];

			$nb->comuna = new stdClass();
			$nb->comuna->id = $resultado[$i]["comuna_id"];
			$nb->comuna->nombre = $resultado[$i]["comunaNombre"];

			$nb->barrioVereda = new stdClass();
			$nb->barrioVereda->id = $resultado[$i]["barrio_vereda_id"];
			$nb->barrioVereda->nombre = $resultado[$i]["barrioVeredaNombre"];
			$nb->barrioVereda->zonaResidencia = $resultado[$i]["zonaResidenciaNombre"];

			$nb->tipoVivienda = new stdClass();
			$nb->tipoVivienda->id = $resultado[$i]["tipo_vivienda_id"];
			$nb->tipoVivienda->nombre = $resultado[$i]["tipoViviendaNombre"];

			$nb->direccionResidencia = $resultado[$i]["direccion_residencia"];

			if ($resultado[$i]["idDemografica"] == "") {
				$resultado[$i]["estrado_id"] = null;
				$resultado[$i]["estratoNombre"] = null;
				$resultado[$i]["grupo_sisben_id"] = null;
				$resultado[$i]["grupoSisbenNombre"] = null;
				$servicios = [];
			} else {

				$sql2 = "SELECT id,nombre FROM servicios_publicos WHERE id IN (" . $resultado[$i]["servicio_id"] . ")";
				if (!$cnx->Consultar($sql2)) {
					return false;
				}
				$resultado2 = $cnx->json;
				for ($m = 0; $m < count($resultado2); $m++) {
					$servicios[] = $resultado2[$m]["id"];
				}
			}

			$nb->estrato = new stdClass();
			$nb->estrato->id = $resultado[$i]["estrado_id"];
			$nb->estrato->nombre = $resultado[$i]["estratoNombre"];

			$nb->grupoSisben = new stdClass();
			$nb->grupoSisben->id = $resultado[$i]["grupo_sisben_id"];
			$nb->grupoSisben->nombre = $resultado[$i]["grupoSisbenNombre"];

			$nb->servicio = $servicios;

			if ($resultado[$i]["discapacidad_id"] == "") {
				$resultado[$i]["discapacidad_id"] = null;
				$resultado[$i]["discapacidadNombre"] = null;
			}
			$nb->discapacidad = new stdClass();
			$nb->discapacidad->id = $resultado[$i]["discapacidad_id"];
			$nb->discapacidad->nombre = $resultado[$i]["discapacidadNombre"];

			if ($resultado[$i]["grupo_etnico_id"] == "") {
				$resultado[$i]["grupo_etnico_id"] = null;
				$resultado[$i]["grupoEtnicoNombre"] = null;
			}
			$nb->grupoEtnico = new stdClass();
			$nb->grupoEtnico->id = $resultado[$i]["grupo_etnico_id"];
			$nb->grupoEtnico->nombre = $resultado[$i]["grupoEtnicoNombre"];

			if ($resultado[$i]["nivel_educativo_id"] == "") {
				$resultado[$i]["nivel_educativo_id"] = null;
				$resultado[$i]["nivelEducativoNombre"] = null;
			}
			$nb->nivelEducativo = new stdClass();
			$nb->nivelEducativo->id = $resultado[$i]["nivel_educativo_id"];
			$nb->nivelEducativo->nombre = $resultado[$i]["nivelEducativoNombre"];

			if ($resultado[$i]["profesion_id"] == "") {
				$resultado[$i]["profesion_id"] = null;
				$resultado[$i]["profesionNombre"] = null;
			}
			$nb->profesionArte = new stdClass();
			$nb->profesionArte->id = $resultado[$i]["profesion_id"];
			$nb->profesionArte->nombre = $resultado[$i]["profesionNombre"];

			if ($resultado[$i]["ocupacion_id"] == "") {
				$resultado[$i]["ocupacion_id"] = null;
				$resultado[$i]["ocupacionNombre"] = null;
			}
			$nb->ocupacion = new stdClass();
			$nb->ocupacion->id = $resultado[$i]["ocupacion_id"];
			$nb->ocupacion->nombre = $resultado[$i]["ocupacionNombre"];

			if ($resultado[$i]["idLugarVotacion"] == "") {
				$resultado[$i]["departamentoVotacionId"] = null;
				$resultado[$i]["departamentoVotacionNombre"] = null;
				$resultado[$i]["municipioVotacionId"] = null;
				$resultado[$i]["municipioVotacionNombre"] = null;
				$resultado[$i]["puesto_id"] = null;
				$resultado[$i]["puestoNombre"] = null;
				$resultado[$i]["direccionNombre"] = '';
				$resultado[$i]["mesa_id"] = null;
				$resultado[$i]["mesaNombre"] = null;
			}

			$nb->departamentoVotacion = new stdClass();
			$nb->departamentoVotacion->id = $resultado[$i]["departamentoVotacionId"];
			$nb->departamentoVotacion->nombre = $resultado[$i]["departamentoVotacionNombre"];

			$nb->municipioVotacion = new stdClass();
			$nb->municipioVotacion->id = $resultado[$i]["municipioVotacionId"];
			$nb->municipioVotacion->nombre = $resultado[$i]["municipioVotacionNombre"];

			$nb->puesto = new stdClass();
			$nb->puesto->id = $resultado[$i]["puesto_id"];
			$nb->puesto->nombre = $resultado[$i]["puestoNombre"];
			$nb->puesto->direccion = $resultado[$i]["direccionNombre"];

			$nb->mesa = new stdClass();
			$nb->mesa->id = $resultado[$i]["mesa_id"];
			$nb->mesa->nombre = $resultado[$i]["mesaNombre"];

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarAlertas($obj)
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
			p.lider,
			u.direccion_residencia as idUbicacion,
            id.id as idDemografica,
            ilv.id as idLugarVotacion
		FROM informacion_personal p
        LEFT JOIN informacion_ubicacion u on p.id = u.informacion_personal_id
        LEFT JOIN informacion_demografica id on p.id = id.informacion_personal_id
        LEFT JOIN informacion_lugar_votacion ilv on p.id = ilv.informacion_personal_id
        WHERE (u.direccion_residencia is null or id.id is null or ilv.id is null) " . $where . "
        ORDER BY p.primer_nombre,p.segundo_nombre,p.primer_apellido,p.segundo_apellido ASC ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->persona = $resultado[$i]["documento"] . ' - ' . $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"] . ' ' . $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];

			$nb->ubicacion = "Si";
			if ($resultado[$i]["idUbicacion"] == "") {
				$nb->ubicacion = "No";
			}

			$nb->demografica = "Si";
			if ($resultado[$i]["idDemografica"] == "") {
				$nb->demografica = "No";
			}

			$nb->lugarVotacion = "Si";
			if ($resultado[$i]["idLugarVotacion"] == "") {
				$nb->lugarVotacion = "No";
			}

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarIntencionVoto($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$sql = "SELECT
			iv.id,
		    iv.candidato_id,c.nombre as candidatoNombre,
		    c.categoria_id,cp.nombre as categoriaNombre
		FROM intencion_voto iv
		INNER JOIN candidatos c on iv.candidato_id = c.id
		INNER JOIN categorias_politicas cp on c.categoria_id = cp.id
		WHERE iv.informacion_personal_id = '" . $obj->idPersona . "'
		AND iv.eliminado = 1 ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->categoriaId = $resultado[$i]["categoria_id"];
			$nb->categoriaNombre = $resultado[$i]["categoriaNombre"];
			$nb->candidatoId = $resultado[$i]["candidato_id"];
			$nb->candidatoNombre = $resultado[$i]["candidatoNombre"];
			$nb->eliminado = 1;

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarReporte($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$where = '';
		$usrStr = $_SESSION["usuarioLogin"];
		if ($usrStr[0]["tipo"] == 2 || $usrStr[0]["tipo"] == '2') {
			$where .= " AND (p.lider = '" . $usrStr[0]["documento"] . "' OR p.documento = '" . $usrStr[0]["documento"] . "') ";
		}

		$filtro = 0;
		if ($obj->documento != '' && $obj->documento != null) {
			$where .= " AND p.documento = '" . $obj->documento . "'  ";
			$filtro = 1;
		}
		if ($obj->nombre != '' && $obj->nombre != null) {
			$where .= " AND CONCAT(p.primer_nombre,' ', IF(p.segundo_nombre IS NULL OR p.segundo_nombre = '', '', CONCAT(p.segundo_nombre,' ')), p.primer_apellido, IF(p.segundo_apellido IS NULL OR p.segundo_apellido = '', '', CONCAT(' ',p.segundo_apellido))) like '%" . $obj->nombre . "%'  ";
			$filtro = 1;
		}
		if ($obj->lider != '' && $obj->lider != null) {
			$where .= " AND p.lider = '" . $obj->lider . "'  ";
			$filtro = 1;
		}
		if ($obj->lugar == 'D') {
			$where .= " AND m.departamento_id = '" . $obj->departamento . "'  ";
			$filtro = 1;
		}
		if ($obj->lugar == 'M') {
			$where .= " AND iu.municipio_id = '" . $obj->municipio . "'  ";
			$filtro = 1;
		}

		if ($filtro == 0) {
			$where .= " ORDER BY iu.id DESC LIMIT 10  ";
		} else {
			$where .= " ORDER BY iu.id DESC  ";
		}

		$sql = "SELECT 
			p.id,
			p.documento,
			p.primer_nombre,
			p.segundo_nombre,
			p.primer_apellido,
			p.segundo_apellido,
			p.fecha_nacimiento,
			m.nombre as municipioNombre,
			d.nombre as departamentoNombre,
			lider.documento as documentoLider,
			lider.primer_nombre as primerNombreLider,
			lider.segundo_nombre as segundoNombreLider,
			lider.primer_apellido as primerApellidoLider,
			lider.segundo_apellido as segundoApellidoLider
		FROM informacion_personal p
		LEFT JOIN informacion_ubicacion iu ON p.id = iu.informacion_personal_id
		LEFT JOIN informacion_demografica id on p.id = id.informacion_personal_id
        LEFT JOIN informacion_lugar_votacion ilv on p.id = ilv.informacion_personal_id
		LEFT JOIN municipios m on iu.municipio_id = m.id
		LEFT JOIN informacion_personal lider on p.lider = lider.documento
        LEFT JOIN departamentos d on m.departamento_id = d.id
		WHERE p.documento IS NOT NULL
		" . $where . "
        ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"];
			$nb->apellido = $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];

			if ($resultado[$i]["municipioNombre"] == NULL || $resultado[$i]["municipioNombre"] == 'NULL' || $resultado[$i]["municipioNombre"] == '') {
				$nb->lugar = '';
			} else {
				$nb->lugar = $resultado[$i]["municipioNombre"] . ", " . $resultado[$i]["departamentoNombre"];
			}

			if ($resultado[$i]["documentoLider"] == NULL || $resultado[$i]["documentoLider"] == 'NULL' || $resultado[$i]["documentoLider"] == '') {
				$nb->lider = '';
			} else {
				$nb->lider = $resultado[$i]["primerNombreLider"] . " " . $resultado[$i]["segundoNombreLider"] . " " . $resultado[$i]["primerApellidoLider"] . " " . $resultado[$i]["segundoApellidoLider"];
			}

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function consultarSeguidor($obj)
	{

		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$where = '';
		if ($obj->documento != '' && $obj->documento != null) {
			$where .= " AND p.documento = '" . $obj->documento . "'  ";
		}
		if ($obj->nombre != '' && $obj->nombre != null) {
			$where .= " AND CONCAT(p.primer_nombre,' ', IF(p.segundo_nombre IS NULL OR p.segundo_nombre = '', '', CONCAT(p.segundo_nombre,' ')), p.primer_apellido, IF(p.segundo_apellido IS NULL OR p.segundo_apellido = '', '', CONCAT(' ',p.segundo_apellido))) like '%" . $obj->nombre . "%'  ";
		}
		if ($obj->lider != '' && $obj->lider != null) {
			$where .= " AND p.lider = '" . $obj->lider . "'  ";
		}
		if ($obj->lugar == 'D') {
			$where .= " AND m.departamento_id = '" . $obj->departamento . "'  ";
		}
		if ($obj->lugar == 'M') {
			$where .= " AND iu.municipio_id = '" . $obj->municipio . "'  ";
		}

		$sql = "SELECT 
			p.id,
			p.documento,
			p.primer_nombre,
			p.segundo_nombre,
			p.primer_apellido,
			p.segundo_apellido,
			p.fecha_nacimiento,
			m.nombre as municipioNombre,
			d.nombre as departamentoNombre,
			lider.documento as documentoLider,
			lider.primer_nombre as primerNombreLider,
			lider.segundo_nombre as segundoNombreLider,
			lider.primer_apellido as primerApellidoLider,
			lider.segundo_apellido as segundoApellidoLider
		FROM informacion_personal p
		LEFT JOIN informacion_ubicacion iu ON p.id = iu.informacion_personal_id
		LEFT JOIN informacion_demografica id on p.id = id.informacion_personal_id
        LEFT JOIN informacion_lugar_votacion ilv on p.id = ilv.informacion_personal_id
		LEFT JOIN municipios m on iu.municipio_id = m.id
		LEFT JOIN informacion_personal lider on p.lider = lider.documento
        LEFT JOIN departamentos d on m.departamento_id = d.id
		WHERE (iu.direccion_residencia is null or id.id is null or ilv.id is null)
		" . $where . "
        ORDER BY iu.id DESC ";

		if (!$cnx->Consultar($sql)) {
			return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->nombre = $resultado[$i]["primer_nombre"] . ' ' . $resultado[$i]["segundo_nombre"];
			$nb->apellido = $resultado[$i]["primer_apellido"] . ' ' . $resultado[$i]["segundo_apellido"];

			if ($resultado[$i]["municipioNombre"] == NULL || $resultado[$i]["municipioNombre"] == 'NULL' || $resultado[$i]["municipioNombre"] == '') {
				$nb->lugar = '';
			} else {
				$nb->lugar = $resultado[$i]["municipioNombre"] . ", " . $resultado[$i]["departamentoNombre"];
			}

			if ($resultado[$i]["documentoLider"] == NULL || $resultado[$i]["documentoLider"] == 'NULL' || $resultado[$i]["documentoLider"] == '') {
				$nb->lider = '';
			} else {
				$nb->lider = $resultado[$i]["primerNombreLider"] . " " . $resultado[$i]["segundoNombreLider"] . " " . $resultado[$i]["primerApellidoLider"] . " " . $resultado[$i]["segundoApellidoLider"];
			}

			$listado[] = $nb;
		}

		$cnx->Desconectar();
		return $listado;
	}

	function asignarLider($obj)
	{
		$cnx = new cls_config();
		if (!$cnx->Conectar()) {
			return false;
		}

		$num_error = 0;
		$mensaje = 'Error al asignar el lider';
		$cnx->Sentencia("START TRANSACTION");

		$sql = "UPDATE informacion_personal SET lider='" . $obj->lider->documento . "' WHERE id=" . $obj->id . " ";
		if (!$cnx->Sentencia($sql)) {
			$num_error = $num_error + 1;
			$consola = $consola . ' Error al editar';
		}

		if ($num_error == 0) {
			$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El lider se asigno correctamente\", \"consola\": \"$consola\"}";
		} else {
			$cnx->Sentencia("ROLLBACK");
			return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		$cnx->Desconectar();
	}
}//FIn Clase
