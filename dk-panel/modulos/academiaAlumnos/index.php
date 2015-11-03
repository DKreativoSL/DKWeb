<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>

<div id="popupCursos" class="modal fade" role="dialog" style="display:none">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Cursos del alumno</h4>
  				</div>
  			<div class="modal-body">
  				
				<form id="formularioPopup" name="formulario">
					<input type="hidden" id="idUsuario" value="" size="15" class="form-control" />
					<input type="hidden" id="idAlumnoCurso" value="" size="15" class="form-control" />	                    
					<div class="panel panel-default">
				        <div class="panel-heading">                   
				            <h3 class="panel-title text-left">Datos del curso</h3>
				        </div>
				        <div class="panel-body">
				            <div class="row">
				                <div class="col-lg-12">
				                    Curso<br />
				                    <select id="idCurso" name="idCurso" class="form-control">
				                    	<option>Cargando...</option>
				                    </select>
				                </div>
				            </div>
				            <div class="row">
				                <div class="col-lg-6">
				                    Fecha alta<br />
				                    <input type="text" id="fechaAlta" size="20" maxlength="20" class="form-control" /></div>
				                <div class="col-lg-6">
				                    Fecha baja<br />
				                    <input type="text" id="fechaBaja" size="20" maxlength="20" class="form-control" />
				                </div>
				            </div>
				        </div>
			    	</div>
    				<!-- fin acceso -->
    			</form>
  			</div>
  			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="guardarPopupCurso">Guardar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  			</div>
		</div>
	</div>
</div>

<div id="listaAlumnos">
	<table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">id</th>
                <th width="20%">nombre</th>
                <th width="36%">usuario</th>
                <th width="12%">cursos</th>
                <th width="10%" align="right">
                	<a id="botonNuevoAlumno" href="#" title="A&ntilde;adir un nuevo alumno" class="botonNuevo">
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
		<input type="hidden" id="idAlumno" value="" size="15" class="form-control" />	                    
		<div class="panel panel-default">
	        <div class="panel-heading">                   
	            <h3 class="panel-title text-left">Datos de Acceso</h3>
	        </div>
	        <div class="panel-body">
	            <div class="row">
	                <div class="col-lg-12">
	                    Usuario/Email<br />
	                    <input type="text" id="email" value="" size="50" maxlength="80"  class="form-control"/>
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-6">
	                    Password<br />
	                    <input type="password" id="clave" placeholder="Dejar vacio si no se quiere modificar" size="20" maxlength="20" class="form-control" /></div>
	                <div class="col-lg-6">
	                    Confirmaci&oacute;n<br />
	                    <input type="password" id="clave2" placeholder="Dejar vacio si no se quiere modificar" size="20" maxlength="20" class="form-control" />
	                </div>
	            </div>
	        </div>
    	</div>
    	<!-- fin acceso -->
    
	    <div class="panel panel-default">
	        <div class="panel-heading">                   
	            <h3 class="panel-title text-left">Datos del usuario</h3>
	        </div>
	        <div class="panel-body">
	            <div class="row">
	                <div class="col-lg-8">
	                    Nombre<br />
	                    <input type="text" id="nombre" value="" size="50" maxlength="150" class="form-control" />
	                </div>
	                <div class="col-lg-4">
	                    NIF<br />
	                    <input type="text" id="nif" value="" size="25" maxlength="50" class="form-control" />
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-8">
	                    Direcci&oacute;n<br />
	                    <textarea cols="50" rows="2" id="direccion" class="form-control"></textarea>
	                </div>
	                <div class="col-lg-4">
	                    CP<br />
	                    <input type="text" id="cp" value="" size="25" maxlength="50" class="form-control" />
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-6">
	                    Poblaci&oacute;n <br />
	                    <input type="text" id="poblacion" value="" size="25" maxlength="50" class="form-control" />
	                </div>
	                <div class="col-lg-6">
	                    Provincia<br />
	                    <input type="text" id="provincia" value="" size="25" maxlength="50" class="form-control" />
	                </div>        
	            </div>
	            <div class="row">
	                <div class="col-lg-6">
	                    Tel&eacute;fono<br />
	                    <input type="text" id="tlf1" value="" size="25" maxlength="15" class="form-control" />
	                </div>
	                <div class="col-lg-6">
	                    Tel&eacute;fono 2<br />
	                    <input type="text" id="tlf2" value="" size="25" maxlength="15" class="form-control" />
	                </div>
	            </div>
	            <div class="row">
	                <div class="col-lg-12">
	                    Sobre t&iacute;<br />
	                    <textarea cols="50" rows="2" id="sobreti" class="form-control"></textarea></div>
	            </div>
	    	</div>
		</div>
	    <!-- fin datos personales del usuario -->
		<div class="panel panel-default">
	    	<div class="panel-heading">                   
	            <h3 class="panel-title text-left">Información de los Cursos</h3>
	        </div>
	        <div class="panel-body">
	            
	            <div class="row">
	                <div class="col-lg-12">
	                    <table id="tablaCursosAlumno" class="table table-hover" cellspacing="0" align="center" role="grid">
	                        <thead>
	                            <tr>
	                                <th width="10%">id</th>
	                                <th width="36%">Curso</th>
	                                <th width="13%">Fecha Alta</th>
	                                <th width="13%">Fecha Baja</th>
	                                <th width="13%">Ultimo acceso</th>                                
	                                <th width="10%">Progreso</th>
	                                <th width="10%" align="right">
	                                    <a id="botonNuevoCurso" href="#" title="Asignar un nuevo curso" class="botonNuevo">
	                                        <i class="fa fa-plus-circle fa-2x"></i>
	                                    </a>
	                                </th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                        </tbody>  
	                    </table>
	                </div>
	            </div>
	        </div>
	    </div>  
	    <div class="row">        
	        <input id="botonGuarda" type="button" value="Guardar alumno" class="btn btn-success" />                   
	    </div>
	</form>
</div>

<!-- <script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script> -->
<script type="text/javascript" src="../assets/global/plugins/tinymce/tinymce.min.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js" type="text/javascript"></script>
<script src="./modulos/academiaAlumnos/dk-logica.js"></script>
