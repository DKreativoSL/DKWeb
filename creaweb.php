<?php

	include("./web/recaptcha.php");
	
	include("./dk-panel/conexion.php");
	//include('config.php');
	
	include("./web/class/MySQL.class.php");
	
	$DB = new DBPDO();
    
	$secret = "6LdS7wcTAAAAAFX_mAfOr7451r_Dtag1u1HzEJ9v";	
	$response = null;
	
    if(isset($_POST['g-recaptcha-response'])){
		$captcha = $_POST['g-recaptcha-response'];
    }
    if(!$captcha){
    	header('Location: registro.php?error=noCaptcha');
        exit();
    }
	
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
	
	if($response.success==false) {
		header('Location: registro.php?error=noHuman');
    } else {

		$md5Password = md5($_POST['password']);
		
		$fechaAlta = date("Y-m-d H:i:s");
		
		$sql = 'INSERT INTO usuarios SET ';
		$sql .= 'email="'.$_POST['email'].'", ';
		$sql .= 'clave="'.$md5Password.'", ';
		$sql .= 'nombre="'.$_POST['nombre'].'", ';
		$sql .= 'direccion="", ';
		$sql .= 'cp="", ';
		$sql .= 'poblacion="", ';
		$sql .= 'provincia="", ';
		$sql .= 'tlf1="", ';
		$sql .= 'tlf2="", ';
		$sql .= 'comentarios="", ';
		$sql .= 'nif="", ';
		$sql .= 'fechaAlta="'.$fechaAlta.'",'; 
		$sql .= 'fechaBaja="0000-00-00",';
		$sql .= 'grupo="administrador";';
		$DB->execute($sql);
		
		//echo $sql.'<br>';
		$idClient = $DB->lastInsertId();
		//echo 'idClient['.$idClient.']<br>';
		
		$md5Token = md5($_POST['dominio']);
		
		$sql = 'INSERT INTO sitiosweb SET idUsuarioPropietario="'.$idClient.'",'; 
		$sql .= 'nombre="'.$_POST['nombre'].'", ';
		$sql .= 'descripcion="'.$_POST['descripcion'].'", ';
		$sql .= 'dominio="'.$_POST['dominio'].'", ';
		$sql .= 'fechaCreacion="'.$fechaAlta.'", ';
		$sql .= 'token="'.$md5Token.'";';
		$DB->execute($sql);
		
		//echo $sql.'<br>';
		$idSitioWeb = $DB->lastInsertId();
		//echo 'idSitioWeb['.$idSitioWeb.']<br>';
		
		$sql = 'INSERT INTO usuariositios SET idUsuario='.$idClient.', idSitioWeb='.$idSitioWeb.', '; 
		$sql .= 'menuContenidoWeb=1, ';
		$sql .= 'menuConfiguracion=1, ';
		$sql .= 'menuSecciones=1, ';
		$sql .= 'menuParametros=1, ';
		$sql .= 'menuUsuarios=1, ';
		$sql .= 'menuInmobiliaria=1, ';
		$sql .= 'menuInmoApuntes=1, ';
		$sql .= 'menuinmoClientes=1, ';
		$sql .= 'menuInmoInmuebles=1, ';
		$sql .= 'menuInmoZonas=1, ';
		$sql .= 'menuCorreos=1, ';
		$sql .= 'menuMigracion=1, ';
		$sql .= 'menuComentarios=1, ';
		$sql .= 'menuTrashSecciones=1, ';
		$sql .= 'menuTrashArticulos=1, ';
		$sql .= 'menuUpdates=1,';
		$sql .= 'menuFTP=1,';
		$sql .= 'menuAcademia=1;';
		$DB->execute($sql);
		
		//echo $sql.'<br>';
		$usuariossitios = $DB->lastInsertId();
		//echo 'usuariossitios['.$usuariossitios.']<br>';
		
		header('Location: registro.php?status=ok');

    }
?>