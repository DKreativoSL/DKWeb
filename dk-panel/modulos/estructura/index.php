    <link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="./modulos/estructura/style.css"/>
    
	<div id="myModal" class="modal fade" role="dialog" style="display:none">
		<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<h4 class="modal-title">Eliminar una sección</h4>
      				</div>
      			<div class="modal-body">
					<p>
						
				  		Esta apunto de eliminar una sección que tiene información dentro. Tiene varias opciónes:<br><br>
				  		
				  		1. Elige la sección donde quiera copiar la información y pulse el boton "Mover contneido y eliminar sección"<br><br>
				  		
	  					<select id="selectPopupEliminar" class="form-control">
	  						<option>Cargando...</option>
	  					</select>
				  		
				  		<br><br>
				  		2. Eliminar la sección y su contenido, para ello, pulse el boton "Eliminar sección y contenido"<br>
  					</p>
      			</div>
      			<div class="modal-footer">
					<button type="button" class="btn btn-success" id="moverEliminar" data-dismiss="modal">Mover contenido y eliminar sección</button>
					<button type="button" class="btn btn-warning" id="eliminarTodo" data-dismiss="modal">Eliminar sección y contenido</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      			</div>
    		</div>
  		</div>
	</div>
    
    <div id="listaRegistros">
    	<div class="row">
    		<div class="col-lg-12">
				<a id="botonNuevo" href="#" title="A&ntilde;adir una nueva secci&oacute;n" class="botonNuevo pull-right">
					<i class="fa fa-plus-circle fa-2x"></i>
				</a>
    		</div>
    	</div>
    	<div class="row">
    		<div class="col-lg-12">
				<div class="just-padding">
					<div id="registros" class="list-group list-group-root well">
						<!-- SE RELLENA CON AJAX -->
					</div> 
				</div>
    		</div>
    	</div>
    </div>

	<div id="camposFormulario" style="display:none">        
		<input name="id" type="hidden" id="id" value="" />
        
          <table width="80%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="105">Nombre</td>
              <td colspan="2">
                  <input name="nombre" type="text" id="nombre" value="" size="45" maxlength="45" class="form-control" />
              </td>
            </tr>
            <tr>
              <td>Secci&oacute;n Padre</td>
              <td colspan="2">
                <select id="seccion" class="form-control">
                    <option value="0">Sin subseccion</option>                    
                 </select>					</td>
            </tr>
            <tr>
              <td>Privada</td>
              <td colspan="2">
                <select id="privada" class="form-control">
                 	<option value="0">No</option>
                    <option value="1">Si</option>
                </select>              
                </td>
            </tr>
            <tr>
              <td>Tipo</td>
              <td>
                    <select id="tipo" class="form-control">
                        <option value="1">Basico</option>
                        <option value="2">Avanzado</option>
                        <option value="3">Curso</option>
                    </select>
              </td>
              <td>
              </td>
            </tr>
            <tr>
            <td></td>
            <td colspan="2">
                <div id="capaFormularioSeccion" hidden="true">
                    <textarea name="formularioSeccion" id="formularioSeccion"></textarea>
<!--                    <button id="botonGuardarFormularioSeccion" class="btn blue" >Guardar formulario</button> -->
                </div>
            </td>
            </tr>
            <tr>
              <td>Descripcion</td>
              <td colspan="2"><textarea name="descripcion" cols="45" rows="3" id="descripcion" class="form-control"></textarea></td>
            </tr>
            <tr>
			  <td width="82">Orden</td>
              <td width="732"><input name="orden" type="text" id="orden" value="" size="5" maxlength="5"  class="form-control"/></td>
              <td width="195"></td>
            </tr>
			<tr>
				<td colspan="3">
					<br>
					<table width="20%" border="0" cellspacing="2" cellpadding="2">
						<thead>
							<tr>
								<th colspan="2" style="text-align:center; font-weight: bold;">Campos del formulario</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>Titulo</td>
								<td><input type="checkbox" id="ch_CampoTitulo" class="camposFormulario" name="ch_CampoTitulo"></td>
							</tr>
							<tr>
								<td>SubTitulo</td>
								<td><input type="checkbox" id="ch_CampoSubTitulo" class="camposFormulario" name="ch_CampoSubTitulo"></td>
							</tr>
							<tr>
								<td>Cuerpo</td>
								<td><input type="checkbox" id="ch_CampoCuerpo" class="camposFormulario" name="ch_CampoCuerpo"></td>
							</tr>
							<tr>
								<td>Cuerpo Avance</td>
								<td><input type="checkbox" id="ch_CampoCuerpoAvance" class="camposFormulario" name="ch_CampoCuerpoAvance"></td>
							</tr>
							<tr>
								<td>Fecha de publicación</td>
								<td><input type="checkbox" id="ch_CampoFechaPublicacion" class="camposFormulario" name="ch_CampoFechaPublicacion"></td>
							</tr>
							<tr>
								<td>Imagen</td>
								<td><input type="checkbox" id="ch_CampoImagen" class="camposFormulario" name="ch_CampoImagen"></td>
							</tr>
							<tr>
								<td>Archivo</td>
								<td><input type="checkbox" id="ch_CampoArchivo" class="camposFormulario" name="ch_CampoArchivo"></td>
							</tr>
							<tr>
								<td>URL</td>
								<td><input type="checkbox" id="ch_CampoURL" class="camposFormulario" name="ch_CampoURL"></td>
							</tr>
							<tr>
								<td>Campo extra</td>
								<td><input type="checkbox" id="ch_CampoCampoExtra" class="camposFormulario" name="ch_CampoCampoExtra"></td>
							</tr>
						</tbody>						
					</table>
				</td>
			</tr>
            <tr>
            <td colspan="3">
                <div align="right">
                    <input id="botonGuarda" type="button" value="Guardar sección" class="btn green" />
                </div>
            </td>
            </tr>
          </table>
	</div>
	
	<div id="duplicarFormulario" style="display:none">        
		<input name="idSeccionDuplicar" type="hidden" id="idSeccionDuplicar" value="" />
        
		<table width="80%" border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td width="155">Sección a duplicar</td>
				<td colspan="2">
					<input name="nombre" type="text" readonly="readonly" id="seccionOld" value="" size="45" maxlength="45" class="form-control" />
				</td>
			</tr>
			<tr>
				<td width="155">Nombre seccion</td>
				<td colspan="2">
					<input name="nombre" type="text" id="seccionNew" value="" size="45" maxlength="45" class="form-control" />
				</td>
			</tr>
            <tr>
            	<td width="155">Opciones de duplicación</td>
            	<td colspan="2">
            		<input type="radio" name="tipoDuplicacion" checked="checked" value="1"> Duplicar solo sección<br>
            		<input type="radio" name="tipoDuplicacion" value="2"> Duplicar solo estructura<br>
            		<input type="radio" name="tipoDuplicacion" value="3"> Duplicar estructura y datos
            	</td>
            </tr>
			<tr>
				<td colspan="3">
					<div align="right">
						<input id="botonDuplicar" type="button" value="Duplicar sección" class="btn green" />
                	</div>
            	</td>
			</tr>
		</table>
	</div>

	<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script>
            
    <script src="./modulos/estructura/dk-logica.js"></script>
