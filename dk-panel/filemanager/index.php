<?php session_start();
//cargo parámetros generales de configuración
include("confic.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editor</title>

<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>

</head>
<body>
	
<div id="formularioImagen">
	
	<div style="height: 20px; margin-top:25px; margin-left:10px;">
		<div style="float:left; width: 100px; padding-top:5px;">
			<label style="line-height: 16px; left: 0px; height: 16px;">URL</label>
		</div>
		<div style="top: 0px; width: 301px; height: 30px; float:left">
			<input id="urlFile" style="width: 258px;">
			<div tabindex="-1" role="button" style="float:right;">
				<button id="ftpButton" type="button">FTP</button>
			</div>
		</div>
	</div>
	<div style="height: 20px; margin-top:25px; margin-left:10px;">
		<div style="float:left; width: 100px; padding-top:5px;">
			<label style="line-height: 16px; height: 16px;">Descripción</label>
		</div>
		<div style="top: 0px; width: 301px; height: 30px; float:left">
			<input id="descriptionFile" style="width: 258px;">
		</div>
	</div>
	<div id="dimensions" style="height: 20px; margin-top:25px; margin-left:10px;">
		<div style="float:left; width: 100px; padding-top:5px;">
			<label style="line-height: 16px; height: 16px;">Dimensión</label>
		</div>
		<div style="top: 0px; width: 55px; height: 30px; float:left">
			<input id="dimensionWidth" style="width: 50px;">
		</div>
		<div style="top: 0px; width: 25px; height: 30px; float:left;padding-top:4px; padding-left:3px;">
			X
		</div>
		<div style="top: 0px; width: 55px; height: 30px; float:left">
			<input id="dimensionHeight" style="width: 50px;">
		</div>
	</div>
	<div style="height: 20px; margin-top:25px; margin-left:10px;">
		<div id="mceu_65" tabindex="-1" role="button" style="left: 355px; top: 10px; width: 50px; height: 28px;">
			<button type="button" id="insertarElemento" style="height: 30px; width: 75px; padding: 4px 8px; font-size: 14px; line-height: 20px; cursor: pointer; text-align: center; background-color:#2d8ac7; color:#fff; font-weight:bold;">Insertar</button>
		</div>
	</div>
</div>
	
<div id="todoCuerpo">	
    <div id="menuSuperior">
    	<button onclick="activaMenu('menuSubeArchivo')">Subir fichero</button>
    	<button onclick="activaMenu('menuCreaCarpeta')">Crear carpeta</button>
    </div>        
    
    <div id="menuCreaCarpeta" hidden="true">
    	<p>
            <input type="text" id="nuevaCarpeta" />
            <button onclick="creaCarpeta();">Crear</button>
        </p>
    </div>
    
    <div id="menuSubeArchivo" hidden="true">
    	<p>
            <form enctype="multipart/form-data" class="formulario">
                <input name="archivo" type="file" id="archivo" />
            </form>
            <button onclick="subirArchivo()">Subir</button>
        </p>
    </div>
    <table id="tablaRegistros" class="table table-hover" cellspacing="0" align="center" role="grid">
        <thead>
            <tr>            
                <th width="62%">Fichero</th>
                <th width="18%" align="right">Tama&ntilde;o</th>
                <th width="20%">Fecha</th>
            </tr>
        </thead>
        <tbody>
        </tbody>  
    </table>

<!-- MENU CONTEXTUAL -->
   	<ul id="menuCapa" class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">            
        <li id="ficheroDeMenu" role="presentation"></li>
        <li role="presentation" class="divider"></li>                        
        <li role="presentation">
          <a role="menuitem" tabindex="-1" href="#" onClick="seleccionaFichero();">Seleccionar</a>
        </li>
        <li role="presentation">
          <a role="menuitem" tabindex="-1" href="#" onClick="renombrarFichero();">Renombrar</a>
        </li>
        <li role="presentation" class="divider"></li>                        
        <li role="presentation">
          <a role="menuitem" tabindex="-1" href="#" onClick="eliminarFichero();">Eliminar</a>
        </li>
  	</ul>
</div>
<!--<script language="javascript" src="js/jquery-1.2.6.min.js"></script> -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/media/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js"></script>
<script type="text/javascript" src="../../assets/admin/pages/scripts/table-editable.js"></script>
<script language="javascript">
	var ftp_rutabase = '<?php echo $_SESSION["ftp_rutabase"]; ?>';
</script>
<script language="javascript" src="ftp_funciones.js"></script>

</body>

</html>

