<?php
	session_start(); //inicializo sesión

	error_reporting(0);	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_REQUEST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	$requestData = $_REQUEST;
	
	switch ($accion) 
	{
		case 'obtenerPropietario':
			$idPropietario = mysqli_real_escape_string($conexion, $_POST['idPropietario']);
						
			$consulta = 'SELECT id, nombre FROM inmo_clientes WHERE idSitioWeb = ' . $idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			$html = '';
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//Si tenemos la idZona igual que la id, la marcamos
				$selected = ($idPropietario == $row['id']) ? 'selected="selected"':'';
				//añadimos el option
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.utf8_encode($row['nombre']).'</option>';
			}
			echo $html;
		break;
		case 'obtenerInmueblesConId':
			
			$refInmueble = mysqli_real_escape_string($conexion, $_POST['refInmueble']);
			
			$consulta = '
			SELECT ref, inmueble 
			FROM inmo_inmuebles
			WHERE idSitioWeb = ' . $idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			$html = '';
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//Si tenemos la refInmueble igual que la id, la marcamos
				$selected = ($refInmueble == $row['ref']) ? 'selected="selected"':'';
				
				//añadimos el option
				$html .= '<option value="'.$row['ref'].'" '.$selected.'>'.$row['ref'].' - '.utf8_encode($row['inmueble']).'</option>';
			}
			echo $html;
		break;
		case 'obtenerInmuebles':
			$consulta = '
			SELECT ref, inmueble, direccion, precioalquiler
			FROM inmo_inmuebles
			WHERE idSitioWeb = ' . $idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			$html = '';
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//añadimos el option
				$html .= '<option value="'.$row['ref'].'">'.$row['ref'].' - '.utf8_encode($row['inmueble']).' - '.utf8_encode($row['direccion']).' - '.$row['precioalquiler'].'€</option>';
			}
			echo $html;
		break;
		case 'obtenerCliente':
			$idCliente = mysqli_real_escape_string($conexion, $_POST['idCliente']);
						
			$consulta = '
			SELECT id, nombre, tlf1 
			FROM inmo_clientes 
			WHERE idSitioWeb = ' . $idSitioWeb . ' 
			AND id = '.$idCliente.';';
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$tabla[$i] = $row;
				$i++;
			}
			echo json_encode($tabla);
		break;
		case 'leerVisita':
			$idVisita = mysqli_real_escape_string($conexion, $_POST['id']);
					
			$consulta = '
			SELECT v.*, 
			c.id as cliente_id,
			c.nombre as cliente_nombre,
			c.tlf1 as cliente_telefono,
			i.ref as inmueble_ref,
			z.nombre as inmueble_zona,
			u.id as usuario_id,
			u.nombre as usuario_nombre
			FROM inmo_visitas AS v
			LEFT JOIN inmo_clientes AS c ON v.cliente = c.id
			LEFT JOIN inmo_inmuebles AS i ON v.inmueble = i.id
			LEFT JOIN usuarios AS u ON v.usuario = u.id
			LEFT JOIN inmo_zonas AS z ON i.zona = z.id
			WHERE v.idSitioWeb = ' . $idSitioWeb . ' 
			AND v.id = '.$idVisita.';';

			$registro = mysqli_query($conexion, $consulta);
			$tabla = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$tabla[$i] = array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
		break;
		case 'listaVisitasCliente':
			$idCliente = mysqli_real_escape_string($conexion, $_POST['idCliente']);
						
			$consulta = '
			SELECT v.*, 
			c.nombre as cliente_nombre,
			i.ref as inmueble_ref,
			u.nombre as usuario_nombre
			FROM inmo_visitas AS v
			LEFT JOIN inmo_clientes AS c ON v.cliente = c.id
			LEFT JOIN inmo_inmuebles AS i ON v.inmueble = i.id
			LEFT JOIN usuarios AS u ON v.usuario = u.id
			WHERE v.idSitioWeb = ' . $idSitioWeb . ' 
			AND v.cliente = '.$idCliente;
			
			//echo $consulta;
			
			//Obtenemos el total
			$registroTotal = mysqli_query($conexion, $consulta);
			$recordsTotal = mysqli_num_rows($registroTotal);
			$recordsTotalFiltered = $recordsTotal;
			
			//Limitamos los registros por pagina
			
			if (isset($requestData['start'])) {
				$consulta .= " LIMIT ".$requestData['start']." ,".$requestData['length'];	
			} else {
				$consulta .= " LIMIT 0,100";
			}
			
			$registro = mysqli_query($conexion, $consulta);
			
			$resultado = array(
				"draw" =>  intval( $requestData['draw'] ),
				"recordsTotal" => $recordsTotal,
				"recordsFiltered" => $recordsTotalFiltered,
				"data" => array()
			);
    
			$totalRegistros = 0;
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//$edita = '<a href=\"#\" onclick=\"modificaApuntes('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$edita = '<a href="#" onclick="modificaApuntesPopup('.$row['id'].')" class="iconModificar"><i class="fa fa-edit fa-2x"></i></a>';
				$borra = '<a class="delete" href="#" onclick="eliminaApuntes('.$row['id'].')"><i class="fa fa-trash-o fa-2x"></i></a>';		
				
				switch ($row['tipo']) {
					case 3:
						$estado = 'Eliminado';
					break;
					default:
						$estado = 'Publicado';
					break;
				}
				
				$creado = formatDate($row['fecha']);
				$cita = formatDate($row['fechaaviso']);
				
				
				$comentario = trim($row['comentarios']);
				$comentario = utf8_encode($comentario);
				$comentario = nl2br($comentario);
				$comentario = strip_tags($comentario);
				$comentario = preg_replace("/\r\n|\r|\n/",' ',$comentario);
				$comentario = str_replace('"','',$comentario);
				$comentario = str_replace("'","",$comentario);
				$comentario = str_replace('	','',$comentario);
				
				$comentario = substr($comentario,0,50);
				if (strlen(trim($row['comentarios'])) > 50) {
					$comentario .= '...';
				}
				
				$data = array(
					'id' => $row['id'],
					'cliente' => utf8_encode($row['cliente_nombre']),
					'tipo' => $row['tipo'],
					'creado' => $creado,
					'cita' => $cita,
					'inmueble' => $row['inmueble_ref'],
					'comentario' => $comentario,
					'usuario' => utf8_encode($row['usuario_nombre']),
					'acciones' => $edita.$borra
				);
				
				$resultado['data'][] = $data;
			}
			echo json_encode($resultado);
		break;
		case 'listaVisitas':
			$consulta = '
			SELECT v.*, 
			c.nombre as cliente_nombre,
			i.ref as inmueble_ref,
			u.nombre as usuario_nombre
			FROM inmo_visitas AS v
			LEFT JOIN inmo_clientes AS c ON v.cliente = c.id
			LEFT JOIN inmo_inmuebles AS i ON v.inmueble = i.id
			LEFT JOIN usuarios AS u ON v.usuario = u.id
			WHERE v.idSitioWeb = ' . $idSitioWeb;
			
			//Campos de filtrado
			//$busca 			= $_POST['filtro_apunte_busqueda'];
			$cliente 		= $_REQUEST['filtro_apunte_cliente'];
			$inmueble 		= $_REQUEST['filtro_apunte_inmueble'];	
			$desde 			= $_REQUEST['filtro_apunte_desde'];
			$hasta 			= $_REQUEST['filtro_apunte_hasta'];
			$desdeaviso 	= $_REQUEST['filtro_apunte_desdeaviso'];
			$hastaaviso 	= $_REQUEST['filtro_apunte_hastaaviso'];
			$cbApunte 		= $_REQUEST['filtro_apunte_cbApunte'];
			$lstUsuario 	= $_REQUEST['filtro_apunte_lstUsuario'];	
			$lstOrden 		= $_REQUEST['filtro_apunte_lstOrden'];	
			$seguimiento 	= $_REQUEST['filtro_apunte_chSeguimiento'];
			$comentarios 	= $_REQUEST['filtro_apunte_chComentarios'];
			
			
			if ($cbApunte==""){ $cbApunte=="Todos";}
			if ($lstUsuario==""){ $lstUsuario = "Todos";}
			/*
			if ($busca != "") {
				$consulta .= "
				 AND (
					c.nombre LIKE '%".$busca."%' 
					OR
					v.inmueble LIKE '%".$busca."%' 
					OR
					v.comentarios LIKE '%".$busca."%' 
					OR
					v.tipo LIKE '%".$busca."%' 
				)				
				";
			}
			*/
			if ($lstOrden=="") {
					$lstOrden = "fecha";
			} elseif($lstOrden=="inmediato") {
				$consulta .= " AND (MONTH(v.fechaaviso) = MONTH(DATE_ADD(CURDATE(),INTERVAL 1 MONTH)) OR MONTH(v.fechaaviso) = MONTH(CURDATE()) AND DAY(v.fechaaviso) > DAY(CURDATE()) OR MONTH(v.fechaaviso) = MONTH(CURDATE()) AND DAY(v.fechaaviso) = DAY(CURDATE()))";		
			}
			
			//a�ado campos para la b�squeda concretando por campo
			if ($cliente != ""){
				$consulta .= " AND c.nombre LIKE '%".$cliente."%'";
			}
		
			if ($inmueble != ""){
				$consulta .= " AND v.inmueble LIKE '%".$nombre."%'";
			}	
			
			if ($desde != "") {
				$desde = str_replace("/", "-", $desde);
				$consulta .= " AND v.fecha >= '".date_format(date_create($desde), 'Y-m-d')."'";
			}
			
			if ($hasta != "") {
				$hasta = str_replace("/", "-", $hasta);	
				$consulta .= " AND v.fecha <= '".date_format(date_create($hasta), 'Y-m-d')." 23:59:59'";
			}
		
			if ($desdeaviso != "") {
				$desdeaviso = str_replace("/", "-", $desdeaviso);
				$consulta .= " AND v.fechaaviso >= '".date_format(date_create($desdeaviso), 'Y-m-d')."'";
			}
			
			if ($hastaaviso != "") {
				$hastaaviso = str_replace("/", "-", $hastaaviso);	
				$consulta .= " AND v.fechaaviso <= '".date_format(date_create($hastaaviso), 'Y-m-d')." 23:59:59'";
			}
			
			if ($cbApunte != "Todos" AND $cbApunte != ""){
				$consulta .= " AND v.tipo = '".$cbApunte."'";
			}	
		
			if (strlen($lstUsuario) > 0) {
				if ($lstUsuario != "Todos"){
					$consulta .= " AND v.usuario = '".$lstUsuario."'";
				}	
			}
		
			if ($seguimiento == "true") {
				$consulta .= " and v.seguimiento = 'true'";		
			}
			
			/*
			if ($lstOrden != "inmediato")
			{
				$consulta .= " ORDER BY v.".$lstOrden." desc";
			}else{
				$consulta .= " ORDER BY v.fechaaviso desc";
			}
			*/
			
			if( !empty($requestData['search']['value']) ) {
					
				$consulta .= " AND (
					c.nombre LIKE '%".$requestData['search']['value']."%' 
					OR v.tipo LIKE '%".$requestData['search']['value']."%' 
					OR v.fecha LIKE '%".$requestData['search']['value']."%' 
					OR v.fechaaviso LIKE '%".$requestData['search']['value']."%'  
					OR v.comentarios LIKE '%".$requestData['search']['value']."%'
					OR u.nombre LIKE '%".$requestData['search']['value']."%'    
				)";
			}
			
			$columns = array(
				0 => 'v.id',
				1 => 'c.nombre',
				2 => 'v.tipo',
				3 => 'v.fecha',
				4 => 'v.fechaaviso',
				5 => 'v.inmueble',
				6 => 'v.comentarios',
				7 => 'v.usuario',
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
    
			$totalRegistros = 0;
			while($row = mysqli_fetch_array($registro))
			{
				//$edita = '<a href=\"#\" onclick=\"modificaApuntes('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				$edita = '<a href="#" onclick="modificaApuntes('.$row['id'].')" class="iconModificar"><i class="fa fa-edit fa-2x"></i></a>';
				$borra = '<a class="delete" href="#" onclick="eliminaApuntes('.$row['id'].')"><i class="fa fa-trash-o fa-2x"></i></a>';		
				
				switch ($row['tipo']) {
					case 3:
						$estado = 'Eliminado';
					break;
					default:
						$estado = 'Publicado';
					break;
				}
				
				$creado = formatDate($row['fecha']);
				$cita = formatDate($row['fechaaviso']);
				
				
				$comentario = trim($row['comentarios']);
				$comentario = utf8_encode($comentario);
				$comentario = nl2br($comentario);
				$comentario = strip_tags($comentario);
				$comentario = preg_replace("/\r\n|\r|\n/",' ',$comentario);
				$comentario = str_replace('"','',$comentario);
				$comentario = str_replace("'","",$comentario);
				$comentario = str_replace('	','',$comentario);
				
				$comentario = substr($comentario,0,50);
				if (strlen(trim($row['comentarios'])) > 50) {
					$comentario .= '...';
				}
				
				$data = array(
					'id' => $row['id'],
					'cliente' => utf8_encode($row['cliente_nombre']),
					'tipo' => $row['tipo'],
					'creado' => $creado,
					'cita' => $cita,
					'inmueble' => $row['inmueble_ref'],
					'comentario' => $comentario,
					'usuario' => $row['usuario_nombre'],
					'acciones' => $edita.$borra
				);
				
				$resultado['data'][] = $data;
			}
			echo json_encode($resultado);
		break;
		case 'elimina':
			$idZona = mysqli_real_escape_string($conexion, $_POST['id']);
			
			$consulta = "
			UPDATE inmo_visitas SET estado = 3
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
			$id = mysqli_real_escape_string($conexion,$_POST['apuntes_txtId']);	
			$usuario = mysqli_real_escape_string($conexion,$_POST['apuntes_txtUsuaId']);									
			$cliente = mysqli_real_escape_string($conexion,$_POST['apuntes_txtPropId']);
			$inmueble = mysqli_real_escape_string($conexion,$_POST['apuntes_txtInmoId']);
			//$fechaaviso = mysqli_real_escape_string($conexion,$_POST['cbAno']."-".$_POST['cbMes']."-".$_POST['cbDia']." ".$_POST['cbHora'].":".$_POST['cbMinuto'].":00");		
		
			//$datos = mysqli_real_escape_string($conexion,$_POST['datos']);
			$comentarios = mysqli_real_escape_string($conexion,$_POST['apuntes_comentarios']);
			$estadoCita = mysqli_real_escape_string($conexion,$_POST['apuntes_estadoCita']);
			//$seguimiento = mysqli_real_escape_string($conexion,$_POST['chSeguimiento']);
			$tipo = mysqli_real_escape_string($conexion,$_POST['apuntes_cbApunte']);					

			$fechaCita = mysqli_real_escape_string($conexion,$_POST['apuntes_fechaCita']);
			
			$split_fechaCita = explode(" ",$fechaCita);
			$split_fecha = explode("/",$split_fechaCita[1]);
			
			$nuevoFormatoFecha = $split_fecha[2] . "-" . $split_fecha[1] . "-" . $split_fecha[0] . " " . $split_fechaCita[0];
			
			$consulta = "
			UPDATE inmo_visitas SET 
			cliente='".$cliente."',  
			usuario = '".$usuario."', 
			inmueble = '".$inmueble."', 
			comentarios='".$comentarios."',
			estadoCita='".$estadoCita."',
			fechaaviso='".$nuevoFormatoFecha."',
			tipo='".$tipo."' 
			WHERE id='".$id."'";
			
			$retorno = mysqli_query($conexion,$consulta);
			
			if ($retorno){
				echo "OK";
			} else{
				echo "KO";
			}			
		break;
		
		case 'inserta':
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos	
			$usuario = mysqli_real_escape_string($conexion,$_POST['apuntes_txtUsuaId']);									
			$cliente = mysqli_real_escape_string($conexion,$_POST['apuntes_txtPropId']);
			$inmueble = mysqli_real_escape_string($conexion,$_POST['apuntes_txtInmoId']);
			//$fechaaviso = mysqli_real_escape_string($conexion,$_POST['cbAno']."-".$_POST['cbMes']."-".$_POST['cbDia']." ".$_POST['cbHora'].":".$_POST['cbMinuto'].":00");		
		
			//$datos = mysqli_real_escape_string($conexion,$_POST['datos']);
			$comentarios = mysqli_real_escape_string($conexion,$_POST['apuntes_comentarios']);
			//$seguimiento = mysqli_real_escape_string($conexion,$_POST['chSeguimiento']);
			$estadoCita = mysqli_real_escape_string($conexion,$_POST['apuntes_estadoCita']);
			$tipo = mysqli_real_escape_string($conexion,$_POST['apuntes_cbApunte']);
			
			$fechaCita = mysqli_real_escape_string($conexion,$_POST['apuntes_fechaCita']);
			$split_fechaCita = explode(" ",$fechaCita);
			$split_fecha = explode("/",$split_fechaCita[0]);
			$nuevoFormatoFecha = $split_fecha[2] . "-" . $split_fecha[1] . "-" . $split_fecha[0] . " " . $split_fechaCita[0];
			
		
			$fecha = date('Y-m-d');
		
			$consulta = "
			INSERT INTO inmo_visitas SET 
			idSitioWeb = " . $idSitioWeb . ",
			cliente='".$cliente."',  
			usuario = '".$usuario."',
			fecha = '".$fecha."',  
			inmueble = '".$inmueble."', 
			comentarios='".$comentarios."',
			fechaaviso='".$nuevoFormatoFecha."',
			estadoCita='".$estadoCita."',
			tipo='".$tipo."';";
		
			$retorno = mysqli_query($conexion,$consulta);
			if ($retorno){
				echo "OK";
			} else{
				echo "KO";
			}		
		break;

		
	}
?>
