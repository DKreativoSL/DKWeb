<?php

	date_default_timezone_set('Europe/Madrid');

	define("cServidor", "SERVIDOR_BASE_DE_DATOS");
	define("cUsuario", "USUARIO_BASE_DE_DATOS");
	define("cPass","CONTRASEÑA_BASE_DE_DATOS");
	define("cBd","NOMBRE_BASE_DE_DATOS");

	//lanzo la conexión una vez
	$conexion = mysqli_connect(cServidor, cUsuario, cPass, cBd);
	
?>