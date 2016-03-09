<link rel="stylesheet" type="text/css" href="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>


<div id="camposFormulario">
	<input name="id" type="hidden" id="id" value="" />
    <div class="panel panel-default">
        <div class="panel-heading">                   
            <h3 class="panel-title text-left">Datos del usuario</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-8">
                    Nombre *<br />
                    <input type="text" id="nombreCliente" value="" size="50" maxlength="150" class="form-control" />
                </div>
                <div class="col-lg-4">
                    NIF<br />
                    <input type="text" id="nif" value="" size="25" maxlength="50" class="form-control" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    Usuario/Email *<br />
                    <input type="text" id="email" value="" size="50" maxlength="80"  class="form-control"/>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    Contraseña *<br />
                    <input type="password" id="clave" size="20" maxlength="20" class="form-control" /></div>
                <div class="col-lg-6">
                    Confirmaci&oacute;n *<br />
                    <input type="password" id="clave2" size="20" maxlength="20" class="form-control" />
                </div>
            </div>
        </div>
    </div>
    <!-- fin acceso -->
    
    <div class="panel panel-default">
        <div class="panel-heading">                   
            <h3 class="panel-title text-left">Datos de la web</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-8">
                    Nombre del sitio<br />
                    <input type="text" id="nombreWebsite" value="" size="50" maxlength="150" class="form-control" />
                </div>
                <div class="col-lg-4">
                    Dominio *<br />
                    <input type="text" id="dominio" value="" size="25" maxlength="50" class="form-control" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    Descripci&oacute;n<br />
                    <textarea cols="50" rows="2" id="descripcion" class="form-control"></textarea>
                </div>
            </div>
    	</div>
	</div>
    <!-- fin datos personales del usuario -->
    
	<div class="row">
		<div class="col-lg-12"><input id="botonCrearWebsite" type="button" value="Crear website" class="btn btn-success pull-right" /></div>
	</div>
</div>

<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>

<script src="./modulos/crearWebsite/dk-logica.js"></script>
