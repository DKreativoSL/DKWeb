<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	require_once('MySQL.class.php');
	require_once('DKWebExportV2.class.php');
	require_once('DKWebImport.class.php');
	
	//Accion
	$accion = "";
	if (isset($_POST['accion'])) {
		$accion = $_POST['accion'];	
	}
	//idWeb
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	
	//MySQL Link
	$DB = new DBPDO();
	
	switch ($accion) {
		case 'exportarWebsite':
			$DKWebExport = new DKWebExportV2($DB,'fileJSON');
			$DKWebExport->exportAll($idSitioWeb);
		break;
		case 'importarWebsite':
			$DKWebExport = new DKWebImport($DB,'fileJSON',$_FILES['fileToImport']['tmp_name']);
			$DKWebExport->setUserId($idUsuario);
			$DKWebExport->importAll($idSitioWeb);
			header('Location: ../../index.php');
		break;
	}
?>
