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
	            "url": "./modulos/usuarios/dk-logica.php",
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
		$('#tablaRegistros').dataTable().on('click', '.delete', function (e) {
            e.preventDefault();            
            var nRow = $(this).parents('tr')[0];
            $('#tablaRegistros').dataTable().fnDeleteRow(nRow);            
        });			
		}
		

	function listaSecciones(){		
		jQuery.post("./modulos/basic/dk-logica.php", {
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
		/*limpio*/
		$("#id").val("");
		$("#email").val("");
		$("#clave").val("");
		$("#nombre").val("");
		$("#nif").val("");
		$("#direccion").val("");
		$("#cp").val("");
		$("#poblacion").val("");
		$("#provincia").val("");
		$("#tlf1").val("");
		$("#tlf2").val("");
		$("#sobreti").val("");
		cargaPermisos(0);
	}


	function modifica(idRegistro){
		
			//antes de modificar el artículo cargo la lista
			listaSecciones();
			
			jQuery.post("./modulos/usuarios/dk-logica.php", {
			accion: "leeRegistro",
			id: idRegistro
			}, function(data, textStatus){
				
				if (data != "KO")
				{
					var datos = JSON.parse(data);						
					//id, email, clave, clave2, nombre, nif, direccion, cp, poblacion, provincia, tlf1, tlf2, sobreti
					
					/*Cargo en los campos*/
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
					
					$('#grupo option').each(function() {
						if ($(this).val() == datos[0]['grupo']) {
							$(this).attr('selected','selected');
						}
					});
					
					//selecciono en el combo según traigo de bbdd
					$("#privada option[value="+ datos[0]['privada'] +"]").attr("selected",true);

					/*Cargo los permisos del usuario*/
					cargaPermisos(datos[0]['id']);

					$("#listaRegistros").fadeOut('fast', function () {
						$("#camposFormulario").fadeIn('fast');
					});
					
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}

	function cargaPermisos(idRegistro){
		jQuery.post("./modulos/usuarios/dk-logica.php", {
			accion: "cargaPermisos",
			id: idRegistro
			}, function(data, textStatus){
				console.log(data);
				if (data != "KO")				
				{
					var permisos = JSON.parse(data);
					$("#permisos").html(permisos);
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


			var permisos = [];
			$('.permisos:checkbox').each(function () {
				var name = $(this).attr('id');
				var value = $(this).is(":checked");
				
				var tmp_permisos = {
					'name':name,
					'value':value
				};
				permisos.push(tmp_permisos);
			});

			jQuery.post("./modulos/usuarios/dk-logica.php", {
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
				comentarios: $("#sobreti").val(),
				grupo: $("#grupo").val(),
				'permisos': permisos			
				}, function(data, textStatus){
					if (data != "KO")
					{
						mensaje("Registro guardado correctamente.","success","check", 5);
						$("#id").val(data);
						
						$("#camposFormulario").fadeOut('fast', function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaRegistros").fadeIn('fast');
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
		jQuery.post("./modulos/usuarios/dk-logica.php", {
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