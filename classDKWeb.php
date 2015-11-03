<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dk Web - Class DKWeb de fácil integración</title>

    <!-- Bootstrap Core CSS -->
    <link href="web/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="web/css/stylish-portfolio.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="web/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <?php
    	include("menu.php");
	?>        

    <!-- Header -->
<!--    <header id="top" class="header">
        <div class="text-vertical-center">
            <h1>Conoce Dk Web</h1>
            <h3>Tu gestor de contenidos sencillo de integrar y de usar</h3>
            <br>
            <a href="#about" class="btn btn-dark btn-lg"> Empieza ya!</a>
        </div>
    </header>   --> 

    <!-- Portfolio -->
    <section id="portfolio" class="portfolio">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-lg-offset-1 text-center">
                    <h2>Funciones actuales</h2>
                    <hr>
                    <div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>__construct</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong><br>
								<ul>
									<li>mysqlLink: Link a la class MYSQL</li>
									<li>idWebsite: id de la website dentro de DKWeb.</li>
									<li>typeOutput: tipo de salida (array, json, xml)</li>
								</ul>
							</div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Link de la class DKWeb
    	                   		<br>
                        	</div>
                        </div>
                    </div>
                    <hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getAllSections</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong>
								<ul>
	                            	<li>dataReturn: Link array para devolver los datos</li>
									<li>withData: Booleano para determinar si devuelve los articulos</li>
									<li>parent: Id de la seccion padre.</li>
								</ul>
						    </div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Todas las secciones y secciones hijas
    	                   		<br>
    	                   		Puede tambien devolver los articulos de las secciones
	                   		</div>
                        </div>
                    </div>
                    <hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getAllArticlesSection</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong>
								<ul>
	                            	<li>dataReturn: Link array para devolver los datos</li>
									<li>idSection: id de la sección a buscar.</li>
								</ul>
						    </div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Todos los articulos de una sección en concreto</div>
                        </div>
                    </div>
					<hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getSeccion</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong>
								<ul>
                        			<li>id: id de la sección.</li>
                        		</ul>
						    </div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		La información de la sección pasada por parametro</div>
                        </div>
                    </div>
                    <hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getSecciones</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong>
								<ul>
									<li>Nada</li>
								</ul>
							</div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Todas las secciones de un sitio web.</div>
                        </div>
                    </div>
                    <hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getPost</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong>
								<br>
								<ul>
									<li>idArticle: id del articulo a buscar</li>
								</ul>
							</div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Toda la información del articulo pasado por parametro.
	                   		</div>
                        </div>
                    </div>
                    <hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getSeccionPostImagen</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left"><strong>Recibe</strong>
								<ul>
                            		<li>id: id de la sección. Si está a 0, devolverá todas las publicaciones de la web.</li>
									<li>inicio: inicio de registro. Siendo 0 la primera.</li>
									<li>cantidad: cantidad de registros a traer. 100 por defecto.</li>
									<li>orden: ascendente o descendente. Siendo ascendente por defecto.</li>
								</ul>
						    </div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Las publicaciones de una sección en concreto o de todas siempre que tengan cumplimentado el campo de "Imagen" (por lo que solo será válido para secciones de tipo avanzado), 
    	                   		pudiendo limitar el inicio y la cantidad de registros a devolver. <br> Además se puede ordenar de forma ascendente (según se han ido creando) o descendente 
    	                   		(para devolver la última publicación como la primera del JSon).</div>
                        </div>
                    </div>
					<hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getSeccionPostURL</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left">
								<strong>Recibe</strong>
								<ul>
                            		<li>id: id de la sección. Si está a 0, devolverá todas las publicaciones de la web.</li>
									<li>inicio: inicio de registro. Siendo 0 la primera.</li>
									<li>cantidad: cantidad de registros a traer. 100 por defecto.</li>
									<li>orden: ascendente o descendente. Siendo ascendente por defecto.</li>
								</ul>
						    </div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br>
    	                   		Las publicaciones de una sección en concreto o de todas siempre que tengan cumplimentado el campo de "URL" (por lo que solo será válido para secciones de tipo avanzado), 
    	                   		pudiendo limitar el inicio y la cantidad de registros a devolver. <br> Además se puede ordenar de forma ascendente (según se han ido creando) o descendente 
    	                   		(para devolver la última publicación como la primera del JSon).</div>
                        </div>
                    </div>
                    <hr>
					<div class="row">
                        <div class="col-md-3 text-right">
                        	<h3>getCommentsArticle</h3>
                        </div>
                        <div class="col-md-9">
							<div class="col-md-12 text-left"><strong>Recibe</strong><ul>
                            	<li>idArticulo: Id del articulo a buscar.</li>
								<li>limit: Limite de comentarios.</li>
						    </div>
    	                   	<div class="col-md-12 text-left">
    	                   		<strong>Devuelve</strong>
    	                   		<br> Todos los comentarios del articulo pasado por parametro</div>
                        </div>
                    </div>
            </div>
      
        </div>
      
    </section>

    <!-- Call to Action -->
    <aside class="call-to-action bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3>¿Tienes alguna consulta o sugerencia?</h3>
                    Si quieres realizar alguna consulta, te invitamos a visitar a nuestro colaborador http://www.vertutorial.com donde encontrarás una comunidad dispuesta a ayudarte<br />
                    Si prefieres ofrecernos alguna sugerencia, puedes ponerte en contacto con nosotros directamente ;)<br>
                    <a href="http://www.vertutoriales.com" class="btn btn-lg btn-light">Visitar VerTutoriales</a>
                    <a href="contacto.html" class="btn btn-lg btn-dark">Enviarnos una sugerencia</a>
                </div>
            </div>
        </div>
    </aside>
    <!-- Footer -->
    <?php
		include("pie.php");
	?>

    <!-- jQuery -->
    <script src="web/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="web/js/bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script>
    // Closes the sidebar menu
    $("#menu-close").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Opens the sidebar menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#sidebar-wrapper").toggleClass("active");
    });

    // Scrolls to the selected menu item on the page
    $(function() {
        $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {

                var target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                if (target.length) {
                    $('html,body').animate({
                        scrollTop: target.offset().top
                    }, 1000);
                    return false;
                }
            }
        });
    });
    </script>

</body>

</html>
