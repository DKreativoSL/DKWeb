<link rel="stylesheet" type="text/css" href="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

<div id="listaRegistros">
	<table width="12%" align="center" cellspacing="0" class="table table-hover" id="tablaRegistros" role="grid">
        <thead>
            <tr>
                <th width="20%">nombre</th>
                <th width="30%">email</th>
                <th width="10%">tlf1</th>
                <th width="15%">fechaAlta</th>                
                <th width="15%">fechaBaja</th>
                <th width="10%" align="right">
                	<a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo usuario" class="botonNuevo">
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
 	<div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-left">Datos de acceso</h3>
        </div>
        <div class="panel-body"> 
       		<input name="id" type="hidden" id="id" value="" />
	            <div class="row form-group">
                    <div class="col-md-2">
                        Usuario/Email
                    </div>                
                    <div class="col-md-10">
                        <input type="text" id="email" value="" size="50" maxlength="80"  class="form-control"/>
                    </div>
                </div>
				<div class="row form-group">
                    <div class="col-md-2">
                        Contraseña
                    </div>
                    <div class="col-md-4">
                        <input id="clave" type="password" value="" class="form-control" />
                    </div>
                    <div class="col-md-2">
                        Confirmar contraseña
                    </div>
                    <div class="col-md-4">
                        <input id="clave2" type="password" class="form-control" />
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
                        <input type="text" id="nombre" value="" size="50" maxlength="150" class="form-control" />
                    </div>
					<div class="col-md-2">
                        NIF
                    </div>                
                    <div class="col-md-3">
                        <input type="text" id="nif" value="" size="25" maxlength="50" class="form-control" />
                    </div>                    
                </div>
	            <div class="row form-group">
	                <div class="col-md-2">
                        Direccion
                    </div>                
                    <div class="col-md-10">
                        <input type="text" id="direccion" value="" class="form-control" />
                    </div>
    	        </div>
	            <div class="row form-group">
                    <div class="col-md-2">
                        CP
                    </div>                
                    <div class="col-md-1">
                        <input type="text" id="cp" value="" class="form-control" />
                    </div>
					<div class="col-md-2">
                        Población
                    </div>                
                    <div class="col-md-3">
                        <input type="text" id="poblacion" value="" class="form-control" />
                    </div>                    
					<div class="col-md-2">
                        Provincia
                    </div>                
                    <div class="col-md-2">
                        <input type="text" id="provincia" value="" size="25" maxlength="50" class="form-control" />
                    </div>
                </div>                                                  
	            <div class="row form-group">
                    <div class="col-md-2">
                        Teléfono 1
                    </div>                
                    <div class="col-md-4">
                        <input type="text" id="tlf1" value="" class="form-control" />
                    </div>
					<div class="col-md-2">
                        Teléfono 2
                    </div>
                    <div class="col-md-4">
                        <input type="text" id="tlf2" value="" class="form-control" />
                    </div>
                </div>
	            <div class="row form-group">
                    <div class="col-md-2">
                        Comentario:
                    </div>                
                    <div class="col-md-10">
                        <input type="text" id="sobreti" value="" class="form-control" />
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
					<div class="col-md-2"><input id="menuPermisoContenidoWeb" type="checkbox" /> Contenido Web</div>
					<div class="col-md-2"><input id="menuPermisoSecciones" type="checkbox" /> Secciones</div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2"><input id="menuPermisoConfiguracion" type="checkbox" /> Configuración</div>
                    <div class="col-md-2"><input id="menuPermisoParametros" type="checkbox" /> Parametros</div>
                    <div class="col-md-2"><input id="menuPermisoUsuarios" type="checkbox" /> Usuarios</div>
                    <div class="col-md-2"><input id="menuPermisoCorreos" type="checkbox" /> Config Correo</div>        
                </div>
                <div class="row form-group" hidden="true">
                    <div class="col-md-2"><input id="menuPermisoInmobiliaria" type="checkbox" /> Inmobiliaria</div>
                    <div class="col-md-2"><input id="menuPermisoInmoApuntes" type="checkbox" /> Inmobiliaria Apuntes</div>
                    <div class="col-md-2"><input id="menuPermisoInmoClientes" type="checkbox" /> Inmobiliaria Clientes</div>
                    <div class="col-md-2"><input id="menuPermisoInmoInmuebles" type="checkbox" /> Inmobiliaria Inmuebles</div>
                    <div class="col-md-2"><input id="menuPermisoInmoZonas" type="checkbox" /> Inmobiliaria Zona</div>
				</div>
            </div>
		</div>                
        <div class="col-md-12" align="right">
            <input id="botonGuarda" type="button" value="Guardar Documento" class="btn green" />
        </div>
		
</div>

<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="./modulos/usuariosAdmin/dk-logica.js"></script>
