<?
require_once("config.php");

//print_r($_POST);
if ($_POST["usuario"])
      {
      $usuario=$_POST["usuario"]; 
      }
echo $html_header;
?>
<form name=form1 method=post>
<input type=hidden name='usuario' value="fer">
<table width=100% align=Center border=0>
<tr>
<td width=70% valign='bottom'><img src="imagenes/firmas_digitales.jpg"></td>
<td align=right valign=middle>
    <font face='Trebuchet MS' color='#2D4D7E' size=3><b>Firmas Digitales</b></font>
    <br>
    <?list($dia,$mes,$anio,$dia_s) = split("-", date("j-n-Y-w",mktime()));?>
     <font face='Trebuchet MS' color='#2D4D7E' size=2>
    <? echo $dia_semana[$dia_s];
       echo " $dia de $meses[$mes] de $anio";
    if ($_SESSION["user"]["name"]) {   
    ?>
    <br>
    <b>Usuario: </b><?=substr($_SESSION["user"]["name"],0,30)?>   
    <?
    }
    ?>
    </font>
</td>

</tr>
</table>
</form>