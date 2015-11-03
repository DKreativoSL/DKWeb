	$(document).ready(function() {
	
		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
			
		$("#botonNuevo").click(function(e) {
			limpiaForm();       
			$("#listaArticulos").fadeOut(500);
			$("#camposFormulario").fadeIn(3000);
		
				/*Añado el editor al cuerpo después de añadirle la info, sino no rula*/
				CKEDITOR.replace('cuerpo',
				{
					  filebrowserBrowseUrl : './filemanager/index.php',
					  filebrowserWindowWidth : '600',
					  filebrowserWindowHeight : '500'
				});
		});		
		actualizaListaArticulos();
		
	});
	
	function cargaDatos(){
		jQuery.post("./modulos/migra2/dk-logica.php", {
			accion: "cargaDatos",
			servidor: $("#servidor").val(),
			usuario: $("#usuario").val(),
			bbdd: $("#bbdd").val(),
			pass: $("#pass").val()			
			}, function(data, textStatus){
				alert(data);
/*
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
					*/
			});
		
		}
	
	function actualizaListaArticulos(){		
		$('#tablaRegistros').dataTable( 		
		{	
			"ajax": {
	            "url": "./modulos/completo/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaArticulos", 
					   "seccion": seccionActual// leeParametro('seccion')
					  }
	        },
		 	"columns": [
					{ "data": "id" },
					{ "data": "titulo" },
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
		jQuery.post("./modulos/completo/dk-logica.php", {
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
		listaSecciones();
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
					$("#fecha").val(datosArticulo[0]['fecha']);
//					CKEDITOR.instances['editor']
					//CKEDITOR.instances.editor.setData(datosArticulo[0]['cuerpo']); //aquí añado el cuerpo
					$("#cuerpo").val(datosArticulo[0]['cuerpo']);
					$("#cuerpoResumen").val(datosArticulo[0]['cuerpoResumen']);
					$("#imagen").val(datosArticulo[0]['imagen']);
					$("#archivo").val(datosArticulo[0]['archivo']);
					$("#url").val(datosArticulo[0]['url']);
					$("#campoExtra").val(datosArticulo[0]['campoExtra']);
					
					//selecciono la seccion					
					$("#seccion option[value="+ datosArticulo[0]['idSeccion'] +"]").attr("selected",true);
					
					$("#listaArticulos").fadeOut(500);
					$("#camposFormulario").fadeIn(500);
					
					/*Añado el editor al cuerpo después de añadirle la info, sino no rula*/
					CKEDITOR.replace('cuerpo',
					{
						  filebrowserBrowseUrl : './filemanager/index.php',
						  filebrowserWindowWidth : '600',
						  filebrowserWindowHeight : '500'
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
		
		}else{ /*Si pasa los filtros, guarda :)*/
		
			//sino trae id está insertando
			if ($("#id").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			
			jQuery.post("./modulos/completo/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				titulo: $("#titulo").val(),
				subtitulo: $("#subtitulo").val(),
				fecha: $("#fecha").val(),
				cuerpo:  CKEDITOR.instances['cuerpo'].getData(),
				cuerpoResumen: $("#cuerpoResumen").val(),
				imagen: $("#imagen").val(),
				archivo: $("#archivo").val(),
				url: $("#url").val(),
				campoExtra: $("#campoExtra").val(),
				orden: $("#orden").val(),
				seccion: $("#seccion").val()
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
		jQuery.post("./modulos/completo/dk-logica.php", {
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