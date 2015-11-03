<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	
	switch ($accion) 
	{
		
		case 'datosFTP':			
			$consulta = "
			SELECT ftp_server, ftp_user, ftp_pass 
			FROM sitiosweb 
			WHERE id = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);

			$infoSitio = array();
			$i=0;
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$infoSitio[$i] = $row;
				$i++;
			}
			
			echo json_encode($infoSitio);

		break;
	}
?>
