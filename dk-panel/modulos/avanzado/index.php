<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="listaArticulos">
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">id</th>
                <th width="80%">principal</th>
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
		
        <input name="id" type="hidden" id="id" value="" />
        <!-- Aquí va el contenido del formulario personalizado en la sección -->
        <div id="contenidoPersonalizado"></div>

		<input align="right" id="botonGuarda" type="button" value="Guardar Documento" class="btn btn-success" />
        
	</form>
</div>




<script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="./modulos/avanzado/dk-logica.js"></script>
