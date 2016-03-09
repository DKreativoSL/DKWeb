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
		case 'crearPDF':
			$idInmueble = mysqli_real_escape_string($conexion, $_POST['idInmueble']);
			
			$sql_inmueble = '
			SELECT *
			FROM inmo_inmuebles
			WHERE id = '.$idInmueble;
			$registro_inmueble = mysqli_query($conexion, $sql_inmueble);
			
			$infoInmueble = mysqli_fetch_array($registro_inmueble,MYSQL_ASSOC);
			
			if (is_array($infoInmueble)) {
				$sql_zona = '
				SELECT z.nombre as nombre, z.subzona
				FROM inmo_zonas AS z
				WHERE z.id = '.$infoInmueble['zona'];
				
				$registro_zona = mysqli_query($conexion, $sql_zona);
				$infoZona = mysqli_fetch_array($registro_zona,MYSQL_ASSOC);
				
				
				if (is_array($infoZona)) {
					//Formateamos las variables
					$archivoSalida = 'documentos/'.$infoInmueble['id'].'.html';
					
					//Precio
					$precio = number_format ($infoInmueble['precio'], 0, ",", ".");
					
					//Imagen principal
					
					
					$idInmueble = mysqli_real_escape_string($conexion, $_POST['idInmueble']);
					$ruta_base_publica = getInmoPublica($conexion,$idSitioWeb);
					
					$imagenPrincipal = '';
					for ($i=1;$i<=15;$i++) {
						$imagen = '../'.$ruta_base_publica.'/'.$idSitioWeb.'/'.$idInmueble.'.'.$i.'.jpg';
						$imagen_src = '../'.$ruta_base_publica.'/'.$idSitioWeb.'/'.$idInmueble.'.'.$i.'.jpg';
						if (file_exists('../'.$imagen)) {
							$imagenPrincipal = '<img width="1000px" height="465" src="'.$imagen_src.'">';
							break;
						}else{		//compruebo que sea imagen Web
							$imagen = '../'.$ruta_base_publica.'/'.$idSitioWeb.'/'.$idInmueble.'w.'.$i.'.jpg';
							$imagen_src = '../'.$ruta_base_publica.'/'.$idSitioWeb.'/'.$idInmueble.'w.'.$i.'.jpg';
		
							if (file_exists('../'.$imagen)) {
								$imagenPrincipal = '<img width="1000px" height="465" src="'.$imagen_src.'">';
								break;
							}					
						}
					}					
					
					//Obtenemos la base del documento
					$escaparate_base = file_get_contents('print_escaparate.html');
					
					//Aplicamos las modificaciones
					$escaparate_base = str_replace('{{imagenPrincipal}}', $imagenPrincipal, $escaparate_base);
					$escaparate_base = str_replace('{{observaciones}}', $infoInmueble['caracteristicas'], $escaparate_base);
					$escaparate_base = str_replace('{{precio}}', $precio, $escaparate_base);
					$escaparate_base = str_replace('{{zona}}', $infoZona['nombre'], $escaparate_base);
					$escaparate_base = str_replace('{{referencia}}', $infoInmueble['ref'],$escaparate_base);
					$escaparate_base = str_replace('{{mConstruidos}}', $infoInmueble['metroscuadra'], $escaparate_base);
					$escaparate_base = str_replace('{{dormitorios}}', $infoInmueble['habitaciones'], $escaparate_base);
					$escaparate_base = str_replace('{{banos}}', $infoInmueble['banos'], $escaparate_base);
					
					//Eliminamos y volvemos a crear el fichero
					if (!file_exists('../../documentos/')) {
						mkdir('../../documentos/');
					}
					@unlink($archivoSalida);
					@file_put_contents('../../'.$archivoSalida, $escaparate_base);
					
					echo $archivoSalida;
				} else {
					echo 'Error al obtener la zona';
				}
			} else {
				echo 'Error al obtener el inmueble';		
			}	
		break;
		case 'obtenerNuevaReferencia':
			$consulta = '
			SELECT ref
			FROM inmo_inmuebles
			WHERE idSitioWeb = ' . $idSitioWeb . '
			ORDER BY ref ASC LIMIT 1';
			
			$registro = mysqli_query($conexion, $consulta);
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				$newRef = $row['ref'] + 1;
			}
			echo $newRef;
		break;
		case 'listaPropietarios':			
			$consulta = "
			SELECT c.id, 
			c.nif, 
			c.nombre, 
			c.tlf1, 
			c.tlf2, 
			c.usuario, 
			c.email,
			c.nif
			FROM inmo_clientes AS c
			LEFT JOIN usuarios AS u ON c.usuario = u.id
			WHERE c.idSitioWeb = " . $idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				$seleccionar = '<a href=\"#\" data-dismiss=\"modal\" onclick=\"cambiarPropietario('.$row['id'].',\''.$row['nombre'].'\')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				
				$tabla.='{"id":"'.$row['id'].'",';
				$tabla.='"cliente":"'.$row['nombre'].'",';
				$tabla.='"telefonos":"'.$row['tlf1'].' - '.$row['tlf2'].'",';
				$tabla.='"nif":"'.$row['nif'].'",';
				$tabla.='"email":"'.$row['email'].'",';
				$tabla.='"acciones":"'.$seleccionar.'"},';		

			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
		break;
		case 'obtenerUsuariosOption':
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
						
			$consulta = '
			SELECT u.id, u.nombre 
			FROM usuarios AS u
			LEFT JOIN usuariositios AS us ON u.id = us.idUsuario
			WHERE us.idSitioWeb = ' . $idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			$html = '';
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//Si tenemos la idZona igual que la id, la marcamos
				$selected = ($idUsuario == $row['id']) ? 'selected="selected"':'';
				//añadimos el option
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.utf8_encode($row['nombre']).'</option>';
			}
			echo $html;
		break;
		case 'obtenerUsuario':
			$idUsuario = mysqli_real_escape_string($conexion, $_POST['idUsuario']);
						
			$consulta = 'SELECT id, nombre FROM usuarios WHERE id = '.$idUsuario.';';
			
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
		case 'obtenerPropietario':
			$idPropietario = mysqli_real_escape_string($conexion, $_POST['idPropietario']);
						
			$consulta = '
			SELECT id, nombre 
			FROM inmo_clientes
			WHERE idSitioWeb = ' . $idSitioWeb;
			
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
		case 'obtenerZonas':
			$idZona = mysqli_real_escape_string($conexion, $_POST['idZona']);
						
			$consulta = '
			SELECT id, nombre
			FROM inmo_zonas
			WHERE idSitioWeb = ' . $idSitioWeb;
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array();
			$i=0;
			$html = '';
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC))
			{
				//Si tenemos la idZona igual que la id, la marcamos
				$selected = ($idZona == $row['id']) ? 'selected="selected"':'';
				//añadimos el option
				$html .= '<option value="'.$row['id'].'" '.$selected.'>'.$row['nombre'].'</option>';
			}
			echo $html;
		break;
		
		case 'printInmueble':
			$idInmueble = mysqli_real_escape_string($conexion, $_POST['id']);
						
			$consulta = '
			SELECT * 
			FROM inmo_inmuebles 
			WHERE ref = "'.$idInmueble.'";';
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC)) {
				//Corregimos la fecha al formato d-m-Y
				$row['fechaalta'] = formatDate($row['fechaalta']);
				
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}

			$tabla = '<div class="col-xs-3"><img src=""></div><div class="col-xs-9"><div class="col-xs-12"><h2><strong>OBSERVACIONES</strong></h2>observaciones<h1>precio</h1></div></div>';			
			
			//escribimos el contenido en impresion
			$file=fopen("impresion.html","a") or die("Problemas");

			fputs($file,$tabla);
			fclose($file);
			
		break;
		
		case 'leerInmueble':
			$idInmueble = mysqli_real_escape_string($conexion, $_POST['id']);
						
			$consulta = '
			SELECT * 
			FROM inmo_inmuebles 
			WHERE ref = "'.$idInmueble.'";';
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = array(); //creamos un array
			//cargamos en un array multidimensional todos los datos de la consulta
			$i=0;
		
			while($row = mysqli_fetch_array($registro,MYSQL_ASSOC)) {
				//Corregimos la fecha al formato d-m-Y
				$row['fechaalta'] = formatDate($row['fechaalta']);
				
				$tabla[$i] = $row; //array_map("utf8_encode",$row);
				$i++;
			}
			
			echo json_encode($tabla);
			
		break;
		case 'listaInmueblesCliente':
			$idCliente = mysqli_real_escape_string($conexion, $_POST['idCliente']);
			
			$consulta = "
			SELECT inmo_inmuebles.id, inmo_inmuebles.ref, inmo_inmuebles.zona, inmo_inmuebles.inmueble, inmo_inmuebles.direccion, 
			inmo_inmuebles.precio, inmo_inmuebles.fechaalta, inmo_inmuebles.fechamod, inmo_inmuebles.precioalquiler, u.nombre as nombreUsuario, z.nombre AS nombreZona
			FROM inmo_inmuebles
			LEFT JOIN inmo_clientes AS clientes ON clientes.id = inmo_inmuebles.propietario
			LEFT JOIN usuarios AS u ON u.id = inmo_inmuebles.usuario
			LEFT JOIN inmo_zonas AS z ON z.id = inmo_inmuebles.zona
			WHERE inmo_inmuebles.propietario = ".$idCliente. " ";
			
			if( !empty($requestData['search']['value']) ) {
					
				$consulta .= " AND (
					inmo_inmuebles.ref LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.inmueble LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.fechaalta LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.fechamod LIKE '%".$requestData['search']['value']."%'  
					OR z.nombre LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.direccion LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.precio LIKE '%".$requestData['search']['value']."%' 
					OR u.nombre LIKE '%".$requestData['search']['value']."%'  
				)";
			}
			
			$columns = array(
				0 => 'inmo_inmuebles.ref',
				1 => 'inmo_inmuebles.inmueble',
				2 => 'inmo_inmuebles.fechaalta',
				3 => 'inmo_inmuebles.fechamod',
				4 => 'z.nombre',
				5 => 'inmo_inmuebles.direccion',
				6 => 'inmo_inmuebles.precio',
				7 => 'u.nombre',
			);
			
			if (isset($requestData['order'])) {
				$consulta .= " ORDER BY " . $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];	
			} else {
				$consulta .= " ORDER BY inmo_inmuebles.ref ASC";
			}
			
			//Obtenemos el total
			$registroTotal = mysqli_query($conexion, $consulta);
			$recordsTotal = mysqli_num_rows($registroTotal);
			$recordsTotalFiltered = $recordsTotal;
			
			//Limitamos los registros por pagina
			if (isset($requestData['start'])) {
				$consulta .= " LIMIT ".$requestData['start']." ,".$requestData['length'];	
			} else {
				$consulta .= " LIMIT 100";
			}
			$registro = mysqli_query($conexion, $consulta);
			
			if (isset($requestData['draw'])) {
				$drawNumber = $requestData['draw'];	
			} else {
				$drawNumber = rand(100,10000);
			}
			
			$resultado = array(
				"draw" =>  intval( $drawNumber ),
				"recordsTotal" => $recordsTotal,
				"recordsFiltered" => $recordsTotalFiltered,
				"data" => array()
			);
    
			while($row = mysqli_fetch_array($registro, MYSQL_ASSOC))
			{
				$edita = '<a href="#" onclick="modificaInmueble('.$row['ref'].')" class="iconModificar"><i class="fa fa-edit fa-2x"></i></a>';
				$borra = '<a class="delete" href="#" onclick="eliminaInmueble('.$row['ref'].')"><i class="fa fa-trash-o fa-2x"></i></a>';
				
				$fechaAlta = formatDate($row['fechaalta']);
				$fechaMod = formatDate($row['fechamod']);		
				
				$data = array(
					'id' => $row['ref'],
					'inmueble' => $row['inmueble'],
					'fechaAlta' => $fechaAlta,
					'fechaMod' => $fechaMod,
					'zona' => utf8_encode($row['nombreZona']),
					'direccion' => utf8_encode($row['direccion']),
					'precio' => $row['precio'].' - '.$row['precioalquiler'],
					'usuario' => utf8_encode($row['nombreUsuario']),
					'acciones' => $edita.$borra
				);
				
				$resultado['data'][] = $data;	
			}
			echo json_encode($resultado);
		break;
		case 'listaInmuebles':
			//$busca = $_POST['busca'];
			$preciodesde = $_POST['preciodesde'];
			$preciohasta = $_POST['preciohasta'];
			$metrosdesde = $_POST['metrosdesde'];
			$metroshasta = $_POST['metroshasta'];
			$banosdesde = $_POST['banosdesde'];
			$banoshasta = $_POST['banoshasta'];
			//$habitadesde = $_POST['desde'];
			//$habitahasta = $_POST['hasta'];
			$desde = $_POST['desde'];
			$hasta = $_POST['hasta'];
			$desdebaja = $_POST['desdebaja'];
			$hastabaja = $_POST['hastabaja'];
			
			$ascensor = $_POST['chAscensor'];
			$garaje = $_POST['chGaraje'];
			$piscina = $_POST['chPiscina'];
			
			$Alquiler = $_POST['chAlquiler'];
			$Venta = $_POST['chVenta'];
			$AlquilerCompra = $_POST['chAlquilerCompra'];
			$Traspaso = $_POST['chTraspaso'];
			$Promocion = $_POST['chPromocion'];
			
			$zona = $_POST['lstZona'];
			$tipo = $_POST['lstTipo'];
			$propietario = $_POST['propietario'];
			$planta = $_POST['planta'];
			$lstUsuario = $_POST['lstUsuario'];	
			$estado = $_POST['cbEstado'];
			$tlfpropietario = $_POST['tlfpropietario'];
		
		
			//TODO: RECOGER VARIABLES JS Y GENERAR EL SQL
			$consulta = "
			SELECT
				inmo_inmuebles.id,
				inmo_inmuebles.ref,
				inmo_inmuebles.zona,
				inmo_inmuebles.inmueble,
				inmo_inmuebles.direccion, 
				inmo_inmuebles.precio,
				inmo_inmuebles.fechaalta,
				inmo_inmuebles.fechamod,
				inmo_inmuebles.precioalquiler,
				u.nombre AS nombreUsuario,
				z.nombre AS nombreZona
			FROM inmo_inmuebles
			LEFT JOIN usuarios AS u ON u.id = inmo_inmuebles.usuario
			LEFT JOIN inmo_zonas AS z ON z.id = inmo_inmuebles.zona
			WHERE inmo_inmuebles.idSitioWeb = " . $idSitioWeb;
			
			//fix
			if ($zona==""){	$zona = "Todas";}
			if ($tipo=="" or $tipo=="0"){ $tipo = "Todos";}	
			if ($estado=="" or $estado=="0"){ $estado = "Todos";}	
			if ($lstUsuario==""){ $lstUsuario = "Todos";}
			
			if ($preciodesde > $preciohasta) { $preciohasta = $preciodesde;	}	
			if ($metrosdesde > $metroshasta) { $metrosdesde = $metrosdesde;	}	
			//if ($habitadesde > $habitahasta) { $habitahasta = $habitadesde;	}
			if ($banosdesde  > $banoshasta ) { $banoshasta  = $banosdesde;	}
			
			if ($desde != "") {
				$desde = str_replace("/", "-", $desde);
				$consulta .= " AND inmo_inmuebles.fechaalta >= '".date_format(date_create($desde), 'Y-m-d')."'";
			}
			if ($hasta != "") {
				$hasta = str_replace("/", "-", $hasta);	
				$consulta .= " AND inmo_inmuebles.fechaalta <= '".date_format(date_create($hasta), 'Y-m-d')."'";
			}
			
			if ($desdebaja != "") {
				$desdebaja = str_replace("/", "-", $desdebaja);
				$consulta .= " AND inmo_inmuebles.fechamod >= '".date_format(date_create($desdebaja), 'Y-m-d')."'";
			}
	
			if ($hastabaja != "") {
				$hastabaja = str_replace("/", "-", $hastabaja);	
				$consulta .= " AND inmo_inmuebles.fechamod <= '".date_format(date_create($hastabaja), 'Y-m-d')."'";
			}
			
			if ($zona != "Todas"){
				$consulta .= " AND inmo_inmuebles.zona = '".$zona."'";
			}
			if ($tipo != "Todos"){
				$consulta .= " AND inmo_inmuebles.inmueble = '".$tipo."'";
			}
			if ($estado != "Todos"){
				$consulta .= " AND inmo_inmuebles.estado = '".$estado."'";
			}	
			if ($propietario != ""){
				$consulta .= " AND clientes.nombre LIKE '%".$propietario."%'";
			}
			if ($tlfpropietario != ""){
				$consulta .= " AND (clientes.tlf1 = '".$tlfpropietario."' or clientes.tlf2 = '".$tlfpropietario."')";
			}	
			if ($planta != ""){
				$consulta .= " AND (inmo_inmuebles.planta LIKE '%".$planta."%')";
			}	
			if ($preciodesde != ""){
				$consulta .= " AND inmo_inmuebles.precio >= ".$preciodesde;
			}
			if ($preciohasta != "") {
				$consulta .= " AND inmo_inmuebles.precio <= ".$preciohasta;
			}
			if ($metrosdesde != ""){
				$consulta .= " AND inmo_inmuebles.metroscuadra >= ".$metrosdesde;
			}
			if ($metroshasta != "") {
				$consulta .= " AND inmo_inmuebles.metroscuadra <= ".$metroshasta;
			}
			if ($banosdesde != ""){
				$consulta .= " AND inmo_inmuebles.banos >= ".$banosdesde;
			}
			if ($banoshasta != "") {
				$consulta .= " AND inmo_inmuebles.banos <= ".$banoshasta;
			}
			if ($piscina == 'true'){
				$consulta .= " AND inmo_inmuebles.piscina = 'true'";
			}
			if ($garaje == 'true'){
				$consulta .= " AND inmo_inmuebles.garaje = 'true'";
			}
			if ($ascensor == 'true'){
				$consulta .= " AND inmo_inmuebles.ascensor = 'true'";
			}
			if ($lstUsuario != "Todos"){
				$consulta .= " AND inmo_inmuebles.usuario = '".$lstUsuario."'";
			}	
			if ($Alquiler == 'true'){
				$consulta .= " AND inmo_inmuebles.alquiler = 'true'";
			}
			if ($Venta == 'true'){
				$consulta .= " AND inmo_inmuebles.venta = 'true'";
			}
			if ($Traspaso == 'true'){
				$consulta .= " AND inmo_inmuebles.traspaso = 'true'";
			}
			if ($Promocion == 'true'){
				$consulta .= " AND inmo_inmuebles.promocion = 'true'";
			}
			if ($AlquilerCompra == 'true'){
				$consulta .= " AND inmo_inmuebles.AlquilarCompra = 'true'";
			}
			
			
			
			if( !empty($requestData['search']['value']) ) {
					
				$consulta .= " AND (
					inmo_inmuebles.ref LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.inmueble LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.fechaalta LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.fechamod LIKE '%".$requestData['search']['value']."%'  
					OR z.nombre LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.direccion LIKE '%".$requestData['search']['value']."%' 
					OR inmo_inmuebles.precio LIKE '%".$requestData['search']['value']."%' 
					OR u.nombre LIKE '%".$requestData['search']['value']."%'  
				)";
			}
			
			
			$columns = array(
				0 => 'inmo_inmuebles.ref',
				1 => 'inmo_inmuebles.inmueble',
				2 => 'inmo_inmuebles.fechaalta',
				3 => 'inmo_inmuebles.fechamod',
				4 => 'z.nombre',
				5 => 'inmo_inmuebles.direccion',
				6 => 'inmo_inmuebles.precio',
				7 => 'u.nombre',
			);
			
			if (isset($requestData['order'])) {
				$consulta .= " ORDER BY " . $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir'];	
			}
			
			//Obtenemos el total
			$registroTotal = mysqli_query($conexion, $consulta);
			$recordsTotal = mysqli_num_rows($registroTotal);
			$recordsTotalFiltered = $recordsTotal;
			
			//Limitamos los registros por pagina
			if (isset($requestData['start'])) {
				$consulta .= " LIMIT ".$requestData['start']." ,".$requestData['length'];	
			}
			$registro = mysqli_query($conexion, $consulta);
			
			$draw = rand(100,1000);
			if (isset($requestData['draw'])) {
				$draw = intval( $requestData['draw'] );
			}
			
			$resultado = array(
				"draw" =>  $draw,
				"recordsTotal" => $recordsTotal,
				"recordsFiltered" => $recordsTotalFiltered,
				"data" => array()
			);
			
			while($row = mysqli_fetch_array($registro))
			{
				$edita = '<a href="#" onclick="modificaInmueble('.$row['ref'].')" class="iconModificar"><i class="fa fa-edit fa-2x"></i></a>';
				$borra = '<a class="delete" href="#" onclick="eliminaInmueble('.$row['ref'].')"><i class="fa fa-trash-o fa-2x"></i></a>';
				
				$fechaAlta = formatDate($row['fechaalta']);
				$fechaMod = formatDate($row['fechamod']);		
				
				$data = array(
					'id' => $row['ref'],
					'inmueble' => $row['inmueble'],
					'fechaAlta' => $fechaAlta,
					'fechaMod' => $fechaMod,
					'zona' => utf8_encode($row['nombreZona']),
					'direccion' => utf8_encode($row['direccion']),
					'precio' => $row['precio'].' - '.$row['precioalquiler'],
					'usuario' => utf8_encode($row['nombreUsuario']),
					'acciones' => $edita.$borra
				);
				
				$resultado['data'][] = $data;		
			}
			
			echo json_encode($resultado);
		break;
		case 'elimina':
			$idInmueble = mysqli_real_escape_string($conexion, $_POST['id']);

			$consulta = "
			UPDATE inmo_inmuebles SET
			estado = 'Borrado'
			WHERE id = ".$idInmueble;
				
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			}else{
				echo "KO";
			};	
			break;
			
		case 'guarda':
			
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$mid = mysqli_real_escape_string($conexion, $_POST['txtId']);	
			$mref = mysqli_real_escape_string($conexion, $_POST['txtRef']);
			$mfechaalta = $_POST['txtFechaalta'];
			$mfechaalta = date("Y-m-d", strtotime($mfechaalta));
			$mfechamod = date("Y-m-d"); //a�adir
			
			$mdireccion = mysqli_real_escape_string($conexion, $_POST['txtDireccion']); //a�adir
			$mzona = mysqli_real_escape_string($conexion, $_POST['zona']);
			
			$mportal = mysqli_real_escape_string($conexion, $_POST['txtPortal']); //a�adir
			$mplanta = mysqli_real_escape_string($conexion, $_POST['txtPlanta']); //a�adir
			$mletra = mysqli_real_escape_string($conexion, $_POST['txtLetra']); //a�adir
			
			$mpoblacion = mysqli_real_escape_string($conexion, $_POST['txtPoblacion']);
			$mprovincia = mysqli_real_escape_string($conexion, $_POST['txtProvincia']);
			
			$musuario = mysqli_real_escape_string($conexion, $_POST['txtUsuarioId']); //a�adir
			$mpropietario = mysqli_real_escape_string($conexion, $_POST['txtPropId']);
			
			$mcaracteristicas = mysqli_real_escape_string($conexion, $_POST['txtCaracteristicas']); //a�adir
			
			$mescaparate = mysqli_real_escape_string($conexion, $_POST['chEscaparate']);
			$mescaparateWeb = mysqli_real_escape_string($conexion, $_POST['chEscaparateWeb']);

			$malquiler = mysqli_real_escape_string($conexion, $_POST['chAlquiler']); //a�adir
			$mventa = mysqli_real_escape_string($conexion, $_POST['chVenta']); //a�adir
			$mpromocion = mysqli_real_escape_string($conexion, $_POST['chPromocion']); //a�adir
			$mtraspaso = mysqli_real_escape_string($conexion, $_POST['chTraspaso']); //a�adir
							
			$mAlquilerCompra = mysqli_real_escape_string($conexion, $_POST['chAlquilerCompra']);					
			$mllaves = mysqli_real_escape_string($conexion, $_POST['chLlaves']); //a�adir
			$mcartel = mysqli_real_escape_string($conexion, $_POST['chCartel']); //a�adir
						
			$mpreciopropie = mysqli_real_escape_string($conexion, $_POST['txtPreciopropie']); //a�adir
			$mpcomision = mysqli_real_escape_string($conexion, $_POST['txtPcomision']); //a�adir
			$mcomision = mysqli_real_escape_string($conexion, $_POST['txtComision']); //a�adir
			$mhonorarios = mysqli_real_escape_string($conexion, $_POST['txtHonorarios']); //a�adir
			
			$mprecio = mysqli_real_escape_string($conexion, $_POST['txtPrecio']);
			$mprecioalquiler = mysqli_real_escape_string($conexion, $_POST['txtPrecioalquiler']); //a�adir
			$mcomunidad = mysqli_real_escape_string($conexion, $_POST['txtComunidad']); //a�adir
			$mpreciogar = mysqli_real_escape_string($conexion, $_POST['txtPreciogar']); //a�adir
			
			$mdescripcion = mysqli_real_escape_string($conexion, $_POST['txtDescripcion']); //a�adir
			
			$mestado = mysqli_real_escape_string($conexion, $_POST['cbEstado']); //a�adir
			$minmueble = mysqli_real_escape_string($conexion, $_POST['lstinmueble']);

			$mmetros = mysqli_real_escape_string($conexion, $_POST['txtMetros']);
			$mparcela = mysqli_real_escape_string($conexion, $_POST['txtParcela']); 
			$mhabitaciones = mysqli_real_escape_string($conexion, $_POST['txtHabitaciones']);
			$mbanos = mysqli_real_escape_string($conexion, $_POST['txtBanos']);
			$maseos = mysqli_real_escape_string($conexion, $_POST['txtAseos']); 
			$msalon = mysqli_real_escape_string($conexion, $_POST['txtSalon']); 
			$mcocina = mysqli_real_escape_string($conexion, $_POST['txtCocina']); 
			$mterraza = mysqli_real_escape_string($conexion, $_POST['txtTerraza']); 
			$mplantas = mysqli_real_escape_string($conexion, $_POST['txtPlantas']); 
			$mtelefono = mysqli_real_escape_string($conexion, $_POST['chTelefono']); 
			$mtendedero = mysqli_real_escape_string($conexion, $_POST['chTendedero']); 
			$marmaempotrado = mysqli_real_escape_string($conexion, $_POST['chArmaempotrado']); 
			$mascensor = mysqli_real_escape_string($conexion, $_POST['chAscensor']);
			$mchimenea = mysqli_real_escape_string($conexion, $_POST['txtChimenea']); 
			$mgaraje = mysqli_real_escape_string($conexion, $_POST['chGaraje']);
			$mtrastero = mysqli_real_escape_string($conexion, $_POST['chTrastero']); 	
			$mpiscina = mysqli_real_escape_string($conexion, $_POST['chPiscina']);
			$mtenis = mysqli_real_escape_string($conexion, $_POST['chTenis']); 
			$mjardines = mysqli_real_escape_string($conexion, $_POST['chJardines']); 		
			$mbuhardilla = mysqli_real_escape_string($conexion, $_POST['chBuhardilla']); 
			$msolados = mysqli_real_escape_string($conexion, $_POST['txtSolados']); 
			$mcarpinext = mysqli_real_escape_string($conexion, $_POST['txtCarpinext']); 
			$mcarpinint = mysqli_real_escape_string($conexion, $_POST['txtCarpinint']); 
			$mcalefaccion = mysqli_real_escape_string($conexion, $_POST['txtCalefaccion']); 
			$mantiguedad = mysqli_real_escape_string($conexion, $_POST['txtAntiguedad']); 
			$msuperutil = mysqli_real_escape_string($conexion, $_POST['txtSuperutil']); 
			$mimagen = mysqli_real_escape_string($conexion, $_POST['txtImagen']); 
			$mexterior = mysqli_real_escape_string($conexion, $_POST['chExterior']); 
			$mamueblado = mysqli_real_escape_string($conexion, $_POST['chAmueblado']); 
			$mcocinaamu = mysqli_real_escape_string($conexion, $_POST['chCocinaamu']); 
			$mreformado = mysqli_real_escape_string($conexion, $_POST['chReformado']); 
			$mportero = mysqli_real_escape_string($conexion, $_POST['chPortero']); 
			
			
			$marmaempo = mysqli_real_escape_string($conexion, $_POST['txtArmaempo']); 
			$mplantasedif = mysqli_real_escape_string($conexion, $_POST['txtPlantasedif']); 
			$mmetroster = mysqli_real_escape_string($conexion, $_POST['txtMetroster']); 
			$mpatio = mysqli_real_escape_string($conexion, $_POST['chPatio']); 
			$mgarobli = mysqli_real_escape_string($conexion, $_POST['chGarobli']); 
			$mvisitas = mysqli_real_escape_string($conexion, $_POST['txtVisitas']); 
			$mmetroscuadra = mysqli_real_escape_string($conexion, $_POST['txtMetroscuadra']); 
			$maltura = mysqli_real_escape_string($conexion, $_POST['txtAltura']); 
			$mestreno = mysqli_real_escape_string($conexion, $_POST['chEstreno']); 
			$malmacena = mysqli_real_escape_string($conexion, $_POST['chAlmacena']); 
			$mvestibulo = mysqli_real_escape_string($conexion, $_POST['chVestibulo']); 
			$mgasciudad = mysqli_real_escape_string($conexion, $_POST['chGasciudad']); 	
			
			$mTerreno = mysqli_real_escape_string($conexion, $_POST['cbTipoTerreno']);
			$mAcceso = mysqli_real_escape_string($conexion, $_POST['cbAcceso']);
			$mRiego = mysqli_real_escape_string($conexion, $_POST['chRiego']);
			$mLuz = mysqli_real_escape_string($conexion, $_POST['chLuz']);
			$mVallado = mysqli_real_escape_string($conexion, $_POST['chVallado']);		
			
			$msupTerreno = mysqli_real_escape_string($conexion, $_POST['cbTerreno']);
			$mServidumbre = mysqli_real_escape_string($conexion, $_POST['chServidumbre']);
			$mTipoCasa = mysqli_real_escape_string($conexion, $_POST['cbTipCasa']);
			$mVPO = mysqli_real_escape_string($conexion, $_POST['chVPO']);
			
			if ($mchfechabaja == "on") {
				$mfechabaja = date("Y-m-d") ; 
			} else{
				$mfechabaja = "NULL"; 
			}
			
			$consulta = "
			UPDATE inmo_inmuebles SET 
			ref='".$mref."', 
			fechaalta='".$mfechaalta."', 
			fechamod='".$mfechamod."', 
			caracteristicas='".$mcaracteristicas."', 
			descripcion='".$mdescripcion."', 
			precio='".$mprecio."', 
			precioalquiler='".$mprecioalquiler."', 
			alquiler='".$malquiler."', 
			venta='".$mventa."', 
			promocion='".$mpromocion."', 
			traspaso='".$mtraspaso."', 
			metros='".$mmetros."', 
			parcela='".$mparcela."', 
			zona='".$mzona."', 
			direccion='".$mdireccion."', 
			portal='".$mportal."', 
			planta='".$mplanta."', 
			letra='".$mletra."', 
			poblacion='".$mpoblacion."', 
			provincia='".$mprovincia."', 
			habitaciones='".$mhabitaciones."', 
			banos='".$mbanos."', 
			aseos='".$maseos."', 
			salon='".$msalon."', 
			cocina='".$mcocina."', 
			terraza='".$mterraza."', 
			plantas='".$mplantas."', 
			telefono='".$mtelefono."', 
			tendedero='".$mtendedero."', 
			armaempotrado='".$marmaempotrado."', 
			ascensor='".$mascensor."', 
			chimenea='".$mchimenea."', 
			garaje='".$mgaraje."', 
			trastero='".$mtrastero."', 
			piscina='".$mpiscina."', 
			tenis='".$mtenis."', 
			jardines='".$mjardines."', 
			buhardilla='".$mbuhardilla."', 
			solados='".$msolados."', 
			carpinext='".$mcarpinext."', 
			carpinint='".$mcarpinint."', 
			calefaccion='".$mcalefaccion."', 
			comunidad='".$mcomunidad."', 
			antiguedad='".$mantiguedad."', 
			llaves='".$mllaves."', 
			cartel='".$mcartel."', 
			propietario='".$mpropietario."', 
			superutil='".$msuperutil."', 
			imagen='".$mimagen."', 
			exterior='".$mexterior."', 
			amueblado='".$mamueblado."', 
			cocinaamu='".$mcocinaamu."', 
			reformado='".$mreformado."', 
			portero='".$mportero."', 
			usuario='".$musuario."', 
			preciopropie='".$mpreciopropie."', 
			pcomision='".$mpcomision."', 
			comision='".$mcomision."', 
			honorarios='".$mhonorarios."', 
			borrado='".$mborrado."', 
			armaempo='".$marmaempo."', 
			plantasedif='".$mplantasedif."', 
			metroster='".$mmetroster."', 
			patio='".$mpatio."', 
			garobli='".$mgarobli."', 
			preciogar='".$mpreciogar."', 
			visitas='".$mvisitas."', 
			metroscuadra='".$mmetroscuadra."', 
			altura='".$maltura."', 
			estreno='".$mestreno."', 
			almacena='".$malmacena."', 
			vestibulo='".$mvestibulo."', 
			gasciudad='".$mgasciudad."', 
			inmueble='".$minmueble."', 
			escaparate='".$mescaparate."',
			escaparateWeb='".$mescaparateWeb."',
			AlquilarCompra='".$mAlquilerCompra."',
			VPO='".$mVPO."',
			estado='".$mestado."',
			tipoCasa='".$mTipoCasa."'
			WHERE id='".$mid."'";
			
			$retorno = mysqli_query($conexion, $consulta);
			
			if ($retorno){
				echo "OK";
			} else{
				echo "KO";
			}
			
		break;
		
		case 'inserta':
			//recojo todos los datos del anterior form y lo paso para guardarlo en la bdatos
			$mid = mysqli_real_escape_string($conexion, $_POST['txtId']);	
			$mref = mysqli_real_escape_string($conexion, $_POST['txtRef']);
			$mfechaalta = $_POST['txtFechaalta']; //a�adir
			//$mfechaalta = substr($mfechaalta, 6, 4)."-".substr($mfechaalta, 3, 2)."-".substr($mfechaalta, 0, 2)." 00:00:00";
			$mfechamod = date("Y/m/d h:m:s"); //a�adir
			
			$mdireccion = mysqli_real_escape_string($conexion, $_POST['txtDireccion']); //a�adir
			$mzona = mysqli_real_escape_string($conexion, $_POST['zona']);
			
			$mportal = mysqli_real_escape_string($conexion, $_POST['txtPortal']); //a�adir
			$mplanta = mysqli_real_escape_string($conexion, $_POST['txtPlanta']); //a�adir
			$mletra = mysqli_real_escape_string($conexion, $_POST['txtLetra']); //a�adir
			
			$mpoblacion = mysqli_real_escape_string($conexion, $_POST['txtPoblacion']);
			$mprovincia = mysqli_real_escape_string($conexion, $_POST['txtProvincia']);
			
			$musuario = mysqli_real_escape_string($conexion, $_POST['txtUsuarioId']); //a�adir
			$mpropietario = mysqli_real_escape_string($conexion, $_POST['txtPropId']);
			
			$mcaracteristicas = mysqli_real_escape_string($conexion, $_POST['txtCaracteristicas']); //a�adir
			
			$mescaparate = mysqli_real_escape_string($conexion, $_POST['chEscaparate']);
			$mescaparateWeb = mysqli_real_escape_string($conexion, $_POST['chEscaparateWeb']);

			$malquiler = mysqli_real_escape_string($conexion, $_POST['chAlquiler']); //a�adir
			$mventa = mysqli_real_escape_string($conexion, $_POST['chVenta']); //a�adir
			$mpromocion = mysqli_real_escape_string($conexion, $_POST['chPromocion']); //a�adir
			$mtraspaso = mysqli_real_escape_string($conexion, $_POST['chTraspaso']); //a�adir
							
			$mAlquilerCompra = mysqli_real_escape_string($conexion, $_POST['chAlquilerCompra']);					
			$mllaves = mysqli_real_escape_string($conexion, $_POST['chLlaves']); //a�adir
			$mcartel = mysqli_real_escape_string($conexion, $_POST['chCartel']); //a�adir
						
			$mpreciopropie = mysqli_real_escape_string($conexion, $_POST['txtPreciopropie']); //a�adir
			$mpcomision = mysqli_real_escape_string($conexion, $_POST['txtPcomision']); //a�adir
			$mcomision = mysqli_real_escape_string($conexion, $_POST['txtComision']); //a�adir
			$mhonorarios = mysqli_real_escape_string($conexion, $_POST['txtHonorarios']); //a�adir
			
			$mprecio = mysqli_real_escape_string($conexion, $_POST['txtPrecio']);
			$mprecioalquiler = mysqli_real_escape_string($conexion, $_POST['txtPrecioalquiler']); //a�adir
			$mcomunidad = mysqli_real_escape_string($conexion, $_POST['txtComunidad']); //a�adir
			$mpreciogar = mysqli_real_escape_string($conexion, $_POST['txtPreciogar']); //a�adir
			
			$mdescripcion = mysqli_real_escape_string($conexion, $_POST['txtDescripcion']); //a�adir
			
			$mestado = mysqli_real_escape_string($conexion, $_POST['cbEstado']); //a�adir
			$minmueble = mysqli_real_escape_string($conexion, $_POST['lstinmueble']);

			$mmetros = mysqli_real_escape_string($conexion, $_POST['txtMetros']);
			$mparcela = mysqli_real_escape_string($conexion, $_POST['txtParcela']); 
			$mhabitaciones = mysqli_real_escape_string($conexion, $_POST['txtHabitaciones']);
			$mbanos = mysqli_real_escape_string($conexion, $_POST['txtBanos']);
			$maseos = mysqli_real_escape_string($conexion, $_POST['txtAseos']); 
			$msalon = mysqli_real_escape_string($conexion, $_POST['txtSalon']); 
			$mcocina = mysqli_real_escape_string($conexion, $_POST['txtCocina']); 
			$mterraza = mysqli_real_escape_string($conexion, $_POST['txtTerraza']); 
			$mplantas = mysqli_real_escape_string($conexion, $_POST['txtPlantas']); 
			$mtelefono = mysqli_real_escape_string($conexion, $_POST['chTelefono']); 
			$mtendedero = mysqli_real_escape_string($conexion, $_POST['chTendedero']); 
			$marmaempotrado = mysqli_real_escape_string($conexion, $_POST['chArmaempotrado']); 
			$mascensor = mysqli_real_escape_string($conexion, $_POST['chAscensor']);
			$mchimenea = mysqli_real_escape_string($conexion, $_POST['txtChimenea']); 
			$mgaraje = mysqli_real_escape_string($conexion, $_POST['chGaraje']);
			$mtrastero = mysqli_real_escape_string($conexion, $_POST['chTrastero']); 	
			$mpiscina = mysqli_real_escape_string($conexion, $_POST['chPiscina']);
			$mtenis = mysqli_real_escape_string($conexion, $_POST['chTenis']); 
			$mjardines = mysqli_real_escape_string($conexion, $_POST['chJardines']); 		
			$mbuhardilla = mysqli_real_escape_string($conexion, $_POST['chBuhardilla']); 
			$msolados = mysqli_real_escape_string($conexion, $_POST['txtSolados']); 
			$mcarpinext = mysqli_real_escape_string($conexion, $_POST['txtCarpinext']); 
			$mcarpinint = mysqli_real_escape_string($conexion, $_POST['txtCarpinint']); 
			$mcalefaccion = mysqli_real_escape_string($conexion, $_POST['txtCalefaccion']); 
			$mantiguedad = mysqli_real_escape_string($conexion, $_POST['txtAntiguedad']); 
			$msuperutil = mysqli_real_escape_string($conexion, $_POST['txtSuperutil']); 
			$mimagen = mysqli_real_escape_string($conexion, $_POST['txtImagen']); 
			$mexterior = mysqli_real_escape_string($conexion, $_POST['chExterior']); 
			$mamueblado = mysqli_real_escape_string($conexion, $_POST['chAmueblado']); 
			$mcocinaamu = mysqli_real_escape_string($conexion, $_POST['chCocinaamu']); 
			$mreformado = mysqli_real_escape_string($conexion, $_POST['chReformado']); 
			$mportero = mysqli_real_escape_string($conexion, $_POST['chPortero']); 
			
			
			$marmaempo = mysqli_real_escape_string($conexion, $_POST['txtArmaempo']); 
			$mplantasedif = mysqli_real_escape_string($conexion, $_POST['txtPlantasedif']); 
			$mmetroster = mysqli_real_escape_string($conexion, $_POST['txtMetroster']); 
			$mpatio = mysqli_real_escape_string($conexion, $_POST['chPatio']); 
			$mgarobli = mysqli_real_escape_string($conexion, $_POST['chGarobli']); 
			$mvisitas = mysqli_real_escape_string($conexion, $_POST['txtVisitas']); 
			$mmetroscuadra = mysqli_real_escape_string($conexion, $_POST['txtMetroscuadra']); 
			$maltura = mysqli_real_escape_string($conexion, $_POST['txtAltura']); 
			$mestreno = mysqli_real_escape_string($conexion, $_POST['chEstreno']); 
			$malmacena = mysqli_real_escape_string($conexion, $_POST['chAlmacena']); 
			$mvestibulo = mysqli_real_escape_string($conexion, $_POST['chVestibulo']); 
			$mgasciudad = mysqli_real_escape_string($conexion, $_POST['chGasciudad']); 	
			
			$mTerreno = mysqli_real_escape_string($conexion, $_POST['cbTipoTerreno']);
			$mAcceso = mysqli_real_escape_string($conexion, $_POST['cbAcceso']);
			$mRiego = mysqli_real_escape_string($conexion, $_POST['chRiego']);
			$mLuz = mysqli_real_escape_string($conexion, $_POST['chLuz']);
			$mVallado = mysqli_real_escape_string($conexion, $_POST['chVallado']);		
			
			$msupTerreno = mysqli_real_escape_string($conexion, $_POST['cbTerreno']);
			$mServidumbre = mysqli_real_escape_string($conexion, $_POST['chServidumbre']);
			$mTipoCasa = mysqli_real_escape_string($conexion, $_POST['cbTipCasa']);
			$mVPO = mysqli_real_escape_string($conexion, $_POST['chVPO']);
			
			if ($mchfechabaja == "on") {
				$mfechabaja = date("Y/m/d") ; 
			} else{
				$mfechabaja = "NULL"; 
			}
			
			$consulta = "
			INSERT INTO inmo_inmuebles SET 
			idSitioWeb = " . $idSitioWeb . ",
			ref='".$mref."', 
			fechaalta='".$mfechaalta."', 
			fechamod='".$mfechamod."', 
			caracteristicas='".$mcaracteristicas."', 
			descripcion='".$mdescripcion."', 
			precio='".$mprecio."', 
			precioalquiler='".$mprecioalquiler."', 
			alquiler='".$malquiler."', 
			venta='".$mventa."', 
			promocion='".$mpromocion."', 
			traspaso='".$mtraspaso."', 
			metros='".$mmetros."', 
			parcela='".$mparcela."', 
			zona='".$mzona."', 
			direccion='".$mdireccion."', 
			portal='".$mportal."', 
			planta='".$mplanta."', 
			letra='".$mletra."', 
			poblacion='".$mpoblacion."', 
			provincia='".$mprovincia."', 
			habitaciones='".$mhabitaciones."', 
			banos='".$mbanos."', 
			aseos='".$maseos."', 
			salon='".$msalon."', 
			cocina='".$mcocina."', 
			terraza='".$mterraza."', 
			plantas='".$mplantas."', 
			telefono='".$mtelefono."', 
			tendedero='".$mtendedero."', 
			armaempotrado='".$marmaempotrado."', 
			ascensor='".$mascensor."', 
			chimenea='".$mchimenea."', 
			garaje='".$mgaraje."', 
			trastero='".$mtrastero."', 
			piscina='".$mpiscina."', 
			tenis='".$mtenis."', 
			jardines='".$mjardines."', 
			buhardilla='".$mbuhardilla."', 
			solados='".$msolados."', 
			carpinext='".$mcarpinext."', 
			carpinint='".$mcarpinint."', 
			calefaccion='".$mcalefaccion."', 
			comunidad='".$mcomunidad."', 
			antiguedad='".$mantiguedad."', 
			llaves='".$mllaves."', 
			cartel='".$mcartel."', 
			propietario='".$mpropietario."', 
			superutil='".$msuperutil."', 
			imagen='".$mimagen."', 
			exterior='".$mexterior."', 
			amueblado='".$mamueblado."', 
			cocinaamu='".$mcocinaamu."', 
			reformado='".$mreformado."', 
			portero='".$mportero."', 
			usuario='".$musuario."', 
			preciopropie='".$mpreciopropie."', 
			pcomision='".$mpcomision."', 
			comision='".$mcomision."', 
			honorarios='".$mhonorarios."', 
			borrado='".$mborrado."', 
			armaempo='".$marmaempo."', 
			plantasedif='".$mplantasedif."', 
			metroster='".$mmetroster."', 
			patio='".$mpatio."', 
			garobli='".$mgarobli."', 
			preciogar='".$mpreciogar."', 
			visitas='".$mvisitas."', 
			metroscuadra='".$mmetroscuadra."', 
			altura='".$maltura."', 
			estreno='".$mestreno."', 
			almacena='".$malmacena."', 
			vestibulo='".$mvestibulo."', 
			gasciudad='".$mgasciudad."', 
			inmueble='".$minmueble."', 
			escaparate='".$mescaparate."',
			escaparateWeb='".$mescaparate."',
			AlquilarCompra='".$mAlquilerCompra."',
			VPO='".$mVPO."',
			estado='".$mestado."',
			tipoCasa='".$mTipoCasa."'
			;";

			$retorno = mysqli_query($conexion, $consulta);
			
			//TODO Insertar Imagenes -> clientes_dk-inserta.php
			
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
		case 'obtenerDocumentoInmueble':
			$idInmueble = mysqli_real_escape_string($conexion, $_POST['idInmueble']);
			
			$ruta_base_privada = getInmoPrivada($conexion,$idSitioWeb);
			echo '../'.$ruta_base_privada.'/'.$idSitioWeb.'/'.$idInmueble.'.jpg';
		break;
		case 'obtenerGaleriaImagenes':
			$idInmueble = mysqli_real_escape_string($conexion, $_POST['idInmueble']);
			$ruta_base_publica = getInmoPublica($conexion,$idSitioWeb);
			
			
			$consulta = "SELECT inmo_rutaPublica FROM sitiosweb WHERE id = ".$_SESSION['sitioWeb'];
			$registro = mysqli_query($conexion, $consulta);
			$row = mysqli_fetch_assoc($registro);
			
			$folders = explode ('/', $_SERVER['PHP_SELF']);
			
			$totalDirs = count($folders)-3;
			$pre_ruta = '';
			for ($i=1;$i<=$totalDirs;$i++) {
				$pre_ruta .= '..'.DIRECTORY_SEPARATOR;
			}

			$pre_ruta_src = '';
			for ($i=1;$i<=($totalDirs-2);$i++) {
				$pre_ruta_src .= '..'.DIRECTORY_SEPARATOR;
			}
			
			$ruta_final_publica = $pre_ruta . $row['inmo_rutaPublica'] . DIRECTORY_SEPARATOR . $idSitioWeb . DIRECTORY_SEPARATOR;
			$ruta_final_publica_src = $pre_ruta_src . $row['inmo_rutaPublica'] . DIRECTORY_SEPARATOR . $idSitioWeb . DIRECTORY_SEPARATOR;
			
			$imagenes = '';
			for ($i=1;$i<=15;$i++) {
				$imagen = $ruta_final_publica.$idInmueble.'.'.$i.'.jpg';
				$imagen_src = $ruta_final_publica_src.$idInmueble.'.'.$i.'.jpg';
				if (file_exists($imagen)) {
       				$imagenes .= '<img height="50px" src="'.$imagen_src.'" onClick="javascript:mostrarImagenGrande(this);" class="imagenGaleria" />';
				}else{		//compruebo que sea imagen Web
					$imagen = $ruta_final_publica.$idInmueble.'w.'.$i.'.jpg';
					$imagen_src = $ruta_final_publica_src.$idInmueble.'w.'.$i.'.jpg';

					if (file_exists($imagen)) {
						$imagenes .= '<img height="50px" src="'.$imagen_src.'" class="imagenGaleria" onClick="javascript:mostrarImagenGrande(this);" />';
					}					
				}
			}
			/*
			for ($i=1;$i<=15;$i++) {
				$imagen = $ruta_final_publica.$idInmueble.'.'.$i.'.jpg';
				$imagen_src = $ruta_final_publica_src.$idInmueble.'.'.$i.'.jpg';
				if (file_exists($imagen)) {
       				$imagenes .= '
       				<a href="'.$imagen_src.'" title="" target="_blank" class="imagenGaleria">
       					<img height="50px" src="'.$imagen_src.'" />
   					</a>';
				}else{		//compruebo que sea imagen Web
					$imagen = $ruta_final_publica.$idInmueble.'w.'.$i.'.jpg';
					$imagen_src = $ruta_final_publica_src.$idInmueble.'w.'.$i.'.jpg';

					if (file_exists($imagen)) {
						$imagenes .= '
						<a href="'.$imagen_src.'" title="" target="_blank" class="imagenGaleria">
							<img height="50px" src="'.$imagen_src.'" />
						</a>';
					}					
				}
			}
			*/
			echo $imagenes;
		break;
		
	}
?>
