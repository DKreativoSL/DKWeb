<input id="migracionExportar" type="button" value="Exportar Información" class="btn blue">
<input id="migracionImportar" type="button" value="Importar Información" class="btn green">

<div id="inputImport" style="margin-top:50px;">
	<form id="importData" enctype="multipart/form-data" action="modulos/migracion/dk-logica.php" method="post">
		<input type="hidden" name="accion" value="importarWebsite">
		<table width="80%" border="0" cellspacing="2" cellpadding="2">
			<tbody>
				<tr>
					<td width="105">Archivo JSON</td>
					<td colspan="2">
                  		<input id="fileToImport" name="fileToImport" type="file" size="45" class="form-control">
              		</td>
            	</tr>
            	<tr><td><br></td></tr>
				<tr>
            		<td colspan="3">
                		<div align="right">
                    		<input type="submit" id="submitImport" value="Empezar importación" class="btn green">
                		</div>
            		</td>
            	</tr>
          	</tbody>
		</table>
	</form>
	
</div>


<script src="./modulos/migracion/dk-logica.js"></script>