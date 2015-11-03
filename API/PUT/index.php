<?php
	include("./../../dk-panel/conexion.php");

	$token = $_GET['token'];

/*	COMPRUEBO QUE EL TOKEN SEA CORRECTO 	*/
	$consulta = '
	SELECT id, idUsuarioPropietario
	FROM sitiosweb 
	WHERE token = "'.$token.'"';
	
	$resultado = mysqli_query($conexion, $consulta);
	$registro = mysqli_fetch_array($resultado);
	
	if ($registro['id'] > 0){		
		//es valido lo registro para guardarlo durante toda la sesion
		$idSitioWeb = $registro['id'];
		$idUsuarioPropietario = $registro['idUsuarioPropietario'];
		$accion = $_GET['accion'];
	}else{
		$idSitioWeb = "";
		echo "Sin acceso <br>"; //sino trae nada el registro digo que no es v�lido				
	}


	switch ($accion) {
		
		case "putPost":
			//recojo el churro decodifico base64 y convierto a json
			$infoArticulo = json_decode(base64_decode($_GET['datos']), true);
			
			//compruebo si hay algún error en el JSON y lo devuelvo
			switch(json_last_error()) {
				case JSON_ERROR_NONE:
				//Saco los parámetros de la ayuda para insertarlo según convenga:
					
					//la sección a la que va por el nombre del aspx
					$consulta = '
						SELECT id
						FROM secciones 
						WHERE idSitioWeb = "'.$idSitioWeb.'"
						AND nombre = "'.$infoArticulo['idSeccion'].'"';
					$resultado = mysqli_query($conexion, $consulta);
					$row = mysqli_fetch_array($resultado);
					$idSeccion = $row[0];
			
					//Sino existe la sesión, la crea
					if ($idSeccion <= 0) 
					{
						$sqls = 'INSERT INTO secciones SET ';			
						$sqls .= 'nombre="'.$infoArticulo['idSeccion'].'", ';
						$sqls .= 'descripcion="Descripción '.$infoArticulo['idSeccion'].'", ';
						$sqls .= 'tipo="0", privada = "0", orden = "0", estado = "1",';
						$sqls .= 'idSitioWeb = "'.$idSitioWeb.'"';
						
						if (!mysqli_query($conexion, $sqls)){
							$error = "- Error al crear la sección";
							}else{
								//si se añadió bien la sección, hago que tome el último id para añadirlo al artículo
								$infoArticulo['idSeccion'] = "Last_insert_id";
								}
					}
					//Sino se pasa usuario, tomo por defecto el usuario propietario del sitio
					if (!$infoArticulo['idUsuario'])
					{
//		ATENCIÓN!!!!!!
//		********	CONTROLAR QUE EL USUARIO QUE SE PASA ESTÁ DENTRO DEL SITIO WEB AL QUE ESTAMOS AÑADIENDO PARA NO LIARLA PARDA
						
					} else{
						$idUsuario = $idUsuarioPropietario;
					}
					
					//Sino lleva fecha de creación/publicación, le pongo la fecha de hoy
					if (!$infoArticulo['fecha']){
						$infoArticulo['fecha'] = date("Y-m-d H:i:s");
						}

					if (!$infoArticulo['fechaPublicacion']){
						$infoArticulo['fechaPublicacion'] = date("Y-m-d H:i:s");
						}
					
					//finalmente compruebo si existe la publicación incluyendo la sección si no es "Last insert id" que sería que es nuevo campo en nueva sección
					//Sino existe la sesión, la crea
					if ($idSeccion != "Last_insert_id") 
					{
						$consulta = '
							SELECT id
							FROM articulos
							WHERE idSeccion = "'.$infoArticulo['idSeccion'].'"
							AND subtitulo = "'.$infoArticulo['subtitulo'].'"';
							
						$resultado = mysqli_query($conexion, $consulta);
						$row = mysqli_fetch_array($resultado);
						if ($row[0]){
							$sql = 'UPDATE articulos SET ';
							$sql .= 'idSeccion='.$infoArticulo['idSeccion'].', ';
							$sql .= 'idUsuario="'.$infoArticulo['idUsuario'].'", ';
							$sql .= 'titulo="'.$infoArticulo['titulo'].'", ';
							$sql .= 'subtitulo="'.$infoArticulo['subtitulo'].'", ';
							$sql .= 'fecha="'.$infoArticulo['fecha'].'", ';
							$sql .= 'fechaPublicacion="'.$infoArticulo['fechaPublicacion'].'", ';
							$sql .= 'cuerpo="'.addslashes($infoArticulo['cuerpo']).'", ';
							$sql .= 'cuerpoResumen="'.$infoArticulo['cuerpoResumen'].'", ';
							$sql .= 'orden="'.$infoArticulo['orden'].'", ';
							$sql .= 'imagen="'.$infoArticulo['imagen'].'", ';
							$sql .= 'archivo="'.$infoArticulo['archivo'].'", ';
							$sql .= 'url="'.$infoArticulo['url'].'", ';
							$sql .= 'campoExtra="'.$infoArticulo['campoExtra'].'", ';
							$sql .= 'estado=1; ';							
						}else{ //SINO TIENE NADA INSERTO COMO NUEVO
							$sql = 'INSERT INTO articulos SET ';
							$sql .= 'idSeccion='.$infoArticulo['idSeccion'].', ';
							$sql .= 'idUsuario="'.$infoArticulo['idUsuario'].'", ';
							$sql .= 'titulo="'.$infoArticulo['titulo'].'", ';
							$sql .= 'subtitulo="'.$infoArticulo['subtitulo'].'", ';
							$sql .= 'fecha="'.$infoArticulo['fecha'].'", ';
							$sql .= 'fechaPublicacion="'.$infoArticulo['fechaPublicacion'].'", ';
							$sql .= 'cuerpo="'.addslashes($infoArticulo['cuerpo']).'", ';
							$sql .= 'cuerpoResumen="'.$infoArticulo['cuerpoResumen'].'", ';
							$sql .= 'orden="'.$infoArticulo['orden'].'", ';
							$sql .= 'imagen="'.$infoArticulo['imagen'].'", ';
							$sql .= 'archivo="'.$infoArticulo['archivo'].'", ';
							$sql .= 'url="'.$infoArticulo['url'].'", ';
							$sql .= 'campoExtra="'.$infoArticulo['campoExtra'].'", ';
							$sql .= 'estado=1; ';
				
							if (!mysqli_query($conexion, $sql)){
								$error .= "- Error al insertar artículo";
								}
						}
					}
		
					if ($error){
							echo $error;
						}else{
							echo "OK";
						}					
				break;
				
				
				case JSON_ERROR_DEPTH:
					echo ' - Excedido tamaño máximo de la pila <br />';
				break;
				case JSON_ERROR_STATE_MISMATCH:
					echo ' - Desbordamiento de buffer o los modos no coinciden <br />';
				break;
				case JSON_ERROR_CTRL_CHAR:
					echo ' - Encontrado carácter de control no esperado <br />';
				break;
				case JSON_ERROR_SYNTAX:
					echo ' - Error de sintaxis, JSON mal formado <br />';
				break;
				case JSON_ERROR_UTF8:
					echo ' - Caracteres UTF-8 malformados, posiblemente están mal codificados <br />';
				break;
				default:
					echo ' - Error desconocido <br />';
				break;
			}
						
		break;
		
		default:
			echo "Sigue esperando...";
		break;	
						
	}//fin switch

?>