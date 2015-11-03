<?php
	session_start(); //inicializo sesión
	
	header("Content-Type: text/html;charset=utf-8");
	
	include("./../../conexion.php");
	include("functions.php");
	
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	switch ($accion) 
	{
		case 'obtenerSecciones':

			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$consulta = "
			SELECT *
			FROM secciones 
			WHERE id <> ".$idRegistro." 
			AND idsitioweb = ".$idSitioWeb."
			AND estado = 1;";
			
			$registro = mysqli_query($conexion, $consulta);
			$html = '';
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				$html .= '<option value="'.$row['id'].'">'.$row['nombre'].'</option>';
			}
			echo $html;
		break;	
		case 'tieneContenido':
			
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			//Comprobamos si tenemos secciones hijas
			$consulta = "
			SELECT id
			FROM secciones 
			WHERE seccion = ".$idRegistro." 
			AND idsitioweb = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);
			$infoSeccion = array();
			$i=0;
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC)) {
				$infoSeccion[$i] = $row;
				$i++;
			}
			
			//Comprobamos si tenemos articulos
			$consulta = "
			SELECT id
			FROM articulos 
			WHERE idSeccion = ".$idRegistro;
			$registro = mysqli_query($conexion, $consulta);
			$infoArticulos = array();
			$i=0;
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC)) {
				$infoArticulos[$i] = $row;
				$i++;
			}
			
			if ( (empty($infoSeccion) ) && ( empty($infoArticulos) ) ) {
				echo 'NO';
			} else {
				echo 'SI';
			}
		break;
		case 'duplicarSeccion':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
			
			$consulta = "
			SELECT *
			FROM secciones 
			WHERE id = ".$idRegistro." 
			AND idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$infoSeccion = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				$infoSeccion[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			$resultado = duplicarSeccion($tipo,$nombre,$infoSeccion,$conexion);
			echo $resultado;
		break;
		case 'cargaDuplicacion':		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT secciones.id, secciones.nombre
			FROM secciones 
			WHERE secciones.id = ".$idRegistro." 
			AND secciones.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			echo json_encode($tabla);			
		break;
		case 'leeRegistro':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "SELECT secciones.id, secciones.nombre, secciones.descripcion, secciones.seccion, secciones.tipo, secciones.orden, secciones.privada FROM secciones, sitiosweb WHERE secciones.id = ".$idRegistro." AND secciones.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
		break;
		case 'listaRegistros':		
			//$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			$consulta = "
			SELECT DISTINCT id, nombre, tipo, orden 
			FROM secciones
			WHERE idsitioweb = ".$idSitioWeb."
			AND estado = 1
			AND tipo = 3;";
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$duplicar = '<a href=\"#\" onclick=\"duplicar('.$row['id'].')\" class=\"iconDuplicar\"><i class=\"fa fa-2x fa-copy\"></i></a>';
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				$borra = '<a href=\"#\" data-toggle=\"modal\" data-target=\"#myModal\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-2x fa-trash-o\"></i></a>';
				
				//TEMAS
				$sql_temas = '
				SELECT count(id) as totalTemas
				FROM secciones
				WHERE seccion = '.$row['id'];
				
				$registroTemas = mysqli_query($conexion, $sql_temas);
				$totalTemas = 0;
				while($rowTemas = mysqli_fetch_array($registroTemas))
				{
					$totalTemas = $rowTemas['totalTemas'];
				}
				
				//ALUMNOS
				$sql_alumnos = '
				SELECT DISTINCT count(idUsuario) as totalAlumnos
				FROM cursoalumno
				WHERE idSeccion = '.$row['id'];
				
				$registroAlumnos = mysqli_query($conexion, $sql_alumnos);
				$totalAlumnos = 0;
				while($rowAlumnos = mysqli_fetch_array($registroAlumnos))
				{
					$totalAlumnos = $rowAlumnos['totalAlumnos'];
				}
				
				
				//Control, si un curso tiene usuarios asignados, no se puede eliminar
				if ($totalAlumnos > 0) {
					$borra = '';
				}
				
				$tabla.='{"id":"'.$row['id'].'",
				"nombre":"'.$row['nombre'].'",
				"temas":"'.$totalTemas.'",
				"alumnos":"'.$totalAlumnos.'",
				"acciones":"'.$duplicar.$edita.$borra.'"},';
						
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
		break;
		case 'eliminarTodo':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			//Obtenemos todas las secciones hijas y sus articulos
			$dataReturn = array();
			getAllSectionsWithData($dataReturn,$idRegistro,$conexion);
			
			//Eliminamos logicamente la seccion
			$sql_eliminarSeccion = '
			UPDATE secciones SET
			estado = 3
			WHERE id = ' . $idRegistro;
			$result = mysqli_query($conexion, $sql_eliminarSeccion);
			
			//Eliminamos logicamente los articulos de la seccion
			$sql_eliminarArticulo = '
			UPDATE articulos SET
			estado = 3
			WHERE idSeccion = ' . $idRegistro;
			$result = mysqli_query($conexion, $sql_eliminarArticulo);	
			
			//Eliminamos logicamente & recursivamente todas las secciones hijas y sus articulos
			eliminarSeccionesArticulosRecursivamente($dataReturn,$conexion);
			
			echo 'OK';
		break;
		case 'guarda':	

			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
		
			$consulta = "
			UPDATE secciones SET 
			nombre='".$nombre."', 
			descripcion='".$descripcion."'
			WHERE id='".$idRegistro."' 
			AND idSitioWeb = ".$idSitioWeb;		

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo $idRegistro;
			}else{
				echo "KO";
			};			
		break;
		
		case 'inserta':	
		//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos

			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
		
			$consulta = "
			INSERT INTO secciones (idSitioWeb, nombre, descripcion, tipo, estado) 
			VALUES ('".$idSitioWeb."','".$nombre."','".$descripcion."','3',1)";

			$retorno = mysqli_query($conexion, $consulta);
			
			//si ha insertado correctamente
			if ($retorno){
				//devuelvo el id recien creado para cargarlo en el documento actual por si pulsara otra vez modificar
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				echo $row[0];
			}else{
				echo "KO".$consulta;
			};
			
		break;
	}
?>
