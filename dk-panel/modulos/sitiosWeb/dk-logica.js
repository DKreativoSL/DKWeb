	var puedoDesactivarPopupDuplicarWeb = false;
	
	$(document).ready(function() {
		
		$('#btn_confirmarDuplicarWeb').click(function () {
			duplicarWeb();
		});
		
		$('#nuevoUsuarioWeb').click(function () {
			limpiarFormularioVincularUsuario();
			$('#listaUsuariosWeb').fadeOut('fast',function () {
				obtenerUsuariosParaVincular();
				$('#formularioVincularUsuario').fadeIn('fast');
			});
		});
		
		$('#volverListadoWebsDesdeModificar').click(function () {
			$('#camposFormulario').fadeOut('fast',function () {
				$('#listaRegistros').fadeIn('fast');
			});
		});
		
		$('#volverListadoWebs').click(function () {
			$('#listaUsuariosWeb').fadeOut('fast',function () {
				$('#textoCabecera').html("<h1>Configuración > <small>sitiosWeb</small></h1>");
				$('#listaRegistros').fadeIn('fast');
			});
		});
		
		$('#volverListadoUsuariosDesdeFormularioEditarUsuarioVinculado').click(function () {
			$('#formularioEditarUsuarioVinculado').fadeOut('fast',function () {
				$('#listaUsuariosWeb').fadeIn('fast');
			});
		});
		
		$('#volverListadoUsuarios').click(function () {
			$('#formularioVincularUsuario').fadeOut('fast',function () {
				$('#listaUsuariosWeb').fadeIn('fast');
			});
		});
		
		$('#botonGuardarNuevoUsuarioYVincularlo').click(function () {
			var idWebsiteAVincular = $('#idWebsiteEditando').val();
			
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: 'crearUsuarioYVincularlo',
				idWebsite: idWebsiteAVincular,
				
				email:$('#frm_usuarioEmail').val(),
				password:$('#frm_usuarioPassword').val(),
				nombre:$('#frm_usuarioNombre').val(),
				nif:$('#frm_usuarioNIF').val(),
				direccion:$('#frm_usuarioDireccion').val(),
				cp:$('#frm_usuarioCP').val(),
				poblacion:$('#frm_usuarioPoblacion').val(),
				provincia:$('#frm_usuarioProvincia').val(),
				tlf1:$('#frm_usuarioTelf1').val(),
				tlf2:$('#frm_usuarioTelf2').val(),
				comentarios:$('#frm_usuarioComentario').val(),
				
				menuPermisoContenidoWeb: $("#frm_menuPermisoContenidoWeb").prop("checked"),
				menuPermisoConfiguracion: $("#frm_menuPermisoConfiguracion").prop("checked"),
				menuPermisoSecciones: $("#frm_menuPermisoSecciones").prop("checked"),
				menuPermisoParametros: $("#frm_menuPermisoParametros").prop("checked"),
				menuPermisoUsuarios: $("#frm_menuPermisoUsuarios").prop("checked"),
				menuPermisoCorreos: $("#frm_menuPermisoCorreos").prop("checked"),
				menuPermisoInmobiliaria: $("#frm_menuPermisoInmobiliaria").prop("checked"),
				menuPermisoInmoApuntes: $("#frm_menuPermisoInmoApuntes").prop("checked"),
				menuPermisoInmoClientes:	$("#frm_menuPermisoInmoClientes").prop("checked"),
				menuPermisoInmoInmuebles: $("#frm_menuPermisoInmoInmuebles").prop("checked"),
				menuPermisoInmoZonas: $("#frm_menuPermisoInmoZonas").prop("checked")
				
				}, function(data, textStatus){
					var dataJSON = JSON.parse(data);
					if (dataJSON.status == "OK") {
						$('#formularioVincularUsuario').fadeOut('fast',function () {
							$('#listaUsuariosWeb').fadeIn('fast');
							$('#tablaUsuariosWeb').dataTable()._fnAjaxUpdate();
							mensaje("Usuario creado y vinculado correctamente.","success","check", 5);
						});
					} else{
						mensaje("Ocurrió algún problema al guardar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
					}
				}
			);
			
			
		});
		
		$('#btn_guardarFormularioEditarUsuarioVinculado').click(function () {
			var idWebsiteAVincular = $('#idWebsiteEditando').val();
			var idUsuarioEditando = $('#editarUsuarioVinculado_id').val();
			
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: 'guardarUsuarioVinculado',
				idWebsite: idWebsiteAVincular,
				idUsuario: idUsuarioEditando,
				
				
				email:$('#editarUsuarioVinculado_email').val(),
				nombre:$('#editarUsuarioVinculado_nombre').val(),
				nif:$('#editarUsuarioVinculado_nif').val(),
				direccion:$('#editarUsuarioVinculado_direccion').val(),
				cp:$('#editarUsuarioVinculado_cp').val(),
				poblacion:$('#editarUsuarioVinculado_poblacion').val(),
				provincia:$('#editarUsuarioVinculado_provincia').val(),
				tlf1:$('#editarUsuarioVinculado_tlf1').val(),
				tlf2:$('#editarUsuarioVinculado_tlf2').val(),
				comentarios:$('#editarUsuarioVinculado_sobreti').val(),
				
				menuPermisoContenidoWeb: $("#editarUsuarioVinculado_menuPermisoContenidoWeb").prop("checked"),
				menuPermisoConfiguracion: $("#editarUsuarioVinculado_menuPermisoConfiguracion").prop("checked"),
				menuPermisoSecciones: $("#editarUsuarioVinculado_menuPermisoSecciones").prop("checked"),
				menuPermisoParametros: $("#editarUsuarioVinculado_menuPermisoParametros").prop("checked"),
				menuPermisoUsuarios: $("#editarUsuarioVinculado_menuPermisoUsuarios").prop("checked"),
				menuPermisoCorreos: $("#editarUsuarioVinculado_menuPermisoCorreos").prop("checked"),
				menuPermisoInmobiliaria: $("#editarUsuarioVinculado_menuPermisoInmobiliaria").prop("checked"),
				menuPermisoInmoApuntes: $("#editarUsuarioVinculado_menuPermisoInmoApuntes").prop("checked"),
				menuPermisoInmoClientes:	$("#editarUsuarioVinculado_menuPermisoInmoClientes").prop("checked"),
				menuPermisoInmoInmuebles: $("#editarUsuarioVinculado_menuPermisoInmoInmuebles").prop("checked"),
				menuPermisoInmoZonas: $("#editarUsuarioVinculado_menuPermisoInmoZonas").prop("checked")
				
				}, function(data, textStatus){
					var dataJSON = JSON.parse(data);
					if (dataJSON.status == "OK") {
						$('#formularioEditarUsuarioVinculado').fadeOut('fast',function () {
							$('#listaUsuariosWeb').fadeIn('fast');
							$('#tablaUsuariosWeb').dataTable()._fnAjaxUpdate();
							mensaje("Usuario actualizado correctamente.","success","check", 5);
						});
					} else{
						mensaje("Ocurrió algún problema al guardar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
					}
				}
			);
			
			
		});
		
		$('#botonGuardarVinculacionUsuario').click(function () {
			var idWebsiteAVincular = $('#idWebsiteEditando').val();
			var idUsuarioAVincular = $('#select_usuarioExistenteParaVincular option:selected').val();
			
			
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: 'vincularUsuarioConWeb',
				idWebsite: idWebsiteAVincular,
				idUsuario: idUsuarioAVincular,				
				
				menuPermisoContenidoWeb: $("#perm_menuPermisoContenidoWeb").prop("checked"),
				menuPermisoConfiguracion: $("#perm_menuPermisoSecciones").prop("checked"),
				menuPermisoSecciones: $("#perm_menuPermisoConfiguracion").prop("checked"),
				menuPermisoParametros: $("#perm_menuPermisoParametros").prop("checked"),
				menuPermisoUsuarios: $("#perm_menuPermisoUsuarios").prop("checked"),
				menuPermisoCorreos: $("#perm_menuPermisoCorreos").prop("checked"),
				menuPermisoInmobiliaria: $("#perm_menuPermisoInmobiliaria").prop("checked"),
				menuPermisoInmoApuntes: $("#perm_menuPermisoInmoApuntes").prop("checked"),
				menuPermisoInmoClientes:	$("#perm_menuPermisoInmoClientes").prop("checked"),
				menuPermisoInmoInmuebles: $("#perm_menuPermisoInmoInmuebles").prop("checked"),
				menuPermisoInmoZonas: $("#perm_menuPermisoInmoZonas").prop("checked")
				
				}, function(data, textStatus){
					var dataJSON = JSON.parse(data);
					if (dataJSON.status == "OK") {
						$('#formularioVincularUsuario').fadeOut('fast',function () {
							$('#listaUsuariosWeb').fadeIn('fast');
							$('#tablaUsuariosWeb').dataTable()._fnAjaxUpdate();
							mensaje("Usuario vinculado correctamente.","success","check", 5);
						});
					} else{
						mensaje("Ocurrió algún problema al guardar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
					}
				}
			);
		});
		
		$('#btn_confirmarDesvincularUsuario').click(function () {
			
			var idWebsite = $('#idWebsiteADesvincular').val().toString();
			var idUsuario = $('#idUsuarioADesvincular').val().toString();
			
			jQuery.post(
				"./modulos/sitiosWeb/dk-logica.php",
				{
					accion: "desvincularUsuario",
					idUsuario: idUsuario,
					idWebsite: idWebsite
				},
				function(data, textStatus) {
					var dataJSON = JSON.parse(data);
					
					if (dataJSON.status == "OK") {
						
						$('#tablaUsuariosWeb').dataTable()._fnAjaxUpdate();
						
						mensaje("Registro desvinculado correctamente.","success","check", 5);
						
						$('#popupConfirmarDesvincularUsuario').modal('hide');
											
					} else {
						mensaje("Ocurrió algún problema al desvincular el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 5);
					}
				}
			);
			
		});
		
		/*
		$('#ftp_contrasena').keyup(function() {
				validaPass($('#ftp_contrasena').val());
			}).focus(function() {
				validaPass($('#ftp_contrasena').val());				
				$('#pswd_info').show();
			}).blur(function() {
				$('#pswd_info').hide();
		});
		*/
		$("#botonGuarda").click(function(e) {
           guarda(); 
        });
		
		$("#desplegar").click(function(e) {
			//$(".FTP").toggle("slow");
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
	function popupDesvincularUsuario(idUsuario, idWebsite) {
		
		$('#idWebsiteADesvincular').val(idWebsite);
		$('#idUsuarioADesvincular').val(idUsuario);
		
		jQuery.post(
			"./modulos/sitiosWeb/dk-logica.php",
			{
				accion: "obtenerInfoUsuarioADesvincular",
				idUsuario: idUsuario,
				idWebsite: idWebsite
			},
			function(data, textStatus) {
				var dataJSON = JSON.parse(data);
				
				if (dataJSON.status == "OK") {
					$('#popupConfirmarDesvincularUsuario_nombre').html(dataJSON.usuario);
					$('#popupConfirmarDesvincularUsuario_web').html(dataJSON.website);
					
					$('#popupConfirmarDesvincularUsuario').modal('show');					
				}
			}
		);
	}
	
	//Funcion llamada desde el listado de websites para mostrar los usuarios vinculados a una web en una datatable
	function listadoUsuariosWeb(idWebSite) {
		jQuery.post(
			"./modulos/sitiosWeb/dk-logica.php",
			{
				accion: "obtenerInfoWebsite",
				idWebsite: idWebSite
			},
			function(data, textStatus) {
				var dataJSON = JSON.parse(data);
				if (dataJSON.status == "OK") {
					
					$('#idWebsiteEditando').val(idWebSite);
					
					$('#tablaUsuariosWeb').dataTable({
						"ajax": {
							"url": "./modulos/sitiosWeb/dk-logica.php",
			    	        "type": "POST",
							data: {
								"accion": "listaUsuariosWeb",
								"idWebsite": idWebSite
							}
				        },
					 	"columns": [
							{ "data": "idUsuario" },
							{ "data": "nombre" },
							{ "data": "email" },
							{ "data": "grupo" },
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
					
					$('#listaRegistros').fadeOut('fast',function () {
						$('#textoCabecera').html("<h1>SitiosWeb > <small>Usuarios de "+dataJSON.website+"</small></h1>");
						$('#listaUsuariosWeb').fadeIn('fast');
					});
				}
			}
		);
	}
	
	function duplicarWeb() {
		
		var idWebsite = $('#idWebADuplicar').val().toString();
		var idUsuarioAVincular = $('#usuarioAVincularDeLaWebADuplicar option:selected').val();
		
		if (idUsuarioAVincular == '\\"-1\\"') {
			$('#popupConfirmarDuplicarWeb').modal('hide');
			mensaje("No se puede duplicar una web si no ha seleccionado un usuario para vincularla","danger","warning", 5);
		} else {
		
			$('#popupConfirmarDuplicarWeb').modal('hide');
			$('#popupDuplicandoWeb').modal('show');
			
			puedoDesactivarPopupDuplicarWeb = false;
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: "duplicarWebsite",
				idWebsite: idWebsite,
				idUsuarioAVincular: idUsuarioAVincular
				}, function(data, textStatus) {
					if (data == "OK") {
						puedoDesactivarPopupDuplicarWeb = true;
						$('#popupDuplicandoWeb').modal('hide');
						$('#tablaRegistros').dataTable()._fnAjaxUpdate();
					} else {
						alert("Se ha producido un error al tratar de duplicar la web; Error: " + data);
					}
				});
			}	
	}
	
	function popupDuplicarWeb(idWebsite) {
		
		$('#idWebADuplicar').val(idWebsite);
		
		jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
			accion: "obtenerUsuariosParaDuplicarWeb",
			idWebsite: idWebsite
			}, function(data, textStatus) {
				
				var dataJSON = JSON.parse(data);
				
				if (dataJSON.status == "OK") {
					
					$('#popupConfirmarDuplicarWeb').modal('show');
					
					$('#usuarioAVincularDeLaWebADuplicar').html(dataJSON.data);
					$('#usuarioAVincularDeLaWebADuplicar').trigger('chosen:updated');
				}
			});
		/*
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
				}
			});
			*/
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
			
			$('[data-toggle=\"tooltip\"]').tooltip();
			
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
				
	function obtenerUsuariosParaVincular() {

		var idWebsiteEditando = $('#idWebsiteEditando').val();
		
		if (idWebsiteEditando.length > 0) {
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: "listaUsuariosNoVinculados",
				idWebsite: idWebsiteEditando,
				}, function(data, textStatus){
	
					var dataJSON = JSON.parse(data);
					
					if (dataJSON.status == 'OK') {
						$('#select_usuarioExistenteParaVincular').html(dataJSON.data);
						$('#select_usuarioExistenteParaVincular').trigger("chosen:updated");
					}
			});
		}		
	}
	function limpiarFormularioVincularUsuario() {
		//Limpiamos todos los inputs
		$('#formularioVincularUsuario input[type=text]').each(function () {
			$(this).val('');	
		});
		
		//Limpiamos todos los textarea
		$('#formularioVincularUsuario input[type=textarea]').each(function () {
			$(this).html('');	
		});
		
		//Limpiamos todos los checkbox
		$('#formularioVincularUsuario input[type=checkbox]').each(function () {
			$(this).removeAttr('checked');
		});
		
		//Limpiamos todos los selects
		$('#formularioVincularUsuario select').each(function () {
			$(this).children('option').each(function () {
				$(this).removeAttr('selected');
			});
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

	function modificaUsuarioWeb(idUsuarioWeb,idWebsite) {
		jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
			accion: "leeRegistroUsuarioWeb",
			idUsuario: idUsuarioWeb,
			idWebsite: idWebsite
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datos = JSON.parse(data);						
					
					/*Cargo en los campos*/
					$("#editarUsuarioVinculado_id").val(datos[0]['id']);
					$('#editarUsuarioVinculado_email').val(datos[0]['email']);
					$("#editarUsuarioVinculado_nombre").val(datos[0]['nombre']);
					$("#editarUsuarioVinculado_nif").val(datos[0]['nif']);
					$("#editarUsuarioVinculado_direccion").val(datos[0]['direccion']);
					$("#editarUsuarioVinculado_cp").val(datos[0]['cp']);
					$("#editarUsuarioVinculado_poblacion").val(datos[0]['poblacion']);
					$("#editarUsuarioVinculado_provincia").val(datos[0]['provincia']);
					$("#editarUsuarioVinculado_tlf1").val(datos[0]['tlf1']);
					$("#editarUsuarioVinculado_tlf2").val(datos[0]['tlf2']);
					$("#editarUsuarioVinculado_sobreti").val(datos[0]['comentarios']);

					//Permisos
					if (datos[0]['menuContenidoWeb'] == 1)
						$('#editarUsuarioVinculado_menuPermisoContenidoWeb').prop('checked',true);
						
					if (datos[0]['menuSecciones'] == 1)
						$('#editarUsuarioVinculado_menuPermisoSecciones').prop('checked',true);
						
					if (datos[0]['menuConfiguracion'] == 1)
						$('#editarUsuarioVinculado_menuPermisoConfiguracion').prop('checked',true);
						
					if (datos[0]['menuParametros'] == 1)
						$('#editarUsuarioVinculado_menuPermisoParametros').prop('checked',true);
						
					if (datos[0]['menuUsuarios'] == 1)
						$('#editarUsuarioVinculado_menuPermisoUsuarios').prop('checked',true);
						
					if (datos[0]['menuCorreos'] == 1)
						$('#editarUsuarioVinculado_menuPermisoCorreos').prop('checked',true);
						
					if (datos[0]['menuInmobiliaria'] == 1)
						$('#editarUsuarioVinculado_menuPermisoInmobiliaria').prop('checked',true);
						
					if (datos[0]['menuInmoApuntes'] == 1)
						$('#editarUsuarioVinculado_menuPermisoInmoApuntes').prop('checked',true);
						
					if (datos[0]['menuInmoClientes'] == 1)
						$('#editarUsuarioVinculado_menuPermisoInmoClientes').prop('checked',true);
						
					if (datos[0]['menuInmoInmuebles'] == 1)
						$('#editarUsuarioVinculado_menuPermisoInmoInmuebles').prop('checked',true);
						
					if (datos[0]['menuInmoZonas'] == 1)
						$('#editarUsuarioVinculado_menuPermisoInmoZonas').prop('checked',true);

					$("#listaUsuariosWeb").fadeOut('fast', function () {
						$("#formularioEditarUsuarioVinculado").fadeIn('fast');
					});
					
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
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
					/*
					$("#ftp_usuario").val(datos[0]['ftp_user']);
					$("#ftp_contrasena").val(datos[0]['ftp_pass']);
					$("#ftp_servidor").val(datos[0]['ftp_server']);
					
					if ($("#ftp_usuario").val()){
						$("#desplegar").attr("checked","checked");
						//$(".FTP").show("slow");
						$("#ftp_servidor").attr("disabled","disabled");
					}
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
			//$("#ftp_usuario").val($("#ftp_usuario").val().toString().toLowerCase());
		
			jQuery.post("./modulos/sitiosWeb/dk-logica.php", {
				accion: accion,
				id: $("#id").val(),
				nombre: $("#nombre").val(),
				descripcion: $("#descripcion").val(),
				dominio: $("#dominio").val(),
				usuarioPropietario: $("#listaUsuarios").val()
				/*
				ftp_user: $("#ftp_usuario").val(),
				ftp_pass: $("#ftp_contrasena").val(),
				ftp_server: $("#ftp_servidor").val()*/				
				}, function(data, textStatus){
					if (data != "KO")
					{
						mensaje("Registro guardado correctamente.","success","check", 5);
						$("#id").val(data);
						
						$('#tablaRegistros').dataTable()._fnAjaxUpdate();
						
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