	var puedoDesactivarPopupDuplicarWeb = false;
	
	$(document).ready(function() {
		
		$('#ftp_contrasena').keyup(function() {
				validaPass($('#ftp_contrasena').val());
			}).focus(function() {
				validaPass($('#ftp_contrasena').val());				
				$('#pswd_info').show();
			}).blur(function() {
				$('#pswd_info').hide();
		});

		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
		
		$("#desplegar").click(function(e) {
			$(".FTP").toggle("slow");
        });
			
						
		$("#botonNuevo").click(function(e) {
			limpiaForm();       
			$("#listaRegistros").fadeOut('fast', function () {
				$("#camposFormulario").fadeIn('fast');
			});
		});		
		actualizaListaRegistros();
		
	    $('#popupDuplicandoWeb').on('hide.bs.modal', function (e) {
	    	if (puedoDesactivarPopupDuplicarWeb == false) {
	    		e.preventDefault();	
	    	}
	    });
		
	});
	
	
	
	function duplicarWeb(idWebsite) {
		$('#popupDuplicandoWeb').modal('show');
		puedoDesactivarPopupDuplicarWeb = false;
		jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
			accion: "duplicarWebsite",
			idWebsite: idWebsite
			}, function(data, textStatus) {
				if (data == "OK") {
					puedoDesactivarPopupDuplicarWeb = true;
					$('#popupDuplicandoWeb').modal('hide');
					$('#tablaRegistros').dataTable()._fnAjaxUpdate();
				} else {
					alert("Se ha producido un error al tratar de duplicar la web; Error: " + data);
					console.log(data);
				}
			});
	}
	
	
	function validaPass(pswd){
		
			//validate the length
			if (pswd.length < 8 ) {
				$('#length').show("fast");
			} else {
				$('#length').hide("fast");
			}	
			//validate letter
			if ( pswd.match(/[a-z]/) ) {
				$('#letter').hide("fast");
			} else {
				$('#letter').show("fast");
			}
	
			//validate capital letter
			if ( pswd.match(/[A-Z]/) ) {
				$('#capital').hide("fast");
			} else {
				$('#capital').show("fast");
			}
	
			//validate number
			if ( pswd.match(/\d/) ) {
				$('#number').hide("fast");
			} else {
				$('#number').show("fast");
			}	
		}
		
	function actualizaListaRegistros(){		
		$('#tablaRegistros').dataTable( 		
		{			
			"ajax": {
	            "url": "./modulos/sitiosWeb/dk-logica.php",
    	        "type": "POST",
				data: {"accion":"listaRegistros"
					  }
	        },
		 	"columns": [
					{ "data": "nombre" },
					{ "data": "descripcion" },
					{ "data": "dominio" },
					{ "data": "fechaCreacion" },
					{ "data": "token" },
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
		

	function listaUsuarios(){		
		jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
			accion: "listaUsuarios",
			}, function(data, textStatus){

					var datos = JSON.parse(data);					
					var id = "";
					var nombre = "";
					
					//$("#listaUsuarios").html('<option value="0">Admin Dkreativo</option>');	
					
					for (x=0; x < datos.length; x++)
					{
						id = datos[x]["id"];
						nombre = datos[x]['nombre'];
						$("#listaUsuarios").append('<option value="'+id+'">'+nombre+'</option>');						
					}
					$("#listaUsuarios option[value=1]").attr("selected",true);		
					
			});
		}
				
		
	function limpiaForm(){
		/*limpio*/
		$("#id").val("");
		$("#nombre").val("");
		$("#dominio").val("");
		$("#descripcion").val("");		
		$("#fechaCreacion").val("");
		$("#token").val("");
		listaUsuarios();
		}

	function modifica(idRegistro){

			//antes de modificar el artículo cargo la lista
			listaUsuarios();
			
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
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
					$("#dominio").val(datos[0]['dominio']);					
					$("#fechaCreacion").val(datos[0]['fechaCreacion']);
					$("#token").val(datos[0]['token']);
					
					$("#listaUsuarios option[value="+ datos[0]['idUsuarioPropietario'] +"]").attr("selected",true);
					
					$("#ftp_usuario").val(datos[0]['ftp_user']);
					$("#ftp_contrasena").val(datos[0]['ftp_pass']);
					$("#ftp_servidor").val(datos[0]['ftp_server']);
					
					if ($("#ftp_usuario").val()){
						$("#desplegar").attr("checked","checked");
						$(".FTP").show("slow");
						$("#ftp_servidor").attr("disabled","disabled");
						}
										
					$("#listaRegistros").fadeOut('fast', function () {
						$("#camposFormulario").fadeIn('fast');
					});
					
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}
/*
	function cargaPermisos(idRegistro){
		jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
			accion: "cargaPermisos",
			id: idRegistro
			}, function(data, textStatus){				
				if (data != "KO")				
				{
					var permisos = JSON.parse(data);
					$("#permisos").html(permisos);
				}
			}
		);
		
	}*/
	
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

			//pongo en minúscula el nombre de usuario			
			$("#ftp_usuario").val($("#ftp_usuario").val().toString().toLowerCase());
		
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				descripcion: $("#descripcion").val(),
				dominio: $("#dominio").val(),
				usuarioPropietario: $("#listaUsuarios").val(),
				ftp_user: $("#ftp_usuario").val(),
				ftp_pass: $("#ftp_contrasena").val(),
				ftp_server: $("#ftp_servidor").val()				
				}, function(data, textStatus){
					if (data != "KO")
					{
						mensaje("Registro guardado correctamente.","success","check", 5);
						$("#id").val(data);
						
						$("#camposFormulario").fadeOut('fast', function () {
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
		jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
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