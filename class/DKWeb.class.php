<?php

class DKWeb {
	
	public $tokenWebiste 	= null;
	public $domain 			= null;
	
	private $mysqlLink 		= null;
	private $idWebsite 		= null;
	
	private $typeOutput 	= null;
	
    /*
     * __construct()
     * @description Constructor de la class DKWeb
     * @param	$mysqlLink		Link  	# Link a la class MySQL
     * @param	$tokenWebiste	String  # Token de la pagina
     * @param	$domain			String  # Dominio de la pagina
     * @param	$typeOutput		String  # Tipo de salida de datos
     * @access	public
     * */
	function __construct(&$mysqlLink,$idWebsite,$typeOutput='array') {
		$this->mysqlLink = $mysqlLink;
		
		$this->typeOutput = $typeOutput;
		$this->idWebsite = $idWebsite;
	}
	/* 
	 * urlFriendly()
	 * @descripcion devuelve un valor sin acentos ni simbolos raros para utilizarse en las urls amigables
	 * @param	$url			String	# nombre de la sección o publicación actual.
	*/
	public function urlFriendly($url){
		// Tranformamos todo a minusculas	
		$url = strtolower($url);
		
		//Rememplazamos caracteres especiales latinos	
		$find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');	
		$repl = array('a', 'e', 'i', 'o', 'u', 'n');	
		$url = str_replace ($find, $repl, $url);
		
		// Añadimos los guiones	
		$find = array(' ', '&', '\r\n', '\n', '+');
		$url = str_replace ($find, '-', $url);
		
		// Eliminamos y Reemplazamos demás caracteres especiales	
		$find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');	
		$repl = array('', '-', '');	
		$url = preg_replace ($find, $repl, $url);	
		return $url;
		}
		
    /*
     * getDomain()
     * @description Devuelve el dominio de un usario
     * @param	$idUser	Integer  # Id del usuario
     * @access	public
     * */
	public function getDomain($idSitioWeb) {
		if (is_numeric($idSitioWeb)) {
			$sql = "
			SELECT id, dominio
			FROM sitiosweb
			WHERE id = '".$idSitioWeb."'
			";
			$dataReturn = array();
			$dataWeb = $this->mysqlLink->fetch($sql);

			if (!empty($dataWeb['dominio'])) {
				return $dataWeb['dominio'];
			} else {
				return 'domain = null';
			}
		} else {
			return 'idUser isnt numeric';
		}
	}

    /*
     * getEmail()
     * @description Devuelve el correo de un usario
     * @param	$idUser	Integer  # Id del usuario
     * @access	public
     * */
	public function getEmail($idUser) {
		if (is_numeric($idUser)) {
			$sql = "
			SELECT id, email
			FROM usuarios
			WHERE id = '".$idUser."'
			";
			$dataReturn = array();
			$dataUsers = $this->mysqlLink->fetchAll($sql);
			foreach ($dataUsers as $id=>$user) {
				$dataReturn['id'] 		= $user['id'];
				$dataReturn['email'] 	= $user['email'];
			}
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idUser isnt numeric';
		}
	}
	
    /*
     * getAllSections()
     * @description Devuelve todas secciones, secciones hijas y sus articulos
     * @param	&$dataReturn	Link Array  # Array de retorno de datos
	 * @param	$withData		Boolean		# Boleano para rellenar los articulos o no (Por defecto: false)
	 * @param	$parent			Integer		# Id de la sección padre (Por defecto: 0)
     * @access	public
     * */
	public function getAllSections(&$dataReturn,$withData=false,$parent=0) {
		
		$sql = '
		SELECT id, nombre, descripcion, seccion, tipo, privada, orden
		FROM secciones
		WHERE seccion = '.$parent.'
		AND idSitioWeb = '.$this->idWebsite.'
		AND estado = 1';
		$dataSections = $this->mysqlLink->fetchAll($sql);
		
		foreach ($dataSections as $id=>$section) {

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
	
			if ($withData === TRUE) {
				$this->getAllArticlesSection($dataReturn[$idSection]['articles'],$idSection);
			}
	
			// Llamada recursiva a la funcion para llenar los hijos de esta seccion
			$this->getAllSections($dataReturn[$idSection]['childrens'],$withData,$idSection);
		}
	}
	
    /*
     * getAllArticlesSection()
     * @description Devuelve todos los articulos de una sección
     * @param	&$dataReturn	Link Array  # Array de retorno de datos
	 * @param	$idSection		Integer		# Id de la sección
     * @access	public
     * */
	public function getAllArticlesSection(&$dataReturn,$idSection) {
		$sql = '
		SELECT a.*
		FROM articulos AS a
		LEFT JOIN secciones AS s ON s.id = a.idSeccion
		WHERE a.idSeccion = '.$idSection.'
		AND s.estado = 1
		AND a.estado = 1;
		';
	
		$dataArticles = $this->mysqlLink->fetchAll($sql);
		foreach ($dataArticles as $id=>$article) {
			
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

    /*
     * getPostFriendly()
     * @description Devuelve un articulo
     * @param	$idArticle	Integer  # Id del articulo
     * @access	public
     * */
	 /*
	public function getPostFriendly($urlArticle) {
		$sql = "
		SELECT id, titulo, cuerpo, orden
		FROM articulos
		WHERE id = '".$idArticle."'
		AND estado = 1;
		";
		
		$dataReturn = array();
		
		$dataArticles = $this->mysqlLink->fetchAll($sql);
		foreach ($dataArticles as $id=>$article) {
			$dataReturn['id'] 		= $article['id'];
			$dataReturn['title'] 	= $article['titulo'];
			$dataReturn['body'] 	= $article['cuerpo'];
			$dataReturn['order'] 	= $article['orden'];
		}
		
		if (!empty($dataReturn)) {
			return $this->outPut($dataReturn);
		} else {
			return 'dataReturn = null';
		}
	}
*/
	
    /*
     * getPost()
     * @description Devuelve un articulo
     * @param	$idArticle	Integer  # Id del articulo
     * @access	public
     * */
	public function getPost($idArticle) {
		if (is_numeric($idArticle)) {
				
			$sql = "
			SELECT id, titulo, cuerpo, orden
			FROM articulos
			WHERE id = '".$idArticle."'
			AND estado = 1;
			";
			
			$dataReturn = array();
			
			$dataArticles = $this->mysqlLink->fetchAll($sql);
			foreach ($dataArticles as $id=>$article) {
				$dataReturn['id'] 		= $article['id'];
				$dataReturn['title'] 	= $article['titulo'];
				$dataReturn['body'] 	= $article['cuerpo'];
				$dataReturn['order'] 	= $article['orden'];
			}
			
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idArticle isnt numeric';
		}
	}
	
    /*
     * getSeccion()
     * @description Devuelve una seccion
     * @param	$idArticle	Integer  # Id del articulo
     * @access	public
     * */
	public function getSeccion($idSection) {
		if (is_numeric($idSection)) {
				
			$sql = "
			SELECT id, nombre, descripcion, seccion, tipo, privada, orden
			FROM secciones
			WHERE id = '".$idSection."'
			AND estado = 1";
			
			$dataReturn = array();
			
			$dataSection = $this->mysqlLink->fetchAll($sql);
			foreach ($dataSection as $id=>$section) {
				$dataReturn['id'] 			= $section['id'];
				$dataReturn['name'] 		= $section['nombre'];
				$dataReturn['description'] 	= $section['descripcion'];
				$dataReturn['section'] 		= $section['seccion'];
				$dataReturn['type'] 		= $section['tipo'];
				$dataReturn['private'] 		= $section['privada'];
				$dataReturn['order'] 		= $section['orden'];
			}
			
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idArticle isnt numeric';
		}
	}
	
    /*
     * getSecciones()
     * @description Devuelve las secciones de una website
     * @access	public
     * */
	public function getSecciones() {
		if (!is_null($this->idWebsite)) {
				
			$sql = "
			SELECT id, nombre, descripcion, seccion, tipo, privada, orden
			FROM secciones
			WHERE idSitioWeb = '".$this->idWebsite."'
			AND estado = 1";
			
			$dataReturn = array();
			
			$dataSections = $this->mysqlLink->fetchAll($sql);
			foreach ($dataSections as $id=>$section) {
				$idSection = $section['id'];
				
				$dataReturn[$idSection]['name'] 		= $section['nombre'];
				$dataReturn[$idSection]['description'] 	= $section['descripcion'];
				$dataReturn[$idSection]['section'] 		= $section['seccion'];
				$dataReturn[$idSection]['type'] 		= $section['tipo'];
				$dataReturn[$idSection]['private'] 		= $section['privada'];
				$dataReturn[$idSection]['order'] 		= $section['orden'];
			}
			
			$_fixSections = array();
			foreach ($dataReturn as $idSection => $section) {
				if ($section['section'] > 0) {
					$parentSection = $section['section'];
					
					$_fixSections[$parentSection]['children'][$idSection] = $section;
				} else {
					$_fixSections[$idSection] = $section;
				}
			}
			
			
			if (!empty($dataReturn)) {
				return $this->outPut($_fixSections);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idArticle isnt numeric';
		}
	}
	
    /*
     * getSeccionPost()
     * @description Devuelve los articulos de una seccion segun su tipo
     * @param	$section	Integer  # Id de la seccion
     * @param	$start		Integer  # Inicio de resultados
     * @param	$limit		Integer  # Limite de resultados
     * @param	$order		String  # Tipo de orden (asc o desc)
     * @access	public
     * */
	public function getSeccionPost($section,$start=0,$limit=100,$order='asc') {
		if (!is_null($this->idWebsite)) {
				
			switch ($order) {
				case 'asc':
				case 'desc':
					$order = $order;
				break;
				default:
					$order = 'asc';
				break;
			}
			
			$sql = "
			SELECT id, tipo
			FROM secciones
			WHERE id = '".$section."'
			AND idSitioWeb = '".$this->idWebsite."'
			AND estado = 1";
			$dataReturn = array();
			
			$dataSectionsPost = $this->mysqlLink->fetch($sql);
			
			if (!empty($dataSectionsPost)) {
				switch ($dataSectionsPost['tipo']) {
					case 0: //Tipo básico
						$sql = "
						SELECT id, titulo, cuerpo, orden
						FROM articulos
						WHERE idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType0 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType0 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['title'] 	= $article['titulo'];
							$dataReturn[$idArticle]['body'] 	= $article['cuerpo'];
							$dataReturn[$idArticle]['order'] 	= $article['orden'];
						}
					break;
					case 1: //Tipo avanzado
						$sql = "
						SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
						FROM articulos
						WHERE idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType1 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType1 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['title'] 		= $article['titulo'];
							$dataReturn[$idArticle]['subTitle'] 	= $article['subtitulo'];
							$dataReturn[$idArticle]['date'] 		= $article['fecha'];
							$dataReturn[$idArticle]['body'] 		= $article['cuerpo'];
							$dataReturn[$idArticle]['bodySummary'] 	= $article['cuerpoResumen'];
							$dataReturn[$idArticle]['order'] 		= $article['orden'];
							$dataReturn[$idArticle]['image'] 		= $article['imagen'];
							$dataReturn[$idArticle]['file'] 		= $article['archivo'];
							$dataReturn[$idArticle]['url'] 			= $article['url'];
							$dataReturn[$idArticle]['extraField'] 	= $article['campoExtra'];
						}
					break;
					case 2: //Tipo personalizado
						$sql = "
						SELECT id, datos
						FROM articulospersonalizado
						WHERE idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType2 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType2 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['data'] = $article['datos'];
						}
					break;
				}
			}
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idWebsite is null';
		}
	}



    /*
     * getSeccionPostImagen()
     * @description Devuelve los articulos de una seccion segun su tipo
     * @param	$section	Integer  # Id de la seccion
     * @param	$start		Integer  # Inicio de resultados
     * @param	$limit		Integer  # Limite de resultados
     * @param	$order		String  # Tipo de orden (asc o desc)
     * @access	public
     * */
	public function getSeccionPostImagen($section,$start=0,$limit=100,$order='asc') {
		if (!is_null($this->idWebsite)) {
				
			switch ($order) {
				case 'asc':
				case 'desc':
					$order = $order;
				break;
				default:
					$order = 'asc';
				break;
			}
			
			$sql = "
			SELECT id, tipo
			FROM secciones
			WHERE id = '".$section."'
			AND idSitioWeb = '".$this->idWebsite."'
			AND estado = 1";
			
			$dataReturn = array();
			
			$dataSectionsPostImage = $this->mysqlLink->fetch($sql);
			
			if (!empty($dataSectionsPostImage)) {
				switch ($dataSectionsPostImage['tipo']) {
					case 0: //Tipo básico
						$sql = "
						SELECT id, titulo, cuerpo, orden
						FROM articulos
						WHERE imagen != ''
						AND idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType0 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType0 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['title'] 	= $article['titulo'];
							$dataReturn[$idArticle]['body'] 	= $article['cuerpo'];
							$dataReturn[$idArticle]['order'] 	= $article['orden'];
						}
					break;
					case 1: //Tipo avanzado
						$sql = "
						SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
						FROM articulos
						WHERE imagen != ''
						AND idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType1 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType1 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['title'] 		= $article['titulo'];
							$dataReturn[$idArticle]['subTitle'] 	= $article['subtitulo'];
							$dataReturn[$idArticle]['date'] 		= $article['fecha'];
							$dataReturn[$idArticle]['body'] 		= $article['cuerpo'];
							$dataReturn[$idArticle]['bodySummary'] 	= $article['cuerpoResumen'];
							$dataReturn[$idArticle]['order'] 		= $article['orden'];
							$dataReturn[$idArticle]['image'] 		= $article['imagen'];
							$dataReturn[$idArticle]['file'] 		= $article['archivo'];
							$dataReturn[$idArticle]['url'] 			= $article['url'];
							$dataReturn[$idArticle]['extraField'] 	= $article['campoExtra'];
						}
					break;
					case 2: //Tipo personalizado
						$sql = "
						SELECT id, datos
						FROM articulospersonalizado
						WHERE idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType2 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType2 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['data'] = $article['datos'];
						}
					break;
				}
			} else {
				$sql = "
				SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
				FROM articulos
				WHERE imagen != ''
				AND estado = 1
				ORDER BY id ".$order."
				LIMIT ".$start.",".$limit.";";
				
				$data = $this->mysqlLink->fetchAll($sql);
				foreach ($data as $id=>$article) {
					$idArticle = $article['id'];
					
					$dataReturn[$idArticle]['title'] 		= $article['titulo'];
					$dataReturn[$idArticle]['subTitle'] 	= $article['subtitulo'];
					$dataReturn[$idArticle]['date'] 		= $article['fecha'];
					$dataReturn[$idArticle]['body'] 		= $article['cuerpo'];
					$dataReturn[$idArticle]['bodySummary'] 	= $article['cuerpoResumen'];
					$dataReturn[$idArticle]['order'] 		= $article['orden'];
					$dataReturn[$idArticle]['image'] 		= $article['imagen'];
					$dataReturn[$idArticle]['file'] 		= $article['archivo'];
					$dataReturn[$idArticle]['url'] 			= $article['url'];
					$dataReturn[$idArticle]['extraField'] 	= $article['campoExtra'];
				}
			}
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idWebsite is null';
		}
	}


    /*
     * getSeccionPostURL()
     * @description Devuelve los articulos de una seccion segun su tipo
     * @param	$section	Integer  # Id de la seccion
     * @param	$start		Integer  # Inicio de resultados
     * @param	$limit		Integer  # Limite de resultados
     * @param	$order		String  # Tipo de orden (asc o desc)
     * @access	public
     * */
	public function getSeccionPostURL($section,$start=0,$limit=100,$order='asc') {
		if (!is_null($this->idWebsite)) {
				
			switch ($order) {
				case 'asc':
				case 'desc':
					$order = $order;
				break;
				default:
					$order = 'asc';
				break;
			}
			
			$sql = "
			SELECT id, tipo
			FROM secciones
			WHERE id = '".$section."'
			AND idSitioWeb = '".$this->idWebsite."'
			AND estado = 1";
			
			$dataReturn = array();
			
			$dataSectionsPostUrl = $this->mysqlLink->fetch($sql);
			
			if (!empty($dataSectionsPostUrl)) {
				switch ($dataSectionsPostUrl['tipo']) {
					case 0: //Tipo básico
						$sql = "
						SELECT id, titulo, cuerpo, orden
						FROM articulos
						WHERE url != ''
						AND idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType0 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType0 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['title'] 	= $article['titulo'];
							$dataReturn[$idArticle]['body'] 	= $article['cuerpo'];
							$dataReturn[$idArticle]['order'] 	= $article['orden'];
						}
					break;
					case 1: //Tipo avanzado
						$sql = "
						SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
						FROM articulos
						WHERE url != ''
						AND idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType1 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType1 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['title'] 		= $article['titulo'];
							$dataReturn[$idArticle]['subTitle'] 	= $article['subtitulo'];
							$dataReturn[$idArticle]['date'] 		= $article['fecha'];
							$dataReturn[$idArticle]['body'] 		= $article['cuerpo'];
							$dataReturn[$idArticle]['bodySummary'] 	= $article['cuerpoResumen'];
							$dataReturn[$idArticle]['order'] 		= $article['orden'];
							$dataReturn[$idArticle]['image'] 		= $article['imagen'];
							$dataReturn[$idArticle]['file'] 		= $article['archivo'];
							$dataReturn[$idArticle]['url'] 			= $article['url'];
							$dataReturn[$idArticle]['extraField'] 	= $article['campoExtra'];
						}
					break;
					case 2: //Tipo personalizado
						$sql = "
						SELECT id, datos
						FROM articulospersonalizado
						WHERE idSeccion = '".$section."'
						AND estado = 1
						ORDER BY id ".$order."
						LIMIT ".$start.",".$limit.";";
						
						$dataType2 = $this->mysqlLink->fetchAll($sql);
						foreach ($dataType2 as $id=>$article) {
							$idArticle = $article['id'];
							
							$dataReturn[$idArticle]['data'] = $article['datos'];
						}
					break;
				}
			} else {
				$sql = "
				SELECT id, titulo, subtitulo, fecha, cuerpo, cuerpoResumen, orden, imagen, archivo, url, campoExtra
				FROM articulos
				WHERE url != ''
				AND estado = 1
				ORDER BY id ".$order."
				LIMIT ".$start.",".$limit.";";
				
				$data = $this->mysqlLink->fetchAll($sql);
				foreach ($data as $id=>$article) {
					$idArticle = $article['id'];
					
					$dataReturn[$idArticle]['title'] 		= $article['titulo'];
					$dataReturn[$idArticle]['subTitle'] 	= $article['subtitulo'];
					$dataReturn[$idArticle]['date'] 		= $article['fecha'];
					$dataReturn[$idArticle]['body'] 		= $article['cuerpo'];
					$dataReturn[$idArticle]['bodySummary'] 	= $article['cuerpoResumen'];
					$dataReturn[$idArticle]['order'] 		= $article['orden'];
					$dataReturn[$idArticle]['image'] 		= $article['imagen'];
					$dataReturn[$idArticle]['file'] 		= $article['archivo'];
					$dataReturn[$idArticle]['url'] 			= $article['url'];
					$dataReturn[$idArticle]['extraField'] 	= $article['campoExtra'];
				}
			}
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idWebsite is null';
		}
	}

    /*
     * getCommentsArticle()
     * @description Devuelve los comentarios de un articulo
     * @param	$idArticulo	Integer  # Id del articulo
     * @param	$limit		Integer  # Limite de resultados
     * @access	public
     * */
	public function getCommentsArticle($idArticulo,$limit=100) {
		if (!is_null($this->idWebsite)) {
			$sql = "
			SELECT *
			FROM comentarios
			WHERE idSitioWeb = ".$this->idWebsite."
			AND idArticulo = ".$idArticulo."
			AND estado = 1";
			$dataReturn = array();
			
			$dataComments = $this->mysqlLink->fetchAll($sql);
			
			if (!empty($dataComments)) {
				foreach ($dataComments as $id=>$comentario) {
					$idComment = $comentario['id'];
					
					$dataReturn[$idComment]['idUsuario']		= $comentario['idUsuario'];
					$dataReturn[$idComment]['idArticulo'] 		= $comentario['idArticulo'];
					$dataReturn[$idComment]['idPadre'] 			= $comentario['idPadreComentario'];
					$dataReturn[$idComment]['comentario'] 		= $comentario['comentario'];
					$dataReturn[$idComment]['fechaCreacion'] 	= $comentario['fechaCreacion'];
					$dataReturn[$idComment]['fechaPublicacion'] = $comentario['fechaPublicacion'];
					$dataReturn[$idComment]['estado'] 			= $comentario['estado'];
				}
			}
			if (!empty($dataReturn)) {
				return $this->outPut($dataReturn);
			} else {
				return 'dataReturn = null';
			}
		} else {
			return 'idWebsite is null';
		}
	}
	

    /*
     * outPut()
     * @description Devuelve el contenido de $data en el formato especificado en la class (Array o JSON)
     * @param	$data	Array  # Array con los datos a devolver
     * @access	private
     * */
	private function outPut($data) {
		if (is_array($data)) {
			if ($this->typeOutput == 'array') {
				return $data;
			} elseif ($this->typeOutput == 'json') {
				return json_encode($data);
			}
		}
	}
}

?>