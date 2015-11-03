<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="listaArticulos">
	<input id="servidor" type="text" />
   	<input id="usuario" type="text" />
   	<input id="bbdd" type="text" />
   	<input id="pass" type="text" />
    <button id="cargaDatos" onclick="cargaDatos();" class="btn">
    
<!--
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">id</th>
                <th width="80%">titulo</th>
                <th width="10%" align="right"><a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo art&iacute;culo"><img src="./imgs/nuevo.png" alt="Nuevo articulo" border="0" /></a></th>
                
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table> -->
</div>

<div id="camposFormulario" style="display:none">

	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th>id</th>
                <th>Fecha</th>
                <th>Titulo</th>
                <th>Subtitulo</th>
                <th>Cuerpo</th>
                <th>Imagen</th>
                <th>Archivo</th>
                <th>URL</th>
                <th>Extra</th>
                <th>Sección</th>
                <th>Orden</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
    
	<!-- <form id="formulario" name="formulario">                    
          <input name="id" type="hidden" id="id" value="" />
        <table align="center" border="0" cellspacing="2" cellpadding="2">
          <tr>
            <td colspan="2">T&iacute;tulo<br />
              <input id="titulo" type="text" value="" size="80" maxlength="100" class="form-control" /></td>
            <td width="36%">Fecha de publicaci&oacute;n<br />
              <input type="text" id="fecha" value="" size="15" maxlength="10" class="form-control" /></td>
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
          		<input align="right" id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
            </td>
          </tr>
        </table>          
	</form> -->
</div>

<script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="./modulos/completo/dk-logica.js"></script>
