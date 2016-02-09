<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_REQUEST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	$requestData = $_REQUEST;
	
	switch ($accion) 
	{
		case 'obtenerZonas':
			$idZona = mysqli_real_escape_string($conexion, $_POST['idZona']);
			$idSubZona = mysqli_real_escape_string($conexion, $_POST['idSubZona']);
						
			$consulta = '
			SELECT * 
			FROM inmo_zonas;';
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			$html = '<option value="0">Sin padre</option>';
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//Si es la misma zona, la saltamos (no puede ser hija de si misma)
				if ($idZona == $row['id']) continue;
				
				//Si tenemos la idZona igual que la id, la marcamos
				$selected = ($idSubZona == $row['id']) ? 'selected="selected"':'';
				//añadimos el option
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.utf8_encode($row['nombre']).'</option>';
			}
			echo $html;
		break;
		case 'leerZona':
			$idZona = mysqli_real_escape_string($conexion, $_POST['id']);			
			$consulta = '
			SELECT * FROM inmo_zonas
			WHERE id = '.$idZona.';';
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
		break;	
		case 'listaZonas':			
			$consulta = "
			SELECT z.*, zz.nombre as subzona_nombre
			FROM inmo_zonas AS z
			LEFT JOIN inmo_zonas AS zz ON z.id = zz.subzona
			WHERE 1 = 1";
			
			
			if( !empty($requestData['search']['value']) ) {
					
				$consulta .= " AND (
					z.id LIKE '%".$requestData['search']['value']."%' 
					OR z.nombre LIKE '%".$requestData['search']['value']."%' 
					OR z.descripcion LIKE '%".$requestData['search']['value']."%' 
					OR zz.nombre LIKE '%".$requestData['search']['value']."%'  
					OR z.estado LIKE '%".$requestData['search']['value']."%'   
				)";
			}
			
			$columns = array(
				0 => 'z.id',
				1 => 'z.nombre',
				2 => 'z.descripcion',
				3 => 'zz.nombre',
				4 => 'z.estado',
			);
			
			$consulta .= " ORDER BY " . $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];
			
			//Obtenemos el total
			$registroTotal = mysqli_query($conexion, $consulta);
			$recordsTotal = mysqli_num_rows($registroTotal);
			$recordsTotalFiltered = $recordsTotal;
			
			//Limitamos los registros por pagina
			$consulta .= " LIMIT ".$requestData['start']." ,".$requestData['length'];
			$registro = mysqli_query($conexion, $consulta);
			
			$resultado = array(
				"draw" =>  intval( $requestData['draw'] ),
				"recordsTotal" => $recordsTotal,
				"recordsFiltered" => $recordsTotalFiltered,
				"data" => array()
			);
			//echo $consulta;
    
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href="#" onclick="modifica('.$row['id'].')" class="iconModificar"><i class="fa fa-edit fa-2x"></i></a>';
				$borra = '<a class="delete" href="#" onclick="elimina('.$row['id'].')"><i class="fa fa-trash-o fa-2x"></i></a>';		
				
				switch ($row['estado']) {
					case 3:
						$estado = 'Eliminado';
					break;
					default:
						$estado = 'Publicado';
					break;
				}
				$data = array(
					'id' => $row['id'],
					'nombre' => utf8_encode($row['nombre']),
					'descripcion' => $row['descripcion'],
					'subzona' => $row['subzona_nombre'],
					'estado' => $estado,
					'acciones' => $edita.$borra
				);
				$resultado['data'][] = $data;
			}
			echo json_encode($resultado);
		break;
		case 'elimina':
			$idZona = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$consulta = "
			UPDATE inmo_zonas SET
			estado = 3
			WHERE id ='".$idZona."'";
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idZona = mysqli_real_escape_string($conexion,$_POST['id']);	
			$nombre = mysqli_real_escape_string($conexion,$_POST['nombre']);									
			$descripcion = mysqli_real_escape_string($conexion,$_POST['descripcion']);
			$subzona = mysqli_real_escape_string($conexion,$_POST['subzona']);		
		
			$consulta = "
			UPDATE inmo_zonas SET 
			nombre='".$nombre."', 
			descripcion='".$descripcion."', 
			subzona='".$subzona."'
			WHERE id='".$idZona."'";

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			} else{
				echo "KO";
			}
			
		break;
		
		case 'inserta':
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$nombre = mysqli_real_escape_string($conexion,$_POST['nombre']);									
			$descripcion = mysqli_real_escape_string($conexion,$_POST['descripcion']);
			$subzona = mysqli_real_escape_string($conexion,$_POST['subzona']);		
			
			$consulta = "
			INSERT INTO inmo_zonas (nombre, descripcion, subzona, estado) 
			VALUES ('".$nombre."', '".$descripcion."', '".$subzona."', 1)";
			
			echo $consulta;

			$retorno = mysqli_query($conexion, $consulta);
			
			//si ha insertado correctamente
			if ($retorno){
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
