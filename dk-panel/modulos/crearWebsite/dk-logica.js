$(document).ready(function() {
	$("#botonCrearWebsite").click(function(e) {
		
		var formularioValidado = validaFormularioCrearWebsite(); 
		
		if (formularioValidado)
			crearWebsite(); 
    });
});
	
		
function limpiaForm(){
	/*limpio*/
	$("#id").val("");
	$("#email").val("");
}

function validaFormularioCrearWebsite() {
	
	var todoOK = true;
	
	if (!$("#nombreCliente").val()) {
		$("#nombreCliente").addClass('errorEncontrado');
		todoOK = false;
	} else {
		$("#nombreCliente").removeClass('errorEncontrado');
	}

	var emailUsuario = $("#email").val(); 
	if (!emailUsuario) {
		$("#email").addClass('errorEncontrado');
		todoOK = false;
		console.log('error');
	} else {
		comprobarSiExisteEmailUsuario(emailUsuario);
	}
	
	var passwordCliente1 = $("#clave").val();
	var passwordCliente2 = $("#clave2").val();
	 
	if (!passwordCliente1) {
		$("#clave").addClass('errorEncontrado');
		todoOK = false;
	} else {
		$("#clave").removeClass('errorEncontrado');
	}
	
	if (!passwordCliente2) {
		$("#clave2").addClass('errorEncontrado');
		todoOK = false;
	} else {
		$("#clave2").removeClass('errorEncontrado');
	}
	
	if (passwordCliente1.length > 0 && passwordCliente2.length > 0) {
		if (passwordCliente1 != passwordCliente2) {
			$("#clave").addClass('errorEncontrado');
			$("#clave2").addClass('errorEncontrado');
		} else {
			$("#clave").removeClass('errorEncontrado');
			$("#clave2").removeClass('errorEncontrado');
		}
	}

	var dominioWeb = $("#dominio").val();
	if (!dominioWeb) {
		$("#dominio").addClass('errorEncontrado');
		todoOK = false;
	} else {
		existeDominio = comprobarSiExisteDominioWeb(dominioWeb);
	}
	
	if ($("#dominio").hasClass('errorEncontrado') || $("#email").hasClass('errorEncontrado')) {
		todoOK = false;
	}
	
	return todoOK;
}

function comprobarSiExisteDominioWeb(dominioWeb) {
	jQuery.post("./modulos/crearWebsite/dk-logica.php", {
		'accion': 'comprobarDominioWeb',
		'dominioWeb': dominioWeb	
		}, function(data, textStatus){
			if (data == "1")
			{
				$("#dominio").addClass('errorEncontrado');
			} else {
				$("#dominio").removeClass('errorEncontrado');
			}
			
		}
	);	
}

function comprobarSiExisteEmailUsuario(email) {
	jQuery.post("./modulos/crearWebsite/dk-logica.php", {
		'accion': 'comprobarEmailUsuario',
		'email': email	
		}, function(data, textStatus){
			if (data == "1")
			{
				$("#email").addClass('errorEncontrado');
			} else {
				$("#email").removeClass('errorEncontrado');
			}
			
		}
	);	
}
	
function crearWebsite(){
	/*antes de nada valido los campos*/
	if (
		!$("#nombreCliente").val() ||
		!$("#email").val() ||
		!$("#clave").val() ||
		!$("#clave2").val() ||
		!$("#dominio").val()
		)
	{
		alert("Faltan campos por rellenar.");
	} else{
		
		var pass1 = $("#clave").val();
		var pass2 = $("#clave2").val();
		
		if (pass1 != pass2) {
			alert("Las contraseñas no coinciden.");
		} else {
			jQuery.post("./modulos/crearWebsite/dk-logica.php", {
				accion: 'crearWebsite',
				nombreCliente: $("#nombreCliente").val(),
				nif: $("#nif").val(),
				email: $("#email").val(),
				clave: $("#clave").val(),
				nombreWebsite: $("#nombreWebsite").val(),
				dominio: $("#dominio").val(),
				descripcion: $("#descripcion").val()	
				}, function(data, textStatus){
					console.log(data);
					if (data != "KO")
					{
						mensaje("Website y usuario creado correctamente.","success","check", 5);
						limpiaForm();
					}else{
						console.log(data);
						mensaje("Ocurrió algún problema al crear la website/usuario. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 5);
					}
					
				}
			);
		}
	}
}