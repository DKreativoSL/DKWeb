$(document).ready(function() {
	actualizaListaArticulos();
	
	$('#botonGuarda').click(function () {
		guarda();
	});
		
});

function listaSecciones(){		
	jQuery.post("./modulos/completo/dk-logica.php", {
		accion: "listaSecciones",
		}, function(data, textStatus){

			var datosSecciones = JSON.parse(data);					
			var id = "";
			var nombre = "";
			
			for (x=0; x < datosSecciones.length; x++)
			{
				id = datosSecciones[x]["id"];
				nombre = datosSecciones[x]['nombre'];
				$("#seccion").append('<option value="'+id+'">'+nombre+'</option>');						
			}
			$("#seccion option[value="+ seccionActual +"]").attr("selected",true);
			
	});
}

function modifica(idArticulo){
	
		//antes de modificar el artículo cargo la lista
		listaSecciones();
		
		jQuery.post("./modulos/completo/dk-logica.php", {
		accion: "leeArticulo",
		id: idArticulo
		}, function(data, textStatus){
			if (data != "KO")
			{
				var datosArticulo = JSON.parse(data);
				
				/*Cargo en los campos*/
				$("#id").val(datosArticulo[0]['id']);
				$("#titulo").val(datosArticulo[0]['titulo']);
				$("#subtitulo").val(datosArticulo[0]['subtitulo']);
				$("#fechaPublicacion").val(datosArticulo[0]['fechaPublicacion']);
				if (typeof datosArticulo[0]['cuerpo'] !== 'undefined') {
					tinyMCE.get('cuerpo').setContent(datosArticulo[0]['cuerpo']);
				}
				if (typeof datosArticulo[0]['cuerpoResumen'] !== 'undefined') {
					tinyMCE.get('resumen').setContent(datosArticulo[0]['cuerpoResumen']);
				}
				$("#cuerpoResumen").val(datosArticulo[0]['cuerpoResumen']);
				$("#imagen").val(datosArticulo[0]['imagen']);
				$("#archivo").val(datosArticulo[0]['archivo']);
				$("#url").val(datosArticulo[0]['url']);
				$("#campoExtra").val(datosArticulo[0]['campoExtra']);
				
				$('#estado option').each(function() {
					if ($(this).val() == datosArticulo[0]['estado']) {
						$(this).attr('selected','selected');
					}
				});					
				
				//selecciono la seccion					
				$("#seccion option[value="+ datosArticulo[0]['idSeccion'] +"]").attr("selected",true);
				
				$("#listaArticulos").fadeOut('fast',function () {
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
	if (!$("#titulo").val())
	{
		alert("Debes rellenar el titulo");
	
	}else{
		jQuery.post("./modulos/basico/dk-logica.php", {
			accion: "guarda",
			id: $("#id").val(),
			titulo: $("#titulo").val(),
			subtitulo: $("#subtitulo").val(),
			fechaPublicacion: $("#fechaPublicacion").val(),
			cuerpo:  tinyMCE.get('cuerpo').getContent(),
			cuerpoResumen: tinyMCE.get('cuerpoResumen').getContent(),
			imagen: $("#imagen").val(),
			archivo: $("#archivo").val(),
			url: $("#url").val(),
			campoExtra: $("#campoExtra").val(),
			orden: $("#orden").val(),
			seccion: $("#seccion").val(),
			estado: $("#estado").val()
			}, function(data, textStatus){
				if (data == "KO") {
					mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
				} else{
					mensaje("Guardado correctamente","success","check", 5);
					
					$("#camposFormulario").fadeOut('fast',function () {
						$('#tablaRegistrosProgramadas').dataTable()._fnAjaxUpdate();
						$('#tablaRegistrosBorrador').dataTable()._fnAjaxUpdate();
						$('#tablaRegistrosPublicadas').dataTable()._fnAjaxUpdate();
						$('#tablaRegistrosPapelera').dataTable()._fnAjaxUpdate();
						$("#listaArticulos").fadeIn('fast');
					});
				}
				
			}
		);
	}
}

function actualizaListaArticulos(estado) {
		
	$('#tablaRegistrosProgramadas').dataTable({	
		"ajax": {
			"url": "./modulos/escritorio/dk-logica.php",
	        "type": "POST",
			data: {
				"accion":"listaArticulos",
				"estado": 2
			}
        },
	 	"columns": [
			{ "data": "usuario" },
			{ "data": "titulo" },
			{ "data": "fecha" },
			{ "data": "acciones","sortable":false }
		],
		"bDeferRender": true,
		"bDestroy" : true,
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
	
	$('#tablaRegistrosBorrador').dataTable({	
		"ajax": {
			"url": "./modulos/escritorio/dk-logica.php",
	        "type": "POST",
			data: {
				"accion":"listaArticulos",
				"estado": 0
			}
        },
	 	"columns": [
			{ "data": "usuario" },
			{ "data": "titulo" },
			{ "data": "fecha" },
			{ "data": "acciones","sortable":false }
		],
		"bDeferRender": true,
		"bDestroy" : true,
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
	
	$('#tablaRegistrosPublicadas').dataTable({	
		"ajax": {
			"url": "./modulos/escritorio/dk-logica.php",
	        "type": "POST",
			data: {
				"accion":"listaArticulos",
				"estado": 1
			}
        },
	 	"columns": [
			{ "data": "usuario" },
			{ "data": "titulo" },
			{ "data": "fecha" },
			{ "data": "acciones","sortable":false }
		],
		"bDeferRender": true,
		"bDestroy" : true,
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
	
	$('#tablaRegistrosPapelera').dataTable({	
		"ajax": {
			"url": "./modulos/escritorio/dk-logica.php",
	        "type": "POST",
			data: {
				"accion":"listaArticulos",
				"estado": 3
			}
        },
	 	"columns": [
			{ "data": "usuario" },
			{ "data": "titulo" },
			{ "data": "fecha" },
			{ "data": "acciones","sortable":false }
		],
		"bDeferRender": true,
		"bDestroy" : true,
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