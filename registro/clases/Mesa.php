<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Mesa{

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

	    $consulta="SELECT count(*) as cantidad FROM mesas_votacion WHERE eliminado=1 AND nombre='".$obj->nombre."' ";
		$cnx->Consultar($consulta);
		$row=mysqli_fetch_array($cnx->resultado);
		$existe=$row['cantidad'];

		if($existe == 0){
			$sql="insert into mesas_votacion (nombre,estado,eliminado) 
			values ('".$obj->nombre."','a',1)";
			if (!$cnx->Sentencia($sql)){
				$num_error=$num_error+1;
				$consola=$consola.' Error al guardar';
			}
		}else{
			$num_error=$num_error+1;
			$mensaje='El registro que deseas guardar ya esta creado.';
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

		$sql = "SELECT b.id,b.nombre,b.estado,
		CASE WHEN b.estado='a' THEN 'ACTIVO' ELSE 'INACTIVO' END as estadoNombre
		FROM mesas_votacion b
		WHERE b.eliminado=1 ORDER BY cast(b.nombre as unsigned) ASC";

		if (!$cnx->Consultar($sql)){
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

			$listado [] = $nb;
		}

		$cnx->Desconectar();
        return $listado;

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

	    $consulta="SELECT count(*) as cantidad FROM mesas_votacion WHERE id!=".$obj->id." AND eliminado=1 AND nombre='".$obj->nombre."' ";
		$cnx->Consultar($consulta);
		$row=mysqli_fetch_array($cnx->resultado);
		$existe=$row['cantidad'];

		if($existe == 0){
		    $sql="UPDATE mesas_votacion SET nombre='".$obj->nombre."',estado='".$obj->estado."' WHERE id=".$obj->id." ";
			if (!$cnx->Sentencia($sql)){
				$num_error=$num_error+1;
				$consola=$consola.' Error al editar';
			}
		}else{
			$num_error=$num_error+1;
			$mensaje='El registro que deseas editar ya esta creado.';
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

	function eliminar($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
	    $cnx->Sentencia("START TRANSACTION");

	    $sql="UPDATE mesas_votacion SET eliminado=0 WHERE id=".$obj->id." ";
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

	function consultarPublico($obj) {

		$cnx = new cls_config();
		if (!$cnx->Conectar()){                
			return false;
		}

		$sql = "SELECT id,nombre,estado,
		CASE WHEN estado='a' THEN 'Activo' ELSE 'Inactivo' END as estadoNombre
		FROM mesas_votacion
		WHERE eliminado=1 AND estado='a' ORDER BY cast(nombre as unsigned) ASC";

		if (!$cnx->Consultar($sql)){
            return false;
		}
		
		$cnx->Desconectar();
        return $cnx->json;

	}
		
}//FIn Clase