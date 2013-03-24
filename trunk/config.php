<?

/*******************************************
 ** Constantes para usar en los include/require
 ** (Directorios relativos al sistema)
 *******************************************/

define("ROOT_DIR", dirname(__FILE__));			// Directorio raiz
define("LIB_DIR", ROOT_DIR."/lib");				// Librerias del sistema
define("UPLOADS_DIR", ROOT_DIR."/uploads");
define("KEYS_DIR", ROOT_DIR."/claves");
define("CODIGO_JAVA", ROOT_DIR."/codigo_java");
/*
define("MOD_DIR", ROOT_DIR."/modulos");			// Modulos del sistema
define("UPLOADS_DIR", ROOT_DIR."/uploads");		// Directorio para uploads
*/
/*******************************************
 ** Headers para que el explorador no guarde
 ** las páginas en cache.
 *******************************************/

/*******************************************
 ** Colores del sistema.
 *******************************************/
$bgcolor_frames = "#A6BAD1";  //color de frames del sistema

$bgcolor1 = "#5090C0";		// Primer color de fondo
$bgcolor2 = "#D5D5D5";		// Segundo color de fondo
$bgcolor3 = "#E0E0E0";		// Tercer color de fondo
$bgcolor4 = "#FF0000";		// Color de fondo para tareas vencidas
$bgcolor  = "#d1c294";
$letra_fondo="#F0F0F0";
$cabecera_fondo="#42AAAA";


$bgcolor_out  = "#B7C7D0"; // Color de fondo (onmouseout)
//$bgcolor_over = "#EEFFE6"; // Color de fondo (onmouseover)
$bgcolor_over = "#FEF9d6"; // Color de fondo (onmouseover)
$text_color_out  = "#000000";
$text_color_over = "#004962";//"#006699";
$fondo = "fondo.gif"; //imagen de fondo

// atributo de los tr de los listados
$atrib_tr="bgcolor=$bgcolor_out onmouseover=\"this.style.backgroundColor = '$bgcolor_over'; this.style.color = '$text_color_over'\" onmouseout=\"this.style.backgroundColor = '$bgcolor_out'; this.style.color = '$text_color_out'\"";

/*******************************************
 ** Cantidad de items a mostrar por página.
 *******************************************/

$itemspp = 50;

/*******************************************
 ** Configuración de la base de datos.
 *******************************************/

$db_type = 'postgres7';				// Tipo de base de datos.
$db_host = 'localhost';
$db_user = 'projekt';				// Usuario.
$db_password = 'propcp';			// Contraseña.
$db_name = 'firmas';

$html_root=ROOT_DIR;

// Nombre de la base de datos.
//$db_name = 'gestion';				// Nombre de la base de datos.
$ADODB_CACHE_DIR = LIB_DIR."/adodb/cache";		// Directorio para cache de consultas
// Arreglo que contiene los nombres de los esquemas en la
// base de datos para poder acceder a las tablas sin tener
// que usar en nombre del esquema.
$db_schemas = array(

);
$db_debug = FALSE;					// Debugger de las consultas.

/*******************************************
 ** Limite de tiempo de inactividad para la
 ** expiración de la sesión (en minutos).
 *******************************************/

$session_timeout = 90;

/*******************************************
 ** Variable $html_root que contiene la ruta
 ** a la raíz de la página.
 ** (Ruta relativa al URL de la página)
 *******************************************/
unset($tmp);

session_start();

/*******************************************
 ** Variable $html_footer contiene el
 ** pie de la página.
 *******************************************/

$html_footer = "
  </body>
</html>
";
$dia_semana = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

$linea_ejecucion="java -jar ..\\..\\codigo_java\\jcripto.jar";


//inicializo las variables de sesion
//session_start();


/*******************************************
 ** Libreria principal del sistema.
 *******************************************/


require_once(LIB_DIR."/lib.php");




/*******************************************
 ** Variable $html_header contiene el
 ** encabezamiento de la página.
 *******************************************/

$html_header = "
<html>
  <head>
	<link rel=stylesheet type='text/css' href='../../lib/estilos.css'>
  </head>
 <body  bgcolor=\"$bgcolor3\">

";
?>
