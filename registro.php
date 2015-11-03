<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dk Web - API de fácil integración</title>

    <!-- Bootstrap Core CSS -->
    <link href="web/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="web/css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="web/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
    <!-- Portfolio -->
    <!-- BEGIN GLOBAL MANDATORY STYLES 
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
<link href="../dkpanel/assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../dkpanel/assets/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css">
<link href="../dkpanel/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="../dkpanel/assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
 END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="./assets/global/plugins/select2/select2.css"/>
<!-- END PAGE LEVEL SCRIPTS -->
<!-- BEGIN THEME STYLES -->
<link href="./assets/global/css/components-rounded.css" id="style_components" rel="stylesheet" type="text/css">
<link href="./assets/global/css/plugins.css" rel="stylesheet" type="text/css">
<link href="./assets/admin/layout3/css/layout.css" rel="stylesheet" type="text/css">
<link href="./assets/admin/layout3/css/themes/default.css" rel="stylesheet" type="text/css" id="style_color">
<link href="./assets/admin/layout3/css/custom.css" rel="stylesheet" type="text/css">
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
</head>
<body>

    <?php
    	include("menu.php");
	?>    
<div class="page-content">
		<div class="container">		
			<div class="row">
				<div class="col-md-12">
					<div class="portlet light" id="form_wizard_1">
						<div class="portlet-title">
							<div class="caption">
								<span class="caption-subject font-green-sharp bold uppercase">
								<i class="fa fa-gift"></i> Nuevo registro - <span class="step-title">Paso 1 de 3 </span>
								</span>
							</div>
						</div>
						<div class="portlet-body form">
							<form action="creaweb.php" class="form-horizontal" id="submit_form" method="POST">
								<div class="form-wizard">
									<div class="form-body">
										<ul class="nav nav-pills nav-justified steps">
											<li>
												<a href="#tab1" data-toggle="tab" class="step">
												<span class="number">
												1 </span>
												<span class="desc">
												<i class="fa fa-check"></i> Datos de usuario </span>
												</a>
											</li>
											<li>
												<a href="#tab2" data-toggle="tab" class="step">
												<span class="number">
												2 </span>
												<span class="desc">
												<i class="fa fa-check"></i> Datos del sitio </span>
												</a>
											</li>
											<li>
												<a href="#tab4" data-toggle="tab" class="step">
												<span class="number">
												3 </span>
												<span class="desc">
												<i class="fa fa-check"></i> Confirmar datos </span>
												</a>
											</li>
										</ul>
										<div id="bar" class="progress progress-striped" role="progressbar">
											<div class="progress-bar progress-bar-success">
											</div>
										</div>
										<div class="tab-content">
											<div class="alert alert-danger display-none">
												<button class="close" data-dismiss="alert"></button>
												Hay algún error. Revisa la información.
											</div>
											<div class="alert alert-success display-none">
												<button class="close" data-dismiss="alert"></button>
												Todo correcto!
											</div>
											<div class="tab-pane active" id="tab1">
												<h3 class="block">Rellena los datos de usuario</h3>												
                                                <div class="form-group">
													<label class="control-label col-md-3">Email <span class="required">
													* </span>
													</label>
													<div class="col-md-4">
														<input type="text" class="form-control" name="email"/>
														<span class="help-block">
														Tu dirección de correo electrónico, no lo usaremos para envío de publicidad ni la venderemos. </span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Contraseña <span class="required">
													* </span>
													</label>
													<div class="col-md-4">
														<input type="password" class="form-control" name="password" id="submit_form_password"/>
														<span class="help-block">
														Una contraseña segura, es un sitio web seguro. Utiliza letras mayúsculas y minúsculas, números y símbolos </span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Confirma la contraseña <span class="required">
													* </span>
													</label>
													<div class="col-md-4">
														<input type="password" class="form-control" name="rpassword"/>
														<span class="help-block">
														Confirma la contraseña</span>
													</div>
												</div>
											</div>
											<div class="tab-pane" id="tab2">
												<h3 class="block">Rellena los datos del sitio web</h3>
												<div class="form-group">
													<label class="control-label col-md-3">Dominio<span class="required">
													* </span>
													</label>
													<div class="col-md-4">
														<input type="text" class="form-control" name="dominio"/>
														<span class="help-block">
														P. Ej. "dkweb.es / dkreativo.com"</span>
													</div>
												</div>  
												<div class="form-group">
													<label class="control-label col-md-3">Nombre del sitio <span class="required">
													* </span>
													</label>
													<div class="col-md-4">
														<input type="text" class="form-control" name="nombre"/>
														<span class="help-block">
														P. Ej. "Gestor de contenidos Dk Web"</span>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Descripción</label>
													<div class="col-md-4">
														<textarea class="form-control" rows="3" name="descripcion"></textarea>
													</div>
												</div>                                              
											</div>
											<div class="tab-pane" id="tab4">
												<h3 class="block">Confirma tus datos</h3>
												<h4 class="form-section">Datos de usuarios</h4>
												<div class="form-group">
													<label class="control-label col-md-3">Usuario:</label>
													<div class="col-md-4">
														<p class="form-control-static" data-display="email">
														</p>
													</div>
												</div>
												<h4 class="form-section">Datos Web</h4>
												<div class="form-group">
													<label class="control-label col-md-3">Dominio:</label>
													<div class="col-md-4">
														<p class="form-control-static" data-display="dominio">
														</p>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Nombre:</label>
													<div class="col-md-4">
														<p class="form-control-static" data-display="nombre">
														</p>
													</div>
												</div>
												<div class="form-group">
													<label class="control-label col-md-3">Descripción:</label>
													<div class="col-md-4">
														<p class="form-control-static" data-display="descripcion">
														</p>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="form-actions">
										<div class="row">
											<div class="col-md-offset-3 col-md-9">
												<a href="javascript:;" class="btn default button-previous"><i class="m-icon-swapleft"></i> Volver </a>
												<a href="javascript:;" class="btn blue button-next">Continuar <i class="m-icon-swapright m-icon-white"></i></a>
												
												<div class="g-recaptcha button-submit" data-sitekey="6LdS7wcTAAAAAPjivzxtwXfZzabKkrX-Zy8_Sd5g"></div>
												<input class="btn green button-submit" type="submit" value="Crear sitio web!" />
												<!-- <a href="creaweb.php" class="btn green button-submit">Crear sitio web! <i class="m-icon-swapright m-icon-white"></i> -->
												</a>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT INNER -->
		</div>
	</div>
	<!-- END PAGE CONTENT -->
</div>
<!-- END PAGE CONTAINER -->

<!-- captcha google -->
<script src='https://www.google.com/recaptcha/api.js?hl=es'></script>

<!-- END FOOTER -->
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
<script src="../dkpanel/assets/global/plugins/respond.min.js"></script>
<script src="../dkpanel/assets/global/plugins/excanvas.min.js"></script> 
<![endif]-->
<script src="./assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip  -->
<script src="./assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script>
<script src="./assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script> 
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="./assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="./assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script>
<script type="text/javascript" src="./assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<script type="text/javascript" src="./assets/global/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="./assets/global/scripts/metronic.js" type="text/javascript"></script>
<script src="./assets/admin/layout3/scripts/layout.js" type="text/javascript"></script>
<script src="./assets/admin/layout3/scripts/demo.js" type="text/javascript"></script>-->
<script src="./assets/admin/pages/scripts/form-wizard.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->
<script>
$(document).ready(function() {       
	// initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	//Demo.init(); // init demo features
   	FormWizard.init();
});
</script>
<!-- END JAVASCRIPTS -->
