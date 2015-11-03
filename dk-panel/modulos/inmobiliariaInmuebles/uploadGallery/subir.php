<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<body>
<form action="imagen.php" method="post" enctype="multipart/form-data" name="form" id="form">
  <p>&nbsp;</p>
  <?php 
  	$idImg = $_GET['ref'];
	
	?>
    
    <input type="hidden" name="numero" id="numero" value="<?php echo $idImg; ?>"/>
    
  <table width="100%" border="0" cellpadding="3">
    <tr>
      <th align="left" scope="col">
      <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".1.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".1.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.1.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.1.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	
		
		</th>
		
      <td align="left" scope="col"><input type="checkbox" name="chBorra1" id="chBorra1" />
        Borrar<br />
        <input name="chWeb1" type="checkbox" id="chWeb1" value="si" <?php echo $websi; ?>  />
Web</td>
      <th width="4%" align="left" scope="col">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".2.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".2.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.2.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.2.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	
        </th>
      <td width="29%" align="left" scope="col"><input type="checkbox" name="chBorra2" id="chBorra2" />
        Borrar<br />
        <input name="chWeb2" type="checkbox" id="chWeb2" value="si" <?php echo $websi; ?> />
Web</td>
      <th width="4%" align="left" scope="col">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".3.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".3.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.3.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.3.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</th>
      <td width="29%" align="left" scope="col"><input type="checkbox" name="chBorra3" id="chBorra3" />
        Borrar<br />
        <input name="chWeb3" type="checkbox" id="chWeb3" value="si" <?php echo $websi; ?> />
Web</td>
    </tr>
    <tr>
      <td colspan="2" align="left"><input name="userfile1" type="file" id="userfile1" />        <br /></td>
      <td colspan="2" align="left"><input name="userfile2" type="file" id="userfile2" />        <br /></td>
      <td colspan="2" align="left"><input name="userfile3" type="file" id="userfile3" />        <br /></td>
    </tr>
    <tr>
      <td align="left">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".4.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".4.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.4.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.4.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra4" id="chBorra4" />
        Borrar<br />
        <input name="chWeb4" type="checkbox" id="chWeb4" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".5.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".5.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.5.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.5.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra5" id="chBorra5" />
        Borrar<br />
        <input name="chWeb5" type="checkbox" id="chWeb5" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".6.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".6.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.6.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.6.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra6" id="chBorra6" />
        Borrar<br />
        <input name="chWeb6" type="checkbox" id="chWeb6" value="si" <?php echo $websi; ?> />
Web</td>
    </tr>
    <tr>
      <td colspan="2" align="left"><input name="userfile4" type="file" id="userfile4" />        <br /></td>
      <td colspan="2" align="left"><input name="userfile5" type="file" id="userfile5" />        <br /></td>
      <td colspan="2" align="left"><input name="userfile6" type="file" id="userfile6" />        <br /></td>
    </tr>
    <tr>
      <td align="left"> 
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".7.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".7.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.7.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.7.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	
        </td>
      <td align="left"><input type="checkbox" name="chBorra7" id="chBorra7" />
        Borrar<br />
        <input name="chWeb7" type="checkbox" id="chWeb7" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".8.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".8.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.8.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.8.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra8" id="chBorra8" />
        Borrar<br />
        <input name="chWeb8" type="checkbox" id="chWeb8" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".9.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".9.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.9.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.9.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra9" id="chBorra9" />
        Borrar<br />
        <input name="chWeb9" type="checkbox" id="chWeb9" value="si" <?php echo $websi; ?> />
Web</td>
    </tr>
    <tr>
      <td colspan="2" align="left"><input name="userfile7" type="file" id="userfile11" /></td>
      <td colspan="2" align="left"><input name="userfile8" type="file" id="userfile8" /></td>
      <td colspan="2" align="left"><input name="userfile9" type="file" id="userfile9" /></td>
    </tr>
    <tr>
      <td align="left">      <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".10.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".10.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.10.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.10.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra10" id="chBorra10" />
        Borrar<br />
        <input name="chWeb10" type="checkbox" id="chWeb10" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">      <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".11.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".11.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.11.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.11.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra11" id="chBorra11" />
        Borrar<br />
        <input name="chWeb11" type="checkbox" id="chWeb11" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">      <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".12.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".12.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.12.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.12.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra12" id="chBorra12" />
        Borrar<br />
        <input name="chWeb12" type="checkbox" id="chWeb12" value="si" <?php echo $websi; ?> />
Web</td>
    </tr>
    <tr>
      <td colspan="2" align="left"><input name="userfile10" type="file" id="userfile10" /></td>
      <td colspan="2" align="left"><input name="userfile11" type="file" id="userfile7" /></td>
      <td colspan="2" align="left"><input name="userfile12" type="file" id="userfile12" /></td>
    </tr>
    <tr>
      <td width="4%" align="left">      <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".13.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".13.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.13.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.13.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	<label for="chBorra13"></label></td>
      <td width="30%" align="left"><input type="checkbox" name="chBorra13" id="chBorra13" />
      Borrar<br />
      <input name="chWeb13" type="checkbox" id="chWeb13" value="si" <?php echo $websi; ?> />
      Web<br /></td>
      <td align="left">      <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".14.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".14.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.14.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.14.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra14" id="chBorra14" />
        Borrar<br />
        <input name="chWeb14" type="checkbox" id="chWeb14" value="si" <?php echo $websi; ?> />
Web</td>
      <td align="left">
            <?php 
		$websi = "";
		  if (file_exists("../../../archivos/inmobiliaria/".$idImg.".15.jpg"))
		  { ?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg.".15.jpg"; ?>" />
            <?php
		  }elseif (file_exists("../../../archivos/inmobiliaria/".$idImg."w.15.jpg")){
		?>
      		<img height="50px" src="../../../archivos/inmobiliaria/<?php echo $idImg."w.15.jpg"; ?>" />            
        <?php
			$websi = 'checked="checked"';
		  }else{
			  echo '<img height="50px" src="../../../imgs/nodisponible.jpg" />';
			}
		?>	</td>
      <td align="left"><input type="checkbox" name="chBorra15" id="chBorra15" />
        Borrar<br />
        <input name="chWeb15" type="checkbox" id="chWeb15" value="si" <?php echo $websi; ?> />
Web</td>
    </tr>
    <tr>
      <td colspan="2" align="left"><input name="userfile13" type="file" id="userfile13" /></td>
      <td colspan="2" align="left"><input name="userfile14" type="file" id="userfile14" /></td>
      <td colspan="2" align="left"><input name="userfile15" type="file" id="userfile15" /></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  
  <p align="center">
    <input type="submit" name="boton" value="Guardar Cambios" />
    <input name="numero" type="hidden" id="numero" value="<?php echo $idImg; ?>" />
  </p>
</form>
</body>
</html>
