<?php
include('../dk-panel/conexion.php');

$fechaActual = date('Y-m-d h:i:s');

$consulta = '
UPDATE articulos
SET estado = 1
WHERE fechaPublicacion < "' . $fechaActual.'"
AND estado = 2';
$registro = mysqli_query($conexion, $consulta);
?>