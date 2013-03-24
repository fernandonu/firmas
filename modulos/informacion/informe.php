<?php
/*
Autor:Fernando
Date:20/04/2005
*/
require_once("../../config.php");
echo $html_header;
?>
<table width=100% align=center class=bordes>
<tr bgcolor=<?=$cabecera_fondo?>><td align=center> <font color=<?=$letra_fondo?> size=3><b> INFORME </b></font></td></tr>
<tr><td>&nbsp</td></tr>
<tr><td >

<ul>
<li> <a href="#comentarios"><font size=3>Comentarios previos sobre el cifrador</font></a>
<li> <a href="#fundamentos"><font size=3>Fundamentos te�ricos del cifrador</font> </a>
<li> <a href="#generacion"><font size=3>Generaci�n de claves </font> </a>
<li> <a href="#cifrado"><font size=3>Cifrado de un archivo  </font>   </a>
<li> <a href="#descifrado"><font size=3>Descifrado de un archivo </font> </a>
<li> <a href="#firmado"><font size=3>Firmado de un archivo  </font>   </a>
<li> <a href="#verificacion"><font size=3>Verificaci�n de la firma </font> </a>
<li> <a href="#compresion"><font size=3>Compresi�n de archivos   </font> </a>
<li> <a href="#comparacion"><font size=3>Comparaci�n de archivos  </font> </a>
<li> <a href="#conclusion"><font size=3>Conclusi�n           </font>     </a>
</ul> 
</td>
</tr>
<tr id=ma><td align=center><b>Comentarios previos sobre el cifrador</b></td></tr>
<tr>
  <td class=presentacion>
  <a name="comentarios">
  <p>
  El cifrador fue programado utilizando el lenguaje Java en su versi�n 1.5.0_03 (Sun JDK1.5.0_03).
  Esto debe ser tenido en cuenta a la hora de la instalac�on de la m�quina virtual (jvm) para la ejecuci�n del programa, 
  se recomienda el uso de Sun Java JRE 1.5.X_XX (o superior), ya que no garantizamos el buen funcionamiento del cifrador con otra m�quina virtual que no sean las mencionadas
 (como en el caso de msjvm � MicroSoft Java Virtual Machine - o Sun JRE 1.4.X 1.3.X o 1.2.X), 
  aunque en el caso particular de Sun JRE 1.4.3_08 el programa funciona correctamente al menos con los tipos de archivos mas utilizados (exe, doc, txt, zip, jpg, jar). 
  </p>
  <p><br>�Por qu� Java?</p>
  <p>Porque por su potencia (estructuras implementadas), seguridad (implementada a traves del paradigma de objetos) y sobre todo independencia de plataforma (no nos obliga al uso de una arquitectura o sistema operativo), nos permite integrarlo en otras herramientas (como por ejemplo PHP) sin preocuparnos del servidor que lo ejecute y brindarle una interfaz mas amigable que la "l�nea de comandos" al usuario.
  </p>
  <p>�Por qu� un m�todo de cifrado nuevo?</p>
  <p>Porque crear un m�todo nuevo nos permiti� ver c�mo funciona todo el proceso de cifrado, y enfrentarnos a problemas de "seguridad" debido a fallas en el dise�o para perfeccionarlo hasta el punto actual.
  </p>
  </a>
  </td>
</tr>
<tr><td>&nbsp;</td></tr>
  
<tr id=ma><td align=center><b>Fundamentos te�ricos del cifrador</b></td></tr>
<tr>
  <td class=presentacion>
  <a name="fundamentos"> 
  <p>El cifrador presentado se basa en la funci�n polin�mica c�bica x2(b-x) y su representaci�n gr�fica es la siguiente:</p>
  <br>
  <img src="../../imagenes/funciong.jpg" border="0" width="717" height="343" alt="funciong.jpg (52.836 bytes)">
  <p>Elegimos esta funci�n debido a las siguientes particularidades</p>
  <p>El rango es limitado y parametrizado por el valor de "b" (y determina un m�ximo=2b/3).</p>
  <p>Dado un valor para "x1" y calculando el correspondiente "f(x1)" se puede aproximar el correspondiente valor de "x2" (f(x1)=f(x2)), la exactitud de la aproximaci�n depende del rango utilizado y fijado por el valor de "b".  </p>
  <p><br>En consecuencia, esta funci�n nos permite, dando un valor a "b", asignar aleatoriamente un valor a "x1", en base al cual aproximar, a trav�s de "f(x1)", el valor de "x2" (f(x2)=f(x1)+E, donde E=f(x1)-f(x2) el error en la aproximaci�n) y as� obtener dos "claves" (o mejor dicho, partes de claves) correspondientes una a la otra y permitir que el cifrado sea reversible</p>
  <p><br>Desde un punto de vista pr�ctico, se puede dar a "b" un valor de un orden superior a, por ejemplo, 5000 bits (el n�mero binario formado por un 1 seguido de 4999 ceros, es decir 25000 = 1.412467032139426e+1505), el m�ximo estar�a ubicado en 2(25000)/3 = 9.4164468809295069e+1504, dando un conjunto de 4.7082234404647535e+1504 n�meros entre los que elegir "x1". </p>
  <p><br>Si bien no hemos demostrado con fundamentos matem�ticos firmes la seguridad del m�todo implementado, sin duda el punto m�s fuerte que �ste tiene es el tiempo computacional que insumir�a "adivinar" el par de claves usados en todo el proceso del firmado digital (el "adivinar" las claves utilizando "fuerza bruta" implicar�a generar un valor para "x1", obtener un valor para "x2" y as� calcular el error E y tratar de descifrar un archivo).</p>
  <p><br>Para el ejemplo suponemos lo siguiente:</p>
  <p><br>El hacker ha decompilado el programa y sabe qu� combinaci�n de variables debe usar para cifrar-descifrar un archivo, conoce la funci�n que las genera y sabe c�mo armar los archivos que contienen las claves que el programa usa (sin embargo, NO conoce la estructura de un archivo cifrado).</p>
  <p><br>Que del rango "[m�ximo, b]" descarte valores "poco probables", reduciendo el conjunto a, digamos, 1/1000 del total (es decir 4.7082234404647535e+1501 valores) lo cual es bastante pesimista para nosotros!</p>
  <p><br>Que posea un poder de c�lculo que le permita efectuar todos los pasos del "cracking" 1000000000000000000 (1 trill�n) de veces por segundo</p>
  <p><br>En base a lo dicho, le tomar�a unos 1.4929678591022176e+1473 MILENIOS!!!! "adivinar" las claves, mientras que para al programa le toma de uno a dos minutos en una computadora con un poder de c�lculo mediano (PC hogare�a) generar las claves de 5000 bits</p>
  </a>
  </td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Generaci�n de claves</b></td></tr>
<tr>
<td class=presentacion>
<a name="generacion">
<p><br>jcripto -g  [cantidad de d�gitos]  [nombre de archivo de las claves]</p>
<p><br>Este comando le indica al cifrador que debe generar una clave p�blica y una privada de longitud igual a <cantidad de d�gitos> (en realidad, el programa si bien genera claves de <cantidad de d�gitos> de longitud, utiliza n�meros de <cantidad de d�gitos> x3, para compensar el descarte de d�gitos producto de la aproximaci�n de los par�metros mencionados mas arriba � f(x1) y f(x2)). Luego de generar dichas claves, el programa las almacena cada una en un archivo: �<nombre de archivo de las claves>.pri� y �[nombre de archivo de las claves].pub� (clave privada y p�blica respectivamente).</p>
</a></td></tr>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Cifrado de un archivo</b></td></tr>
<tr><td class=presentacion>
    <a name="Cifrado">
    <p><br>jcripto -c [archivo a cifrar] [clave a utilizar]</p>
    <p><br>En este caso se le indica al cifrador que tome el archivo <archivo a cifrar> y el archivo de clave <clave a utilizar> (un archivo de extensi�n �pri� o �pub�, seg�n se desee cifrar con la clave privada del remitente o con la clave p�blica del destinatario). Para tal fin se utiliza un m�todo bastante simple (al menos en esta versi�n del programa) que consiste en �leer� el archivo <archivo a cifrar> en bloques de bytes de igual tama�o que la clave usada y contenida en <clave a utilizar>, aplicar entre ellos una operaci�n l�gica de XOR (hemos elegido �sta por ser reversible y por lo mismo asegurar la igualdad a nivel de bits del archivo original sin cifrar con el archivo descifrado), y �escribir� el bloque resultante en un archivo de salida llamado �[archivo a cifrar].cif�</p>
    </a>
</tr></td>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Descifrado de un archivo</b></td></tr>
<tr><td class=presentacion>
    <a name="descifrado">
    <p><br>jcripto -d [archivo cifrado] [clave a utilizar]</p>
    <p><br>Con estos par�metros el programa utiliza la clave �[clave a utilizar]� (un archivo .pri o .pub seg�n corresponda) para descifrar el archivo �<archivo cifrado>� (utilizando la misma operaci�n que se utiliz� para cifrarlo, XOR) y generando un archivo cuyo nombre es igual al del archivo �[archivo cifrado]� pero sin la extensi�n �cif�.</p>
    </a>
</tr></td>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Firmado de un archivo</b></td></tr>
<tr><td class=presentacion>
    <a name="firmado">
    <p><br>jcripto -f [archivo]</p>
    <p><br>Esto realiza el firmado del archivo �<archivo>� utilizando un m�todo simple, con el cual se �lee� el archivo en bloques fijos de �n� bytes, y luego de suman estos bloques (trat�ndolos como n�meros �grandes�), el resultado se almacena en un archivo �<archivo>.hash�. El m�todo utilizado es lo suficientemente preciso como para obtener un �firmado� distinto en caso de modificar incluso una letra en el archivo original.</p>
    </a>
</tr></td>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Verificaci�n de la firma</b></td></tr>
<tr><td class=presentacion>
    <a name="verificacion">
    <p><br>jcripto -v [firma] [archivo descifrado]</p>
    <p><br>En este caso el programa compara (a nivel de bits) la firma provista �[firma]" con la obtenida al realizar el �firmado� del archivo descifrado �[archivo descifrado]�, si coinciden se establece la legitimidad del archivo recibido.</p>
   </a>
</tr></td>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Compresi�n de archivos</b></td></tr>
<tr><td class=presentacion>
    <a name="compresion">
    <p><br>jcripto -z [archivo1] [archivo2]</p>
    <p><br>La intenci�n de esta funcionalidad es la de comprimir (utilizando el algoritmo de Huffman -ZIP) el archivo cifrado y el que contiene la firma para que el env�o por un medio digital (Internet, tel�fono, etc.) sea menos costoso. El programa genera un archivo �zip� cuyo nombre corresponde al del primer archivo pasado como par�metro.</p>
    </a>
</tr></td>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Descomprensi�n de archivos</b></td></tr>
<tr><td class=presentacion>
    <a name="descompresion">
    <p><br>jcripto -u [archivo1]</p>
    <p><br>Con esto el programa descomprime el archivo �[archivo]� en los dos originalmente comprimidos.</p>
    </a>
</tr></td>
<tr><td>&nbsp;</td></tr>
<tr id=ma><td align=center><b>Comparaci�n de archivos</b></td></tr>
<tr><td class=presentacion>
    <a name="comparacion">
    <p><br>jcripto -cmp [archivo1] [archivo2]</p>
    <p><br>Esta es una funcionalidad agregada con el solo prop�sito de verificaci�n �manual� del correcto funcionamiento del cifrado principalmente al procesar archivos con formatos nuevos o de tama�os considerables.</p>
    </a>
</tr></td>
<tr><td>&nbsp;</td></tr>



<tr id=ma><td align=center><b>Conclusi�n </b></td></tr>

<tr><td class=presentacion>
    <a name="conclusion">
    <p><br>La implementaci�n del m�todo descripto nos permiti� entender el funcionamiento de todo el proceso de cifrado y firmado digital enfrent�ndonos a "problemas reales" en cuanto a seguridad d�ndonos una idea de lo que se busca en dicha �rea de la ciencia de la computaci�n de una manera mas did�ctica que simplemente implementar un m�todo reconocido y en el que no hay algo para descubrir.</p>
    </a>
</tr></td>
</table>                                                                                                                               