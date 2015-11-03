<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    	<title>Subir imagen con Dk Web</title>
    </head>

    <body>
        <form action="imagen.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" name="form" id="form">
            <p align="center">
                <input name="userfile1" type="file" id="userfile1" />
            </p>
            <p align="center">
                <input type="submit" name="boton" value="<?php if ($_GET['id'] != ""){ echo 'Subir Imagen'; }else{ echo 'Subir Archivo'; } ?>" />
            </p>
        </form>
    </body>
</html>