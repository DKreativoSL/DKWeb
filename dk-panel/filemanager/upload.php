<?php
	session_start(); //inicializo sesión

	$ftp_server = $_SESSION["ftp_server"];
	$ftp_user_name = $_SESSION["ftp_user_name"];
	$ftp_user_pass = $_SESSION["ftp_user_pass"];
	
	// conexión
	$cid = ftp_connect($ftp_server); 
	
	// logeo
	$resultado = ftp_login($cid, $ftp_user_name, $ftp_user_pass); 	
	
	// Comprobamos que se creo el Id de conexión y se pudo hacer el login
	if ((!$cid) || (!$resultado)) {
		echo "Fallo en la conexión ".$ftp_server; die;
	} else {
		echo "Conectado.";
	}
	
	// Cambiamos a modo pasivo (nosotros gestionamos)	
	ftp_pasv ($cid, true) ;

	// Tomamos el nombre del archivo a transmitir
	$local = $_FILES["archivo"]["tmp_name"];
	
	// Este es el nombre temporal del archivo mientras dura la transmisión
	$remoto = $_FILES["archivo"]["name"];

	// Juntamos la ruta del servidor con el nombre real del archivo
	ftp_chdir ($cid, $_SESSION["ftp_ruta"]); 

	if (ftp_put($cid, $remoto, $local, FTP_BINARY)) 
	{
		echo "OK";
	}else{		
		echo "KO";
	}
	
	//cerramos la conexión FTP
	ftp_close($cid);
	
?>