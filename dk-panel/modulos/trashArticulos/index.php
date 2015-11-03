<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
    
    
	<div id="myModal" class="modal fade" role="dialog" style="display:none">
		<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<h4 class="modal-title">Recuperar articulo</h4>
      				</div>
      			<div class="modal-body">
					<p>
	  					Elige la sección donde quieres recuperarlo<br><br>
	  					<select id="selectPopupRecuperar" class="form-control">
	  						<option>Cargando...</option>
	  					</select>
  					</p>
      			</div>
      			<div class="modal-footer">
					<button type="button" class="btn btn-success" id="recuperarArticulo" data-dismiss="modal">Recuperar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      			</div>
    		</div>
  		</div>
	</div>

    <div id="listaRegistros">
        <table width="12%" align="center" cellspacing="0" class="table table-hover" id="tablaRegistros" role="grid">
            <thead>
                <tr>
	                <th width="10%">id</th>
	                <th width="20%">usuario</th>
	                <th width="36%">titulo</th>
	                <th width="12%">estado</th>
	                <th width="12%">fecha publicación</th>
                    <th width="10%" align="right"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>  
        </table>
        <div class="row">
        	<div class="col-lg-4">
        		<input type="button" value="Vaciar papelera" id="vaciarPapeleraArticulos" class="btn btn-warning">
        	</div>
        </div>
    </div>
    
	<div id="camposFormulario" style="display:none">
		<!--
		<div class="row">
			<div class="col-lg-12">
				<form class="form-horizontal" role="form" id="formulario" name="formulario">
					<input name="tipo" type="hidden" id="tipo" value="dkgest" />	                    
					<input name="id" type="hidden" id="id" value="" />
					<div class="form-group">
						<label for="titulo" class="col-lg-2 control-label">T&iacute;tulo</label>
						<div class="col-lg-10">
							<input type="email" class="form-control" id="titulo" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label for="ejemplo_password_3" class="col-lg-2 control-label">Contraseña</label>
						<div class="col-lg-10">
							<input type="password" class="form-control" id="ejemplo_password_3" placeholder="Contraseña">
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
  							<div class="checkbox">
								<label><input type="checkbox"> No cerrar sesión</label>
  							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-offset-2 col-lg-10">
							<input align="right" id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
						</div>
					</div>
				</form>
			</div>
		</div>
	-->
	<form class="form-horizontal" role="form" id="formulario" name="formulario">
		<input name="tipo" type="hidden" id="tipo" value="dkgest" />	                    
		<input name="id" type="hidden" id="id" value="" />
			<table align="center" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="2">T&iacute;tulo<br />
              <input id="titulo" type="text" value="" size="80" maxlength="100" class="form-control" /></td>
            <td width="36%">Fecha de publicaci&oacute;n<br />
				<input type="text" value="" id="fechaPublicacion" value="" size="15" class="form-control">
			</td>
          </tr>
          <tr>
            <td colspan="3">Subt&iacute;tulo<br />
              <input id="subtitulo" type="text"  value="" size="80" maxlength="250" class="form-control" /></td>            
          </tr>
          <tr>
            <td colspan="3">Cuerpo<br />
              <textarea id="cuerpo"  style="height:400px;" class="form-control" ></textarea></td>
          </tr>
          <tr>
            <td colspan="3">Cuerpo Avance<br />
              <textarea id="cuerpoResumen" cols="100" rows="2" class="form-control" ></textarea></td>
          </tr>
          <tr>
            <td>Imagen<br /><input id="imagen" type="text" value="" size="30" maxlength="200" class="form-control"  /></td>
            <td><br /><input type="button" value="Gestionar" onclick="ventanaPopup('./filemanager/index.php?campo=imagen')" class="form-control"  /><div id="imagen_previo"></div></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>Archivo<br /><input id="archivo" type="text" value="" size="30" maxlength="200" class="form-control" /></td>            
            <td><br /><input type="button" value="Gestionar" onclick="ventanaPopup('./filemanager/index.php?campo=archivo')" class="form-control" /></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" scope="col">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3" scope="col">URL<br />
              <input id="url" type="text" value="" size="100" maxlength="100" class="form-control" /></td>
          </tr>
          <tr>
            <td colspan="3" scope="col">Campo extra<br />
              <input id="campoExtra" type="text" value="" size="100" maxlength="100" class="form-control" /></td>
          </tr>
          <tr>
            <td>Secci&oacute;n<br />
              <select id="seccion" class="form-control" >
                <option value="0">&gt; Borrador</option>
              </select></td>
            <td>Orden<br />
              <input id="orden" type="text" value="" size="5" maxlength="3" class="form-control" /></td>
            <td scope="col">&nbsp;</td>
          </tr>
          <tr>
          	<td align="right" colspan="3">
          		<input align="right" id="botonGuarda" type="button" value="Guardar articulo" class="btn btn-success"/>
            </td>
          </tr>
        </table>
        </form>
</div>
    
<script type="text/javascript" src="../assets/global/plugins/tinymce/tinymce.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>
<script src="../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js" type="text/javascript"></script>
<script type="text/javascript">
	$("#fechaPublicacion").datetimepicker({
		format: 'yyyy-mm-dd hh:ii:ss',
		todayBtn: true,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});
</script>
        
<script src="./modulos/trashArticulos/dk-logica.js"></script>
