var ficheroSeleccionado;
var directorio = "";
var dominio = "";

	$(document).ready(function() {
		//inicializo eventos para el menú contextual 
		$("#menuCapa").mouseleave(function() {
				$("#menuCapa").hide('fast');
			});

		$("#menuCapa").blur(function(e){
			$("#menuCapa").hide('fast');
		});
		
		$('#ftpButton').click(function () {
			var type = getUrlVars()["type"];
			ventanaPopup('index.php?button=clicked&type='+type);
		});
		
		
		$('#insertarElemento').click(function () {
			//Obtenemos el parametro get BUTTON
			var type = getUrlVars()["type"];
			var nameInput = getUrlVars()["name"];
			
			var nUrl  = $('#urlFile').val();
			var nDescription  = $('#descriptionFile').val();
			var html = '';
			
			var width = $('#dimensionWidth').val();
			var height = $('#dimensionHeight').val();
			
			switch (type) {
				case 'image':
					var html = '<img alt="'+nDescription+'" width="'+width+'" height="'+height+'" src="'+nUrl+'"/>';
					window.opener.tinyMCE.get('cuerpo').execCommand('mceInsertContent', false, html);
				break;
				case 'file':
					var html = '<a href="'+nUrl+'">'+nDescription+'</a>';
					window.opener.tinyMCE.get('cuerpo').execCommand('mceInsertContent', false, html);
				break;
				case 'video':
					var html = '<a href="'+nUrl+'">'+nDescription+'</a>';
					window.opener.tinyMCE.get('cuerpo').execCommand('mceInsertContent', false, html);
				break;
				case 'image_form':
					//$('#imagen', window.parent.document).val(nUrl);
					window.opener.$("#imagen").val(nUrl);
				break;
				case 'file_form':
					window.opener.$("#archivo").val(nUrl);
				break;
				case 'image_customForm':
				case 'file_customForm':
					window.opener.$('#' + nameInput).val(nUrl);
				break;
			}
			
			window.close();
		});
		
		
		var button = getUrlVars()["button"];
		if (button == "none") {
			$('#todoCuerpo').hide();
			$('#formularioImagen').show();
			
			var type = getUrlVars()["type"];
			
			$('#dimensions').hide();
			if (type == 'image') {
				$('#dimensions').show();
			}
			$('#formularioImagen').show();
		} else {
			$('#todoCuerpo').show();
			$('#formularioImagen').hide();
			
			leeDirectorio("/");
			
		
			jQuery.post("ftp_funciones.php", {
				accion: 'leeDominio'
				}, function(data, textStatus){
					if (data == "KO")
					{
						alert("Ocurrió algún error al traer el dominio.");
					}else{
						dominio = data;
						}
				});
		}
	});
	
	function ventanaPopup(url) {
		var randomnumber = Math.floor((Math.random()*100)+1);
		newwindow = window.open(url,"_blank","Dk Web - Tu gestor de contenidos web",randomnumber,'height=800,width=800, scrollbars=no, toolbar=no, location=no, status=no, menubar=no,');
	}	
	
	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars;
	}
	
	function seleccionaFichero(){
		var url = document.location.href;
		if (directorio == "/") directorio = "";		
		var seleccionaFichero = $("#"+ficheroSeleccionado).text();
		var nUrl  = "http://" + dominio + "/" + ftp_rutabase + directorio + "/" +seleccionaFichero;
		
		window.opener.$("[id=urlFile]").val(nUrl);
		window.opener.$("[id=descriptionFile]").val(seleccionaFichero);
		window.close();
	}
		
		
	function eliminarFichero(){
		var eliminarFichero = $("#"+ficheroSeleccionado).text();
		
		jQuery.post("ftp_funciones.php", {
			accion: 'ftpEliminaFichero',
			eliminarFichero: eliminarFichero
			}, function(data, textStatus){
				if (data == "KO")
				{
					alert("Ocurrió algún error y no se pudo eliminar el archivo.");
				}else{
//					alert("Archivo eliminado correctamente");
					cargaDirectorio(data);
					}
			});
		}
	
	function muestraMenuContextual(idFichero){
		 	ficheroSeleccionado = idFichero;
			var posx = $("#"+idFichero).position().left;
			var posy = $("#"+idFichero).position().top;
			$("#ficheroDeMenu").html('[ '+$("#"+idFichero).text()+' ]');
			$("#menuCapa").css("top", posy - 1);
			$("#menuCapa").css("left", posx - 1);
			$("#menuCapa").show('fast');
		}
		
	function cargaDirectorio(ruta){
		$("#tablaRegistros").dataTable().fnDestroy();
		leeDirectorio(ruta);
		}
		
	function leeDirectorio(ruta){
		directorio = ruta;
		
		jQuery.post("./ftp_funciones.php", {
			accion: "ftpLeeDirectorio",
			"ruta": ruta				
		},
		function(data, textStatus){
			console.log(data);
		});
		
		$('#tablaRegistros').dataTable( 		
		{	
			"ajax": {
	            "url": "./ftp_funciones.php",
    	        "type": "POST",
				data: {"accion":"ftpLeeDirectorio", 
					   "ruta": ruta
					  }
	        },
		 	"columns": [
					{ "data": "nombre" },				
					{ "data": "tamaño" },
					{ "data": "fecha" }
					],
			//"bRetrieve": true,
			"bDeferRender": true,
			"oLanguage": {
			"iDisplayLength": 50,
			"iDisplayStart" : 50,
			"sLengthMenu" : 'Mostrar <SELECT>' +
				'<Option value = "50"> 50 </ option>' +
				'<Option value = "100"> 100 </ option>' +			
				'<Option value = "- 1"> Todo </ option>' +
				'</ select> registros',
			"sEmptyTable": "No hay archivos disponibles",
			"sInfo": "Hay _TOTAL_ archivos/carpetas. Mostrando de (_START_ a _END_)",
			"sLoadingRecords": "Por favor espera - Cargando...",
			"sSearch": "Buscar:",			
     		 "oPaginate": {
		        "sLast": "Última página",
				"sFirst": "Primera",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"				
		      		}			
			    }
			});
	}
	
	function creaCarpeta(){
		var nuevaCarpeta = $("#nuevaCarpeta").val();
		
		jQuery.post("ftp_funciones.php", {
			accion: 'ftpCreaDirectorio',
			nuevaCarpeta: nuevaCarpeta
			}, function(data, textStatus){
				if (data == "KO")
				{
					alert("Ocurrió algún error al crear el directorio");
				}else{
					alert("Directorio creado con éxito");
					}
			});
		}
	
	function subirArchivo()
		{
			//información del formulario
			var formData = new FormData($(".formulario")[0]);
	
			//hacemos la petición ajax  
			$.ajax({
				url: 'upload.php',
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				//mientras enviamos el archivo
				beforeSend: function(){
					//no hgo nada
				},
				//una vez finalizado correctamente
				success: function(data){
					alert("se ha subido > " + data);				
				},
				//si ha ocurrido un error
				error: function(){
					alert("ha petao");
				}
			});
		};
		
	function activaMenu(idMenu){
		 $("#"+idMenu).toggle("slow");
		}
		