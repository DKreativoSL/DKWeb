<?php
	session_start(); //inicializo sesión
	
	header("Content-Type: text/html;charset=utf-8");
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	switch ($accion) 
	{
		case 'obtenerSecciones':

			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			
			//Obtenemos la sección padre (si la tenemos)
			$consulta = "
			SELECT seccion FROM secciones 
			WHERE id = ".$idRegistro;
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row;
				$i++;
			}
			
			
			
			$consulta = "
			SELECT *
			FROM secciones 
			WHERE id <> ".$idRegistro." 
			AND idsitioweb = ".$idSitioWeb."
			AND estado = 1;";
			$registro = mysqli_query($conexion, $consulta);
			$html = '<option value="0">Sin sección padre</option>"';
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				if ($tabla[0]['seccion'] == $row['id']) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['nombre'].'</option>';
			}
			echo $html;
		break;	
		case 'listaSecciones':			
			$consulta = "
			SELECT * FROM secciones 
			WHERE idSitioWeb = ".$idSitioWeb."
			AND estado = 1;";
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
			AND estado = 3;";
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$recuperar = '<a href=\"#\" onclick=\"recuperar('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-undo fa-2x\"></i></a>';
				$recuperar .= '<button type=\"button\" id=\"btn_'.$row['id'].'\" style=\"display:none\" class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#myModal\">Recuperar</button>';
				
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				
				$tabla.='{"id":"'.$row['id'].'","nombre":"'.$row['nombre'].'","tipo":"'.$row['tipo'].'","orden":"'.$row['orden'].'","acciones":"'.$recuperar.$edita.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
		break;
		case 'recuperarSeccion':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$idToMove = mysqli_real_escape_string($conexion, $_POST['idToMove']);
						
			$sql_updateSeccion = '
			UPDATE secciones SET
			seccion = '.$idToMove.',
			estado = 1
			WHERE id = '.$idRegistro;
			
			$result = mysqli_query($conexion, $sql_updateSeccion);

			echo "OK";
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
		case 'guarda':	

			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
			$orden = mysqli_real_escape_string($conexion, $_POST['orden']);
			$privada = mysqli_real_escape_string($conexion, $_POST['privada']);
			$seccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			$tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
		
			$consulta = "
			UPDATE secciones SET 
			nombre='".$nombre."', 
			descripcion='".$descripcion."', 
			orden='".$orden."', 
			privada='".$privada."', 
			seccion='".$seccion."', 
			tipo='".$tipo."' 
			WHERE id='".$idRegistro."' 
			AND idSitioWeb = ".$idSitioWeb;			

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo $idRegistro;
			}else{
				echo "KO";
			};			
		break;
	}
?>
