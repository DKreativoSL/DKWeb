function cambiarFlecha(idRegistro) {
	var idFlecha = idRegistro
	
	if ($('#flecha-' + idFlecha).hasClass('glyphicon-chevron-right')) {
		$('#flecha-' + idFlecha).removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down');
	} else {
		$('#flecha-' + idFlecha).removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
	}
}

$(document).ready(function() {

		
	$('#moverEliminar').click(function () {
		var idToMove = $('#selectPopupEliminar option:selected').val();
		var idElimina = $(this).attr('idElimina');
		moverContenidoEliminarSeccion(idElimina,idToMove);
	});
		
	$('#eliminarTodo').click(function () {
		var idElimina = $(this).attr('idElimina');
		eliminarTodo(idElimina);
	});
		
	//pongo editor a la caja de texto del popup
	CKEDITOR.replace('formularioSeccion',
	{
		toolbar :[
				['Source','-','NewPage','Preview'],					
				['Undo','Redo','-','SelectAll'],
				['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
				'/',
				['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],					
				['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
				['Link','Unlink','Anchor'],
				['Image','Table']					
			],

		  filebrowserBrowseUrl : './filemanager/index.php',
		  filebrowserWindowWidth : '600',
		  filebrowserWindowHeight : '500'
	});
	
	$("#botonGuarda").click(function(e) {
       	guarda(); 
    });
		
	$("#botonNuevo").click(function(e) {
		limpiaForm();
		$("#listaRegistros").fadeOut('fast', function () {
			$("#camposFormulario").fadeIn('fast');	
		});
	});
		
	//si el tipo de sección es 2 (Personalizado) activo o no el botón de generar formulario
	$("#tipo").click(function(e) {			
		if ($("#tipo").val() == 2) {
			if ($("id").val() > 0) {
				cargaFormulario();
			}
			$("#capaFormularioSeccion").show("fast");
		}else{
			$("#capaFormularioSeccion").hide("slow");
		}
	});
	
	$('#botonDuplicar').click(function (e) {
		var idSeccion = $('#idSeccionDuplicar').val();
		var nombreSeccion = $('#seccionNew').val();
		var tipoDuplicacion = $('input[name=tipoDuplicacion]:checked', '#duplicarFormulario').val();
		
		jQuery.post("./modulos/estructura/dk-logica.php", {
			'accion': "duplicarSeccion",
			'id': idSeccion,
			'nombre': nombreSeccion,
			'tipo': tipoDuplicacion		
		},
		function(data, textStatus){
			if (data == "OK") {
				mensaje("Sección duplicada correctamente.","success","check", 5);
				
				//Actualizo la tabla de registros
				actualizaListaRegistros();
				
				//Actualizo el menu lateral
				cargaMenuLateral();
				
				$("#listaRegistros").fadeIn('fast', function () {
					$("#duplicarFormulario").fadeOut('fast');
				});
			}else{
				mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});
	});

	actualizaListaRegistros();
		
});
	
function duplicar(idSeccion) {
	
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "cargaDuplicacion",
		id: idSeccion					
	},
	function(data, textStatus){
		
		dataJSON = JSON.parse(data);
							
		if (data != "KO") {
			$('#idSeccionDuplicar').val(dataJSON[0].id);
			$('#seccionOld').val(dataJSON[0].nombre);
			$('#seccionNew').val('Copia de ' + dataJSON[0].nombre);
			
			$("#listaRegistros").fadeOut('fast', function () {
				$("#duplicarFormulario").fadeIn('fast');
			});
		}else{
			mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
		}
	});
}
	
	
function GuardarFormularioSeccion()
{
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "guardaFormulario",
		id: $("#id").val(),
		formulario:  CKEDITOR.instances['formularioSeccion'].getData()
		}, function(data, textStatus){
			if (data != "KO")
			{
				//mensaje("Formulario guardado correctamente. Ya puedes utilizar el nuevo formulario desde contenido.","success","check", 5);						
				//$('#capaFormularioSeccion').hide("slow");
			}else{
				mensaje("Ocurrió algún problema al guardar el formulario. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});	
}
		
function cargaFormulario()
{
	//cargo el formulario
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "cargaFormulario",
		id: $("#id").val()					
		}, function(data, textStatus){						
			if (data != "KO")
			{
				CKEDITOR.instances['formularioSeccion'].setData(data);
				$('#capaFormularioSeccion').show("slow");
			}else{
				mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});
}
		
		
function actualizaListaRegistros() {
	
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "listaRegistros"					
	}, function(data, textStatus){						
		if (data != "KO") {
			$('#registros').html(data);
		} else{
			mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
		}
	});
	
	
	$('#tablaRegistros').dataTable( 		
	{			
		"ajax": {
            "url": "./modulos/estructura/dk-logica.php",
	        "type": "POST",
			data: {"accion":"listaRegistros"
				  }
        },
	 	"columns": [
				{ "data": "id" },
				{ "data": "nombre" },
				{ "data": "tipo" },
				{ "data": "orden" },										
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
		
		// Me cargo la linea que se pulse
		/*
		$('#tablaRegistros').dataTable().on('click', '.delete', function (e) {
        	e.preventDefault();            
        	var nRow = $(this).parents('tr')[0];
        	$('#tablaRegistros').dataTable().fnDeleteRow(nRow);            
    	});
    	*/			
	}
		

function listaSecciones(){		
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "listaSecciones",
		}, function(data, textStatus){

				var datosSecciones = JSON.parse(data);					
				var id = "";
				var nombre = "";
				
				$("#seccion").html('');
				
				$("#seccion").html('<option value="0">Sin sección padre</option>');
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
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#orden").val("");

	$("#seccion option[value=0]").attr("selected",true);
	$("#privada option[value=0]").attr("selected",true);
	$("#tipo option[value=0]").attr("selected",true);
	
	listaSecciones();
}


function modifica(idRegistro){
		//antes de modificar el artículo cargo la lista
		listaSecciones();
		
		jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "leeRegistro",
		id: idRegistro
		}, function(data, textStatus){
			if (data != "KO")
			{
				var datos = JSON.parse(data);						
				
				/*Cargo en los campos*/
				$("#id").val(datos[0]['id']);
				$("#nombre").val(datos[0]['nombre']);
				$("#descripcion").val(datos[0]['descripcion']);					
				$("#orden").val(datos[0]['orden']);
				
				//selecciono en el combo según traigo de bbdd
				$("#privada option[value="+ datos[0]['privada'] +"]").attr("selected",true);
				$("#seccion option[value="+ datos[0]['seccion'] +"]").attr("selected",true);
				$("#tipo option[value="+ datos[0]['tipo'] +"]").attr("selected",true);
				
				$('.camposFormulario').each(function () { $(this).removeAttr('checked'); });
				
				if ( datos[0]['ch_CampoTitulo'] == 1)
					$("#ch_CampoTitulo").attr("checked",true);
					
				if ( datos[0]['ch_CampoSubTitulo'] == 1)
					$("#ch_CampoSubTitulo").attr("checked",true);

				if ( datos[0]['ch_CampoCuerpo'] == 1)
					$("#ch_CampoCuerpo").attr("checked",true);
					
				if ( datos[0]['ch_CampoCuerpoAvance'] == 1)
					$("#ch_CampoCuerpoAvance").attr("checked",true);
					
				if ( datos[0]['ch_CampoFechaPublicacion'] == 1)
					$("#ch_CampoFechaPublicacion").attr("checked",true);
					
				if ( datos[0]['ch_CampoImagen'] == 1)
					$("#ch_CampoImagen").attr("checked",true);
					
				if ( datos[0]['ch_CampoArchivo'] == 1)
					$("#ch_CampoArchivo").attr("checked",true);
					
				if ( datos[0]['ch_CampoURL'] == 1)
					$("#ch_CampoURL").attr("checked",true);
					
				if ( datos[0]['ch_CampoCampoExtra'] == 1)
					$("#ch_CampoCampoExtra").attr("checked",true);
									
				$("#listaRegistros").fadeOut('fast',function () {
					$("#camposFormulario").fadeIn('fast');
				});
				
				//si el tipo es personalizado desbloqueo botón
				if ($("#tipo").val() == 2) {
					cargaFormulario();
					$("#capaFormularioSeccion").show("fast");
				}else{
					$("#capaFormularioSeccion").hide("fast");
				}					
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
	
	}else{		
		//sino trae id está insertando
		if ($("#id").val() == 0) {
			accion = "inserta";
		}else{
			accion = "guarda";
		}
		jQuery.post("./modulos/estructura/dk-logica.php", {
			accion: accion,
			id: 						$("#id").val(),
			nombre: 					$("#nombre").val(),
			descripcion: 				$("#descripcion").val(),
			orden: 						parseInt($("#orden").val()),
			privada: 					$("#privada").val(),
			seccion: 					$("#seccion").val(),
			tipo: 						$("#tipo").val(),
			ch_CampoTitulo: 			$("#ch_CampoTitulo").is(':checked'),
			ch_CampoSubTitulo: 			$("#ch_CampoSubTitulo").is(':checked'),
			ch_CampoCuerpo: 			$("#ch_CampoCuerpo").is(':checked'),
			ch_CampoCuerpoAvance: 		$("#ch_CampoCuerpoAvance").is(':checked'),
			ch_CampoFechaPublicacion:	$("#ch_CampoFechaPublicacion").is(':checked'),
			ch_CampoImagen: 			$("#ch_CampoImagen").is(':checked'),
			ch_CampoArchivo: 			$("#ch_CampoArchivo").is(':checked'),
			ch_CampoURL: 				$("#ch_CampoURL").is(':checked'),
			ch_CampoCampoExtra: 		$("#ch_CampoCampoExtra").is(':checked')
			}, function(data, textStatus){
				if (data != "KO")
				{
					//actualizo el ID con el valor recibido
					$("#id").val(data);
					//Si es tipo personalizado guardo formulario
					if ($("#tipo").val() == 2) {
						GuardarFormularioSeccion();
					}							
					mensaje("Registro guardado correctamente.","success","check", 5);
					
					$("#camposFormulario").fadeOut('fast',function () {
						actualizaListaRegistros();
						$("#listaRegistros").fadeIn('fast');
					});
					
					//cambian las secciones, actualizo el menú
					cargaMenuLateral();
				}else{
					mensaje("Ocurrió algún problema al guardar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				} //else					
			} //función de vuelta
		); //llamada JQuery
	}
}
	
function popupEliminar(idElimina) {
	
	//$("#btn_" + idElimina).trigger("click");
	$('#myModal').modal('show');
	
	$('#moverEliminar').attr('idElimina',idElimina);
	$('#eliminarTodo').attr('idElimina',idElimina);
	
	//selectPopupEliminar
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "obtenerSecciones",
		id: idElimina
		}, function(data, textStatus){
			if (data != "KO") {
				$('#selectPopupEliminar').html(data);
			}else{
				alert('error');
			}
		}
	);
}

function moverContenidoEliminarSeccion(idElimina,idToMove) {
	jQuery.post("./modulos/estructura/dk-logica.php", {
		'accion': "moverContenidoEliminarSeccion",
		'id': idElimina,
		'idToMove': idToMove
		}, function(data, textStatus){
			if (data == "OK") {
				mensaje("Registro eliminado correctamente.","success","check", 5);
				$("#escritorio").load("./modulos/estructura/index.php");
				//cambian las secciones, actualizo el menú					
				cargaMenuLateral();					
			} else{
				mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		}
	);
}
	
function eliminarTodo(idElimina) {
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "eliminarTodo",
		id: idElimina
		}, function(data, textStatus){
			if (data == "OK") {
				mensaje("Registro eliminado correctamente.","success","check", 5);
				$("#escritorio").load("./modulos/estructura/index.php");
				//cambian las secciones, actualizo el menú					
				cargaMenuLateral();					
			}else{
				mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		}
	);
}
	
function elimina(idElimina){
	
	 /*Si pasa los filtros, guarda :)*/
	jQuery.post("./modulos/estructura/dk-logica.php", {
		accion: "tieneContenido",
		id: idElimina
		}, function(data, textStatus){			
			if (data == "SI") {
				popupEliminar(idElimina);
			} else{
				eliminarTodo(idElimina);
			}
		}
	);
}