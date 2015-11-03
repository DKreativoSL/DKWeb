<?php
	session_start();
	include_once("../../conexion.php");
	include_once("../../funciones.php");
	
	$css = getCSS($conexion, $_SESSION['sitioWeb']);
?>

<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div id="listaZonas">
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="5%">id</th>
                <th width="20%">Nombre</th>
                <th width="20%">Descripcion</th>
                <th width="12%">Zona padre</th>
                <th width="12%">Estado</th>
                <th width="10%" align="right">
                	<a id="botonNuevo" href="#" title="A&ntilde;adir una zona" class="botonNuevo">
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
	<form action="guarda.php" method="post" name="formulario">
		<input name="id" type="hidden" id="id"/>
		
		
		<div class="row margin-bottom-15">
			<div class="col-lg-12">
				<strong>DATOS DE ZONA</strong>
			</div>
		</div>
		<div class="row margin-bottom-15">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-12">
						Nombre
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<input name="nombre" class="form-control" type="text" id="nombre" size="50" maxlength="150" />
					</div>
				</div>
			</div>
		</div>
		<div class="row margin-bottom-15">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-12">
						Descripcion
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<textarea class="form-control" name="descripcion" cols="100" rows="6" id="descripcion"></textarea>
					</div>
				</div>
			</div>
		</div>
		<div class="row margin-bottom-15">
			<div class="col-lg-6">
				<div class="row">
					<div class="col-lg-12">
						Zona padre
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
	        			<select name="subzona" id="subzona" class="form-control">
	          				<option value="-1">Cargando...</option>
	        			</select>
					</div>
				</div>
			</div>
		</div>
		<div class="row margin-bottom-15">
			<div class="col-lg-12">
				<input id="botonGuarda" type="button" value="Guardar Zona" class="btn btn-success" />
			</div>
		</div>
	</form>
</div>

<!-- <script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script> -->
<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="./modulos/inmobiliariaZonas/dk-logica.js"></script>
