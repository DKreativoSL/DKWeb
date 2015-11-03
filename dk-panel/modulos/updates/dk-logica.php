<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_POST['accion'];
	
	$idUsuario = $_SESSION['idUsuario'];
	
	switch ($accion) {
		case 'listaUpdates':
			$tabla = listar_directorios_ruta('./sqls/');
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
		break;
		case 'lanzarUpdate':
			$file = $_POST['file'];
			$sqlUpdate = file_get_contents($file, FILE_USE_INCLUDE_PATH);
			$statusUpdate = mysqli_query($conexion, $sqlUpdate);
			if ($statusUpdate) {
				@unlink($file);
				echo 'OK';
			} else {
				echo 'KO';
			}
		break;
	}
	
	function listar_directorios_ruta($ruta){		
		$tabla = '';
		if (is_dir($ruta)) { 
			if ($dh = opendir($ruta)) {
				$idArchivo = 1; 
				while (($file = readdir($dh)) !== false) {
					if ( ($file != '.') && ($file != '..') ) {
						if (!is_dir($ruta . $file)) {
							$lanzar = '<a href=\"#\" onclick=\"lanzarUpdate(\''.$ruta.'/'.$file.'\')\"><i class=\"fa fa-paper-plane-o fa-2x\"></i></a>';
							
							$tabla .= '{"id":"'.$idArchivo.'","archivo":"'.$ruta.'/'.$file.'","acciones":"'.$lanzar.'"},';
							$idArchivo++;	
						} else {
							$tabla .= listar_directorios_ruta($ruta.$file);
						}
					}
         		}
      			closedir($dh); 
      		} 
		} else {
			$tabla .= '{"id":"-1","archivo":"RUTA DE DIRECTORIO NO VALIDA","acciones":""},';
		}
		return $tabla;
	}
	
?>
