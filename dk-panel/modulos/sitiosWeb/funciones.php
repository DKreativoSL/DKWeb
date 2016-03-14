<?php

function importAllSectionsWithData(&$dataReturn,$idSitioWeb,$idSeccionPadre,$conexion,$idUsuarioAVincularDatos) {
	foreach ($dataReturn as $id=>$seccion) {
		$sql_insert = '
		INSERT INTO secciones SET
		idSitioWeb = ' . $idSitioWeb.', 
		nombre = "'.$seccion['nombre'].'",
		descripcion = "'.$seccion['descripcion'].'",
		seccion = '.$idSeccionPadre.',
		tipo = '.$seccion['tipo'].',
		privada = '.$seccion['privada'].',
		orden = ' . $seccion['orden'].',
		estado = ' .$seccion['estado']. ',
		ch_CampoTitulo=' .$seccion['ch_CampoTitulo']. ',
		ch_CampoSubTitulo=' .$seccion['ch_CampoSubTitulo']. ',
		ch_CampoCuerpo=' .$seccion['ch_CampoCuerpo']. ',
		ch_CampoCuerpoAvance=' .$seccion['ch_CampoCuerpoAvance']. ',
		ch_CampoFechaPublicacion=' .$seccion['ch_CampoFechaPublicacion']. ',
		ch_CampoImagen=' .$seccion['ch_CampoImagen']. ',
		ch_CampoArchivo=' .$seccion['ch_CampoArchivo']. ',
		ch_CampoURL=' .$seccion['ch_CampoURL']. ',
		ch_CampoCampoExtra=' .$seccion['ch_CampoCampoExtra']. ';';
		
		$result = mysqli_query($conexion, $sql_insert);
		if ($result === TRUE) {
			
			$registro = mysqli_query($conexion, "select last_insert_id()");				
			$row = mysqli_fetch_array($registro);
			$idPadre = $row[0];
			
			//INSERTAMOS LOS ARTICULOS
			if (!empty($seccion['articles'])) {
				foreach ($seccion['articles'] as $k=>$articulo) {
					
					$sql = 'INSERT INTO articulos SET ';
					$sql .= 'idSeccion='.$idPadre.', ';
					$sql .= 'idUsuario="'.$idUsuarioAVincularDatos.'", ';
					$sql .= 'titulo="'.$articulo['titulo'].'", ';
					$sql .= 'subtitulo="'.$articulo['subtitulo'].'", ';
					$sql .= 'fecha="'.$articulo['fecha'].'", ';
					$sql .= 'cuerpo="'.$articulo['cuerpo'].'", ';
					$sql .= 'cuerpoResumen="'.$articulo['cuerpoResumen'].'", ';
					$sql .= 'orden="'.$articulo['orden'].'", ';
					$sql .= 'imagen="'.$articulo['imagen'].'", ';
					$sql .= 'archivo="'.$articulo['archivo'].'", ';
					$sql .= 'url="'.$articulo['url'].'", ';
					$sql .= 'campoExtra="'.$articulo['campoExtra'].'", ';
					$sql .= 'estado='.$articulo['estado'].'; ';
					
					$result = mysqli_query($conexion, $sql);
					
				}
			}
			
			//INSERTAMOS LOS HIJOS CON DATOS
			if (!empty($seccion['childrens'])) {
				importAllSectionsWithData($seccion['childrens'],$idSitioWeb,$idPadre,$conexion,$idUsuarioAVincularDatos);	
			}
		} else {
			return false;
		}
	}
	return true;
}

function getAllSectionsWithData(&$dataReturn,$idSitioWeb,$parent,$conexion) {
	
	$sql = '
	SELECT *
	FROM secciones
	WHERE seccion = '.$parent.'
	AND idSitioWeb = '.$idSitioWeb.';';
	
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
		$dataReturn[$idSection]['estado'] 		= $section['estado'];
		
		$dataReturn[$idSection]['ch_CampoTitulo'] 			= $section['ch_CampoTitulo'];
		$dataReturn[$idSection]['ch_CampoSubTitulo'] 		= $section['ch_CampoSubTitulo'];
		$dataReturn[$idSection]['ch_CampoCuerpo'] 			= $section['ch_CampoCuerpo'];
		$dataReturn[$idSection]['ch_CampoCuerpoAvance'] 	= $section['ch_CampoCuerpoAvance'];
		$dataReturn[$idSection]['ch_CampoFechaPublicacion'] = $section['ch_CampoFechaPublicacion'];
		$dataReturn[$idSection]['ch_CampoImagen'] 			= $section['ch_CampoImagen'];
		$dataReturn[$idSection]['ch_CampoArchivo'] 			= $section['ch_CampoArchivo'];
		$dataReturn[$idSection]['ch_CampoURL'] 				= $section['ch_CampoURL'];
		$dataReturn[$idSection]['ch_CampoCampoExtra'] 		= $section['ch_CampoCampoExtra'];
		
		$dataReturn[$idSection]['childrens'] 	= array();
		$dataReturn[$idSection]['articles'] 	= array();

		getAllArticlesSection($dataReturn[$idSection]['articles'],$idSection,$conexion);

		// Llamada recursiva a la funcion para llenar los hijos de esta seccion
		getAllSectionsWithData($dataReturn[$idSection]['childrens'],$idSitioWeb,$idSection,$conexion);
	}
}

function getAllArticlesSection(&$dataReturn,$idSection,$conexion) {
	$sql = '
	SELECT a.*
	FROM articulos AS a
	LEFT JOIN secciones AS s ON s.id = a.idSeccion
	WHERE a.idSeccion = '.$idSection.';';

	$result = mysqli_query($conexion, $sql);
	$articles = array();
	$i=0;
	while($row = mysqli_fetch_array($result, MYSQL_ASSOC))
	{
		$articles[$i] = $row;
		$i++;
	}	
	
	foreach ($articles as $id=>$article) {
		
		//Guardamos id de la seccion 
		$idSection = $article['idSeccion'];
		
		$articleArray = array(
			'id'			=>	$article['id'],
			'idSeccion'		=>	$article['idSeccion'],
			'idUsuario'		=>	$article['idUsuario'],
			'titulo'		=>	$article['titulo'],
			'subtitulo'		=>	$article['subtitulo'],
			'fecha'			=>	$article['fecha'],
			'cuerpo'		=>	addslashes($article['cuerpo']),
			'cuerpoResumen'	=>	$article['cuerpoResumen'],
			'orden'			=>	$article['orden'],
			'imagen'		=>	$article['imagen'],
			'archivo'		=>	$article['archivo'],
			'url'			=>	$article['url'],
			'campoExtra'	=>	$article['campoExtra'],
			'estado'		=>	$article['estado']
		);
		
		$dataReturn[] = $articleArray;
	}
}

?>