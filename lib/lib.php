<?php
/*
Autor: Fernando Tripicchio
Date: 20/04/2005
Comentario: Funciones de ambito general para el sistema de firmas digitales
*/
require_once(LIB_DIR."/adodb/adodb.inc.php");
require_once(LIB_DIR."/adodb/adodb-pager.inc.php");

define("TIEMPO_INICIO", getmicrotime());

// Tamaño máximo de los archivos a subir
$max_file_size = get_cfg_var("upload_max_filesize");  // Por defecto deberia se 5 MB


$db = &ADONewConnection($db_type) or die("Error al conectar a la base de datos");
$db->Connect($db_host, $db_user, $db_password, $db_name);
$db->cacheSecs = 3600;
//$sql="SET search_path=".join(",",$db_schemas);
//$result=$db->Execute($sql) or die($db->ErrorMsg()." ".$sql);
unset($result);
$db->debug = $db_debug;

if (ereg("Win32",$_SERVER["SERVER_SOFTWARE"]) ||
    ereg("Microsoft",$_SERVER["SERVER_SOFTWARE"]))
	define("SERVER_OS", "windows");
else
	define("SERVER_OS", "linux");
// libreria para administrar los permisos


/***********************************
 ** Funciones de ambito general
 ***********************************/


 function destruir_sesion(){
   global $_SESSION;
    $_SESSION["user"]=0;

 }
function registrar_sesion($var){
   global $_SESSION;

   $_SESSION["user"]=$var;

}

function mix_string($string) {
	$split = 4;    // mezclar cada $split caracteres
	$str = str_replace("=","",$string);
	$string = "";
	$str_tmp = explode(":",chunk_split($str,$split,":"));
	for ($i=0;$i<count($str_tmp);$i+=2) {
		 if (strlen($str_tmp[$i+1]) != $split) {
			 $string .= $str_tmp[$i] . $str_tmp[$i+1];
		 }
         else {
               $string .= $str_tmp[$i+1] . $str_tmp[$i];
		 }
    }
	return $string;
}
function encode_link() {
	$args = func_num_args();
	if ($args == 2) {
		$link = func_get_arg(0);
		$p = func_get_arg(1);
	}
	elseif ($args == 1) {
		$p = func_get_arg(0);
	}
	$str = comprimir_variable($p);
	$string = mix_string($str);
	if(isset($link))
		return $link."?p=".$string;
	else
		return $string;
}
function decode_link($link) {
    $str = mix_string($link);
	$cant = strlen($str)%4;
    if ($cant > 0) $cant = 4 - $cant;
    for ($i=0;$i < $cant;$i++) {
		 $str .= "=";
    }
    return descomprimir_variable($str);
}
/* Funcion para cambiar el tipo de arreglo
   que retorna la consulta a la base de datos
   El paramentro puede ser "a" para que retorne
	un arreglo asociativo con los nombres de las
   columnas como indices, y "n" para que retorne
   un arreglo con los indices de forma de numeros
*/
function db_tipo_res($tipo="d") {
	global $db;
	switch ($tipo) {
	   case "a":   // tipo asociativo
		   $db->SetFetchMode(ADODB_FETCH_ASSOC);
		   break;
	   case "n":   // tipo numerico
		   $db->SetFetchMode(ADODB_FETCH_NUM);
		   break;
	   case "d":
		   $db->SetFetchMode(ADODB_FETCH_BOTH);
		   break;
   }
}

/*
 * Funcion para cambiar un color por otro alternativo
 * cuando los colores son parecidos o no contrastan mucho.
 * los parametros son de la forma: #ffffff
*/
function contraste($fondo, $frente, $reemplazo) {
	$brillo = 125;
   $diferencia = 400;
	$bg = ereg_replace("#","",$fondo);
	$fg = ereg_replace("#","",$frente);
	$bg_r = hexdec(substr($bg,0,2));
	$bg_g = hexdec(substr($bg,2,2));
	$bg_b = hexdec(substr($bg,4,2));
	$fg_r = hexdec(substr($fg,0,2));
	$fg_g = hexdec(substr($fg,2,2));
	$fg_b = hexdec(substr($fg,4,2));
	$bri_bg = (($bg_r * 299) + ($bg_g * 587) + ($bg_b * 114)) / 1000;
	$bri_fg = (($fg_r * 299) + ($fg_g * 587) + ($fg_b * 114)) / 1000;
	$dif = max(($fg_r - $bg_r),($bg_r - $bg_r)) + max(($fg_g - $bg_g),($bg_g - $fg_g)) + max(($fg_b - $bg_b),($bg_b - $fg_b));
	if(intval($bri_bg - $bri_fg) > $brillo or $dif > $diferencia) {
   	return $frente;
   }
   else {
   	return $reemplazo;
   }
}
/*
 * @return array
 * @param sql string
 * @param orden array
 * @param filtro array
 * @param link_pagina string
 * @param where_extra string (opcional)
 * @desc Esta funcion genera el formulario de busqueda y divide el resultado
         de una consulta sql por paginas
         Ejemplo:
		 // variables que contienen los datos actuales de la busqueda
         $page = $_GET["page"] or $page = 0;                                                                //pagina actual
				 $filter = $_POST["filter"] or $filter = $_GET["filter"];                //campo por el que se esta filtrando
				 $keyword = $_POST["keyword"] or $keyword = $_GET["keyword"];        //palabra clave

                 $orden = array(                                        //campos que voy a mostar
                        "default" => "2",                                //campo por defecto
                        "1" => "IdProv",
                        "2" => "Proveedor"
                 );

                 $filtro = array(
						"Proveedor"                => "Proveedor",                //elementos en donde se van a hacer las busquedas
                        "Contacto"                => "Contacto",                //el formato del aarreglo es:
                        "Mail"                        => "Mail"                        //$filtro=array("nombre de la columna en la base de datos" => "nombre a mostrar en el formulario");
                 );
                 //sentencia sql que sin ninguna condicion
				 $sql_tmp = "SELECT IdProv,Proveedor,Contacto,Mail,Teléfono,Comentarios FROM bancos.proveedores";
				 //prefijo para los links de paginas siguiente y anterior
                 $link_tmp = "<a id=ma href='bancos.php?mode=$mode&cmd=$cmd";
                 //condiciones extras de la consulta
				 $where_tmp = "";

				 list($sql,$total_Prov,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp);

*/
function form_busqueda($sql,$orden,$filtro,$link_pagina,$where_extra="",$contar=0,$sumas="",$ignorar="",$seleccion="") {
		global $bgcolor2,$page,$filter,$keyword,$sort,$up;
		global $itemspp,$parametros;
        
        
           
		if ($_GET['page'])
			$page=($_GET['page'] > 0)?$_GET['page']-1:0;//controlo que no pongan valores negativos

		if ($up == "") {
			$up = $orden["default_up"];
		}
		if ($up == "") {
			$up = "1";
		}
		if ($up == "0") {
//				$up = $parametros["up"];
				$direction="DESC";
				$up2 = "1";
		}
		else {
				$up = "1";
				$direction = "ASC";
				$up2 = "0";
		}
		if ($sort == "") $sort = "default";
		if ($sort == "default") { $sort = $orden["default"]; }
		if ($orden[$sort] == "") { $sort = $orden["default"]; }
		if ($filtro[$filter] == "") { $filter = "all"; }
     
   
       
		$tmp=es_numero($keyword);
		echo "<input type=hidden name=form_busqueda value=1>";
		echo "<b>Buscar:&nbsp;</b><input type='text' name='keyword' value='$keyword' size=20 maxlength=150>\n";
		echo "<b>&nbsp;en:&nbsp;</b><select name='filter'>&nbsp;\n";
		echo "<option value='all'";
		if (!$filter or $filtro[$filter] == "") echo " selected";
		echo ">Todos los campos\n";
		while (list($key, $val) = each($filtro)) {
				echo "<option value='$key'";
				if ($filter == "$key") echo " selected";
				echo ">$val\n";
		}
		echo "</select>\n";

		//print_r($ignore);


		if ($keyword) {
				$where = "\nWHERE ";
				if ($filter == "all" or !$filter) {
						$where_arr = array();
						if (is_array($ignorar)) $where .= "((";
						else $where .= "(";
						reset($filtro);
						while (list($key, $val) = each($filtro)) {
							    if (is_array($ignorar) && !in_array($key,$ignorar))
							     $where_arr[] = "$key ILIKE '%$keyword%'";
							    if (!is_array($ignorar)) $where_arr[] = "$key ILIKE '%$keyword%'";

						}

						$where .= implode(" OR ", $where_arr);
						$where .= ")";

						if (is_array($seleccion)){
						while (list($key, $val) = each($seleccion)) {
						$where .= " OR ($val)";
						}
						$where .= ")";
						}
				}
				else {if (!is_array($ignorar)) $where .= "$filter ILIKE '%$keyword%'";
					  elseif (is_array($ignorar) && !in_array($filter,$ignorar))
						$where .= "$filter ILIKE '%$keyword%'";
						else $where .= " (".$seleccion[$filter].")";
				}
		}

		$sql .= " $where";
		if ($where_extra != "") {
				if ($where != "")
				{
					 //si no tiene un group by al principio
					 if (!eregi("^group by.*|^ group by.*",$where_extra))
						 $sql .= "\nAND";

				}
				else
				{
					 //si no tiene un group by al principio
					 if (!eregi("^group by.*|^ group by.*",$where_extra))
						 $sql .= "\nWHERE";

				}
				$sql .= " $where_extra";
		}
        //echo $sumas." AAAAAAAAAAAAAAAA<br>";
		if ("$contar"=="buscar") {
			$tipo_res = db_tipo_res("a");
//			$result = sql($sql,"CONTAR") or reportar_error($sql,__FILE__,__LINE__);
			$result = sql($sql,"CONTAR") or fin_pagina();
			$tipo_res = db_tipo_res();
			$total = $result->RecordCount();

			//Sumas de campos de montos caso en que usa la consulta general
			$res_sumas='';

			if (	$sumas!='' &&
					substr_count($sql,$sumas["campo"])>0 &&//si el campo esta definido
					is_array($sumas["mask"])//mascara para configurar el resultado
					) {
						$count_mask = count($sumas["mask"]);//tamaño de la mascara
						if ($count_mask==0) {//caso en que suma solo cantidades
							$acum=0;
							for($i=0;$i<$total;$i++){//for
								$acum+=$result->fields[$sumas["campo"]];
								$result->MoveNext();
							}	//fin de for
							$res_sumas ="$acum";
						}//fin de caso suma cantidades solam.
						elseif(substr_count($sql,$sumas["moneda"])>0) {//otro caso //si la moneda esta definida
							$sql_moneda="Select simbolo,id_moneda from moneda";
							$res_moneda=sql($sql_moneda,"Imposible obtener el listado de moneda") or fin_pagina();
							for($i;$i<$res_moneda->RecordCount();$i++){
								$moneda[$res_moneda->fields["id_moneda"]]=$res_moneda->fields["simbolo"];
								$res_moneda->MoveNext();
							}
								//print_r($moneda);
							for($i=0;$i<$count_mask;$i++) {//preparando el acumulador
								$acum[$i]=0;
							}//fin del for

							for($i=0;$i<$total;$i++){//for
								$pos = array_search($moneda[$result->fields[$sumas["moneda"]]],$sumas["mask"]);
								if (is_int($pos))
									$acum[$pos]+=$result->fields[$sumas["campo"]];
								$result->MoveNext();
							}	//fin de for
							$res_sumas = "";
							for($i=0;$i<$count_mask;$i++) { //preparando el resultado
								$res_sumas.=$sumas["mask"][$i].formato_money($acum[$i])." ";
							}//fin del for

						}//fin otro caso

					}
		}
		elseif($contar)
		{
//		$sql_cont = eregi_replace("^SELECT(.*)FROM", "SELECT COUNT(*) AS total FROM", $sql);
//		$sql_cont = eregi_replace("GROUP BY .*", "", $sql_cont);
			$tipo_res = db_tipo_res("n");
//		$result = $db->Execute($sql_cont) or die($db->ErrorMsg());
			$result = sql($contar,"CONTAR") or fin_pagina();
//		$total = $result->fields[0];
			$tipo_res = db_tipo_res();
			$total = $result->fields[0];


			if (is_string($sumas) && $sumas!="") {
				$tipo_res = db_tipo_res("n");
				$result = sql($sumas,"SUMAS") or fin_pagina();
				$tipo_res = db_tipo_res();
				$res_sumas="";
				for ($i=0;$i<$result->RecordCount();$i++){
					$res_sumas.=$result->fields[0]." ".formato_money($result->fields[1])." ";
					$result->MoveNext();
				}

			}
		}
		else {
			$total = 0;
			$res_sumas="";
		}

// $total=99;
		if ($sort != "") {
		    $sql .= "\nORDER BY ".$orden[$sort]." $direction";
		}

		$sql .= "\nLIMIT $itemspp OFFSET ".($page * $itemspp);

		$page_n = $page + 1;
		$page_p = $page - 1;
		$link_pagina_p = "";
		$link_pagina_n = "";
		if (!is_array($link_pagina)) $link_pagina = array();
//		$link_pagina["sort"] = $sort;
//		$link_pagina["up"] = $up;
//		$link_pagina["keyword"] = $keyword;
//		$link_pagina["filter"] = $filter;
		if ($page > 0) {
			$link_pagina["page"] = $page_p;
			$link_pagina_p = "<a title='Página anterior' href='".encode_link($_SERVER["SCRIPT_NAME"],$link_pagina)."'><<</a>";
		}
		$sum=0;
		if (($total % $itemspp)>0) $sum=1;

		$last_page=(intval($total/$itemspp)+$sum);
		$link_pagina_num = "&nbsp;&nbsp;Página&nbsp;<input type='text' value=".($page+1)." name='page' size=2 style='text-align:right;border:none' onkeypress=\" if ((show_alert=(window.event.keyCode==13)) && parseInt(this.value)>0 && parseInt(this.value)<= $last_page ) {location.href='".encode_link($_SERVER["SCRIPT_NAME"],$link_pagina). "&page='+parseInt(this.value);return false;} else if (show_alert) {alert('Por favor ingrese un número válido'); return false;} \" />&nbsp;de&nbsp;$last_page&nbsp;&nbsp;";
		if ($total > $page_n*$itemspp) {
			$link_pagina["page"] = $page_n;
			$link_pagina_n = "<a title='Página siguiente' href='".encode_link($_SERVER["SCRIPT_NAME"],$link_pagina)."'>>></a>";
		}
		if ($total > 0 and $total > $itemspp) {
			$link_pagina_ret = $link_pagina_p.$link_pagina_num.$link_pagina_n;
		}
		else {
			$link_pagina_ret = "";
		}

		return array($sql,$total,$link_pagina_ret,$up2,$res_sumas);
}

function Error($msg,$num="") {
	global $error;
	echo "<center><font size=4 color=#FF0000>Error $num: $msg</font><br></center>\n";
	$error = 1;
}

function link_calendario($control_pos, $control_dat="") {
	global $html_root;
	if ($control_dat == "") {
		$control_dat = $control_pos;
	}
	return "<img src=$html_root/imagenes/cal.gif border=0 align=middle style='cursor:hand;' alt='Haga click aqui para\nseleccionar la fecha'  onClick=\"javascript:popUpCalendar($control_pos, $control_dat, 'dd/mm/yyyy');\">";
}

function Aviso($msg) {
	echo "<br><center><font size=3 color=red><b>$msg</b></font></center><br>\n";
}

/**
 * @return string
 * @param fecha_db string
 * @desc Convierte una fecha de la forma AAAA-MM-DD
 *       a la forma DD/MM/AAAA
 */
function Fecha($fecha_db) {
		$m = substr($fecha_db,5,2);
		$d = substr($fecha_db,8,2);
		$a = substr($fecha_db,0,4);
		if (is_numeric($d) && is_numeric($m) && is_numeric($a)) {
				return "$d/$m/$a";
		}
		else {
				return "";
		}
}

function hora_ok($hora) {
    if ($hora) {
         $hora_arr = explode(":", $hora);
         if ( (is_numeric($hora_arr[0])) && ($hora_arr[0]>=0 && $hora_arr[0]<=23))
             $hora_apertura = $hora_arr[0];
         else
             return 0;
         if ( (is_numeric($hora_arr[1]))  && ($hora_arr[1]>=0 && $hora_arr[1]<=59) )
            $hora_apertura .= ":".$hora_arr[1];
        else
            return 0;
        if ( (is_numeric($hora_arr[2]))  && ($hora_arr[2]>=0 && $hora_arr[2]<=59))
            $hora_apertura .= ":".$hora_arr[2];
        else
           return 0;
    }

return $hora_apertura;

}


function Hora($hora_db) {
	if (ereg("([0-9]{2}:[0-9]{2}:[0-9]{2})",$hora_db,$hora))
		return $hora[0];
	else
		return "00:00:00";
}



/**
 * @return string
 * @param fecha string
 * @desc Convierte una fecha de la forma DD/MM/AAAA
 *       a la forma AAAA-MM-DD
 */

//funcion defectuosa
//cuidado
function Fecha_db($fecha) {
		if (strstr($fecha,"/"))
			list($d,$m,$a) = explode("/",$fecha);
		elseif (strstr($fecha,"-"))
			list($d,$m,$a) = explode("-",$fecha);
		else
			return "";
		return "$a-$m-$d";
}




/**
 * @return 1 o 0
 * @param fecha date
 * @desc Devuelve 1 si es fecha y 0 si no lo es.
 */
function FechaOk($fecha) {
	if (ereg("-",$fecha))
		list($dia,$mes,$anio)=split("-", $fecha);
	elseif (ereg("/",$fecha))
		list($dia,$mes,$anio)=split("/", $fecha);
	else
		return 0;
	return checkdate($mes,$dia,$anio);
}

/**
 * @return date
 * @param fecha date
 * @desc Convierte una fecha del formato dd-mm-aaaa al
 *       formato aaaa-mm-dd que usa la base de datos.
 */
function ConvFecha($fecha) {
	list($dia,$mes,$anio)=split("-", $fecha);
	return "$anio-$mes-$dia";
}

/**
 * @return int
 * @param fecha date
 * @desc Compara la fecha $fecha con la fecha actual.
 *       Retorna:
 *               0 si $fecha es mayor de 7 dias.
 *               1 si $fecha esta entre 0 y 7 dias.
 *               2 si $fecha es anterior a la fecha actual.
 */
function check_fecha($fecha) {
	$fecha2=strtotime($fecha);
	$num1=($fecha2-intval(time()))/60/60/24;
//    $res=0;
	if ($num1 > 7) {
	   $res=0;
    } elseif ($num1>=0 and $num1<=7) {
       $res=1;
    } else {
	   $res=2;
    }
	return($res);
}

// the same specialy for hidden form fields and select field option values (uev -> UrlEncodedValues)
//function uev_out($outstr){return ereg_replace("'","&#39;",htmlspecialchars(urlencode($outstr)));}


function atrib_tr($bgcolor_out_int='#FFFFFF'){
  global $bgcolor_over, $text_color_over, $text_color_out ;
  return "bgcolor=$bgcolor_out_int onmouseover=\"this.style.backgroundColor = '$bgcolor_over'; this.style.color = '$text_color_over'\" onmouseout=\"this.style.backgroundColor = '$bgcolor_out_int'; this.style.color = '$text_color_out'\"; style='cursor: hand;'";
  }

function tr_tag ($dblclick,$extra="",$bgcolor_out_int='#B7C7D0') {
  global $atrib_tr, $bgcolor_out, $cnr, $bgcolor1, $bgcolor2;
  if (($cnr/2) == round($cnr/2)) { $color = "$bgcolor1"; $cnr++;}
  else { $color = "$bgcolor2"; $cnr++; }
  if (!(strpos($dblclick,"target" )===false))
  {

	$t1=substr($dblclick,strpos($dblclick,"=")+1);
	$target=substr($t1,0,strpos($t1,";")).".";
	//; separa el target de la URL
	$dblclick=substr($dblclick,strpos($dblclick,";")+1);
  }
  $tr_hover_on = atrib_tr($bgcolor_out_int)." onClick=\"$target"."location.href ='$dblclick'\"";
  echo "<tr $tr_hover_on $extra>\n";
}


function formato_money($num) {
	return number_format($num, 2, ',', '.');
}

function es_numero(&$num) {
	if (strstr($num,",")) {
		$num = ereg_replace("\.","",$num);
		$num = ereg_replace(",",".",$num);
	}
	return is_numeric($num);
}



function cargar_feriados() {
	global $_ses_feriados;
	$ret = "";
	foreach ($_ses_feriados as $fecha => $descripciones) {
		list($mes,$dia) = split("-",$fecha);
		foreach ($descripciones as $descripcion) {
			$ret .= "addHoliday($dia,$mes,0,'$descripcion');\n";
		}
	}
	return $ret;
}

function cargar_calendario() {
	global $html_root;
	echo "<script language='javascript' src='$html_root/lib/popcalendar.js'></script>\n";
	echo "<script language='javascript'>".cargar_feriados()."</script>\n";
}

function mkdirs($strPath, $mode = "0700") {
//	global $server_os;
	if (SERVER_OS == "windows") {
		$strPath = ereg_replace("/","\\",$strPath);
	}
	if (is_dir($strPath)) return true;
	$pStrPath = dirname($strPath);
	if (!mkdirs($pStrPath, $mode)) return false;
	return mkdir($strPath);
}




function sql($sql, $error = -1) {
	global $db,$contador_consultas,$debug_datos;
	$msg = "";
	$result = null;
	if (count($sql) > 1 or is_array($sql)) {
		$db->StartTrans();
		foreach ($sql as $indice => $sql_str) {
			$debug_datos_temp["sql"] = $sql_str;
			if ($db->Execute($sql_str) === false) {
				$msg .= "(Consulta ".($indice + 1)."): ".$db->ErrorMsg()."<br>";
				$debug_datos_temp["error"] = $db->ErrorMsg();
                  echo $db->ErrorMsg();
				//sql_error($error,$sql_str,$db->ErrorMsg());
			}
			else {
				$debug_datos_temp["affected"] = $db->Affected_Rows();
//				$debug_datos_temp["count"] = $result->RecordCount();
			}
			$debug_datos[] = $debug_datos_temp;
			$contador_consultas++;
		}
		$db->CompleteTrans();
	}
	else {
		$result = $db->Execute($sql);
		$debug_datos_temp["sql"] = $sql;
		if (!$result) {
			$msg .= $db->ErrorMsg()."<br>";
			$debug_datos_temp["error"] = $db->ErrorMsg();
			echo $db->ErrorMsg();
			//sql_error($error,$sql,$db->ErrorMsg());
		}
		else {
			//$debug_datos_temp["affected"] = $db->Affected_Rows();
			$debug_datos_temp["count"] = $result->RecordCount();
		}
		$debug_datos[] = $debug_datos_temp;
		$contador_consultas++;
	}
	if ($msg) {
		echo "</form></center></table><br><font color=#ff0000 size=3><b>ERROR $error: No se pudo ejecutar la consulta en la base de datos.</font><br>Descripción:<br>$msg</b>";
		return false;
	}
	if ($result)
		return $result;
	else
		return true;
}

function sql_error($error,$sql_error,$db_msg) {
	global $_ses_user,$db;
	$error = addslashes($error);
	$sql_error = encode_link($sql_error);
	$db_msg = encode_link($db_msg);
	$sql = "INSERT INTO errores_sql (codigo_error, sql, msg_error, fecha, usuario) ";
	$sql .= "VALUES ('$error', '$sql_error', '$db_msg', '".date("Y-m-d H:i:s")."', ";
	$sql .= "'".$_ses_user["name"]."')";
	$result = $db->Execute($sql);
}


//toma una letra y un string como parametros y devuelve
//el numero de ocurrencias de es letra en ese string
function str_count_letra($letra,$string) {
 $largo=strlen($string);
 $counter=0;
 for($i=0;$i<$largo;$i++)
 {
  if($string[$i]==$letra)
   $counter++;
 }
 return $counter;

}





/*********************************************************************************
function insertar_string($cadena,$str, $limite)
Proposito:
          Inserta en $cadena, el string $str cada $limite caracteres.

variables utilizadas:
          - $longitud = contador para la longitud de $cadena
          - $tok = division en palabras de $cadena.
          - $palabra = variable utilizada para armar nuevamente $cadena
          - $string = cadena retornada por la funcion es $cadena con $str insertado $limite
          veces.

Logica:
         La funcion recorre $cadena separando a dicha cadena en palabras con la ayuda
         de la funcion strtok().
         Si la longitud de las palabras procesadas hasta el momento supera a $limite entonces
         se concatena al final de dicha palabra $str y se resetea el contador de longitud.
         antes de procesar la proxima palabra se concatena en $string las palabras procesadas
         hasta el momento.

NOTA: funcion implementada para utilizarse en el modulo licitaciones, en pagina
      funciones.php.
**********************************************************************************/
function insertar_string($cadena,$str, $limite){
$longitud=0;
    $tok = strtok ($cadena," ");
    while ($tok) {
        $longitud+=strlen($tok);
        $palabra=$tok;
        $tok = strtok (" ");
        if($longitud>$limite) {$palabra.=$str;$longitud=0;}
        $string.=" ".$palabra;
    }
    return $string;
}
//final de insertar_string

function variables_form_busqueda($prefijo,$extra=array()) {
	global $parametros;
	global $page,$keyword,$up,$filter,$sort,$cmd,$cmd1;
	global ${"_ses_".$prefijo};

	if ($_POST["form_busqueda"]) {
		$page = "0";
		$keyword = $_POST["keyword"];
        
	}
	else {
		if ((string)$_GET["page"] != "")
			$page = $_GET["page"] - 1;
		elseif ((string)$parametros["page"] != "")
			$page = (string)$parametros["page"];
		elseif ((string)${"_ses_".$prefijo}["page"] != "")
			$page = (string)${"_ses_".$prefijo}["page"];
		else
			$page = "0";
	}

	if (!isset($keyword)) {
        
		if ((string)$parametros["keyword"] != "")
			$keyword = (string)$parametros["keyword"];
		elseif ((string)${"_ses_".$prefijo}["keyword"] != "")
			$keyword = (string)${"_ses_".$prefijo}["keyword"];
		else
			$keyword = "";
	}
	if ((string)$_POST["up"] != "")
		$up = (string)$_POST["up"];
	elseif ((string)$parametros["up"] != "")
		$up = (string)$parametros["up"];
	elseif ((string)${"_ses_".$prefijo}["up"] != "")
		$up = (string)${"_ses_".$prefijo}["up"];
	else
		$up = "";

	if ((string)$_POST["filter"] != "")
		$filter = (string)$_POST["filter"];
	elseif ((string)$parametros["filter"] != "")
		$filter = (string)$parametros["filter"];
	elseif ((string)${"_ses_".$prefijo}["filter"] != "")
		$filter = (string)${"_ses_".$prefijo}["filter"];
	else
		$filter = "";

	if ((string)$_POST["sort"] != "")
		$sort = (string)$_POST["sort"];
	elseif ((string)$parametros["sort"] != "")
		$sort = (string)$parametros["sort"];
	elseif ((string)${"_ses_".$prefijo}["sort"] != "")
		$sort = (string)${"_ses_".$prefijo}["sort"];
	else
		$sort = "default";

	if ((string)$_POST["cmd"] != "")
		$cmd = (string)$_POST["cmd"];
	elseif ((string)$parametros["cmd"] != "")
		$cmd = (string)$parametros["cmd"];
	else
		$cmd = "";

	if ((string)$_POST["cmd1"] != "")
		$cmd1 = (string)$_POST["cmd1"];
	elseif ((string)$parametros["cmd1"] != "")
		$cmd1 = (string)$parametros["cmd1"];
	else
		$cmd1 = "";
    /*
	if ((string)$cmd != "") {
		if ((string)$cmd != (string)${"_ses_".$prefijo}["cmd"]) {
            
			$up = "";
			$page = "0";
			$filter = "";
			$keyword = "";
			$sort = "default";
			if (is_array($extra) and count($extra) > 0) {
				foreach ($extra as $key => $val) {
					global $$key;
					$$key = $val;
				}
			}
			//$flag_vaciar=1;
			$extra = array();
		}
    
	}
	else $cmd = (string)${"_ses_".$prefijo}["cmd"];
        */
		//if (!$flag_vaciar && is_array($extra) and count($extra) > 0) {
		if (is_array($extra) and count($extra) > 0) {
		foreach ($extra as $key => $val) {
			if ((string)$_POST[$key] != "")
				$extra[$key] = (string)$_POST[$key];
			elseif ((string)$parametros[$key] != "")
				$extra[$key] = (string)$parametros[$key];
			elseif ((string)${"_ses_".$prefijo}[$key] != "")
				$extra[$key] = (string)${"_ses_".$prefijo}[$key];
			global $$key;
			$$key = $extra[$key];
		}
	}

	$variables = array("cmd"=>$cmd,"cmd1"=>$cmd1,"page"=>$page,"keyword"=>$keyword,"filter"=>$filter,"sort"=>$sort,"up"=>$up);
	$variables = array_merge($variables, $extra);
    /*
	if (serialize($variables) != serialize(${"_ses_".$prefijo})) {
		phpss_svars_set("_ses_".$prefijo, $variables);
	}
    */
}






function generar_barra_nav($campos_barra) {
	global $cmd,$total_registros,$bgcolor3,$html_root;
     
	$barra = "";
	$width = floor(100/count($campos_barra));
	foreach ($campos_barra as $clave => $valor) {
		if ($valor["sql_contar"]) {
			$result = sql($valor["sql_contar"]) or die;
			$total_registros[$valor["cmd"]] = $result->fields[0];
			$valor["descripcion"] .= " (".$total_registros[$valor["cmd"]].")";
		}
		if ($cmd == $valor["cmd"]) {
			
            $menuid="ma background='../../imagenes/btn_verde.gif'";
                        $barra .= "<a href='".encode_link($_SERVER["PHP_SELF"],is_array($valor["extra"])?array_merge($valor["extra"],array("cmd" => $valor["cmd"])):array("cmd" => $valor["cmd"]))."'>";
			$barra .= "<td id=$menuid  width='$width%' style='cursor:hand' onmouseover=\"this.style.color='#000000'\" onmouseout=\"this.style.color='#000000'\" >".$valor["descripcion"]."</td>";
                        $barra.="</a>";
		}
		else {
			
            $menuid="ma"." background='../../imagenes/btn_azul.gif'";
			$barra .= "<a href='".encode_link($_SERVER["PHP_SELF"],is_array($valor["extra"])?array_merge($valor["extra"],array("cmd" => $valor["cmd"])):array("cmd" => $valor["cmd"]))."'>";
			$barra .= "<td id=$menuid  width='$width%' style='cursor:hand' onmouseover=\"this.style.color='#000000'\" onmouseout=\"this.style.color='#000000'\" >".$valor["descripcion"]."</td>";
			$barra.="</a>";
		}
	}
	echo "<table width=95% border=0 cellspacing=3 cellpadding=5  align=center>\n";    //bgcolor=$bgcolor3
	echo "<tr>$barra</tr></table>\n";
}





function getmicrotime() {
	list($useg, $seg) = explode(" ",microtime());
	return ((float)$useg + (float)$seg);
}
// Funcion que devuelve el tiempo que se demora en generarse la pagina
function tiempo_de_carga () {
	$tiempo_fin = getmicrotime();
	$tiempo = sprintf('%.4f', $tiempo_fin - TIEMPO_INICIO);
	return $tiempo;
}

 function ProcForm($FVARS,$tipo,$id_usuario){
     
   		$size=$FVARS["archivo"]["size"];
		$type=$FVARS["archivo"]["type"];
		$name=$FVARS["archivo"]["name"];
		$temp=$FVARS["archivo"]["tmp_name"];
        
        if ($tipo=="enviados")
        $path=UPLOADS_DIR."/$id_usuario/enviados";
        if ($tipo=="recibidos")
        $path=UPLOADS_DIR."/$id_usuario/recibidos";
		$ret = FileUpload($temp,$size,$name,$type,"5000000",$path,"",$extensiones,"",1);  
     
 };
 


function FileUpload($TempFile, $FileSize, $FileName, $FileType, $MaxSize, $Path, $ErrorFunction, $ExtsOk, $ForceFilename, $OverwriteOk,$comprimir=1,$mostrar_carteles=1) {
	global $ID,$_ses_user_name,$id_archivo;
	$retorno["error"] = 0;
	if (strlen($ForceFilename)) { $FileName = $ForceFilename; }
	//$err=`mkdir -p '$Path'`;
	mkdirs (enable_path($Path));

	if (!function_exists($ErrorFunction)) {
		if (!function_exists('DoFileUploadDefErrorHandle')) {
			function DoFileUploadDefErrorHandle($ErrorNumber, $ErrorText) {
				echo "<tr><td colspan=2 align=center><font color=red><b>Error $ErrorNumber: $ErrorText</b></font><br><br></td></tr>";
			}
		}
		$ErrorFunction = 'DoFileUploadDefErrorHandle';
	}
	if($TempFile == 'none' || $TempFile == '') {
		$ErrorTxt = "No se especificó el nombre del archivo<br>";
		$ErrorTxt .= "o el archivo excede el máximo de tamaño de:<br>";
		$ErrorTxt .= ($MaxSize / 1024)." Kb.";
		$retorno["error"] = 1;
		$ErrorFunction($retorno["error"], $ErrorTxt);
		return $retorno;
	}

	if(!is_uploaded_file($TempFile)) {
		$ErrorTxt = "File Upload Attack, Filename: \"$FileName\"";
		$retorno["error"] = 2;
		$ErrorFunction($retorno["error"], $ErrorTxt);
		return $retorno;
	}

	if($FileSize == 0) {
		$ErrorTxt = 'El archivo que ha intentado subir, está vacio!';
		$retorno["error"] = 3;
		$ErrorFunction($retorno["error"], $ErrorTxt);
		return $retorno;
	}


	if($FileSize > $MaxSize) {
		$ErrorTxt = 'El archivo que ha intentado subir excede el máximo de ' . ($MaxSize / 1024) . 'kb.';
		$retorno["error"] = 5;
		$ErrorFunction($retorno["error"], $ErrorTxt);
		return $retorno;
	}

	$FileNameFull = enable_path($Path."/".$FileName);
	$FileNameFullComp = substr($FileNameFull,0,strlen($FileNameFull) - strpos(strrev($FileNameFull),".") - 1).".zip";

	clearstatcache();
	if((file_exists($FileNameFull) || file_exists($FileNameFullComp)) && !strlen($OverwriteOk)) {
		$ErrorTxt = 'El archivo que ha intentado subir ya existe. Por favor especifique un nombre distinto.';
		$retorno["error"] = 6;
		$ErrorFunction($retorno["error"], $ErrorTxt);
		return $retorno;
	}

	move_uploaded_file ($TempFile, $FileNameFull) or die("error al mover el temporal <br> $TempFile <br> hasta <br> $FileNameFull");
	chmod ($FileNameFull, 0600);
   	return $retorno;
}



function GetExt($Filename) {
	$RetVal = explode ( '.', $Filename);
	return $RetVal[count($RetVal)-1];
}


/***********************************************************************
FileDownload sirve para bajar archivos, ya sea comprimidos o no

@Comp Sirve para indicar que se quiere bajar el archivo sin descomprimir
************************************************************************/
function FileDownload($FileName, $FileNameFull, $FileType, $FileSize){
     
         
		if (file_exists($FileNameFull))
		    {
				Mostrar_Header($FileName,$FileType,$FileSize);
				readfile($FileNameFull);
                exit(); 
			}
			else
			{
				Mostrar_Error("Se produjo un error al intentar abrir el archivo ");
			}
 
}



function fin_pagina($debug=true,$mostrar_tiempo=true,$mostrar_consultas=true) {
	global $_ses_user,$debug_datos;
	if ($debug and $_ses_user["debug"] == "on") {
		echo "<pre>\$debug_datos=";
		print_r($debug_datos);
		echo "</pre>";
	}
	if ($mostrar_tiempo) {
		echo "Página generada en ".tiempo_de_carga()." segundos.<br>";
	}
	if ($mostrar_consultas) {
		echo "Se utilizaron ".(count($debug_datos))." consulta/s SQL.<br>";
	}
	die("</body></html>\n");
}


//funcion para corregir el path segun el sistema operativo
function enable_path($paso){
	if (($paso != "") && ((str_count_letra('/',$paso) > 0) || (str_count_letra('\\',$paso) > 0))) {
		if (SERVER_OS == "linux") {
			$ret = str_replace("\\","/",$paso);
		} elseif (SERVER_OS == "windows") {
			$ret = str_replace("/","\\",$paso);
		}
	} else $ret = $paso;

	return $ret;
}

function Mostrar_Error($msg) {
	global $bgcolor3, $ID, $cmd;
	echo "<html><body bgcolor=$bgcolor3>";
	echo "<form action='".$_SERVER["PHP_SELF"]."' method=post>\n";
	echo "<table bgcolor=$bgcolor3 align=center width=80%>\n";
	echo "<tr><td align=center>\n";
	echo "<font size=4 color=#FF0000><b>$msg</b></font>\n";
	echo "</td></tr>\n";
	echo "</table></form>\n";
	echo "</body></html>";
}
function comprimir_variable($var) {
	$ret = "";
	if ($var != "") {
		$var = serialize($var);
		if ($var != "") {
			$gz = @gzcompress($var);
			if ($gz != "") {
				$ret = base64_encode($gz);
			}
		}
	}
	return $ret;
return base64_encode(gzcompress(serialize($var)));
}
function descomprimir_variable($var) {
	$ret = "";
	if ($var != "") {
		$var = base64_decode($var);
		if ($var != "") {
			$gz = @gzuncompress($var);
			if ($gz != "") {
				$ret = unserialize($gz);
			}
		}
	}
	return $ret;
}

function errorHandler($errno, $errstr, $errfile, $errline, $errcontext) {
	global $_ses_user,$_ultimo_error,$html_root;
	$mostrar = 0;
	switch ($errno) {
		case E_USER_WARNING:
			$tipo_error = "USER_WARNING";
			$mostrar = 0;
			break;
		case E_USER_NOTICE:
			$tipo_error = "USER_NOTICE";
			$mostrar = 1;
			break;
		case E_WARNING:
			$tipo_error = "WARNING";
			$mostrar = 2;
			break;
		case E_NOTICE:
			$tipo_error = "NOTICE";
			$mostrar = 0;
			break;
		case E_CORE_WARNING:
			$tipo_error = "CORE_WARNING";
			$mostrar = 2;
			break;
		case E_COMPILE_WARNING:
			$tipo_error = "COMPILE_WARNING";
			$mostrar = 2;
			break;
		case E_USER_ERROR:
			$tipo_error = "USER_ERROR";
			$mostrar = 0;
			break;
		case E_ERROR:
			$tipo_error = "ERROR";
			$mostrar = 2;
			break;
		case E_PARSE:
			$tipo_error = "PARSE";
			$mostrar = 2;
			break;
		case E_CORE_ERROR:
			$tipo_error = "CORE_ERROR";
			$mostrar = 2;
			break;
		case E_COMPILE_ERROR:
			$tipo_error = "COMPILE_ERROR";
			$mostrar = 2;
			break;
		case 2048:
			$mostrar = 0;
	}
	if ($mostrar == 2) {
		$_ultimo_error[] = $errstr;
	}
	$msg_error = "<table width='50%' height='100%' border=0 align=center cellpadding=0 cellspacing=0>";
	$msg_error .= "<tr><td height='50%'>&nbsp;</td></tr>";
	$msg_error .= "<tr><td align=center>";
	$msg_error .= "<table border=2 width='100%' bordercolor='#FF0000' bgcolor='#FFFFFF' cellpadding=0 cellspacing=0>";
	if ($mostrar == 1) {
		if  ($_SERVER["HTTP_HOST"]=="gestion.coradir.com.ar" || $_SERVER["HTTP_HOST"]=="gestion.local") {
			$msg_error .= "<tr><td width=15% align=center valign=middle style='border-right:0'>";
			$msg_error .= "<img src=$html_root/imagenes/error.gif alt='ERROR' border=0>";
			$msg_error .= "</td><td width=85% align=center valign=middle style='border-left:0'>";
			$msg_error .= "<font size=2 color=#000000 face='Verdana, Arial, Helvetica, sans-serif'><b>";
			$msg_error .= "SE HA PRODUCIDO UN ERROR EN EL SISTEMA<br>";
			$msg_error .= "El error fue notificado a los programadores y sera solucionado a la brevedad<br>";
			$msg_error .= "</b></font>";
			$msg_error .= "</td></tr>";
		}
		else {
			$msg_error .= "TIPO:$tipo_error<br>";
			$a = explode("\t\n\t",$errstr);
			if (substr($a[0],0,2) == "a:") {
				$a[0] = unserialize($a[0]);
			}
				echo "DESCRIPCION:<pre>";
				if (is_array($a[0])) {
					print_r($a[0]);
				}
				else {
					echo $a[0];
				}
				echo "</pre><br>";
				echo "ARCHIVO:".$a[1]."<br>";
				echo "LINEA:".$a[2]."<br>";
			if (count($_ultimo_error) > 0) {
				echo "ERRORES:<pre>";
				print_r($_ultimo_error);
				echo "</pre>";
				$_ultimo_error = array();
			}
			echo "USUARIO:".$_ses_user["name"]."<br>";
		}
		$msg_error .= "</table></td></tr>";
		$msg_error .= "<tr><td height='50%' align='center'>";
		/*$link_volver = "";
		if ($_SERVER["REQUEST_URI"] != "") {
			$link_volver .= $_SERVER["REQUEST_URI"];
		}
		elseif ($_SERVER["HTTP_REFERER"] != "") {
			$link_volver .= $_SERVER["HTTP_REFERER"];
		}
		if ($link_volver == "") {
			$msg_error .= "&nbsp;";
		}
		else {*/
			//$msg_error .= "<input type=button value='Volver' onClick=\"document.location='$link_volver';\" style='width:100px;height:30px;'>";
			$msg_error .= "<input type=button value='Volver' onClick=\"history.back();\" style='width:100px;height:30px;'>";
		//}
		$msg_error .= "</td></tr>";
		$msg_error .= "</table>\n";
		echo $msg_error;
		//phpinfo();
	}
}

function reportar_error($descripcion,$archivo,$linea) {
	if (is_array($descripcion)) {
		$descripcion = serialize($descripcion);
	}
	trigger_error($descripcion."\t\n\t".$archivo."\t\n\t".$linea);
	//fin_pagina();
	exit();
}




function Mostrar_Header($FileName,$FileType,$FileSize) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: must-revalidate");
    
    header("Content-Type: application/octet-stream");
    header("Content-Transfer-Encoding: binary");
    header("Content-Disposition: attachment; filename=\"$FileName\"");
    header("Content-Description: $FileName");
    header("Content-Length: $FileSize");
    header("Content-Connection: close");
   }
   
function firmar_digitalmente($texto,$id_usuario,$id_mensaje,$tipo ,$archivo=""){

   //si no existen los directorios lo creo
   if ($tipo=="enviados")
       $path=UPLOADS_DIR."/$id_usuario/enviados";

   if ($tipo=="recibidos")
        $path=UPLOADS_DIR."/$id_usuario/recibidos";

   mkdirs($path);
   $claves=KEYS_DIR."/$id_usuario/$id_usuario"."_"."claves".".pub";
   if (SERVER_OS == "windows")
            {
            $path=ereg_replace("/","\\",$path) ;
            $claves=ereg_replace("/","\\",$claves) ;
            }

   //creo el archvio correspondiente
   $name="$id_mensaje.txt";
   $ruta_completa=$path."/".$name;
   $fp = fopen($ruta_completa,"w+");
   //echo $ruta_completa;
   fwrite($fp,$texto);

   //$obtendo la ruta con las claves
   // vVERIFICAR PARA LINUX!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!1 LAS RUTAS

   //echo " datos de importancia $ruta_completa  $claves <br>";
   //cifro el archivo
   
   if ($archivo<>"")
            {
            $copiar=copiar($path."/".$archivo,CODIGO_JAVA);
            $copiar=copiar($ruta_completa,CODIGO_JAVA);
            //coprimo el archivo
            $comprimir="gij -jar ../../codigo_java/jcripto.jar -z ".CODIGO_JAVA."/$name"." ".CODIGO_JAVA."/".$archivo;
            $return.=shell_exec($comprimir);            


            $cifrar="gij -jar ../../codigo_java/jcripto.jar -c ".CODIGO_JAVA."/$name.zip"."  $claves ";
            //echo "<br> cifrar $cifrar <br>";
            $return.=shell_exec($cifrar);
                      
            //genero el hash
            $hash="gij -jar ../../codigo_java/jcripto.jar -f ".CODIGO_JAVA."/$name.zip";
            $return.=shell_exec($hash);
   
            //coprimo el archivo
            $comprimir="gij -jar ../../codigo_java/jcripto.jar -z ".CODIGO_JAVA."/$name.zip.hash ".CODIGO_JAVA."/$name.zip.cif";
            $return.=shell_exec($comprimir);
               
            //copio el archivo en la carpeta correspondiente
            $return.=copiar(CODIGO_JAVA."/$name*.zip",$path);
            
            $eliminar=CODIGO_JAVA."/$id_mensaje*.*";
           //echo "<br>$eliminar<br>";
            $return.=eliminar($eliminar);            
            }
             
            else {
   
            $copiar=copiar($ruta_completa,CODIGO_JAVA);
            $cifrar="gij -jar ../../codigo_java/jcripto.jar -c ".CODIGO_JAVA."/$name  $claves ";
            //echo "<br> cifrar $cifrar <br>";
            $return.=shell_exec($cifrar);
                      
            //genero el hash
            $hash="gij -jar ../../codigo_java/jcripto.jar -f ".CODIGO_JAVA."/$name ";
            $return.=shell_exec($hash);
   
            //coprimo el archivo
            $comprimir="gij -jar ../../codigo_java/jcripto.jar -z ".CODIGO_JAVA."/$name.hash ".CODIGO_JAVA."/$name.cif";
            $return.=shell_exec($comprimir);
               
            //copio el archivo en la carpeta correspondiente
            $return.=copiar(CODIGO_JAVA."/$name*.zip",$path);

            //elimino los archvios
            $eliminar=CODIGO_JAVA."/$id_mensaje*.*";
           //echo "<br>$eliminar<br>";
            $return.=eliminar($eliminar);
            }
            
   fclose($fp);  
  
   return  $return;
   }// de la funcion de firmado




//funcion que me genera las claves prublicas y privadas
function generar_claves($id_usuario){

   $path=KEYS_DIR."/$id_usuario/";
     
   mkdirs($path);
   if (SERVER_OS == "windows")
            $path=ereg_replace("/","\\",$path) ;

   //genero las claves
   if (SERVER_OS == "windows") {    
           $return=shell_exec("java -jar ..\\..\\codigo_java\\jcripto.jar -g 10 ".$id_usuario."_claves");
           $return=copiar("$id_usuario"."_claves*",$path);
           }
           else
           {   
           shell_exec("gij -jar ../../codigo_java/jcripto.jar -g 10 ".$id_usuario."_claves");
            copiar("$id_usuario"."_claves*","../../claves/$id_usuario");
           }
    return $return;

}

function verificar_firma($id_mensaje,$id_usuario,$tipo="recibidos",$adjunto=0){

   if ($tipo=="enviados"){
          $archivos_a_copiar=UPLOADS_DIR."/$id_usuario/enviados/$id_mensaje"."*".".zip";
          $path_destino=CODIGO_JAVA;
   }

   if ($tipo=="recibidos"){
       $archivos_a_copiar=UPLOADS_DIR."/$id_usuario/recibidos/$id_mensaje"."*".".zip";
       $path_destino=CODIGO_JAVA;
   }

   
    if (SERVER_OS == "linux") {
   
            $claves=KEYS_DIR."/$id_usuario/$id_usuario"."_"."claves".".pri";
            $return=copiar($archivos_a_copiar,$path_destino);

              if (!$adjunto){
                  //descomprimo el archivo
                  $descomprimir="gij -jar ../../codigo_java/jcripto.jar -u ".CODIGO_JAVA."/$id_mensaje".".txt.hash.zip";
                  shell_exec($descomprimir);

                   //descrifo el archivo
                   $descifrar="gij  -jar ../../codigo_java/jcripto.jar -d ".CODIGO_JAVA."/$id_mensaje".".txt.cif  $claves";
                 
                  shell_exec($descifrar);

                   //verifico la firma
                   $verifico="gij -jar ../../codigo_java/jcripto.jar -v ".CODIGO_JAVA."/$id_mensaje".".txt.hash ".CODIGO_JAVA."/$id_mensaje".".txt ";
                   $return=shell_exec ($verifico);
              }
              else{
                  //descomprimo el archivo
                  $descomprimir="gij -jar ../../codigo_java/jcripto.jar -u ".CODIGO_JAVA."/$id_mensaje".".txt.zip.hash.zip";
                  shell_exec($descomprimir);

                   //descrifo el archivo
                   $descifrar="gij  -jar ../../codigo_java/jcripto.jar -d ".CODIGO_JAVA."/$id_mensaje".".txt.zip.cif  $claves";
                 
                  shell_exec($descifrar);

                   //verifico la firma
                   $verifico="gij -jar ../../codigo_java/jcripto.jar -v ".CODIGO_JAVA."/$id_mensaje".".txt.zip.hash ".CODIGO_JAVA."/$id_mensaje".".txt.zip";
                   $return=shell_exec ($verifico);                  
                  
              }
               
            
			} 
            elseif (SERVER_OS == "windows"){
               $claves=KEYS_DIR."/$id_usuario/$id_usuario"."_"."claves".".pri";
               //copio el archivo para trabajar
               $return.=copiar($archivos_a_copiar,$path_destino);


               //descomprimo el archivo
               $descomprimir="java -jar ..\\..\\codigo_java\\jcripto.jar -u ".CODIGO_JAVA."\\$id_mensaje".".txt.hash.zip";
               $return.=shell_exec($descomprimir);

               //descrifo el archivo
               $descifrar="java -jar ..\\..\\codigo_java\\jcripto.jar -d ".CODIGO_JAVA."\\$id_mensaje".".txt.cif  $claves";
               $return.=shell_exec($descifrar);

               //verifico la firma
               $verifico="java -jar ..\\..\\codigo_java\\jcripto.jar -v ".CODIGO_JAVA."\\$id_mensaje".".txt.hash ".CODIGO_JAVA."\\$id_mensaje".".txt ";
               $return.=shell_exec($verifico);
               
            }
   
   
   return $return;

}//de la funcion verificar_firma

function copiar($archivo,$destino){

 if (SERVER_OS == "linux") {
 		    $copiar = "cp -f $archivo  $destino ";
            $return=exec ($copiar); 
			} 
            elseif (SERVER_OS == "windows"){
                $destino=ereg_replace("/","\\",$destino) ;
                $archivo=ereg_replace("/","\\",$archivo) ;
                $copiar = "copy  $archivo $destino /Y";
            $return=shell_exec($copiar);
            }
return $return;            
}
function eliminar($archivo){
if (SERVER_OS == "linux") {
               $sentencia="rm  $archivo";
               //$return=$sentencia;
               $return=exec($sentencia);
               
			} elseif (SERVER_OS == "windows"){
                $archivo=ereg_replace("/","\\",$archivo) ;
                $sentencia=" del $archivo /F";
                $return=shell_exec($sentencia);     
            }
 return $return ;
}//de la funcion eliminar






// Para usar con los resultados boolean de la base de datos
$sino=array(
	"0" => "No",
	"f" => "No",
	"false" => "No",
	"NO" => "No",
	"n" => "No",
	"t" => "Sí",
	"true" => "Sí",
	"SI" => "Sí",
	"s" => "Sí"
);
// Para el formato de fecha
$dia_semana = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
$meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

// El tipo de resultado debe ser n para que funcione la
// libreria phpss
db_tipo_res("d");


if (ereg("Win32",$_SERVER["SERVER_SOFTWARE"]) ||
    ereg("Microsoft",$_SERVER["SERVER_SOFTWARE"]))
	define("SERVER_OS", "windows");
else
	define("SERVER_OS", "linux");

$GLOBALS["parametros"] = decode_link($_GET["p"]);



define("lib_included","1");
?>