var getTablaRegistrosClientes;

$(document).ready(function() {
	
	$('#filtrarClientes').click(function () {
		filtrarClientes();
	});
		
	$("#botonGuarda").click(function(e) {
		guarda(); 
	});
			
	$("#botonNuevo").click(function(e) {
		limpiaForm();
		obtenerUsuario(idUsuario);       
		$("#listaClientes").fadeOut(500, function () {
			$("#camposFormulario").fadeIn(300);
		});
	});		
	actualizaListaClientes();
	inicializaFiltrosClientes();
});

function cambiarDocumentoCliente() {
	var idCliente = $("#id").val();
	ventanaPopup('./modulos/inmobiliariaClientes/subir/subir.php?id=' + idCliente);
}

function verDocumentoCliente() {
	var idCliente = $("#id").val();
	ventanaPopup('./archivos/inmobiliaria/' + idCliente + '.jpg');
}

function inicializaFiltrosClientes() {
	//Usuario
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerUsuariosOption",
		'idUsuario': 0
	}, function(data, textStatus){
		var html = '<option value="Todos">Todos</option>' + data;
		$('#filtro_cliente_lstUsuario').html(html);
	});
}

function filtrarClientes() {
	getTablaRegistrosClientes();
}

function cargarInmuebles() {
	var idCliente = $("#id").val();
	
	$("#cliente_inmuebles").load("./modulos/inmobiliariaInmuebles/index.php?idCliente=" + idCliente);
}

function cargarApuntes() {
	var idCliente = $("#id").val();
	
	$("#cliente_apuntes").load("./modulos/inmobiliariaApuntes/index.php?idCliente=" + idCliente);
}

function actualizaListaClientes() {
	var tablaRegistrosClientes;
	getTablaRegistrosClientes = function () {
		filtro_cliente_nif 			= $('#filtro_cliente_nif').val();
		filtro_cliente_nombre 		= $('#filtro_cliente_nombre').val();
		filtro_cliente_tlf 			= $('#filtro_cliente_tlf').val();
		filtro_cliente_desde 		= $('#filtro_cliente_desde').val();
		filtro_cliente_hasta 		= $('#filtro_cliente_hasta').val();
		filtro_cliente_desdebaja 	= $('#filtro_cliente_desdebaja').val();
		filtro_cliente_hastabaja 	= $('#filtro_cliente_hastabaja').val();
		filtro_cliente_lstTipo 		= $('#filtro_cliente_lstTipo').val();
		filtro_cliente_lstUsuario 	= $('#filtro_cliente_lstUsuario').val();
		filtro_cliente_lstEstado 	= $('#filtro_cliente_lstEstado').val();
		
		
		tablaRegistrosClientes = $('#tablaRegistrosClientes').dataTable({
			"processing": true,
			"serverSide": true,		
			"ajax": {
				"url": "./modulos/inmobiliariaClientes/dk-logica.php",
		        "type": "POST",
				data: {
					"accion":"listaClientes",
					"filtro_cliente_nif":filtro_cliente_nif,
					"filtro_cliente_nombre":filtro_cliente_nombre,
					"filtro_cliente_tlf":filtro_cliente_tlf,
					"filtro_cliente_desde":filtro_cliente_desde,
					"filtro_cliente_hasta":filtro_cliente_hasta,
					"filtro_cliente_desdebaja":filtro_cliente_desdebaja,
					"filtro_cliente_hastabaja":filtro_cliente_hastabaja,
					"filtro_cliente_lstTipo":filtro_cliente_lstTipo,
					"filtro_cliente_lstUsuario":filtro_cliente_lstUsuario,
					"filtro_cliente_lstEstado":filtro_cliente_lstEstado
				}
	        },
		 	"columns": [
				{ "data": "id" },
				{ "data": "cliente" },
				{ "data": "telefonos" },
				{ "data": "fechas" },
				{ "data": "usuario" },
				{ "data": "tipo" },
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
	getTablaRegistrosClientes();
}				
		
function limpiaForm() {
	
	//Limpiamos todos los inputs
	$('#camposFormulario input[type=text]').each(function () {
		$(this).val('');	
	});
	//Limpiamos todos los textarea
	$('#camposFormulario input[type=textarea]').each(function () {
		$(this).html('');	
	});
	//Limpiamos todos los checkbox
	$('#camposFormulario input[type=checkbox]').each(function () {
		$(this).removeAttr('checked');
	});
	//Limpiamos todos los selects
	$('#camposFormulario select').each(function () {
		$(this).children('option').each(function () {
			$(this).removeAttr('selected');
		});
	});
}
	
	function obtenerUsuario(idUsuario) {
		jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
			'accion': "obtenerUsuariosOption",
			'idUsuario': idUsuario
		}, function(data, textStatus){
			$('#selectUsuario').html(data);
			$("#selectUsuario").trigger("chosen:updated");
		});
	}
	
	function modifica(idCliente) {
		jQuery.post("./modulos/inmobiliariaClientes/dk-logica.php", {
			'accion': "leerCliente",
			'id': idCliente
		}, function(data, textStatus){
			if (data != "KO") {
				var datosCliente = JSON.parse(data);
				
				$("#id").val(datosCliente[0]['id']);
				
				$("#nombre").val(datosCliente[0]['nombre']);
				$("#tlf1").val(datosCliente[0]['tlf1']);
				
				$("#email").val(datosCliente[0]['email']);
				$("#tlf2").val(datosCliente[0]['tlf2']);

				$("#fuente").val(datosCliente[0]['fuente']);
				$("#fax").val(datosCliente[0]['fax']);
				
				$("#comentarios").val(datosCliente[0]['comentarios']);
				
				$("#nif").val(datosCliente[0]['nif']);
				
				obtenerUsuario(datosCliente[0]['usuario']);
				
				$('#tipoc option').each(function() {
					if ($(this).val() == datosCliente[0]['tipoc']) {
						$(this).attr('selected','selected');
					}
				});
				
				$("#direccion").val(datosCliente[0]['direccion']);
				
				$("#poblacion").val(datosCliente[0]['poblacion']);
				$("#provincia").val(datosCliente[0]['provincia']);
				$("#cpostal").val(datosCliente[0]['cpostal']);
				
				$("#fechaAlta").val(datosCliente[0]['fechaalta']);
				$("#fechaBaja").val(datosCliente[0]['fechabaja']);
				$("#bajamotivo").val(datosCliente[0]['bajamotivo']);
				
				obtenerDocumentoCliente(datosCliente[0]['id']);

				$("#listaClientes").fadeOut(500, function () {
					$("#camposFormulario").fadeIn(500);
					
					cargarInmuebles();
					cargarApuntes();
					
				});
				
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}
	
	function obtenerDocumentoCliente(idCliente) {
		jQuery.post("./modulos/inmobiliariaClientes/dk-logica.php", {
			'accion': "obtenerDocumentoCliente",
			'idCliente': idCliente
		}, function(data, textStatus){
			$('#imagenCliente').attr('src',data);
		});
	}

	function guarda(){
		/*antes de nada valido los campos*/
		if (!$("#nombre").val())
		{
			alert("Debes rellenar el nombre");
		
		}else{ /*Si pasa los filtros, guarda :)*/
		
			//sino trae id está insertando
			if ($("#id").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			
			jQuery.post("./modulos/inmobiliariaClientes/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				direccion: $("#direccion").val(),
				poblacion: $("#poblacion").val(),
				provincia: $("#provincia").val(),
				cpostal: $("#cpostal").val(),
				tlf1: $("#tlf1").val(),
				tlf2: $("#tlf2").val(),
				fax: $("#fax").val(),
				fuente: $("#fuente").val(),
				email: $("#email").val(),
				nif: $("#nif").val(),
				fechabaja: $("#fechaBaja").val(),
				bajamotivo: $("#bajamotivo").val(),
				comentarios: $("#comentarios").val(),
				txtUsuarioId: $("#selectUsuario").val(),
				tipoc: $("#tipoc").val()
				}, function(data, textStatus){
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						if (accion == 'inserta') {
							$("#id").val(data);	
						}
						mensaje("Guardado correctamente","success","check", 5);
						
						$("#camposFormulario").fadeOut(500, function () {
							$('#tablaRegistrosClientes').dataTable()._fnAjaxUpdate();
							$("#listaClientes").fadeIn(500);
						});
						
					}
					
				}
			);
		}
	}
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/inmobiliariaClientes/dk-logica.php", {
			accion: "elimina",
			id: idElimina
			}, function(data, textStatus){
				if (data != "OK") {
					mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				} else{
					mensaje("El cliente se eliminó correctamente.","success","check", 5);
					$('#tablaRegistrosClientes').dataTable()._fnAjaxUpdate();					
				}
			}
		);		
	}