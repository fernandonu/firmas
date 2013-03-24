<?
require_once("../../config.php");
$id_cliente=$parametros["id_cliente"]  or $id_cliente=$_POST["id_cliente"];
$modificar=$parametros["modificar"]   or $modificar=$_POST["modificar"];
if ($_POST["aceptar"])
    {
    $apellido_nombre=$_POST["nombre_apellido"];
    $direccion=$_POST["direccion"];
    $localidad=$_POST["localidad"];
    $mail=$_POST["mail"];
    $telefono=$_POST["telefono"];
    $id_distrito=$_POST["distrito"];
    $codigo_postal=$_POST["codigo_postal"];
    $login=$_POST["login"];
    $password=$_POST["password_1"];

     if ($id_cliente){
         $sql="update clientes set apellido_nombre='$apellido_nombre',direccion='$direccion',
                                   localidad='$localidad',mail='$mail',telefono='$telefono',
                                   id_distrito='$id_distrito',codigo_postal='$codigo_postal'
                                   where id_cliente=$id_cliente
                                   ";
          if (sql($sql) or fin_pagina()) $msg="Se modificaron con éxito los datos del cliente";
                                  else
                                  $msg="Error: No se pudo   modificar los datos del cliente";

     }
                         else{
                             $sql="select nextval('clientes_id_cliente_seq') as id_cliente";
                             $res=sql($sql) or fin_pagina();
                             $id_cliente=$res->fields["id_cliente"];

                             $sql=" select count(id_cliente) as cantidad from clientes where login='$login'";
                             $res=sql($sql) or fin_pagina();
                             $cantidad=$res->fields["cantidad"];
                             if ($cantidad)
                                       $msg="Ya existe un usuario con ese Login";
                                       else{
                                         $sql=" insert into clientes (id_cliente,apellido_nombre,direccion,localidad,mail,telefono,login,password,id_distrito,codigo_postal)
                                                  values
                                                  ($id_cliente,'$apellido_nombre','$direccion','$localidad','$mail','$telefono','$login','$password',$id_distrito,'$codigo_postal')";
                                          sql($sql,"Error al insertar el cliente: $sql") or fin_pagina();
                                          $msg="Se ha realizado la operación exitosamente";
                                          $msg.="<br>Se generaron las claves con éxito!";
                                          $return=generar_claves($id_cliente);
                                          
                                          $return=eliminar("$id_cliente"."_claves*");
                                          
                                           $link=encode_link("listado_clientes.php",array("msg"=>$msg));
                                           header("location: $link");                           
   
                                       }
                           } //DEL ELSE
                           
    
    } //del if de aceptar


if ($id_cliente){
      $sql="select * from clientes  where id_cliente=$id_cliente";
      $res=sql($sql) or fin_pagina($sql);
      $apellido_nombre=$res->fields["apellido_nombre"];
      $direccion=$res->fields["direccion"];
      $telefono=$res->fields["telefono"];
      $localidad=$res->fields["localidad"];
      $mail=$res->fields["mail"];
      $id_distrito=$res->fields["id_distrito"];
      $codigo_postal=$res->fields["codigo_postal"];
      $login=$res->fields["login"];

    }



echo $html_header;
if ($msg) Aviso($msg);
?>
<script>
  function control_datos(){
    if (document.form1.nombre_apellido.value=="") {
       alert("Error:Debe Ingresar Apellido y Nombre");
       return false;
    }
    if (document.form1.distrito.options[document.form1.distrito.selectedIndex].value==-1)
       {
       alert("Error:Debe Seleccionar un Distrito");
       return false;
       }

    if (document.form1.login.value=="")
       {
       alert("Error:Debe Ingresar un Login");
       return false;
       }
    if (document.form1.password_1.value=="" || document.form1.password_2.value=="")
       {
       alert("Error:Debe Ingresar un Password");
       return false;
       }

     if (document.form1.password_1.value!=document.form1.password_2.value){
        alert("Error: No coinciden los passwords!");
        return false;
     }
  return true;
  }
</script>
 <form name=form1 method="post" >
 <input type=hidden name="id_cliente" value="<?=$id_cliente?>">
 <input type=hidden name="modificar" value="<?=$modificar?>">
    <table width=95% align=center class=bordes>
      <tr id=mo>
         <td align=center>Datos del cliente</td>
      </tr>
      <tr>
        <td width=100%>
          <table width=100% align=center>
             <tr>
                <td width=25%><b>Nombre y Apellido:</b></td>
                <td>
                <input type=text name=nombre_apellido value="<?=$apellido_nombre?>" size=60>
                </td>
             </tr>
             <tr>
                <td><b>Dirección:</b></td>
                <td>
                <input type=text name=direccion value="<?=$direccion?>" size=60>
                </td>
             </tr>
             <tr>
                <td><b>Teléfono:</b></td>
                <td>
                <input type=text name=telefono value="<?=$telefono?>" size=25>
                </td>
             </tr>
             <tr>
                <td><b>E-Mail:</b></td>
                <td>
                <input type=text name=mail value="<?=$mail?>" size=25>
                </td>
             </tr>

             <tr>
                <td><b>Provincia:</b></td>
                <td>
                <select name=distrito>
                  <option value=-1>Seleccione un Distrito</option>
                  <?
                  $sql="select * from distrito order by nombre ASC";
                  $distritos=sql($sql) or fin_pagina();
                  for ($i=0;$i<$distritos->recordcount();$i++){
                      $value=$distritos->fields["id_distrito"];
                      $nombre_distrito=$distritos->fields["nombre"];
                      if ($id_distrito==$value) $selected="selected";
                                          else   $selected="";
                  ?>
                  <option value="<?=$value?>" <?=$selected?>> <?=$nombre_distrito?> </option>
                  <?
                  $distritos->movenext();
                  }
                  ?>
                </select>
                </td>
             </tr>
             <tr>
                <td><b>Codigo Postal:</b></td>
                <td>
                <input type=text name=codigo_postal value="<?=$codigo_postal?>" size=60>
                </td>
             </tr>
             <tr>
                <td><b>Localidad:</b></td>
                <td>
                <input type=text name=localidad value="<?=$localidad?>" size=60>
                </td>
             </tr>
             <tr>
                <td><b>Login:<b></td>
                <td>
                <input type=text name=login value="<?=$login?>" size=30>
                </td>
             </tr>
<? if (!$modificar) {?>
             <tr>
                <td><b>Password:</b></td>
                <td>
                <input type=password name=password_1 value="" size=15>
                </td>
             </tr>
             <tr>
                <td><b>Confirmar Password:</b></td>
                <td>
                <input type=password name=password_2 value="" size=15>
                </td>
             </tr>
                  <?
                  }
                  $onclick=" onclick='return control_datos()'";
                  ?>

          </table>
        </td>
      </tr>
      <tr id=ma>
        <td align=center>
        <?
         if ($_SESSION["user"]["id"]==$id_cliente && $modificar)
            $disabled="";
            elseif (!$modificar)
              $disabled="";
              else
              $disabled=" disabled"
        ?>
             <input type=submit name='aceptar' value='Aceptar' <?=$onclick?> <?=$disabled?>>
             &nbsp;&nbsp;
             <input type=reset name='borrar' value='Limpiar'>
             &nbsp;&nbsp;
             <input type=button name=volver value="Volver Listado" onclick="document.location='listado_clientes.php'"> 
             
      </td>
      </tr>
    </table>
 </form>
</body>
</html>