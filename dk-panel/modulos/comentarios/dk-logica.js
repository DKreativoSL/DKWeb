tinymce.init({
	id:'comentario',
    selector: "#comentario",
    plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker	",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality textcolor paste textcolor colorpicker textpattern"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify",

    menubar: false,
    toolbar_items_size: 'small',
    style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ]
});
	
	$(document).ready(function() {
	
		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
			
		$("#botonNuevo").click(function(e) {
			limpiaForm();       
			$("#listaArticulos").fadeOut(500);
			$("#camposFormulario").fadeIn(3000);
		});
		
		$("#botonResponder").click(function(e) {
			
			var idComentario = $("#idResponderPadre").val();
			var comentario = $('#comentarioResponder').val();
			jQuery.post("./modulos/comentarios/dk-logica.php", {
				accion: "responderComentario",
				id: idComentario,
				'comentario': comentario
				}, function(data, textStatus){
					if (data == "OK") {
						mensaje("Comentario creado correctamente","success","check", 2);
						
						$('#tablaRegistros').dataTable()._fnAjaxUpdate();
						
						$("#listaArticulos").fadeIn(500);
						$("#responder").fadeOut(500);
					} else {
						mensaje("Ocurrió algún problema crear el comentario. <br>Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 5);
					}
			});
		});
				
		actualizaListaComentarios();
		
	});

	function responder(idComentario){			

		$("#idResponderPadre").val(idComentario);
		$("#comentarioResponder").val('');
		
		$("#listaArticulos").fadeOut(500);
		$("#responder").fadeIn(500);
	}

	function cambiarEstado(idComentario,estado) {
		
		jQuery.post("./modulos/comentarios/dk-logica.php", {
			accion: "cambiarEstado",
			id: idComentario,
			'estado': estado
			}, function(data, textStatus){
				if (data == "OK") {
					mensaje("Estado cambiado correctamente","success","check", 2);
					$('#tablaRegistros').dataTable()._fnAjaxUpdate();
				} else {
					mensaje("Ocurrió algún problema al cambiar el estado del comentario. <br>Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 5);
				}
			});
	}

	function actualizaListaComentarios(){		
		$('#tablaRegistros').dataTable( 		
		{	
			"ajax": {
	            "url": "./modulos/comentarios/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaComentarios"}
	        },
		 	"columns": [
					{ "data": "id" },
					{ "data": "titulo" },
					{ "data": "comentario" },
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
		jQuery.post("./modulos/comentarios/dk-logica.php", {
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
		$("#usuario").val("");
		$("#fecha").val("");
		$("#comentario").val("");
		tinyMCE.get('comentario').setContent('');
		//listaSecciones();
	}
	
	function modifica(idComentario){
		
			//antes de modificar el artículo cargo la lista
			//listaSecciones();
			
			jQuery.post("./modulos/comentarios/dk-logica.php", {
				'accion': "leeComentario",
				'id': idComentario
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datosComentario = JSON.parse(data);						

					$("#id").val(datosComentario[0]['id']);
					$("#usuario").val(datosComentario[0]['nombre']);
					$("#fechaCreacion").val(datosComentario[0]['fechaCreacion']);
					$("#fechaPublicacion").val(datosComentario[0]['fechaPublicacion']);
					if (typeof datosComentario[0]['comentario'] !== 'undefined') {
						tinyMCE.get('comentario').setContent(datosComentario[0]['comentario']);
					}
					
					$('#estado option').each(function() {
						if ($(this).val() == datosComentario[0]['estado']) {
							$(this).attr('selected','selected');
						}
					});
					
					$("#listaArticulos").fadeOut(500,function () {
						$('#tablaRegistros').dataTable()._fnAjaxUpdate();
						$("#camposFormulario").fadeIn(500);
					});
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}

	function guarda(){
		/*antes de nada valido los campos*/
		if (!tinyMCE.get('comentario').getContent())
		{
			alert("Debes rellenar el comentario");
		
		} else {
			jQuery.post("./modulos/comentarios/dk-logica.php", {
				accion: "guarda",
				id: $("#id").val(),
				fechaCreacion: $("#fechaCreacion").val(),
				fechaPublicacion: $("#fechaPublicacion").val(),
				comentario: tinyMCE.get('comentario').getContent(),
				estado: $("#estado").val()
				}, function(data, textStatus){
					if (data == "KO") {
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						mensaje("Guardado correctamente","success","check", 5);
						
						$("#camposFormulario").fadeOut(500, function () {
							$("#listaArticulos").fadeIn(500);	
						});
						
					}
				}
			);
		}
	}
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/comentarios/dk-logica.php", {
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