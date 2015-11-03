    <link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
    
    
	<div id="myModal" class="modal fade" role="dialog" style="display:none">
		<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<h4 class="modal-title">Recuperar sección</h4>
      				</div>
      			<div class="modal-body">
					<p>
	  					Elige la sección donde quieres recuperarla<br><br>
	  					<select id="selectPopupRecuperar" class="form-control">
	  						<option>Cargando...</option>
	  					</select>
  					</p>
      			</div>
      			<div class="modal-footer">
					<button type="button" class="btn btn-success" id="recuperarSeccion" data-dismiss="modal">Recuperar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      			</div>
    		</div>
  		</div>
	</div>
    
    <div id="listaRegistros">
        <table width="12%" align="center" cellspacing="0" class="table table-hover" id="tablaRegistros" role="grid">
            <thead>
                <tr>
                    <th width="7%">id</th>
                    <th width="55%">nombre</th>
                    <th width="15%">tipo</th>
                    <th width="15%">orden</th>
                    <th width="8%" align="right"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>  
        </table>
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
                        <option value="0">Basico</option>
                        <option value="1">Completo</option>
                        <option value="2">Personalizado</option>
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
                </div>
            </td>
            </tr>
            <tr>
              <td>Descripcion</td>
              <td colspan="2"><textarea name="descripcion" cols="45" rows="3" id="descripcion" class="form-control"></textarea></td>
            </tr>
            <tr>
			  <td width="82">Orden</td>
              <td width="732"><input name="txtOrden" type="text" id="txtOrden" value="" size="5" maxlength="5"  class="form-control"/></td>
              <td width="195"></td>
            </tr>
            <tr>
            <td colspan="3">
                <div align="right">
                    <input id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
                </div>
            </td>
            </tr>
          </table>
	</div>

	<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script>
            
    <script src="./modulos/trashSecciones/dk-logica.js"></script>
