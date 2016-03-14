	$(document).ready(function() {

		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
			
		$("#botonNuevo").click(function(e) {
			preparaFormulario();       
			$("#listaArticulos").fadeOut('fast');
			$("#camposFormulario").fadeIn('fast');
		});	
		
		actualizaListaArticulos();
		
	});
	
	function preparaFormulario(){		
		//cargo el formulario
		jQuery.post("./modulos/avanzado/dk-logica.php", {
			accion: "cargaFormulario",
			seccion: seccionActual
			}, function(data, textStatus){
					$("#contenidoPersonalizado").html(data);
					//Añado funcionalidades a los campos por ejemplo de imagenes o botones
					$("#contenidoPersonalizado").find(':input').each(function() {
						
						//tipoCampo = this.name.substr(0, this.name.indexOf('_'));
						this.id = this.name;
			
						/*Le añado estilos*/
						$(this).addClass("form-control");
						
						switch (this.name.substr(0, this.name.indexOf('_')))
						{
							case "imagen":
								$(this).after('<input type="button" value="Gestionar" onclick="ventanaPopup(\'./filemanager/index.php?button=none&type=image_customForm&name='+this.name+'\')" class="btn">');
								break;
								
							case "archivo":
								$(this).after('<input type="button" value="Gestionar" onclick="ventanaPopup(\'./filemanager/index.php?button=none&type=file_customForm&name='+this.name+'\')" class="btn">');
								break;
							
							case "editor":
								/*Añado el editor*/
								CKEDITOR.replace($(this).attr("id"),
								{
									  filebrowserBrowseUrl : './filemanager/index.php',
									  filebrowserWindowWidth : '600',
									  filebrowserWindowHeight : '500'
								});
								break;							
						}			
					});
					
			});

	}

	function actualizaListaArticulos(){
		
		jQuery.post("./modulos/avanzado/dk-logica.php", {
			accion: "listaArticulos",
			seccion: seccionActual
		}, function(data, textStatus){
			console.log(data);
		});
		
		$('#tablaRegistros').dataTable( 		
		{	
			"ajax": {
	            "url": "./modulos/avanzado/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaArticulos", 
					   "seccion": seccionActual// leeParametro('seccion')
					  }
	        },
		 	"columns": [
					{ "data": "id" },
					{ "data": "principal" },
					{ "data": "acciones","sortable":false }
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
			}
			);
		}
		// Me cargo la linea que se pulse
		$('#tablaRegistros').dataTable().on('click', '.delete', function (e) {
            e.preventDefault();            
            var nRow = $(this).parents('tr')[0];
            $('#tablaRegistros').dataTable().fnDeleteRow(nRow);            
        });
	
	function modifica(idArticulo){
			//cargo el id para luego guardar
			$("#id").val(idArticulo);
			preparaFormulario();
			
			jQuery.post("./modulos/avanzado/dk-logica.php", {
			accion: "leeArticulo",
			id: idArticulo
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datosArticulo = JSON.parse(data);						
					
					//asigno a cada campo su valor traido del json
					for(var campo in datosArticulo ){						  
						  $("#"+campo).val(datosArticulo[campo]);
					}
					
					$("#listaArticulos").fadeOut('fast');
					$("#camposFormulario").fadeIn('fast');
					
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}

	function guarda(){		
		//sino trae id está insertando
		if ($("#id").val() == 0) {
			accion = "inserta";
		}else{
			accion = "guarda";
		}
		
		//monto el json en texto para pasar los datos		
		var infoFormulario = "";
		$("#contenidoPersonalizado").find(':input').each(function(i){
			if ($(this).attr("name"))
			{
			   infoFormulario += JSON.stringify($(this).attr("name")) + ':' + JSON.stringify($(this).val()) + ',';
			}
		});
		
		infoFormulario = "{"+infoFormulario.substr(0, infoFormulario.length - 1)+ "}";
		
		jQuery.post("./modulos/avanzado/dk-logica.php", {
			accion: accion,
			id: $("#id").val(),
			idSeccion: seccionActual,
			datos: infoFormulario
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
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/avanzado/dk-logica.php", {
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