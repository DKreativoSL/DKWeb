<?php
	session_start(); //inicializo sesión
	
	header("Content-Type: text/html;charset=utf-8");
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	
	$accion = $_POST['accion'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	switch ($accion) 
	{
		case 'obtenerSecciones':

			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			//Obtenemos la sección padre (si la tenemos)
			$consulta = "
			SELECT idSeccion FROM articulos WHERE id = ".$idRegistro;
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
			WHERE idsitioweb = ".$idSitioWeb."
			AND estado = 1;";
			
			$registro = mysqli_query($conexion, $consulta);
			$html = '';
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				$selected = '';
				if ($row['id'] == $tabla[0]['idSeccion']) {
					$selected = 'selected="selected"';
				}
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['nombre'].'</option>';
			}
			echo $html;
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
		case 'listaRegistros':		
			//$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			$consulta = "
			SELECT DISTINCT a.id, a.titulo, a.idUsuario, a.estado, a.fechaPublicacion
			FROM articulos AS a
			LEFT JOIN secciones AS s ON (a.idSeccion = s.id)
			WHERE a.estado = 3";
			
			$where_in = getSeccionesWeb($conexion,$idSitioWeb);
			$consulta .= ' AND s.id IN ('.$where_in.')';			

			if ($grupo == "administrador") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND a.idUsuario IN ('.$where_in.')';
			}
			if ($grupo == "colaborador") {
				$consulta .= ' AND a.idUsuario = '.$idUsuario;
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND a.idUsuario IN ('.$where_in.')';
			}
			
			$registro = mysqli_query($conexion, $consulta);

			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$recuperar = '<a data-toggle=\"modal\" data-target=\"#myModal\" href=\"#\" onclick=\"recuperar('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-undo fa-2x\"></i></a>';
				
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				$borrar = '<a href=\"#\" onclick=\"eliminarArticuloPapelera('.$row['id'].')\" class=\"iconEliminar\"><i class=\"fa fa-2x fa-trash-o\"></i></a>';
				
				$tabla.='{"id":"'.$row['id'].'",';
				$tabla.='"usuario":"'.getUsuario($conexion,$row['idUsuario']).'",';
				$tabla.='"titulo":"'.$row['titulo'].'",';
				$tabla.='"estado":"'.getEstado($row['estado']).'",';
				$tabla.='"fecha":"'.$row['fechaPublicacion'].'",';
				$tabla.='"acciones":"'.$recuperar.$edita.$borrar.'"},';
				
						
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
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
		case 'recuperarArticulo':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$idToMove = mysqli_real_escape_string($conexion, $_POST['idToMove']);
						
			$sql_updateSeccion = '
			UPDATE articulos SET
			idSeccion = '.$idToMove.',
			estado = 0
			WHERE id = '.$idRegistro;
			
			$result = mysqli_query($conexion, $sql_updateSeccion);

			echo "OK";
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
			idSeccion=".$mseccion."
			WHERE id=".$idArticulo;

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			}
			
		break;
		case 'eliminarArticuloPapelera':
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$sql = '
			DELETE FROM articulos 
			WHERE id = ' . $idArticulo.'
			AND estado = 3';

			$retorno = mysqli_query($conexion, $sql);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			}
			
		break;
		case 'vaciarPapeleraArticulos':
			$consulta = "
			DELETE FROM articulos
			WHERE estado = 3";

			if ($grupo == "administrador") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND idUsuario IN ('.$where_in.')';
			}
			if ($grupo == "colaborador") {
				$consulta .= ' AND idUsuario = '.$idUsuario;
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND idUsuario IN ('.$where_in.')';
			}
						
			$retorno = mysqli_query($conexion, $consulta);
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			}
		break;
	}
?>
