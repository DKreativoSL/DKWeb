<?php
session_start();
?>

<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="camposFormulario">
	<form id="formulario" name="formulario">
		<table align="center" border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td width="36%">Consulta<br />
					<select id="selectConsulta">
						<option value="null">Seleccionar consulta</option>
						<option value="publicar_todo">Publicar todos los articulos</option>
						<option value="publicar_borrador">Publicar todos los articulos en borrador</option>
						<option value="eliminar_borrador">Eliminar todos los articulos en borrador</option>
					</select>
				</td>
			</tr>
			<tr>
				<td width="36%">SQL<br />
					<textarea rows="30" cols="60" id="sql" class="form-control"></textarea>
				</td>
			</tr>
          	<tr>
				<td align="right" colspan="3">
					<br>
					<input id="botonLanzar" type="button" value="Lanzar UPDATE" class="btn green" />
        		</td>
          	</tr>
		</table>
	</form>
</div>

<script>
	var idSitioWeb = <?php echo $_SESSION['sitioWeb']; ?>;
</script>
<script src="./modulos/consultasMasivas/dk-logica.js"></script>
