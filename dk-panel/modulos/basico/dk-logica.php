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
		
		case 'obtenerCamposSeccion':
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['idSeccion']);
			
			$sql = '
			SELECT
			ch_CampoTitulo, 
			ch_CampoSubTitulo,
			ch_CampoCuerpo, 
			ch_CampoCuerpoAvance, 
			ch_CampoFechaPublicacion, 
			ch_CampoImagen, 
			ch_CampoArchivo, 
			ch_CampoURL, 
			ch_CampoCampoExtra
			FROM secciones WHERE id = ' . $idSeccion. ' LIMIT 1';
			$registro = mysqli_query($conexion, $sql);

			$infoSeccion = array();
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$infoSeccion = $row;
			}
			
			echo json_encode($infoSeccion);
		break;
		case 'duplicarArticulo':
			
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
						
			$consulta = "
			SELECT * 
			FROM articulos 
			WHERE id = ".$idArticulo;
			$registro = mysqli_query($conexion, $consulta);

			$infoArticulo = array();
			$i=0;
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$infoArticulo[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			if ($infoArticulo[0]['fecha'] == '0000-00-00 00:00:00') {
				$fechaCreacion = date('Y-m-d H:i:s');	
			} else {
				$fechaCreacion = $infoArticulo[0]['fecha'];
			}
			
			$sql = 'INSERT INTO articulos SET ';
			$sql .= 'idSeccion='.$infoArticulo[0]['idSeccion'].', ';
			$sql .= 'idUsuario="'.$infoArticulo[0]['idUsuario'].'", ';
			$sql .= 'titulo="'.$infoArticulo[0]['titulo'].'", ';
			$sql .= 'subtitulo="'.$infoArticulo[0]['subtitulo'].'", ';
			$sql .= 'fecha="'.$fechaCreacion.'", ';
			$sql .= 'fechaPublicacion="'.$infoArticulo[0]['fechaPublicacion'].'", ';
			$sql .= 'cuerpo="'.mysqli_real_escape_string($conexion,$infoArticulo[0]['cuerpo']).'", ';
			$sql .= 'cuerpoResumen="'.mysqli_real_escape_string($conexion,$infoArticulo[0]['cuerpoResumen']).'", ';
			$sql .= 'orden="'.$infoArticulo[0]['orden'].'", ';
			$sql .= 'imagen="'.$infoArticulo[0]['imagen'].'", ';
			$sql .= 'archivo="'.$infoArticulo[0]['archivo'].'", ';
			$sql .= 'url="'.$infoArticulo[0]['url'].'", ';
			$sql .= 'campoExtra="'.$infoArticulo[0]['campoExtra'].'", ';
			$sql .= 'estado=0; ';
			
			$result = mysqli_query($conexion, $sql);
			if ($result === TRUE) {
				echo 'OK';
			} else {
				echo 'KO';
				echo $sql;
			}
			
		break;
		
		case 'listaSecciones':			
			$consulta = "
			SELECT * FROM secciones 
			WHERE idSitioWeb = ".$idSitioWeb."
			AND estado = 1";
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
					
		
		case 'leeArticulo':
		
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT articulos.id, articulos.idSeccion, articulos.titulo, articulos.subtitulo, articulos.fecha, articulos.fechaPublicacion, articulos.cuerpo, 
			articulos.cuerporesumen, articulos.orden, articulos.imagen, articulos.archivo, articulos.url, articulos.campoExtra, articulos.estado
			FROM articulos, secciones, sitiosweb 
			WHERE articulos.id = ". $idArticulo." 
			AND secciones.id = articulos.idseccion 
			AND secciones.idsitioweb = ".$idSitioWeb;
			
			if ($grupo == "administrador") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND idUsuario IN ('.$where_in.')';
			}
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulos.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulos.estado = "nopublicado"';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulos.idUsuario IN ('.$where_in.')';
			}
			
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
			
			$consulta = "
			SELECT DISTINCT articulos.id, articulos.idSeccion, articulos.titulo, articulos.idUsuario, articulos.estado, articulos.fechaPublicacion
			FROM articulos, secciones, sitiosweb 
			WHERE idSeccion = ".$idSeccion." 
			AND secciones.id = articulos.idseccion 
			AND secciones.idsitioweb = ".$idSitioWeb."
			AND articulos.estado IN (0, 1, 2) ";
			
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulos.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulos.estado = "nopublicado"';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulos.idUsuario IN ('.$where_in.')';
			}
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				//ClassDuplica =     margin-right: 10px;
				//classModifica =     margin-right: 10px; position: relative; top: 3px;
				
				$duplicar = '<a href=\"#\" onclick=\"duplicar('.$row['id'].')\" class=\"iconDuplicar\"><i class=\"fa fa-2x fa-copy\"></i></a>';
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-2x fa-trash-o\"></i></a>';
				
				if (strlen($row['fechaPublicacion'] > 0)) {
					$e_date = explode(" ",$row['fechaPublicacion']);
					$date = date_create($e_date[0]);
					$fecha = date_format($date,"d-m-Y");
					$fecha = $e_date[1] . " " . $fecha;	
				} else {
					$fecha = '00-00-0000';
				}
				
				$tabla.='{"id":"'.$row['id'].'",';
				$tabla.='"usuario":"'.getUsuario($conexion,$row['idUsuario']).'",';
				$tabla.='"titulo":"'.$row['titulo'].'",';
				$tabla.='"estado":"'.getEstado($row['estado']).'",';
				$tabla.='"fecha":"'.$fecha.'",';
				$tabla.='"acciones":"'.$duplicar.$edita.$borra.'"},';			
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
			break;
			
		case 'elimina':
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "
			UPDATE articulos SET
			estado = 3
			WHERE id = '".$idArticulo."'
			AND EXISTS(
				SELECT 1 
				FROM secciones, sitiosweb 
				WHERE secciones.id = articulos.idseccion 
				AND secciones.idsitioweb = ".$idSitioWeb.")";
				
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulos.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulos.estado = "nopublicado"';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulos.idUsuario IN ('.$where_in.')';
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
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);	
		
			$mtitulo = mysqli_real_escape_string($conexion, $_POST['titulo']);
			$msubtitulo = mysqli_real_escape_string($conexion, $_POST['subtitulo']);
			$mfechaPublicacion = mysqli_real_escape_string($conexion, $_POST['fechaPublicacion']);
			$mcuerpo = mysqli_real_escape_string($conexion, $_POST['cuerpo']);
			$mcuerpoResumen = mysqli_real_escape_string($conexion, $_POST['cuerpoResumen']);
			$mimagen = mysqli_real_escape_string($conexion, $_POST['imagen']);
			$marchivo = mysqli_real_escape_string($conexion, $_POST['archivo']);
			$murl = mysqli_real_escape_string($conexion, $_POST['url']);
			$mcampoExtra = mysqli_real_escape_string($conexion, $_POST['campoExtra']);			
			$morden = mysqli_real_escape_string($conexion, $_POST['orden']);
			$mseccion = intval($_POST['seccion']);
			$estado = $_POST['estado'];
		
			$consulta = "
			UPDATE articulos SET 
			titulo='".$mtitulo."', 
			subtitulo='".$msubtitulo."',
			fechaPublicacion='".$mfechaPublicacion."', 
			cuerpo='".$mcuerpo."', 
			cuerpoResumen='".$mcuerpoResumen."', 
			imagen='".$mimagen."', 
			archivo='".$marchivo."', 
			url='".$murl."', 
			campoExtra='".$mcampoExtra."', 
			orden='".$morden."', 
			idSeccion=".$mseccion.",
			estado=".$estado." 
			WHERE id=".$idArticulo." 
			AND EXISTS(
				SELECT 1 
				FROM secciones, sitiosweb 
				WHERE secciones.id = articulos.idseccion 
				AND secciones.idsitioweb = ".$idSitioWeb.")";			

			if ($grupo == "colaborador") {
				$consulta .= ' AND articulos.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulos.estado = "nopublicado"';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulos.idUsuario IN ('.$where_in.')';
			}

			$retorno = mysqli_query($conexion, $consulta);
			
			echo $consulta;
			
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
			$mfecha = mysqli_real_escape_string($conexion, $_POST['fechaPublicacion']);
			$mcuerpo = mysqli_real_escape_string($conexion, $_POST['cuerpo']);
			$mimagen = mysqli_real_escape_string($conexion, $_POST['imagen']);
			$marchivo = mysqli_real_escape_string($conexion, $_POST['archivo']);
			$murl = mysqli_real_escape_string($conexion, $_POST['url']);
			$mcampoExtra = mysqli_real_escape_string($conexion, $_POST['campoExtra']);	
			
			$morden = mysqli_real_escape_string($conexion, $_POST['orden']);
			$mseccion = intval($_POST['seccion']);
			$estado = intval($_POST['estado']);
			
			$fechaActual = date('Y-m-d H:i:s');
			if (strlen($mfecha)>0) {
				$fechaPublicacion = $mfecha;
			} else {
				$fechaPublicacion = $fechaActual;
			}
		
			$consulta = "
			INSERT INTO articulos (titulo, subtitulo, fecha, fechaPublicacion, cuerpo, cuerporesumen, imagen, archivo, url, campoExtra, idSeccion, orden, idUsuario, estado) 
			VALUES ('".$mtitulo."','".$msubtitulo."','".$fechaActual."','".$fechaPublicacion."','".$mcuerpo."','','".$mimagen."','".$marchivo."','".$murl."','".$mcampoExtra."','".$mseccion."','".$morden."',".$idUsuario.", $estado)";

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
