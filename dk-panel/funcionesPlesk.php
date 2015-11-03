<?php
	
	/*	FUNCIÓN suscriptionPlesk									CARLOS GUERRA 11/05/2015
		^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		RECIBE:		accion: 	-c crear, -u modificar, -r eliminar.
					cliente: 	el propietario al que va el dominio, por defecto DkWebAutomatico (en blanco)
					login: 		Usuario de acceso al FTP
					passwd: 	la contraseña del FTP
					token:		para corroborar a qué cuenta/dominio ha de ir
		DEVUELVE:	OK y KO	*/
	
	function suscriptionPlesk($accion, $cliente, $login, $pass, $idWeb)
	{	
		$conexionPlesk = mysqli_connect(cServidor, cUsuario, cPass, cBd);
		$consulta = "SELECT ftp_server FROM sitiosweb WHERE id = '".$idWeb."'";
		$registro = mysqli_query($conexionPlesk, $consulta);

		$row = mysqli_fetch_array($registro);
		sLog ("Servidor ".$row['ftp_server']);
		sLog ("Consulta ".$consulta);		
		//si el token devuelve un servidor continúo
		if ($row){
			//monto la cadena ssh
			$comando = "suscription -".$accion;
			
			//sino trae cliente pongo uno por defecto
			if ($cliente == "") 
			{
				$cliente = "DkWebAutomatico";
			}
			
			$comando .= " -owner ".$cliente;
			
			//si tiene hosting (ahora mismo no parametrizable) y también la IP del ftp_server actual
			$comando .= " -hosting 'true' -ip ".gethostbyname($row['ftp_server']);
			
			$comando .= " -login '".$login."' -passwd '".$pass."'";
			xPlesk($comando, $row['ftp_server']);
		}else
		{
			sLog ("ERROR Suscripcion ".$accion);
			}
	}
	
	
	/*	FUNCIÓN MAIL									CARLOS GUERRA 11/05/2015
		^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		RECIBE:		accion: 	crear, modificar, eliminar.
					correo: 	el email completo con mail@dominio.com
					passwd: 	la contraseña del correo (ha de ser verificada antes de enviar que sea segura)
					cuota: 		100M espacio del buzón	
					webmail: 	(true, false) si el usuario tendrá acceso desde webmail o no
					token:		para corroborar a qué cuenta/dominio ha de ir
		DEVUELVE:	OK y KO	*/
	
	function mailPlesk($accion, $correo, $pass, $cuota, $webmail, $idWeb)
	{	
		$conexionPlesk = mysqli_connect(cServidor, cUsuario, cPass, cBd);
		$consulta = "SELECT ftp_server FROM sitiosweb WHERE id = '".$idWeb."'";
		$registro = mysqli_query($conexion, $consulta);

		$row = mysqli_fetch_array($registro);
		
		//si el token devuelve un servidor continúo
		if ($row){		
			//monto la cadena ssh
			$comando = "mail -".$accion;
			$comando .= $correo ." -mailbox true -passwd '".$pass."'";
	
			if ($cuota){
				$comando .= "-mbox_quota ".$cuota;			
				}
			
			if ($webmail){
				$comando .= " -cp-access true";
				}
		
			xPlesk($comando, $row['ftp_server']);
		}
	}
	
	/*	FUNCIÓN xPlesk										CARLOS GUERRA	 11/05/2015
		^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		RECIBE: 	comando: 	la linea completa de comando (suscripción, mail, etc...)
					servidor:	el servidor en el cual ha de lanzarse el SSH 
		DEVUELVE:	OK y KO		*/
	function xPlesk($comando, $servidor){		
		sLog ($servidor. " " .$comando);
		//según el servidor 
		if ($servidor == "vps48028.ovh.net"){
			//es en local
			$comando = "/usr/local/psa/bin/".$comando;
		}else{
			//es en un server externo al propio
			$comando = "ssh root@".$servidor." /usr/local/psa/bin/".$comando;
		}
	
		//guardo en el fichero SH la sentencia SSH completa
		$file = fopen("./../../../plesk.sh", "a");
		fwrite($file, $comando . PHP_EOL);
		fclose($file);				
	}

		
	/*	FUNCIÓN sLog										CARLOS GUERRA	 11/05/2015
		^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
		RECIBE: 	log:		texto de la acción realizada
		DEVUELVE:	un fichero en el que registra, fecha/hora con el usuario y la acción */
	function sLog($log){			
		//guardo en el fichero SH la sentencia SSH completa
		$file = fopen("./log.txt", "a");
		fwrite($file, date("Y-m-d H:i:s")." ".$log . PHP_EOL);
		fclose($file);	
	}
	
?>