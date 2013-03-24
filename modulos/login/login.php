<?
/*
$Author: fernando $
$Revision: 1.0$
$Date: 2005/5/13 $

*/

require_once ("../../config.php");
$user=array();

if ($_POST["ingresar"]){
    $login=$_POST["login"];
    $password=$_POST["password"];
    $sql="select * from clientes where login='$login' and password='$password'";
    $res=sql($sql) or fin_pagina();

    if ($res->recordcount()==1)

            {
            // if (session_register($user)) error("Se produjo un error al registrar la sesión");
            $user["login"]=$res->fields["login"];
            $user["name"]=$res->fields["apellido_nombre"];
            $user["id"]=$res->fields["id_cliente"];
            registrar_sesion($user);
            ?>
            <script>
            window.parent.frames[0].form1.usuario.value='<?=$res->fields["apellido_nombre"];?>';
            window.parent.frames[0].form1.submit();
            window.parent.frames[1].form1.submit();
            window.parent.frames[2].document.location="../mensajes/listado_mensajes.php";
            </script>
            <?
            }
         else
         {
             Aviso("Usuario o Password Incorrectos!!!!!");
         }
    }
echo $html_header;
?>
<form name=form1 method=post>
  <br>
  <br>
   <table width=100% align=center class=bordes>

     <tr bgcolor=<?=$cabecera_fondo?>>
       <td align=center><font color=<?=$letra_fondo?> size=3><b>Inicie Sesión </b></font></td>
     </tr>
     <tr>
       <td align=center>
       <table width=40% bgcolor=<?=$bgcolor2?>>
         <tr>
            <td><b>Login</b></td>
            <td align=center><input type=text name=login value="<?=$_SESSION["user"]["login"]?>" size=25></td>
         </tr>
         <tr>
            <td><b>Password<b></td>
            <td align=center><input type=password name=password value="" size=25></td>
         </tr>
       </table>
     <tr>
     <tr>
        <td align=center>
          <input type=submit name=ingresar value="Ingresar >>>>">
        </td>
     </tr>
   </table>
</form>