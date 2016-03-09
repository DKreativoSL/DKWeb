tinymce.init({
	id:'cuerpo',
    selector: "#cuerpo",
    plugins: [
            "dkfiles advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker	",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality textcolor paste textcolor colorpicker textpattern"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor | dkimage dkvideo dkfile | code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

	language : 'es',
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

tinymce.init({
	id:'resumen',
    selector: "#cuerpoResumen",
    plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern"
    ],

    toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

	language : 'es',
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
		actualizaListaRegistros();
		
		$("#botonGuarda").click(function(e) {
	       guarda(); 
	    });
		
		$('#vaciarPapeleraArticulos').click(function () {
			vaciarPapeleraArticulos();
		});
		
		$('#recuperarArticulo').click(function () {
			var idToMove = $('#selectPopupRecuperar').val();
			var idRecuperar = $(this).attr('idArticulo');
			recuperarArticulo(idRecuperar,idToMove);
		});
	});
		
	function actualizaListaRegistros(){	
		$('#tablaRegistros').dataTable( 		
		{			
			"ajax": {
	            "url": "./modulos/trashArticulos/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaRegistros"
					  }
	        },
		 	"columns": [
					{ "data": "id" },
					{ "data": "usuario" },
					{ "data": "titulo" },
					{ "data": "estado" },
					{ "data": "fecha" },
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
		

	function listaSecciones(){		
		jQuery.post("./modulos/trashArticulos/dk-logica.php", {
			accion: "listaSecciones",
			}, function(data, textStatus){

					var datosSecciones = JSON.parse(data);					
					var id = "";
					var nombre = "";
					
					//$("#seccion").html('<option value="'+id+'">&gt; Borrador</option>');
					for (x=0; x < datosSecciones.length; x++)
					{
						id = datosSecciones[x]["id"];
						nombre = datosSecciones[x]['nombre'];
						$("#seccion").append('<option value="'+id+'">'+nombre+'</option>');						
					}					
					
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
		tinyMCE.get('cuerpo').setContent('');
		//tinyMCE.get('resumen').setContent('');
		listaSecciones();
	}

	function eliminarArticuloPapelera(idArticulo){
		jQuery.post("./modulos/trashArticulos/dk-logica.php", {
			accion: "eliminarArticuloPapelera",
			id: idArticulo
		}, function(data, textStatus){
			if (data == "OK") {
				mensaje("Eliminado correctamente","success","check", 5);
				//Actualizo la tabla de registros
				$('#tablaRegistros').dataTable()._fnAjaxUpdate();
				
			} else {
				mensaje("Ocurrió algún problema al eliminar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});
	}
	
	function vaciarPapeleraArticulos() {
		jQuery.post("./modulos/trashArticulos/dk-logica.php", {
			accion: "vaciarPapeleraArticulos"
		}, function(data, textStatus){
			console.log(data);
			if (data == "OK") {
				mensaje("Papelera vaciada correctamente","success","check", 5);
				//Actualizo la tabla de registros
				$('#tablaRegistros').dataTable()._fnAjaxUpdate();
				
			} else {
				mensaje("Ocurrió algún problema al vaciar la papelera de articulos. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});		
	}
	
	function modifica(idArticulo){
			//antes de modificar el artículo cargo la lista
			listaSecciones();
			
			jQuery.post("./modulos/trashArticulos/dk-logica.php", {
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
					
					$("#listaRegistros").fadeOut('fast', function () {
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
		
		}else{ /*Si pasa los filtros, guarda :)*/
			
			accion = "guarda";

			jQuery.post("./modulos/trashArticulos/dk-logica.php", {
				accion: accion,
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
				seccion: $("#seccion").val()
				}, function(data, textStatus){
					if (data == "KO") {
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						mensaje("Guardado correctamente","success","check", 5);
						
						$("#camposFormulario").fadeOut('fast', function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaRegistros").fadeIn('fast'); 	
						});
					}
					
				}
			);
		}
	}
	
	
	//RECUPERAR
	function popupRecuperar(idRecuperar) {
		
		$("#btn_" + idRecuperar).trigger("click");
		
		$('#recuperarArticulo').attr('idArticulo',idRecuperar);
		
		//selectPopupEliminar
		jQuery.post("./modulos/trashArticulos/dk-logica.php", {
			accion: "obtenerSecciones",
			id: idRecuperar
			}, function(data, textStatus){
				if (data != "KO") {
					$('#selectPopupRecuperar').html(data);
				}
			}
		);
	}
	
	function recuperarArticulo(idRecuperar,idToMove) {
		jQuery.post("./modulos/trashArticulos/dk-logica.php", {
			'accion': "recuperarArticulo",
			'id': idRecuperar,
			'idToMove': idToMove
			}, function(data, textStatus){
				if (data == "OK") {
					mensaje("Articulo recuperado correctamente.","success","check", 5);
					
					//Actualizo la tabla de registros
					$('#tablaRegistros').dataTable()._fnAjaxUpdate();
					
					//Actualizo el menu lateral
					cargaMenuLateral();
				} else{
					mensaje("Ocurrió algún problema al recuperar el articulo. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}
	
	function recuperar(idRecuperar){
		popupRecuperar(idRecuperar);
	}