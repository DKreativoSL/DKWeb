<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include('funciones.php');
	$accion = $_POST['accion'];
	
	if (!isset($_SESSION['sitioWeb'])) {
		$idSitioWeb = -1;
	} else {
		$idSitioWeb = $_SESSION['sitioWeb'];	
	}
	
	switch ($accion) 
	{
		case 'duplicarWebsite':
			$idRegistroWebOriginal = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			
			//Seleccionamos la web a duplicar
			//Cogemos su información y la guardamos en una variable
			$website_original = array();
			$sql_selectWebsite = "SELECT * FROM sitiosweb WHERE id = ".$idRegistroWebOriginal;
			$data_selectWebsite = mysqli_query($conexion, $sql_selectWebsite);
			while($row = mysqli_fetch_array($data_selectWebsite,MYSQL_ASSOC))
			{
				$website_original = $row;
				break;
			}
			//echo '<pre>'.print_r($website_original,true).'</pre>';
						
						
			//Creo la nueva web en la tabla sitiosweb
			$sql_insertNuevaWeb = "
			INSERT INTO sitiosweb SET 
			idUsuarioPropietario=".$website_original['idUsuarioPropietario'].", 
			nombre='Copia web ".$website_original['nombre']."', 
			descripcion='".$website_original['descripcion']."', 
			dominio='".$website_original['dominio']."', 
			fechaCreacion='".$website_original['fechaCreacion']."', 
			token='".md5($website_original['token'])."', 
			ftp_server='".$website_original['ftp_server']."', 
			ftp_user='".$website_original['ftp_user']."', 
			ftp_pass='".$website_original['ftp_pass']."', 
			ftp_rutabase='".$website_original['ftp_rutabase']."', 
			css_tinymce='".$website_original['css_tinymce']."';";
			mysqli_query($conexion, $sql_insertNuevaWeb);
			$idSitioWebNuevo = mysqli_insert_id($conexion);

			
			//Buscamos en la tabla de usuariositios la relación entre el usuario y la web
			$usuarioSitios_original = array();
			$sql_selectUsuarioSitios = "SELECT * FROM usuariositios WHERE idSitioWeb = ".$website_original['id']. " AND idUsuario = ".$website_original['idUsuarioPropietario'];
			$data_selectUsuarioSitios = mysqli_query($conexion, $sql_selectUsuarioSitios);
			while($row = mysqli_fetch_array($data_selectUsuarioSitios,MYSQL_ASSOC))
			{
				$usuarioSitios_original = $row;
				break;
			}
			//echo '<pre>'.print_r($usuarioSitios_original,true).'</pre>';
			
			//Vinculo el usuario de la antigua web con la nueva web en usuariositios
			$sql_insertNuevoUsuarioSitios = "
			INSERT INTO usuariositios SET 
			idUsuario=".$website_original['idUsuarioPropietario'].",
			idSitioWeb=".$idSitioWebNuevo.",
			menuContenidoWeb=".$usuarioSitios_original['menuContenidoWeb'].",
			menuConfiguracion=".$usuarioSitios_original['menuConfiguracion'].",
			menuSecciones=".$usuarioSitios_original['menuSecciones'].",
			menuParametros=".$usuarioSitios_original['menuParametros'].",
			menuUsuarios=".$usuarioSitios_original['menuUsuarios'].",
			menuInmobiliaria=".$usuarioSitios_original['menuInmobiliaria'].",
			menuInmoApuntes=".$usuarioSitios_original['menuInmoApuntes'].",
			menuInmoClientes=".$usuarioSitios_original['menuInmoClientes'].",
			menuInmoInmuebles=".$usuarioSitios_original['menuInmoInmuebles'].",
			menuInmoZonas=".$usuarioSitios_original['menuInmoZonas'].",
			menuCorreos=".$usuarioSitios_original['menuCorreos'].",
			menuMigracion=".$usuarioSitios_original['menuMigracion'].",
			menuComentarios=".$usuarioSitios_original['menuComentarios'].",
			menuTrashSecciones=".$usuarioSitios_original['menuTrashSecciones'].",
			menuTrashArticulos=".$usuarioSitios_original['menuTrashArticulos'].",
			menuUpdates=".$usuarioSitios_original['menuUpdates'].",
			menuFTP=".$usuarioSitios_original['menuFTP'].",
			menuAcademia=".$usuarioSitios_original['menuAcademia'].";
			";
			
			mysqli_query($conexion, $sql_insertNuevoUsuarioSitios);
			$idUsuariosSitiosNuevo = mysqli_insert_id($conexion);
			//Obtengo todas las secciones de la vieja web y las creo en la nueva web con sus respectivos articulos
			
			$dataReturn = array();
			getAllSectionsWithData($dataReturn,$idRegistroWebOriginal,0,$conexion);
			
			if (!empty($dataReturn)) {
				importAllSectionsWithData($dataReturn,$idSitioWebNuevo,0,$conexion);
			}
			echo "OK";
		break;
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
				$duplicarWeb = '<a class=\"iconDuplicar\" href=\"#\" onclick=\"duplicarWeb('.$row['id'].')\"><i class=\"fa fa-copy fa-2x\"></i></a>';
				
				$tabla.='{"nombre":"'.utf8_encode($row['nombre']).'","descripcion":"'.utf8_encode($row['descripcion']).'","dominio":"'.utf8_encode($row['dominio']).'","fechaCreacion":"'.utf8_encode($row['fechaCreacion']).'","token":"'.utf8_encode($row['token']).'","acciones":"'.$duplicarWeb.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);

			echo '{"data":['.$tabla.']}';
		break;
		case 'elimina':
			$idRegistro = mysqli_real_escape_string($conexion, $_POST['id']);
			
			//Elimino los articulos de la web a eliminar
			$sql_eliminarArticulos = "
			DELETE FROM articulos
			WHERE idSeccion in (
				SELECT s.id 
				FROM secciones AS s
				WHERE s.idSitioWeb = ".$idRegistro."
			);
			";
			if (mysqli_query($conexion, $sql_eliminarArticulos)) {
				//Elimino las secciones
				$sql_eliminarSecciones = "DELETE FROM secciones WHERE idSitioWeb=".$idRegistro;
				if (mysqli_query($conexion, $sql_eliminarSecciones)) {
					//Elimino la relacion de la web con el usuario
					$sql_eliminarUsuarioSitios = "DELETE FROM usuariositios WHERE idSitioWeb=".$idRegistro;
					if (mysqli_query($conexion, $sql_eliminarUsuarioSitios)) {
						//Elimino la web
						$sql_eliminarWebsite = "DELETE FROM sitiosweb WHERE id=".$idRegistro;
						if (mysqli_query($conexion, $sql_eliminarWebsite)) {
							//Elimino el registro en plesk			
							include("./../../funcionesPlesk.php");
							suscriptionPlesk("r", "", "", "", $idRegistro);
							
							echo "OK";
							
						} else echo "KO";
					} else echo "KO";
				} else echo "KO";
			} else echo "KO";
			
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