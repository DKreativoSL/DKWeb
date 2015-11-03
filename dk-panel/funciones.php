<?php

function formatDate($fecha) {
	
	if ($fecha != "" && $fecha != "0000-00-00" && $fecha != "0000-00-00 00:00:00") {
		$newDate = date("Y-m-d", strtotime($fecha));
	} else{
		$newDate = "0000-00-00";
	}
	
	return $newDate;
	
}

function getInmoPrivada($conexion,$idSitioWeb) {
	//tomo los parámetros de configuración del ftp
	$consulta = "SELECT inmo_rutaPrivada FROM sitiosweb WHERE id = ".$idSitioWeb;
	$registro = mysqli_query($conexion, $consulta);
	$row = mysqli_fetch_assoc($registro);
	return $row['inmo_rutaPrivada'];
}
function getInmoPublica($conexion,$idSitioWeb) {
	//tomo los parámetros de configuración del ftp
	$consulta = "SELECT inmo_rutaPublica FROM sitiosweb WHERE id = ".$idSitioWeb;
	$registro = mysqli_query($conexion, $consulta);
	$row = mysqli_fetch_assoc($registro);
	return $row['inmo_rutaPublica'];
}

function getCSS($conexion,$idSitioWeb) {
	//tomo los parámetros de configuración del ftp
	$consulta = "SELECT css_tinymce FROM sitiosweb WHERE id = ".$idSitioWeb;
	$registro = mysqli_query($conexion, $consulta);
	
	//Obtengo el registro
	$row = mysqli_fetch_assoc($registro);
	return $row;
}

function getColaboradores($conexion,$idSitioWeb) {
	$consulta = "
	SELECT idUsuario
	FROM usuariositios
	WHERE idSitioWeb = ".$idSitioWeb;
	$registro = mysqli_query($conexion, $consulta);
	$WHERE_IN = "";
	
	while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
	{
		if (strlen($WHERE_IN) > 0) $WHERE_IN .= ',';
		$WHERE_IN .= $row['idUsuario'];
	}
	return $WHERE_IN;
}

function getUsuario($conexion,$idUsuario) {
	$consulta = "
	SELECT nombre
	FROM usuarios
	WHERE id = ".$idUsuario;
	$registro = mysqli_query($conexion, $consulta);
	$WHERE_IN = "";
	
	while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
	{
		return $row['nombre'];
	}
}

function getEstado($estado) {
	
	switch ($estado) {
		case '1':
			return 'Publicado';
		break;
		case '2':
			return 'Programado';
		break;
		case '3':
			return 'Eliminado';
		break;
		case '4':
			return 'Spam';
		break;
		case '0':
		default:
			return 'Borrador';
		break;
	}
}


?>