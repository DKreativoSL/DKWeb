<?php
	if ($_SESSION['idUsuario'])
	{			
	}else{
		//echo "usuario".$_SESSION['idUsuario'];
		header('Location: login.php');
	}

?>