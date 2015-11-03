<?php
class DKWebExportV1 {
	
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
	public function exportAll() {
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
		SELECT id, nombre, descripcion, seccion, tipo, privada, idioma, estilo
		FROM secciones
		WHERE seccion = '.$parent.';';
		
		$tmp_dataSections = $this->mysqlLink->fetchAll($sql);
		
		//echo '<pre>'.print_r($tmp_dataSections,true).'</pre>';
		
		foreach ($tmp_dataSections as $id=>$section) {
			
			//Guardamos id de la seccion 
			$idSection = $section['id'];
			
			/*
			$description = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
    			return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
			}, $section['descripcion']);
			*/
			
			$dataReturn[$idSection]['id'] 			= $idSection;
			$dataReturn[$idSection]['nombre'] 		= utf8_encode($section['nombre']);
			$dataReturn[$idSection]['descripcion'] 	= utf8_encode($section['descripcion']);
			$dataReturn[$idSection]['seccion'] 		= $section['seccion'];
			$dataReturn[$idSection]['tipo'] 		= $section['tipo'];
			$dataReturn[$idSection]['privada'] 		= $section['privada'];
			$dataReturn[$idSection]['orden'] 		= '0';
			$dataReturn[$idSection]['estado'] 		= 1;
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
		LEFT JOIN secciones AS s ON s.id = a.seccion
		WHERE a.seccion = '.$idSection.';';
		
		$tmp_dataArticles = $this->mysqlLink->fetchAll($sql);
		
		
		foreach ($tmp_dataArticles as $id=>$article) {
			
			//Guardamos id de la seccion 
			$idSection = $article['seccion'];
			
			$articleArray = array(
				'id'			=>	$article['id'],
				'idSeccion'		=>	$article['seccion'],
				'idUsuario'		=>	0,
				'titulo'		=>	utf8_encode($article['titulo']),
				'subtitulo'		=>	'',
				'fecha'			=>	'',
				'cuerpo'		=>	utf8_encode(addslashes($article['cuerpo'])),
				'cuerpoResumen'	=>	'',
				'orden'			=>	$article['orden'],
				'imagen'		=>	'',
				'archivo'		=>	'',
				'url'			=>	'',
				'campoExtra'	=>	'',
				'estado'		=>	1,
			);
			
			$dataReturn[] = $articleArray;
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
				
				echo 'Archivo creado: '.$this->outputFileName;
				
				$fp = fopen($this->outputFileName, 'w');
				fwrite($fp, $dataXML);
				fclose($fp);
			} else if ($this->typeOutput == 'fileJSON') {		
				$jsonString = json_encode($data);
				
				echo 'Archivo creado: '.$this->outputFileName;
				
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