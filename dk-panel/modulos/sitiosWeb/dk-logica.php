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
	
	function booleanToInt($string) {
		$boolean = 0;
		if (trim(strtolower($string)) == "true") {
			$boolean = 1;
		}
		return $boolean;
	}
	
	$return = array();
	
	switch ($accion) 
	{
		case 'crearUsuarioYVincularlo':
			
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			
			$email = mysqli_real_escape_string($conexion, $_POST['email']);
			$password = mysqli_real_escape_string($conexion, $_POST['password']);
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
			
			$consulta = "
			INSERT INTo usuarios SET 
			email='".$email."',
			clave='".md5($password)."', 
			nombre='".$nombre."', 
			nif='".$nif."', 
			direccion='".$direccion."', 
			cp='".$cp."', 
			provincia='".$provincia."', 
			poblacion='".$poblacion."', 
			tlf1='".$tlf1."', 
			tlf2='".$tlf2."', 
			comentarios='".$comentarios."',
			grupo='administrador'";
			
			if (mysqli_query($conexion, $consulta)){
				
				$idUsuarioRecienCreado = mysqli_insert_id($conexion);
				
				$consulta = "
				INSERT INTO usuariositios SET 
				idUsuario=".$idUsuarioRecienCreado.",
				idSitioWeb=".$idWebsite.",
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
				menuCorreos=".booleanToInt($menuPermisoCorreos);
				
				if (mysqli_query($conexion, $consulta)) {
					$return['status'] = 'OK';
				} else {
					$return['status'] = 'KO';
				}
			} else {
				$return['status'] = 'KO';
			}
			echo json_encode($return);
			
		break;
		case 'guardarUsuarioVinculado':
			
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			
			$email = mysqli_real_escape_string($conexion, $_POST['email']);
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
			WHERE id=".$idUsuario;
			
			if (mysqli_query($conexion, $consulta)){
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
				WHERE idUsuario =".$idUsuario." 
				AND idSitioWeb = " . $idWebsite;
				
				if (mysqli_query($conexion, $consulta)) {
					$return['status'] = 'OK';
				} else {
					$return['status'] = 'KO';
				}
			} else {
				$return['status'] = 'KO';
			}
			echo json_encode($return);
		break;
		case 'leeRegistroUsuarioWeb':
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "
			SELECT u.*, 
			us.menuContenidoWeb, us.menuSecciones, us.menuConfiguracion, 
			us.menuParametros, us.menuUsuarios, us.menuCorreos,
			us.menuInmobiliaria,
			us.menuInmoApuntes, us.menuInmoClientes, us.menuInmoInmuebles, us.menuInmoZonas
			FROM usuarios AS u
			LEFT JOIN usuariositios AS us ON (us.idUsuario = u.id)
			WHERE u.id = ".$idUsuario."
			AND us.idSitioWeb = ". $idWebsite;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				$tabla[$i] = array_map("utf8_encode", $row);
				$i++;
			}
			//echo $consulta;
			echo json_encode($tabla);
			
		break;
		case 'vincularUsuarioConWeb':
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			$idUsuario = str_replace('\\"', '', $_POST['idUsuario']);
			
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
			
			$consulta = "
			INSERT INTO usuariositios SET
			idUsuario=".$idUsuario.",
			idSitioWeb=".$idWebsite.",
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
			menuCorreos=".booleanToInt($menuPermisoCorreos);
			
			if (mysqli_query($conexion, $consulta)) {
				$return['status'] = 'OK';
			} else {
				$return['status'] = 'KO';
			}
			echo json_encode($return);
		break;
		case 'obtenerUsuariosParaDuplicarWeb':
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);

			$sql_usuarios = '
			SELECT u.*
			FROM usuarios AS u
			WHERE id NOT IN (
				SELECT u.id
				FROM usuariositios AS us
				LEFT JOIN usuarios AS u ON (us.idUsuario = u.id)
			)';
			$data_usuarios = mysqli_query($conexion, $sql_usuarios);
			
			$html_options = '';
			while ($info_usuarios = mysqli_fetch_array($data_usuarios,MYSQL_ASSOC)) {
				$html_options .= '<option value=\"'.utf8_encode($info_usuarios['id']).'\">'.utf8_encode($info_usuarios['nombre']).' ('.utf8_encode($info_usuarios['email']).')</option>';	
			}
			
			if (strlen($html_options) == 0) {
				$html_options .= '<option value=\"-1\">No hay usuarios libres para vincular la web</option>';
			}
			
			
			$return['status'] = 'OK';
			$return['data'] = $html_options;
			
			echo json_encode($return);
		break;
		case 'listaUsuariosNoVinculados':
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);

			$sql_usuarios = '
			SELECT u.*
			FROM usuarios AS u
			WHERE id NOT IN (
				SELECT u.id
				FROM usuariositios AS us
				LEFT JOIN usuarios AS u ON (us.idUsuario = u.id)
			)
			
			UNION
			
			SELECT u.* 
			FROM usuarios AS u
			LEFT JOIN usuariositios AS us ON (u.id = us.idUsuario)
			WHERE us.idSitioWeb <> '.$idWebsite;
			
			$data_usuarios = mysqli_query($conexion, $sql_usuarios);
			
			$html_options = '';
			while ($info_usuarios = mysqli_fetch_array($data_usuarios,MYSQL_ASSOC)) {
				$html_options .= '<option value=\"'.utf8_encode($info_usuarios['id']).'\">'.utf8_encode($info_usuarios['nombre']).' ('.utf8_encode($info_usuarios['email']).')</option>';	
			}
			
			$return['status'] = 'OK';
			$return['data'] = $html_options;
			
			echo json_encode($return);
		break;
		case 'obtenerInfoWebsite':
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);

			$sql_website = "SELECT * FROM sitiosweb WHERE id = " . $idWebsite. " LIMIT 1;";
			$data_website = mysqli_query($conexion, $sql_website);
			$info_website = mysqli_fetch_array($data_website,MYSQL_ASSOC);
			
			$return['status'] = 'OK';
			$return['website'] = utf8_encode($info_website['nombre']);
			
			echo json_encode($return);
		break;
		case 'desvincularUsuario':
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			
			$sql = 'DELETE FROM usuariositios WHERE idUsuario = ' . $idUsuario . ' AND idSitioWeb = ' . $idWebsite;
			if (mysqli_query($conexion, $sql)) {
				$return['status'] = 'OK';	
			} else {
				$return['status'] = 'KO';
			}
			echo json_encode($return);
		break;
		case 'obtenerInfoUsuarioADesvincular':
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			
			$sql_usuario = "SELECT * FROM usuarios WHERE id = " . $idUsuario. " LIMIT 1;";
			$data_usuario = mysqli_query($conexion, $sql_usuario);
			$info_usuario = mysqli_fetch_array($data_usuario,MYSQL_ASSOC);

			$sql_website = "SELECT * FROM sitiosweb WHERE id = " . $idWebsite. " LIMIT 1;";
			$data_website = mysqli_query($conexion, $sql_website);
			$info_website = mysqli_fetch_array($data_website,MYSQL_ASSOC);
			
			$return = array();
			$return['status'] = 'OK';
			$return['usuario'] = utf8_encode($info_usuario['nombre']);
			$return['website'] = utf8_encode($info_website['nombre']);
			
			echo json_encode($return);
			
		break;
		case 'listaUsuariosWeb':
					
			$idWebsite = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
					
			$consulta = "
			SELECT u.*
			FROM usuarios AS u
			LEFT JOIN usuariositios AS us ON (us.idUsuario = u.id)
			LEFT JOIN sitiosweb AS sw ON (sw.id = us.idSitioWeb)
			WHERE sw.id = ".$idWebsite;
			
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" title=\"Editar usuario\" data-toggle=\"tooltip\" onclick=\"modificaUsuarioWeb('.$row['id'].','.$idWebsite.')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" title=\"Eliminar usuario\" data-toggle=\"tooltip\" href=\"#\" onclick=\"eliminaUsuarioWeb('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				$desVincular = '<a class=\"desvincularUsuario\" title=\"Desvincular usuario con la web\" data-toggle=\"tooltip\" href=\"#\" onclick=\"popupDesvincularUsuario('.$row['id'].','.$idWebsite.')\"><i class=\"fa fa-chain-broken fa-2x\"></i></a>';
				
				$borra = '';
				
				$tabla.='{"idUsuario":"'.utf8_encode($row['id']).'","nombre":"'.utf8_encode($row['nombre']).'","email":"'.utf8_encode($row['email']).'","grupo":"'.utf8_encode($row['grupo']).'","acciones":"'.$desVincular.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);

			echo '{"data":['.$tabla.']}';
		break;
		case 'duplicarWebsite':
			$idRegistroWebOriginal = mysqli_real_escape_string($conexion, $_POST['idWebsite']);
			$idUsuarioAVincular = $_POST['idUsuarioAVincular'];
			$idUsuarioAVincular = str_replace("\\\"","",$idUsuarioAVincular);
			
			//echo 'idUsuario PHP: ' . $idUsuarioAVincular;
			
			//Seleccionamos la web a duplicar
			//Cogemos su información y la guardamos en una variable
			$website_original = array();
			$sql_selectWebsite = "SELECT * FROM sitiosweb WHERE id = ".$idRegistroWebOriginal;
			$data_selectWebsite = mysqli_query($conexion, $sql_selectWebsite);
			while($row = mysqli_fetch_array($data_selectWebsite,MYSQL_ASSOC))
			{
				$website_original = array_map('utf8_encode',$row);
				break;
			}
			//echo '<pre>'.print_r($website_original,true).'</pre>';
						
						
			//Creo la nueva web en la tabla sitiosweb
			$sql_insertNuevaWeb = "
			INSERT INTO sitiosweb SET 
			idUsuarioPropietario=".$idUsuarioAVincular.", 
			nombre='Copia web ".$website_original['nombre']."', 
			descripcion='".$website_original['descripcion']."', 
			dominio='".$website_original['dominio']."', 
			fechaCreacion='".date('Y-m-d h:i:s')."', 
			token='".md5($website_original['token'] . " - " . rand(10,1000))."', 
			ftp_server='".$website_original['ftp_server']."', 
			ftp_user='".$website_original['ftp_user']."', 
			ftp_pass='".$website_original['ftp_pass']."', 
			ftp_rutabase='".$website_original['ftp_rutabase']."', 
			css_tinymce='".$website_original['css_tinymce']."';";
			mysqli_query($conexion, $sql_insertNuevaWeb);
			
			//echo $sql_insertNuevaWeb;
			
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
			idUsuario=".$idUsuarioAVincular.",
			idSitioWeb=".$idSitioWebNuevo.",
			menuContenidoWeb=1,
			menuConfiguracion=1,
			menuSecciones=1,
			menuParametros=1,
			menuUsuarios=1,
			menuInmobiliaria=0,
			menuInmoApuntes=0,
			menuInmoClientes=0,
			menuInmoInmuebles=0,
			menuInmoZonas=0,
			menuCorreos=0,
			menuMigracion=0,
			menuComentarios=0,
			menuTrashSecciones=1,
			menuTrashArticulos=1,
			menuUpdates=0,
			menuFTP=1,
			menuAcademia=0;
			";
			
			mysqli_query($conexion, $sql_insertNuevoUsuarioSitios);
			$idUsuariosSitiosNuevo = mysqli_insert_id($conexion);
			//Obtengo todas las secciones de la vieja web y las creo en la nueva web con sus respectivos articulos
			
			$dataReturn = array();
			getAllSectionsWithData($dataReturn,$idRegistroWebOriginal,0,$conexion);
			
			if (!empty($dataReturn)) {
				importAllSectionsWithData($dataReturn,$idSitioWebNuevo,0,$conexion,$idUsuarioAVincular);
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
				$duplicarWeb = '<a class=\"iconDuplicar\" data-toggle=\"tooltip\" title=\"Duplicar web\" href=\"#\" onclick=\"popupDuplicarWeb('.$row['id'].')\"><i class=\"fa fa-copy fa-2x\"></i></a>';
				$edita = '<a href=\"#\" title=\"Editar web\" data-toggle=\"tooltip\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$borra = '<a class=\"delete\" title=\"Eliminar web\" data-toggle=\"tooltip\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				$usuarioWeb = '<a href=\"#\" title=\"Usuarios vinculados a la web\" data-toggle=\"tooltip\" class=\"listadoUsuariosWeb\" onclick=\"listadoUsuariosWeb('.$row['id'].')\"><i class=\"fa fa-users fa-2x\"></i></a>';
				
				$tabla.='{"nombre":"'.utf8_encode($row['nombre']).'","descripcion":"'.utf8_encode($row['descripcion']).'","dominio":"'.utf8_encode($row['dominio']).'","fechaCreacion":"'.utf8_encode($row['fechaCreacion']).'","token":"'.utf8_encode($row['token']).'","acciones":"'.$usuarioWeb.$duplicarWeb.$edita.$borra.'"},';		
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

			$ftp_user = $ftp_pass = $ftp_server = "";
		
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
			
			$ftp_user = $ftp_pass = $ftp_server = "";

			
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