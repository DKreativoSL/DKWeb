$(document).ready(function(){
	$("#botonEntrarLogin").click(function(e) {
		loginDKW();
	});
});

$(document).keypress(function(e) {
    if(e.which == 13) {
        loginDKW();
    }
});

function loginDKW() {
	jQuery.post("principal.php", {
		accion: 'accesoUsuario',
		email: $("#username").val(),
		clave: $("#password").val()
	}, function(data, textStatus){
		if (data == "OK"){
			window.location.href = "index.php";
		}else{
			alert("Acceso incorrecto");						
		}
	});
}
		
