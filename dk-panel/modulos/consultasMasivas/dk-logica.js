$(document).ready(function() {
	$('#botonLanzar').click(function () {
		lanzarUpdate();
	});
	
	$('#selectConsulta').on('change', function () {
		
    	var valueSelected = this.value;
    	
    	switch (valueSelected) {
    		case 'publicar_todo':
				var sql = 	'UPDATE articulos SET ' + 
							'estado = 1 ' + 
							'WHERE idSeccion IN (' + 
							'SELECT id ' + 
							'FROM secciones ' + 
							'WHERE idSitioWeb = ' + idSitioWeb + 
							');';
			break;
    		case 'publicar_borrador':
				var sql = 	'UPDATE articulos SET ' + 
							'estado = 1 ' +
							'WHERE estado = 0 ' +
							'AND idSeccion IN (' + 
							'SELECT id ' + 
							'FROM secciones ' + 
							'WHERE idSitioWeb = ' + idSitioWeb + 
							');';
			break;
    		case 'eliminar_borrador':
				var sql = 	'DELETE FROM articulos ' + 
							'WHERE estado = 0 ' +
							'AND idSeccion IN (' + 
							'SELECT id ' + 
							'FROM secciones ' + 
							'WHERE idSitioWeb = ' + idSitioWeb + 
							');';
			break;
			default:
				var sql = '';
			break;
    	}
    	
    	$('#sql').val(sql);
    	
	});
});

function lanzarUpdate() {
	
	var sql = $('#sql').val();

	jQuery.post("./modulos/consultasMasivas/dk-logica.php", {
		'accion': "lanzarUpdate",
		'sql': sql 
	}, function(data, textStatus){
		if (data == "OK") {
			mensaje("Actualización ejecutada con exito","success","check", 2);
		} else {
			mensaje("Ocurrió algún problema al lanzar la actualización. <br>Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>"+data,"danger","warning", 0);
		}
	});
}