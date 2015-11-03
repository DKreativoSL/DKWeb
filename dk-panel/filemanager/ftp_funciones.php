<?php
	session_start(); //inicializo sesión
	
	$accion = $_POST['accion'];
	
	switch ($accion) 
	{
		case 'leeDominio':
			echo $_SESSION['dominio'];
			break;
			
		case 'ftpLeeDirectorio':
			$tabla = '';
			$volver = '';
			
			$directorio = $_POST['ruta']; //httpdocs (inicial)
			$_SESSION["ftp_ruta"] = $directorio;
			
			$ftp_server = $_SESSION["ftp_server"];
			$ftp_user_name = $_SESSION["ftp_user_name"];
			$ftp_user_pass = $_SESSION["ftp_user_pass"];
			
			// conexión
			$conn_id = ftp_connect($ftp_server);
			
			// logeo
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 			 
			// conexión
			if ((!$conn_id) || (!$login_result)) { 
					echo "Conexión al FTP con errores al leer el directorio! <br>";
					echo "Intentando conectar al server: <$ftp_server> con el usuario: <$ftp_user_name>"; 
					exit; 
			} else {		   
					//echo "Conectado a $ftp_server, for user $ftp_user_name";
			}		
			$lista=ftp_nlist($conn_id,$directorio); //Devuelve un array con los nombres de ficheros


// Añado el primer valor que es el .. para volver  
		
		$directorios = explode("/", $directorio, -1);
		
		foreach ($directorios as $cadena) {
		    $volver .= "/".$cadena;
		}
		
		$valor = '<a href=\"#\" onclick=\"cargaDirectorio(\''.$volver.'\')\"> '.$volver.'..</a>';
		
		$tabla .=  "{";
		$tabla .=  '"nombre":"'.$valor.'",';
		$tabla .=  '"tamaño":"dir",';
		$tabla .=  '"fecha":"dir"';						
		$tabla .=  "},";

		//Primero muestro los DIRECTORIOS
				foreach ($lista as $clave=>$valor)
				{
					
					$tamano=number_format(((ftp_size($conn_id, $valor))/1024),2)." Kb"; 
					//Obtiene tamaño de archivo y lo pasa a KB
					if($tamano=="-0.00 Kb") // Si es -0.00 Kb se refiere a un directorio
					{ 
						$rutaFormato = str_replace($directorio, "", $valor);
						//$rutaFormato = str_replace("/", "", $valor);
						
						//asigno a valor llamada a la función y le quito la ruta actual que trae incluida						
						$valor = '<a href=\"#\" onclick=\"cargaDirectorio(\''.$valor.'\')\">'.$rutaFormato.'</a>';
												
						$tabla.=  "{";
						$tabla.=  '"nombre":"'.$valor.'",';
						$tabla.=  '"tamaño":"dir",';
						$tabla.=  '"fecha":"dir"';						
						$tabla.=  "},";
					}
				}
				
		//Segundo los FICHEROS
				foreach ($lista as $clave=>$valor)
				{
					$tamano=number_format(((ftp_size($conn_id, $valor))/1024),2)." Kb"; 
					//Obtiene tamaño de archivo y lo pasa a KB
					if($tamano=="-0.00 Kb") // Si es -0.00 Kb se refiere a un directorio
					{ 
						//Es directorio, pasando
					}else{
						$fecha=date("d/m/y H:i", ftp_mdtm($conn_id,$valor));
						
						//le quito a valor el directorio para que solo muestre el fichero concreto sin la ruta completa
						$valor = trim(str_replace($directorio, "", $valor));
						$valor = trim(str_replace("/", "", $valor));

						$valor = '<a rutaCompleta = \"'.$directorio.$valor.'\" id=\"'.str_replace(".", "_", $valor).'\" href=\"#\" onclick=\"muestraMenuContextual(\''.str_replace(".", "_", $valor).'\');\">'.$valor.'</a>';						
						
						$tabla.= "{";
						$tabla.=  '"nombre":"'.$valor.'",';
						$tabla.=  '"tamaño":"'.$tamano.'",';
						$tabla.=  '"fecha":"'.$fecha.'"';
						$tabla.=  "},";
						
					}
				
				}

			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
			
			// cerramos
			ftp_close($conn_id);	
		break;
		
		
		// CREAR DIRECTORIO
		case "ftpCreaDirectorio":
			$nuevaCarpeta = $_POST['nuevaCarpeta'];
			
			$ftp_server = $_SESSION["ftp_server"];
			$ftp_user_name = $_SESSION["ftp_user_name"];
			$ftp_user_pass = $_SESSION["ftp_user_pass"];
			
			
			// conexión
			$conn_id = ftp_connect($ftp_server); 
			
			// logeo
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
			 
			// conexión
			if ((!$conn_id) || (!$login_result)) { 
				   echo "Conexión al FTP con errores!";
				   echo "Intentando conectar a ".$ftp_server." con el usuario ".$ftp_user_name; 
				   exit; 
			   } else {
				   // Me situo en la ruta a crear el directorio
					ftp_chdir ($conn_id, $_SESSION["ftp_ruta"]); 
					$resultado =  ftp_mkdir ($conn_id, $nuevaCarpeta);
					
					// estado de subida/copiado
					if (!$resultado) { 
					    echo "Error al crear el directorio!";
				   	} else {
						echo "OK";
				   	}
			   }
			 
			// cerramos
			ftp_close($conn_id);	
			
		break;
		
		// elimina un fichero
		case "ftpEliminaFichero":
			$fichero = $_POST['eliminarFichero'];
			
			$ftp_server = $_SESSION["ftp_server"];
			$ftp_user_name = $_SESSION["ftp_user_name"];
			$ftp_user_pass = $_SESSION["ftp_user_pass"];
			
			
			// conexión
			$conn_id = ftp_connect($ftp_server); 
			
			// logeo
			$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass); 
			 
			// conexión
			if ((!$conn_id) || (!$login_result)) { 
				   echo "Conexión al FTP con errores!";
				   echo "Intentando conectar a ".$ftp_server." con el usuario ".$ftp_user_name; 
				   exit; 
			   }else{
					ftp_chdir ($conn_id, $_SESSION["ftp_ruta"]); 	
					$resultado =  ftp_delete ($conn_id ,$fichero);
	
					if (!$resultado) { 
					  	echo "KO";
					} else {
					 	echo $_SESSION["ftp_ruta"];
					}
			   }
			 
			// cerramos
			ftp_close($conn_id);	
			
		break;
		
		
	} // fin swith
	
	
	
?>