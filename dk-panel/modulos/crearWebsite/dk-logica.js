$(document).ready(function() {
	$("#botonCrearWebsite").click(function(e) {
       crearWebsite(); 
    });
});
	
		
function limpiaForm(){
	/*limpio*/
	$("#id").val("");
	$("#email").val("");
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