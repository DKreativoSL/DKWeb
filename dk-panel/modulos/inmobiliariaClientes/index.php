<?php
session_start();
?>

<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen-bootstrap.css" rel="stylesheet" type="text/css"/>
<style>
	.input-small {
		width: 300px !important;
	}
</style>

<div id="listaClientes">
	<div id="filtrosClientes">

		<div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Filtros de b&uacute;squeda</h3>
            </div>
            <div class="panel-body">
            	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
		            NIF <br />
					<input name="filtro_cliente_nif" type="text" id="filtro_cliente_nif" maxlength="20" class="form-control"  />
                	Estado<br />
					<select name="filtro_cliente_lstEstado" id="filtro_cliente_lstEstado"  class="form-control" >
						<option value="Alta">Alta</option>
						<option value="Baja">Baja</option>
						<option value="Todos">Todos</option>
					</select>                    
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                	Nombre<br />
					<input name="filtro_cliente_nombre" type="text" id="filtro_cliente_nombre" class="form-control"  />
                </div>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                	Tel&eacute;fono<br />
                	<input name="filtro_cliente_tlf" type="text" id="filtro_cliente_tlf" maxlength="15" class="form-control"  />
				</div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                	Fecha de Alta <br />
                    <input name="filtro_cliente_desde" type="text" id="filtro_cliente_desde" maxlength="12" placeholder="Desde..." class="form-control"  />
                    <br />
                    <input name="filtro_cliente_hasta" type="text" id="filtro_cliente_hasta" maxlength="12" placeholder="Hasta..." class="form-control"  />
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                    Fecha de Baja <br />
                    <input name="filtro_cliente_desdebaja" type="text" id="filtro_cliente_desdebaja" maxlength="12" placeholder="Desde..." class="form-control"  />
                    <br />
                    <input name="filtro_cliente_hastabaja" type="text" id="filtro_cliente_hastabaja" maxlength="12" placeholder="Hasta..." class="form-control"  />
                </div>
                <div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
					Tipo Cliente<br />
					<select name="filtro_cliente_lstTipo" id="filtro_cliente_lstTipo"  class="form-control" >
						<option value="Todos">Todos</option>
						<option value="0">Comprador</option>          
						<option value="1">Vendedor</option>
						<option value="2">Compra/Vende</option>
						<option value="3">Alquiler</option>          
					</select>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
					Usuario <br />
					<select name="filtro_cliente_lstUsuario" id="filtro_cliente_lstUsuario"  class="form-control" >
						<option value="Todos">Todos</option>
					</select>
				</div>
			</div>
		</div>
        <div align="right">
			<button type="button" class="btn btn-success" id="filtrarClientes">Buscar</button>
        </div>
	</div>
	
	<table id="tablaRegistrosClientes" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="5%">id</th>
                <th width="20%">Cliente</th>
                <th width="10%">Tel&eacute;fonos</th>
                <th width="10%">Fecha de Alta y Baja</th>
                <th width="10%">Usuario</th>
                <th width="10%">Tipo Cliente</th>
                <th width="10%" align="right">
                	<a id="botonNuevo" href="#" title="A&ntilde;adir un nuevo cliente" class="botonNuevo">
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
	<form action="guarda.php" method="post" name="formulario">
	<input name="id" type="hidden" id="id"/>
		
		
	<div class="panel panel-default">
        <div class="panel-heading">                   
            <h3 class="panel-title text-left">Datos de Contacto</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    Nombre<br />
                    <input name="nombre" class="form-control" type="text" id="nombre" size="50" maxlength="150" />
                </div>
                <div class="col-lg-6">
                    Tlfno 1<br />
                    <input name="tlf1" class="form-control" type="text" id="tlf1" maxlength="15" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    Email<br />
                    <input name="email" class="form-control" type="text" id="email" size="50" maxlength="80" />
                </div>
                <div class="col-lg-6">
                    Tlfno 2<br />
                    <input name="tlf2" class="form-control" type="text" id="tlf2" size="25" maxlength="15" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    Fuente de entrada <br />
                    <input name="fuente" class="form-control" type="text" id="fuente" size="50" maxlength="80" />
                </div>
                <div class="col-lg-6">
                    Fax<br />
                    <input name="fax" class="form-control" type="text" id="fax" size="25" maxlength="15" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    Notas<br />
                    <textarea class="form-control" name="comentarios" cols="100" rows="6" id="comentarios"></textarea>
                </div>
            </div>
        </div>
	</div> <!-- FIN datos de contacto -->
    <div class="panel panel-default">
        <div class="panel-heading">                   
            <h3 class="panel-title text-left">Otros Datos</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-4">
                    NIF<br />
                    <input name="nif" class="form-control" type="text" id="nif" size="50" maxlength="80" />
                </div>
                <div class="col-lg-4">
                    Usuario<br />
					<select id="selectUsuario" data-placeholder="Selecciona un usuario..." class="chosen-select form-control">
						<option>Cargando...</option>
					</select>
                </div>
                <div class="col-lg-4">
                    Tipo<br />
                    <select name="tipoc" id="tipoc" class="form-control">
                        <option value="0">Comprador</option>
                        <option value="1">Vendedor</option>
                        <option value="2">Comprador/Vendedor</option>
                        <option value="3">Alquiler</option>          
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    Direcci&oacute;n<br />
                    <textarea class="form-control" name="direccion" cols="50" rows="2" id="direccion"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    Poblaci&oacute;n <br />
                    <input name="poblacion" class="form-control" type="text" id="poblacion" size="25" maxlength="50" />
                </div>
                <div class="col-lg-4">
                    Provincia
                    <input name="provincia" class="form-control" type="text" id="provincia" size="25" maxlength="50" />
                </div>
                <div class="col-lg-4">
                    C&oacute;digo postal
                    <input name="cpostal" class="form-control" type="text" id="cpostal" size="25" maxlength="15" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    Fecha Alta <br />
                    <input name="fechaAlta" readonly="readonly" class="form-control" type="text" id="fechaAlta" size="25" maxlength="50" />
                </div>
                <div class="col-lg-4">
                    Fecha Baja <br />
                    <input name="fecha" class="form-control" type="text" id="fechaBaja" size="25" maxlength="50" />
                </div>
                <div class="col-lg-4">
                    Motivo de baja<br />
                    <input name="bajamotivo" type="text" id="bajamotivo" size="25" maxlength="50" class="form-control" />
                </div>
            </div>
            
	        <div class="row margin-top-15">
	            <div class="col-lg-12">
	                Documento
	                <a href="#" onclick="cambiarDocumentoCliente()">Cambiar</a>
	                <a href="#" onclick="verDocumentoCliente()">Ver</a>
	                <br />
	                <img src="#" id="imagenCliente" alt="Imagen-del-contrato" width="160" height="160" longdesc="Imagen-del-contrato" />
	            </div>
	        </div>
            
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <input id="botonGuarda" type="button" value="Guardar Cliente" class="btn btn-success" />
        </div>
    </div>
</form>

	<div class="panel panel-default margin-top-15">
        <div class="panel-heading">                   
            <h3 class="panel-title text-left">Apuntes</h3>
        </div>
        <div class="panel-body">
            <div id="cliente_apuntes"></div>
        </div>
    </div>
    
	<div class="panel panel-default">
        <div class="panel-heading">                   
            <h3 class="panel-title text-left">Inmuebles</h3>
        </div>
        <div class="panel-body">
            <div id="cliente_inmuebles"></div>
        </div>
	</div>
    
</div>

<script type="text/javascript" src="../assets/global/plugins/jquery-chosen/chosen.jquery.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>

<script src="../assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js" type="text/javascript"></script>
<script type="text/javascript">
<?php
	echo 'var idUsuario = '.$_SESSION['idUsuario'].';';
?>
</script>
<script type="text/javascript">
	$("#fechaBaja").datetimepicker({
		format: 'dd-mm-yyyy',
		todayBtn: true,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});
</script>

<script src="./modulos/inmobiliariaClientes/dk-logica.js"></script>
