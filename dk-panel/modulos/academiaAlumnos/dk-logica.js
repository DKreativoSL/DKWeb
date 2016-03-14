var getTablaCursosAlumno;

$(document).ready(function() {	
	$("#fechaAlta, #fechaBaja").datetimepicker({
		format: 'dd/mm/yyyy',
		todayBtn: true,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});

	$("#botonGuarda").click(function(e) {
		guarda(); 
	});
	
	$('#guardarPopupCurso').click(function () {
		guardarCurso();
	});
	
	$('#botonNuevoCurso').click(function (e) {
		$('#popupCursos').modal('show');
		limpiaFormPopup();
		obtenerCursos(0);
	});
			
	$("#botonNuevoAlumno").click(function(e) {
		limpiaForm();
		$("#listaAlumnos").fadeOut('fast', function () {
			$("#camposFormulario").fadeIn('fast');
		});
	});		
	actualizaListaAlumnos();		
});

	function obtenerCursos(idCurso) {
		jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
			'accion': "obtenerCursos",
			'id': idCurso
		}, function(dataCursos, textStatus){
			$('#idCurso').html(dataCursos);
		});
	}


	function modificaCursoAlumno(idCursoAlumno) {
		jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
			'accion': "leerCurso",
			'id': idCursoAlumno
		}, function(data, textStatus){
			console.log(data);
			if (data != "KO")
			{
				limpiaFormPopup();
				
				var datosCurso = JSON.parse(data);
				
				//Cargo los cursos marcando el que estamos editando
				obtenerCursos(idCursoAlumno);
				
				$('#idAlumnoCurso').val(idCursoAlumno);
				$('#idUsuario').val(datosCurso[0]['idUsuario']);
				$("#fechaAlta").val(datosCurso[0]['fechaAlta']);
				$("#fechaBaja").val(datosCurso[0]['fechaBaja']);
				
				$('#popupCursos').modal('show');
				
			}
		});
	}
	
	function guardarCurso() {
		
		idAlumno = $("#idAlumno").val();
		
		if (idAlumno.toString().length <= 0) {
			guardaUsuarioYCurso();
		} else {
			if (!$("#fechaAlta").val()) {
				alert("Debes rellenar la fecha de alta");
			} else {
				if ($("#idAlumnoCurso").val() == 0) {
					accion = "insertaCurso";
				}else{
					accion = "guardaCurso";
				}
				jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
					accion: accion,
					id: $("#idAlumnoCurso").val(),
					idUsuario: $("#idAlumno").val(),
					idCurso: $("#idCurso").val(),
					fechaAlta: $("#fechaAlta").val(),
					fechaBaja: $("#fechaBaja").val(),
					}, function(data, textStatus) {
						if (data == "KO")
						{
							mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
						}else{
							if (accion == 'inserta') {
								$("#id").val(data);	
							}
							mensaje("Guardado correctamente","success","check", 5);
							
							//$('#tablaCursosAlumno').dataTable()._fnAjaxUpdate();
							getTablaCursosAlumno();
							
							$('#popupCursos').modal('hide');
							
						}
						
					}
				);
			}
		}
	}

	
	function eliminaCursoAlumno(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
			accion: "eliminaCursoAlumno",
			id: idElimina
			}, function(data, textStatus){
				if (data != "OK") {
					mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				} else {
					mensaje("Curso eliminado del alumno correctamente.","success","check", 5);
					getTablaCursosAlumno();
					//$('#tablaCursosAlumno').dataTable()._fnAjaxUpdate();					
				}
			}
		);		
	}
	

	function actualizaListaAlumnos() {		
		$('#tablaRegistros').dataTable({	
			"ajax": {
				"url": "./modulos/academiaAlumnos/dk-logica.php",
    	        "type": "POST",
				data: {
					"accion":"listaAlumnos"
				}
	        },
		 	"columns": [
				{ "data": "id" },
				{ "data": "nombre" },
				{ "data": "usuario" },
				{ "data": "cursos" },
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
	}
		// Me cargo la linea que se pulse
		$('#tablaRegistros').dataTable().on('click', '.delete', function (e) {
            e.preventDefault();            
            var nRow = $(this).parents('tr')[0];
            $('#tablaRegistros').dataTable().fnDeleteRow(nRow);            
        });

				
	
	function limpiaFormPopup(){
		//Limpiamos todos los inputs
		$('#formularioPopup input[type=text]').each(function () {
			$(this).val('');	
		});
		//Limpiamos todos los textarea
		$('#formularioPopup input[type=textarea]').each(function () {
			$(this).html('');	
		});
		//Limpiamos todos los checkbox
		$('#formularioPopup input[type=checkbox]').each(function () {
			$(this).removeAttr('checked');
		});
		//Limpiamos todos los selects
		$('#formularioPopup select').each(function () {
			$(this).children('option').each(function () {
				$(this).removeAttr('selected');
			});
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
	
	getTablaCursosAlumno = function () {
		var idAlumno = $('#idAlumno').val();	
		
		tablaCursosAlumno = $('#tablaCursosAlumno').dataTable({
			"ajax": {
				"url": "./modulos/academiaAlumnos/dk-logica.php",
				"type": "POST",
				data: {
					"accion":"listaCursosAlumno",
					'idAlumno': idAlumno
				}
			},
			"columns": [
				{ "data": "id" },
				{ "data": "Curso" },
				{ "data": "fechaAlta" },
				{ "data": "fechaBaja" },
				{ "data": "ultimoAcceso" },
				{ "data": "progreso" },
				{ "data": "acciones","sortable":false }
			],
			"bDeferRender": true,
			"bDestroy": true,
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
	};
	
	function modifica(idAlumno) {
			jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
				"accion":"listaCursosAlumno",
				'idAlumno': idAlumno
			}, function(data, textStatus){
				console.log(data);
			});
		
			jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
				'accion': "leerAlumno",
				'id': idAlumno
			}, function(data, textStatus){
				if (data != "KO")
				{
					var datosAlumno = JSON.parse(data);
					
					$('#idAlumno').val(idAlumno);
					/*Cargo en los campos*/
					$("#id").val(datosAlumno[0]['id']);
					$("#email").val(datosAlumno[0]['email']);
					//$("#clave").val(datosAlumno[0]['clave']);
					$("#nombre").val(datosAlumno[0]['nombre']);
					$("#nif").val(datosAlumno[0]['nif']);
					$("#direccion").val(datosAlumno[0]['direccion']);
					$("#cp").val(datosAlumno[0]['cp']);
					$("#poblacion").val(datosAlumno[0]['poblacion']);
					$("#provincia").val(datosAlumno[0]['provincia']);
					$("#tlf1").val(datosAlumno[0]['tlf1']);
					$("#tlf2").val(datosAlumno[0]['tlf2']);
					$("#sobreti").val(datosAlumno[0]['sobreti']);					
					$("#listaAlumnos").fadeOut('fast', function () {
						$("#camposFormulario").fadeIn('fast');	
					});
					
					getTablaCursosAlumno();
					
				}else{
					mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
				}
			}
		);
	}

	function guarda(){
		if (!$("#email").val()) {
			alert("Debes rellenar el email");
		
		} else {
			if ($("#idAlumno").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
				accion: accion,
				id: $("#idAlumno").val(),
				email: $("#email").val(),
				password: $("#clave").val(),
				password2: $("#clave2").val(),
				nombre: $("#nombre").val(),
				nif: $("#nif").val(),
				direccion: $("#direccion").val(),
				cp: $("#cp").val(),
				poblacion: $("#poblacion").val(),
				provincia: $("#provincia").val(),
				tlf1: $("#tlf1").val(),
				tlf2: $("#tlf2").val(),
				sobreti: $("#sobreti").val()
				}, function(data, textStatus){
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						if (accion == 'inserta') {
							$("#id").val(data);	
						}
						mensaje("Guardado correctamente","success","check", 5);
						
						$("#camposFormulario").fadeOut('fast', function () {
							$('#tablaRegistros').dataTable()._fnAjaxUpdate();
							$("#listaAlumnos").fadeIn('fast');
						});
						
					}
					
				}
			);
		}
	}
	
	function guardaUsuarioYCurso(){
		if (!$("#email").val()) {
			alert("Debes rellenar el email");
		
		} else {
			if ($("#idAlumno").val() == 0) {
				accion = "inserta";
			}else{
				accion = "guarda";
			}
			jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
				accion: accion,
				id: $("#idAlumno").val(),
				email: $("#email").val(),
				password: $("#clave").val(),
				password2: $("#clave2").val(),
				nombre: $("#nombre").val(),
				nif: $("#nif").val(),
				direccion: $("#direccion").val(),
				cp: $("#cp").val(),
				poblacion: $("#poblacion").val(),
				provincia: $("#provincia").val(),
				tlf1: $("#tlf1").val(),
				tlf2: $("#tlf2").val(),
				sobreti: $("#sobreti").val()
				}, function(data, textStatus){
					if (data == "KO")
					{
						mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
					}else{
						if (accion == 'inserta') {
							$("#idAlumno").val(data);
							guardarCurso();	
						}
					}
					
				}
			);
		}
	}
	
	
	function elimina(idElimina){
		 /*Si pasa los filtros, guarda :)*/
		jQuery.post("./modulos/academiaAlumnos/dk-logica.php", {
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