<?
/*
Autor:Fernando
Date:20/04/2005
*/
require_once("../../config.php");
echo $html_header;
?>
<table width=100% align=center class=bordes>
  <tr bgcolor=<?=$cabecera_fondo?>>
  <td align=center class=titulo><font color=<?=$letra_fondo?> size=3><b>Informaci�n Sobre Firmas Digitales</b></font></td>
  </tr>
  <tr>
    <td class=presentacion>
                      <P>La seguridad es uno de los elementos clave en el desarrollo
                  positivo de las redes de informaci�n mundial y particularmente
                  en el comercio electr�nico, �sta genera confianza, y hace que
                  los usuarios al depositar sus datos en la red, est�n seguros
                  de que no ser�n alterados ni desviados a usuarios no
                  autorizados.</P>
                  <P><BR>�Qu� es una firma electr�nica?</P>
                  <P><BR>Es aqu�l conjunto de datos, como c�digos o claves
                  criptogr�ficas privadas, en forma electr�nica, que se asocian
                  inequ�vocamente a un documento electr�nico (es decir,
                  contenido en un soporte magn�tico -disquete o disco duro de un
                  ordenador- y no de papel), que permite identificar a su autor.
                  Cuando esto identificaci�n es altamente fiable y permite
                  detectar cualquier alteraci�n del documento no autorizada
                  merced a que los dispositivos empleados en la creaci�n de la
                  firma son seguros, por cumplir ciertas exigencias t�cnicas, y
                  porque el Prestador de Servicios de Certificaci�n que ha
                  intervenido esta "acreditado" entonces se habla de "firma
                  electr�nica avanzada".</P>
                  <P><BR>Debemos contar con un ordenador con conexi�n con
                  Internet y con un dispositivo lector de tarjetas de firma
                  electr�nica. A continuaci�n debemos acudir a un Prestador de
                  Servicios de Certificaci�n, que proceder� a nuestra
                  identificaci�n personal. Tras ello generar� nuestras claves,
                  p�blica y privada, y nos entregar� la tarjeta o disquete que
                  contenga esta clave privada, as� como la aplicaci�n
                  inform�tica o programa necesario para su uso, que se ha de
                  instalar en nuestro ordenador. Con ello ya estamos listos para
                  la firma de un documento o archivo que hayamos creado, el cual
                  podremos adem�s encriptar, y lo enviaremos por correo
                  electr�nico a su destinatario, junto con el certificado de
                  nuestro Prestador en el que se avala nuestra identidad.</P>
                  <P><BR>La clave privada (secreta), generalmente, seg�n hemos
                  indicado se encuentra incorporada en tarjetas inteligentes,
                  similares a las de cr�dito, que incorporan un chip que
                  contiene informaci�n de su titular, la entidad que la ha
                  emitido y el conjunto de bits en que consiste la clave. Estas
                  tarjetas son de uso personal e intransferible por estar
                  protegida por un c�digo secreto que s�lo su titular
conoce.</P>
                  <P></P>
                  <P>Dentro de la categor�a gen�rica de firma electr�nica, es
                  necesario hacer una subdivisi�n entre firma electr�nica en
                  general y firma electr�nica segura, refrendada o firma
                  digital. Esta distinci�n tiene su origen en la cifra de la
                  firma electr�nica, en la tecnolog�a que se ha aplicado para
                  poder calificar a la firma como segura o no. Adem�s, tiene
                  repercusiones posteriores, puesto que la legislaci�n prima a
                  las firmas digitales o firmas electr�nicas seguras (tanto a
                  nivel internacional como comunitario). </P>
                  <P>La diferencia principal entre ambos tipos de firma, radica
                  en el sistema criptogr�fico que se ha utilizado: para las
                  firmas electr�nicas en general se utilizan un sistema
                  criptogr�fico sim�trico o de clave secreta; mientras que para
                  la firma digital el m�todo utilizado es asim�trico o de clave
                  p�blica. </P>
                  <P><BR>Una firma digital es una cadena de datos creada a
                  partir de un mensaje, o parte de un mensaje, de forma que sea
                  imposible que qui�n env�a el mensaje reniegue de �l (repudio)
                  y que quien recibe el mensaje pueda asegurar que qui�n dice
                  que lo ha enviado es realmente quien lo ha enviado, es decir,
                  el receptor de un mensaje digital puede asegurar cual es el
                  origen del mismo (autenticaci�n). As� mismo, las firmas
                  digitales pueden garantizar la integridad de los datos (que no
                  se hayan modificado los datos durante la transmisi�n).</P>
                  <P><BR>Los sistemas de clave p�blica permiten adem�s cumplir
                  los requisitos de integridad del mensaje, autenticaci�n y no
                  repudio del remitente utilizando firmas digitales. El
                  procedimiento de firma digital de un mensaje consiste en
                  extraer un "resumen" (o hash en ingl�s) del mensaje, cifrar
                  este resumen con la clave privada del remitente y a�adir el
                  resumen cifrado al final del mensaje. A continuaci�n, el
                  mensaje m�s la firma (el resumen cifrado) se env�an como antes
                  cifrados con la clave p�blica del destinatario. El algoritmo
                  que se utiliza para obtener el resumen del mensaje debe
                  cumplir la propiedad de que cualquier modificaci�n del mensaje
                  original, por peque�a que sea, d� lugar a un resumen
                  diferente. (N�tese que la firma digital de un usuario no es
                  siempre la misma secuencia de bits, sino que depende del
                  mensaje firmado.) </P>
                  <P>El proceso de firma es el siguiente:</P>
                  <P><BR>El usuario prepara el mensaje a enviar. </P>
                  <P><BR>El usuario utiliza una funci�n hash segura para
                  producir un resumen del mensaje. </P>
                  <P><BR>El remitente encripta el resumen con su clave privada.
                  La clave privada es aplicada al texto del resumen usando un
                  algoritmo matem�tico. La firma digital consiste en la
                  encriptaci�n del resumen. </P>
                  <P><BR>El remitente une su firma digital a los datos. </P>
                  <P><BR>El remitente env�a electr�nicamente la firma digital y
                  el mensaje original al destinatario. El mensaje puede estar
                  encriptado, pero esto es independiente del proceso de firma.
                  </P>
                  <P><BR>El destinatario usa la clave p�blica del remitente para
                  verificar la firma digital, es decir para desencriptar el
                  resumen adosado al mensaje. </P>
                  <P><BR>El destinatario realiza un resumen del mensaje
                  utilizando la misma funci�n resumen segura. </P>
                  <P><BR>El destinatario compara los dos res�menes. Si los dos
                  son exactamente iguales el destinatario sabe que los datos no
                  han sido alterados desde que fueron firmados. </P>
                  <P>
                  <IMG src="../../imagenes/firma_digital_esquema.gif"><BR>
                  </P>
                  <P></P>
                  <P>Cuando el destinatario recibe el mensaje, lo descifra con
                  su clave privada y pasa a comprobar la firma. Para ello, hace
                  dos operaciones: por un lado averigua la clave p�blica del
                  remitente y descifra con ella el resumen que calcul� y cifr�
                  el remitente. Por otro lado, el destinatario calcula el
                  resumen del mensaje recibido repitiendo el procedimiento que
                  us� el remitente. Si los dos res�menes (el del remitente
                  descifrado y el calculado ahora por el destinatario) coinciden
                  la firma se considera v�lida y el destinatario puede estar
                  seguro de la integridad del mensaje: si el mensaje hubiera
                  sido alterado a su paso por la red, el resumen calculado por
                  el destinatario no coincidir�a con el original calculado por
                  el remitente. </P>
                  <P>Adem�s, el hecho de que el resumen original se ha
                  descifrado con la clave p�blica del remitente prueba que s�lo
                  �l pudo cifrarlo con su clave privada. As� el destinatario
                  est� seguro de la procedencia del mensaje (autenticaci�n del
                  origen) y, llegado el caso, el remitente no podr�a negar
                  haberlo enviado (no repudio) ya que s�lo �l conoce su clave
                  secreta. </P>
                  <P>Los inconvenientes de este sistema son la lentitud de los
                  algoritmos de clave asim�trica (t�picamente varia veces m�s
                  lentos que los de clave sim�trica) y la necesidad de las
                  autoridades de certificaci�n ya mencionadas.</P>
                  <P><BR>Las funciones Hash sirven para comprimir un texto en un
                  bloque de longitud fija. Se utilizan en autenticaci�n y firma
                  digital para:</P>
                  <P>1. No tener que encriptar todo el texto en los servicios de
                  autenticaci�n y firma digital, ya que este proceso es lento
                  con los algoritmos asim�tricos. El resumen sirve para
                  comprobar si la clave privada del emisor es aut�ntica, no es
                  necesario encriptar todo el texto si no se quiere
                  confidencialidad.</P>
                  <P>2. Para poder comprobar autom�ticamente la autenticidad. Si
                  se encripta todo el texto, al desencriptar s�lo se puede
                  comprobar la autenticidad mirando si el resultado es
                  inteligible, evidentemente este proceso debe realizarse de
                  forma manual.</P>
                  <P>Utilizando un resumen del texto, se puede comprobar si es
                  aut�ntico comparando el resumen realizado en el receptor con
                  el desencriptado.</P>
                  <P>3. Para comprobar la integridad del texto, ya que si ha
                  sido da�ado durante la transmisi�n o en recepci�n no
                  coincidir� el resumen del texto recibido con la
                  desencriptaci�n.</P>
                  <P><BR></P>
                  <P><BR>Las funciones Hash son p�blicas e irreversibles. No
                  encriptan, s�lo comprimen los textos en un bloque de longitud
                  fija. Son diferentes de las funciones cl�sicas de compresi�n
                  de textos, como ZIP, Huffman, V-42, etc.., estas funciones son
                  reversibles e intentan eliminar la redundancia de los textos
                  manteniendo el significado. Las funciones Hash no son
                  reversibles, es decir, no se puede recuperar el texto desde el
                  resumen, pero deben cumplir las siguientes condiciones:</P>
                  <P><BR>1. Transformar un texto de longitud variable en un
                  bloque de longitud fija.</P>
                  <P><BR>2. Ser irreversibles.</P>
                  <P><BR>3. Conocido un mensaje y su funci�n Hash debe ser
                  imposible encontrar otro mensaje con la misma funci�n Hash.
                  Esto se debe cumplir para evitar que los criptoanalistas
                  firmen un mensaje propio como si fueran otra persona.</P>
                  <P><BR>4.Es imposible inventar dos mensajes cuya funci�n Hash sea la
                  misma.</P>
                  <P>Los algoritmos m�s utilizados son:</P>
                  <P>Para aplicaciones �nicamente de autenticaci�n y integridad,
                  no firma, se puede a�adir una clave sim�trica a la generaci�n
                  del resumen. De esta manera no es necesario encriptar, est�
                  clave ya demuestra que el usuario es aut�ntico y el resumen
                  propiamente demuestra la integridad del texto. El problema es
                  utilizar una clave sim�trica y, por lo tanto, se debe
                  transmitir por un canal seguro, el sistema utilizado
                  actualmente es el de claves de sesi�n encriptadas mediante la
                  clave privada del emisor.</P>


    </td>
  </tr>
</table>