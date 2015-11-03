<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="listaArticulos">
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">id</th>
                <th width="40%">Articulo</th>
                <th width="40%">Comentario</th>
                <th width="10%" align="right"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
</div>

<div id="camposFormulario" style="display:none">
	<form id="formulario" name="formulario">
          <input name="tipo" type="hidden" id="tipo" value="dkgest" />	                    
			<input name="id" type="hidden" id="id" value="" />
			<input name="id" type="hidden" id="idPadre" value="" />
			<table align="center" border="0" cellspacing="2" cellpadding="2">
				<tr>
					<td>
						Usuario<br />
              			<input id="usuario" readonly="readonly" type="text" value="" size="80" maxlength="100" class="form-control" />
          			</td>
      			</tr>
				<tr>
					<td>
						Fecha de creación<br />
              			<input type="text" id="fechaCreacion" value="" size="15" maxlength="10" class="form-control" />
          			</td>
          		</tr>
				<tr>
					<td>
						Fecha de publicacion<br />
              			<input type="text" id="fechaPublicacion" value="" size="15" maxlength="10" class="form-control" />
          			</td>
      			</tr>
          		<tr>
            		<td colspan="3">Comentario<br />
              			<textarea id="comentario"  style="height:400px;" class="form-control" ></textarea>
          			</td>
      			</tr>
			<tr>
				<td>¿Estado?<br />
					<select id="estado" class="form-control" >
						<option value="0">Borrador</option>
						<option value="1">Publicado</option>
						<option value="4">Eliminado</option>
					</select>
				</td>
          </tr>
          <tr>
          	<td align="right" colspan="3">
          		<input id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
            </td>
          </tr>
        </table>
	</form>
</div>

<div id="responder" style="display:none">
	<form id="formularioResponder" name="formularioResponder">	                    
		<input name="id" type="hidden" id="idResponder" value="" />
		<input name="id" type="hidden" id="idResponderPadre" value="" />
		<table align="center" border="0" cellspacing="2" cellpadding="2">
      		<tr>
        		<td colspan="3">Comentario<br />
          			<textarea id="comentarioResponder"  style="height:400px; width:600px;" class="form-control" ></textarea>
      			</td>
  			</tr>
      		<tr>
      			<td align="right" colspan="3">
      				<input id="botonResponder" type="button" value="Crear comentario" class="btn green" />
        		</td>
      		</tr>
    	</table>
	</form>
</div>

<!-- <script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script> -->
<script type="text/javascript" src="../assets/global/plugins/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="./modulos/comentarios/dk-logica.js"></script>
