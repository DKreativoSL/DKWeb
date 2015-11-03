	$(document).ready(function() {
		actualizaListaRegistros();
		
		$("#botonGuarda").click(function(e) {
           	guarda();
        });
		
		$('#recuperarSeccion').click(function () {
			var idToMove = $('#selectPopupRecuperar').val();
			var idRecuperar = $(this).attr('idSeccion');
			recuperarSeccion(idRecuperar,idToMove);
		});
	});
		
	function actualizaListaRegistros(){		
		$('#tablaRegistros').dataTable( 		
		{			
			"ajax": {
	            "url": "./modulos/trashSecciones/dk-logica.php",
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
		}
		

	function listaSecciones(){		
		jQuery.post("./modulos/secciones/dk-logica.php", {
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

	
	function popupRecuperar(idRecuperar) {
		
		$("#btn_" + idRecuperar).trigger("click");
		
		$('#recuperarSeccion').attr('idSeccion',idRecuperar);
		
		//selectPopupEliminar
		jQuery.post("./modulos/trashSecciones/dk-logica.php", {
			accion: "obtenerSecciones",
			id: idRecuperar
			}, function(data, textStatus){
				if (data != "KO") {
					$('#selectPopupRecuperar').html(data);
				}
			}
		);
	}
	
	function recuperarSeccion(idRecuperar,idToMove) {
		jQuery.post("./modulos/trashSecciones/dk-logica.php", {
			'accion': "recuperarSeccion",
			'id': idRecuperar,
			'idToMove': idToMove
			}, function(data, textStatus){
				if (data == "OK") {
					mensaje("Sección recuperada correctamente.","success","check", 5);
					
					//Actualizo la tabla de registros
					$('#tablaRegistros').dataTable()._fnAjaxUpdate();
					
					//Actualizo el menu lateral
					cargaMenuLateral();
				} else{
					mensaje("Ocurrió algún problema al recuperar la sección. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}
	
	function recuperar(idRecuperar){
		popupRecuperar(idRecuperar);
	}
	

	function modifica(idRegistro){
			//antes de modificar el artículo cargo la lista
			listaSecciones();
			
			jQuery.post("./modulos/secciones/dk-logica.php", {
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
										
					$("#listaRegistros").fadeOut(500,function () {
						$("#camposFormulario").fadeIn(500);
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
			jQuery.post("./modulos/secciones/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				descripcion: $("#descripcion").val(),
				orden: parseInt($("#orden").val()),
				privada: $("#privada").val(),
				seccion: $("#seccion").val(),
				tipo: $("#tipo").val()	
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
