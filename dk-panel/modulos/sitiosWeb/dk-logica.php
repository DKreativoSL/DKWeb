<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
		
	$accion = $_POST['accion'];
	
	if (!isset($_SESSION['sitioWeb'])) {
		$idSitioWeb = -1;
	} else {
		$idSitioWeb = $_SESSION['sitioWeb'];	
	}
	
	switch ($accion) 
	{
		
		case 'listaUsuarios':			
			$consulta = "SELECT * FROM usuarios";
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row; // array_map(utf8_encode,$row);
				$i++;
			}
			
			echo json_encode($tabla);
			break;

		/*Devuelvo un usuario comprobando que pertenezca al sitio actual*/
		case 'leeRegistro':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "SELECT * FROM sitiosweb WHERE id = ".$idRegistro;
			
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row; //array_map(utf8_encode,$row);
				$i++;
			}
			//echo $consulta;
			echo json_encode($tabla);
			
			break;
					
		/*Devuelvo todos los sitiosweb para listarlos*/
		case 'listaRegistros':
					
			$consulta = "SELECT * from sitiosweb";
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
					
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				
				$tabla.='{"nombre":"'.utf8_encode($row['nombre']).'","descripcion":"'.utf8_encode($row['descripcion']).'","dominio":"'.utf8_encode($row['dominio']).'","fechaCreacion":"'.utf8_encode($row['fechaCreacion']).'","token":"'.utf8_encode($row['token']).'","acciones":"'.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);

			echo '{"data":['.$tabla.']}';
			break;
		
		
		//ACTUALMENTE NO ESTÁ FUNCIONAL	
		case 'elimina':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			//antes de eliminarlo de bbdd, lanzo para eliminarlo en el server			
			include("./../../funcionesPlesk.php");
			suscriptionPlesk("r", "", "", "", $idRegistro);	
			
			$consulta = "delete from sitiosweb where id='".$idRegistro."'";			
			
			//HAY QUE ELIMINAR LA RELACIÓN WEB CON USUARIO
			//elimino la relación de usuario con sitio web
			if (mysqli_query($conexion, $consulta))
			{							
				echo "OK";
				
			}else{
				echo "KO";
				/*if (mysqli_affected_rows($conexion) > 0){
					//Si elimina algún usuariositios, lo elimino también de usuarios					
					$consulta = "delete from usuariositioweb where id='".$idRegistro."'";					
					if (mysqli_query($conexion, $consulta))
					{
						echo "OK";
					}else{
						echo "KO";
						}
				}else{
					echo "KO";
				}*/
			}
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
			$dominio = mysqli_real_escape_string($conexion, $_POST['dominio']);
			$usuarioPropietario = mysqli_real_escape_string($conexion, $_POST['usuarioPropietario']);

			$ftp_user = mysqli_real_escape_string($conexion, $_POST['ftp_user']);
			$ftp_pass = mysqli_real_escape_string($conexion, $_POST['ftp_pass']);
			$ftp_server = mysqli_real_escape_string($conexion, $_POST['ftp_server']);

		
			$consulta = "UPDATE sitiosweb SET idUsuarioPropietario='".$usuarioPropietario."', nombre='".$nombre."', descripcion='".$descripcion."', dominio='".$dominio."', ftp_user='".$ftp_user."', ftp_pass='".$ftp_pass."', ftp_server='".$ftp_server."' WHERE id='".$idRegistro."'";
				
			if (mysqli_query($conexion, $consulta)){
//				//si todo salió bien, actualizo el sitioweb y lanzo el plesk
				if ($ftp_user){
					include("./../../funcionesPlesk.php");
					suscriptionPlesk("u", "", $nombre, $ftp_pass, $idRegistro);
				}
				echo $idRegistro;
			}else{
				echo "KO".$consulta;
			};
			
		break;
		
	case 'inserta':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
			$dominio = mysqli_real_escape_string($conexion, $_POST['dominio']);
			$fechaCreacion = date("Y-m-d H:i:s");
			$usuarioPropietario = mysqli_real_escape_string($conexion, $_POST['usuarioPropietario']);

			$ftp_user = mysqli_real_escape_string($conexion, $_POST['ftp_user']);
			$ftp_pass = mysqli_real_escape_string($conexion, $_POST['ftp_pass']);
			$ftp_server = mysqli_real_escape_string($conexion, $_POST['ftp_server']);

			
			//si no trae usuario, le asigno el 1 que es Dkreativo
			if ($usuarioPropietario  < 1) $usuarioPropietario  = 1;
			
			$consulta = "INSERT INTO sitiosweb (idUsuarioPropietario, nombre, descripcion, dominio, fechaCreacion, token, ftp_user, ftp_pass, ftp_server) VALUES ('".$usuarioPropietario."','".$nombre."','".$descripcion."','".$dominio."','".$fechaCreacion."',md5('".$dominio.$fechaCreacion."'),'".$ftp_user."','".$ftp_pass."','".$ftp_server."')";

			if (mysqli_query($conexion, $consulta)){				
				//si se inserta correctamente, añado la relación a la tabla de usuariositios con el usuario administrador para luego modificar lo que haga falta			
				$usuario = $_SESSION['idUsuario'];	
				
				//traigo el último id insertado				
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$idUltimo = mysqli_fetch_array($registro);
				
				$consulta = "INSERT INTO usuariositios (idUsuario, idSitioWeb, menuContenidoWeb, menuConfiguracion, menuSecciones, menuParametros, menuUsuarios, menuInmobiliaria, menuInmoApuntes, menuInmoClientes, menuInmoInmuebles, menuInmoZonas) VALUES (".$usuarioPropietario.",'".$idUltimo[0]."','1','1','1','1','1','0','0','0','0','0')";
				
				//Lanzo la consulta
				if (mysqli_query($conexion, $consulta)){
					
					//si se ha insertado correctamente:					
					if ($ftp_user){  //se ha de crear la suscripción por que se está haciendo desde SuperAdministración

						include("./../../funcionesPlesk.php");
						 
						suscriptionPlesk("c", "", $nombre, $ftp_pass, $idUltimo[0]);
						
						echo $idUltimo[0];
						
						}else{							
							//si salió todo bien, creo la carpeta para la web y devuelvo el registro del sitio web creado
							if (mkdir("./../../../sitiosWeb/".md5($dominio.$fechaCreacion), 0700))
							{
								echo $idUltimo[0];
							}else{
								echo "KO";
							}
						}
				}
			}else{
				echo "KO";
			};
			
		break;

		
	}
?>