<?php
	session_start(); //inicializo sesión
	
	
	include("./../../conexion.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
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
				$tabla[$i] = array_map(utf8_encode,$row);
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
				$tabla[$i] = array_map(utf8_encode,$row);
				$i++;
			}
			//echo $consulta;
			echo json_encode($tabla);
			
			break;
					
		/*Devuelvo todos los sitiosweb para listarlos*/
		case 'listaRegistros':
					
			$consulta = "SELECT * from sitiosweb where id = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
			$row = mysqli_fetch_array($registro);

			$tabla.= '{"nombre":"nombre","valor":"<input id=\"nombre\" type=\"text\" value=\"'.utf8_encode($row['nombre']).'\" /><button onclick=\"guarda(\'nombre\')\">Guardar</button>"},'.
					'{"nombre":"descripcion","valor":"<input id=\"descripcion\" type=\"text\" value=\"'.utf8_encode($row['descripcion']).'\" /><button onclick=\"guarda(\'descripcion\')\">Guardar</button>"},'.
					'{"nombre":"dominio","valor":"<input id=\"dominio\" type=\"text\" value=\"'.utf8_encode($row['dominio']).'\" /><button onclick=\"guarda(\'dominio\')\">Guardar</button>"},'.
					'{"nombre":"fechaCreacion","valor":"<input id=\"fechaCreacion\" type=\"text\" value=\"'.utf8_encode($row['fechaCreacion']).'\" />"},'.
					'{"nombre":"token","valor":"<input type=\"text\" value=\"'.utf8_encode($row['token']).'\" />"},'.
					'{"nombre":"ftp_rutabase","valor":"<input id=\"ftp_rutabase\" type=\"text\" value=\"'.utf8_encode($row['ftp_rutabase']).'\" /><button onclick=\"guarda(\'ftp_rutabase\')\">Guardar</button>"},'.
					'{"nombre":"ftp_server","valor":"<input id=\"ftp_server\" type=\"text\" value=\"'.utf8_encode($row['ftp_server']).'\" /><button onclick=\"guarda(\'ftp_server\')\">Guardar</button>"},'.
					'{"nombre":"ftp_user","valor":"<input id=\"ftp_user\" type=\"text\" value=\"'.utf8_encode($row['ftp_user']).'\" /><button onclick=\"guarda(\'ftp_user\')\">Guardar</button>"},'.
					'{"nombre":"ftp_pass","valor":"<input id=\"ftp_pass\" type=\"text\" value=\"'.utf8_encode($row['ftp_pass']).'\" /><button onclick=\"guarda(\'ftp_pass\')\">Guardar</button>"},'.
					'{"nombre":"css_tinymce","valor":"<input id=\"css_tinymce\" type=\"text\" value=\"'.utf8_encode($row['css_tinymce']).'\" /><button onclick=\"guarda(\'css_tinymce\')\">Guardar</button>"},'.
					'{"nombre":"inmo_rutaPublica","valor":"<input id=\"inmo_rutaPublica\" type=\"text\" value=\"'.utf8_encode($row['inmo_rutaPublica']).'\" /><button onclick=\"guarda(\'inmo_rutaPublica\')\">Guardar</button>"},'.
					'{"nombre":"inmo_rutaPrivada","valor":"<input id=\"inmo_rutaPrivada\" type=\"text\" value=\"'.utf8_encode($row['inmo_rutaPrivada']).'\" /><button onclick=\"guarda(\'inmo_rutaPrivada\')\">Guardar</button>"}';

			echo '{"data":['.$tabla.']}';
			break;
		
		
		//ACTUALMENTE NO ESTÁ FUNCIONAL	
		case 'elimina':
		
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "delete from sitiosweb where id='".$idRegistro;
			
			
			//HAY QUE ELIMINAR LA RELACIÓN WEB CON USUARIO
/*			//elimino la relación de usuario con sitio web
			if (mysqli_query($conexion, $consulta))
			{				
				if (mysqli_affected_rows($conexion) > 0){
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
				}
			} */
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$nombre = mysqli_real_escape_string($conexion, $_POST['nombre']);
			$valor = mysqli_real_escape_string($conexion, $_POST['valor']);
		
			$consulta = "UPDATE sitiosweb SET ".$nombre."='".$valor."' WHERE id='".$idSitioWeb."'";
				
			if (mysqli_query($conexion, $consulta)){
				echo "OK";
			}else{
				echo "KO";
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
			
			//si no trae usuario, le asigno el 1 que es Dkreativo
			if ($usuarioPropietario  < 1) $usuarioPropietario  = 1;
			
			$consulta = "INSERT INTO sitiosweb (idUsuarioPropietario, nombre, descripcion, dominio, fechaCreacion, token) VALUES ('".$usuarioPropietario."','".$nombre."','".$descripcion."','".$dominio."','".$fechaCreacion."',md5('".$dominio.$fechaCreacion."'))";

			if (mysqli_query($conexion, $consulta)){				
				//si se inserta correctamente, añado la relación a la tabla de usuariositios con el usuario administrador para luego modificar lo que haga falta			
				$usuario = $_SESSION['idUsuario'];	
				
				//traigo el último id insertado				
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$idUltimo = mysqli_fetch_array($registro);
				
				$consulta = "INSERT INTO usuariositios (idUsuario, idSitioWeb, menuContenidoWeb, menuConfiguracion, menuSecciones, menuParametros, menuUsuarios, menuInmobiliaria, menuInmoApuntes, menuInmoClientes, menuInmoInmuebles, menuInmoZonas) VALUES (".$usuarioPropietario.",'".$idUltimo[0]."','1','1','1','1','1','1','1','1','1','1')";
				
				//Lanzo la consulta
				if (mysqli_query($conexion, $consulta)){
					//si salió todo bien, creo la carpeta para la web y devuelvo el registro del sitio web creado
					if (mkdir("./../../../sitiosWeb/".md5($dominio.$fechaCreacion), 0700))
					{
						echo $idUltimo[0];
					}else{
						echo "KO al crear carpet";
						}
				}
			}else{
				echo "KO";
			};
			
		break;

		
	}
?>
