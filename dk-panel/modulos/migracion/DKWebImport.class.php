<?php
class DKWebImport {
	
	private $tokenWebiste 	= null;
	private $idWebsite		= null;
	private $mysqlLink 		= null;
	private $typeInput 		= null;
	private $inputFileName  = null;
	private $userId			= null;
    /*
     * __construct()
     * @description Funcion constructora
     * @param	&$mysqlLink		Link Mysql	Link a la class MYSQL
     * @param	$typeInput	String	Tipo de entrada [json,xml]
     * @access	public
     * */
	public function __construct(&$mysqlLink,$typeInput='json',$inputFileName='output.json') {
		$this->mysqlLink = $mysqlLink;
		
		$this->typeInput = $typeInput;
		$this->inputFileName = $inputFileName;
	}
	
	
    /*
     * setUserId()
     * @description Establece el idUsuario que importara la informacion
     * @param	$idUsuario	Integer	ID del usuario de la website
     * @access	public
     * */
	public function setUserId($idUser) {
		$this->userId = $idUser;
	}
	
    /*
     * importAll()
     * @description Funcion principal que ejecuta la importacion de los datos
     * @param	$idWebsite	String	ID de la website
     * @access	public
     * */
	public function importAll($idWebsite) {
		
		if (!file_exists($this->inputFileName)) {
			exit('file input no exists');
		}
		
		$dataFile = file($this->inputFileName);
		$data = $dataFile[0];
		
		$this->setIdWebsite($idWebsite);
		
		switch ($this->typeInput) {
			case 'fileJSON':
				//Decodificar JSON en Array
				$dataArray = json_decode($data);
				
				//Importar datos
				$this->importData($dataArray,0);
				//echo '<pre>'.print_r($dataArray,true).'</pre>';
			break;
			case 'xml':
				//Decodificar XML en Array
				exit('En construcción');
				
				//Importar datos
				//$this->importData($dataArray,0);
			break;
		}
	}
	
	
    /*
     * importData()
     * @description Importa las secciones y los articulos recursivamente
     * @param	$dataSections	Array  # Información de las secciones, subsercciones y articulos
     * @param	$idParent		Integer  # id de la seccion padre - DEFAULT: 0
     * @access	private
     * */
	private function importData($dataSections,$idParent) {
		
		foreach ($dataSections as $idSeccion=>$section) {
			
			//echo '<pre>'.print_r($section,true).'</pre>';
			$sql = 'INSERT INTO secciones SET ';
			$sql .= 'idSitioWeb='.$this->idWebsite.', ';
			$sql .= 'nombre="'.$section->nombre.'", ';
			$sql .= 'descripcion="'.$section->descripcion.'", ';
			$sql .= 'seccion="'.$idParent.'", ';
			$sql .= 'tipo="'.$section->tipo.'", ';
			$sql .= 'privada="'.$section->privada.'", ';
			$sql .= 'orden="'.$section->orden.'", ';
			$sql .= 'estado='.$section->estado.'; ';
			
			$this->mysqlLink->execute($sql);
			$tmp_idParent = $this->mysqlLink->lastInsertId();
			
			//echo $sql;
			//$this->mysqlLink->execute($sql);
			if (!empty($section->childrens)) {
				//Tenemos hijos
				$this->importData($section->childrens,$tmp_idParent);
			}
			if (!empty($section->articles)) {
				//Tenemos articulos
				$this->importArticles($section->articles,$tmp_idParent);
			}
		}
	}
	
    /*
     * importArticles()
     * @description Importa los articulos de una seccion
     * @param	$dataArticle	Array  # Información del articulo
     * @param	$idParent		Integer  # id de la seccion padre - DEFAULT: 0
     * @access	private
     * */
	private function importArticles($dataArticle,$idParent) {
		foreach ($dataArticle as $idSeccion=>$article) {
			
			$sql = 'INSERT INTO articulos SET ';
			$sql .= 'idSeccion='.$idParent.', ';
			
			if ($article->idUsuario == 0) {
				$sql .= 'idUsuario="'.$this->userId.'", ';
			} else {
				$sql .= 'idUsuario="'.$article->idUsuario.'", ';
			}
			
			$sql .= 'titulo="'.$article->titulo.'", ';
			$sql .= 'subtitulo="'.$article->subtitulo.'", ';
			$sql .= 'fecha="'.$article->fecha.'", ';
			$sql .= 'cuerpo="'.$article->cuerpo.'", ';
			$sql .= 'cuerpoResumen="'.$article->cuerpoResumen.'", ';
			$sql .= 'orden="'.$article->orden.'", ';
			$sql .= 'imagen="'.$article->imagen.'", ';
			$sql .= 'archivo="'.$article->archivo.'", ';
			$sql .= 'url="'.$article->url.'", ';
			$sql .= 'campoExtra="'.$article->campoExtra.'", ';
			$sql .= 'estado='.$article->estado.'; ';
			$this->mysqlLink->execute($sql);
			
		}
	}
	
    /*
     * setIdWebsite()
     * @description Setea el token de la pagina para poder importar los datos a la misma
     * @param	$tokenWebsite	String  # Token
     * @access	private
     * */
	private function setIdWebsite($idWebsite) {
		$sql = "
		SELECT id
		FROM sitiosweb
		WHERE id = '".$idWebsite."';";
	
		$dataWebsite = $this->mysqlLink->fetch($sql);
		
		if (!empty($dataWebsite)) {
			if ($dataWebsite['id'] > 0) {
				$this->idWebsite = $dataWebsite['id'];
			} else {
				exit('ERROR ID WEBSITE');
			}
		} else {
			exit('ERROR ID WEBSITE');
		}
		
	}
}
?>