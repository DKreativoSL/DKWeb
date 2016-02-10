<?php
session_start();

require_once('./config.php');
require_once('./class/MySQL.class.php');
require_once('./class/DKWeb.class.php');

$DB = new DBPDO();
$DKWeb = new DKWeb($DB,ID_WEBSITE);

//Recojo valores de url
$sectionName = isset($_GET['sectionName']) ? $_GET['sectionName'] : '';
$sectionId = isset($_GET['sectionId']) ? $_GET['sectionId'] : '';
$postName = isset($_GET['postName']) ? $_GET['postName'] : '';
$postId = isset($_GET['postId']) ? $_GET['postId'] : '';

// Esta condicional es para trabajar de forma manual las urls
//pero se automatizará desde el propio Dk Web
if ($postId != 0)
{	//cargo la publicación
	include("./fuente/".$postName.".php");
}elseif ($sectionId != 0){ //cargo la sección
		include("./fuente/".$sectionName.".php");
}else{
	include("./fuente/inicio.php");
	}
	
//header('Location: mipagina.php');




?>
