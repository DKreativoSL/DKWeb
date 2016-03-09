<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	$accion = $_POST['accion'];
	
	if (!isset($_SESSION['sitioWeb'])) {
		$idSitioWeb = -1;
	} else {
		$idSitioWeb = $_SESSION['sitioWeb'];	
	}
	
	function booleanToInt($string) {
		$boolean = 0;
		if (trim(strtolower($string)) == "true") {
			$boolean = 1;
		}
		return $boolean;
	}
	
	
	switch ($accion) 
	{
		case 'entrar':
			$idRegistroUsuario = mysqli_real_escape_string($conexion, $_POST['id']);

			$consulta = "SELECT * FROM usuarios WHERE id = ".$idRegistroUsuario;
			$registro = mysqli_query($conexion, $consulta);
			$resultado = mysqli_fetch_array($registro);

			//cambio la sesión de ID
			$_SESSION['idUsuario'] = $resultado['id'];
			$_SESSION['nombreUsuario'] = $resultado['nombre'];

			break;
				
		case 'listaSecciones':			
			$consulta = "SELECT * FROM secciones WHERE idSitioWeb = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row;
				$i++;
			}
			
			echo json_encode($tabla);
			break;
					
		
		/*Devuelvo un usuario comprobando que pertenezca al sitio actual*/
		case 'leeRegistro':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "SELECT * FROM usuarios left join usuariositios on usuarios.Id=usuariositios.idUsuario WHERE usuarios.id = ".$idRegistro;
			
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row;
				$i++;
			}
			//echo $consulta;
			echo json_encode($tabla);
			
			break;
					
		/*Devuelvo todos los usuarios de un sitioweb para listarlos*/
		case 'listaRegistros':
					
			$consulta = "SELECT usuarios.id, usuarios.nombre, usuarios.email, usuarios.tlf1, usuarios.fechaAlta, usuarios.fechaBaja from usuarios";
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
					
			while($row = mysqli_fetch_array($registro))
			{
				//$llave = '<a href=\"#\" onclick=\"entrar('.$row['id'].')\"><img border=\"0px\" src=\"./imgs/iconoLlave_24x24.png\" alt=\"Entrar\"/></a>';
				$llave = '<a href=\"#\" onclick=\"entrar('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-key fa-2x\"></i></a>';
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				
				$tabla.='{"nombre":"'.utf8_encode($row['nombre']).'","email":"'.utf8_encode($row['email']).'","tlf1":"'.utf8_encode($row['tlf1']).'","fechaAlta":"'.utf8_encode($row['fechaAlta']).'","fechaBaja":"'.utf8_encode($row['fechaBaja']).'","acciones":"'.$llave.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);

			echo '{"data":['.$tabla.']}';
			break;
		
		
			
		case 'elimina':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "delete from usuariositios where idUsuario='".$idRegistro."'";
			
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
						echo "KO".$consulta;
						}
				}else{
					echo "KO".$consulta;
				}
			}
			break;
			
			
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
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
			
			$menuPermisoContenidoWeb= mysqli_real_escape_string($conexion, $_POST['menuPermisoContenidoWeb']);
			$menuPermisoConfiguracion= mysqli_real_escape_string($conexion, $_POST['menuPermisoConfiguracion']);
			$menuPermisoSecciones= mysqli_real_escape_string($conexion, $_POST['menuPermisoSecciones']);
			$menuPermisoParametros= mysqli_real_escape_string($conexion, $_POST['menuPermisoParametros']);
			$menuPermisoUsuarios= mysqli_real_escape_string($conexion, $_POST['menuPermisoUsuarios']);
			$menuPermisoCorreos= mysqli_real_escape_string($conexion, $_POST['menuPermisoCorreos']);
			$menuPermisoInmobiliaria= mysqli_real_escape_string($conexion, $_POST['menuPermisoInmobiliaria']);
			$menuPermisoInmoApuntes= mysqli_real_escape_string($conexion, $_POST['menuPermisoInmoApuntes']);
			$menuPermisoInmoClientes=	mysqli_real_escape_string($conexion, $_POST['menuPermisoInmoClientes']);
			$menuPermisoInmoInmuebles= mysqli_real_escape_string($conexion, $_POST['menuPermisoInmoInmuebles']);
			$menuPermisoInmoZonas= mysqli_real_escape_string($conexion, $_POST['menuPermisoInmoZonas']);			
	
			$consulta = "UPDATE usuarios SET email='".$email."', clave='".md5($clave)."', nombre='".$nombre."', nif='".$nif."', direccion='".$direccion."', cp='".$cp."', provincia='".$provincia."', poblacion='".$poblacion."', tlf1='".$tlf1."', tlf2='".$tlf2."', comentarios='".$comentarios."' WHERE id='".$idRegistro."'";;			

			if (mysqli_query($conexion, $consulta)){
				//si todo salió bien, lanzo los permisos
				$consulta = "
				UPDATE usuariositios SET 
				menuContenidoWeb=".booleanToInt($menuPermisoContenidoWeb).", 
				menuConfiguracion=".booleanToInt($menuPermisoConfiguracion).", 
				menuSecciones=".booleanToInt($menuPermisoSecciones).", 
				menuParametros=".booleanToInt($menuPermisoParametros).", 
				menuUsuarios=".booleanToInt($menuPermisoUsuarios).",
				menuInmobiliaria=".booleanToInt($menuPermisoInmobiliaria).",
				menuInmoApuntes=".booleanToInt($menuPermisoInmoApuntes).",
				menuInmoClientes=".booleanToInt($menuPermisoInmoClientes).",
				menuInmoInmuebles=".booleanToInt($menuPermisoInmoInmuebles).",
				menuInmoZonas=".booleanToInt($menuPermisoUsuarios).", 
				menuCorreos=".booleanToInt($menuPermisoCorreos)." 
				WHERE id=".$idRegistro;
				
				echo $consulta;
				
				if (mysqli_query($conexion, $consulta)) {
					echo $idRegistro;	
				} else {
					echo "KO".$consulta;	
				}
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
		
			$consulta = "INSERT INTO usuarios (email, clave, nombre, nif, direccion, cp, poblacion, provincia, tlf1, tlf2, comentarios) VALUES ('".$email."','".md5($clave)."','".$nombre."','".$nif."','".$direccion."','".$cp."','".$poblacion."','".$provincia."','".$tlf1."','".$tlf2."','".$comentarios."')";

			if (mysqli_query($conexion, $consulta)){
				//si se inserta correctamente, añado la relación a la tabla de usuariositios
				//traigo el último id insertado				
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$idUltimo = mysqli_fetch_array($registro);
				
				$consulta = "INSERT INTO usuariositios (idUsuario, idSitioWeb, menuContenidoWeb, menuConfiguracion, menuSecciones, menuParametros, menuUsuarios, menuInmobiliaria, menuInmoApuntes, menuInmoClientes, menuInmoInmuebles, menuInmoZonas) VALUES (".$idUltimo[0].",'".$idSitioWeb."','1','1','1','1','1','1','1','1','1','1')";
				
				//Lanzo la consulta
				if (mysqli_query($conexion, $consulta)){
					//si salió todo bien devuelvo el registro del id usuario creado
					echo $idUltimo[0];					
				}
			}else{
				echo "KO";
			};
			
		break;

		
	}
?>
