$(document).ready(function() {
	actualizaListaUpdates();
	/*
	$('#botonLanzar').click(function () {
		lanzarUpdate();
	});
	*/
});

function lanzarUpdate(file) {

	jQuery.post("./modulos/updates/dk-logica.php", {
		'accion': "lanzarUpdate",
		'file': file 
	}, function(data, textStatus){
		if (data == "OK") {
			mensaje("Actualización ejecutada con exito","success","check", 2);
		} else {
			mensaje("Ocurrió algún problema al lanzar la actualización. <br>Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
		}
	});
}

function actualizaListaUpdates() {
	$('#tablaRegistros').dataTable({	
		"ajax": {
            "url": "./modulos/updates/dk-logica.php",
	        "type": "POST",
			data: {"accion":"listaUpdates"}
        },
	 	"columns": [
			{ "data": "id" },
			{ "data": "archivo" },
			{ "data": "acciones" }
		],
		"bDeferRender": true,
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