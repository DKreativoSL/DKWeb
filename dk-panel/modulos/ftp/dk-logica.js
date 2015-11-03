$('#iframeFTP').load(function(){
	rellenarDatosFTP(this);
});

function rellenarDatosFTP(iframe) {
	
	jQuery.post("./modulos/ftp/dk-logica.php", {
		accion: "datosFTP"				
	},
	function(data, textStatus){
		
		var dataJSON = JSON.parse(data);
		
		$(iframe).contents().find('input[name=ftp_host]').val(dataJSON[0].ftp_server);
		$(iframe).contents().find('input[name=ftp_port]').val('21');
		$(iframe).contents().find('input[name=ftp_user]').val(dataJSON[0].ftp_user);
		$(iframe).contents().find('input[name=ftp_pass]').val(dataJSON[0].ftp_pass);
		
		$(iframe).contents().find('#btnLogin').trigger("click");
	});
}