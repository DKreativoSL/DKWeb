<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	
	function getProgress($idUser,$idCourse,$conexion) {
		
		
		//Seleccionamos todas las secciones de un curso
		$sql = '
		SELECT id
		FROM secciones
		WHERE seccion = '.$idCourse;
		
		$registro = mysqli_query($conexion, $sql);
		$in_lessons = '';
		while($row = mysqli_fetch_array($registro))
		{
			if (strlen($in_lessons)>0) $in_lessons .= ',';
			$in_lessons .= $row['id'];
		}
		
		
		if (strlen($in_lessons) > 0) {
			$sql = "
			SELECT COUNT(a.id) as total
			FROM articulos AS a
			LEFT JOIN secciones AS s ON s.id = a.idSeccion 
			WHERE s.id IN (".$in_lessons.")
			AND a.estado = 1;";
				
			$registro = mysqli_query($conexion, $sql);
			while($row = mysqli_fetch_array($registro))
			{
				$totalLessons = $row;
			}
	
			$sql = "
			SELECT COUNT(a.id) as total
			FROM progresoCursos AS pg
			LEFT JOIN articulos AS a ON pg.idPublicacion = a.id
			LEFT JOIN secciones AS s ON s.id = a.idSeccion 
			WHERE s.id IN (".$in_lessons.")
			AND a.estado = 1
			AND pg.idUsuario = ".$idUser."
			AND pg.visto = 1;";
			
			$registro = mysqli_query($conexion, $sql);
			while($row = mysqli_fetch_array($registro))
			{
				$totalLessonsFinished = $row;
			}
			
			if ( ($totalLessonsFinished['total'] > 0) && ($totalLessons['total'] > 0) ) {
				$percentProgress = ( $totalLessonsFinished['total'] * 100 ) / $totalLessons['total'];
				$percentProgress = intval($percentProgress);	
			} else {
				$percentProgress = 0;
			}
				
		} else {
			$percentProgress = 0;
		}
		
		return $percentProgress;
	}
	
	switch ($accion) 
	{
		case 'leerCurso':
		
			$idAlumnoCurso = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = 'SELECT * FROM cursoAlumno WHERE id = '.$idAlumnoCurso;
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array();
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				
				$row['fechaAlta'] = formatDate($row['fechaAlta']);
				$row['fechaBaja'] = formatDate($row['fechaBaja']);
				
				
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
		break;
		case 'obtenerCursos':
			
			$idCurso = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$consulta = "
			SELECT id, nombre
			FROM secciones
			WHERE tipo = 3 
			AND idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$html = '';
			while($row = mysqli_fetch_array($registro))
			{
				$selected = ($idCurso == $row['id']) ? 'selected="selected"':'';
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['nombre'].'</option>';
			}
			echo $html;
		break;
		
		case 'guardaCurso':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idCursoAlumno = mysqli_real_escape_string($conexion, $_POST['id']);	

			$idSeccion = mysqli_real_escape_string($conexion, $_POST['idCurso']);
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
			
			$fechaAlta = mysqli_real_escape_string($conexion, $_POST['fechaAlta']);
			$fecha_e = explode('/',$fechaAlta);
			$fechaAlta = $fecha_e[2] . '-' . $fecha_e[1] . '-' . $fecha_e[0];
			
			$fechaBaja = mysqli_real_escape_string($conexion, $_POST['fechaBaja']);
			$fecha_e = explode('/',$fechaBaja);
			$fechaBaja = $fecha_e[2] . '-' . $fecha_e[1] . '-' . $fecha_e[0];
		
			$consulta = "
			UPDATE cursoAlumno SET
			fechaAlta = '".$fechaAlta."',
			fechaBaja = '".$fechaBaja."',
			idUsuario = '".$idUsuario."',
			idSeccion = '".$idSeccion."'
			WHERE id ='".$idCursoAlumno."';";

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};
			
		break;
		
		case 'insertaCurso':
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['idCurso']);
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
			
			$fechaAlta = mysqli_real_escape_string($conexion, $_POST['fechaAlta']);
			$fecha_e = explode('/',$fechaAlta);
			$fechaAlta = $fecha_e[2] . '-' . $fecha_e[1] . '-' . $fecha_e[0];
			
			$fechaBaja = mysqli_real_escape_string($conexion, $_POST['fechaBaja']);
			$fecha_e = explode('/',$fechaBaja);
			$fechaBaja = $fecha_e[2] . '-' . $fecha_e[1] . '-' . $fecha_e[0];
			
			$consulta = "
			INSERT INTO cursoAlumno SET
			fechaAlta = '".$fechaAlta."',
			fechaBaja = '".$fechaBaja."',
			idUsuario = '".$idUsuario."',
			idSeccion = '".$idSeccion."';";
			
			echo $consulta;

			$retorno = mysqli_query($conexion, $consulta);
			//si ha insertado correctamente
			if ($retorno){
				//devuelvo el id recien creado para cargarlo en el documento actual por si pulsara otra vez modificar
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				echo $row[0];
			}else{
				echo "KO".$consulta;
			}
		break;
		case 'eliminaCursoAlumno':
			$fechaBaja = date('Y-m-d');
			$idAlumno = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$consulta = "
			UPDATE cursoAlumno SET
			fechaBaja = '".$fechaBaja."'
			WHERE id ='".$idAlumno."';";
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
		
		case 'leerAlumno':
		
			$idAlumno = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = 'SELECT * FROM usuarios WHERE id = '.$idAlumno;
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array();
			$i=0;
		
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
		break;

		case 'listaCursosAlumno':
			
			$idAlumno = mysqli_real_escape_string($conexion, $_POST['idAlumno']);
			
			$consulta = "
			SELECT DISTINCT 
			cursoAlumno.id as idCursoAlumno,
			cursoAlumno.idUsuario as idUsuario,
			cursoAlumno.idSeccion as idCurso,  
			cursoAlumno.fechaAlta as fechaAlta, 
			cursoAlumno.fechaBaja as fechaBaja, 
			cursoAlumno.fechaUltimoacceso as ultimoAcceso, 
			secciones.nombre as nombreCurso
			FROM cursoAlumno as cursoAlumno
			LEFT JOIN secciones AS secciones ON secciones.id = cursoAlumno.idSeccion
			WHERE cursoAlumno.idUsuario = ".$idAlumno."
			AND secciones.tipo = 3
			AND secciones.estado = 1
			AND idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modificaCursoAlumno('.$row['idCursoAlumno'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"eliminaCursoAlumno('.$row['idCursoAlumno'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				
				$progreso = getProgress($row['idUsuario'], $row['idCurso'],$conexion);
				
				$tabla.='{"id":"'.$row['idCursoAlumno'].'",';
				$tabla.='"Curso":"'.$row['nombreCurso'].'",';
				$tabla.='"fechaAlta":"'.formatDate($row['fechaAlta']).'",';
				$tabla.='"fechaBaja":"'.formatDate($row['fechaBaja']).'",';
				$tabla.='"ultimoAcceso":"'.$row['ultimoAcceso'].'",';
				$tabla.='"progreso": "'.$progreso.'%",';
				$tabla.='"acciones":"'.$edita.$borra.'"},';
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
		break;
		
							
		case 'listaAlumnos':
			$consulta = "
			SELECT 
			u.*, 
			u.email as usuario_email, 
			ca.id as idCursoAlumno,
			COUNT(ca.id) AS totalCursos
			FROM usuarios AS u
			RIGHT JOIN cursoAlumno AS ca ON ca.idUsuario = u.id
			LEFT JOIN usuariositios AS us ON us.idUsuario = u.id    
			WHERE us.idSitioWeb = ".$idSitioWeb."
			AND u.fechaBaja = '0000-00-00'
			GROUP BY u.id";
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				
				$tabla.='{"id":"'.$row['id'].'",';
				$tabla.='"nombre":"'.getUsuario($conexion,$row['id']).'",';
				$tabla.='"usuario":"'.$row['usuario_email'].'",';
				$tabla.='"cursos":"'.($row['totalCursos']-1).'",';
				$tabla.='"acciones":"'.$edita.$borra.'"},';		

			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
		break;
		
		case 'elimina':
		
			$fechaBaja = date('Y-m-d');
			$idAlumno = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$consulta = "
			UPDATE usuarios SET
			fechaBaja = '".$fechaBaja."'
			WHERE id ='".$idAlumno."';";
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
		break;
		
		case 'guarda':	
		
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$email = mysqli_real_escape_string($conexion, $_POST['email']);
			
			$clave = $_POST['clave'];
			if (strlen($clave) > 0) {
				//Como la clave esta llena de texto, la actualizamos
				$consulta = "UPDATE usuarios SET clave='".md5($clave)."' WHERE id='".$idRegistro."'";;			
				mysqli_query($conexion, $consulta);
			}
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$nif = mysqli_real_escape_string($conexion, $_POST['nif']);
			$direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
			$cp = mysqli_real_escape_string($conexion, $_POST['cp']);
			$poblacion = mysqli_real_escape_string($conexion, $_POST['poblacion']);
			$provincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
			$tlf1 = mysqli_real_escape_string($conexion, $_POST['tlf1']);
			$tlf2 = mysqli_real_escape_string($conexion, $_POST['tlf2']);
			$comentarios = mysqli_real_escape_string($conexion, $_POST['sobreti']);
		
			$consulta = "
			UPDATE usuarios SET 
			email='".$email."',
			nombre='".$nombre."', 
			nif='".$nif."', 
			direccion='".$direccion."', 
			cp='".$cp."', 
			provincia='".$provincia."', 
			poblacion='".$poblacion."', 
			tlf1='".$tlf1."', 
			tlf2='".$tlf2."', 
			comentarios='".$comentarios."'
			WHERE id='".$idRegistro."'";

			if (mysqli_query($conexion, $consulta)){
				//si todo salió bien, devuelvo el id del registro
				echo $idRegistro;
			}else{
				echo "KO".$consulta;
			};
			
		break;
		
		case 'inserta':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$email = mysqli_real_escape_string($conexion, $_POST['email']);
			$clave = $_POST['password'];
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$nif = mysqli_real_escape_string($conexion, $_POST['nif']);
			$direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
			$cp = mysqli_real_escape_string($conexion, $_POST['cp']);
			$poblacion = mysqli_real_escape_string($conexion, $_POST['poblacion']);
			$provincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
			$tlf1 = mysqli_real_escape_string($conexion, $_POST['tlf1']);
			$tlf2 = mysqli_real_escape_string($conexion, $_POST['tlf2']);
			$comentarios = mysqli_real_escape_string($conexion, $_POST['sobreti']);

			$fechaAlta = date('Y-m-d h:i:s');
		
			$consulta = "INSERT INTO usuarios (email, clave, nombre, nif, direccion, cp, poblacion, provincia, tlf1, tlf2, comentarios, fechaAlta, grupo) 
			VALUES ('".$email."','".md5($clave)."','".$nombre."','".$nif."','".$direccion."','".$cp."','".$poblacion."','".$provincia."','".$tlf1."','".$tlf2."','".$comentarios."', '".$fechaAlta."', 'suscriptor')";

			if (mysqli_query($conexion, $consulta)){
				//si se inserta correctamente, añado la relación a la tabla de usuariositios
				//traigo el último id insertado				
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$idUltimo = mysqli_fetch_array($registro);
				
				
				//GUARDAMOS LOS PERMISOS
				$sql_permisos = '
				INSERT usuariositios SET
				idUsuario =  '.$idUltimo[0].',
				idSitioWeb =  '.$idSitioWeb.',  
				menuContenidoWeb = 0,
				menuConfiguracion = 0,
				menuSecciones = 0,
				menuParametros = 0,
				menuUsuarios = 0,
				menuInmobiliaria = 0,
				menuInmoApuntes = 0,
				menuInmoClientes = 0,
				menuInmoInmuebles = 0,
				menuInmoZonas = 0,
				menuCorreos = 0,
				menuMigracion = 0,
				menuComentarios = 1,
				menuTrashSecciones = 0,
				menuTrashArticulos = 0,
				menuUpdates = 0,
				menuFTP = 0';
				
				
				$fechaAltaCurso = date('Y-m-d');
				
				//GUARDAMOS LOS PERMISOS
				$sql_curso_ficticio = '
				INSERT cursoAlumno SET
				idSeccion = "-1",
				idUsuario = '.$idUltimo[0].',
				fechaAlta = "'.$fechaAltaCurso.'";';
				
				if (mysqli_query($conexion, $sql_curso_ficticio)) {
					//Lanzo la consulta
					if (mysqli_query($conexion, $sql_permisos)){
						//si salió todo bien devuelvo el registro del id usuario creado
						echo $idUltimo[0];					
					}
				} else {
					echo 'KO';
				}
			}else{
				echo "KO";
			};
			
		break;
		
	}
?>
