	$(document).ready(function() {
		$("#botonGuarda").click(function(e) {
           	guarda(); 
        });
			
		$("#botonNuevo").click(function(e) {
			limpiaForm();
			$('#id').val(0);
			$("#listaRegistros").fadeOut(500, function () {
				$("#camposFormulario").fadeIn(500);
			});
		});

		$('#botonDuplicar').click(function (e) {
			var idSeccion = $('#idSeccionDuplicar').val();
			var nombreSeccion = $('#seccionNew').val();
			var tipoDuplicacion = $('input[name=tipoDuplicacion]:checked', '#duplicarFormulario').val();
			
			jQuery.post("./modulos/academiaCursos/dk-logica.php", {
				'accion': "duplicarSeccion",
				'id': idSeccion,
				'nombre': nombreSeccion,
				'tipo': tipoDuplicacion		
			},
			function(data, textStatus){
				if (data == "OK") {
					mensaje("Sección duplicada correctamente.","success","check", 5);
					
					//Actualizo la tabla de registros
					$('#tablaRegistros').dataTable()._fnAjaxUpdate();
					
					//Actualizo el menu lateral
					cargaMenuLateral();
					
					$("#duplicarFormulario").fadeOut(500, function () {
						$("#listaRegistros").fadeIn(500);
					});
				}else{
					mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			});
			
			
		});

		actualizaListaRegistros();
		
	});
	
	function duplicar(idSeccion) {
		
		jQuery.post("./modulos/academiaCursos/dk-logica.php", {
			accion: "cargaDuplicacion",
			id: idSeccion					
		},
		function(data, textStatus){
			
			dataJSON = JSON.parse(data);
								
			if (data != "KO") {
				$('#idSeccionDuplicar').val(dataJSON[0].id);
				$('#seccionOld').val(dataJSON[0].nombre);
				$('#seccionNew').val('Copia de ' + dataJSON[0].nombre);
				
				$("#listaRegistros").fadeOut(500, function () {
					$("#duplicarFormulario").fadeIn(500);
				});
			}else{
				mensaje("Ocurrió algún problema al cargar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});
	}
	
	
	function GuardarFormularioSeccion()
	{
		jQuery.post("./modulos/academiaCursos/dk-logica.php", {
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
		jQuery.post("./modulos/academiaCursos/dk-logica.php", {
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
		
		
	function actualizaListaRegistros(){		
		$('#tablaRegistros').dataTable( 		
		{			
			"ajax": {
	            "url": "./modulos/academiaCursos/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaRegistros"
					  }
	        },
		 	"columns": [
					{ "data": "id" },
					{ "data": "nombre" },
					{ "data": "temas" },
					{ "data": "alumnos" },										
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
		
	function limpiaForm(){
		//Limpiamos todos los inputs
		$('#camposFormulario input[type=text]').each(function () {
			$(this).val('');	
		});
		//Limpiamos todos los textarea
		$('#camposFormulario input[type=textarea]').each(function () {
			$(this).html('');	
		});
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


	function modifica(idRegistro){
		jQuery.post("./modulos/academiaCursos/dk-logica.php", {
			accion: "leeRegistro",
			id: idRegistro
		}, function(data, textStatus){
			if (data != "KO") {
				var datos = JSON.parse(data);						
					
				/*Cargo en los campos*/
				$("#id").val(datos[0]['id']);
				$("#nombre").val(datos[0]['nombre']);
				$("#descripcion").val(datos[0]['descripcion']);					
										
				$("#listaRegistros").fadeOut(500,function () {
					$("#camposFormulario").fadeIn(500);
				});					
			}else{
				mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		});
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
			jQuery.post("./modulos/academiaCursos/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				descripcion: $("#descripcion").val()	
				}, function(data, textStatus){
					console.log(data);
					if (data != "KO")
					{
						//actualizo el ID con el valor recibido
						$("#id").val(data);					
						mensaje("Registro guardado correctamente.","success","check", 5);
						
						$("#camposFormulario").fadeOut(500,function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaRegistros").fadeIn(500);
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
	
	function elimina(idElimina){
		jQuery.post("./modulos/academiaCursos/dk-logica.php", {
			accion: "eliminarTodo",
			id: idElimina
			}, function(data, textStatus){
				if (data == "OK") {
					mensaje("Registro eliminado correctamente.","success","check", 5);
					$("#escritorio").load("./modulos/academiaCursos/index.php");
					//cambian las secciones, actualizo el menú					
					cargaMenuLateral();					
				}else{
					mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}