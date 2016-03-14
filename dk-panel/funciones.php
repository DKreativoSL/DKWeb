<?php

function formatDate($fecha) {
	
	if ($fecha != "" && $fecha != "0000-00-00" && $fecha != "0000-00-00 00:00:00") {
		$newDate = date("d-m-Y", strtotime($fecha));
	} else{
		$newDate = "0000-00-00";
	}
	
	return $newDate;
	
}

function formatDateWithTime($fecha) {
	
	if ($fecha != "" && $fecha != "0000-00-00" && $fecha != "0000-00-00 00:00:00") {
		$newDate = date("d-m-Y h:i:s", strtotime($fecha));
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

function getSeccionesWeb($conexion,$idSitioWeb) {
	$consulta = "
	SELECT id
	FROM secciones
	WHERE idSitioWeb = ".$idSitioWeb;
	$registro = mysqli_query($conexion, $consulta);
	$WHERE_IN = "";
	
	while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
	{
		if (strlen($WHERE_IN) > 0) $WHERE_IN .= ',';
		$WHERE_IN .= $row['id'];
	}
	return $WHERE_IN;
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

function obtenerArbolSecciones(&$dataReturn,$parent,$conexion,$idWebsite) {
	
	$sql = '
	SELECT id, nombre, descripcion, seccion, tipo, privada, orden
	FROM secciones
	WHERE seccion = '.$parent.'
	AND idSitioWeb = '.$idWebsite.'
	AND estado = 1
	ORDER BY orden ASC, id ASC';
	
	$result = mysqli_query($conexion, $sql);
	$infoSeccion = array();
	$i=0;
	while($row = mysqli_fetch_array($result, MYSQL_ASSOC))
	{
		$infoSeccion[$i] = $row;
		$i++;
	}
	foreach ($infoSeccion as $id=>$section) {
		
		//Guardamos id de la seccion 
		$idSection = $section['id'];
		
		$dataReturn[$idSection]['id'] 			= $idSection;
		$dataReturn[$idSection]['nombre'] 		= $section['nombre'];
		$dataReturn[$idSection]['descripcion'] 	= $section['descripcion'];
		$dataReturn[$idSection]['seccion'] 		= $section['seccion'];
		$dataReturn[$idSection]['tipo'] 		= $section['tipo'];
		$dataReturn[$idSection]['privada'] 		= $section['privada'];
		$dataReturn[$idSection]['orden'] 		= $section['orden'];
		$dataReturn[$idSection]['childrens'] 	= array();
		$dataReturn[$idSection]['articles'] 	= array();

		// Llamada recursiva a la funcion para llenar los hijos de esta seccion
		obtenerArbolSecciones($dataReturn[$idSection]['childrens'],$idSection,$conexion,$idWebsite);
	}
}

function crearMenuSecciones(&$_html,$infoSecciones) {
	foreach ($infoSecciones as $seccion) {
		
		$nombreSeccion = $seccion['nombre'];
		$mostrarToolTip = '';
		if (strlen($seccion['nombre']) > 15) {
			$nombreSeccion = trim(substr($seccion['nombre'],0,12)).'...';
			$mostrarToolTip = 'data-toggle="tooltip"';
		}
		
		if (!empty($seccion['childrens'])) {
			$flechaSubSeccion = '<span id="submenu'.$seccion['id'].'" class="arrow">';

			$_html .= '<li>';
			$_html .= '<a href="#" title="'.$seccion['nombre'].'" '.$mostrarToolTip.' onclick="cargaSeccion('.$seccion['id'].','.$seccion['tipo'].',\''.$seccion['nombre'].'\')"><i class="icon-pencil"></i> '.$nombreSeccion.' '.$flechaSubSeccion.'</span></a>';
			$_html .= '<ul id="menu'.$seccion['id'].'" class="sub-menu">';
			crearMenuSecciones($_html,$seccion['childrens']);
			$_html .= '</ul>';
			$_html .= '</li>';
			
		} else {
			
			$_html .= '<li>';
			$_html .= '<a href="#" title="'.$seccion['nombre'].'" '.$mostrarToolTip.' onclick="cargaSeccion('.$seccion['id'].','.$seccion['tipo'].',\''.$seccion['nombre'].'\')"><i class="icon-pencil"></i> '.$nombreSeccion.' </span></a>';
			$_html .= '<ul id="menu'.$seccion['id'].'" class="sub-menu"></ul>';
			$_html .= '</li>';
		}
	}
}


?>