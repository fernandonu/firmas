<?
/*
$Author: fernando $
$Revision: 1.0$
$Date: 2005/5/13 $

*/

require_once ("../../config.php");

$id_mensaje=$parametros["id_mensaje"] or $id_mensaje=$_POST["id_mensaje"];
$extensiones = array("doc","obd","xls","zip"); 
//print_r($parametros);


if ($_POST["volver"]) {
    $link=encode_link("listado_mensajes.php",array("msg"=>""));
    header("location: $link");   
}

if ($parametros["download"]){
    
     $sql="select * from mensajes where id_mensaje=$id_mensaje";
     $res=sql($sql,"Error al traer los datos del archivo: $sql") or fin_pagina();
     $archivo=$res->fields["archivo"];
     $size=$res->fields["size"];
     $type=$res->fields["tipo"];
     $id_usuario=$res->fields["id_receptor"];
     $pathfull=UPLOADS_DIR."/$id_usuario/recibidos/$archivo";
     
     FileDownload($archivo,$pathfull,$type,$size);

   // $link=encode_link("listado_mensajes.php",array("msg"=>""));
   // header("location: $link"); 
}

if ($_POST["responder"]){
     //ahora el destinatario es el que remitio antes
     $id_receptor=$_POST["id_emisor"];
     $contenido=$_POST["mensaje"];
     $id_mensaje="";
     $responder=1;
     $asunto="Fwd: ".$_POST["asunto"];
}


if ($_POST["borrar"]){
    $sql="update mensajes set borrado=1 where id_mensaje=$id_mensaje";
     sql($sql) or fin_pagina();
     $msg="Se eliminaron los mensajes exitosamente";
    $link=encode_link("listado_mensajes.php",array("msg"=>$msg));
    header("location: $link");     
}
if ($_POST["aceptar"]){
    //mando el mensaje al destinatario
   
   
   
    $db->starttrans();
    $id_emisor=$_SESSION["user"]["id"];
    $id_receptor=$_POST["destinatario"];
    $mensaje=$_POST["mensaje"];
    $asunto=$_POST["asunto"];
    $fecha_emision=date("Y-m-d H:i:s");
    $sql="select nextval('mensajes_id_mensaje_seq') as id_mensaje";
    $res=sql($sql) or fin_pagina();
    $id_mensaje=$res->fields["id_mensaje"];
    $firmado=0;

    //si adjunta un archivo lo subo
    if ($_FILES["archivo"]["name"]) {
                 ProcForm($_FILES,"recibidos",$id_receptor);
                 //ProcForm($_FILES,"recibidos",$id_receptor);
                 $tiene_adjunto=1;
                 }
                   
    if ($_POST["firmado"]){
                          //guarda a quien le envio
                           $firmado=1;
                           $return=firmar_digitalmente($mensaje,$id_receptor,$id_mensaje,"recibidos",$_FILES["archivo"]["name"]);
                           //guardo una copia para mi
                           $exito=0;
                           if ( substr_count($return,"Firmado terminado")) $exito++;
                           $return=firmar_digitalmente($mensaje,$id_emisor,$id_mensaje,"enviados",$_FILES["archivo"]["name"]);
                           if ( substr_count($return,"Firmado terminado")) $exito++;
                           
                           if ($exito==2) $msg="Se realizo con éxito el firmado";
                           }


    if ($tiene_adjunto){
                        $archivo=$_FILES["archivo"]["name"];
                        $tipo=$_FILES["archivo"]["type"];
                        $size=$_FILES["archivo"]["size"];
                        
                        $columna_adj=",archivo,tipo,size,tiene_adjunto";
                        $valores_adj=",'$archivo','$tipo',$size,1";
                        }
    $sql="insert into mensajes (id_mensaje,leido,id_emisor,id_receptor,asunto,contenido,fecha_emision,firmado $columna_adj)
         values ($id_mensaje,0,$id_emisor,$id_receptor,'$asunto','$mensaje','$fecha_emision',$firmado $valores_adj)";
    sql($sql) or fin_pagina($sql);
    
    if ($db->completetrans()) $msg.="<br>Se envio el mensaje exitosamente";
    
   
    $link=encode_link("listado_mensajes.php",array("msg"=>$msg));
    header("location: $link");                     
    die($sql); 
}

if ($id_mensaje){

     $sql="select * from mensajes where id_mensaje=$id_mensaje";
     $mensaje=sql($sql) or fin_pagina();

     $id_receptor=$mensaje->fields["id_receptor"];
     $asunto=$mensaje->fields["asunto"];
     $contenido=$mensaje->fields["contenido"];
     $firmado=$mensaje->fields["firmado"];
     $archivo=$mensaje->fields["archivo"];
     
     //marco el mensaje como leido
     
     $sql="update mensajes set leido=1 where id_mensaje=$id_mensaje";
     sql($sql) or fin_pagina();

}
echo $html_header;
?>
<script>
  function control_datos(){
 

  if (document.form1.destinatario.options[document.form1.destinatario.selectedIndex].text=="")
    {
    alert("Error: Falta el Destinatario");
    return false;
    }

  return true;
  }
</script>
<form name=form1 method=POST enctype='multipart/form-data'>
<input type=hidden name=id_mensaje value=<?=$id_mensaje?>>
   <table width=95% align=center class=bordes>
     <tr>
       <td id=mo>Mensajes</td>
     </tr>
     <tr>
        <td>
          <table width="100%" align=center>
            <?
            if ($id_mensaje){
            ?>
              <td align=left width=10%><b>Remitente:</b></td>
              <?
              $sql="select * from clientes where id_cliente=".$mensaje->fields["id_emisor"];
              
              $res=sql($sql) or fin_pagina();
              $id_emisor=$res->fields["id_cliente"];
              //echo "cantidad".$res->recordcount();
              ?>
              <input type=hidden name=id_emisor value="<?=$id_emisor?>">
              <td><b><input type=text name=remitente value="<?=$res->fields["apellido_nombre"]?>" readonly size=30></b></td>
            </tr>
            <?
            }
            ?>
           
            <tr>
              <td align=left width=10%><b>Destinatario:</b></td>
             <?
             if (!$id_mensaje) {
             ?>
               
              <td>
              <?
              $sql="select * from clientes where activo=1";
              $res=sql($sql) or fin_pagina();
              //echo "cantidad".$res->recordcount();

              ?>
              <select name=destinatario>
                <option value=-1></option>
                <?
                
                for($i=0;$i<$res->recordcount();$i++){
                  $id_cliente=$res->fields["id_cliente"];
                  $nombre_cliente=$res->fields["apellido_nombre"];
                  if ($id_cliente==$id_receptor) $selected="selected";
                                                 else $selected="";
                ?>
                  <option value=<?=$id_cliente?> <?=$selected?>><?=$nombre_cliente?></option>
                <?
                $res->movenext();
                }
                ?>
              </select>
              </td>
              <?} 
              else {
              $sql="select * from clientes where id_cliente=$id_receptor and activo=1";
              $res=sql($sql) or fin_pagina();                  
              $nombre_cliente=$res->fields["apellido_nombre"];
              ?>
              <td> <input type=text name=destinatario value="<?=$nombre_cliente?>" readonly size=30></b> </td>
              <?}?>
              
              <input type=hidden name=id_receptor value="<?=$id_receptor?>">
            </tr>
            <tr>
              <td align=left><b>Asunto:</b></td>
              <td><input type=text name=asunto value="<?=$asunto?>" size=40></td>
            </tr>
            <?
            if (!$id_mensaje){
            ?>
            <tr>
              <td align=left><b>Adjuntar:</b></td>
              <td><input type=file name='archivo' size=40 onkeypress='return false'></td>
            </tr>
            <?}else{?>
            <tr>
              <td align=left><b>Adjunto:</b></td>
              <td><a href="<?=encode_link($_SERVER["PHP_SELF"],array("download"=>1,"id_mensaje"=>$id_mensaje))?>"><?=$archivo?></a></td>
            </tr>            
            <?}?>
            <tr>
             <td align=left valign=top><b>Mensaje:</b></td>
             <td>
             <textarea name=mensaje rows=10 style="width:100%"><?=$contenido?></textarea>
             </td>
            </tr>
            <tr>
            <td>&nbsp;</td>
            <td align=left>
            <?
            ($firmado)?$checked="checked":$checked="";
            ?>
             <input type=checkbox name=firmado value=1 <?=$checked?>>&nbsp;<b>Firmado</b>
            </td>
           </tr>

          </table>
        </td>
     </tr>
     <tr id=ma>
        <td align=center>
        <?if (!$id_mensaje){?>
          <input type=submit name=aceptar value=Aceptar onclick="return control_datos()">
          &nbsp;
          <input type=reset name=borrar value=Limpiar>
          &nbsp;
          <input type=submit name=volver value="Volver Listado" onclick="document.location='listado_mensajes.php'">
          
          <?
          }else {
          ?>
          <input type=submit name=responder value=Responder>
          &nbsp;
          <input type=submit name=borrar value=Borrar>
          &nbsp;
          <input type=submit name=volver value="Volver Listado" >
          <?
          }
          ?>
        </td>
     </tr>
     
   </table>
</form>