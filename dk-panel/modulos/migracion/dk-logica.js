$(document).ready(function(){

	$('#inputImport').hide();

	$('#migracionExportar').click(function() {
		$.post("modulos/migracion/dk-logica.php", {
			accion: 'exportarWebsite',
			}, function(data, textStatus){
				if (data == "KO"){
					alert("Ha ocurrido un error con la carga de sus sitios webs, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
				}else{
					if (data.length > 0) {
						//Obtenemos la ruta actual
						var locationURL = location.href;
						//Eliminamos el # que puede causar error
						locationURL = locationURL.replace('#','');
						//Abrimos una nueva ventana con el archivo a descargar
						window.open(
						  locationURL + '/modulos/migracion/' + data,
						  '_blank'
						);	
					} else {
						alert('Esta web no tiene información');
					}
				}
			});	
	});
	
	$('#migracionImportar').click(function() {
		$('#inputImport').fadeToggle('fast');
	});
});