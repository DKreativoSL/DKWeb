<?php
function duplicarSeccion($tipoDuplicacion,$nombre,$infoSeccion,$conexion) {
	switch ($tipoDuplicacion) {
		case '1': //SOLO SECCION
			$sql_insert = '
			INSERT INTO secciones SET
			idSitioWeb = ' . $infoSeccion[0]['idSitioWeb'].', 
			nombre = "'.$nombre.'",
			descripcion = "'.$infoSeccion[0]['descripcion'].'",
			seccion = '.$infoSeccion[0]['seccion'].',
			tipo = '.$infoSeccion[0]['tipo'].',
			privada = '.$infoSeccion[0]['privada'].',
			orden = ' . $infoSeccion[0]['orden'];
			
			$result = mysqli_query($conexion, $sql_insert);
			if ($result === TRUE) {
				$resultado = 'OK';
			} else {
				$resultado = 'KO';
			}
		break;
		case '2': //SOLO ESTRUCTURA
		
			$sql_insert = '
			INSERT INTO secciones SET
			idSitioWeb = ' . $infoSeccion[0]['idSitioWeb'].', 
			nombre = "'.$nombre.'",
			descripcion = "'.$infoSeccion[0]['descripcion'].'",
			seccion = '.$infoSeccion[0]['seccion'].',
			tipo = '.$infoSeccion[0]['tipo'].',
			privada = '.$infoSeccion[0]['privada'].',
			orden = ' . $infoSeccion[0]['orden'];
			
			$result = mysqli_query($conexion, $sql_insert);
			if ($result === TRUE) {
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				$idSeccionPadre = $row[0]; 
				
				
				$dataReturn = array();
				getAllSections($dataReturn,$infoSeccion[0]['id'],$conexion);
				$returnImport = importAllSections($dataReturn,$infoSeccion[0]['idSitioWeb'],$idSeccionPadre,$conexion);
				
				if ($returnImport) {
					$resultado = 'OK';
				} else {
					$resultado = 'KO';
					echo '<pre>'.print_r($dataReturn,true).'</pre>';	
				}
			}
		break;
		case '3': //ESTRUCTURA Y DATOS
		
			$sql_insert = '
			INSERT INTO secciones SET
			idSitioWeb = ' . $infoSeccion[0]['idSitioWeb'].', 
			nombre = "'.$nombre.'",
			descripcion = "'.$infoSeccion[0]['descripcion'].'",
			seccion = '.$infoSeccion[0]['seccion'].',
			tipo = '.$infoSeccion[0]['tipo'].',
			privada = '.$infoSeccion[0]['privada'].',
			orden = ' . $infoSeccion[0]['orden'];
			
			$result = mysqli_query($conexion, $sql_insert);
			if ($result === TRUE) {
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				$idSeccionPadre = $row[0]; 
				
				
				//INSERTAMOS LOS ARTICULOS DE LA SECCION PADRE
				$articulosSeccion = array();
				getAllArticlesSection($articulosSeccion,$infoSeccion[0]['id'],$conexion);
				if (!empty($articulosSeccion)) {
					foreach ($articulosSeccion as $k=>$articulo) {
						
						$sql = 'INSERT INTO articulos SET ';
						$sql .= 'idSeccion='.$idSeccionPadre.', ';
						$sql .= 'idUsuario="'.$articulo['idUsuario'].'", ';
						$sql .= 'titulo="'.$articulo['titulo'].'", ';
						$sql .= 'subtitulo="'.$articulo['subtitulo'].'", ';
						$sql .= 'fecha="'.$articulo['fecha'].'", ';
						$sql .= 'cuerpo="'.$articulo['cuerpo'].'", ';
						$sql .= 'cuerpoResumen="'.$articulo['cuerpoResumen'].'", ';
						$sql .= 'orden="'.$articulo['orden'].'", ';
						$sql .= 'imagen="'.$articulo['imagen'].'", ';
						$sql .= 'archivo="'.$articulo['archivo'].'", ';
						$sql .= 'url="'.$articulo['url'].'", ';
						$sql .= 'campoExtra="'.$articulo['campoExtra'].'"; ';
						
						$result = mysqli_query($conexion, $sql);
						
					}
				}
				
				//LLAMADAS RECURSIVAS PARA LOS HIJOS DE LA SECCION
				$dataReturn = array();
				getAllSectionsWithData($dataReturn,$infoSeccion[0]['id'],$conexion);
				$returnImport = importAllSectionsWithData($dataReturn,$infoSeccion[0]['idSitioWeb'],$idSeccionPadre,$conexion);
				if ($returnImport) {
					$resultado = 'OK';
				} else {
					$resultado = 'KO';
					echo '<pre>'.print_r($dataReturn,true).'</pre>';	
				}
			}
		break;
	}
	
	return $resultado;
	
}

function importAllSectionsWithData(&$dataReturn,$idSitioWeb,$idSeccionPadre,$conexion) {
	foreach ($dataReturn as $id=>$seccion) {
		$sql_insert = '
		INSERT INTO secciones SET
		idSitioWeb = ' . $idSitioWeb.', 
		nombre = "'.$seccion['nombre'].'",
		descripcion = "'.$seccion['descripcion'].'",
		seccion = '.$idSeccionPadre.',
		tipo = '.$seccion['tipo'].',
		privada = '.$seccion['privada'].',
		orden = ' . $seccion['orden'];
		
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
					$sql .= 'idUsuario="'.$articulo['idUsuario'].'", ';
					$sql .= 'titulo="'.$articulo['titulo'].'", ';
					$sql .= 'subtitulo="'.$articulo['subtitulo'].'", ';
					$sql .= 'fecha="'.$articulo['fecha'].'", ';
					$sql .= 'cuerpo="'.$articulo['cuerpo'].'", ';
					$sql .= 'cuerpoResumen="'.$articulo['cuerpoResumen'].'", ';
					$sql .= 'orden="'.$articulo['orden'].'", ';
					$sql .= 'imagen="'.$articulo['imagen'].'", ';
					$sql .= 'archivo="'.$articulo['archivo'].'", ';
					$sql .= 'url="'.$articulo['url'].'", ';
					$sql .= 'campoExtra="'.$articulo['campoExtra'].'"; ';
					
					$result = mysqli_query($conexion, $sql);
					
				}
			}
			
			//INSERTAMOS LOS HIJOS CON DATOS
			if (!empty($seccion['childrens'])) {
				importAllSectionsWithData($seccion['childrens'],$idSitioWeb,$idPadre,$conexion);	
			}
		} else {
			return false;
		}
	}
	return true;
}

function eliminarSeccionesArticulosRecursivamente(&$dataReturn,$conexion) {
	foreach ($dataReturn as $id=>$seccion) {
		$sql_insert = '
		UPDATE secciones SET
		estado = "eliminado"
		WHERE id = ' . $seccion['id'];
		$result = mysqli_query($conexion, $sql_insert);
		if ($result === TRUE) {
			if (!empty($seccion['childrens'])) {
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				$idPadre = $row[0];
				eliminarSeccionesArticulosRecursivamente($seccion['childrens'],$conexion);
			}
			if (!empty($seccion['articles'])) {
				
				foreach ($seccion['articles'] as $k=>$articulo) {
					$sql_insert = '
					UPDATE articulos SET
					estado = "eliminado"
					WHERE id = ' . $articulo['id'];
					$result = mysqli_query($conexion, $sql_insert);					
				}
			}
		} else {
			return false;
		}
	}
	return true;
}

function importAllSections(&$dataReturn,$idSitioWeb,$idSeccionPadre,$conexion) {
	foreach ($dataReturn as $id=>$seccion) {
		$sql_insert = '
		INSERT INTO secciones SET
		idSitioWeb = ' . $idSitioWeb.', 
		nombre = "'.$seccion['nombre'].'",
		descripcion = "'.$seccion['descripcion'].'",
		seccion = '.$idSeccionPadre.',
		tipo = '.$seccion['tipo'].',
		privada = '.$seccion['privada'].',
		orden = ' . $seccion['orden'];
		
		$result = mysqli_query($conexion, $sql_insert);
		if ($result === TRUE) {
			if (!empty($seccion['childrens'])) {
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				$idPadre = $row[0];
				importAllSections($seccion['childrens'],$idPadre,$conexion);
			}
		} else {
			return false;
		}
	}
	return true;
}

function getAllSections(&$dataReturn,$parent,$conexion) {
	
	$sql = '
	SELECT id, nombre, descripcion, seccion, tipo, privada, orden
	FROM secciones
	WHERE seccion = '.$parent;
	
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
		getAllSections($dataReturn[$idSection]['childrens'],$idSection,$conexion);
	}
}

function getAllSectionsWithData(&$dataReturn,$parent,$conexion) {
	
	$sql = '
	SELECT id, nombre, descripcion, seccion, tipo, privada, orden
	FROM secciones
	WHERE seccion = '.$parent;
	
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

		getAllArticlesSection($dataReturn[$idSection]['articles'],$idSection,$conexion);

		// Llamada recursiva a la funcion para llenar los hijos de esta seccion
		getAllSectionsWithData($dataReturn[$idSection]['childrens'],$idSection,$conexion);
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
			'campoExtra'	=>	$article['campoExtra']
		);
		
		$dataReturn[] = $articleArray;
	}
}


?>