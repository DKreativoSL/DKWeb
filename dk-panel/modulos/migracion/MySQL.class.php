<?php

/**
 * Short description for file
 *
 * Long description for file (if any)...
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_01.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   CategoryName
 * @package    PackageName
 * @author     Original Author <author@example.com>
 * @author     Another Author <another@example.com>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    SVN: $Id$
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      File available since Release 1.2.0
 * @deprecated File deprecated in Release 2.0.0
 */


class DBPDO {

	public $pdo;
	private $error;


    /**
     * __construct()
     * @description Constructor de la class MySQL
     * @access	public
     */
	function __construct() {
		$this->connect();
	}


    /**
     * prep_query()
     * @description Prepara una query para ser lanazada
     * @param	$query	String  # SQL
     * @access	public
     */
	function prep_query($query){
		return $this->pdo->prepare($query);
	}

    /**
     * prep_query()
     * @description Se conecta a la BDD con los datos proporcionados
     * @access	public
     */
	function connect(){
		if(!$this->pdo){

			$dsn      = 'mysql:dbname=' . cBd . ';host=' . cServidor;
			$user     = cUsuario;
			$password = cPass;

			try {
				$this->pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_PERSISTENT => true));
				return true;
			} catch (PDOException $e) {
				$this->error = $e->getMessage();
				die($this->error);
				return false;
			}
		}else{
			$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			return true;
		}
	}

    /**
     * table_exists()
     * @description Comprueba si una tabla existe o no
     * @param	$table_name	String  # Nombre de la tabla
     * @access	public
     */
	function table_exists($table_name){
		$stmt = $this->prep_query('SHOW TABLES LIKE ?');
		$stmt->execute(array($this->add_table_prefix($table_name)));
		return $stmt->rowCount() > 0;
	}

    /**
     * execute()
     * @description Ejecuta una SQL Query
     * @param	$query	String  # SQL
     * @param	$values	Array  # Campos que queremos obtener
     * @access	public
     */
	function execute($query, $values = null){
		if($values == null){
			$values = array();
		}else if(!is_array($values)){
			$values = array($values);
		}
		$stmt = $this->prep_query($query);
		$stmt->execute($values);
		return $stmt;
	}

    /**
     * fetch()
     * @description Devuelve un array asociativo de la consulta SQL
     * @param	$query	String  # SQL
     * @param	$values	Array  # Campos que queremos obtener
     * @access	public
     */
	function fetch($query, $values = null){
		if($values == null){
			$values = array();
		}else if(!is_array($values)){
			$values = array($values);
		}
		$stmt = $this->execute($query, $values);
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    /**
     * fetchAll()
     * @description Devuelve un array asociativo de la consulta SQL
     * @param	$query	String  # SQL
     * @param	$values	Array   # Campos que queremos obtener
     * @param	$key	String  # Columnas que queremos obtener
     * @access	public
     */
	function fetchAll($query, $values = null, $key = null){
		if($values == null){
			$values = array();
		}else if(!is_array($values)){
			$values = array($values);
		}
		$stmt = $this->execute($query, $values);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if($key != null && $results[0][$key]){
			$keyed_results = array();
			foreach($results as $result){
				$keyed_results[$result[$key]] = $result;
			}
			$results = $keyed_results;
		}
		return $results;
	}

    /*
     * lastInsertId()
     * @description Devuelve la ultima ID insertada en la base de datos
     * @access	public
     * */
	function lastInsertId(){
		return $this->pdo->lastInsertId();
	}

}
?>