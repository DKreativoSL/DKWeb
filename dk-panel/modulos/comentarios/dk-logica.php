<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	
	switch ($accion) 
	{
		case 'leeComentario':
		
			$idComentario = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT c.*, u.nombre
			FROM comentarios AS c
			LEFT JOIN usuarios AS u ON u.id = c.idUsuario 
			WHERE c.id = ". $idComentario."  
			AND c.idsitioweb = ".$idSitioWeb;
			
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
					
		case 'listaComentarios':
			$consulta = "
			SELECT c.*, a.titulo
			FROM comentarios AS c
			LEFT JOIN articulos AS a ON a.id = c.idArticulo
			WHERE idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-2x fa-trash-o\"></i></a>';
				$responder = '<a href=\"#\" onclick=\"responder('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-reply\"></i></a>';
				
				if ($row['estado'] == 1) { //Estado publicado
					$despublicar = '<a href=\"#\" onclick=\"cambiarEstado('.$row['id'].',\'0\')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-minus-circle\"></i></a>';	
				} else if ($row['estado'] == 0) { //Estado nopublicado
					$despublicar = '<a href=\"#\" onclick=\"cambiarEstado('.$row['id'].',\'1\')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-check-circle\"></i></a>';
				} else {
					$publicar = "";
					$despublicar = "";
				}
				
				$tabla.='{"id":"'.$row['id'].'","titulo":"'.$row['titulo'].'", "comentario":"'.$row['comentario'].'","acciones":"'.$responder.$despublicar.$edita.$borra.'"},';		

			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
			break;
			
		case 'elimina':
			$idComentario = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "
			DELETE FROM comentarios 
			WHERE id='".$idComentario."'
			AND EXISTS(
				SELECT 1 
				FROM sitiosweb  
				WHERE id = ".$idSitioWeb.")";
					
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idComentario = mysqli_real_escape_string($conexion, $_POST['id']);	
		
			$mFechaCreacion = mysqli_real_escape_string($conexion, $_POST['fechaCreacion']);
			$mFechaPublicacion = mysqli_real_escape_string($conexion, $_POST['fechaPublicacion']);
			$mComentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
			$mEstado = mysqli_real_escape_string($conexion, $_POST['estado']);
		
			$consulta = "
			UPDATE comentarios SET
			fechaCreacion='".$mFechaCreacion."',
			fechaPublicacion='".$mFechaPublicacion."',
			comentario='".$mComentario."',
			estado=".$mEstado." 
			WHERE id=".$idComentario."
			AND EXISTS(
				SELECT 1 
				FROM sitiosweb  
				WHERE id = ".$idSitioWeb.")";
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};
			
		break;
		case 'cambiarEstado':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idComentario = mysqli_real_escape_string($conexion, $_POST['id']);	
			$estado = mysqli_real_escape_string($conexion, $_POST['estado']);
		
			if ($estado == 1) { //Estado publicado
				$fechaPublicacion = date("Y-m-d h:i:s");
				$sql_publicacion = ", fechaPublicacion = '".$fechaPublicacion."'";	
			} else {
				$sql_publicacion = "";
			}
		
			$consulta = "
			UPDATE comentarios SET
			estado=".$estado." ".
			$sql_publicacion.
			"WHERE id=".$idComentario."
			AND EXISTS(
				SELECT 1 
				FROM sitiosweb  
				WHERE id = ".$idSitioWeb.")";
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};
			
		break;
		case 'responderComentario':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idComentario = mysqli_real_escape_string($conexion, $_POST['id']);	
			$comentario = mysqli_real_escape_string($conexion, $_POST['comentario']);
			
			
			//Obtengo la información del comentario padre
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT c.*, u.nombre
			FROM comentarios AS c
			LEFT JOIN usuarios AS u ON u.id = c.idUsuario 
			WHERE c.id = ". $idComentario."  
			AND c.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);

			$infoComentario = array();
			$i=0;
			while($row = mysqli_fetch_array($registro))
			{
				$infoComentario[$i] = $row;
				$i++;
			}
			
			
			$fechaCreacion = date("Y-m-d h:i:s");
		
			$sql_insert = "
			INSERT INTO comentarios SET
			idSitioWeb = ".$idSitioWeb.", 
			idUsuario = ".$idUsuario.", 
			idArticulo = ".$infoComentario[0]['idArticulo'].",
			idPadreComentario = ".$idComentario.",
			comentario = '".$comentario."',
			fechaCreacion = '".$fechaCreacion."',
			fechaPublicacion = '".$fechaCreacion."',
			estado = 1";

			$retorno = mysqli_query($conexion, $sql_insert);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};
			
		break;
	}
?>
