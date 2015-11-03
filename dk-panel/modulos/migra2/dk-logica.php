<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	switch ($accion) 
	{
		
		case 'cargaDatos':
			$servidor = mysqli_real_escape_string($conexion, $_POST['servidor']);
			$usuario = mysqli_real_escape_string($conexion, $_POST['usuario']);
			$bbdd = mysqli_real_escape_string($conexion, $_POST['bbdd']);
			$pass = mysqli_real_escape_string($conexion, $_POST['pass']);
		
			//lanzo la conexión una vez
			$conexionRemota = mysqli_connect($servidor,  $usuario, $pass, $bbdd);
			
			$consulta = "SELECT * FROM secciones";
			$registro = mysqli_query($conexionRemota, $consulta);

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
					
		
		case 'leeArticulo':
		
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "SELECT articulos.id, articulos.idSeccion, articulos.titulo, articulos.subtitulo, articulos.fecha, articulos.cuerpo, articulos.cuerporesumen, articulos.orden, articulos.imagen, articulos.archivo, articulos.url, articulos.campoExtra FROM articulos, secciones, sitiosweb where articulos.id = ". $idArticulo." AND secciones.id = articulos.idseccion and secciones.idsitioweb = ".$idSitioWeb;
			
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
					
		case 'listaArticulos':		
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			$consulta = "SELECT DISTINCT articulos.id, articulos.idSeccion, articulos.titulo FROM articulos, secciones, sitiosweb WHERE idSeccion = ".$idSeccion." AND secciones.id = articulos.idseccion and secciones.idsitioweb = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\"><img border=\"0px\" src=\"./imgs/editar.png\" alt=\"Modificar\"/></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><img border=\"0px\" src=\"./imgs/borrar.png\" alt=\"Eliminar\"/></a>';
				
				$tabla.='{"id":"'.$row['id'].'","titulo":"'.$row['titulo'].'","acciones":"'.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
			break;
			
		case 'elimina':
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "delete from articulos where id='".$idArticulo."' AND EXISTS(SELECT 1 FROM secciones, sitiosweb WHERE secciones.id = articulos.idseccion and secciones.idsitioweb = ".$idSitioWeb.")";
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);	
		
			$mtitulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
			$msubtitulo = mysqli_real_escape_string($conexion, $_POST['subtitulo']);
			$mfecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
			$mcuerpo = mysqli_real_escape_string($conexion, $_POST['cuerpo']);
			$mcuerpoResumen = mysqli_real_escape_string($conexion, $_POST['cuerpoResumen']);
			$mimagen = mysqli_real_escape_string($conexion, $_POST['imagen']);
			$marchivo = mysqli_real_escape_string($conexion, $_POST['archivo']);
			$murl = mysqli_real_escape_string($conexion, $_POST['url']);
			$mcampoExtra = mysqli_real_escape_string($conexion, $_POST['campoExtra']);			
			$morden = mysqli_real_escape_string($conexion, $_POST['orden']);
			$mseccion = intval($_POST['seccion']);
		
			$consulta = "UPDATE articulos SET titulo='".$mtitulo."', subtitulo='".$msubtitulo."', fecha='".$mfecha."', cuerpo='".$mcuerpo."', cuerpoResumen='".$mcuerpoResumen."', imagen='".$mimagen."', archivo='".$marchivo."', url='".$murl."', campoExtra='".$mcampoExtra."', orden='".$morden."', idSeccion=".$mseccion." WHERE id=".$idArticulo." AND EXISTS(SELECT 1 FROM secciones, sitiosweb WHERE secciones.id = articulos.idseccion and secciones.idsitioweb = ".$idSitioWeb.")";			

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};
			
		break;
		
	case 'inserta':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
		
			$mtitulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
			$msubtitulo = mysqli_real_escape_string($conexion, $_POST['subtitulo']);
			$mfecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
			$mcuerpo = mysqli_real_escape_string($conexion, $_POST['cuerpo']);
			$mcuerpoResumen = mysqli_real_escape_string($conexion, $_POST['cuerpoResumen']);
			$mimagen = mysqli_real_escape_string($conexion, $_POST['imagen']);
			$marchivo = mysqli_real_escape_string($conexion, $_POST['archivo']);
			$murl = mysqli_real_escape_string($conexion, $_POST['url']);
			$mcampoExtra = mysqli_real_escape_string($conexion, $_POST['campoExtra']);	
			
			$morden = mysqli_real_escape_string($conexion, $_POST['orden']);
			$mseccion = intval($_POST['seccion']);
		
			$consulta = "INSERT INTO articulos (titulo, subtitulo, fecha, cuerpo, cuerporesumen, imagen, archivo, url, campoExtra, idSeccion, orden) VALUES ('".$mtitulo."','".$msubtitulo."','".$mfecha."','".$mcuerpo."','".$mcuerpoResumen."','".$mimagen."','".$marchivo."','".$murl."','".$mcampoExtra."','".$mseccion."','".$morden."')";

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
