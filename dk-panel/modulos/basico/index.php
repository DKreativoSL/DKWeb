<?php
	session_start();
	include_once("../../conexion.php");
	include_once("../../funciones.php");
	$css = getCSS($conexion, $_SESSION['sitioWeb']);
?>
<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div id="listaArticulos">
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">id</th>
                <th width="20%">usuario</th>
                <th width="36%">titulo</th>
                <th width="12%">estado</th>
                <th width="12%">fecha publicación</th>
                <th width="10%" align="right">
                	<a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo art&iacute;culo" class="botonNuevo">
                		<i class="fa fa-plus-circle fa-2x"></i>
            		</a>
        		</th>
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
        <table align="center" border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td id="campo_Titulo" colspan="2">
					T&iacute;tulo<br />
					<input id="titulo" type="text" value="" size="80" maxlength="100" class="form-control" />
				</td>
            	<td id="campo_FechaPublicacion" width="36%">
            		Fecha de publicaci&oacute;n<br />
					<input type="text" value="" id="fechaPublicacion" value="" size="15" class="form-control">
				</td>
          	</tr>
          	<tr id="campo_SubTitulo">
            	<td colspan="3">
            		Subt&iacute;tulo<br />
              		<input id="subtitulo" type="text"  value="" size="80" maxlength="250" class="form-control" />
          		</td>            
          	</tr>
          	<tr id="campo_Cuerpo">
            	<td colspan="3">
            		Cuerpo<br />
              		<textarea id="cuerpo"  style="height:400px;" class="form-control" ></textarea>
          		</td>
          	</tr>
          	<tr id="campo_CuerpoAvance">
            	<td colspan="3">
            		Cuerpo Avance<br />
              		<textarea id="cuerpoResumen" cols="100" rows="2" class="form-control" ></textarea>
          		</td>
          	</tr>
          	<tr id="campo_Imagen">
            	<td>Imagen<br /><input id="imagen" type="text" value="" size="30" maxlength="200" class="form-control"  /></td>
            	<td><br /><input type="button" value="Gestionar" onclick="ventanaPopup('./filemanager/index.php?button=none&type=image_form')" class="form-control"  /><div id="imagen_previo"></div></td>
            	<td>&nbsp;</td>
          	</tr>
          	<tr id="campo_Archivo">
            	<td>Archivo<br /><input id="archivo" type="text" value="" size="30" maxlength="200" class="form-control" /></td>            
            	<td><br /><input type="button" value="Gestionar" onclick="ventanaPopup('./filemanager/index.php?button=none&type=file_form')" class="form-control" /></td>
            	<td>&nbsp;</td>
          	</tr>
          	<tr>
            	<td colspan="3" scope="col">&nbsp;</td>
          	</tr>
          	<tr id="campo_CampoURL">
            	<td colspan="3" scope="col">
            		URL<br />
              		<input id="url" type="text" value="" size="100" maxlength="100" class="form-control" />
          		</td>
          	</tr>
          	<tr id="campo_CampoExtra">
            	<td colspan="3" scope="col">
            		Campo extra<br />
              		<input id="campoExtra" type="text" value="" size="100" maxlength="100" class="form-control" />
          		</td>
          	</tr>
          	<tr>
            	<td>
            		Secci&oacute;n<br />
              		<select id="seccion" class="form-control" >
              		</select>
          		</td>
            	<td>
            		Orden<br />
              		<input id="orden" type="text" value="" size="5" maxlength="3" class="form-control" />
          		</td>
            	<td scope="col">&nbsp;</td>
          	</tr>
			<tr>
				<td>¿Estado?<br />
					<select id="estado" class="form-control" >
						<option value="0">Borrador</option>
						<option value="1">Publicado</option>
						<option value="2">Programado</option>
						<option value="3">Eliminado</option>
					</select>
				</td>
          	</tr>
          	<tr>
          		<td align="right" colspan="3">
          			<input align="right" id="botonGuarda" type="button" value="Guardar Documento" class="btn btn-success" />
            	</td>
          	</tr>
    	</table>          
	</form>
</div>

<script type="text/javascript" src="../assets/global/plugins/tinymce/tinymce.min.js"></script>

<!-- <script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script> -->

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
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
	
tinymce.init({
	id:'cuerpo',
    selector: "#cuerpo",
	content_css : "<?php echo $css['css_tinymce']; ?>",
    plugins: [
            "dkfiles advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker	",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality textcolor paste textcolor colorpicker textpattern"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor | dkimage dkvideo dkfile | code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

	language : 'es',
    menubar: false,
    toolbar_items_size: 'small',
    style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
});

tinymce.init({
	id:'resumen',
    selector: "#cuerpoResumen",
	content_css : "<?php echo $css['css_tinymce']; ?>",
    plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

	language : 'es',
    menubar: false,
    toolbar_items_size: 'small',
    style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
});
</script>

<script src="./modulos/basico/dk-logica.js"></script>
