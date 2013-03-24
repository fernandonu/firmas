<?
/*
$Author: fernando $
$Revision: 1.0$
$Date: 2005/5/13 $

*/

require_once ("../../config.php");

 $msg=$parametros["msg"];

if ($_POST["pasar_activo"]){

     $id_cliente=$_POST["id_cliente"];

     for($i=0;$i<count($id_cliente);$i++){
           $sql="update clientes set activo=1 where id_cliente=".$id_cliente[$i]; 
           sql($sql) or fin_pagina();
     }
 
}


if ($_POST["pasar_inactivo"]){

     $id_cliente=$_POST["id_cliente"];

     for($i=0;$i<count($id_cliente);$i++){
           $sql="update clientes set activo=0 where id_cliente=".$id_cliente[$i]; 
           sql($sql) or fin_pagina();
     }
 
}

echo $html_header;

if ($msg) Aviso($msg);
variables_form_busqueda("listado_varios");

$orden=array(
	"default" => "1",
	"default_up" => "1",
    "1"=>"id_cliente",
    "2"=>"apellido_nombre",
    "3"=>"login",
    "4"=>"direccion",
    "5"=>"distrito");

$filtro=array(
        "id_cliente"=>"Id Cliente",
        "apellido_nombre"=>" Apellido Y Nombre",
        "login"=>"Login",
        "nombre"=>"Distrito",
        );
if (!$cmd) $cmd="activos"; 


if ($cmd=="activos" || !$cmd) $activo=1;
if ($cmd=="no_activos") $activo=0;

$sql_temp=" select * from clientes
            join distrito using (id_distrito)";
            
$where_temp=" clientes.activo=$activo";
            

            
$datos_barra=array(
                   array(
                        "descripcion"	=> "Activos",
						"cmd"			=> "activos",
                        ),

                   array(
                        "descripcion" => "No Activos",
                        "cmd" =>"no_activos"
                   )
                   );            
?>
<form name=form1 method=post>
   <table width=100% align=center >
     <tr>
        <td>
           <?
           generar_barra_nav($datos_barra);
           ?>
        </td>
     </tr>
     <tr>
        <td align=center>
          <?
          list($sql,$total_reg,$link_pagina,$up) = form_busqueda($sql_temp,$orden,$filtro,$link_temp,$where_temp,"buscar");
          //echo $sql;
          //print_r($filtro);
          $resultado = sql($sql,"Error al ejecutar la consulta: $sql") or fin_pagina();
         ?>
         &nbsp
         <input type=submit name=form_busqueda value='Buscar'>
        </td>
     </tr>
     <tr>
       <td>
       <table width="100%" align=center>
         <tr>
           <td id=mo width=1%>&nbsp;</td>
           <td id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"1","up"=>$up))?>'>Id</a></td>
           <td id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"2","up"=>$up))?>'>Apellido</a></td>
           <td id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"3","up"=>$up))?>'>Login</a></td>
           <td id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"4","up"=>$up))?>'>Dirección</a></td>
           <td id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"5","up"=>$up))?>'>Distrito</a></td>
         </tr>
         <?
         for($i=0;$i<$resultado->recordcount();$i++){
           $id_cliente=$resultado->fields["id_cliente"];


          $link=encode_link("clientes.php",array("id_cliente"=>$id_cliente,"modificar"=>1));
          $onclick="onclick=\"document.location='$link'\""; 
          
         ?>
           <tr <?=atrib_tr()?>>
           <td align=center><input type=checkbox name=id_cliente[] class="estilos_check" value="<?=$id_cliente?>"></td>
           <td <?=$onclick?>><?=$id_cliente?></td>
           <td <?=$onclick?>><?=$resultado->fields["apellido_nombre"]?></td>
           <td <?=$onclick?>><?=$resultado->fields["login"]?></td>
           <td <?=$onclick?>><?=$resultado->fields["direccion"]?></td>
           <td <?=$onclick?>><?=$resultado->fields["nombre"]?></td>
         </tr>
         <?
         $resultado->movenext();
         }
         ?>
       </table>
       </td>
     </tr>
     <input type=hidden name=cantidad value="<?=$resultado->recordcount()?>">
     <tr>
       <td align=center>
       <?if ($cmd=="activos"){?>
       <input type=submit name=pasar_inactivo value="Pasar a Inactivo">
       <?
       }
       if ($cmd=="no_activos") {
       ?>
       <input type=submit name=pasar_activo value="Pasar a Activo">
       <?}?>
       </td>
     </tr>
   </table>
</form>