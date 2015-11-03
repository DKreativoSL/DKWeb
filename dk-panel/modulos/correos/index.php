<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="listaArticulos">
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">id</th>
                <th width="80%">correo</th>
                <th width="10%" align="right"><a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo correo"><img src="./imgs/nuevo.png" alt="Crear correo" border="0" /></a></th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
</div>

<div id="camposFormulario" style="display:none">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-left">Datos básicos</h3>
        </div>
        <div class="panel-body">        
            <form id="formulario" name="formulario">
                <input name="id" type="hidden" id="id" value="" />
                <div class="row form-group">
                    <div class="col-md-2">
                        Cuenta de correo
                    </div>                
                    <div class="col-md-10">
                        <input  id="correo" type="text" value="" class="form-control" /><div id="dominio"></div>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        Contraseña
                    </div>
                    <div class="col-md-4">
                        <input id="password" type="password" value="" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        Confirmar contraseña
                    </div>
                    <div class="col-md-4">
                        <input id="passwordconfirma" type="password" class="form-control" />
                    </div>
				</div>
                <div class="col-md-12" align="right">
                    <input id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
                </div>
            </form>
		</div>
	</div>                    
</div>

<script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="./modulos/correos/dk-logica.js"></script>
