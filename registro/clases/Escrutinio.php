<?php
@session_start();
//Conexión a datos
require_once('../conexion/cls_config.php');

//Manejo de datos
class Escrutinio{


	function guardar($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
		$mensaje = 'Error al guardar el registro';  
	    $cnx->Sentencia("START TRANSACTION");

	    $fecha = date('Y-m-d');

	    $consulta="SELECT count(*) as cantidad FROM escrutinio WHERE eliminado=1 AND estado='a' AND fecha='".$fecha."' AND puesto_id='".$obj->puesto."' AND mesa_id='".$obj->mesa."' AND categoria_id='".$obj->categoria."' AND candidato_id='".$obj->candidato."' ";
		$cnx->Consultar($consulta);
		$row=mysqli_fetch_array($cnx->resultado);
		$existe=$row['cantidad'];

		if($existe == 0){
			$sql="insert into escrutinio (fecha,puesto_id,mesa_id,categoria_id,candidato_id,
			votos,estado,eliminado) values ('".$fecha."','".$obj->puesto."','".$obj->mesa."','".$obj->categoria."','".$obj->candidato."','".$obj->votos."','a',1)";
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

		$condicion = "";
		if($obj->puesto != '' || $obj->puesto != null){
			$condicion = $condicion." AND es.puesto_id=".$obj->puesto;
		}
		if($obj->mesa != '' || $obj->mesa != null){
			$condicion = $condicion." AND es.mesa_id=".$obj->mesa;
		}
		if($obj->categoria != '' || $obj->categoria != null){
			$condicion = $condicion." AND es.categoria_id=".$obj->categoria;
		}
		if($obj->candidato != '' || $obj->candidato != null){
			$condicion = $condicion." AND es.candidato_id=".$obj->candidato;
		}

		$sql = "SELECT es.id,es.fecha,es.votos,
		pv.nombre as puestoNombre,
		mv.nombre as mesaNombre,
		cp.nombre as categoriaNombre,
		c.nombre as candidatoNombre,
		(select count(*) as cantidad 
			from (select puesto_id,mesa_id from escrutinio 
					where eliminado=1 AND estado='a' 
					group by puesto_id,mesa_id) as vista
			) as totalMesas
		FROM escrutinio es
		INNER JOIN puestos_votacion pv on es.puesto_id = pv.id
		INNER JOIN mesas_votacion mv on es.mesa_id = mv.id
		INNER JOIN categorias_politicas cp on es.categoria_id = cp.id
		INNER JOIN candidatos c on es.candidato_id = c.id
		WHERE es.eliminado=1 AND es.estado='a' ".$condicion." ORDER BY pv.nombre,mv.nombre,cp.nombre,c.nombre ASC";

		if (!$cnx->Consultar($sql)){
            return false;
		}

		$resultado = $cnx->json;
		$listado = array();
		for ($i = 0; $i < count($resultado); $i++) {

			$nb = new stdClass();
			$nb->id = $resultado[$i]["id"];
			$nb->fecha = $resultado[$i]["fecha"];
			$nb->puestoNombre = $resultado[$i]["puestoNombre"];
			$nb->mesaNombre = $resultado[$i]["mesaNombre"];
			$nb->categoriaNombre = $resultado[$i]["categoriaNombre"];
			$nb->candidatoNombre = $resultado[$i]["candidatoNombre"];
			$nb->votos = $resultado[$i]["votos"];
			$nb->totalMesas = $resultado[$i]["totalMesas"];

			$listado [] = $nb;
		}

		$cnx->Desconectar();
        return $listado;

	}

	function eliminar($obj){
		$cnx=new cls_config();	
		if (!$cnx->Conectar()){
			return false;
		}
		
		$num_error=0;        
	    $cnx->Sentencia("START TRANSACTION");

	    $sql="UPDATE escrutinio SET eliminado=0 WHERE id=".$obj->id." ";
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
