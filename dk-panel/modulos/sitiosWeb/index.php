<link rel="stylesheet" type="text/css" href="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="listaRegistros">
	<table width="12%" align="center" cellspacing="0" class="table table-hover" id="tablaRegistros" role="grid">
        <thead>
            <tr>
                <th width="20%">Nombre</th>
                <th width="30%">Descripcion</th>
                <th width="10%">Dominio</th>
                <th width="15%">Fecha Creaci&oacute;n</th>                
                <th width="15%">Token</th>
                <th width="10%" align="right">
                	<a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo sitio Web" class="botonNuevo">
                		<i class="fa fa-plus-circle fa-2x"></i>
            		</a>
        		</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
	
    <div class="modal fade" id="popupDuplicandoWeb" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">            
                <div class="modal-body">
                    Duplicando web...
                </div>
            </div>
        </div>
    </div>
	
</div>

<div id="camposFormulario" style="display:none">
	
	<form>
		<input name="id" type="hidden" id="id" value="" />
		<fieldset class="form-group">
    		<label for="nombre">Nombre</label>
    		<input type="text" class="form-control" id="nombre" placeholder="Nombre de la web">
  		</fieldset>
  		<fieldset class="form-group">
    		<label for="descripcion">Descripción</label>
    		<input type="text" id="descripcion" class="form-control" placeholder="Descripción de la web">
  		</fieldset>
  		<fieldset class="form-group">
    		<label for="dominio">Dominio</label>
    		<input type="text" id="dominio" class="form-control" placeholder="www.prueba.es">
  		</fieldset>
  		<fieldset class="form-group">
    		<label for="token">Token</label>
    		<input type="text" id="token" class="form-control" placeholder="5H811b2804fe56cdsa6f97e5178def52">
  		</fieldset>
  		<fieldset class="form-group">
    		<label for="fechaCreacion">Fecha Creaci&oacute;n</label>
    		<input type="text" id="fechaCreacion" class="form-control" placeholder="01/01/2016">
  		</fieldset>
  		<fieldset class="form-group">
    		<label for="exampleSelect1">Usuario creador</label>
    		<select class="form-control" disabled="disabled" id="listaUsuarios"><!-- --></select>
  		</fieldset>
  		<!--
		<fieldset class="form-group">
  			<div class="checkbox">
    		<label>
	      		<input id="desplegar" type="checkbox"> Crear suscripción
    		</label>
			</div>
  		</fieldset>
		<fieldset class="form-group FTP" style="display:none">
    		<label for="ftp_usuario">Usuario FTP</label>
    		<input type="text" class="form-control" id="ftp_usuario" placeholder="Usuario FTP">
  		</fieldset>
		<fieldset class="form-group FTP" style="display:none">
    		<label for="ftp_contrasena">Contraseña FTP</label>
    		<input type="password" class="form-control" id="ftp_contrasena" placeholder="Contraseña FTP">
    		<small class="text-muted pswd_info" style="display:none">
	            <ul>
					<li id="letter">No incluye una <strong> letra minuscula. </strong></li>
					<li id="capital"> No incluye una <strong>letra mayúscula. </strong></li>
	              	<li id="number">No incluye una <strong>número. </strong></li>
	              	<li id="length">Mejoraría con una longitud de <strong>8 carácteres</strong> como mínimo</li>
	            </ul>
    		</small>
  		</fieldset>
		<fieldset class="form-group FTP" style="display:none">
    		<label for="ftp_servidor">Servidor FTP</label>
      		<select class="form-control" id="ftp_servidor">
            	<option value="vps48028.ovh.net">vps48028.ovh.net</option>
            	<option value="vps52140.ovh.net">vps52140.ovh.net</option>
            	<option value="vps105964.ovh.net">vps105964.ovh.net</option>                                                        
          	</select>
  		</fieldset>
  		-->
  		<button type="button" id="botonGuarda" class="btn btn-primary">Guardar Website</button>
	</form>
	
</div>

<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="./modulos/sitiosWeb/dk-logica.js"></script>
