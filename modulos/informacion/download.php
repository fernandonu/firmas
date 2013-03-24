<?php
require_once("../../config.php");  
 
if ($parametros["cmd"]=="jcripto.jar"){
    
                $FileNameFull=CODIGO_JAVA."/jcripto.jar";
                $FileType="application/x-zip-compressed";
                $FileSize="13045";
                
				FileDownload("jcripto.jar",$FileNameFull,$FileType,$FileSize);
      } 
if ($parametros["cmd"]=="JCripto11.java"){
                $FileNameFull=CODIGO_JAVA."/jcripto/JCripto11.java";
                $FileType="text/plain";
                $FileSize="21000";
				FileDownload("JCripto11.java",$FileNameFull,$FileType,$FileSize);
      } 
if ($parametros["cmd"]=="informe.pdf"){
                $FileNameFull=ROOT_DIR."/documentacion/informe.pdf";
                $FileType="application/pdf";
                $FileSize="207196";
				FileDownload("informe.pdf",$FileNameFull,$FileType,$FileSize);
      } 
      
if ($parametros["cmd"]=="informe.doc"){
                $FileNameFull=ROOT_DIR."/documentacion/informe.doc";
                $FileType="application/msword";
                $FileSize="199680";
				FileDownload("informe.doc",$FileNameFull,$FileType,$FileSize);
      } 

echo $html_header;
?>
<body topmargin="6" leftmargin="6" rightmargin="6" bottommargin="6" marginwidth="6" marginheight="6" bgcolor="#E0E0E0" link="#006600" vlink="#006600" alink="#006600">
<form name=form1 > 
<p style="margin: 10px"><u><b><font size="4" face="Arial" color="#006600">Download</font></b></u></p>

<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table3">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><b><a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"jcripto.jar"))?>'><font face="Arial Black" color="#333333">Jcripto (versión compilada)</font></a></b></p>
        </td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>



<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table3">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><b><a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"JCripto11.java"))?>'><font face="Arial Black" color="#333333">Jcrito (Codigo Fuente)</font><a></b></p>
        </td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>

<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table3">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><b><a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"informe.pdf"))?>'><font face="Arial Black" color="#333333">Informe.pdf</font></a></b></p>
        </td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table3">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><b><a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"informe.doc"))?>'><font face="Arial Black" color="#333333">Informe.doc</font></a></b></p>
        </td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>


<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table1">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><a href="http://www.sun.com/download/index.jsp?cat=Java%20%26%20Technologies&tab=3&subcat=Javap" target="_blank" border="0"> <img src="../../imagenes/sun.gif"> </a></p>
        </td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table2">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><a href="http://ar.php.net/downloads.php" target="_blank"><img src="../../imagenes/php.gif" border="0"> </a></p>
		</td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="60%"  align="center" id="table3">
	<tr>
		<td width="20"><img border="0" src="borde1.jpg" width="20" height="20"></td>
		<td background="relleno.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde2.jpg" width="20" height="20"></td>
	</tr>
	<tr>
		<td width="20" background="cost1.jpg">&nbsp;</td>
		<td bgcolor="#EDEFEE" align=center>
		<p style="margin: 6px"><a href="http://www.postgresql.org/download/" target="_blank"><img src="../../imagenes/postgres.jpg" border="0"></a></b></p>
		</td>
		<td width="20" background="cost2.jpg">&nbsp;</td>
	</tr>
	<tr>
		<td width="20"><img border="0" src="borde3.jpg" width="20" height="20"></td>
		<td background="relleno2.jpg">&nbsp;</td>
		<td width="20"><img border="0" src="borde4.jpg" width="20" height="20"></td>
	</tr>
</table>
<p align="center"><font face="Arial" size="2" color="#006600">Teoría de la 
Información - U.N.S.L. - 2006</font></p>
</form>
</body>
</html>




<!--
<form name=form1 >
  <table width=60% align=center class=bordes>
  <tr id=mo  align=center><td colspan=2>Downloads</td></tr>
  <tr><td align=center> <a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"JCripto11.java"))?>'><font size=3>Jcripto.jar (Codigo Fuente) </font> </a></td></tr>
  <tr><td align=center> <a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"jcripto.jar"))?>'><font size=3>Jcripto.jar (Codigo Compilado)</font></a></td></tr>
  <tr><td align=center> <a href='<?=encode_link($_SERVER["PHP_SELF"],array("cmd"=>"informe.pdf"))?>'> <font>Informe.pdf</font></a></td></tr>
  <tr><td width=20% align=center><a href="http://www.sun.com/download/index.jsp?cat=Java%20%26%20Technologies&tab=3&subcat=Javap" target="_blank" border="0"> <img src="../../imagenes/sun.gif"> </a></td></tr>
  <tr><td width=20% align=center><a href="http://ar.php.net/downloads.php" target="_blank"><img src="../../imagenes/php.gif" border="0"> </a></td></tr>
  <tr><td width=20% align=center><a href="http://www.postgresql.org/download/" target="_blank"><img src="../../imagenes/postgres.jpg" border="0"></a></td></tr> 
  <tr></tr>
</form>
-->