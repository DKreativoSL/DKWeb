<?php
	session_start(); //inicializo sesión
	
	include("./../../conexion.php");
	include("./../../funciones.php");
	$accion = $_POST['accion'];
	
	$idSitioWeb = $_SESSION['sitioWeb'];
	$idUsuario = $_SESSION['idUsuario'];
	$grupo = $_SESSION['grupo'];
	
	switch ($accion) 
	{
		case 'listaArticulos':
			$estado = mysqli_real_escape_string($conexion, $_POST['estado']);
			
			$consulta = "
			SELECT DISTINCT
			articulos.id, articulos.idSeccion, articulos.titulo, articulos.estado, articulos.fechaPublicacion, articulos.idUsuario
			FROM articulos, secciones, sitiosweb
			WHERE secciones.id = articulos.idseccion
			AND secciones.idsitioweb = ".$idSitioWeb."
			AND articulos.estado = " . $estado;
			
			if ($grupo == "administrador") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND idUsuario IN ('.$where_in.')';
			}
			if ($grupo == "colaborador") {
				$consulta .= ' AND articulos.idUsuario = '.$idUsuario;
				$consulta .= ' AND articulos.estado = "nopublicado"';
			}
			if ($grupo == "editor") {
				$where_in = getColaboradores($conexion,$idSitioWeb);
				$consulta .= ' AND articulos.idUsuario IN ('.$where_in.')';
			}
			
			
			
			$registro = mysqli_query($conexion, $consulta);
			$tabla = "";
    
			while($row = mysqli_fetch_array($registro))
			{
				//$duplicar = '<a href=\"#\" onclick=\"duplicar('.$row['id'].')\" class=\"iconDuplicar\"><i class=\"fa fa-copy fa-2x\"></i></a>';
				$edita = '<a href=\"#\" onclick=\"modifica('.$row['id'].')\" class=\"iconModificar\"><i class=\"fa fa-edit fa-2x\"></i></a>';
				//$borra = '<a class=\"delete\" href=\"#\" onclick=\"elimina('.$row['id'].')\"><i class=\"fa fa-trash-o fa-2x\"></i></a>';
				
				if (strlen($row['fechaPublicacion'] > 0)) {
					$e_date = explode(" ",$row['fechaPublicacion']);
					$date = date_create($e_date[0]);
					$fecha = date_format($date,"d-m-Y");
					$fecha = $e_date[1] . " " . $fecha;	
				} else {
					$fecha = '00-00-0000';
				}
				
				$tabla.='{"id":"'.$row['id'].'",';
				$tabla.='"usuario":"'.getUsuario($conexion,$row['idUsuario']).'",';
				$tabla.='"titulo":"'.$row['titulo'].'",';
				$tabla.='"estado":"'.getEstado($row['estado']).'",';
				$tabla.='"fecha":"'.$fecha.'",';
				$tabla.='"acciones":"'.$edita.'"},';		

			}
			$tabla = substr($tabla,0, strlen($tabla) - 1);
			echo '{"data":['.$tabla.']}';
		break;		
	}
?>
