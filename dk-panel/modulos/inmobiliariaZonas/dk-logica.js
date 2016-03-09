$(document).ready(function() {	
	$("#botonGuarda").click(function(e) {
		guarda(); 
	});
			
	$("#botonNuevo").click(function(e) {
		limpiaForm();       
		$("#listaZonas").fadeOut('fast', function () {
			$("#camposFormulario").fadeIn('fast', function () {
				jQuery.post("./modulos/inmobiliariaZonas/dk-logica.php", {
					'accion': "obtenerZonas",
					'idSubZona': -1,
					'idZona': -1
				}, function(data, textStatus){
					var html = data;
					$('#subzona').html(html);
				});
			});
		});
	});
	actualizaListaZonas();
});

	function actualizaListaZonas() {
		
		$('#tablaRegistros').dataTable({
			"processing": true,
			"serverSide": true,	
			"ajax": {
				"url": "./modulos/inmobiliariaZonas/dk-logica.php",
    	        "type": "POST",
				data: {
					"accion":"listaZonas"
				}
	        },
		 	"columns": [
				{ "data": "id" },
				{ "data": "nombre" },
				{ "data": "descripcion" },
				{ "data": "subzona" },
				{ "data": "estado" },
				{ "data": "acciones", "sortable":false }
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
	}

				
		
	function limpiaForm(){
		//Limpiamos todos los inputs
		$('#camposFormulario input[type=text]').each(function () {
			$(this).val('');	
		});
		//Limpiamos todos los textarea
		$('#descripcion').val('');
		
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
	
	function modifica(idZona){
		jQuery.post("./modulos/inmobiliariaZonas/dk-logica.php", {
			'accion': "leerZona",
			'id': idZona
		}, function(data, textStatus){
			if (data != "KO") {
				
				var datosZona = JSON.parse(data);
				
				$("#id").val(datosZona[0]['id']);
				
				$("#nombre").val(datosZona[0]['nombre']);
				$("#descripcion").val(datosZona[0]['descripcion']);
				$("#subzona").val(datosZona[0]['subzona']);

				jQuery.post("./modulos/inmobiliariaZonas/dk-logica.php", {
					'accion': "obtenerZonas",
					'idSubZona': datosZona[0]['subzona'],
					'idZona': datosZona[0]['id']
				}, function(data, textStatus){
					var html = data;
					$('#subzona').html(html);
				});

				$("#listaZonas").fadeOut('fast', function () {
					$("#camposFormulario").fadeIn('fast');	
				});
				
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
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
			
			jQuery.post("./modulos/inmobiliariaZonas/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				descripcion: $("#descripcion").val(),
				subzona: $("#subzona").val()
				}, function(data, textStatus){
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						if (accion == 'inserta') {
							//$("#id").val(data);	
						}
						limpiaForm();
						mensaje("Guardado correctamente","success","check", 5);
						
						$("#camposFormulario").fadeOut('fast', function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaZonas").fadeIn('fast');
						});
						
					}
					
				}
			);
		}
	}
	
	
	function elimina(idElimina){
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