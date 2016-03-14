	$(document).ready(function() {

		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
			
		$("#botonNuevo").click(function(e) {
			limpiaForm();       
			$("#listaRegistros").fadeOut('fast', function () {
				$("#camposFormulario").fadeIn('fast');
			});	
		});		
		actualizaListaRegistros();
		
	});
	
	function actualizaListaRegistros(){		
		$('#tablaRegistros').dataTable( 		
		{			
			"ajax": {
	            "url": "./modulos/usuariosAdmin/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaRegistros"
					  }
	        },
		 	"columns": [
					{ "data": "nombre" },
					{ "data": "email" },
					{ "data": "tlf1" },
					{ "data": "fechaAlta" },
					{ "data": "fechaBaja" },
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
			});
			
		// Me cargo la linea que se pulse
		$('#tablaRegistros').dataTable().on('click', '.delete', function (e) {
            e.preventDefault();            
            var nRow = $(this).parents('tr')[0];
            $('#tablaRegistros').dataTable().fnDeleteRow(nRow);            
        });			
		}
				
		
	function limpiaForm(){
		/*limpio*/
		//Limpiamos todos los inputs
		$('#camposFormulario input[type=text]').each(function () {
			$(this).val('');	
		});
		
		$('#camposFormulario input[type=password]').each(function () {
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
		
		limpiaForm();
		
		jQuery.post("./modulos/usuariosAdmin/dk-logica.php", {
			accion: "leeRegistro",
			id: idRegistro
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datos = JSON.parse(data);
					
					$("#id").val(datos[0]['id']);
					$("#email").val(datos[0]['email']);
					$("#clave").val(datos[0]['clave']);
					$("#nombre").val(datos[0]['nombre']);
					$("#nif").val(datos[0]['nif']);
					$("#direccion").val(datos[0]['direccion']);
					$("#cp").val(datos[0]['cp']);
					$("#poblacion").val(datos[0]['poblacion']);
					$("#provincia").val(datos[0]['provincia']);
					$("#tlf1").val(datos[0]['tlf1']);
					$("#tlf2").val(datos[0]['tlf2']);
					$("#sobreti").val(datos[0]['sobreti']);
				
					/*
					$("#menuPermisoContenidoWeb").prop("checked" , parseInt(datos[0]['menuContenidoWeb']));
					$("#menuPermisoConfiguracion").prop("checked" , parseInt(datos[0]['menuConfiguracion']));
					$("#menuPermisoSecciones").prop("checked" , parseInt(datos[0]['menuSecciones']));
					$("#menuPermisoParametros").prop("checked" , parseInt(datos[0]['menuParametros']));
					$("#menuPermisoUsuarios").prop("checked" ,parseInt( datos[0]['menuUsuarios']));
					$("#menuPermisoCorreos").prop("checked" , parseInt(datos[0]['menuCorreos']));
					$("#menuPermisoInmobiliaria").prop("checked" , parseInt(datos[0]['menuInmobiliaria']));
					$("#menuPermisoInmoApuntes").prop("checked" , parseInt(datos[0]['menuInmoApuntes']));
					$("#menuPermisoInmoClientes").prop("checked" , parseInt(datos[0]['menuInmoClientes']));
					$("#menuPermisoInmoInmuebles").prop("checked" , parseInt(datos[0]['menuInmoInmuebles']));
					$("#menuPermisoInmoZonas").prop("checked" , parseInt(datos[0]['menuInmoZonas']));	
					*/
					
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
		if (!$("#nombre").val())
		{
			alert("Debes rellenar el nombre");
		
		}else{ /*Si pasa los filtros, guarda :)*/
		
			//sino trae id está insertando
			if ($("#id").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}

			jQuery.post("./modulos/usuariosAdmin/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				email: $("#email").val(),
				clave: $("#clave").val(),
				nombre: $("#nombre").val(),
				nif: $("#nif").val(),
				direccion: $("#direccion").val(),
				cp: $("#cp").val(),
				poblacion: $("#poblacion").val(),
				provincia: $("#provincia").val(),
				tlf1: $("#tlf1").val(),
				tlf2: $("#tlf2").val(),
				comentarios: $("#sobreti").val()
				
				/*
				menuPermisoContenidoWeb: $("#menuPermisoContenidoWeb").prop("checked"),
				menuPermisoConfiguracion: $("#menuPermisoConfiguracion").prop("checked"),
				menuPermisoSecciones: $("#menuPermisoSecciones").prop("checked"),
				menuPermisoParametros: $("#menuPermisoParametros").prop("checked"),
				menuPermisoUsuarios: $("#menuPermisoUsuarios").prop("checked"),
				menuPermisoCorreos: $("#menuPermisoCorreos").prop("checked"),
				menuPermisoInmobiliaria: $("#menuPermisoInmobiliaria").prop("checked"),
				menuPermisoInmoApuntes: $("#menuPermisoInmoApuntes").prop("checked"),
				menuPermisoInmoClientes:	$("#menuPermisoInmoClientes").prop("checked"),
				menuPermisoInmoInmuebles: $("#menuPermisoInmoInmuebles").prop("checked"),
				menuPermisoInmoZonas: $("#menuPermisoInmoZonas").prop("checked")
				*/
				}, function(data, textStatus){
					if (data != "KO")
					{
						$("#camposFormulario").fadeOut('fast', function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaRegistros").fadeIn('fast');
							mensaje("Registro guardado correctamente.","success","check", 5);
							$("#id").val('');
						});
						
					}else{
						mensaje("Ocurrió algún problema al guardar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
					}
					
				}
			);
		}
	}
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/usuariosAdmin/dk-logica.php", {
			accion: "elimina",
			id: idElimina
			}, function(data, textStatus){
				if (data == "OK")
				{
					mensaje("Registro eliminado correctamente.","success","check", 5);
					//$("#escritorio").load("./modulos/secciones/index.php");
					//cambian las secciones, actualizo el menú					
					//cargaMenuLateral();					
				}else{
					mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);		
	}
	
	function entrar(idUsuario){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/usuariosAdmin/dk-logica.php", {
			accion: "entrar",
			id: idUsuario
			}, function(data, textStatus){
				window.location.href = "index.php";
			}
		);		
	}