<?php
	session_start();
	include_once("../../conexion.php");
	include_once("../../funciones.php");
	
	$css = getCSS($conexion, $_SESSION['sitioWeb']);
?>
<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen-bootstrap.css" rel="stylesheet" type="text/css"/>

<div id="popupApuntes" class="modal fade" role="dialog" style="display:none">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Apuntes</h4>
  				</div>
  			<div class="modal-body" id="apuntes_body">
  				
  				
  				
  			</div>
  			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="guardarPopupApunte">Guardar Apunte</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  			</div>
		</div>
	</div>
</div>
<div id="listaVisitas">
	
	<div id="filtrosApuntes">
		<div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Filtros de b&uacute;squeda</h3>
            </div>
            <div class="panel-body">
            	<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                	Cliente <br />
					<input name="filtro_apunte_cliente" type="text" id="filtro_apunte_cliente" size="20" maxlength="20" class="form-control"/>
                    Ref. Inmueble <br />
					<input name="filtro_apunte_inmueble" type="text" id="filtro_apunte_inmueble" size="30" class="form-control"/>
				</div>
               	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                	Fecha del Apunte<br />
                    <input name="filtro_apunte_desde" type="text" id="filtro_apunte_desde" size="12" maxlength="12" placeholder="Desde..." class="form-control"/><br />
                    <input name="filtro_apunte_hasta" type="text" id="filtro_apunte_hasta" size="12" maxlength="12" placeholder="Hasta..." class="form-control"/>
				</div>
            	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                	Fecha del Aviso<br />
                	<input name="filtro_apunte_desdeaviso" type="text" id="filtro_apunte_desdeaviso" size="12" maxlength="12" placeholder="Desde..." class="form-control"/><br />
					<input name="filtro_apunte_hastaaviso" type="text" id="filtro_apunte_hastaaviso" size="12" maxlength="12" placeholder="Hasta..." class="form-control"/>
				</div>
				<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                	Tipo<br />
					<select name="filtro_apunte_cbApunte" id="filtro_apunte_cbApunte" class="form-control">
						<option value="Todos">Todos</option>
						<option value="Llamada Entrante">Llamada Entrante</option>
						<option value="Llamada Saliente">Llamada Saliente</option>
						<option value="Correo Entrante">Correo Entrante</option>
						<option value="Correo Saliente">Correo Saliente</option>
						<option value="Cita">Cita</option>
						<option value="Aviso">Aviso</option>
						<option value="Auto">Autom�tico</option>          
					</select>
					Usuario <br />
					<select name="filtro_apunte_lstUsuario" id="filtro_apunte_lstUsuario" class="form-control">
						<option value="Todos">Todos</option>
					</select>
				</div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">					
                    Ordenar por<br />
                    <select name="filtro_apunte_lstOrden" id="filtro_apunte_lstOrden" class="form-control">
                        <option value="fechaaviso">Fecha Cita/Aviso</option>
                        <option value="fecha">Fecha del Apunte</option>
                        <option value="inmediato">Cita/Aviso Inmediato</option>
                    </select>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12"><br />
                    <input type="checkbox" value="checked" name="filtro_apunte_chSeguimiento" id="filtro_apunte_chSeguimiento" />
                    Seguimiento 
                    <input name="filtro_apunte_chComentarios" type="checkbox" id="filtro_apunte_chComentarios" value="checked" />
                    Comentarios
                </div>
			</div>
		</div>
        <div align="right">
            <button type="button" class="btn btn-success" id="filtrarApuntes">Buscar</button>
        </div>
	</div>
	
	<table id="tablaRegistrosVisitas" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="5%">id</th>
                <th width="20%">Cliente</th>
                <th width="10%">Tipo</th>
                <th width="10%">Creado</th>
                <th width="10%">Cita/Aviso</th>
                <th width="15%">Inmueble</th>
                <th width="15%">Comentario</th>
                <th width="10%">Usuario</th>
                <th width="10%" align="right">
                	<a id="botonNuevoVisita" href="#" title="A&ntilde;adir nuevo apunte" class="botonNuevo">
                		<i class="fa fa-plus-circle fa-2x"></i>
            		</a>
        		</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
</div>

<div id="camposFormularioVisitas" style="display:none">
	<form action="guarda.php" method="post" name="formulario">
		<input name="apuntes_txtId" type="hidden" id="apuntes_txtId"/>
		<input name="apuntes_txtPropId" type="hidden" id="apuntes_txtPropId"/>
		
		
        <div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Datos del Cliente</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        Nombre<br />
						<select id="apuntes_selectPropietario" data-placeholder="Selecciona un propietario..." class="chosen-select form-control">
							<option>Cargando...</option>
						</select>
                        <!-- <input name="apuntes_txtPropNombre" readonly="readonly" class="form-control" type="text" id="apuntes_txtPropNombre" size="50" maxlength="150" /> -->
                    </div>
                    <!--
                    <div class="col-lg-6">
                        Telefono<br />
                        <input name="apuntes_txtPropTelf" readonly="readonly" class="form-control" type="text" id="apuntes_txtPropTelf" size="50" maxlength="150" />
                    </div>
                    -->
                </div>
            </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Datos del Inmueble</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        Referencia<br />
						<select id="apuntes_selectInmueble" data-placeholder="Selecciona un inmueble..." class="chosen-select form-control">
							<option>Cargando...</option>
						</select>
                        <!-- <input name="apuntes_txtInmoRef" readonly="readonly" class="form-control" type="text" id="tapuntes_xtInmoRef" size="50" maxlength="80" /> -->
                    </div>
                    <!--
                    <div class="col-lg-6">
                        Zona<br />
                        <input name="apuntes_txtInmoZona" readonly="readonly" class="form-control" type="text" id="apuntes_txtInmoZona" size="50" maxlength="80" />
                    </div>
                    -->
                </div>
        	</div>
		</div> <!-- FIN Panel Datos inmueble -->
        
		<div class="panel panel-default">
        	<div class="panel-heading">                   
                <h3 class="panel-title text-left">Otros Datos</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        Tipo apunte<br />
                        <select name="apuntes_cbApunte" class="form-control" id="apuntes_cbApunte" onchange="MuestraFechaAviso(this)">
                            <option value="Llamada Entrante">Llamada Entrante</option>                
                            <option value="Llamada Saliente">Llamada Saliente</option>
                            <option value="Correo Entrante">Correo Entrante</option>
                            <option value="Correo Saliente">Correo Saliente</option>                
                            <option value="Cita">Cita</option>                       
                            <option value="Aviso">Aviso</option>                                 
                        </select>
                    </div>
                    <div class="col-lg-6">
                        Usuario<br />
						<select id="apuntes_selectUsuario" data-placeholder="Selecciona un usuario..." class="chosen-select form-control">
							<option>Cargando...</option>
						</select>
						<!--
                        <input name="apuntes_txtUsuaId" type="hidden" id="apuntes_txtUsuaId" size="50" maxlength="80" />
                        <input name="apuntes_txtUsuaNombre" readonly="readonly" class="form-control" type="text" id="apuntes_txtUsuaNombre" size="50" maxlength="80" />
                       -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        Comentario
                        <textarea name="apuntes_comentarios" class="form-control" id="apuntes_comentarios" size="50" maxlength="80" /></textarea>
                    </div>
                </div>
			</div>
		</div> <!-- FIN panel OTRO -->
        
		<div class="row">
			<div class="col-lg-12">
				<input id="botonGuardaVisita" type="button" value="Guardar apunte" class="btn btn-success" />
			</div>
		</div>
	</form>
</div>

<script type="text/javascript" src="../assets/global/plugins/jquery-chosen/chosen.jquery.js"></script>

<!-- <script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script> -->
<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>
<script type="text/javascript">
	<?php
		echo 'var idUsuario = '.$_SESSION['idUsuario'].';';
		if (isset($_GET['idCliente'])) {
			echo 'var idCliente = ' . $_GET['idCliente'] . ';';
		} else {
			echo 'var idCliente = 0;';
		}
	?>
</script>
<script src="./modulos/inmobiliariaApuntes/dk-logica.js"></script>
