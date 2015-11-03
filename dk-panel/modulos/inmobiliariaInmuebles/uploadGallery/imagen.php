<?php
	session_start();
	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");

	//GESTIONO PARA SUBIR LA IMAGEN
	$refM = $_POST['numero'];
	
	include("./../../../conexion.php");
	include("./../../../funciones.php");
	
	//tomo los parámetros de configuración del ftp
	$consulta = "SELECT inmo_rutaPublica FROM sitiosweb WHERE id = ".$_SESSION['sitioWeb'];
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
	
	$ruta_final_publica = $pre_ruta . $row['inmo_rutaPublica'] . DIRECTORY_SEPARATOR . $idSitioWeb . DIRECTORY_SEPARATOR;
	
	
	if (!file_exists($ruta_final_publica))
		@mkdir($ruta_final_publica,0755,true);
	
?>


<table width="100%" border="0" cellpadding="3">
  <tr>
    <th align="left" scope="col">
	<?php 
	
		$tipo_archivo = $_FILES['userfile1']['type']; 
		$tamano_archivo = $_FILES['userfile1']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".1.jpg"))
		  { 
		  	$nombre_archivo = $refM.".1.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra1']) && ($_POST['chBorra1'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb1']) && ($_POST['chWeb1'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.1.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.1.jpg"))
		  { 
			$nombre_archivo = $refM."w.1.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra1'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb1'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".1.jpg");				
				}
			}	   		
		  } 
		?>	</th>
    <td align="left" scope="col">
	<?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 1<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".1.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile1']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <th width="4%" align="left" scope="col"><?php 
	
		$tipo_archivo = $_FILES['userfile2']['type']; 
		$tamano_archivo = $_FILES['userfile2']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".2.jpg"))
		  { 
		  	$nombre_archivo = $refM.".2.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra2']) && ($_POST['chBorra2'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb2']) && ($_POST['chWeb2'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.2.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.2.jpg"))
		  { 
			$nombre_archivo = $refM."w.2.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra2'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb2'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".2.jpg");				
				}
			}	   		
		  } 
		?></th>
    <td width="29%" align="left" scope="col"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 2<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".2.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile2']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <th width="4%" align="left" scope="col"><?php 
	
		$tipo_archivo = $_FILES['userfile3']['type']; 
		$tamano_archivo = $_FILES['userfile3']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".3.jpg"))
		  { 
		  	$nombre_archivo = $refM.".3.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra3']) && ($_POST['chBorra3'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb3']) && ($_POST['chWeb3'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.3.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.3.jpg"))
		  { 
			$nombre_archivo = $refM."w.3.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra3'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb3'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".3.jpg");				
				}
			}	   		
		  } 
		?></th>
    <td width="29%" align="left" scope="col"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 3<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".3.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile3']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
  </tr>
  <tr>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile4']['type']; 
		$tamano_archivo = $_FILES['userfile4']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".4.jpg"))
		  { 
		  	$nombre_archivo = $refM.".4.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra4']) && ($_POST['chBorra4'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb4']) && ($_POST['chWeb4'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.4.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.4.jpg"))
		  { 
			$nombre_archivo = $refM."w.4.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra4'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb4'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".4.jpg");				
				}
			}	   		
		  } 
		?>      <br /></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 4<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".4.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile4']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile5']['type']; 
		$tamano_archivo = $_FILES['userfile5']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".5.jpg"))
		  { 
		  	$nombre_archivo = $refM.".5.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra5']) && ($_POST['chBorra5'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb5']) && ($_POST['chWeb5'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.5.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.5.jpg"))
		  { 
			$nombre_archivo = $refM."w.5.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra5'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb5'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".5.jpg");				
				}
			}	   		
		  } 
		?>      <br /></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 5<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".5.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile5']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
        
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile6']['type']; 
		$tamano_archivo = $_FILES['userfile6']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".6.jpg"))
		  { 
		  	$nombre_archivo = $refM.".6.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra6']) && ($_POST['chBorra6'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb6']) && ($_POST['chWeb6'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.6.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.6.jpg"))
		  { 
			$nombre_archivo = $refM."w.6.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra6'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb6'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".6.jpg");				
				}
			}	   		
		  } 
		?>      <br /></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 6<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".6.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile6']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
  </tr>
  <tr>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile7']['type']; 
		$tamano_archivo = $_FILES['userfile7']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".7.jpg"))
		  { 
		  	$nombre_archivo = $refM.".7.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra7']) && ($_POST['chBorra7'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb7']) && ($_POST['chWeb7'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.7.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.7.jpg"))
		  { 
			$nombre_archivo = $refM."w.7.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra7'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb7'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".7.jpg");				
				}
			}	   		
		  } 
		?>      <br /></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 7<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".7.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile7']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile8']['type']; 
		$tamano_archivo = $_FILES['userfile8']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".8.jpg"))
		  { 
		  	$nombre_archivo = $refM.".8.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra8']) && ($_POST['chBorra8'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb8']) && ($_POST['chWeb8'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.8.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.8.jpg"))
		  { 
			$nombre_archivo = $refM."w.8.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra8'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb8'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".8.jpg");				
				}
			}	   		
		  } 
		?>      <br /></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 8<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".8.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile8']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
        
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile9']['type']; 
		$tamano_archivo = $_FILES['userfile9']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".9.jpg"))
		  { 
		  	$nombre_archivo = $refM.".9.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra9']) && ($_POST['chBorra9'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb9']) && ($_POST['chWeb9'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.9.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.9.jpg"))
		  { 
			$nombre_archivo = $refM."w.9.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra9'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb9'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".9.jpg");				
				}
			}	   		
		  } 
		?></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 9<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".9.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile9']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
        
  </tr>
  <tr>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile10']['type']; 
		$tamano_archivo = $_FILES['userfile10']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".10.jpg"))
		  { 
		  	$nombre_archivo = $refM.".10.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra10']) && ($_POST['chBorra10'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb10']) && ($_POST['chWeb10'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.10.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.10.jpg"))
		  { 
			$nombre_archivo = $refM."w.10.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra10'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb10'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".10.jpg");				
				}
			}	   		
		  } 
		?></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 10<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".10.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile10']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile11']['type']; 
		$tamano_archivo = $_FILES['userfile11']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".11.jpg"))
		  { 
		  	$nombre_archivo = $refM.".11.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra11']) && ($_POST['chBorra11'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb11']) && ($_POST['chWeb11'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.11.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.11.jpg"))
		  { 
			$nombre_archivo = $refM."w.11.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra11'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb11'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".11.jpg");				
				}
			}	   		
		  } 
		?></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 11<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".11.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile11']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile12']['type']; 
		$tamano_archivo = $_FILES['userfile12']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".12.jpg"))
		  { 
		  	$nombre_archivo = $refM.".12.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra12']) && ($_POST['chBorra12'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb12']) && ($_POST['chWeb12'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.12.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.12.jpg"))
		  { 
			$nombre_archivo = $refM."w.12.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra12'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb12'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".12.jpg");				
				}
			}	   		
		  } 
		?></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 12<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".12.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile12']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
  </tr>
  <tr>
    <td width="4%" align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile13']['type']; 
		$tamano_archivo = $_FILES['userfile13']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".13.jpg"))
		  { 
		  	$nombre_archivo = $refM.".13.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra13']) && ($_POST['chBorra13'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb13']) && ($_POST['chWeb13'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.13.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.13.jpg"))
		  { 
			$nombre_archivo = $refM."w.13.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra13'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb13'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".13.jpg");				
				}
			}	   		
		  } 
		?>      </td>
    <td width="30%" align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 13<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".13.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile13']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?>      <br /></td>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile14']['type']; 
		$tamano_archivo = $_FILES['userfile14']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".14.jpg"))
		  { 
		  	$nombre_archivo = $refM.".14.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra14']) && ($_POST['chBorra14'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb14']) && ($_POST['chWeb14'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.14.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.14.jpg"))
		  { 
			$nombre_archivo = $refM."w.14.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra14'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb14'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".14.jpg");				
				}
			}	   		
		  } 
		?></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 14<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".14.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile14']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	
	?></td>
    <td align="left"><?php 
	
		$tipo_archivo = $_FILES['userfile15']['type']; 
		$tamano_archivo = $_FILES['userfile15']['size']; 
	
		  if (file_exists($ruta_final_publica.$refM.".15.jpg"))
		  { 
		  	$nombre_archivo = $refM.".15.jpg";
      		echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ( isset($_POST['chBorra15']) && ($_POST['chBorra15'] == "on") )
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ( isset($_POST['chWeb15']) && ($_POST['chWeb15'] == "si") ) 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM."w.15.jpg");				
				}
			} 
			
		  }elseif (file_exists($ruta_final_publica.$refM."w.15.jpg"))
		  { 
			$nombre_archivo = $refM."w.15.jpg";
		  	echo '<img height="50px" src="'.$ruta_final_publica.$nombre_archivo.'" />';
			if ($_POST['chBorra15'] == "on") 
			{
				unlink($ruta_final_publica.$nombre_archivo);
				echo "<br />Borrada";
			}else{ //sino está borrando		
				//Para poner como web o no
				if ($_POST['chWeb15'] != "si") 
				{
					rename($ruta_final_publica.$nombre_archivo, $ruta_final_publica.$refM.".15.jpg");				
				}
			}	   		
		  } 
		?></td>
    <td align="left"><?php
	//datos del arhivo 1
	echo "Fotograf&iacute;a 15<br>";
	
	//Para subir el fichero
	if ($tipo_archivo <> "")
	{ $nombre_archivo = $refM.".15.jpg";
		//compruebo si las caracter�sticas del archivo son las que deseo 
		if ( !((strpos($tipo_archivo, "gif")) || (strpos($tipo_archivo, "jpeg")) ) && ( ($tamano_archivo < 300000) ) )  { 
			echo "Error en tama&ntilde;o o formato"."<br>";
				}else{ 
			if (move_uploaded_file($_FILES['userfile15']['tmp_name'], $ruta_final_publica.$nombre_archivo)){ 
			   echo "El archivo ha sido cargado correctamente."; 
			}else{ 
			   echo "Ocurri&oacute; alg&uacute;n error al subir el fichero. No pudo guardarse."; 
			} 
		}
	}
	?>
	</td>
	<script>
	window.opener.obtenerGaleriaImagenes();
	</script>	
  </tr>
</table>

