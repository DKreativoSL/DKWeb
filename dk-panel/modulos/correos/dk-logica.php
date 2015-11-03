<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	
	switch ($accion) 
	{
		
		case 'listaSecciones':			
			$consulta = "SELECT * FROM secciones WHERE idSitioWeb = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);			
			break;
					
		
		case 'leeArticulo':
		
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			
			/* Tiro una SQL comprobando que el id pertenece al sitioweb del usuario autenticado */			
			$consulta = "SELECT correos.id, correos.idsitioweb, correos.correo FROM correos, sitiosweb where correos.id = ". $idArticulo." AND correos.idsitioweb = ".$idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);

			$tabla = array(); //creamos un array

			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro))
			{
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
			break;
					
		case 'listaArticulos':		
			$idSeccion = mysqli_real_escape_string($conexion, $_POST['seccion']);
			
			$consulta = "SELECT DISTINCT correos.id, correos.correo, correos.idsitioweb FROM correos, sitiosweb WHERE correos.idsitioweb = ".$idSitioWeb;
			$registro = mysqli_query($conexion, $consulta);

			//guardamos en un array multidimensional todos los datos de la consulta
			$i=0;
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\"><img border=\"0px\" src=\"./imgs/editar.png\" alt=\"Modificar\"/></a>';
				$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><img border=\"0px\" src=\"./imgs/borrar.png\" alt=\"Eliminar\"/></a>';
				
				$tabla.='{"id":"'.$row['id'].'","correo":"'.$row['correo'].'","acciones":"'.$edita.$borra.'"},';		
				$i++;
			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			//echo $consulta;
			echo '{"data":['.$tabla.']}';
			break;
			
		case 'elimina':
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);
			$consulta = "delete from correos where id='".$idArticulo."' AND idsitioweb = '".$idSitioWeb."'";

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				//lanzo el comando para plesk
				include("./../../funcionesPlesk.php");
				mailPlesk('r', $mcorreo, $mpassword, "300M", "true", $idSitioWeb);
				
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idArticulo = mysqli_real_escape_string($conexion, $_POST['id']);	
		
			$mcorreo = mysqli_real_escape_string($conexion, $_POST['correo']);
			$mpassword = mysqli_real_escape_string($conexion, $_POST['password']);
		
			$consulta = "UPDATE correos SET correo='".$mcorreo."' WHERE id=".$idArticulo." AND idsitioweb = '".$idSitioWeb."'";

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				//lanzo el comando para plesk
				include("./../../funcionesPlesk.php");
				mailPlesk('u', $mcorreo, $mpassword, "300M", "true", $idSitioWeb);
				
				echo "OK";
			}else{
				echo "KO";
			};
			
		break;
		
		case 'inserta':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$mcorreo = mysqli_real_escape_string($conexion, $_POST['correo']);
			$mpassword = mysqli_real_escape_string($conexion, $_POST['password']);
		
			$consulta = "INSERT INTO correos (correo, idsitioweb) VALUES ('".$mcorreo."','".$idSitioWeb."')";

			$retorno = mysqli_query($conexion, $consulta);
			
			//si ha insertado correctamente
			if ($retorno){
				
				//lanzo el comando para plesk
				include("./../../funcionesPlesk.php");
				mailPlesk('c', $mcorreo, $mpassword, "300M", "true", $idSitioWeb);
				
				//devuelvo el id recien creado para cargarlo en el documento actual por si pulsara otra vez modificar
				$registro = mysqli_query($conexion, "select last_insert_id()");				
				$row = mysqli_fetch_array($registro);
				echo $row[0];
			}else{
				echo "KO".$consulta;
			};
			
		break;

		
	}
?>
