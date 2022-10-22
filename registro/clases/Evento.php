<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Evento{

	function guardar($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
		$mensaje = 'Error al guardar el registro';  
	    $cnx->Sentencia("START TRANSACTION");

	    $obj->nombre=utf8_decode($obj->nombre);
	    $obj->nombre=strtoupper($obj->nombre);
	    $obj->descripcion=utf8_decode($obj->descripcion);
	    $obj->descripcion=strtoupper($obj->descripcion);

	    $usrStr = $_SESSION["usuarioLogin"];
	    $colaborador = $usrStr[0]["id"];

		$sql="insert into eventos (nombre,fecha_inicio,fecha_finalizacion,descripcion,estado,colaborador,eliminado) 
		values ('".$obj->nombre."','".$obj->fechaInicio."','".$obj->fechaFinalizacion."','".$obj->descripcion."','a','".$colaborador."',1)";
		if (!$cnx->Sentencia($sql)){
			$num_error=$num_error+1;
			$consola=$consola.' Error al guardar';
		}

		$consulta2="SELECT id FROM eventos WHERE colaborador='".$colaborador."' ORDER BY id DESC limit 1 ";
		$cnx->Consultar($consulta2);
		$row=mysqli_fetch_array($cnx->resultado);
		$id=$row['id'];

		foreach ($obj->personalApoyoLista as $item){
			$sql2="insert into personal_apoyo (evento_id,informacion_personal_id,tipo_personal_apoyo_id,estado,eliminado) 
			values ('".$id."','".$item->idPersona."','".$item->tipoId."','a',1)";
			if (!$cnx->Sentencia($sql2)){
				$num_error=$num_error+1;
				$consola=$consola.' Error al guardar personal_apoyo';
			}
		}

		if($num_error==0){
	   		$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se guardo correctamente\", \"consola\": \"$consola\"}";
		}else{
	   		$cnx->Sentencia("ROLLBACK");
	   		return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		$cnx->Desconectar();
	}

	function consultar($obj) {

		$cnx = new cls_config();
		if (!$cnx->Conectar()){                
			return false;
		}

		$obj->nombre=utf8_decode($obj->nombre);
	    $obj->nombre=strtoupper($obj->nombre);
	    $obj->nombrePersona=utf8_decode($obj->nombrePersona);
	    $obj->nombrePersona=strtoupper($obj->nombrePersona);

		$condicion;
		switch ($obj->estado) {
			case 'c':
				$condicion = " AND e.fecha_finalizacion >= CURDATE() AND (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1) <> (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1 AND pa.estado = 'i')";
			break;
			case 'e':
				$condicion = " AND e.fecha_finalizacion < CURDATE() AND (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1) <> (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1 AND pa.estado = 'i')";
			break;
			case 'r':
				$condicion = " AND (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1) = (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1 AND pa.estado = 'i')";
			break;
		}
		if($obj->nombre  != '' && $obj->nombre != null){
			$condicion = $condicion." AND e.nombre LIKE '%".$obj->nombre."%'";
		}
		if($obj->fechaDe  != '' && $obj->fechaDe != null){
			if($obj->fechaHasta  != '' && $obj->fechaHasta != null){
				$condicion = $condicion." AND e.fecha_inicio BETWEEN '".$obj->fechaDe."' AND '".$obj->fechaHasta."'";
			}else{
				$condicion = $condicion." AND e.fecha_inicio = '".$obj->fechaDe."'";
			}
		}
		if($obj->documento  != '' && $obj->documento != null){
			$condicion = $condicion." AND ip.documento = '".$obj->documento."'";
		}
		if($obj->nombrePersona  != '' && $obj->nombrePersona != null){
			$condicion = $condicion." AND CONCAT(ip.primer_nombre, ' ', ip.segundo_nombre, ' ', ip.primer_apellido, ' ', ip.segundo_apellido) LIKE '%".$obj->nombrePersona."%'";
		}

		$sql = "SELECT
			e.id,
		    e.nombre,
		    e.fecha_inicio,
		    e.fecha_finalizacion,
		    e.descripcion,
		    e.colaborador,
		    ip.documento,
		    CONCAT(ip.primer_nombre, ' ', ip.segundo_nombre, ' ', ip.primer_apellido, ' ', ip.segundo_apellido) AS colaboradorNombre,
		    CASE  
		    	WHEN (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1) = (SELECT COUNT(*) FROM personal_apoyo pa WHERE pa.evento_id = e.id AND pa.eliminado = 1 AND pa.estado = 'i') THEN 'REALIZADO' 
		    	WHEN e.fecha_finalizacion < CURDATE() THEN 'EJECUTADO'
		    	ELSE 'CREADO' 
		    END as estadoNombre
		FROM eventos e
		INNER JOIN usuarios u on e.colaborador = u.id
		INNER JOIN informacion_personal ip ON u.informacion_personal_id = ip.id
		WHERE e.eliminado = 1 ".$condicion."
		ORDER BY e.id DESC";

		if (!$cnx->Consultar($sql)){
            return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->nombre = $resultado[$i]["nombre"];
			$nb->fechaInicio = $resultado[$i]["fecha_inicio"];
			$nb->fechaFinalizacion = $resultado[$i]["fecha_finalizacion"];
			$nb->descripcion = $resultado[$i]["descripcion"];
			$nb->colaborador = $resultado[$i]["colaborador"];
			$nb->documento = $resultado[$i]["documento"];
			$nb->colaboradorNombre = $resultado[$i]["colaboradorNombre"];
			$nb->estadoNombre = $resultado[$i]["estadoNombre"];

			/*$nb->departamento = new stdClass();
			$nb->departamento->id = $resultado[$i]["departamentoId"];
			$nb->departamento->nombre = $resultado[$i]["departamentoNombre"];*/

			$listado [] = $nb;
		}

		$cnx->Desconectar();
        return $listado;

	}

	function consultarPersonalApoyo($obj) {

		$cnx = new cls_config();
		if (!$cnx->Conectar()){                
			return false;
		}

		$sql = "SELECT
			pa.id,
		    CONCAT(ip.documento, ' - ', ip.primer_nombre, ' ', ip.segundo_nombre, ' ', ip.primer_apellido, ' ', ip.segundo_apellido) AS persona,
		    tpa.nombre as tipoNombre,
		    CASE WHEN pa.estado='a' THEN 'CONTRATADO' ELSE 'REALIZADO' END as estadoNombre,
		    pa.estado
		FROM personal_apoyo pa
		INNER JOIN informacion_personal ip ON pa.informacion_personal_id = ip.id
		INNER JOIN tipos_personal_apoyo tpa ON pa.tipo_personal_apoyo_id = tpa.id
		WHERE pa.evento_id = ".$obj->evento."";

		if (!$cnx->Consultar($sql)){
            return false;
		}
		
		$cnx->Desconectar();
        return $cnx->json;

	}

	function editar($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
		$mensaje = 'Error al editar el registro';  
	    $cnx->Sentencia("START TRANSACTION");

	    $obj->nombre=utf8_decode($obj->nombre);
	    $obj->nombre=strtoupper($obj->nombre);
	    $obj->descripcion=utf8_decode($obj->descripcion);
	    $obj->descripcion=strtoupper($obj->descripcion);

	    $sql="UPDATE eventos SET nombre='".$obj->nombre."',fecha_inicio='".$obj->fechaInicio."',
	    fecha_finalizacion='".$obj->fechaFinalizacion."',descripcion='".$obj->descripcion."' WHERE id=".$obj->id." ";
		if (!$cnx->Sentencia($sql)){
			$num_error=$num_error+1;
			$consola=$consola.' Error al editar';
		}

		if($num_error==0){
	   		$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se edito correctamente\", \"consola\": \"$consola\"}";
		}else{
	   		$cnx->Sentencia("ROLLBACK");
	   		return "{\"resultado\": false, \"mensaje\": \"$mensaje\", \"consola\": \"$consola\"}";
		}

		$cnx->Desconectar();
	}

	function realizarPersonal($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
	    $cnx->Sentencia("START TRANSACTION");

	    $sql="UPDATE personal_apoyo SET estado='i' WHERE id=".$obj->id." ";
		if (!$cnx->Sentencia($sql)){
			$num_error=$num_error+1;
			$mensaje=$mensaje.' Error al eliminar';
		}

		if($num_error==0){
	   		$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se actualizo correctamente\", \"consola\": \"$mensaje\"}";
		}else{
	   		$cnx->Sentencia("ROLLBACK");
	   		return "{\"resultado\": false, \"mensaje\": \"Error al actualizar el registro\", \"consola\": \"$mensaje\"}";
		}

		$cnx->Desconectar();
	}

	function eliminar($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
	    $cnx->Sentencia("START TRANSACTION");

	    $sql="UPDATE eventos SET eliminado=0 WHERE id=".$obj->id." ";
		if (!$cnx->Sentencia($sql)){
			$num_error=$num_error+1;
			$mensaje=$mensaje.' Error al eliminar';
		}

		if($num_error==0){
	   		$cnx->Sentencia("COMMIT");
			return "{\"resultado\": true, \"mensaje\": \"El registro se elimino correctamente\", \"consola\": \"$mensaje\"}";
		}else{
	   		$cnx->Sentencia("ROLLBACK");
	   		return "{\"resultado\": false, \"mensaje\": \"Error al eliminar el registro\", \"consola\": \"$mensaje\"}";
		}

		$cnx->Desconectar();
	}
		
}//FIn Clase
