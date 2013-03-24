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
  <td align=center class=titulo><font color=<?=$letra_fondo?> size=3><b>Información Sobre Firmas Digitales</b></font></td>
  </tr>
  <tr>
    <td class=presentacion>
                      <P>La seguridad es uno de los elementos clave en el desarrollo
                  positivo de las redes de información mundial y particularmente
                  en el comercio electrónico, ésta genera confianza, y hace que
                  los usuarios al depositar sus datos en la red, estén seguros
                  de que no serán alterados ni desviados a usuarios no
                  autorizados.</P>
                  <P><BR>¿Qué es una firma electrónica?</P>
                  <P><BR>Es aquél conjunto de datos, como códigos o claves
                  criptográficas privadas, en forma electrónica, que se asocian
                  inequívocamente a un documento electrónico (es decir,
                  contenido en un soporte magnético -disquete o disco duro de un
                  ordenador- y no de papel), que permite identificar a su autor.
                  Cuando esto identificación es altamente fiable y permite
                  detectar cualquier alteración del documento no autorizada
                  merced a que los dispositivos empleados en la creación de la
                  firma son seguros, por cumplir ciertas exigencias técnicas, y
                  porque el Prestador de Servicios de Certificación que ha
                  intervenido esta "acreditado" entonces se habla de "firma
                  electrónica avanzada".</P>
                  <P><BR>Debemos contar con un ordenador con conexión con
                  Internet y con un dispositivo lector de tarjetas de firma
                  electrónica. A continuación debemos acudir a un Prestador de
                  Servicios de Certificación, que procederá a nuestra
                  identificación personal. Tras ello generará nuestras claves,
                  pública y privada, y nos entregará la tarjeta o disquete que
                  contenga esta clave privada, así como la aplicación
                  informática o programa necesario para su uso, que se ha de
                  instalar en nuestro ordenador. Con ello ya estamos listos para
                  la firma de un documento o archivo que hayamos creado, el cual
                  podremos además encriptar, y lo enviaremos por correo
                  electrónico a su destinatario, junto con el certificado de
                  nuestro Prestador en el que se avala nuestra identidad.</P>
                  <P><BR>La clave privada (secreta), generalmente, según hemos
                  indicado se encuentra incorporada en tarjetas inteligentes,
                  similares a las de crédito, que incorporan un chip que
                  contiene información de su titular, la entidad que la ha
                  emitido y el conjunto de bits en que consiste la clave. Estas
                  tarjetas son de uso personal e intransferible por estar
                  protegida por un código secreto que sólo su titular
conoce.</P>
                  <P></P>
                  <P>Dentro de la categoría genérica de firma electrónica, es
                  necesario hacer una subdivisión entre firma electrónica en
                  general y firma electrónica segura, refrendada o firma
                  digital. Esta distinción tiene su origen en la cifra de la
                  firma electrónica, en la tecnología que se ha aplicado para
                  poder calificar a la firma como segura o no. Además, tiene
                  repercusiones posteriores, puesto que la legislación prima a
                  las firmas digitales o firmas electrónicas seguras (tanto a
                  nivel internacional como comunitario). </P>
                  <P>La diferencia principal entre ambos tipos de firma, radica
                  en el sistema criptográfico que se ha utilizado: para las
                  firmas electrónicas en general se utilizan un sistema
                  criptográfico simétrico o de clave secreta; mientras que para
                  la firma digital el método utilizado es asimétrico o de clave
                  pública. </P>
                  <P><BR>Una firma digital es una cadena de datos creada a
                  partir de un mensaje, o parte de un mensaje, de forma que sea
                  imposible que quién envía el mensaje reniegue de él (repudio)
                  y que quien recibe el mensaje pueda asegurar que quién dice
                  que lo ha enviado es realmente quien lo ha enviado, es decir,
                  el receptor de un mensaje digital puede asegurar cual es el
                  origen del mismo (autenticación). Así mismo, las firmas
                  digitales pueden garantizar la integridad de los datos (que no
                  se hayan modificado los datos durante la transmisión).</P>
                  <P><BR>Los sistemas de clave pública permiten además cumplir
                  los requisitos de integridad del mensaje, autenticación y no
                  repudio del remitente utilizando firmas digitales. El
                  procedimiento de firma digital de un mensaje consiste en
                  extraer un "resumen" (o hash en inglés) del mensaje, cifrar
                  este resumen con la clave privada del remitente y añadir el
                  resumen cifrado al final del mensaje. A continuación, el
                  mensaje más la firma (el resumen cifrado) se envían como antes
                  cifrados con la clave pública del destinatario. El algoritmo
                  que se utiliza para obtener el resumen del mensaje debe
                  cumplir la propiedad de que cualquier modificación del mensaje
                  original, por pequeña que sea, dé lugar a un resumen
                  diferente. (Nótese que la firma digital de un usuario no es
                  siempre la misma secuencia de bits, sino que depende del
                  mensaje firmado.) </P>
                  <P>El proceso de firma es el siguiente:</P>
                  <P><BR>El usuario prepara el mensaje a enviar. </P>
                  <P><BR>El usuario utiliza una función hash segura para
                  producir un resumen del mensaje. </P>
                  <P><BR>El remitente encripta el resumen con su clave privada.
                  La clave privada es aplicada al texto del resumen usando un
                  algoritmo matemático. La firma digital consiste en la
                  encriptación del resumen. </P>
                  <P><BR>El remitente une su firma digital a los datos. </P>
                  <P><BR>El remitente envía electrónicamente la firma digital y
                  el mensaje original al destinatario. El mensaje puede estar
                  encriptado, pero esto es independiente del proceso de firma.
                  </P>
                  <P><BR>El destinatario usa la clave pública del remitente para
                  verificar la firma digital, es decir para desencriptar el
                  resumen adosado al mensaje. </P>
                  <P><BR>El destinatario realiza un resumen del mensaje
                  utilizando la misma función resumen segura. </P>
                  <P><BR>El destinatario compara los dos resúmenes. Si los dos
                  son exactamente iguales el destinatario sabe que los datos no
                  han sido alterados desde que fueron firmados. </P>
                  <P>
                  <IMG src="../../imagenes/firma_digital_esquema.gif"><BR>
                  </P>
                  <P></P>
                  <P>Cuando el destinatario recibe el mensaje, lo descifra con
                  su clave privada y pasa a comprobar la firma. Para ello, hace
                  dos operaciones: por un lado averigua la clave pública del
                  remitente y descifra con ella el resumen que calculó y cifró
                  el remitente. Por otro lado, el destinatario calcula el
                  resumen del mensaje recibido repitiendo el procedimiento que
                  usó el remitente. Si los dos resúmenes (el del remitente
                  descifrado y el calculado ahora por el destinatario) coinciden
                  la firma se considera válida y el destinatario puede estar
                  seguro de la integridad del mensaje: si el mensaje hubiera
                  sido alterado a su paso por la red, el resumen calculado por
                  el destinatario no coincidiría con el original calculado por
                  el remitente. </P>
                  <P>Además, el hecho de que el resumen original se ha
                  descifrado con la clave pública del remitente prueba que sólo
                  él pudo cifrarlo con su clave privada. Así el destinatario
                  está seguro de la procedencia del mensaje (autenticación del
                  origen) y, llegado el caso, el remitente no podría negar
                  haberlo enviado (no repudio) ya que sólo él conoce su clave
                  secreta. </P>
                  <P>Los inconvenientes de este sistema son la lentitud de los
                  algoritmos de clave asimétrica (típicamente varia veces más
                  lentos que los de clave simétrica) y la necesidad de las
                  autoridades de certificación ya mencionadas.</P>
                  <P><BR>Las funciones Hash sirven para comprimir un texto en un
                  bloque de longitud fija. Se utilizan en autenticación y firma
                  digital para:</P>
                  <P>1. No tener que encriptar todo el texto en los servicios de
                  autenticación y firma digital, ya que este proceso es lento
                  con los algoritmos asimétricos. El resumen sirve para
                  comprobar si la clave privada del emisor es auténtica, no es
                  necesario encriptar todo el texto si no se quiere
                  confidencialidad.</P>
                  <P>2. Para poder comprobar automáticamente la autenticidad. Si
                  se encripta todo el texto, al desencriptar sólo se puede
                  comprobar la autenticidad mirando si el resultado es
                  inteligible, evidentemente este proceso debe realizarse de
                  forma manual.</P>
                  <P>Utilizando un resumen del texto, se puede comprobar si es
                  auténtico comparando el resumen realizado en el receptor con
                  el desencriptado.</P>
                  <P>3. Para comprobar la integridad del texto, ya que si ha
                  sido dañado durante la transmisión o en recepción no
                  coincidirá el resumen del texto recibido con la
                  desencriptación.</P>
                  <P><BR></P>
                  <P><BR>Las funciones Hash son públicas e irreversibles. No
                  encriptan, sólo comprimen los textos en un bloque de longitud
                  fija. Son diferentes de las funciones clásicas de compresión
                  de textos, como ZIP, Huffman, V-42, etc.., estas funciones son
                  reversibles e intentan eliminar la redundancia de los textos
                  manteniendo el significado. Las funciones Hash no son
                  reversibles, es decir, no se puede recuperar el texto desde el
                  resumen, pero deben cumplir las siguientes condiciones:</P>
                  <P><BR>1. Transformar un texto de longitud variable en un
                  bloque de longitud fija.</P>
                  <P><BR>2. Ser irreversibles.</P>
                  <P><BR>3. Conocido un mensaje y su función Hash debe ser
                  imposible encontrar otro mensaje con la misma función Hash.
                  Esto se debe cumplir para evitar que los criptoanalistas
                  firmen un mensaje propio como si fueran otra persona.</P>
                  <P><BR>4.Es imposible inventar dos mensajes cuya función Hash sea la
                  misma.</P>
                  <P>Los algoritmos más utilizados son:</P>
                  <P>Para aplicaciones únicamente de autenticación y integridad,
                  no firma, se puede añadir una clave simétrica a la generación
                  del resumen. De esta manera no es necesario encriptar, está
                  clave ya demuestra que el usuario es auténtico y el resumen
                  propiamente demuestra la integridad del texto. El problema es
                  utilizar una clave simétrica y, por lo tanto, se debe
                  transmitir por un canal seguro, el sistema utilizado
                  actualmente es el de claves de sesión encriptadas mediante la
                  clave privada del emisor.</P>


    </td>
  </tr>
</table>