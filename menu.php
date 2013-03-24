<?
require_once("config.php");

if ($parametros["cmd"]=="salir"){
     destruir_sesion();
     ?>
     <script>
     window.parent.document.form1.submit();
     </script>
     <?
}
//echo $html_header;
?>
<html>
  <head>
	<link rel=stylesheet type='text/css' href='./lib/estilos.css'>
  </head>
 <body  bgcolor="#E0E0E0">
<head>
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
</head>
<body bgcolor=11111111>
<form name=form1 method=post action=menu.php>
<input type=hidden name=salir value=0>
<?
 $i=0;
 $ancho_celda=30;
 $stylo="bgcolor= onmouseover=\"this.style.backgroundColor = '$bgcolor_over'; this.style.color = '$text_color_over'\" onmouseout=\"this.style.backgroundColor = '$bgcolor_out_int'; this.style.color = '$text_color_out'\"; style='cursor: hand;'";
 
 $stylo="onmouseover=\"this.style.backgroundColor = '#D0D0D0'; this.style.color = '#000000'\";  onmouseout=\"this.style.backgroundColor = '#42AAAA'; this.style.color = '#E0E0E0'\";  style='cursor: hand;'";
 ?>
<table width=90% align=center border=0 cellpadding=1 cellspacing=1 class=bordes >
  <tr><a href="./modulos/informacion/informacion.php"   target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Información             </td></a></tr>
  <?if (!$_SESSION["user"]["id"]) {?>
  <tr><a href="./modulos/clientes/clientes.php"         target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Nuevo Cliente           </td></a></tr>
  <?}?>
  <tr><a href="./modulos/clientes/listado_clientes.php" target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Listado Clientes        </td></a></tr>
  <?if ($_SESSION["user"]["id"]) {?>  
  <tr><a href="./modulos/mensajes/mensajes.php"         target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Nuevo Mensaje           </td></a></tr>
  <tr><a href="./modulos/mensajes/listado_mensajes.php" target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Listado Mensajes        </td></a></tr>
  <?}?>
  <tr><a href="./modulos/informacion/informe.php"       target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Informe                 </td></a></tr>
  <tr><a href="./modulos/informacion/integrantes.php"   target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Integrantes             </td></a></tr>  
  <tr><a href="./modulos/informacion/download.php"               target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Download       </td></a></tr>  
  <?if (!$_SESSION["user"]["id"]) {?>
  <tr><a href="./modulos/login/login.php"               target="principal"><td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Login                   </td></a></tr>
  <?}?>
  <?if ($_SESSION["user"]["id"]) {?>
  <tr><a href="<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"salir"));?>"> <td <?=$stylo?> class="menu">&nbsp;&nbsp;&nbsp; Salir</td></a></tr>
  <?}?>

    
</table>
<br><br><br>

<table  width=100% align=center>
  <tr><td align=center><a target="_blank" href="http://www.unsl.edu.ar"><img src="./imagenes/unsl.gif" border=0></a></td></tr>
  <tr><td align=center><a target="_blank" href="http://www.unsl.edu.ar"><font color=#314496 size=6 ><b>UNSL</b></a></td></td></tr>
</table>     
</form>
</body>
</html>