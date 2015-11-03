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
</div>

<div id="camposFormulario" style="display:none">        

		   <input name="id" type="hidden" id="id" value="" />
              <table width="80%" border="0" cellspacing="5" cellpadding="5">
               <tr>
                  <td align="right">Nombre</td> 
                  <td align="left"><input type="text" id="nombre" value="" size="50" maxlength="80"  class="form-control"/></td>
                </tr>
                <tr>
                  <td align="right" scope="col">Descripci&oacute;n</td>
                  <th align="left" scope="col"><input type="text" id="descripcion" value="" size="50" maxlength="150" class="form-control" /></th>
                </tr>
                <tr>
                  <td align="right">Dominio</td>
                  <td align="left"><input type="text" id="dominio" value="" size="25" maxlength="50" class="form-control" /></td>
                </tr> 
				<tr>
                  <td align="right">Token</td>
                  <td align="left"><input type="text" id="token" value="" size="25" maxlength="50" class="form-control" readonly="readonly" /></td>
                </tr>                               
				<tr>
                  <td align="right">Fecha Creaci&oacute;n</td>
                  <td align="left"><input type="text" id="fechaCreacion" value="" size="25" maxlength="50" class="form-control" readonly="readonly" /></td>
                </tr>
				<tr>
                  <td align="right">Usuario</td>
                  <td align="left"><select id="listaUsuarios"></select></td>
                </tr> 
                <tr>
                    <td align="right">Crear suscripción</td>
                    <td align="left"><input id="desplegar" type="checkbox" /></td>                
                </tr>
                <tr class="FTP" hidden="true">
                  <td align="right">FTP > Usuario</td>
                  <td align="left"><input type="text" id="ftp_usuario" value="" class="form-control" /></td>
                </tr>
                <tr class="FTP" hidden="true">
                  <td align="right">FTP > Contraseña</td>
                  <td align="left"><input type="text" id="ftp_contrasena" value="" class="form-control" /></td>
                </tr>
                <tr  id="pswd_info" hidden="true">
	                <td></td>
	                <td>
                        <ul>
                          <li id="letter">No incluye una <strong> letra minuscula. </strong></li>
                          <li id="capital"> No incluye una <strong>letra mayúscula. </strong></li>
                          <li id="number">No incluye una <strong>número. </strong></li>
                          <li id="length">Mejoraría con una longitud de <strong>8 carácteres</strong> como mínimo</li>
                        </ul>
    	            </td>
                </tr>
                <tr class="FTP" hidden="true">
                  <td align="right">FTP > Servidor</td>
                  <td align="left">
    	          		<select id="ftp_servidor">
                        	<option value="vps48028.ovh.net">vps48028.ovh.net</option>
                        	<option value="vps52140.ovh.net">vps52140.ovh.net</option>
                        	<option value="vps105964.ovh.net">vps105964.ovh.net</option>                                                        
	                  	</select>                  
                  </td>
                </tr>
              </table>
             
	
    <div align="right">
        <input id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
    </div>

</div>

<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="./modulos/sitiosWeb/dk-logica.js"></script>
