<?php
session_start(); //inicializo sesión

include("./../../conexion.php");
include("./../../funciones.php");

$accion = $_POST['accion'];
$idUsuario = $_SESSION['idUsuario'];

switch ($accion) {
	case 'lanzarUpdate':
		$sql = $_POST['sql'];
		
		try {
			$statusUpdate = mysqli_query($conexion, $sql);
			
			if ($statusUpdate) {
				echo 'OK';
			} else {
				echo 'KO';
			}
		} catch (exception $e) {
			echo $e->getMessage();
		}
	break;
}
?>
