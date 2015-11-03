<?php
	include("./../../dk-panel/conexion.php");

	$token = $_GET['token'];
	$dominio = $_SERVER['HTTP_HOST'];			
	
	//if ($dominio = "localhost" or $dominio = "dkweb.es") $dominio = "porfinsabado.com";
	
/*	$consulta = '
	SELECT id 
	FROM sitiosweb 
	WHERE dominio = "'.$dominio.'" 
	AND token = "'.$token.'"';
*/
	$consulta = '
	SELECT id 
	FROM sitiosweb 
	WHERE token = "'.$token.'"';
	
	$resultado = mysqli_query($conexion, $consulta);
	$registro = mysqli_fetch_array($resultado);
	
	if ($registro['id'] > 0){		
		//si el usuario es v�lido lo registro para guardarlo durante toda la sesi�n
		$idSitioWeb = $registro['id'];
		$accion = $_GET['accion'];
	}else{
		$idSitioWeb = "";
		echo "Sin acceso <br>"; //sino trae nada el registro digo que no es v�lido				
	}


	switch ($accion) {
		case "login":
			$email = $_GET['email'];
			$password = $_GET['password'];
		
			$consulta = "
			SELECT id, nombre, email 
			FROM usuarios
			WHERE email = '".$email."'
			AND clave = '".md5($password)."'
			LIMIT 1";
			$resultado = mysqli_query($conexion, $consulta);
			$registro = mysqli_fetch_array($resultado);
			
			if ($registro['id'] > 0){		
				$tabla.='{"id":"'.addslashes($registro['id']).'","nombre":"'.addslashes($registro['nombre']).'","email":"'.addslashes($registro['email']).'"}';
				echo $tabla;
			}else{
				$idSitioWeb = "";
				echo "KO"; //sino trae nada el registro digo que no es valido			
			}
		break;

		case "getPost":
		
			$idArticulo = $_GET['id'];
			$consulta = '
			SELECT id, titulo, subtitulo, fecha, fechaPublicacion, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
			FROM articulos 
			WHERE id = "'.$idArticulo.'"
			AND estado = 1';
			$resultado = mysqli_query($conexion, $consulta);
	
			$tabla = "";
			while($row = mysqli_fetch_array($resultado))
			{
				$tabla.='{"id":"'.addslashes($row['id']).'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","fechaPublicacion":"'.addslashes($row['fechaPublicacion']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
			}
			
			$tabla = substr($tabla, 0, strlen($tabla) - 1);
			echo $tabla;
		break;
		
		
		case "getSecciones":
		
			if (!isset($_GET['idPadre'])) {
				//Si no existe el idPadre, devolvemos todas las secciones de la web
				$consulta = '
				SELECT id, nombre, descripcion, seccion, tipo, privada, orden 
				FROM secciones 
				WHERE idSitioWeb = "'.$idSitioWeb.'"
				AND estado = 1';
			} else {
				//Devolvemos las secciones con un idPadre
				$idPadre = intval($_GET['idPadre']);
				
				$consulta = '
				SELECT id, nombre, descripcion, seccion, tipo, privada, orden 
				FROM secciones 
				WHERE idSitioWeb = "'.$idSitioWeb.'"
				AND seccion = '.$idPadre.'
				AND estado = 1';
			}
			$resultado = mysqli_query($conexion, $consulta);
	
			// recorremos el resultado y pintamos los nodos
			$tabla = ""; //array(); //creamos un array
	
			while($row = mysqli_fetch_array($resultado))
			{
				$tabla.='{"id":"'.$row['id'].'","nombre":"'.addslashes($row['nombre']).'","descripcion":"'.addslashes($row['descripcion']).'","seccion":"'.addslashes($row['seccion']).'","tipo":"'.addslashes($row['tipo']).'","privada":"'.addslashes($row['privada']).'","orden":"'.addslashes($row['orden']).'"},';
			}
			
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo "[".$tabla."]";
		break;

/*
		TOMA TODOS LOS ART�CULOS DE UNA SECCI�N
*/
		
		case "getSeccionPost":
			$seccion = $_GET['id'];
			$inicio = $_GET['inicio'];
			$limit = $_GET['cantidad'];
			$orden = $_GET['orden'];
			
			if ($orden == "asc" or $orden == "desc"){			
			}else{
				$orden = "asc";
				}
			
			if (!$inicio) $inicio = 0;
			if (!$limit) $limit = 100;
			
			//controlo primero el tipo y guardo el ID que corresponda a la secci�n
			$consulta = '
			SELECT id, tipo 
			FROM secciones 
			WHERE id = "'.$seccion.'" 
			AND idSitioWeb = "'.$idSitioWeb.'"
			AND estado = 1';
			
			$resultado = mysqli_query($conexion, $consulta);
			$registroSeccion = mysqli_fetch_array($resultado);
			
			//si la consulta trae algo
			if ($registroSeccion)
			{
				switch ($registroSeccion['tipo'])
				{
					case 0: //Tipo b�sico
						$consulta = '
						SELECT id, titulo, cuerpo, orden 
						FROM articulos 
						WHERE idSeccion = "'.$seccion.'"
						AND estado = 1 
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","cuerpo":"'.addslashes($row['cuerpo']).'","orden":"'.addslashes($row['orden']).'"},';
						}
	
					break;
					
					case 1: //tipo avanzado
						$consulta = '
						SELECT id, titulo, subtitulo, fecha, fechaPublicacion, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra 
						FROM articulos 
						WHERE idSeccion = "'.$seccion.'"
						AND estado = 1 
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","fechaPublicacion":"'.addslashes($row['fechaPublicacion']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
						}
	
					break;
							
					case 2: //tipo personalizado
						$consulta = '
						SELECT id, datos 
						FROM articulospersonalizado 
						WHERE idSeccion = "'.$seccion.'"
						AND estado = 1 
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'"datos:['.$datos.']},';
						}
						
					break;
				}
		
				$tabla = substr($tabla,0, strlen($tabla) - 1);
				
				echo '['.$tabla.']';
				
			} else {
				$consulta = '
				SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
				FROM articulos
				WHERE idSeccion IN (
					SELECT id 
					FROM secciones AS s
					WHERE s.idSitioWeb = "'.$idSitioWeb.'" 
					AND s.estado = 1
				)
				AND estado = 1
				ORDER BY id '.$orden.'
				LIMIT '.$inicio.','.$limit;
							
				$resultado = mysqli_query($conexion, $consulta);
				$tabla = ""; //creamos un array
			
				while($row = mysqli_fetch_array($resultado))
				{
					$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
				}
				
				$tabla = substr($tabla,0, strlen($tabla) - 1);
				
				echo '['.$tabla.']';
			}
			
		break;

/*
		TOMA TODOS LOS ART�CULOS DE UNA SECCI�N QUE TIENE SOLO IM�GENES
*/
		case "getSeccionPostImagen":
			$seccion = $_GET['id'];
			$inicio = $_GET['inicio'];
			$limit = $_GET['cantidad'];
			$orden = $_GET['orden'];
			
			if ($orden == "asc" or $orden == "desc"){			
			}else{
				$orden = "asc";
				}
			
			if (!$inicio) $inicio = 0;
			if (!$limit) $limit = 100;
			
			//controlo primero el tipo y guardo el ID que corresponda a la secci�n
			$consulta = '
			SELECT id, tipo 
			FROM secciones 
			WHERE id = "'.$seccion.'"
			AND estado = 1 
			AND idSitioWeb = "'.$idSitioWeb.'"';
			
			$resultado = mysqli_query($conexion, $consulta);
			$registroSeccion = mysqli_fetch_array($resultado);
			
			//si la consulta trae algo
			if ($registroSeccion)
			{
				switch ($registroSeccion['tipo'])
				{
					case 0: //Tipo b�sico
						$consulta = '
						SELECT id, titulo, cuerpo, orden 
						FROM articulos 
						WHERE imagen != "" 
						AND idSeccion = "'.$seccion.'"
						AND estado = 1 
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","cuerpo":"'.addslashes($row['cuerpo']).'","orden":"'.addslashes($row['orden']).'"},';
						}
	
					break;
					
					case 1: //tipo avanzado
						$consulta = '
						SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra 
						FROM articulos 
						WHERE imagen != "" 
						AND idSeccion = "'.$seccion.'" 
						AND estado = 1
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
						}
	
					break;
							
					case 2: //tipo personalizado
						$consulta = '
						SELECT id, datos 
						FROM articulospersonalizado 
						WHERE idSeccion = "'.$seccion.'" 
						AND estado = 1
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'"datos:['.$datos.']},';
						}
						
					break;
				}
		
				$tabla = substr($tabla,0, strlen($tabla) - 1);
				
				echo '['.$tabla.']';
				
			} else {
				$consulta = '
				SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
				FROM articulos
				WHERE imagen != ""
				AND idSeccion IN (
					SELECT id 
					FROM secciones AS s
					WHERE s.idSitioWeb = "'.$idSitioWeb.'" 
					AND s.estado = 1
				)
				AND estado = 1
				ORDER BY id '.$orden.'
				LIMIT '.$inicio.','.$limit;
					
				$resultado = mysqli_query($conexion, $consulta);
				$tabla = ""; //creamos un array
			
				while($row = mysqli_fetch_array($resultado))
				{
					$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
				}
				
				$tabla = substr($tabla,0, strlen($tabla) - 1);
				
				echo '['.$tabla.']';
			}
			
		break;

/*
		TOMA TODOS LOS ART�CULOS DE UNA SECCI�N QUE TIENE ALGO EN LA URL
*/
		case "getSeccionPostURL":
			$seccion = $_GET['id'];
			$inicio = $_GET['inicio'];
			$limit = $_GET['cantidad'];
			$orden = $_GET['orden'];
			
			if ($orden == "asc" or $orden == "desc"){			
			}else{
				$orden = "asc";
				}
			
			if (!$inicio) $inicio = 0;
			if (!$limit) $limit = 100;
			
			//controlo primero el tipo y guardo el ID que corresponda a la secci�n
			$consulta = '
			SELECT id, tipo 
			FROM secciones 
			WHERE id = "'.$seccion.'" 
			AND estado = 1
			AND idSitioWeb = "'.$idSitioWeb.'"';
			
			$resultado = mysqli_query($conexion, $consulta);
			$registroSeccion = mysqli_fetch_array($resultado);
			
			//si la consulta trae algo
			if ($registroSeccion)
			{
				switch ($registroSeccion['tipo'])
				{
					case 0: //Tipo b�sico
						$consulta = '
						SELECT id, titulo, cuerpo, orden 
						FROM articulos 
						WHERE url != "" 
						AND idSeccion = "'.$seccion.'" 
						AND estado = 1
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","cuerpo":"'.addslashes($row['cuerpo']).'","orden":"'.addslashes($row['orden']).'"},';
						}
	
					break;
					
					case 1: //tipo avanzado
						$consulta = '
						SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra 
						FROM articulos 
						WHERE url != "" 
						AND idSeccion = "'.$seccion.'"
						AND estado = 1 
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
						}
	
					break;
							
					case 2: //tipo personalizado
						$consulta = '
						SELECT id, datos 
						FROM articulospersonalizado 
						WHERE idSeccion = "'.$seccion.'" 
						AND estado = 1
						ORDER BY id '.$orden.' 
						LIMIT '.$inicio.','.$limit;
						$resultado = mysqli_query($conexion, $consulta);
						$tabla = ""; //creamos un array
				
						while($row = mysqli_fetch_array($resultado))
						{
							$tabla.='{"id":"'.$row['id'].'"datos:['.$datos.']},';
						}
						
					break;
				}
		
				$tabla = substr($tabla,0, strlen($tabla) - 1);
				
				echo '['.$tabla.']';
				
			} else { 
				$consulta = '
				SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
				FROM articulos
				WHERE url != ""
				AND idSeccion IN (
					SELECT id 
					FROM secciones AS s
					WHERE s.idSitioWeb = "'.$idSitioWeb.'" 
					AND s.estado = 1
				)
				AND estado = 1
				ORDER BY id '.$orden.'
				LIMIT '.$inicio.','.$limit;
							
				$resultado = mysqli_query($conexion, $consulta);
				$tabla = ""; //creamos un array
			
				while($row = mysqli_fetch_array($resultado))
				{
					$tabla.='{"id":"'.$row['id'].'","titulo":"'.addslashes($row['titulo']).'","subtitulo":"'.addslashes($row['subtitulo']).'","fecha":"'.addslashes($row['fecha']).'","cuerpo":"'.addslashes($row['cuerpo']).'","cuerpoResumen":"'.addslashes($row['cuerpoResumen']).'","orden":"'.addslashes($row['orden']).'","imagen":"'.addslashes($row['imagen']).'","archivo":"'.addslashes($row['archivo']).'","url":"'.addslashes($row['url']).'","campoExtra":"'.addslashes($row['campoExtra']).'"},';
				}
				
				$tabla = substr($tabla,0, strlen($tabla) - 1);
				
				echo '['.$tabla.']';
			}
			
		break;
		
		default:
			echo "Sigue esperando...";
		break;	
						
	}//fin switch

?>