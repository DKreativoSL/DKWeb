var seccionActual = 0;

$(document).ready(function(){
	
	InicializaMenu();
	
	$('body').tooltip({
	    selector: '[data-toggle=tooltip]',
	    container : 'body'
	});
	
});

	function ventanaPopup(url) {
		var randomnumber = Math.floor((Math.random()*100)+1);
		//newwindow=window.open(url,'Dk Web - Tu gestor de contenidos web','height=800,width=800, scrollbars=yes, toolbar=no, location=no, status=no, menubar=no,');
		newwindow = window.open(url,"_blank","Dk Web - Tu gestor de contenidos web",randomnumber,'height=800,width=800, scrollbars=no, toolbar=no, location=no, status=no, menubar=no,');
		//if (window.focus) {newwindow.focus()}
		//return false;
	}
	
	function ventanaPopupFTP(url) {
		var randomnumber = Math.floor((Math.random()*100)+1);
		newwindow = window.open(url,"_blank","Dk Web - Tu gestor de contenidos web",randomnumber,'height=800,width=800, scrollbars=no, toolbar=no, location=no, status=no, menubar=no,');
		
		jQuery.post("principal.php", {
			accion: 'cerrarSesion',
		}, function(data, textStatus){
			if (data == ""){
				alert("Ha ocurrido un error con la carga de su usuario, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
			}else{
				window.location.href = "login.php";
			}
		});
		
		//ftp_user
		//ftp_pass
		
	    newwindow.onload = function() {
	        setTimeout(function(){ alert('hola'); }, 2000);
	    }
	}
	
	function cerrarSesion(){
		jQuery.post("principal.php", {
			accion: 'cerrarSesion',
			}, function(data, textStatus){
				if (data == ""){
					alert("Ha ocurrido un error con la carga de su usuario, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
				}else{
					window.location.href = "login.php";
				}
			});
		}
		
	function cambiaSitioWeb(idSitio, nombreWeb){
		jQuery.post("principal.php", {
			accion: 'cambiaSitioWeb',
			idCambiaSitioWeb: idSitio
			}, function(data, textStatus){
				var sitiosWeb = JSON.parse(data);
				
				if (data == ""){
					alert("Ha ocurrido un error con la carga de su sitio web, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
				}else{
					cargaMenuLateral();
					$("#menuSitiosWebTitulo").html(sitiosWeb['nombre']+'&nbsp;');
				}
			});
		}
			
	function InicializaMenu(){
		//Relleno el menú de Sitios Webs
		jQuery.post("principal.php", {
			accion: 'traeSitiosWebUsuario',
		}, function(data, textStatus){
			if (data == "KO"){
				alert("Ha ocurrido un error con la carga de sus sitios webs, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
			}else{
				//console.log(data);
				var sitiosWeb = JSON.parse(data);
				
				if (sitiosWeb.length > 0 ) {
					$("#menuSitiosWebTitulo").html(sitiosWeb[0]['nombre']+'&nbsp;');
					$("#menuSitiosWeb").html("");
					var id = "";
					var nombre = "";
						
					for (x=0; x < sitiosWeb.length; x++)
					{
						id = sitiosWeb[x]["id"];
						nombre = sitiosWeb[x]['nombre'];
						$("#menuSitiosWeb").append('<li><a href="#" onclick=\"cambiaSitioWeb('+id+',\''+nombre+'\');"><i class="icon-docs"></i> '+nombre+'</a></li>');
					}
				}
			}
		});			
		
		//Cargo el nombre del usuario
		jQuery.post("principal.php", {
			accion: 'traeNombreUsuario',
		}, function(data, textStatus){
			//console.log(data);
			if (data == ""){
				alert("Ha ocurrido un error con la carga de su usuario, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
			}else{
				$("#menuNombreUsuario").html(data+'&nbsp;');						
			}
		});
		//Cargo las opciones del usuario del menú lateral
		cargaMenuLateral();
	}
		
	function cargaContenidoWeb(){
		/*Cargo las secciones por sitioweb */
		
		jQuery.post(
			"principal.php",
			{
				accion: 'traeSeccionesV2'
			},
			function(data, textStatus){
				$("#seccionesWeb").html(data);
			}
		);
		
		/*
		jQuery.post("principal.php", {
			accion: 'traeSecciones'
			}, function(data, textStatus){
				//console.log(data);
				var seccionesWeb = JSON.parse(data);
				if (data == ""){
					alert("Ha ocurrido un error con la carga de las secciones, pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
				} else{						
					if (seccionesWeb.length > 0) {
						for (x=0; x < seccionesWeb.length; x++)
						{
							if (seccionesWeb[x]['seccionPadre'] == 0)
							{
								$("#seccionesWeb").append('<li><a href="#" onclick="cargaSeccion('+seccionesWeb[x]['id']+','+seccionesWeb[x]['tipo']+',\''+seccionesWeb[x]['nombre']+'\')" ><i class="icon-pencil"></i> '+seccionesWeb[x]['nombre']+'<span id="submenu'+seccionesWeb[x]['id']+'"></span></a><ul id="menu'+seccionesWeb[x]['id']+'" class="sub-menu"></ul></li>');
							} else {
								$("#menu"+seccionesWeb[x]['seccionPadre']).append('<li><a href="#" onclick="cargaSeccion('+seccionesWeb[x]['id']+','+seccionesWeb[x]['tipo']+',\''+seccionesWeb[x]['nombre']+'\')" ><i class="icon-pencil"></i> '+seccionesWeb[x]['nombre']+'<span id="submenu'+seccionesWeb[x]['id']+'"></span></a><ul id="menu'+seccionesWeb[x]['id']+'" class="sub-menu"></ul></li>');
								
								//añado la clase para submenús
								$("#submenu"+seccionesWeb[x]['seccionPadre']).addClass("arrow");
							}
							
						}
					} else {
						$("#seccionesWeb").append('<li><a href="#" onclick="cargaContenido(\'estructura\')" ><i class="fa fa-plus"></i>Crear estructura web</a></li>');
					}
				}
			});
			*/
		}

	function cargaMenuLateral() {
		//Según los permisos de usuario añado las diferentes secciones
		jQuery.post("principal.php", {
			accion: 'cargaMenuLateral'
		}, function(data, textStatus){
			//console.log(data);
			var menuLateral = JSON.parse(data);
			if (data == ""){
				alert("Ha ocurrido un error con la carga del menú o no tiene ningún permiso. Pongase en contacto con Dkreativo (desarrollo@dkreativo.es)");
			}else{
				// Limpio el menú completo y añado la "cabecera del menú de Escritorio"
				$("#menuLateral").html(menuLateral);
				cargaContenidoWeb();
			}
		});		
	}
		
	function cargaSeccion(idSeccion, tipoSeccion, nombreSeccion){
		switch (tipoSeccion)
			{
				case 0:
				case 1:
				case 3:
					seccionActual = idSeccion;
					$("#textoCabecera").html("<h1>"+nombreSeccion+"</h1>");
					$("#escritorio").load("./modulos/basico/index.php");
				break;
				case 2:
					seccionActual = idSeccion;
					$("#textoCabecera").html("<h1>"+nombreSeccion+"</h1>");
					$("#escritorio").load("./modulos/avanzado/index.php");
				break;
				
				default:
					  alert("¿Qué intentas hacer? >:|");
				break;
			}
		}

	function cargaContenido(contenido){
		
		switch (contenido) {
			case 'inmobiliariaApuntes':
				textoCabecera = "<h1>Inmobiliaria <small>Apuntes</small></h1>";
			break;
			case 'inmobiliariaClientes':
				textoCabecera = "<h1>Inmobiliaria <small>Clientes</small></h1>";
			break;
			case 'inmobiliariaInmuebles':
				textoCabecera = "<h1>Inmobiliaria <small>Inmuebles</small></h1>";
			break;
			case 'inmobiliariaZonas':
				textoCabecera = "<h1>Inmobiliaria <small>Zonas</small></h1>";
			break;
			default:
				textoCabecera = "<h1>Configuración > <small>"+contenido+" </small></h1>";
			break;
		}
		
		$("#textoCabecera").html(textoCabecera);
		$("#escritorio").load("./modulos/"+contenido+"/index.php");
		$('.menuOptions').each(function () {
			$(this).removeClass('active');	
		});
		$('#menu_' + contenido).addClass('active');
	}

	/* Muestra mensajes en la parte superior molones
		mensaje: El texto que mostrará
		tipo: success, danger, warning, info
		icono: "", warning, check, user
	*/
	function mensaje(mensaje, tipo, icono, tiempo){		
		    Metronic.alert({
                container: "", // alerts parent container(by default placed after the page breadcrumbs)
                place: "append", // append or prepent in container 
                type: tipo,  // alert's type
                message: mensaje,  // alert's message
                close: 1, // make alert closable
                reset: 0, // close all previouse alerts first
                focus: 1, // auto scroll to the alert after shown
                closeInSeconds: tiempo, // auto close after defined seconds
                icon: icono // put icon before the message
            });
		}

	function leeParametro(key)	{
			key = key.replace(/[\[]/, '\\[');  
			key = key.replace(/[\]]/, '\\]');  
			var pattern = "[\\?&]" + key + "=([^&#]*)";  
			var regex = new RegExp(pattern);  
			var url = unescape(window.location.href);  
			var results = regex.exec(url);
			if (results === null) {  
				return null;  
			} else {  
				return results[1];  
			}  
		};

		




function AceptaSoloTextoYGuion(nombreControl) {
    
    $('#' + nombreControl).unbind('keypress');
    $('#' + nombreControl).bind('keypress', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if((code < 97 || code > 122) && (code < 65 || code > 90) && (code != 45)) return false; 
        else return true;
    });
}

function AceptaSoloTexto(nombreControl) {
    
    $('#' + nombreControl).unbind('keypress');
    $('#' + nombreControl).bind('keypress', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if((code < 97 || code > 122) && (code < 65 || code > 90)) return false; 
        else return true;
    });
}

function AceptaSoloNumeros(nombreControl) {
    
    $('#' + nombreControl).unbind('keypress');
    $('#' + nombreControl).bind('keypress', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (!((code > 47 && code < 58))) {  //Solo números
            return false;
        } else {
            return true;
        }
    });
}

function AceptaSoloNumerosYGuiones(nombreControl) {
    
    $('#' + nombreControl).unbind('keypress');
    $('#' + nombreControl).bind('keypress', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (code == 45) {
        	return true; //Aceptamos el guion
        }
        if (!(code > 47 && code < 58)) {  //Solo números
            return false;
        } else {
            return true;
        }
    });
}

function AceptaSoloNumerosYComa(nombreControl) {
    
    $('#' + nombreControl).unbind('keypress');
    $('#' + nombreControl).bind('keypress', function (e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if (!((code > 47 && code < 58) || code == 44 || code == 46 || code == 8 || code == 9)) {  //Solo números y coma y punto
            return false;
        } else {
            return true;
        }
    });
}

