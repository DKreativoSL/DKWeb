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
		case 'obtenerDocumentoCliente':
			$idCliente = mysqli_real_escape_string($conexion, $_POST['idCliente']);
			
			$ruta_base_privada = getInmoPrivada($conexion,$idSitioWeb);
			echo '../'.$ruta_base_privada.'/'.$idSitioWeb.'/'.$idCliente.'.jpg';
		break;
		
		case 'leerCliente':
			$idCliente = mysqli_real_escape_string($conexion, $_POST['id']);			
			$consulta = '
			SELECT * FROM inmo_clientes
			WHERE idSitioWeb = ' . $idSitioWeb . '
			AND id = '.$idCliente.';';
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				
				$row['fechaalta'] = formatDate($row['fechaalta']);
				$row['fechabaja'] = formatDate($row['fechabaja']);				
				
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
		break;	
		case 'listaClientes':			
			/*
			$consulta = "
			SELECT clientes.id as IId, 
			clientes.nif, 
			clientes.nombre, 
			clientes.tlf1, 
			clientes.tlf2, 
			clientes.fechaalta, 
			clientes.fechabaja, 
			clientes.tipoc, 
			clientes.usuario, 
			clientes.email, 
			usuarios.nombre as usuarioname 
			FROM inmo_clientes AS clientes
			LEFT JOIN usuarios ON clientes.usuario = usuarios.id
			WHERE clientes.usuario = ".$idUsuario;
			*/
			$consulta = "
			SELECT clientes.id as IId, 
			clientes.nif, 
			clientes.nombre, 
			clientes.tlf1, 
			clientes.tlf2, 
			clientes.fechaalta, 
			clientes.fechabaja, 
			clientes.tipoc, 
			clientes.usuario, 
			clientes.email, 
			usuarios.nombre as usuarioname 
			FROM inmo_clientes AS clientes
			LEFT JOIN usuarios ON clientes.usuario = usuarios.id
			WHERE idSitioWeb = " . $idSitioWeb;
			
			//Campos de busqueda
			//$busca 		= $_POST['filtro_cliente_busqueda'];
			$nif 		= $_POST['filtro_cliente_nif'];
			$nombre 	= $_POST['filtro_cliente_nombre'];
			$tlf 		= $_POST['filtro_cliente_tlf'];
			$desde 		= $_POST['filtro_cliente_desde'];
			$hasta 		= $_POST['filtro_cliente_hasta'];
			$desdebaja 	= $_POST['filtro_cliente_desdebaja'];
			$hastabaja 	= $_POST['filtro_cliente_hastabaja'];
				
			$lstTipo 	= $_POST['filtro_cliente_lstTipo'];
			$lstUsuario = $_POST['filtro_cliente_lstUsuario'];	
			$lstEstado 	= $_POST['filtro_cliente_lstEstado'];	
			
			if ($lstTipo == ""){ $lstTipo=="Todos"; }
			if ($lstUsuario == ""){ $lstUsuario = "Todos"; }

			if ($nif != ""){
				$consulta .= " AND clientes.nif LIKE '%".$nif."%'";
			}

			if ($nombre != ""){
				$consulta .= " AND clientes.nombre LIKE '%".$nombre."%'";
			}	
	
			if ($tlf != ""){
				$consulta .= " AND (clientes.tlf1 LIKE '%".$tlf."%' OR clientes.tlf2 LIKE '%".$tlf."%')";
			}
			
			//echo "desde".date_format(date_create($desde), 'Y-m-d');
			if ($desde != "") {
				$desde = str_replace("/", "-", $desde);
				$consulta .= " AND fechaalta >= '".date_format(date_create($desde), 'Y-m-d')."'";
			}
			if ($hasta != "") {
				$hasta = str_replace("/", "-", $hasta);	
				$consulta .= " AND fechaalta <= '".date_format(date_create($hasta), 'Y-m-d')."'";
			}
		
			if ($desdebaja != "") {
				$desdebaja = str_replace("/", "-", $desdebaja);
				$consulta .= " AND fechabaja >= '".date_format(date_create($desdebaja), 'Y-m-d')."'";
			}
			if ($hastabaja != "") {
				$hastabaja = str_replace("/", "-", $hastabaja);	
				$consulta .= " AND fechabaja <= '".date_format(date_create($hastabaja), 'Y-m-d')."'";
			}
				
			
			if ($lstTipo != "Todos" and $lstTipo != ""){
				$consulta .= " AND clientes.tipoc = ".$lstTipo;
			}	
		
			if ($lstUsuario != "Todos"){
				$consulta .= " AND clientes.usuario = '".$lstUsuario."'";
			}	
		
			if ($lstEstado == "Alta"){
				$consulta .= " AND (clientes.fechabaja = '0000-00-00' OR clientes.fechabaja is null)";
			}elseif ($lstEstado == "Baja"){
				$consulta .= " AND (clientes.fechabaja <> '0000-00-00' AND clientes.fechabaja is not null)";	
			}		
			//$consulta .= " ORDER BY clientes.fechaalta desc";
			
			if( !empty($requestData['search']['value']) ) {
					
				$consulta .= " AND (
					clientes.id LIKE '%".$requestData['search']['value']."%' 
					OR clientes.nombre LIKE '%".$requestData['search']['value']."%' 
					OR clientes.tlf1 LIKE '%".$requestData['search']['value']."%' 
					OR clientes.tlf2 LIKE '%".$requestData['search']['value']."%'  
					OR clientes.fechaalta LIKE '%".$requestData['search']['value']."%' 
					OR clientes.usuario LIKE '%".$requestData['search']['value']."%' 
					OR clientes.tipoc LIKE '%".$requestData['search']['value']."%'   
				)";
			}
			
			$columns = array(
				0 => 'clientes.id',
				1 => 'clientes.nombre',
				2 => 'clientes.tlf1',
				3 => 'clientes.fechaalta',
				4 => 'clientes.usuario',
				5 => 'clientes.tipoc'
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
				$edita = '<a href="#" onclick="modifica('.$row['IId'].')" class="iconModificar"><i class="fa fa-edit fa-2x"></i></a>';
				$borra = '<a class="delete" href="#" onclick="elimina('.$row['IId'].')"><i class="fa fa-trash-o fa-2x"></i></a>';
				
				
				$fechaAlta = formatDate($row['fechaalta']);
				$fechaBaja = formatDate($row['fechabaja']);
				
				
				switch ($row['tipoc']) {
					case 0:
						$tipoc = "Comprador";
					break;
					case 1:
						$tipoc = "Vendedor";
					break;
					case 2:
						$tipoc = "Compra/Vende";
					break;
					default:
						$tipoc = "Alquiler";
					break;
				}				
				
				$data = array(
					'id' => $row['IId'],
					'cliente' => utf8_encode($row['nombre']),
					'telefonos' => $row['tlf1'].' - '.$row['tlf2'],
					'fechas' => $fechaAlta.' - '.$fechaBaja,
					'usuario' => $row['usuarioname'],
					'tipo' => $tipoc,
					'acciones' => $edita.$borra
				);
				
				$resultado['data'][] = $data;		

			}
			echo json_encode($resultado);
		break;
		case 'elimina':
			$idCliente = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$fechaBaja = date('Y-m-d');
			
			$consulta = "
			UPDATE inmo_clientes SET
			fechabaja = '".$fechaBaja."'
			WHERE id ='".$idCliente."'";
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':	
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$idCliente = mysqli_real_escape_string($conexion, $_POST['id']);	
			$mnombre = mysqli_real_escape_string($conexion, $_POST['nombre']);									
			$mdireccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
			$mpoblacion = mysqli_real_escape_string($conexion, $_POST['poblacion']);
			$mprovincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
			$mcpostal = mysqli_real_escape_string($conexion, $_POST['cpostal']);
			$mtlf1 = mysqli_real_escape_string($conexion, $_POST['tlf1']);
			$mtlf2 = mysqli_real_escape_string($conexion, $_POST['tlf2']);
			$mfax = mysqli_real_escape_string($conexion, $_POST['fax']);
			$mfuente = mysqli_real_escape_string($conexion, $_POST['fuente']);
			$memail = mysqli_real_escape_string($conexion, $_POST['email']);
	
			$mnif = mysqli_real_escape_string($conexion, $_POST['nif']);
			$mfechabaja = mysqli_real_escape_string($conexion, $_POST['fechabaja']);
			$mbajamotivo = mysqli_real_escape_string($conexion, $_POST['bajamotivo']);
			$mcomentarios = mysqli_real_escape_string($conexion, $_POST['comentarios']);
			$musuario = mysqli_real_escape_string($conexion, $_POST['txtUsuarioId']);
			$mtipoc = mysqli_real_escape_string($conexion, $_POST['tipoc']);	
			
			/*
			if ($mchfechabaja == "on") {
				$mfechabaja = date("Y/m/d") ; 
			} else{
				$mfechabaja = "NULL"; 
			}
			*/
			$mchfechabaja = '';
		
			$consulta = "
			UPDATE inmo_clientes SET 
			usuario=".$musuario.",
			nombre='".$mnombre."', 
			direccion='".$mdireccion."', 
			poblacion='".$mpoblacion."', 
			provincia='".$mprovincia."', 
			cpostal='".$mcpostal."', 
			tlf1='".$mtlf1."', 
			tlf2='".$mtlf2."', 
			email='".$memail."', 
			fax='".$mfax."', 
			fuente='".$mfuente."', 
			nif='".$mnif."', 
			fechabaja='".$mfechabaja."', 
			bajamotivo='".$mbajamotivo."', 
			comentarios='".$mcomentarios."', 
			tipoc='".$mtipoc."' 
			WHERE id='".$idCliente."'";

			echo $consulta;

			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			} else{
				echo "KO";
			}
			
		break;
		
		case 'inserta':
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$mnombre = mysqli_real_escape_string($conexion, $_POST['nombre']);									
			$mtlf1 = mysqli_real_escape_string($conexion, $_POST['tlf1']);
			$mtlf2 = mysqli_real_escape_string($conexion, $_POST['tlf2']);
			$mfax = mysqli_real_escape_string($conexion, $_POST['fax']);
			$mfuente = mysqli_real_escape_string($conexion, $_POST['fuente']);
			
			$memail = mysqli_real_escape_string($conexion, $_POST['email']);
			
			$mcomentarios = mysqli_real_escape_string($conexion, $_POST['comentarios']);
			
			$mnif = mysqli_real_escape_string($conexion, $_POST['nif']);
			$mdireccion = mysqli_real_escape_string($conexion, $_POST['direccion']);
			$mpoblacion = mysqli_real_escape_string($conexion, $_POST['poblacion']);
			$mprovincia = mysqli_real_escape_string($conexion, $_POST['provincia']);
			$mcpostal = mysqli_real_escape_string($conexion, $_POST['cpostal']);
	
			$mfecha = date("Y-m-d");
	
			$musuario = mysqli_real_escape_string($conexion, $_POST['txtUsuarioId']);
			$mtipoc = mysqli_real_escape_string($conexion, $_POST['tipoc']);
			
			if ($mchfechabaja == "on") {
				$mfechabaja = date("Y/m/d") ; 
			} else{
				$mfechabaja = "NULL"; 
			}
			
			$consulta = "
			INSERT INTO inmo_clientes (idSitioWeb, usuario, clave, nombre, direccion, poblacion, provincia, cpostal, tlf1, tlf2, email, fax, fuente, nif, fechaalta, comentarios, tipoc) 
			VALUES (".$idSitioWeb.", ".$musuario.", '','".$mnombre."', '".$mdireccion."', '".$mpoblacion."', '".$mprovincia."', '".$mcpostal."', '".$mtlf1."', '".$mtlf2."', '".$memail."', '".$mfax."', '".$mfuente."', '".$mnif."', '".$mfecha."', '".$mcomentarios."', '".$mtipoc."')";

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
