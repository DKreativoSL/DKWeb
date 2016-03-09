
$(document).ready(function() {

	$("#botonGuarda").click(function(e) {
       guarda(); 
    });
		
	$("#botonNuevo").click(function(e) {
		limpiaForm();       
		$("#listaArticulos").fadeOut(500);
		$("#camposFormulario").fadeIn(3000);
		
		jQuery.post("./modulos/basico/dk-logica.php", {
			accion: "obtenerCamposSeccion",
			idSeccion: seccionActual
		}, function(dataCampos, textStatus){
			ocultarCamposFormulario(dataCampos);
		});
		
	});		
	actualizaListaArticulos();
	
});

	function duplicar(idArticulo) {
		
		jQuery.post("./modulos/basico/dk-logica.php", {
			accion: "duplicarArticulo",
			id: idArticulo					
		},
		function(data, textStatus){
			console.log(data);
			if (data == "OK") {
				mensaje("Articulo duplicado correctamente","success","check", 5);
				$('#tablaRegistros').dataTable()._fnAjaxUpdate();
			}else{
				mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});
	}

	function actualizaListaArticulos(){
		/*
		jQuery.post("./modulos/basico/dk-logica.php", {
			accion: "listaArticulos",
			seccion: seccionActual					
		},
		function(data, textStatus){
			console.log(data);
		});
		*/
		
		$('#tablaRegistros').dataTable( 		
		{	
			"ajax": {
	            "url": "./modulos/basico/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaArticulos", 
					   "seccion": seccionActual// leeParametro('seccion')
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
		jQuery.post("./modulos/basico/dk-logica.php", {
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
				
		
	function ocultarCamposFormulario(data) {
		var dataCampos = JSON.parse(data);
		
		$('#campo_Titulo, #campo_SubTitulo, #campo_Cuerpo, #campo_CuerpoAvance, #campo_FechaPublicacion, #campo_Imagen, #campo_Archivo, #campo_CampoURL, #campo_CampoExtra').hide();
		
		if (dataCampos['ch_CampoTitulo'] == 1)
			$('#campo_Titulo').show();
			
		if (dataCampos['ch_CampoSubTitulo'] == 1)
			$('#campo_SubTitulo').show();
			
		if (dataCampos['ch_CampoCuerpo'] == 1)
			$('#campo_Cuerpo').show();
			
		if (dataCampos['ch_CampoCuerpoAvance'] == 1)
			$('#campo_CuerpoAvance').show();
			
		if (dataCampos['ch_CampoFechaPublicacion'] == 1)
			$('#campo_FechaPublicacion').show();
			
		if (dataCampos['ch_CampoImagen'] == 1)
			$('#campo_Imagen').show();
			
		if (dataCampos['ch_CampoArchivo'] == 1)
			$('#campo_Archivo').show();
			
		if (dataCampos['ch_CampoURL'] == 1)
			$('#campo_CampoURL').show();
			
		if (dataCampos['ch_CampoCampoExtra'] == 1)
			$('#campo_CampoExtra').show();
		
	}
	
	function setFechaHoraDefecto(idCapa) {
	
		var now = new Date();
		var day = now.getDay();
		if (day < 10) {
			day = "0" + day;
		}
		var month = now.getMonth();
		if (month < 10) {
			month = "0" + month;
		}
		var hours = now.getHours();
		if (hours < 10) {
			hours = "0" + hours;
		}
		var minutes = now.getMinutes();
		if (minutes < 10) {
			minutes = "0" + minutes;
		}
		var seconds = now.getSeconds();
		if (seconds < 10) {
			seconds = "0" + seconds;
		}		
					
		//var fechaPublicacion = day + "/" + month + "/" +  now.getFullYear() + " " + now.getHours() + ":" + now.getMinutes() + ":" + now.getSeconds();
		var fechaPublicacion = now.getFullYear() + "-" + month + "-" + day + " " + now.getHours() + ":" + minutes + ":" + seconds;
		$("#"+idCapa).val(fechaPublicacion);
		
	}
	
	function limpiaForm(){
		$("#id").val("");
		$("#titulo").val("");
		$("#subtitulo").val("");
		$("#fechaPublicacion").val("");
		$("#cuerpo").val("");
		$("#cuerpoResumen").val("");
		$("#imagen").val("");
		$("#archivo").val("");
		$("#url").val("");
		$("#campoExtra").val("");
		$("#orden").val("");
		
		setFechaHoraDefecto('fechaPublicacion');

		if(typeof(tinyMCE) !== 'undefined') {
  			var length = tinyMCE.editors.length;
  			for (var i=length; i>0; i--) {
    			tinyMCE.editors[i-1].setContent('');
  			};
		}


		listaSecciones();
	}
	
	function modifica(idArticulo){
		
			//antes de modificar el artículo cargo la lista
			listaSecciones();
			
			limpiaForm();
			
			jQuery.post("./modulos/basico/dk-logica.php", {
			accion: "leeArticulo",
			id: idArticulo
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datosArticulo = JSON.parse(data);
				
					jQuery.post("./modulos/basico/dk-logica.php", {
						accion: "obtenerCamposSeccion",
						idSeccion: datosArticulo[0]['idSeccion']
					}, function(dataCampos, textStatus){
						ocultarCamposFormulario(dataCampos);
					});
					
					/*Cargo en los campos*/
					$("#id").val(datosArticulo[0]['id']);
					$("#titulo").val(datosArticulo[0]['titulo']);
					$("#subtitulo").val(datosArticulo[0]['subtitulo']);
					$("#fechaPublicacion").val(datosArticulo[0]['fechaPublicacion']);
					if (typeof datosArticulo[0]['cuerpo'] !== 'undefined') {
						tinyMCE.editors[0].setContent(datosArticulo[0]['cuerpo']);
					}
					
					if (typeof datosArticulo[0]['cuerporesumen'] !== 'undefined') {
						tinyMCE.editors[1].setContent(datosArticulo[0]['cuerporesumen']);
					}
					$("#cuerpoResumen").val(datosArticulo[0]['cuerpoResumen']);
					$("#imagen").val(datosArticulo[0]['imagen']);
					$("#archivo").val(datosArticulo[0]['archivo']);
					$("#url").val(datosArticulo[0]['url']);
					$("#campoExtra").val(datosArticulo[0]['campoExtra']);
					
					$("#orden").val(datosArticulo[0]['orden']);
					
					$('#estado option').each(function() {
						if ($(this).val() == datosArticulo[0]['estado']) {
							$(this).attr('selected','selected');
						}
					});					
					
					//selecciono la seccion					
					$("#seccion option[value="+ datosArticulo[0]['idSeccion'] +"]").attr("selected",true);
					
					$("#listaArticulos").fadeOut(500,function () {
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
		if (!$("#titulo").val())
		{
			alert("Debes rellenar el titulo");
		
		}else{ /*Si pasa los filtros, guarda :)*/
			
			if ($("#id").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			
			jQuery.post("./modulos/basico/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				titulo: $("#titulo").val(),
				subtitulo: $("#subtitulo").val(),
				fechaPublicacion: $("#fechaPublicacion").val(),
				cuerpo:  tinyMCE.editors[0].getContent(),
				cuerpoResumen: tinyMCE.editors[1].getContent(),
				imagen: $("#imagen").val(),
				archivo: $("#archivo").val(),
				url: $("#url").val(),
				campoExtra: $("#campoExtra").val(),
				orden: $("#orden").val(),
				seccion: $("#seccion").val(),
				estado: $("#estado").val()
				}, function(data, textStatus){
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						if (accion == "inserta") {
							$("#id").val(data);	
						}
						mensaje("Guardado correctamente","success","check", 5);
						
						$("#camposFormulario").fadeOut(500,function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaArticulos").fadeIn(500);
						});
					}
					
				}
			);
		}
	}
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/basico/dk-logica.php", {
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