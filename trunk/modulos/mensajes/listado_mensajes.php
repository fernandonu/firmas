<?
/*
$Author: fernando $
$Revision: 1.0$
$Date: 2005/5/13 $
*/
require_once ("../../config.php");
$msg=$msg=$parametros["msg"];
if ($_POST["borrar"])
    {
     $mensajes=$_POST["accion"];
     $db->starttrans();
     for($i=0;$i<sizeof($mensajes);$i++){
         $id_mensaje=$mensajes[$i];
         $sql="update mensajes set borrado=1 where id_mensaje=$id_mensaje";
         sql($sql) or fin_pagina();
     }
    $msg="No se eliminaron los mensajes"; 
    if ($db->completetrans()) $msg="Se eliminaron los mensajes exitosamente";
    }
if ($_POST["restaurar"])
    {
     $mensajes=$_POST["accion"];
     $db->starttrans();
     for($i=0;$i<sizeof($mensajes);$i++){
         $id_mensaje=$mensajes[$i];
         $sql="update mensajes set borrado=0 where id_mensaje=$id_mensaje";
         sql($sql) or fin_pagina();
     }
    $msg="No se restauraron los mensajes"; 
    if ($db->completetrans()) $msg="Se restauraron los mensajes exitosamente";
    }
    
if ($_POST["no_leido"])
    {
     $mensajes=$_POST["accion"];
     $db->starttrans();
     for($i=0;$i<sizeof($mensajes);$i++){
         $id_mensaje=$mensajes[$i];
         $sql="update mensajes set leido=0 where id_mensaje=$id_mensaje";
         sql($sql) or fin_pagina();
     }
    $msg="No se marcaron como no leídos  los mensajes"; 
    if ($db->completetrans()) $msg="Se marcaron como no leídos los mensajes exitosamente";
    }    

if ($_POST["verificar"]){
    $db->StartTrans();

     $mensajes=$_POST["accion"];
     $db->starttrans();
     for($i=0;$i<sizeof($mensajes);$i++){
         $id_mensaje=$mensajes[$i];
         $sql="select tiene_adjunto from mensajes where id_mensaje=$id_mensaje";
         $res=sql($sql) or fin_pagina();
         $adjunto=$res->fields["tiene_adjunto"];
         $id_usuario=$_SESSION["user"]["id"];
         
         $return=verificar_firma($id_mensaje,$id_usuario,$tipo="recibidos",$adjunto);
               
         if (substr_count($return,"Firmas coincidentes")) $msg="Las Firmas coinciden!!";
                                                        else $msg="Las Firmas NO coinciden!!";
     }    
    $db->CompleteTrans();
}


echo $html_header;


if ($msg) Aviso($msg);
variables_form_busqueda("listado_mensajes");
if (!$cmd)  $cmd="bandeja_entrada";


$datos_barra=array(
                   array(
                        "descripcion"	=> "Bandeja Entrada",
						"cmd"			=> "bandeja_entrada",
                        ),
                   array(
                        "descripcion" => "Papelera",
                        "cmd" =>"papelera"
                   )
                   );


$orden=array(
	"default" => "3",
	"default_up" => "0",
    "1"=>"emisor",
    "2"=>"asunto",
    "3"=>"fecha_emision",
    );

$filtro=array(
        "emisor"=>"Emisor",
        "asunto"=>" Asunto",
        "contenido"=>"Contenido",
        );

$sql_temp=" select * from mensajes
             join
              ( select id_cliente,apellido_nombre as emisor from clientes
              ) as emisores on mensajes.id_emisor=emisores.id_cliente
               ";

if ($cmd=="bandeja_entrada") $where_temp=" borrado=0";
if ($cmd=="papelera") $where_temp=" borrado=1";

if ($_SESSION["user"]["id"])
                           $where_temp.= " and id_receptor=".$_SESSION["user"]["id"];
                           else
                           $where_temp.= " and id_receptor=-1";;
?>
<form name=form1 method=post>
<?
generar_barra_nav($datos_barra);

$sql="select count(id_mensaje) as cantidad from mensajes where leido=0 and  borrado=0 and id_receptor=".$_SESSION["user"]["id"];
$res=sql($sql) or fin_pagina();
$cantidad_mensajes_sin_leer=$res->fields["cantidad"];
?>
   <table width=100% align=center >
     <tr>
        <td align=center>
          <?
          list($sql,$total_reg,$link_pagina,$up) = form_busqueda($sql_temp,$orden,$filtro,$link_temp,$where_temp,"buscar");
          
          $resultado = sql($sql,"Error al ejecutar la consulta: $sql") or fin_pagina();
         ?>
         &nbsp
         <input type=submit name=form_busqueda value='Buscar'>
        </td>
     </tr>
     <? if ($cantidad_mensajes_sin_leer) {?>
     <tr>
      <td class=presentacion>Tiene <?=$cantidad_mensajes_sin_leer?> Mensaje/s Sin Leer</td>
     </tr>
     <?}?>
     <tr>
       <td>
       <table width="100%" align=center>
         <tr>
           <td width=1% id=mo>&nbsp;</td>
           <td width=20% id=mo><a  href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"1","up"=>$up))?>'>De</a></td>
           <td width=25% id=mo><a  href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"2","up"=>$up))?>'>Asunto</a></td>
           <td id=mo width=10%><a href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"3","up"=>$up))?>'>Fecha</a></td>
           <td width=1% id=mo>&nbsp;</td>
         </tr>
         <?
         for($i=0;$i<$resultado->recordcount();$i++){
           $id_mensaje=$resultado->fields["id_mensaje"];
           $link=encode_link("mensajes.php",array("id_mensaje"=>$id_mensaje));
           $firmado=$resultado->fields["firmado"];
           if ($firmado) $logo_firmado="<img src='../../imagenes/key.png' title='Firmado Digitalmente'>";
                   else  $logo_firmado="";
           $onclick="onclick=\"document.location='$link'\"";
           $hora=substr($resultado->fields["fecha_emision"],10,9);
           
         ?>
           <tr <?=atrib_tr()?>>
           <td id=celda><input type=radio class="estilos_check" name=accion[] value=<?=$id_mensaje?>></td>
           <td id=celda <?=$onclick?>><?=$resultado->fields["emisor"]?></td>
           <td id=celda <?=$onclick?>>
            <table width=100% align=center>
            <tr >
                <td id=celda width=90% align=left>
                <?=$resultado->fields["asunto"]?>
                </td>
                <td width=5%>
                <?
                 if ($resultado->fields["tiene_adjunto"]) {
                ?>
                  <img src="../../imagenes/adjunto.gif" title="Archivo Adjunto">
                <?
                }else echo "&nbsp;";
                ?>
                <td whidth=5%>
                <?
                if ($resultado->fields["leido"]) {
                ?>
                  <img src="../../imagenes/leido.gif" title="Mensaje  Leido">
                <?} else {?>  
                  <img src="../../imagenes/no_leido.gif" title="Mensaje No Leido";>                
                <?}?>
                </td>
                </td>
                
            </tr>
            </table>
           </td>
           <td id=celda<?=$onclick?> align=center><?=fecha($resultado->fields["fecha_emision"])." $hora"?></td>
           <td id=celda><?=$logo_firmado?>&nbsp;</td>
         </tr>
         <?
         $resultado->movenext();
         }
         ?>
       </table>
       </td>
     </tr>
     <tr>
       <td align=center>
         <?if ($cmd=="bandeja_entrada"){?>
         <input type=submit name=borrar value=Borrar>
         <?}?>
         <?if ($cmd=="papelera"){?>
         <input type=submit name=restaurar value=Restaurar>
         <?}?>         
         &nbsp;
         <input type=submit name=verificar value=Verificar>
         &nbsp;
         <input type=submit name=no_leido value="Marcar Como No Leído">
         
       </td>
     </tr>
   </table>
</form>