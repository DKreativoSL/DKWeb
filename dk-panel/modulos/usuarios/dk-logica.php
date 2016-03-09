<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	switch ($accion) 
	{
		
		case 'listaSecciones':			
			$consulta = "
			SELECT * 
			FROM secciones 
			WHERE idSitioWeb = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = array_map(utf8_encode,$row);
				$i++;
			}
			
			echo json_encode($tabla);
			break;
		
		
		/*
		Devuelve una estructura con los permisos del usuario
		*/
		case 'cargaPermisos':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);		
			
			if ($idRegistro == 0) {
				$consulta = 'SELECT * FROM usuariositios LIMIT 1';
			} else {
				$consulta = 'SELECT * FROM usuariositios WHERE idSitioWeb = "'.$idSitioWeb.'" and idUsuario = "'.$idRegistro.'"';
			}
			
			$registro = mysqli_query($conexion, $consulta);			
			
			$row = mysqli_fetch_assoc($registro);
			
			$tabla = ""; //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			//$i=0;
			
			echo '<pre>'.print_r($row,true).'</pre>';
			
			if($row) {
				$colActual = 1;
				$col = array(
					1 =>'',
					2 =>'',
					3 =>''
				);
				foreach($row as $key => $value) {
					if ($key != "id" and $key != "idUsuario" and $key != "idSitioWeb"){
						
						//Si la ID de registro es 0, estamos creando el usuario y todos los values son 0
						if ($idRegistro == 0) { $value = 0; }
						
						//Si la columna es superior a 3, seteamos la primera columna
						if ($colActual > 3) $colActual = 1;
						
						if ($value == 0){
							$col[$colActual] .= ' <input id="'.$key.'" class="permisos" type="checkbox" value="1"> '.substr($key, 4).'<br>';
						}else{
							$col[$colActual] .= ' <input id="'.$key.'" class="permisos" type="checkbox" value="1" checked="checked"> '.substr($key, 4).'<br>';
						}
						$colActual++;
					}
				}
				
				$tabla = '
				<div class="col-lg-4">
					'.$col[1].'
				</div>
				<div class="col-lg-4">
					'.$col[2].'
				</div>
				<div class="col-lg-4">
					'.$col[3].'
				</div>
				';
				
			}
			
			//$tabla .= "Permisos no disponibles";
			echo json_encode($tabla);
			
		break;
		
		

		/*Devuelvo un usuario comprobando que pertenezca al sitio actual*/
		case 'leeRegistro':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT usuarios.id, usuarios.nombre, usuarios.email, usuarios.tlf1,  usuarios.tlf2, usuarios.direccion, usuarios.cp, usuarios.provincia, 
			usuarios.poblacion, usuarios.nif,  usuarios.comentarios, usuarios.fechaAlta, usuarios.fechaBaja, usuarios.grupo 
			FROM usuarios, usuariositios 
			WHERE usuarios.id = ".$idRegistro." 
			AND usuariositios.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = array_map('utf8_encode',$row);
				$i++;
			}
			//echo $consulta;
			echo json_encode($tabla);
			
			break;
					
		/*Devuelvo todos los usuarios de un sitioweb para listarlos*/
		case 'listaRegistros':
					
			$consulta = "SELECT usuarios.id, usuarios.nombre, usuarios.email, usuarios.tlf1, usuarios.fechaAlta, usuarios.fechaBaja from usuarios, usuariositios WHERE usuarios.id = usuariositios.idusuario AND usuariositios.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
					
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				
				$tabla.='{"nombre":"'.utf8_encode($row['nombre']).'","email":"'.utf8_encode($row['email']).'","tlf1":"'.utf8_encode($row['tlf1']).'","fechaAlta":"'.utf8_encode($row['fechaAlta']).'","fechaBaja":"'.utf8_encode($row['fechaBaja']).'","acciones":"'.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);

			echo '{"data":['.$tabla.']}';
			break;
		
		
			
		case 'elimina':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "delete from usuariositios where idUsuario='".$idRegistro."' AND idsitioweb = ".$idSitioWeb;
			
			//elimino la relación de usuario con sitio web
			if (mysqli_query($conexion, $consulta))
			{				
				if (mysqli_affected_rows($conexion) > 0){
					//Si elimina algún usuariositios, lo elimino también de usuarios					
					$consulta = "delete from usuarios where id='".$idRegistro."'";					
					if (mysqli_query($conexion, $consulta))
					{
						echo "OK";
					}else{
						echo "KO";
						}
				}else{
					echo "KO";
				}
			}
			break;
			
			
			
		case 'guarda':	
		
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$email = mysqli_real_escape_string($conexion, $_POST['email']);
			
			$clave = mysqli_real_escape_string($conexion, $_POST['clave']);
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
			$comentarios = mysqli_real_escape_string($conexion, $_POST['comentarios']);
			$grupo = mysqli_real_escape_string($conexion, $_POST['grupo']);
		
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
			comentarios='".$comentarios."',
			grupo='".$grupo."'  
			WHERE id='".$idRegistro."'";
			
			//GUARDAMOS LOS PERMISOS
			$sql_permisos = 'UPDATE usuariositios SET ';
			$tmp_permisos = '';
			foreach ($_POST['permisos'] as $k=>$permiso) {
				if (strlen($tmp_permisos)>0) $tmp_permisos .= ', ';
				
				$perm = 0;
				if ($permiso['value'] == 'true') {
					$perm = 1;
				}
				
				$tmp_permisos .= $permiso['name'] . ' = ' . $perm;
			}	
			$sql_permisos = 'UPDATE usuariositios SET '. $tmp_permisos.'  WHERE idUsuario = ' . $idRegistro;
			mysqli_query($conexion, $sql_permisos);
			
//			echo $consulta;
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
			$clave = mysqli_real_escape_string($conexion, $_POST['clave']);
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$nif = mysqli_real_escape_string($conexion, $_POST['nif']);
			$direccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
			$cp = mysqli_real_escape_string($conexion, $_POST['cp']);
			$poblacion = mysqli_real_escape_string($conexion, $_POST['poblacion']);
			$provincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
			$tlf1 = mysqli_real_escape_string($conexion, $_POST['tlf1']);
			$tlf2 = mysqli_real_escape_string($conexion, $_POST['tlf2']);
			$comentarios = mysqli_real_escape_string($conexion, $_POST['comentarios']);

			$fechaAlta = date('Y-m-d h:i:s');
		
			$consulta = "INSERT INTO usuarios (email, clave, nombre, nif, direccion, cp, poblacion, provincia, tlf1, tlf2, comentarios, fechaAlta) VALUES ('".$email."','".md5($clave)."','".$nombre."','".$nif."','".$direccion."','".$cp."','".$poblacion."','".$provincia."','".$tlf1."','".$tlf2."','".$comentarios."', '".$fechaAlta."')";

			if (mysqli_query($conexion, $consulta)){
				//si se inserta correctamente, añado la relación a la tabla de usuariositios
				//traigo el último id insertado				
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$idUltimo = mysqli_fetch_array($registro);
				
				
				//GUARDAMOS LOS PERMISOS
				$tmp_permisos = '';
				foreach ($_POST['permisos'] as $k=>$permiso) {
					if (strlen($tmp_permisos)>0) $tmp_permisos .= ', ';
					
					$perm = 0;
					if ($permiso['value'] == 'true') {
						$perm = 1;
					}
					
					$tmp_permisos .= $permiso['name'] . ' = ' . $perm;
				}	
				$sql_permisos = '
				INSERT usuariositios SET
				idUsuario =  '.$idUltimo[0].',
				idSitioWeb =  '.$idSitioWeb.',  
				'. $tmp_permisos;
				
				//$consulta = "INSERT INTO usuariositios (idUsuario, idSitioWeb, menuContenidoWeb, menuConfiguracion, menuSecciones, menuParametros, menuUsuarios, menuInmobiliaria, menuInmoApuntes, menuInmoClientes, menuInmoInmuebles, menuInmoZonas) VALUES (".$idUltimo[0].",'".$idSitioWeb."','1','1','1','1','1','1','1','1','1','1')";
				
				//Lanzo la consulta
				if (mysqli_query($conexion, $sql_permisos)){
					//si salió todo bien devuelvo el registro del id usuario creado
					echo $idUltimo[0];					
				}
			}else{
				echo "KO";
			};
			
		break;

		
	}
?>
