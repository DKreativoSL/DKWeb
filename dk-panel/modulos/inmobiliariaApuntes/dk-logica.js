var getTablaRegistrosVisitas;
var getTablaRegistrosVisitasCliente;

$(document).ready(function() {
	
	$("#apuntes_fechaCita").datetimepicker({
		format: 'hh:ii:ss dd/mm/yyyy',
		todayBtn: true,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});
	
	$('#filtrarApuntes').click(function () {
		filtrarApuntes();
	});
		
	$("#botonGuardaVisita").click(function(e) {
		guardaApuntes(false); 
	});
	
	$('#guardarPopupApunte').click(function (e) {
		guardaApuntes(true);
	});
	
	//Si tenemos un cliente, cambiamos el formulario al POPUP
	if (idCliente > 0) {
		var formulario = $('#camposFormularioVisitas').html();
		$('#apuntes_body').html(formulario);
		
		$("#apuntes_fechaCita").datetimepicker({
			format: 'hh:ii:ss dd/mm/yyyy',
			todayBtn: true,
			language: 'es',
			todayHighlight: true,
			weekStart: 1
		});
		
		$('#camposFormularioVisitas').html('');
		$('#filtrosApuntes').hide();
	}
	
	$("#botonNuevoVisita").click(function(e) {
		if (idCliente > 0) {
			//limpiaFormApuntesCliente(idCliente,idUsuario);
			limpiaFormApuntes();
			obtenerPropietario(idCliente);
			obtenerInmuebles();
			bloquearSelectPropietario();
			
			$('#botonGuardaVisita').hide();
			$('#popupApuntes').modal('show');
		} else {			
			$("#listaVisitas").fadeOut(500, function () {
				$("#camposFormularioVisitas").fadeIn(300);
			});
			limpiaFormApuntes();
			
			obtenerPropietario(0);
			obtenerInmuebles();
			
		}   
	});
	
	if (idCliente > 0) {
		actualizaListaVisitasCliente(idCliente);
	} else {
		actualizaListaVisitas();
	}
	inicializaFiltrosApuntes();
	$('.chosen-select').chosen({});
	
	$('#apuntes_cbApunte').change(function () {
		if ($(this).val().toString() == "Cita") {
			mostrarCamposCita(true);
		} else {
			mostrarCamposCita(false);
		}
	});
});

function inicializaFiltrosApuntes() {
	//Usuario
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerUsuariosOption",
		'idUsuario': 0
	}, function(data, textStatus){
		var html = '<option value="Todos">Todos</option>' + data;
		$('#filtro_apunte_lstUsuario').html(html);
	});
}

	function filtrarApuntes() {
		getTablaRegistrosVisitas();
	}

	function bloquearSelectPropietario() {
		$('#apuntes_selectPropietario').prop('disabled', true).trigger("chosen:updated");
	}

	function actualizaListaVisitasCliente(idCliente) {
		
		var tablaRegistrosVisitasCliente;
		getTablaRegistrosVisitasCliente = function () {
				

			tablaRegistrosVisitasCliente = $('#tablaRegistrosVisitas').dataTable({
				"processing": true,
				"serverSide": true,
				"ajax": {
					"url": "./modulos/inmobiliariaApuntes/dk-logica.php",
	    	        "type": "POST",
					data: {
						"accion":"listaVisitasCliente",
						'idCliente': idCliente
					}
		        },
			 	"columns": [
					{ "data": "id" },
					{ "data": "cliente" },
					{ "data": "tipo" },
					{ "data": "creado" },
					{ "data": "cita" },
					{ "data": "inmueble" },
					{ "data": "comentario" },
					{ "data": "usuario" },
					{ "data": "acciones","sortable":false }
				],
				"bDeferRender": true,
				"bDestroy": true,
				"oLanguage": {
					"sEmptyTable": "No hay registros disponibles",
					"sInfo": "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)",
					"sLoadingRecords": "Por favor espera - Cargando...",
					"sSearch": "Filtro:",
					"sLengthMenu": "Mostrar _MENU_",
	     		 	"oPaginate": {
				        "sLast": "Última página",
						"sFirst": "Primera",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"				
		      		}			
			    }
			});
		};
		getTablaRegistrosVisitasCliente();
	}	

	function actualizaListaVisitas() {
		var tablaRegistrosVisitas;
		getTablaRegistrosVisitas = function () {
			
			filtro_apunte_cliente 		= $('#filtro_apunte_cliente').val();
			filtro_apunte_inmueble 		= $('#filtro_apunte_inmueble').val();
			filtro_apunte_desde 		= $('#filtro_apunte_desde').val();
			filtro_apunte_hasta 		= $('#filtro_apunte_hasta').val();
			filtro_apunte_desdeaviso 	= $('#filtro_apunte_desdeaviso').val();
			filtro_apunte_hastaaviso 	= $('#filtro_apunte_hastaaviso').val();
			filtro_apunte_cbApunte 		= $('#filtro_apunte_cbApunte').val();
			filtro_apunte_lstUsuario 	= $('#filtro_apunte_lstUsuario').val();
			filtro_apunte_chSeguimiento = $('#filtro_apunte_chSeguimiento').val();
			filtro_apunte_chComentarios = $('#filtro_apunte_chComentarios').val();
			filtro_apunte_lstOrden 		= $('#filtro_apunte_lstOrden').val();
			
			tablaRegistrosVisitas = $('#tablaRegistrosVisitas').DataTable({
				"processing": true,
				"serverSide": true,
				"ajax":{
					url :"./modulos/inmobiliariaApuntes/dk-logica.php",
					type: "post",
					data: {
						"accion":"listaVisitas",
						"filtro_apunte_cliente":filtro_apunte_cliente,
						"filtro_apunte_inmueble":filtro_apunte_inmueble,
						"filtro_apunte_desde":filtro_apunte_desde,
						"filtro_apunte_hasta":filtro_apunte_hasta,
						"filtro_apunte_desdeaviso":filtro_apunte_desdeaviso,
						"filtro_apunte_hastaaviso":filtro_apunte_hastaaviso,
						"filtro_apunte_cbApunte":filtro_apunte_cbApunte,
						"filtro_apunte_lstUsuario":filtro_apunte_lstUsuario,
						"filtro_apunte_chSeguimiento":filtro_apunte_chSeguimiento,
						"filtro_apunte_chComentarios":filtro_apunte_chComentarios,
						"filtro_apunte_lstOrden":filtro_apunte_lstOrden
					},
					error: function() {
						$(".tablaRegistrosVisitas-error").html("");
						$("#tablaRegistrosVisitas").append('<tbody class="employee-grid-error"><tr><th colspan="3">No se han encontrado registros en la base de datos</th></tr></tbody>');
						$("#tablaRegistrosVisitas_processing").css("display","none");
						
					}
				},
			 	"columns": [
					{ "data": "id" },
					{ "data": "cliente" },
					{ "data": "tipo" },
					{ "data": "creado" },
					{ "data": "cita" },
					{ "data": "inmueble" },
					{ "data": "comentario" },
					{ "data": "usuario" },
					{ "data": "acciones","sortable":false }
				],
				"bDeferRender": true,
				"bDestroy": true,
				"oLanguage": {
					"sEmptyTable": "No hay registros disponibles",
					"sInfo": "Hay _TOTAL_ registros. Mostrando de (_START_ a _END_)",
					"sLoadingRecords": "Por favor espera - Cargando...",
					"sSearch": "Filtro:",
					"sLengthMenu": "Mostrar _MENU_",
	     		 	"oPaginate": {
				        "sLast": "Última página",
						"sFirst": "Primera",
						"sNext": "Siguiente",
						"sPrevious": "Anterior"				
		      		}			
			    }
			});
		};
		getTablaRegistrosVisitas();
	}	
		
	function obtenerPropietario(idPropietario) {
		//Propietario
		jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
			'accion': "obtenerPropietario",
			'idPropietario': idPropietario
		}, function(data, textStatus){
			$('#apuntes_selectPropietario').html(data);
			$("#apuntes_selectPropietario").trigger("chosen:updated");
		});
	}
		
	function obtenerCliente(idCliente) {
		//Propietario
		jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
			'accion': "obtenerCliente",
			'idCliente': idCliente
		}, function(data, textStatus){
			
			dataCliente = JSON.parse(data);
			
			$('#apuntes_txtPropNombre').val(dataCliente[0].nombre);
			$('#apuntes_txtPropTelf').val(dataCliente[0].tlf1);
			$('#apuntes_txtPropId').val(idCliente);
			
			
			$("#apuntes_selectPropietario").trigger("chosen:updated");
		});
	}
	
	function obtenerUsuario(idUsuario) {
		jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
			'accion': "obtenerUsuariosOption",
			'idUsuario': idUsuario
		}, function(data, textStatus){
			$('#apuntes_selectUsuario').html(data);
			$("#apuntes_selectUsuario").trigger("chosen:updated");
		});
	}
	
	function obtenerInmuebles() {
		jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
			'accion': "obtenerInmuebles"
		}, function(data, textStatus){
			$('#apuntes_selectInmueble').html(data);
			$("#apuntes_selectInmueble").trigger("chosen:updated");
		});
	}
	
	function obtenerInmueblesConId(refInmueble) {
		jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
			'accion': "obtenerInmueblesConId",
			'refInmueble': refInmueble
		}, function(data, textStatus){
			$('#apuntes_selectInmueble').html(data);
			$("#apuntes_selectInmueble").trigger("chosen:updated");
		});		
	}
		
	function limpiaFormApuntesCliente(idCliente,idUsuario) {
		limpiaFormInmuebles();
	}
		
	function limpiaFormApuntes(){
		
		//Limpiamos todos los inputs
		$('#camposFormularioVisitas input[type=text]').each(function () {
			$(this).val('');	
		});
		
		//Limpiamos todos los textarea
		$('#camposFormularioVisitas input[type=textarea]').each(function () {
			$(this).html('');	
		});
		
		//Limpiamos todos los checkbox
		$('#camposFormularioVisitas input[type=checkbox]').each(function () {
			$(this).removeAttr('checked');
		});
		
		//Limpiamos todos los selects
		$('#camposFormularioVisitas select').each(function () {
			$(this).children('option').each(function () {
				$(this).removeAttr('selected');
			});
		});
		
		//Limpiamos todos los inputs del popup
		$('#popupApuntes input[type=text]').each(function () {
			$(this).val('');	
		});
		
		//Limpiamos todos los textarea del popup
		$('#apuntes_comentarios').html('');
		//Limpiamos todos los checkbox del popup
		$('#popupApuntes input[type=checkbox]').each(function () {
			$(this).removeAttr('checked');
		});
		//Limpiamos todos los selects del popup
		$('#popupApuntes select').each(function () {
			$(this).children('option').each(function () {
				$(this).removeAttr('selected');
			});
		});
		
		obtenerUsuario(idUsuario);
	}
	
	
function modificaApuntesPopup(idVisita){
	jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
		'accion': "leerVisita",
		'id': idVisita
	}, function(data, textStatus){
		if (data != "KO") {
			var datosVisita = JSON.parse(data);
			limpiaFormApuntes();
			
			$("#apuntes_txtId").val(datosVisita[0]['id']);
			
			var fechaCitaSplit = datosVisita[0]['fechaaviso'].split(" ");
			var fechaSplit = fechaCitaSplit[0].split("-");
			var nuevoFormatoFecha = fechaSplit[2] + "/" + fechaSplit[1] + "/" + fechaSplit[0] + " " + fechaCitaSplit[1];
			$("#apuntes_fechaCita").val(nuevoFormatoFecha);
			
			obtenerPropietario(datosVisita[0]['cliente_id']);
			obtenerInmueblesConId(datosVisita[0]['inmueble']);
			bloquearSelectPropietario();
			
			/*
			$("#apuntes_txtPropId").val(datosVisita[0]['cliente_id']);
			$("#apuntes_txtPropNombre").val(datosVisita[0]['cliente_nombre']);
			$("#apuntes_txtPropTelf").val(datosVisita[0]['cliente_telefono']);
			$("#apuntes_txtInmoRef").val(datosVisita[0]['inmueble_ref']);
			$("#apuntes_txtInmoZona").val(datosVisita[0]['inmueble_zona']);
			*/
			
			$('#apuntes_cbApunte option').each(function() {
				if ($(this).val() == datosVisita[0]['tipo']) {
					$(this).attr('selected','selected');
					$('#apuntes_cbApunte').trigger("chosen:updated");
					if ($(this).val().toString() == "Cita") {
						mostrarCamposCita(true);
					} else {
						mostrarCamposCita(false);
					}
					
				}
			});
			
			$('#apuntes_estadoCita option').each(function() {
				if ($(this).val().toString() == datosVisita[0]['estadoCita']) {
					$(this).attr('selected','selected');
					$('#apuntes_estadoCita').trigger("chosen:updated");
				}
			});
			
			/*
			$("#apuntes_txtUsuaId").val(datosVisita[0]['usuario_id']);
			$("#apuntes_txtUsuaNombre").val(datosVisita[0]['usuario_nombre']);
			*/
			
			$("#apuntes_comentarios").val(datosVisita[0]['comentarios']);
			
			$('#botonGuardaVisita').hide();
			
			//Mostramos el popup
			$('#popupApuntes').modal('show');
		} else{
			mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
		}
	});
}
	
function modificaApuntes(idVisita){
	jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
		'accion': "leerVisita",
		'id': idVisita
	}, function(data, textStatus){
		
		if (data != "KO") {
			var datosVisita = JSON.parse(data);
			limpiaFormApuntes();
			
			$("#apuntes_txtId").val(datosVisita[0]['id']);
			
			
			var fechaCitaSplit = datosVisita[0]['fechaaviso'].split(" ");
			var fechaSplit = fechaCitaSplit[0].split("-");
			var nuevoFormatoFecha = fechaSplit[2] + "/" + fechaSplit[1] + "/" + fechaSplit[0] + " " + fechaCitaSplit[1];
			$("#apuntes_fechaCita").val(nuevoFormatoFecha);
			
			obtenerPropietario(datosVisita[0]['cliente_id']);
			obtenerInmueblesConId(datosVisita[0]['inmueble']);
			/*
			$("#apuntes_txtPropId").val(datosVisita[0]['cliente_id']);
			$("#apuntes_txtPropNombre").val(datosVisita[0]['cliente_nombre']);
			$("#apuntes_txtPropTelf").val(datosVisita[0]['cliente_telefono']);
			$("#apuntes_txtInmoRef").val(datosVisita[0]['inmueble_ref']);
			$("#apuntes_txtInmoZona").val(datosVisita[0]['inmueble_zona']);
			*/
			$('#apuntes_cbApunte option').each(function() {
				if ($(this).val() == datosVisita[0]['tipo']) {
					$(this).attr('selected','selected');
					$('#apuntes_cbApunte').trigger("chosen:updated");
					if ($(this).val().toString() == "Cita") {
						mostrarCamposCita(true);
					} else {
						mostrarCamposCita(false);
					}
					
				}
				
			});
			
			$('#apuntes_estadoCita option').each(function() {
				if ($(this).val().toString() == datosVisita[0]['estadoCita']) {
					$(this).attr('selected','selected');
					$('#apuntes_estadoCita').trigger("chosen:updated");
				}
			});
			
			/*
			$("#apuntes_txtUsuaId").val(datosVisita[0]['usuario_id']);
			$("#apuntes_txtUsuaNombre").val(datosVisita[0]['usuario_nombre']);
			*/
			$("#apuntes_comentarios").val(datosVisita[0]['comentarios']);
			
			$("#listaVisitas").fadeOut(500, function () {
				$("#camposFormularioVisitas").fadeIn(500);	
			});
		} else{
			mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
		}
	});
}

	function guardaApuntes(esPopup){
		if (!$("#apuntes_comentarios").val())
		{
			alert("Debes rellenar el comentario");
		
		}else{ /*Si pasa los filtros, guarda :)*/
		
			//sino trae id está insertando
			if ($("#apuntes_txtId").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			
			jQuery.post("./modulos/inmobiliariaApuntes/dk-logica.php", {
				'accion': accion,
				apuntes_txtId: $("#apuntes_txtId").val(),
				apuntes_txtUsuaId: $("#apuntes_selectUsuario option:selected").val(),
				apuntes_txtPropId: $("#apuntes_selectPropietario option:selected").val(),
				apuntes_txtInmoId: $("#apuntes_selectInmueble option:selected").val(),
				apuntes_comentarios: $("#apuntes_comentarios").val(),
				apuntes_estadoCita: $("#apuntes_estadoCita option:selected").val(),
				apuntes_fechaCita: $("#apuntes_fechaCita").val(),
				apuntes_cbApunte: $("#apuntes_cbApunte option:selected").val()
				}, function(data, textStatus){
					console.log(data);
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						if (accion == 'inserta') {
							$("#id").val(data);	
						}
						mensaje("Guardado correctamente","success","check", 5);
						
						if (esPopup) {
							//Actualizamos el registro
							$('#tablaRegistrosVisitas').dataTable()._fnAjaxUpdate();
							
							//Ocultamos el popup
							$('#popupApuntes').modal('hide');
						} else {
							$("#camposFormularioVisitas").fadeOut(500, function () {
								$('#tablaRegistrosVisitas').dataTable()._fnAjaxUpdate();
								$("#listaVisitas").fadeIn(500);
							});
						}
					}
					
				}
			);
		}
	}
	
	
	function eliminaApuntes(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/inmobiliariaZonas/dk-logica.php", {
			accion: "elimina",
			id: idElimina
			}, function(data, textStatus){
				if (data != "OK") {
					mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				} else{
					mensaje("El cliente se eliminó correctamente.","success","check", 5);
					$('#tablaRegistros').dataTable()._fnAjaxUpdate();					
				}
			}
		);		
	}
	

function mostrarCamposCita(mostrar) {
	if (mostrar) {
		$('#camposEspecificosCita').show();
	} else {
		$('#camposEspecificosCita').hide();
	}
}
