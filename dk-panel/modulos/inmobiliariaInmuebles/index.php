<?php
session_start();
?>

<link rel="stylesheet" type="text/css" href="./../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<link href="./../assets/global/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen.css" rel="stylesheet" type="text/css"/>
<link href="./../assets/global/plugins/jquery-chosen/chosen-bootstrap.css" rel="stylesheet" type="text/css"/>

<div id="cambiarUsuario" class="modal fade" role="dialog" style="display:none; z-index:1000">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Cambiar usuario</h4>
  				</div>
  			<div class="modal-body">
				<p>
  					<select id="selectCambiarUsuario" class="form-control">
  						<option>Cargando...</option>
  					</select>
				</p>
  			</div>
  			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="cambiarUsuarioButton" data-dismiss="modal">Guardar</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  			</div>
		</div>
	</div>
</div>

<div id="cambiarPropietario" class="modal fade" role="dialog" style="display:none; z-index:1000">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Cambiar propietario</h4>
  				</div>
  			<div class="modal-body">
				<table id="tablaRegistrosPropietarios" class="table table-hover" cellspacing="0" align="center" role="grid">
			        <thead>
			            <tr>
			                <th width="5%">id</th>
			                <th width="20%">Nombre</th>
			                <th width="10%">Tel&eacute;fonos</th>
			                <th width="10%">NIF</th>
			                <th width="10%">Email</th>
			                <th width="10%" align="right"></th>
			            </tr>
			        </thead>
			        <tbody>
			        </tbody>  
				</table>
  			</div>
  			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  			</div>
		</div>
	</div>
</div>

<div id="popupInmobiliaria" class="modal fade" role="dialog" style="display:none; z-index:10">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
  			<div class="modal-header">
    			<button type="button" class="close" data-dismiss="modal">&times;</button>
    				<h4 class="modal-title">Inmuebles</h4>
  				</div>
  			<div class="modal-body" id="inmuebles_body">
  				
  				
  				
  			</div>
  			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="guardarPopupInmueble">Guardar Inmueble</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
  			</div>
		</div>
	</div>
</div>

<div id="listaInmuebles">
	<div id="filtrosInmuebles">
		 <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">                   
                    <h3 class="panel-title text-left">Filtros marginales</h3>
                </div>
                <div class="panel-body">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        Precio <br />
                        <input name="preciodesde" type="text" id="preciodesde" class="form-control" size="6" placeholder="Desde..." /><br />
                        <input name="preciohasta" type="text" id="preciohasta" class="form-control" size="6" placeholder="Hasta..."/>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        Metros <br />
                        <input name="metrosdesde" type="text" id="metrosdesde" class="form-control" size="5" maxlength="10" placeholder="Desde..."/><br />
                        <input name="metroshasta" type="text" id="metroshasta" class="form-control" size="5" maxlength="10" placeholder="Hasta..."/>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        Habitaciones <br />
                        <input name="habitadesde" type="text" id="habitadesde" class="form-control" size="3" maxlength="3" placeholder="Desde..."/><br />
                        <input name="habitahasta" type="text" id="habitahasta" class="form-control" size="3" maxlength="3" placeholder="Hasta..."/>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        Ba&ntilde;os <br />
                        <input name="banosdesde" type="text" id="banosdesde" class="form-control" size="3" maxlength="3" placeholder="Desde..."/><br />
                        <input name="banoshasta" type="text" id="banoshasta" class="form-control" size="3" maxlength="3" placeholder="Hasta..."/>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        Fecha Alta <br />
                        <input name="desde" type="text" id="desde" class="form-control" size="8" maxlength="10" placeholder="Desde..."/><br />
                        <input name="hasta" type="text" id="hasta" class="form-control" size="8" maxlength="10" placeholder="Hasta..."/>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        Fecha Baja <br />
                        <input name="desdebaja" type="text" id="desdebaja" class="form-control" size="8" maxlength="10" placeholder="Desde..."/><br />
                        <input name="hastabaja" type="text" id="hastabaja" class="form-control" size="8" maxlength="10" placeholder="Hasta..."/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">                   
                        <h3 class="panel-title text-left">Extras</h3>
                    </div>
                    <div class="panel-body">        
                        <input name="chAscensor" type="checkbox" id="chAscensor" value="checked"> Ascensor<br />
                        <input name="chGaraje" type="checkbox" id="chGaraje" value="checked"> Garaje<br />
                        <input name="chPiscina" type="checkbox" id="chPiscina" value="checked"> Piscina
                    </div>
                </div>
            </div>
        	<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12">            
                <div class="panel panel-default">
                    <div class="panel-heading">                   
                        <h3 class="panel-title text-left">Tipo</h3>
                    </div>
                    <div class="panel-body">
                        <input name="chAlquiler" type="checkbox" id="chAlquiler" value="checked"> Alquiler<br />
                        <input name="chVenta" type="checkbox" id="chVenta" value="checked"> Venta<br />
                        <input name="chAlquilerCompra" type="checkbox" id="chAlquilerCompra" value="checked"> Alq. Opc. Compra<br />
                        <input name="chTraspaso" type="checkbox" id="chTraspaso" value="checked"> Traspaso<br />
                        <input name="chPromocion" type="checkbox" id="chPromocion" value="checked"> Promoci&oacute;n
                    </div>
                </div>
			</div>
           	<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">                   
                        <h3 class="panel-title text-left">Otros</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            Zona<br />
                            <select name="lstZona" id="lstZona" class="form-control" >
                                <option value="Todas">Todas</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            Tipo<br />
                            <select name="lstTipo" id="lstTipo" class="form-control">
                                <option>Todos</option>
                                <option value="Apartamento">Apartamento</option>
                                <option value="Atico">&Aacute;tico</option>
                                <option value="Casa">Casa</option>
                                <option value="Chalet">Chalet</option>
                                <option value="Cochera">Cochera</option>
                                <option value="Duplex">Duplex</option>
                                <option value="Local">Local</option>
                                <option value="Parcela">Parcela</option>
                                <option value="Piso">Piso</option>
                                <option value="Terreno">Terreno</option>
                            </select>	                
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">                    
                            Estado<br />
                            <select name="cbEstado" id="cbEstado" class="form-control" >
                                <option value="Todos">Todos</option>
                                <option value="Ofertado">Ofertado</option>
                                <option value="Borrado">Borrado</option>
                                <option value="Alquilado">Alquilado</option>
                                <option value="Vendido">Vendido</option>
                                <option value="Reservado">Reservado</option>
                            </select>
                        </div>                    
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            Usuario<br />
                            <select name="lstUsuario" id="lstUsuario" class="form-control" >
                                <option value="Todos">Todos</option>
                            </select>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            Propietario<br />
                            <input name="propietario" type="text" class="form-control" id="propietario">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">                    
                            Tlf Propietario<br />
                            <input name="tlfpropietario" type="text" class="form-control" id="tlfpropietario">
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                            Planta<br />
                            <input name="planta" type="text" class="form-control" id="planta">
                        </div>
                    </div>                        				
                </div> <!-- fin otros -->
			</div>
        </div>
        <div class="row">
            <button type="button" class="btn btn-success" id="filtrarInmuebles">Buscar</button>
        </div>
	</div><!-- fin filtros-->
	<table id="tablaRegistrosInmuebles" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>
                <th width="10%">Ref</th>
                <th width="20%">Inmueble</th>
                <th width="5%">Fecha Alta</th>
                <th width="5%">Fecha Mod.</th>
                <th width="10%">Zona</th>
                <th width="20%">Direcci&oacute;n</th>
                <th width="10%">Precio</th>
                <th width="10%">Usuario</th>
                <th width="10%" align="right">
                	<a id="botonNuevoInmueble" href="#" title="A&ntilde;adir un nuevo inmueble" class="botonNuevo">
                		<i class="fa fa-plus-circle fa-2x"></i>
            		</a>
        		</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
	</table>
</div>

<div id="camposFormularioInmuebles">	
	<form action="#" method="post" name="formulario">
		<input name="inmueble_id" type="hidden" id="inmueble_id"/>
		
		<div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Datos del inmueble</h3>
            </div>
			<div class="panel-body">
            	<div class="row">
                    <div class="col-lg-4">
                        Referencia<br />
                        <input name="inmueble_txtRef" class="form-control" type="text" id="inmueble_txtRef" size="50" maxlength="150" />
                    </div>                
                    <div class="col-lg-4">
                        Inmueble <br />
                        <select name="inmueble_lstinmueble" class="form-control" id="inmueble_lstinmueble" onchange="CambiaInmueble()">
                            <option value="apartamento">Apartamento</option>
                            <option value="atico">&Aacute;tico</option>
                            <option value="casa">Casa</option>
                            <option value="chalet">Chalet</option>
                            <option value="cochera">Cochera</option>
                            <option value="duplex">Duplex</option>                  
                            <option value="local">Local</option>
                            <option value="parcela">Parcela</option>
                            <option value="piso">Piso</option>
                            <option value="terreno">Terreno</option>
                        </select>
                    </div>			           
                    <div class="col-lg-4">				
                        Fecha Alta<br />
                        <input name="inmueble_txtFechaalta" type="text" class="form-control" id="inmueble_txtFechaalta" size="10" maxlength="10" />
                    </div>
				</div>
	            <div class="row">
                	<div class="col-lg-6">
						Direcci&oacute;n <br />
                        <input name="inmueble_txtDireccion" type="text" class="form-control" id="inmueble_txtDireccion" size="35" maxlength="35" />
                    </div>
	                <div class="col-lg-6">
                            Zona <br />
                            <select name="inmueble_zona" id="inmueble_zona" class="form-control">
                                <option>Cargando...</option>
                            </select>
                    </div>
                </div>
	            <div class="row">
	                <div class="col-lg-4">
                        Portal<br />
                        <input name="inmueble_txtPortal" class="form-control" type="text" id="inmueble_txtPortal" size="50" maxlength="80" />
                    </div>
	                <div class="col-lg-4">
                        Planta <br />
                        <input name="inmueble_txtPlanta" class="form-control" type="text" id="inmueble_txtPlanta" size="25" maxlength="15" />
                    </div>
	                <div class="col-lg-4">
    	                Letra <br />
                        <input name="inmueble_txtLetra" class="form-control" type="text" id="inmueble_txtLetra" size="25" maxlength="15" />
                    </div>
                </div>
	            <div class="row">
	                <div class="col-lg-6">
    	                Poblaci&oacute;n<br />
                        <input name="inmueble_txtPoblacion" class="form-control" type="text" id="inmueble_txtPoblacion" size="50" maxlength="80" />
                    </div>
	                <div class="col-lg-6">    
                        Provincia<br />
                        <input name="inmueble_txtProvincia" class="form-control" type="text" id="inmueble_txtProvincia" size="15" maxlength="15"/>
                    </div>
                </div>
	            <div class="row">
	                <div class="col-lg-6">
                        Propietario
                        <a href="#" data-toggle="modal" data-target="#cambiarPropietario" onclick="cambiarPropietarioPopup()">Cambiar</a> <br />
						<select id="inmueble_selectPropietario" data-placeholder="Selecciona un propietario..." class="chosen-select form-control">
							<option>Cargando...</option>
						</select>
                        <!-- <input name="inmueble_txtPropNombre" readonly="readonly" type="text" id="inmueble_txtPropNombre" size="35" maxlength="15" class="form-control"/> -->
                    </div>
	                <div class="col-lg-6">
                        Usuario 
                        <!-- <a href="#" onclick="cambiarUsuarioPopup();" data-toggle="modal" data-target="#cambiarUsuario">Cambiar</a> -->
						<br>
						<select id="inmueble_selectUsuario" data-placeholder="Selecciona un usuario..." class="chosen-select form-control">
							<option>Cargando...</option>
						</select>
                        <!-- <input name="inmueble_txtUsuario" readonly="readonly" type="text" id="inmueble_txtUsuario" size="35" maxlength="15" class="form-control"/> -->
                    </div>
                </div>
	            <div class="row">
	                <div class="col-lg-12">
                        Caracteristicas<br />
                        <textarea name="inmueble_txtCaracteristicas" cols="50" rows="2" id="inmueble_txtCaracteristicas" class="form-control"></textarea>
                    </div>
                </div>
            	<div class="row">
	                <div class="col-lg-3">
                        <input name="inmueble_chEscaparateWeb" type="checkbox" id="inmueble_chEscaparateWeb" value="checked"/>
                        Publicar en la web
                    </div>
	                <div class="col-lg-3">
                        <input name="inmueble_chEscaparate" type="checkbox" id="inmueble_chEscaparate" value="checked"/>
                        Publicar en el escaparate
                    </div>
                </div>
				<div class="row">
					<div class="col-lg-3">
						<input name="inmueble_chAlquiler" type="checkbox" id="inmueble_chAlquiler" value="checked" />
						Alquiler
					</div>
					<div class="col-lg-3">
						<input name="inmueble_chVenta" type="checkbox" id="inmueble_chVenta" value="checked"/>
						Venta
					</div>
					<div class="col-lg-3">
						<input name="inmueble_chPromocion" type="checkbox" id="inmueble_chPromocion" value="checked"/>
						Promoci&oacute;n
					</div>
					<div class="col-lg-3">
						<input name="inmueble_chTraspaso" type="checkbox" id="inmueble_chTraspaso" value="checked"/>
						Traspaso
					</div>
				</div>
				<div class="row">
					<div class="col-lg-3">
						<input name="inmueble_chAlquilerCompra" type="checkbox" id="inmueble_chAlquilerCompra" value="checked"/>
						Alquiler opci&oacute;n Compra
					</div>
					<div class="col-lg-3">
						<input name="inmueble_chCartel" type="checkbox" id="inmueble_chCartel" value="checked"/>
						Cartel colocado
					</div>
					<div class="col-lg-3">
						<input name="inmueble_chLlaves" type="checkbox" id="inmueble_chLlaves" value="checked"/>
						Llaves disponibles
					</div>                
				</div> 
			</div>
        </div>     <!-- FIN DATOS INMUEBLE -->
		<div class="panel panel-default inputs">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Superficies</h3>
            </div>
			<div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                    	Parcela<br />
                    	<input name="inmueble_txtParcela" type="text" id="inmueble_txtParcela"size="10" maxlength="10" class="form-control"/>        
                    </div>
                    <div class="col-lg-6">
                        Tipo<br />
                        <select name="inmueble_cbTipoTerreno" id="inmueble_cbTipoTerreno" class="form-control" >
                           <option value="Urbano" >Urbano</option>
                           <option value="Rustico">Rustico</option>
                        </select>
                    </div>
                    <div class="col-lg-6">
                     Acceso<br />
                     <select name="inmueble_cbAcceso" id="inmueble_cbAcceso" class="form-control">
                        <option value="Calle Asfalto">Calle Asfalto</option>
                        <option value="Camino">Camino</option>
                     </select>
                    </div>
                    <div class="col-lg-6">
                     Superficie
                     <select name="inmueble_cbTerreno" id="inmueble_cbTerreno" class="form-control">
                        <option value="Llana">Llana</option>
                        <option value="Inclinada">Inclinada</option>
                     </select>
                    </div>
				</div>
                <div class="row">
                    <div class="col-lg-3">Parcela<br /><input name="inmueble_txtParcela" type="text" id="inmueble_txtParcela" size="10" maxlength="10"  class="form-control"/></div>
                    <div class="col-lg-3">Metros<br /><input name="inmueble_txtMetros" type="text" id="inmueble_txtMetros" size="10" maxlength="10"class="form-control" /></div>
                    <div class="col-lg-3">Metros &uacute;tiles<br /><input name="inmueble_txtSuperutil" type="text" id="inmueble_txtSuperutil" size="10" maxlength="10" class="form-control"/></div>
                    <div class="col-lg-3">Altura<br /><input name="inmueble_txtAltura" type="text" id="inmueble_txtAltura" size="10" maxlength="10" class="form-control"/></div>
                    <div class="col-lg-3">Superf. terraza<br /><input name="inmueble_txtMetroster" type="text" id="inmueble_txtMetroster" size="10" maxlength="10" class="form-control"/></div>
                    <div class="col-lg-3">Metros construidos<br /><input name="inmueble_txtMetroscuadra" type="text" id="inmueble_txtMetroscuadra" size="10" maxlength="10" class="form-control"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-3">Habitaciones<br /><input name="inmueble_txtHabitaciones" type="text" id="inmueble_txtHabitaciones" size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">Ba&ntilde;os<br /><input name="inmueble_txtBanos" type="text" id="inmueble_txtBanos"size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">Aseos<br /><input name="inmueble_txtAseos" type="text" id="inmueble_txtAseos" size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">Salones<br /><input name="inmueble_txtSalon" type="text" id="inmueble_txtSalon" size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">Cocinas<br /><input name="inmueble_txtCocina" type="text" id="inmueble_txtCocina" size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">Armarios empotrados<br /><input name="inmueble_txtArmaempo" type="text" id="inmueble_txtArmaempo" size="5" maxlength="5" class="form-control"/></div>
                </div>
                <div class="row">
                    <div class="col-lg-3">&nbsp;</div>
                    <div class="col-lg-3">Plantas edificio<br /><input name="inmueble_txtPlantasedif" type="text" id="inmueble_txtPlantasedif"  size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">Terrazas<br /><input name="inmueble_txtTerraza" type="text" id="inmueble_txtTerraza" size="5" maxlength="5" class="form-control"/></div>
                    <div class="col-lg-3">&nbsp;</div>
                    <div width="93" valign="top">&nbsp;</div>
                    <div class="col-lg-3">&nbsp;</div>
               </div>
			</div> <!-- FIN SUPERFICIE -->
        </div>
		<div class="panel panel-default inputs">
            	<div class="panel-heading">                   
                	<h3 class="panel-title text-left">Extras</h3>
	            </div>
				<div class="panel-body">
                    <div class="row">
                        <div class="col-lg-3"><input name="inmueble_chRiego" type="checkbox" id="inmueble_chRiego" value="checked"/> Sist. de riego</div>
                        <div class="col-lg-3"><input name="inmueble_chLuz" type="checkbox" id="inmueble_chLuz" value="checked"/> Luz</div>
                        <div class="col-lg-3"><input name="inmueble_chVallado" type="checkbox" id="inmueble_chVallado" value="checked"/> Vallado</div>
                        <div class="col-lg-3"> <input name="inmueble_chServidumbre" type="checkbox" id="inmueble_chServidumbre" value="checked"/> Servidumbre de vista </div>
                        <div class="col-lg-3"><input name="inmueble_chTelefono" type="checkbox" id="inmueble_chTelefono" value="checked"> Telefono</div>
                        <div class="col-lg-3"><input name="inmueble_chTendedero" type="checkbox" id="inmueble_chTendedero" value="checked"/> Tendedero</div>
                        <div class="col-lg-3"><input name="inmueble_chVestibulo" type="checkbox" id="inmueble_chVestibulo" value="checked"/> Vestibulo</div>
                        <div class="col-lg-3"><input name="inmueble_chAmueblado" type="checkbox" id="inmueble_chAmueblado" value="checked"/> Amueblado</div>
                        <div class="col-lg-3"><input name="inmueble_chCocinaamu" type="checkbox" id="inmueble_chCocinaamu" value="checked"/> Cocina amu.</div>
                        <div class="col-lg-3"><input name="inmueble_chAscensor" type="checkbox" id="inmueble_chAscensor" value="checked"/> Ascensor</div>
                        <div class="col-lg-3"><input name="inmueble_chChimenea" type="checkbox" id="inmueble_chChimenea" value="checked"  /> Chimenea</div>
                        <div class="col-lg-3"><input name="inmueble_chReformado" type="checkbox" id="inmueble_chReformado" value="checked"/> Reformado</div>
                        <div class="col-lg-3"><input name="inmueble_chExterior" type="checkbox" id="inmueble_chExterior" value="checked"/> Exterior</div>
                        <div class="col-lg-3"><input name="inmueble_chPortero" type="checkbox" id="inmueble_chPortero" value="checked"/> Portero</div>
                        <div class="col-lg-3"><input name="inmueble_chEstreno" type="checkbox" id="inmueble_chEstreno" value="checked" /> Estreno</div>
                        <div class="col-lg-3"><input name="inmueble_chTenis" type="checkbox" id="inmueble_chTenis" value="checked" /> Tenis</div>
                        <div class="col-lg-3"><input name="inmueble_chJardines" type="checkbox" id="inmueble_chJardines" value="checked"  /> Jardines</div>
                        <div class="col-lg-3"><input name="inmueble_chPiscina" type="checkbox" id="inmueble_chPiscina" value="checked"/> Piscina</div>
                        <div class="col-lg-3"><input name="inmueble_chPatio" type="checkbox" id="inmueble_chPatio" value="checked" /> Patio</div>
                        <div class="col-lg-3"><input name="inmueble_chGaraje" type="checkbox" id="inmueble_chGaraje" value="checked"/> Garaje</div>
                        <div class="col-lg-3"><input name="inmueble_chAlmacena" type="checkbox" id="inmueble_chAlmacena" value="checked"/> Almac&eacute;n</div>
                        <div class="col-lg-3"><input name="inmueble_chBuhardilla" type="checkbox" id="inmueble_chBuhardilla" value="checked"/> Buhardilla</div>
                        <div class="col-lg-3"><input name="inmueble_chTrastero" type="checkbox" id="inmueble_chTrastero" value="checked"/> Trastero</div>
                        <div class="col-lg-3"><input name="inmueble_chGasciudad" type="checkbox" id="inmueble_chGasciudad" value="checked"/> Gas ciudad</div>
                    </div>
				</div>
            </div>
		<div class="panel panel-default">
                <div class="panel-heading">                   
                    <h3 class="panel-title text-left">Construcciones</h3>
                </div>
                <div class="panel-body">
                	<div class="row">
                        <div class="col-lg-4">Climatizaci&oacute;n<br /><input name="inmueble_txtCalefaccion" type="text" id="inmueble_txtCalefaccion" size="30"  class="form-control"/></div>
                        <div class="col-lg-4">Carpinteria exterior<br /><input name="inmueble_txtCarpinext" type="text" id="inmueble_txtCarpinext" size="30" class="form-control"/></div>
                        <div class="col-lg-4">Carpinteria interior<br /><input type="text" name="inmueble_txtCarpinint" id="inmueble_txtCarpinint" size="30" class="form-control"/></div>
					</div>
					<div class="row">
                        <div class="col-lg-4">Soleria<br /><input name="inmueble_txtSolados" type="text" id="inmueble_txtSolados" size="30" class="form-control"/></div>
                        <div class="col-lg-4">A&ntilde;o de construcci&oacute;n<br /><input name="inmueble_txtAntiguedad" type="text" id="inmueble_txtAntiguedad" size="12" class="form-control"/></div>
                        <div class="col-lg-4">
                            Tipo<br>
                            <select name="inmueble_cbTipCasa" id="inmueble_cbTipCasa"  class="form-control">
                                <option value="Adosada">Adosada</option>
                                <option value="Pareada">Pareada</option>
                                <option value="Independiente">Independiente</option>
                            </select>
                        </div>
					</div>
					<div class="row">
	                    <div class="col-lg-12">                        
	                    	<input name="inmueble_chVPO" type="checkbox" id="inmueble_chVPO" value="checked"/>Vivienda Proteccion Oficial
                        </div>
                    </div>					    
				</div>
			</div> <!--  FIN CONSTRUCCIONES -->
        <div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Precios <input type="button" class="btn btn-xs btn-info" name="inmueble_Mdir2" id="inmueble_Mdir2" value="+" onclick="MuestraCostes()"/></h3>
            </div>
			<div class="panel-body">
				<div id="muestraCostes" class="row" style="display:none">
					<div class="col-lg-3">
                        Precio propietario<br />     
                        <input type="text" name="inmueble_txtPreciopropie" id="inmueble_txtPreciopropie" class="form-control"/>
					</div>
					<div class="col-lg-3">
                        Comisi&oacute;n %<br />    
                        <input type="text" name="inmueble_txtPcomision" id="inmueble_txtPcomision" class="form-control"/>
					</div>
					<div class="col-lg-3">						
                        Comisi&oacute;n<br />
                        <input type="text" name="inmueble_txtComision" id="inmueble_txtComision" class="form-control"/>						
					</div>
					<div class="col-lg-3">					
                        Honorarios fijos<br />
                        <input type="text" name="inmueble_txtHonorarios" id="inmueble_txtHonorarios" class="form-control"/>
					</div>
				</div>

                <div class="row">
                    <div class="col-lg-3">
                        Precio venta<br />
                        <input type="text" name="inmueble_txtPrecio" id="inmueble_txtPrecio" class="form-control"/>
                    </div>
                    <div class="col-lg-3">
                        Precio alquiler<br />
                        <input type="text" name="inmueble_txtPrecioalquiler" id="inmueble_txtPrecioalquiler" class="form-control"/>
                    </div>
                    <div class="col-lg-3">
                        Comunidad<br />
                        <input type="text" name="inmueble_txtComunidad" id="inmueble_txtComunidad" class="form-control"/>
                    </div>
                    <div class="col-lg-3">
                        Precio venta Garaje<br />
                        <input type="text" name="inmueble_txtPreciogar" id="inmueble_txtPreciogar" class="form-control" />
                    </div>
                </div>
				
                <div class="row">
					<div class="col-lg-9">
						Descripci&oacute;n<br />
						<textarea name="inmueble_txtDescripcion" cols="50" rows="2" id="inmueble_txtDescripcion" class="form-control"></textarea>
					</div>
					<div class="col-lg-3">
						Estado<br />
						<select name="inmueble_cbEstado" id="inmueble_cbEstado" class="form-control">
       						<option value="Ofertado">Ofertado</option>
        					<option value="Borrado">Borrado</option>
        					<option value="Alquilado">Alquilado</option>
        					<option value="Vendido">Vendido</option>
        					<option value="Reservado">Reservado</option>
      					</select>
					</div>
				</div>                				
           	</div> 
		</div> <!-- panel precio -->
		
        <div class="panel panel-default">
            <div class="panel-heading">                   
                <h3 class="panel-title text-left">Otros datos</h3>
            </div>
			<div class="panel-body">
				<div class="row">
	            	<div class="col-lg-6">
	                	Documento
	                	<a href="#" onclick="cambiarDocumentoInmueble()">Cambiar</a>
	                	<a href="#" onclick="verDocumentoInmueble()">Ver</a>
	                	<br />
	                	<img src="#" id="documentoInmueble" alt="Imagen-del-contrato" width="160" height="160" longdesc="Imagen-del-contrato" />
	            	</div>
	            	<div class="col-lg-6">
	                	<a href="#" onclick="crearPDF()">Crear PDF</a>
	                	<a href="#" onclick="verPDF()">Ver PDF</a>
	            	</div>
	        	</div>
				<div class="row margin-top-15">
	            	<div class="col-lg-12">
	                	Galeria de imagenes
	                	<a href="#" onclick="cambiarImagenesInmueble()">Cambiar</a>
	                	<br />
	                	<div id="galeriaImagenesInmueble">
	                		
	                	</div>
	            	</div>
	        	</div>
           	</div> 
		</div> <!-- panel documento & imagenes -->
		
        
        <div class="row">
            <div class="col-lg-12">                
                <input id="botonGuardaInmueble" type="button" value="Guardar Inmueble" class="btn btn-success" />
            </div>
        </div>
	</form>   
</div>

<!-- <script type="text/javascript" src="../assets/global/plugins/ckeditor/ckeditor.js"></script> -->

<script type="text/javascript" src="../assets/global/plugins/jquery-chosen/chosen.jquery.js"></script>

<script type="text/javascript" src="../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../assets/admin/pages/scripts/table-editable.js"></script>
<script src="../assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
<script src="../assets/global/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.es.js" type="text/javascript"></script>
<script type="text/javascript">
	$("#inmueble_txtFechaalta").datepicker({
		format: 'dd-mm-yyyy',
		todayBtn: true,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});
</script>

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

<script src="./modulos/inmobiliariaInmuebles/dk-logica.js"></script>