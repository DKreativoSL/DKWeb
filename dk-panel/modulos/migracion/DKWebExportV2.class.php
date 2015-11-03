<?php
class DKWebExportV2 {
	
	private $tokenWebiste 	= null;
	private $idWebsite		= null;
	private $mysqlLink 		= null;
	private $typeOutput 	= null;
	private $xmlReturn		= null;
	private $outputFileName	= null;
	
    /*
     * __construct()
     * @description Funcion constructora
     * @param	&$mysqlLink		Link Mysql	Link a la class MYSQL
     * @param	$typeOutput	String	Tipo de salida [json,xml,debug]
     * @access	public
     * */
	public function __construct(&$mysqlLink,$typeOutput='json',$outputFileName='output.json') {
		$this->mysqlLink = $mysqlLink;
		
		$this->typeOutput = $typeOutput;
		$this->outputFileName = $outputFileName;
		
		if ($this->typeOutput == "xml") {
			$this->xmlReturn = new SimpleXMLElement("<?xml version=\"1.0\"?><sections></sections>");
		}
	}
	
    /*
     * exportAll()
     * @description Funcion principal que ejecuta la salida de todos los datos
     * @param	$tokenWebsite	String	Token de la website
     * @access	public
     * */
	public function exportAll($idWebsite) {
		$this->setIdWebsite($idWebsite);
		
		$this->getAllSections($dataReturn,0);
		
		$this->outPut($dataReturn);
	}
	
    /*
     * getAllSections()
     * @description Obtiene las secciones y sus hijas y los guarda en el array
     * @param	&$dataReturn	Link Array  # Link al array de dataReturn
     * @param	$idSection		Integer  # id de la seccion padre - DEFAULT: 0
     * @access	private
     * */
	private function getAllSections(&$dataReturn,$parent) {
		
		$sql = '
		SELECT id, nombre, descripcion, seccion, tipo, privada, orden, estado
		FROM secciones
		WHERE idSitioWeb = ' .$this->idWebsite.'
		AND seccion = '.$parent;
		
		$tmp_dataSections = $this->mysqlLink->fetchAll($sql);
		
		
		foreach ($tmp_dataSections as $id=>$section) {
			
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
			$dataReturn[$idSection]['childrens'] 	= array();
			$dataReturn[$idSection]['articles'] 	= array();

			$this->getAllArticlesSection($dataReturn[$idSection]['articles'],$idSection);

			// Llamada recursiva a la funcion para llenar los hijos de esta seccion
			$this->getAllSections($dataReturn[$idSection]['childrens'],$section['id']);
		}
	}
	
    /*
     * getAllArticlesSection()
     * @description Obtiene los articulos de una seccion y los guarda en el array
     * @param	&$dataReturn	Link Array  # Link al array de dataReturn
     * @param	$idSection		Integer  # id de la seccion
     * @access	private
     * */
	private function getAllArticlesSection(&$dataReturn,$idSection) {
		$sql = '
		SELECT a.*
		FROM articulos AS a
		LEFT JOIN secciones AS s ON s.id = a.idSeccion
		WHERE s.idSitioWeb = ' .$this->idWebsite.'
		AND a.idSeccion = '.$idSection.';';
		$tmp_dataArticles = $this->mysqlLink->fetchAll($sql);
		
		
		foreach ($tmp_dataArticles as $id=>$article) {
			
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
	
    /*
     * setToken()
     * @description Setea el token de la pagina para poder exportar los datos de la misma
     * @param	$tokenWebsite	String  # Token
     * @access	private
     * */
	private function setIdWebsite($idWebsite) {
		$this->idWebsite = $idWebsite;
		if ($this->outputFileName == 'output.json') {
			$this->outputFileName = 'output_'.$idWebsite.'.json';
		}
	}
	
    /*
     * outPut()
     * @description Devuelve el contenido de $data en el formato especificado en la class (JSON o XMÑ)
     * @param	$data	Array  # Array con los datos a devolver
     * @access	private
     * */
	private function outPut($data) {
		if (is_array($data)) {
				
			if ($this->typeOutput == 'fileXML') {
				// Convertimos el array en XML
				$this->arrayToXml($data,$this->xmlReturn);
				
				//Devolvemos el XML
				$dataXML = $this->xmlReturn->asXML();
				
				echo $this->outputFileName;
				
				$fp = fopen($this->outputFileName, 'w');
				fwrite($fp, $dataXML);
				fclose($fp);
			} else if ($this->typeOutput == 'fileJSON') {		
				$jsonString = json_encode($data);
				
				echo $this->outputFileName;
				
				$fp = fopen($this->outputFileName, 'w');
				fwrite($fp, $jsonString);
				fclose($fp);
			} else if ($this->typeOutput == 'json') {
				
				header('Content-type: application/json; charset=utf-8');
				
				echo json_encode($data);
			} elseif ($this->typeOutput == 'xml') {
				
				header("Content-Type: application/xml; charset=utf-8");
				
				// Convertimos el array en XML
				$this->arrayToXml($data,$this->xmlReturn);
				
				//Devolvemos el XML
				echo $this->xmlReturn->asXML();
			} elseif ( ($this->typeOutput == 'debug') || ($this->typeOutput == 'array') ) {
				// Printamos el array
				echo '<pre>'.print_r($data,true).'</pre>';
			}
		}
	}
	
    /*
     * arrayToXml()
     * @description Convierte un array en XML
     * @param	$data	Array  # Array con los datos a devolver
     * @param	$data	Array  # Array con los datos a devolver 
     * @access	private
     * */
	private function arrayToXml($arrayData, &$xmlInfo) {
	    foreach($arrayData as $key => $value) {
	        if(is_array($value)) {
	            if(!is_numeric($key)){
	                $subnode = $xmlInfo->addChild("$key");
	                $this->arrayToXml($value, $subnode);
	            }
	            else{
	                $subnode = $xmlInfo->addChild("item$key");
	                $this->arrayToXml($value, $subnode);
	            }
	        }
	        else {
	            $xmlInfo->addChild("$key",htmlspecialchars("$value"));
	        }
	    }
	}
}
?>