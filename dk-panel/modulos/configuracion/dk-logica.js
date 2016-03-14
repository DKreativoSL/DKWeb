	$(document).ready(function() {	
		actualizaListaRegistros();
	});
	
	function actualizaListaRegistros(){		
		
		jQuery.post("./modulos/configuracion/dk-logica.php", {
			accion: "listaRegistros",
			}, function(data, textStatus){
				console.log(data);
			});
		
		
		$('#tablaRegistros').dataTable( 		
		{			
			"ajax": {
	            "url": "./modulos/configuracion/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaRegistros"
					  }
	        },
		 	"columns": [
					{ "data": "nombre" },
					{ "data": "valor" }
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

	function guarda(parametro){
		valor = $("#"+parametro).val();
		jQuery.post("./modulos/configuracion/dk-logica.php", {
			accion: "guarda",
			nombre: parametro,
			valor: valor
			}, function(data, textStatus){
				if (data != "KO")
				{
					mensaje("Registro guardado correctamente.","success","check", 5);					
				}else{
					mensaje("Ocurrió algún problema al guardar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
				
			}
		);
		
	}