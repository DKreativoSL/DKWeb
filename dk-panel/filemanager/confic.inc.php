<?php
	
	include("./../conexion.php");
	//tomo los parámetros de configuración del ftp
	$consulta = "SELECT dominio, ftp_server, ftp_user, ftp_pass, ftp_rutabase FROM sitiosweb WHERE id = ".$_SESSION['sitioWeb'];
	$registro = mysqli_query($conexion, $consulta);
	
	$row = mysqli_fetch_assoc($registro);
	
	//lo guardo en variable de sesión
	$_SESSION["ftp_server"] = $row['ftp_server'];
	$_SESSION["ftp_user_name"] = $row['ftp_user'];
	$_SESSION["ftp_user_pass"] = $row['ftp_pass'];
	$_SESSION["ftp_rutabase"] = $row['ftp_rutabase'];
	
	
	$token = $_SESSION['token'];
	
	$_SESSION['canAccessFTP'] = false;
	
	if ((strlen($_SESSION["ftp_server"])>0) && (strlen($_SESSION["ftp_user_name"])>0) && (strlen($_SESSION["ftp_user_pass"])>0) ) {
		$_SESSION['canAccessFTP'] = true;	
	}
	

?>