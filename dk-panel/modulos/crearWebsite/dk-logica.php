<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	$accion = $_POST['accion'];
	
	switch ($accion) 
	{
		case 'comprobarDominioWeb':
			$dominioWeb = mysqli_real_escape_string($conexion, $_POST['dominioWeb']);
			$sql = '
			SELECT id 
			FROM sitiosweb 
			WHERE dominio = "'.$dominioWeb.'"
			LIMIT 1';
			
			$registro = mysqli_query($conexion, $sql);				
			$idCliente = mysqli_fetch_array($registro,MYSQLI_NUM);
			if (!empty($idCliente)) {
				$idCliente = $idCliente[0];
				if ($idCliente <= 0) {
					$idCliente = -1;
				}
			} else {
				$idCliente = -1;
			}
			
			if ($idCliente == -1) {
				//No existe el cliente en la base de datos
				echo "0";
			} else {
				//El cliente ya existe en la base de datos con ese email
				echo "1";
			}
		break;	
		case 'comprobarEmailUsuario':
			$clienteEmail = mysqli_real_escape_string($conexion, $_POST['email']);
			$sql = '
			SELECT id 
			FROM usuarios 
			WHERE email = "'.$clienteEmail.'"
			LIMIT 1';
			
			$registro = mysqli_query($conexion, $sql);				
			$idCliente = mysqli_fetch_array($registro,MYSQLI_NUM);
			if (!empty($idCliente)) {
				$idCliente = $idCliente[0];
				if ($idCliente <= 0) {
					$idCliente = -1;
				}
			} else {
				$idCliente = -1;
			}
			
			if ($idCliente == -1) {
				//No existe el cliente en la base de datos
				echo "0";
			} else {
				//El cliente ya existe en la base de datos con ese email
				echo "1";
			}
		break;		
		case 'crearWebsite':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$clienteNombre = mysqli_real_escape_string($conexion, $_POST['nombreCliente']);
			$clienteNif = mysqli_real_escape_string($conexion, $_POST['nif']);
			$clienteEmail = mysqli_real_escape_string($conexion, $_POST['email']);
			$clientePassword = $_POST['clave'];
			
			$websiteNombre = mysqli_real_escape_string($conexion, $_POST['nombreWebsite']);
			$websiteDominio = mysqli_real_escape_string($conexion, $_POST['dominio']);
			$websiteDescripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
			
			
			$fechaAlta = date("Y-m-d H:i:s");
			$md5Password = md5($clientePassword);
			$md5Token = md5($fechaAlta.$websiteNombre.$websiteDominio);
			
			$errors = 0;
			
			//Creamos el usuario
			$sql = 'INSERT INTO usuarios SET ';
			$sql .= 'email="'.$clienteEmail.'", ';
			$sql .= 'clave="'.$md5Password.'", ';
			$sql .= 'nombre="'.$clienteNombre.'", ';
			$sql .= 'direccion="", ';
			$sql .= 'cp="", ';
			$sql .= 'poblacion="", ';
			$sql .= 'provincia="", ';
			$sql .= 'tlf1="", ';
			$sql .= 'tlf2="", ';
			$sql .= 'comentarios="", ';
			$sql .= 'nif="'.$clienteNif.'", ';
			$sql .= 'fechaAlta="'.$fechaAlta.'",'; 
			$sql .= 'fechaBaja="0000-00-00",';
			$sql .= 'grupo="administrador";';
			if (mysqli_query($conexion, $sql)) {
				//Obtenemos el id del usuario creado
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$idCliente = mysqli_fetch_array($registro,MYSQLI_NUM);
				$idCliente = $idCliente[0];
				
				if ($idCliente > 0) {
					//Creamos el sitio web
					$sql = 'INSERT INTO sitiosweb SET idUsuarioPropietario="'.$idCliente.'",'; 
					$sql .= 'nombre="'.$websiteNombre.'", ';
					$sql .= 'descripcion="'.$websiteDescripcion.'", ';
					$sql .= 'dominio="'.$websiteDominio.'", ';
					$sql .= 'fechaCreacion="'.$fechaAlta.'", ';
					$sql .= 'token="'.$md5Token.'";';
					if (mysqli_query($conexion, $sql)) {
						$registro = mysqli_query($conexion, "select last_insert_id()");				
						$idWebsite = mysqli_fetch_array($registro,MYSQLI_NUM);
						$idWebsite = $idWebsite[0];
						
						if ($idWebsite > 0) {
							$sql = 'INSERT INTO usuariositios SET idUsuario='.$idCliente.', idSitioWeb='.$idWebsite.', '; 
							$sql .= 'menuContenidoWeb=1, ';
							$sql .= 'menuConfiguracion=1, ';
							$sql .= 'menuSecciones=1, ';
							$sql .= 'menuParametros=1, ';
							$sql .= 'menuUsuarios=1, ';
							$sql .= 'menuInmobiliaria=1, ';
							$sql .= 'menuInmoApuntes=1, ';
							$sql .= 'menuinmoClientes=1, ';
							$sql .= 'menuInmoInmuebles=1, ';
							$sql .= 'menuInmoZonas=1, ';
							$sql .= 'menuCorreos=1, ';
							$sql .= 'menuMigracion=1, ';
							$sql .= 'menuComentarios=1, ';
							$sql .= 'menuTrashSecciones=1, ';
							$sql .= 'menuTrashArticulos=1, ';
							$sql .= 'menuUpdates=1,';
							$sql .= 'menuFTP=1,';
							$sql .= 'menuAcademia=1;';
							if (!mysqli_query($conexion, $sql)) {
								$errors++;
							}
						} else {
							$errors++;
						}
					} else {
						$errors++;
					}
				} else {
					$errors++;
				}
			} else {
				$errors++;
			}
			
			if ($errors == 0) {
				echo "OK";
			} else {
				echo "KO";
			}
			
		break;		
	}
?>
