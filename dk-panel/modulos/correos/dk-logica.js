	$(document).ready(function() {
	
		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
			
		$("#botonNuevo").click(function(e) {
			limpiaForm();       
			$("#listaArticulos").fadeOut(500);
			$("#camposFormulario").fadeIn(3000);
		});		
		actualizaListaArticulos();
		///$_SESSION['dominio']
	});

	function actualizaListaArticulos(){		
		$('#tablaRegistros').dataTable( 		
		{	
			"ajax": {
	            "url": "./modulos/correos/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaArticulos", 
					   "seccion": seccionActual// leeParametro('seccion')
					  }
	        },
		 	"columns": [
					{ "data": "id" },
					{ "data": "correo" },
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
			}
			);
		}
		// Me cargo la linea que se pulse
		$('#tablaRegistros').dataTable().on('click', '.delete', function (e) {
            e.preventDefault();            
            var nRow = $(this).parents('tr')[0];
            $('#tablaRegistros').dataTable().fnDeleteRow(nRow);            
        });

	function listaSecciones(){		
		jQuery.post("./modulos/correos/dk-logica.php", {
			accion: "listaSecciones",
			}, function(data, textStatus){

					var datosSecciones = JSON.parse(data);					
					var id = "";
					var nombre = "";
					
					$("#seccion").html('<option value="'+id+'">&gt; Borrador</option>');
					for (x=0; x < datosSecciones.length; x++)
					{
						id = datosSecciones[x]["id"];
						nombre = datosSecciones[x]['nombre'];
						$("#seccion").append('<option value="'+id+'">'+nombre+'</option>');						
					}
					$("#seccion option[value="+ seccionActual +"]").attr("selected",true);
					
			});
		}
				
		
	function limpiaForm(){
		$("#id").val("");
		$("#titulo").val("");
		$("#subtitulo").val("");
		$("#fecha").val("");
		$("#cuerpo").val("");
		$("#cuerpoResumen").val("");
		$("#imagen").val("");
		$("#archivo").val("");
		$("#url").val("");
		$("#campoExtra").val("");
		}
	
	function modifica(idArticulo){
		
			jQuery.post("./modulos/correos/dk-logica.php", {
			accion: "leeArticulo",
			id: idArticulo
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datosArticulo = JSON.parse(data);						

					/*Cargo en los campos*/
					$("#id").val(datosArticulo[0]['id']);
					$("#correo").val(datosArticulo[0]['correo']);
					
					$("#listaArticulos").fadeOut(500);
					$("#camposFormulario").fadeIn(500);
					
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}

	function guarda(){
		/*antes de nada valido los campos*/
		var todoOK = true;
		if (!$("#correo").val())
		{
			mensaje("Sin correo no se puede guardar. Inserta un correo.","danger","warning", 0);
			todoOK = false;
		}
		
		if ($("#password").val() != $("#passwordconfirma").val())
		{
			mensaje("Confirma la contraseña correctamente.","danger","warning", 0);
			todoOK = false;			
		}
		
		/*Si pasa los filtros, sigo adelante :)*/
		if (todoOK == true)
		{
			//sino trae id está insertando
			if ($("#id").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			
			jQuery.post("./modulos/correos/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				correo: $("#correo").val(),
				password: $("#password").val()
				}, function(data, textStatus){
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						$("#id").val(data);
						mensaje("Guardado correctamente","success","check", 5);
					}
				}
			);
		}
	}
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/correos/dk-logica.php", {
			accion: "elimina",
			id: idElimina
			}, function(data, textStatus){
				if (data != "OK")
				{
					mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}else{
					mensaje("El registro se eliminó correctamente.","success","check", 5);					
					}
			}
		);		
	}