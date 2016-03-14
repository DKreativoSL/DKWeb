<link rel="stylesheet" type="text/css" href="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen-bootstrap.css" rel="stylesheet" type="text/css"/>

<style>
	.listadoUsuariosWeb {
		margin-right: 10px;
	}
	.desvincularUsuario {
		margin-right: 10px;
	}
</style>

<input type="hidden" id="idWebsiteEditando">

<!-- LISTADO WEBS -->
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
                	<a id="botonNuevo" href="#" title="Añadir un nuevo sitio Web" data-toggle="tooltip" class="botonNuevo">
                		<i class="fa fa-plus-circle fa-2x"></i>
            		</a>
        		</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
	
    <div id="popupDuplicandoWeb" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">            
                <div class="modal-body">
                    Duplicando web...
                </div>
            </div>
        </div>
    </div>
    
	<div id="popupConfirmarDuplicarWeb" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<input tpye="hidden" id="idWebADuplicar">
		<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<h4 class="modal-title">Duplicar web</h4>
      				</div>
      			<div class="modal-body">
					<p>
						Seleccione el usuario al cual quiere vincular la nueva web
						<select id="usuarioAVincularDeLaWebADuplicar" class="form-control chosen-select" data-placeholder="Selecciona un usuario existente..."></select>
  					</p>
      			</div>
      			<div class="modal-footer">
					<button type="button" class="btn btn-success" id="btn_confirmarDuplicarWeb">Duplicar web</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      			</div>
    		</div>
  		</div>
	</div>
    
    <div id="popupPrevioDuplicarWeb" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">            
                <div class="modal-body">
                    Duplicando web...
                </div>
            </div>
        </div>
    </div>	
    
</div>
<!-- FIN: LISTADO WEBS -->

<!-- FORMULARIO EDITAR WEB -->
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
	    <button type="button" id="volverListadoWebsDesdeModificar" class="btn btn-default btn-sm">
	      <span class="fa fa-arrow-left"></span> Volver
	    </button>
  		<button type="button" id="botonGuarda" class="btn btn-sm btn-primary pull-right">
  			<span class="fa fa-floppy-o"></span> Guardar website
		</button>
	</form>	
</div>
<!-- FIN: FORMULARIO EDITAR WEB -->

<!-- LISTADO DE USUARIOS VINCULADOS A UNA WEB -->
<div id="listaUsuariosWeb" style="display:none">
	<table width="12%" align="center" cellspacing="0" class="table table-hover" id="tablaUsuariosWeb" role="grid">
        <thead>
            <tr>
                <th width="20%">Id</th>
                <th width="30%">Nombre</th>
                <th width="10%">Email</th>
                <th width="15%">Grupo</th>                
                <th width="10%" align="right">
                	<a id="nuevoUsuarioWeb" href="#" title="Añadir un nuevo usuario" data-toggle="tooltip" class="botonNuevo">
                		<i class="fa fa-plus-circle fa-2x"></i>
            		</a>
        		</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
    <button type="button" id="volverListadoWebs" class="btn btn-default btn-sm">
      <span class="fa fa-arrow-left"></span> Volver
    </button>
    
	<div id="popupConfirmarDesvincularUsuario" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<input tpye="hidden" id="idWebsiteADesvincular">
		<input tpye="hidden" id="idUsuarioADesvincular">
		<div class="modal-dialog">
    		<!-- Modal content-->
    		<div class="modal-content">
      			<div class="modal-header">
        			<button type="button" class="close" data-dismiss="modal">&times;</button>
        				<h4 class="modal-title">Desvincular un usuario</h4>
      				</div>
      			<div class="modal-body">
					<p>
						Estas seguro de querer desvincular a <strong id="popupConfirmarDesvincularUsuario_nombre"></strong> de la web <strong id="popupConfirmarDesvincularUsuario_web"></strong>?
  					</p>
      			</div>
      			<div class="modal-footer">
					<button type="button" class="btn btn-success" id="btn_confirmarDesvincularUsuario" data-dismiss="modal">Continuar</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      			</div>
    		</div>
  		</div>
	</div>
</div>
<!-- FIN LISTADO DE USUARIOS VINCULADOS A UNA WEB -->

<!-- FORMULARIO EDITAR USUARIO VINCULADO -->
<div id="formularioEditarUsuarioVinculado" style="display:none">        
 	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-left">Datos de acceso</h3>
        </div>
        <div class="panel-body"> 
       			<input name="id" type="hidden" id="editarUsuarioVinculado_id" value="" />
	            <div class="row form-group">
                    <div class="col-md-2">
                        Usuario/Email
                    </div>                
                    <div class="col-md-10">
                        <input type="text" id="editarUsuarioVinculado_email" value="" size="50" maxlength="80"  class="form-control"/>
                    </div>
                </div>
				<div class="row form-group">
                    <div class="col-md-2">
                        Contraseña
                    </div>
                    <div class="col-md-4">
                        <input id="editarUsuarioVinculado_clave" type="password" value="" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        Confirmar contraseña
                    </div>
                    <div class="col-md-4">
                        <input id="editarUsuarioVinculado_clave2" type="password" class="form-control" />
                    </div>
				</div>
		</div>
	</div>	
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-left">Datos informativos</h3>
        </div>
        <div class="panel-body">                 
	            <div class="row form-group">
                    <div class="col-md-2">
                        Nombre
                    </div>                
                    <div class="col-md-5">
                        <input type="text" id="editarUsuarioVinculado_nombre" value="" size="50" maxlength="150" class="form-control" />
                    </div>
					<div class="col-md-2">
                        NIF
                    </div>                
                    <div class="col-md-3">
                        <input type="text" id="editarUsuarioVinculado_nif" value="" size="25" maxlength="50" class="form-control" />
                    </div>                    
                </div>
	            <div class="row form-group">
	                <div class="col-md-2">
                        Direccion
                    </div>                
                    <div class="col-md-10">
                        <input type="text" id="editarUsuarioVinculado_direccion" value="" class="form-control" />
                    </div>
    	        </div>
	            <div class="row form-group">
                    <div class="col-md-2">
                        CP
                    </div>                
                    <div class="col-md-1">
                        <input type="text" id="editarUsuarioVinculado_cp" value="" class="form-control" />
                    </div>
					<div class="col-md-2">
                        Población
                    </div>                
                    <div class="col-md-3">
                        <input type="text" id="editarUsuarioVinculado_poblacion" value="" class="form-control" />
                    </div>                    
					<div class="col-md-2">
                        Provincia
                    </div>                
                    <div class="col-md-2">
                        <input type="text" id="editarUsuarioVinculado_provincia" value="" size="25" maxlength="50" class="form-control" />
                    </div>
                </div>                                                  
	            <div class="row form-group">
                    <div class="col-md-2">
                        Teléfono 1
                    </div>                
                    <div class="col-md-4">
                        <input type="text" id="editarUsuarioVinculado_tlf1" value="" class="form-control" />
                    </div>
					<div class="col-md-2">
                        Teléfono 2
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="editarUsuarioVinculado_tlf2" value="" class="form-control" />
                    </div>
                </div>
	            <div class="row form-group">
                    <div class="col-md-2">
                        Comentario:
                    </div>                
                    <div class="col-md-10">
                        <input type="text" id="editarUsuarioVinculado_sobreti" value="" class="form-control" />
                    </div>
                </div>
			</div>
		</div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title text-left">Permisos</h3>
            </div>
            <div class="panel-body">
                <div class="row form-group">
					<div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoContenidoWeb" type="checkbox" /> Contenido Web</div>
					<div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoSecciones" type="checkbox" /> Secciones</div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoConfiguracion" type="checkbox" /> Configuración</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoParametros" type="checkbox" /> Parametros</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoUsuarios" type="checkbox" /> Usuarios</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoCorreos" type="checkbox" /> Config Correo</div>        
                </div>
                <div class="row form-group">
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoInmobiliaria" type="checkbox" /> Inmobiliaria</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoInmoApuntes" type="checkbox" /> Inmobiliaria Apuntes</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoInmoClientes" type="checkbox" /> Inmobiliaria Clientes</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoInmoInmuebles" type="checkbox" /> Inmobiliaria Inmuebles</div>
                    <div class="col-md-2"><input id="editarUsuarioVinculado_menuPermisoInmoZonas" type="checkbox" /> Inmobiliaria Zona</div>
				</div>
            </div>
		</div>

		<button type="button" id="volverListadoUsuariosDesdeFormularioEditarUsuarioVinculado" class="btn btn-default btn-sm">
	  		<span class="fa fa-arrow-left"></span> Volver
		</button>
		           
  		<button type="button" id="btn_guardarFormularioEditarUsuarioVinculado" class="btn btn-sm btn-primary pull-right">
  			<span class="fa fa-floppy-o"></span> Guardar usuario
		</button>
</div>

<!-- FORMULARIO NUEVO USUARIO & VINCULAR USUARIO EXISTENTE -->
<div id="formularioVincularUsuario" style="display:none">
 	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-left">
            	<a data-toggle="collapse" href="#vincularUsuarioPanel" id="btn_vincularUsuarioPanel">Vincular usuario existente</a>
        </div>
      	<div id="vincularUsuarioPanel" class="panel-collapse" aria-expanded="true">
      		<div class="panel-body">
	            <div class="row form-group">
	                <div class="col-md-2">
	                    Usuario/Email
	                </div>                
	                <div class="col-md-10">
	                	<select id="select_usuarioExistenteParaVincular" data-placeholder="Selecciona un usuario existente..." class="form-control chosen-select"></select>
	                </div>
	            </div>
	            
				<h3>Permisos</h3>
	            <div class="row form-group">
					<div class="col-md-2"><input id="perm_menuPermisoContenidoWeb" type="checkbox" /> Contenido Web</div>
					<div class="col-md-2"><input id="perm_menuPermisoSecciones" type="checkbox" /> Secciones</div>
	            </div>
	            <div class="row form-group">
	                <div class="col-md-2"><input id="perm_menuPermisoConfiguracion" type="checkbox" /> Configuración</div>
	                <div class="col-md-2"><input id="perm_menuPermisoParametros" type="checkbox" /> Parametros</div>
	                <div class="col-md-2"><input id="perm_menuPermisoUsuarios" type="checkbox" /> Usuarios</div>
	                <div class="col-md-2"><input id="perm_menuPermisoCorreos" type="checkbox" /> Config Correo</div>        
	            </div>
	            <div class="row form-group">
	                <div class="col-md-2"><input id="perm_menuPermisoInmobiliaria" type="checkbox" /> Inmobiliaria</div>
	                <div class="col-md-2"><input id="perm_menuPermisoInmoApuntes" type="checkbox" /> Inmobiliaria Apuntes</div>
	                <div class="col-md-2"><input id="perm_menuPermisoInmoClientes" type="checkbox" /> Inmobiliaria Clientes</div>
	                <div class="col-md-2"><input id="perm_menuPermisoInmoInmuebles" type="checkbox" /> Inmobiliaria Inmuebles</div>
	                <div class="col-md-2"><input id="perm_menuPermisoInmoZonas" type="checkbox" /> Inmobiliaria Zona</div>
				</div>
	            
		  		<button type="button" id="botonGuardarVinculacionUsuario" class="btn btn-sm btn-primary pull-right">
		  			<span class="fa fa-floppy-o"></span> Guardar vinculación
				</button>
			</div>
      	</div>
	</div>
	
 	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-left">
            	<a data-toggle="collapse" href="#crearUsuarioPanel" id="btn_crearUsuarioPanel">Crear usuario nuevo y vincularlo</a>
        </div>
      	<div id="crearUsuarioPanel" class="panel-collapse collapse">
			<div class="panel-body">
	            <h3>Datos de acceso</h3>
	       		<input name="id" type="hidden" id="id" value="" />
	            <div class="row form-group">
	                <div class="col-md-2">
	                    Usuario/Email
	                </div>                
	                <div class="col-md-10">
	                    <input type="text" id="frm_usuarioEmail" value="" size="50" maxlength="80"  class="form-control"/>
	                </div>
	            </div>
				<div class="row form-group">
	                <div class="col-md-2">
	                    Contraseña
	                </div>
	                <div class="col-md-4">
	                    <input id="frm_usuarioPassword" type="password" value="" class="form-control" />
	                </div>
	                <div class="col-md-2">
	                    Confirmar contraseña
	                </div>
	                <div class="col-md-4">
	                    <input id="frm_usuarioPassword2" type="password" class="form-control" />
	                </div>
				</div>
				
				
				
				<h3>Datos informativos</h3>
				<div class="row form-group">
		            <div class="col-md-2">
		                Nombre
		            </div>                
		            <div class="col-md-5">
		                <input type="text" id="frm_usuarioNombre" value="" size="50" maxlength="150" class="form-control" />
		            </div>
					<div class="col-md-2">
		                NIF
		            </div>                
		            <div class="col-md-3">
		                <input type="text" id="frm_usuarioNIF" value="" size="25" maxlength="50" class="form-control" />
		            </div>                    
		        </div>
	            <div class="row form-group">
	                <div class="col-md-2">
	                    Direccion
	                </div>                
	                <div class="col-md-10">
	                    <input type="text" id="frm_usuarioDireccion" value="" class="form-control" />
	                </div>
		        </div>
	            <div class="row form-group">
	                <div class="col-md-2">
	                    CP
	                </div>                
	                <div class="col-md-1">
	                    <input type="text" id="frm_usuarioCP" value="" class="form-control" />
	                </div>
					<div class="col-md-2">
	                    Población
	                </div>                
	                <div class="col-md-3">
	                    <input type="text" id="frm_usuarioPoblacion" value="" class="form-control" />
	                </div>                    
					<div class="col-md-2">
	                    Provincia
	                </div>                
	                <div class="col-md-2">
	                    <input type="text" id="frm_usuarioProvincia" value="" size="25" maxlength="50" class="form-control" />
	                </div>
	            </div>                                                  
	            <div class="row form-group">
	                <div class="col-md-2">
	                    Teléfono 1
	                </div>                
	                <div class="col-md-4">
	                    <input type="text" id="frm_usuarioTelf1" value="" class="form-control" />
	                </div>
					<div class="col-md-2">
	                    Teléfono 2
	                </div>
	                <div class="col-md-4">
	                    <input type="text" id="frm_usuarioTelf2" value="" class="form-control" />
	                </div>
	            </div>
	            <div class="row form-group">
	                <div class="col-md-2">
	                    Comentario:
	                </div>                
	                <div class="col-md-10">
	                    <input type="text" id="frm_usuarioComentario" value="" class="form-control" />
	                </div>
	            </div>
				
				<h3>Permisos</h3>
	            <div class="row form-group">
					<div class="col-md-2"><input id="frm_menuPermisoContenidoWeb" type="checkbox" /> Contenido Web</div>
					<div class="col-md-2"><input id="frm_menuPermisoSecciones" type="checkbox" /> Secciones</div>
	            </div>
	            <div class="row form-group">
	                <div class="col-md-2"><input id="frm_menuPermisoConfiguracion" type="checkbox" /> Configuración</div>
	                <div class="col-md-2"><input id="frm_menuPermisoParametros" type="checkbox" /> Parametros</div>
	                <div class="col-md-2"><input id="frm_menuPermisoUsuarios" type="checkbox" /> Usuarios</div>
	                <div class="col-md-2"><input id="frm_menuPermisoCorreos" type="checkbox" /> Config Correo</div>        
	            </div>
	            <div class="row form-group">
	                <div class="col-md-2"><input id="frm_menuPermisoInmobiliaria" type="checkbox" /> Inmobiliaria</div>
	                <div class="col-md-2"><input id="frm_menuPermisoInmoApuntes" type="checkbox" /> Inmobiliaria Apuntes</div>
	                <div class="col-md-2"><input id="frm_menuPermisoInmoClientes" type="checkbox" /> Inmobiliaria Clientes</div>
	                <div class="col-md-2"><input id="frm_menuPermisoInmoInmuebles" type="checkbox" /> Inmobiliaria Inmuebles</div>
	                <div class="col-md-2"><input id="frm_menuPermisoInmoZonas" type="checkbox" /> Inmobiliaria Zona</div>
				</div>
				
		  		<button type="button" id="botonGuardarNuevoUsuarioYVincularlo" class="btn btn-sm btn-primary pull-right">
		  			<span class="fa fa-floppy-o"></span> Crear usuario y vincularlo
				</button>
				
			</div>
      	</div>
	</div>	
		    
	<button type="button" id="volverListadoUsuarios" class="btn btn-default btn-sm">
  		<span class="fa fa-arrow-left"></span> Volver
	</button>
</div>
<!-- FIN: FORMULARIO NUEVO USUARIO & VINCULAR USUARIO EXISTENTE -->

<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="./modulos/sitiosWeb/dk-logica.js"></script>
