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
	<input name="id" type="hidden" id="id" value="" />
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
                    <input type="password" id="clave" size="20" maxlength="20" class="form-control" /></div>
                <div class="col-lg-6">
                    Confirmaci&oacute;n<br />
                    <input type="password" id="clave2" size="20" maxlength="20" class="form-control" />
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
            <h3 class="panel-title text-left">Permiso y acceso</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 form-group">Grupo de Permiso<br />
                    <select class="form-control" name="grupo" id="grupo">
                        <option value="administrador">Administrador</option>
                        <option value="editor">Editor</option>
                        <option value="colaborador">Colaborador</option>
                        <option value="suscriptor">Suscriptor</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div id="permisos" class="col-lg-12">
                </div>
            </div>
        </div>
    </div>
    <!-- fin accesos -->
    
	<div class="row">
		<div class="col-lg-9"></div>
		<div class="col-lg-1"><input id="botonGuarda" type="button" value="Guardar Documento" class="btn green" /></div>
	</div>
</div>

<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="./modulos/usuarios/dk-logica.js"></script>
