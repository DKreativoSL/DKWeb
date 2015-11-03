<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	
	switch ($accion) 
	{
		

		//Traigo el formulario personalizado como se guardó en la sección				
		case 'cargaFormulario':
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT DISTINCT seccionesformulario.idSeccion, seccionesformulario.formulario 
			FROM seccionesformulario, secciones, sitiosweb 
			WHERE seccionesformulario.idSeccion = ".$idSeccion." 
			AND secciones.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);			
			$row = mysqli_fetch_array($registro);
			echo $row['formulario'];
			
			break;
			
		case 'leeArticulo':
		
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT DISTINCT articulospersonalizado.id, articulospersonalizado.idSeccion, articulospersonalizado.datos 
			FROM articulospersonalizado, secciones, sitiosweb 
			WHERE articulospersonalizado.id = ". $idArticulo." 
			AND secciones.id = articulospersonalizado.idseccion 
			AND secciones.idsitioweb = ".$idSitioWeb;
			
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulospersonalizado.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulospersonalizado.publicado = 0';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulospersonalizado.idUsuario IN ('.$where_in.')';
			}
			
			$registro = mysqli_query($conexion, $consulta);

			$row = mysqli_fetch_array($registro);

			echo base64_decode($row['datos']);
			
			break;
					
		case 'listaArticulos':		
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			$consulta = 
			"SELECT DISTINCT articulospersonalizado.id, articulospersonalizado.idSeccion, articulospersonalizado.datos 
			FROM articulospersonalizado, secciones, sitiosweb 
			WHERE articulospersonalizado.idSeccion = ".$idSeccion." 
			AND secciones.id = articulospersonalizado.idseccion 
			AND secciones.idsitioweb = ".$idSitioWeb."
			AND articulospersonalizado.estado IN (0, 1, 2)";
			
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulospersonalizado.idUsuario = '.$idUsuario;
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulospersonalizado.idUsuario IN ('.$where_in.')';
			}
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				//desgloso el json de datos y paso el primer valor
				$datos = json_decode(base64_decode($row['datos']), true);
				$principal = array_keys($datos);
				
				//$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\"><img border=\"0px\" src=\"./imgs/editar.png\" alt=\"Modificar\"/></a>';
				
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-2x fa-trash-o\"></i></a>';
				
				if (strlen($datos[$principal[0]]) > 100) {
					$tabla.='{"id":"'.$row['id'].'","principal":"'.addslashes(substr($datos[$principal[0]],0,100)).' [...]","acciones":"'.$edita.$borra.'"},';				
				}else{
					$tabla.='{"id":"'.$row['id'].'","principal":"'.addslashes($datos[$principal[0]]).'","acciones":"'.$edita.$borra.'"},';				
				}
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);

			echo '{"data":['.$tabla.']}';
			break;
			
		case 'elimina':
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "
			DELETE FROM articulospersonalizado 
			WHERE id='".$idArticulo."' 
			AND EXISTS(
				SELECT 1 
				FROM secciones, sitiosweb 
				WHERE secciones.id = articulospersonalizado.idseccion 
				AND secciones.idsitioweb = ".$idSitioWeb.")";
				
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulospersonalizado.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulospersonalizado.publicado = 0';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulospersonalizado.idUsuario IN ('.$where_in.')';
			}
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos		
			$mid = mysqli_real_escape_string($conexion, $_POST['id']);
			$mdatos = base64_encode($_POST["datos"]);
		
			//actualizo y compruebo que la sección sea del mismo sitio web
			$consulta = "
			UPDATE articulospersonalizado 
			SET datos = '".$mdatos."' 
			WHERE id = '".$mid."' 
			AND EXISTS(
				SELECT 1 
				FROM secciones, sitiosweb 
				WHERE secciones.id = articulospersonalizado.idseccion 
				AND secciones.idsitioweb = ".$idSitioWeb.")";
				
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulospersonalizado.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulospersonalizado.publicado = 0';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulospersonalizado.idUsuario IN ('.$where_in.')';
			}
			
			//si ha actualizado correctamente
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO".$consulta;
			};
			
		break;
		
	case 'inserta':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos		
			$mid = mysqli_real_escape_string($conexion, $_POST['id']);
			$midSeccion = mysqli_real_escape_string($conexion, $_POST['idSeccion']);
			$mdatos = base64_encode($_POST["datos"]);
		
			$consulta = "
			INSERT INTO articulospersonalizado (idSeccion, idUsuario, datos, estado) 
			VALUES ('".$midSeccion."',".$idUsuario.",'".$mdatos."', 1)";
			
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
