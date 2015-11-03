<?php

	date_default_timezone_set('Europe/Madrid');

	define("cServidor", "localhost");
	define("cUsuario", "root");
	define("cPass","");
	define("cBd","mad");

	//lanzo la conexión una vez
	$conexion = mysqli_connect(cServidor, cUsuario, cPass, cBd);
	
?>