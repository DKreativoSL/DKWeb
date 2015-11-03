<?php
	session_start(); //inicializo sesión
	
	include("./../../../conexion.php");
	include("./../../../funciones.php");
	
	//tomo los parámetros de configuración del ftp
	$consulta = "SELECT inmo_rutaPrivada FROM sitiosweb WHERE id = ".$_SESSION['sitioWeb'];
	$registro = mysqli_query($conexion, $consulta);
	
	$idUsuario = $_SESSION['idUsuario'];
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	$row = mysqli_fetch_assoc($registro);
	
	$folders = explode ('/', $_SERVER['PHP_SELF']);
	
	$totalDirs = count($folders)-3;
	$pre_ruta = '';
	for ($i=1;$i<=$totalDirs;$i++) {
		$pre_ruta .= '..'.DIRECTORY_SEPARATOR;
	}
	
	$ruta_final_privada = $pre_ruta . $row['inmo_rutaPrivada'] . DIRECTORY_SEPARATOR . $idSitioWeb . DIRECTORY_SEPARATOR;
	
	$ruta_final_Mostrarprivada = '../' . $row['inmo_rutaPrivada'] . DIRECTORY_SEPARATOR . $idSitioWeb . DIRECTORY_SEPARATOR;
	$ruta_final_Mostrarprivada = str_replace('\\','/',$ruta_final_Mostrarprivada);
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
		<title>Panel de control</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	
</head>
<body>
<table width="100%" cellspacing="2" cellpadding="1">
  <tr>
    <th scope="col">
	  <?php
	$idInmue = $_GET['id'];

	$tipo_archivo = $_FILES['userfile1']['type']; 	
	$tamano_archivo = $_FILES['userfile1']['size'];

	if ($idInmue != "")
	{
		$nombre_archivo = $idInmue;
		
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  {
			echo "ERROR DE TAMANO O FORMATO"."<br>";
		} else{
			if (!file_exists($ruta_final_privada)) {
				@mkdir($ruta_final_privada,0755,true);
			}
			
			if (file_exists($ruta_final_privada.$nombre_archivo.".jpg"))
				@unlink($ruta_final_privada.$nombre_archivo.".jpg");
			 
			if (move_uploaded_file($_FILES['userfile1']['tmp_name'], $ruta_final_privada.$nombre_archivo.".jpg"))
			{ 
			   	echo "
			   	<script>
			   	window.opener.$('#documentoInmueble').attr('src','".$ruta_final_Mostrarprivada.$nombre_archivo.".jpg');
			   	window.close();
			   	</script>
			   	"; 
			}else{ 
			   echo "Ocurrio algun error al subir el fichero. No pudo guardarse."; 
			} 
		} 		
	
	}	

?>
    </th>
  </tr>
</table>

	<p align="center">
        <form>
            <input type=button value="Cerrar" onClick="window.close()">
        </form>     
    </p>

</body>