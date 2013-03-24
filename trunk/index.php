<?
if (!$_SESSION["user"]["id"]) $pagina_inicio="modulos/informacion/informacion.php";
?>

<html>
<head>
<title>Firmas Digitales</title>
</head>
<form name=form1>
<frameset rows="15%,85%" >
     <FRAME name="cabecera" src="cabecera.php" frameborder=0 marginwidth="1" marginheight="1"> 
     <frameset cols="20%,80%">
       <FRAME name="menu" src="menu.php" frameborder=0>
       <FRAME name="principal" src="<?=$pagina_inicio?>" name="principal" frameborder=0>
      </frameset>
</frameset>
</form>
</html>