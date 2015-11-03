<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
    
    <div id="listaRegistros">
        <table width="12%" align="center" cellspacing="0" class="table table-hover" id="tablaRegistros" role="grid">
            <thead>
                <tr>
                    <th width="7%">id</th>
                    <th width="55%">Curso</th>
                    <th width="15%">Temas</th>
                    <th width="15%">Alumnos</th>
                    <th width="8%" align="right">
                    	<a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo curso" class="botonNuevo">
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
        
          <table width="80%" border="0" cellspacing="2" cellpadding="2">
            <tr>
              <td width="105">Nombre curso</td>
              <td colspan="2">
                  <input name="nombre" type="text" id="nombre" value="" size="45" maxlength="45" class="form-control" />
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
            <td colspan="3">
                <div align="right">
                	<br>
                    <input id="botonGuarda" type="button" value="Guardar Curso" class="btn btn-success" />
                </div>
            </td>
            </tr>
          </table>
	</div>
	
	<div id="duplicarFormulario" style="display:none">        
		<input name="idSeccionDuplicar" type="hidden" id="idSeccionDuplicar" value="" />
        
		<table width="80%" border="0" cellspacing="2" cellpadding="2">
			<tr>
				<td width="155">Curso a duplicar</td>
				<td colspan="2">
					<input name="nombre" type="text" readonly="readonly" id="seccionOld" value="" size="45" maxlength="45" class="form-control" />
				</td>
			</tr>
			<tr>
				<td width="155">Nombre curso</td>
				<td colspan="2">
					<input name="nombre" type="text" id="seccionNew" value="" size="45" maxlength="45" class="form-control" />
				</td>
			</tr>
            <tr>
            	<td width="155">Opciones de duplicación</td>
            	<td colspan="2">
            		<input type="radio" name="tipoDuplicacion" checked="checked" value="1"> Duplicar solo curso<br>
            		<input type="radio" name="tipoDuplicacion" value="2"> Duplicar solo estructura<br>
            		<input type="radio" name="tipoDuplicacion" value="3"> Duplicar estructura y datos
            	</td>
            </tr>
			<tr>
				<td colspan="3">
					<div align="right">
						<input id="botonDuplicar" type="button" value="Duplicar curso" class="btn btn-success" />
                	</div>
            	</td>
			</tr>
		</table>
	</div>
	
	<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
	
	<script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script>
            
    <script src="./modulos/academiaCursos/dk-logica.js"></script>
