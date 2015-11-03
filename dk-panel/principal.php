<?php
	session_start(); //inicializo sesión
	include("conexion.php");
	
	//también la acción que ha de venir para todas las funciones
	$accion = $_POST['accion'];
	
	switch ($accion)
	{
		case 'traeNombreUsuario':
			echo $_SESSION['nombreUsuario'];
		break;
		
		case 'cerrarSesion':
			// Destruir todas las variables de sesión.
			$_SESSION = array();

			// Si se desea destruir la sesión completamente, borre también la cookie de sesión.
			// Nota: ¡Esto destruirá la sesión, y no la información de la sesión!
			if (ini_get("session.use_cookies")) {
				$params = session_get_cookie_params();
				setcookie(session_name(), '', time() - 42000,
					$params["path"], $params["domain"],
					$params["secure"], $params["httponly"]
				);
			}
			// Finalmente, destruir la sesión.
			session_destroy();
			echo "OK";
				
		break;
		
		case 'accesoUsuario':
			$email = $_POST['email'];
			$clave = md5($_POST['clave']);
		
			$consulta = '
			SELECT id, grupo, nombre 
			FROM usuarios 
			WHERE email = "'.$email.'" 
			AND clave = "'.$clave.'" 
			LIMIT 1';
			
			$registro = mysqli_query($conexion, $consulta);
			
			$resultado = mysqli_fetch_array($registro);
						
			if (!$resultado['id']){		
				echo "KO".$consulta; //sino trae nada el registro digo que no es válido
			}else{
				//si el usuario es válido lo registro para guardarlo durante toda la sesión
				$_SESSION['idUsuario'] = $resultado['id'];
				$_SESSION['grupo'] = $resultado['grupo'];
				$_SESSION['nombreUsuario'] = $resultado['nombre'];
				echo "OK";
			}
			break;
		
		case 'traeSecciones':
			if (isset($_SESSION['sitioWeb'])) {
				$consulta = '
				SELECT * 
				FROM secciones 
				WHERE idSitioWeb = "'.$_SESSION['sitioWeb'].'"
				AND estado = 1
				ORDER BY seccion ASC, id';
				
				$registro = mysqli_query($conexion, $consulta);
							
				$tabla = array(); //creamos un array
	
				//guardamos en un array multidimensional todos los datos de la consulta
				$i=0;
				
				while($row = mysqli_fetch_array($registro))
				{
					$tabla[$i] =  array("id"=>$row['id'], "nombre"=>$row['nombre'], "tipo"=>$row['tipo'], "seccionPadre"=>$row['seccion']);
					$i++;
				}	
				echo json_encode($tabla);
			} else {
				echo json_encode(array('null'));
			}
		break;
			
		case 'cargaMenuLateral':
			
			$menu ='<li><a href="index.php"><i class="icon-monitor"></i><span class="title">Escritorio</span></a></li>';
			
			if (isset($_SESSION['sitioWeb'])) {
			
				$consulta = '
				SELECT * 
				FROM usuariositios 
				WHERE idSitioWeb = "'.$_SESSION['sitioWeb'].'" 
				AND idUsuario = "'.$_SESSION['idUsuario'].'"';
				
				$registro = mysqli_query($conexion, $consulta);
							
				$tabla = array(); //creamos un array
	
				//guardamos en un array multidimensional todos los datos de la consulta
				$i=0;
				
				$row = mysqli_fetch_array($registro);
						
				/*Compruebo los permisos del usuario y cargo en el menú las opciones que tiene activas*/
				if ($row['menuContenidoWeb'] == true){
					$menu .= '<li><a id="menuContenidoWeb" href="#"><i class="icon-globe"></i><span class="title">Contenido web</span><span class="arrow "></span></a><ul id="seccionesWeb" class="sub-menu"></ul></li>';
				}
	
					/*Si tiene activa inmobiliaria, compruebo el resto de menús disponibles*/
				if ($row['menuInmobiliaria'] == true){
					$menu.= '<li id="menuInmobiliaria"><a href="javascript:;"><i class="icon-home"></i><span class="title">Inmobiliaria</span><span class="arrow "></span></a><ul id="menuInmobiliariaSecciones" class="sub-menu">';
							
					if ($row['menuInmoApuntes'] == true){
						$menu .= '<li id="menu_inmoApuntes" class="menuOptions" onclick="cargaContenido(\'inmobiliariaApuntes\')"><a href="#"><i class="icon-pencil"></i>Apuntes</a></li>';
					}
						
					if ($row['menuInmoClientes'] == true){
						$menu.= '<li id="menu_inmoClientes" class="menuOptions" onclick="cargaContenido(\'inmobiliariaClientes\')"><a href="#"><i class="icon-users"></i>Clientes</a></li>';
					}
					
					if ($row['menuInmoInmuebles'] == true){
						$menu .= '<li id="menu_inmoInmuebles" class="menuOptions" onclick="cargaContenido(\'inmobiliariaInmuebles\')"><a href="#"><i class="icon-home"></i>Inmuebles</a></li>';
					}
	
					if ($row['menuInmoZonas'] == true){
						$menu .= '<li id="menu_inmoZonas" class="menuOptions" onclick="cargaContenido(\'inmobiliariaZonas\')"><a href="#"><i class="icon-pointer"></i>Zonas</a></li>';
					}
					$menu .= '</ul></li>';
						
				}

				if ($row['menuAcademia'] == true){
					$menu.= '<li id="academiaAlumnos"><a href="javascript:;"><i class="icon-home"></i><span class="title">Academia</span><span class="arrow "></span></a><ul id="menuAcademiaSecciones" class="sub-menu">';
					$menu .= '<li id="menu_academiaCursos" class="menuOptions" onclick="cargaContenido(\'academiaCursos\')"><a href="#"><i class="icon-pencil"></i>Cursos</a></li>';
					$menu .= '<li id="menu_academiaAlumnos" class="menuOptions" onclick="cargaContenido(\'academiaAlumnos\')"><a href="#"><i class="icon-pencil"></i>Alumnos</a></li>';
					$menu .= '</ul></li>';
				}
					
				
				/*Si tiene activa la configuración, compruebo el resto de menús disponibles*/
				if ($row['menuConfiguracion'] == true){
	//				$menu .= '<li id="menuConfiguracion" class="heading"><h3>CONFIGURACIÓN</h3></li><ul id="menuInmobiliariaSecciones" class="sub-menu">';
					$menu.= '<li id="menuConfiguracion"><a href="javascript:;"><i class="icon-home"></i><span class="title">Configuración</span><span class="arrow "></span></a><ul id="menuConfiguracionSecciones" class="sub-menu">';
					
					if ($row['menuSecciones'] == true){
						$menu .= '<li id="menu_secciones" class="menuOptions" onclick="cargaContenido(\'secciones\')"><a href="#"><i class="icon-list"></i><span class="title">Secciones</span></a></li>';
					}
					
					if ($row['menuParametros'] == true){
						$menu .= '<li id="menu_configuracion" class="menuOptions" onclick="cargaContenido(\'configuracion\')"><a href="#"><i class="icon-settings"></i><span class="title">Parámetros</span></a></li>';
					}
					
					if ($row['menuUsuarios'] == true){
						$menu .= '<li id="menu_usuarios" class="menuOptions" onclick="cargaContenido(\'usuarios\')"><a href="#"><i class="icon-users"></i><span class="title">Usuarios</span></a></li>';
					}
					if ($row['menuComentarios'] == true){
						$menu .= '<li id="menu_comentarios" class="menuOptions" onclick="cargaContenido(\'comentarios\')"><a href="#"><i class="fa fa-comments-o"></i><span class="title">Comentarios</span></a></li>';
					}
					if ($row['menuTrashSecciones'] == true){
						$menu .= '<li id="menu_trashSecciones" class="menuOptions" onclick="cargaContenido(\'trashSecciones\')"><a href="#"><i class="fa fa-trash-o"></i><span class="title">Papelera secciones</span></a></li>';
					}
					if ($row['menuTrashArticulos'] == true){
						$menu .= '<li id="menu_trashArticulos" class="menuOptions" onclick="cargaContenido(\'trashArticulos\')"><a href="#"><i class="fa fa-trash-o"></i><span class="title">Papelera articulos</span></a></li>';
					}
					if ($row['menuCorreos'] == true){
						$menu .= '<li id="menu_correos" class="menuOptions" onclick="cargaContenido(\'correos\')"><a href="#"><i class="fa fa-envelope-o"></i><span class="title">Correos</span></a></li>';
					}
					if ($row['menuMigracion'] == true){
						$menu .= '<li id="menu_migracion" class="menuOptions" onclick="cargaContenido(\'migracion\')"><a href="#"><i class="fa fa-database"></i><span class="title">Migración</span></a></li>';
					}
					if ($row['menuUpdates'] == true){
						$menu .= '<li id="menu_consultasMasivas" onclick="cargaContenido(\'consultasMasivas\')"><a href="#"><i class="fa fa-random"></i><span class="title">Consultas masivas</span></a></li>';
					}
					if ($row['menuFTP'] == true){
						$menu .= '<li id="menu_ftp" onclick="cargaContenido(\'ftp\')"><a href="#"><i class="fa fa-random"></i><span class="title">FTP</span></a></li>';
					}
					$menu .= '</ul></li>';					
				}
				
			}
			
			//va a mano, si el usuario es el 1, automáticamente muestro datos de SUPER ADMINISTRADOR
			if ($_SESSION['idUsuario'] == "1"){
				$menu .= '<li id="menuAdministradores" class="heading">ADMINISTRADORES DKREATIVO</li>';
				$menu .= '<li id="menu_usuariosAdmin" class="menuOptions" onclick="cargaContenido(\'usuariosAdmin\')"><a href="#"><i class="icon-users"></i><span class="title">Usuarios Admin</span></a></li>';
				$menu .= '<li id="menu_sitiosWeb" class="menuOptions" onclick="cargaContenido(\'sitiosWeb\')"><a href="#"><i class="icon-users"></i><span class="title">Sitios Web</span></a></li>';
				$menu .= '<li id="menu_updates" class="menuOptions" onclick="cargaContenido(\'updates\')"><a href="#"><i class="fa fa-random"></i><span class="title">Actualizaciones</span></a></li>';
				$menu .= '<li id="menu_updates" class="menuOptions" onclick="cargaContenido(\'crearWebsite\')"><a href="#"><i class="fa fa-plus"></i><span class="title">Crear web</span></a></li>';
			}
			
					
			echo json_encode($menu);
			break;
			
			
		case 'traeSitiosWebUsuario':
			
			$consulta = '
			SELECT sitiosweb.id, nombre, dominio, token
			FROM sitiosweb, usuariositios
			WHERE sitiosweb.id = usuariositios.idSitioWeb
			AND usuariositios.idUsuario= "'.$_SESSION['idUsuario'].'"';
			
			$registro = mysqli_query($conexion, $consulta);
						
			$tabla = array(); //creamos un array

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			
			while($row = mysqli_fetch_array($registro))
			{
				if ($i == 0){
					//Cargo en la sesión los parámetros del usuario actual
					$_SESSION['sitioWeb'] = $row['id'];
					$_SESSION['dominio'] = $row['dominio'];
					$_SESSION['token'] = $row['token'];					
				}
				
				$tabla[$i] =  array("id"=>$row['id'], "nombre"=>$row['nombre']);
				$i++;
			}
			
			echo json_encode($tabla);

			break;
			
		case 'cambiaSitioWeb':
			$idCambiaSitioWeb = $_POST['idCambiaSitioWeb'];
			
			$consulta = '
			SELECT sitiosweb.id, nombre, dominio, token FROM sitiosweb, usuariositios 
			WHERE sitiosweb.id = usuariositios.idSitioWeb 
			AND usuariositios.idUsuario= "'.$_SESSION['idUsuario'].'" 
			AND usuariositios.idSitioWeb = "'.$idCambiaSitioWeb.'"';
			
			$registro = mysqli_query($conexion, $consulta);
	
			$row = mysqli_fetch_array($registro);
	
			//Cargo en la sesión los parámetros del usuario actual
			$_SESSION['sitioWeb'] = $row['id'];
			$_SESSION['dominio'] = $row['dominio'];
			$_SESSION['token'] = $row['token'];					
	
			$tabla = array("id"=>$row['id'], "nombre"=>$row['nombre']);
			
			echo json_encode($tabla);
		break;		
	}
?>