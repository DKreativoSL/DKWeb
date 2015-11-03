	$(document).ready(function(){
			
		$("#botonEntrarLogin").click(function(e) {
			jQuery.post("principal.php", {
			accion: 'accesoUsuario',
			email: $("#username").val(),
			clave: $("#password").val()
			}, function(data, textStatus){
				console.log(data);
				if (data == "OK"){
					window.location.href = "index.php";
				}else{
					alert("Acceso incorrecto");						
				}
			});
		});
		
	});
		
