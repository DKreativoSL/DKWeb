var getTablaRegistrosInmuebles;
var getListaInmueblesCliente;

$(document).ready(function() {
	//Restricciones listado
	AceptaSoloNumeros('preciodesde');
	AceptaSoloNumeros('preciohasta');
	
	AceptaSoloNumeros('metrosdesde');
	AceptaSoloNumeros('metroshasta');
	
	AceptaSoloNumeros('habitadesde');
	AceptaSoloNumeros('habitahasta');
	
	AceptaSoloNumeros('banosdesde');
	AceptaSoloNumeros('banoshasta');
	
	AceptaSoloNumerosYGuiones('desde');
	AceptaSoloNumerosYGuiones('hasta');
	
	AceptaSoloNumerosYGuiones('desdebaja');
	AceptaSoloNumerosYGuiones('hastabaja');
	
	AceptaSoloNumeros('preciodesde');
	AceptaSoloNumeros('preciodesde');
	
	AceptaSoloNumeros('planta');
	
	$("#desde, #hasta, #desdebaja, #hastabaja").datepicker({
		format: 'dd-mm-yyyy',
		todayBtn: false,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});
	
	
	//Restricciones Formulario
	AceptaSoloTextoYGuion('inmueble_txtDireccion');
	
	AceptaSoloTexto('inmueble_txtLetra');
	AceptaSoloNumeros('inmueble_txtPlanta');
	
	AceptaSoloTextoYGuion('inmueble_txtPoblacion');
	AceptaSoloTextoYGuion('inmueble_txtProvincia');
		
		
	AceptaSoloNumeros('inmueble_txtSuperutil');
	AceptaSoloNumeros('inmueble_txtMetroster');
	AceptaSoloNumeros('inmueble_txtMetroscuadra');
	
	AceptaSoloNumeros('inmueble_txtHabitaciones');
	AceptaSoloNumeros('inmueble_txtBanos');
	AceptaSoloNumeros('inmueble_txtAseos');
	AceptaSoloNumeros('inmueble_txtSalon');
	AceptaSoloNumeros('inmueble_txtCocina');	
	AceptaSoloNumeros('inmueble_txtArmaempo');
	
	
	AceptaSoloNumeros('inmueble_txtPlantasedif');
	AceptaSoloNumeros('inmueble_txtTerraza');
	
	AceptaSoloNumeros('inmueble_txtAntiguedad');
	
	
	AceptaSoloNumerosYComa('inmueble_txtPreciopropie');
	AceptaSoloNumerosYComa('inmueble_txtPcomision');
	AceptaSoloNumerosYComa('inmueble_txtComision');
	AceptaSoloNumerosYComa('inmueble_txtHonorarios');
	
	AceptaSoloNumerosYComa('inmueble_txtPrecio');
	AceptaSoloNumerosYComa('inmueble_txtPrecioalquiler');
	AceptaSoloNumerosYComa('inmueble_txtComunidad');
	AceptaSoloNumerosYComa('inmueble_txtPreciogar');
		
	$('#camposFormularioInmuebles').hide();
	
	$('#filtrarInmuebles').click(function () {
		filtrarInmuebles();
	});
	
	$("#botonGuardaInmueble").click(function(e) {
		e.preventDefault();
		guardaInmuebles(false);
	});
	
	$('#guardarPopupInmueble').click(function (e) {
		e.preventDefault();
		guardaInmuebles(true);		
	});
	
	$('#cambiarUsuarioButton').click(function () {
		cambiarUsuario();
	});
	$('#cambiarPropietarioButton').click(function () {
		cambiarPropietario();
	});
				
	$('#botonVolverInmueble').click(function () {
		$("#camposFormularioInmuebles").fadeOut('fast', function () {
			$("#listaInmuebles").fadeIn('fast');
		});
	});
	
	//Si tenemos un cliente, cambiamos el formulario al POPUP
	if (idCliente > 0) {
		var formulario = $('#camposFormularioInmuebles').html();
		$('#inmuebles_body').html(formulario);
		$('#camposFormularioInmuebles').html('');
		$('#filtrosInmuebles').hide();
	}
	$('.chosen-select').chosen({});
	
				
	$("#botonNuevoInmueble").click(function(e) {
		e.preventDefault();
		
		if (idCliente > 0) {
			limpiaFormConCliente(idCliente,idUsuario);
			$('#botonGuardaInmueble').hide();
			$('#popupInmobiliaria').modal('show');
		} else {
			limpiaFormInmuebles();
			obtenerUsuarioInmueble(idUsuario);
			obtenerPropietarioInmueble(0);
			$("#listaInmuebles").fadeOut('fast', function () {
				$("#camposFormularioInmuebles").fadeIn('fast');
			});
		}
		obtenerNuevaReferencia();
		
		jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
			'accion': "obtenerZonas",
			'idZona': 0
		}, function(data, textStatus){
			var html = data;
			$('#inmueble_zona').html(html);
		});
	});
		
	if (idCliente > 0) {
		actualizaListaInmueblesCliente(idCliente);
	} else {
		actualizaListaInmuebles();
		inicializaFiltrosInmuebles();
	}
	//Cargamos el datatable con los propietarios
	inicializaPropietarioPopup();
	
	
	$("#inmueble_txtFechaalta").datepicker({
		format: 'dd-mm-yyyy',
		todayBtn: false,
		language: 'es',
		todayHighlight: true,
		weekStart: 1
	});
});

function mostrarImagenGrande(imagen) {
	var href = $(imagen).attr('src');
	$('#imageModal').attr('src',href);
	$('#modalImageGalley').modal('show');
}

function obtenerDocumentoInmueble(idInmueble) {
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerDocumentoInmueble",
		'idInmueble': idInmueble
	}, function(data, textStatus){
		$('#documentoInmueble').attr('src',data);
	});
}

function cambiarDocumentoInmueble() {
	var idInmueble = $("#inmueble_id").val();
	ventanaPopup('./modulos/inmobiliariaInmuebles/subir/subir.php?id=' + idInmueble);
}

function verDocumentoInmueble() {
	var idInmueble = $("#inmueble_id").val();
	ventanaPopup('./archivos/inmobiliaria/' + idInmueble + '.jpg');
}

function cambiarImagenesInmueble() {
	var idInmueble = $("#inmueble_id").val();
	ventanaPopup('./modulos/inmobiliariaInmuebles/uploadGallery/subir.php?id=' + idInmueble);	
}

function obtenerGaleriaImagenes() {
	var idInmueble = $("#inmueble_id").val();
	
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerGaleriaImagenes",
		'idInmueble': idInmueble
	}, function(data, textStatus){
		$('#galeriaImagenesInmueble').html(data);
	});	
}

function inicializaFiltrosInmuebles() {
	//Zonas
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerZonas",
		'idZona': 0
	}, function(data, textStatus){
		var html = '<option value="Todas">Todas</option>' + data;
		$('#lstZona').html(html);
	});	
	
	//Usuario
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerUsuariosOption",
		'idUsuario': 0
	}, function(data, textStatus){
		var html = '<option value="Todos">Todos</option>' + data;
		$('#lstUsuario').html(html);
	});
}

function filtrarInmuebles() {
	getTablaRegistrosInmuebles();
}

function obtenerNuevaReferencia() {
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerNuevaReferencia"
	}, function(data, textStatus){
		$('#inmueble_txtRef').val(data);
	});
	
}

function crearPDF() {
	var idInmueble = $("#inmueble_id").val();
	
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "crearPDF",
		'idInmueble': idInmueble
	}, function(data, textStatus){
		console.log(data);
		window.open(data);
	});		
}

function verPDF() {
	var idInmueble = $("#inmueble_id").val();
	var url = 'documentos/' + idInmueble + '.html';
	window.open(url);
}


function hideInputs() {
	//Limpiamos todos los inputs
	$('.inputs input[type=text]').each(function () {
		$(this).parent().hide();
	});
	//Limpiamos todos los textarea
	$('.inputs input[type=textarea]').each(function () {
		$(this).parent().hide();	
	});
	//Limpiamos todos los checkbox
	$('.inputs input[type=checkbox]').each(function () {
		$(this).parent().hide();
	});
	//Limpiamos todos los selects
	$('.inputs select').each(function () {
		$(this).parent().hide();
	});
}

function clearInputs() {
	//Limpiamos todos los inputs
	$('.inputs input[type=text]').each(function () {
		$(this).val('');
		$(this).parent().hide();
	});
	//Limpiamos todos los textarea
	$('.inputs input[type=textarea]').each(function () {
		$(this).html('');
		$(this).parent().hide();	
	});
	//Limpiamos todos los checkbox
	$('.inputs input[type=checkbox]').each(function () {
		$(this).removeAttr('checked');
		$(this).parent().hide();
	});
	//Limpiamos todos los selects
	$('.inputs select').each(function () {
		$(this).parent().hide();
	});	
}

function CambiaInmueble()
{		
	var sInmueble = $('#inmueble_lstinmueble option:selected').val();
	
	hideInputs();
	
	switch (sInmueble) {
		case 'casa':
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtAltura').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chVestibulo').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			$('#inmueble_chChimenea').parent().show();
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chExterior').parent().show();
			$('#inmueble_chEstreno').parent().show();
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chPatio').parent().show();
			$('#inmueble_chGaraje').parent().show();
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
			$('#inmueble_chVPO').parent().show();
		break;
		case 'apartamento':
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			$('#inmueble_txtPlantas').parent().show();
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chVestibulo').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			$('#inmueble_chAscensor').parent().show();
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chExterior').parent().show();
			$('#inmueble_chPortero').parent().show();
			$('#inmueble_chEstreno').parent().show();
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chPatio').parent().show();
			$('#inmueble_chGaraje').parent().show();
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
		break;
		case 'atico':
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			$('#inmueble_chAscensor').parent().show();
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chExterior').parent().show();
			$('#inmueble_chPortero').parent().show();
			$('#inmueble_chEstreno').parent().show();
			
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chGaraje').parent().show();
			
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
			$('#inmueble_chVPO').parent().show();
		break;
		case 'chalet':
			$('#inmueble_txtParcela').parent().show();
			$('#inmueble_txtMetros').parent().show();
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chVestibulo').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			
			$('#inmueble_chChimenea').parent().show();
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chEstreno').parent().show();
			
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chPatio').parent().show();
			$('#inmueble_chGaraje').parent().show();
			
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
		break;
		case 'cochera':
			$('#inmueble_txtMetroscuadra').parent().show();

			$('#inmueble_txtPlantasedif').parent().show();
		break;
		case 'duplex':
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtAltura').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			
			$('#inmueble_txtPlantas').parent().show();
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chVestibulo').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chEstreno').parent().show();
			
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chPatio').parent().show();
			$('#inmueble_chGaraje').parent().show();
			
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
			$('#inmueble_cbTipCasa').parent().show();
			$('#inmueble_chVPO').parent().show();
		break;
		case 'local':
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtAltura').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtCocina').parent().show();
			
			$('#inmueble_txtPlantasedif').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chExterior').parent().show();
			$('#inmueble_chEstreno').parent().show();
			
			$('#inmueble_chAlmacena').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
		break;
		case 'parcela':
			$('#inmueble_txtParcela').parent().show();
			$('#inmueble_txtMetros').parent().show();
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtAltura').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			
			$('#inmueble_chChimenea').parent().show();
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chEstreno').parent().show();
			
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chPatio').parent().show();
			$('#inmueble_chGaraje').parent().show();
			
			$('#inmueble_chAlmacena').parent().show();
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
		break;
		case 'piso':
			$('#inmueble_txtSuperutil').parent().show();
			$('#inmueble_txtMetroster').parent().show();
			$('#inmueble_txtMetroscuadra').parent().show();
			
			$('#inmueble_txtHabitaciones').parent().show();
			$('#inmueble_txtBanos').parent().show();
			$('#inmueble_txtAseos').parent().show();
			$('#inmueble_txtSalon').parent().show();
			$('#inmueble_txtCocina').parent().show();
			$('#inmueble_txtArmaempo').parent().show();
			
			$('#inmueble_txtPlantas').parent().show();
			$('#inmueble_txtPlantasedif').parent().show();
			$('#inmueble_txtTerraza').parent().show();
			
			$('#inmueble_chTelefono').parent().show();
			$('#inmueble_chTendedero').parent().show();
			$('#inmueble_chAmueblado').parent().show();
			$('#inmueble_chCocinaamu').parent().show();
			
			$('#inmueble_chAscensor').parent().show();
			$('#inmueble_chChimenea').parent().show();
			$('#inmueble_chReformado').parent().show();
			$('#inmueble_chExterior').parent().show();
			$('#inmueble_chPortero').parent().show();
			$('#inmueble_chEstreno').parent().show();
			
			$('#inmueble_chTenis').parent().show();
			$('#inmueble_chJardines').parent().show();
			$('#inmueble_chPiscina').parent().show();
			$('#inmueble_chPatio').parent().show();
			$('#inmueble_chGaraje').parent().show();
			
			$('#inmueble_chBuhardilla').parent().show();
			$('#inmueble_chTrastero').parent().show();
			$('#inmueble_chGasciudad').parent().show();
			
			$('#inmueble_txtCalefaccion').parent().show();
			$('#inmueble_txtCarpinext').parent().show();
			$('#inmueble_txtCarpinint').parent().show();
			
			$('#inmueble_txtSolados').parent().show();
			$('#inmueble_txtAntiguedad').parent().show();
			$('#inmueble_chVPO').parent().show();
		break;
		case 'terreno':
			$('#inmueble_txtParcela').parent().show();
			$('#inmueble_cbTipoTerreno').parent().show();
			$('#inmueble_cbAcceso').parent().show();
			
			$('#inmueble_cbTerreno').parent().show();
			$('#inmueble_chRiego').parent().show();
			$('#inmueble_chLuz').parent().show();
			$('#inmueble_chVallado').parent().show();
			$('#inmueble_chServidumbre').parent().show();
		break;
	}
}	

//Usuario
function cambiarUsuario() {
	$('#selectCambiarUsuario option:selected').each(function (e) {
		var idUsuario = $(this).val();
		var nombreUsuario =$(this).html(); 
		
		$('#inmueble_txtUsuarioId').val(idUsuario);
		$('#inmueble_txtUsuario').val(nombreUsuario);
	});
}
function cambiarUsuarioPopup() {
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerUsuariosOption",
		'idUsuario': idUsuario
	}, function(data, textStatus){
		$('#selectCambiarUsuario').html(data);
	});
}
//Propietario
function cambiarPropietario(idPropietario,nombrePropietario) {
	$('#inmueble_selectPropietario option').each(function () {
		if ($(this).val() == idPropietario) {
			$(this).attr('selected','selected');
		} else {
			$(this).removeAttr('selected');
		}
	});
	$("#inmueble_selectPropietario").trigger("chosen:updated");
}

function inicializaPropietarioPopup() {
	$('#tablaRegistrosPropietarios').dataTable({	
		"ajax": {
			"url": "./modulos/inmobiliariaInmuebles/dk-logica.php",
	        "type": "POST",
			data: {
				"accion":"listaPropietarios"
			}
        },
	 	"columns": [
			{ "data": "id" },
			{ "data": "cliente" },
			{ "data": "telefonos" },
			{ "data": "nif" },
			{ "data": "email" },
			{ "data": "acciones" }
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

function cambiarPropietarioPopup() {
	$('#tablaRegistrosPropietarios').dataTable()._fnAjaxUpdate();
}

var miPopup;

function llamaImagenes() { 
	miPopup = window.open("./modulos/inmobiliaria/inmuebles/upload/subir.php?ref=<?php echo $mid; ?>","dkweb","width=800,height=550,scrollbars=yes"); 
	miPopup.focus();
} 
	
function llamaPropietario() { 
	miPopup = window.open("./modulos/inmobiliaria/inmuebles/lstclientes.php","dkweb","width=600,height=350,scrollbars=yes"); 
	miPopup.focus(); 
} 
	
function llamaUsuario() { 
	miPopup = window.open("./modulos/inmobiliaria/inmuebles/lstusuario.php","dkweb","width=600,height=350,scrollbars=yes"); 
	miPopup.focus();
} 

function MuestraCostes() { 	
	$("#muestraCostes").toggle("fast");
}

function actualizaListaInmueblesCliente(idCliente) {
	
	var tablaRegistrosInmueblesCliente;
	/*
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "listaInmueblesCliente",
		'idCliente': idCliente
	}, function(data, textStatus){
		console.log('Resultado: ' + data);
	});
	
	*/
	getListaInmueblesCliente = function () {
		tablaRegistrosInmueblesCliente = $('#tablaRegistrosInmuebles').dataTable({
		"processing": true,
		"serverSide": true,
		"ajax": {
			"url": "./modulos/inmobiliariaInmuebles/dk-logica.php",
	        "type": "POST",
			data: {
				"accion":"listaInmueblesCliente",
				'idCliente': idCliente
			}
        },
	 	"columns": [
			{ "data": "id" },
			{ "data": "inmueble" },
			{ "data": "fechaAlta" },
			{ "data": "fechaMod" },
			{ "data": "zona" },
			{ "data": "direccion" },
			{ "data": "precio" },
			{ "data": "usuario" },
			{ "data": "acciones" }
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
	getListaInmueblesCliente();
}

function actualizaListaInmuebles() {
	var tablaRegistrosInmuebles;
	getTablaRegistrosInmuebles = function () {
		preciodesde 		= $('#preciodesde').val();
		preciohasta 		= $('#preciohasta').val();
		metrosdesde 		= $('#metrosdesde').val();
		metroshasta 		= $('#metroshasta').val();
		banosdesde 			= $('#banosdesde').val();
		banoshasta 			= $('#banoshasta').val();
		desde 				= $('#desde').val();
		hasta 				= $('#hasta').val();
		desdebaja 			= $('#desdebaja').val();
		hastabaja 			= $('#hastabaja').val();
		chAscensor 			= $('#chAscensor').is(':checked');
		chGaraje 			= $('#chGaraje').is(':checked');
		chPiscina 			= $('#chPiscina').is(':checked');
		chAlquiler 			= $('#chAlquiler').is(':checked');
		chVenta 			= $('#chVenta').is(':checked');
		chAlquilerCompra 	= $('#chAlquilerCompra').is(':checked');
		chTraspaso 			= $('#chTraspaso').is(':checked');
		chPromocion 		= $('#chPromocion').is(':checked');
		lstZona 			= $('#lstZona').val();
		lstTipo 			= $('#lstTipo').val();
		propietario 		= $('#propietario').val();
		planta 				= $('#planta').val();
		lstUsuario 			= $('#lstUsuario').val();
		cbEstado 			= $('#cbEstado').val();
		tlfpropietario 		= $('#tlfpropietario').val();
		/*
		jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
			"accion":"listaInmuebles",
			"preciodesde":preciodesde,
			"preciohasta":preciohasta,
			"metrosdesde":metrosdesde,
			"metroshasta":metroshasta,
			"banosdesde":banosdesde,
			"banoshasta":banoshasta,
			"desde":desde,
			"hasta":hasta,
			"desdebaja":desdebaja,
			"hastabaja":hastabaja,
			"chAscensor":chAscensor,
			"chGaraje":chGaraje,
			"chPiscina":chPiscina,
			"chAlquiler":chAlquiler,
			"chVenta":chVenta,
			"chAlquilerCompra":chAlquilerCompra,
			"chTraspaso":chTraspaso,
			"chPromocion":chPromocion,
			"lstZona":lstZona,
			"lstTipo":lstTipo,
			"propietario":propietario,
			"planta":planta,
			"lstUsuario":lstUsuario,
			"cbEstado":cbEstado,
			"tlfpropietario":tlfpropietario
		}, function(data, textStatus){
			console.log(data);
		});
		*/
		tablaRegistrosInmuebles = $('#tablaRegistrosInmuebles').dataTable({
			"processing": true,
			"serverSide": true,		
			"ajax": {
				"url": "./modulos/inmobiliariaInmuebles/dk-logica.php",
    	        "type": "POST",
				data: {
					"accion":"listaInmuebles",
					"preciodesde":preciodesde,
					"preciohasta":preciohasta,
					"metrosdesde":metrosdesde,
					"metroshasta":metroshasta,
					"banosdesde":banosdesde,
					"banoshasta":banoshasta,
					"desde":desde,
					"hasta":hasta,
					"desdebaja":desdebaja,
					"hastabaja":hastabaja,
					"chAscensor":chAscensor,
					"chGaraje":chGaraje,
					"chPiscina":chPiscina,
					"chAlquiler":chAlquiler,
					"chVenta":chVenta,
					"chAlquilerCompra":chAlquilerCompra,
					"chTraspaso":chTraspaso,
					"chPromocion":chPromocion,
					"lstZona":lstZona,
					"lstTipo":lstTipo,
					"propietario":propietario,
					"planta":planta,
					"lstUsuario":lstUsuario,
					"cbEstado":cbEstado,
					"tlfpropietario":tlfpropietario
				}
	        },
		 	"columns": [
				{ "data": "id" },
				{ "data": "inmueble" },
				{ "data": "fechaAlta" },
				{ "data": "fechaMod" },
				{ "data": "zona" },
				{ "data": "direccion" },
				{ "data": "precio" },
				{ "data": "usuario" },
				{ "data": "acciones" }
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
	getTablaRegistrosInmuebles();
}
		
function limpiaFormInmuebles(){
	//Limpiamos todos los inputs
	$('#camposFormularioInmuebles input[type=text]').each(function () {
		$(this).val('');	
	});
	//Limpiamos todos los textarea
	$('#camposFormularioInmuebles input[type=textarea]').each(function () {
		$(this).html('');	
	});
	//Limpiamos todos los checkbox
	$('#camposFormularioInmuebles input[type=checkbox]').each(function () {
		$(this).removeAttr('checked');
	});
	//Limpiamos todos los selects
	$('#camposFormularioInmuebles select').each(function () {
		$(this).children('option').each(function () {
			$(this).removeAttr('selected');
		});
	});
	//Limpiamos la imagen del documento
	$('#documentoInmueble').attr('src','');
	//Limpiamos la galeria de imagenes
	$('#galeriaImagenesInmueble').html('');
}

function limpiaFormConCliente(idCliente,idUsuario) {
	limpiaFormInmuebles();
	obtenerPropietarioInmueble(idCliente);
	obtenerUsuarioInmueble(idUsuario);
}

function obtenerPropietarioInmueble(idPropietario) {
	//Propietario
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerPropietario",
		'idPropietario': idPropietario
	}, function(data, textStatus){
		$('#inmueble_selectPropietario').html(data);
		$("#inmueble_selectPropietario").trigger("chosen:updated");
	});
}

function obtenerUsuarioInmueble(idUsuario) {
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "obtenerUsuariosOption",
		'idUsuario': idUsuario
	}, function(data, textStatus){
		$('#inmueble_selectUsuario').html(data);
		$("#inmueble_selectUsuario").trigger("chosen:updated");
	});
}

function modificaInmueble(idInmueble) {
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		'accion': "leerInmueble",
		'id': idInmueble
	}, function(data, textStatus){
		if (data != "KO") {
			
			limpiaFormInmuebles();
			var datosInmueble = JSON.parse(data);
			$("#inmueble_id").val(datosInmueble[0]['id']);
			
			$('#inmueble_lstinmueble option').each(function() {
				if ($(this).val() == datosInmueble[0]['inmueble']) {
					$(this).attr('selected','selected');
				}
			});
			
			//Limpiamos los inputs (dinamicos)
			clearInputs();
			//Cargamos los inputs dinamicos
			CambiaInmueble();
			
			$("#inmueble_txtRef").val(datosInmueble[0]['ref']);
			
			$("#inmueble_txtFechaalta").val(datosInmueble[0]['fechaalta']);
			$("#inmueble_txtDireccion").val(datosInmueble[0]['direccion']);
			
			jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
				'accion': "obtenerZonas",
				'idZona': datosInmueble[0]['zona']
			}, function(data, textStatus){
				var html = data;
				$('#inmueble_zona').html(html);
			});
			
			$("#inmueble_txtPortal").val(datosInmueble[0]['portal']);
			$("#inmueble_txtPlanta").val(datosInmueble[0]['planta']);
			$("#inmueble_txtLetra").val(datosInmueble[0]['letra']);
			
			$("#inmueble_txtPoblacion").val(datosInmueble[0]['poblacion']);
			$("#inmueble_txtProvincia").val(datosInmueble[0]['provincia']);
			
			//Propietario
			obtenerPropietarioInmueble(datosInmueble[0]['propietario']);
			
			//Usuario
			obtenerUsuarioInmueble(datosInmueble[0]['usuario']);
			
			$("#inmueble_txtCaracteristicas").val(datosInmueble[0]['caracteristicas']);
			
			
			if (datosInmueble[0]['escaparate'] == 'true') {
				$('#inmueble_chEscaparate').attr('checked',true);
			}
			
			if (datosInmueble[0]['escaparateWeb'] == 'true') {
				$('#inmueble_chEscaparateWeb').attr('checked',true);
			}

			if (datosInmueble[0]['AlquilarCompra'] == 'true') {
				$('#inmueble_chAlquilerCompra').attr('checked',true);
			}
						
			if (datosInmueble[0]['alquiler'] == 'true') {
				$('#inmueble_chAlquiler').attr('checked',true);
			}
			
			if (datosInmueble[0]['venta'] == 'true') {
				$('#inmueble_chVenta').attr('checked',true);
			}
			
			if (datosInmueble[0]['promocion'] == 'true') {
				$('#inmueble_chPromocion').attr('checked',true);
			}
			
			if (datosInmueble[0]['traspaso'] == 'true') {
				$('#inmueble_chTraspaso').attr('checked',true);
			}
			
			if (datosInmueble[0]['cartel'] == 'true') {
				$('#inmueble_chCartel').attr('checked',true);
			}

			if (datosInmueble[0]['llaves'] == 'true') {
				$('#inmueble_chLlaves').attr('checked',true);
			}

			$("#inmueble_txtPreciopropie").val(datosInmueble[0]['preciopropie']);
			$("#inmueble_txtPcomision").val(datosInmueble[0]['pcomision']);
			$("#inmueble_txtComision").val(datosInmueble[0]['comision']);
			$("#inmueble_txtHonorarios").val(datosInmueble[0]['honorarios']);
			
			$("#inmueble_txtPrecio").val(datosInmueble[0]['precio']);
			$("#inmueble_txtPrecioalquiler").val(datosInmueble[0]['precioalquiler']);
			$("#inmueble_txtComunidad").val(datosInmueble[0]['comunidad']);
			$("#inmueble_txtPreciogar").val(datosInmueble[0]['preciogar']);
			
			$("#inmueble_txtDescripcion").html(datosInmueble[0]['descripcion']);
			
			$('#inmueble_cbEstado option').each(function() {
				if ($(this).val() == datosInmueble[0]['estado']) {
					$(this).attr('selected','selected');
				}
			});
			
			//CAMPOS DINAMICOS
			$("#inmueble_txtSuperutil").val(datosInmueble[0]['superutil']);
			$("#inmueble_txtAltura").val(datosInmueble[0]['altura']);
			$("#inmueble_txtMetroster").val(datosInmueble[0]['metroster']);
			$("#inmueble_txtMetroscuadra").val(datosInmueble[0]['metroscuadra']);
			$("#inmueble_txtHabitaciones").val(datosInmueble[0]['habitaciones']);
			$("#inmueble_txtBanos").val(datosInmueble[0]['banos']);
			$("#inmueble_txtAseos").val(datosInmueble[0]['aseos']);
			
			$("#inmueble_txtSalon").val(datosInmueble[0]['salon']);
			$("#inmueble_txtCocina").val(datosInmueble[0]['cocina']);
			$("#inmueble_txtArmaempo").val(datosInmueble[0]['armaempo']);
			$("#inmueble_txtPlantasedif").val(datosInmueble[0]['plantasedif']);
			$("#inmueble_txtTerraza").val(datosInmueble[0]['terraza']);
			
			$("#inmueble_txtCalefaccion").val(datosInmueble[0]['calefaccion']);
			
			$("#inmueble_txtCarpinext").val(datosInmueble[0]['carpinext']);
			$("#inmueble_txtCarpinint").val(datosInmueble[0]['carpinint']);
			$("#inmueble_txtSolados").val(datosInmueble[0]['solados']);
			$("#inmueble_txtAntiguedad").val(datosInmueble[0]['antiguedad']);
			
			$('#inmueble_cbTipCasa option').each(function() {
				if ($(this).val() == datosInmueble[0]['tipoCasa']) {
					$(this).attr('selected','selected');
				}
			});
			
			
			if (datosInmueble[0]['telefono'] == 'true') {
				$('#inmueble_chVPO').attr('checked',true);
			}
			
			if (datosInmueble[0]['telefono'] == 'true') {
				$('#inmueble_chTelefono').attr('checked',true);
			}
			
			if (datosInmueble[0]['tendedero'] == 'true') {
				$('#inmueble_chTendedero').attr('checked',true);
			}
			
			if (datosInmueble[0]['vestibulo'] == 'true') {
				$('#inmueble_chVestibulo').attr('checked',true);
			}
			
			if (datosInmueble[0]['amueblado'] == 'true') {
				$('#inmueble_chAmueblado').attr('checked',true);
			}
			
			if (datosInmueble[0]['cocinaamu'] == 'true') {
				$('#inmueble_chCocinaamu').attr('checked',true);
			}
			
			if (datosInmueble[0]['ascensor'] == 'true') {
				$('#inmueble_chAscensor').attr('checked',true);
			}			
			
			if (datosInmueble[0]['chimenea'] == 'true') {
				$('#inmueble_chChimenea').attr('checked',true);
			}
			
			if (datosInmueble[0]['reformado'] == 'true') {
				$('#inmueble_chReformado').attr('checked',true);
			}
			
			if (datosInmueble[0]['exterior'] == 'true') {
				$('#inmueble_chExterior').attr('checked',true);
			}
			
			if (datosInmueble[0]['portero'] == 'true') {
				$('#inmueble_chPortero').attr('checked',true);
			}
			
			if (datosInmueble[0]['estreno'] == 'true') {
				$('#inmueble_chEstreno').attr('checked',true);
			}
			
			if (datosInmueble[0]['tenis'] == 'true') {
				$('#inmueble_chTenis').attr('checked',true);
			}
			
			if (datosInmueble[0]['jardines'] == 'true') {
				$('#inmueble_chJardines').attr('checked',true);
			}
			
			if (datosInmueble[0]['piscina'] == 'true') {
				$('#inmueble_chPiscina').attr('checked',true);
			}
			
			if (datosInmueble[0]['patio'] == 'true') {
				$('#inmueble_chPatio').attr('checked',true);
			}
			
			if (datosInmueble[0]['garaje'] == 'true') {
				$('#inmueble_chGaraje').attr('checked',true);
			}
			
			if (datosInmueble[0]['buhardilla'] == 'true') {
				$('#inmueble_chBuhardilla').attr('checked',true);
			}
			
			if (datosInmueble[0]['trastero'] == 'true') {
				$('#inmueble_chTrastero').attr('checked',true);
			}
			
			if (datosInmueble[0]['gasciudad'] == 'true') {
				$('#inmueble_chGasciudad').attr('checked',true);
			}
		
			if (datosInmueble[0]['calefaccion'] == 'true') {
				$('#inmueble_txtCalefaccion').attr('checked',true);
			}
			
			if (datosInmueble[0]['carpinext'] == 'true') {
				$('#inmueble_txtCarpinext').attr('checked',true);
			}
			
			if (datosInmueble[0]['carpinint'] == 'true') {
				$('#inmueble_txtCarpinint').attr('checked',true);
			}
			
			if (datosInmueble[0]['VPO'] == 'true') {
				$('#inmueble_chVPO').attr('checked',true);
			}
			
			//Documento del inmueble
			obtenerDocumentoInmueble(datosInmueble[0]['id']);
			
			//Galeria de imagenes del inmueble
			obtenerGaleriaImagenes();
			
			if (idCliente > 0) {
				$('#botonGuardaInmueble').hide();
				//Mostramos el popup
				$('#popupInmobiliaria').modal('show');
				
				$("#inmueble_selectPropietario").trigger("chosen:updated");
				
			} else {
				$("#listaInmuebles").fadeOut('fast', function () {
					$("#camposFormularioInmuebles").fadeIn('fast');	
				});
			}

			
			} else {
				mensaje("Ocurrió algún problema al cargar el registro. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			}
		}
	);
}

function guardaInmuebles(esPopup){
	if (!$("#inmueble_txtRef").val()) {
		alert("Debes rellenar el nombre");
	} else {
		if ($("#inmueble_id").val() == 0) {
			accion = "inserta";
		} else{
			accion = "guarda";
		}
		
		jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
			accion: accion,
			txtId: $("#inmueble_id").val(),
			txtRef: $("#inmueble_txtRef").val(),
			txtFechaalta: $("#inmueble_txtFechaalta").val(),
			txtDireccion: $("#inmueble_txtDireccion").val(),
			zona: $("#inmueble_zona").val(),
			
			lstinmueble: $('#inmueble_lstinmueble option:selected').val(),
			
			txtPortal: $("#inmueble_txtPortal").val(),
			txtPlanta: $("#inmueble_txtPlanta").val(),
			txtLetra: $("#inmueble_txtLetra").val(),
			
			txtPoblacion: $("#inmueble_txtPoblacion").val(),
			txtProvincia: $("#inmueble_txtProvincia").val(),
			
			txtUsuarioId: $("#inmueble_selectUsuario").val(),
			txtPropId: $("#inmueble_selectPropietario").val(),
			
			txtCaracteristicas: $("#inmueble_txtCaracteristicas").val(),
			chEscaparate: $("#inmueble_chEscaparate").is(':checked'),
			chEscaparateWeb: $("#inmueble_chEscaparateWeb").is(':checked'),
			chAlquiler: $("#inmueble_chAlquiler").is(':checked'),
			chVenta: $("#inmueble_chVenta").is(':checked'),
			chPromocion: $("#inmueble_chPromocion").is(':checked'),
			chTraspaso: $("#inmueble_chTraspaso").is(':checked'),
			
			chAlquilerCompra: $("#inmueble_chAlquilerCompra").is(':checked'),
			chLlaves: $("#inmueble_chLlaves").is(':checked'),
			chCartel: $("#inmueble_chCartel").is(':checked'),
			
			txtPreciopropie: $("#inmueble_txtPreciopropie").val(),
			txtPcomision: $("#inmueble_txtPcomision").val(),
			txtComision: $("#inmueble_txtComision").val(),
			txtHonorarios: $("#inmueble_txtHonorarios").val(),
			
			txtPrecio: $("#inmueble_txtPrecio").val(),
			txtPrecioalquiler: $("#inmueble_txtPrecioalquiler").val(),
			txtComunidad: $("#inmueble_txtComunidad").val(),
			txtPreciogar: $("#inmueble_txtPreciogar").val(),
			
			txtDescripcion: $("#inmueble_txtDescripcion").val(),

			txtMetros: $("#inmueble_txtMetros").val(),
			txtParcela: $("#inmueble_txtParcela").val(),
			txtHabitaciones: $("#inmueble_txtHabitaciones").val(),
			txtBanos: $("#inmueble_txtBanos").val(),
			txtAseos: $("#inmueble_txtAseos").val(),
			txtSalon: $("#inmueble_txtSalon").val(),
			txtCocina: $("#inmueble_txtCocina").val(),
			txtTerraza: $("#inmueble_txtTerraza").val(),
			txtPlantas: $("#inmueble_txtPlantas").val(),
			chTelefono: $("#inmueble_chTelefono").is(':checked'),
			chTendedero: $("#inmueble_chTendedero").is(':checked'),
			chArmaempotrado: $("#inmueble_chArmaempotrado").is(':checked'),
			chAscensor: $("#inmueble_chAscensor").is(':checked'),
			txtChimenea: $("#inmueble_chChimenea").is(':checked'),
			chGaraje: $("#inmueble_chGaraje").is(':checked'),
			chTrastero: $("#inmueble_chTrastero").is(':checked'),
			chPiscina: $("#inmueble_chPiscina").is(':checked'),
			chTenis: $("#inmueble_chTenis").is(':checked'),
			chJardines: $("#inmueble_chJardines").is(':checked'),
			chBuhardilla: $("#inmueble_chBuhardilla").is(':checked'),
			txtSolados: $("#inmueble_txtSolados").val(),
			txtCarpinext: $("#inmueble_txtCarpinext").val(),
			txtCarpinint: $("#inmueble_txtCarpinint").val(),
			txtCalefaccion: $("#inmueble_txtCalefaccion").val(),
			txtAntiguedad: $("#inmueble_txtAntiguedad").val(),
			txtSuperutil: $("#inmueble_txtSuperutil").val(),
			txtImagen: $("#inmueble_txtImagen").val(),
			chExterior: $("#inmueble_chExterior").is(':checked'),
			chAmueblado: $("#inmueble_chAmueblado").is(':checked'),
			chCocinaamu: $("#inmueble_chCocinaamu").is(':checked'),
			chReformado: $("#inmueble_chReformado").is(':checked'),
			chPortero: $("#inmueble_chPortero").is(':checked'),
			txtArmaempo: $("#inmueble_txtArmaempo").val(),
			txtPlantasedif: $("#inmueble_txtPlantasedif").val(),
			txtMetroster: $("#inmueble_txtMetroster").val(),
			chPatio: $("#inmueble_chPatio").is(':checked'),
			chGarobli: $("#inmueble_chGarobli").is(':checked'),
			txtVisitas: $("#inmueble_txtVisitas").val(),
			txtMetroscuadra: $("#inmueble_txtMetroscuadra").val(),
			txtAltura: $("#inmueble_txtAltura").val(),
			chEstreno: $("#inmueble_chEstreno").is(':checked'),
			chAlmacena: $("#inmueble_chAlmacena").is(':checked'),
			chVestibulo: $("#inmueble_chVestibulo").is(':checked'),
			chGasciudad: $("#inmueble_chGasciudad").is(':checked'),
			cbTipoTerreno: $("#inmueble_cbTipoTerreno").val(),
			cbAcceso: $("#inmueble_cbAcceso").val(),
			chRiego: $("#inmueble_chRiego").is(':checked'),
			chLuz: $("#inmueble_chLuz").is(':checked'),
			chVallado: $("#inmueble_chVallado").is(':checked'),
			cbTerreno: $("#inmueble_cbTerreno").val(),
			chServidumbre: $("#inmueble_chServidumbre").is(':checked'),
			cbTipCasa: $("#inmueble_cbTipCasa").val(),
			chVPO: $("#inmueble_chVPO").is(':checked'),
			cbEstado: $('#inmueble_cbEstado').val()
			}, function(data, textStatus){
				if (data == "KO")
				{
					mensaje("Ocurrió algún problema en el guardado. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.","danger","warning", 0);
				}else{
					if (accion == 'inserta') {
						$("#inmueble_id").val(data);	
					}
					mensaje("Guardado correctamente","success","check", 5);
					
					if (esPopup) {
						//Actualizamos el registro
						$('#tablaRegistrosInmuebles').dataTable()._fnAjaxUpdate();
						
						//Ocultamos el popup
						$('#popupInmobiliaria').modal('hide');
					} else {
						$("#camposFormularioInmuebles").fadeOut('fast', function () {
							$('#tablaRegistrosInmuebles').dataTable()._fnAjaxUpdate();
							$("#listaInmuebles").fadeIn('fast');
						});
					}						
				}
				
			}
		);
	}
}


function eliminaInmueble(idElimina){
	jQuery.post("./modulos/inmobiliariaInmuebles/dk-logica.php", {
		accion: "elimina",
		id: idElimina
		}, function(data, textStatus){
			if (data != "OK") {
				mensaje("Ocurrió algún problema al eliminar. Pongase en contacto con desarrollo@dkreativo.es si el problema continua.<br>","danger","warning", 0);
			} else{
				mensaje("El inmueble se eliminó correctamente.","success","check", 5);
				$('#tablaRegistrosInmuebles').dataTable()._fnAjaxUpdate();					
			}
		}
	);		
}