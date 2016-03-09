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
			AND estado = 1
			ORDER BY orden ASC;";
			
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
		case 'listaSecciones':			
			$consulta = "
			SELECT * FROM secciones 
			WHERE idSitioWeb = ".$idSitioWeb."
			AND estado = 1
			ORDER BY orden ASC;";
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
		case 'leeRegistro':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT 
			secciones.*
			FROM secciones, sitiosweb 
			WHERE secciones.id = ".$idRegistro." 
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
		
		case 'listaRegistros':
			
			$secciones = array();
			getAllSectionsV2($secciones,0,$idSitioWeb,$conexion);
			
			$registroActual = 0;
			$_html = '';
			listaRegistrosIndexada($_html,$registroActual,$secciones);
			
			echo $_html;
		break;
		
		case 'listaRegistrosDataTable':		
			//$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			$consulta = "
			SELECT DISTINCT id, nombre, tipo, orden 
			FROM secciones
			WHERE idsitioweb = ".$idSitioWeb."
			AND estado = 1;";
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$duplicar = '<a href=\"#\" onclick=\"duplicar('.$row['id'].')\" class=\"iconDuplicar\"><i class=\"fa fa-2x fa-copy\"></i></a>';
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-2x fa-edit\"></i></a>';
				$borra = '<a href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-2x fa-trash-o\"></i></a>';
				//$borra .= '<button type=\"button\" id=\"btn_'.$row['id'].'\" style=\"display:none\" class=\"btn btn-info btn-lg\" data-toggle=\"modal\" data-target=\"#myModal\">Eliminar</button>';
				
				$tabla.='{"id":"'.$row['id'].'","nombre":"'.$row['nombre'].'","tipo":"'.$row['tipo'].'","orden":"'.$row['orden'].'","acciones":"'.$duplicar.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
		break;
		case 'moverContenidoEliminarSeccion':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$idToMove = mysqli_real_escape_string($conexion, $_POST['idToMove']);
			
			
			//Obtenemos todas las secciones hijas
			$dataReturn = array();
			getAllSectionsWithData($dataReturn,$idRegistro,$conexion);
			foreach ($dataReturn as $k=>$seccion) {
				
				//Movemos las secciones a la nueva seccion
				$sql_updateSecciones = '
				UPDATE secciones SET
				seccion = '.$idToMove.'
				WHERE id = '.$seccion['id'];
				
				//echo $sql_updateSecciones;
				$result = mysqli_query($conexion, $sql_updateSecciones);
			}
			
			//Movemos los articulos a la nueva seccion
			$sql_updateArticulos = '
			UPDATE articulos SET
			idSeccion = '.$idToMove.'
			WHERE idSeccion = '.$idRegistro;
			
			//echo $sql_updateArticulos;
			$result = mysqli_query($conexion, $sql_updateArticulos);
			
			
			//Eliminamos logicamente la seccion
			$sql_eliminarLogico = '
			UPDATE secciones SET
			estado = 3
			WHERE id = '.$idRegistro;
			$result = mysqli_query($conexion, $sql_eliminarLogico);
			
			echo "OK";
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
			$idRegistro 	= mysqli_real_escape_string($conexion, $_POST['id']);
			$nombre 		= mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion 	= mysqli_real_escape_string($conexion, $_POST['descripcion']);
			$orden 			= mysqli_real_escape_string($conexion, $_POST['orden']);
			$privada 		= mysqli_real_escape_string($conexion, $_POST['privada']);
			$seccion 		= mysqli_real_escape_string($conexion, $_POST['seccion']);
			$tipo 			= mysqli_real_escape_string($conexion, $_POST['tipo']);
			
			$campos = array();
			
			$campos['ch_CampoTitulo'] 			= $_POST['ch_CampoTitulo'];
			$campos['ch_CampoSubTitulo'] 		= $_POST['ch_CampoSubTitulo'];
			$campos['ch_CampoCuerpo'] 			= $_POST['ch_CampoCuerpo'];
			$campos['ch_CampoCuerpoAvance'] 	= $_POST['ch_CampoCuerpoAvance'];
			$campos['ch_CampoFechaPublicacion'] = $_POST['ch_CampoFechaPublicacion'];
			$campos['ch_CampoImagen'] 			= $_POST['ch_CampoImagen'];
			$campos['ch_CampoArchivo'] 			= $_POST['ch_CampoArchivo'];
			$campos['ch_CampoURL'] 				= $_POST['ch_CampoURL'];
			$campos['ch_CampoCampoExtra'] 		= $_POST['ch_CampoCampoExtra'];
		
			echo '<pre>'.print_r($campos,true).'</pre>';
		
			foreach ($campos as $nombreCampo => $valor) {
				if ($valor == "true") {
					$campos[$nombreCampo] = 1;
				} else {
					$campos[$nombreCampo] = 0;
				}
			}
		
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
				
				
				$consultaCampos = "
				UPDATE secciones SET 
				ch_CampoTitulo='".$campos['ch_CampoTitulo']."', 
				ch_CampoSubTitulo='".$campos['ch_CampoSubTitulo']."', 
				ch_CampoCuerpo='".$campos['ch_CampoCuerpo']."', 
				ch_CampoCuerpoAvance='".$campos['ch_CampoCuerpoAvance']."', 
				ch_CampoFechaPublicacion='".$campos['ch_CampoFechaPublicacion']."', 
				ch_CampoImagen='".$campos['ch_CampoImagen']."',
				ch_CampoArchivo='".$campos['ch_CampoArchivo']."',
				ch_CampoURL='".$campos['ch_CampoURL']."',
				ch_CampoCampoExtra='".$campos['ch_CampoCampoExtra']."'
				WHERE id='".$idRegistro."' 
				AND idSitioWeb = ".$idSitioWeb;					
				echo $consultaCampos;
				$retornoCampos = mysqli_query($conexion, $consultaCampos);
				
				if ($retornoCampos){
					echo $idRegistro;
				} else{
					echo "KO";
				}
			} else{
				echo "KO";
			}		
		break;
		
		case 'inserta':	
		//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos

			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
			$orden = mysqli_real_escape_string($conexion, $_POST['orden']);
			$privada = mysqli_real_escape_string($conexion, $_POST['privada']);
			$seccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			$tipo = mysqli_real_escape_string($conexion, $_POST['tipo']);
		
			$campos = array();
			
			$campos['ch_CampoTitulo'] 			= mysqli_real_escape_string($conexion, $_POST['ch_CampoTitulo']);
			$campos['ch_CampoSubTitulo'] 		= mysqli_real_escape_string($conexion, $_POST['ch_CampoSubTitulo']);
			$campos['ch_CampoCuerpo'] 			= mysqli_real_escape_string($conexion, $_POST['ch_CampoCuerpo']);
			$campos['ch_CampoCuerpoAvance'] 	= mysqli_real_escape_string($conexion, $_POST['ch_CampoCuerpoAvance']);
			$campos['ch_CampoFechaPublicacion'] = mysqli_real_escape_string($conexion, $_POST['ch_CampoFechaPublicacion']);
			$campos['ch_CampoImagen'] 			= mysqli_real_escape_string($conexion, $_POST['ch_CampoImagen']);
			$campos['ch_CampoArchivo'] 			= mysqli_real_escape_string($conexion, $_POST['ch_CampoArchivo']);
			$campos['ch_CampoURL'] 				= mysqli_real_escape_string($conexion, $_POST['ch_CampoURL']);
			$campos['ch_CampoCampoExtra'] 		= mysqli_real_escape_string($conexion, $_POST['ch_CampoCampoExtra']);
		
			foreach ($campos as $nombreCampo => $valor) {
				if ($valor == "true") {
					$campos[$nombreCampo] = 1;
				} else {
					$campos[$nombreCampo] = 0;
				}
			}
		
			$consulta = "
			INSERT INTO secciones (idSitioWeb, nombre, descripcion, orden, privada, seccion, tipo, estado) 
			VALUES ('".$idSitioWeb."','".$nombre."','".$descripcion."','".$orden."','".$privada."','".$seccion."','".$tipo."',1)";

			$retorno = mysqli_query($conexion, $consulta);
			
			//si ha insertado correctamente
			if ($retorno){
				//devuelvo el id recien creado para cargarlo en el documento actual por si pulsara otra vez modificar
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				$idRegistro = $row[0];
				
				$consultaCampos = "
				UPDATE secciones SET 
				ch_CampoTitulo='".$campos['ch_CampoTitulo']."', 
				ch_CampoSubTitulo='".$campos['ch_CampoSubTitulo']."', 
				ch_CampoCuerpo='".$campos['ch_CampoCuerpo']."', 
				ch_CampoCuerpoAvance='".$campos['ch_CampoCuerpoAvance']."', 
				ch_CampoFechaPublicacion='".$campos['ch_CampoFechaPublicacion']."', 
				ch_CampoImagen='".$campos['ch_CampoImagen']."',
				ch_CampoArchivo='".$campos['ch_CampoArchivo']."',
				ch_CampoURL='".$campos['ch_CampoURL']."',
				ch_CampoCampoExtra='".$campos['ch_CampoCampoExtra']."'
				WHERE id=".$idRegistro."
				AND idSitioWeb = " . $idSitioWeb;
	
				$retornoCampos = mysqli_query($conexion, $consultaCampos);
				
				if ($retornoCampos){
					echo $row[0];
				} else{
					echo "KO";
				}
			}else{
				echo "KO".$consulta;
			};
			
		break;
		case 'cargaFormulario':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT DISTINCT secciones.id, secciones.idsitioweb, seccionesformulario.idSeccion, seccionesformulario.formulario as formulario 
			FROM secciones, seccionesformulario, sitiosweb 
			WHERE secciones.id = ".$idRegistro." 
			AND secciones.idsitioweb = ".$idSitioWeb." 
			AND secciones.id = seccionesformulario.idSeccion";

			$registro = mysqli_query($conexion, $consulta);

			$resulta = mysqli_fetch_array($registro);
			
			echo $resulta['formulario'];

		break;				
		case 'guardaFormulario':	
			//recojo datos 
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['id']);			
			$formulario = mysqli_real_escape_string($conexion, $_POST['formulario']);

			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "SELECT secciones.id as idSeccion FROM secciones, sitiosweb WHERE secciones.id = ".$idSeccion." AND secciones.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);		
			$resulta = mysqli_fetch_array($registro);
			
			//si es la sección del usuario, inserto/actualizo el form
			if ($resulta['idSeccion'] == $idSeccion){
				$consulta = 'INSERT INTO seccionesformulario (idSeccion, formulario) VALUES ('.$idSeccion.',"'.$formulario.'") ON DUPLICATE KEY UPDATE formulario = "'.$formulario.'"';
			}
			
			//si se ha insertado correctamente
			if (mysqli_query($conexion, $consulta)){				
				echo "OK".$consulta;
			}else{
				echo "KO".$consulta;
			};
		break;
	}
?>
